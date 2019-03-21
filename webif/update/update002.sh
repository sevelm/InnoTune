#!/bin/bash
echo "running update002"

# add commands here

# set new update count and reference to newer update file
sudo echo "2" > /opt/innotune/settings/update_cnt.txt
echo "100% - finished update" > /opt/innotune/settings/updatestatus.txt
#/var/www/update/update003.sh
