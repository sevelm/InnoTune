#!/bin/bash

##########################################################
# 					Config
# Shell Script zum Updaten des InnoTune-Systems
# Source: https://github.com/JHoerbst/InnoTune.git
##########################################################

############Section: Update############
echo "running generic update"
echo "10% - starting generic update" > /opt/innotune/settings/updatestatus.txt

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
echo "20% - updated packages" > /opt/innotune/settings/updatestatus.txt

# Settings Ordner
cp -R /opt/innotune/update/cache/InnoTune/settings/* -n /opt/innotune/settings
sudo mkdir /opt/innotune/settings/settings_player/eq
sudo chmod -R 777 /opt/innotune/settings

# WebInterface Ordner
cp -R /opt/innotune/update/cache/InnoTune/webif/* /var/www
sudo chmod -R 777 /var/www

echo "30% - copied files" > /opt/innotune/settings/updatestatus.txt

#InnoPlay Mobile
sudo git clone https://github.com/AElmecker/InnoPlayMobile.git /usr/share/squeezeboxserver/HTML/InnoPlayMobile
sudo rm -r /usr/share/squeezeboxserver/HTML/m
sudo cp -R /usr/share/squeezeboxserver/HTML/InnoPlayMobile/m /usr/share/squeezeboxserver/HTML/m
sudo rm -r /usr/share/squeezeboxserver/HTML/InnoPlayMobile

echo "35% - cloned innoplay mobile" > /opt/innotune/settings/updatestatus.txt
