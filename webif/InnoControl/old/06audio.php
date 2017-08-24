<?php

//page settings
$namePage = "06audio.php";

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


// Button Equalizer Player 01
if (isset($_GET['equal01']))
    {
    header("Location: 06audio_helper.php?DEV=01");
    }
// Button Equalizer Player 02
if (isset($_GET['equal02']))
    {
    header("Location: 06audio_helper.php?DEV=02");
    }
// Button Equalizer Player 03
if (isset($_GET['equal03']))
    {
    header("Location: 06audio_helper.php?DEV=03");
    }
// Button Equalizer Player 04
if (isset($_GET['equal04']))
    {
    header("Location: 06audio_helper.php?DEV=04");
    }
// Button Equalizer Player 05
if (isset($_GET['equal05']))
    {
    header("Location: 06audio_helper.php?DEV=05");
    }
// Button Equalizer Player 06
if (isset($_GET['equal06']))
    {
    header("Location: 06audio_helper.php?DEV=06");
    }
// Button Equalizer Player 07
if (isset($_GET['equal07']))
    {
    header("Location: 06audio_helper.php?DEV=07");
    }
// Button Equalizer Player 08
if (isset($_GET['equal08']))
    {
    header("Location: 06audio_helper.php?DEV=08");
    }
// Button Equalizer Player 09
if (isset($_GET['equal09']))
    {
    header("Location: 06audio_helper.php?DEV=09");
    }
// Button Equalizer Player 10
if (isset($_GET['equal10']))
    {
    header("Location: 06audio_helper.php?DEV=10");
    }

// Button Line-In Player 01
if (isset($_GET['linein01']))
    {
    header("Location: 06audio_linein.php?DEV=01");
    }
// Button Line-In Player 02
if (isset($_GET['linein02']))
    {
    header("Location: 06audio_linein.php?DEV=02");
    }
// Button Line-In Player 03
if (isset($_GET['linein03']))
    {
    header("Location: 06audio_linein.php?DEV=03");
    }
// Button Line-In Player 04
if (isset($_GET['linein04']))
    {
    header("Location: 06audio_linein.php?DEV=04");
    }
// Button Line-In Player 05
if (isset($_GET['linein05']))
    {
    header("Location: 06audio_linein.php?DEV=05");
    }
// Button Line-In Player 06
if (isset($_GET['linein06']))
    {
    header("Location: 06audio_linein.php?DEV=06");
    }
// Button Line-In Player 07
if (isset($_GET['linein07']))
    {
    header("Location: 06audio_linein.php?DEV=07");
    }
// Button Line-In Player 08
if (isset($_GET['linein08']))
    {
    header("Location: 06audio_linein.php?DEV=08");
    }
// Button Line-In Player 09
if (isset($_GET['linein09']))
    {
    header("Location: 06audio_linein.php?DEV=09");
    }
// Button Line-In Player 10
if (isset($_GET['linein10']))
    {
    header("Location: 06audio_linein.php?DEV=10");
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
      <h3>Konfiguration Sound</h3>
          <br>
          <form method="get">
          <table align="center">
                  <td></td>
                  <td></td>
                  <td></td>
                  </tr>
                 <tr>
                 <td><div><img $array_dev_ready[0] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 1:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal01">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein01">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[1] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 2:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal02">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein02">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[2] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 3:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal03">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein03">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[3] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 4:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal04">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein04">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[4] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 5:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal05">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein05">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[5] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 6:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal06">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein06">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[6] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 7:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal07">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein07">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[7] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 8:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal08">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein08">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[8] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 9:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal09">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein09">Line-In</button></td></form>
                 </tr>
                 <tr>
                 <td><div><img $array_dev_ready[9] style="width: 30px;display: block;margin-right: auto;margin-left: auto" /></div></td>
                 <td><p>USB-Gerät 10:</p></td>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="equal10">Equalizer</button></td></form>
                 <td><button style="width: 130px;display: block;margin-right: auto;margin-left: auto" name="linein10">Line-In</button></td></form>
                 </tr>
                 <tr>
           </table>
          <br>
          <br>
           <table align="center">
                    
                 </tr>
                 <tr> 
            </table>
       </form>
       <h5></h5>
  


EOT;
include "footer.php";
?>





















