#!/bin/bash
#
# $1 path
# $2 username
# $3 password

#refresh credentials file
> /opt/ituneslogin
echo "username=$2" >> /opt/ituneslogin
echo "password=$3" >> /opt/ituneslogin

#mount network manually
sudo mount -t cifs -o credentials=/opt/ituneslogin "$1" /opt/itunesshare &> /tmp/mttmp
# check ob mount befehl funktioniert hat, wenn ja dann wird mount gespeichert
if [[ $? -eq 0 ]]; then
    echo "successfully mounted"
    #add to fstab for automount
    echo "$1 /opt/itunesshare cifs credentials=/opt/ituneslogin 0 0" >> /etc/fstab
    sudo /var/www/itunesrefresh.sh
else
    error=$(cat /tmp/mttmp)
    echo "error not saving networkmount. $error"
fi
