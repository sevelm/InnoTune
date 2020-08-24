#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             readcputemp.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   17.04.2018                                                    ##
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
## This script reads the cpu temperature from thermal zone 0 or zone 1 if it  ##
## isn't available.                                                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/checkcputemp.sh                                                   ##
##                                                                            ##
################################################################################
################################################################################

if [[ -f /sys/class/thermal/thermal_zone0/temp ]]; then
    t0=$(cat /sys/class/thermal/thermal_zone0/temp)
    if [[ $(uname -r | grep rockchip | wc -l) -eq 1 ]]; then
        t1=$(cat /sys/class/thermal/thermal_zone1/temp)
        if [[ "${t0/Invalid argument}" = "$t0" ]]; then
            echo "$t1"
        else
            echo "$t0"
        fi
    else
        echo "$t0"
    fi
else
    echo "-1"
fi
