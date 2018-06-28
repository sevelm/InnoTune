#!/bin/bash
if [[ -f /sys/class/thermal/thermal_zone0/temp ]]; then
  t0=$(cat /sys/class/thermal/thermal_zone0/temp)
  if [[ $(uname -r | grep rockchip | wc -l) ]]; then
    t1=$(cat /sys/class/thermal/thermal_zone1/temp)
    if [[ "${t0/Invalid argument}" = "$t0" ]]; then
        echo "$t1"
    else
        echo "$t0"
    fi
  else
    echo "$t0"
  fi
else
  echo "-1"
fi
