#!/bin/bash

##########################################################
# Config


##########################################################

#CARD01=$(aplay -l | grep "card 1")
#CARD02=$(aplay -l | grep "card 2")
#CARD03=$(aplay -l | grep "card 3")
#CARD04=$(aplay -l | grep "card 4")
#CARD05=$(aplay -l | grep "card 5")
#CARD06=$(aplay -l | grep "card 6")
#CARD07=$(aplay -l | grep "card 7")
#CARD08=$(aplay -l | grep "card 8")
#CARD09=$(aplay -l | grep "card 9")
#CARD10=$(aplay -l | grep "card 10")

CARD01=$(cat /proc/asound/card1/stream0 | grep "Burr")
CARD02=$(cat /proc/asound/card2/stream0 | grep "Burr")
CARD03=$(cat /proc/asound/card3/stream0 | grep "Burr")
CARD04=$(cat /proc/asound/card4/stream0 | grep "Burr")
CARD05=$(cat /proc/asound/card5/stream0 | grep "Burr")
CARD06=$(cat /proc/asound/card6/stream0 | grep "Burr")
CARD07=$(cat /proc/asound/card7/stream0 | grep "Burr")
CARD08=$(cat /proc/asound/card8/stream0 | grep "Burr")
CARD09=$(cat /proc/asound/card9/stream0 | grep "Burr")
CARD10=$(cat /proc/asound/card10/stream0 | grep "Burr")

echo -e "${CARD01:+1}\n""${CARD02:+1}\n""${CARD03:+1}\n""${CARD04:+1}\n""${CARD05:+1}\n""${CARD06:+1}\n""${CARD07:+1}\n""${CARD08:+1}\n""${CARD09:+1}\n""${CARD10:+1}\n" > /opt/innotune/settings/usb_dev.txt

case "$1" in
	0) echo "${CARD01:+1};${CARD02:+1};${CARD03:+1};${CARD04:+1};${CARD05:+1};${CARD06:+1};${CARD07:+1};${CARD08:+1};${CARD09:+1};${CARD10:+1}";;
        1) echo ${CARD01:+1};;
        2) echo ${CARD02:+1};;
        3) echo ${CARD03:+1};;
        4) echo ${CARD04:+1};;
        5) echo ${CARD05:+1};;
        6) echo ${CARD06:+1};;
        7) echo ${CARD07:+1};;
        8) echo ${CARD08:+1};;
        9) echo ${CARD09:+1};;
        10) echo ${CARD10:+1};;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac
