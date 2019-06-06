#!/bin/bash

##########################################################
# Config


##########################################################

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

### Konfiguration USB-Gerät 01
if [ $USB_DEV01 == 1 ]; then
sed -e s/XXX/01/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV01 == 2 ]; then
sed -e s/XXX/01/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 02
if [ $USB_DEV02 == 1 ]; then
sed -e s/XXX/02/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV02 == 2 ]; then
sed -e s/XXX/02/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 03
if [ $USB_DEV03 == 1 ]; then
sed -e s/XXX/03/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV03 == 2 ]; then
sed -e s/XXX/03/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 04
if [ $USB_DEV04 == 1 ]; then
sed -e s/XXX/04/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV04 == 2 ]; then
sed -e s/XXX/04/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 05
if [ $USB_DEV05 == 1 ]; then
sed -e s/XXX/05/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV05 == 2 ]; then
sed -e s/XXX/05/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 06
if [ $USB_DEV06 == 1 ]; then
sed -e s/XXX/06/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV06 == 2 ]; then
sed -e s/XXX/06/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 07
if [ $USB_DEV07 == 1 ]; then
sed -e s/XXX/07/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV07 == 2 ]; then
sed -e s/XXX/07/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 08
if [ $USB_DEV08 == 1 ]; then
sed -e s/XXX/08/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV08 == 2 ]; then
sed -e s/XXX/08/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 09
if [ $USB_DEV09 == 1 ]; then
sed -e s/XXX/09/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV09 == 2 ]; then
sed -e s/XXX/09/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

### Konfiguration USB-Gerät 10
if [ $USB_DEV10 == 1 ]; then
sed -e s/XXX/10/g /var/www/create_asound/asound_stereo_XXX.conf  >> /var/www/create_asound/asound.conf
fi
if [ $USB_DEV10 == 2 ]; then
sed -e s/XXX/10/g /var/www/create_asound/asound_geteilt_XXX.conf  >> /var/www/create_asound/asound.conf
fi

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

exit 0
