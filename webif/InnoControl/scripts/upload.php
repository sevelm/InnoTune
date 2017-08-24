<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 20.09.2016
 * Time: 20:57
 */
if (isset($_POST['settings_upload'])) {
    $uploaddir = '/var/www/upload_download/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        exec("sudo /var/www/sudoscript.sh restore_settings");
        $CONTROL_INPUT="erfolgreich";
    } else {
        $CONTROL_INPUT="fehlgeschlagen!";
    }
    header('Location: http://'.$_SERVER['SERVER_ADDR'].'/#docs?result='.$CONTROL_INPUT);
}

if (isset($_POST['music_upload'])){
    $uploaddir = '/media/Soundfiles/uploads/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        $CONTROL_INPUT="erfolgreich";
    } else {
        $CONTROL_INPUT="fehlgeschlagen!";
        echo $uploadfile;
    }
    header('Location: http://'.$_SERVER['SERVER_ADDR'].'/#mpd?result='.$CONTROL_INPUT);
}
echo $CONTROL_INPUT;
?>