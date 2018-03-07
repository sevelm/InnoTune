package Plugins::ExPL::Plugin;

# Extended xPL Support Plugin for SqueezeCenter
#
# b.a.b-technologie gmbh (http://www.bab-tec.de), January 2009
#
# This code is derived from code with the following copyright message:
#
# SqueezeCenter Copyright 2001-2007 Logitech.
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License,
# version 2.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# (see GIT for version information)
#

use strict;
use base qw(Slim::Plugin::Base);

use IO::Socket;
use Scalar::Util qw(blessed);

use Slim::Schema;
use Slim::Music::Info;
use Slim::Utils::Log;
use Slim::Utils::Misc;
use Slim::Utils::Network;
use Slim::Utils::Prefs;

#use Plugins::ExPL::Settings;

my $xpl_source = "slimdev-slimserv";
my $SERVER_NAME = "squeezecenter";

my $server_version = Slim::Control::Request::executeRequest(undef, ["version", "?"]);
   $server_version = $server_version->getResult("_version");

my $localip;
my $xpl_interval;
my $xpl_ir;
my $xpl_socket;
my $xpl_port;
my $volsave;
my $req_count;

my $log = Slim::Utils::Log->addLogCategory({
    'category'     => 'plugin.expl',
    'defaultLevel' => 'ERROR',
    'description'  => getDisplayName(),
});

my $prefs = preferences('plugin.expl');

my @playlists;
my %lastPlaylist = ();

################################################################################
# PLUGIN CODE
################################################################################

# plugin: initialize xPL support
sub initPlugin {
    my $class = shift;

    $log->debug("Initializing");

    $class->SUPER::initPlugin();

    $log->debug("server version: $server_version");

    if(Slim::Utils::Versions::compareVersions(undef, "7.4", "7.3") >= 0) {
        $log->debug("version is at least 7.3");
    } else {
        $log->debug("version is older than 7.3");
    }
#   Plugins::ExPL::Settings->new;

#   my $computername = Slim::Utils::Network::hostName();
#   $localip = inet_ntoa((gethostbyname($computername))[4]);

    $localip = Slim::Utils::Network::hostAddr;

    $volsave = -1;
    $req_count = -1;
    
    $xpl_interval = 2; #$prefs->get('interval');

    $xpl_port = 50000;

    # Try and bind to a free port
    while (!$xpl_socket && $xpl_port < 50200) {

        $xpl_socket = IO::Socket::INET->new(
            Proto     => 'udp',
            LocalPort => $xpl_port,
            LocalAddr => $main::localClientNetAddr
        );

        if (!$xpl_socket) {
            $xpl_port = $xpl_port + 1;
        } else {
            $log->debug("Bound to port: $xpl_port");
        }
    }

    defined(Slim::Utils::Network::blocking($xpl_socket,0)) || die "Cannot set port nonblocking";
    die "Could not create socket: $!\n" unless $xpl_socket;
    Slim::Networking::Select::addRead($xpl_socket, \&readxpl);
    sendxplhbeat();

    Slim::Control::Request::subscribe(\&xplExecuteCallback);

    $log->debug("Finished initializing");

    getPlaylistsByScheme();
}

sub shutdownPlugin {
    Slim::Control::Request::unsubscribe(\&xplExecuteCallback);
}

# plugin: name of our plugin
sub getDisplayName {
    return 'PLUGIN_EXPL';
}

sub enabled {
    return ($::VERSION ge '7.0');
}

# This routine ensures an xPL instance is valid
# by removing any invalid characters and trimming to
# a maximum of 16 characters.
sub validInstance {

    my $instance = $_[0];
    $instance =~ s/(-|\.|!|;| )//g;
    if (length($instance) > 16) {
        $instance = substr($instance,0,16);
    }
    return $instance;
}

sub getClientByName {
    my $clientName = shift;
    my $instance;

    foreach my $client (Slim::Player::Client->clients) {
        $instance = lc validInstance($client->name);
        if ($clientName eq "$xpl_source\.$instance") {
            return $client;
        }
    }
    return undef;
}

# Processes an incoming xPL message
sub readxpl {
    my $sock = shift;

    my $schema;
    my $msgtarget;
    my $msg = '';
    my $client;

    $log->debug("Listening");
    recv($sock,$msg,1500,0);

    $log->debug("Received message");

    # If message is not for us, ignore it
    $msgtarget = lc(gethdrparam($msg,"target"));

    if ($msgtarget ne "*") {
        $client = getClientByName($msgtarget);
        return unless defined $client;
        $log->debug("reading client-id: ".$client->name);
    }

    # We're only interested in command messages
    if (getmsgtype($msg) eq "xpl-cmnd") {
        $schema = getmsgschema($msg);
        if ($schema eq "audio.ext") {
            handleAudioExtended($msg, $client, $msgtarget);
        } elsif($schema eq "audio.request") {
            handleAudioRequest($msg, $client, $msgtarget);
        } elsif($schema eq "audio.slimserv") {
            handleAudioSlimserv($msg, $client, $msgtarget);
        }
    }
}

