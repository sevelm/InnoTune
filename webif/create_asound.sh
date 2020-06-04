#!/bin/bash

##########################################################
# Config


##########################################################

echo "creating asound" > /opt/innotune/settings/updatestatus.txt

/var/www/show_soundcard.sh 0
/var/www/create_udevrule.sh

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

rm /var/www/create_asound/asound.conf

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

cp /var/www/create_asound/asound.conf /etc/asound.conf

sudo alsa force-reload

sleep 1

for i in $(seq -f "%02g" 1 10)
do
    aplay -B 1 -D plug:airplay"$i" > /dev/null 2>&1 & echo $!                          ### Softvol-Regler erstellen
    aplay -B 1 -D plug:airplayli"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
    aplay -B 1 -D plug:airplayre"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen

    aplay -B 1 -D plug:airplay_"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
    aplay -B 1 -D plug:airplayli_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen
    aplay -B 1 -D plug:airplayre_"$i" > /dev/null 2>&1 & echo $!                       ### Softvol-Regler erstellen

    aplay -B 1 -D plug:LineIn"$i" > /dev/null 2>&1 & echo $!                           ### Softvol-Regler erstellen
    aplay -B 1 -D plug:LineInli"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen
    aplay -B 1 -D plug:LineInre"$i" > /dev/null 2>&1 & echo $!                         ### Softvol-Regler erstellen

    aplay -B 1 -D plug:LineIn_"$i" > /dev/null 2>&1 & echo $!                          ### Softvol-Regler erstellen
    aplay -B 1 -D plug:LineInli_"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
    aplay -B 1 -D plug:LineInre_"$i" > /dev/null 2>&1 & echo $!                        ### Softvol-Regler erstellen
done

sleep 1
killall aplay

echo "config finished" > /opt/innotune/settings/updatestatus.txt

exit 0
