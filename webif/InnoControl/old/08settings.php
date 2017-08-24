<?php

$datei = "/opt/innotune/settings/web_settings.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen



include "head.php";
include "navigation.php";

  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Einstellungen</h3>
      <form name="network_info" method="get" action="08settings_helper.php" onsubmit="return confirm('Sind Sie sicher?')">
              <table align="center">
                <td><p>Benutzername:</p></td>
                <td><i>admin</i></td>
                </tr>
                <tr>
                <td><p>Passwort:</p></td>
                <td><input name="password" type="text" value="$array_config[0]" size=12 /></td>
                </tr>
                <tr>
                <td><p>Port Webinterface:</p></td>
                <td><input name="port" type="text" value="$array_config[1]" size=12 /></td>
                </tr>
                <tr>
            </table>
    <br><br><br>
    <div style="margin:0 auto;width:50px">
        <button style="width:100px;margin:0 5px">Speichern</button>
    </div>
    <br><br><br>         
EOT;
include "footer.php";
?>