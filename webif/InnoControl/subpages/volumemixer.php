<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */
if (strpos(shell_exec("uname -r"), "rockchip")) {
    $tinkerboard = true;
}?>


<div ng-init="selectDevice()" class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Geräte</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <devices></devices>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--8-col">
    <div class="mdl-card__title">
        <?php if($tinkerboard){
            echo "<h2 ng-if=\"selectedDevice.id==1\" class=\"mdl-card__title-text\">HDMI-Audio</h2>".
                "<h2 ng-if=\"selectedDevice.id!=1&&selectedDevice!=null\" class=\"mdl-card__title-text\">InnoAmp {{formatId(selectedDevice.id-1)}}</h2>".
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        }else{
            echo "<h2 class=\"mdl-card__title-text\" ng-if=\"selectedDevice!=null\">InnoAmp {{formatId(selectedDevice.id)}}</h2>".
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        }?>
    </div>
    <div class="mdl-card__supporting-text md-padding">
        <div ng-if="!selectedDevice" class="mdl-grid">
            <h2>Wählen sie ein Gerät aus!</h2>
        </div>

        <div ng-if="selectedDevice">
            <div ng-if="selectedDevice.betrieb!='deaktiviert'">
                <div id="mpd_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Player Zentral (MPD)</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" ng-change="changeVol('mpd')" md-discrete=""  min="0" max="10" step="1" ng-model="selectedDevice.vol.mpd"
                                   id="red-slider" aria-label="mpd">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" ng-change="changeVol('mpd')" ng-model="selectedDevice.vol.mpd"
                                   aria-controls="red-slider" aria-label="mpd">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="squeezebox_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Squeezebox</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" ng-change="changeVol('squeeze')" min="0" md-discrete=""  max="10" step="1" ng-model="selectedDevice.vol.squeezebox"
                                   id="red-slider" aria-label="squeeze">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" ng-change="changeVol('squeeze')" ng-model="selectedDevice.vol.squeezebox"
                                   aria-controls="red-slider" aria-label="squeeze">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="airplay_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Airplay & Spotify</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" ng-change="changeVol('airplay')"  md-discrete=""  min="0" max="10" step="1" ng-model="selectedDevice.vol.airplay"
                                   id="red-slider" aria-label="airplay">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" ng-change="changeVol('airplay')" ng-model="selectedDevice.vol.airplay"
                                   aria-controls="red-slider" aria-label="airplay">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="linein_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Line-In</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" ng-change="changeVol('LineIn')"  md-discrete=""  min="0" max="10" step="1" ng-model="selectedDevice.vol.linein"
                                   id="red-slider" aria-label="linein">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" ng-change="changeVol('LineIn')" ng-model="selectedDevice.vol.linein"
                                   aria-controls="red-slider" aria-label="linein">
                        </md-input-container>
                    </md-slider-container>
                </div>
            </div>

            <!-- Slider deaktivieren wenn der betrieb auch deaktiviert ist -->
            <div ng-if="selectedDevice.betrieb=='deaktiviert'">
                <div id="mpd_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Player Zentral (MPD)</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" min="0" max="10"  disabled ng-model="selectedDevice.vol.mpd" id="red-slider" aria-label="mpd">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" disabled ng-model="selectedDevice.vol.mpd"
                                   aria-controls="red-slider" aria-label="mpd">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="squeezebox_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Squeezebox</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.vol.squeezebox"
                                   id="red-slider" aria-label="squeeze">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" disabled ng-model="selectedDevice.vol.squeezebox"
                                   aria-controls="red-slider" aria-label="squeeze">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="airplay_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Airplay + Spotify</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.vol.airplay"
                                   id="red-slider" aria-label="airplay">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" disabled ng-model="selectedDevice.vol.airplay"
                                   aria-controls="red-slider" aria-label="airplay">
                        </md-input-container>
                    </md-slider-container>
                </div>
                <div id="linein_vol" class="mdl-grid">
                    <h6 class="mdl-cell mdl-cell--3-col">Line-In</h6>
                    <md-slider-container class="mdl-cell mdl-cell--9-col">
                        <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.vol.linein"
                                   id="red-slider" aria-label="linein">
                        </md-slider>
                        <md-input-container>
                            <input flex="" type="number" disabled ng-model="selectedDevice.vol.linein"
                                   aria-controls="red-slider" aria-label="linein">
                        </md-input-container>
                    </md-slider-container>
                </div>
            </div>
        </div>
    </div>
    <div class="mdl-card__menu">
        <button ng-if="selectedDevice" ng-click="muteAmp(selectedDevice.id)"
                class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">volume_off</i>
        </button>
    </div>
</div>

