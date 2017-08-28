<?php

$datei = "/opt/innotune/settings/mpdvolplay.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1     >> Playlist Name                $array_config[0]
// Zeile 2     >> Lautstärke SQ&AIRPLAY        $array_config[1]
// Zeile 3-12  >> Lautstärke Z1                $array_config[2]

$TITLE01 = $_GET['TITLE01'];
$TITLE02 = $_GET['TITLE02'];
$TITLE03 = $_GET['TITLE03'];
$TITLE04 = $_GET['TITLE04'];
$TITLE05 = $_GET['TITLE05'];
$TITLE06 = $_GET['TITLE06'];
$TITLE07 = $_GET['TITLE07'];
$TITLE08 = $_GET['TITLE08'];
$TITLE09 = $_GET['TITLE09'];
$TITLE10 = $_GET['TITLE10'];

// Button Audio Einstellungen Player 01
if (isset($_GET['setting01']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 0, 1, "$TITLE01"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=01");
    }
// Button Audio Einstellungen Player 02
if (isset($_GET['setting02']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 12, 1, "$TITLE02"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=02");
    }
// Button Audio Einstellungen Player 03
if (isset($_GET['setting03']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 24, 1, "$TITLE03"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=03");
    }
// Button Audio Einstellungen Player 04
if (isset($_GET['setting04']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 36, 1, "$TITLE04"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=04");
    }
// Button Audio Einstellungen Player 05
if (isset($_GET['setting05']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 48, 1, "$TITLE05"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=05");
    }
// Button Audio Einstellungen Player 06
if (isset($_GET['setting06']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 60, 1, "$TITLE06"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=06");
    }
// Button Audio Einstellungen Player 07
if (isset($_GET['setting07']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 72, 1, "$TITLE07"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=07");
    }
// Button Audio Einstellungen Player 08
if (isset($_GET['setting08']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 84, 1, "$TITLE08"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=08");
    }
// Button Audio Einstellungen Player 09
if (isset($_GET['setting09']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 96, 1, "$TITLE09"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=09");
    }
// Button Audio Einstellungen Player 10
if (isset($_GET['setting10']))
    {
$array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
array_splice($array, 108, 1, "$TITLE10"."\n");
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
             header("Location: 03mpd_helper.php?DEV=10");
    }


include "head.php";
include "navigation.php";
  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Player Zentral (MPD)</h3>
        <p1>Wird auf allen angeschlossenen AMP´s ausgegeben. Anwendung bsp.: Haustürgong, Zentralmeldung, ...</p1>
        <iframe src="/phpMPD/index.php" width="100%" height=350" style="width:100%;height:500;" frameborder="0" scrolling="yes"></iframe>
            <br>
     <form method="get">
           <h5>Playlist Zonen-Wiedergabe</h5><input readonly name="DEV" type="text" value="$DEVICE" size=22 style="border:#FFFFFF; color:#FFFFFF";" />
              <table align="center">
                <td></td>
                <td><p style="font-weight:bold;">Eingabe Playlist Titel</p></td>
                <td></td>
                </tr>
                <tr>
                <td><p>Playlist ID 01:</p></td>
                <td><input name="TITLE01" type="text" value="$array_config[0]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting01">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 02:</p></td>
                <td><input name="TITLE02" type="text" value="$array_config[12]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting02">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 03:</p></td>
                <td><input name="TITLE03" type="text" value="$array_config[24]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting03">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 04:</p></td>
                <td><input name="TITLE04" type="text" value="$array_config[36]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting04">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 05:</p></td>
                <td><input name="TITLE05" type="text" value="$array_config[48]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting05">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 06:</p></td>
                <td><input name="TITLE06" type="text" value="$array_config[60]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting06">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 07:</p></td>
                <td><input name="TITLE07" type="text" value="$array_config[72]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting07">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 08:</p></td>
                <td><input name="TITLE08" type="text" value="$array_config[84]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting08">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 09:</p></td>
                <td><input name="TITLE09" type="text" value="$array_config[96]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting09">Audio Einstellungen</button></td>
                </tr>
                <tr>
                <td><p>Playlist ID 10:</p></td>
                <td><input name="TITLE10" type="text" value="$array_config[108]" size=22 /></td>
                <td><button style="width: 150px;display: block;margin-right: auto;margin-left: auto" name="setting10">Audio Einstellungen</button></td>
                </tr>
                <tr>
              </table>
          </form>
            <br>
            <br>


EOT;
include "footer.php";
?>