#!/bin/bash

##########################################################
# Config



##########################################################

# removes leading zeros from card out (Amp 08 and 09 wont work otherwise)
card=$(echo $1 | sed 's/^0*//')

# Abfrage ob Normalbetrieb (1) oder Geteilt (2)
USB_DEV=$(cat /opt/innotune/settings/settings_player/dev$1.txt | head -n1  | tail -n1)

split=""
if [ $USB_DEV == 2 ]; then
	split="li"
fi

# check if card starts with sndc prefix
amixer -c sndc$1 get mpd_$1 &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
	echo "sndc$1 not found, exit code: $code"
	amixer -c $card get mpd_$split$1 &> /dev/null
	code=$(($?))
	if [ $code -gt 0 ]; then
	  echo "card: $1 not found, exit code: $code"
	else
	  echo "card: $1 found"
	fi
	prefix="$card"
else
  prefix="sndc$1"
fi

echo "used card: $prefix"

if [ $USB_DEV == 1 ]; then
MPD=$(amixer -c $prefix get mpd_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then
MPD=$(amixer -c $prefix get mpdli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then
SQUEEZEBOX=$(amixer -c $prefix get squeeze_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then
SQUEEZEBOX=$(amixer -c $prefix get squeezeli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then
AIRPLAY=$(amixer -c $prefix get airplay_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then
AIRPLAY=$(amixer -c $prefix get airplayli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then
LINEIN=$(amixer -c $prefix get LineIn_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then
LINEIN=$(amixer -c $prefix get LineInli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

sudo alsactl store

echo -e "$MPD\n""$SQUEEZEBOX\n""$AIRPLAY\n""$LINEIN\n" > /opt/innotune/settings/status_vol_equal/dev$1.txt

case "$2" in
	all) echo "$MPD;$SQUEEZEBOX;$AIRPLAY;$LINEIN";;
        mpd) echo $MPD;;
        squeeze) echo $SQUEEZEBOX;;
        airplay) echo $AIRPLAY;;
        linein) echo $LINEIN;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac
