#!/bin/bash
## check if any player is playing
## and restart squeezelite if timetoplay is equal on both checks

##vars
port=9090
server=localhost

# use a fresh logfile
mv /tmp/playercheck.log /tmp/playercheck.txt
echo "$(date)">/tmp/playercheck.log
#sensors|grep 'Core 0' |awk '{print $3}'|cut -b2,3,4,5,7>>/tmp/playercheck.log

# get number of known players
players=$(printf "player count ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)

## check all known players
for((i=0; i<$players; i++))
do
        playerID=$(printf "player id $i ?\nexit\n" | nc $server $port | cut -d ' ' -f 4 | sed 's/%/%%/g')
        playermodel=$(printf "player model $i ? \nexit\n" | nc 127.0.0.1 9090 |cut -d ' ' -f 3)

                if [ !$playermodel = squeezelite ]
                        then
                        echo Player Nr. $i is not a squeezelite Session>>/tmp/playercheck.log
                else
                        playername=$(printf "$playerID name ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
                        playermode=$(printf "$playerID mode ? \nexit\n" | nc 127.0.0.1 9090 |cut -d ' ' -f 3)
                                if [ $playermode = pause ]
                                        then
                                        grep $playername:$playermode /tmp/playercheck.txt && printf "$playerID power 0 \nexit\n" | nc $server $port
                                        echo $playername:$playermode>>/tmp/playercheck.log
                                else
                                        timetoplay1=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
                                        echo "$playername:Playtime:$timetoplay1">>/tmp/playercheck.log
                                        timetoplay2=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
                                                if [ $timetoplay1 = $timetoplay2 ]
                                                        then
                                                        echo no difference between 1st and 2.nd check
                                                        timetoplay3=$(printf "$playerID time ?\nexit\n" | nc $server $port | cut -d ' ' -f 3)
                                                                if [ $timetoplay1 = $timetoplay3 ]
                                                                        then
                                                                        printf "$playerID power 0 \nexit\n" | nc $server $port
                                                                fi
                                                else
                                                echo "$playername is playing nothing 2do 4me">>/tmp/playercheck.log

                                                        if [ $(echo "if ($timetoplay1 >= 7200.376459999084) 1 else 0" | bc) -eq 1 ] ## reboot every 2 hour
                                                        then
                                                        printf "$playerID power 0 \nexit\n" | nc $server $port
                                                        sleep 1
                                                        printf "$playerID play \nexit\n" | nc $server $port
                                                        echo "$playername:restart">>/tmp/playercheck.log
                                                fi
                                                fi
                                fi
                fi
done
