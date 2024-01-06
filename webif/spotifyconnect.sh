#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            spotifyconnect.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   04.09.2017                                                    ##
## Edited   :   27.07.2020                                                    ##
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

a=$2
if [ "$PLAYER_EVENT" = "start" ]; then
    a=1
elif [ "$PLAYER_EVENT" = "stop" ] || [ "$PLAYER_EVENT" = "pause" ] || [ "$PLAYER_EVENT" = "paused" ] || [ "$PLAYER_EVENT" = "stopped" || [ "$PLAYER_EVENT" = "session_disconnected" ]; then
    a=0
fi
echo $a > /opt/innotune/settings/status_shairplay/status_shairplay$3$1.txt
