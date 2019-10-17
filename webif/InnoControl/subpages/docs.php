<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */

// Verfügbare Version einlesen
$datei = "https://raw.githubusercontent.com/sevelm/InnoTune/master/version.txt"; // Name der Datei
$version_server = file($datei); // Datei in ein Array einlesen

//Aktuelle Version einlesen
$datei = "/var/www/version.txt"; // Name der Datei
$version_local = file($datei); // Datei in ein Array einlesen

$kernel_datei = "/var/www/kernel/version.txt";
$kernel = file($kernel_datei);
$kernel_local = exec("uname -r");

$beta_datei = "https://raw.githubusercontent.com/sevelm/InnoTune/master/webif/beta/version.txt";
$beta = file($beta_datei);
?>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Download Loxone Vorlagen</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Loxone Musterprojekt Downloaden</h5>
            <a style="border-radius: 0;" href="http://www.innotune.at/download" target="_blank"
               class="mdl-button mdl-button--primary mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--2-col">Download</a>
        </div>
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">Virtuelle Ausgänge</h5>
            <a style="border-radius: 0;" href="http://www.innotune.at/download" target="_blank"
               class="mdl-button mdl-button--primary mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--2-col">Download</a>
        </div>
        <div class="mdl-grid">
            <h5 class="mdl-cell mdl-cell--5-col">InnoTune-Loxone Integrator</h5>
            <a style="border-radius: 0;" href="http://www.innotune.at/download" target="_blank"
               class="mdl-button mdl-button--primary mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--2-col">Download</a>
        </div>
    </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Update</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <br>
                <div class="mdl-grid">
                    <h5 class="mdl-cell mdl-cell--7-col">Aktuelle Version</h5>
                    <p><?php echo $version_local[0]; ?></p>
                </div>
                <div class="mdl-grid">
                    <h5 class="mdl-cell mdl-cell--7-col">Verfügbare Version</h5>
                    <p><?php echo $version_server[0]; ?></p>
                </div>
                <br>
                <?php
                  if (strpos($kernel_local, 'rockchip') !== false) {
                 ?>
                  <div class="mdl-grid">
                      <h5 class="mdl-cell mdl-cell--7-col">Aktuelle Kernelversion</h5>
                      <p><?php echo $kernel_local; ?></p>
                  </div>
                  <div class="mdl-grid">
                      <h5 class="mdl-cell mdl-cell--7-col">Verfügbare Kernelversion</h5>
                      <p><?php echo $kernel[0]; ?></p>
                  </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="mdl-card__actions mdl-card--border">
    <?php
    if (strcmp(trim($version_local[0]), trim($version_server[0])) == 0) {
        echo "<button disabled='' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
            UPDATE
        </button >";
    } else {
        echo "<button ng-click='update()' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
            UPDATE
        </button >";
    }
    if (strpos($kernel_local, 'rockchip') !== false) {
      if (strcmp(trim($kernel_local), trim($kernel[0])) == 0) {
          echo "<button disabled='' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
              KERNEL-UPDATE
          </button >";
      } else {
          echo "<button ng-click='updateKernel()' class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" >
              KERNEL-UPDATE
          </button >";
      }
    }
    ?>
  </div>
</div>

<div class="mdl-cell mdl-cell--6-col" style="margin: 0">
  <div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title">
          <h2 class="mdl-card__title-text">Sicherung exportieren</h2>
      </div>
      <div class="mdl-card__supporting-text">
          <h5>Einstellungen auf PC Sichern</h5>
          <br>
      </div>
      <div class="mdl-card__actions mdl-card--border">
          <a href="/scripts/download.php?file=settings"
             class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
              Sichern
          </a>
      </div>
  </div>
  <div ng-init="isfileuploaded()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
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
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Log</h2>
    </div>
    <div class="mdl-card__supporting-text">
      <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--12-col">
              <h5 style="margin-top: 0">Letzten Einträge:</h5>
              <p>
                <?php
                    $out = array();
                    exec("tail -5 /var/www/checkprocesses.log", $out);
                    foreach($out as $line) {
                      echo "$line<br>";
                    }
                ?>
              </p>
          </div>
      </div>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <a href="/api/helper.php?logfile"
           class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Sichern
        </a>
        <a href="/log/"
           class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Weitere Logs
        </a>
        <button ng-click="resetLogs($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                style="color: rgb(255, 54, 47);">
            Logs löschen
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Changelog</h2>
    </div>
    <div class="mdl-card__supporting-text">
      <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--12-col">
                <h5 style="margin-top: 0">Changelog:</h5>
                <ul>
                <?php
                    echo shell_exec("cd /opt/innotune/update/cache/InnoTune ; git log --date=short --pretty=format:'<li> <a href=\"http://github.com/sevelm/InnoTune/commit/%H\"> view commit &bull;</a> %x09%ad: %s</li> ' | head -n 5");
                ?>
                </ul>
          </div>
      </div>
    </div>
</div>

<script type="application/javascript">
    document.getElementById('userfile').onchange = function () {
        document.getElementById('display-text').textContent = document.getElementById('userfile').value.split(/(\\|\/)/g).pop();
        document.getElementById('settings_upload').disabled = document.getElementById('userfile').value.split(/(\\|\/)/g).pop().trim() !== "settings.zip";
    }
</script>
