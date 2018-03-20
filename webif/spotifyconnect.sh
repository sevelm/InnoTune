#!/bin/sh

# $1 = player nummer
# $2 = ein/aus
# $3 = li/re

if [ "$PLAYER_EVENT" = "start" ]; then
  a=1
elif [ "$PLAYER_EVENT" = "stop" ] || [ "$PLAYER_EVENT" = "pause" ]; then
  a=0
fi
echo $a > /opt/innotune/settings/status_shairplay/status_shairplay$3$1.txt
