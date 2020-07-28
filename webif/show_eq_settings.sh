#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                          show_eq_settings.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   23.05.2018                                                    ##
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
## This script prints the current vol percentages of the three eq frequencies ##
## in the format 'LOW;MID;HIGH'.                                              ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

LOW=$(amixer -D equal$1 get "00. 31 Hz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
MID=$(amixer -D equal$1 get "04. 500 Hz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
HIGH=$(amixer -D equal$1 get "09. 16 kHz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)

echo "$LOW;$MID;$HIGH"
