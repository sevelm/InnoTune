#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                             create_asound.sh                               ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##              Severin Elmecker                                              ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script creates the alsa configuration (asound.conf) with the config   ##
## values for each inno amp.                                                  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

echo "creating asound" > /opt/innotune/settings/updatestatus.txt

# check plugged audio devices and create udev rule for usb mapping
/var/www/show_soundcard.sh 0
/var/www/create_udevrule.sh

# get mode of all 10 devices
# seems to be obsolete
USB_DEV01=$(cat /opt/innotune/settings/settings_player/dev01.txt | head -n1  | tail -n1)
USB_DEV02=$(cat /opt/innotune/settings/settings_player/dev02.txt | head -n1  | tail -n1)
USB_DEV03=$(cat /opt/innotune/settings/settings_player/dev03.txt | head -n1  | tail -n1)
USB_DEV04=$(cat /opt/innotune/settings/settings_player/dev04.txt | head -n1  | tail -n1)
USB_DEV05=$(cat /opt/innotune/settings/settings_player/dev05.txt | head -n1  | tail -n1)
USB_DEV06=$(cat /opt/innotune/settings/settings_player/dev06.txt | head -n1  | tail -n1)
USB_DEV07=$(cat /opt/innotune/settings/settings_player/dev07.txt | head -n1  | tail -n1)
USB_DEV08=$(cat /opt/innotune/settings/settings_player/dev08.txt | head -n1  | tail -n1)
USB_DEV09=$(cat /opt/innotune/settings/settings_player/dev09.txt | head -n1  | tail -n1)
USB_DEV10=$(cat /opt/innotune/settings/settings_player/dev10.txt | head -n1  | tail -n1)

# remove previous create asound config
rm /var/www/create_asound/asound.conf

# iterate over all 10 devices
# gets device mode and appends alsa plugs for the device in the config file
for i in $(seq -f "%02g" 1 10)
do
    mode=$(cat "/opt/innotune/settings/settings_player/dev$i.txt" | head -n1  | tail -n1)
    if [ $mode == 1 ]; then
        stm=$(sed '14q;d' "/opt/innotune/settings/settings_player/dev$i.txt")
        if [ $stm == 1 ]; then
            sed -e "s/XXX/$i/g" /var/www/create_asound/asound_stereo_mono_XXX.conf  >> /var/www/create_asound/asound.conf
        else
            sed -e "s/XXX/$i/g" /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
        fi
    elif [ $mode == 2 ]; then
        sed -e "s/XXX/$i/g" /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
    fi
done

echo "restarting alsa, init softvol" > /opt/innotune/settings/updatestatus.txt

# copy newly generated config over old alsa config
cp /var/www/create_asound/asound.conf /etc/asound.conf

# reload alsa with new config
sudo alsa force-reload

sleep 1

# iterate over all 10 devices again
# use aplay on each pcm plug to generate the softvol controls
for i in $(seq -f "%02g" 1 10)
do
    aplay -B 1 -D plug:airplay"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:airplayli"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:airplayre"$i" > /dev/null 2>&1 & echo $!

    aplay -B 1 -D plug:airplay_"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:airplayli_"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:airplayre_"$i" > /dev/null 2>&1 & echo $!

    aplay -B 1 -D plug:LineIn"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:LineInli"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:LineInre"$i" > /dev/null 2>&1 & echo $!

    aplay -B 1 -D plug:LineIn_"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:LineInli_"$i" > /dev/null 2>&1 & echo $!
    aplay -B 1 -D plug:LineInre_"$i" > /dev/null 2>&1 & echo $!
done

# kill all spawned aplay processes used for generating softvol controls
sleep 1
killall aplay

echo "config finished" > /opt/innotune/settings/updatestatus.txt

exit 0
