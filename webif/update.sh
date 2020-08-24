#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                 update.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##              Severin Elmecker                                              ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script clears the update cache and clones the new software from the   ##
## github repository. Executes the newly downloaded update script to          ##
## continue the update-chain.                                                 ##
##                                                                            ##
##                                Update-Chain                                ##
## 1)  /var/www/update.sh                                                     ##
## 2)  /opt/innotune/update/cache/InnoTune/update.sh                          ##
## 3)  /opt/innotune/update/cache/InnoTune/webif/update/generic.sh            ##
## 4+) /var/www/update/updateXXX.sh                                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/update/checkImportantUpdates.sh                                   ##
##                                                                            ##
################################################################################
################################################################################

echo "0% - starting update" > /opt/innotune/settings/updatestatus.txt
# clear update directory
rm -r /opt/innotune/update/*
# fix dpkg errors if there are any
sudo DEBIAN_FRONTEND=noninteractive dpkg --configure -a --force-confdef --force-confold
sudo DEBIAN_FRONTEND=noninteractive apt-get -f -y install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold"
# install git (should be installed already)
apt-get -y install git
# create cache directory and move to it
mkdir /opt/innotune/update/cache
cd /opt/innotune/update/cache
echo "5% - cloning git repo" > /opt/innotune/settings/updatestatus.txt
# the 'updatetest' branch is for testing an update before release
# git clone --single-branch --branch updatetest https://github.com/sevelm/InnoTune.git
# clone the git repository and set permissions
git clone https://github.com/sevelm/InnoTune.git
sudo chmod -R 0777 InnoTune
chmod +x InnoTune/update.sh

# Run the downloaded update script
./InnoTune/update.sh > /var/www/InnoControl/log/update.log
echo "1" > /opt/innotune/settings/validate.txt
