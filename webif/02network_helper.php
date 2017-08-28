<?php



$DHCP = $_GET['DHCP'];
$IP = $_GET['IP'];
$SUBNET = $_GET['SUBNET'];
$GATE = $_GET['GATE'];
$DNS1 = $_GET['DNS1'];
$DNS2 = $_GET['DNS2'];


//DHCP Aktiviert - keine Eingabekontrolle nÃ¶tig.
if (isset($_GET['DHCP']))
    {

 $CONTROL_INPUT="Aktion erfolgreich! System startet neu.";
      $array = file("/opt/innotune/settings/network.txt"); // Datei in ein Array einlesen
        array_splice($array, 0, 1, "$DHCP"."\n");
        array_splice($array, 1, 1, "$IP"."\n");
        array_splice($array, 2, 1, "$SUBNET"."\n");
        array_splice($array, 3, 1, "$GATE"."\n");
        array_splice($array, 5, 1, "$DNS1"."\n");
        array_splice($array, 6, 1, "$DNS2"."\n");
       $string = implode("", $array);
      file_put_contents("/opt/innotune/settings/network.txt", $string);

exec('sudo /var/www/sudoscript.sh setnet',$output,$return_var);  



// Control inputs
    } elseif ( preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $IP) &&  preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $SUBNET) &&  preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $GATE) &&  preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $DNS1) &&  preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $DNS2) ) {
     $CONTROL_INPUT="Aktion erfolgreich! System startet neu.";
      $array = file("/opt/innotune/settings/network.txt"); // Datei in ein Array einlesen
        array_splice($array, 0, 1, "$DHCP"."\n");
        array_splice($array, 1, 1, "$IP"."\n");
        array_splice($array, 2, 1, "$SUBNET"."\n");
        array_splice($array, 3, 1, "$GATE"."\n");
        array_splice($array, 5, 1, "$DNS1"."\n");
        array_splice($array, 6, 1, "$DNS2"."\n");
       $string = implode("", $array);
      file_put_contents("/opt/innotune/settings/network.txt", $string);

exec('sudo /var/www/sudoscript.sh setnet',$output,$return_var);


   } else {
     $CONTROL_INPUT="...Bitte Ã¼berprÃ¼fen Sie Ihre Eingaben! Bei einer Ã„nderung mÃ¼ssen alle Eingabefelder richtig ausgefÃ¼llt werden!...</p1>";
     $ZUSATZTEXT="Sollten sie nicht automatisch weitergeleitet werden, klicken Sie bitte im NavigationsmenÃ¼.";
}

include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Netzwerk</h3>
            

   <h5>$CONTROL_INPUT</h5>
   <i>$ZUSATZTEXT</i>
<meta http-equiv="refresh" content="4; URL=02network.php">


EOT;
include "footer.php";
?>