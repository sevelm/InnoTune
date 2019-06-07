#!/bin/bash

ifconfig "eth0:backup" inet "172.30.250.250" netmask 255.255.255.0 broadcast 172.30.250.250 route add default dev "eth0:backup" metric 10 ||:
