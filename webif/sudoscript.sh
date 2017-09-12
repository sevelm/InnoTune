#!/bin/bash

##########################################################
# Config
##########################################################

 
case "$1" in
     store_settings) cd /opt/innotune/settings/
                              zip -r /var/www/upload_download/settings.zip ./;;
     restore_settings) unzip -o /var/www/upload_download/settings.zip -d /opt/innotune/settings
                                 sudo chmod -R 777 /opt/innotune/settings;;
     update) /var/www/update.sh;;
     set_linein) /var/www/set_linein.sh "$2" "$3" "$4";;
     setplayer) /etc/init.d/setplayer restart;;
     shnet) /var/www/show_network.sh "$2";;
     setnet) /var/www/set_network.sh "$2";;
     create_asound) /var/www/create_asound.sh;;
     show_vol_equal) /var/www/show_vol_equal.sh "$2" "$3" ;;
     showsoundcard) /var/www/show_soundcard.sh "$2";; 
     set_vol) amixer -c "$2" set "$3"_"$2" "$4"%; amixer -c "$2" set "$3"li_"$2" "$4"%; amixer -c "$2" set "$3"re_"$2" "$4"%;;                
     mpdvolplay) killall mpdvolplay
                 /var/www/src/mpdvolplay "$2";;
     mpdstop) mpc stop 
              mpc repeat off;;
     mpdrepeat) mpc repeat on;;
     reboot) reboot;;
     stop_lms) /etc/init.d/logitechmediaserver stop & update-rc.d logitechmediaserver remove  & killall squeezeboxserver;;
     start_lms) /etc/init.d/logitechmediaserver start;;
     start_sendudp) /etc/init.d/sendUDP start & update-rc.d sendUDP defaults;;
     stop_sendudp) /etc/init.d/sendUDP stop & update-rc.d sendUDP remove;;
     password) echo admin:"$(cat /opt/innotune/settings/web_settings.txt | head -n1  | tail -n1)">/opt/innotune/settings/password.txt
               sed -i 's/server.port =.*$/server.port = '$(cat /opt/innotune/settings/web_settings.txt | head -n2  | tail -n1)'/' /etc/lighttpd/lighttpd.conf
               /etc/init.d/lighttpd restart;;
     sendmail) /var/www/sendmail.sh "$2" "$3" > /var/www/return_values/sendmail.txt;;
     ttsvolplay) /var/www/src/ttsvolplay "$2";;
     usbmount) sudo sed -i 's/^\(ENABLED\).*/\1'="$2"'/'  /etc/usbmount/usbmount.conf;;
     reset) OUTPUT="$(/var/www/reset.sh "$2")"
            echo "${OUTPUT}";;
    *) echo "ERROR: invalid parameter: $1 (for $0)"; exit 1 ;;
esac

exit 0