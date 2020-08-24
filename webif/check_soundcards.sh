#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            check_soundcards.sh                             ##
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
## This script iterates through the sound card mapping file and checks it     ##
## against the current list of recognized cards of the system.                ##
## Logs any missing card and the overall fail-count to a file.                ##
##                                                                            ##
## A cronjob excecutes this script every 15 minutes.                          ##
## Cronjob Entry:                                                             ##
## */15 * * * * /var/www/check_soundcards.sh                                  ##
##                                                                            ##
################################################################################
################################################################################

# init vars
i=$((0))
datetime=$(date '+%d-%m-%Y %H:%M:%S')

# checks if mapping file exists
if [ -f /opt/innotune/settings/mapping.txt ]; then
    # iterate through all line of the mapping file
    while IFS='' read -r line || [[ -n "$line" ]]; do
        # get card number and check if system still recognizes the card
        sndc=$(echo $line | cut -d ";" -f1)
        check=$(aplay -l | grep $sndc)
        # if the check failed print an error message to the log
        if [[ -z $check ]]; then
            i=$(($i+1))
            datetime=$(date '+%d-%m-%Y %H:%M:%S')
            echo "$datetime Soundkarte: $sndc nicht verbunden" >> /var/www/checkprocesses.log
        fi
    done < /opt/innotune/settings/mapping.txt
fi

# if i is greater than 0 it means that at least one card check failed and
# it will be appended to the log file
if [[ "$i" -gt "0" ]]; then
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    echo "$datetime Soundkartencheck mit $i Fehler(n) abgeschlossen" >> /var/www/checkprocesses.log
fi
