#!/bin/bash
check=$(cat /var/www/InnoControl/log/validate.log | grep "$1;failed" | wc -l)
if [[ $check -eq 1 ]]; then
  log=$(grep -v "php5.6-gd;failed" /var/www/InnoControl/log/validate.log)
  echo "$log" > /var/www/InnoControl/log/validate.log
  echo "1" > /opt/innotune/settings/validate.txt
  sudo dpkg --configure -a
  sudo apt-get install -f -y "$1"
  if [[ $(dpkg-query -W -f='${Status}\n' "$1" | grep installed | wc -l) -eq 1 ]]; then
    echo "$1;installed" >> /var/www/InnoControl/log/validate.log
    echo "$1;installed"
  else
    echo "$1;failed" >> /var/www/InnoControl/log/validate.log
    echo "$1;failed"
  fi
else
  echo "invalid or already installed package $1"
fi
