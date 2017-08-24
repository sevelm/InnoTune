<?php

// Übergabewert welches Device soll eingelesen weden 01,02,03,...
$DEVICE = $_GET['DEV'];

exec("sudo /var/www/sudoscript.sh show_vol_equal $DEVICE",$output,$return_var);

$datei = "/opt/innotune/settings/status_vol_equal/dev$DEVICE.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1  >> Lautstärke MPD               $array_config[0]
// Zeile 2  >> Lautstärke SQUEEZEBOX        $array_config[1]
// Zeile 3  >> Lautstärke AIRPLAY           $array_config[2]
// Zeile 4  >> Lautstärke LINE-IN           $array_config[3]

// In Int umrechnen
$valueMPD = intval( $array_config[0] );
$valueSQ = intval( $array_config[1] );
$valueAIR = intval( $array_config[2] );
$valueLINEIN = intval( $array_config[3] );

//MPD Vol. Setup
if (isset($_GET['mpd_vol_up']))
    {
      $up = $valueMPD + 10;
       echo $valueMPD;
              exec("sudo /var/www/sudoscript.sh set_vol $DEVICE mpd $up",$output,$return_var);
              header("Location: 06audio_helper.php?DEV=$DEVICE");  
    }
if (isset($_GET['mpd_vol_down']))
    {
     $down = $valueMPD - 10;
               exec("sudo /var/www/sudoscript.sh set_vol $DEVICE mpd $down",$output,$return_var);
               header("Location: 06audio_helper.php?DEV=$DEVICE"); 
    }

//Squeezebox Vol. Setup
if (isset($_GET['squeeze_vol_up']))
    {
      $up = $valueSQ + 10;
              exec("sudo /var/www/sudoscript.sh set_vol $DEVICE squeeze $up",$output,$return_var);
              header("Location: 06audio_helper.php?DEV=$DEVICE");  
    }

if (isset($_GET['squeeze_vol_down']))
    {
     $down = $valueSQ - 10;
               exec("sudo /var/www/sudoscript.sh set_vol $DEVICE squeeze $down",$output,$return_var);
               header("Location: 06audio_helper.php?DEV=$DEVICE"); 
    }

//Airplay Vol. Setup
if (isset($_GET['airplay_vol_up']))
    {
      $up = $valueAIR + 10;
              exec("sudo /var/www/sudoscript.sh set_vol $DEVICE airplay $up",$output,$return_var);
              header("Location: 06audio_helper.php?DEV=$DEVICE");  
    }
if (isset($_GET['airplay_vol_down']))
    {
     $down = $valueAIR - 10;
               exec("sudo /var/www/sudoscript.sh set_vol $DEVICE airplay $down",$output,$return_var);
               header("Location: 06audio_helper.php?DEV=$DEVICE"); 
    }

//Line-In Vol. Setup
if (isset($_GET['linein_vol_up']))
    {
      $up = $valueLINEIN + 10;
              exec("sudo /var/www/sudoscript.sh set_vol $DEVICE LineIn $up",$output,$return_var);
              header("Location: 06audio_helper.php?DEV=$DEVICE");  
    }
if (isset($_GET['linein_vol_down']))
    {
     $down = $valueLINEIN - 10;
               exec("sudo /var/www/sudoscript.sh set_vol $DEVICE LineIn $down",$output,$return_var);
               header("Location: 06audio_helper.php?DEV=$DEVICE"); 
    }




include "head.php";
include "navigation.php";

  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Audio</h1>
      <h3>Audio Zone $DEVICE</h3>
        <form method="get">
           <h5>Lautstärkenbegrenzung</h5><input readonly name="DEV" type="text" value="$DEVICE" size=22 style="border:#FFFFFF; color:#FFFFFF";" />
              <table align="center">
                <td><p2>Player Zentral (MPD)</p2></td>
                <td><p2>Squeezebox</p2></td>
                <td><p2>Airplay</p2></td>
                <td><p2>Line-In</p2></td>
                </tr>
                <tr>
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="mpd_vol_up">+</button></td>  
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="squeeze_vol_up">+</button></td>  
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="airplay_vol_up">+</button></td>
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="linein_vol_up">+</button></td>
                </tr>
                <tr>
                <td>
                <div style="margin-right: auto;margin-left: auto;display: block;height: 100px;position: relative;width: 30px; background-color: #F2F2F2;">
                <div style="height: $valueMPD%;position: absolute;bottom: 0px;left: 0px;width: 100%;background-color: #6E6E6E;font-size: 1px;">&nbsp;</div>
                </div></td>
                <td>
                <div style="margin-right: auto;margin-left: auto;display: block;height: 100px;position: relative;width: 30px; background-color: #F2F2F2;">
                <div style="height: $valueSQ%;position: absolute;bottom: 0px;left: 0px;width: 100%;background-color: #6E6E6E;font-size: 1px;">&nbsp;</div>
                </div></td>
                <td>
                <div style="margin-right: auto;margin-left: auto;display: block;height: 100px;position: relative;width: 30px; background-color: #F2F2F2;">
                <div style="height: $valueAIR%;position: absolute;bottom: 0px;left: 0px;width: 100%;background-color: #6E6E6E;font-size: 1px;">&nbsp;</div>
                </div></td>
                <td>
                <div style="margin-right: auto;margin-left: auto;display: block;height: 100px;position: relative;width: 30px; background-color: #F2F2F2;">
                <div style="height: $valueLINEIN%;position: absolute;bottom: 0px;left: 0px;width: 100%;background-color: #6E6E6E;font-size: 1px;">&nbsp;</div>
                </div></td>
                </tr>
                <tr>
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="mpd_vol_down">-</button></td>  
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="squeeze_vol_down">-</button></td>  
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="airplay_vol_down">-</button></td>
                <td><button style="width: 30px;display: block;margin-right: auto;margin-left: auto" name="linein_vol_down">-</button></td>  
                </tr>
                <tr>
              </table>
             </form>
EOT;

include "footer.php";

?>