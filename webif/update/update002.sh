#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                update002.sh                                ##
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
## This is the second update script containing package installation/removal,  ##
## file creating/copying, permission settings, etc.                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update/full.sh                                                    ##
## /var/www/update/latest.sh                                                  ##
## /opt/innotune/update/cache/InnoTune/update.sh                              ##
##                                                                            ##
################################################################################
################################################################################

echo "running update002"

# installs wiringPi for tinkerboard
cd /root/
git clone http://github.com/TinkerBoard/gpio_lib_c --depth 1 GPIO_API_for_C
cd GPIO_API_for_C/
sudo chmod +x build

sudo ./build

# make dirs and files for gpio
sudo mkdir /opt/innotune/settings/gpio
sudo mkdir /opt/innotune/settings/gpio/mute

sudo printf "0" > /opt/innotune/settings/gpio/coding
sudo printf "0;0;0" > /opt/innotune/settings/gpio/fan_options

for i in $(seq -f "%02g" 1 10)
do
    sudo touch "/opt/innotune/settings/gpio/mute/p$i"
    sudo printf "0;0" > "/opt/innotune/settings/gpio/mute/state$i"
done

sudo chmod -R 777 /opt/innotune/settings

# kill programs so we can override the old ones
killall fanreg
killall mutecard
killall readCoding

# copy gpio files to the right place
sudo cp /var/www/src/gpio/fanreg /var/www/src/fanreg
sudo cp /var/www/src/gpio/mutecard /var/www/src/mutecard
sudo cp /var/www/src/gpio/readCoding /var/www/src/readCoding

sudo cp /var/www/src/gpio/fanreg.sh /var/www/fanreg.sh
sudo cp /var/www/src/gpio/mutereg.sh /var/www/mutereg.sh

sudo chmod -R 777 /var/www

sudo cp /opt/innotune/update/cache/InnoTune/dhclient.conf /etc/dhcp/dhclient.conf

sudo touch /opt/innotune/settings/lmswa.txt
sudo chmod 777 /opt/innotune/settings/lmswa.txt

sudo touch /var/www/InnoControl/log/lmswa.log
sudo chmod 777 /var/www/InnoControl/log/lmswa.log

ln -s /var/log /var/www/InnoControl/log/syslogs

touch /opt/innotune/settings/knxcallbacks
chmod 777 /opt/innotune/settings/knxcallbacks

touch /var/log/knxlistener
chmod 777 /var/log/knxlistener
touch /var/log/knxcallback
chmod 777 /var/log/knxcallback

sudo cp /opt/innotune/update/cache/InnoTune/logrotate.conf /etc/logrotate.conf
sudo cp /opt/innotune/update/cache/InnoTune/journald.conf /etc/systemd/journald.conf

sudo rm -R /var/log/journal
sudo rm -R /var/log/*.gz
sudo rm -R /var/log.hdd/journal
sudo rm -R /var/log.hdd/*.gz
rm -R /root/build/

# would save about 0.5 GB
# sudo apt-get remove -y chromium-browser
# sudo apt-get remove -y thunderbird
# sudo apt-get remove -y libreoffice-core
# sudo apt-get remove -y libreoffice-common

sudo cp /opt/innotune/update/cache/InnoTune/importantUpdate.txt /opt/innotune/settings/importantUpdate.txt
chmod 777 /opt/innotune/settings/importantUpdate.txt

is_added=$(crontab -l | grep checkImportantUpdates.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "30 3 * * * sudo /var/www/update/checkImportantUpdates.sh"; } | crontab -
fi

is_added=$(crontab -l | grep check_linein.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "*/30 * * * * /var/www/check_linein.sh"; } | crontab -
fi

is_added=$(crontab -l | grep hutdown_hook.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "0 */4 * * * sudo /var/www/shutdown_hook.sh"; } | crontab -
fi

cp /opt/innotune/update/cache/InnoTune/custom_shutdown.service /etc/systemd/system/custom_shutdown.service
chmod 777 /etc/systemd/system/custom_shutdown.service
systemctl enable custom_shutdown.service

# install strongswan and add all config files etc.
# sudo apt-get -y install vpnc
sudo echo "0" > /opt/innotune/settings/vpn.txt
chmod 777 /opt/innotune/settings/vpn.txt

sudo apt-get -y install strongswan
cp /opt/innotune/update/cache/InnoTune/vpn/ipsec.conf /etc/ipsec.conf
cp /opt/innotune/update/cache/InnoTune/vpn/ipsec.secrets /etc/ipsec.secrets

unzip -o /opt/innotune/update/cache/InnoTune/vpn/certs.zip -d /opt/
cp /opt/ca.crt /etc/ipsec.d/cacerts/ca.crt
cp /opt/innotune.crt /etc/ipsec.d/certs/innotune.crt
cp /opt/innotune.key /etc/ipsec.d/private/innotune.key
rm /opt/ca.crt
rm /opt/innotune.crt
rm /opt/innotune.key

# install exfat support and configure usbmount to support this fs
sudo apt-get install -y exfat-fuse exfat-utils

cp /opt/innotune/update/cache/InnoTune/usbmount.conf /etc/usbmount/usbmount.conf
cp /opt/innotune/update/cache/InnoTune/usbmount.rules /etc/udev/rules.d/usbmount.rules
cp /opt/innotune/update/cache/InnoTune/usbmount.service /etc/systemd/system/usbmount@.service

systemctl daemon-reload
systemctl enable usbmount@.service

# set new update count and reference to newer update file
sudo echo "2" > /opt/innotune/settings/update_cnt.txt
echo "100% - finished update" > /opt/innotune/settings/updatestatus.txt
# /var/www/update/update003.sh
