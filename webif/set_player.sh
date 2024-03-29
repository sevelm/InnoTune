#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                              set_player.sh                                 ##
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
## This script starts the logitech media server playmonitor, readConding,     ##
## fanreg, all airplay instances, all squeezelite instances and all spotify   ##
## instances.                                                                 ##
## Resets all player state files and creates soft vol controls.               ##
## Also starts vpn and knx.                                                   ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 player type (nothing = all, 1 = airplay, 2 = squeezelite, 3 = spotify)  ##
##                                                                            ##
##                                 References                                 ##
##                                                                            ##
################################################################################
################################################################################

if [[ "$1" -ne "1" ]] && [[ "$1" -ne "2" ]] && [[ "$1" -ne "3" ]]; then
    #/var/www/checkpackages.sh > /dev/null 2>&1 &
    killall net_backup
    killall mutecard
    killall readCoding
    /var/www/src/readCoding > /dev/null 2>&1 &
    killall fanreg
    /var/www/fanreg.sh > /dev/null 2>&1 &
    /var/www/checklogports.sh > /dev/null 2>&1 &
    /var/www/validateupdate.sh > /dev/null 2>&1 &
    killall playmonitor
    /var/www/src/playmonitor > /dev/null 2>&1 &
    killall shairport-sync
    killall squeezelite-armv6hf
    killall squeezeboxserver
    killall mpd
    killall aplay
    killall librespot

    > /opt/innotune/settings/p_shairplay
    > /opt/innotune/settings/p_spotify
    > /opt/innotune/settings/p_squeeze

    # logitech media server
    LMS=$(cat /opt/innotune/settings/logitechmediaserver.txt | head -n1 | tail -n1)
    if [ $LMS == "1" ]; then
        if [[ -f "/opt/server.prefs" ]]; then
            echo "copying server.prefs"
            cp /opt/server.prefs /var/lib/squeezeboxserver/prefs/server.prefs
            sync
        fi
        /etc/init.d/logitechmediaserver restart & sudo systemctl enable logitechmediaserver.service
        IPLMS="-s $(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}')"
        START_PORT=10000
        /var/www/msb_set_credentials.sh > /dev/null 2>&1 &
    else
        /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove & sudo systemctl disable logitechmediaserver.service & sudo systemctl stop logitechmediaserver.service
        START_PORT=11000
    fi

    VPN=$(cat /opt/innotune/settings/vpn.txt | head -n1 | tail -n1)
    if [ $VPN == "1" ]; then
        sudo /var/www/sudoscript.sh vpn_connect &
    fi

    KNX=$(cat /opt/innotune/settings/knxrun.txt)
    sudo /var/www/knxrun.sh $KNX "upstart"

    for i in $(seq -f "%02g" 1 10)
    do
        PORT_BASE=$(($START_PORT+10*${i#0}))

        MODE=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n1 | tail -n1)

        if [ "$MODE" == "1" ] ||  [ "$MODE" == "2" ]; then
            /var/www/mutereg.sh "$i" > /dev/null 2>&1 &

            PLAYER=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n2  | tail -n1)
            PLAYERli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n3  | tail -n1)
            PLAYERre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n4  | tail -n1)
            SQMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n5  | tail -n1)
            SQliMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n6  | tail -n1)
            SQreMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n7  | tail -n1)
            AP=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n8  | tail -n1)
            APli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n9  | tail -n1)
            APre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n10 | tail -n1)
            SP=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n11  | tail -n1)
            SPli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n12  | tail -n1)
            SPre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n13 | tail -n1)

            datetime=$(date '+%d-%m-%Y')

            if [[ $PLAYER ]] || [[ $PLAYERli"$i" ]] || [[ $PLAYERre"$i" ]]; then
                # start airplay
                if [[ $AP ]]; then
                    shairport-sync -v -w -p $(($PORT_BASE+1)) -a "$PLAYER" --on-start "/var/www/spotifyconnect.sh $i 1" --on-stop "/var/www/spotifyconnect.sh $i 0" -o alsa -- -d airplay"$i" >> /var/www/InnoControl/log/airplay$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_shairplay
                fi
                if [[ $APli ]]; then
                    shairport-sync -v -w -p $(($PORT_BASE+2)) -a "$PLAYERli" --on-start "/var/www/spotifyconnect.sh $i 1 li" --on-stop "/var/www/spotifyconnect.sh $i 0 li" -o alsa -- -d airplayli"$i" >> /var/www/InnoControl/log/airplayli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_shairplay
                fi
                if [[ $APre ]]; then
                    shairport-sync -v -w -p $(($PORT_BASE+3)) -a "$PLAYERre" --on-start "/var/www/spotifyconnect.sh $i 1 re" --on-stop "/var/www/spotifyconnect.sh $i 0 re" -o alsa -- -d airplayre"$i" >> /var/www/InnoControl/log/airplayre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_shairplay
                fi

                # start squeezelite
                if [[ $SQMAC ]]; then
                    /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeeze"$i" -n "$PLAYER" -m "$SQMAC" -z > /dev/null 2>&1 & echo "$i;" >> /opt/innotune/settings/p_squeeze
                fi
                if [[ $SQliMAC ]]; then
                    /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezeli"$i" -n "$PLAYERli" -m "$SQliMAC" -z > /dev/null 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_squeeze
                fi
                if [[ $SQreMAC ]]; then
                    /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezere"$i" -n "$PLAYERre" -m "$SQreMAC" -z > /dev/null 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_squeeze
                fi

                # start spotify
                if [[ $SP ]]; then
                    sudo /root/librespot -v --name "$PLAYER" --cache /tmp --bitrate 320 --backend alsa --device airplay$i --onevent "/var/www/spotifyconnect.sh $i 1" >> /var/www/InnoControl/log/spotify$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_spotify
                fi
                if [[ $SPli ]]; then
                    sudo /root/librespot -v --name "$PLAYERli" --cache /tmp --bitrate 320 --backend alsa --device airplayli$i --onevent "/var/www/spotifyconnect.sh $i 1 li" >> /var/www/InnoControl/log/spotifyli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_spotify
                fi
                if [[ $SPre ]]; then
                    sudo /root/librespot -v --name "$PLAYERre" --cache /tmp --bitrate 320 --backend alsa --device airplayre$i --onevent "/var/www/spotifyconnect.sh $i 1 re" >> /var/www/InnoControl/log/spotifyre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_spotify
                fi

                # reset state files
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt
                echo 0 > /opt/innotune/settings/status_line-in/line-in"$i".txt
                echo 0 > /opt/innotune/settings/status_line-in/line-inre"$i".txt
                echo 0 > /opt/innotune/settings/status_line-in/line-inli"$i".txt

                # set volume
                amixer -c "$i" set PCM 100%       > /dev/null 2>&1

                /var/www/sudoscript.sh set_vol "$i" MuteIfMPD 100       > /dev/null 2>&1
                /var/www/sudoscript.sh set_vol "$i" MuteIfAirplay 100   > /dev/null 2>&1
                /var/www/sudoscript.sh set_vol "$i" MuteIfLineIn 100    > /dev/null 2>&1
            fi
        fi
    done

    ########  workaround play mpd first start to open audio device
    cp -R /var/www/src/1punkt5stille.mp3 -n /media/Soundfiles/Toene/1punkt5stille.mp3
    /etc/init.d/mpd restart > /dev/null 2>&1
    sleep 1
    mpc update
    mpc clear
    mpc add Soundfiles/Toene/1punkt5stille.mp3
    mpc play

    /var/www/net_backup.sh > /dev/null 2>&1 &

