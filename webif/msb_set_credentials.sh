#!/bin/bash
echo "trying to connect to port 9090..."
con=0
while [[ $(echo exit | nc -w 300 localhost 9090 | wc -l) -eq 0 ]] && [[ $con -lt 20 ]]
do
  echo "failed to connect, trying again..."
  sleep 30
  con=$((con+1))
done
if [[ $con -lt 20 ]]; then
  echo "successfully connected"
  echo "trying to set credentials..."
  response=$(printf "setsncredentials squeeze@innotune.at innotune\n" | nc -q 120 localhost 9090)
  count=0
  while [[ $(grep validated%3A1 <<< $response | wc -l) -eq 0 ]] && [[ $count -lt 5 ]]
  do
    echo "failed to set credentials, trying again..."
    response=$(printf "setsncredentials squeeze@innotune.at innotune\n" | nc -q 120 localhost 9090)
    count=$((count+1))
  done
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    echo "$datetime LMS Logindaten wurden gesetzt" >> /var/www/checkprocesses.log
  echo "successfully set credentials"
else
  datetime=$(date '+%d-%m-%Y %H:%M:%S')
  echo "$datetime Fehler beim setzen der LMS Logindaten" >> /var/www/checkprocesses.log
fi
