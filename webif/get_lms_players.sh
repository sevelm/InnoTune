#!/bin/bash

## get all known LMS-players

##vars
port=9090
server=localhost

##remove and create file
sudo rm /opt/innotune/settings/all_lms_players.txt
sudo touch /opt/innotune/settings/all_lms_players.txt

# get number of known players
players=$(printf "player count ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

## check all known players
for((i=0; i<$players; i++))
do
    playerID=$(printf "player id $i ?\nexit\n" | nc $server $port | cut -d ' ' -f 4 | sed 's/%/%%/g')
    playermodel=$(printf "player model $i ?\nexit\n" | nc $server $port |cut -d ' ' -f 3)
    playername=$(printf "$playerID name ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
    echo "$i;$playerID;$playermodel;$playername" | sed -e 's/%%3A/:/g' >>/opt/innotune/settings/all_lms_players.txt
done

sudo cp /var/lib/squeezeboxserver/prefs/upnpbridge.xml /opt/innotune/settings/upnpbridge.xml
