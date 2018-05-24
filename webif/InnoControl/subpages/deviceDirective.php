<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 09.09.2016
 * Time: 13:32
 */?>

<md-list flex="">
    <md-list-item ng-repeat="device in devices" class="md-2-line" ng-click="selectDevice(device.id)">
        <img ng-src="./images/{{device.betrieb}}.png" class="md-avatar" style="border-radius: 0px;">
        <div class="md-list-item-text">
            <h3>Usb Gerät {{device.id}}</h3>
            <p ng-if="device.betrieb=='normalbetrieb'">{{device.name}}</p>
            <p ng-if="device.betrieb=='geteilterbetrieb'">{{device.nameR}} - {{device.nameL}}</p>
            <p ng-if="device.betrieb=='deaktiviert'">Deaktiviert</p>
            <p ng-if="device.betrieb=='nichtverbunden'">Nicht verfügbar</p>
            <p>{{device.display}}</p>
        </div>
    </md-list-item>
</md-list>
