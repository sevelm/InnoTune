<style xmlns="http://www.w3.org/1999/html">
    .settingsN {
        height: 15px;
    }

    .settingsW {
        height: 15px;
    }
</style>

<!-- Netzwerkspeicher Einstellungen -->
<div ng-init="getNetworkMount()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Netzwerkspeicher</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <?php
          $kernel_datei = "/var/www/kernel/version.txt";
          $kernel = file($kernel_datei);
          $kernel_local = exec("uname -r");

          if (strpos($kernel_local, 'rockchip') !== false) {
            if (strcmp(trim($kernel_local), trim($kernel[0])) != 0) {
                echo "<h4>Updaten Sie bitte Ihren Kernel!</h4>";
              }
          }
        ?>
        <h5>Verbundene Mountpoints:</h5>
        <div ng-if="networkmount.list.length==0"><ul><li><h6>Keine Mountpoints verbunden!</h6></li></ul></div>
        <div class="mdl-grid" style="padding:0" ng-if="networkmount.list.length!=0">
           <div class="mdl-cell mdl-cell--3-col" style=" font-weight: bold;">Lokaler Ordner</div>
           <div class="mdl-cell mdl-cell--4-col" style=" font-weight: bold;">Adresse</div>
           <div class="mdl-cell mdl-cell--3-col" style=" font-weight: bold;">Dateisystem</div>
           <div class="mdl-cell mdl-cell--1-col" style="margin-top:0">
           </div>
         </div>
           <div ng-repeat="entry in networkmount.list" class="mdl-cell mdl-cell--12-col" ng-click="">
             <div class="mdl-grid" style="padding:0">
                <div class="mdl-cell mdl-cell--3-col">{{entry.dir}}</div>
                <div class="mdl-cell mdl-cell--4-col">{{entry.net}}</div>
                <div class="mdl-cell mdl-cell--3-col">{{entry.fs}}</div>
                <div class="mdl-cell mdl-cell--1-col" style="margin-top:0">
                  <button ng-click="removeNetworkMount(entry)" style="margin-top:0" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--1-col">
                      <i class="material-icons">delete</i>
                      <md-tooltip md-direction="bottom">Löschen</md-tooltip>
                  </button>
                </div>
              </div>
           </div>
        <h5>Neuen Mountpoint hinzufügen: </h5>
        <div class="mdl-grid">
            <p class="mdl-cell mdl-cell--3-col">Pfad</p>
            <md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.path" aria-label="path" ng-pattern="urlPattern">
            </md-input-container>
            <p>zb.: <b>//192.168.0.240/Share</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell  mdl-cell--3-col">Mountpoint-Ordner</p>
            <!--<md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.mountpoint" aria-label="mountpoint"
                  ng-pattern="mntDir">
            </md-input-container>-->
            <md-select placeholder="" ng-model="networkmount.mountpoint"
                       ng-change="networkmount.mountpoint"
                       class="mdl-cell md-no-underline" style="color: #545454"
                       aria-label="mountpoint">
                <md-option ng-repeat="dir in netdirs" value="{{dir}}">{{dir}}</md-option>
            </md-select>
            <p>zb.: <b>net0</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell  mdl-cell--3-col">Typ</p>
            <!--<md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.type" aria-label="type">
            </md-input-container>-->
            <md-select placeholder="" ng-model="networkmount.type"
                       ng-change="networkmount.type"
                       class="mdl-cell md-no-underline" style="color: #545454"
                       aria-label="type">
                <md-option ng-repeat="fstype in netfs" value="{{fstype}}">{{fstype}}</md-option>
            </md-select>
            <p>zb.: <b>cifs</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell mdl-cell--3-col">Optionen</p>
            <md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.options" aria-label="options">
            </md-input-container>
            <p>zb.: <b>user=name,password=pass</b></p>
            <p>Falls keine Optionen das Bsp. in das Feld eintragen.</p>
        </div>
        <br>
        <button ng-click="saveNetworkMount()"
                ng-disabled="!networkmount.path || !networkmount.type || !networkmount.mountpoint"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Speichern
        </button>
        <div>
            <hr>
            Pfad im LMS: <b>Eigene Musik -> Musikordner -> Mountpoint</b>
        </div>
    </div>

    <div class="mdl-card__menu">
        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">info</i>
            <md-tooltip md-direction="bottom">
                Der Netztwerkspeicher ermöglicht Audio-Dateien die auf z.B. Synology NAS liegen<br>
                im Logitech Media Server abzuspielen.<br><br>
                Weitere Infos zur Einbindung finden Sie in unserer Online-Dokumentation.
            </md-tooltip>
        </button>
    </div>
