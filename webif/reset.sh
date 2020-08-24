#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                reset.sh                                    ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   12.09.2017                                                    ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script resets following settings to default values:                   ##
## network                                                                    ##
## usb audio                                                                  ##
## mpd playlists                                                              ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# reset network settings
if [ "$1" = "net" ]; then
    sed -i "1s/.*/"dhcp"/" /opt/innotune/settings/network.txt
    sudo /var/www/sudoscript.sh setnet
    OUTPUT="network";
fi

# reset usb audio settings
if [ "$1" = "usb" ]; then
    for entry in "/opt/innotune/settings/settings_player"/*
    do
        echo "0 \n\n\n\n\n\n\n\n\n\n\n\n" > $entry
    done

    echo "0\n0" > /opt/innotune/settings/changedconf.txt
    /var/www/reset_udevrule.sh
    OUTPUT="usb";
fi

# reset mpd playlists
if [ "$1" = "playlists" ]; then
    echo "Alarm\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" > /opt/innotune/settings/mpdvolplay.txt
    echo "Gong\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt
    echo "Gong2\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt
    echo "Vorwarnung\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt
fi

echo "${OUTPUT}";
