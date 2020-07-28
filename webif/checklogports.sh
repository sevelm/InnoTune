#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            checklogports.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   06.02.2019                                                    ##
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
## Checks if tcpdumps are or aren't running accordingly e.g. no processes     ##
## when disabled or right count of processes when enabled.                    ##
## If this isn't true then right measures are taken e.g. all processes killed ##
## or restarted.                                                              ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

LOG=$(cat /opt/innotune/settings/logports)
DUMP=$(ps cax | grep tcpdump | wc -l)
if [ $LOG == "1" ]; then
    if [ $DUMP -ne 2 ]; then
        # if dump should run but there are not all tcpdump processes running
        # kill all tcpdump processes and restart
        killall tcpdump
        sudo /var/www/logports.sh "1" &> /dev/null
    fi
elif [ $LOG == "0" ]; then
    if [ $DUMP -ne 0 ]; then
        # if dump shouldn't run, but there are processes running
        # stop the tcpdumps
        sudo /var/www/logports.sh "0" &> /dev/null
    fi
fi
