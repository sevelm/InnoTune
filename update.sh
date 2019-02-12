#!/bin/bash

##########################################################
# 					Config
# Shell Script zum Updaten des InnoTune-Systems
# Source: https://github.com/JHoerbst/InnoTune.git
##########################################################

############Section: Update############

# Killall
killall shairport
killall shairport-sync
killall squeezelite-armv6hf
killall squeezeboxserver
killall mpd
killall aplay
killall librespot
killall playmonitor

#Set apt repository to xenial (important for odroid updates)
sudo cp /opt/innotune/update/cache/InnoTune/sources.list /etc/apt/sources.list

#remove pulseaudio (pa may cause conflicts)
sudo apt-get -y purge pulseaudio

sudo apt-get -y update

# Settings Ordner
cp -R /opt/innotune/update/cache/InnoTune/settings/* -n /opt/innotune/settings
sudo mkdir /opt/innotune/settings/settings_player/eq
sudo chmod -R 777 /opt/innotune/settings

sudo touch /opt/innotune/settings/logports
sudo chmod 777 /opt/innotune/settings/logports
echo "0" > /opt/innotune/settings/logports

# WebInterface Ordner
cp -R /opt/innotune/update/cache/InnoTune/webif/* /var/www
sudo chmod -R 777 /var/www

# ExPL Ordner
sudo cp -R /opt/innotune/update/cache/InnoTune/ExPL /var/lib/squeezeboxserver/cache/InstalledPlugins/Plugins/
sudo chmod -R 755 /var/lib/squeezeboxserver/cache/InstalledPlugins/Plugins/
sudo chown -R squeezeboxserver:nogroup /var/lib/squeezeboxserver/cache/InstalledPlugins/Plugins/

# Update Lighttpd config
sudo cp /etc/lighttpd/lighttpd.conf /etc/lighttpd/lighttpd.conf.old
sudo cp /opt/innotune/update/cache/InnoTune/lighttpd.conf /etc/lighttpd/lighttpd.conf

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

#bc (for check_squeezelite_startup.sh)
sudo apt-get -y install bc

# Cifs
sudo apt-get -y install cifs-utils

sudo mkdir /media/net0
sudo mkdir /media/net1
sudo mkdir /media/net2
sudo mkdir /media/net3
sudo mkdir /media/net4

sudo chmod 777 /media/net0
sudo chmod 777 /media/net1
sudo chmod 777 /media/net2
sudo chmod 777 /media/net3
sudo chmod 777 /media/net4

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

# Shairport (old) remove if it still exists
sudo rm /usr/local/bin/shairport
sudo rm -R /opt/shairport

# Shairport-sync
sudo apt-get -y install shairport-sync --force-yes
sudo systemctl stop shairport-sync
sudo systemctl disable shairport-sync

#Spotify Connect
sudo rm /root/librespot-linux-armhf-raspberry_pi.zip
sudo apt-get -y install build-essential portaudio19-dev
sudo cp /opt/innotune/update/cache/InnoTune/librespot /root/librespot

#process info files
sudo touch /opt/innotune/settings/p_shairplay
sudo touch /opt/innotune/settings/p_squeeze
sudo touch /opt/innotune/settings/p_spotify
sudo chmod 777 /opt/innotune/settings/p_*

#process log file
sudo touch /var/www/checkprocesses.log
sudo chmod 777 /var/www/checkprocesses.log

sudo mkdir /var/www/InnoControl/log
sudo chmod 777 /var/www/InnoControl/log

#sudo mkdir /var/log/innologs
#sudo chmod 777 /var/log/innologs

#sudo cp /opt/innotune/update/cache/InnoTune/fstab /etc/fstab
#sudo cp /opt/innotune/update/cache/InnoTune/innolog /etc/logrotate.d/innolog

sudo apt-get install -y libasound2-dev
sudo apt-get install -y libasound2-plugin-equal

if [[ ! -d "/opt/innotune/settings/settings_player/oac" ]]; then
  sudo mkdir /opt/innotune/settings/settings_player/oac
  for (( c=1; c < 10; c++ ))
  do
    path="/opt/innotune/settings/settings_player/oac/oac0$c.txt"
    echo "1" > $path
  done
  path="/opt/innotune/settings/settings_player/oac/oac10.txt"
  echo "1" > $path
  sudo chmod -R 777 /opt/innotune/settings/settings_player/oac
fi

#add script to cron if it isn't already added
is_added=$(crontab -l | grep checkprocesses.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "* * * * * /var/www/checkprocesses.sh"; } | crontab -
fi

#add script to cron if it isn't already added
#is_added=$(crontab -l | grep check_soundcards.sh | wc -l)
#if [[ $is_added -eq 0 ]]; then
#    crontab -l | { cat; echo "*/15 * * * * /var/www/check_soundcards.sh"; } | crontab -
#fi

