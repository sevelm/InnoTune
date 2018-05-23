#!/bin/bash
i=$((0))
datetime=$(date '+%d-%m-%Y %H:%M:%S')
if [ -f /opt/innotune/settings/mapping.txt ]; then
  while IFS='' read -r line || [[ -n "$line" ]]; do
    sndc=$(echo $line | cut -d ";" -f1)
    check=$(aplay -l | grep $sndc)
    if [[ -z $check ]]; then
      i=$(($i+1))
      datetime=$(date '+%d-%m-%Y %H:%M:%S')
      echo "$datetime Soundkarte: $sndc nicht verbunden" >> /var/www/checkprocesses.log
    fi
  done < /opt/innotune/settings/mapping.txt
fi
if [[ "$i" -gt "0" ]]; then
  datetime=$(date '+%d-%m-%Y %H:%M:%S')
  echo "$datetime Soundkartencheck mit $i Fehler(n) abgeschlossen" >> /var/www/checkprocesses.log
fi
