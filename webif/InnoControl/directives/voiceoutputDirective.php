<?php
if (strpos(shell_exec("uname -r"), "4.4.73-rockchip")) {
    $tinkerboard = true;
}
?>
<div class="mdl-grid" ng-repeat="dev in rssvoice.vol_dev">
    <!-- Normalbetrieb -->
    <md-input-container ng-if="devices[dev.id].betrieb=='normalbetrieb'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev01\" ng-if='dev.id==0'>HDMI-Audio: </label>
                  <label id=\"dev01\" ng-if='dev.id!=0'>InnoAmp {{formatId(dev.id)}} ({{devices[dev.id].name}}): </label>";
        } else{
            echo "<label id=\"dev01\">InnoAmp {{formatId(dev.id +1)}} ({{devices[dev.id].name}}): </label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volume" min="0" max="100">
    </md-input-container>

    <!-- Deaktiviert -->
    <md-input-container ng-if="devices[dev.id].betrieb=='deaktiviert'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev01\" ng-if='dev.id==0'>HDMI-Audio: </label>
                  <label id=\"dev01\" ng-if='dev.id!=0'>InnoAmp {{formatId(dev.id)}} ({{devices[dev.id].name}}): </label>";
        } else{
            echo "<label id=\"dev01\">InnoAmp {{formatId(dev.id +1)}} ({{devices[dev.id].name}}): </label>";
        }
        ?>
        <input disabled aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volume" min="0" max="100">
    </md-input-container>

    <!-- Geteilter Betrieb-->
    <md-input-container ng-if="devices[dev.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev01\" ng-if='dev.id==0'>HDMI-Audio Links: </label>
                  <label id=\"dev01\" ng-if='dev.id!=0'>InnoAmp {{formatId(dev.id)}} ({{devices[dev.id].nameL}}): </label>";
        } else{
            echo "<label id=\"dev01\">InnoAmp {{formatId(dev.id +1)}} ({{devices[dev.id].nameL}}): </label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volumeL" min="0" max="100">
    </md-input-container>
    <md-input-container ng-if="devices[dev.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev01\" ng-if='dev.id==0'>HDMI-Audio Rechts: </label>
                  <label id=\"dev01\" ng-if='dev.id!=0'>InnoAmp {{formatId(dev.id)}} ({{devices[dev.id].nameR}}): </label>";
        } else{
            echo "<label id=\"dev01\">InnoAmp {{formatId(dev.id +1)}} ({{devices[dev.id].nameR}}): </label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volumeR" min="0" max="100">
    </md-input-container>
</div>
