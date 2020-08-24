#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                   lox.sh                                   ##
##                                                                            ##
## Directory:   /var/www/InnoControl/api/                                     ##
## Created  :   01.03.2018                                                    ##
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
## This script reads the metadata of the currently playing title of a lms     ##
## player and returns the values separated by a semicolon.                    ##
##                                                                            ##
##                                                                            ##
##                                Parameter                                   ##
## $1 mac address of lms player                                               ##
##                                                                            ##
##                                References                                  ##
## /var/www/InnoControl/api/lox.php                                           ##
##                                                                            ##
################################################################################
################################################################################

# vars
port=9090
server=localhost

connected=$(printf "$1 connected ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
mode=$(printf "$1 mode ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
title=$(printf "$1 title ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
album=$(printf "$1 album ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
artist=$(printf "$1 artist ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
# currtitle=$(printf "$1 current_title ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

# encoded chars: :   & ' | ! ? , / Ä Ö Ü ä ö ü [ ] ... ß
echo "$title;$connected;$artist;$mode" | sed -e 's/%3A/:/g' \
                                   -e 's/%20/ /g' \
                                   -e 's/%26/\&/g' \
                                   -e "s/%27/'/g" \
                                   -e 's/%7C/|/g' \
                                   -e 's/%21/!/g' \
                                   -e 's/%3F/?/g' \
                                   -e 's/%2C/,/g' \
                                   -e 's/%2F/\//g' \
                                   -e 's/%C3%84/Ä/g' \
                                   -e 's/%C3%96/Ö/g' \
                                   -e 's/%C3%9C/Ü/g' \
                                   -e 's/%C3%A4/ä/g' \
                                   -e 's/%C3%B6/ö/g' \
                                   -e 's/%C3%BC/ü/g' \
                                   -e 's/%5B/[/g' \
                                   -e 's/%5D/]/g' \
                                   -e 's/%E2%80%A6/.../g' \
                                   -e 's/%C3%9F/ß/g'
