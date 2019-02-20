/**
 * Created by Julian on 31.08.2016.
 */

app.config(function ($routeProvider) {
    $routeProvider
        .when("/home", {
            templateUrl: "subpages/home.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("homeanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Home";
                }
            }
        })
        .when("/devices", {
            templateUrl: "subpages/devices.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("devicesanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Gerätekonfiguration";
                }
            }
        })
        .when("/volumemixer", {
            templateUrl: "subpages/volumemixer.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("volumemixeranchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Lautstärkenanpassung";
                }
            }
        })
        .when("/linein", {
            templateUrl: "subpages/linein.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("lineinanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Line-In";
                }
            }
        })
        .when("/mpd", {
            templateUrl: "subpages/mpd.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("mpdanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Zentral Player";
                }
            }
        })
        .when("/voiceoutput", {
            templateUrl: "subpages/voiceoutput.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("voiceoutputanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Sprachausgabe";
                }
            }
        })
        .when("/docs", {
            templateUrl: "subpages/docs.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("docsanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Dokumente und Update";
                }
            }
        })
        .when("/settings", {
            templateUrl: "subpages/settings.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("settingsanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Einstellungen";
                }
            }
        })
        .when("/storage", {
            templateUrl: "subpages/storage.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("storageanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Speichergeräte";
                }
            }
        })
        .when("/updatesummary", {
            templateUrl: "subpages/updatesummary.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("updatesummaryanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Server Meldungen";
                }
            }
        })
        .when("/knx", {
            templateUrl: "subpages/knx.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("knxanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "KNX-Schnittstelle";
                }
            }
        })
        .otherwise({
            templateUrl: "subpages/home.php",
            resolve: {
                return: function () {
                    resetColor();
                    document.getElementById("homeanchor").style.backgroundColor = "#263238";
                    document.getElementById("location").innerHTML = "Home";
                }
            }
        });


    var resetColor = function () {
        var anchors = document.getElementsByName("routeanchors");
        var index;

        for (index = 0; index < anchors.length; ++index) {
            anchors[index].style.removeProperty("background-color");
        }
    };

});
