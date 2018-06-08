#!/bin/bash
if [[ -f /sys/class/thermal/thermal_zone0/temp ]]; then
  echo $(cat /sys/class/thermal/thermal_zone0/temp)
else
  echo "-1"
fi
