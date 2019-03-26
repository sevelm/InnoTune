#!/bin/bash
##installed=$(dpkg -s knxd | grep Status)
##if [ "$installed" == "Status: install ok installed" ]; then
##    exit 1
##fi
#
# https://github.com/knxd/knxd
#
cd /root/

# fix dpkg errors if there are any
sudo dpkg --configure -a
sudo apt-get -f -y install

# first, install build tools and dependencies
sudo apt-get -y install git-core build-essential

# now get the source code
git clone https://github.com/knxd/knxd.git

# now build+install knxd
cd knxd
git checkout master

#fix dependencies here
sudo apt-get -y install debhelper/xenial-backports
sudo apt-get -y install libsystemd-dev
sudo apt-get -y install libev-dev
sudo apt-get -y install cmake

#fix unmet dependencies before this step
dpkg-buildpackage -b -uc

#install knxd
cd ..
sudo dpkg -i knxd_*.deb knxd-tools_*.deb

#knx rules
sudo cp /opt/innotune/update/cache/InnoTune/70-knxd.rules /etc/udev/rules.d/70-knxd.rules

#knx data
sudo touch /opt/innotune/settings/knxcmd.txt
sudo touch /opt/innotune/settings/knxrun.txt
sudo touch /opt/innotune/settings/knx.txt
sudo chmod 777 -R /opt/innotune/settings/knx*
