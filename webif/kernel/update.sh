#!/bin/bash
if [[ $(uname -r | grep rockchip | wc -l) -eq 1 ]] && [[ $(uname -r | cut -d '.' -f3 | cut -d '-' -f1) -lt $(cat /var/www/kernel/version.txt | cut -d '.' -f3 | cut -d '-' -f1) ]]; then
  sudo dpkg -i /var/www/kernel/img-135.deb
fi
