#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            show_soundcard.sh                               ##
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
## This script checks if usb audio soundcards are active and creates the      ##
## current mapping file and saves the results.                                ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 card number (0 = all cards)                                             ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# clearing mapping_current file
> /opt/innotune/settings/mapping_current.txt

# card 1 could be hdmi audio, therefore we alternatively check if it has id "rockchipminiarm",
# if it isn't a InnoAMP
if [ "$(cat /proc/asound/card1/stream0 | grep "Burr")" ]; then
    if [[ "$(cat /proc/asound/card1/id | cut -c 5- | cut -c 1)" = "C" ]]; then
        if [[ -f /opt/innotune/settings/mapping.txt ]]; then
            hnm=$(tail -1 /opt/innotune/settings/mapping.txt | cut -d ";" -f1 | cut -c 5-)
            hnmc=$(tail -1 /opt/innotune/settings/mapping_current.txt | cut -d ";" -f1 | cut -c 5-)
            if [[ "$hnmc" -gt "$hnm" ]]; then
                cn=$((10#$hnmc+1))
            else
                cn=$((10#$hnm+1))
            fi
            if [[ "$cn" -ne "10" ]]; then
                cn="0$cn"
            fi
            eval $(echo CARD$cn='aktiv')
        else
            CARD01=aktiv
            cn="01"
        fi
    else
        cn=$(cat /proc/asound/card1/id | cut -c 5-)
        eval $(echo CARD$cn='aktiv')
    fi
    devpath=$(udevadm info -a -p $(udevadm info -q path -n /dev/snd/pcmC1D0c) | grep "looking at device" | cut -d "'" -f2 | rev | cut -c11- | rev)
    echo "sndc$cn;$devpath" >> /opt/innotune/settings/mapping_current.txt
elif [ "$(cat /proc/asound/card1/id | grep "rockchipminiarm")" ]; then
    CARD01=aktiv
fi

# card 2 to 9
for i in {2..9}
do
    if [[ "$(cat /proc/asound/card$i/id | cut -c 5- | cut -c 1)" = "C" ]]; then
        if [[ -f /opt/innotune/settings/mapping.txt ]]; then
            hnm=$(tail -1 /opt/innotune/settings/mapping.txt | cut -d ";" -f1 | cut -c 5-)
            hnm=$((10#$hnm+1))
            hnmc=$(tail -1 /opt/innotune/settings/mapping_current.txt | cut -d ";" -f1 | cut -c 5-)
            hnmc=$((10#$hnmc+1))
            if [[ "$hnmc" -gt "$hnm" ]]; then
                cn=$((10#$hnmc))
            else
                cn=$((10#$hnm))
            fi
            if [[ "$cn" -ne "10" ]]; then
                cn="0$cn"
            fi
            eval $(echo CARD$cn='$(cat /proc/asound/card'$i'/stream0 | grep "Burr")')
        else
            cn="0$i"
            eval $(echo CARD$cn='$(cat /proc/asound/card'$i'/stream0 | grep "Burr")')
        fi
    else
        cn=$(cat /proc/asound/card$i/id | cut -c 5-)
        eval $(echo CARD$cn='$(cat /proc/asound/card'$i'/stream0 | grep "Burr")')
    fi

    devpath=$(udevadm info -a -p $(udevadm info -q path -n /dev/snd/pcmC"$i"D0c) | grep "looking at device" | cut -d "'" -f2 | rev | cut -c11- | rev)
    if [ $devpath ]; then
        echo "sndc$cn;$devpath" >> /opt/innotune/settings/mapping_current.txt
    fi
done

# card 10
i=$((10#$i+1))
if [[ "$(cat /proc/asound/card10/id | cut -c 5- | cut -c 1)" = "C" ]]; then
    if [[ -f /opt/innotune/settings/mapping.txt ]]; then
        hnm=$(tail -1 /opt/innotune/settings/mapping.txt | cut -d ";" -f1 | cut -c 5-)
        hnm=$((10#$hnm+1))
        hnmc=$(tail -1 /opt/innotune/settings/mapping_current.txt | cut -d ";" -f1 | cut -c 5-)
        hnmc=$((10#$hnmc+1))
        if [[ "$hnmc" -gt "$hnm" ]]; then
            cn=$((10#$hnmc))
        else
            cn=$((10#$hnm))
        fi
        if [[ "$cn" -ne "10" ]]; then
            cn="0$cn"
        fi
        eval $(echo CARD$cn='$(cat /proc/asound/card10/stream0 | grep "Burr")')
    else
        CARD10=aktiv
        cn="10"
    fi
else
    cn=$(cat /proc/asound/card10/id | cut -c 5-)
    eval $(echo CARD$cn='aktiv')
fi

devpath=$(udevadm info -a -p $(udevadm info -q path -n /dev/snd/pcmC10D0c) | grep "looking at device" | cut -d "'" -f2 | rev | cut -c13- | rev)
if [ $devpath ]; then
    echo "sndc$cn;$devpath" >> /opt/innotune/settings/mapping_current.txt
fi

# output to /opt/innotune/settings/usb_dev.txt
echo -e "${CARD01:+1}\n""${CARD02:+1}\n""${CARD03:+1}\n""${CARD04:+1}\n""${CARD05:+1}\n""${CARD06:+1}\n""${CARD07:+1}\n""${CARD08:+1}\n""${CARD09:+1}\n""${CARD10:+1}\n" > /opt/innotune/settings/usb_dev.txt

# output to shell with param $1 (allowed param values are 0-10)
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
