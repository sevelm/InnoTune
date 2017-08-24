<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */

// Verfügbare Version einlesen
$datei = "https://raw.githubusercontent.com/JHoerbst/InnoTune/master/version.txt"; // Name der Datei
$version_server = file($datei); // Datei in ein Array einlesen

//Aktuelle Version einlesen
$datei = "/var/www/version.txt"; // Name der Datei
$version_local = file($datei); // Datei in ein Array einlesen
?>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Download Loxone Vorlagen</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Loxone Musterprojekt Downloaden</h5>
            <a style="border-radius: 0%;" href="./scripts/download.php?file=loxone"
               class="mdl-button mdl-button--primary mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--2-col">Download</a>
        </div>
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Virtuelle Ausgänge</h5>
            <a style="border-radius: 0%;" href="./scripts/download.php?file=virtuelle_ausgaenge"
               class="mdl-button mdl-button--primary mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--2-col">Download</a>
        </div>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Update</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Aktuelle Version</h5>
            <p><?php echo $version_local[0]; ?></p>
        </div>
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Verfügbare Version</h5>
            <p><?php echo $version_server[0]; ?></p>
        </div>
    </div>
    <?php
    if (strcmp(trim($version_local[0]), trim($version_server[0])) == 0) {
        echo "    <div class=\"mdl-card__actions mdl-card--border\" >
        <button disabled='' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
            UPDATE
        </button >
    </div>";
    } else {
        echo "    <div class=\"mdl-card__actions mdl-card--border\" >
        <button ng-click='update()' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
            UPDATE
        </button >
    </div>";
    }
    ?>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Sicherung exportieren</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <h5>Einstellungen auf PC Sichern</h5>
        <br>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <a href="./scripts/download.php?file=settings"
           class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Sichern
        </a>
    </div>
</div>
<div ng-init="isfileuploaded()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Sicherung importieren</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <h5>Einstellung in InnoTune importieren.</h5>
        Wichtig! Dateiname: <b>settings.zip</b>
        <br/>
        <br>
        <div class="mdl-card__actions mdl-card--border">
            <form enctype="multipart/form-data" action="scripts/upload.php?settings_upload" method="POST">
                <input type="hidden" name="MAX_FILE_SIZE" value="512000"/>
                <label class="mdl-button">
                    <input name="userfile" id="userfile" type="file" accept="settings.zip" ng-click="settingsupload=1"/>
                    Settings importieren
                </label>
                <span id="display-text"></span>
                <button id="settings_upload" type="submit" disabled name="settings_upload" value="Wiederherstellen"
                        class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Wiederherstellen
                </button>

            </form>
        </div>

    </div>
</div>

<script type="application/javascript">
    document.getElementById('userfile').onchange = function () {
        document.getElementById('display-text').textContent = document.getElementById('userfile').value.split(/(\\|\/)/g).pop();
        document.getElementById('settings_upload').disabled = document.getElementById('userfile').value.split(/(\\|\/)/g).pop().trim() !== "settings.zip";
    }
</script>