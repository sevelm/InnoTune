#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                           reinstallPackage.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   15.11.2018                                                    ##
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
## This script checks if a package failed to install and tries to install it  ##
## again.                                                                     ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 package                                                                 ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# check if parameter is in validation log
check=$(cat /var/www/InnoControl/log/validate.log | grep "$1;failed" | wc -l)
if [[ $check -eq 1 ]]; then
    log=$(grep -v "php5.6-gd;failed" /var/www/InnoControl/log/validate.log)
    echo "$log" > /var/www/InnoControl/log/validate.log
    echo "1" > /opt/innotune/settings/validate.txt
    sudo dpkg --configure -a
    sudo apt-get install -f -y "$1"
    # check if package was installed
    if [[ $(dpkg-query -W -f='${Status}\n' "$1" | grep installed | wc -l) -eq 1 ]]; then
        echo "$1;installed" >> /var/www/InnoControl/log/validate.log
        echo "$1;installed"
    else
        echo "$1;failed" >> /var/www/InnoControl/log/validate.log
        echo "$1;failed"
    fi
else
    echo "invalid or already installed package $1"
fi
