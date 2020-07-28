#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              set_gpios.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   14.05.2019                                                    ##
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
## This script reads the card mute states and mutes/unmutes the card.         ##
## data[0] = 0 (auto-mute), 1 (manual mute)                                   ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 card number                                                             ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

options=$(cat "/opt/innotune/settings/gpio/mute/state$1")
IFS=';' read -ra data <<< "$options"

case "$1" in
    01) pin="7";;
    02) pin="2";;
    03) pin="22";;
    04) pin="24";;
    05) pin="6";;
    06) pin="27";;
    07) pin="28";;
    08) pin="29";;
    *) echo "invalid parameter"
       exit 1;;
esac

if [[ "${data[0]}" -eq 0 ]]; then
    /var/www/src/mutecard "$pin" 2>&1 /dev/null &
    printf "$!" > "/opt/innotune/settings/gpio/mute/p$1"
else
    # kill only card specific process
    pid=$(cat "/opt/innotune/settings/gpio/mute/p$1")
    kill "$pid"
    gpio mode "$pin" OUT
    gpio write "$pin" "${data[1]}"
fi
