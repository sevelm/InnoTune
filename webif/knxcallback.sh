#!/bin/bash
while read line ; do
    IFS=' ' read -ra array <<< "$line"
    if [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "open" ]] || [[ "${array[1]}" = "play" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "${array[1]} $callback"
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "knxtool on ip: ${cba[1]} ($line)"
            knxtool on ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "pause" ]] || [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "stop" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "${array[1]} $callback"
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "knxtool off ip: ${cba[1]} ($line)"
            knxtool off ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "pause" ]];then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "${array[1]} $callback"
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            if [[ "${array[3]}" = "1" ]]; then
                echo "knxtool off ip: ${cba[1]} ($line)"
                knxtool off ip: "${cba[1]}"
            else
                echo "knxtool on ip: ${cba[1]} ($line)"
                knxtool on ip: "${cba[1]}"
            fi
        fi
    elif [[ "${array[1]}" = "prefset" ]] && [[ "${array[2]}" = "server" ]] && [[ "${array[3]}" = "volume" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "${array[1]} $callback"
        if [[ -n "$callback" ]]; then
            vol=$(printf "%x\n" "${array[4]}")
            IFS='|' read -ra cba <<< "$callback"
            echo "knxtool write ip: ${cba[2]} $vol"
            knxtool write ip: "${cba[2]}" "$vol"
        fi
    fi
done
