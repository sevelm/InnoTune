#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             knxeditradio.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   19.04.2019                                                    ##
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
## This script adds, updates or deletes radio entries from the knx radio list ##
## file.                                                                      ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 action (0 = delete, 1 = edit, 2 = add)                                  ##
## $2 old line number ($1 = 0 or 1), new entry ($1 = 2)                       ##
## $3 updated entry ($1 = 1)                                                  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

if [[ "$1" -eq 0 ]]; then
    # delete entry
    sed -i "\;|$2|*;d" /opt/innotune/settings/knxradios.txt
    # renumber remaining entries
    count=$((1))
    while IFS='\n' read -r line; do
        IFS='|' read -ra nextdata <<< "$line"
        # escape problematic characters for sed
        txt=$(echo "${nextdata[2]}|${nextdata[3]}" | sed -e 's/\&/\\&/g')

        sed -i "${count}s[.*[|${count}|${txt}[" /opt/innotune/settings/knxradios.txt
        count=$(($count+1))
    done < "/opt/innotune/settings/knxradios.txt"
elif [[ "$1" -eq 1 ]]; then
    # update edited entry
    count=$((1))
    while IFS='\n' read -r line; do
        IFS='|' read -ra nextdata <<< "$line"
        if [ "${nextdata[1]}" -eq "$2" ]; then
            # escape problematic characters for sed
            txt=$(echo "${3}" | sed -e 's/\&/\\&/g')

            sed -i "${count}s[.*[${txt}[" /opt/innotune/settings/knxradios.txt
        fi
        count=$(($count+1))
    done < "/opt/innotune/settings/knxradios.txt"
elif [[ "$1" -eq 2 ]]; then
    count=$((1))
    while IFS='\n' read -r line; do
        count=$(($count+1))
    done < "/opt/innotune/settings/knxradios.txt"
    echo "|$count|$2" >> /opt/innotune/settings/knxradios.txt
fi
