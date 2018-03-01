<?php
$refresh = 0;
if (isset($_GET['refreshCnt'])) {
  $refresh = intval($_GET['refreshCnt']);
}
if((refresh % 10000) == 0) {
if (isset($_GET['player'])) {
$mac = $_GET['player'];
//urls to fetch the images from
$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':9000/music/current/cover.jpg?player=' . $mac;
$image = 'http://' . $_SERVER['SERVER_ADDR'] . '/images/loxart.jpeg';
$back = 'http://' . $_SERVER['SERVER_ADDR'] . '/images/loxbackground.jpeg';

//constants to check image file format
$PNG = "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a";
$JPEG = "\xFF\xD8\xFF";

//size of the album art in composed image
$innerSize = 300;

//Get album art as string
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
//if 404 not found fetch loxart.jpeg as replacement
if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $image);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  header('Content-Type: image/jpeg');
  echo $output;
  exit;
}
//fetch background image
curl_setopt($ch, CURLOPT_URL, $back);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$background = imagecreatefromstring(curl_exec($ch));
curl_close($ch);

$info = explode(';', shell_exec("./lox.sh $mac"));

//if album art is in png format convert the composed image to jpeg
if (substr($output, 0, strlen($PNG)) === $PNG) {
    header('Content-Type: image/jpeg');
    $im = imagecreatefromstring($output);
    $scaled = imagescale($im, $innerSize);
    imagedestroy($im);
    imagecopy($background, $scaled, 150, 75, 0, 0, $innerSize, $innerSize);
    imagedestroy($scaled);
    $white = imagecolorallocate($background, 255, 255, 255);

    $textboxsong = imagettfbbox(20, 0, './font.ttf', $info[0]);
    $songposx = 600 - ($textboxsong[4] - $textboxsong[6]);
    if($songposx < 0) {
      $songposx = 0;
    }
    imagettftext($background, 20, 0, ($songposx / 2), 450, $white, './font.ttf', $info[0]);

    if($info[2] !== "") {
        $textboxartist = imagettfbbox(20, 0, './font.ttf', $info[2]);
        $artistposx = 600 - ($textboxartist[4] - $textboxartist[6]);
        if($artistposx < 0) {
          $artistposx = 0;
        }
        imagettftext($background, 20, 0, ($artistposx / 2), 490, $white, './font.ttf', $info[2]);
    }
    if($info[1] !== "") {
        $textboxalbum = imagettfbbox(20, 0, './font.ttf', $info[1]);
        $albumposx = 600 - ($textboxalbum[4] - $textboxalbum[6]);
        if($albumposx < 0) {
          $albumposx = 0;
        }
        imagettftext($background, 20, 0, ($albumposx / 2), 530, $white, './font.ttf', $info[1]);
    }
    imagejpeg($background, $converted, 100);
    imagedestroy($background);
    imagejpeg($converted);
    imagedestroy($converted);
} else if(substr($output, 0, strlen($JPEG)) === $JPEG) {
    header('Content-Type: image/jpeg');
    $im = imagecreatefromstring($output);
    $scaled = imagescale($im, $innerSize);
    imagedestroy($im);
    imagecopy($background, $scaled, 150, 75, 0, 0, $innerSize, $innerSize);
    imagedestroy($scaled);
    $white = imagecolorallocate($background, 255, 255, 255);

    $textboxsong = imagettfbbox(20, 0, './font.ttf', $info[0]);
    $songposx = 600 - ($textboxsong[4] - $textboxsong[6]);
    if($songposx < 0) {
      $songposx = 0;
    }
    imagettftext($background, 20, 0, ($songposx / 2), 450, $white, './font.ttf', $info[0]);

    if($info[2] !== "") {
        $textboxartist = imagettfbbox(20, 0, './font.ttf', $info[2]);
        $artistposx = 600 - ($textboxartist[4] - $textboxartist[6]);
        if($artistposx < 0) {
          $artistposx = 0;
        }
        imagettftext($background, 20, 0, ($artistposx / 2), 490, $white, './font.ttf', $info[2]);
    }
    if($info[1] !== "") {
        $textboxalbum = imagettfbbox(20, 0, './font.ttf', $info[1]);
        $albumposx = 600 - ($textboxalbum[4] - $textboxalbum[6]);
        if($albumposx < 0) {
          $albumposx = 0;
        }
        imagettftext($background, 20, 0, ($albumposx / 2), 530, $white, './font.ttf', $info[1]);
    }
    imagejpeg($background);
    imagedestroy($background);
}
}
}
?>
