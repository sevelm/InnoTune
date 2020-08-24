#!/bin/bash

################################################################################
################################################################################
##                                                                            ##
##                            checkpackages.sh                                ##
##                                                                            ##
## Directory:   /var/www/                                                     ##
## Created  :   04.06.2018                                                    ##
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
## Checks if certain important packages aren't installed, if so this script   ##
## tries to install them correctly.                                           ##
## Following packages are checked:                                            ##
## libasound2-plugin-equal (alsa equalizer plugin)                            ##
## libasound2-dev (alsa equalizer plugin)                                     ##
## shairport-sync (airplay)                                                   ##
## libportaudio2 (only on raspberry pi for librespot)                         ##
##                                                                            ##
##                                 References                                 ##
## /var/www/sudoscript.sh                                                     ##
##                                                                            ##
################################################################################
################################################################################

# check if equalizer package is installed
# if not there will be no sound
if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | wc -l) -ne 1 ]]; then
    echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libasound2-plugin-equal
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep installed | wc -l) -eq 1 ]]; then
        echo "equalizer installed" >> /var/www/checkprocesses.log
    else
        echo "equalizer installation failed!" >> /var/www/checkprocesses.log
    fi
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep not-installed | wc -l) -eq 1 ]]; then
    echo "equalizer not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libasound2-plugin-equal
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' libasound2-plugin-equal | grep installed | wc -l) -eq 1 ]]; then
        echo "equalizer installed" >> /var/www/checkprocesses.log
    else
        echo "equalizer installation failed!" >> /var/www/checkprocesses.log
    fi
fi

# check if libasound2 dev package is installed
# if not there will be no sound
if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | wc -l) -ne 1 ]]; then
    echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libasound2-dev
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep installed | wc -l) -eq 1 ]]; then
        echo "equalizer lib installed" >> /var/www/checkprocesses.log
    else
        echo "equalizer lib installation failed!" >> /var/www/checkprocesses.log
    fi
elif [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep not-installed | wc -l) -eq 1 ]]; then
    echo "equalizer lib not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y libasound2-dev
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' libasound2-dev | grep installed | wc -l) -eq 1 ]]; then
        echo "equalizer lib installed" >> /var/www/checkprocesses.log
    else
        echo "equalizer lib installation failed!" >> /var/www/checkprocesses.log
    fi
fi

# check if shairport package is installed
# if not airplay will not work
if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | wc -l) -ne 1 ]]; then
    echo "shairport not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y shairport-sync
    sudo systemctl stop shairport-sync
    sudo systemctl disable shairport-sync
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep installed | wc -l) -eq 1 ]]; then
        echo "shairport installed" >> /var/www/checkprocesses.log
    else
        echo "shairport installation failed!" >> /var/www/checkprocesses.log
    fi
elif [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep not-installed | wc -l) -eq 1 ]]; then
    echo "shairport not installed...installing" >> /var/www/checkprocesses.log
    sudo apt-get install -f -y shairport-sync
    # check if install worked
    if [[ $(dpkg-query -W -f='${Status}\n' shairport-sync | grep installed | wc -l) -eq 1 ]]; then
        echo "shairport installed" >> /var/www/checkprocesses.log
    else
        echo "shairport installation failed!" >> /var/www/checkprocesses.log
    fi
fi

# check only on raspi
if [[ $(cat /etc/os-release | grep Raspbian | wc -l) -ge 1 ]]; then
    echo "raspian image"
    # check if libportaudio2 package is installed
    # if not librespot will not work on a raspberry pi
    if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | wc -l) -ne 1 ]]; then
        echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
        sudo apt-get install -f -y libportaudio2
        # check if install worked
        if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep installed | wc -l) -eq 1 ]]; then
            echo "libportaudio2 installed" >> /var/www/checkprocesses.log
        else
            echo "libportaudio2 installation failed!" >> /var/www/checkprocesses.log
        fi
    elif [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep not-installed | wc -l) -eq 1 ]]; then
        echo "libportaudio2 not installed...installing" >> /var/www/checkprocesses.log
        sudo apt-get install -f -y libportaudio2
        # check if install worked
        if [[ $(dpkg-query -W -f='${Status}\n' libportaudio2 | grep installed | wc -l) -eq 1 ]]; then
            echo "libportaudio2 installed" >> /var/www/checkprocesses.log
        else
            echo "libportaudio2 installation failed!" >> /var/www/checkprocesses.log
        fi
    fi
fi
