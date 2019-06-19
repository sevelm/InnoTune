#!/bin/bash
while read line ; do
  echo "receviced: $line"
  IFS=' ' read -ra array <<< "$line"
  if [[ "${array[1]}" = "playlist" ]]; then
    if [[ "${array[2]}" = "open" ]] || [[ "${array[2]}" = "play" ]] || [[ "${array[2]}" = "add" ]] ||
       [[ "${array[2]}" = "insert" ]]; then
      echo "array 0: ${array[0]}"
      echo "array 1: ${array[1]}"
      echo "array 2: ${array[2]}"
      echo "array 3: ${array[3]}"
      if [[ "${array[3]}" = "http%3A%2F%2Fstream.radiocorp.nl%2Fweb11_mp3" ]] ||
         [[ "${array[3]}" = "http%3A%2F%2F19993.live.streamtheworld.com%2FWEB11_MP3_SC%3F" ]]; then
        data="${array[0]}"
        mac=${data//%3A/:}
        printf "$mac playlist clear\nexit\n" | nc localhost 9090
      fi
    fi
  fi
done
