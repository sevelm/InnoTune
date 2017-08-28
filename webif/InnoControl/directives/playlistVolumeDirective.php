<?php
if (strpos(shell_exec("uname -r"), "rockchip")) {
    $tinkerboard = true;
}
?>
<div ng-repeat="vol in selectedPlaylist.vol_dev" ng-if="devices[vol.id]!=null" class="mdl-grid">
    <!-- Normalbetrieb-->
    <md-input-container ng-if="devices[vol.id].betrieb=='normalbetrieb'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}}:</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}}:</label>";
        }
        ?>

        <input aria-label="dev01" type="number" step="5" name="dev01"
               ng-model="vol.volume" min="0" max="100">
    </md-input-container>

    <!-- Kein Gerät verfügbar
    <md-input-container ng-if="devices[vol.id]==null" class="md-block playlstvol mdl-cell--12-col">
        <label id="dev0{{vol.id}}">USB-Gerät {{vol.id+1}}:</label>
        <input aria-label="dev01" type="number" step="5" name="dev01"
               ng-model="vol.volume" min="0" max="100">
    </md-input-container> -->

    <!-- Deaktiviert -->
    <md-input-container ng-if="devices[vol.id].betrieb=='deaktiviert'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}}:</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}}:</label>";
        }
        ?>
        <input disabled aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volume" min="0" max="100">
    </md-input-container>

    <!-- Geteilter Betrieb -->
    <md-input-container ng-if="devices[vol.id].betrieb==='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} Links:</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio Links:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} Links:</label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volumeL" min="0" max="100">
    </md-input-container>
    <md-input-container ng-if="devices[vol.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} Rechts:</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio Rechts:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} Rechts:</label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volumeR" min="0" max="100">
    </md-input-container>
</div>