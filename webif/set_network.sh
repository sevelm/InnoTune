#!/bin/bash

##########################################################
# Config


##########################################################
DHCP=$(cat /opt/innotune/settings/network.txt | head -n1 | tail -n1)
NEW_IP=$(cat /opt/innotune/settings/network.txt | head -n2 | tail -n1)
NEW_SUBNET=$(cat /opt/innotune/settings/network.txt | head -n3 | tail -n1)
NEW_GATEWAY=$(cat /opt/innotune/settings/network.txt | head -n4 | tail -n1)
NEW_DNS1=$(cat /opt/innotune/settings/network.txt | head -n6 | tail -n1)
NEW_DNS2=$(cat /opt/innotune/settings/network.txt | head -n7 | tail -n1)
WLAN=$(cat /opt/innotune/settings/wlan.txt | cut -c1)


if [[ $DHCP ]]; then #select DHCP or STATIC mode
  if [[ $WLAN -eq 1 ]]; then
    cat > /etc/network/interfaces <<EOT
auto lo
iface lo inet loopback

auto eth0
iface eth0 inet dhcp

auto wlan0
iface wlan0 inet dhcp
     wpa-conf /opt/innotune/settings/wpa_supplicant.conf

EOT
  else
    cat > /etc/network/interfaces <<EOT
auto lo
iface lo inet loopback

auto eth0
iface eth0 inet dhcp

EOT
  fi
else
  if [[ $WLAN -eq 1 ]]; then
   cat > /etc/network/interfaces <<EOT
auto lo
iface lo inet loopback

auto eth0
iface eth0 inet dhcp

auto wlan0
iface wlan0 inet static
     wpa-conf /opt/innotune/settings/wpa_supplicant.conf
     address $NEW_IP
     netmask $NEW_SUBNET
     gateway $NEW_GATEWAY
     dns-nameservers $NEW_DNS1 $NEW_DNS2
EOT
  else
   cat > /etc/network/interfaces <<EOT
 auto lo
 iface lo inet loopback

 auto eth0
 iface eth0 inet static
      address $NEW_IP
      netmask $NEW_SUBNET
      gateway $NEW_GATEWAY
      dns-nameservers $NEW_DNS1 $NEW_DNS2
EOT
  fi
fi


#sudo service networking restart
#reboot
#/etc/init.d/networking restart
