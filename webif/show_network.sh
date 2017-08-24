#!/bin/bash

##########################################################
# Config


##########################################################

DHCP=$(grep dhcp /etc/network/interfaces | tr -s ' ' | cut -d ' ' -f4)
if [ -z "$DHCP"];
	then  $DHCP=false
fi
IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4)
GATE=$(ip route show | grep 'default' | awk '{print $3}' | tail -n1)
MAC=$(ip addr show eth0 | grep 'link/ether' | tr -s ' ' | cut -d ' ' -f3)
DNS1=$(cat /etc/resolv.conf | grep "nameserver" | cut -d ' ' -f2 | head -n1 | tail -n1)
DNS2=$(cat /etc/resolv.conf | grep "nameserver" | cut -d ' ' -f2 | head -n2 | tail -n1)


echo -e "$DHCP\n""$IP\n""$SUBNET\n""$GATE\n""$MAC\n""$DNS1\n""$DNS2\n" > /opt/innotune/settings/network.txt

case "$1" in
	all) echo "$DHCP;$IP;$SUBNET;$GATE;$MAC;$DNS1;$DNS2";;
        dhcp) echo $DHCP;;
        ip) echo $IP;;
        subnet) echo $SUBNET;;
        gate) echo $GATE;;
        mac) echo $MAC;;
        dns1) echo $DNS1;;
        dns2) echo $DNS2;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac

