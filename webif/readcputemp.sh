#!/bin/bash
if [[ -f /sys/class/thermal/thermal_zone0/temp && $(cat /sys/class/thermal/thermal_zone0/type | grep cpu_thermal) ]]; then
  echo $(cat /sys/class/thermal/thermal_zone0/temp)
elif [[ -f /sys/class/thermal/thermal_zone1/temp && $(cat /sys/class/thermal/thermal_zone1/type | grep cpu_thermal) ]]; then
  echo $(cat /sys/class/thermal/thermal_zone1/temp)
elif [[ -f /sys/class/thermal/thermal_zone2/temp && $(cat /sys/class/thermal/thermal_zone2/type | grep cpu_thermal) ]]; then
  echo $(cat /sys/class/thermal/thermal_zone2/temp)
else
  echo "-1"
fi
