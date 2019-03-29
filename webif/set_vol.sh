#!/bin/bash
################################################################################
#
# $1 = card number
# $2 = control name
# $3 = volume percentage
#
################################################################################

# removes leading zeros from card out (Amp 08 and 09 wont work otherwise)
card=$(echo $1 | sed 's/^0*//')

# set stereo volume control
amixer -c sndc"$1" set "$2"_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
  echo "sndc$1 not found, exit code: $code"
  amixer -c "$card" set "$2"_"$1" "$3"% &> /dev/null
  code=$(($?))
  if [ $code -gt 0 ]; then
    echo "card: $1 not found, exit code: $code"
  else
    echo "card: $1 found"
  fi
else
  echo "sndc$1 found"
fi

# set mono left channel volume control
amixer -c sndc"$1" set "$2"li_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
  echo "sndc$1 not found, exit code: $code"
  amixer -c "$card" set "$2"li_"$1" "$3"% &> /dev/null
  code=$(($?))
  if [ $code -gt 0 ]; then
    echo "card: $1 not found, exit code: $code"
  else
    echo "card: $1 found"
  fi
else
  echo "sndc$1 found"
fi

# set mono right channel volume control
amixer -c sndc"$1" set "$2"re_"$1" "$3"% &> /dev/null
code=$(($?))
if [ $code -gt 0 ]; then
  echo "sndc$1 not found, exit code: $code"
  amixer -c "$card" set "$2"re_"$1" "$3"% &> /dev/null
  code=$(($?))
  if [ $code -gt 0 ]; then
    echo "card: $1 not found, exit code: $code"
  else
    echo "card: $1 found"
  fi
else
  echo "sndc$1 found"
fi
