#!/bin/bash

##########################################################
# Config
##########################################################

# set_vol)  # removes leading zeros from card out (Amp 08 and 09 wont work otherwise)
#           card=$(echo $2 | sed 's/^0*//')
#          amixer -c "$card" set "$3"_"$2" "$4"%; amixer -c "$card" set "$3"li_"$2" "$4"%; amixer -c "$card" set "$3"re_"$2" "$4"%;;

case "$1" in
     store_settings) cd /opt/innotune/settings/
                              /var/www/get_lms_players.sh
                              zip -r /var/www/upload_download/settings.zip ./;;
     restore_settings) unzip -o /var/www/upload_download/settings.zip -d /opt/innotune/settings
                                 sudo chmod -R 777 /opt/innotune/settings;;
     update) /var/www/update.sh;;
     fullupdate) /var/www/update/full.sh;;
     latestupdate) /var/www/update/latest.sh;;
     updateKernel) /var/www/kernel/update.sh;;
     updateBeta) /var/www/update.sh
                 /var/www/beta/update.sh;;
     fixDependencies) sudo /var/www/checkpackages.sh
                      sudo apt-get -y install shairport-sync
                      sudo apt-get -f -y install
                      sudo systemctl stop shairport-sync
                      sudo systemctl disable shairport-sync;;
     set_linein) /var/www/set_linein.sh "$2" "$3" "$4" "$5";;
     setplayer) /etc/init.d/setplayer restart;;
     shnet) /var/www/show_network.sh "$2";;
     setnet) /var/www/set_network.sh "$2";;
     testwlan) /var/www/testwlan.sh "$2" "$3";;
     listwifi) /var/www/listwifis.sh;;
     create_asound) /var/www/create_asound.sh;;
     show_vol_equal) /var/www/show_vol_equal.sh "$2" "$3" ;;
     showsoundcard) /var/www/show_soundcard.sh "$2";;
     resetudev) /var/www/reset_udevrule.sh;;
     set_vol) amixer -c "$2" set "$3"_"$2" "$4"%; amixer -c "$2" set "$3"li_"$2" "$4"%; amixer -c "$2" set "$3"re_"$2" "$4"%;;
     show_eq) /var/www/show_eq_settings.sh "$2";;
     set_eq) /var/www/set_eq_settings.sh "$2" "$3" "$4";;
     mpdvolplay) killall mpdvolplay
                 /var/www/src/mpdvolplay "$2";;
     mpdstop) mpc stop
              mpc repeat off;;
     mpdrepeat) mpc repeat on;;
     reboot) reboot;;
     reset_lms) /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove  & killall squeezeboxserver
                rm /var/lib/squeezeboxserver/prefs/server.prefs;;
     stop_lms) /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove  & killall squeezeboxserver;;
     start_lms) /etc/init.d/logitechmediaserver start;;
     start_sendudp) /etc/init.d/sendUDP start & update-rc.d sendUDP defaults;;
     stop_sendudp) /etc/init.d/sendUDP stop & update-rc.d sendUDP remove;;
     password) echo admin:"$(cat /opt/innotune/settings/web_settings.txt | head -n1  | tail -n1)">/opt/innotune/settings/password.txt
               sed -i 's/server.port =.*$/server.port = '$(cat /opt/innotune/settings/web_settings.txt | head -n2  | tail -n1)'/' /etc/lighttpd/lighttpd.conf
               /etc/init.d/lighttpd restart;;
     sendmail) /var/www/sendmail.sh "$2" "$3" > /var/www/return_values/sendmail.txt;;
     ttsvolplay) /var/www/src/ttsvolplay "$2";;
     killtts) sudo killall ttsvolplay;;
     usbmount) sudo sed -i 's/^\(ENABLED\).*/\1'="$2"'/'  /etc/usbmount/usbmount.conf;;
     networkmount) echo $(/var/www/mountnetwork.sh "$2" "$3" "$4" "$5" "$6");;
     removenetworkmount) /var/www/unmountnetwork.sh "$2" "$3" "$4" "$5";;
     itunesmount) sudo /var/www/itunesmnt.sh "$2" "$3" "$4";;
     itunesrefresh) sudo /var/www/itunesrefresh.sh;;
     itunesunmount) sudo /var/www/itunesumnt.sh;;
     checkpa) out=$(ps cax | grep pulseaudio | wc -l)
              out2=$(dpkg-query -W -f='${Status}\n' pulseaudio | cut -d ' ' -f 3)
              echo "$out;$out2";;
     logports) killall tcpdump
               sudo /var/www/logports.sh "$2" &> /dev/null;;
     checklogports) out=$(sudo ps cax | grep tcpdump | wc -l)
                    echo "$out";;
     removepa) sudo apt-get -y purge pulseaudio;;
     addradio) sudo /var/www/radiohistory.sh "$2" "$3";;
     reinstall) OUTPUT=$(sudo /var/www/reinstallPackage.sh "$2")
                echo "${OUTPUT}";;
     reinstall_lms) sudo /var/www/reinstall_lms.sh > /var/www/InnoControl/log/reinstall_lms.log;;
     reset) OUTPUT=$(/var/www/reset.sh "$2")
            echo "$OUTPUT";;
     getknx) settings=$(cat /opt/innotune/settings/knx.txt)
             running=$(cat /opt/innotune/settings/knxrun.txt)
             current=$(ps cax | grep knx | wc -l)
             echo "$settings;$running;$current";;
     runknx) /var/www/knxrun.sh "$2" &> /dev/null;;
     setknx) echo "$2" > /opt/innotune/settings/knx.txt
             #edit knx address in /etc/knxd.conf
             sed -i "/^KNXD_OPTS/c\KNXD_OPTS=\"-e $2 -E 1.1.245:1 -c -DTRS -b usb\"" /etc/knxd.conf;;
     installknx) /var/www/knxinstaller.sh;;
     setknxcmd) /var/www/knxeditcmd.sh "1" "$2" "$3";;
     deleteknxcmd) /var/www/knxeditcmd.sh "0" "$2" "$3";;
     deleteGeneratedTTS) sudo rm -r /media/Soundfiles/tts/*
                         sudo mpc update;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac

exit 0
