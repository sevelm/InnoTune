<?php

$CARD_OUT = ($_GET["card_out"]);
$CARD_IN = ($_GET["card_in"]);
$VOL = ($_GET["volume"]);

// Settings USB-Gerät einlesen
$datei = "/opt/innotune/settings/settings_player/dev$CARD_OUT.txt"; // Name der Datei
$array_usb_mode = file($datei); // Datei in ein Array einlesen
// Zeile 1   >> Modus (0=AUS;1=NORMAL;2=GETEILT)          $array_usb_mode[0]
// Zeile 2   >> Bezeichnung Zone (Normal)                 $array_usb_mode[1]
// Zeile 3   >> Bezeichnung Zone (Geteilt-links)          $array_usb_mode[2]
// Zeile 4   >> Bezeichnung Zone (Geteilt-Rechts)         $array_usb_mode[3]
// Zeile 5   >> MAC-Squeezelite (Normal)                  $array_usb_mode[4]
// Zeile 6   >> MAC-Squeezelite (Geteilt-links)           $array_usb_mode[5]
// Zeile 7   >> MAC-Squeezelite (Geteilt-Rechts)          $array_usb_mode[6]
// Zeile 8   >> Checkbox (Sh)Airplay (Normal)             $array_usb_mode[7]
// Zeile 9   >> Checkbox (Sh)Airplay (Geteilt-links)      $array_usb_mode[8]
// Zeile 10  >> Checkbox (Sh)Airplay (Geteilt-Rechts)     $array_usb_mode[9]

  if ($CARD_OUT != "" && $CARD_IN != "" && $VOL  == "") {
        exec("sudo /var/www/sudoscript.sh set_linein $CARD_OUT $CARD_IN $array_usb_mode[0]",$output,$return_var);
     } elseif ($CARD_OUT != "" && $VOL  == "") {
        exec("sudo /var/www/sudoscript.sh set_linein $CARD_OUT",$output,$return_var);
     }

  if ($VOL  != "" && $CARD_OUT != "" && $CARD_IN == "") {
        exec("sudo /var/www/sudoscript.sh set_vol $CARD_OUT LineIn $VOL",$output,$return_var);
     }

?>