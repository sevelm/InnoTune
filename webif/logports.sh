#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                               logports.sh                                  ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   06.02.2019                                                    ##
## Edited   :   28.07.2020                                                    ##
## Company  :   InnoTune elektrotechnik Severin Elmecker                      ##
## Email    :   office@innotune.at                                            ##
## Website  :   https://innotune.at/                                          ##
## Git      :   https://github.com/sevelm/InnoTune/                           ##
## Authors  :   Alexander Elmecker                                            ##
##                                                                            ##
################################################################################
##                                                                            ##
##                                Description                                 ##
##                                                                            ##
## Starts or stop logging the tcp dump of following:                          ##
## tcp 9090 (LMS cmd interface)                                               ##
## tcp 9000 (LMS Interface)                                                   ##
## udp 3865 (xPL)                                                             ##
##                                                                            ##
## When stopped the log files will be compressed into a single file for       ##
## downloading.                                                               ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/checklogports.sh                                                  ##
##                                                                            ##
################################################################################
################################################################################

if [[ "$1" -eq "1" ]]; then
    # start tcpdumps on:
    # tcp 9090 (LMS cmd interface)
    # tcp 9000 (LMS Interface)
    # udp 3865 (xPL)
    tcpdump -s 0 -A 'tcp dst port 9090' | grep --line-buffered "00:00:00:00:\|.*innotune.*length [1-9][0-9]" >> /var/www/InnoControl/log/tcpdump9090.log &
    tcpdump -s 0 -A 'tcp dst port 9000 and (tcp[((tcp[12:1] & 0xf0) >> 2):4] = 0x47455420)' >> /var/www/InnoControl/log/tcpdump9000.log &
    tcpdump -s 0 -A 'udp dst port 3865' >> /var/www/InnoControl/log/udpdump3865.log
else
    # kill all tcpdump processes
    killall tcpdump
    # compress all logs to a single file
    cd /var/www/InnoControl/log/
    tar cvf logports.tar.gz tcpdump9090.log tcpdump9000.log udpdump3865.log
    # reset log files
    > tcpdump9090.log
    > tcpdump9000.log
    > udpdump3865.log
fi
