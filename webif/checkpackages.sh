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
  echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y libasound2-dev
fi

if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | wc -l) -ne 1 ]]; then
  echo "shairport not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y shairport-sync
elif [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep not-installed | wc -l) -eq 1 ]]; then
  echo "shairport not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -y shairport-sync
fi

#check only on raspi
if [[ $(cat /etc/os-release | grep Raspbian | wc -l) -ge 1 ]]; then
  echo "raspian image"
  if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | wc -l) -ne 1 ]]; then
    echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -y libportaudio2
  elif [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep not-installed | wc -l) -eq 1 ]]; then
    echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -y libportaudio2
  fi
fi
