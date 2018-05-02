#!/bin/bash

if [[ "$1" -ne "1" ]] && [[ "$1" -ne "2" ]] && [[ "$1" -ne "3" ]]; then
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

  ###Logitech Media Server
  LMS=$(cat /opt/innotune/settings/logitechmediaserver.txt | head -n1 | tail -n1)
  if [ $LMS == "1" ]; then
     /etc/init.d/logitechmediaserver restart
     IPLMS="-s $(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}')"
     START_PORT=10000
     /var/www/msb_set_credentials.sh > /dev/null 2>&1 &
  else
     /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove
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
              ########### Start Airplay
              if [[ $AP ]]; then           ###Aktivate Player on USB device "$i" - AirPlay
                    shairport-sync -v -w -p $(($PORT_BASE+1)) -a "$PLAYER" --on-start "/var/www/spotifyconnect.sh $i 1" --on-stop "/var/www/spotifyconnect.sh $i 0" -o alsa -- -d airplay"$i" >> /var/www/InnoControl/log/airplay$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_shairplay
              fi
              if [[ $APli ]]; then         ###Aktivate Player left on USB device "$i" - AirPlay
                    shairport-sync -v -w -p $(($PORT_BASE+2)) -a "$PLAYERli" --on-start "/var/www/spotifyconnect.sh $i 1 li" --on-stop "/var/www/spotifyconnect.sh $i 0 li" -o alsa -- -d airplayli"$i" >> /var/www/InnoControl/log/airplayli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_shairplay
              fi
              if [[ $APre ]]; then         ###Aktivate Player right on USB device "$i" - AirPlay
                    shairport-sync -v -w -p $(($PORT_BASE+3)) -a "$PLAYERre" --on-start "/var/www/spotifyconnect.sh $i 1 re" --on-stop "/var/www/spotifyconnect.sh $i 0 re" -o alsa -- -d airplayre"$i" >> /var/www/InnoControl/log/airplayre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_shairplay
              fi

              ########### Start Squeezelite
              if [[ $SQMAC ]]; then       ###Aktivate Player on USB device "$i" - Squeezbox
              /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeeze"$i" -n "$PLAYER" -m "$SQMAC" -z -d all=info -f /var/www/InnoControl/log/squeeze$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_squeeze
              fi
              if [[ $SQliMAC ]]; then     ###Aktivate Player on USB device "$i" left - Squeezbox
              /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezeli"$i" -n "$PLAYERli" -m "$SQliMAC" -z -d all=info -f /var/www/InnoControl/log/squeezeli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_squeeze
              fi
              if [[ $SQreMAC ]]; then     ###Aktivate Player on USB device "$i" right - Squeezbox
              /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezere"$i" -n "$PLAYERre" -m "$SQreMAC" -z -d all=info -f /var/www/InnoControl/log/squeezere$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_squeeze
              fi

              ########### Start Spotify Connect
              if [[ $SP ]]; then           ###Aktivate Player on USB device "$i" - Spotify
                      sudo /root/librespot -v --name "$PLAYER" --cache /tmp --bitrate 320 --backend alsa --device airplay$i --onevent "/var/www/spotifyconnect.sh $i 1" >> /var/www/InnoControl/log/spotify$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_spotify
                      fi
              if [[ $SPli ]]; then         ###Aktivate Player left on USB device "$i" - Spotify
                      sudo /root/librespot -v --name "$PLAYERli" --cache /tmp --bitrate 320 --backend alsa --device airplayli$i --onevent "/var/www/spotifyconnect.sh $i 1 li" >> /var/www/InnoControl/log/spotifyli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_spotify
              fi
              if [[ $SPre ]]; then         ###Aktivate Player right on USB device "$i" - Spotify
                      sudo /root/librespot -v --name "$PLAYERre" --cache /tmp --bitrate 320 --backend alsa --device airplayre$i --onevent "/var/www/spotifyconnect.sh $i 1 re" >> /var/www/InnoControl/log/spotifyre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_spotify
              fi
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt           ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt         ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt         ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_line-in/line-in"$i".txt                      ### Line-In ablöschen

              #aplay -f cd -D dmixer"$i" /dev/zero > /dev/null 2>&1 & echo $!                     ### Grundstille erzeugen
              amixer -c "$i" set PCM 100%       > /dev/null 2>&1                                  ### Lautstärke Setzen
              #amixer -c sndc"$i" set PCM 100%       > /dev/null 2>&1                                  ### Lautstärke Setzen
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

  ########  workaround play mpd first start to open audio device
  cp -R /var/www/src/1punkt5stille.mp3 -n /media/Soundfiles/Toene/1punkt5stille.mp3
  /etc/init.d/mpd restart > /dev/null 2>&1
  sleep 1
  mpc update
  mpc clear
  mpc add Soundfiles/Toene/1punkt5stille.mp3
  mpc play

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

  ###Start-Port
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
                  ########### Start Airplay
                  if [[ $AP ]]; then           ###Aktivate Player on USB device "$i" - AirPlay
                        shairport-sync -v -w -p $(($PORT_BASE+1)) -a "$PLAYER" --on-start "/var/www/spotifyconnect.sh $i 1" --on-stop "/var/www/spotifyconnect.sh $i 0" -o alsa -- -d airplay"$i" >> /var/www/InnoControl/log/airplay$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_shairplay
                  fi
                  if [[ $APli ]]; then         ###Aktivate Player left on USB device "$i" - AirPlay
                        shairport-sync -v -w -p $(($PORT_BASE+2)) -a "$PLAYERli" --on-start "/var/www/spotifyconnect.sh $i 1 li" --on-stop "/var/www/spotifyconnect.sh $i 0 li" -o alsa -- -d airplayli"$i" >> /var/www/InnoControl/log/airplayli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_shairplay
                  fi
                  if [[ $APre ]]; then         ###Aktivate Player right on USB device "$i" - AirPlay
                        shairport-sync -v -w -p $(($PORT_BASE+3)) -a "$PLAYERre" --on-start "/var/www/spotifyconnect.sh $i 1 re" --on-stop "/var/www/spotifyconnect.sh $i 0 re" -o alsa -- -d airplayre"$i" >> /var/www/InnoControl/log/airplayre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_shairplay
                  fi
              fi

              if [[ $1 -eq 2 ]]; then
                  ########### Start Squeezelite
                  if [[ $SQMAC ]]; then       ###Aktivate Player on USB device "$i" - Squeezbox
                      /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeeze"$i" -n "$PLAYER" -m "$SQMAC" -z -d all=info -f /var/www/InnoControl/log/squeeze$i-$datetime > /dev/null 2>&1 & echo "$i;" >> /opt/innotune/settings/p_squeeze
                  fi
                  if [[ $SQliMAC ]]; then     ###Aktivate Player on USB device "$i" left - Squeezbox
                      /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezeli"$i" -n "$PLAYERli" -m "$SQliMAC" -z -d all=info -f /var/www/InnoControl/log/squeezeli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_squeeze
                  fi
                  if [[ $SQreMAC ]]; then     ###Aktivate Player on USB device "$i" right - Squeezbox
                      /usr/bin/squeezelite-armv6hf -b 2048:4096 -a 60:16:16:0 -r 44100,44100 -R -u hMX -o squeezere"$i" -n "$PLAYERre" -m "$SQreMAC" -z -d all=info -f /var/www/InnoControl/log/squeezere$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_squeeze
                  fi
              fi

              if [[ $1 -eq 3 ]]; then
                  ########### Start Spotify Connect
                  if [[ $SP ]]; then           ###Aktivate Player on USB device "$i" - Spotify
                          sudo /root/librespot -v --name "$PLAYER" --cache /tmp --bitrate 320 --backend alsa --device airplay$i --onevent "/var/www/spotifyconnect.sh $i 1" >> /var/www/InnoControl/log/spotify$i-$datetime 2>&1 & echo "$i;" >> /opt/innotune/settings/p_spotify
                          fi
                  if [[ $SPli ]]; then         ###Aktivate Player left on USB device "$i" - Spotify
                          sudo /root/librespot -v --name "$PLAYERli" --cache /tmp --bitrate 320 --backend alsa --device airplayli$i --onevent "/var/www/spotifyconnect.sh $i 1 li" >> /var/www/InnoControl/log/spotifyli$i-$datetime 2>&1 & echo "$i;li" >> /opt/innotune/settings/p_spotify
                  fi
                  if [[ $SPre ]]; then         ###Aktivate Player right on USB device "$i" - Spotify
                          sudo /root/librespot -v --name "$PLAYERre" --cache /tmp --bitrate 320 --backend alsa --device airplayre$i --onevent "/var/www/spotifyconnect.sh $i 1 re" >> /var/www/InnoControl/log/spotifyre$i-$datetime 2>&1 & echo "$i;re" >> /opt/innotune/settings/p_spotify
                  fi
              fi
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplay"$i".txt           ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayli"$i".txt         ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_shairplay/status_shairplayre"$i".txt         ### Airplay ablöschen
              echo 0 > /opt/innotune/settings/status_line-in/line-in"$i".txt                      ### Line-In ablöschen

              amixer -c "$i" set PCM 100%       > /dev/null 2>&1                                  ### Lautstärke Setzen
              #amixer -c sndc"$i" set PCM 100%       > /dev/null 2>&1
              /var/www/sudoscript.sh set_vol "$i" MuteIfMPD 100       > /dev/null 2>&1            ### MPD Lautstärke setzen
              /var/www/sudoscript.sh set_vol "$i" MuteIfAirplay 100   > /dev/null 2>&1            ### (Sh)Airplay Lautstärke setzen
              /var/www/sudoscript.sh set_vol "$i" MuteIfLineIn 100    > /dev/null 2>&1            ### Line-In Lautstärke setzen

              #killall aplay
          fi
  	fi
  done
fi
exit 0
