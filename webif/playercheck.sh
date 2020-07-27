#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               playercheck.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   27.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Severin Elmecker                                              ##
##              Alexander Elmecker                                            ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script iterates over all known players of the logitech media server   ##
## and checks their playstate or rather the current stream duration.          ##
## If the duration exceeds two hours of playing the stream will be restarted  ##
## in order to keep the player and the radio station server in sync (kinda).  ##
##                                                                            ##
## A cronjob excecutes this script every 15 minutes.                          ##
## Cronjob Entry:                                                             ##
## */15 * * * * /var/www/playercheck.sh                                       ##
##                                                                            ##
################################################################################
################################################################################

# vars
port=9090
server=localhost

# use a fresh logfile
mv /tmp/playercheck.log /tmp/playercheck.txt
echo "$(date)" > /tmp/playercheck.log

# get number of known players
players=$(printf "player count ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
echo "Known Players: $players" >> /tmp/playercheck.log

# iterate over all known players
for((i=0; i<$players; i++))
do
    echo "--------------------------------------------------------------------------------" >> /tmp/playercheck.log
    # get id and model of the current player
    playerID=$(printf "player id $i ?\nexit\n" | nc $server $port | cut -d ' ' -f 4 | sed 's/%3A/:/g')
    playermodel=$(printf "player model $i ? \nexit\n" | nc $server $port |cut -d ' ' -f 3)

    # if the player isn't of type squeezebox, we don't want to check them,
    # because they don't really belong to the innotune system
    if [ !$playermodel = squeezelite ]; then
        echo "Player ($i) $playerID: not a squeezelite Session" >> /tmp/playercheck.log
    else
        playername=$(printf "$playerID name ?\nexit\n" | nc $server $port | cut -d ' ' -f 3 | sed 's/%20/ /g')
        playermode=$(printf "$playerID mode ? \nexit\n" | nc $server $port | cut -d ' ' -f 3)

        # check if the player is even playing
        if [ $playermode = play ]; then
            timetoplay1=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
            echo "$playername ($playerID): duration=$timetoplay1" >> /tmp/playercheck.log
            timetoplay2=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

            # check two following "stream durations" if the 2nd is larger than
            # the first. (hence the stream is really playing/running)
            if [ $timetoplay1 = $timetoplay2 ]; then
                echo "$playername ($playerID): 1st duration ($timetoplay1) equals 2nd duration ($timetoplay2)" >> /tmp/playercheck.log
                # if players are synced the slave player's duration can be 0
                # because the master already got restarted, therefore we wait
                # and check the duration again afterwards
                if [ $timetoplay1 = 0 ]; then
                    echo "$playername ($playerID): sleeping (duration was 0)" >> /tmp/playercheck.log
                    sleep 4
                fi
                timetoplay3=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

                # checking against a third duration
                # stopping if the durations where all the same
                if [ $timetoplay1 = $timetoplay3 ]; then
                    echo "$playername ($playerID): 1st duration ($timetoplay1) also equals 3rd duration ($timetoplay2)" >> /tmp/playercheck.log
                    printf "$playerID power 0 \nexit\n" | nc $server $port
                fi
            else
                echo "$playername ($playerID): playing..., checking stream duration" >> /tmp/playercheck.log

                # restart stream if running for two or more hours (7200 sec)
                # to keep the some kind of sync between players and radio station server
                if [ $(echo "if ($timetoplay1 >= 7200.376459999084) 1 else 0" | bc) -eq 1 ]; then
                    echo "$playername ($playerID): stop and play" >> /tmp/playercheck.log
                    printf "$playerID stop\nexit\n" | nc $server $port
                    sleep 1
                    printf "$playerID play\nexit\n" | nc $server $port
                fi
            fi
        else
            # if the player was previously (15 min ago) not in play mode and
            # isn't now, then we finally turn the player off
            grep "$playername ($playerID): mode=$playermode" /tmp/playercheck.txt && printf "$playerID power 0 \nexit\n" | nc $server $port
            echo "$playername ($playerID): mode=$playermode" >> /tmp/playercheck.log
        fi
    fi
done
