#!/bin/bash
uptimeraw=$(awk '{print $1}' /proc/uptime | cut -d "." -f1)
uptime=$(($uptimeraw / 60))
if [[ $uptime -gt 5 ]]; then
  is_running=$(ps cax | grep set_player | wc -l)
  if [[ $is_running -eq 0 ]]; then
    datetime=$(date '+%d-%m-%Y %H:%M:%S')
    filepath="/opt/innotune/settings"

    fshair="$filepath/p_shairplay"
    count=0
    while IFS='' read -r line || [[ -n "$line" ]]; do
        count=$((count+1))
    done < $fshair
    echo "$count $(ps cax | grep shairport-sync | wc -l)"
    pc=$(ps cax | grep shairport-sync | wc -l)
    if [[ $pc -ne $count ]]; then
        for (( c=1; c<=$pc; c++ ))
        do
          zonename=$(ps ax | grep shairport-sync | sed "${c}q;d" | grep -Po "(?<=-a ).*?(?= --on)")
          echo "$datetime Shairplay: $zonename läuft" >> /var/www/checkprocesses.log
        done
        echo "$datetime $pc von $count Shairplay-Instanzen laufen" >> /var/www/checkprocesses.log
        /var/www/set_player.sh 1
    fi

    fspot="$filepath/p_spotify"
    count=0
    while IFS='' read -ra line || [[ -n "$line" ]]; do
        count=$((count+1))
    done < $fspot
    echo "$count $(ps cax | grep librespot | wc -l)"
    pc=$(ps cax | grep librespot | wc -l)
    if [[ $pc -ne $count ]]; then
        for (( c=1; c<=$pc; c++ ))
        do
          lc=$(($c*2))
          zonename=$(ps ax | grep librespot | sed "${lc}q;d" | grep -Po "(?<=--name ).*?(?= --cache)")
          echo "$datetime Spotify: $zonename läuft" >> /var/www/checkprocesses.log
        done
        echo "$datetime $pc von $count Spotify-Instanzen laufen" >> /var/www/checkprocesses.log
        /var/www/set_player.sh 3
    fi

    fsqueeze="$filepath/p_squeeze"
    count=0
    while IFS='' read -r line || [[ -n "$line" ]]; do
        count=$((count+1))
    done < $fsqueeze
    echo "$count $(ps cax | grep squeezelite-arm | wc -l)"
    pc=$(ps cax | grep squeezelite-arm | wc -l)
    if [[ $pc -ne $count ]]; then
        for (( c=1; c<=$pc; c++ ))
        do
          zonename=$(ps ax | grep squeezelite | sed "${c}q;d" | grep -Po "(?<=-n ).*?(?= -m)")
          echo "$datetime Squeezelite: $zonename läuft" >> /var/www/checkprocesses.log
        done
        echo "$datetime $pc von $count Squeezelite-Instanzen laufen" >> /var/www/checkprocesses.log
        /var/www/set_player.sh 2
    fi
  fi
fi