#this routine handles audio.slimserv messages
sub handleAudioSlimserv {
    my $msg = shift;
    my $client = shift;
    my $msgtarget = shift;

    if (!defined($client)) {
        foreach my $client (Slim::Player::Client->clients) {
            handleAudioSlimserv($msg, $client, $msgtarget);
        }
        return;
    }
    my $clientName = validInstance($client->name);

    my $xplcmd = lc getparam($msg, "extended");

    my @params = split " ", $xplcmd;

    if(getparam($msg, "extended") =~ /^playlist play .*/) {
        my $playlist = getPlaylistByName($params[2]);
        $log->debug("new playlist via audio.slimserv ".$playlist->title);
        $lastPlaylist{$client} = $playlist;
    }
}

# this routine handles audio.request messages
sub handleAudioRequest {
    my $msg = shift;
    my $client = shift;
    my $msgtarget = shift;

    if (!defined($client)) {
        foreach my $client (Slim::Player::Client->clients) {
            handleAudioRequest($msg, $client, $msgtarget);
        }
        return;
    }
    my $clientName = validInstance($client->name);

    my $xplcmd = lc getparam($msg,"command");

    $log->debug("audio.request ".$xplcmd);

    my @params = split " ", $xplcmd;
    if($xplcmd eq "status") {
        sendXplHBeatMsg($client);
    } elsif($xplcmd eq "volume") {
        my $rs = Slim::Control::Request::executeRequest($client, ["mixer", "volume", "?"]);
        my $volume = $rs->getResult("_volume");
        $log->debug("new volume for ".$clientName.": $volume");

        sendxplmsg("xpl-trig", "*","audio.ext", "type=volume\nvalue=$volume", $clientName);
    } elsif($xplcmd =~ "playlist (current|next|prev)") {
        my $rs = Slim::Control::Request::executeRequest($client, ["playlist", "name", "?"]);
        my $plname;
        my $type;
        if($params[1] eq "current") {
            $plname = $rs->getResult("_name");
            $type = "current";
        } elsif($params[1] eq "next") {
            $plname = getNextPlaylist($rs->getResult("_name"))->name;
            $type = "next";
        } elsif($params[1] eq "prev") {
            $plname = getPrevPlaylist($rs->getResult("_name"))->name;
            $type = "prev";
        }
        my $plindex = getPlaylistIndex($plname);
        $log->debug("returning $type playlist $clientName: ".$plname);
        
        sendxplmsg("xpl-stat", "*","audio.ext", "type=playlist\nwhat=$type\nindex=$plindex\nname=$plname", $clientName);
    } elsif($xplcmd =~ /^playlists \d* \d*/) {
        my $from = $params[1];
        my $count = $params[2];
        my $current;
        for($current = $from; $current < $from + $count; $current++) {
	    if (defined ($playlists[$current])) {
		my $plname = $playlists[$current]->name;
		$log->debug("returning info playlist: ".$plname);
        
		sendxplmsg("xpl-stat", "*","audio.ext", "type=playlist\nwhat=info\nindex=$current\nname=$plname", $clientName);
	    }
        }
    } elsif($xplcmd eq "playlists") {
        my $current;
        for($current = 0; $current < scalar(@playlists); $current++) {
	    if (defined ($playlists[$current])) {
		my $plname = $playlists[$current]->name;
		$log->debug("returning info playlist: ".$plname);
        
		sendxplmsg("xpl-stat", "*","audio.ext", "type=playlist\nwhat=info\nindex=$current\nname=$plname", $clientName);
	    }
        }
    }
}

