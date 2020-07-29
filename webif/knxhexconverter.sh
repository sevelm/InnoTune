#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                           knxhexconverter.sh                               ##
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
## This script converts the hex value input from the knx interface to a       ##
## decimal number.                                                            ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 hex value                                                               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/knxlistener.sh                                                    ##
##                                                                            ##
################################################################################
################################################################################

exp=$((0x$1 >> 11))
base=$((0x$1 & 0x07FF))
var=$(echo "(($base*0.01)*2^$exp)" | bc)
echo $var | awk '{print int($1+0.5)}'
