#!/bin/bash
#######################################################
#
# This script is used to execute commands on halt, shudown or reboot.
# It runs in an systemd service.
#
#######################################################

### saving LMS Preferences ###
cp /var/lib/squeezeboxserver/prefs/server.prefs /opt/server.prefs
sync
