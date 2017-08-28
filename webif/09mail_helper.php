<?php

$SENDER1           = $_GET['SENDER'];
$RECIPIENT1        = $_GET['RECIPIENT'];
$SMTPSERVER1       = $_GET['SMTPSERVER'];
$SMTPUSERNAME1     = $_GET['SMTPUSERNAME'];
$SMTPPASSWORD1     = $_GET['SMTPPASSWORD'];
$REGARD1           = $_GET['REGARD'];

     $CONTROL_INPUT="...Aktion erfolgreich!...";



      $array = file("/opt/innotune/settings/sendmail_data.txt"); // Datei in ein Array einlesen
        array_splice($array, 0, 1, "$SENDER1"."\n");
        array_splice($array, 1, 1, "$RECIPIENT1"."\n");
        array_splice($array, 2, 1, "$SMTPSERVER1"."\n");
        array_splice($array, 3, 1, "$SMTPUSERNAME1"."\n");
        array_splice($array, 4, 1, "$SMTPPASSWORD1"."\n");
        array_splice($array, 5, 1, "$REGARD1"."\n");
       $string = implode("", $array);
      file_put_contents("/opt/innotune/settings/sendmail_data.txt", $string);


include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Allgemein</h1>
      <h3>E-mail</h3>
            

   <h5>$CONTROL_INPUT</h5>
<i>Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im Navigationsmenü.</i>
<meta http-equiv="refresh" content="4; URL=09mail.php">



EOT;
include "footer.php";
