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
        <iframe src="phpmpd/index.php" width="100%" height="350" style="width:100%;height:500px;" frameborder="0"
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
                           ng-model="selectedPlaylist.vol_background" min="0" max="100"
                           ng-change="selectedPlaylist.volchanged=true">
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
        <div ng-if="selectedPlaylist.volchanged">
          <button ng-click="savePlaylist(selectedPlaylist.id)"
                  class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
              <i class="material-icons" style="color: rgb(255, 54, 47) !important">save</i>
          </button>
        </div>
        <div ng-if="!selectedPlaylist.volchanged">
          <button ng-click="savePlaylist(selectedPlaylist.id)"
                  class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
              <i class="material-icons">save</i>
          </button>
        </div>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">API Zentral-Player:</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <table>
            <tr>
                <td><b>Wiedergabe der Playlist mit ID01:</b></td>
                <td style="padding:0 50px 0 50px;"></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?id=1&play=1</td>
            </tr>
            <tr>
                <td><b>Wiedergabe der Playlist mit ID02:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?id=2&play=1</td>
            </tr>
            <tr>
                <td><b>Wiedergabe Stoppen:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?stop=1</td>
            </tr>
            <tr>
                <td><b>Wiedergabe Repeat (Bsp. für Alarm):</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?repeat=1</td>
            </tr>
            <tr>
                <td><b>Playlist 01: Hintergrundlaustärke auf 50%:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?id=1&vb=50</td>
            </tr>
            <tr>
                <td><b>Playlist 01: Lautstärke der Zone01 auf 50%:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?id=1&v01=50</td>
            </tr>
            <tr>
                <td><b>Playlist 01: Lautstärke der Zone02 links auf 50% rechts auf mute (0%):</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/mpdvol.php?id=1&v02=50&lr02&vr02=0</td>
            </tr>
        </table>
        <table style="margin-top: 20px">
            <tr>
                <td><b>Parameterliste:</b></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>play</b></td>
                <td></td>
                <td>Abspielen einer Playlist</td>
            </tr>
            <tr>
                <td><b>stop</b></td>
                <td></td>
                <td>Stoppen einer Playlist</td>
            </tr>
            <tr>
                <td><b>repeat</b></td>
                <td></td>
                <td>Wiederholen einer Playlist</td>
            </tr>
            <tr>
                <td><b>id</b></td>
                <td></td>
                <td>ID der Playlist (Format: 1-10)</td>
            </tr>
            <tr>
                <td><b>title</b></td>
                <td></td>
                <td>Playlisttitel setzen</td>
            </tr>
            <tr>
                <td><b>vb</b></td>
                <td></td>
                <td>Hintergrundlautstärke (0-100)</td>
            </tr>
            <tr>
                <td><b>vx</b></td>
                <td></td>
                <td>Lautstärke Zone x (x = 01-10) bei Angabe von Parameter lrx ist Lautstärke für linken Kanal von Zone x</td>
            </tr>
            <tr>
                <td><b>lrx</b></td>
                <td></td>
                <td>Angabe Zone x ist im geteilten Betrieb</td>
            </tr>
            <tr>
                <td><b>vrx</b></td>
                <td></td>
                <td>Lautstärke rechter Kanal Zone x (x = 01-10)</td>
            </tr>
        </table>
    </div>
</div>


<script type="application/javascript">
    document.getElementById('userfile').onchange = function () {
        document.getElementById('display-text').textContent = document.getElementById('userfile').value.split(/(\\|\/)/g).pop();
    };
</script>