# This routine handles audio.ext messages
sub handleAudioExtended {
    my $msg = shift;
    my $client = shift;
    my $msgtarget = shift;

    $log->debug("audio.cmd");

    # If client is undefined, send to all clients
    if (!defined($client)) {
        foreach my $client (Slim::Player::Client->clients) {
            handleAudioExtended($msg, $client, $msgtarget);
        }
        return;
    } else {
        # Handle standard audio.ext commands
        my $xplcmd = lc getparam($msg,"command");
        my @params = split " ", $xplcmd;
        # playlist
        if ($xplcmd =~ /^playlist (next|prev)/) {
            my $currentpl = $client->currentPlaylist();
            # select at least one playlist if $client->currentPlaylist() is not set.
            # if no playlists exist return from that method
            if(!defined($currentpl)) {
                if(!defined($lastPlaylist{$client})) {
                    if(scalar(@playlists) == 0) {
                        return;
                    } elsif (!defined ($playlists[0])) {
			return;
		    } else {
                        $currentpl = $playlists[0];
                    }
                } else {
                    $currentpl = $lastPlaylist{$client};
                }
            }
            my $newpl;
            if($params[1] eq "next") {
                $log->debug("getting next playlist ".$currentpl->title);
                $newpl = getNextPlaylist($currentpl->title);
            } elsif($params[1] eq "prev") {
                $log->debug("getting prev playlist ".$currentpl->title);
                $newpl = getPrevPlaylist($currentpl->title);
            }
            $log->debug("setting new playlist for ".$client->name.": ".$newpl->title);
            Slim::Control::Request::executeRequest($client, ["playlist", "play", $newpl]);
            $client->currentPlaylist($newpl);
            $client->currentPlaylistModified(0);
        } elsif ($xplcmd =~ /^playlist play .*/) {
            my $pl = getPlaylistByName($params[2]);

            if(!defined($pl)) { return; }

            $log->debug("setting new playlist for ".$client->name.": ".$pl->title);

            if(Slim::Utils::Versions::compareVersions(undef, $server_version, "7.3") >= 0) {
                Slim::Control::Request::executeRequest($client, ["playlist", "play", $pl]);
            } else {
                Slim::Control::Request::executeRequest($client, ["playlist", "play", $pl->name]);
            }
            $client->currentPlaylist($pl);
            $client->currentPlaylistModified(0);
        } elsif ($xplcmd =~ /^playlist playindex \d*/) {
            my $plIndex = $params[2];
            my $pl = $playlists[$plIndex];

            if(!defined($pl)) { return; }

            $log->debug("setting new index ".$plIndex." -> ".$client->name.": ".$pl->title);

            if(Slim::Utils::Versions::compareVersions(undef, $server_version, "7.3") >= 0) {
                Slim::Control::Request::executeRequest($client, ["playlist", "play", $pl]);
            } else {
                Slim::Control::Request::executeRequest($client, ["playlist", "play", $pl->name]);
            }
            $client->currentPlaylist($pl);
            $client->currentPlaylistModified(0);
        }
    }
}

# Sends either a hbeat.app or audio.basic status message from a particular client
sub sendXplHBeatMsg {
    my $msg;
    my $client = $_[0];
    my $clientName = validInstance($client->name);

    $log->debug("sendXplHBeatMsg for ".$clientName." ".$client);

    my $playmode;

    my $album = " ";
    my $artist = " ";
    my $trackname = " ";
    my $power = "1";


    if (defined($client->revision())) {
        $power = $client->power();
    }

    if(Slim::Utils::Versions::compareVersions(undef, $server_version, "7.3") >= 0) {
        if($client->isPlaying()) { $playmode = "playing" };
        if($client->isStopped()) { $playmode = "stopped" };
        if($client->isPaused()) { $playmode = "paused" };
    } else {
        $playmode = $client->playmode;
        if($playmode eq 'playing' || $playmode eq 'playout-play') { $playmode = "playing" };
        if($playmode eq 'stop') { $playmode = "stopped" };
        if($playmode eq 'pause') { $playmode = "paused" };
    }

    if ($playmode eq "playing") {
        my $track = Slim::Schema->rs('Track')->objectForUrl({
            'url'      => Slim::Player::Playlist::song($client),
            'create'   => 1,
            'readTags' => 1,
        });

        if (blessed($track)) {
            my $albumObj = $track->album;
            if (blessed($albumObj) && $albumObj->can('title')) {
                $album = $albumObj->title;
            }
            my $artistObj = $track->artist;
            if (blessed($artistObj) && $artistObj->can('name')) {
                $artist = $artistObj->name;
            }
        }

        $trackname = Slim::Music::Info::getCurrentTitle($client, Slim::Player::Playlist::url($client));

        # if the song name has the track number at the beginning, remove it
        $trackname =~ s/^[0-9]*\.//g;
        $trackname =~ s/^ //g;

    }

    $msg = "status=$playmode\nARTIST=$artist\nALBUM=$album\nTRACK=$trackname\nPOWER=$power";

    sendxplmsg("xpl-stat", "*","audio.basic", $msg, $clientName);
}

