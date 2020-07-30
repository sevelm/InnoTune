/*******************************************************************************
 *                                  INFO
 *
 * Filename :    app.js
 * Directory:    /var/www/InnoControl/scripts/
 * Created  :    24.07.2017 (initial git commit)
 * Edited   :    29.07.2020
 * Company  :    InnoTune elektrotechnik Severin Elmecker
 * Email    :    office@innotune.at
 * Website  :    https://innotune.at/
 * Git      :    https://github.com/sevelm/InnoTune/
 * Authors  :    Alexander Elmecker
 *               Julian Hoerbst
 *
 *                              DESCRIPTION
 *
 *  This script contains/handles the angular directives.
 *
 ******************************************************************************/

var app = angular.module("innoControl", ['ngRoute', 'ngMaterial', 'ngMessages']);

app.directive('devices', function () {
    return {
        restrict: 'AE',
        templateUrl: "./directives/deviceDirective.php"
    };
});
app.directive('playlists', function () {
    return {
        restrict: 'AE',
        templateUrl: "./directives/playlistDirective.php"
    };
});
app.directive('voiceoutput', function () {
    return {
        restrict: 'AE',
        templateUrl: "./directives/voiceoutputDirective.php"
    };
});
app.directive('playlistvolume', function () {
    return {
        restrict: 'AE',
        templateUrl: "./directives/playlistVolumeDirective.php"
    };
});
