<?php
$namePage = "index.php";

exec('sudo /var/www/sudoscript.sh shnet',$output,$return_var);

$datei = "/opt/innotune/settings/network.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1 >> checkbox DHCP             $array_config[0]
// Zeile 2 >> IP-Adresse                $array_config[1]
// Zeile 3 >> Sunetmaske                $array_config[2]
// Zeile 4 >> Gateway                   $array_config[3]
// Zeile 5 >> MAC-Adresse               $array_config[4]

$datei = "/opt/innotune/settings/logitechmediaserver.txt"; // Name der Datei
$array_lms = file($datei); // Datei in ein Array einlesen
// Zeile 1 >> checkbox LMS              $array_config[0]


// checkbox LMS
if ($array_lms[0]>="1") {
	$checkbox_lms = "checked=checked";
}


if (isset($_POST['LMS'])) {
    $FRG_LMS = "1";
   } else {
    $FRG_LMS = "0";
}

//LMS Save
if (isset($_POST['lms_save']))
    {
     $array = file("/opt/innotune/settings/logitechmediaserver.txt"); // Datei in ein Array einlesen
     array_splice($array, 0, 1, "$FRG_LMS"."\n");
     $string = implode("", $array);
     file_put_contents("/opt/innotune/settings/logitechmediaserver.txt", $string);
     header("Location: $namePage");
    }

//Reboot
if (isset($_POST['reboot']))
    {
      exec("sudo /var/www/sudoscript.sh reboot",$output,$return_var);
    }

//LMS Stop
if (isset($_POST['lms_stop']))
    {
      exec("sudo /var/www/sudoscript.sh stop_lms",$output,$return_var);
    }

//LMS Stop
if (isset($_POST['lms_start']))
    {
      exec("sudo /var/www/sudoscript.sh start_lms",$output,$return_var);
    }


include "head.php";
include "navigation.php";

print <<< EOT


<td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Home</h3>     
               <br>         
              <table align="center">
                </tr>
                <tr>
                <td><h5>Steuerung InnoServer</h5></td>
                </tr>
                <tr>
              </table>
                <br>
              <table align="center">
                </tr>
                <tr>
                <td><form method="post" onsubmit="return confirm('Sind Sie sicher? System wird neu gestartet!')"><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="reboot">Reboot</button></td></form> 
                </tr>
                <tr>
              </table>
                <br>
                <br>
                <br>
              <table align="center">
                </tr>
                <tr>
                <td><h5>Steuerung Logitech Media Server</h5></td> 
                </tr>
                <tr>
              </table>
                <br> 
              <table align="center">
                </tr>
                <tr>     
                <td><form method="post"><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="lms_stop">Stop LMS</button></td></form> 
                <td><form method="post"><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="lms_start">Start LMS</button></td></form>
                <td>SystemLink Logitech Media Server: <a href="http://$array_config[1]:9000" target="_blank">$array_config[1]:9000</a></td>
                </tr>
                <tr>
             </table>  
                <br>
             <table align="center">
                </tr>
                <tr>
                <td>LMS bei Systemstart:</td><form method="post">
                <td align="center"><input name="LMS" type="checkbox" $checkbox_lms value="LMS" /></td>
                <td><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="lms_save">Speichern</button></td></form>
                </tr>
                <tr>
              </table>        
               <br>
               <br> 
               <br>
               <br>  

EOT;
include "footer.php";
?>