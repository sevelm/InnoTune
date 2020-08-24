#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              lmslistener.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   19.06.2019                                                    ##
## Edited   :   28.07.2020                                                    ##
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
## This script reads all incoming data from a tcp connection to the lms via   ##
## the 9090 port. (a listen command is send so the lms returns everything     ##
## that is happening on the lms.)                                             ##
## If streams are started which are to our knowledge not played by the        ##
## customer, playback is stopped and the playlist will be cleared.            ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

while read line ; do
    IFS=' ' read -ra array <<< "$line"
    if [[ "${array[1]}" = "playlist" ]]; then
        if [[ "${array[2]}" = "open" ]] || [[ "${array[2]}" = "play" ]] || [[ "${array[2]}" = "add" ]] ||
           [[ "${array[2]}" = "insert" ]]; then
            echo "array 0: ${array[0]}"
            echo "array 1: ${array[1]}"
            echo "array 2: ${array[2]}"
            echo "array 3: ${array[3]}"
            if [[ "${array[3]}" = "http%3A%2F%2Fstream.radiocorp.nl%2Fweb11_mp3" ]] ||
               [[ "${array[3]}" = "http%3A%2F%2F19993.live.streamtheworld.com%2FWEB11_MP3_SC%3F" ]]; then
                data="${array[0]}"
                mac=${data//%3A/:}
                printf "$mac playlist clear\nexit\n" | nc localhost 9090
            fi
            pretty=${line//%3A/:}
            pretty=${pretty//%2F//}
            datetime=$(date '+%d-%m-%Y %H:%M:%S')
            echo "$datetime $pretty" >> /var/www/InnoControl/log/lmswa.log
        fi
    fi
done
