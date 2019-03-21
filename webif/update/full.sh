#!/bin/bash
echo "running full update"

# generic commands for every update
/opt/innotune/update/cache/InnoTune/webif/update/generic.sh

# run first update script (they reference a newer scripts if it exists)
/var/www/update/update001.sh
