<?php

exec('sudo /var/www/sudoscript.sh shnet',$output,$return_var);

$datei = "/opt/innotune/settings/network.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1 >> checkbox DHCP             $array_config[0]
// Zeile 2 >> IP-Adresse                $array_config[1]
// Zeile 3 >> Sunetmaske                $array_config[2]
// Zeile 4 >> Gateway                   $array_config[3]
// Zeile 5 >> MAC-Adresse               $array_config[4]
// Zeile 6 >> DNS-Eintrag 1             $array_config[5]
// Zeile 7 >> DNS-Eintrag 2             $array_config[6]

if ($array_config[0]>="1") {
	$checkbox = "checked=checked";
}

include "head.php";
include "navigation.php";

  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Netzwerk</h3>
      <form name="network_info" method="get" action="02network_helper.php" onsubmit="return confirm('Sind Sie sicher? System wird neu gestartet!')">
             <table align="center">
                <td><p>IP-Adresse Automatisch (DHCP)</p></td>
                <td><input name="DHCP" type="checkbox" $checkbox value="DHCP-on" /></td>
                </tr>
                <tr>
                <td><p>IP-Adresse:</p></td>
                <td><input name="IP" type="text" value="$array_config[1]" size=22 /></td>
                </tr>
                <tr>
                <td><p>Subnetmaske:</p></td>
                <td><input name="SUBNET" type="text" value="$array_config[2]" size=22 /></td>
                </tr>
                <tr>
                <td><p>Gateway:</p></td>
                <td><input name="GATE" type="text" value="$array_config[3]" size=22 /></td>
                </tr>
                <tr>
                <td><p>DNS1-Adresse:</p></td>
                <td><input name="DNS1" type="text" value="$array_config[5]" size=22 /></td>
                </tr>
                <tr>
                <td><p>DNS2-Adresse:</p></td>
                <td><input name="DNS2" type="text" value="$array_config[6]" size=22 /></td>
                </tr>
                <tr>
                <td><p>MAC-Adresse:</p></td>
                <td>$array_config[4]</td>
            </table>
    <br><br><br>
    <div style="margin:0 auto;width:50px">
        <button style="width:100px;margin:0 5px">Speichern</button>
    </div>
               <br>
               <br>          
EOT;
include "footer.php";
?>