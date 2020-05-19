<?php
/*
                                    MPD-Vol
    ----------------------------------------------------------------------------

    Interface to Play, Stop, Repeat or Set MPD Values

    ----------------------------------------------------------------------------

    Parameterlist:

    Playlist ID:        id
    Playlist Title:     title
    Background Volume:  vb
    Zone Volume:        vx      (x = 01-10) Standard or Left Channel
    Left/Right Channel: lrx     (x = 01-10) Indicates that Channels are split
    Zone Volume Right:  vrx     (x = 01-10) Right Channel
    Play:               play
    Stop:               stop
    Repeat:             repeat
    Debug:              debug (shows infos, warnings and errors)
    ----------------------------------------------------------------------------
*/

$mt = microtime(true);
$DEBUG_STRING = "";
// if param stop is set, stop mpd
if (isset($_GET["stop"])) {
    exec("sudo /var/www/sudoscript.sh mpdstop", $output, $return_var);
    $DEBUG_STRING = $DEBUG_STRING . "INFO: mpd stop<br>";
}

// if param repeat is set, set mpd to repeat
if (isset($_GET["repeat"])) {
    exec("sudo /var/www/sudoscript.sh mpdrepeat", $output, $return_var);
    $DEBUG_STRING = $DEBUG_STRING . "INFO: mpd repeat<br>";
}

