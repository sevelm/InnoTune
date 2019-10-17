#!/bin/bash
function sigterm_listener()
{
    sd=$(date)
    echo "[$sd] knxcallback terminated" >> /var/log/knxcallback
    exit
}

function sigint_listener()
{
    sd=$(date)
    echo "[$sd] knxcallback exited" >> /var/log/knxcallback
    exit
}

trap sigterm_listener TERM
trap sigint_listener INT

echo "-----------------------------------" >> /var/log/knxcallback
sd=$(date)
echo "[$sd] started knxcallback" >> /var/log/knxcallback
while read line ; do
    sd=$(date)
    IFS=' ' read -ra array <<< "$line"
    if [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "open" ]] || [[ "${array[1]}" = "play" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} ${array[2]} $callback" >> /var/log/knxcallback
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool on ip: ${cba[1]} ($line)" >> /var/log/knxcallback
            knxtool on ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "pause" ]] || [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "stop" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} ${array[2]} $callback" >> /var/log/knxcallback
        if [[ -n "$callback" ]]; then
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool off ip: ${cba[1]} ($line)" >> /var/log/knxcallback
            knxtool off ip: "${cba[1]}"
        fi
    elif [[ "${array[1]}" = "playlist" ]] && [[ "${array[2]}" = "pause" ]];then
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} pause $callback" >> /var/log/knxcallback
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
        data="${array[0]}"
        mac=${data//%3A/:}
        callback=$(grep "$mac" /opt/innotune/settings/knxcallbacks)
        echo "[$sd] ${array[1]} $callback" >> /var/log/knxcallback
        if [[ -n "$callback" ]]; then
            #scale 0-100 to 0-256
            vol_dec=$(echo "${array[4]}*255/100" | bc)
            #convert dec to hex
            vol=$(printf "%x\n" "$vol_dec")
            IFS='|' read -ra cba <<< "$callback"
            echo "[$sd] knxtool write ip: ${cba[2]} $vol" >> /var/log/knxcallback
            knxtool write ip: "${cba[2]}" "$vol"
        fi
    fi
done
