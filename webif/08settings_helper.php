<?php

$passwort1     = $_GET['password'];
$port1         = $_GET['port'];

     $CONTROL_INPUT="...Aktion erfolgreich!...";



      $array = file("/opt/innotune/settings/web_settings.txt"); // Datei in ein Array einlesen
        array_splice($array, 0, 1, "$passwort1"."\n");
        array_splice($array, 1, 1, "$port1"."\n");
       $string = implode("", $array);
      file_put_contents("/opt/innotune/settings/web_settings.txt", $string);

exec('sudo /var/www/sudoscript.sh password',$output,$return_var);

include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Allgemein</h1>
      <h3>Einstellungen</h3>
            

   <h5>$CONTROL_INPUT</h5>
<i>Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im Navigationsmenü.</i>
<meta http-equiv="refresh" content="4; URL=08settings.php">



EOT;
include "footer.php";
