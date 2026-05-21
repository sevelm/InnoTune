#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            spotifyconnect.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   04.09.2017                                                    ##
## Edited   :   21.05.2026                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script saves the current playstate of a single spotify/airplay        ##
## instance.                                                                  ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 player number                                                           ##
## $2 mode (start/stop)                                                       ##
## $3 stereo/mono left/mono right                                             ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

a=""

echo "$(date '+%F %T') zone=$1 side=$3 arg=$2 PLAYER_EVENT=$PLAYER_EVENT" >> /tmp/spotifyconnect-events.log

if [ -z "$PLAYER_EVENT" ]; then
    a=$2
elif [ "$PLAYER_EVENT" = "started" ] || [ "$PLAYER_EVENT" = "start" ] || [ "$PLAYER_EVENT" = "playing" ]; then
    a=1
elif [ "$PLAYER_EVENT" = "stop" ] || [ "$PLAYER_EVENT" = "pause" ] || [ "$PLAYER_EVENT" = "paused" ] || [ "$PLAYER_EVENT" = "stopped" ] || [ "$PLAYER_EVENT" = "session_disconnected" ] || [ "$PLAYER_EVENT" = "session_cleared" ]; then
    a=0
else
    exit 0
fi

echo $a > /opt/innotune/settings/status_shairplay/status_shairplay$3$1.txt
