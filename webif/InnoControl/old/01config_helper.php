<?php


exec('sudo /var/www/sudoscript.sh setplayer',$output,$return_var);

include "head.php";
include "navigation.php";

  print <<< EOT


<td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Player Konfiguration erzeugt</h3>
            

   <h5>...Aktion Erfolgreich!...</h5>
<i>Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im Navigationsmenü.</i>
 <meta http-equiv="refresh" content="4; URL=05mode.php">


EOT;
include "footer.php";
?>
