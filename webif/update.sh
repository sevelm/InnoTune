#!/bin/bash

##########################################################
#			 Config
# InnoTune Update über Github
##########################################################


echo "0% - starting update" > /opt/innotune/settings/updatestatus.txt
rm -r /opt/innotune/update/*   ### Update-Ordner leeren
# fix dpkg errors if there are any
sudo DEBIAN_FRONTEND=noninteractive dpkg --configure -a --force-confdef --force-confold
sudo DEBIAN_FRONTEND=noninteractive apt-get -f -y install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"
apt-get -y install git
mkdir /opt/innotune/update/cache
cd /opt/innotune/update/cache
echo "5% - cloning git repo" > /opt/innotune/settings/updatestatus.txt
#git clone --single-branch --branch updatetest https://github.com/sevelm/InnoTune.git
git clone https://github.com/sevelm/InnoTune.git
sudo chmod -R 0777 InnoTune
chmod +x InnoTune/update.sh
./InnoTune/update.sh > /var/www/InnoControl/log/update.log
echo "1" > /opt/innotune/settings/validate.txt
