<?php


include "head.php";
include "navigation.php";

  print <<< EOT

     <td class="mainbg" valign="top"><div id="maintext"><h1>Einstellungen Allgemein</h1>
      <h3>Dokumentation</h3>
        <form method="get">
         <h5>Download Loxone Vorlagen</h5>
<br>
<br>
               <table align="center">       
                <td><p>Loxone Musterprojekt (V5.66):</p></td>
                <td><a href="/download/download.php?DEV=01">Download</a></td>                   
                </tr>
                <tr>
                <td><p>Virtuelle Ausgänge:</p></td>
                <td><a href="/download/download.php?DEV=02">Download</a></td>                   
                </tr>
                <tr>
              </table>  
             </form>


 
<br>
<br>

</body>
</html>
 


EOT;
include "footer.php";
?>
