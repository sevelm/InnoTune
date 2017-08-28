<?php

// Variablen definieren
$datei = "/opt/innotune/settings/voiceoutput/voicersskey.txt"; // Name der Datei
$array_config = file($datei); // Datei in ein Array einlesen

$key = $array_config[0];
$q = "22khz_16bit_stereo"; // Andere Einstellungen siehe VoiceRSS Doku

$gain = ($_GET["gain"]);
$lang = ($_GET["lang"]);

if ($gain == "")
{
    $gain = "100";
}

if ($lang == "")
{
    $lang = "de-de"; // Andere Einstellungen siehe VoiceRSS Doku (en-us / en-gb)
}

// Prüfen ob noch ein Prozess läuft
$pids = shell_exec("ps aux | grep -i 'mpg321' | grep -v grep");

if(empty($pids))
{
    // Text anpassen
    $words = urlencode($_GET['text']);
    echo "Text to Speech Input: <b>" . $words . "</b><br>";

    // Parameter VoiceRSS
    $inlay = "key=$key&hl=$lang&src=$words&f=$q"; // Variablen Key, Sprache, Text und Qualität definieren
    echo "Parameter VoiceRSS: <b>" . $inlay . "</b><br>";

    // Speicherort der MP3 Datei
    $file = "/media/Soundfiles/tts/" . str_replace("+","_",$words) . ".mp3";

    // Prüfen ob die MP3 Datei bereits vorhanden ist
    if (!file_exists($file))
    {
        $mp3 = file_get_contents('http://api.voicerss.org/?' . $inlay); // HTTPS ist auch möglich
        file_put_contents($file, $mp3);
    }

    echo "/var/www/src/ttsvolplay " . str_replace("+","_",$words);

    //Update MPD Library
    shell_exec("mpc update");
    //Execute ttsvolplay
    shell_exec("sudo /var/www/sudoscript.sh ttsvolplay " . str_replace("+","_",$words));
}
else
{
    echo "Fehler!";
}
?>