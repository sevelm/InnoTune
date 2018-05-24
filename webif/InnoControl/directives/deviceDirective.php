<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 09.09.2016
 * Time: 13:32
 */
$actual_kernel = (shell_exec("uname -r"));
$tb_kernel  = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}
?>

<md-list flex="" ng-init="getDevices()">
    <md-list-item ng-repeat="device in devices | orderBy : 'id'" class="md-2-line" ng-click="selectDevice(device.id)">
        <img ng-src="./images/{{device.betrieb}}.png" class="md-avatar" style="border-radius: 0;">
        <div class="md-list-item-text">
            <?php
            if($tinkerboard) {
                echo "<h3 ng-if=\"device.id==1\">HDMI-Audio</h3>
            <h3 ng-if=\"device.id!=1\">InnoAmp {{formatId(device.id - 1)}}</h3>";
            } else{
                echo "<h3>InnoAmp {{formatId(device.id)}}</h3>";
            }
            ?>

            <p ng-if="device.betrieb=='normalbetrieb'">{{device.name}}</p>
            <p ng-if="device.betrieb=='geteilterbetrieb'">{{device.nameL}} - {{device.nameR}}</p>
            <p ng-if="device.betrieb=='gekoppelt'">Gekoppelt mit <strong>{{devices[device.linktoDevice-1].name}}</strong></p>
            <p ng-if="device.betrieb=='deaktiviert'">Deaktiviert</p>
            <p ng-if="device.betrieb=='nichtverbunden'">Nicht verfügbar</p>
            <p>{{device.display}}</p>
        </div>
    </md-list-item>
</md-list>
