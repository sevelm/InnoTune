<?php


$datei = "/opt/innotune/settings/sendmail_data.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen
// Zeile 1 >> SENDER                   $array_config[0]
// Zeile 2 >> RECIPIENT                $array_config[1]
// Zeile 3 >> SMTPSERVER               $array_config[2]
// Zeile 4 >> SMTPUSERNAME             $array_config[3]
// Zeile 5 >> SMTPPASSWORD             $array_config[4]
// Zeile 6 >> REGARD                   $array_config[5]

// Button Testmail
if (isset($_GET['Testmail']))
    {
    header("Location: 09mail_send.php?BETREFF=InnoTune Mailer&NACHRICHT=Tür öffnen Testnachricht Testnachricht Testnachricht");
    }


include "head.php";
include "navigation.php";

  print <<< EOT

      <td class="mainbg" valign="top"><div id="maintext"><h1>Allgemein</h1>
      <h3>E-mail</h3>
      <form name="mail_info" method="get" action="09mail_helper.php" onsubmit="return confirm('Sind Sie sicher?')">
             <table align="center">
                <td><p>Email Absender:</p></td>
                <td><input name="SENDER" type="text" value="$array_config[0]" size=22 /></td>
                <td><i>ip.io.watchdog@gmail.com</i></td>
                </tr>
                <tr>
                <td><p>Email Empfänger:</p></td>
                <td><input name="RECIPIENT" type="text" value="$array_config[1]" size=22 /></td>
                <td><i>????@gmail.com;????@yahoo.de</i></td>
                </tr>
                <tr>
                <td><p>SMTP Server:</p></td>
                <td><input name="SMTPSERVER" type="text" value="$array_config[2]" size=22 /></td>
                <td><i>smtp.gmail.com</i></td>
                </tr>
                <tr>
                <td><p>SMTP Benutzername:</p></td>
                <td><input name="SMTPUSERNAME" type="text" value="$array_config[3]" size=22 /></td>
                <td><i>ip.io.watchdog@gmail.com</i></td>
                </tr>
                <tr>
                <td><p>SMTP Passwort:</p></td>
                <td><input name="SMTPPASSWORD" type="text" value="$array_config[4]" size=22 /></td>
                <td><i>watchdog2015</i></td>
            </table>
    <br><br><br>
    <div style="margin:0 auto;width:50px">
        <button style="width:100px;margin:0 5px">Speichern</button>
    </div></form>
    <form method="get">
    <div style="margin:0 auto;width:50px">
        <button style="width:100px;margin:0 5px" name="Testmail">Testmail</button>
    </div></form>
    <br>  
      <h5>Beispiel Loxone Virtueller Ausgangsverbinder:</h5>
            <br>
           <table align="center">
             <td><p1 style="font-weight:bold;">Mail Senden:<p1></td>
             <td><p1>/09mail_send.php?BETREFF=Loxone&NACHRICHT=Terrassentuer%20offen!</p1></td>
           </table>
           <table align="center">
              <td>Encodieren des URL-Textes: <a href="http://url-encoder.de/" target="_blank">http://url-encoder.de/</a></td>
           </table>
            <br>
            <br>      
EOT;
include "footer.php";
?>