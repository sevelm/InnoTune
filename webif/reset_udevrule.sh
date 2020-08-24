#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            reset_udevrule.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   22.06.2018                                                    ##
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
## This script removes device mappings, udev rules, the asound config and it  ##
## resets all amp configs.                                                    ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/reset.sh                                                          ##
##                                                                            ##
################################################################################
################################################################################

# removes mapping, udev rules and alsa config
start=$(cat /opt/innotune/settings/mapping_current.txt | wc -l)
rm /opt/innotune/settings/mapping.txt
rm /opt/innotune/settings/mapping_current.txt
rm /opt/innotune/settings/80-usb-audio-id.rules
rm /etc/udev/rules.d/80-usb-audio-id.rules
rm /etc/udev/rules.d/90-usb-audio-log-remove.rules
rm /opt/innotune/settings/90-usb-audio-log-remove.rules
rm /etc/asound.conf

# resets the innotune config of each inno amp
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