else

    if [[ $1 -eq 1 ]]; then
        > /opt/innotune/settings/p_shairplay
        killall shairport-sync
    fi
    if [[ $1 -eq 2 ]]; then
        > /opt/innotune/settings/p_squeeze
        killall squeezelite-armv6hf
    fi
    if [[ $1 -eq 3 ]]; then
        > /opt/innotune/settings/p_spotify
        killall librespot
    fi

    # start port
    LMS=$(cat /opt/innotune/settings/logitechmediaserver.txt | head -n1 | tail -n1)
    if [ $LMS == "1" ]; then
        START_PORT=10000
    else
        START_PORT=11000
    fi

    for i in $(seq -f "%02g" 1 10)
    do
        PORT_BASE=$(($START_PORT+10*${i#0}))

        MODE=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n1 | tail -n1)

        if [ "$MODE" == "1" ] ||  [ "$MODE" == "2" ]; then
            PLAYER=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n2  | tail -n1)
            PLAYERli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n3  | tail -n1)
            PLAYERre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n4  | tail -n1)
            SQMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n5  | tail -n1)
            SQliMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n6  | tail -n1)
            SQreMAC=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n7  | tail -n1)
            AP=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n8  | tail -n1)
            APli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n9  | tail -n1)
            APre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n10 | tail -n1)
            SP=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n11  | tail -n1)
            SPli=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n12  | tail -n1)
            SPre=$(cat /opt/innotune/settings/settings_player/dev"$i".txt | head -n13 | tail -n1)

            datetime=$(date '+%d-%m-%Y')

            if [[ $PLAYER ]] || [[ $PLAYERli"$i" ]] || [[ $PLAYERre"$i" ]]; then
                if [[ $1 -eq 1 ]]; then
                    # start airplay
                    if [[ $AP ]]; then
                        shairport-sync -v -w -p $(($PORT_BASE+1)) -a "$PLAYER" --on-start "/var/www/spotifyconnect.sh $i 1" --on-stop "/var/www/spotifyconnect.sh $i 0" -o alsa -- -d airplay"$i" >> /var/www/InnoControl/log/airplay$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_shairplay
                    fi
                    if [[ $APli ]]; then
                        shairport-sync -v -w -p $(($PORT_BASE+2)) -a "$PLAYERli" --on-start "/var/www/spotifyconnect.sh $i 1 li" --on-stop "/var/www/spotifyconnect.sh $i 0 li" -o alsa -- -d airplayli"$i" >> /var/www/InnoControl/log/airplayli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_shairplay
                    fi
                    if [[ $APre ]]; then
                        shairport-sync -v -w -p $(($PORT_BASE+3)) -a "$PLAYERre" --on-start "/var/www/spotifyconnect.sh $i 1 re" --on-stop "/var/www/spotifyconnect.sh $i 0 re" -o alsa -- -d airplayre"$i" >> /var/www/InnoControl/log/airplayre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_shairplay
                    fi
                fi

                if [[ $1 -eq 2 ]]; then
                    # start squeezelite
                    if [[ $SQMAC ]]; then
                        /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeeze"$i" -n "$PLAYER" -m "$SQMAC" -z > /dev/null 2>&1 & echo "$i;" >> /opt/innotune/settings/p_squeeze
                    fi
                    if [[ $SQliMAC ]]; then
                        /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezeli"$i" -n "$PLAYERli" -m "$SQliMAC" -z > /dev/null 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_squeeze
                    fi
                    if [[ $SQreMAC ]]; then
                        /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezere"$i" -n "$PLAYERre" -m "$SQreMAC" -z > /dev/null 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_squeeze
                    fi
                fi

                if [[ $1 -eq 3 ]]; then
                    # start spotify
                    if [[ $SP ]]; then
                        sudo /root/librespot -v --name "$PLAYER" --cache /tmp --bitrate 320 --backend alsa --device airplay$i --onevent "/var/www/spotifyconnect.sh $i 1" >> /var/www/InnoControl/log/spotify$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_spotify
                    fi
                    if [[ $SPli ]]; then
                        sudo /root/librespot -v --name "$PLAYERli" --cache /tmp --bitrate 320 --backend alsa --device airplayli$i --onevent "/var/www/spotifyconnect.sh $i 1 li" >> /var/www/InnoControl/log/spotifyli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_spotify
                    fi
                    if [[ $SPre ]]; then
                        sudo /root/librespot -v --name "$PLAYERre" --cache /tmp --bitrate 320 --backend alsa --device airplayre$i --onevent "/var/www/spotifyconnect.sh $i 1 re" >> /var/www/InnoControl/log/spotifyre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_spotify
                    fi
                fi
                # reset state files
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt
                echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt
                echo 0 > /opt/innotune/settings/status_line-in/line-in"$i".txt

                # set volume
                amixer -c "$i" set PCM 100%       > /dev/null 2>&1

                /var/www/sudoscript.sh set_vol "$i" MuteIfMPD 100       > /dev/null 2>&1
                /var/www/sudoscript.sh set_vol "$i" MuteIfAirplay 100   > /dev/null 2>&1
                /var/www/sudoscript.sh set_vol "$i" MuteIfLineIn 100    > /dev/null 2>&1
            fi
        fi
    done
fi

LMSWA=$(cat /opt/innotune/settings/lmswa.txt | head -n1 | tail -n1)
if [ $LMSWA == "1" ]; then
    echo "test"
    sleep 5
    printf "listen\n" | nc -q 87000 localhost 9090 | /var/www/lmslistener.sh > /dev/null 2>&1 &
fi
exit 0
