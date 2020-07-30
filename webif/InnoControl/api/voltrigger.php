<?php
/*******************************************************************************
 *                                  INFO
 *
 * Filename :    voltrigger.php
 * Directory:    /var/www/InnoControl/api/
 * Created  :    20.04.2018
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Alexander Elmecker
 *
 *                              DESCRIPTION
 *
 *  This script manages the vol trigger state and start/stops the voltrigger.sh
 *  script accordingly.
 *
 *                              URL-PARAMETER
 *  mode: action to execute (trigger, stop, reset)
 *  name: name of the lms player
 *  mac : mac of the lms player
 *
 ******************************************************************************/

if($_GET['mode'] == 'trigger') {
    //starts the voltrigger (either up or down, is always the inversed save state)
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(str_split('\\/:*?"<>|[]() \''), "_", $_GET['name']) . ".txt", "r");
    if ($file && (($line = fgets($file)) !== false)) {
        echo $line;
        if(trim($line) == 'u') {
            $status = 'd';
        } else {
            $status = 'u';
        }
        fclose($file);
    } else {
        $status = 'u';
    }
    echo $status;

    $pid = exec("./voltrigger.sh " . $_GET['mac'] . " " . $status . " > /dev/null 2>&1 & echo $!");
    echo $pid;
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(str_split('\\/:*?"<>|[]() \''), "_", $_GET['name']) . ".txt", "w");
    fwrite($file, $status . "\n" . $pid);
    fclose($file);
} else if ($_GET['mode'] == 'stop') {
    //stops the voltrigger process
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(str_split('\\/:*?"<>|[]() \''), "_", $_GET['name']) . ".txt", "r");
    $i = 0;
    while ($file && (($line = fgets($file)) !== false) && $i < 2) {
        if($i == 1) {
            $pid = $line;
        }
        $i = $i + 1;
    }
    fclose($file);
    if(isset($pid)) {
        exec("kill " . $pid);
    }
} else if ($_GET['mode'] == 'reset') {
    //stops the voltrigger and resets the state (next time it will go up again)
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(str_split('\\/:*?"<>|[]() \''), "_", $_GET['name']) . ".txt", "r");
    $i = 0;
    while ($file && (($line = fgets($file)) !== false) && $i < 2) {
        if($i == 1) {
            $pid = $line;
        }
        $i = $i + 1;
    }
    fclose($file);
    if(isset($pid)) {
        exec("kill " . $pid);
    }

    //reset flag
    $file = fopen("/opt/innotune/settings/voltriggerstate_" . str_replace(str_split('\\/:*?"<>|[]() \''), "_", $_GET['name']) . ".txt", "w");
    fwrite($file, "d\n");
    fclose($file);
}
?>
