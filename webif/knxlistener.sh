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
                    printf "${data[2]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                else
                    echo "http"
                    curl "${data[2]}" 2>&1 /dev/null &
                fi
            else
                echo "cmd: ${data[3]}, with hex: $hexval,dec : $dec"
                if [[ "${data[3]}" == 00:00:00:* ]]; then
                    printf "${data[3]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                else
                    curl "${data[3]}" 2>&1 /dev/null &
                fi
            fi
        elif [ "${data[1]}" -eq "1" ]; then
            hexval="${array[5]}${array[6]}"
            dec=$(/var/www/knxhexconverter.sh "$hexval")
            c="${data[2]}"
            cmd="${c/<v>/$dec}"
            echo "cmd: $cmd, with hex: $hexval, dec: $dec"
            if [[ "$cmd" == 00:00:00:* ]]; then
                printf "$cmd\nexit\n" | nc localhost 9090 2>&1 /dev/null &
            else
                curl "$cmd" 2>&1 /dev/null &
            fi
        elif [ "${data[1]}" -eq "2" ]; then
            hexval="${array[5]}"
            dec=$((16#$hexval))

            if [ "${data[2]}" -eq "1" ]; then
                mode=$(printf "${data[3]} mode ?\nexit\n" | nc localhost 9090 | cut -d ' ' -f 3)
                echo "mode: $mode, with hex: $hexval, dec: $dec"
                if [ "$dec" -eq "1" ]; then
                    cr=$(cat /opt/innotune/settings/knxcurrentradio.txt)
                    # checks if LMS is already playing something
                    # if playing: play next radio, else start last selected radio
                    if [ "$mode" == "play" ]; then
                        echo "play next radio"
                        nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                        IFS='|' read -ra nextdata <<< "$nextquery"
                        if [ "$cr" -ge "${nextdata[1]}" ]; then
                            cr="1"
                        else
                            cr=$(($cr+1))
                        fi
                    else
                        echo "play last selected radio"
                        nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                        IFS='|' read -ra nextdata <<< "$nextquery"
                        if [ "$cr" -gt "${nextdata[1]}" ]; then
                            cr="1"
                        fi
                    fi
                    printf "$cr" > /opt/innotune/settings/knxcurrentradio.txt
                    radioquery=$(cat /opt/innotune/settings/knxradios.txt | grep "|$cr|")
                    IFS='|' read -ra radiodata <<< "$radioquery"
                    echo "cr: $cr, ${radiodata[1]}"

                    #format amp id
                    amp="${data[4]}"
                    if [ "${data[4]}" -lt "10" ]; then
                        amp="0${data[4]}"
                    fi

                    #format vol for stereo/splitted output
                    if [ "${data[5]}" -eq "0" ]; then
                        vol="30"
                    elif [ "${data[5]}" -eq "1" ]; then
                        vol="30/0"
                    elif [ "${data[5]}" -eq "2" ]; then
                        vol="0/30"
                    fi

                    #encode text for curl command
                    txt=$(echo "${radiodata[2]}" | sed -e 's/:/%3A/g' \
                                                       -e 's/ /%20/g' \
                                                       -e 's/\&/%26/g' \
                                                       -e "s/'/%27/g" \
                                                       -e 's/!/%21/g' \
                                                       -e 's/?/%3F/g' \
                                                       -e 's/,/%2C/g' \
                                                       -e 's/\//%2F/g' \
                                                       -e 's/Ä/%C3%84/g' \
                                                       -e 's/Ö/%C3%96/g' \
                                                       -e 's/Ü/%C3%9C/g' \
                                                       -e 's/ä/%C3%A4/g' \
                                                       -e 's/ö/%C3%B6/g' \
                                                       -e 's/ü/%C3%BC/g' \
                                                       -e 's/ß/%C3%9F/g')

                    # send curl request for TTS and nc request for LMS command
                    curl "localhost/api/tts.php?text=$txt&speed=-3&vol_$amp=$vol&vol_all=0&vol_back=0&noqueue" 2>&1 /dev/null &
                    printf "${data[3]} playlist play ${radiodata[3]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                else
                    echo "stop radio"
                    printf "${data[3]} power 0\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                fi
            else
                # 0 = stop, 1 = volume down, 9 = volume up
                # stores PID from voltrigger script to stop it at request
                if [ "$dec" -ge "2" ]; then
                    echo "value: $dec, start vol up trigger"
                    vtpid=$(/var/www/InnoControl/api/voltrigger.sh "${data[3]}" "u" > /dev/null 2>&1 & echo $!)
                    echo "pid: $vtpid /opt/innotune/settings/knx_vol_${data[3]}.txt"
                    printf "$vtpid" > "/opt/innotune/settings/knx_vol_${data[3]}.txt"
                elif [ "$dec" -eq "1" ]; then
                    echo "value: $dec, start vol down trigger"
                    vtpid=$(/var/www/InnoControl/api/voltrigger.sh "${data[3]}" "d" > /dev/null 2>&1 & echo $!)
                    echo "pid: $vtpid /opt/innotune/settings/knx_vol_${data[3]}.txt"
                    printf "$vtpid" > "/opt/innotune/settings/knx_vol_${data[3]}.txt"
                else
                    echo "value: $dec, stop vol trigger"
                    vtpid=$(cat "/opt/innotune/settings/knx_vol_${data[3]}.txt")
                    kill "$vtpid"
                fi
            fi
        fi
    fi
done
