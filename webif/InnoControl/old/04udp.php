<?php

//PL01 SAVE
if (isset($_POST['SAVE']))    
     {
     $array = file("/opt/innotune/settings/udp.txt"); // Datei in ein Array einlesen
      array_splice($array, 0, 1, $_POST['FRG']."\n");
      array_splice($array, 1, 1, $_POST['IP']."\n");
      array_splice($array, 2, 1, $_POST['PORT']."\n");
      array_splice($array, 3, 1, $_POST['STATS_SQ']."\n");
      array_splice($array, 4, 1, $_POST['STATS_AIR']."\n");
      array_splice($array, 5, 1, $_POST['STATS_MPD']."\n");
     $string = implode("", $array);
   file_put_contents("/opt/innotune/settings/udp.txt", $string);   
     }


$datei = "/opt/innotune/settings/udp.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1  >> Freigabe            $array_config[0]
// Zeile 2  >> IP-Adresse          $array_config[1]
// Zeile 3  >> Port                $array_config[2]
// Zeile 4  >> Status Squeezbox    $array_config[3]
// Zeile 5  >> Status Airplay      $array_config[4]
// Zeile 6  >> Status MPD          $array_config[5]


if (isset($_POST['SAVE']) && $array_config[0]>="1") { 
     exec("sudo /var/www/sudoscript.sh start_sendudp",$output,$return_var);
     }
if (isset($_POST['SAVE']) && $array_config[0]<="1") { 
     exec("sudo /var/www/sudoscript.sh stop_sendudp",$output,$return_var);
     }


// checkbox Freigabe
if ($array_config[0]>="1") {
	$checkboxFRG = "checked=checked";
}

// checkbox Status Squeezbox
if ($array_config[3]>="1") {
	$checkboxSQ = "checked=checked";
}

// checkbox Status Airplay
if ($array_config[4]>="1") {
	$checkboxAIR = "checked=checked";
}

// checkbox Status MPD
if ($array_config[5]>="1") {
	$checkboxMPD = "checked=checked";
}

include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>UDP Kommunikation</h3>
        <form method="post">
              <table align="center">
        <p1>Der Status der angewählten Player wird bei Wertänderung und im Minutentakt gesendet.</p1>
        <br>
        <br>
                <td><p style="font-weight:bold;">Freigabe Senden:</p></td>
                <td><input name="FRG" type="checkbox" $checkboxFRG value="ON" /></td>
                </tr>
                <tr>
                <td><p>IP-Adresse:</p></td>
                <td><input name="IP" type="text" value="$array_config[1]" size=22 /></td>
                </tr>
                <tr>
                <td><p>Sende Port:</p></td>
                <td><input name="PORT" type="text" value="$array_config[2]" size=22 /></td>
                </tr>
                <tr>
                <td><p style="font-weight:bold;">Auswahl Status</p></td>
                </tr>
                <tr>
                <td><p>Squeezebox EIN/AUS:</p></td>
                <td><input name="STATS_SQ" type="checkbox" $checkboxSQ value="STATS_SQ" /></td>
<td><p>(Funktion noch in Arbeit)</p></td>
                </tr>
                <tr>
                <td><p>Airplay EIN/AUS:</p></td>
                <td><input name="STATS_AIR" type="checkbox" $checkboxAIR value="STATS_AIR" /></td>
                </tr>
                <tr>
                <td><p>Player Zentral (MPD) EIN/AUS:</p></td>
                <td><input name="STATS_MPD" type="checkbox" $checkboxMPD value="STATS_MPD" /></td>             
                </tr>
                <tr>
              </table> 
    <br><br><br>
    <div style="margin:0 auto;width:50px">
        <button style="width:100px;margin:0 5px" name="SAVE">Speichern</button>
    </div>
  </form>
 
<br>
<br>

</body>
</html>
 


EOT;
include "footer.php";
?>
