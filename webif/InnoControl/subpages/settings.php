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
<div class="mdl-grid mdl-cell--6-col no-spacing">
    <!-- Netzwerk Einstellungen -->
    <div ng-init="getNetworkSettings()"
         class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--top">
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
                    <p class="mdl-cell ">Gateway</p>
                    <md-input-container class="md-block mdl-cell settingsN">
                        <input class="settingsNF" name="gate" ng-pattern="ipPattern" ng-model="network.gate"
                               aria-label="gate">
                    </md-input-container>
                </div>
                <span ng-show="networkForm.gate.$error.pattern"
                      style="color:red">IP Adresse ist nicht möglich!</span>

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
                <?php
                if (!(exec("cat /etc/os-release | grep Raspbian | wc -l") == 0 &&
                    exec("uname -r | grep rockchip | wc -l") == 0)) {?>
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Wlan-Einstellungen</h2>
                </div>
                <div class="mdl-grid">
                  <h6 ng-if="network.wlanfailed == 'true'">
                    Wlan-Verbindung fehlgeschlagen, bitte vergewissern Sie sich ob die eingegebenen Daten korrekt sind!
                  </h6>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--5-col">
                        Wlan verwenden</p>
                    <md-switch class="mdl-cell mdl-cell--1-col" ng-true-value="'1'"
                               ng-false-value="'0'" ng-model="network.wlan"
                               aria-label="Switch 3">
                    </md-switch>
                    <div class="mdl-layout-spacer"></div>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell">MAC-Adresse</p>
                    <md-input-container class="md-block mdl-cell settingsN">
                        <input id="wlanmac" style="color:gray" ng-model="network.macwlan" readonly aria-label="macwlan">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell">SSID</p>
                    <!--<md-input-container class="md-block mdl-cell settingsN">
                        <input ng-model="network.ssid" aria-label="ssid">
                    </md-input-container>-->
                    <md-select placeholder="{{network.ssid}}" ng-model="network.ssid"
                               ng-change=""
                               class="mdl-cell md-no-underline" style="color: #545454">
                        <md-option ng-repeat="wifiname in network.wifilist" value="{{wifiname}}">{{wifiname}}</md-option>
                    </md-select>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell">Passwort</p>
                    <md-input-container class="md-block mdl-cell settingsN">
                        <input ng-model="network.psk" type="text" aria-label="wlanpassword">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                  <h6 ng-if="network.test == 1">
                    Wlan-Test erfolgreich. Wlan kann verwendet werden.
                  </h6>
                  <h6 ng-if="network.test == 0">
                    Wlan-Test fehlgeschlagen
                  </h6>
                  <h6 ng-if="network.test == -1">
                    Test läuft...
                  </h6>
                </div>
              <?php } ?>
            </ng-form>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="setNetworkSettings()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Speichern
            </button>
            <?php
            if (!(exec("cat /etc/os-release | grep Raspbian | wc -l") == 0 &&
                exec("uname -r | grep rockchip | wc -l") == 0)) {?>
              <button ng-click="testWlan()"
                      class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                  Wlan-Test
              </button>
            <?php } ?>
        </div>
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
                    <input name="password" ng-model="settings.password" type="text" aria-label="password"
                        ng-pattern="passwordPattern" aria-controls="password-help" aria-describedby="password-help">
                </md-input-container>
                <div>
                  <i class="material-icons">info</i>
                  <md-tooltip md-direction="bottom">
                    Erlaubte Zeichen: a-z A-Z ß 0-9 ! &quot; § % / ( ) = ? * '
                  </md-tooltip>
                </div>
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
    <div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Werkseinstellungen</h2>
        </div>
        <div class="mdl-card__supporting-text">
            <center>
                <h5>Auf Werkseinstellungen zurücksetzen</h5>
            </center>
            <br>
            <b>Bereiche zum Zurücksetzen wählen:</b>

            <br>
            <div class="mdl-grid">
                <label class="mdl-cell mdl-cell--5-col">Usb-Geräte</label>
                <md-switch class="mdl-cell" ng-model="resetcb.usb" aria-label="Switch 1">
                </md-switch>
                <div class="mdl-layout-spacer"></div>
            </div>
            <div class="mdl-grid">
                <label class="mdl-cell mdl-cell--5-col">Netzwerk</label>
                <md-switch class="mdl-cell" ng-model="resetcb.network" aria-label="Switch 1">
                </md-switch>
                <div class="mdl-layout-spacer"></div>
            </div>
            <div class="mdl-grid">
                <label class="mdl-cell mdl-cell--5-col">Playlists</label>
                <md-switch class="mdl-cell" ng-model="resetcb.playlists" aria-label="Switch 1">
                </md-switch>
                <div class="mdl-layout-spacer"></div>
            </div>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="reset()" ng-disabled="resetcb.network==false && resetcb.usb==false && resetcb.playlists==false" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Zurücksetzen
            </button>
        </div>
    </div>
</div>
