<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 03.09.2016
 * Time: 13:45
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>InnoControl - Rebooting</title>
    <link rel="stylesheet" href="../css/material.cyan-light_blue.min.css">
    <link rel="icon" sizes="512x512" href="../images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
</head>
<body onload="reboot()" style="background-color: #333333; height: 100%; min-height:100%; position: relative">
<header>
    <img style="margin: 1%; height: 20%; width: 20%;px" src="../images/innotuneweiss.png">
</header>
<div style="text-align: center">
    <div class="mdl-grid">
        <div class="mdl-cell"></div>
        <div class="mdl-cell">
            <div style="margin: 0 auto; align-items: center" class="demo-card-wide mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Rebooting</h2>
                </div>
                <div class="mdl-card__supporting-text" id="text">
                    Der Server wird gerade neugestartet. Diese Seite wird aktualisiert wenn der Server wieder verfügbar
                    ist. (ca. 1 Minute)
                </div>
                <div id="spinner" class="mdl-card__actions mdl-card--border">
                    <div class="mdl-spinner mdl-js-spinner is-active"></div>
                </div>
            </div>
        </div>
        <div class="mdl-cell"></div>
    </div>
</div>
<script>
    function reboot() {
        if (getParameterByName("dhcp") == "dhcp") {
            document.getElementById("text").innerHTML = "Der Server wurde auf DHCP umgestellt, benutze den InnoTuneScanner um dein InnoTune System im Netzwerk zu finden! \n\n";
            var node = document.createElement("a");
            node.setAttribute('href',"http://www.innotune.at/web_2016/wp-content/uploads/www.innotune.at-innotunescanner.zip");
            node.innerHTML = "InnoTuneScanner";
            document.getElementById("text").appendChild(node);
            document.getElementById("spinner").style.visibility = "hidden";

            if (getParameterByName("reload") === null) {
                window.history.pushState('reboot.php', 'InnoControl - Rebooting',
                    document.location + '&reload=true');
                var xhr = new XMLHttpRequest();
                xhr.open('GET', "/api/helper.php?reboot", true);
                xhr.send();
            }
        } else if (getParameterByName("update") == "true") {
            if (getParameterByName("reload") === null) {
                window.history.pushState('reboot.php', 'InnoControl - Rebooting',
                    document.location + '&reload=true');
                var xhr = new XMLHttpRequest();
                xhr.open('GET', "/api/helper.php?reboot", true);
                xhr.send();
            }

            setInterval(function () {
                $.ajax({
                    url: '/index.php',
                    success: function (result) {
                        location.replace("/index.php");
                    },
                    error: function () {
                        console.log("down");
                    }
                })
            }, 3000);
        } else {
            var newIp;

            if (getParameterByName("ip") == null) {
                newIp = "";
            } else {
                newIp = "http://" + getParameterByName("ip");
            }

            if (getParameterByName("reload") === null) {
                window.history.pushState('reboot.php', 'InnoControl - Rebooting',
                    document.location + '&reload=true');
                var xhr = new XMLHttpRequest();
                xhr.open('GET', "/api/helper.php?reboot", true);
                xhr.send();
            }
            setInterval(function () {
                $.ajax({
                    url: newIp + '/index.php',
                    success: function (result) {
                        if (newIp == "") {
                            location.replace("/index.php");
                        } else {
                            location.replace(newIp);
                        }
                    },
                    error: function () {
                        console.log("down");
                    }
                })
            }, 3000);
        }
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
</script>

<script src="../js/material.min.js"></script>
<script src="../js/jquery-3.1.0.min.js"></script>
</body>
</html>
