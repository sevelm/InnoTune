#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                           set_eq_settings.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   23.05.2018                                                    ##
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
## This script sets the eq frequency of a pcm plug to the requested value.    ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 pcm plug                                                                ##
## $2 eq frequency                                                            ##
## $3 value                                                                   ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

case "$2" in
    "low")
        amixer -D equal$1 set "00. 31 Hz" "$3"%
        amixer -D equal$1 set "01. 63 Hz" "$3"%
        amixer -D equal$1 set "02. 125 Hz" "$3"%;;
    "mid")
        amixer -D equal$1 set "03. 250 Hz" "$3"%
        amixer -D equal$1 set "04. 500 Hz" "$3"%
        amixer -D equal$1 set "05. 1 kHz" "$3"%
        amixer -D equal$1 set "06. 2 kHz" "$3"%;;
    "high")
        amixer -D equal$1 set "07. 4 kHz" "$3"%
        amixer -D equal$1 set "08. 8 kHz" "$3"%
        amixer -D equal$1 set "09. 16 kHz" "$3"%;;
esac

exit 0
