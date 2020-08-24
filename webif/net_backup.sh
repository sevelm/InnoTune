#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              net_backup.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   06.06.2019                                                    ##
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
## This script adds a second ethernet interface, used for backup purpose when ##
## a direct connection is needed and the ip address is unknown.               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

sleep 180
ifconfig "eth0:backup" inet "172.30.250.250" netmask 255.255.255.0 broadcast 172.30.250.250
