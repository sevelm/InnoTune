<?php
// Variablen definieren

$VOL_BACK = trim  ($_GET["vol_back"]);
$VOL_AMPALL = trim ($_GET["vol_ampall"]);
$VOL_AMP01 = trim ($_GET["vol_amp01"]);
$VOL_AMP02 = trim ($_GET["vol_amp02"]);
$VOL_AMP03 = trim ($_GET["vol_amp03"]);
$VOL_AMP04 = trim ($_GET["vol_amp04"]);
$VOL_AMP05 = trim ($_GET["vol_amp05"]);
$VOL_AMP06 = trim ($_GET["vol_amp06"]);
$VOL_AMP07 = trim ($_GET["vol_amp07"]);
$VOL_AMP08 = trim ($_GET["vol_amp08"]);
$VOL_AMP09 = trim ($_GET["vol_amp09"]);
$VOL_AMP10 = trim ($_GET["vol_amp10"]);

           if ($VOL_AMPALL != "" ) {
                         if ($VOL_AMP01 == "" ) {
                         $VOL_AMP01 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP02 == "" ) {
                         $VOL_AMP02 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP03 == "" ) {
                         $VOL_AMP03 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP04 == "" ) {
                         $VOL_AMP04 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP05 == "" ) {
                         $VOL_AMP05 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP06 == "" ) {
                         $VOL_AMP06 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP07 == "" ) {
                         $VOL_AMP07 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP08 == "" ) {
                         $VOL_AMP08 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP09 == "" ) {
                         $VOL_AMP09 = trim ($_GET["vol_ampall"]);
                         }
                         if ($VOL_AMP10 == "" ) {
                         $VOL_AMP10 = trim ($_GET["vol_ampall"]);
                         }
            }

// Volume einlesen
$datei = "/opt/innotune/settings/voiceoutput/voiceoutputvol.txt"; // Name der Datei
$array_vol = file($datei); // Datei in ein Array einlesen
$array_vol=array_map('trim',$array_vol); //Array Trimmen

// Zeile 1   >> Vol. Hintergrund         $array_vol[0]
// Zeile 2   >> Vol. Amp01               $array_vol[1]
// usw.

           $array = file("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt"); // Datei in ein Array einlesen
           if ($VOL_BACK != "" && $VOL_BACK != $array_vol[0]) {
           array_splice($array, 0, 1, "$VOL_BACK"."\n");
           echo "Derzeit Hintergrundlautstärke: <b>" . $array_vol[0] . "</b><br>";
           echo "Schreibe Hintergrundlautstärke: <b>" . $VOL_BACK . "</b><br>";
             }
           if ($VOL_AMP01 != "" && $VOL_AMP01 != $array_vol[1]){
           array_splice($array, 1, 1, "$VOL_AMP01"."\n");
           echo "Derzeit InnoAMP01 Lautstärke : <b>" . $array_vol[1] . "</b><br>";
           echo "Schreibe InnoAMP01 Lautstärke: <b>" . $VOL_AMP01 . "</b><br>";
             }
           if ($VOL_AMP02 != "" && $VOL_AMP02 != $array_vol[2]){
           array_splice($array, 2, 1, "$VOL_AMP02"."\n");
           echo "Derzeit InnoAMP02 Lautstärke : <b>" . $array_vol[2] . "</b><br>";
           echo "Schreibe InnoAMP02 Lautstärke: <b>" . $VOL_AMP02 . "</b><br>";
             }
           if ($VOL_AMP03 != "" && $VOL_AMP03 != $array_vol[3]){
           array_splice($array, 3, 1, "$VOL_AMP03"."\n");
           echo "Derzeit InnoAMP03 Lautstärke : <b>" . $array_vol[3] . "</b><br>";
           echo "Schreibe InnoAMP03 Lautstärke: <b>" . $VOL_AMP03 . "</b><br>";
             }
           if ($VOL_AMP04 != "" && $VOL_AMP04 != $array_vol[4]){
           array_splice($array, 4, 1, "$VOL_AMP04"."\n");
           echo "Derzeit InnoAMP04 Lautstärke : <b>" . $array_vol[4] . "</b><br>";
           echo "Schreibe InnoAMP04 Lautstärke: <b>" . $VOL_AMP04 . "</b><br>";
             }
           if ($VOL_AMP05 != "" && $VOL_AMP05 != $array_vol[5]){
           array_splice($array, 5, 1, "$VOL_AMP05"."\n");
           echo "Derzeit InnoAMP05 Lautstärke : <b>" . $array_vol[5] . "</b><br>";
           echo "Schreibe InnoAMP05 Lautstärke: <b>" . $VOL_AMP05 . "</b><br>";
             }
           if ($VOL_AMP06 != "" && $VOL_AMP06 != $array_vol[6]){
           array_splice($array, 6, 1, "$VOL_AMP06"."\n");
           echo "Derzeit InnoAMP06 Lautstärke : <b>" . $array_vol[6] . "</b><br>";
           echo "Schreibe InnoAMP06 Lautstärke: <b>" . $VOL_AMP06 . "</b><br>";
             }
           if ($VOL_AMP07 != "" && $VOL_AMP07 != $array_vol[7]){
           array_splice($array, 7, 1, "$VOL_AMP07"."\n");
           echo "Derzeit InnoAMP07 Lautstärke : <b>" . $array_vol[7] . "</b><br>";
           echo "Schreibe InnoAMP07 Lautstärke: <b>" . $VOL_AMP07 . "</b><br>";
             }
           if ($VOL_AMP08 != "" && $VOL_AMP08 != $array_vol[8]){
           array_splice($array, 8, 1, "$VOL_AMP08"."\n");
           echo "Derzeit InnoAMP08 Lautstärke : <b>" . $array_vol[8] . "</b><br>";
           echo "Schreibe InnoAMP08 Lautstärke: <b>" . $VOL_AMP08 . "</b><br>";
             }
           if ($VOL_AMP09 != "" && $VOL_AMP09 != $array_vol[9]){
           array_splice($array, 9, 1, "$VOL_AMP09"."\n");
           echo "Derzeit InnoAMP09 Lautstärke : <b>" . $array_vol[9] . "</b><br>";
           echo "Schreibe InnoAMP09 Lautstärke: <b>" . $VOL_AMP09 . "</b><br>";
             }
           if ($VOL_AMP10 != "" && $VOL_AMP10 != $array_vol[10]){
           array_splice($array, 10, 1, "$VOL_AMP10"."\n");
           echo "Derzeit InnoAMP10 Lautstärke : <b>" . $array_vol[10] . "</b><br>";
           echo "Schreibe InnoAMP10 Lautstärke: <b>" . $VOL_AMP10 . "</b><br>";
             }
           $string = implode("", $array);
           file_put_contents("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt", $string);




