<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */ ?>

<div ng-init="isfileuploaded()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">MPD Interface</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <iframe src="phpmpd/index.php" width="100%" height=350" style="width:100%;height:500px;" frameborder="0"
                scrolling="yes"></iframe>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <form enctype="multipart/form-data" method="POST" action="scripts/upload.php">
            <label class="mdl-button">
                <input name="userfile" id="userfile" type="file" ng-click="uploadfile=1" accept="audio/*"/>
                Musik auswählen ...
            </label>
            <span id="display-text"></span>
            <button ng-disabled="!uploadfile" type="submit" value="upload" name="music_upload"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Musik Hochladen
            </button>
        </form>
    </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Playlist Wiedergabe</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <playlists ng-init="getPlaylists()"></playlists>
    </div>
    <div class="mdl-card__menu">
        <button ng-click="addPlaylist()" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">add</i>
        </button>
    </div>
</div>
<div ng-init="selectPlaylist()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--2-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 ng-if="selectedPlaylist" class="mdl-card__title-text">Playlist {{selectedPlaylist.id+1}}  {{selectedPlaylist.name}}</h2>
        <h2 ng-if="!selectedPlaylist" class="mdl-card__title-text">Playlist</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div ng-if="!selectedPlaylist">
            <h5>Playlist auswählen ... </h5>
        </div>
        <div ng-if="selectedPlaylist">
            <md-input-container class="md-block playlstvol">
                <label id="background">Hintergrundmusik</label>
                <input aria-label="background" type="number" step="any" name="background"
                       ng-model="selectedPlaylist.vol_background" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev01">USB-Gerät 1:</label>
                <input aria-label="dev01" type="number" step="5" name="dev01"
                       ng-model="selectedPlaylist.vol_dev01" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev02">USB-Gerät 2:</label>
                <input aria-label="dev02" type="number" step="5" name="dev02"
                       ng-model="selectedPlaylist.vol_dev02" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label for="dev03" id="dev03">USB-Gerät 3:</label>
                <input aria-label="dev03" type="number" step="5" name="dev03"
                       ng-model="selectedPlaylist.vol_dev03" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev04">USB-Gerät 4:</label>
                <input aria-label="dev04" type="number" step="5" name="dev04"
                       ng-model="selectedPlaylist.vol_dev04" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev05">USB-Gerät 5:</label>
                <input aria-label="dev05" type="number" step="5" name="dev05"
                       ng-model="selectedPlaylist.vol_dev05" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev06">USB-Gerät 6:</label>
                <input aria-label="dev06" type="number" step="5" name="dev06"
                       ng-model="selectedPlaylist.vol_dev06" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev07">USB-Gerät 7:</label>
                <input aria-label="dev07" type="number" step="5" name="dev07"
                       ng-model="selectedPlaylist.vol_dev07" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev08">USB-Gerät 8:</label>
                <input aria-label="dev08" type="number" step="5" name="dev08"
                       ng-model="selectedPlaylist.vol_dev08" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev09" for="dev09">USB-Gerät 9:</label>
                <input aria-label="dev09" type="number" step="5" name="dev09"
                       ng-model="selectedPlaylist.vol_dev09" min="0" max="100">
            </md-input-container>
            <md-input-container class="md-block playlstvol">
                <label id="dev10" for="dev10">USB-Gerät 10:</label>
                <input aria-label="dev10" type="number" step="5" name="dev10"
                       ng-model="selectedPlaylist.vol_dev10" min="0" max="100">
            </md-input-container>
        </div>
    </div>
    <div class="mdl-card__actions mdl-card--border">
        <div ng-if="!selectedPlaylist">
            <button disabled ng-click="playPlaylist(selectedPlaylist.id+1)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Play
            </button>
            <button disabled ng-click="stopPlaylist(selectedPlaylist.id+1)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Stop
            </button>
        </div>
        <div ng-if="selectedPlaylist">
            <button  ng-click="playPlaylist(selectedPlaylist.id+1)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Play
            </button>
            <button ng-click="stopPlaylist(selectedPlaylist.id+1)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Stop
            </button>
        </div>
    </div>
    <div ng-if="selectedPlaylist" class="mdl-card__menu">
        <button ng-click="savePlaylist(selectedPlaylist.id)"
                class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">save</i>
        </button>
    </div>
</div>

<script type="application/javascript">
    document.getElementById('userfile').onchange = function () {
        document.getElementById('display-text').textContent = document.getElementById('userfile').value.split(/(\\|\/)/g).pop();
    };
</script>