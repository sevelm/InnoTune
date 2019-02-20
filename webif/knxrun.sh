#!/bin/bash
var="$1"
if [ "$#" -eq 0 ]; then
  run=$(cat /opt/innotune/settings/knxrun.txt)
  if [[ "$run" -eq 1 ]]; then
    var="0"
  else
    var="1"
  fi
fi
if [[ "$var" -eq 1 ]]; then
  echo "1" > /opt/innotune/settings/knxrun.txt
  killall knxtool
  killall knxlistener.sh
  systemctl restart knxd
  knxtool groupsocketlisten local: | /var/www/knxlistener.sh 2>&1 /dev/null &
else
  echo "0" > /opt/innotune/settings/knxrun.txt
  killall knxtool
  killall knxlistener.sh
  systemctl stop knxd
fi
