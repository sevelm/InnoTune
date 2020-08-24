#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                 update.sh                                  ##
##                                                                            ##
## Directory:   /opt/innotune/update/cache/InnoTune/                          ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##              Severin Elmecker                                              ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script runs after the new software was downloaded. First it runs the  ##
## main (generic) update with the following update00X files depending on      ##
## which updates are already installed.                                       ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update.sh                                                         ##
##                                                                            ##
################################################################################
################################################################################

echo "running latest update"

# generic commands for every update
/opt/innotune/update/cache/InnoTune/webif/update/generic.sh

# run the latest update scripts version that is not installed yet
# the cnt number should select the script updateXXX, where XXX = cnt + 1
cnt=$(cat /opt/innotune/settings/update_cnt.txt)
if [[ "$cnt" -ge "1" ]]; then
    /var/www/update/update002.sh
else
    /var/www/update/update001.sh
fi
