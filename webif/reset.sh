#!/bin/sh

if [ "$1" = "net" ]
then
   sed -i "1s/.*/"dhcp"/" /opt/innotune/settings/network.txt
   sudo /var/www/sudoscript.sh setnet

   OUTPUT="network";
fi

if [ "$1" = "usb" ]
then
    for entry in "/opt/innotune/settings/settings_player"/*
    do
      echo "0 \n\n\n\n\n\n\n\n\n\n\n\n" > $entry
    done

    echo "0\n0" > /opt/innotune/settings/changedconf.txt

    OUTPUT="usb";
fi

if [ "$1" = "playlists" ]
then
    echo "Alarm\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" > /opt/innotune/settings/mpdvolplay.txt
    echo "Gong\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt
    echo "Gong2\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt
    echo "Vorwarnung\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0\n0" >> /opt/innotune/settings/mpdvolplay.txt

fi

echo "${OUTPUT}";