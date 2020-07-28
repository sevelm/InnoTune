#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             checkprocesses.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   22.03.2018                                                    ##
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
## This script checks if all instances of shairplay, spotify and squeezelite  ##
## are running. Each types will be checked indepentently and if a process is  ##
## not running the zone is logged and all process of a type are restarted by  ##
## the set_player.sh script.                                                  ##
##                                                                            ##
## A cronjob excecutes this script every minute.                              ##
## Cronjob Entry:                                                             ##
## * * * * * /var/www/checkprocesses.sh                                       ##
##                                                                            ##
################################################################################
################################################################################

# get server uptime and update running
uptimeraw=$(awk '{print $1}' /proc/uptime | cut -d "." -f1)
uptime=$(($uptimeraw / 60))
updating=$(ps cax | grep update.sh | wc -l)

# only check if server is up for more than 5 minutes
# and no update is currently running
if [[ $uptime -gt 5 ]] && [[ $updating -eq 0 ]]; then
    is_running=$(ps cax | grep set_player | wc -l)
    # continue if set_player is not running
    if [[ $is_running -eq 0 ]]; then
        datetime=$(date '+%d-%m-%Y %H:%M:%S')
        filepath="/opt/innotune/settings"

        # count all saved shairplay process ids
        fshair="$filepath/p_shairplay"
        count=0
        while IFS='' read -r line || [[ -n "$line" ]]; do
            count=$((count+1))
        done < $fshair
        echo "$count $(ps cax | grep shairport-sync | wc -l)"

        # count all running shairplay instances
        pc=$(ps cax | grep shairport-sync | wc -l)
        # check unequal process ids and running instances
        if [[ $pc -ne $count ]]; then
            # log not running instance zone name
            for (( c=1; c<=$pc; c++ ))
            do
                zonename=$(ps ax | grep shairport-sync | sed "${c}q;d" | grep -Po "(?<=-a ).*?(?= --on)")
                echo "$datetime Shairplay: $zonename läuft" >> /var/www/checkprocesses.log
            done
            echo "$datetime $pc von $count Shairplay-Instanzen laufen" >> /var/www/checkprocesses.log

            # check if shairport-sync is installed
            if [[ $pc -eq 0 ]] && [[ ! $(command -v shairport-sync) ]]; then
                # try to install and disable service afterwards
                sudo apt-get install -y shairport-sync
                sudo systemctl stop shairport-sync
                sudo systemctl disable shairport-sync
            fi
            # restart all shairplay instances
            /var/www/set_player.sh 1
        fi

        # count all saved spotify process ids
        fspot="$filepath/p_spotify"
        count=0
        while IFS='' read -ra line || [[ -n "$line" ]]; do
            count=$((count+1))
        done < $fspot

        echo "$count $(ps cax | grep librespot | wc -l)"
        # count all running spotify instances
        pc=$(ps cax | grep librespot | wc -l)
        # check unequal process ids and running instances
        if [[ $pc -ne $count ]]; then
            # log not running instance zone name
            for (( c=1; c<=$pc; c++ ))
            do
                lc=$(($c*2))
                zonename=$(ps ax | grep librespot | sed "${lc}q;d" | grep -Po "(?<=--name ).*?(?= --cache)")
                echo "$datetime Spotify: $zonename läuft" >> /var/www/checkprocesses.log
            done
            echo "$datetime $pc von $count Spotify-Instanzen laufen" >> /var/www/checkprocesses.log
            # restart all spotify instances
            /var/www/set_player.sh 3
        fi

        # count all saved squeezelite process ids
        fsqueeze="$filepath/p_squeeze"
        count=0
        while IFS='' read -r line || [[ -n "$line" ]]; do
            count=$((count+1))
        done < $fsqueeze

        echo "$count $(ps cax | grep squeezelite-arm | wc -l)"
        # count all running squeezelite instances
        pc=$(ps cax | grep squeezelite-arm | wc -l)
        # check unequal process ids and running instances
        if [[ $pc -ne $count ]]; then
            # log not running instance zone name
            for (( c=1; c<=$pc; c++ ))
            do
                zonename=$(ps ax | grep squeezelite | sed "${c}q;d" | grep -Po "(?<=-n ).*?(?= -m)")
                echo "$datetime Squeezelite: $zonename läuft" >> /var/www/checkprocesses.log
            done
            echo "$datetime $pc von $count Squeezelite-Instanzen laufen" >> /var/www/checkprocesses.log
            # restart all squeezelite instances
            /var/www/set_player.sh 2
        fi
    fi
fi
