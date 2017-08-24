#!/bin/bash

##########################################################
# Config
##########################################################

card_out=$1              # Nummer der Soundkarte die Angesteuert werden soll > 01,02,03,04,05 usw.....
card_in=$2               # Welcher LineIn von welcher Soundkarte soll wiedergegeben werden > 01,02,03,04,05 usw.....
zone2=$3                 # Geteilter Modus, beide Zonen Ansteuern

PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird
PID2=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n2 | tail -n1)               #Abfrage ob Line bereits ausgegeben wird

# Line-In beenden wenn aktiv
if [ $PID1 != "0" ] || [ $PID2 != "0" ]; then  
      kill $PID1 $PID2
      echo -e "0\n""0\n" > /opt/innotune/settings/status_line-in/line-in$card_out.txt
      amixer -c $card_out set MuteIfLineIn_$card_out 100%
      amixer -c $card_out set MuteIfLineInli_$card_out 100%
      amixer -c $card_out set MuteIfLineInre_$card_out 100%
fi


# Line-In wiedergeben
if [[ $card_in ]]; then      
      amixer -c $card_out set MuteIfLineIn_$card_out 1%
      amixer -c $card_out set MuteIfLineInli_$card_out 1%
      amixer -c $card_out set MuteIfLineInre_$card_out 1%



        if [ $zone2 == "2" ]; then
              # csound.csd file für den linken kanal erzeugen und starten
              rm /var/www/create_linein/csound_lineinli$card_in.csd
              sed -e s/XXX/$card_in/g /var/www/create_linein/csound_lineinXXX.csd  >> /var/www/create_linein/csound_linein$card_in$card_out.csd
              sed -e s/YYY/li$card_out/g /var/www/create_linein/csound_linein$card_in$card_out.csd >> /var/www/create_linein/csound_lineinli$card_in.csd
              newPID1=$(csound /var/www/create_linein/csound_lineinli$card_in.csd > /dev/null 2>&1 & echo $!)
              rm /var/www/create_linein/csound_linein$card_in$card_out.csd

              # csound.csd file für den rechten kanal erzeugen und starten
              rm /var/www/create_linein/csound_lineinre$card_in.csd
              sed -e s/XXX/$card_in/g /var/www/create_linein/csound_lineinXXX.csd  >> /var/www/create_linein/csound_linein$card_in$card_out.csd
              sed -e s/YYY/re$card_out/g /var/www/create_linein/csound_linein$card_in$card_out.csd >> /var/www/create_linein/csound_lineinre$card_in.csd
              newPID2=$(csound /var/www/create_linein/csound_lineinre$card_in.csd > /dev/null 2>&1 & echo $!)
              rm /var/www/create_linein/csound_linein$card_in$card_out.csd

        else   
              rm /var/www/create_linein/csound_linein$card_in.csd 
              sed -e s/XXX/$card_in/g /var/www/create_linein/csound_lineinXXX.csd  >> /var/www/create_linein/csound_linein$card_in$card_out.csd
              sed -e s/YYY/$card_out/g /var/www/create_linein/csound_linein$card_in$card_out.csd >> /var/www/create_linein/csound_linein$card_in.csd
              newPID1=$(csound /var/www/create_linein/csound_linein$card_in.csd > /dev/null 2>&1 & echo $!)
              rm /var/www/create_linein/csound_linein$card_in$card_out.csd
        fi     
      echo -e "$newPID1\n""$newPID2\n""$card_in\n" > /opt/innotune/settings/status_line-in/line-in$card_out.txt
fi

exit 0