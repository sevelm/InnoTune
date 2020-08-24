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
## This script creates the local mountpoint directory and tries to mount the  ##
## network storage. If mount was successful the mount will be saved in the    ##
## fstab file.                                                                ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 fstab mount string                                                      ##
## $2 mountpoint directory                                                    ##
## $3 network path                                                            ##
## $4 filesystem type                                                         ##
## $5 mount options                                                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# checks if mountpoint exists, if not it will be created and permissions will
# be granted
if [ ! -d "/media/$2" ]; then
    sudo mkdir "/media/$2"
    sudo chmod 777 "/media/$2"
fi

sudo mount -t "$4" -o "$5" "$3" "$2" &> /tmp/mttmp
# if mount was successfull, then the mount is saved in the fstab file
if [[ $? -eq 0 ]]; then
    echo "successfully mounted"
    echo "$2;$3;$4;$1" >> /opt/innotune/settings/netmount.txt
    echo "$1" >> /etc/fstab
else
    error=$(cat /tmp/mttmp)
    echo "error not saving networkmount. $error"
fi
