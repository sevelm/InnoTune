#!/bin/bash

##########################################################
# Config



##########################################################

# Abfrage ob Normalbetrieb (1) oder Geteilt (2)
USB_DEV=$(cat /opt/innotune/settings/settings_player/dev$1.txt | head -n1  | tail -n1)

if [ $USB_DEV == 1 ]; then  
MPD=$(amixer -c $1 get mpd_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then 
MPD=$(amixer -c $1 get mpdli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then  
SQUEEZEBOX=$(amixer -c $1 get squeeze_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then 
SQUEEZEBOX=$(amixer -c $1 get squeezeli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then  
AIRPLAY=$(amixer -c $1 get airplay_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then 
AIRPLAY=$(amixer -c $1 get airplayli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi

if [ $USB_DEV == 1 ]; then  
LINEIN=$(amixer -c $1 get LineIn_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
fi
if [ $USB_DEV == 2 ]; then 
LINEIN=$(amixer -c $1 get LineInli_$1 | grep Front | head -n3 | tail -n1 | cut -d[ -f2 | cut -d% -f1)
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





















