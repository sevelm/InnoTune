#!/bin/bash

# Parameterliste
# $1  fstab mount String
# $2  Mountpoint-Ordner
# $3  Netzwerkpfad
# $4  Dateisystemtyp
# $5  Mountoptionen

# checkt ob mountpoint order existiert, wenn nicht dann wird dieser angelegt und
# die rechte werden vergeben
if [ ! -d "/media/$2" ]; then
  sudo mkdir "/media/$2"
  sudo chmod 777 "/media/$2"
fi

sudo mount -t "$4" -o "$5" "$3" "$2"
# check ob mount befehl funktioniert hat, wenn ja dann wird mount gespeichert
if [[ $? -eq 0 ]]; then
    echo "successfully mounted"
    echo "$2;$3;$4;$1" >> /opt/innotune/settings/netmount.txt
    echo "$1" >> /etc/fstab
else
    echo "error not saving networkmount"
fi
