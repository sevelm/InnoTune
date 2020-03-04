#!/bin/bash
function sigterm_listener()
{
    sd=$(date)
    echo "[$sd] knxlistener terminated" >> /var/log/knxlistener
    exit
}

function sigint_listener()
{
    sd=$(date)
    echo "[$sd] knxlistener exited" >> /var/log/knxlistener
    exit
}

trap sigterm_listener TERM
trap sigint_listener INT

echo "-----------------------------------" >> /var/log/knxlistener
sd=$(date)
echo "[$sd] started knxlistener" >> /var/log/knxlistener
while read line ; do
    sd=$(date)
    start=$(($(date +%s%N)/1000000))
    IFS=' ' read -ra array <<< "$line"
    item="${array[4]}"
    addr="${item%?}"
    query=$(grep "$addr|" /opt/innotune/settings/knxcmd.txt)
    if [ ! -z "$query" ]; then
        echo "[$sd] receviced: $line" >> /var/log/knxlistener
        IFS='|' read -ra data <<< "$query"
        if [ "${data[1]}" -eq "0" ]; then
            hexval="${array[5]}"
            dec=$((16#$hexval))
            if [[ "$dec" -eq "1" ]]; then
                echo "[$sd] cmd: ${data[2]}, with hex: $hexval,dec : $dec" >> /var/log/knxlistener

                if [[ "${data[2]}" == 00:00:00:* ]]; then
                    echo "[$sd] tcp" >> /var/log/knxlistener
                    # if play next is active check if currently playing
                    if [[ "${data[4]}" = "true" ]]; then
                        IFS=' ' read -ra macdata <<< "${data[2]}"
                        mode=$(printf "${macdata[0]} mode ?\nexit\n" | nc localhost 9090 | cut -d ' ' -f 3)
                        echo "[$sd] mode: $mode" >> /var/log/knxlistener

                        #format amp id
                        amp="${data[5]}"
                        if [ "${data[5]}" -lt "10" ]; then
                            amp="0${data[5]}"
                        fi

                        #format vol for stereo/splitted output
                        if [ "${data[6]}" -eq "0" ]; then
                            vol="30"
                        elif [ "${data[6]}" -eq "1" ]; then
                            vol="30/0"
                        elif [ "${data[6]}" -eq "2" ]; then
                            vol="0/30"
                        fi

                        kxrfile="$amp${data[6]}"

                        if [[  -f "/opt/innotune/settings/knxcurrentradio$kxrfile.txt" ]]; then
                            cr=$(cat "/opt/innotune/settings/knxcurrentradio$kxrfile.txt")
                        else
                            touch "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                            chmod 777 "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                            echo "1" > "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                            cr=$(cat "/opt/innotune/settings/knxcurrentradio$kxrfile.txt")
                        fi
                        # checks if LMS is already playing something
                        # if playing: play next radio, else start last selected radio
                        if [ "$mode" == "play" ]; then
                            echo "[$sd] play next radio" >> /var/log/knxlistener
                            nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                            IFS='|' read -ra nextdata <<< "$nextquery"
                            if [ "$cr" -ge "${nextdata[1]}" ]; then
                                cr="1"
                            else
                                cr=$(($cr+1))
                            fi
                        else
                            echo "[$sd] play last selected radio" >> /var/log/knxlistener
                            nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                            IFS='|' read -ra nextdata <<< "$nextquery"
                            if [ "$cr" -gt "${nextdata[1]}" ]; then
                                cr="1"
                            fi
                        fi
                        printf "$cr" > "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                        radioquery=$(cat /opt/innotune/settings/knxradios.txt | grep "|$cr|")
                        IFS='|' read -ra radiodata <<< "$radioquery"
                        echo "[$sd] cr: $cr, ${radiodata[1]}" >> /var/log/knxlistener

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
                        printf "${macdata[0]} playlist play ${radiodata[3]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &

                    else
                        printf "${data[2]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                    fi
                elif [[ "${data[2]}" == voltrigger* ]]; then
                    IFS=' ' read -ra cmddata <<< "${data[2]}"
                    mac="${cmddata[1]}"
                    direction="${cmddata[2]}"

                    vtpid=$(/var/www/InnoControl/api/voltrigger.sh "$mac" "$direction" > /dev/null 2>&1 & echo $!)
                    echo "[$sd] pid: $vtpid /opt/innotune/settings/knx_vol_$mac.txt" >> /var/log/knxlistener
                    printf "$vtpid" > "/opt/innotune/settings/knx_vol_$mac.txt"
                else
                    echo "[$sd] http" >> /var/log/knxlistener
                    curl "${data[2]}" 2>&1 /dev/null &
                fi
            else
                echo "[$sd] cmd: ${data[3]}, with hex: $hexval,dec : $dec" >> /var/log/knxlistener
                if [[ "${data[3]}" == 00:00:00:* ]]; then
                    printf "${data[3]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                elif [[ "${data[3]}" == voltrigger* ]]; then
                    IFS=' ' read -ra cmddata <<< "${data[3]}"
                    mac="${cmddata[1]}"
                    direction="${cmddata[2]}"

                    vtpid=$(cat "/opt/innotune/settings/knx_vol_${mac}.txt")
                    kill "$vtpid"
                else
                    curl "${data[3]}" 2>&1 /dev/null &
                fi
            fi
        elif [ "${data[1]}" -eq "1" ]; then
            hexval="${array[5]}${array[6]}"
            dec=$(/var/www/knxhexconverter.sh "$hexval")
            c="${data[2]}"
            cmd="${c/<v>/$dec}"
            echo "[$sd] cmd: $cmd, with hex: $hexval, dec: $dec" >> /var/log/knxlistener
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
                echo "[$sd] mode: $mode, with hex: $hexval, dec: $dec" >> /var/log/knxlistener
                if [ "$dec" -eq "1" ]; then
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

                    kxrfile="$amp${data[6]}"

                    if [[  -f "/opt/innotune/settings/knxcurrentradio$kxrfile.txt" ]]; then
                        cr=$(cat "/opt/innotune/settings/knxcurrentradio$kxrfile.txt")
                    else
                        touch "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                        chmod 777 "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                        echo "1" > "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                        cr=$(cat "/opt/innotune/settings/knxcurrentradio$kxrfile.txt")
                    fi
                    # checks if LMS is already playing something
                    # if playing: play next radio, else start last selected radio
                    playing="0"
                    if [ "$mode" == "play" ]; then
                        echo "[$sd] play next radio" >> /var/log/knxlistener
                        playing="1"
                        nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                        IFS='|' read -ra nextdata <<< "$nextquery"
                        if [ "$cr" -ge "${nextdata[1]}" ]; then
                            cr="1"
                        else
                            cr=$(($cr+1))
                        fi
                    else
                        echo "[$sd] play last selected radio" >> /var/log/knxlistener
                        nextquery=$(tail /opt/innotune/settings/knxradios.txt -n1)
                        IFS='|' read -ra nextdata <<< "$nextquery"
                        if [ "$cr" -gt "${nextdata[1]}" ]; then
                            cr="1"
                        fi
                    fi
                    printf "$cr" > "/opt/innotune/settings/knxcurrentradio$kxrfile.txt"
                    radioquery=$(cat /opt/innotune/settings/knxradios.txt | grep "|$cr|")
                    IFS='|' read -ra radiodata <<< "$radioquery"
                    echo "[$sd] cr: $cr, ${radiodata[1]}" >> /var/log/knxlistener

                    if [[ $playing == "1" ]]; then
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
                    fi
                    printf "${data[3]} playlist play ${radiodata[3]}\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                else
                    echo "[$sd] stop radio" >> /var/log/knxlistener
                    printf "${data[3]} power 0\nexit\n" | nc localhost 9090 2>&1 /dev/null &
                fi
            else
                # 0 = stop, 1 = volume down, 9 = volume up
                # stores PID from voltrigger script to stop it at request
                if [ "$dec" -ge "9" ]; then
                    echo "[$sd] value: $dec, start vol up trigger" >> /var/log/knxlistener
                    vtpid=$(/var/www/InnoControl/api/voltrigger.sh "${data[3]}" "u" > /dev/null 2>&1 & echo $!)
                    echo "[$sd] pid: $vtpid /opt/innotune/settings/knx_vol_${data[3]}.txt"
                    printf "$vtpid" > "/opt/innotune/settings/knx_vol_${data[3]}.txt"
                elif [ "$dec" -eq "1" ]; then
                    echo "[$sd] value: $dec, start vol down trigger" >> /var/log/knxlistener
                    vtpid=$(/var/www/InnoControl/api/voltrigger.sh "${data[3]}" "d" > /dev/null 2>&1 & echo $!)
                    echo "[$sd] pid: $vtpid /opt/innotune/settings/knx_vol_${data[3]}.txt" >> /var/log/knxlistener
                    printf "$vtpid" > "/opt/innotune/settings/knx_vol_${data[3]}.txt"
                else
                    echo "[$sd] value: $dec, stop vol trigger" >> /var/log/knxlistener
                    vtpid=$(cat "/opt/innotune/settings/knx_vol_${data[3]}.txt")
                    kill "$vtpid"
                fi
            fi
        fi
        echo "[$sd] time: $((($(date +%s%N)/1000000)-$start))ms" >> /var/log/knxlistener
    fi
done

sd=$(date)
echo "[$sd] knxlistener exited" >> /var/log/knxlistener
