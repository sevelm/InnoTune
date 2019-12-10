#!/bin/bash
cd /tmp
wget -q https://raw.githubusercontent.com/sevelm/InnoTune/master/importantUpdate.txt
newCount=$(head -n1 /tmp/importantUpdate.txt)
oldCount=$(head -n1 /opt/innotune/settings/importantUpdate.txt)

if [[ "$oldCount" -lt "$newCount" ]]; then
    /var/www/update.sh
fi
