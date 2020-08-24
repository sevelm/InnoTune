#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              shutdown_hook.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   20.04.2020                                                    ##
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
## This script is used to execute commands on halt, shudown or reboot.        ##
## It runs in a systemd service, getting excecuted before above commands.     ##
##                                                                            ##
## A cronjob excecutes this script every 4 hours at minute 0.                 ##
## Cronjob Entry:                                                             ##
## 0 */4 * * * sudo /var/www/shutdown_hook.sh                                 ##
##                                                                            ##
################################################################################
################################################################################

# saving LMS Preferences
cp /var/lib/squeezeboxserver/prefs/server.prefs /opt/server.prefs
# syncing to filesystem
sync
