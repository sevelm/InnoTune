#!/bin/bash
#mount will be gone next reboot

#delete old credentials
> /opt/ituneslogin
sudo rm /opt/iTunesMusicLibrary.xml

#delete from fstab
fstab=$(grep -v "/opt/itunesshare" /etc/fstab)
echo "$fstab" > /etc/fstab
