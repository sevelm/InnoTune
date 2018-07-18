#!/bin/bash
SSID=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'ssid="' | cut -d '"' -f2)
PSK=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'psk="' | cut -d '"' -f2)
echo "$3" > /opt/innotune/settings/wlan.txt
sed -i "s/ssid=\"$SSID\"/ssid=\"$1\"/g" /opt/innotune/settings/wpa_supplicant.conf
sed -i "s/psk=\"$PSK\"/psk=\"$2\"/g" /opt/innotune/settings/wpa_supplicant.conf
