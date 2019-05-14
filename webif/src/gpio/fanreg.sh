#!/bin/bash
options=$(cat /opt/innotune/settings/gpio/fan_options)
IFS=';' read -ra data <<< "$options"

if [[ "${data[0]}" -eq 0 ]]; then
    /var/www/src/fanreg 2>&1 /dev/null &
else
    killall fanreg
    if [[ "${data[1]}" -eq 0 ]]; then
        gpio mode 26 OUT
        gpio write 26 "${data[2]}"
    else
        gpio mode 26 PWM
        val=$((102*${data[2]}))
        gpio pwm 26 "$val"
    fi
fi
