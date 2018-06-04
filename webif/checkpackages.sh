#!/bin/bash
if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | wc -l) -ne 1 ]]; then
  echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y libasound2-plugin-equal
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep not-installed | wc -l) -eq 1 ]]; then
  echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y libasound2-plugin-equal
fi

if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | wc -l) -ne 1 ]]; then
  echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y libasound2-dev
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep not-installed | wc -l) -eq 1 ]]; then
  echo "install libasound2 dev"
  echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
fi
