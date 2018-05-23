#!/bin/bash
case "$2" in
  "low")
    #LOW
    amixer -D equal$1 set "00. 31 Hz" "$3"%
    amixer -D equal$1 set "01. 63 Hz" "$3"%
    amixer -D equal$1 set "02. 125 Hz" "$3"%;;
  "mid")
    #MID
    amixer -D equal$1 set "03. 250 Hz" "$3"%
    amixer -D equal$1 set "04. 500 Hz" "$3"%
    amixer -D equal$1 set "05. 1 kHz" "$3"%
    amixer -D equal$1 set "06. 2 kHz" "$3"%;;
  "high")
    #HIGH
    amixer -D equal$1 set "07. 4 kHz" "$3"%
    amixer -D equal$1 set "08. 8 kHz" "$3"%
    amixer -D equal$1 set "09. 16 kHz" "$3"%;;
esac

exit 0
