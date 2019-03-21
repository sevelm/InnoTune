#!/bin/bash
echo "running latest update"

# generic commands for every update
/opt/innotune/update/cache/InnoTune/webif/update/generic.sh

# run the latest update scripts version that is not installed yet
# the cnt number should select the script updateXXX, where XXX = cnt + 1
cnt=$(cat /opt/innotune/settings/update_cnt.txt)
if [[ "$cnt" -ge "1" ]]; then
    /var/www/update/update002.sh
else
    /var/www/update/update001.sh
fi
