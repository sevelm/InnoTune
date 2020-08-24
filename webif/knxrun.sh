#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                knxrun.sh                                   ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   20.02.2019                                                    ##
## Edited   :   29.07.2020                                                    ##
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
## This script starts or stops all related knx processes.                     ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 state (1 = start, 0 = stop)                                             ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

var="$1"
# if state param doesn't exist, invert current state and use this as input
if [ "$#" -eq 0 ]; then
    run=$(cat /opt/innotune/settings/knxrun.txt)
    if [[ "$run" -eq 1 ]]; then
        var="0"
    else
        var="1"
    fi
fi

if [[ "$var" -eq 1 ]]; then
    # stop and start all knx related processes
    echo "1" > /opt/innotune/settings/knxrun.txt
    killall knxtool
    killall knxlistener.sh
    killall knxcallback.sh
    systemctl restart knxd

    knxtool groupsocketlisten local: | /var/www/knxlistener.sh 2>&1 /dev/null &
    if [ "$#" -gt 1 ]; then
        sleep 15
    fi
    printf "listen\n" | nc -q 87000 localhost 9090 | /var/www/knxcallback.sh 2>&1 /dev/null &
else
    # stop all knx related processes
    echo "0" > /opt/innotune/settings/knxrun.txt
    killall knxtool
    killall knxlistener.sh
    killall knxcallback.sh
    systemctl stop knxd
fi
