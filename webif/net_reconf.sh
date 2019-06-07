#!/bin/bash
if [[ $(ifconfig | grep eth0:avahi | wc -l) -gt 0 ]]; then
    ifdown -v eth0
    ifup -v eth0
fi

if [[ $(ifconfig | grep wlan0:avahi | wc -l) -gt 0 ]]; then
    ifdown -v wlan0
    ifup -v wlan0
fi
