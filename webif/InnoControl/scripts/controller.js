/**
 * Created by Julian on 31.08.2016.
 */

var ctrl = app.controller("InnoController", function ($scope, $http, $mdDialog, $mdToast, $interval, $location) {
    $scope.ipPattern = /^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/;
    $scope.macPattern = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
    $scope.admin = "admin";
    $scope.network = {};
    $scope.settings = {};
    $scope.rssvoice = {};
    $scope.rssvoice.vol_dev = [];
    $scope.playlists = [];
    $scope.devices = [];
    $scope.devicestmp = [];
    $scope.uploadfile = undefined;
    $scope.sysinfo = {};


    $scope.formatId = function (id) {
        if (id != 10) {
            id = '0' + id;
        }
        return id;
    };

    $scope.showLmsSwitch = function () {
        $http.get('api/helper.php?lms')
            .success(function (data) {
                if (data == "1") {
                    $scope.lmsCB = 1;
                } else {
                    $scope.lmsCB = 0;
                }
            });
    };

    $scope.changedLmsSwitch = function () {
        $scope.lmsCB = $scope.lmsCB == 1 ? 0 : 1;

        $http.get('api/helper.php?lms_save&value=' + $scope.lmsCB);
    };

    $scope.stop_lms = function () {
        $http.get('subpages/home.php?stop_lms');
        location.reload();
    };

    $scope.start_lms = function () {
        $http.get('subpages/home.php?start_lms');
        location.reload();
    };

    $scope.playlinein = function (idIN, idOUT) {
        idIN = $scope.formatId(idIN);
        idOUT = $scope.formatId(idOUT);

        $http.get('api/helper.php?setlinein&card_in=' + idIN + '&card_out=' + idOUT);
        $scope.selectedDevice.lineinStatus = idIN;
    };

    $scope.stoplinein = function (idOUT) {
        idOUT = $scope.formatId(idOUT);

        $http.get('api/helper.php?setlinein&card_out=' + idOUT);
        $scope.selectedDevice.lineinStatus = null;
    };

    $scope.muteAmp = function (id) {
        $scope.devices[id - 1].vol.linein = 0;
        $scope.devices[id - 1].vol.mpd = 0;
        $scope.devices[id - 1].vol.squeezebox = 0;
        $scope.devices[id - 1].vol.airplay = 0;

        $http.get('api/helper.php?vol_mute&dev=' + $scope.formatId($scope.selectedDevice.id));
    };

    /**
     * Sets the Inputs to disabled if toggled.
     */
    $scope.onChangeDHCP = function () {
        var fields, i;

        if ($scope.network.dhcp == 'dhcp') {
            fields = document.getElementsByClassName("settingsNF");

            for (i = 0; i < fields.length; i++) {
                fields[i].readOnly = true;
                fields[i].style.color = "gray";
            }
        } else {
            fields = document.getElementsByClassName("settingsNF");

            for (i = 0; i < fields.length; i++) {
                fields[i].removeAttribute('readonly');
                fields[i].style.color = "black";
            }
        }
    };

    $scope.setNetworkSettings = function () {
        //noinspection JSUnresolvedFunction,JSValidateTypes
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Server wird neugestartet.')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent(event)
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?setnet' +
                '&dhcp=' + $scope.network.dhcp +
                '&ip=' + $scope.network.ip +
                '&subnet=' + $scope.network.subnet +
                '&gate=' + $scope.network.gate +
                '&dns1=' + $scope.network.dns1 +
                '&dns2=' + $scope.network.dns2)
                .success(function (data) {
                    location.href = "scripts/reboot.php?ip=" + $scope.network.ip + "&dhcp=" + $scope.network.dhcp;
                });

        }, function () {

        });
    };

    /**
     * Selects the device to show on the second card.
     * @param {number} id Sets the clicked device to the selecteddevice.
     */
    $scope.selectDevice = function (id) {
        if (id == null) {
            $scope.selectedDevice = null;
        } else {
            $http.get('api/helper.php?vol&dev=' + $scope.formatId(id))
                .success(function (data) {
                    var arr = data.split(";");
                    if (data != 0) {
                        for (var i = 0; i < $scope.devices.length; i++) {
                            if ($scope.devices[i].id == id) {
                                $scope.devices[i].vol.mpd = parseInt(arr[0]) / 10;
                                $scope.devices[i].vol.squeezebox = parseInt(arr[1]) / 10;
                                $scope.devices[i].vol.airplay = parseInt(arr[2]) / 10;
                                $scope.devices[i].vol.linein = parseInt(arr[3]) / 10;
                            }
                        }
                    }
                });

            $http.get('api/helper.php?lineinstatus&dev=' + $scope.formatId(id))
                .success(function (data) {
                    if (data > 0) {
                        for (var i = 0; i < $scope.devices.length; i++) {
                            if ($scope.devices[i].id == id) {
                                $scope.devices[i].lineinStatus = data;
                            }
                        }
                    }
                });
            for (var i = 0; i < $scope.devices.length; i++) {
                if ($scope.devices[i].id == id) {
                    $scope.selectedDevice = $scope.devices[i];
                }
            }
        }
    };

    /**
     * Changes the Volume in the devices variable and sends it to the server.
     * @param {string} player chooses the player to change volume.
     */
    $scope.changeVol = function (player) {
        var value = 0;
        var id = $scope.formatId($scope.selectedDevice.id);

        switch (player) {
            case 'mpd':
                value = $scope.selectedDevice.vol.mpd * 10;
                break;
            case 'squeeze':
                value = $scope.selectedDevice.vol.squeezebox * 10;
                break;
            case 'airplay':
                value = $scope.selectedDevice.vol.airplay * 10;
                break;
            case 'LineIn':
                value = $scope.selectedDevice.vol.linein * 10;
                break;
        }
        $http.get('api/helper.php?vol_set&dev=' + id + '&player=' + player + '&value=' + value);
    };

    /**
     * Shows the Reboot Toast an REBOOT Button
     */
    $scope.showToast = function () {
        var toast = $mdToast.simple()
            .textContent('Der Server muss neugestartet werden!')
            .action('Reboot')
            .highlightAction(false);
        $mdToast.show(toast).then(function (response) {
            if (response == 'ok') {
                $http.get('api/helper.php?audio_configuration')
                    .success(function (data) {
                        location.href = "scripts/reboot.php";
                    });
            }
        });
    };

    /**
     * Controlls the Reboot dialog.
     * @param {event} event.
     */
    $scope.rebootDialog = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Server wird neugestartet.')
            .ariaLabel('Der Server ist nicht erreichbar!')
            .targetEvent(event)
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            location.href = "scripts/reboot.php";
        }, function () {

        });
    };


    $scope.genAudioConf = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Server wird neugestartet.')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent(event)
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?audio_configuration')
                .success(function (data) {
                    location.href = "scripts/reboot.php";
                });
        }, function () {

        });
    };


    $scope.genPlayerConf = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Sind Sie sicher? Media Server & (Sh)Airplay wird neu gestartet, dies kann mehrere Minuten dauern!.')
            .ariaLabel('Der LMS ist kurz nicht erreichbar!')
            .targetEvent(event)
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
            $http.get('api/helper.php?player_configuration')
                .success(function (data) {
                    document.getElementById("loadingsymbol").style.display = "none";
                    $scope.playerConfChanged = 0;
                    var toast = $mdToast.simple()
                        .textContent("Erfolgreich Player Konfiguration erzeugt!")
                        .highlightAction(true);
                    $mdToast.show(toast).then();
                })
        });
    };

    $scope.getNetworkSettings = function () {
        $http.get('api/helper.php?shnet')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0) {
                    $scope.network.dhcp = arr[0];
                    $scope.network.ip = arr[1];
                    $scope.network.subnet = arr[2];
                    $scope.network.gate = arr[3];
                    $scope.network.mac = arr[4];
                    $scope.network.dns1 = arr[5];
                    $scope.network.dns2 = arr[6];
                }
                $scope.onChangeDHCP();
            });
    };

    $scope.getWebinterfaceSettings = function () {
        $http.get('api/helper.php?web_settings')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0) {
                    $scope.settings.password = arr[0];
                    $scope.settings.port = parseInt(arr[1]);
                }
            });
    };
    $scope.setWebinterfaceSettings = function () {
        $http.get('api/helper.php?web_settings_set&password=' + $scope.settings.password + '&port=' + $scope.settings.port);
        $http.post('index.php')
            .success(function (data) {
                location.href = "/InnoControl/login.php";
            });
    };

    $scope.selectPlaylist = function (id) {
        if (id != null) {
            $scope.selectedPlaylist = $scope.playlists[id];
            $scope.getPlaylist(id);
        } else {
            $scope.selectedPlaylist = null;
        }
    };

    $scope.getPlaylists = function () {
        $scope.playlists = [];

        $http.get('api/helper.php?playlists')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0) {
                    for (var i = 0; i < arr.length - 1; i++) {
                        $scope.playlists.push({id: i, name: arr[i]});
                    }
                }
            });
    };
    $scope.getPlaylist = function (id) {
        $http.get('api/helper.php?getplaylist&ID=' + id)
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0) {
                    $scope.playlists[id].vol_background = parseInt(arr[0]);
                    $scope.playlists[id].vol_dev01 = parseInt(arr[1]);
                    $scope.playlists[id].vol_dev02 = parseInt(arr[2]);
                    $scope.playlists[id].vol_dev03 = parseInt(arr[3]);
                    $scope.playlists[id].vol_dev04 = parseInt(arr[4]);
                    $scope.playlists[id].vol_dev05 = parseInt(arr[5]);
                    $scope.playlists[id].vol_dev06 = parseInt(arr[6]);
                    $scope.playlists[id].vol_dev07 = parseInt(arr[7]);
                    $scope.playlists[id].vol_dev08 = parseInt(arr[8]);
                    $scope.playlists[id].vol_dev09 = parseInt(arr[9]);
                    $scope.playlists[id].vol_dev10 = parseInt(arr[10]);

                }
            });
    };

    $scope.deletePlaylist = function (id) {
        $http.get('api/helper.php?deleteplaylist&ID=' + (id + 1));
        $scope.playlists.splice(id, 1);
    };

    $scope.savePlaylist = function (id) {
        $http.get('api/helper.php?saveplaylist' +
            '&ID=' + (id + 1) +
            '&VOL_BACKGROUND=' + $scope.playlists[id].vol_background +
            '&VOL_DEV01=' + $scope.playlists[id].vol_dev01 +
            '&VOL_DEV02=' + $scope.playlists[id].vol_dev02 +
            '&VOL_DEV03=' + $scope.playlists[id].vol_dev03 +
            '&VOL_DEV04=' + $scope.playlists[id].vol_dev04 +
            '&VOL_DEV05=' + $scope.playlists[id].vol_dev05 +
            '&VOL_DEV06=' + $scope.playlists[id].vol_dev06 +
            '&VOL_DEV07=' + $scope.playlists[id].vol_dev07 +
            '&VOL_DEV08=' + $scope.playlists[id].vol_dev08 +
            '&VOL_DEV09=' + $scope.playlists[id].vol_dev09 +
            '&VOL_DEV10=' + $scope.playlists[id].vol_dev10);
    };

    $scope.savePlaylistName = function (id) {
        console.log("ID:" + id);

        if ($scope.playlists[id].vol_background == undefined) {
            $http.get('api/helper.php?saveplaylist' +
                '&ID=' + (id + 1) +
                '&NAME=' + $scope.playlists[id].name +
                '&VOL_BACKGROUND=-1')

                .success(function (data) {
                    location.reload();
                });
        } else {
            $http.get('api/helper.php?saveplaylist' +
                '&ID=' + (id + 1) +
                '&NAME=' + $scope.playlists[id].name)

                .success(function (data) {
                    location.reload();
                });
        }
    };

    $scope.playPlaylist = function (id) {
        $http.get('api/helper.php?playlistplay' +
            '&ID=' + (id));
    };
    $scope.stopPlaylist = function (id) {
        $http.get('api/helper.php?playliststop' +
            '&ID=' + (id));
    };


    $scope.addPlaylist = function () {
        $scope.playlists.push({id: ($scope.playlists[$scope.playlists.length - 1].id + 1), name: ""});
    };

    $scope.setAudioConfiguration = function () {
        var id = $scope.formatId($scope.selectedDevice.id);
        var mode;

        if ($scope.selectedDevice.betrieb == 'normalbetrieb') {
            mode = "1";
        } else if ($scope.selectedDevice.betrieb == 'geteilterbetrieb') {
            mode = "2";
        } else if ($scope.selectedDevice.betrieb == 'deaktiviert') {
            mode = "0";
        }

        $http.get('api/helper.php?set_audio_configuration&dev=' + id + '&mode=' + mode)
            .success(function () {
                $scope.audioConfChanged = 1;
                $scope.showToast();
            });
    };

    $scope.checkAirplay = function (airplayString) {
        if (airplayString == 0 || airplayString == undefined) {
            return "";
        } else if (airplayString == 1) {
            return "AP" + $scope.formatId($scope.selectedDevice.id);
        }
    };
    $scope.checkSpotify = function (spotifyString) {
        if (spotifyString == 0 || spotifyString == undefined) {
            return "";
        } else if (spotifyString == 1) {
            return "SP" + $scope.formatId($scope.selectedDevice.id);
        }
    };

    $scope.saveDevice = function () {
        var id = $scope.formatId($scope.selectedDevice.id);

        if ($scope.selectedDevice.betrieb == 'normalbetrieb') {
            $http.get('api/helper.php?device_set&dev=' + id +
                '&NAME_NORMAL=' + $scope.selectedDevice.name +
                '&MAC_NORMAL=' + $scope.selectedDevice.mac +
                '&AP_NORMAL=' + $scope.checkAirplay($scope.selectedDevice.airplay) +
                '&SP_NORMAL=' + $scope.checkSpotify($scope.selectedDevice.spotify));
            $scope.playerConfChanged = 1;

        } else if ($scope.selectedDevice.betrieb == 'geteilterbetrieb') {
            $http.get('api/helper.php?device_set&dev=' + id +
                '&NAMEli_GETEILT=' + $scope.selectedDevice.nameL +
                '&NAMEre_GETEILT=' + $scope.selectedDevice.nameR +
                '&MACli_GETEILT=' + $scope.selectedDevice.macL +
                '&MACre_GETEILT=' + $scope.selectedDevice.macR +
                '&APli_GETEILT=' + $scope.checkAirplay($scope.selectedDevice.airplayL) +
                '&APre_GETEILT=' + $scope.checkAirplay($scope.selectedDevice.airplayR) +
                '&SPli_GETEILT=' + $scope.checkSpotify($scope.selectedDevice.spotifyL) +
                '&SPre_GETEILT=' + $scope.checkSpotify($scope.selectedDevice.spotifyR));
            $scope.playerConfChanged = 1;
        }

    };

    $scope.getDevices = function () {
        $http.get('api/helper.php?activedevices')
            .success(function (data) {
                var arr = data.split(";");
                $scope.devicestmp = [];
                for (var i = 0; i < arr.length; i++) {
                    (function (e) {
                        const tmp_id = (i + 1);
                        if (arr[i] == 1) {
                            $http.get('api/helper.php?getdevice&dev=' + $scope.formatId(tmp_id))
                                .success(function (data) {
                                    var dev = data.split(";");
                                    if (dev[0] == 1) {
                                        $betrieb = "normalbetrieb";
                                        $airplayString = 0;
                                        if (dev[7].startsWith("AP")) {
                                            $airplayString = 1;
                                        } else {
                                            $airplayString = 0;
                                        }

                                        $spotifyString = 0;
                                        if (dev[10].startsWith("SP")) {
                                            $spotifyString = 1;
                                        } else {
                                            $spotifyString = 0;
                                        }

                                        $scope.devicestmp.push({
                                            id: tmp_id,
                                            betrieb: $betrieb,
                                            name: dev[1],
                                            mac: dev[4],
                                            airplay: $airplayString,
                                            spotify: $spotifyString,
                                            vol: {}
                                        });
                                    } else if (dev[0] == 2) {
                                        $betrieb = "geteilterbetrieb";

                                        $airplayStringL = 0;
                                        if (dev[8].startsWith("AP")) {
                                            $airplayStringL = 1;
                                        } else {
                                            $airplayStringL = 0;
                                        }

                                        $airplayStringR = 0;
                                        if (dev[9].startsWith("AP")) {
                                            $airplayStringR = 1;
                                        } else {
                                            $airplayStringR = 0;
                                        }

                                        $spotifyStringL = 0;
                                        if (dev[11].startsWith("SP")) {
                                            $spotifyStringL = 1;
                                        } else {
                                            $spotifyStringL = 0;
                                        }

                                        $spotifyStringR = 0;
                                        if (dev[12].startsWith("SP")) {
                                            $spotifyStringR = 1;
                                        } else {
                                            $spotifyStringR = 0;
                                        }

                                        $scope.devicestmp.push({
                                            id: tmp_id,
                                            betrieb: $betrieb,
                                            nameL: dev[2],
                                            nameR: dev[3],
                                            macL: dev[5],
                                            macR: dev[6],
                                            airplayL: $airplayStringL,
                                            airplayR: $airplayStringR,
                                            spotifyL: $spotifyStringL,
                                            spotifyR: $spotifyStringR,
                                            vol: {}
                                        });
                                    } else {
                                        $betrieb = "deaktiviert";
                                        $scope.devicestmp.push({id: tmp_id, betrieb: $betrieb, vol: {}});
                                    }

                                    if ($scope.devicestmp !== $scope.devices) {
                                        $scope.devices = $scope.devicestmp;
                                    }
                                });
                        }
                    })(i);
                }
            });
        $http.get('api/helper.php?getchangedconf')
            .success(function (data) {
                var arr = data.split(";");
                $scope.audioConfChanged = arr[0] == 1;

                $scope.playerConfChanged = arr[1] == 1;

            });

    };
    $scope.getVoiceRssKey = function () {
        $http.get('api/helper.php?getvoicersskey')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0 && arr[0] != "none") {
                    $scope.rssvoice.key = arr[0];
                }
            });

    };

    $scope.saveRssVoiceKey = function (key) {
        if (key == "none") {
            $http.get('api/helper.php?setvoicersskey&value=none');
            $scope.rssvoice = {};
            $scope.getVoiceRssKey();
        } else {
            $http.get('api/helper.php?setvoicersskey&value=' + $scope.rssvoice.tmpkey);
            $scope.rssvoice.key = $scope.rssvoice.tmpkey;
        }
    };

    $scope.getSysInfo = function () {
        $http.get('api/helper.php?getsysinfo')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0 && arr[0] != "none") {
                    $scope.sysinfo.cpu = arr[0];
                    $scope.sysinfo.ram = arr[1];
                    if (!arr[2].includes(":")) {
                        $scope.sysinfo.uptime = arr[2] + " min";
                    } else if (arr[2].length > 3 && arr[2].length < 6) {
                        $scope.sysinfo.uptime = arr[2] + " h";
                    } else if (arr[2].charAt(0) == "1") {
                        $scope.sysinfo.uptime = arr[2] + " Tag";
                    } else {
                        $scope.sysinfo.uptime = arr[2] + " Tage";
                    }

                    $scope.sysinfo.disksize = arr[3];
                    $scope.sysinfo.diskspace = arr[4];
                    $scope.sysinfo.diskpercent = arr[5];
                }
            });
    };

    $scope.formatSizeUnits = function (kilobytes) {
        if (kilobytes >= 1048576) {
            kilobytes = (kilobytes / 1048576).toFixed(2) + ' GB';
        }
        else if (kilobytes >= 1024) {
            kilobytes = (kilobytes / 1024).toFixed(2) + ' MB';
        }
        else if (kilobytes > 1) {
            kilobytes = kilobytes + ' KB';
        }
        else if (kilobytes == 1) {
            kilobytes = kilobytes + ' KB';
        }
        else {
            kilobytes = '0 byte';
        }
        return kilobytes;
    };

    $scope.isfileuploaded = function () {
        if ($location.search().result != undefined) {
            var toast = $mdToast.simple()
                .textContent('Upload ' + $location.search().result + '!')
                .highlightAction(true);
            $mdToast.show(toast).then();
        }
    };

    $scope.getVoiceoutputVol = function () {


        $http.get('api/helper.php?getvoiceoutputvol')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0) {
                    $scope.rssvoice.vol_background = parseInt(arr[0]);

                    $scope.rssvoice.vol_dev = [];

                    for (var i = 0; i < $scope.devices.length; i++) {
                        if (arr[i + 1].includes("/")) {
                            var tmparr = arr[i + 1].split("/");
                            console.log(tmparr);
                            $scope.rssvoice.vol_dev.push({
                                id: i,
                                volumeL: parseInt(tmparr[0]),
                                volumeR: parseInt(tmparr[1])
                            })
                        } else {
                            $scope.rssvoice.vol_dev.push({id: i, volume: parseInt(arr[i + 1])})
                        }
                    }
                }
            });
    };

    $scope.saveVoiceoutputVol = function () {
        var volStr = "";
        for (var i = 0; i < $scope.rssvoice.vol_dev.length; i++) {
            if ($scope.devices[i].betrieb == "normalbetrieb") {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=" + $scope.rssvoice.vol_dev[i].volume);
            } else if ($scope.devices[i].betrieb == "geteilterbetrieb") {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=" + $scope.rssvoice.vol_dev[i].volumeL + "/" + $scope.rssvoice.vol_dev[i].volumeR);
            } else if ($scope.devices[i].betrieb == "deaktiviert") {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=0");
            }
        }
        console.log('api/helper.php?setvoiceoutputvol' +
            '&VOL_BACKGROUND=' + $scope.rssvoice.vol_background + volStr);

        $http.get('api/helper.php?setvoiceoutputvol' +
            '&VOL_BACKGROUND=' + $scope.rssvoice.vol_background + volStr)
            .success(function (data) {
                $scope.makeToast("Erfolgreich gespeichert!");
                //location.reload();
            });
    };

    $scope.makeToast = function (text) {
        var toast = $mdToast.simple()
            .textContent(text)
            .highlightAction(true);
        $mdToast.show(toast).then();
    };

    $scope.update = function () {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Update auf neue Version! Der Server wird neu gestartet, dies kann mehrere Minuten dauern!.')
            .ariaLabel('Update!')
            .targetEvent(event)
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
            $http.get('api/helper.php?update').success(function () {
                document.getElementById("loadingsymbol").style.display = "none";

            });
        });
    };

    //Interval für System Info
    $interval($scope.getSysInfo, 4000);
});
