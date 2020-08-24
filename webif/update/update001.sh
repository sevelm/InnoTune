#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                update001.sh                                ##
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
## This is the first update script containing package installation/removal,   ##
## file creating/copying, permission settings, etc.                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update/full.sh                                                    ##
## /opt/innotune/update/cache/InnoTune/update.sh                              ##
##                                                                            ##
################################################################################
################################################################################

echo "running update001"
echo "50% - adding new updates" > /opt/innotune/settings/updatestatus.txt

# set apt repository to xenial (important for odroid updates)
sudo cp /opt/innotune/update/cache/InnoTune/sources.list /etc/apt/sources.list

# remove pulseaudio (pa may cause conflicts)
sudo apt-get -y purge pulseaudio

# log ports state file
sudo touch /opt/innotune/settings/logports
sudo chmod 777 /opt/innotune/settings/logports
echo "0" > /opt/innotune/settings/logports

# ExPL directory
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

# USB-Mount
sudo apt-get -y install usbmount

# Zip
sudo apt-get -y install zip

#MPC (for tts update)
sudo apt-get -y install mpc

#bc (for check_squeezelite_startup.sh)
sudo apt-get -y install bc

# Cifs
sudo apt-get -y install cifs-utils

# create networkmount directories
sudo mkdir /media/net0
sudo mkdir /media/net1
sudo mkdir /media/net2
sudo mkdir /media/net3
sudo mkdir /media/net4

# set netmount dir permissions
sudo chmod 777 /media/net0
sudo chmod 777 /media/net1
sudo chmod 777 /media/net2
sudo chmod 777 /media/net3
sudo chmod 777 /media/net4

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

# process info files
sudo touch /opt/innotune/settings/p_shairplay
sudo touch /opt/innotune/settings/p_squeeze
sudo touch /opt/innotune/settings/p_spotify
sudo chmod 777 /opt/innotune/settings/p_*

# process log file
sudo touch /var/www/checkprocesses.log
sudo chmod 777 /var/www/checkprocesses.log

sudo mkdir /var/www/InnoControl/log
sudo chmod 777 /var/www/InnoControl/log

# install alsa equalizer
sudo apt-get install -y libasound2-dev
sudo apt-get install -y libasound2-plugin-equal

# add config file for open audio channel
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

# add script to cron if it isn't already added
is_added=$(crontab -l | grep checkprocesses.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "* * * * * /var/www/checkprocesses.sh"; } | crontab -
fi

# add script to cron if it isn't already added
is_added=$(crontab -l | grep checkcputemp.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "*/15 * * * * /var/www/checkcputemp.sh"; } | crontab -
fi

# add script to cron if it isn't already added
is_added=$(crontab -l | grep archivelogs.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "45 3 * * * /var/www/archivelogs.sh"; } | crontab -
fi

# add script to cron if it isn't already added
is_added=$(crontab -l | grep filesizechecker.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "30 */1 * * * /var/www/filesizechecker.sh"; } | crontab -
fi

echo "70% - adding new updates" > /opt/innotune/settings/updatestatus.txt

# Imagestream 4 Loxone
# used php extensions
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" php5.6-gd
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" php5.6-curl

# php.ini file with enabled curl-extension
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
sudo DEBIAN_FRONTEND=noninteractive apt-get -f -y install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"

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

# add and give permission to conf files
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

# update voice rss key
printf %s "a269cdea933c4994a8ce81916d748ef8" > /opt/innotune/settings/voiceoutput/voicersskey.txt

# install tcp dump
sudo apt-get install -y tcpdump

echo "80% - adding new updates" > /opt/innotune/settings/updatestatus.txt

# knx dimmer
cp /opt/innotune/update/cache/InnoTune/knxdefaultradios.txt /opt/innotune/settings/knxdefaultradios.txt
sudo chmod 777 /opt/innotune/settings/knxdefaultradios.txt

# knx radio user settings
sudo touch /opt/innotune/settings/knxradios.txt
sudo chmod 777 /opt/innotune/settings/knxradios.txt

if [[ ! -f /opt/innotune/settings/knxcurrentradio.txt ]]; then
    sudo touch /opt/innotune/settings/knxcurrentradio.txt
    sudo chmod 777 /opt/innotune/settings/knxcurrentradio.txt
    printf %s "1" > /opt/innotune/settings/knxcurrentradio.txt
fi

# add update count
sudo touch /opt/innotune/settings/update_cnt.txt
sudo chmod 777 /opt/innotune/settings/update_cnt.txt

# set new update count and reference to newer update file
sudo echo "1" > /opt/innotune/settings/update_cnt.txt
/var/www/update/update002.sh
