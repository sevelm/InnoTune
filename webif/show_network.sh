#!/bin/bash

##########################################################
# Config


##########################################################

DHCP=$(grep dhcp /etc/network/interfaces | tr -s ' ' | cut -d ' ' -f4)
WLAN=$(cat /opt/innotune/settings/wlan.txt | cut -c1)
WFAILED="false"
if [ -z "$DHCP" ];
	then  $DHCP=false
fi
if [[ $WLAN -eq 1 ]]; then
	IP=$(ip route show | grep 'src' | grep 'wlan0' | awk '{print $9}' | tail -n1)
	SUBNET=$( ifconfig wlan0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4)
else
	IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
	if [ $IP = "172.30.250.250" ]; then
		IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
	fi
	SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4)
fi
GATE=$(ip route show | grep 'default' | awk '{print $3}' | tail -n1)
if [ $GATE = "wlan0" ]; then
	GATE=$(ip route show | grep 'default' | awk '{print $3}' | head -n1)
	IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
	if [ $IP = "172.30.250.250" ]; then
		IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
	fi
	SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4)
	WFAILED="true"
	echo "0" > /opt/innotune/settings/wlan.txt
elif [ $GATE = "tun0" ]; then
	GATE=$(ip route show | grep 'via' | awk '{print $3}' | head -n1)
	IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
	if [ $IP = "172.30.250.250" ]; then
		IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
	fi
	SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4)
fi
MAC=$(ip addr show eth0 | grep 'link/ether' | tr -s ' ' | cut -d ' ' -f3)
MACWLAN=$(ip addr show wlan0 | grep 'link/ether' | tr -s ' ' | cut -d ' ' -f3)
DNS1=$(cat /etc/resolv.conf | grep "nameserver" | cut -d ' ' -f2 | head -n1 | tail -n1)
DNS2=$(cat /etc/resolv.conf | grep "nameserver" | cut -d ' ' -f2 | head -n2 | tail -n1)
SSID=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'ssid="' | cut -d '"' -f2)
PSK=$(cat /opt/innotune/settings/wpa_supplicant.conf | grep 'psk="' | cut -d '"' -f2)


echo -e "$DHCP\n""$IP\n""$SUBNET\n""$GATE\n""$MAC\n""$DNS1\n""$DNS2\n" > /opt/innotune/settings/network.txt

case "$1" in
	all) echo "$DHCP;$IP;$SUBNET;$GATE;$MAC;$DNS1;$DNS2;$WLAN;$SSID;$PSK;$WFAILED;$MACWLAN";;
        dhcp) echo $DHCP;;
        ip) echo $IP;;
        subnet) echo $SUBNET;;
        gate) echo $GATE;;
        mac) echo $MAC;;
        dns1) echo $DNS1;;
        dns2) echo $DNS2;;
				wlan) echo $WLAN;;
				ssid) echo $SSID;;
				psk) echo $PSK;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac
