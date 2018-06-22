#!/bin/bash
cd /var/www/beta/data
cp reset_udevrule.sh /var/www/reset_udevrule.sh
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
