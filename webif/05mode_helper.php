<?php

exec("sudo /var/www/sudoscript.sh create_asound",$output,$return_var);

//Reboot
if (isset($_POST['reboot']))
    {
      exec("sudo /var/www/sudoscript.sh reboot",$output,$return_var);
    }


include "head.php";
include "navigation.php";

  print <<< EOT


<td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Audio Konfiguration erzeugt</h3>
            

   <h5>Aktion Erfolgreich!</h5>
   <h6>System muss neu gestartet werden!</h6>
          <br>
          <br>
 <table align="center">
                </tr>
                <tr>
                <td><form method="post" onsubmit="return confirm('Sind Sie sicher? System wird neu gestartet!')"><button style="width: 100px;display: block;margin-right: auto;margin-left: auto" name="reboot">Reboot</button></td></form> 
                </tr>
                <tr>
              </table>



EOT;

include "footer.php";
?>