#!/bin/bash
sleep 180
ifconfig "eth0:backup" inet "172.30.250.250" netmask 255.255.255.0 broadcast 172.30.250.250
