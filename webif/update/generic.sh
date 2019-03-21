#!/bin/bash

##########################################################
# 					Config
# Shell Script zum Updaten des InnoTune-Systems
# Source: https://github.com/JHoerbst/InnoTune.git
##########################################################

############Section: Update############
echo "running generic update"

# Killall
killall shairport
killall shairport-sync
killall squeezelite-armv6hf
killall squeezeboxserver
killall mpd
killall aplay
killall librespot
killall playmonitor

#update packages
sudo apt-get -y update

# Settings Ordner
cp -R /opt/innotune/update/cache/InnoTune/settings/* -n /opt/innotune/settings
sudo mkdir /opt/innotune/settings/settings_player/eq
sudo chmod -R 777 /opt/innotune/settings

# WebInterface Ordner
cp -R /opt/innotune/update/cache/InnoTune/webif/* /var/www
sudo chmod -R 777 /var/www

#InnoPlay Mobile
sudo git clone https://github.com/AElmecker/InnoPlayMobile.git /usr/share/squeezeboxserver/HTML/InnoPlayMobile
sudo rm -r /usr/share/squeezeboxserver/HTML/m
sudo cp -R /usr/share/squeezeboxserver/HTML/InnoPlayMobile/m /usr/share/squeezeboxserver/HTML/m
sudo rm -r /usr/share/squeezeboxserver/HTML/InnoPlayMobile
