#!/bin/bash
if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | wc -l) -ne 1 ]]; then
  echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y libasound2-plugin-equal
  if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep installed | wc -l) -eq 1 ]]; then
    echo "equalizer installed" >> /var/www/checkprocesses.log
  else
    echo "equalizer installation failed!" >> /var/www/checkprocesses.log
  fi
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep not-installed | wc -l) -eq 1 ]]; then
  echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y libasound2-plugin-equal
  if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep installed | wc -l) -eq 1 ]]; then
    echo "equalizer installed" >> /var/www/checkprocesses.log
  else
    echo "equalizer installation failed!" >> /var/www/checkprocesses.log
  fi
fi

if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | wc -l) -ne 1 ]]; then
  echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y libasound2-dev
  if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep installed | wc -l) -eq 1 ]]; then
    echo "equalizer lib installed" >> /var/www/checkprocesses.log
  else
    echo "equalizer lib installation failed!" >> /var/www/checkprocesses.log
  fi
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep not-installed | wc -l) -eq 1 ]]; then
  echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y libasound2-dev
  if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep installed | wc -l) -eq 1 ]]; then
    echo "equalizer lib installed" >> /var/www/checkprocesses.log
  else
    echo "equalizer lib installation failed!" >> /var/www/checkprocesses.log
  fi
fi

if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | wc -l) -ne 1 ]]; then
  echo "shairport not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y shairport-sync
  sudo systemctl stop shairport-sync
  sudo systemctl disable shairport-sync
  if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep installed | wc -l) -eq 1 ]]; then
    echo "shairport installed" >> /var/www/checkprocesses.log
  else
    echo "shairport installation failed!" >> /var/www/checkprocesses.log
  fi
elif [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep not-installed | wc -l) -eq 1 ]]; then
  echo "shairport not installed...installing" >> /var/www/checkprocesses.log
  sudo apt-get install -f -y shairport-sync
  if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep installed | wc -l) -eq 1 ]]; then
    echo "shairport installed" >> /var/www/checkprocesses.log
  else
    echo "shairport installation failed!" >> /var/www/checkprocesses.log
  fi
fi

#check only on raspi
if [[ $(cat /etc/os-release | grep Raspbian | wc -l) -ge 1 ]]; then
  echo "raspian image"
  if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | wc -l) -ne 1 ]]; then
    echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libportaudio2
    if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep installed | wc -l) -eq 1 ]]; then
      echo "libportaudio2 installed" >> /var/www/checkprocesses.log
    else
      echo "libportaudio2 installation failed!" >> /var/www/checkprocesses.log
    fi
  elif [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep not-installed | wc -l) -eq 1 ]]; then
    echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libportaudio2
    if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep installed | wc -l) -eq 1 ]]; then
      echo "libportaudio2 installed" >> /var/www/checkprocesses.log
    else
      echo "libportaudio2 installation failed!" >> /var/www/checkprocesses.log
    fi
  fi
fi
