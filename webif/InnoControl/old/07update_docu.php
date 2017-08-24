<?php

// Aktuelle Version einlesen
$datei = "version.txt"; // Name der Datei
$array_now = file($datei); // Datei in ein Array einlesen
// Zeile 1   >> Version          $array_now[0]

// Verfügbare Version einlesen
$datei = "http://innotune.at/update_innotune/odroid/version.txt"; // Name der Datei
$array_new = file($datei); // Datei in ein Array einlesen
// Zeile 1   >> Version          $array_new[0]

//Button Update
if (isset($_GET['button_update']))
    {
              exec("sudo /var/www/sudoscript.sh update",$output,$return_var);
    }

include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Update & Dokumentation & Sicherung</h3>
        <form method="get">
         <h5>Download Loxone Vorlagen</h5>
<br>
               <table align="center">       
                <td><p>Loxone Musterprojekt (V5.66):</p></td>
                <td><a href="/upload_download/download.php?DEV=01">Download</a></td>                   
                </tr>
                <tr>
                <td><p>Virtuelle Ausgänge:</p></td>
                <td><a href="/upload_download/download.php?DEV=02">Download</a></td>                   
                </tr>
                <tr>
              </table>
         <h5>Update</h5>
<br>
               <table align="center">       
                <td><p>Aktuelle Version:</p></td>
                <td><p style="font-weight:bold;">$array_now[0]</p></td>                   
                </tr>
                <tr>
                <td><p>Verfügbare Version:</p></td>
                <td><p style="font-weight:bold;">$array_new[0]</p></td>                   
                </tr>
                <tr>
              </table>  
             </form>
              <table align="center">
                    <form name="name_info" method="get" onsubmit="return confirm('Sind Sie sicher? Update wird durchgeführt, dies kann mehrere Minuten dauern!')">
                    <td><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="button_update">Update</button></td></form>
                 </tr>
                 <tr> 
              </table>
         <h5>Sichernung exportieren</h5>
<br>
             <table align="center">       
                <td><p>Daten auf den PC sichern:</p></td>
                <td><a href="/upload_download/download.php?DEV=03">Download</a></td> 
             </table>
         <h5>Sichernung importieren</h5>
<br> 
             <table align="center"> 
                <form enctype="multipart/form-data" action="07upload_settings_helper.php" method="POST">
                       <input type="hidden" name="MAX_FILE_SIZE" value="512000" />

                <td><input name="userfile" type="file" /></td> 
                <td><input type="submit" value="Wiederherstellen" /></form></td>
                 </tr>
                 <tr> 
                <td><p>Wichtig! Dateiname: settings.zip</p></td>
             </table>





 
<br>
<br>

</body>
</html>
 


EOT;
include "footer.php";
?>
