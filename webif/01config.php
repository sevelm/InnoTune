<?php

// Übergabewert welches Device soll eingelesen weden 01,02,03,...
$DEVICE = $_GET['DEV'];

// Settings USB-Gerät einlesen
$datei = "/opt/innotune/settings/settings_player/dev$DEVICE.txt"; // Name der Datei
$array_setting = file($datei); // Datei in ein Array einlesen
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

// Checkbox (Sh)AirPlay Normalbetrieb
if ($array_setting[7]>="1") {
	$checkbox_ap01 = "checked=checked";
}
if ($array_setting[8]>="1") {
	$checkbox_ap02 = "checked=checked";
}
if ($array_setting[9]>="1") {
	$checkbox_ap03 = "checked=checked";
}

// Button Speichern und zurück
$NAME_NORMAL = $_GET['NAME_NORMAL'];
$NAMEli_GETEILT = $_GET['NAMEli_GETEILT'];
$NAMEre_GETEILT = $_GET['NAMEre_GETEILT'];
$MAC_NORMAL = $_GET['MAC_NORMAL'];
$MACli_GETEILT = $_GET['MACli_GETEILT'];
$MACre_GETEILT = $_GET['MACre_GETEILT'];
$AP_NORMAL = $_GET['AP_NORMAL'];
$APli_GETEILT = $_GET['APli_GETEILT'];
$APre_GETEILT = $_GET['APre_GETEILT'];

if (isset($_GET['button_save']))
    {
        $array = file("/opt/innotune/settings/settings_player/dev$DEVICE.txt"); // Datei in ein Array einlesen
        array_splice($array, 1, 1, "$NAME_NORMAL"."\n");
        array_splice($array, 2, 1, "$NAMEli_GETEILT"."\n");
        array_splice($array, 3, 1, "$NAMEre_GETEILT"."\n");       
        array_splice($array, 4, 1, "$MAC_NORMAL"."\n");
        array_splice($array, 5, 1, "$MACli_GETEILT"."\n");
        array_splice($array, 6, 1, "$MACre_GETEILT"."\n");
        array_splice($array, 7, 1, "$AP_NORMAL"."\n");
        array_splice($array, 8, 1, "$APli_GETEILT"."\n");
        array_splice($array, 9, 1, "$APre_GETEILT"."\n");
        $string = implode("", $array);
        file_put_contents("/opt/innotune/settings/settings_player/dev$DEVICE.txt", $string); 
             header("Location: 05mode.php");  
}

// Settings Normalbetrieb 
$array_mode_normal;
if ($array_setting[0] != 1 ) {
    $array_mode_normal[0] = "style=\"background-color:#F2F2F2; color:#F2F2F2; border:0px;\"";
    $array_mode_normal[1] = readonly;
    $array_mode_normal[2] = "style=\"color:#D8D8D8;\"";
    $array_mode_normal[3] = "disabled=\"disabled\"";
        $array = file("/opt/innotune/settings/settings_player/dev$DEVICE.txt"); // Datei in ein Array einlesen
        array_splice($array, 1, 1, ""."\n");
        array_splice($array, 4, 1, ""."\n");
        array_splice($array, 7, 1, ""."\n");
        $string = implode("", $array);
        file_put_contents("/opt/innotune/settings/settings_player/dev$DEVICE.txt", $string);
}
// Settings Geteilter-Betrieb 
$array_mode_geteilt;
if ($array_setting[0] != 2 ) {
    $array_mode_geteilt[0] = "style=\"background-color:#F2F2F2; color:#F2F2F2; border:0px;\"";
    $array_mode_geteilt[1] = readonly;
    $array_mode_geteilt[2] = "style=\"color:#D8D8D8;\"";
    $array_mode_geteilt[3] = "disabled=\"disabled\"";
        $array = file("/opt/innotune/settings/settings_player/dev$DEVICE.txt"); // Datei in ein Array einlesen
        array_splice($array, 2, 1, ""."\n");
        array_splice($array, 3, 1, ""."\n");
        array_splice($array, 5, 1, ""."\n");
        array_splice($array, 6, 1, ""."\n");
        array_splice($array, 8, 1, ""."\n");
        array_splice($array, 9, 1, ""."\n");
        $string = implode("", $array);
        file_put_contents("/opt/innotune/settings/settings_player/dev$DEVICE.txt", $string);
}



include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Player Konfiguration USB-Gerät $DEVICE</h3>
     <form method="get">
       <h5>Player Konfiguration:</h5><input readonly name="DEV" type="text" value="$DEVICE" size=22 style="border:#FFFFFF; color:#FFFFFF";" />
          <br>
          <br>
             <table align="center">
                <td></td>
                <td><p align="center" >Bezeichung</p><p1 align="center">(ohne Leer-Sonderzeichen)</p1></td>
                <td><p align="center" >MAC Squeezebox</p><p1 align="center">(Bsp.: 00:00:00:00:00:01)</p1></td>
                <td><p align="center" >(Sh)Airplay</p></td>                
                </tr>
                <tr> 
                <td><p align="right" $array_mode_normal[2]>Normalbetrieb:</p></td>          
                <td align="center"><input name="NAME_NORMAL" type="text" $array_mode_normal[0] $array_mode_normal[1] value="$array_setting[1]" size=22 /></td>
                <td align="center"><input name="MAC_NORMAL" type="text" $array_mode_normal[0] $array_mode_normal[1] value="$array_setting[4]" size=22 /></td>
                <td align="center"><input name="AP_NORMAL" type="checkbox" $array_mode_normal[3] $checkbox_ap01 value="AP01" /></td>
                </tr>
                <tr> 
                <td><p align="right" $array_mode_geteilt[2]>Geteilter-Betrieb Links:</p></td>          
                <td align="center"><input name="NAMEli_GETEILT" type="text" $array_mode_geteilt[0] $array_mode_geteilt[1] value="$array_setting[2]" size=22 /></td>
                <td align="center"><input name="MACli_GETEILT" type="text" $array_mode_geteilt[0] $array_mode_geteilt[1] value="$array_setting[5]" size=22 /></td>
                <td align="center"><input name="APli_GETEILT" type="checkbox" $array_mode_geteilt[3] $checkbox_ap02 value="AP02" /></td>
                </tr>
                <tr>
                <td><p align="right" $array_mode_geteilt[2]>Geteilter-Betrieb Rechts:</p></td>
                <td align="center"><input name="NAMEre_GETEILT" type="text" $array_mode_geteilt[0] $array_mode_geteilt[1] value="$array_setting[3]" size=22 /></td>
                <td align="center"><input name="MACre_GETEILT" type="text" $array_mode_geteilt[0] $array_mode_geteilt[1] value="$array_setting[6]" size=22 /></td>
                <td align="center"><input name="APre_GETEILT" type="checkbox" $array_mode_geteilt[3] $checkbox_ap03 value="AP03" /></td>
                </tr>
                <tr>
             </table>
          <br>
          <br>
          <div>
          <button style="width: 200px;display: block;margin-right: auto;margin-left: auto" name="button_save">Speichern und zurück</button>   
          </div>
       </form>

EOT;
include "footer.php";
?>