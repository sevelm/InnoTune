#!/bin/bash
rm -R /var/www/InnoControl/log/old/*
cd /var/www/InnoControl/log
for i in *; do
  if [[ "$i" != "old" ]]; then
    rm $i
  fi
done
chmod -R 777 /var/www/InnoControl/log
rm /var/www/checkprocesses.log
touch /var/www/checkprocesses.log
chmod 777 /var/www/checkprocesses.log
