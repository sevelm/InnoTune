#!/bin/bash

##########################################################
# 					Config
# Shell Script zum Updaten des InnoTune-Systems
# Source: https://github.com/JHoerbst/InnoTune.git
##########################################################

############Section: Update############

sudo apt-get update

# Settings Ordner
cp -R /opt/innotune/update/cache/InnoTune/settings/* -n /opt/innotune/settings
sudo chmod -R 777 /opt/innotune/settings

# WebInterface Ordner
cp -R /opt/innotune/update/cache/InnoTune/webif/* /var/www
sudo chmod -R 777 /var/www

# Change Document Root for InnoControl
var="\"\\/var\\/www\\/InnoControl\""
sed -i 's/^\(server.document-root\).*/\1 '=$var'/'  /etc/lighttpd/lighttpd.conf

# Remove Lighttpd Authentication
sed -e '/mod_auth/ s/^#*/#/' -i /etc/lighttpd/lighttpd.conf

# USB-Mount:
sudo apt-get -y install usbmount

# Zip
sudo apt-get -y install zip

#MPC (for tts update)
sudo apt-get -y install mpc

# Cifs
#sudo apt-get -y install cifs-utils

############Section: Fixes############

# PHP File Upload Fix 24.08.2017
PHPVersion=$(php -v|grep --only-matching --perl-regexp "5\.\\d+\.\\d+");

sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php/${PHPVersion:0:3}/cli/php.ini
sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php/${PHPVersion:0:3}/cgi/php.ini

sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php5/cli/php.ini
sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php5/cgi/php.ini

# Make & change permissions on uploads directory
mkdir /media/Soundfiles/uploads
sudo chmod -R 777 /media/Soundfiles/uploads

# Make & change permissions on tts directory
mkdir /media/Soundfiles/tts
sudo chmod -R 777 /media/Soundfiles/tts


# Shairport
sudo git clone https://github.com/abrasive/shairport.git /opt/shairport
cd /opt/shairport
sudo ./configure
sudo make install

# Spotify Connect
cd /root
wget https://github.com/herrernst/librespot/releases/download/v20170717-910974e/librespot-linux-armhf-raspberry_pi.zip
unzip librespot-linux-armhf-raspberry_pi.zip -d .


# Add Crontabs
grep -q -F "*/15 * * * * /var/www/playercheck.sh" /var/spool/cron/crontabs/root || echo "*/15 * * * * /var/www/playercheck.sh" >> /var/spool/cron/crontabs/root
grep -q -F "3 3 * * * sudo shutdown -r now" /var/spool/cron/crontabs/root || echo "3 3 * * * sudo shutdown -r now" >> /var/spool/cron/crontabs/root

# Overwrite current Version number with new
cd /opt/innotune/update/cache
sudo cat InnoTune/version.txt > /var/www/version.txt

sudo /var/www/create_asound.sh
sudo apt-get -y install mpc
sudo service dhcpcd stop
sudo systemctl disable dhcpcd
sudo update-rc.d -f dhcpcd remove