#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              net_backup.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   17.03.2020                                                    ##
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
## This script checks if the board can ping 8.8.8.8 (google server) with a    ##
## timeout of 30 seconds.                                                     ##
## 0 = success                                                                ##
## 1 = failure                                                                ##
##                                                                            ##
##                                 References                                 ##
##                                                                            ##
################################################################################
################################################################################

ping -q -c4 -w30 8.8.8.8 &>/dev/null ; echo $?
