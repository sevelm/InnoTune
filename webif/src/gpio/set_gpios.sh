#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              set_gpios.sh                                  ##
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
## This script sets all gpio modes.                                           ##
##                                                                            ##
##                                 References                                 ##
##                                                                            ##
################################################################################
################################################################################

# mode for crossbar leds
gpio mode 8 OUT
gpio mode 9 OUT
gpio mode 0 OUT


# value for crossbar leds
gpio write 8 1
gpio write 9 1
gpio write 0 1

echo "crossbar"

# mode for folientaster leds
gpio mode 15 OUT
gpio mode 16 OUT

# value for folientaster leds
gpio write 15 1
gpio write 16 1

# mode for folientaster inputs
gpio mode 4 UP
gpio mode 5 UP

echo "folientaster"

# mode for fan relais
gpio mode 26 OUT
gpio write 26 0

# mode for fan pwm
# gpio mode 26 PWM

# GPIO_API_for_C/examples/pwm 2>&1 /dev/null &

echo "fan"

#mode for mute relays
gpio mode 7 OUT
gpio mode 2 OUT
gpio mode 22 OUT
gpio mode 24 OUT
gpio mode 6 OUT
gpio mode 27 OUT
gpio mode 28 OUT
gpio mode 29 OUT

echo "mute relays"

# mode for coding DIs
gpio mode 1 UP
gpio mode 3 UP
gpio mode 21 UP

echo "DI coding"

gpio readall
