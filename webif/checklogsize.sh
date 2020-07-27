#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              checklogsize.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   09.05.2018                                                    ##
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
## This script deletes any file in the log directory if the size of the file  ##
## exceeds 100 MB.                                                            ##
##                                                                            ##
## A cronjob excecutes this script every 5 minutes.                           ##
## Cronjob Entry:                                                             ##
## */5 * * * * /var/www/checklogsize.sh                                       ##
##                                                                            ##
################################################################################
################################################################################

cd /var/www/InnoControl/log
datetime=$(date '+%d-%m-%Y %H:%M:%S')
# iterate through files in directory
for i in *; do
    # check if file is not an old log
    if [[ "$i" != "old" ]]; then
        size=$(du -k $i | cut -f1)
        echo "size of $i is $size"
        # file size check
        if [[ "$size" -gt "100000" ]]; then
            # reset log file and log the event
            > $i
            size=$(($size/1000))
            echo "$datetime Logdatei $i gelöscht ($size MB)" >> /var/www/checkprocesses.log
            # kill the process spawning the overflowing log file
            pid=$(ps aux | grep -v grep | grep $i | awk '{print $2}')
            kill $pid
        fi
    fi
done
