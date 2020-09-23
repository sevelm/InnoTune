#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              check_linein.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   11.12.2019                                                    ##
## Edited   :   27.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script iterates over all 10 amps and checks if a zone is playing      ##
## longer than 2 hours. If playtime exceeds the limit the line-in will be     ##
## restarted with the set_linein.sh script.                                   ##
##                                                                            ##
## A cronjob excecutes this script every 30 minutes.                          ##
## Cronjob Entry:                                                             ##
## */30 * * * * /var/www/check_linein.sh                                      ##
##                                                                            ##
################################################################################
################################################################################


################################################################################
#                                                                              #
#                                check function                                #
#                                                                              #
################################################################################
check() {
    # init vars
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    card_out="$1"
    USB_DEV=$(cat /opt/innotune/settings/settings_player/dev$card_out.txt | head -n1  | tail -n1)
    zone2=""
    modus=""
    PID1="0"
    PID2="0"
    modus2=""
    card_in2=""

    # check type (1x stereo/2x mono)
    # get line-in process ids and source (input) card
    if [ $USB_DEV == 1 ]; then
        PID1=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n1 | tail -n1)
        PID2="0"
        card_in=$(cat /opt/innotune/settings/status_line-in/line-in$card_out.txt | head -n3 | tail -n1)
    elif [ $USB_DEV == 2 ]; then
        zone2="2"
        PID2="0"
        PID1=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n1 | tail -n1)
        if [ "$PID1" != "0" ]; then
            modus="li"
            card_in=$(cat /opt/innotune/settings/status_line-in/line-inli$card_out.txt | head -n3 | tail -n1)
        fi

        PID2=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n1 | tail -n1)
        if [ "$PID2" != "0" ]; then
            modus2="re"
            card_in2=$(cat /opt/innotune/settings/status_line-in/line-inre$card_out.txt | head -n3 | tail -n1)
        fi
    fi

    echo "$i: dev: $USB_DEV out: $card_out in: $card_in z: $zone2 m: $modus m2: $modus2 in2: $card_in2"

    # check line-in process runtime
    if [ "$PID1" != "0" ]; then
        runtime=$(ps -o etimes= -p "$PID1")
        echo "$datetime card $i/P1: runtime: $runtime, out: $card_out in: $card_in" >> /var/www/checkprocesses.log
        # running longer than 2 hours
        if [ "$runtime" -ge "7200" ]; then
            # restart line-in
            /var/www/set_linein.sh "$card_out" "$card_in" "$zone2" "$modus"
        fi
    fi

    if [ "$PID2" != "0" ]; then
        if [ "$zone2" == "2" ]; then
            runtime=$(ps -o etimes= -p "$PID2")
            echo "$datetime card $i/P2: runtime: $runtime, out: $card_out in: $card_in" >> /var/www/checkprocesses.log
            # running longer than 2 hours
            if [ "$runtime" -ge "7200" ]; then
                # restart line-in
                /var/www/set_linein.sh "$card_out" "$card_in2" "$zone2" "$modus2"
            fi
        fi
    fi
}

################################################################################
#                                                                              #
#                                     main                                     #
#                                                                              #
################################################################################

# check line-in for card 01 to 09
for i in {1..9}
do
    check "0$i"
done

# check line-in for card 10
check "10"