sub sendExPLHBeatMsg {
    my $client = shift;
    my $clientName = validInstance($client->name);
    my $msg = "interval=$xpl_interval\nport=$xpl_port\nremote-ip=$localip\nschema=audio.ext\nversion=1";

    sendxplmsg("xpl-stat", "*","hbeat.app", $msg, $clientName);
}

# Sends an xPL heartbeat from all clients that are currently connected
sub sendxplhbeat {

    foreach my $client (Slim::Player::Client->clients) {
        sendExPLHBeatMsg($client);
    }

    Slim::Utils::Timers::setTimer("", Time::HiRes::time() + ($xpl_interval * 60), \&sendxplhbeat);
}

# Generic routine for sending an xPL message.
sub sendxplmsg {
    my $msg = "$_[0]\n{\nhop=1\nsource=$xpl_source.$_[4]\ntarget=$_[1]\n}\n$_[2]\n{\n$_[3]\n}\n";

    my $ipaddr   = inet_aton('255.255.255.255');
    my $portaddr = sockaddr_in(3865, $ipaddr);

    my $sockUDP = IO::Socket::INET->new(
        'PeerPort' => 3865,
        'Proto'    => 'udp',
    );

    $sockUDP->autoflush(1);
    $sockUDP->sockopt(SO_BROADCAST,1);

    eval { $sockUDP->send( Slim::Utils::Unicode::utf8encode($msg), 0, $portaddr ) };

    if ($@) {
        logError("Caught exception when trying to ->send: [$@]");
    }

    $log->debug("Sending message");

    close $sockUDP;
}

# Retrieves a parameter from the body of an xPL message
sub getparam {
    my $buff = $_[0];
        $buff =~ s/$_[1]/$_[1]/gi;
    $buff = substr($buff,index($buff,"}"),length($buff)-index($buff,"}"));
    $buff = substr($buff,index($buff,"{")+2,length($buff)-index($buff,"{")-2);
    $buff = substr($buff,0,index($buff,"}")-1);
    my %params = map { split /=/, $_, 2 } split /\n/, $buff ;
    return $params{$_[1]};
}

# Retrieves a parameter from the header of an xPL message
sub gethdrparam {
    my $buff = $_[0];
        $buff =~ s/$_[1]/$_[1]/gi;
    $buff = substr($buff,index($buff,"{")+2,length($buff)-index($buff,"{")-2);
    $buff = substr($buff,0,index($buff,"}")-1);
    my %params = map { split /=/, $_, 2 } split /\n/, $buff ;
    return $params{$_[1]};
}

# Returns the type of an xPL message, e.g. xpl-stat, xpl-trig or xpl-cmnd
sub getmsgtype {
    return lc substr($_[0],0,8);
}

# This routine accepts an xPL message and returns the message schema, in lowercase characters
sub getmsgschema {
    my $buff = $_[0];
    $buff = substr($buff,index($buff,"}")+2,length($buff)-index($buff,"}")-2);
    $buff = substr($buff,0,index($buff,"\n"));
    return lc $buff;
}

