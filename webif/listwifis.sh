#!/bin/bash
if [[ $(cat /etc/os-release | grep Raspbian | wc -l) ]]; then
  rfkill unblock wifi
  ifconfig wlan0 up
fi
iwlist wlan0 scan | grep ESSID | cut -d '"' -f2 | sort -u | tr '\n' ';'
