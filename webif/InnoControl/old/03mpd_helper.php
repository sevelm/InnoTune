<?php

// Übergabewert welches Device soll eingelesen weden 01,02,03,...
$DEVICE = $_GET['DEV'];

exec('sudo /var/www/sudoscript.sh showsoundcard',$output,$return_var);

$datei = "/opt/innotune/settings/usb_dev.txt"; // Name der Datei
$array_usb_dev = file($datei); // Datei in ein Array einlesen
// Zeile 1  >> USB-Gerät 01 IO/NIO    $array_usb-dev[0]
// Zeile 2  >> USB-Gerät 02 IO/NIO    $array_usb-dev[1]
// Zeile 3  >> USB-Gerät 03 IO/NIO    $array_usb-dev[2]
// Zeile 4  >> USB-Gerät 04 IO/NIO    $array_usb-dev[3]
// Zeile 5  >> USB-Gerät 05 IO/NIO    $array_usb-dev[4]
// Zeile 6  >> USB-Gerät 06 IO/NIO    $array_usb-dev[5]
// Zeile 7  >> USB-Gerät 07 IO/NIO    $array_usb-dev[6]
// Zeile 8  >> USB-Gerät 08 IO/NIO    $array_usb-dev[7]
// Zeile 9  >> USB-Gerät 09 IO/NIO    $array_usb-dev[8]
// Zeile 10 >> USB-Gerät 10 IO/NIO    $array_usb-dev[9]

// USB-Gerät gefunden/nicht gefunden
$array_dev_ready;
if     ($array_usb_dev[0]==0) {
	$array_dev_ready[0] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[0]==1) {
	$array_dev_ready[0] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[1]==0) {
	$array_dev_ready[1] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[1]==1) {
	$array_dev_ready[1] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[2]==0) {
	$array_dev_ready[2] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[2]==1) {
	$array_dev_ready[2] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[3]==0) {
	$array_dev_ready[3] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[3]==1) {
	$array_dev_ready[3] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[4]==0) {
	$array_dev_ready[4] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[4]==1) {
	$array_dev_ready[4] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[5]==0) {
	$array_dev_ready[5] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[5]==1) {
	$array_dev_ready[5] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[6]==0) {
	$array_dev_ready[6] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[6]==1) {
	$array_dev_ready[6] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[7]==0) {
	$array_dev_ready[7] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[7]==1) {
	$array_dev_ready[7] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[8]==0) {
	$array_dev_ready[8] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[8]==1) {
	$array_dev_ready[8] = "src='/images/hack_green.png'";
} 
if     ($array_usb_dev[9]==0) {
	$array_dev_ready[9] = "src='/images/ring_close_red.png'";
}
elseif ($array_usb_dev[9]==1) {
	$array_dev_ready[9] = "src='/images/hack_green.png'";
} 


// Button Play
if (isset($_GET['play']))
    {
              exec("sudo /var/www/sudoscript.sh mpdvolplay $DEVICE",$output,$return_var);
              header("Location: 03mpd_helper.php?DEV=$DEVICE");
    }
// Button Stop
if (isset($_GET['stop']))
    {
              exec("sudo /var/www/sudoscript.sh mpdstop",$output,$return_var);
              header("Location: 03mpd_helper.php?DEV=$DEVICE");
    }
// Button Save
if (isset($_GET['save']))
    {
$WRITE_NR = ($DEVICE * 12) - 12 ; //Anfangsnummer für den Bereich in den geschrieben werden soll
     $array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
      array_splice($array, $WRITE_NR,    1, $_GET['TITLE']."\n");
      array_splice($array, $WRITE_NR+1,  1, $_GET['VOL_BACKROUND']."\n");
      array_splice($array, $WRITE_NR+2,  1, $_GET['VOL_DEV01']."\n");
      array_splice($array, $WRITE_NR+3,  1, $_GET['VOL_DEV02']."\n");
      array_splice($array, $WRITE_NR+4,  1, $_GET['VOL_DEV03']."\n");
      array_splice($array, $WRITE_NR+5,  1, $_GET['VOL_DEV04']."\n");
      array_splice($array, $WRITE_NR+6,  1, $_GET['VOL_DEV05']."\n");
      array_splice($array, $WRITE_NR+7,  1, $_GET['VOL_DEV06']."\n");
      array_splice($array, $WRITE_NR+8,  1, $_GET['VOL_DEV07']."\n");
      array_splice($array, $WRITE_NR+9,  1, $_GET['VOL_DEV08']."\n");
      array_splice($array, $WRITE_NR+10, 1, $_GET['VOL_DEV09']."\n");
      array_splice($array, $WRITE_NR+11, 1, $_GET['VOL_DEV10']."\n");
     $string = implode("", $array);
   file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
              header("Location: 03mpd_helper.php?DEV=$DEVICE");
    }


