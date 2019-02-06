#!/bin/bash

cd /var/www/InnoControl/log
find -type f \( -name "*.tar.gz" \) -size +100M -delete
find -type f \( -name "*.log" \) -size +150M -delete
find -type f \( -name "spotify*" -o -name "airplay*" \) -size +10M -delete
