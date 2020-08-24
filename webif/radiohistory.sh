#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             radiohistory.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   20.12.2018                                                    ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script adds a new entry to the radio history, but deletes any entries ##
## matching beforehand to avoid duplicates.                                   ##
## There can also only be 20 entries, if a new one is added the oldest will   ##
## be deleted.                                                                ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 radio entry                                                             ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# count entries
entries=$(cat /opt/innotune/settings/history.txt | wc -l)

# delete entries with same name
sed -i "\;$1;d" /opt/innotune/settings/history.txt

# if there are 20 or more entries remove oldest entry (entry at position 0)
if [ $entries -ge 20 ]; then
    sed -i '1d' /opt/innotune/settings/history.txt
fi

# add entry to file
echo "$2" >> /opt/innotune/settings/history.txt
