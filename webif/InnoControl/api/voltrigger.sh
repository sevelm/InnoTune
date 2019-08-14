#!/bin/bash
current=$(printf "$1 mixer volume ?\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
echo "$1 $2 $current"
i=$((1))
multiplier=$((1))
if [[ $2 = "u" ]]; then
  while [[ $current -lt 100 ]]; do
    new=$(($current+3*$multiplier))
    current=$(printf "$1 mixer volume $new\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
    sleep .33
    i=$(($i+1))
    #if [[ $(($i%4)) -eq 0 ]]; then
    #  multiplier=$(($multiplier+1))
    #fi
  done
else
  while [[ $current -gt 0 ]]; do
    new=$(($current-3*$multiplier))
    current=$(printf "$1 mixer volume $new\nexit\n" | nc localhost 9090 | cut -d ' ' -f 4)
    sleep .33
    i=$(($i+1))
    if [[ $(($i%4)) -eq 0 ]]; then
      multiplier=$(($multiplier+1))
    fi
  done
fi
