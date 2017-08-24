<?php

// Übergabewert welches Device soll eingelesen weden 01,02,03,...
$DEVICE = $_GET['DEV'];

// Line-In einlesen
$datei = "/opt/innotune/settings/status_line-in/line-in$DEVICE.txt"; // Name der Datei
$array_linein = file($datei); // Datei in ein Array einlesen
// Zeile 1  >> PID                                $array_linein[0]
// Zeile 2  >> PID 2 (optional wenn modus 2)      $array_linein[1]
// Zeile 3  >> Quelle                             $array_linein[2]

// Settings USB-Gerät einlesen
$datei = "/opt/innotune/settings/settings_player/dev$DEVICE.txt"; // Name der Datei
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


$array_valsel;
$source_line_in = $_GET['value_line_in'];

//Line-In
if (isset($_GET['line_in_play']))
    {
              exec("sudo /var/www/sudoscript.sh set_linein $DEVICE $source_line_in $array_usb_mode[0]",$output,$return_var);
              header("Location: 06audio_linein.php?DEV=$DEVICE");
    }
if (isset($_GET['line_in_stop']))
    {
              exec("sudo /var/www/sudoscript.sh set_linein $DEVICE",$output,$return_var);
              header("Location: 06audio_linein.php?DEV=$DEVICE");
    }
if ($array_linein[2]==1) {
	$array_valsel[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==2) {
	$array_valsel[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==3) {
	$array_valsel[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==4) {
	$array_valsel[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==5) {
	$array_valsel[5] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==6) {
	$array_valsel[6] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==7) {
	$array_valsel[7] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==8) {
	$array_valsel[8] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==9) {
	$array_valsel[9] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_linein[2]==10) {
	$array_valsel[10] = "selected=selected style=\"background-color: #40FF00;\"";
}

if     ($array_linein[2]<=0) {
       $play_pic = "src='/images/pause_red.png'";
}
elseif ($array_linein[2]>=0)  {
       $play_pic = "src='/images/play_green.png'";
}




include "head.php";
include "navigation.php";

  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Audio</h1>
      <h3>Audio Zone $DEVICE</h3>
        <form method="get">
           <h5>Steuerung Line-In</h5><input readonly name="DEV" type="text" value="$DEVICE" size=22 style="border:#FFFFFF; color:#FFFFFF";" />
               <table align="center">       
                <td><p2>Wiedergabe Line-In:</p2></td>
                    <td><select name="value_line_in"> 
                    <option value="01" $array_valsel[1] name="line_in_dev_01">Gerät/Zone 1</option>
                    <option value="02" $array_valsel[2] name="line_in_dev_02">Gerät/Zone 2</option>
                    <option value="03" $array_valsel[3] name="line_in_dev_03">Gerät/Zone 3</option>
                    <option value="04" $array_valsel[4] name="line_in_dev_04">Gerät/Zone 4</option>
                    <option value="05" $array_valsel[5] name="line_in_dev_05">Gerät/Zone 5</option>
                    <option value="06" $array_valsel[6] name="line_in_dev_06">Gerät/Zone 6</option>
                    <option value="07" $array_valsel[7] name="line_in_dev_07">Gerät/Zone 7</option>
                    <option value="08" $array_valsel[8] name="line_in_dev_08">Gerät/Zone 8</option>
                    <option value="09" $array_valsel[9] name="line_in_dev_09">Gerät/Zone 9</option>
                    <option value="10" $array_valsel[10] name="line_in_dev_10">Gerät/Zone 10</option>  
                    </select></td>
                <td><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="line_in_play">Play</button></td>
                <td><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="line_in_stop">Stop</button></td>
               <td><div><img $play_pic style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                </td>
                </tr>
                <tr>
              </table>  
             </form>
      <h5>Beispiel Loxone Virtueller Ausgangsverbinder:</h5>
            <br>
           <table align="center">
             <td><p1 style="font-weight:bold;">Wiedergabe von Line-In Zone$DEVICE auf Zone02:<p1></td>
             <td><p1>/phpcontrol/linein.php?card_in=$DEVICE&card_out=02</td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Wiedergabe von Line-In Zone$DEVICE auf Zone$DEVICE:<p1></td>
             <td><p1>/phpcontrol/linein.php?card_in=$DEVICE&card_out=$DEVICE</td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Wiedergabe Line-In von Zone$DEVICE Stop:<p1></td>
             <td><p1>/phpcontrol/linein.php?card_out=$DEVICE</td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Lautstärke von Line-In Zone$DEVICE:<p1></td>
             <td><p1>/phpcontrol/linein.php?card_out=$DEVICE&volume=&#60;v&#62;</td>
             </tr>
             <tr>
           </table>
            <br>
            <br>

EOT;

include "footer.php";

?>