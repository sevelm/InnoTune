#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                latest.sh                                   ##
##                                                                            ##
## Directory:   /var/www/update/                                              ##
## Created  :   21.03.2019                                                    ##
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
## This script reruns the latest update script without downloading the        ##
## software again.                                                            ##
## Used for resolving update problems.                                        ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update/sudoscript.sh                                              ##
##                                                                            ##
################################################################################
################################################################################
echo "running latest update"

# generic commands for every update
/opt/innotune/update/cache/InnoTune/webif/update/generic.sh

# run the latest update scripts version that is not installed yet
/var/www/update/update002.sh
