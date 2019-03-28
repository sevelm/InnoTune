#!/bin/bash
datetime=$(date '+%d-%m-%Y %H:%M:%S')
param=$(($2))
if [[ "$param" -eq "1" ]]; then
  echo "$datetime Soundkarte ($1) wurde angesteckt" >> /var/www/checkprocesses.log
else
  echo "$datetime Soundkarte ($1) wurde entfernt!" >> /var/www/checkprocesses.log
fi
