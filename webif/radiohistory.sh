#!/bin/bash
#count entries
entries=$(cat /opt/innotune/settings/history.txt | wc -l)

#delete entries with same name
sed -i "\;$1;d" /opt/innotune/settings/history.txt

#if there are 20 or more entries remove oldest entry (entry at position 0)
if [ $entries -ge 20 ]; then
  sed -i '1d' /opt/innotune/settings/history.txt
fi

#add entry to file
echo "$2" >> /opt/innotune/settings/history.txt
