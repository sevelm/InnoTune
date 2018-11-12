#!/bin/bash
sudo cp /opt/itunesshare/iTunes\ Music\ Library.xml /opt/iTunesMusicLibrary.xml
sudo chmod 777 /opt/iTunesMusicLibrary.xml

oldpath=$(cat /opt/iTunesMusicLibrary.xml | grep "<key>Music Folder</key><string>" | sed -e "s/<key>Music Folder<\/key><string>\(.*\)<\/string>/\1/")
oldpath=$(echo $oldpath)
echo "s;$oldpath;file:///opt/itunesshare/iTunes%20Media/;g"
sed -i "s;$oldpath;file:///opt/itunesshare/iTunes%20Media/;g" /opt/iTunesMusicLibrary.xml
