<?php

//page settings
$namePage = "05mode.php";

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

// Settings USB-Gerät 01
$datei = "/opt/innotune/settings/settings_player/dev01.txt"; // Name der Datei
$array_usb01_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 02
$datei = "/opt/innotune/settings/settings_player/dev02.txt"; // Name der Datei
$array_usb02_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 03
$datei = "/opt/innotune/settings/settings_player/dev03.txt"; // Name der Datei
$array_usb03_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 04
$datei = "/opt/innotune/settings/settings_player/dev04.txt"; // Name der Datei
$array_usb04_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 05
$datei = "/opt/innotune/settings/settings_player/dev05.txt"; // Name der Datei
$array_usb05_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 06
$datei = "/opt/innotune/settings/settings_player/dev06.txt"; // Name der Datei
$array_usb06_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 07
$datei = "/opt/innotune/settings/settings_player/dev07.txt"; // Name der Datei
$array_usb07_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 08
$datei = "/opt/innotune/settings/settings_player/dev08.txt"; // Name der Datei
$array_usb08_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 09
$datei = "/opt/innotune/settings/settings_player/dev09.txt"; // Name der Datei
$array_usb09_mode = file($datei); // Datei in ein Array einlesen
// Settings USB-Gerät 10
$datei = "/opt/innotune/settings/settings_player/dev10.txt"; // Name der Datei
$array_usb10_mode = file($datei); // Datei in ein Array einlesen

$DEV01_MODE = $_GET['mode_dev01'];
$DEV02_MODE = $_GET['mode_dev02'];
$DEV03_MODE = $_GET['mode_dev03'];
$DEV04_MODE = $_GET['mode_dev04'];
$DEV05_MODE = $_GET['mode_dev05'];
$DEV06_MODE = $_GET['mode_dev06'];
$DEV07_MODE = $_GET['mode_dev07'];
$DEV08_MODE = $_GET['mode_dev08'];
$DEV09_MODE = $_GET['mode_dev09'];
$DEV10_MODE = $_GET['mode_dev10'];

//Button Audio Konfiguration erzeugen
if (isset($_GET['button_audio_config']))
    {
    header("Location: 05mode_helper.php");
    }

//Button Player Konfiguration erzeugen
if (isset($_GET['button_player_config']))
    {
    header("Location: 01config_helper.php");
    }

// Button Konfiguration Player 01
if (isset($_GET['setting01']))
    {
$array = file("/opt/innotune/settings/settings_player/dev01.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV01_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev01.txt", $string);
             header("Location: 01config.php?DEV=01");
    }
// Button Konfiguration Player 02
if (isset($_GET['setting02']))
    {
$array = file("/opt/innotune/settings/settings_player/dev02.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV02_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev02.txt", $string);
             header("Location: 01config.php?DEV=02");
    }
// Button Konfiguration Player 03
if (isset($_GET['setting03']))
    {
$array = file("/opt/innotune/settings/settings_player/dev03.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV03_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev03.txt", $string);
             header("Location: 01config.php?DEV=03");
    }
// Button Konfiguration Player 04
if (isset($_GET['setting04']))
    {
$array = file("/opt/innotune/settings/settings_player/dev04.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV04_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev04.txt", $string);
             header("Location: 01config.php?DEV=04");
    }
// Button Konfiguration Player 05
if (isset($_GET['setting05']))
    {
$array = file("/opt/innotune/settings/settings_player/dev05.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV05_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev05.txt", $string);
             header("Location: 01config.php?DEV=05");
    }
// Button Konfiguration Player 06
if (isset($_GET['setting06']))
    {
$array = file("/opt/innotune/settings/settings_player/dev06.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV06_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev06.txt", $string);
             header("Location: 01config.php?DEV=06");
    }
// Button Konfiguration Player 07
if (isset($_GET['setting07']))
    {
$array = file("/opt/innotune/settings/settings_player/dev07.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV07_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev07.txt", $string);
             header("Location: 01config.php?DEV=07");
    }
// Button Konfiguration Player 08
if (isset($_GET['setting08']))
    {
$array = file("/opt/innotune/settings/settings_player/dev08.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV08_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev08.txt", $string);
             header("Location: 01config.php?DEV=08");
    }
