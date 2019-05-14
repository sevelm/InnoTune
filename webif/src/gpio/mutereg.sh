#!/bin/bash
options=$(cat "/opt/innotune/settings/gpio/mute/state$1")
IFS=';' read -ra data <<< "$options"

case "$1" in
    01) pin="7";;
    02) pin="2";;
    03) pin="22";;
    04) pin="24";;
    05) pin="6";;
    06) pin="27";;
    07) pin="28";;
    08) pin="29";;
    *) echo "invalid parameter"
       exit 1;;
esac

if [[ "${data[0]}" -eq 0 ]]; then
    /var/www/src/mutecard "$pin" 2>&1 /dev/null &
    printf "$!" > "/opt/innotune/settings/gpio/mute/p$1"
else
    # kill only card specific process
    pid=$(cat "/opt/innotune/settings/gpio/mute/p$1")
    kill "$pid"
    gpio mode "$pin" OUT
    gpio write "$pin" "${data[1]}"
fi
