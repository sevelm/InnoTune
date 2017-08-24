<?php
/**
 * Created by PhpStorm.
 * User: julia
 * Date: 20.09.2016
 * Time: 09:29
 */
?>
<div ng-repeat="playlist in playlists"  class="mdl-grid">
    <p class="mdl-cell mdl-cell--3-col">Playlist {{playlist.id+1}}:</p>
    <md-input-container style="height: 15px;" class="md-block mdl-cell mdl-cell--4-col">
        <input placeholder="Name..." class="" ng-model="playlist.name" aria-label="playlist0{{p.id+1}}">
    </md-input-container>

    <button ng-click="savePlaylistName(playlist.id)" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--1-col">
        <i class="material-icons">save</i>
    </button>
    <button ng-click="selectPlaylist(playlist.id)" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--1-col">
        <i class="material-icons">settings_application</i>
    </button>
    <button ng-click="deletePlaylist(playlist.id)" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--1-col">
        <i class="material-icons">delete</i>
    </button>
</div>