# This routine is called by Slim::Command::execute() for each command it processes.
sub xplExecuteCallback {
    my $request = shift;

    my $client = $request->client();

    $log->debug("execute Callback ".$request->getRequestString);

    # callback is all client based below, so avoid a crash and shortcut all of it when no client supplied
    if (!defined $client) {
        if ($request->isCommand([['rescan'], ['done']])) {
            $log->debug("rescan done command");
            getPlaylistsByScheme();

            sendxplmsg("xpl-trig", "*","audio.ext", "type=playlists\nwhat=changed", $SERVER_NAME);
        } elsif ($request->isCommand([['playlists'], ['delete']])) {
            $log->debug("playlists delete command");
            getPlaylistsByScheme();

            sendxplmsg("xpl-trig", "*","audio.ext", "type=playlists\nwhat=changed", $SERVER_NAME);
        } elsif ($request->isCommand([['playlists'], ['rename']])) {
            $log->debug("playlists rename command");
            getPlaylistsByScheme();

            sendxplmsg("xpl-trig", "*","audio.ext", "type=playlists\nwhat=changed", $SERVER_NAME);
        } elsif ($request->isCommand([['playlists'], ['new']])) {
            $log->debug("playlists new command");
            getPlaylistsByScheme();

            sendxplmsg("xpl-trig", "*","audio.ext", "type=playlists\nwhat=changed", $SERVER_NAME);
        } else {
            logWarning("Called without a client: " . $request->getRequestString);
        }
        return;
    }

    my $clientname = validInstance($client->name);

    if ($request->isCommand([['play']]) || $request->isCommand([['pause']])) {
        sendXplHBeatMsg($client);
    } elsif ($request->isCommand([['playlist'], ['open']])) {
        $log->debug($request->getRequestString);
        my $rs = Slim::Control::Request::executeRequest($client, ["playlist", "name", "?"]);

        my $plname = $rs->getResult("_name");

        $log->debug("noticed new playlist is triggered: ".$plname);
        if(defined($plname)) {
            my $plindex = getPlaylistIndex($plname);
            $log->debug("new playlist for $clientname: ".$plname);

            $lastPlaylist{$client} = getPlaylistByName($plname);
            sendxplmsg("xpl-trig", "*","audio.ext", "type=playlist\nwhat=current\nindex=$plindex\nname=$plname", $clientname);
        }
    } elsif ($request->isCommand([['mixer'], ['volume']])) {
        $req_count = $req_count + 1;
        #nur jeden zweiten Request behandeln
        if(($req_count % 2) == 0) {
        
            my $rs = Slim::Control::Request::executeRequest($client, ["mixer", "volume", "?"]);
            my $volume = $rs->getResult("_volume");
            $log->debug("new volume for $clientname: $volume");

            if($volsave == -1) {
                $volsave = $volume;
                sendxplmsg("xpl-trig", "*","audio.ext", "type=volume\nvalue=$volume\nclient=$clientname", $clientname);
            } elsif($volsave == $volume) {
                sendxplmsg("xpl-trig", "*","audio.ext", "type=volume\nvalue=$volume\nclient=$clientname", $clientname);
            } else {
                while($volsave != $volume) {
                    $volsave = $volume;
                    $rs = Slim::Control::Request::executeRequest($client, ["mixer", "volume", "?"]);
                    $volume = $rs->getResult("_volume");
                }
                $volsave = $volume;
                sendxplmsg("xpl-trig", "*","audio.ext", "type=volume\nvalue=$volume\nclient=$clientname", $clientname);
            }
        }
    };
}

sub getPlaylistByName() {
    my $name = shift;
    for (my $i = 0; $i < @playlists; $i++){
	if (defined ($playlists[$i])) {
	    if($playlists[$i]->title eq $name) {
		return $playlists[$i];
	    }
	}
    }
}

sub getNextPlaylist {
    my $name = shift;

    my $index = getPlaylistIndex($name) + 1;
    if($index >= scalar(@playlists)) {
        $index = 0;
    }

    return $playlists[$index];
}

sub getPrevPlaylist {
    my $name = shift;

    my $index = getPlaylistIndex($name) - 1;
    if($index < 0) {
        $index = scalar(@playlists) - 1;
    }

    return $playlists[$index];
}

sub getPlaylistIndex {
    my $name = shift;

    for (my $i = 0; $i < @playlists; $i++){
	if (defined ($playlists[$i])) {
	    if($playlists[$i]->title eq $name) {
		return $i;
	    }
	}
    }
}

sub getPlaylistsByScheme {
    $log->debug("requesting playlists");

    my $search;
    my $rs = Slim::Schema->rs('Playlist')->getPlaylists('all', $search);

    my @newPl;

    for my $eachitem ($rs->slice(0, $rs->count)) {
        $newPl[scalar(@newPl)] = $eachitem;
    }

    @playlists = @newPl;
    for (my $i = 0; $i < @playlists; $i++){
	if (defined ($playlists[$i])) {
	    $log->debug($i." -> ".$playlists[$i]->title."\n");
	}
    }

    $log->debug("requesting playlists finished");
}

#       $log->debug("next playlist: ".getNextPlaylist($plname)->title);
#       $log->debug("prev playlist: ".getPrevPlaylist($plname)->title);

#sub getPlaylistsByCLI() {
#   my $client = Slim::Player::Client::clientRandom();
#   my @rs = Slim::Control::Request::executeLegacy($client, ["playlists"]);
#   my $rs = Slim::Control::Request::executeRequest($client, ["playlists", 4, 3]);
#   $log->debug($rs->getResults());
#   my $rs = Slim::Control::Stdio::executeCmd("$client playlists 1 5");

#   for my $eachitem ($rs->slice(0, $rs->count)) {
#       my $id = $eachitem->id();
#       $id += 0;
#
#       $log->debug($eachitem->title);
#   }
#   $log->debug($rs[0]);
#}

#       $log->debug("new volume for $clientname: ".preferences('server')->client($client)->get("volume"));
#
#       my $rs = Slim::Music::Info::playlistForClient($client)->tracks;
#       for my $eachitem ($rs->slice(0, $rs->count)) {
#           $log->debug($eachitem->title);
#       }

1;
