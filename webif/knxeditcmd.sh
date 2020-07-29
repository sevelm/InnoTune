#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              knxeditcmd.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   20.02.2019                                                    ##
## Edited   :   29.07.2020                                                    ##
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
## This script deletes, edits or adds new knx commands to the list of cmds.   ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 action (0 = delete, 1 = add/edit)                                       ##
## $2 old entry                                                               ##
## $3 new entry                                                               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

if [[ "$1" -eq 0 ]]; then
    # delete entry
    sed -i "\;$2;d" /opt/innotune/settings/knxcmd.txt
else
    # delete entry
    sed -i "\;$2;d" /opt/innotune/settings/knxcmd.txt
    # add entry
    echo "$3" >> /opt/innotune/settings/knxcmd.txt
fi
