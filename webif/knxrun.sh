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
  killall knxcallback.sh
  systemctl restart knxd

  sleep 5
  knxtool groupsocketlisten local: | /var/www/knxlistener.sh 2>&1 /dev/null &
  sleep 15
  printf "listen\n" | nc -q 87000 localhost 9090 | /var/www/knxcallback.sh 2>&1 /dev/null &
else
  echo "0" > /opt/innotune/settings/knxrun.txt
  killall knxtool
  killall knxlistener.sh
  killall knxcallback.sh
  systemctl stop knxd
fi
