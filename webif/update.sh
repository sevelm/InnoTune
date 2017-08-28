#!/bin/bash

##########################################################
# Config


##########################################################


#wget http://innotune.at/update_innotune/odroid/update.sh -P /opt/innotune/update/
#sudo chmod 777 /opt/innotune/update/update.sh
#/opt/innotune/update/update.sh

rm -r /opt/innotune/update/*   ### Update-Ordner leeren
apt-get install git
cd /opt/innotune/update/cache
git clone https://github.com/JHoerbst/InnoTune.git
sudo chmod -R 0777 InnoTune
chmod +x InnoTune/update.sh
./InnoTune/update.sh

exit 0