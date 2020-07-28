#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               itunesumnt.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   12.11.2018                                                    ##
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
## This script deletes the saved credentials and removes the fstab entry.     ##
## The mount will be gone after the next reboot.                              ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# delete old credentials
> /opt/ituneslogin
sudo rm /opt/iTunesMusicLibrary.xml

# delete from fstab
# mount will be gone next reboot
fstab=$(grep -v "/opt/itunesshare" /etc/fstab)
echo "$fstab" > /etc/fstab
