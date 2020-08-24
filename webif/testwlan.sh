#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               testwlan.sh                                  ##
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
## This script tries to connect to a wifi network as a test if the            ##
## entered credentials were correct.                                          ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 ssid                                                                    ##
## $2 psk                                                                     ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# set credentials
SSID=$(cat /opt/innotune/settings/test_wpa.conf | grep 'ssid="' | cut -d '"' -f2)
PSK=$(cat /opt/innotune/settings/test_wpa.conf | grep 'psk="' | cut -d '"' -f2)
sed -i "s/ssid=\"$SSID\"/ssid=\"$1\"/g" /opt/innotune/settings/test_wpa.conf
sed -i "s/psk=\"$PSK\"/psk=\"$2\"/g" /opt/innotune/settings/test_wpa.conf

echo "" > /opt/wlantest.txt

killall dhclient
killall wpa_supplicant

# try to connect and check if interface got ip address
wpa_supplicant -B -i wlan0 -c /opt/innotune/settings/test_wpa.conf
sleep 40
sudo dhclient -v wlan0 > /opt/wlantest.txt 2>&1 &
sleep 20

cat /opt/wlantest.txt | tail -n1 | grep "bound to" | wc -l
