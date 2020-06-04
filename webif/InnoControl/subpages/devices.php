<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */
$actual_kernel = (shell_exec("uname -r"));
$tb_kernel  = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}
?>

<div ng-init="selectDevice()" class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Geräte</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <devices></devices>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <button ng-style="audioConfChanged && {'color':'#ff362f'}" ng-click="genAudioConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Audio Konfiguration erzeugen
        </button>
        <button ng-style="playerConfChanged && {'color':'#ff362f'}" ng-click="genPlayerConf($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            Player Konfiguration erzeugen
        </button>
        <button ng-click="resetMapping($event)"
                class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
                style="color: rgb(255, 54, 47);">
            USB-Geräte-Zuweisung zurücksetzen
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
                           ng-change="setAudioConfiguration();selectDeviceDefaultValues();"
                           class="mdl-cell--5-col md-no-underline" style="color: #545454">
                    <md-option value="deaktiviert">Deaktiviert</md-option>
                    <md-option value="normalbetrieb">Normalbetrieb</md-option>
                    <md-option value="geteilterbetrieb">Geteilter Betrieb</md-option>
                </md-select>
            </div>
            <div class="mdl-grid">
              <h5 class="mdl-cell--6-col" style="margin-top: 12px">
                  Audiokanal durchgehend offen halten:&nbsp;
              </h5>
              <div class="mdl-cell--1-col">
                  <md-switch class="mdl-cell" ng-model="selectedDevice.oac" aria-label="oac"
                             ng-true-value="1" ng-false-value="0" ng-change="selectedDevice.changed=true">
                  </md-switch>
              </div>
              <div class="mdl-cell--1-col" style="margin: 8px">
                    <div id="ttoac" class="icon material-icons">info
                        <md-tooltip for="ttoac">
                            Wenn auf den Lautsprechern ein Rauschen vorhanden ist kann der Audiokanal geschlossen werden,<br>
                            um dies zu verhindern.<br>
                            Bei geschlossenem Audiokanal kann es aber zu einem Einschaltknacksen kommen.<br><br>
                            Das Rauschen/Einschaltknacksen ist von den Gegebenheiten abhängig.<br>
                            Hier sind Faktoren wie Lautsprechermodell, Lautsprecherkabel und Länge ausschlaggebend.<br><br>
                            Standard: Ein
                      </md-tooltip>
                  </div>
              </div>
            </div>
            <div class="mdl-grid" ng-if="selectedDevice.betrieb=='normalbetrieb'">
              <h5 class="mdl-cell--6-col" style="margin-top: 12px">
                  Stereo-Signal zu Mono konvertieren:&nbsp;
              </h5>
              <div class="mdl-cell--1-col">
                  <md-switch class="mdl-cell" ng-model="selectedDevice.stm" aria-label="stm"
                             ng-true-value="1" ng-false-value="0" ng-change="setAudioConfiguration();selectedDevice.changed=true">
                  </md-switch>
              </div>
              <div class="mdl-cell--1-col" style="margin: 8px">
                  <div id="ttstm" class="icon material-icons">info
                      <md-tooltip for="ttstm">
                          Mit dieser Option wird das Signal von Stereo zu Mono konvertiert und dann auf beiden Lautsprechern ausgegeben.<br><br>
                          Z.B.: bei einem Bad (linker LS) mit Sauna (rechter LS) soll es eine Zone sein, jedoch würde die Abtrennung<br>
                          der LS von der Saunatür den Stereo-Sound zu sehr abschwächen.<br>
                          Hier ist es besser auf beiden die Musik Mono zu wiedergeben.<br><br>

                          Bei sehr großen Räumen die eine Zone darstellen, kann diese Option auch von nutzen sein.<br><br>
                          Standard: Aus
                    </md-tooltip>
                </div>
              </div>
            </div>
            <hr>
            <!-- Normalbetrieb -->
            <div id="normalbetrieb" ng-if="selectedDevice.betrieb=='normalbetrieb'">
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--2-col">Name</h6>
                    <md-input-container class="md-block mdl-cell">
                        <input aria-label="name" name="name" ng-model="selectedDevice.name" ng-change="selectedDevice.changed=true">
                    </md-input-container>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttname" class="icon material-icons">info
                            <md-tooltip for="ttname">
                                Dieser Name wird für die Zone in unserer App, Spotify, Airplay usw. angezeigt.
                            </md-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--2-col">Mac</h6>
                    <md-input-container class="md-block mdl-cell">
                        <input placeholder="00:00:00:00:00:xx" aria-label="mac" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.mac"  ng-change="selectedDevice.changed=true"
                               ng-disabled="!editMacs">
                    </md-input-container>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttmac" class="icon material-icons">info
                              <md-tooltip for="ttmac">
                                  Die Mac-Adresse dient zur eindeutigen Identifikation der Zone.<br>
                                  Es dürfen keine Mac-Adressen doppelt vergeben werden!<br><br>
                                  Mit der Option "Benutzerdefinierte Mac-Adresse" können Sie den Standardwert überschreiben.<br>
                                  Beispiel: 00:00:00:00:00:XX
                            </md-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                  <md-checkbox ng-model="editMacs"
                    aria-label="Benutzerdefinierte Mac-Adresse">
                    Benutzerdefinierte Mac-Adresse
                  </md-checkbox>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--5-col">Shairplay</h6>
                    <md-switch aria-label="airplay" class="mdl-cell" ng-model="selectedDevice.airplay" ng-true-value="1"
                               ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttshair" class="icon material-icons">info
                              <md-tooltip for="ttshair">
                                  Hier können Sie Airplay für die Zone aktivieren.<br>
                                  Standard: Aus
                            </md-tooltip>
                        </div>
                    </div>
                    <div class="mdl-layout-spacer"></div>
                </div>
                <div class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--5-col">Spotify Connect</h6>
                    <md-switch aria-label="airplay" class="mdl-cell" ng-model="selectedDevice.spotify" ng-true-value="1"
                               ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttspot" class="icon material-icons">info
                              <md-tooltip for="ttspot">
                                  Hier können Sie Spotify für die Zone aktivieren.<br>
                                  Standard: Aus<br><br>
                                  Für Spotify wird ein Premium-Account benötigt!
                            </md-tooltip>
                        </div>
                    </div>
                    <div class="mdl-layout-spacer"></div>
                </div>
            </div>

            <!-- Geteilter Betrieb -->
            <div id="normalbetrieb" ng-if="selectedDevice.betrieb=='geteilterbetrieb'">
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Name Links</p>
                    <md-input-container class="md-block mdl-cell--3-col">
                        <input aria-label="nameL" name="name" ng-model="selectedDevice.nameL" ng-change="selectedDevice.changed=true">
                    </md-input-container>
                    <p class="mdl-cell mdl-cell--2-col">Name Rechts</p>
                    <md-input-container class="md-block mdl-cell--3-col">
                        <input aria-label="nameR" name="name" ng-model="selectedDevice.nameR" ng-change="selectedDevice.changed=true">
                    </md-input-container>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttname2" class="icon material-icons">info
                            <md-tooltip for="ttname2">
                                Diese Namen werden für die jeweilige Zone in unserer App, Spotify, Airplay usw. angezeigt.
                            </md-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Mac Links</p>
                    <md-input-container class="md-block mdl-cell--3-col">
                        <input placeholder="00:00:00:00:00:xx" aria-label="macL" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.macL" ng-change="selectedDevice.changed=true"
                               ng-disabled="!editMacs">
                    </md-input-container>
                    <p class="mdl-cell mdl-cell--2-col">Mac Rechts</p>
                    <md-input-container class="md-block mdl-cell--3-col">
                        <input placeholder="00:00:00:00:00:xx" aria-label="macR" name="mac"
                               pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                               ng-model="selectedDevice.macR" ng-change="selectedDevice.changed=true"
                               ng-disabled="!editMacs">
                    </md-input-container>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttmac2" class="icon material-icons">info
                              <md-tooltip for="ttmac2">
                                  Die Mac-Adresse dient zur eindeutigen Identifikation der Zone.<br>
                                  Es dürfen keine Mac-Adressen doppelt vergeben werden!<br><br>
                                  Mit der Option "Benutzerdefinierte Mac-Adresse" können Sie den Standardwert überschreiben.<br>
                                  Beispiel: 00:00:00:00:YY:XX
                            </md-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                  <md-checkbox ng-model="editMacs"
                    aria-label="Benutzerdefinierte Mac-Adresse">
                    Benutzerdefinierte Mac-Adresse
                  </md-checkbox>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Shairplay Links</p>
                    <md-switch class="mdl-cell--3-col" ng-model="selectedDevice.airplayL" aria-label="airplayL"
                               ng-true-value="1" ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <p class="mdl-cell mdl-cell--2-col">Shairplay Rechts</p>
                    <md-switch class="mdl-cell--3-col" ng-model="selectedDevice.airplayR" aria-label="airplayR"
                               ng-true-value="1" ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttshair2" class="icon material-icons">info
                              <md-tooltip for="ttshair2">
                                  Hier können Sie Airplay für die Zonen aktivieren.<br>
                                  Standard: Aus
                            </md-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                    <p class="mdl-cell mdl-cell--2-col">Spotify Connect Links</p>
                    <md-switch class="mdl-cell--3-col" ng-model="selectedDevice.spotifyL" aria-label="airplayL"
                               ng-true-value="1" ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <p class="mdl-cell mdl-cell--2-col">Spotify Connect Rechts</p>
                    <md-switch class="mdl-cell--3-col" ng-model="selectedDevice.spotifyR" aria-label="airplayR"
                               ng-true-value="1" ng-false-value="0" ng-change="selectedDevice.changed=true">
                    </md-switch>
                    <div class="mdl-cell--1-col" style="margin: 8px">
                          <div id="ttspot2" class="icon material-icons">info
                              <md-tooltip for="ttspot2">
                                  Hier können Sie Spotify für die Zonen aktivieren.<br>
                                  Standard: Aus<br><br>
                                  Für Spotify wird ein Premium-Account benötigt!
                            </md-tooltip>
                        </div>
                    </div>
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
        <button ng-style="selectedDevice.changed && {'color':'#ff362f'}" ng-click="saveDevice()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
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
        <button ng-if="selectedDevice" ng-click="restoreDefault()"
                class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">settings_backup_restore</i>
            <md-tooltip md-direction="bottom">Werte auf Standard zurücksetzen</md-tooltip>
        </button>
    </div>
</div>
