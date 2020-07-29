#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                         msb_set_credentials.sh                             ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   28.03.2018                                                    ##
## Edited   :   28.07.2020                                                    ##
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
## This script tries to establish a connection to the lms cmd interface, if   ##
## it connects, it sends the credentials to the server.                       ##
## Retries if the credentials couldn't be validated.                          ##
##                                                                            ##
##                                 References                                 ##
## /var/www/set_player.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# try to connect to the lms cmd interface
echo "trying to connect to port 9090..."
con=0
while [[ $(echo exit | nc -w 300 localhost 9090 | wc -l) -eq 0 ]] && [[ $con -lt 20 ]]
do
    echo "failed to connect, trying again..."
    sleep 30
    con=$((con+1))
done

# when there were less than 20 connection tries continue
if [[ $con -lt 20 ]]; then
    echo "successfully connected"
    echo "trying to set credentials..."
    # send set crendials command
    response=$(printf "setsncredentials squeeze@innotune.at innotune\n" | nc -q 120 localhost 9090)
    count=0
    # check response
    while [[ $(grep validated%3A1 <<< $response | wc -l) -eq 0 ]] && [[ $count -lt 5 ]]
    do
        echo "failed to set credentials, trying again..."
        response=$(printf "setsncredentials squeeze@innotune.at innotune\n" | nc -q 120 localhost 9090)
        count=$((count+1))
    done
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    echo "$datetime LMS Logindaten wurden gesetzt" >> /var/www/checkprocesses.log
    echo "successfully set credentials"
else
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    echo "$datetime Fehler beim setzen der LMS Logindaten" >> /var/www/checkprocesses.log
fi
