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
