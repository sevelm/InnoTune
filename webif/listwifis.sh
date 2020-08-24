#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               listwifis.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   18.07.2018                                                    ##
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
## This script scans for nearby wifis through the wlan0 interface and prints  ##
## them out.                                                                  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# unblock wifi if the os is of type rasbian
if [[ $(cat /etc/os-release | grep Raspbian | wc -l) ]]; then
    rfkill unblock wifi
    ifconfig wlan0 up
fi
# scan for wifi
iwlist wlan0 scan | grep ESSID | cut -d '"' -f2 | sort -u | tr '\n' ';'
