<?php
$actual_kernel = (shell_exec("uname -r"));
$tb_kernel  = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}
?>
<div ng-init="sortDevicesList()"
     ng-repeat="vol in selectedPlaylist.vol_dev"
     ng-if="devices[vol.id]!=null"
     class="mdl-grid">
    <!-- Normalbetrieb-->
    <md-input-container ng-if="devices[vol.id].betrieb=='normalbetrieb'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} ({{devices[vol.id].name}}):</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} ({{devices[vol.id].name}}):</label>";
        }
        ?>

        <input aria-label="dev01" type="number" step="5" name="dev01"
               ng-model="vol.volume" min="0" max="100" ng-change="selectedPlaylist.volchanged=true">
    </md-input-container>

    <!-- Deaktiviert -->
    <md-input-container ng-if="devices[vol.id].betrieb=='deaktiviert'"
                        class="md-block playlstvol mdl-cell--12-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} ({{devices[vol.id].name}}):</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} ({{devices[vol.id].name}}):</label>";
        }
        ?>
        <input disabled aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volume" min="0" max="100"
               ng-change="selectedPlaylist.volchanged=true">
    </md-input-container>

    <!-- Geteilter Betrieb -->
    <md-input-container ng-if="devices[vol.id].betrieb==='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} ({{devices[vol.id].nameL}}):</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio Links:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} ({{devices[vol.id].nameL}}):</label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volumeL" min="0" max="100"
               ng-change="selectedPlaylist.volchanged=true">
    </md-input-container>
    <md-input-container ng-if="devices[vol.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <?php
        if($tinkerboard){
            echo "<label id=\"dev0{{vol.id}}\" ng-if=\"vol.id!=0\">InnoAmp {{formatId(vol.id)}} ({{devices[vol.id].nameR}}):</label>
        <label id=\"dev0{{vol.id}}\" ng-if=\"vol.id==0\">HDMI-Audio Rechts:</label>";
        } else{
            echo "<label id=\"dev0{{vol.id}}\">InnoAmp {{formatId(vol.id+1)}} ({{devices[vol.id].nameR}}):</label>";
        }
        ?>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="vol.volumeR" min="0" max="100"
               ng-change="selectedPlaylist.volchanged=true">
    </md-input-container>
</div>
