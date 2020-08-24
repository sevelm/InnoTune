#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            itunesrefresh.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   12.11.2018                                                    ##
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
## This script refreshes the itunes music library meta-file, used by the LMS  ##
## plugin for playing the libraries' songs.                                   ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
## /var/www/itunesmnt.sh                                                      ##
##                                                                            ##
################################################################################
################################################################################

# copy library from network storage to local fs
# /opt/iTunesMusicLibrary.xml is the entry for the LMS plugin
sudo cp /opt/itunesshare/iTunes\ Music\ Library.xml /opt/iTunesMusicLibrary.xml
sudo chmod 777 /opt/iTunesMusicLibrary.xml

# resets the old path with the 'local' filesystem path
oldpath=$(cat /opt/iTunesMusicLibrary.xml | grep "<key>Music Folder</key><string>" | sed -e "s/<key>Music Folder<\/key><string>\(.*\)<\/string>/\1/")
oldpath=$(echo $oldpath)
echo "s;$oldpath;file:///opt/itunesshare/iTunes%20Media/;g"
sed -i "s;$oldpath;file:///opt/itunesshare/iTunes%20Media/;g" /opt/iTunesMusicLibrary.xml
