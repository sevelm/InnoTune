#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               itunesmnt.sh                                 ##
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
## This script tries to mount the newtwork storage containing the itunes      ##
## library. It saves the credentials and adds an fstab entry to automount the ##
## network filesystem, only if manual mount succeeded.                        ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 path                                                                    ##
## $2 username                                                                ##
## $3 password                                                                ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# refresh credentials file
> /opt/ituneslogin
echo "username=$2" >> /opt/ituneslogin
echo "password=$3" >> /opt/ituneslogin

# mount network manually
sudo mount -t cifs -o credentials=/opt/ituneslogin "$1" /opt/itunesshare &> /tmp/mttmp
# check if mount succeeded, if so add an fstab entry for automount
if [[ $? -eq 0 ]]; then
    echo "successfully mounted"
    # add to fstab for automount
    echo "$1 /opt/itunesshare cifs credentials=/opt/ituneslogin 0 0" >> /etc/fstab
    sudo /var/www/itunesrefresh.sh
else
    error=$(cat /tmp/mttmp)
    echo "error not saving networkmount. $error"
fi
