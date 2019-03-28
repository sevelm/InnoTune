#!/bin/bash

##########################################################
# Config
##########################################################

card_out=$1              # Nummer der Soundkarte die Angesteuert werden soll > 01,02,03,04,05 usw.....
card_in=$2               # Welcher LineIn von welcher Soundkarte soll wiedergegeben werden > 01,02,03,04,05 usw.....
zone2=$3                 # Geteilter Modus, beide Zonen Ansteuern
modus=$4

if [[ $card_out == *"li"* || $card_out == *"re"* ]]; then
	echo "It's there! "
	modus=${card_out:2};
	card_out=${card_out:0:2};
fi


USB_DEV=$(cat /opt/innotune/settings/settings_player/dev$card_out.txt | head -n1  | tail -n1)


if [ $USB_DEV == 1 ]; then
    PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
    PID2=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n2 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
elif [ $USB_DEV == 2 ]; then
     if [ "$modus" = "li" ]; then
        PID1=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n1 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
        PID2=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n2 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
	elif [ "$modus" = "re" ]; then
        PID1=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n1 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
        PID2=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n2 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
	else
     	PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
        PID2=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n2 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
	fi
fi

# Line-In beenden wenn aktiv
if [ "$PID1" != "0" ] || [ "$PID2" != "0" ]; then
      kill $PID1 $PID2
      echo -e "0\n""0\n" > /opt/innotune/settings/status_line-in/line-in$modus$card_out.txt

	  if [ "$modus" = "li" ]; then
	  	amixer -c sndc$card_out set MuteIfLineInli_$card_out 100%
	  elif [ "$modus" = "re" ]; then
	  	amixer -c sndc$card_out set MuteIfLineInre_$card_out 100%
	  else
  		amixer -c sndc$card_out set MuteIfLineIn_$card_out 100%
      	amixer -c sndc$card_out set MuteIfLineInli_$card_out 100%
      	amixer -c sndc$card_out set MuteIfLineInre_$card_out 100%
	  fi
fi


# Line-In wiedergeben
if [[ $card_in ]]; then
        if [ "$zone2" == "2" ]; then
			modus=$4;
			if [ "$modus" = "li" ]; then
				amixer -c sndc$card_out set MuteIfLineInli_$card_out 1%
	        	newPID1=$(arecord -f S16_LE -c2 -r44100 -d 0 -D plug:dsnoop$card_in | aplay -B 1 -D LineInli$card_out > /dev/null 2>&1 & echo $!)
	        	echo -e "$newPID1\n""$newPID2\n""$card_in\n" > /opt/innotune/settings/status_line-in/line-inli$card_out.txt
			elif [ "$modus" == "re" ]; then
				amixer -c sndc$card_out set MuteIfLineInre_$card_out 1%
	        	newPID1=$(arecord -f S16_LE -c2 -r44100 -d 0 -D plug:dsnoop$card_in | aplay -B 1 -D LineInre$card_out > /dev/null 2>&1 & echo $!)
				echo -e "$newPID1\n""$newPID2\n""$card_in\n" > /opt/innotune/settings/status_line-in/line-inre$card_out.txt
			else
				amixer -c sndc$card_out set MuteIfLineInli_$card_out 1%
				amixer -c sndc$card_out set MuteIfLineInre_$card_out 1%

	    		newPID1=$(arecord -f S16_LE -c2 -r44100 -d 0 -D plug:dsnoop$card_in | aplay -B 1 -D LineInli$card_out > /dev/null 2>&1 & echo $!)
	    		newPID2=$(arecord -f S16_LE -c2 -r44100 -d 0 -D plug:dsnoop$card_in | aplay -B 1 -D LineInre$card_out > /dev/null 2>&1 & echo $!)
				echo -e "$newPID1\n""$newPID2\n""$card_in\n" > /opt/innotune/settings/status_line-in/line-in$card_out.txt
			fi
        else
			amixer -c sndc$card_out set MuteIfLineIn_$card_out 1%
			amixer -c sndc$card_out set MuteIfLineInli_$card_out 1%
			amixer -c sndc$card_out set MuteIfLineInre_$card_out 1%

			newPID1=$(arecord -f S16_LE -c2 -r44100 -d 0 -D plug:dsnoop$card_in | aplay -B 1 -D LineIn$card_out  > /dev/null 2>&1 & echo $!)
			echo -e "$newPID1\n""$newPID2\n""$card_in\n" > /opt/innotune/settings/status_line-in/line-in$card_out.txt
        fi
fi

exit 0
