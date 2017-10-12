#!/usr/bin/php
<?php

$PLAY = ($_GET["play"]);
$STOP = ($_GET["stop"]);
$REPEAT = ($_GET["repeat"]);
$PLAYLIST_ID = ($_GET["playlist_id"]);
$PLAYL_TITLE = ($_GET["playlist_title"]);
$VOL_SQAIR = trim ($_GET["vol_sqair"]);
$VOL_PLAY01 = trim ($_GET["vol_play01"]);
$VOL_PLAY02 = trim ($_GET["vol_play02"]);
$VOL_PLAY03 = trim ($_GET["vol_play03"]);
$VOL_PLAY04 = trim ($_GET["vol_play04"]);
$VOL_PLAY05 = trim ($_GET["vol_play05"]);
$VOL_PLAY06 = trim ($_GET["vol_play06"]);
$VOL_PLAY07 = trim ($_GET["vol_play07"]);
$VOL_PLAY08 = trim ($_GET["vol_play08"]);
$VOL_PLAY09 = trim ($_GET["vol_play09"]);
$VOL_PLAY10 = trim ($_GET["vol_play10"]);


       if ($STOP != "") {    
       exec("sudo /var/www/sudoscript.sh mpdstop",$output,$return_var);
       }
          
       if ($REPEAT != "") {    
       exec("sudo /var/www/sudoscript.sh mpdrepeat",$output,$return_var);
       }
      
 

     if ($PLAYLIST_ID != "") {   

           $WRITE_NR = ($PLAYLIST_ID * 12) - 12 ; //Anfangsnummer für den Bereich in den geschrieben werden soll
     
           $array = file("/opt/innotune/settings/mpdvolplay.txt"); // Datei in ein Array einlesen
           if ($PLAYL_TITLE != ""){
           array_splice($array, $WRITE_NR, 1, "$PLAYL_TITLE"."\n");
             }
           if ($VOL_SQAIR != ""){
           array_splice($array, $WRITE_NR+1, 1, "$VOL_SQAIR"."\n");
             }
           if ($VOL_PLAY01 != ""){
           array_splice($array, $WRITE_NR+2, 1, "$VOL_PLAY01"."\n");
             }
           if ($VOL_PLAY02 != ""){
           array_splice($array, $WRITE_NR+3, 1, "$VOL_PLAY02"."\n");
             }
           if ($VOL_PLAY03 != ""){
           array_splice($array, $WRITE_NR+4, 1, "$VOL_PLAY03"."\n");
             }
           if ($VOL_PLAY04 != ""){
           array_splice($array, $WRITE_NR+5, 1, "$VOL_PLAY04"."\n");
             }
           if ($VOL_PLAY05 != ""){
           array_splice($array, $WRITE_NR+6, 1, "$VOL_PLAY05"."\n");
             }
           if ($VOL_PLAY06 != ""){
           array_splice($array, $WRITE_NR+7, 1, "$VOL_PLAY06"."\n");
             }
           if ($VOL_PLAY07 != ""){
           array_splice($array, $WRITE_NR+8, 1, "$VOL_PLAY07"."\n");
             }
           if ($VOL_PLAY08 != ""){
           array_splice($array, $WRITE_NR+9, 1, "$VOL_PLAY08"."\n");
             }
           if ($VOL_PLAY09 != ""){
           array_splice($array, $WRITE_NR+10, 1, "$VOL_PLAY09"."\n");
             }
           if ($VOL_PLAY10 != ""){
           array_splice($array, $WRITE_NR+11, 1, "$VOL_PLAY10"."\n");
             }
           $string = implode("", $array);
           file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);
 
           if ($PLAY != "") {    
           exec("sudo /var/www/sudoscript.sh mpdvolplay $PLAYLIST_ID",$output,$return_var);
             }
       }
   
?>