<?php
/*******************************************************************************
 *                                  INFO
 *
 * Filename :    tts.php
 * Directory:    /var/www/InnoControl/api/
 * Created  :    24.07.2017 (initial git commit)
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Severin Elmecker
 *               Alexander Elmecker
 *               Julian Hoerbst
 *
 *                              DESCRIPTION
 *
 *  This script prepares the output volumes for tts, prepares the text and
 *  sends it to the voicerss api to receive the audio file and plays it
 *  with the mpd.
 *
 *                              URL-PARAMETER
 *  noqueue : starts playing tts immediatly, not waiting for others to finish
 *  vol_back: background volume
 *  vol_all : volume for all amps (that aren't specified)
 *  vol_0X  : volume for a certain amp (X = amp number)
 *  mac_0X  : mac address of a certain zone
 *  gain    : gain for voicerss
 *  lang    : language for voicerss (de_de, en_en, etc.)
 *  speed   : voice speed for voicerss
 *  time    : indicator to create a text with the current time
 *  text    : text to play
 *
 ******************************************************************************/

$mt = microtime(true);
if (isset($_GET["noqueue"])) {
    $current = file_get_contents("/opt/innotune/settings/voiceoutput/current_tts.txt");
    if ($current == "1") {
        $process = shell_exec("ps cax | grep ttsvolplay | cut -d ' ' -f 1");
        echo "running tts processes: $process<br>";
        if (!empty($process)) {
            echo exec("sudo /var/www/sudoscript.sh killtts", $out, $returnval);
            echo "Killall return value: $returnval<br>";
        }
        echo "<br>";
    }
    file_put_contents("/opt/innotune/settings/voiceoutput/current_tts.txt", "1");
}

