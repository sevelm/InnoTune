#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                setwlan.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   18.07.2018                                                    ##
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
## This script replaces the old ssid and psk with the parameter values in the ##
## wpa supplicant config file. Also enables/disables the wifi.                ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 ssid                                                                    ##
## $2 psk                                                                     ##
## $3 mode (1 = enabled, 0 = disabled)                                        ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# get ssid and psk from innotune wifi settings
SSID=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'ssid="' | cut -d '"' -f2)
PSK=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'psk="' | cut -d '"' -f2)
echo "$3" > /opt/innotune/settings/wlan.txt

# read old ssid and psk and override it with new values
sed -i "s/ssid=\"$SSID\"/ssid=\"$1\"/g" /opt/innotune/settings/wpa_supplicant.conf
sed -i "s/psk=\"$PSK\"/psk=\"$2\"/g" /opt/innotune/settings/wpa_supplicant.conf
