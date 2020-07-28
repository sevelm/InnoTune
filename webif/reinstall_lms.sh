#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            reinstall_lms.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   19.11.2018                                                    ##
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
## This script automatically loads the lms 7.9.2 version for the current os   ##
## and installs it on the current system.                                     ##
## Script adapted from the 'how to install lms on ubuntu' from the lms wiki.  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

os=$(dpkg --print-architecture)
if [ "$os" = "armhf" ]; then os=arm; fi
url="http://www.mysqueezebox.com/update/?version=7.9.2&revision=1&geturl=1&os=deb$os"
if wget -q --spider $url; then
    sudo apt-get remove --purge -y logitechmediaserver
    sudo apt-get install -y libio-socket-ssl-perl
    latest_lms=$(wget -q -O - "$url")
    mkdir -p /sources
    cd /sources
    wget $latest_lms
    lms_deb=${latest_lms##*/}
    sudo dpkg -i $lms_deb
    sudo apt-get -f -y install
    # LMS Wizard fix (completes form automatically, if wizard pops up)
    sudo cp /opt/innotune/update/cache/InnoTune/wizard.html /usr/share/squeezeboxserver/HTML/EN/settings/server/wizard.html
    find /sources/ -type f \( -name "logitechmediaserver*" \) -delete
else
    echo "couldn't resolve download url"
fi
