#!/bin/bash
while read line ; do
    echo "receviced: $line"
    IFS=' ' read -ra array <<< "$line"
    item="${array[4]}"
    addr="${item%?}"
    query=$(cat /opt/innotune/settings/knxcmd.txt | grep "$addr|")
    if [ ! -z "$query" ]; then
        IFS='|' read -ra data <<< "$query"
        if [ "${data[1]}" -eq "0" ]; then
            hexval="${array[5]}"
            dec=$((16#$hexval))
            if [[ "$dec" -eq "1" ]]; then
                echo "cmd: ${data[2]}, with hex: $hexval,dec : $dec"
                if [[ "${data[2]}" == 00:00:00:* ]]; then
                    echo "tcp"
                    printf "${data[2]}\nexit\n" | nc localhost 9090 &> /dev/null
                else
                    echo "http"
                    curl "${data[2]}" &> /dev/null
                fi
            else
                echo "cmd: ${data[3]}, with hex: $hexval,dec : $dec"
                if [[ "${data[3]}" == 00:00:00:* ]]; then
                    printf "${data[3]}\nexit\n" | nc localhost 9090 &> /dev/null
                else
                    curl "${data[3]}" &> /dev/null
                fi
            fi
        else
            hexval="${array[5]}${array[6]}"
            dec=$(/var/www/knxhexconverter.sh "$hexval")
            c="${data[2]}"
            cmd="${c/<v>/$dec}"
            echo "cmd: $cmd, with hex: $hexval, dec: $dec"
            if [[ "$cmd" == 00:00:00:* ]]; then
                printf "$cmd\nexit\n" | nc localhost 9090 &> /dev/null
            else
                curl "$cmd" &> /dev/null
            fi
        fi
    fi
done
