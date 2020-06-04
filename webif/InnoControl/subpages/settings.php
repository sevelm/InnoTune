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
                    <p class="mdl-cell">Test-IP</p>
                    <md-input-container class="md-block mdl-cell settingsN">
                        <input id="wlanip" style="color:gray" ng-model="network.ipwlan" readonly aria-label="ipwlan">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell">SSID</p>
                    <md-select placeholder="{{network.ssid}}" ng-model="network.ssid"
                               ng-change=""
                               class="mdl-cell md-no-underline" style="color: #545454">
                        <md-option ng-repeat="wifiname in network.wifilist track by $index" value="{{wifiname}}">{{wifiname}}</md-option>
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
                  <div class="mdl-grid" style="margin: 0" ng-if="network.test == -1">
                      <md-progress-circular class="mdl-cell mdl-cell--2-col"
                        md-mode="indeterminate" md-diameter="30"></md-progress-circular>
                      <h6 class="mdl-cell mdl-cell--10-col">
                        Test läuft... (Dauer ca. 1 Minute)
                      </h6>
                      </div>
                </div>
                <div class="mdl-grid" ng-if="network.wlan == '1'">
                    Bei bestehender Wlan-Verbindung sollte kein Wlan-Test durchgeführt werden. Dies kann
                    zu Komplikationen führen, sodass der Server nur mehr über die Backup-Adresse erreichbar ist.
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
              <button ng-click="testWlan()" ng-disabled="network.test == -1 || network.wlan == '1'"
                      class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                  Wlan-Test
              </button>
            <?php } ?>
        </div>
        <div class="mdl-card__menu">
            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    der Server unterstützt DHCP und statische IP-Adressen.<br>
                    Wenn der Server von einer Loxone oder anderem Netzwerkgerät aus angesprochen wird,<br>
                    sollte eine statische IP verwendet werden.<br>
                </md-tooltip>
            </button>
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
            <ng-form name="webForm">
                <div class="mdl-grid">
                    <p class="mdl-cell ">Benutzername</p>
                    <md-input-container class="md-block mdl-cell settingsW">
                        <input style="color:gray" ng-model="admin" readonly aria-label="username">
                    </md-input-container>
                </div>

                <div class="mdl-grid">
                    <p class="mdl-cell ">Passwort</p>
                    <md-input-container class="md-block mdl-cell settingsW">
                        <input name="password" required ng-model="settings.password" type="text" aria-label="password"
                            ng-pattern="webForm.password.$dirty && passwordPattern" aria-controls="password-help" aria-describedby="password-help">
                    </md-input-container>
                    <div>
                      <i class="material-icons">info</i>
                      <md-tooltip md-direction="bottom" style="font-size: 1em">
                        Erlaubte Zeichen: a-z A-Z ß 0-9 ! &quot; § % / ( ) = ? * ' (Keine Leerzeichen!)
                      </md-tooltip>
                    </div>
                </div>
                <div class="mdl-grid">
                    <span ng-show="webForm.password.$dirty && webForm.password.$invalid" style="color:red">Passwort ungültig!</span>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell ">Port Webinterface</p>
                    <md-input-container class="md-block mdl-cell settingsW">
                        <input name="port" required ng-model="settings.port" type="number" aria-label="port">
                    </md-input-container>
                    <div>
                      <i class="material-icons">info</i>
                      <md-tooltip md-direction="bottom" style="font-size: 1em">
                        Erlaubte Portnummern: 80 bis 65535
                      </md-tooltip>
                    </div>
                </div>
            </ng-form>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="setWebinterfaceSettings()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Speichern
            </button>
        </div>
        <div class="mdl-card__menu">
            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    Hier können Sie den Login des Webinterface ändern.
                </md-tooltip>
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
        <div class="mdl-card__menu">
            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    Hier können einzelne Komponenten des Servers auf Werkseinstellungen zurückgesetzt werden.
                </md-tooltip>
            </button>
        </div>
    </div>
    <!--<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col" ng-init="checkVPNConnection()">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Fernwartungszugang</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Hiermit können Sie uns einen externen Zugriff auf Ihren Server gewähren.<br>
            Status: {{vpncState}}
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="startVPNConnection()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Start
            </button>
            <button ng-click="stopVPNConnection()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Stop
            </button>
        </div>
    </div>-->
</div>

<div class="mdl-grid mdl-cell--6-col no-spacing">
    <div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Ifconfig</h2>
        </div>
        <div class="mdl-card__supporting-text">
            <?php echo str_replace("\n", "<br>", shell_exec("ifconfig")); ?>
        </div>
        <div class="mdl-card__menu">
            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    Zeigt die Netwerk-Interfaces des Servers.
                </md-tooltip>
            </button>
        </div>
    </div>
</div>
<div class="mdl-grid mdl-cell--6-col no-spacing"></div>
