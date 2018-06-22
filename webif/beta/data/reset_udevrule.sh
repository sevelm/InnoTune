#!/bin/bash
start=$(cat /opt/innotune/settings/mapping_current.txt | wc -l)
rm /opt/innotune/settings/mapping.txt
rm /opt/innotune/settings/mapping_current.txt
rm /opt/innotune/settings/80-usb-audio-id.rules
rm /etc/udev/rules.d/80-usb-audio-id.rules
rm /etc/udev/rules.d/90-usb-audio-log-remove.rules
rm /opt/innotune/settings/90-usb-audio-log-remove.rules
rm /etc/asound.conf
cnt=$((10-$start))
start=$(($start+1))
for (( c=$start; c<=${cnt}; c++ ))
do
  if [[ "$c" -ne "10" ]]; then
    sed -i "1s/.*/0/" "/opt/innotune/settings/settings_player/dev0$c.txt"
  else
    sed -i "1s/.*/0/" "/opt/innotune/settings/settings_player/dev$c.txt"
  fi
done
