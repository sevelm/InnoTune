#!/bin/bash
# 0 = delete, 1 = add/edit
if [[ "$1" -eq 0 ]]; then
  #delete entry
  sed -i "\;$2;d" /opt/innotune/settings/knxcmd.txt
else
  #delete entry
  sed -i "\;$2;d" /opt/innotune/settings/knxcmd.txt
  #add entry
  echo "$3" >> /opt/innotune/settings/knxcmd.txt
fi
