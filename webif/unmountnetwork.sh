#!/bin/bash
# $1 Netzwerkpfad
# $2 Mountordner
# Löscht net mount einträge aus files heraus und unmounted das gerät
netmount=$(grep -v "$1;$2" /opt/innotune/settings/netmount.txt)
fstab=$(grep -v "$2 $1" /etc/fstab)
echo "$netmount" > /opt/innotune/settings/netmount.txt
echo "$fstab" > /etc/fstab
sudo umount -f "$1"
