#!/bin/bash
# makes a tar file of yesterdays instance log files
# removes tar files that are older than a week
# script runs in crontab everyday at 3:45
date=$(date -d "yesterday 13:00" '+%d-%m-%Y')

sudo mkdir /var/www/InnoControl/log/old
sudo chmod 777 /var/www/InnoControl/log/old
sudo mkdir /var/www/InnoControl/log/old/$date
for i in /var/www/InnoControl/log/*$date; do
  echo "moving $i to /opt/innotune/log/old/$date"
  sudo mv $i /var/www/InnoControl/log/old/$date
done
cd /var/www/InnoControl/log/old
sudo tar -zcf $date.tar.gz $date
sudo rm -r /var/www/InnoControl/log/old/$date

date=$(date -d "7 day ago" '+%d-%m-%Y')
sudo rm /var/www/InnoControl/log/old/$date.tar.gz

cd /var/www/InnoControl/log/old
for i in *; do
  size=$(du -k $i | cut -f1)
  echo "size of $i is $size"
  if [[ "$size" -gt "500000" ]]; then
    sudo rm $i
  fi
done
