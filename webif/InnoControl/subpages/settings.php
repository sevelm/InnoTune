<?php
if (isset($_GET['start_usbmount'])) {
    exec("sudo /var/www/sudoscript.sh usbmount 1");
}
if (isset($_GET['stop_usbmount'])) {
    exec("sudo /var/www/sudoscript.sh usbmount 0");
}
?>
<style xmlns="http://www.w3.org/1999/html">
    .settingsN {
        height: 15px;
    }

    .settingsW {
        height: 15px;
    }
</style>

<!-- Netzwerk Einstellungen -->
<div ng-init="getNetworkSettings()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Netzwerk</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <ng-form name="networkForm">
            <div class="mdl-grid">

                <p class="mdl-cell mdl-cell--5-col">
                    IP Adresse Automatisch (DHCP)</p>
                <md-switch class="mdl-cell mdl-cell--1-col" ng-change="onChangeDHCP()" ng-true-value="'dhcp'"
                           ng-false-value="''" ng-model="network.dhcp"
                           aria-label="Switch 2">
                </md-switch>
                <div class="mdl-layout-spacer"></div>
            </div>
            <div class="mdl-grid">
                <p class="mdl-cell">IP Adresse</p>
                <md-input-container class="md-block mdl-cell settingsN">
                    <input name="ip" ng-model="network.ip" ng-pattern="ipPattern" class="settingsNF" value=""
                           aria-label="ip">
                </md-input-container>
            </div>
            <span ng-show="networkForm.ip.$error.pattern" style="color:red">IP Adresse ist nicht möglich!</span>

            <div class="mdl-grid">
                <p class="mdl-cell ">Subnetmask</p>
                <md-input-container class="md-block mdl-cell settingsN">
                    <input class="settingsNF" name="subnetmask" ng-pattern="ipPattern" ng-model="network.subnet"
                           aria-label="subnet">
                </md-input-container>
            </div>
            <span ng-show="networkForm.subnetmask.$error.pattern"
                  style="color:red">Subnetmask ist nicht möglich!</span>

            <div class="mdl-grid">
                <p class="mdl-cell ">DNS1-Adresse</p>
                <md-input-container class="md-block mdl-cell settingsN">
                    <input class="settingsNF" name="dns1" ng-pattern="ipPattern" ng-model="network.dns1"
                           aria-label="dns1">
                </md-input-container>
            </div>
            <span ng-show="networkForm.dns1.$error.pattern"
                  style="color:red">IP Adresse ist nicht möglich!</span>

            <div class="mdl-grid">
                <p class="mdl-cell ">DNS2-Adresse</p>
                <md-input-container class="md-block mdl-cell settingsN">
                    <input class="settingsNF" name="dns2" ng-pattern="ipPattern" ng-model="network.dns2"
                           aria-label="dns2">
                </md-input-container>
            </div>
            <span ng-show="networkForm.dns2.$error.pattern"
                  style="color:red">IP Adresse ist nicht möglich!</span>

            <div class="mdl-grid">
                <p class="mdl-cell">MAC-Adresse</p>
                <md-input-container class="md-block mdl-cell settingsN">
                    <input id="mac" style="color:gray" ng-model="network.mac" readonly aria-label="mac">
                </md-input-container>
            </div>
        </ng-form>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-click="setNetworkSettings()"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Speichern
        </button>
    </div>
</div>
<!-- Webinterface Einstellungen-->
<div class="mdl-grid mdl-cell--6-col no-spacing">
    <div ng-init="getWebinterfaceSettings()"
         class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Webinterface</h2>
        </div>
        <div class="mdl-card__supporting-text">
            <div class="mdl-grid">
                <p class="mdl-cell ">Benutzername</p>
                <md-input-container class="md-block mdl-cell settingsW">
                    <input style="color:gray" ng-model="admin" readonly aria-label="username">
                </md-input-container>
            </div>

            <div class="mdl-grid">
                <p class="mdl-cell ">Passwort</p>
                <md-input-container class="md-block mdl-cell settingsW">
                    <input name="password" ng-model="settings.password" type="text" aria-label="password">
                </md-input-container>
            </div>
            <div class="mdl-grid">
                <p class="mdl-cell ">Port Webinterface</p>
                <md-input-container class="md-block mdl-cell settingsW">
                    <input name="port" ng-model="settings.port" type="number" aria-label="port">
                </md-input-container>
            </div>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="setWebinterfaceSettings()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Speichern
            </button>
        </div>
    </div>
    <!-- Usb-Mount Einstellungen -->
    <div ng-init="showUSBSwitch()"
         class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
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
</div>