for ($i = 1; $i < 10; $i++) {
    if ($_GET["vol_0$i"] == "squeeze") {
        $zonemastervol = explode(";", exec("sudo /var/www/sudoscript.sh show_vol_equal 0" . $i . " all"));
        if (isset($_GET["mac_0$i"])) {
            $currmac = $_GET["mac_0$i"];
            $squeezevol = intval(exec("echo $(printf \"$currmac mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        } else {
            $squeezevol = intval(exec("echo $(printf \"00:00:00:00:00:0$i mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        }
        $val = ceil((intval($zonemastervol[1])) * ($squeezevol) / 60);
        if ($val < 0) {
            $val = 0;
        } else if ($val > 60) {
            $val = 60;
        }

        $_GET["vol_0$i"] = $val;
    } else if (strpos($_GET["vol_0$i"], "squeeze/") !== false) {
        $zonemastervol = explode(";", exec("sudo /var/www/sudoscript.sh show_vol_equal 0" . $i . " all"));
        if (isset($_GET["mac_0$i"])) {
            $currmac = $_GET["mac_0$i"];
            $squeezevol = intval(exec("echo $(printf \"$currmac mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        } else {
            $squeezevol = intval(exec("echo $(printf \"00:00:00:00:00:0$i mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        }

        if ($squeezevol <= 90 && $squeezevol > 0) {
            $squeezevol = $squeezevol + 10;
        }

        $vall = ceil(intval($zonemastervol[1]) * ($squeezevol / 60));
        if ($vall < 50) {
            $vall = 50;
        } else if ($vall > 80) {
            $vall = 80;
        }

        $valr = explode("/", $_GET["vol_0$i"])[1];
        $_GET["vol_0$i"] = "$vall/$valr";
    } else if (strpos($_GET["vol_0$i"], "/squeeze") !== false) {
        $zonemastervol = explode(";", exec("sudo /var/www/sudoscript.sh show_vol_equal 0" . $i . " all"));
        if (isset($_GET["mac_0$i"])) {
            $currmac = $_GET["mac_0$i"];
            $squeezevol = intval(exec("echo $(printf \"$currmac mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        } else {
            $squeezevol = intval(exec("echo $(printf \"00:00:00:00:00:0$i mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
        }

        if ($squeezevol <= 90 && $squeezevol > 0) {
            $squeezevol = $squeezevol + 10;
        }

        $valr = ceil(intval($zonemastervol[1]) * ($squeezevol / 60));
        if ($valr < 50) {
          $valr = 50;
        } else if ($valr > 80) {
            $valr = 80;
        }

        $vall = explode("/", $_GET["vol_0$i"])[0];
        $_GET["vol_0$i"] = "$vall/$valr";
    }
}
if ($_GET["vol_10"] == "squeeze") {
    $zonemastervol = explode(";", exec("sudo /var/www/sudoscript.sh show_vol_equal 10 all"));
    if (isset($_GET["mac_10"])) {
        $currmac = $_GET["mac_10"];
        $squeezevol = intval(exec("echo $(printf \"$currmac mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
    } else {
        $squeezevol = intval(exec("echo $(printf \"00:00:00:00:00:10 mixer volume ?\nexit\n\" | nc localhost 9090 | cut -d ' ' -f 4)"));
    }

    if ($squeezevol <= 90 && $squeezevol > 0) {
        $squeezevol = $squeezevol + 10;
    }

    $val = ceil(intval($zonemastervol[1]) * ($squeezevol / 60));
    if ($val < 50) {
        $val = 50;
    } else if ($val > 80) {
        $val = 80;
    }
    $_GET["vol_10"] = $val;
}

// declare variables
$VOL_BACK = trim($_GET["vol_back"]);
$VOL_ALL = trim($_GET["vol_all"]);
$VOL_01 = trim($_GET["vol_01"]);
$VOL_02 = trim($_GET["vol_02"]);
$VOL_03 = trim($_GET["vol_03"]);
$VOL_04 = trim($_GET["vol_04"]);
$VOL_05 = trim($_GET["vol_05"]);
$VOL_06 = trim($_GET["vol_06"]);
$VOL_07 = trim($_GET["vol_07"]);
$VOL_08 = trim($_GET["vol_08"]);
$VOL_09 = trim($_GET["vol_09"]);
$VOL_10 = trim($_GET["vol_10"]);

if ($VOL_ALL != "") {
    if ($VOL_01 == "") {
        $VOL_01 = trim($_GET["vol_all"]);
    }
    if ($VOL_02 == "") {
        $VOL_02 = trim($_GET["vol_all"]);
    }
    if ($VOL_03 == "") {
        $VOL_03 = trim($_GET["vol_all"]);
    }
    if ($VOL_04 == "") {
        $VOL_04 = trim($_GET["vol_all"]);
    }
    if ($VOL_05 == "") {
        $VOL_05 = trim($_GET["vol_all"]);
    }
    if ($VOL_06 == "") {
        $VOL_06 = trim($_GET["vol_all"]);
    }
    if ($VOL_07 == "") {
        $VOL_07 = trim($_GET["vol_all"]);
    }
    if ($VOL_08 == "") {
        $VOL_08 = trim($_GET["vol_all"]);
    }
    if ($VOL_09 == "") {
        $VOL_09 = trim($_GET["vol_all"]);
    }
    if ($VOL_10 == "") {
        $VOL_10 = trim($_GET["vol_all"]);
    }
}

// read volumes from file into an array
$datei = "/opt/innotune/settings/voiceoutput/voiceoutputvol.txt";
$array_vol = file($datei);
$array_vol = array_map('trim', $array_vol);

// row 1   >> vol background              $array_vol[0]
// row 2   >> vol output 01               $array_vol[1]
// etc.

$array = file("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt");
if ($VOL_BACK != "" && $VOL_BACK != $array_vol[0]) {
    array_splice($array, 0, 1, "$VOL_BACK" . "\n");
    echo "Derzeit Hintergrundlautstärke: <b>" . $array_vol[0] . "</b><br>";
    echo "Schreibe Hintergrundlautstärke: <b>" . $VOL_BACK . "</b><br>";
}
if ($VOL_01 != "" && $VOL_01 != $array_vol[1]) {
    array_splice($array, 1, 1, "$VOL_01" . "\n");
    echo "Derzeit InnoAMP01 Lautstärke : <b>" . $array_vol[1] . "</b><br>";
    echo "Schreibe InnoAMP01 Lautstärke: <b>" . $VOL_01 . "</b><br>";
}
if ($VOL_02 != "" && $VOL_02 != $array_vol[2]) {
    array_splice($array, 2, 1, "$VOL_02" . "\n");
    echo "Derzeit InnoAMP02 Lautstärke : <b>" . $array_vol[2] . "</b><br>";
    echo "Schreibe InnoAMP02 Lautstärke: <b>" . $VOL_02 . "</b><br>";
}
if ($VOL_03 != "" && $VOL_03 != $array_vol[3]) {
    array_splice($array, 3, 1, "$VOL_03" . "\n");
    echo "Derzeit InnoAMP03 Lautstärke : <b>" . $array_vol[3] . "</b><br>";
    echo "Schreibe InnoAMP03 Lautstärke: <b>" . $VOL_03 . "</b><br>";
}
if ($VOL_04 != "" && $VOL_04 != $array_vol[4]) {
    array_splice($array, 4, 1, "$VOL_04" . "\n");
    echo "Derzeit InnoAMP04 Lautstärke : <b>" . $array_vol[4] . "</b><br>";
    echo "Schreibe InnoAMP04 Lautstärke: <b>" . $VOL_04 . "</b><br>";
}
if ($VOL_05 != "" && $VOL_05 != $array_vol[5]) {
    array_splice($array, 5, 1, "$VOL_05" . "\n");
    echo "Derzeit InnoAMP05 Lautstärke : <b>" . $array_vol[5] . "</b><br>";
    echo "Schreibe InnoAMP05 Lautstärke: <b>" . $VOL_05 . "</b><br>";
}
if ($VOL_06 != "" && $VOL_06 != $array_vol[6]) {
    array_splice($array, 6, 1, "$VOL_06" . "\n");
    echo "Derzeit InnoAMP06 Lautstärke : <b>" . $array_vol[6] . "</b><br>";
    echo "Schreibe InnoAMP06 Lautstärke: <b>" . $VOL_06 . "</b><br>";
}
if ($VOL_07 != "" && $VOL_07 != $array_vol[7]) {
    array_splice($array, 7, 1, "$VOL_07" . "\n");
    echo "Derzeit InnoAMP07 Lautstärke : <b>" . $array_vol[7] . "</b><br>";
    echo "Schreibe InnoAMP07 Lautstärke: <b>" . $VOL_07 . "</b><br>";
}
if ($VOL_08 != "" && $VOL_08 != $array_vol[8]) {
    array_splice($array, 8, 1, "$VOL_08" . "\n");
    echo "Derzeit InnoAMP08 Lautstärke : <b>" . $array_vol[8] . "</b><br>";
    echo "Schreibe InnoAMP08 Lautstärke: <b>" . $VOL_08 . "</b><br>";
}
if ($VOL_09 != "" && $VOL_09 != $array_vol[9]) {
    array_splice($array, 9, 1, "$VOL_09" . "\n");
    echo "Derzeit InnoAMP09 Lautstärke : <b>" . $array_vol[9] . "</b><br>";
    echo "Schreibe InnoAMP09 Lautstärke: <b>" . $VOL_09 . "</b><br>";
}
if ($VOL_10 != "" && $VOL_10 != $array_vol[10]) {
    array_splice($array, 10, 1, "$VOL_AMP10" . "\n");
    echo "Derzeit InnoAMP10 Lautstärke : <b>" . $array_vol[10] . "</b><br>";
    echo "Schreibe InnoAMP10 Lautstärke: <b>" . $VOL_10 . "</b><br>";
}
$string = implode("", $array);
file_put_contents("/opt/innotune/settings/voiceoutput/voiceoutputvol.txt", $string);

//read rss key
$datei = "/opt/innotune/settings/voiceoutput/voicersskey.txt";
$array_config = file($datei);

//rss api settings
$key = $array_config[0];
$q = "44khz_16bit_stereo";
$c = "mp3";

$gain = ($_GET["gain"]);
$lang = ($_GET["lang"]);

if ($gain == "") {
    $gain = "100";
}

if ($lang == "") {
    $lang = "de-de";
}

$speed = strval(($_GET["speed"]));
if ($speed == "" || strpos($speed, '-') === false) {
    $speed = "0";
}

// check if process is running
$pids = shell_exec("ps aux | grep -i 'mpg321' | grep -v grep");

if (empty($pids)) {
    //play current time
    $TIME = ($_GET["time"]);

    //prepare text
    $words = rawurldecode($_GET['text']);
    if ($TIME != "") {
        $Stunde = date("H");
        $Minute = number_format(date("i"));

        if ($Minute == 0) {
            $words = "Es ist" . $Stunde . "Uhr.";
        } elseif ($Minute == 1) {
            $words = "Es ist" . $Stunde . "Uhr und eine Minute.";
        } else {
            $words = "Es ist" . $Stunde . "Uhr und" . $Minute . "Minuten.";
        }
    }
    echo "<br>Text to Speech Input: <b>" . $words . "</b><br>";

    //convert special chars
    $umlaute = Array("/ä/", "/ö/", "/ü/", "/Ä/", "/Ö/", "/Ü/", "/ß/");
    $replace = Array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
    $words_neu = preg_replace($umlaute, $replace, $words);

    //set rss parameters
    $encodedwords = urlencode($words);
    $inlay = "key=$key&hl=$lang&src=$encodedwords&f=$q&r=$speed&c=$c";
    echo "Parameter VoiceRSS: <b>" . $inlay . "</b><br>";

    // location of mp3 file
    $file = "/media/Soundfiles/tts/" . strtoupper($lang) . str_replace(" ", "_", $words_neu) . $speed . ".mp3";
    $file = str_replace("ä", "ae", $file);

    $error = false;
    // check if mp3 already exists
    if (!file_exists($file)) {
        $mp3 = file_get_contents('http://api.voicerss.org/?' . $inlay);
        if (strlen($mp3) < 100) {
            echo "suspicious string length: " . strlen($mp3) . "<br>";
            echo $mp3 . "<br>";
            $error = true;
            file_put_contents("/media/Soundfiles/tts/error.txt",
                "Timestamp: " . date("H:i:s d.m.Y") . "\nMessage: " . $mp3 . "\nParameter: " . $inlay);
        } else {
            file_put_contents($file, $mp3);
        }
    } else {
        if (filesize($file) < 100) {
            echo "suspicious filesize: " . filesize($file) . " Bytes <br>";
            $mp3 = file_get_contents('http://api.voicerss.org/?' . $inlay);
            if (strlen($mp3) < 100) {
                echo "suspicious string length: " . strlen($mp3) . "<br>";
                echo $mp3 . "<br>";
                $error = true;
                file_put_contents("/media/Soundfiles/tts/error.txt",
                    "Timestamp: " . date("H:i:s d.m.Y") . "\nMessage: " . $mp3 . "\nParameter: " . $inlay);
            } else {
                file_put_contents($file, $mp3);
            }
        }
    }

    if (!$error) {
        echo "Script Aufruf: /var/www/src/ttsvolplay " . strtoupper($lang) . str_replace(" ", "_", $words_neu) . $speed;
        echo "<br>Speicherort: " . $file;

        //update mpd library
        exec("mpc update");

        //execute ttsvolplay
        // check if request should be added to queue
        if (isset($_GET["noqueue"])) {
            shell_exec("sudo /var/www/sudoscript.sh ttsvolplay " . strtoupper($lang) . str_replace(" ", "_", $words_neu) . $speed . " > /dev/null 2>/dev/null &");
        } else {
            shell_exec("sudo /var/www/sudoscript.sh ttsvolplay " . strtoupper($lang) . str_replace(" ", "_", $words_neu) . $speed);
        }
    }
} else {
    echo "Fehler!";
}

$mt = microtime(true) - $mt;
echo "<br><br>execution time: $mt s"
?>
