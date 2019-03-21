#!/bin/bash
echo "running latest update"

# generic commands for every update
/opt/innotune/update/cache/InnoTune/webif/update/generic.sh

# run the latest update scripts version that is not installed yet
/var/www/update/update002.sh
