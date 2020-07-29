#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             knxcallback.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   09.07.2019                                                    ##
## Edited   :   29.07.2020                                                    ##
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
## This script listens on the lms cmd interface and filters data about zone   ##
## playback and volume adjustments and checks them against a list of knx      ##
## group addresses that are interested in this information and sends it to    ##
## them via the knxd interface.                                               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/knxrun.sh                                                         ##
##                                                                            ##
################################################################################
################################################################################

# function listens for sigterm event to log script termination
function sigterm_listener()
{
    sd=$(date)
    echo "[$sd] knxcallback terminated" >> /var/log/knxcallback
    exit
}

# function listens for sigint event to log script execution stopped
function sigint_listener()
{
    sd=$(date)
    echo "[$sd] knxcallback exited" >> /var/log/knxcallback
    exit
}

# add traps for signals
trap sigterm_listener TERM
trap sigint_listener INT

echo "-----------------------------------" >> /var/log/knxcallback
sd=$(date)
echo "[$sd] started knxcallback" >> /var/log/knxcallback
# endless loop gets data feed through a pipe
while read line ; do
    sd=$(date)
    IFS=' ' read -ra array <<< "$line"
    if [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "open" ]] || [[ "${array[1]}" = "play" ]]; then
        # a lms zone started to play
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} ${array[2]} $callback" >> /var/log/knxcallback

        # if a callback is found, send the knx message
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool on ip: ${cba[1]} ($line)" >> /var/log/knxcallback
            knxtool on ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "pause" ]] || [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "stop" ]]; then
        # a lms zone was paused/stopped
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} ${array[2]} $callback" >> /var/log/knxcallback

        # if a callback is found, send the knx message
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool off ip: ${cba[1]} ($line)" >> /var/log/knxcallback
            knxtool off ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "pause" ]];then
        # a lms zone was paused/unpaused
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} pause $callback" >> /var/log/knxcallback

        # if a callback is found, send the knx message
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            if [[ "${array[3]}" = "1" ]]; then
                echo "[$sd] knxtool off ip: ${cba[1]} ($line)" >> /var/log/knxcallback
                knxtool off ip: "${cba[1]}"
            else
                echo "[$sd] knxtool on ip: ${cba[1]} ($line)" >> /var/log/knxcallback
                knxtool on ip: "${cba[1]}"
            fi
        fi
    elif [[ "${array[1]}" = "prefset" ]] && [[ "${array[2]}" = "server" ]] && [[ "${array[3]}" = "volume" ]]; then
        # a lms zone adjusted its volume
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} $callback" >> /var/log/knxcallback

        # if a callback is found, send the knx message
        if [[ -n "$callback" ]]; then
            # scale 0-100 to 0-256
            vol_dec=$(echo "${array[4]}*255/100" | bc)
            # convert dec to hex
            vol=$(printf "%x\n" "$vol_dec")
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool write ip: ${cba[2]} $vol" >> /var/log/knxcallback
            knxtool write ip: "${cba[2]}" "$vol"
        fi
    fi
done
