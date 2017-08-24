<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */ ?>

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
        <h2 class="mdl-card__title-text">Usb-Gerät {{selectedDevice.id}}</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div ng-if="!selectedDevice" class="mdl-grid">
            <h2>Wählen sie ein Gerät aus!</h2>
        </div>

        <div ng-if="selectedDevice">
            <div class="mdl-grid">
                <p class="mdl-cell mdl-cell--3-col">Wiedergabe von Line-In:</p>
                <md-input-container class="mdl-cell--3-col md-no-underline">
                    <md-select ng-model="selectedLineIn" placeholder="Input auswahlen...">
                        <md-option ng-value="opt.id" ng-repeat="opt in devices">Usb Gerät {{opt.id}}</md-option>
                    </md-select>
                </md-input-container>
            </div>
            <button ng-if="selectedLineIn" ng-click="playlinein(selectedLineIn,selectedDevice.id)"
                    class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons md-48">play_arrow</i>
            </button>
            <button ng-click="stoplinein(selectedLineIn)"
                    class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons md-48 md-inactive">stop</i>
            </button>
        </div>
    </div>
    <div ng-if="selectedDevice" class="mdl-card__actions mdl-card--border">

        <div ng-if="selectedDevice.lineinStatus">
            Status: Play von Usb-Gerät {{selectedDevice.lineinStatus}}
        </div>
        <div ng-if="!selectedDevice.lineinStatus">
            Status: Stop
        </div>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Beispiel Loxone Virtueller Ausgangsverbinder:</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <table>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone02:</b></td>
                <td style="padding:0 50px 0 50px;"></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?setlinein&card_in=01&card_out=02</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone01:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?setlinein&card_in=01&card_out=01</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 Stop:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?setlinein&card_out=01</td>
            </tr>
            <tr>
                <td><b>Lautstärke von Line-In Zone01:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?card_out=01&volume=V</td>
            </tr>
        </table>
    </div>
</div>
