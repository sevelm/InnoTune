#!/bin/bash
echo "0% - starting knx installation" > /opt/innotune/settings/updatestatus.txt

sudo cp /opt/innotune/update/cache/InnoTune/knxd_0.14.29-5_armhf.deb /root/knxd_0.14.29-5_armhf.deb
sudo cp /opt/innotune/update/cache/InnoTune/knxd-tools_0.14.29-5_armhf.deb /root/knxd-tools_0.14.29-5_armhf.deb

sudo chmod -R 777 /root/knxd_0.14*
sudo rm -R /root/knxd_0.12*

#install knxd
echo "30% - installing knxd" > /opt/innotune/settings/updatestatus.txt

#if old knx is installed save knxd.conf file
sudo mv /etc/knxd.conf /root/savedknxd.conf

#dependencies
sudo apt-get install libev4

cd /root/
sudo dpkg -i knxd_0.14*.deb knxd-tools_0.14*.deb

sudo cp /root/savedknxd.conf /etc/knxd.conf

echo "95% - knx installed, creating knx settings files" > /opt/innotune/settings/updatestatus.txt
#knx rules
sudo cp /opt/innotune/update/cache/InnoTune/70-knxd.rules /etc/udev/rules.d/70-knxd.rules

#knx data
sudo touch /opt/innotune/settings/knxcmd.txt
sudo touch /opt/innotune/settings/knxrun.txt
sudo touch /opt/innotune/settings/knx.txt
sudo chmod 777 -R /opt/innotune/settings/knx*

echo "100% - finished knx installation" > /opt/innotune/settings/updatestatus.txt
