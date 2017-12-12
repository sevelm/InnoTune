<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */
if (strcmp(shell_exec("uname -r"), "4.4.73-rockchip") === 0) {
    $tinkerboard = true;
}
?>
<!--suppress CssUnusedSymbol -->
<style>
    .modal {
        position: fixed;
        display: none;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, .8) 50% 50% no-repeat;
    }
</style>

<div ng-init="selectDevice()" class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Geräte</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <devices></devices>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-if="audioConfChanged" style="color:#ff362f" ng-click="genAudioConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Audio Konfiguration erzeugen
        </button>
        <button ng-if="!audioConfChanged" ng-click="genAudioConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Audio Konfiguration erzeugen
        </button>
        <button ng-if="playerConfChanged" style="color:#ff362f" ng-click="genPlayerConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Player Konfiguration erzeugen
        </button>
        <button ng-if="!playerConfChanged" ng-click="genPlayerConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Player Konfiguration erzeugen
        </button>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--8-col">
    <div class="mdl-card__title">
        <?php if ($tinkerboard) {
            echo "<h2 ng-if=\"selectedDevice.id==1\" class=\"mdl-card__title-text\">HDMI-Audio</h2>" .
                "<h2 ng-if=\"selectedDevice.id!=1&&selectedDevice!=null\" class=\"mdl-card__title-text\">InnoAmp {{formatId(selectedDevice.id-1)}}</h2>" .
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        } else {
            echo "<h2 class=\"mdl-card__title-text\" ng-if=\"selectedDevice!=null\">InnoAmp {{formatId(selectedDevice.id)}}</h2>" .
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        } ?>
    </div>
    <div class="mdl-card__supporting-text">
        <div ng-if="selectedDevice && !selectedDevice.linktoDevice">
            <div class="mdl-grid">
                <h5 class="mdl-cell--3-col">Modus:&nbsp;</h5>
                <md-select placeholder="{{selectedDevice.betrieb}}" ng-model="selectedDevice.betrieb"
                           ng-change="setAudioConfiguration()"
                           class="mdl-cell--5-col md-no-underline" style="color: #545454">
                    <md-option value="deaktiviert">Deaktiviert</md-option>
                    <md-option value="normalbetrieb">Normalbetrieb</md-option>
                    <md-option value="geteilterbetrieb">Geteilter Betrieb</md-option>
                </md-select>
            </div>
            <hr>
            <!-- Normalbetrieb -->
            <div id="normalbetrieb" ng-if="selectedDevice.betrieb=='normalbetrieb'">
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--2-col">Name</h6>
                    <md-input-container class="md-block mdl-cell">
                        <input aria-label="name" name="name" ng-model="selectedDevice.name">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--2-col">Mac</h6>
                    <md-input-container class="md-block mdl-cell">
                        <input placeholder="00:00:00:00:00:xx" aria-label="mac" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.mac">
                    </md-input-container>
                    <div class="md-block mdl-cell">
                        <p>Mac Beispiel: 00:00:00:00:00:xx</p>
                    </div>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--5-col">Shairplay</h6>
                    <md-switch aria-label="airplay" class="mdl-cell" ng-model="selectedDevice.airplay" ng-true-value="1"
                               ng-false-value="0">
                    </md-switch>
                    <div class="mdl-layout-spacer"></div>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--5-col">Spotify Connect</h6>
                    <md-switch aria-label="airplay" class="mdl-cell" ng-model="selectedDevice.spotify" ng-true-value="1"
                               ng-false-value="0">
                    </md-switch>
                    <div class="mdl-layout-spacer"></div>
                </div>
            </div>

            <!-- Geteilter Betrieb -->
            <div id="normalbetrieb" ng-if="selectedDevice.betrieb=='geteilterbetrieb'">
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Name Links</p>
                    <md-input-container class="md-block mdl-cell">
                        <input aria-label="nameL" name="name" ng-model="selectedDevice.nameL">
                    </md-input-container>
                    <p class="mdl-cell mdl-cell--2-col">Name Rechts</p>
                    <md-input-container class="md-block mdl-cell">
                        <input aria-label="nameR" name="name" ng-model="selectedDevice.nameR">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Mac Links</p>
                    <md-input-container class="md-block mdl-cell">
                        <input placeholder="00:00:00:00:00:xx" aria-label="macL" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.macL">
                    </md-input-container>
                    <p class="mdl-cell mdl-cell--2-col">Mac Rechts</p>
                    <md-input-container class="md-block mdl-cell">
                        <input placeholder="00:00:00:00:00:xx" aria-label="macR" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.macR">
                    </md-input-container>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Shairplay Links</p>
                    <md-switch class="mdl-cell" ng-model="selectedDevice.airplayL" aria-label="airplayL"
                               ng-true-value="1" ng-false-value="0">
                    </md-switch>
                    <p class="mdl-cell mdl-cell--2-col">Shairplay Rechts</p>
                    <md-switch class="mdl-cell" ng-model="selectedDevice.airplayR" aria-label="airplayR"
                               ng-true-value="1" ng-false-value="0">
                    </md-switch>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Spotify Connect Links</p>
                    <md-switch class="mdl-cell" ng-model="selectedDevice.spotifyL" aria-label="airplayL"
                               ng-true-value="1" ng-false-value="0">
                    </md-switch>
                    <p class="mdl-cell mdl-cell--2-col">Spotify Connect Rechts</p>
                    <md-switch class="mdl-cell" ng-model="selectedDevice.spotifyR" aria-label="airplayR"
                               ng-true-value="1" ng-false-value="0">
                    </md-switch>
                </div>
            </div>
        </div>

        <div ng-if="!selectedDevice" class="mdl-grid">
            <h2>Wählen sie ein Gerät aus!</h2>
        </div>

        <!-- koppeln -->
        <div ng-if="selectedDevice.linktoDevice">
            <div class="mdl-grid" ng-if="selectedDevice.linktoDevice==true">

                <div class="mdl-grid">
                    <h5>Geräte zum Koppeln auswählen:&nbsp;</h5>
                    <md-select ng-model="selectedLink"
                               ng-change="selectedDevice.linktoDevice=selectedLink; setLinkConfiguration(selectedLink)"
                               placeholder="Gerät auswählen...">
                        <?php
                        if ($tinkerboard) {
                            echo "<md-option ng-hide=\"opt.id==selectedDevice.id || opt.betrieb=='deaktiviert' || opt.betrieb=='gekoppelt'\" ng-if=\"opt.id!=1\" ng-value=\"opt.id\" ng-repeat=\"opt in devices\">InnoAmp {{formatId(opt.id-1)}} ({{opt.name}})</md-option>";
                        } else {
                            echo "<md-option ng-hide=\"opt.id==selectedDevice.id || opt.betrieb=='deaktiviert' || opt.betrieb=='gekoppelt'\" ng-value=\"opt.id\" ng-repeat=\"opt in devices\">InnoAmp {{formatId(opt.id-1)}} ({{opt.name}})</md-option>";
                        } ?>
                    </md-select>
                </div>


            </div>
            <div class="mdl-grid" ng-if="selectedDevice.linktoDevice!=true">
                <h5>Dieses Gerät ist mit <strong><a href="" ng-click="selectDevice(selectedDevice.linktoDevice)">{{devices[selectedDevice.linktoDevice-1].name}}</a></strong>
                    gekoppelt.</h5>
            </div>
        </div>
    </div>
    <div ng-if="selectedDevice && !selectedDevice.linktoDevice" class="mdl-card__actions mdl-card--border">
        <button ng-click="saveDevice()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Speichern
        </button>
    </div>
    <div ng-if="selectedDevice && selectedDevice.linktoDevice" class="mdl-card__actions mdl-card--border">
        <md-button ng-click="setAudioConfigurationDeactivated()">Kopplung aufheben</md-button>
    </div>


    <div class="mdl-card__menu">
        <button disabled ng-if="selectedDevice" ng-click="selectedDevice.linktoDevice=true"
                class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">link</i>
            <md-tooltip md-direction="bottom">Mit anderem Gerät koppeln</md-tooltip>
        </button>
    </div>
</div>