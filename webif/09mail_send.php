<?php

$BETREFF1              = $_GET['BETREFF'];
$NACHRICHT1            = $_GET['NACHRICHT'];

exec("sudo /var/www/sudoscript.sh sendmail \"$BETREFF1\" \"$NACHRICHT1\"",$output,$return_var);

// Ausgabe einlesen
$datei = "/var/www/return_values/sendmail.txt"; // Name der Datei
$array = file($datei); // Datei in ein Array einlesen


include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Allgemein</h1>
      <h3>E-mail</h3>
            

   <p>$array[0]</p>
   <p>$array[1]</p>
   <p>$array[2]</p>
   <p>$array[3]</p>
   <p>$array[4]</p>
   <p>$array[5]</p>
   <p>$array[6]</p>
   <p>$array[7]</p>
   <p>$array[8]</p>
   <p>$array[9]</p>
<br>
<i>Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im Navigationsmenü.</i>
<meta http-equiv="refresh" content="6; URL=09mail.php">



EOT;
include "footer.php";
