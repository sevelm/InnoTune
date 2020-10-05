#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                          checkImportantUpdates.sh                          ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   09.12.2019                                                    ##
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
## Checks the current local important update number against the number on the ##
## git repository. If repository number is greater then the update is         ##
## started.                                                                   ##
##                                                                            ##
## A cronjob excecutes this script every 4 hours at minute 0.                 ##
## Cronjob Entry:                                                             ##
## 30 3 * * * sudo /var/www/update/checkImportantUpdates.sh                   ##
##                                                                            ##
################################################################################
################################################################################

# get update number from git repository and current number
cd /tmp
wget -q https://raw.githubusercontent.com/sevelm/InnoTune/master/importantUpdate.txt
newCount=$(head -n1 /tmp/importantUpdate.txt)
oldCount=$(head -n1 /opt/innotune/settings/importantUpdate.txt)

# check numbers if old is less than new count, start update
if [[ "$oldCount" -lt "$newCount" ]]; then
    /var/www/update.sh
fi
