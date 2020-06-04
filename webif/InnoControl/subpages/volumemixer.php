<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */
$actual_kernel = (shell_exec("uname -r"));
$tb_kernel = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}
$needsConfig = intval(shell_exec("cat /etc/asound.conf | grep plug:equal | wc -l"));
?>


<div ng-init="selectDevice()" class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Geräte</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <devices></devices>
    </div>
</div>

<div class="mdl-cell--8-col">
    <div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
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
        <div class="mdl-card__supporting-text md-padding">
            <div ng-if="!selectedDevice" class="mdl-grid">
                <h2>Wählen sie ein Gerät aus!</h2>
            </div>

            <div ng-if="selectedDevice">
                <div ng-if="!selectedDevice.offline && selectedDevice.betrieb!='deaktiviert' && selectedDevice.betrieb!=='gekoppelt'">
                    <div id="mpd_vol" class="mdl-grid">
                        <h6 class="mdl-cell mdl-cell--3-col">Player Zentral (MPD)</h6>
                        <md-slider-container class="mdl-cell mdl-cell--9-col">
                            <md-slider flex="" ng-change="changeVol('mpd')" md-discrete="" min="0" max="10" step="1"
                                       ng-model="selectedDevice.vol.mpd"
                                       id="red-slider" aria-label="mpd">
                            </md-slider>
                            <md-input-container>
                                <input flex="" type="number" ng-change="changeVol('mpd')"
                                       ng-model="selectedDevice.vol.mpd"
                                       aria-controls="red-slider" aria-label="mpd">
                            </md-input-container>
                        </md-slider-container>
                    </div>
                    <div id="squeezebox_vol" class="mdl-grid">
                        <h6 class="mdl-cell mdl-cell--3-col">Squeezebox</h6>
                        <md-slider-container class="mdl-cell mdl-cell--9-col">
                            <md-slider flex="" ng-change="changeVol('squeeze')" min="0" md-discrete="" max="10" step="1"
                                       ng-model="selectedDevice.vol.squeezebox"
                                       id="red-slider" aria-label="squeeze">
                            </md-slider>
                            <md-input-container>
                                <input flex="" type="number" ng-change="changeVol('squeeze')"
                                       ng-model="selectedDevice.vol.squeezebox"
                                       aria-controls="red-slider" aria-label="squeeze">
                            </md-input-container>
                        </md-slider-container>
                    </div>
                    <div id="airplay_vol" class="mdl-grid">
                        <h6 class="mdl-cell mdl-cell--3-col">Airplay & Spotify</h6>
                        <md-slider-container class="mdl-cell mdl-cell--9-col">
                            <md-slider flex="" ng-change="changeVol('airplay')" md-discrete="" min="0" max="10" step="1"
                                       ng-model="selectedDevice.vol.airplay"
                                       id="red-slider" aria-label="airplay">
                            </md-slider>
                            <md-input-container>
                                <input flex="" type="number" ng-change="changeVol('airplay')"
                                       ng-model="selectedDevice.vol.airplay"
                                       aria-controls="red-slider" aria-label="airplay">
                            </md-input-container>
                        </md-slider-container>
                    </div>
                    <div id="linein_vol" class="mdl-grid">
                        <h6 class="mdl-cell mdl-cell--3-col">Line-In</h6>
                        <md-slider-container class="mdl-cell mdl-cell--9-col">
                            <md-slider flex="" ng-change="changeVol('LineIn')" md-discrete="" min="0" max="10" step="1"
                                       ng-model="selectedDevice.vol.linein"
                                       id="red-slider" aria-label="linein">
                            </md-slider>
                            <md-input-container>
                                <input flex="" type="number" ng-change="changeVol('LineIn')"
                                       ng-model="selectedDevice.vol.linein"
                                       aria-controls="red-slider" aria-label="linein">
                            </md-input-container>
                        </md-slider-container>
                    </div>
                </div>

                <!-- Slider deaktivieren wenn der betrieb auch deaktiviert oder gekoppelt ist -->
                <div ng-if="selectedDevice.offline || selectedDevice.betrieb=='deaktiviert' || selectedDevice.betrieb=='gekoppelt'">
                    <div id="mpd_vol" class="mdl-grid">
                        <h6 class="mdl-cell mdl-cell--3-col">Player Zentral (MPD)</h6>
                        <md-slider-container class="mdl-cell mdl-cell--9-col">
                            <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.vol.mpd"
                                       id="red-slider" aria-label="mpd">
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
                <md-tooltip md-direction="bottom">Mute</md-tooltip>
            </button>
            <button ng-if="selectedDevice"
                    class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    Hier können Sie die Master-Lautstärke für die einzelnen Wiedergabeformate wählen.<br><br>
                    Bei "Player Zentral" und "Line-In" gibt es nur die Master-Lautstärke, hingegen bei<br>
                    "Squeezebox" und "Airplay & Spotify" kann in der jeweiligen App noch eine Lautstärke<br>
                    eingestellt werden.<br><br>
                    Bei geteiltem Betrieb teilen sich beide Zonen die gleichen Master-Lautstärken.
                </md-tooltip>
            </button>
        </div>
    </div>

    <div class="mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--4-col"></div>
    <div ng-if="selectedDevice" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Equalizer</h2>
        </div>

        <div ng-if="selectedDevice.betrieb!='deaktiviert' && selectedDevice.betrieb!=='gekoppelt'">
            <div id="mpd_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Tiefen</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" ng-change="changeEq('low')" md-discrete="" min="0" max="10" step="1"
                               ng-model="selectedDevice.eq.low"
                               id="red-slider" aria-label="low">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" ng-change="changeEq('low')" ng-model="selectedDevice.eq.low"
                               aria-controls="red-slider" aria-label="low">
                    </md-input-container>
                </md-slider-container>
            </div>
            <div id="squeezebox_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Mitten</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" ng-change="changeEq('mid')" min="0" md-discrete="" max="10" step="1"
                               ng-model="selectedDevice.eq.mid"
                               id="red-slider" aria-label="mid">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" ng-change="changeEq('mid')" ng-model="selectedDevice.eq.mid"
                               aria-controls="red-slider" aria-label="mid">
                    </md-input-container>
                </md-slider-container>
            </div>
            <div id="airplay_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Höhen</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" ng-change="changeEq('high')" md-discrete="" min="0" max="10" step="1"
                               ng-model="selectedDevice.eq.high"
                               id="red-slider" aria-label="high">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" ng-change="changeEq('high')" ng-model="selectedDevice.eq.high"
                               aria-controls="red-slider" aria-label="high">
                    </md-input-container>
                </md-slider-container>
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <button ng-click="resetEqSettings()"
                        class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                    Zurücksetzen
                </button>
                <?php
                if ($needsConfig == 0) {
                    echo '<a href="#devices" style="color:#ff362f"
                  class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
              Audiokonfiguration muss erzeugt werden!
          </a>';
                } ?>
            </div>
        </div>

        <!-- Slider deaktivieren wenn der betrieb auch deaktiviert oder gekoppelt ist -->
        <div ng-if="selectedDevice.betrieb=='deaktiviert' || selectedDevice.betrieb=='gekoppelt'">
            <div id="mpd_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Tiefen</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.eq.low" id="red-slider"
                               aria-label="low">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" disabled ng-model="selectedDevice.eq.low"
                               aria-controls="red-slider" aria-label="low">
                    </md-input-container>
                </md-slider-container>
            </div>
            <div id="squeezebox_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Mitten</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.eq.mid"
                               id="red-slider" aria-label="mid">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" disabled ng-model="selectedDevice.eq.mid"
                               aria-controls="red-slider" aria-label="mid">
                    </md-input-container>
                </md-slider-container>
            </div>
            <div id="airplay_vol" class="mdl-grid">
                <h6 class="mdl-cell mdl-cell--3-col">Höhen</h6>
                <md-slider-container class="mdl-cell mdl-cell--9-col">
                    <md-slider flex="" min="0" max="10" disabled ng-model="selectedDevice.eq.high"
                               id="red-slider" aria-label="high">
                    </md-slider>
                    <md-input-container>
                        <input flex="" type="number" disabled ng-model="selectedDevice.eq.high"
                               aria-controls="red-slider" aria-label="high">
                    </md-input-container>
                </md-slider-container>
            </div>
        </div>

        <div class="mdl-card__menu">
            <button ng-if="selectedDevice"
                    class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">info</i>
                <md-tooltip md-direction="bottom">
                    Mit dem 3-Band Equalizer können die Frequenzen an die Raumverhältnisse angepasst werden.<br>
                    Standard: 7<br><br>
                    Achtung: Wenn Sie alle Frequenzen erhöhen bzw. verringern hat das Auswirkungen auf die Gesamtlautstärke!
                </md-tooltip>
            </button>
        </div>
    </div>
</div>
