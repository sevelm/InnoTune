<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">System</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid">
        System-Typ: {{systemType}}<br>
    </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Lüfterregelung</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid" ng-if="systemCoding > 0">
        <div class="mdl-cell--3-col">
            <div class="mdl-cell--12-col">
                <p>Modus: {{fanOptions.type}}</p>
            </div>
            <div class="mdl-cell--12-col">
                <p>Status: {{fanOptions.state}}</p>
            </div>
            <div class="mdl-cell--12-col" ng-if="tempSensor.online == 1">
                <p>Sensor: erkannt</p>
            </div>
            <div class="mdl-cell--12-col" ng-if="tempSensor.online == 0">
                <p>Sensor: nicht erkannt/verbaut</p>
            </div>
        </div>

        <div class="mdl-cell--9-col">
            <div class="mdl-cell--12-col">
                <p>CPU-Temperatur: {{sysinfo.cputemp}} °C</p>
            </div>
            <div class="mdl-cell--12-col" ng-if="tempSensor.online == 1">
                <p>Innen-Temperatur: {{tempSensor.temperature}} °C</p>
            </div>
            <div class="mdl-cell--12-col" ng-if="tempSensor.online == 1">
                <p>Luftfeuchtigkeit: {{tempSensor.humidity}} %</p>
            </div>
        </div>

        <div class="mdl-cell--3-col" style="margin: auto 0">
            Manueller Betrieb:
        </div>
        <md-switch class="mdl-cell--1-col" ng-model="fanOptions.manualOperation"
                   aria-label="fanManual" ng-change="setFanOperation()"
                   ng-true-value="1" ng-false-value="0" style="margin-right: 0">
        </md-switch>
        <div class="mdl-cell--8-col"></div>

        <div class="mdl-cell--3-col" ng-if="fanOptions.typeValue == 0"
            style="margin: auto 0">
            Lüfter einschalten:
        </div>
        <md-switch class="mdl-cell--1-col" ng-model="fanOptions.newStateValue"
                   aria-label="fanswitch" ng-change="setFanState()"
                   ng-true-value="1" ng-false-value="0"
                   ng-disabled="fanOptions.manualOperation == 0"
                   ng-if="fanOptions.typeValue == 0" style="margin-right: 0">
        </md-switch>
        <div class="mdl-cell--8-col" ng-if="fanOptions.typeValue == 0"></div>

        <div class="mdl-cell--3-col" ng-if="fanOptions.typeValue == 1"
            style="margin: auto 0">
            Lüfter-Stufe:
        </div>
        <md-slider-container class="mdl-cell--9-col" ng-if="fanOptions.typeValue == 1">
            <md-slider flex="" ng-change="setFanState()" md-discrete="" min="0" max="10" step="1"
                       ng-model="fanOptions.newStateValue"
                       id="fan-slider" aria-label="fanslider"
                       ng-disabled="fanOptions.manualOperation == 0">
            </md-slider>
            <md-input-container>
                <input flex="" type="number" ng-change="setFanState()"
                       ng-model="fanOptions.newStateValue"
                       aria-controls="fan-slider" aria-label="fansliderinput"
                       ng-disabled="fanOptions.manualOperation == 0">
            </md-input-container>
        </md-slider-container>
    </div>
    <div class="mdl-card__supporting-text mdl-grid" ng-if="systemCoding == 0">
        <p>Der InnoServer verfügt über keinen Lüfter.</p>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Soundkarten-Stummschaltung</h2>
    </div>
    <div class="mdl-card__supporting-text mdl-grid" ng-if="systemCoding >= 2">
        <div ng-repeat="device in devices" class="mdl-cell--12-col mdl-grid">
            <h6 class="mdl-cell--12-col"><b>Usb Gerät {{device.id}} (
                <span ng-if="device.betrieb=='normalbetrieb'">{{device.name}}</span>
                <span ng-if="device.betrieb=='geteilterbetrieb'">{{device.nameR}} - {{device.nameL}}</span>
                <span ng-if="device.betrieb=='deaktiviert'">Deaktiviert</span>
                <span ng-if="device.betrieb=='nichtverbunden'">Nicht verfügbar</span>
                )</b></h6>
            <div class="mdl-cell--12-col"
                ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'">
                Status: <span ng-if="device.isMuted==1">Stummgeschaltet</span>
                <span ng-if="device.isMuted==0">Nicht Stummgeschaltet</span>
            </div>
            <div class="mdl-cell--3-col" style="margin: auto 0"
                ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'">
                Manueller Betrieb:
            </div>
            <md-switch class="mdl-cell--1-col" ng-model="device.manualOperation"
                       aria-label="deviceManual" ng-change="setMuteOperation(device)"
                       ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'"
                       ng-true-value="1" ng-false-value="0" style="margin-right: 0">
            </md-switch>
            <div class="mdl-cell--8-col"
                ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'"></div>

            <div class="mdl-cell--3-col" style="margin: auto 0"
                ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'">
                Stummschaltung:
            </div>
            <md-switch class="mdl-cell--1-col" ng-model="device.isMuted"
                       aria-label="deviceMute" ng-change="setMuteState(device)"
                       ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'"
                       ng-true-value="1" ng-false-value="0" style="margin-right: 0"
                       ng-disabled="device.manualOperation == 0">
            </md-switch>
            <div class="mdl-cell--8-col"
                ng-if="device.betrieb!='deaktiviert' && device.betrieb!='nichtverbunden'"></div>

            <div class="mdl-cell--12-col" ng-if="device.betrieb=='deaktiviert'">
                Für die Stummschaltung muss der AMP aktiviert sein.
            </div>
            <div class="mdl-cell--12-col" ng-if="device.betrieb=='nichtverbunden'">
                Für die Stummschaltung muss der AMP verbunden sein.
            </div>
        </div>
    </div>
    <div class="mdl-card__supporting-text mdl-grid" ng-if="systemCoding < 2">
        <p>InnoServer und InnoRack V1 verfügen nicht über die Option zur manuellen Stummschaltung.</p>
    </div>
    <div class="mdl-card__actions mdl-card--border" ng-if="systemCoding >= 2">
        <button ng-click="getAllMuteStates()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Aktualisieren
        </button>
    </div>
</div>
