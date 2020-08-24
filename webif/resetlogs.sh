#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              resetlogs.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   09.05.2018                                                    ##
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
## This script resets log files in the /var/www/InnoControl/log/old/          ##
## directory and the checkprocesses.log file.                                 ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

rm -R /var/www/InnoControl/log/old/*
cd /var/www/InnoControl/log
for i in *; do
    if [[ "$i" != "old" ]]; then
        rm $i
    fi
done
chmod -R 777 /var/www/InnoControl/log
rm /var/www/checkprocesses.log
touch /var/www/checkprocesses.log
chmod 777 /var/www/checkprocesses.log
