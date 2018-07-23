#!/bin/bash
SSID=$(cat /opt/innotune/settings/test_wpa.conf | grep 'ssid="' | cut -d '"' -f2)
PSK=$(cat /opt/innotune/settings/test_wpa.conf | grep 'psk="' | cut -d '"' -f2)
sed -i "s/ssid=\"$SSID\"/ssid=\"$1\"/g" /opt/innotune/settings/test_wpa.conf
sed -i "s/psk=\"$PSK\"/psk=\"$2\"/g" /opt/innotune/settings/test_wpa.conf

wpa_supplicant -B -i wlan0 -c /opt/innotune/settings/test_wpa.conf

sudo dhclient -v wlan0 > /opt/wlantest.txt 2>&1 &
sleep 20

killall dhclient
killall wpa_supplicant

cat /opt/wlantest.txt | tail -n1 | grep "bound to" | wc -l