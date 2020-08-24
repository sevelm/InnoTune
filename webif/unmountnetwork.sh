#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             mountnetwork.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   12.06.2018                                                    ##
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
## This script deletes the mount entry from the fstab file and unmounts the   ##
## network storage.                                                           ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 network path                                                            ##
## $2 mountpoint directory                                                    ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

netmount=$(grep -v "$1;$2" /opt/innotune/settings/netmount.txt)
fstab=$(grep -v "$2 $1" /etc/fstab)
echo "$netmount" > /opt/innotune/settings/netmount.txt
echo "$fstab" > /etc/fstab
sudo umount -f "$1"
