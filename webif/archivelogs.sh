#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               archivelogs.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   20.04.2018                                                    ##
## Edited   :   27.07.2020                                                    ##
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
## This script creates a tar file of yesterdays spotify, airplay, etc.        ##
## instance log files.                                                        ##
## Also removes tar files that are older than a week or exceed 200MB,         ##
## to keep the storage from getting full of logs.                             ##
##                                                                            ##
## A cronjob excecutes this script everyday at 3:45                           ##
## Cronjob Entry:                                                             ##
## 45 3 * * * /var/www/archivelogs.sh                                         ##
##                                                                            ##
################################################################################
################################################################################

# get yesterdays date
date=$(date -d "yesterday 13:00" '+%d-%m-%Y')

# create log directory for yesterdays logs
sudo mkdir /var/www/InnoControl/log/old
sudo chmod 777 /var/www/InnoControl/log/old
sudo mkdir /var/www/InnoControl/log/old/$date

# move yesterdays logs into the new directory
for i in /var/www/InnoControl/log/*$date; do
  echo "moving $i to /opt/innotune/log/old/$date"
  sudo mv $i /var/www/InnoControl/log/old/$date
done

# compress the directory of yesterdays logs and delete the directory
cd /var/www/InnoControl/log/old
sudo tar -zcf $date.tar.gz $date
sudo rm -r /var/www/InnoControl/log/old/$date

# remove compressed logs from a week ago
date=$(date -d "7 day ago" '+%d-%m-%Y')
sudo rm /var/www/InnoControl/log/old/$date.tar.gz

# remove all files in old log directory that exceed a file size of 200MB
cd /var/www/InnoControl/log/old
for i in *; do
  size=$(du -k $i | cut -f1)
  echo "size of $i is $size"
  if [[ "$size" -gt "200000" ]]; then
    sudo rm $i
  fi
done
