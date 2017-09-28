<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */ ?>

<div ng-init="isfileuploaded()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--top">
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
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--7-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Playlist Wiedergabe</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <playlists ng-init="getPlaylists()"></playlists>
    </div>
    <div class="mdl-card__menu">
        <button ng-click="addPlaylist()" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">add</i>
            <md-tooltip md-direction="bottom">Neue Playlist hinzufügen</md-tooltip>
        </button>
    </div>
</div>
<div ng-init="selectPlaylist()" class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--5-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 ng-if="selectedPlaylist" class="mdl-card__title-text">Lautstärke - {{selectedPlaylist.name}}</h2>
        <h2 ng-if="!selectedPlaylist" class="mdl-card__title-text">Lautstärke</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <div ng-if="!selectedPlaylist">
            <h5>Playlist auswählen ... </h5>
        </div>
        <div ng-if="selectedPlaylist">
            <div class="mdl-grid">
                <md-input-container class="md-block playlstvol mdl-cell--12-col">
                    <label id="background">Hintergrundmusik</label>
                    <input aria-label="background" type="number" step="any" name="background"
                           ng-model="selectedPlaylist.vol_background" min="0" max="100">
                </md-input-container>
            </div>
            <playlistvolume></playlistvolume>
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
            <button ng-click="playPlaylist(selectedPlaylist.id+1)"
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