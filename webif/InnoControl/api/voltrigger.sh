#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               voltrigger.sh                                ##
##                                                                            ##
## Directory:   /var/www/InnoControl/api/                                     ##
## Created  :   20.04.2018                                                    ##
## Edited   :   27.07.2020                                                    ##
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
## This script increases or decreases the volume of a lms player until it     ##
## reaches the max/min volume or the process is killed.                       ##
##                                                                            ##
##                                                                            ##
##                                Parameter                                   ##
## $1 mac address of lms player                                               ##
## $1 command (u = volume up, d = volume down)                                ##
##                                                                            ##
##                                References                                  ##
## /var/www/InnoControl/api/voltrigger.php                                    ##
##                                                                            ##
################################################################################
################################################################################

current=$(printf "$1 mixer volume ?\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
echo "$1 $2 $current"
i=$((1))
multiplier=$((1))
if [[ $2 = "u" ]]; then
    while [[ $current -lt 100 ]]; do
        new=$(($current+3*$multiplier))
        current=$(printf "$1 mixer volume $new\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
        sleep .33
        i=$(($i+1))
        # if [[ $(($i%4)) -eq 0 ]]; then
            # multiplier=$(($multiplier+1))
        # fi
    done
else
    while [[ $current -gt 0 ]]; do
        new=$(($current-3*$multiplier))
        current=$(printf "$1 mixer volume $new\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
        sleep .33
        i=$(($i+1))
        if [[ $(($i%4)) -eq 0 ]]; then
            multiplier=$(($multiplier+1))
        fi
    done
fi
