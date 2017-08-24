#!/bin/bash

##########################################################
# Config


##########################################################

rm -r /opt/innotune/update/*   ### Update-Ordner leeren
wget http://innotune.at/update_innotune/odroid/update.sh -P /opt/innotune/update/
sudo chmod 777 /opt/innotune/update/update.sh
/opt/innotune/update/update.sh

exit 0