$datei = "/opt/innotune/settings/mpdvolplay.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1     >> Playlist Name                $array_config[0]
// Zeile 2     >> Lautstärke SQ&AIRPLAY        $array_config[1]
// Zeile 3-12  >> Lautstärke Z1                $array_config[2]

$TITLE=($DEVICE*12)-12;
$VOL_BACKROUND=($DEVICE*12)-11;
$VOL_DEV01=($DEVICE*12)-10;
$VOL_DEV02=($DEVICE*12)-9;
$VOL_DEV03=($DEVICE*12)-8;
$VOL_DEV04=($DEVICE*12)-7;
$VOL_DEV05=($DEVICE*12)-6;
$VOL_DEV06=($DEVICE*12)-5;
$VOL_DEV07=($DEVICE*12)-4;
$VOL_DEV08=($DEVICE*12)-3;
$VOL_DEV09=($DEVICE*12)-2;
$VOL_DEV10=($DEVICE*12)-1;

include "head.php";
include "navigation.php";
  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Audio Einstellung Playlist ID $DEVICE</h3>
          <br>
          <form method="get">
          <table align="center"><input readonly name="DEV" type="text" value="$DEVICE" size=1 style="border:#FFFFFF; color:#FFFFFF";" />          
                 <td><h5>Playlist Title</h5></td>
                 <td><input name="TITLE" type="text" value="$array_config[$TITLE]" size=22 /></td>
                 </tr>
                 <tr>
                 <td><h5>Lautstärke Hintergrundmusik (%)</h5></td>
                 <td><input name="VOL_BACKROUND" type="text" value="$array_config[$VOL_BACKROUND]" size=1 /></td>
                 </tr>
                 <tr>
          </table>
          <table align="center">
               <h5>Lautstärke der einzelnen Zonen</h5>
                 <td><div><img $array_dev_ready[0] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 1:</p></td>
                 <td><input name="VOL_DEV01" type="text" value="$array_config[$VOL_DEV01]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[1] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 2:</p></td>
                 <td><input name="VOL_DEV02" type="text" value="$array_config[$VOL_DEV02]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[2] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 3:</p></td>
                 <td><input name="VOL_DEV03" type="text" value="$array_config[$VOL_DEV03]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[3] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 4:</p></td>
                 <td><input name="VOL_DEV04" type="text" value="$array_config[$VOL_DEV04]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[4] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 5:</p></td>
                 <td><input name="VOL_DEV05" type="text" value="$array_config[$VOL_DEV05]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[5] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 6:</p></td>
                 <td><input name="VOL_DEV06" type="text" value="$array_config[$VOL_DEV06]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[6] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 7:</p></td>
                 <td><input name="VOL_DEV07" type="text" value="$array_config[$VOL_DEV07]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[7] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 8:</p></td>
                 <td><input name="VOL_DEV08" type="text" value="$array_config[$VOL_DEV08]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[8] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 9:</p></td>
                 <td><input name="VOL_DEV09" type="text" value="$array_config[$VOL_DEV09]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[9] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 10:</p></td>
                 <td><input name="VOL_DEV10" type="text" value="$array_config[$VOL_DEV10]" size=1 /></td>
                 <td><p>%</p></td>
                 </tr>
                 <tr>
           </table>
          <br>
          <br>
          <table align="center">
                 <td><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="save">Speichern</button></td>
                 <td><button style="width: 50px;display: block;margin-right: auto;margin-left: auto" name="play">Play</button></td>
                 <td><button style="width: 50px;display: block;margin-right: auto;margin-left: auto" name="stop">Stop</button></td>
                 </tr>
                 <tr>
          </table>
          </form>
           <br>
           <br>
        <h5>Beispiel Loxone Virtueller Ausgangsverbinder:</h5>
            <br>
           <table align="center">
             <td><p1 style="font-weight:bold;">Playlist $DEVICE Play:<p1></td>
             <td><p1>/phpcontrol/mpdvol.php?playlist_id=$DEVICE&play=1<p1></td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Stop:<p1></td>
             <td><p1>/phpcontrol/mpdvol.php?stop=1<p1></td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Playlist Repeat:<p1></td>
             <td><p1>/phpcontrol/mpdvol.php?repeat=1<p1></td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Lautstärke Playlist $DEVICE USB-Gerät 1:<p1></td>
             <td><p1>/phpcontrol/mpdvol.php?playlist_id=$DEVICE&vol_play01=&#60;v&#62;<p1></td>
             </tr>
             <tr>
             <td><p1 style="font-weight:bold;">Lautstärke Playlist $DEVICE USB-Gerät 2:<p1></td>
             <td><p1>/phpcontrol/mpdvol.php?playlist_id=$DEVICE&vol_play02=&#60;v&#62;<p1></td>
             </tr>
             <tr> 
           </table>

            <br>
            <br>


EOT;
include "footer.php";
?>