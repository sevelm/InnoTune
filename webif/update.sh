#!/bin/bash

##########################################################
#			 Config
# InnoTune Update über Github
##########################################################


echo "0% - starting update" > /opt/innotune/settings/updatestatus.txt
rm -r /opt/innotune/update/*   ### Update-Ordner leeren
apt-get install git
mkdir /opt/innotune/update/cache
cd /opt/innotune/update/cache
echo "5% - cloning git repo" > /opt/innotune/settings/updatestatus.txt
#git clone --single-branch --branch updatetest https://github.com/sevelm/InnoTune.git
git clone https://github.com/sevelm/InnoTune.git
sudo chmod -R 0777 InnoTune
chmod +x InnoTune/update.sh
./InnoTune/update.sh > /var/www/InnoControl/log/update.log
echo "1" > /opt/innotune/settings/validate.txt
