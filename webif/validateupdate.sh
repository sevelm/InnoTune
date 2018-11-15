#!/bin/bash
validate=$(cat /opt/innotune/settings/validate.txt | head -n1 | tail -n1)
if [ $validate == "1" ]; then
  > /var/www/InnoControl/log/validate.log
  declare -a packages=("php5.6-gd" "php5.6-curl" "libasound2-plugin-equal"
                       "libasound2-dev" "shairport-sync" "usbmount" "zip"
                       "mpc" "bc" "cifs-utils")

  for package in "${packages[@]}"
  do
    echo "checking $package..."
    if [[ $(dpkg-query -W -f='${Status}\n' "$package" | wc -l) -ne 1 ]]; then
      sudo apt-get install -f -y "$package"
      if [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep installed | wc -l) -eq 1 ]]; then
        echo "$package;installed" >> /var/www/InnoControl/log/validate.log
      else
        echo "$package;failed" >> /var/www/InnoControl/log/validate.log
      fi
    elif [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep not-installed | wc -l) -eq 1 ]]; then
      sudo apt-get install -f -y "$package"
      if [[ $(dpkg-query -W -f='${Status}\n' "$package" | grep installed | wc -l) -eq 1 ]]; then
        echo "$package;installed" >> /var/www/InnoControl/log/validate.log
      else
        echo "$package;failed" >> /var/www/InnoControl/log/validate.log
      fi
    fi
  done
  echo "0" > /opt/innotune/settings/validate.txt
fi