</div>
<!-- Usb-Mount Einstellungen -->
<div ng-init="showUSBSwitch()"
     class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Usb-Mount</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            <h6 class="mdl-cell mdl-cell--8-col">Automatisch Usb-Speichergeräte mounten:</h6>
            <div class="mdl-cell mdl-cell--1-col"></div>
            <md-switch class="mdl-cell mdl-cell--1-col" ng-change="onChangeUsbMount()" ng-true-value="1"
                       ng-false-value="0" ng-model="usbmount"
                       aria-label="Switch 2">
            </md-switch>
            <div>
                <hr>
                <br>
                <b>Information:</b>
                <br>
                Die angesteckten Speichergeräte werden nach dem Zeitpunkt des physischen einsteckens geordnet.<br>
                <br>
                <center>
                    erstes Usb-Gerät = <b>usb0</b>
                    <br>
                    zweites Usb-Gerät = <b>usb1</b>
                    <br>
                    ...
                </center>
                <br><br>
                Pfad im LMS: <b>Eigene Musik -> Musikordner -> usbX</b>
            </div>
        </div>
    </div>

    <div class="mdl-card__menu">
        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">info</i>
            <md-tooltip md-direction="bottom">
                Wenn Sie Ihre Audio-Dateien von einem USB-Stick aus im Logitech Media Server abzuspielen<br>
                möchten, sollten Sie diese automatisch mounten.<br>
                Damit wird der USB-Stick sofort eingebunden.
            </md-tooltip>
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--6-col">
  <div class="mdl-card__title">
      <h2 class="mdl-card__title-text">iTunes Bibliothek</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <h5>Bibliothek einbinden:</h5>
    <div class="mdl-grid">
        <p class="mdl-cell mdl-cell--3-col">Pfad</p>
        <md-input-container class="md-block mdl-cell settingsW">
            <input style="color:gray" ng-model="ituneslib.path" aria-label="path" ng-pattern="urlPattern">
        </md-input-container>
        <p>zb.: <b>//192.168.0.240/Share</b></p>
    </div>
    <div class="mdl-grid">
        <p class="mdl-cell mdl-cell--3-col">Benutzer</p>
        <md-input-container class="md-block mdl-cell settingsW">
            <input style="color:gray" ng-model="ituneslib.user" aria-label="user" type="text">
        </md-input-container>
    </div>
    <div class="mdl-grid">
        <p class="mdl-cell mdl-cell--3-col">Passwort</p>
        <md-input-container class="md-block mdl-cell settingsW">
            <input style="color:gray" ng-model="ituneslib.password" aria-label="password" type="password">
        </md-input-container>
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <button ng-click='saveItunes()' class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" >
        Speichern
    </button>
  </div>
  <div class="mdl-card__menu">
      <button ng-click="refreshItunes()"
              class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
          <i class="material-icons">autorenew</i>
          <md-tooltip md-direction="bottom">Aktualisieren</md-tooltip>
      </button>
      <button ng-click="deleteItunes()"
              class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
          <i class="material-icons">delete</i>
          <md-tooltip md-direction="bottom">Löschen</md-tooltip>
      </button>
      <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
          <i class="material-icons">info</i>
          <md-tooltip md-direction="bottom">
              Hier können Sie Ihre iTunes Library die auf einer NAS liegt<br>
              in den Logitech Media Server einbinden.<br>
              Das Tutorial finden Sie in unserer Online-Dokumentation.
          </md-tooltip>
      </button>
  </div>
</div>
