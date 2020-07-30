<?php
/*******************************************************************************
 *                                  INFO
 *
 * Filename :    download.php
 * Directory:    /var/www/InnoControl/api/
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
 *  This script is used to download a certain file.
 *
 *                              URL-PARAMETER
 *  file: name of the requested file
 *
 ******************************************************************************/
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 03.09.2016
 * Time: 18:33
 */
// Uebergabewert welches File eingelesen werden soll: loxone,virtuelle_ausgaenge,..
$file = $_GET['file'];

if (strcmp($file, "loxone") == 0) {
    $file = '/var/www/InnoControl/download/InnoTune.Loxone';
    header('Content-Type: application/loxone');
}
if ($file=="virtuelle_ausgaenge") {
    $file = '/var/www/InnoControl/download/innotune_vorlage_virtuelle_ausgaenge.zip';
    header('Content-Type: application/zip');
}
if($file=="settings"){
    exec("sudo /var/www/sudoscript.sh store_settings");
    $file = '/var/www/upload_download/settings.zip';
    header('Content-Type: application/zip');
}

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers

header('Content-Disposition: attachment; filename="'. basename($file) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));

readfile($file);

exit;
?>
