#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            validateupdate.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   14.11.2018                                                    ##
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
## This script runs after a update-reboot and validated that important        ##
## packages will be checked if they are installed and if not it will try to   ##
## install the package again.                                                 ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

validate=$(cat /opt/innotune/settings/validate.txt | head -n1 | tail -n1)
if [ $validate == "1" ]; then
    > /var/www/InnoControl/log/validate.log

#Edit Elmecker 06.01.2024
#    declare -a packages=("php5.6-gd" "php5.6-curl" "libasound2-plugin-equal"
#                       "libasound2-dev" "shairport-sync" "usbmount" "zip"
#                       "mpc" "bc" "cifs-utils")
    declare -a packages=("libasound2-plugin-equal"
                       "libasound2-dev" "shairport-sync" "usbmount" "zip"
                       "mpc" "bc" "cifs-utils")

    # iterate through declared packages
    for package in "${packages[@]}"
    do
        echo "checking $package..."
        if [[ $(dpkg-query -W -f='${Status}\n' "$package" | wc -l) -ne 1 ]]; then
            sudo apt-get install -f -y "$package"
            # check if package was installed
            if [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep installed | wc -l) -eq 1 ]]; then
                echo "$package;installed" >> /var/www/InnoControl/log/validate.log
            else
                echo "$package;failed" >> /var/www/InnoControl/log/validate.log
            fi
        elif [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep not-installed | wc -l) -eq 1 ]]; then
            sudo apt-get install -f -y "$package"
            # check if package was installed
            if [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep installed | wc -l) -eq 1 ]]; then
                echo "$package;installed" >> /var/www/InnoControl/log/validate.log
            else
                echo "$package;failed" >> /var/www/InnoControl/log/validate.log
            fi
        fi
    done
    echo "0" > /opt/innotune/settings/validate.txt
fi
