#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            get_lms_players.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   27.02.2018                                                    ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script iterates through all known players in the lms and saves them   ##
## to the innotune settings. (for exporting)                                  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# vars
port=9090
server=localhost

# remove and create file
sudo rm /opt/innotune/settings/all_lms_players.txt
sudo touch /opt/innotune/settings/all_lms_players.txt

# get number of known players
players=$(printf "player count ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

# check all known players
for((i=0; i<$players; i++))
do
    playerID=$(printf "player id $i ?\nexit\n" | nc $server $port | cut -d ' ' -f 4 | sed 's/%/%%/g')
    playermodel=$(printf "player model $i ?\nexit\n" | nc $server $port |cut -d ' ' -f 3)
    playername=$(printf "$playerID name ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
    echo "$i;$playerID;$playermodel;$playername" | sed -e 's/%%3A/:/g' >>/opt/innotune/settings/all_lms_players.txt
done

sudo cp /var/lib/squeezeboxserver/prefs/upnpbridge.xml /opt/innotune/settings/upnpbridge.xml