// if playlist id is set, set values for playlist
if (isset($_GET["id"])) {
    $PLAYLIST_ID = trim($_GET["id"]);

    //if id is numeric and is greater than 0, then continue with writing values
    if (is_numeric($PLAYLIST_ID)) {
        $ID = intval($PLAYLIST_ID);
        if ($ID > 0) {
            $DEBUG_STRING = $DEBUG_STRING . "INFO: Playlist ID: $ID<br>";
            //get starting point to write in file
            $WRITE_NR = ($PLAYLIST_ID * 12) - 11;
            //read file data in array
            $array = file("/opt/innotune/settings/mpdvolplay.txt");

            //set playlist title if param is set
            if (isset($_GET["title"]) && (trim($_GET["title"]) !== '')) {
                $PLAYL_TITLE = trim($_GET["title"]);
                //array_splice($array, $WRITE_NR, 1, "$PLAYL_TITLE\n");
                exec("sed -i '" . "$WRITE_NR" . "s|.*|" . "$PLAYL_TITLE" . "|' /opt/innotune/settings/mpdvolplay.txt");
                $DEBUG_STRING = $DEBUG_STRING . "INFO: Playlist Titel: $PLAYL_TITLE<br>";
            } else {
                $DEBUG_STRING = $DEBUG_STRING . "ERROR: Playlist Titel is nicht gesetzt bzw. leer<br>";
            }

            //set background volume
            if (isset($_GET["vb"]) && is_numeric(trim($_GET["vb"]))) {
                $VOL_SQAIR = intval(trim($_GET["vb"]));
                if ($VOL_SQAIR >= 0 && $VOL_SQAIR <= 100) {
                    //array_splice($array, $WRITE_NR + 1, 1, "$VOL_SQAIR\n");
                    $LINE = $WRITE_NR + 1;
                    exec("sed -i '" . "$LINE" . "s|.*|" . "$VOL_SQAIR" . "|' /opt/innotune/settings/mpdvolplay.txt");
                    $DEBUG_STRING = $DEBUG_STRING . "INFO: Hintergrundlautstärke: $VOL_SQAIR%<br>";
                } else {
                    $DEBUG_STRING = $DEBUG_STRING . "ERROR: Hintergrundlautstärke nicht im Bereich von 0 bis 100.<br>";
                }
            } else {
                $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Hintergrundlautstärke nicht gesetzt oder nicht numerisch.<br>";
            }

            //set zone volume for 1-9
            for($x = 1; $x < 10; $x++) {
                if (isset($_GET["lr0$x"])) {
                    $DEBUG_STRING = $DEBUG_STRING . "INFO: Geteilter Betrieb Zone 0$x<br>";

                    if (isset($_GET["v0$x"]) && is_numeric(trim($_GET["v0$x"]))) {
                        $VOL = intval(trim($_GET["v0$x"]));
                        if ($VOL >= 0 && $VOL <= 100) {
                            $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 0$x (Links): $VOL%<br>";
                        } else {
                            $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 0$x (Links) nicht im Bereich von 0 bis 100.<br>";
                            $data = explode("/", $array[($WRITE_NR + $x)]);
                            if (is_numeric(trim($data[0]))) {
                                $VOL = intval(trim($data[0]));
                            } else {
                                $VOL = 0;
                            }
                        }
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 0$x (Links) nicht gesetzt oder nicht numerisch.<br>";
                        $data = explode("/", $array[($WRITE_NR + $x)]);
                        if (is_numeric(trim($data[0]))) {
                            $VOL = intval(trim($data[0]));
                        } else {
                            $VOL = 0;
                        }
                    }


                    if (isset($_GET["vr0$x"]) && is_numeric(trim($_GET["vr0$x"]))) {
                        $VOL_R = intval(trim($_GET["vr0$x"]));
                        if ($VOL_R >= 0 && $VOL_R <= 100) {
                            $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 0$x (Rechts): $VOL_R%<br>";
                        } else {
                            $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 0$x (Rechts) nicht im Bereich von 0 bis 100.<br>";
                            $data = explode("/", $array[($WRITE_NR + $x)]);
                            if (is_numeric(trim($data[1]))) {
                                $VOL_R = intval(trim($data[1]));
                            } else {
                                $VOL_R = 0;
                            }
                        }
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 0$x (Rechts) nicht gesetzt oder nicht numerisch.<br>";
                        $data = explode("/", $array[($WRITE_NR + $x)]);
                        if (is_numeric(trim($data[1]))) {
                            $VOL_R = intval(trim($data[1]));
                        } else {
                            $VOL_R = 0;
                        }
                    }
                    $DEBUG_STRING = $DEBUG_STRING . "INFO: Lautstärke: $VOL/$VOL_R<br>";
                    //array_splice($array, $WRITE_NR + (1 + $x), 1, "$VOL/$VOL_R\n");
                    $LINE = $WRITE_NR + (1 + $x);
                    exec("sed -i '" . "$LINE" . "s|.*|" . "$VOL/$VOL_R" . "|' /opt/innotune/settings/mpdvolplay.txt");
                } else {
                    $DEBUG_STRING = $DEBUG_STRING . "INFO: Stereo Betrieb Zone 0$x<br>";

                    if (isset($_GET["v0$x"]) && is_numeric(trim($_GET["v0$x"]))) {
                        $VOL = intval(trim($_GET["v0$x"]));
                        if ($VOL >= 0 && $VOL <= 100) {
                            //array_splice($array, $WRITE_NR + (1 + $x), 1, "$VOL\n");
                            $LINE = $WRITE_NR + (1 + $x);
                            exec("sed -i '" . "$LINE" . "s|.*|" . "$VOL" . "|' /opt/innotune/settings/mpdvolplay.txt");
                            $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 0$x: $VOL%<br>";
                        } else {
                            $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 0$x nicht im Bereich von 0 bis 100.<br>";
                        }
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 0$x nicht gesetzt oder nicht numerisch.<br>";
                    }
                }
            }

            //set zone volume for 10
            if (isset($_GET["lr10"])) {
                $DEBUG_STRING = $DEBUG_STRING . "INFO: Geteilter Betrieb Zone 10<br>";
                if (isset($_GET["v10"]) && is_numeric(trim($_GET["v10"]))) {
                    $VOL = intval(trim($_GET["v10"]));
                    if ($VOL >= 0 && $VOL <= 100) {
                        $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 10 (Links): $VOL%<br>";
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 10 (Links) nicht im Bereich von 0 bis 100.<br>";
                        $data = explode("/", $array[($WRITE_NR + 11)]);
                        if (is_numeric(trim($data[0]))) {
                            $VOL = intval(trim($data[0]));
                        } else {
                            $VOL = 0;
                        }
                    }
                } else {
                    $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 10 (Links) nicht gesetzt oder nicht numerisch.<br>";
                    $data = explode("/", $array[($WRITE_NR + 11)]);
                    if (is_numeric(trim($data[0]))) {
                        $VOL = intval(trim($data[0]));
                    } else {
                        $VOL = 0;
                    }
                }

                if (isset($_GET["vr10"]) && is_numeric(trim($_GET["vr10"]))) {
                    $VOL_R = intval(trim($_GET["vr10"]));
                    if ($VOL_R >= 0 && $VOL_R <= 100) {
                        $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 10 (Rechts): $VOL_R%<br>";
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 10 (Rechts) nicht im Bereich von 0 bis 100.<br>";
                        $data = explode("/", $array[($WRITE_NR + 11)]);
                        if (is_numeric(trim($data[1]))) {
                            $VOL_R = intval(trim($data[1]));
                        } else {
                            $VOL_R = 0;
                        }
                    }
                } else {
                    $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 10 (Rechts) nicht gesetzt oder nicht numerisch.<br>";
                    $data = explode("/", $array[($WRITE_NR + (1 + $x))]);
                    if (is_numeric(trim($data[1]))) {
                        $VOL_R = intval(trim($data[1]));
                    } else {
                        $VOL_R = 0;
                    }
                }

                $DEBUG_STRING = $DEBUG_STRING . "INFO: Lautstärke: $VOL/$VOL_R<br>";
                //array_splice($array, $WRITE_NR + 11, 1, "$VOL/$VOL_R\n");
                $LINE = $WRITE_NR + 11;
                exec("sed -i '" . "$LINE" . "s|.*|" . "$VOL/$VOL_R" . "|' /opt/innotune/settings/mpdvolplay.txt");
            } else {
                $DEBUG_STRING = $DEBUG_STRING . "INFO: Stereo Betrieb Zone 10<br>";
                if (isset($_GET["v10"]) && is_numeric(trim($_GET["v10"]))) {
                    $VOL = intval(trim($_GET["v10"]));
                    if ($VOL >= 0 && $VOL <= 100) {
                        //array_splice($array, $WRITE_NR + 11, 1, "$VOL\n");

                        $LINE = $WRITE_NR + 11;
                        exec("sed -i '" . "$LINE" . "s|.*|" . "$VOL" . "|' /opt/innotune/settings/mpdvolplay.txt");
                        $DEBUG_STRING = $DEBUG_STRING . "INFO: Zonenlautstärke 10: $VOL%<br>";
                    } else {
                        $DEBUG_STRING = $DEBUG_STRING . "ERROR: Zonenlautstärke 10 nicht im Bereich von 0 bis 100.<br>";
                    }
                } else {
                    $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Zonenlautstärke 10 nicht gesetzt oder nicht numerisch.<br>";
                }
            }

            //write to file
            $string = implode("", $array);
            //sed -i '15s|.*|20|' /opt/innotune/settings/mpdvolplay.txt
            //file_put_contents("/opt/innotune/settings/mpdvolplay.txt", $string);

            //if param play is set, play mpd
            if (isset($_GET["play"])) {
                exec("sudo /var/www/sudoscript.sh mpdvolplay $PLAYLIST_ID", $output, $return_var);
                $DEBUG_STRING = $DEBUG_STRING . "INFO: mpd play<br>";
            }

            //persists file changes to disk
            exec("sudo sync /opt/innotune/settings/mpdvolplay.txt");
        } else {
            $DEBUG_STRING = $DEBUG_STRING . "ERROR: Playlist ID ist kleiner als 1.<br>";
        }
    } else {
        $DEBUG_STRING = $DEBUG_STRING . "ERROR: Playlist ID ist nicht numerisch.<br>";
    }
} else {
    $DEBUG_STRING = $DEBUG_STRING . "WARNUNG: Playlist ID ist nicht gesetzt.<br>";
}

if (isset($_GET["debug"])) {
    $mt = microtime(true) - $mt;
    echo $DEBUG_STRING . "<br><br> Excecution Time: " . $mt . " s";
}
?>
