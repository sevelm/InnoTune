#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                log_card.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   28.03.2018                                                    ##
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
## This script logs if a certain usb audio device gets connected or           ##
## disconnected. This is executed by udev rules.                              ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 soundcard name                                                          ##
## $2 mode (0 = disconnected, 1 = connected)                                  ##
##                                                                            ##
##                                 References                                 ##
## /etc/udev/rules.d/90-usb-audio-log-remove.rules                            ##
## /etc/udev/rules.d/80-usb-audio-id.rules                                    ##
##                                                                            ##
################################################################################
################################################################################

datetime=$(date '+%d-%m-%Y %H:%M:%S')
param=$(($2))
if [[ "$param" -eq "1" ]]; then
    echo "$datetime Soundkarte ($1) wurde angesteckt" >> /var/www/checkprocesses.log
else
    echo "$datetime Soundkarte ($1) wurde entfernt!" >> /var/www/checkprocesses.log
fi
