<?php

    $uploaddir = '/var/www/upload_download/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);



    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
       exec("sudo /var/www/sudoscript.sh restore_settings",$output,$return_var);
      $CONTROL_INPUT="...Aktion erfolgreich!...";
    } else {
      $CONTROL_INPUT="...Upload failed!...";
    }

include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Allgemein</h1>
      <h3>Update & Dokumentation</h3>
            

   <h5>$CONTROL_INPUT</h5>
<i>Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im Navigationsmenü.</i>
<meta http-equiv="refresh" content="4; URL=07update_docu.php">



EOT;
include "footer.php";
?>
