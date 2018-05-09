#!/bin/bash
cd /var/www/InnoControl/log
datetime=$(date '+%d-%m-%Y %H:%M:%S')
for i in *; do
  if [[ "$i" != "old" ]]; then
    size=$(du -k $i | cut -f1)
    echo "size of $i is $size"
    if [[ "$size" -gt "500000" ]]; then
      sudo rm $i
      sudo touch $i
      size=$(($size/1000))
      echo "$datetime Logdatei $i gelöscht ($size MB)" >> /var/www/checkprocesses.log
      pid=$(ps aux | grep -v grep | grep $i | awk '{print $2}')
      kill $pid
    fi
  fi
done
