#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            create_udevrule.sh                              ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
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
## This script creates the udev rules for the usb audio devices from the      ##
## current usb device mapping.                                                ##
##                                                                            ##
##                                 References                                 ##
## /var/www/create_asound.sh                                                  ##
##                                                                            ##
################################################################################
################################################################################

# iterate through current mapping and set it to the fixed mapping
cp /opt/innotune/settings/mapping_current.txt /opt/innotune/settings/maptemp.txt
if [[ -f /opt/innotune/settings/mapping.txt ]]; then
    cnt=$(cat /opt/innotune/settings/mapping.txt | wc -l)
    for (( c=1; c<=$cnt; c++ ))
    do
        line=$(cat /opt/innotune/settings/mapping.txt | sed "${c}q;d")
        grep -q -F "$line" /opt/innotune/settings/maptemp.txt || echo "$line" >> /opt/innotune/settings/maptemp.txt
    done
fi
> /opt/innotune/settings/mapping.txt
sort /opt/innotune/settings/maptemp.txt -o /opt/innotune/settings/mapping.txt

# remove/reset current mapping/rules
rm /opt/innotune/settings/maptemp.txt
> /opt/innotune/settings/80-usb-audio-id.rules
> /opt/innotune/settings/90-usb-audio-log-remove.rules
> /etc/udev/rules.d/90-usb-audio-log-remove.rules
> /opt/innotune/settings/mapping_current.txt

# static rule file header (checks if subsystem and action match
#                         otherwise it ignores the following rules)
echo "SUBSYSTEM!=\"sound\", GOTO=\"my_usb_audio_end\"" >> /opt/innotune/settings/80-usb-audio-id.rules
echo "ACTION!=\"add\", GOTO=\"my_usb_audio_end\"" >> /opt/innotune/settings/80-usb-audio-id.rules

echo "SUBSYSTEM!=\"sound\", GOTO=\"usb_audio_log_end\"" >> /etc/udev/rules.d/90-usb-audio-log-remove.rules
echo "ACTION!=\"remove\", GOTO=\"usb_audio_log_end\"" >> /etc/udev/rules.d/90-usb-audio-log-remove.rules

# iterate through mapping file to set individual rules for each device
cnt=$(cat /opt/innotune/settings/mapping.txt | wc -l)
for (( c=1; c<=$cnt; c++ ))
do
    line=$(cat /opt/innotune/settings/mapping.txt | sed "${c}q;d")
    name=$(echo $line | cut -d ";" -f1)
    path=$(echo $line | cut -d ";" -f2)
    echo "DEVPATH==\"$path?\", ATTR{id}=\"$name\", RUN+=\"/var/www/log_card.sh $name 1\"" >> /opt/innotune/settings/80-usb-audio-id.rules
    echo "DEVPATH==\"$path?\", RUN+=\"/var/www/log_card.sh $name 0\"" >> /etc/udev/rules.d/90-usb-audio-log-remove.rules
done

# static udev rule footer (if header conditions doesn't match they jump to this point of the file)
echo "LABEL=\"my_usb_audio_end\"" >> /opt/innotune/settings/80-usb-audio-id.rules

echo "LABEL=\"usb_audio_log_end\"" >> /etc/udev/rules.d/90-usb-audio-log-remove.rules

# copy generates rules to udev directory
cp /opt/innotune/settings/80-usb-audio-id.rules /etc/udev/rules.d/80-usb-audio-id.rules
cp /etc/udev/rules.d/90-usb-audio-log-remove.rules /opt/innotune/settings/90-usb-audio-log-remove.rules
