tempraw=$(/var/www/readcputemp.sh)
if [[ "$tempraw" -ne "1" ]]; then
  temp=$(($tempraw/1000))
  datetime=$(date '+%d-%m-%Y %H:%M:%S')
  if [[ "$tempraw" -gt "79000" ]]; then
    echo "$datetime Achtung! CPU-Temperatur beträgt $temp°C" >> /var/www/checkprocesses.log
  elif [[ "$tempraw" -gt "59000" ]]; then
    echo "$datetime Warnung! CPU-Temperatur beträgt $temp°C" >> /var/www/checkprocesses.log
  fi
fi