#add script to cron if it isn't already added
#is_added=$(crontab -l | grep checklogsize.sh | wc -l)
#if [[ $is_added -eq 0 ]]; then
#    crontab -l | { cat; echo "* * * * * /var/www/checklogsize.sh"; } | crontab -
#else
#    crontab -l | grep -v "*/5 * * * * /var/www/checklogsize.sh" | crontab -
#    crontab -l | { cat; echo "* * * * * /var/www/checklogsize.sh"; } | crontab -
#fi

#add script to cron if it isn't already added
is_added=$(crontab -l | grep checkcputemp.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "*/15 * * * * /var/www/checkcputemp.sh"; } | crontab -
fi

#add script to cron if it isn't already added
is_added=$(crontab -l | grep archivelogs.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "45 3 * * * /var/www/archivelogs.sh"; } | crontab -
fi

is_added=$(crontab -l | grep filesizechecker.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "30 */1 * * * /var/www/filesizechecker.sh"; } | crontab -
fi

#InnoPlay Mobile
sudo git clone https://github.com/AElmecker/InnoPlayMobile.git /usr/share/squeezeboxserver/HTML/InnoPlayMobile
sudo rm -r /usr/share/squeezeboxserver/HTML/m
sudo cp -R /usr/share/squeezeboxserver/HTML/InnoPlayMobile/m /usr/share/squeezeboxserver/HTML/m
sudo rm -r /usr/share/squeezeboxserver/HTML/InnoPlayMobile

#Imagestream 4 Loxone
#used php extensions
sudo apt-get install -y php5.6-gd
sudo apt-get install -y php5.6-curl

#LMS Wizard fix (completes form automatically, if wizard pops up)
sudo cp /opt/innotune/update/cache/InnoTune/wizard.html /usr/share/squeezeboxserver/HTML/EN/settings/server/wizard.html

#php.ini file with enabled curl-extension
sudo cp /opt/innotune/update/cache/InnoTune/php.ini /etc/php/5.6/cgi/php.ini

# Add Crontabs
grep -q -F "*/15 * * * * /var/www/playercheck.sh" /var/spool/cron/crontabs/root || echo "*/15 * * * * /var/www/playercheck.sh" >> /var/spool/cron/crontabs/root
grep -q -F "3 3 * * * sudo shutdown -r now" /var/spool/cron/crontabs/root || echo "3 3 * * * sudo shutdown -r now" >> /var/spool/cron/crontabs/root

# install additional packages for raspberry_pi
rasp=$(cat /etc/os-release | grep Raspbian | wc -l)
if [ $rasp -ge 1 ]; then
  sudo apt-get install -y libportaudio2
fi

# fix unmet dependencies/broken packages
sudo apt-get -f -y install

# Overwrite current Version number with new
cd /opt/innotune/update/cache
sudo cat InnoTune/version.txt > /var/www/version.txt

sudo /var/www/create_asound.sh
sudo service dhcpcd stop
sudo systemctl disable dhcpcd
sudo update-rc.d -f dhcpcd remove

# add wlan files
if [[ ! -f /opt/innotune/settings/wlan.txt ]]; then
  echo "0" > /opt/innotune/settings/wlan.txt
  sudo chmod 777 /opt/innotune/settings/wlan.txt
fi

if [[ ! -f /opt/innotune/settings/wpa_supplicant.conf ]]; then
  cp /opt/innotune/update/cache/InnoTune/wpa_supplicant.conf /opt/innotune/settings/wpa_supplicant.conf
  sudo chmod 777 /opt/innotune/settings/wpa_supplicant.conf
fi

cp /opt/innotune/update/cache/InnoTune/wpa_supplicant.conf /opt/innotune/settings/test_wpa.conf
sudo chmod 777 /opt/innotune/settings/test_wpa.conf

sudo mkdir /opt/itunesshare
sudo chmod 777 /opt/itunesshare
sudo touch /opt/ituneslogin
sudo chmod 644 /opt/ituneslogin

sudo touch /opt/innotune/settings/validate.txt
sudo chmod 777 /opt/innotune/settings/validate.txt
sudo touch /var/www/InnoControl/log/validate.log
sudo chmod 777 /var/www/InnoControl/log/validate.log
sudo touch /var/www/InnoControl/log/reinstall_lms.log
sudo chmod 777 /var/www/InnoControl/log/reinstall_lms.log

#update voice rss key
echo "c8f843969ced443ba02e6e1efde33851" > /opt/innotune/settings/voiceoutput/voicersskey.txt
