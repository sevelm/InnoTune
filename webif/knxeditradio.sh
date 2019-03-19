#!/bin/bash
#
# Parameter 1:
# 0 = delete
# 1 = edit
# 2 = add
#
# Parameter 2:
# P1 = 0 || 1: old line number
# P1 = 2: new entry
#
# Parameter 3:
# P1 = 1: updated entry
#

if [[ "$1" -eq 0 ]]; then
  # delete entry
  sed -i "\;|$2|*;d" /opt/innotune/settings/knxradios.txt
  # renumber remaining entries
  count=$((1))
  while IFS='\n' read -r line; do
      IFS='|' read -ra nextdata <<< "$line"
      # escape problematic characters for sed
      txt=$(echo "${nextdata[2]}|${nextdata[3]}" | sed -e 's/\&/\\&/g')

      sed -i "${count}s[.*[|${count}|${txt}[" /opt/innotune/settings/knxradios.txt
      count=$(($count+1))
  done < "/opt/innotune/settings/knxradios.txt"
elif [[ "$1" -eq 1 ]]; then
  # update edited entry
  count=$((1))
  while IFS='\n' read -r line; do
      IFS='|' read -ra nextdata <<< "$line"
      if [ "${nextdata[1]}" -eq "$2" ]; then
          # escape problematic characters for sed
          txt=$(echo "${3}" | sed -e 's/\&/\\&/g')

          sed -i "${count}s[.*[${txt}[" /opt/innotune/settings/knxradios.txt
      fi
      count=$(($count+1))
  done < "/opt/innotune/settings/knxradios.txt"
elif [[ "$1" -eq 2 ]]; then
    count=$((1))
    while IFS='\n' read -r line; do
        count=$(($count+1))
    done < "/opt/innotune/settings/knxradios.txt"
    echo "|$count|$2" >> /opt/innotune/settings/knxradios.txt
fi