$datei = "/opt/innotune/settings/voiceoutput/voicersskey.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen

$key = $array_config[0];
$q = "22khz_16bit_stereo"; // Andere Einstellungen siehe VoiceRSS Doku

$gain = ($_GET["gain"]);
$lang = ($_GET["lang"]);

if ($gain == "") {
    $gain = "100";
}

if ($lang == "") {
    $lang = "de-de"; // Andere Einstellungen siehe VoiceRSS Doku (en-us / en-gb)
}

// Prüfen ob noch ein Prozess läuft
$pids = shell_exec("ps aux | grep -i 'mpg321' | grep -v grep");

if (empty($pids)) {
    // Text anpassen
    $words = rawurldecode($_GET['text']);
    echo "Text to Speech Input: <b>" . $words . "</b><br>";

    //Umlaute konvertieren
    $umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
    $replace = Array("ae","oe","ue","Ae","Oe","Ue","ss");
    $words_neu = preg_replace($umlaute, $replace, $words);

    // Parameter VoiceRSS
    $encodedwords = urlencode($words);
    $inlay = "key=$key&hl=$lang&src=$encodedwords&f=$q"; // Variablen Key, Sprache, Text und Qualität definieren
    echo "Parameter VoiceRSS: <b>" . $inlay . "</b><br>";

    // Speicherort der MP3 Datei
    $file = "/media/Soundfiles/tts/" . str_replace(" ", "_", $words_neu) . ".mp3";
    $file = str_replace("ä", "ae", $file);

    // Prüfen ob die MP3 Datei bereits vorhanden ist
    if (!file_exists($file)) {
        $mp3 = file_get_contents('http://api.voicerss.org/?' . $inlay); // HTTPS ist auch möglich
        file_put_contents($file, $mp3);
    }

    echo "Script Aufruf: /var/www/src/ttsvolplay " . str_replace(" ", "_", $words_neu);
    echo "<br>Speicherort: " . $file;

    //Update MPD Library
    shell_exec("mpc update");
    //Execute ttsvolplay
    shell_exec("sudo /var/www/sudoscript.sh ttsvolplay " . str_replace(" ", "_", $words_neu));
} else {
    echo "Fehler!";
}
?>

