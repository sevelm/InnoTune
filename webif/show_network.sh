#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            show_network.sh                                 ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   24.08.2017 (date of initial git commit)                       ##
## Edited   :   27.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Severin Elmecker                                              ##
##              Alexander Elmecker                                            ##
##              Julian Hoerbst                                                ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## This script shows the current ip address and other interface infos.        ##
## Wifi has the highest priority following ethernet.                          ##
## VPN tunnel and backup ip address should be discarded.                      ##
##                                                                            ##
##                                                                            ##
##                                 Parameter                                  ##
## $1 output                                                                  ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

DHCP=$(grep dhcp /etc/network/interfaces | tr -s ' ' | cut -d ' ' -f4)
WLAN=$(cat /opt/innotune/settings/wlan.txt | cut -c1)
WFAILED="false"
if [ -z "$DHCP" ]; then
    $DHCP=false
fi

# if wifi active show wifi address
if [[ $WLAN -eq 1 ]]; then
    IP=$(ip route show | grep 'src' | grep 'wlan0' | awk '{print $9}' | tail -n1)
    SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4 | grep -v '^$' || ifconfig eth0 | awk '/netmask/{print $4}')
else
    IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
    if [ $IP = "172.30.250.250" ]; then
        IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
    fi
    SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4 | grep -v '^$' || ifconfig eth0 | awk '/netmask/{print $4}')
fi

# else show default ip
GATE=$(ip route show | grep 'default' | awk '{print $3}' | tail -n1)
if [ $GATE = "wlan0" ]; then
    GATE=$(ip route show | grep 'default' | awk '{print $3}' | head -n1)
    IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
    if [ $IP = "172.30.250.250" ]; then
        IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
    fi
    SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4 | grep -v '^$' || ifconfig eth0 | awk '/netmask/{print $4}')
    WFAILED="true"
    echo "0" > /opt/innotune/settings/wlan.txt
elif [ $GATE = "tun0" ]; then
    GATE=$(ip route show | grep 'via' | awk '{print $3}' | head -n1)
    IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | tail -n1)
    if [ $IP = "172.30.250.250" ]; then
        IP=$(ip route show | grep 'src' | grep 'eth0' | awk '{print $9}' | head -n1)
    fi
    SUBNET=$( ifconfig eth0 | grep "Bcast:" | tr -s ' ' | cut -d: -f4 | grep -v '^$' || ifconfig eth0 | awk '/netmask/{print $4}')
fi

MAC=$(ip addr show eth0 | grep 'link/ether' | tr -s ' ' | cut -d ' ' -f3)
MACWLAN=$(ip addr show wlan0 | grep 'link/ether' | tr -s ' ' | cut -d ' ' -f3)
DNS1=$((systemd-resolve --status 2>/dev/null || echo "DNS Servers: $(cat /etc/resolv.conf | grep "^nameserver" | cut -d ' ' -f2 | head -n1)") | grep "DNS Servers" | awk '{print $3}')
DNS2=$(cat /etc/resolv.conf | grep "^nameserver" | cut -d ' ' -f2 | head -n2 | tail -n1)
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
