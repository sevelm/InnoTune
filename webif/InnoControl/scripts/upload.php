<?php
/*******************************************************************************
 *                                  INFO
 *
 * Filename :    upload.php
 * Directory:    /var/www/InnoControl/scripts/
 * Created  :    24.07.2017 (initial git commit)
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Alexander Elmecker
 *               Julian Hoerbst
 *
 *                              DESCRIPTION
 *
 *  This script is used for file uploading.
 *
 *                              URL-PARAMETER
 *  settings_upload: innotune settings upload
 *  music_upload   : mpd music file upload
 *  certs_upload   : vpn certificate upload
 *
 ******************************************************************************/
 
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

if (isset($_POST['certs_upload'])) {
    $uploaddir = '/var/www/upload_download/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        exec("sudo /var/www/sudoscript.sh vpn_certs");
        $CONTROL_INPUT="erfolgreich";
    } else {
        $CONTROL_INPUT="fehlgeschlagen!";
        echo $uploadfile;
    }
    header('Location: http://'.$_SERVER['SERVER_ADDR'].'/#settings?result='.$CONTROL_INPUT);
}

echo $CONTROL_INPUT;
?>
