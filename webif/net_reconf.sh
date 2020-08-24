#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             net_reconf.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   07.06.2019                                                    ##
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
## This scripts restarts an interface if it has a avahi interface.            ##
##                                                                            ##
##                                 References                                 ##
##                                                                            ##
################################################################################
################################################################################

if [[ $(ifconfig | grep eth0:avahi | wc -l) -gt 0 ]]; then
    ifdown -v eth0
    ifup -v eth0
fi

if [[ $(ifconfig | grep wlan0:avahi | wc -l) -gt 0 ]]; then
    ifdown -v wlan0
    ifup -v wlan0
fi
