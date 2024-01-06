#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                generic.sh                                  ##
##                                                                            ##
## Directory:   /var/www/update/                                              ##
## Created  :   21.03.2019                                                    ##
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
## This script clears the update cache and clones the new software from the   ##
## github repository. Executes the newly downloaded update script to          ##
## continue the update-chain.                                                 ##
##                                                                            ##
##                                 References                                 ##
## /opt/innotune/update/cache/InnoTune/update.sh                              ##
## /var/www/update/full.sh                                                    ##
## /var/www/update/latest.sh                                                  ##
##                                                                            ##
################################################################################
################################################################################

echo "running generic update"
echo "10% - starting generic update" > /opt/innotune/settings/updatestatus.txt

# kill running audio processes to avoid conflicts
killall shairport
killall shairport-sync
killall squeezelite-armv6hf
killall squeezeboxserver
killall mpd
killall aplay
killall librespot
killall playmonitor

# fix dpkg errors if there are any
sudo DEBIAN_FRONTEND=noninteractive dpkg --configure -a --force-confdef --force-confold
sudo DEBIAN_FRONTEND=noninteractive apt-get -f -y install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"

# update packages
sudo apt-get -y update
echo "20% - updated packages" > /opt/innotune/settings/updatestatus.txt

# copy settings directory and set permissions
cp -R /opt/innotune/update/cache/InnoTune/settings/* -n /opt/innotune/settings
sudo mkdir /opt/innotune/settings/settings_player/eq
sudo chmod -R 777 /opt/innotune/settings

# copy webinterface directory and set permissions
cp -R /opt/innotune/update/cache/InnoTune/webif/* /var/www
sudo chmod -R 777 /var/www

# remove old spotify client and install librespot
sudo rm /root/librespot-linux-armhf-raspberry_pi.zip
sudo apt-get -y install build-essential portaudio19-dev
sudo cp /opt/innotune/update/cache/InnoTune/librespot /root/librespot

echo "30% - copied files" > /opt/innotune/settings/updatestatus.txt

# clone InnoTune mobile lms skin
sudo git clone https://github.com/sevelm/InnoPlayMobile.git /usr/share/squeezeboxserver/HTML/InnoPlayMobile
sudo rm -r /usr/share/squeezeboxserver/HTML/m
sudo cp -R /usr/share/squeezeboxserver/HTML/InnoPlayMobile/m /usr/share/squeezeboxserver/HTML/m
sudo rm -r /usr/share/squeezeboxserver/HTML/InnoPlayMobile

# LMS Wizard fix (completes form automatically, if wizard pops up)
sudo cp /opt/innotune/update/cache/InnoTune/wizard.html /usr/share/squeezeboxserver/HTML/EN/settings/server/wizard.html

# Add Crontabs
grep -q -F "*/5 * * * * /var/www/check_xplhub.sh" /var/spool/cron/crontabs/root || echo "*/5 * * * * /var/www/check_xplhub.sh" >> /var/spool/cron/crontabs/root

echo "35% - cloned innoplay mobile" > /opt/innotune/settings/updatestatus.txt
