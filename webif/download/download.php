<?php

// Übergabewert welches Device soll eingelesen weden 01,02,03,...
$DEVICE = $_GET['DEV'];




//
if ($DEVICE==1) {
$filename = 'InnoTune.Loxone'; // of course find the exact filename....   
}
if ($DEVICE==2) {
$filename = 'Vorlage_Virtuelle_Ausgänge.zip'; // of course find the exact filename....   
}
     
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers 
header('Content-Type: application/pdf');

header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));

readfile($filename);

exit;
?>
