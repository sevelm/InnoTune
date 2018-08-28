<?php
header('Access-Control-Allow-Origin: *');

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset();
    session_destroy();
}
if (!isset($_SESSION["logged_in"]) && empty($_SESSION["logged_in"])) {
    header('Location: ' . "login.php", true, 303);
    die();
}

?>

<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html ng-app="innoControl" lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="InnoControl - Die Oberfläche zum steuren deines InnoTune Systems.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="theme-color" content="#40c4ff">
    <title>InnoControl - Dashboard</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="512x512" href="images/favicon.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/favicon.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css" type="text/css">
    <link rel="stylesheet" href="css/styles.min.css" type="text/css">
    <link rel="stylesheet" href="css/angular-material.min.css" type="text/css">
    <style>
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
        }

        #view-source {
            position: fixed;
            display: block;
            right: 0;
            bottom: 0;
            margin-right: 40px;
            margin-bottom: 40px;
            z-index: 900;
        }
    </style>
</head>
<body id="InnoController" ng-controller="InnoController">
<div class="innocontrol-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
            <span id="location" class="mdl-layout-title" style="font-size: 30px">InnoControl</span>
            <div class="mdl-layout-spacer"></div>
            <a ng-show="audioConfChanged=='1'" ng-click="genAudioConf($event)"
               class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-cell--hide-phone"
               style="right: 20%; color: red">
                Der Server muss neu gestartet werden!
            </a>
            <a href="/old"
               class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-cell--hide-phone ">
                Alte Konfigurationsseite
            </a>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="verticalmenu">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="verticalmenu">
                <a target="_blank" style="text-decoration: none" href="http://www.innotune.at/kontakt/">
                    <li class="mdl-menu__item">Kontakt</li>
                </a>
                <a target="_blank" style="text-decoration: none" href="http://www.innotune.at/">
                    <li class="mdl-menu__item">Homepage</li>
                </a>
                <a target="_blank" style="text-decoration: none" href="https://github.com/JHoerbst/InnoTune/issues/new">
                    <li class="mdl-menu__item">Bug melden</li>
                </a>
                <form id="logoutForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <li onclick="logoutForm.submit()" class="mdl-menu__item">Logout</li>
                </form>
            </ul>
        </div>
    </header>
    <div class="innocontrol-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="innocontrol-drawer-header">
            <div class="mdl-layout-spacer"></div>
            <img src="images/innocontrol.png" class="unselectable" alt="InnoControl header"
                 style="height: 45%; width: 100%;">
            <div class="mdl-layout-spacer"></div>
        </header>
        <nav class="innocontrol-navigation mdl-navigation mdl-color--blue-grey-800">
            <a id="homeanchor" name="routeanchors" class="mdl-navigation__link" href="#home"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">home</i>Home</a>
            <a id="devicesanchor" name="routeanchors" class="mdl-navigation__link" href="#devices"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">usb</i>InnoAmp's</a>
            <a id="volumemixeranchor" name="routeanchors" class="mdl-navigation__link" href="#volumemixer"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">volume_up</i>Lautstärke</a>
            <a id="lineinanchor" name="routeanchors" class="mdl-navigation__link" href="#linein"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">settings_input_component</i>Line-In</a>
            <a id="mpdanchor" name="routeanchors" class="mdl-navigation__link" href="#mpd"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">speaker_group</i>Player Zentral</a>
            <a id="voiceoutputanchor" name="routeanchors" class="mdl-navigation__link" href="#voiceoutput"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">record_voice_over</i>Sprachausgabe</a>
            <a id="storageanchor" name="routeanchors" class="mdl-navigation__link" href="#storage"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">sd_storage</i>Speichergeräte</a>
            <a id="docsanchor" name="routeanchors" class="mdl-navigation__link" href="#docs"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">system_update_alt</i>Dokumente</a>
            <div class="mdl-layout-spacer"></div>
            <a id="settingsanchor" name="routeanchors" class="mdl-navigation__link" href="#settings"><i
                        class="mdl-color-text--blue-grey-400 material-icons"
                        role="presentation">settings</i>Einstellungen</a>
        </nav>
    </div>
    <main class="mdl-layout__content mdl-color--grey-100">


        <div ng-view id="content" class="mdl-grid">
            <!-- Here goes content through routing mechanism-->
        </div>


    </main>
</div>
<div id="loadingsymbol" class="modal">
    <div layout="row" layout-sm="column" layout-align="space-around">
        <md-progress-circular md-mode="indeterminate" md-diameter="70" style="margin-top: 20%"></md-progress-circular>
    </div>
</div>
<!-- Frameworks, etc.-->
<script src="js/material.min.js"></script>
<script src="js/jquery-3.1.0.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-route.min.js"></script>
<script src="js/angular-animate.min.js"></script>
<script src="js/angular-aria.min.js"></script>
<script src="js/angular-messages.min.js"></script>
<script src="js/angular-material.min.js"></script>
<!-- Eigene Scripts-->
<script src="scripts/app.js?version=1.0.1"></script>
<script src="scripts/controller.js?version=1.1.3"></script>
<script src="scripts/routes.js?version=1.0.1"></script>
</body>
</html>
