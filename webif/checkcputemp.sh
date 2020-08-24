#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              checkcputemp.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   17.04.2018                                                    ##
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
## This script reads the cpu temp from /var/www/readcputemp.sh and checks if  ##
## it exceeds 69.9 celcius to log the event.                                  ##
##                                                                            ##
## A cronjob excecutes this script every 15 minutes.                          ##
## Cronjob Entry:                                                             ##
## */15 * * * * /var/www/checkcputemp.sh                                      ##
##                                                                            ##
################################################################################
################################################################################

# get raw sytem cpu temp from othe script
tempraw=$(/var/www/readcputemp.sh)
if [[ "$tempraw" -ne "1" ]]; then
    # humanize values
    temp=$(($tempraw/1000))
    datetime=$(date '+%d-%m-%Y %H:%M:%S')

    # check cpu temperature and log if it exceeds 69.9 celcius
    if [[ "$tempraw" -gt "79999" ]]; then
        echo "$datetime Achtung! CPU-Temperatur beträgt $temp°C" >> /var/www/checkprocesses.log
    elif [[ "$tempraw" -gt "69999" ]]; then
        echo "$datetime Warnung! CPU-Temperatur beträgt $temp°C" >> /var/www/checkprocesses.log
    fi
fi
