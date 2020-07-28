#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              set_linein.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   27.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Severin Elmecker                                              ##
##              Alexander Elmecker                                            ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script handles the line-in process starting and stopping.             ##
## Stops a activ process when, a new process for the same output will be      ##
## spawned.                                                                   ##
## For stopping the card in parameter must be given but is not used.          ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 card out                                                                ##
## $2 card in                                                                 ##
## $3 zone                                                                    ##
## $4 mode                                                                    ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# parameters
# number of soundcard for output
card_out=$1
# number of soundcard for input
card_in=$2
# playback mode
zone2=$3
# card mode
modus=$4

# if card out is splitted, filter card number and mode
if [[ $card_out == *"li"* || $card_out == *"re"* ]]; then
	echo "It's there!"
	modus=${card_out:2};
	card_out=${card_out:0:2};
fi


# retrieve process ids of possible running line-in processes
USB_DEV=$(cat /opt/innotune/settings/settings_player/dev$card_out.txt | head -n1  | tail -n1)

if [ $USB_DEV == 1 ]; then
    PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)
    PID2=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n2 | tail -n1)
elif [ $USB_DEV == 2 ]; then
    if [ "$modus" = "li" ]; then
        PID1=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n1 | tail -n1)
        PID2=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n2 | tail -n1)
    elif [ "$modus" = "re" ]; then
        PID1=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n1 | tail -n1)
        PID2=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n2 | tail -n1)
    else
        PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)
        PID2=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n2 | tail -n1)
    fi
fi

# stop line-in if active
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


# play line-in
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
