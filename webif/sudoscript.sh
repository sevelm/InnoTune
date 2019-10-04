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
     updateLms) sudo dpkg -i /var/lib/squeezeboxserver/cache/updates/logitechmediaserver_7.9.1_arm.deb
                sudo apt-get -f -y install;;
     updaterunning) upfile=$(ps ax | grep "update.sh" | grep -v grep | wc -l)
                    upfolder=$(ps ax | grep "/update/" | grep -v grep | wc -l)
                    knx=$(ps ax | grep "knxinstaller.sh" | grep -v grep | wc -l)
                    casound=$(ps ax | grep "create_asound.sh" | grep -v grep | wc -l)
                    rmpa=$(ps ax | grep "sudoscript.sh removepa" | grep -v grep | wc -l)
                    out=$(($upfile + $upfolder + $knx + $casound + $rmpa))
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
     reset_lms) update-rc.d logitechmediaserver remove
                /etc/init.d/logitechmediaserver stop
                kill $(ps cax | grep squeezeboxserve | awk '{print $1}')
                rm /var/lib/squeezeboxserver/prefs/server.prefs;;
     stop_lms) update-rc.d logitechmediaserver remove
               /etc/init.d/logitechmediaserver stop
               kill $(ps cax | grep squeezeboxserve | awk '{print $1}');;
     start_lms) /etc/init.d/logitechmediaserver start
                killall knxcallback.sh
                run=$(cat /opt/innotune/settings/knxrun.txt)
                if [[ "$run" -eq 1 ]]; then
                    bash -c 'sleep 15; printf "listen\n" | nc -q 87000 localhost 9090 | /var/www/knxcallback.sh 2>&1 /dev/null &' &
                fi;;
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
                 sed -i "/^KNXD_OPTS/c\KNXD_OPTS=\"-e $2 -E 1.1.245:5 -c -f9 -DTRS -b usb\"" /etc/knxd.conf
             else
                 #KNXD_OPTS="-e $2 -E 1.1.245:5 -b ip:"
                 sed -i "/^KNXD_OPTS/c\KNXD_OPTS=\"-e $2 -E 1.1.245:5 -f9 -b ip:\"" /etc/knxd.conf
             fi;;
     installknx) /var/www/knxinstaller.sh;;
     setknxcmd) /var/www/knxeditcmd.sh "1" "$2" "$3";;
     deleteknxemptyaddr) sed -i "/^|/d" /opt/innotune/settings/knxcmd.txt;;
     deleteknxcmd) /var/www/knxeditcmd.sh "0" "$2" "$3";;
     deleteknxradio) /var/www/knxeditradio.sh "0" "$2";;
     saveknxradio) /var/www/knxeditradio.sh "1" "$2" "$3";;
     addknxradio) /var/www/knxeditradio.sh "2" "$2";;
     resetknxradios) cp /opt/innotune/settings/knxdefaultradios.txt /opt/innotune/settings/knxradios.txt;;
     saveKnxCallback) sed -i "/$2/d" /opt/innotune/settings/knxcallbacks
                      echo "$2|$3|$4" >> /opt/innotune/settings/knxcallbacks;;
     clearKnxCallback) sed -i "/$2/d" /opt/innotune/settings/knxcallbacks;;
     deleteGeneratedTTS) sudo rm -r /media/Soundfiles/tts/*
                         sudo mpc update;;
     fanoperation) options=$(cat /opt/innotune/settings/gpio/fan_options)
                   IFS=';' read -ra data <<< "$options"
                   printf "$2;${data[1]};${data[2]}" > /opt/innotune/settings/gpio/fan_options
                   /var/www/fanreg.sh;;
     fanstate) options=$(cat /opt/innotune/settings/gpio/fan_options)
                   IFS=';' read -ra data <<< "$options"
                   printf "${data[0]};${data[1]};$2" > /opt/innotune/settings/gpio/fan_options
                   /var/www/fanreg.sh;;
     muteoperation) options=$(cat "/opt/innotune/settings/gpio/mute/state$2")
                    IFS=';' read -ra data <<< "$options"
                    printf "$3;${data[1]}" > "/opt/innotune/settings/gpio/mute/state$2"
                    /var/www/mutereg.sh "$2";;
     mutestate) options=$(cat "/opt/innotune/settings/gpio/mute/state$2")
                IFS=';' read -ra data <<< "$options"
                printf "${data[0]};$3" > "/opt/innotune/settings/gpio/mute/state$2"
                /var/www/mutereg.sh "$2";;
    lmswa)
      killall lmslistener.sh
      printf "$2" > /opt/innotune/settings/lmswa.txt
      if [ "$2" -eq "1" ]; then
          printf "listen\n" | nc -q 87000 localhost 9090 | /var/www/lmslistener.sh > /dev/null 2>&1 &
      fi
    ;;
    knxjournal_latest) journalctl -uknxd -n 15;;
    knxjournal_since) journalctl -uknxd --since="$2";;
    journal_size) journalctl --disk-usage;;
    journal_vacuum)
                echo "before: "
                journalctl --disk-usage
                journalctl --vacuum-size="$2"
                echo "after: "
                journalctl --disk-usage;;
    journal_boots) journalctl --list-boots;;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac

exit 0
