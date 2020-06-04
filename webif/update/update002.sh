#!/bin/bash
echo "running update002"

# add commands here

#installs wiringPi for tinkerboard
cd /root/
git clone http://github.com/TinkerBoard/gpio_lib_c --depth 1 GPIO_API_for_C
cd GPIO_API_for_C/
sudo chmod +x build

sudo ./build

#make dirs and files for gpio
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

#copy gpio files to the right place
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

#would save about 0.5 GB
#sudo apt-get remove -y chromium-browser
#sudo apt-get remove -y thunderbird
#sudo apt-get remove -y libreoffice-core
#sudo apt-get remove -y libreoffice-common

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

sudo apt-get -y install vpnc
cp /opt/innotune/update/cache/InnoTune/vpnc.conf /etc/vpnc.conf
chmod 777 /etc/vpnc.conf
sudo echo "0" > /opt/innotune/settings/vpn.txt
chmod 777 /opt/innotune/settings/vpn.txt

# set new update count and reference to newer update file
sudo echo "2" > /opt/innotune/settings/update_cnt.txt
echo "100% - finished update" > /opt/innotune/settings/updatestatus.txt
#/var/www/update/update003.sh
