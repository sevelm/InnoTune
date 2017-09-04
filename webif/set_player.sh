#!/bin/bash

killall shairport
killall squeezelite-armv6hf
killall squeezeboxserver
killall mpd
killall aplay
killall librespot

###Logitech Media Server
LMS=$(cat /opt/innotune/settings/logitechmediaserver.txt | head -n1 | tail -n1)
if [ $LMS == "1" ]; then
   /etc/init.d/logitechmediaserver restart
   IPLMS="-s $(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}')"
else
   /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove
fi

for i in $(seq -f "%02g" 1 10)
do
	PORT_BASE=$((5000+10*${i#0}))

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

        if [[ $PLAYER ]] || [[ $PLAYERli"$i" ]] || [[ $PLAYERre"$i" ]]; then
            ########### Start Airplay
            if [[ $AP ]]; then           ###Aktivate Player on USB device "$i" - AirPlay
                  /usr/local/bin/shairport -w -p $(($PORT_BASE+1)) -a "$PLAYER" --on-start "echo 1 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt" --on-stop "echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt" -o alsa -- -d airplay"$i" > /dev/null 2>&1 & echo $!
            fi
            if [[ $APli ]]; then         ###Aktivate Player left on USB device "$i" - AirPlay
                  /usr/local/bin/shairport -w -p $(($PORT_BASE+2)) -a "$PLAYERli" --on-start "echo 1 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt" --on-stop "echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt" -o alsa -- -d airplayli"$i" > /dev/null 2>&1 & echo $!
            fi
            if [[ $APre ]]; then         ###Aktivate Player right on USB device "$i" - AirPlay
                  /usr/local/bin/shairport -w -p $(($PORT_BASE+3)) -a "$PLAYERre" --on-start "echo 1 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt" --on-stop "echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt" -o alsa -- -d airplayre"$i" > /dev/null 2>&1 & echo $!
            fi

            ########### Start Squeezelite
            if [[ $SQMAC ]]; then       ###Aktivate Player on USB device "$i" - Squeezbox
                  /usr/bin/squeezelite-armv6hf -c flac,pcm,mp3,ogg,aac -b 2048:4096 -a 60:16::0 -r 44100,44100   -p 97 -o plug:squeeze"$i" -n "$PLAYER" -m "$SQMAC" -z  > /dev/null 2>&1 & echo $!
        #alt:  /usr/bin/squeezelite-armv6hf -n $PLAYER -o squeeze"$i" -d all=debug -a 4096:1024:16:0 -r 44100 -m $SQMAC > /dev/null 2>&1 & echo $!
            fi
            if [[ $SQliMAC ]]; then     ###Aktivate Player on USB device "$i" left - Squeezbox
                  /usr/bin/squeezelite-armv6hf -c flac,pcm,mp3,ogg,aac -b 2048:4096 -a 60:16::0 -r 44100 -p 97 -o plug:squeezeli"$i" -n "$PLAYERli" -m "$SQliMAC" -z > /dev/null 2>&1 & echo $!
        #alt:	/usr/bin/squeezelite-armv6hf -n $PLAYERli -o squeezeli"$i" -d all=debug -a 4096:1024:16:0 -r 44100 -m $SQliMAC > /dev/null 2>&1 & echo $!
            fi
            if [[ $SQreMAC ]]; then     ###Aktivate Player on USB device "$i" right - Squeezbox
                  /usr/bin/squeezelite-armv6hf -c flac,pcm,mp3,ogg,aac -b 2048:4096 -a 60:16::0 -r 44100 -p 97 -o plug:squeezere"$i" -n "$PLAYERre" -m "$SQreMAC" -z > /dev/null 2>&1 & echo $!
        #alt:	/usr/bin/squeezelite-armv6hf -n $PLAYERre -o squeezere"$i" -d all=debug -a 4096:1024:16:0 -r 44100 -m $SQreMAC > /dev/null 2>&1 & echo $!
            fi

            ########### Start Spotify Connect
            if [[ $SP ]]; then           ###Aktivate Player on USB device "$i" - Spotify
                    sudo /root/librespot --name $PLAYER --cache /tmp --bitrate 320 --backend alsa --device airplay$i --onstart "./var/www/spotifyconnect.sh $i 1" --onstop "./var/www/spotifyconnect.sh $i 0" > /dev/null 2>&1 & echo $!
                    fi
            if [[ $SPli ]]; then         ###Aktivate Player left on USB device "$i" - Spotify
                    sudo /root/librespot --name $PLAYERli --cache /tmp --bitrate 320 --backend alsa --device airplayli$i --onstart "./var/www/spotifyconnect.sh $i 1 li" --onstop "./var/www/spotifyconnect.sh $i 0 li" > /dev/null 2>&1 & echo $!
            fi
            if [[ $SPre ]]; then         ###Aktivate Player right on USB device "$i" - Spotify
                    sudo /root/librespot --name $PLAYERre --cache /tmp --bitrate 320 --backend alsa --device airplayre$i --onstart "./var/www/spotifyconnect.sh $i 1 re" --onstop "./var/www/spotifyconnect.sh $i 0 re" > /dev/null 2>&1 & echo $!
            fi
            echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt           ### Airplay ablöschen
            echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt         ### Airplay ablöschen
            echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt         ### Airplay ablöschen
            echo 0 > /opt/innotune/settings/status_line-in/line-in"$i".txt                      ### Line-In ablöschen

            #aplay -f cd -D dmixer"$i" /dev/zero > /dev/null 2>&1 & echo $!                     ### Grundstille erzeugen
            amixer -c "$i" set PCM 100%       > /dev/null 2>&1                                  ### Lautstärke Setzen
            /var/www/sudoscript.sh set_vol "$i" MuteIfMPD 100       > /dev/null 2>&1            ### MPD Lautstärke setzen
            /var/www/sudoscript.sh set_vol "$i" MuteIfAirplay 100   > /dev/null 2>&1            ### (Sh)Airplay Lautstärke setzen
            /var/www/sudoscript.sh set_vol "$i" MuteIfLineIn 100    > /dev/null 2>&1            ### Line-In Lautstärke setzen

            #aplay -B 1 -D plug:mpd"$i" > /dev/null 2>&1 & echo $!                              ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:mpdli"$i" > /dev/null 2>&1 & echo $!                            ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:mpdre"$i" > /dev/null 2>&1 & echo $!                            ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:squeeze_"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:squeezeli_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:squeezere_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplay"$i" > /dev/null 2>&1 & echo $!                          ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplayli"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplayre"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplay_"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplayli_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:airplayre_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineIn"$i" > /dev/null 2>&1 & echo $!                           ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineInli"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineInre"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineIn_"$i" > /dev/null 2>&1 & echo $!                          ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineInli_"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
            #aplay -B 1 -D plug:LineInre_"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
            killall aplay
        fi
	fi
done

/etc/init.d/mpd restart > /dev/null 2>&1

exit 0
