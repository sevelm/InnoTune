<?php

// server response
$response = '';

?>

<!doctype html>
<html class="no-js" lang="">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Loxone Musicserver">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Loxone Music Server Info</title>

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/modernizr-2.8.3.min.js"></script>
        <script src="js/jquery-1.11.2.min.js"></script>

    </head>
    <body>
        <div class="header-wrapper">
            <div class="header">
                <h1>Loxone Music Server Info</h1>
            </div>
        </div>

        <div class="settings-wrapper">
            <div class="settings">
                <h2>Versions</h2>
                    <?php $file = file_get_contents('./versions.txt', FILE_USE_INCLUDE_PATH); $file = nl2br($file); echo $file; ?>                 
            </div>
        </div>

        <div class="actions-wrapper">
            <div class="actions">
                
                <h2>Service Log:</h2>
                <?php $lines = `tail -500 /var/log/loxone/lws.log`; $lines = nl2br($lines); echo $lines; ?>
                <div style="clear: both;"></div>
            </div>
        </div>
    </body>
</html>
