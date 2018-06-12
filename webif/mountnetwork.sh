#!/bin/bash

# Parameterliste
# $1  fstab mount String
# $2  Mountpoint-Ordner
# $3  Netzwerkpfad
# $4  Dateisystemtyp
# $5  Mountoptionen

# checkt ob mountpoint order existiert, wenn nicht dann wird dieser angelegt und
# die rechte werden vergeben
if [ ! -d "$2" ]; then
  mkdir "/media/$2"
  chmod 777 "/media/$2"
fi

echo "$2;$3;$4;$1" >> /opt/innotune/settings/netmount.txt
echo "$1" >> /etc/fstab
sudo mount -t "$4" -o "$5" "$3" "$2"
