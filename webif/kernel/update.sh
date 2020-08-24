#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                update.sh                                   ##
##                                                                            ##
## Directory:   /var/www/kernel/                                              ##
## Created  :   22.06.2018                                                    ##
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
## This script checks if the board is a tinkerboard and if the current kernel ##
## version is lesser than the version in /var/www/kernel/version.txt.         ##
## If so the new kernel package will be installed.                            ##
##                                                                            ##
## This isn't really needed anymore because, shipped innoserver already       ##
## contain this kernel version.                                               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update/sudoscript.sh                                              ##
##                                                                            ##
################################################################################
################################################################################

if [[ $(uname -r | grep rockchip | wc -l) -eq 1 ]] && [[ $(uname -r | cut -d '.' -f3 | cut -d '-' -f1) -lt $(cat /var/www/kernel/version.txt | cut -d '.' -f3 | cut -d '-' -f1) ]]; then
    sudo dpkg -i /var/www/kernel/img-135.deb
fi
