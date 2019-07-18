<p class="mdl-cell--12-col" style="margin:0; padding:0" ng-init="getKnxVersion()">{{knxversion}}</p>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col"
    ng-init="checkKnx()" ng-if="!knxinstalled">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">KNXD-Installation</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        KNXD ist noch nicht auf Ihren InnoServer installiert.<br>
        Um KNXD zu installieren drücken Sie bitte auf den "Installieren"-Button.
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-click="installKnx()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Installieren
        </button>
    </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col"
    ng-if="knxinstalled && knxversion != '0.12.15-1'">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">KNXD-Update</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        Um die Rückmeldungen-Funktion zu verwenden müssen Sie KNXD aktualisieren.
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-click="installKnx()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Update
        </button>
    </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--6-col" ng-init="getKnxSettings()">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">KNXD-Einstellung</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <h6 class="mdl-cell mdl-cell--4-col">KNX-Adresse (InnoServer):<br>(z.B.: 0.0.1)</h6>
        <ng-form name="knxAddressForm" class="md-block mdl-cell mdl-cell--8-col">
            <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                <input aria-label="name" name="name" ng-model="knx.address"
                    ng-pattern="knxAddressPattern" ng-change="knx.changed=true">
            </md-input-container>
            <span ng-show="knxAddressForm.name.$error.pattern" style="color:indianred">ungültige KNX-Adresse!</span>
        </ng-form>

        <h6 class="mdl-cell mdl-cell--3-col">Verbindung:</h6>
        <md-select placeholder="{{knx.type}}"
            ng-model="knx.type" ng-change="knx.changed=true"
            class="md-block mdl-cell mdl-cell--9-col md-no-underline" style="color: #545454">
            <md-option value="1">KNX USB-Interface</md-option>
            <md-option value="2">KNX over Ethernet (KNX IP)</md-option>
        </md-select>

        <h6 class="mdl-cell mdl-cell--12-col" ng-if="knx.interfaces == 0">
            Kein KNX USB-Interface angeschlossen.
        </h6>
        <h6 class="mdl-cell mdl-cell--12-col" ng-if="knx.interfaces > 0">
            KNX USB-Interface erkannt.
        </h6>

        <h6 class="mdl-cell mdl-cell--12-col">
            Gestartet:
            <span ng-if="knx.running == 1"> Ja</span>
            <span ng-if="knx.running == 0"> Nein</span>
        </h6>
        <h6 class="mdl-cell mdl-cell--12-col">
            KNXD:
            <span ng-if="knxprocess.knxd >= 1"> Running - OK</span>
            <span ng-if="knxprocess.knxd < 1"> Not Running</span>
        </h6>
        <h6 class="mdl-cell mdl-cell--12-col">
            Listener:
            <span ng-if="knxprocess.listener > 1"> Running - Too Many Processes</span>
            <span ng-if="knxprocess.listener == 1"> Running - OK</span>
            <span ng-if="knxprocess.listener < 1"> Not Running</span>
        </h6>
        <h6 class="mdl-cell mdl-cell--12-col">
            Callback:
            <span ng-if="knxprocess.callback > 1"> Running - Too Many Processes</span>
            <span ng-if="knxprocess.callback == 1"> Running - OK</span>
            <span ng-if="knxprocess.callback < 1"> Not Running</span>
        </h6>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-style="knx.changed && {'color':'indianred'}" ng-click="saveKnxSettings()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Speichern
        </button>
        <button ng-click="restartKnx()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Start
        </button>
        <button ng-click="stopKnx()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Stop
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--6-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Befehl hinzufügen</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <h6 class="mdl-cell mdl-cell--3-col">Gruppen-Adresse:<br>(z.B.: 1/6/6)</h6>
        <ng-form name="knxGroupForm" class="md-block mdl-cell mdl-cell--9-col">
            <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                <input aria-label="name" name="name" ng-model="knxcmd.group" ng-required=""
                    ng-pattern="knxGroupPattern" ng-change="knxcmd.changed=true">
            </md-input-container>
            <span ng-show="knxGroupForm.name.$error.pattern" style="color:indianred">ungültige Gruppen-Adresse!</span>
        </ng-form>

        <h6 class="mdl-cell mdl-cell--3-col">Modus:</h6>
        <md-select placeholder="{{knxcmd.type}}" ng-model="knxcmd.type" ng-change="knxcmd.changed=true"
            class="md-block mdl-cell mdl-cell--9-col md-no-underline" style="color: #545454">
            <md-option value="0" ng-selected="true">Ein/Aus</md-option>
            <md-option value="1">Wert</md-option>
            <md-option value="2">Dimmer</md-option>
        </md-select>

        <h6 class="mdl-cell mdl-cell--3-col" ng-if="knxcmd.type == 2">Dimmer-Typ:</h6>
        <md-select ng-if="knxcmd.type == 2" placeholder="{{knxcmd.dimmertype}}"
            ng-model="knxcmd.dimmertype" ng-change="knxcmd.changed=true"
            class="md-block mdl-cell mdl-cell--9-col md-no-underline" style="color: #545454">
            <md-option value="1">Radio Ein/Weiter/Aus</md-option>
            <md-option value="2">Lautstärke lauter/leiser</md-option>
        </md-select>

        <h6 class="mdl-cell mdl-cell--3-col" ng-if="knxcmd.type == 0">Befehl Ein:</h6>
        <h6 class="mdl-cell mdl-cell--3-col" ng-if="knxcmd.type == 1">Befehl:</h6>
        <h6 class="mdl-cell mdl-cell--3-col" ng-if="knxcmd.type == 2">Mac-Adresse (Zone):</h6>
        <md-input-container class="md-block mdl-cell mdl-cell--9-col">
            <textarea aria-label="name" name="name" ng-model="knxcmd.cmd"
                ng-change="knxcmd.changed=true"></textarea>
        </md-input-container>

        <h6 class="mdl-cell mdl-cell--12-col" ng-if="knxcmd.type == 1">
            Verwenden Sie bitte &lt;v&gt; als Platzhalter für den Wert.
        </h6>

        <h6 class="mdl-cell mdl-cell--3-col" ng-if="knxcmd.type == 0">Befehl Aus:</h6>
        <md-input-container class="md-block mdl-cell mdl-cell--9-col" ng-if="knxcmd.type == 0">
            <textarea aria-label="name" name="name" ng-model="knxcmd.cmdoff"
                ng-change="knxcmd.changed=true"></textarea>
        </md-input-container>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-style="knxcmd.changed && {'color':'indianred'}" ng-click="saveKnxCmd()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Speichern
        </button>
        <button ng-click="resetKnxCmd()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Abbrechen
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Befehlweiterleitung</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            Achtung!<br>
            Wenn die Gruppenadresse einer bestehenden Befehlsweiterleitung geändert wird, bleibt der alte Eintrag vorhanden.<br>
            Dieser Eintrag sollte danach manuell gelöscht werden.
        </div>
        <div class="mdl-cell mdl-cell--2-col">
            <b>Gruppe</b>
        </div>
        <div class="mdl-cell mdl-cell--1-col">
            <b>Typ</b>
        </div>
        <div class="mdl-cell mdl-cell--7-col">
            <b>Befehl bzw. Mac-Adresse (für KNX-Dimmer)</b>
        </div>
        <div class="mdl-cell mdl-cell--2-col"></div>
        <div ng-repeat="savedcmd in knxcmds"
            class="mdl-cell mdl-cell--12-col mdl-grid"
            style="padding: 0; margin: 0">
            <div class="mdl-cell mdl-cell--2-col">{{savedcmd.group}}</div>
            <div class="mdl-cell mdl-cell--1-col">
                <span ng-if="savedcmd.type == 0">
                    Ein/Aus
                </span>
                <span ng-if="savedcmd.type == 1">
                    Wert
                </span>
                <span ng-if="savedcmd.type == 2">
                    Dimmer<br>
                    <span ng-if="savedcmd.dimmertype == 1">
                        Ein/Aus
                    </span>
                    <span ng-if="savedcmd.dimmertype == 2">
                        Lautstärke
                    </span>
                </span>
            </div>
            <div class="mdl-cell mdl-cell--7-col" ng-if="savedcmd.type == 0">
                {{savedcmd.cmd}}<br>
                {{savedcmd.cmdoff}}
            </div>
            <div class="mdl-cell mdl-cell--7-col" ng-if="savedcmd.type == 1">{{savedcmd.cmd}}</div>
            <div class="mdl-cell mdl-cell--7-col" ng-if="savedcmd.type == 2">
                {{savedcmd.cmd}}
            </div>
            <div class="mdl-cell mdl-cell--1-col">
                <button ng-click="editKnxCmd(savedcmd)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">edit</i>
                </button>
            </div>
            <div class="mdl-cell mdl-cell--1-col">
                <button ng-click="deleteKnxCmd(savedcmd)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:indianred">
                    <i class="material-icons">delete</i>
                </button>
            </div>
        </div>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-click="deleteKnxEmptyAddr()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Alle Befehle mit leerer Gruppe löschen
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Rückmeldungen</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            Hier können Sie die Gruppenadressen eintragen, welche bei einer Veränderung des Status/der
            Lautstärke einer Zone, die eine Rückmeldung erhalten sollen.
        </div>

        <div class="mdl-cell mdl-cell--3-col">
            <b>Name</b>
        </div>
        <div class="mdl-cell mdl-cell--3-col">
            <b>Mac</b>
        </div>
        <div class="mdl-cell mdl-cell--2-col">
            <b>Gruppe Status</b>
        </div>
        <div class="mdl-cell mdl-cell--2-col">
            <b>Gruppe Lautstärke</b>
        </div>
        <div class="mdl-cell mdl-cell--4-col">
        </div>

        <div ng-repeat="dev in devices"
            class="mdl-cell mdl-cell--12-col mdl-grid"
            style="padding: 0; margin: 0">
            <div ng-if="dev.betrieb=='normalbetrieb'"
                class="mdl-cell mdl-cell--12-col mdl-grid"
                style="padding: 0; margin: 0">
                <div class="mdl-cell mdl-cell--3-col">
                    {{dev.name}}
                </div>
                <div class="mdl-cell mdl-cell--3-col">
                    {{dev.mac}}
                </div>
                <div class="mdl-cell mdl-cell--2-col">
                    <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                    style="padding: 0; margin: 0">
                        <input aria-label="status" name="status" ng-model="dev.knx.gpstatus">
                    </md-input-container>
                </div>
                <div class="mdl-cell mdl-cell--2-col">
                    <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                    style="padding: 0; margin: 0">
                        <input aria-label="volume" name="volume" ng-model="dev.knx.gpvolume">
                    </md-input-container>
                </div>
                <div class="mdl-cell mdl-cell--1-col">
                    <button ng-click="saveKnxCallback(dev.mac, dev.knx.gpstatus, dev.knx.gpvolume)"
                        class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                        style="color:#000">
                        <i class="material-icons">save</i>
                    </button>
                </div>
                <div class="mdl-cell mdl-cell--1-col">
                    <button ng-click="clearKnxCallback(dev.mac); dev.knx.gpstatus=''; dev.knx.gpvolume='';"
                        class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                        style="color:#000">
                        <i class="material-icons">clear</i>
                    </button>
                </div>
            </div>
            <div ng-if="dev.betrieb=='geteilterbetrieb'"
                class="mdl-cell mdl-cell--12-col mdl-grid"
                style="padding: 0; margin: 0">
                <div class="mdl-cell mdl-cell--12-col mdl-grid"
                    style="padding: 0; margin: 0">
                    <div class="mdl-cell mdl-cell--3-col">
                        {{dev.nameL}}
                    </div>
                    <div class="mdl-cell mdl-cell--3-col">
                        {{dev.macL}}
                    </div>
                    <div class="mdl-cell mdl-cell--2-col">
                        <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                        style="padding: 0; margin: 0">
                            <input aria-label="status" name="status" ng-model="dev.knx.gpstatusL">
                        </md-input-container>
                    </div>
                    <div class="mdl-cell mdl-cell--2-col">
                        <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                        style="padding: 0; margin: 0">
                            <input aria-label="volume" name="volume" ng-model="dev.knx.gpvolumeL">
                        </md-input-container>
                    </div>
                    <div class="mdl-cell mdl-cell--1-col">
                        <button ng-click="saveKnxCallback(dev.macL, dev.knx.gpstatusL, dev.knx.gpvolumeL)"
                            class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                            style="color:#000">
                            <i class="material-icons">save</i>
                        </button>
                    </div>
                    <div class="mdl-cell mdl-cell--1-col">
                        <button ng-click="clearKnxCallback(dev.macL); dev.knx.gpstatusL=''; dev.knx.gpvolumeL='';"
                            class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                            style="color:#000">
                            <i class="material-icons">clear</i>
                        </button>
                    </div>
                </div>

                <div class="mdl-cell mdl-cell--12-col mdl-grid"
                    style="padding: 0; margin: 0">
                    <div class="mdl-cell mdl-cell--3-col">
                        {{dev.nameR}}
                    </div>
                    <div class="mdl-cell mdl-cell--3-col">
                        {{dev.macR}}
                    </div>
                    <div class="mdl-cell mdl-cell--2-col">
                        <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                        style="padding: 0; margin: 0">
                            <input aria-label="status" name="status" ng-model="dev.knx.gpstatusR">
                        </md-input-container>
                    </div>
                    <div class="mdl-cell mdl-cell--2-col">
                        <md-input-container class="md-block mdl-cell mdl-cell--12-col"
                        style="padding: 0; margin: 0">
                            <input aria-label="volume" name="volume" ng-model="dev.knx.gpvolumeR">
                        </md-input-container>
                    </div>
                    <div class="mdl-cell mdl-cell--1-col">
                        <button ng-click="saveKnxCallback(dev.macR, dev.knx.gpstatusR, dev.knx.gpvolumeR)"
                            class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                            style="color:#000">
                            <i class="material-icons">save</i>
                        </button>
                    </div>
                    <div class="mdl-cell mdl-cell--1-col">
                        <button ng-click="clearKnxCallback(dev.macR); dev.knx.gpstatusR=''; dev.knx.gpvolumeR='';"
                            class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                            style="color:#000">
                            <i class="material-icons">clear</i>
                        </button>
                    </div>
                </div>
            </div>
            <div ng-if="dev.betrieb=='deaktiviert'" class="mdl-cell mdl-cell--12-col">
                InnoAmp {{dev.id}} ist deaktiviert.<br>
                Der Amp muss konfiguriert werden, damit dieser hier verwendet werden kann.
            </div>
        </div>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col" ng-init="getKnxRadios()">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Radiosender für Dimmer</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            Hier können Sie die Liste der Radiosender bearbeiten, die für den KNX-Dimmer verwendet werden.<br>
            Alle Dimmer verwenden diese Liste, also ist es nicht möglich für einzelne Dimmer andere Sender zu benutzen.
        </div>
        <div class="mdl-cell mdl-cell--2-col">
            <b>Name</b>
        </div>
        <div class="mdl-cell mdl-cell--8-col">
            <b>URL</b>
        </div>
        <div class="mdl-cell mdl-cell--2-col"></div>

        <div ng-repeat="radio in knxradios"
            class="mdl-cell mdl-cell--12-col mdl-grid"
            style="padding: 0; margin: 0">
            <div class="mdl-cell mdl-cell--2-col" ng-if="radio.edit == 0">
                {{radio.name}}
            </div>
            <div class="mdl-cell mdl-cell--2-col" ng-if="radio.edit == 1">
                <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                    <input aria-label="name" name="name" ng-model="radio.editname">
                </md-input-container>
            </div>
            <div class="mdl-cell mdl-cell--8-col" ng-if="radio.edit == 0">
                {{radio.url}}
            </div>
            <div class="mdl-cell mdl-cell--8-col" ng-if="radio.edit == 1">
                <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                    <input aria-label="url" name="url" ng-model="radio.editurl">
                </md-input-container>
            </div>
            <div class="mdl-cell mdl-cell--1-col" ng-if="radio.edit == 0">
                <button ng-click="radio.editname = radio.name; radio.editurl = radio.url;radio.edit = 1"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">edit</i>
                </button>
            </div>
            <div class="mdl-cell mdl-cell--1-col" ng-if="radio.edit == 1">
                <button ng-click="saveKnxRadio(radio)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">save</i>
                </button>
            </div>
            <div class="mdl-cell mdl-cell--1-col" ng-if="radio.edit == 0">
                <button ng-click="deleteKnxRadio(radio)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:indianred">
                    <i class="material-icons">delete</i>
                </button>
            </div>
            <div class="mdl-cell mdl-cell--1-col" ng-if="radio.edit == 1">
                <button ng-click="radio.edit = 0"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">cancel</i>
                </button>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--12-col">
            <b>Neuen Radiosender hinzufügen:</b>
        </div>
        <div class="mdl-cell mdl-cell--12-col mdl-grid"
            style="padding: 0; margin: 0">
            <div class="mdl-cell mdl-cell--2-col">
                <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                    <input aria-label="name" name="name" ng-model="radioAdd.name">
                </md-input-container>
            </div>
            <div class="mdl-cell mdl-cell--8-col">
                <md-input-container class="md-block mdl-cell mdl-cell--12-col">
                    <input aria-label="url" name="url" ng-model="radioAdd.url">
                </md-input-container>
            </div>
            <div class="mdl-cell mdl-cell--1-col">
                <button ng-click="addKnxRadio()"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">save</i>
                </button>
            </div>
            <div class="mdl-cell mdl-cell--1-col">
                <button ng-click="radioAdd.name = ''; radioAdd.url = ''"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                    style="color:#000">
                    <i class="material-icons">cancel</i>
                </button>
            </div>
        </div>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-click="resetKnxRadios()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Sender zurücksetzen
        </button>
    </div>
</div>
