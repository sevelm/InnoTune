#!/bin/bash
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
      echo "$datetime $pc/$count shairplay" >> /var/www/checkprocesses.log
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
      echo "$datetime $pc/$count spotifyconnect" >> /var/www/checkprocesses.log
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
      echo "$datetime $pc/$count squeezelite" >> /var/www/checkprocesses.log
      /var/www/set_player.sh 2
  fi
fi
