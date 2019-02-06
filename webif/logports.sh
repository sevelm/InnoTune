#!/bin/bash
if [[ "$1" -eq "1" ]]; then
  tcpdump -s 0 -A 'tcp dst port 9090' | grep --line-buffered "00:00:00:00:\|.*innotune.*length [1-9][0-9]" >> /var/www/InnoControl/log/tcpdump9090.log &
  tcpdump -s 0 -A 'tcp dst port 9000 and (tcp[((tcp[12:1] & 0xf0) >> 2):4] = 0x47455420)' >> /var/www/InnoControl/log/tcpdump9000.log &
  tcpdump -s 0 -A 'udp dst port 3865' >> /var/www/InnoControl/log/udpdump3865.log
else
  killall tcpdump
  cd /var/www/InnoControl/log/
  tar cvf logports.tar.gz tcpdump9090.log tcpdump9000.log udpdump3865.log
  > tcpdump9090.log
  > tcpdump9000.log
  > udpdump3865.log
fi
