<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 13.09.2016
 * Time: 13:48
 */

//<editor-fold desc="LMS_Einstellungen">
if (isset($_GET['lms'])) {
    $datei = "/opt/innotune/settings/logitechmediaserver.txt"; // Name der Datei
    $array_lms = file($datei); // Datei in ein Array einlesen
    // Zeile 1 >> checkbox LMS              $array_config[0]
    echo $array_lms[0];
}
if (isset($_GET['lms_save'])) {
    $value = $_GET['value'];
    file_put_contents("/opt/innotune/settings/logitechmediaserver.txt", $value);
}
// </editor-fold>

//Lautstärke muss in einem externen file regeln zu sein da man sonst für die App sich einloggen müsste
// <editor-fold desc="Lautstärke">
if (isset($_GET['vol'])) {
    $dev = $_GET['dev'];
    echo exec("sudo /var/www/sudoscript.sh show_vol_equal " . $dev . " all");

    //Alle einzeln aufrufen dauert länger
    /*echo exec("sudo /var/www/sudoscript.sh show_vol_equal " . $dev . " mpd") . ";";
    echo exec("sudo /var/www/sudoscript.sh show_vol_equal " . $dev . " squeeze") . ";";
    echo exec("sudo /var/www/sudoscript.sh show_vol_equal " . $dev . " airplay") . ";";
    echo exec("sudo /var/www/sudoscript.sh show_vol_equal " . $dev . " linein");*/
}

if (isset($_GET['vol_set'])) {
    $dev = $_GET['dev'];
    $player = $_GET['player'];
    $value = $_GET['value'];
    exec("sudo /var/www/sudoscript.sh set_vol $dev $player $value");
}

if (isset($_GET['vol_mute'])) {
    $dev = $_GET['dev'];
    exec("sudo /var/www/sudoscript.sh set_vol $dev mpd 0");
    exec("sudo /var/www/sudoscript.sh set_vol $dev squeeze 0");
    exec("sudo /var/www/sudoscript.sh set_vol $dev airplay 0");
    exec("sudo /var/www/sudoscript.sh set_vol $dev LineIn 0");
}

if(isset($_GET['eq'])) {
    $dev = $_GET['dev'];
    echo exec("sudo /var/www/sudoscript.sh show_eq $dev");
}

if(isset($_GET['eq_set'])) {
    $dev = $_GET['dev'];
    $freq = $_GET['freq'];
    $value = $_GET['value'];
    exec("sudo /var/www/sudoscript.sh set_eq $dev $freq $value");
}
// </editor-fold>

// <editor-fold desc="Settings">
if (isset($_GET['shnet'])) {
    echo exec("sudo /var/www/sudoscript.sh shnet all");
}

if (isset($_GET['wifi'])) {
    echo exec("iwlist wlan0 scan | grep ESSID | cut -d '\"' -f2 | sort -u | tr '\n' ';'");
}

