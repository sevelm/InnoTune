#!/bin/bash

LOG=$(cat /opt/innotune/settings/logports)
DUMP=$(ps cax | grep tcpdump | wc -l)
if [ $LOG == "1" ]; then
  if [ $DUMP -ne 2 ]; then
    killall tcpdump
    sudo /var/www/logports.sh "1" &> /dev/null
  fi
elif [ $LOG == "0" ]; then
  if [ $DUMP -ne 0 ]; then
    sudo /var/www/logports.sh "0" &> /dev/null
  fi
fi
