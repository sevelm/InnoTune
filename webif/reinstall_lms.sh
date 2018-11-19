#!/bin/bash
sudo apt-get remove --purge -y logitechmediaserver
sudo apt-get install -y libio-socket-ssl-perl
os=$(dpkg --print-architecture)
if [ "$os" = "armhf" ]; then os=arm; fi
url="http://www.mysqueezebox.com/update/?version=7.9.2&revision=1&geturl=1&os=deb$os"
latest_lms=$(wget -q -O - "$url")
mkdir -p /sources
cd /sources
wget $latest_lms
lms_deb=${latest_lms##*/}
sudo dpkg -i $lms_deb
sudo apt-get -f -y install