if (isset($_GET['setnet'])) {
    $DHCP = $_GET['dhcp'];
    $IP = $_GET['ip'];
    $SUBNET = $_GET['subnet'];
    $GATE = $_GET['gate'];
    $DNS1 = $_GET['dns1'];
    $DNS2 = $_GET['dns2'];
    $WLAN = $_GET['wlan'];
    $SSID = $_GET['ssid'];
    $PSK = $_GET['psk'];

    $array = file("/opt/innotune/settings/network.txt"); // Datei in ein Array einlesen
    array_splice($array, 0, 1, "$DHCP" . "\n");
    array_splice($array, 1, 1, "$IP" . "\n");
    array_splice($array, 2, 1, "$SUBNET" . "\n");
    array_splice($array, 3, 1, "$GATE" . "\n");
    array_splice($array, 5, 1, "$DNS1" . "\n");
    array_splice($array, 6, 1, "$DNS2" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/network.txt", $string);
    exec("/var/www/setwlan.sh \"$SSID\" \"$PSK\" \"$WLAN\"");
    exec('sudo /var/www/sudoscript.sh setnet');
}

if (isset($_GET['web_settings_set'])) {
    $passwort = $_GET['password'];
    $port = $_GET['port'];

    $array = file("/opt/innotune/settings/web_settings.txt"); // Datei in ein Array einlesen
    array_splice($array, 0, 1, "$passwort" . "\n");
    array_splice($array, 1, 1, "$port " . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/web_settings.txt", $string);

    //exec('sudo /var/www/sudoscript.sh password', $output, $return_var);
}
if (isset($_GET['web_settings'])) {
    $datei = "/opt/innotune/settings/web_settings.txt"; // Name der Datei
    $array_config = file($datei); // Datei in ein Array einlesen
    echo $array_config[0] . ';' . $array_config[1];
}
// </editor-fold>

// <editor-fold desc="Devices">
if (isset($_GET['activedevices'])) {
    echo exec("sudo /var/www/sudoscript.sh showsoundcard 0");
}

if (isset($_GET['mappeddevices'])) {
    $file = fopen("/opt/innotune/settings/mapping.txt", "r");
    if($file) {
      $i = 0;
      $strarr = "";
      while (($line = fgets($file)) !== false) {
        $strarr = $strarr . "1;";
        $i = $i + 1;
      }
      while ($i < 10) {
        $strarr = $strarr . ";";
        $i = $i + 1;
      }
      fclose($file);
      echo $strarr;
    } else {
      echo ";;;;;;;;;";
    }
}

// Zeile 1   >> Modus (0=AUS;1=NORMAL;2=GETEILT)          $array_setting[0]
// Zeile 2   >> Bezeichnung Zone (Normal)                 $array_setting[1]
// Zeile 3   >> Bezeichnung Zone (Geteilt-links)          $array_setting[2]
// Zeile 4   >> Bezeichnung Zone (Geteilt-Rechts)         $array_setting[3]
// Zeile 5   >> MAC-Squeezelite (Normal)                  $array_setting[4]
// Zeile 6   >> MAC-Squeezelite (Geteilt-links)           $array_setting[5]
// Zeile 7   >> MAC-Squeezelite (Geteilt-Rechts)          $array_setting[6]
// Zeile 8   >> Checkbox (Sh)Airplay (Normal)             $array_setting[7]
// Zeile 9   >> Checkbox (Sh)Airplay (Geteilt-links)      $array_setting[8]
// Zeile 10  >> Checkbox (Sh)Airplay (Geteilt-Rechts)     $array_setting[9]
// Zeile 11  >> Checkbox Spotify Connect(Normal)          $array_setting[7]
// Zeile 12  >> Checkbox Spotify Connect (Geteilt-links)      $array_setting[8]
// Zeile 13  >> Checkbox Spotify Connect (Geteilt-Rechts)     $array_setting[9]
if (isset($_GET['getdevice'])) {
    $dev = $_GET['dev'];

    $datei = "/opt/innotune/settings/settings_player/dev" . $dev . ".txt"; // Name der Datei
    $usb_mode = file($datei);
    $device = trim($usb_mode[0]) . ";" . trim($usb_mode[1]) . ";" . trim($usb_mode[2]) . ";" . trim($usb_mode[3]) . ";" . trim($usb_mode[4]) . ";" . trim($usb_mode[5]) . ";" . trim($usb_mode[6]) . ";" . trim($usb_mode[7]) . ";" . trim($usb_mode[8]) . ";" . trim($usb_mode[9]) . ";" . trim($usb_mode[10]) . ";" . trim($usb_mode[11]) . ";" . trim($usb_mode[12]) . ";" . trim($usb_mode[13]);
    $execstring = "aplay -l | grep sndc" . $dev . " | cut -d \":\" -f1 | cut -c 6-";
    $devpath = exec("cat /opt/innotune/settings/mapping.txt | grep sndc" . $dev . " | cut -c 44- | rev | cut -c 12- | rev");
    if($devpath == "") {
      $devpath = exec("cat /opt/innotune/settings/mapping_current.txt | grep sndc" . $dev . " | cut -c 44- | rev | cut -c 12- | rev");
    }
    $oac = trim(file("/opt/innotune/settings/settings_player/oac/oac" . $dev . ".txt")[0]);
    $device = $device . ";" . $devpath . ";" . $oac;
    echo $device;
}

if (isset($_GET['reset_usb_mapping'])) {
    exec("sudo /var/www/sudoscript.sh resetudev");
}

if(isset($_GET['reset_logs'])) {
  exec("sudo /var/www/resetlogs.sh");
}

if (isset($_GET['set_audio_configuration'])) {
    $dev = $_GET['dev'];
    $mode = $_GET['mode'];

    $array = file("/opt/innotune/settings/settings_player/dev" . $dev . ".txt"); // Datei in ein Array einlesen
    array_splice($array, 0, 1, "$mode" . "\n");
    array_splice($array, 1, 1, "" . "\n");
    array_splice($array, 2, 1, "" . "\n");
    array_splice($array, 3, 1, "" . "\n");
    array_splice($array, 4, 1, "" . "\n");
    array_splice($array, 5, 1, "" . "\n");
    array_splice($array, 6, 1, "" . "\n");
    array_splice($array, 7, 1, "" . "\n");
    array_splice($array, 8, 1, "" . "\n");
    array_splice($array, 9, 1, "" . "\n");
    array_splice($array, 10, 1, "" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/settings_player/dev" . $dev . ".txt", $string);

    $array = file("/opt/innotune/settings/changedconf.txt");
    array_splice($array, 0, 1, "1" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/changedconf.txt", $string);
}

if (isset($_GET['device_set'])) {
    $dev = $_GET['dev'];
    $NAME_NORMAL = $_GET['NAME_NORMAL'];
    $NAMEli_GETEILT = $_GET['NAMEli_GETEILT'];
    $NAMEre_GETEILT = $_GET['NAMEre_GETEILT'];
    $MAC_NORMAL = $_GET['MAC_NORMAL'];
    $MACli_GETEILT = $_GET['MACli_GETEILT'];
    $MACre_GETEILT = $_GET['MACre_GETEILT'];
    $AP_NORMAL = $_GET['AP_NORMAL'];
    $APli_GETEILT = $_GET['APli_GETEILT'];
    $APre_GETEILT = $_GET['APre_GETEILT'];
    $SP_NORMAL = $_GET['SP_NORMAL'];
    $SPli_GETEILT = $_GET['SPli_GETEILT'];
    $SPre_GETEILT = $_GET['SPre_GETEILT'];
    $oac = $_GET['oac'];

    $array = file("/opt/innotune/settings/settings_player/dev$dev.txt"); // Datei in ein Array einlesen
    array_splice($array, 1, 1, "$NAME_NORMAL" . "\n");
    array_splice($array, 2, 1, "$NAMEli_GETEILT" . "\n");
    array_splice($array, 3, 1, "$NAMEre_GETEILT" . "\n");
    array_splice($array, 4, 1, "$MAC_NORMAL" . "\n");
    array_splice($array, 5, 1, "$MACli_GETEILT" . "\n");
    array_splice($array, 6, 1, "$MACre_GETEILT" . "\n");
    array_splice($array, 7, 1, "$AP_NORMAL" . "\n");
    array_splice($array, 8, 1, "$APli_GETEILT" . "\n");
    array_splice($array, 9, 1, "$APre_GETEILT" . "\n");
    array_splice($array, 10, 1, "$SP_NORMAL" . "\n");
    array_splice($array, 11, 1, "$SPli_GETEILT" . "\n");
    array_splice($array, 12, 1, "$SPre_GETEILT" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/settings_player/dev$dev.txt", $string);


    $array = file("/opt/innotune/settings/changedconf.txt");
    array_splice($array, 1, 1, "1" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/changedconf.txt", $string);
    file_put_contents("/opt/innotune/settings/settings_player/oac/oac$dev.txt", $oac . "\n");
}

if (isset($_GET['audio_configuration'])) {
    $array = file("/opt/innotune/settings/changedconf.txt");
    array_splice($array, 0, 1, "0" . "\n");
    array_splice($array, 1, 1, "0" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/changedconf.txt", $string);

    exec("sudo /var/www/sudoscript.sh create_asound", $output, $return_var);
}
if (isset($_GET['player_configuration'])) {
    $array = file("/opt/innotune/settings/changedconf.txt");
    array_splice($array, 1, 1, "0" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/changedconf.txt", $string);

    exec('sudo /var/www/sudoscript.sh setplayer', $output, $return_var);
}
// </editor-fold>


// <editor-fold desc="Lines">
if (isset($_GET['lineinstatus'])) {
    $DEVICE = ($_GET["dev"]);

    $datei = "/opt/innotune/settings/status_line-in/line-in$DEVICE.txt"; // Name der Datei
    $array_linein = file($datei); // Datei in ein Array einlesen
// Zeile 1  >> PID                                $array_linein[0]
// Zeile 2  >> PID 2 (optional wenn modus 2)      $array_linein[1]
// Zeile 3  >> Quelle                             $array_linein[2]

    echo $array_linein[2];
}
if (isset($_GET['setlinein'])) {
    $CARD_OUT = ($_GET["card_out"]);
    $CARD_IN = ($_GET["card_in"]);
    $VOL = ($_GET["volume"]);

// Settings USB-Gerät einlesen
    $datei = "/opt/innotune/settings/settings_player/dev$CARD_OUT.txt"; // Name der Datei
    $array_usb_mode = file($datei); // Datei in ein Array einlesen

    if ($CARD_OUT != "" && $CARD_IN != "" && $VOL == "") {
        exec("sudo /var/www/sudoscript.sh set_linein $CARD_OUT $CARD_IN $array_usb_mode[0]", $output, $return_var);
    } elseif ($CARD_OUT != "" && $VOL == "") {
        exec("sudo /var/www/sudoscript.sh set_linein $CARD_OUT", $output, $return_var);
    }

    if ($VOL != "" && $CARD_OUT != "" && $CARD_IN == "") {
        exec("sudo /var/www/sudoscript.sh set_vol $CARD_OUT LineIn $VOL", $output, $return_var);
    }
}
// </editor-fold>

// <editor-fold desc="Playlists">
if (isset($_GET['playlists'])) {
    $datei = "/opt/innotune/settings/mpdvolplay.txt"; // Name der Datei
    $playlists = file($datei); // Datei in ein Array einlesen

    for ($i = 0; $i < (count($playlists) / 12); $i++) {
        echo trim($playlists[$i * 12]) . ";";
    }

    //.csv
    /*$row = 1;
    if (($handle = fopen("/opt/innotune/settings/volmpdplay.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 100, ";")) !== FALSE) {
            $num = count($data);
            $row++;
            echo $data[0] . ";";
        }
        fclose($handle);
    }*/

    /*$db = new MyDB();
    $results = $db->query('SELECT ID,NAME FROM PLAYLISTS');
    while ($row = $results->fetchArray()) {
        echo $row['ID'] . ";";
        echo $row['NAME'] . ";";
    }
    $db->close();*/
}

if (isset($_GET['getplaylist'])) {
    $datei = "/opt/innotune/settings/mpdvolplay.txt"; // Name der Datei
    $playlists = file($datei); // Datei in ein Array einlesen
    $PLAYLISTID = ($_GET["ID"]);

    //.txt File
    for ($i = $PLAYLISTID * 12; $i < (($PLAYLISTID * 12) + 11); $i++) {
        echo trim($playlists[$i + 1]) . ";";
    }



    //.csv File
    /*$row = 0;
    if (($handle = fopen("/opt/innotune/settings/volmpdplay.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $num = count($data);
            if ($row == $PLAYLISTID) {
                for ($c = 1; $c < $num; $c++) {
                    echo $data[$c] . ";";
                }
            }
            $row++;

        }
        fclose($handle);
    }*/

    //SQL
    /*
    $db = new MyDB();
    $results = $db->query("SELECT * FROM PLAYLISTS WHERE ID = $PLAYLISTID");
    while ($row = $results->fetchArray()) {
        echo $row['VOL_BACKGROUND'] . ";";
        echo $row['VOL_01'] . ";";
        echo $row['VOL_02'] . ";";
        echo $row['VOL_03'] . ";";
        echo $row['VOL_04'] . ";";
        echo $row['VOL_05'] . ";";
        echo $row['VOL_06'] . ";";
        echo $row['VOL_07'] . ";";
        echo $row['VOL_08'] . ";";
        echo $row['VOL_09'] . ";";
        echo $row['VOL_10'] . ";";
    }
    $db->close();
*/
}

if (isset($_GET['deleteplaylist'])) {
    $file = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
    $PLAYLISTID = (($_GET["ID"]) * 12) - 12;
    echo $PLAYLISTID;
    array_splice($file, $PLAYLISTID, 1, "");
    array_splice($file, $PLAYLISTID + 1, 1, "");
    array_splice($file, $PLAYLISTID + 2, 1, "");
    array_splice($file, $PLAYLISTID + 3, 1, "");
    array_splice($file, $PLAYLISTID + 4, 1, "");
    array_splice($file, $PLAYLISTID + 5, 1, "");
    array_splice($file, $PLAYLISTID + 6, 1, "");
    array_splice($file, $PLAYLISTID + 7, 1, "");
    array_splice($file, $PLAYLISTID + 8, 1, "");
    array_splice($file, $PLAYLISTID + 9, 1, "");
    array_splice($file, $PLAYLISTID + 10, 1, "");
    array_splice($file, $PLAYLISTID + 11, 1, "");
    $string = implode("", $file);
    file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
    /*$file = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
    $db = new MyDB();
    $PLAYLISTID = $_GET["ID"];
    $ID = intval($PLAYLISTID);

    if (!$db) {
        echo $db->lastErrorMsg();
    } else {
        $query = "DELETE FROM PLAYLISTS WHERE ID=$ID; DELETE FROM SQLITE_SEQUENCE WHERE NAME = 'PLAYLISTS' ";
echo $query;
        $update = $db->exec($query);
    }
    $db->close();*/


}

if (isset($_GET['saveplaylist'])) {
    $PLAYLISTID = ($_GET["ID"]);
    $VOL_BACKGROUND = ($_GET["VOL_BACKGROUND"]);
    $VOL_DEV01 = ($_GET["VOL_DEV01"]);
    $VOL_DEV02 = ($_GET["VOL_DEV02"]);
    $VOL_DEV03 = ($_GET["VOL_DEV03"]);
    $VOL_DEV04 = ($_GET["VOL_DEV04"]);
    $VOL_DEV05 = ($_GET["VOL_DEV05"]);
    $VOL_DEV06 = ($_GET["VOL_DEV06"]);
    $VOL_DEV07 = ($_GET["VOL_DEV07"]);
    $VOL_DEV08 = ($_GET["VOL_DEV08"]);
    $VOL_DEV09 = ($_GET["VOL_DEV09"]);
    $VOL_DEV10 = ($_GET["VOL_DEV10"]);

    $PLAYLISTID = ($PLAYLISTID * 12) - 12; //Anfangsnummer für den Bereich in den geschrieben werden soll
    $file = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen

    if ($_GET["NAME"] != null) {
        array_splice($file, $PLAYLISTID, 1, $_GET["NAME"] . "\n");
        if ($VOL_BACKGROUND == "-1") {
            array_splice($file, $PLAYLISTID + 1, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 2, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 3, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 4, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 5, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 6, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 7, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 8, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 9, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 10, 1, "0" . "\n");
            array_splice($file, $PLAYLISTID + 11, 1, "0" . "\n");
        }
    } else {
        array_splice($file, $PLAYLISTID + 1, 1, $VOL_BACKGROUND . "\n");
        array_splice($file, $PLAYLISTID + 2, 1, $VOL_DEV01 . "\n");
        array_splice($file, $PLAYLISTID + 3, 1, $VOL_DEV02 . "\n");
        array_splice($file, $PLAYLISTID + 4, 1, $VOL_DEV03 . "\n");
        array_splice($file, $PLAYLISTID + 5, 1, $VOL_DEV04 . "\n");
        array_splice($file, $PLAYLISTID + 6, 1, $VOL_DEV05 . "\n");
        array_splice($file, $PLAYLISTID + 7, 1, $VOL_DEV06 . "\n");
        array_splice($file, $PLAYLISTID + 8, 1, $VOL_DEV07 . "\n");
        array_splice($file, $PLAYLISTID + 9, 1, $VOL_DEV08 . "\n");
        array_splice($file, $PLAYLISTID + 10, 1, $VOL_DEV09 . "\n");
        array_splice($file, $PLAYLISTID + 11, 1, $VOL_DEV10 . "\n");
    }
    $string = implode("", $file);
    file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);

    /*$db = new MyDB();
    $ID = intval($PLAYLISTID);

    if (!$db) {
        echo $db->lastErrorMsg();
    } else {
        if ($_GET["NAME"] != null) {
            $NAME = $_GET["NAME"];

            $query = "INSERT OR REPLACE
                    INTO
                      PLAYLISTS (id, NAME)
                    VALUES
                      ($ID, '$NAME')";

            $update = $db->exec($query);
            if ($VOL_BACKGROUND == "-1") {
                $query = "UPDATE PLAYLISTS set VOL_BACKGROUND = 0 WHERE ID = $ID";

                $count = $db->exec($query);
            }
        } else {
            $query = "UPDATE PLAYLISTS set VOL_BACKGROUND = '$VOL_BACKGROUND',
                          VOL_01 = '$VOL_DEV01',
                          VOL_02 = '$VOL_DEV02',
                          VOL_03 = '$VOL_DEV03',
                          VOL_04 = '$VOL_DEV04',
                          VOL_05 = '$VOL_DEV05',
                          VOL_06 = '$VOL_DEV06',
                          VOL_07 = '$VOL_DEV07',
                          VOL_08 = '$VOL_DEV08',
                          VOL_09 = '$VOL_DEV09',
                          VOL_10 = '$VOL_DEV10' WHERE ID = $ID";

            echo $query;

            $count = $db->exec($query);
        }
    }

    $db->close();*/
}

//Button Start Playlist
if (isset($_GET['playlistplay'])) {
    $PLAYLISTID = ($_GET["ID"]);
    exec("sudo /var/www/sudoscript.sh mpdvolplay $PLAYLISTID", $output, $return_var);
}
// Button Stop Playlist
if (isset($_GET['playliststop'])) {
    exec("sudo /var/www/sudoscript.sh mpdstop", $output, $return_var);
}
// </editor-fold>

//Check if the player or audio config changed
if (isset($_GET['getchangedconf'])) {
    $datei = "/opt/innotune/settings/changedconf.txt";
    $array_config = file($datei); // Datei in ein Array einlesen
    echo trim($array_config[0]) . ";" . trim($array_config[1]);
}

//Reboot the Server
if (isset($_GET['reboot'])) {
    //change Audio Conf before rebooting
    $array = file("/opt/innotune/settings/changedconf.txt");
    array_splice($array, 0, 1, "0" . "\n");
    array_splice($array, 1, 1, "0" . "\n");
    $string = implode("", $array);
    file_put_contents("/opt/innotune/settings/changedconf.txt", $string);

    exec("sudo /var/www/sudoscript.sh reboot");
}

//VoiceRss-Key
if (isset($_GET['getvoicersskey'])) {
    $datei = "/opt/innotune/settings/voiceoutput/voicersskey.txt"; // Name der Datei
    $array_config = file($datei); // Datei in ein Array einlesen
    echo $array_config[0];
}
if (isset($_GET['setvoicersskey'])) {
    $value = $_GET['value'];
    file_put_contents("/opt/innotune/settings/voiceoutput/voicersskey.txt", $value);
}
if (isset($_GET['getvoiceoutputvol'])) {
    $datei = "/opt/innotune/settings/voiceoutput/voiceoutputvol.txt"; // Name der Datei
    $array_config = file($datei); // Datei in ein Array einlesen
    echo trim($array_config[0]) . ";" . trim($array_config[1]) . ";" . trim($array_config[2]) . ";" . trim($array_config[3]) . ";" . trim($array_config[4]) . ";" . trim($array_config[5]) . ";" . trim($array_config[6]) . ";" . trim($array_config[7]) . ";" . trim($array_config[8]) . ";" . trim($array_config[9]) . ";" . trim($array_config[10]);
}
if (isset($_GET['setvoiceoutputvol'])) {
    $file = file("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt"); // Datei in ein Array einlesen
    $VOL_BACKGROUND = ($_GET["VOL_BACKGROUND"]);
    $VOL_DEV01 = ($_GET["VOL_DEV01"]);
    $VOL_DEV02 = ($_GET["VOL_DEV02"]);
    $VOL_DEV03 = ($_GET["VOL_DEV03"]);
    $VOL_DEV04 = ($_GET["VOL_DEV04"]);
    $VOL_DEV05 = ($_GET["VOL_DEV05"]);
    $VOL_DEV06 = ($_GET["VOL_DEV06"]);
    $VOL_DEV07 = ($_GET["VOL_DEV07"]);
    $VOL_DEV08 = ($_GET["VOL_DEV08"]);
    $VOL_DEV09 = ($_GET["VOL_DEV09"]);
    $VOL_DEV10 = ($_GET["VOL_DEV10"]);

    array_splice($file, 0, 1, $VOL_BACKGROUND . "\n");
    array_splice($file, 1, 1, $VOL_DEV01 . "\n");
    array_splice($file, 2, 1, $VOL_DEV02 . "\n");
    array_splice($file, 3, 1, $VOL_DEV03 . "\n");
    array_splice($file, 4, 1, $VOL_DEV04 . "\n");
    array_splice($file, 5, 1, $VOL_DEV05 . "\n");
    array_splice($file, 6, 1, $VOL_DEV06 . "\n");
    array_splice($file, 7, 1, $VOL_DEV07 . "\n");
    array_splice($file, 8, 1, $VOL_DEV08 . "\n");
    array_splice($file, 9, 1, $VOL_DEV09 . "\n");
    array_splice($file, 10, 1, $VOL_DEV10);

    $string = implode("", $file);
    file_put_contents("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt", $string);
}


// funktion für die sysinfo
function between($von, $bis, $string)
{
    $a = explode($von, $string);
    $b = explode($bis, $a[1]);
    return $b[0];
}

//Objekt 1: Cpu-Auslastung in %
//Objekt 2: Ram-Auslastung in %
//Objekt 3: Uptime
//Objekt 4: disk size in kb
//Objekt 5: disk full in kb
//Objekt 6: disk full in %
//Objekt 7: Cpu-Temperatur in Celcius
if (isset($_GET['getsysinfo'])) {
    $cpu = shell_exec("cat /proc/stat");
    $cpu = between("cpu  ", "\n", $cpu);
    $cpu = explode(" ", $cpu);
    $cpuges = $cpu[0] + $cpu[1] + $cpu[2] + $cpu[3];
    $cpuidle = $cpu[3];
    $cpuproz = (100 / $cpuges) * $cpuidle;
    $cpuproz = round(100 - $cpuproz, 2);

    $ram = shell_exec("free -mt");
    $ramtotal = between("Mem:          ", "       ", $ram);
    $ramused = between("$ramtotal       ", "       ", $ram);
    $ramproz = round((100 / $ramtotal) * $ramused, 2);

    $uptime = shell_exec("uptime | grep -ohe 'up .*' | sed 's/,//g' | awk '{ print $2}'");
    $diskinfo = shell_exec("df -Pl|grep '^/dev'|awk 'NR==1{print $2, $3,100-$5}' | sed 's/%//'");
    $diskinfo = str_replace(" ", ";", $diskinfo);

    $tempraw = intval(shell_exec("/var/www/readcputemp.sh"));
    $temp = round(($tempraw/1000));

    echo $cpuproz . ";" . $ramproz . ";" . trim($uptime) . ";" . trim($diskinfo) . ";" . $temp;
}

if (isset($_GET['update'])) {
    exec("sudo /var/www/sudoscript.sh update", $output, $return_var);
}

if(isset($_GET['updateKernel'])) {
    exec("sudo /var/www/sudoscript.sh updateKernel", $output, $return_var);
}

if (isset($_GET['reset'])) {
    if (isset($_GET["network"])) {
        echo shell_exec("sudo /var/www/sudoscript.sh reset net");
    }
    if (isset($_GET["usb"])) {
        echo shell_exec("sudo /var/www/sudoscript.sh reset usb");
    }
    if (isset($_GET["playlists"])) {
        echo shell_exec("sudo /var/www/sudoscript.sh reset playlists");
    }
}

if (isset($_GET['get_usbmount'])) {
    echo shell_exec("grep --only-matching --perl-regex \"(?<=ENABLED\=).*\" /etc/usbmount/usbmount.conf");
}

if (isset($_GET['get_netmount'])) {
  echo shell_exec("cat /opt/innotune/settings/netmount.txt");
}

if (isset($_GET['savenetworkmount'])) {
    $PATH = trim($_GET["path"]);
    $MOUNTPOINT = "/media/" . trim($_GET["mountpoint"]);
    $TYPE = trim($_GET["type"]);
    $OPTIONS = trim($_GET["options"]);

    $mount = $PATH . " " . $MOUNTPOINT . " " . $TYPE . " " . $OPTIONS . " 0 0";

    shell_exec("sudo /var/www/sudoscript.sh networkmount \"$mount\" \"$MOUNTPOINT\" \"$PATH\" \"$TYPE\" \"$OPTIONS\"");
}

if(isset($_GET['removenetworkmount'])) {
  $PATH = trim($_GET["path"]);
  $MOUNTPOINT = trim($_GET["mountpoint"]);
  $TYPE = trim($_GET["type"]);
  $FSTAB = trim($_GET['fstab']);

  shell_exec("sudo /var/www/sudoscript.sh removenetworkmount \"$MOUNTPOINT\" \"$PATH\" \"$TYPE\" \"$FSTAB\"");
}

/*class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('test.db');
    }
}*/
/*if (isset($_GET['sqlite'])) {
    $sql = <<<EOF
    DROP TABLE PLAYLISTS;
      CREATE TABLE PLAYLISTS
      (ID INTEGER PRIMARY KEY ,
      NAME           TEXT    NOT NULL,
      VOL_BACKGROUND TEXT  ,
      VOL_01        TEXT,
      VOL_02        TEXT,
      VOL_03        TEXT,
      VOL_04        TEXT,
      VOL_05        TEXT,
      VOL_06        TEXT,
      VOL_07        TEXT,
      VOL_08        TEXT,
      VOL_09        TEXT,
      VOL_10        TEXT);
EOF;

    $db = new MyDB();

    $ret = $db->exec($sql);
    if (!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "Table created successfully\n";
    }
    $db->close();


}*/


/* PHP-Methods for iOS InnoPlay */
if (isset($_GET['getversion'])) {
    $datei = "/var/www/version.txt"; // Name der Datei
    $version_local = file($datei); // Datei in ein Array einlesen
    echo $version_local[0];
}

if (isset($_GET['log'])) {
    echo nl2br(file_get_contents("/var/www/checkprocesses.log"));
}
if (isset($_GET['logfile'])) {
    $filepath = "/var/www/checkprocesses.log";
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($filepath) . "\"");
    readfile($filepath);
}
?>
