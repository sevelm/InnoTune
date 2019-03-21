#!/bin/bash

##########################################################
#			 Config
# InnoTune Update über Github
##########################################################


rm -r /opt/innotune/update/*   ### Update-Ordner leeren
apt-get install git
mkdir /opt/innotune/update/cache
cd /opt/innotune/update/cache
git clone --single-branch --branch updatetest https://github.com/JHoerbst/InnoTune.git
sudo chmod -R 0777 InnoTune
chmod +x InnoTune/update.sh
./InnoTune/update.sh > /var/www/InnoControl/log/update.log
echo "1" > /opt/innotune/settings/validate.txt
