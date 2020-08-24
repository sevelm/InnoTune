#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            filesizechecker.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   06.02.2019                                                    ##
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
## This script deletes overflowing system and innotune logs and syncs the     ##
## filesystem afterwards.                                                     ##
##                                                                            ##
## A cronjob excecutes this script every hour at 30 minutes.                  ##
## Cronjob Entry:                                                             ##
## 30 */1 * * * /var/www/filesizechecker.sh                                   ##
##                                                                            ##
################################################################################
################################################################################

# delete overflowing innotune logs
cd /var/www/InnoControl/log
find -type f \( -name "*.tar.gz" \) -size +100M -delete
find -type f \( -name "*.log" \) -size +150M -delete
find -type f \( -name "spotify*" -o -name "airplay*" \) -size +10M -delete

# delete overflowing system logs and rotated files
cd /var/log
find -type f \( -name "*.gz" \) -size +5M -delete
find -type f \( -name "*.1" -o -name "*.2" \) -delete

# delete overflowing system hdd logs and rotated files
cd /var/log.hdd
find -type f \( -name "*.gz" \) -size +5M -delete
find -type f \( -name "*.1" -o -name "*.2" \) -delete

# sync filesystem
sync
