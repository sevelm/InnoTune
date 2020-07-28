#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                                 update.sh                                  ##
##                                                                            ##
## Directory:   /var/www/beta/                                                ##
## Created  :   22.06.2018                                                    ##
## Edited   :   27.07.2020                                                    ##
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
## This script installs beta software over the current main software.         ##
## The beta files are localted in the ./data/ directory.                      ##
##                                                                            ##
## This scripts is not used anymore                                           ##
##                                                                            ##
##                                 References                                 ##
## /var/www/update/sudoscript.sh                                              ##
##                                                                            ##
################################################################################
################################################################################

cd /var/www/beta/data
cp create_udevrule.sh /var/www/create_udevrule.sh
cp create_asound.sh /var/www/create_asound.sh
cp log_card.sh /var/www/log_card.sh
cp set_linein.sh /var/www/set_linein.sh
cp show_vol_equal.sh /var/www/show_vol_equal.sh
cp sudoscript.sh /var/www/sudoscript.sh

cp asound_geteilt_XXX.conf /var/www/create_asound/asound_geteilt_XXX.conf
cp asound_stereo_XXX.conf /var/www/create_asound/asound_stereo_XXX.conf

cp upload.php /var/www/InnoControl/scripts/upload.php

sudo chmod -R 777 /var/www/

#add script to cron if it isn't already added
is_added=$(crontab -l | grep check_soundcards.sh | wc -l)
if [[ $is_added -eq 0 ]]; then
    crontab -l | { cat; echo "*/15 * * * * /var/www/check_soundcards.sh"; } | crontab -
fi

cp /var/www/beta/version.txt /var/www/version.txt
