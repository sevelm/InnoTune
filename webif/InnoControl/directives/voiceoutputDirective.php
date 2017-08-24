<div class="mdl-grid" ng-repeat="dev in rssvoice.vol_dev">
    <!-- Normalbetrieb -->
    <md-input-container ng-if="devices[dev.id].betrieb=='normalbetrieb'"
                        class="md-block playlstvol mdl-cell--12-col">
        <label id="dev01">USB-Gerät {{dev.id + 1}}: </label>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volume" min="0" max="100">
    </md-input-container>

    <!-- Deaktiviert -->
    <md-input-container ng-if="devices[dev.id].betrieb=='deaktiviert'"
                        class="md-block playlstvol mdl-cell--12-col">
        <label id="dev01">USB-Gerät {{dev.id + 1}}: </label>
        <input disabled aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volume" min="0" max="100">
    </md-input-container>

    <!-- Geteilter Betrieb-->
    <md-input-container ng-if="devices[dev.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <label id="dev01">USB-Gerät {{dev.id + 1}} Links: </label>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volumeL" min="0" max="100">
    </md-input-container>
    <md-input-container ng-if="devices[dev.id].betrieb=='geteilterbetrieb'"
                        class="md-block playlstvol mdl-cell--6-col">
        <label id="dev01">USB-Gerät {{dev.id + 1}} Rechts: </label>
        <input aria-label="dev01" type="number" step="5"
               name="dev01"
               ng-model="dev.volumeR" min="0" max="100">
    </md-input-container>
</div>
