#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                set_vol.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   29.03.2019                                                    ##
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
## This script sets the volume of a soft vol control of a specific card.      ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 card number                                                             ##
## $2 control name                                                            ##
## $3 volume percentage                                                       ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# removes leading zeros from card out (Amp 08 and 09 wont work otherwise)
card=$(echo $1 | sed 's/^0*//')

# set stereo volume control
amixer -c sndc"$1" set "$2"_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
    echo "sndc$1 not found, exit code: $code"
    amixer -c "$card" set "$2"_"$1" "$3"% &> /dev/null
    code=$(($?))
    if [ $code -gt 0 ]; then
        echo "card: $1 not found, exit code: $code"
    else
        echo "card: $1 found"
    fi
else
    echo "sndc$1 found"
fi

# set mono left channel volume control
amixer -c sndc"$1" set "$2"li_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
    echo "sndc$1 not found, exit code: $code"
    amixer -c "$card" set "$2"li_"$1" "$3"% &> /dev/null
    code=$(($?))
    if [ $code -gt 0 ]; then
        echo "card: $1 not found, exit code: $code"
    else
        echo "card: $1 found"
    fi
else
    echo "sndc$1 found"
fi

# set mono right channel volume control
amixer -c sndc"$1" set "$2"re_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
    echo "sndc$1 not found, exit code: $code"
    amixer -c "$card" set "$2"re_"$1" "$3"% &> /dev/null
    code=$(($?))
    if [ $code -gt 0 ]; then
        echo "card: $1 not found, exit code: $code"
    else
        echo "card: $1 found"
    fi
else
    echo "sndc$1 found"
fi
