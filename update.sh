#!/bin/bash

##########################################################
# 					Config
# Shell Script zum Updaten des InnoTune-Systems
# Source: https://github.com/JHoerbst/InnoTune.git
##########################################################

############Section: Update############

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


############Section: Fixes############

# PHP File Upload Fix 24.08.2017
PHPVersion=$(php -v|grep --only-matching --perl-regexp "5\.\\d+\.\\d+");

sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php/${PHPVersion:0:3}/cli/php.ini
sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php/${PHPVersion:0:3}/cgi/php.ini

sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php5/cli/php.ini
sed -i "s/^\(upload_max_filesize\).*/\1 $(eval echo =20M)/" /etc/php5/cgi/php.ini


mkdir /media/Soundfiles/uploads
sudo chmod -R 777 /media/Soundfiles/uploads

mkdir /media/Soundfiles/tts
sudo chmod -R 777 /media/Soundfiles/tts


# Shairport
sudo git clone https://github.com/abrasive/shairport.git /opt/shairport
cd /opt/shairport
sudo ./configure
sudo make install

sudo /var/www/create_asound.sh
sudo apt-get -y install mpc
sudo service dhcpcd stop
sudo systemctl disable dhcpcd
sudo update-rc.d -f dhcpcd remove