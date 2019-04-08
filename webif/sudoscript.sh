#!/bin/bash

##########################################################
# Config
##########################################################

case "$1" in
     store_settings) cd /opt/innotune/settings/
                              /var/www/get_lms_players.sh
                              zip -r /var/www/upload_download/settings.zip ./;;
     restore_settings) unzip -o /var/www/upload_download/settings.zip -d /opt/innotune/settings \
                       -x mapping.txt mapping_current.txt update_cnt.txt updatestatus.txt 80-usb-audio-id.rules 90-usb-audio-log-remove.rules
                       sudo chmod -R 777 /opt/innotune/settings;;
     update) /var/www/update.sh;;
     fullupdate) /var/www/update/full.sh;;
     latestupdate) /var/www/update/latest.sh;;
     updateKernel) /var/www/kernel/update.sh;;
     updateBeta) /var/www/update.sh
                 /var/www/beta/update.sh;;
     updaterunning) upfile=$(ps ax | grep "update.sh" | grep -v grep | wc -l)
                    upfolder=$(ps ax | grep "/update/" | grep -v grep | wc -l)
                    out=$(($upfile + $upfolder))
                    echo "$out";;
     fixDependencies) sudo /var/www/checkpackages.sh
                      sudo apt-get -y install shairport-sync
                      sudo dpkg --configure -a
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
     set_vol) /var/www/set_vol.sh "$2" "$3" "$4";;
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
             interfaces=$(lsusb -v | grep -E '\<(Bus|iProduct)' 2>/dev/null | grep KNX | wc -l)
             echo "$settings;$running;$current;$interfaces";;
     runknx) /var/www/knxrun.sh "$2" &> /dev/null;;
     setknx) echo "$3;$2" > /opt/innotune/settings/knx.txt
             #edit knx address in /etc/knxd.conf
             if [ "$3" -eq "1" ]; then
                 #KNXD_OPTS="-e $2 -E 1.1.245:1 -c -DTRS -b usb"
                 sed -i "/^KNXD_OPTS/c\KNXD_OPTS=\"-e $2 -E 1.1.245:1 -c -DTRS -b usb\"" /etc/knxd.conf
             else
                 #KNXD_OPTS="-e $2 -E 0.0.2:8 -u /tmp/eib -b ip:"
                 sed -i "/^KNXD_OPTS/c\KNXD_OPTS=\"-e $2 -E 1.1.245:1 -u /tmp/eib -b ip:\"" /etc/knxd.conf
             fi;;
     installknx) /var/www/knxinstaller.sh;;
     setknxcmd) /var/www/knxeditcmd.sh "1" "$2" "$3";;
     deleteknxcmd) /var/www/knxeditcmd.sh "0" "$2" "$3";;
     deleteknxradio) /var/www/knxeditradio.sh "0" "$2";;
     saveknxradio) /var/www/knxeditradio.sh "1" "$2" "$3";;
     addknxradio) /var/www/knxeditradio.sh "2" "$2";;
     resetknxradios) cp /opt/innotune/settings/knxdefaultradios.txt /opt/innotune/settings/knxradios.txt;;
     deleteGeneratedTTS) sudo rm -r /media/Soundfiles/tts/*
                         sudo mpc update;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac

exit 0
