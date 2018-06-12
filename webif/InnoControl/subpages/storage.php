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
        <h5>Verbundene Mountpoints:</h5>

        <div class="mdl-grid" style="padding:0">
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
                <input style="color:gray" ng-model="networkmount.path" aria-label="path">
            </md-input-container>
            <p>zb.: <b>//192.168.0.240/Share</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell  mdl-cell--3-col">Mountpoint-Ordner</p>
            <md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.mountpoint" aria-label="mountpoint">
            </md-input-container>
            <p>zb.: <b>nas</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell  mdl-cell--3-col">Typ</p>
            <md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.type" aria-label="type">
            </md-input-container>
            <p>zb.: <b>cifs</b></p>
        </div>
        <div class="mdl-grid">
            <p class="mdl-cell mdl-cell--3-col">Optionen</p>
            <md-input-container class="md-block mdl-cell settingsW">
                <input style="color:gray" ng-model="networkmount.options" aria-label="options">
            </md-input-container>
            <p>zb.: <b>user=name,password=pass,...</b></p>
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
</div>
