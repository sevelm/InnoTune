#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                fanreg.sh                                   ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   14.05.2019                                                    ##
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
## This script reads the fan options and sets the fan accordingly.            ##
## data[0] = 0 (automatic fan regulation), 1 (manual fan regulation)          ##
## data[1] = 0 (on/off mode), 1 (pwm mode)                                    ##
## data[2] = on/off (data[1] = 0), fan speed percentage (data[1] = 1)         ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

options=$(cat /opt/innotune/settings/gpio/fan_options)
IFS=';' read -ra data <<< "$options"

pkill -f tempSensor.py
python3 /var/www/src/gpio/tempSensor.py 2>&1 /dev/null &

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
