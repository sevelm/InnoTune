#!/bin/bash

LOW=$(amixer -D equal$1 get "00. 31 Hz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
MID=$(amixer -D equal$1 get "04. 500 Hz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
HIGH=$(amixer -D equal$1 get "09. 16 kHz" | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)

echo "$LOW;$MID;$HIGH"