// Button Konfiguration Player 09
if (isset($_GET['setting09']))
    {
$array = file("/opt/innotune/settings/settings_player/dev09.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV09_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev09.txt", $string);
             header("Location: 01config.php?DEV=09");
    }
// Button Konfiguration Player 10
if (isset($_GET['setting10']))
    {
$array = file("/opt/innotune/settings/settings_player/dev10.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$DEV10_MODE"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/settings_player/dev10.txt", $string);
             header("Location: 01config.php?DEV=10");
    }



// USB-Gerät Modus Gerät 1
$array_dev01_mode;
if     ($array_usb01_mode[0]==0) {
	$array_dev01_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb01_mode[0]==1) {
	$array_dev01_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb01_mode[0]==2) {
	$array_dev01_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb01_mode[0]==3) {
	$array_dev01_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb01_mode[0]==4) {
	$array_dev01_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 2
$array_dev02_mode;
if     ($array_usb02_mode[0]==0) {
	$array_dev02_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb02_mode[0]==1) {
	$array_dev02_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb02_mode[0]==2) {
	$array_dev02_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb02_mode[0]==3) {
	$array_dev02_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb02_mode[0]==4) {
	$array_dev02_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 3
$array_dev03_mode;
if     ($array_usb03_mode[0]==0) {
	$array_dev03_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb03_mode[0]==1) {
	$array_dev03_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb03_mode[0]==2) {
	$array_dev03_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb03_mode[0]==3) {
	$array_dev03_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb03_mode[0]==4) {
	$array_dev03_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 4
$array_dev04_mode;
if     ($array_usb04_mode[0]==0) {
	$array_dev04_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb04_mode[0]==1) {
	$array_dev04_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb04_mode[0]==2) {
	$array_dev04_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb04_mode[0]==3) {
	$array_dev04_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb04_mode[0]==4) {
	$array_dev04_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 5
$array_dev05_mode;
if     ($array_usb05_mode[0]==0) {
	$array_dev05_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb05_mode[0]==1) {
	$array_dev05_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb05_mode[0]==2) {
	$array_dev05_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb05_mode[0]==3) {
	$array_dev05_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb05_mode[0]==4) {
	$array_dev05_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 6
$array_dev06_mode;
if     ($array_usb06_mode[0]==0) {
	$array_dev06_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb06_mode[0]==1) {
	$array_dev06_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb06_mode[0]==2) {
	$array_dev06_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb06_mode[0]==3) {
	$array_dev06_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb06_mode[0]==4) {
	$array_dev06_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 7
$array_dev07_mode;
if     ($array_usb07_mode[0]==0) {
	$array_dev07_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb07_mode[0]==1) {
	$array_dev07_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb07_mode[0]==2) {
	$array_dev07_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb07_mode[0]==3) {
	$array_dev07_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb07_mode[0]==4) {
	$array_dev07_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 8
$array_dev08_mode;
if     ($array_usb08_mode[0]==0) {
	$array_dev08_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb08_mode[0]==1) {
	$array_dev08_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb08_mode[0]==2) {
	$array_dev08_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb08_mode[0]==3) {
	$array_dev08_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb08_mode[0]==4) {
	$array_dev08_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 9
$array_dev09_mode;
if     ($array_usb09_mode[0]==0) {
	$array_dev09_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb09_mode[0]==1) {
	$array_dev09_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb09_mode[0]==2) {
	$array_dev09_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb09_mode[0]==3) {
	$array_dev09_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb09_mode[0]==4) {
	$array_dev09_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}
// USB-Gerät Modus Gerät 10
$array_dev10_mode;
if     ($array_usb10_mode[0]==0) {
	$array_dev10_mode[0] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb10_mode[0]==1) {
	$array_dev10_mode[1] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb10_mode[0]==2) {
	$array_dev10_mode[2] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb10_mode[0]==3) {
	$array_dev10_mode[3] = "selected=selected style=\"background-color: #40FF00;\"";
}
elseif ($array_usb10_mode[0]==4) {
	$array_dev10_mode[4] = "selected=selected style=\"background-color: #40FF00;\"";
}

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



include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Konfiguration USB-Geräte</h3>
          <br>
          <form method="get">
          <table align="center">
                  <td></td>
                  <td></td>
                  <td><p style="font-weight:bold;">Audio Konfiguration</p></td>
                  </tr>
                 <tr>
                 <td><div><img $array_dev_ready[0] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 1:</p></td>
                 <td><select name="mode_dev01"> 
                    <option value="0" $array_dev01_mode[0] name="dev01_mode01">Deaktiviert</option>
                    <option value="1" $array_dev01_mode[1] name="dev01_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev01_mode[2] name="dev01_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting01">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[1] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 2:</p></td>
                 <td><select name="mode_dev02"> 
                    <option value="0" $array_dev02_mode[0] name="dev02_mode01">Deaktiviert</option>
                    <option value="1" $array_dev02_mode[1] name="dev02_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev02_mode[2] name="dev02_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting02">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[2] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 3:</p></td>
                 <td><select name="mode_dev03"> 
                    <option value="0" $array_dev03_mode[0] name="dev03_mode01">Deaktiviert</option>
                    <option value="1" $array_dev03_mode[1] name="dev03_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev03_mode[2] name="dev03_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting03">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[3] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 4:</p></td>
                 <td><select name="mode_dev04"> 
                    <option value="0" $array_dev04_mode[0] name="dev04_mode01">Deaktiviert</option>
                    <option value="1" $array_dev04_mode[1] name="dev04_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev04_mode[2] name="dev04_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting04">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[4] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 5:</p></td>
                 <td><select name="mode_dev05"> 
                    <option value="0" $array_dev05_mode[0] name="dev05_mode01">Deaktiviert</option>
                    <option value="1" $array_dev05_mode[1] name="dev05_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev05_mode[2] name="dev05_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting05">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[5] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 6:</p></td>
                 <td><select name="mode_dev06"> 
                    <option value="0" $array_dev06_mode[0] name="dev06_mode01">Deaktiviert</option>
                    <option value="1" $array_dev06_mode[1] name="dev06_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev06_mode[2] name="dev06_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting06">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[6] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 7:</p></td>
                 <td><select name="mode_dev07"> 
                    <option value="0" $array_dev07_mode[0] name="dev07_mode01">Deaktiviert</option>
                    <option value="1" $array_dev07_mode[1] name="dev07_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev07_mode[2] name="dev07_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting07">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[7] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 8:</p></td>
                 <td><select name="mode_dev08"> 
                    <option value="0" $array_dev08_mode[0] name="dev08_mode01">Deaktiviert</option>
                    <option value="1" $array_dev08_mode[1] name="dev08_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev08_mode[2] name="dev08_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting08">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[8] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 9:</p></td>
                 <td><select name="mode_dev09"> 
                    <option value="0" $array_dev09_mode[0] name="dev09_mode01">Deaktiviert</option>
                    <option value="1" $array_dev09_mode[1] name="dev09_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev09_mode[2] name="dev09_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting09">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[9] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 10:</p></td>
                 <td><select name="mode_dev10"> 
                    <option value="0" $array_dev10_mode[0] name="dev10_mode01">Deaktiviert</option>
                    <option value="1" $array_dev10_mode[1] name="dev10_mode02">Normalbetrieb 1xZone (60W Stereo)</option>
                    <option value="2" $array_dev10_mode[2] name="dev10_mode03">Geteilter-Betrieb 2xZone (60W Mono)</option>  
                    </select></td>
                 <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting10">Player Konfigration</button></td></form>
                 </tr>
                 <tr>
           </table>
          <br>
          <br>
           <table align="center">
                    <form name="name_info" method="get" onsubmit="return confirm('Sind Sie sicher? Audio Konfiguration wird erzeugt, dies kann mehrere Minuten dauern! SYSTEM MUSS ANSCHLIESSEND NEU GESTARTET WERDEN!')">
                    <td><button style="width: 200px;display: block;margin-right: auto;margin-left: auto" name="button_audio_config">Audio Konfiguration erzeugen</button></td></form>
                    <form name="name_info" method="get" onsubmit="return confirm('Sind Sie sicher? Media Server & (Sh)Airplay wird neu gestartet, dies kann mehrere Minuten dauern!')">
                    <td><button style="width: 200px;display: block;margin-right: auto;margin-left: auto" name="button_player_config">Player Konfiguration erzeugen</button></td></form> 
                 </tr>
                 <tr> 
            </table>
       </form>
       <h5></h5>
  


EOT;
include "footer.php";
?>





















