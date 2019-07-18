/**
 * Created by Julian on 31.08.2016.
 */

var ctrl = app.controller("InnoController", function ($scope, $http, $mdDialog, $mdToast, $interval, $location) {
    $scope.ipPattern = /^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/;
    $scope.macPattern = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
    $scope.mntDir = /^[a-zA-Z0-9_-]*$/;
    $scope.urlPattern = /^[^\\]*$/;
    $scope.passwordPattern = /^[a-zA-Z0-9!"§%\/()=ß?'*]*$/;
    $scope.admin = "admin";
    $scope.network = {};
    $scope.settings = {
        password: ''
    };
    $scope.rssvoice = {};
    $scope.rssvoice.languages = ["de-de","en-us","fr-fr","it-it","pl-pl"];
    $scope.rssvoice.vol_dev = [];
    $scope.playlists = [];
    $scope.playlists.vol_dev = [];
    $scope.devices = [];
    $scope.deviceOnlineCount = 0;
    $scope.devicestmp = [];
    $scope.uploadfile = undefined;
    $scope.sysinfo = {};
    $scope.networkmount = {};
    $scope.netfs = [];
    $scope.netdirs = ["net0","net1","net2","net3","net4"];
    $scope.LineInSelection = [];
    $scope.resetcb = {
        usb: false,
        network: false,
        playlists: false
    };
    $scope.shairinstances = 0;
    $scope.editMacs = false;
    $scope.ituneslib = {};
    $scope.updateErrors = [];
    $scope.lmsstate = "ok";
    $scope.collapseRL = false;
    $scope.collapseUL = false;
    $scope.collapseLL = false;
    $scope.logports = false;
    $scope.logportsRunning = false;
    $scope.pastatus = {installed: 'unknown', running: 'unknown'};

    $scope.knxcmds = [];
    $scope.knxradios = [];
    $scope.knxprocess = {
        knxd: 0,
        listener: 0,
        callback: 0
    }
    $scope.radioAdd = {
        name: '',
        url: ''
    };
    $scope.knxcmd = {
        group: '',
        type: 0,
        cmd: '',
        cmdoff: '',
        dimmertype: 1,
        changed: false
    };
    $scope.knx = {type: 1, changed: false};
    $scope.knxinstalled = false;
    $scope.knxversion = "";
    $scope.knxAddressPattern = /^(?:[0-9]{1,3}\.){2}[0-9]{1,3}$/;
    $scope.knxGroupPattern = /^(?:[0-9]{1,3}\/){2}[0-9]{1,3}$/;
    $scope.updatestatus = '';

    $scope.systemCoding = -2;
    $scope.systemType = 'Konnte nicht ausgelesen werden.';
    $scope.fanOptions = {
        typeValue: -1,
        type: '',
        stateValue: -1,
        state: '',
        manualOperation: 0,
        newStateValue: -1
    };
    $scope.lmswastate = false;

    $scope.getLmsWaState = function() {
        $http.get('api/helper.php?lmswastate')
            .success(function (data) {
                if (data !== '') {
                    $scope.lmswastate = parseInt(data) == 1;
                } else {
                    $scope.lmswastate = false;
                }
            })
    };

    $scope.switchLmsWaState = function() {
        $scope.lmswastate = !$scope.lmswastate;
        var val = 0;
        if ($scope.lmswastate) {
            val = 1;
        }
        $http.get('api/helper.php?lmswa=' + val)
            .success(function (data) {
                $scope.getLmsWaState();
            })
    }

    $scope.resetWaLog = function() {
        $http.get('api/helper.php?lmswalogreset')
            .success(function (data) {

            })
    };

    $scope.readSystemStates = function() {
        $http.get('api/helper.php?readSystemCoding')
              .success(function (data) {
                  if (data !== '') {
                      $scope.systemCoding = parseInt(data);
                      if ($scope.systemCoding === 0) {
                          $scope.systemType = 'InnoServer';
                      } else if ($scope.systemCoding === 1) {
                          $scope.systemType = 'InnoRack V1';
                      } else if ($scope.systemCoding >= 2) {
                          $scope.systemType = 'InnoRack V2';
                      }
                  } else {
                      $scope.systemCoding = -2;
                      $scope.systemType = 'Konnte nicht ausgelesen werden.';
                  }
                  $scope.readFanOptions();
              });
    };

    $scope.readFanOptions = function() {
        $http.get('api/helper.php?readFanOptions')
              .success(function (csv) {
                  if (csv !== '') {
                      var data = csv.split(';');
                      if (data.length !== 0) {
                          $scope.fanOptions.manualOperation = parseInt(data[0]);
                          $scope.fanOptions.typeValue = parseInt(data[1]);
                          $scope.fanOptions.stateValue = parseInt(data[2]);
                          $scope.fanOptions.newStateValue = $scope.fanOptions.stateValue;

                          if ($scope.fanOptions.typeValue === 0) {
                              $scope.fanOptions.type = 'Relais';
                              if ($scope.fanOptions.stateValue === 0) {
                                  $scope.fanOptions.state = 'Lüfter ausgeschaltet';
                              } else {
                                  $scope.fanOptions.state = 'Lüfter eingeschaltet';
                              }
                          } else {
                              $scope.fanOptions.type = 'PWM';
                              $scope.fanOptions.state = 'Lüfter läuft auf ' + ($scope.fanOptions.stateValue * 10) + ' %';
                          }
                      }
                  } else {
                      $scope.fanOptions = {
                          typeValue: -1,
                          type: '',
                          stateValue: -1,
                          state: '',
                          manualOperation: 0,
                          newStateValue: -1
                      };
                  }
              });
    };

    $scope.setFanOperation = function() {
        console.log('fan operation');
        $http.get('api/helper.php?setFanOperation&op=' + $scope.fanOptions.manualOperation)
              .success(function () {
                  $scope.fanOptions.newStateValue = $scope.fanOptions.stateValue;
              });
    };

    $scope.setFanState = function() {
        console.log('fan state');
        $http.get('api/helper.php?setFanState&state=' + $scope.fanOptions.newStateValue)
              .success(function () {
                  $scope.readFanOptions();
              });
    };

    $scope.getAllMuteStates = function() {
        for (var i = 0; i < $scope.devices.length; i++) {
            var device = $scope.devices[i];
            if (device.betrieb != 'deaktiviert'
                    && device.betrieb != 'nichtverbunden') {
                $scope.getMuteState(device);
            }
        }
    };

    $scope.getMuteState = function(device) {
        console.log('get mute');
        $http.get('api/helper.php?getMuteState&id=' + $scope.formatId(device.id))
              .success(function (csv) {
                  var data = csv.split(';');
                  for (var i = 0; i < $scope.devices.length; i++) {
                      if ($scope.devices[i].id == device.id) {
                          $scope.devices[i].manualOperation = parseInt(data[0]);
                          $scope.devices[i].isMuted = parseInt(data[1]);
                      }
                  }
              });
    };

    $scope.setMuteOperation = function(device) {
        console.log('mute operation');
        $http.get('api/helper.php?setMuteOperation&id=' + $scope.formatId(device.id)
                    + '&op=' + device.manualOperation)
              .success(function () {
              });
    };

    $scope.setMuteState = function(device) {
        console.log('mute state');
        $http.get('api/helper.php?setMuteState&id=' + $scope.formatId(device.id)
                    + '&state=' + device.isMuted)
              .success(function () {
              });
    };

    $scope.getKnxCallbacks = function() {
        $http.get('api/helper.php?getKnxCallbacks')
            .success(function (csv) {
                var lines = csv.split('\n');
                lines.forEach(function (element) {
                    if (element.includes("|")) {
                        var data = element.split("|");
                        for(var i = 0; i < $scope.devices.length; i++) {
                            if ($scope.devices[i].betrieb == 'normalbetrieb') {
                                if (data[0] == $scope.devices[i].mac) {
                                    $scope.devices[i].knx.gpstatus = data[1];
                                    $scope.devices[i].knx.gpvolume = data[2];
                                }
                            } else if ($scope.devices[i].betrieb == 'geteilterbetrieb') {
                                if (data[0] == $scope.devices[i].macL) {
                                    $scope.devices[i].knx.gpstatusL = data[1];
                                    $scope.devices[i].knx.gpvolumeL = data[2];
                                } else if (data[0] == $scope.devices[i].macR) {
                                    $scope.devices[i].knx.gpstatusR = data[1];
                                    $scope.devices[i].knx.gpvolumeR = data[2];
                                }
                            }
                        }
                    }
                });
            });
    };

    $scope.saveKnxCallback = function(mac, status, volume) {
        $http.get('api/helper.php?saveKnxCallback&mac=' + mac +
                    '&status=' + status + '&volume=' + volume)
            .success(function () {
                $scope.getKnxCallbacks();
            });
    };

    $scope.clearKnxCallback = function(mac) {
        $http.get('api/helper.php?clearKnxCallback&mac=' + mac)
            .success(function () {
                $scope.getKnxCallbacks();
            });
    };

    $scope.saveKnxSettings = function() {
        $http.get('api/helper.php?setknx&address=' + $scope.knx.address +
                    '&mode=' + $scope.knx.type)
              .success(function () {
                  $scope.knx.changed = false;
              });
    };

    $scope.saveKnxCmd = function() {
        if ($scope.knxcmd.group !== undefined && $scope.knxcmd.group !== null &&
            $scope.knxcmd.group !== '') {
            if ($scope.knxcmd.type === '1' || $scope.knxcmd.type === '2') {
                $scope.knxcmd.cmdoff = '';
            }
            var ampId = '';
            var geteilt = '';
            $scope.devices.forEach(function(device) {
                if (device.mac !== undefined) {
                    if (device.mac == $scope.knxcmd.cmd) {
                        ampId = device.id;
                        geteilt = 0;
                        console.log(ampId + ', ' + geteilt);
                    }
                } else if (device.macL !== undefined) {
                    if (device.macL == $scope.knxcmd.cmd) {
                        ampId = device.id;
                        geteilt = 1;
                        console.log(ampId + ', ' + geteilt);
                    } else if (device.macR == $scope.knxcmd.cmd) {
                        ampId = device.id;
                        geteilt = 2;
                        console.log(ampId + ', ' + geteilt);
                    }
                }
            });
            $http.get('api/helper.php?setknxcmd&group=' + $scope.knxcmd.group +
                        '&type=' + $scope.knxcmd.type +
                        '&cmd=' + encodeURIComponent($scope.knxcmd.cmd) +
                        '&cmdoff=' + encodeURIComponent($scope.knxcmd.cmdoff) +
                        '&dimmertype=' + $scope.knxcmd.dimmertype +
                        '&amp=' + ampId +
                        '&geteilt=' + geteilt)
                  .success(function () {
                      $scope.knxcmd = {
                          group: '',
                          type: 0,
                          cmd: '',
                          cmdoff: '',
                          dimmertype: 1,
                          changed: false
                      };
                      $scope.getKnxCmds();
                  });
        }
    };

    $scope.resetKnxCmd = function() {
        $scope.knxcmd = {
            group: '',
            type: 0,
            cmd: '',
            cmdoff: '',
            dimmertype: 1,
            changed: false
        };
    };

    $scope.editKnxCmd = function(cmd) {
        $scope.knxcmd = {
            group: cmd.group,
            type: cmd.type,
            cmd: cmd.cmd,
            cmdoff: cmd.cmdoff,
            dimmertype: cmd.dimmertype,
            changed: false
        };
    };

    $scope.deleteKnxCmd = function(cmd) {
        $http.get('api/helper.php?deleteknxcmd&group=' + cmd.group)
              .success(function () {
                $scope.getKnxCmds();
              });
    };

    $scope.deleteKnxEmptyAddr = function () {
        $http.get('api/helper.php?deleteknxemptyaddr')
              .success(function () {
                $scope.getKnxCmds();
              });
    };

    $scope.getKnxSettings = function() {
        $http.get('api/helper.php?getknx')
              .success(function (csv) {
                var data = csv.split(';');
                $scope.knx.type = data[0];
                $scope.knx.address = data[1];
                $scope.knx.running = data[2];
                $scope.knx.current = data[3];
                $scope.knx.interfaces = data[4];
                $scope.getKnxCmds();
              });
      $scope.getKnxProcess();
    };

    $scope.getKnxCmds = function() {
        $http.get('api/helper.php?getknxcmds')
              .success(function (csv) {
                  var lines = csv.split('\n');
                  $scope.knxcmds = [];
                  lines.forEach(function (element) {
                    if (element.includes("|")) {
                      var data = element.split("|");
                      if (data[1] !== '2') {
                          $scope.knxcmds.push({
                              group: data[0],
                              type: data[1],
                              cmd: data[2],
                              cmdoff: data[3],
                              dimmertype: 1
                            });
                      } else {
                          $scope.knxcmds.push({
                              group: data[0],
                              type: data[1],
                              cmd: data[3],
                              cmdoff: '',
                              dimmertype: data[2]
                            });
                      }
                    }
                  });
              });
    };

    $scope.restartKnx = function() {
        $http.get('api/helper.php?startknx=1')
              .success(function () {
                  $scope.getKnxSettings();
              });
    };

    $scope.stopKnx = function() {
        $http.get('api/helper.php?startknx=0')
              .success(function () {
                  $scope.getKnxSettings();
              });
    };

    $scope.checkKnx = function() {
        $http.get('api/helper.php?checkknx')
              .success(function (data) {
                  console.log(data);
                  if (data == "1") {
                      $scope.knxinstalled = true;
                  } else {
                      $scope.knxinstalled = false;
                  }
              });
    };

    $scope.getKnxProcess = function() {
        $http.get('api/helper.php?getknxprocess')
              .success(function (data) {
                  var array = data.replace("\n", "").split(";");
                  $scope.knxprocess.knxd = array[0];
                  $scope.knxprocess.listener = array[1];
                  $scope.knxprocess.callback = array[2];
              });
    };

    $scope.getKnxVersion = function() {
        $http.get('api/helper.php?knxversion')
              .success(function (data) {
                  $scope.knxversion = data;
              });
    };

    $scope.installKnx = function() {
        document.getElementById("loadingsymbol").style.display = "block";
        $http.get('api/helper.php?installknx')
              .success(function () {
                  location.href = "/scripts/reboot.php?update=true"
              });
    };

    $scope.getKnxRadios = function() {
        $http.get('api/helper.php?getknxradios')
              .success(function (csv) {
                  var lines = csv.split('\n');
                  $scope.knxradios = [];
                  lines.forEach(function (element) {
                    if (element.includes("|")) {
                      var data = element.split("|");
                      $scope.knxradios.push({
                          id: data[1],
                          name: data[2],
                          url: data[3],
                          edit: 0,
                          editname: data[2],
                          editurl: data[3]
                        });
                    }
                  });
              });
    };

    $scope.deleteKnxRadio = function(radio) {
        $http.get('api/helper.php?deleteknxradio&id=' + radio.id)
              .success(function () {
                  $scope.getKnxRadios();
              });
    };

    $scope.saveKnxRadio = function(radio) {
        radio.name = radio.editname;
        radio.url = radio.editurl;
        radio.edit = 0;
        $http.get('api/helper.php?saveknxradio' +
                '&id=' + radio.id +
                '&name=' + encodeURIComponent(radio.name) +
                '&url=' + encodeURIComponent(radio.url))
            .success(function () {

            });
    };

    $scope.addKnxRadio = function() {
        $http.get('api/helper.php?addknxradio' +
                '&name=' + encodeURIComponent($scope.radioAdd.name) +
                '&url=' + encodeURIComponent($scope.radioAdd.url))
            .success(function () {
                $scope.radioAdd.name = '';
                $scope.radioAdd.url = '';
                $scope.getKnxRadios();
            });
    };

    $scope.resetKnxRadios = function() {
        $http.get('api/helper.php?resetknxradios')
            .success(function () {
                $scope.getKnxRadios();
            });
    };

    $scope.setCollapseRL = function() {
      $scope.collapseRL = !$scope.collapseRL;
    }

    $scope.setCollapseUL = function() {
      $scope.collapseUL = !$scope.collapseUL;
    }

    $scope.setCollapseLL = function() {
      $scope.collapseLL = !$scope.collapseLL;
    }

    $scope.getLogPorts = function() {
      $http.get('api/helper.php?getlogports')
            .success(function (data) {
              $scope.logports = data == "1";
            });
      $http.get('api/helper.php?checklogports')
            .success(function (data) {
              $scope.logportsRunning = data.replace("\n", "") != "0";
            });
    };

    $scope.setLogPorts = function() {
      var log = "1";
      if ($scope.logports) {
        log = "0";
      }
      $http.get('api/helper.php?setlogports=' + log)
            .success(function (data) {
              $scope.logports = !$scope.logports;
              $http.get('api/helper.php?checklogports')
                    .success(function (data) {
                      $scope.logportsRunning = data.replace("\n", "") != "0";
                    });
            });
    };

    $scope.downloadPortLogs = function() {

    };

    $scope.removePa = function() {
      document.getElementById("loadingsymbol").style.display = "block";
      $http.get('api/helper.php?removepulseaudio')
            .success(function () {
                location.href = "/scripts/reboot.php?update=true";
            });
    };

    $scope.getPa = function() {
      $http.get('api/helper.php?checkpulseaudio')
            .success(function (data) {
                var arr = data.split(';');
                if (arr[0] == "0") {
                  $scope.pastatus.running = 'Nein';
                } else {
                  $scope.pastatus.running = 'Ja';
                }
                if (arr[1] == "installed\n") {
                  $scope.pastatus.installed = 'Ja';
                } else {
                  $scope.pastatus.installed = 'Nein';
                }
            });
    }

    $scope.reinstallLms = function() {
      document.getElementById("loadingsymbol").style.display = "block";
      $http.get('api/helper.php?reinstall_lms')
            .success(function () {
              $http.get('api/helper.php?update').success(function () {
                  location.href = "/scripts/reboot.php?update=true"
              });
            });
    };

    $scope.getUpdateValidation = function() {
        $http.get('api/helper.php?validateupdate')
          .success(function (data) {
            $scope.updateErrors = [];
            var arr = data.split("\n");
            arr.forEach(function (element) {
              if (element.includes(";")) {
                var elementData = element.split(";");
                $scope.updateErrors.push({package: elementData[0], status: elementData[1]});
              }
            });
          });
    };

    $scope.reinstallPackage = function(package) {
        if (package.status === 'failed') {
          document.getElementById('spinner' + package.package).style.removeProperty('display');
            document.getElementById('button' + package.package).style.display = "none";
          $http.get('api/helper.php?reinstall=' + package.package)
            .success(function(data) {
                if(data.includes('invalid or already installed')) {
                  var confirm = $mdDialog.confirm()
                      .title('Fehler')
                      .textContent('Das Paket "' + package.package + '" ist ungültig.')
                      .targetEvent()
                      .ok('Ok')
                  $mdDialog.show(confirm);
                } else {
                  if (data.includes(';installed')) {
                    var index = $scope.updateErrors.indexOf(package);
                    $scope.updateErrors[index] = {package: package.package, status: "installed"};
                  } else {
                    var confirm = $mdDialog.confirm()
                        .title('Fehler')
                        .textContent('Beim installieren des Pakets "' + package.package + '" ist ein Fehler aufgetreten.')
                        .targetEvent()
                        .ok('Ok')
                    $mdDialog.show(confirm);
                  }
                }
                document.getElementById('button' + package.package).style.removeProperty('display');
                document.getElementById('spinner' + package.package).style.display = "none";
            });
        }
    };

    $scope.checkLmsStatus = function() {
      $http.get('api/helper.php?check_lms')
          .success(function (data) {
              $scope.lmsstate = data;
          });
    };

    $scope.rebootAndValidate = function() {
      $http.get('api/helper.php?revalidate')
            .success(function() {
              location.href = "scripts/reboot.php";
            });
    };

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

    $scope.resetLms = function () {
      document.getElementById("loadingsymbol").style.display = "block";
        $http.get('api/helper.php?reset_lms')
            .success(function (data) {
                location.href = "scripts/reboot.php";
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


    $scope.getLineInStatus = function () {
        for (let i = 0; i < $scope.devices.length; i++) {
            $http.get('api/helper.php?lineinstatus&dev=' + $scope.formatId($scope.devices[i].id))
                .success(function (data) {
                    if ($scope.devices[i] !== undefined) {
                        if(data.indexOf(";") >=0){
                            $scope.devices[i].lineinStatusre = data.substr(0,data.indexOf(";"));
                            $scope.devices[i].lineinStatusli = data.substr(data.indexOf(";")+1);
                        } else {
                            $scope.devices[i].lineinStatus = data;
                        }
                    }
                });
        }

    };
    $scope.checkLineInStatus = function (lineinStatus) {
        if (parseInt(lineinStatus) == parseInt($scope.formatId($scope.selectedDevice.id))) {
            console.log("Play von: " + parseInt($scope.formatId($scope.selectedDevice.id)) + ", Play auf: " + lineinStatus);
            return true;
        } else {
            return false;
        }
    };

    $scope.playlinein = function (idIN) {
        $scope.sortDevices();
        idIN = $scope.formatId(idIN);
        var idOUT = "";
        for (var i = 0; i < $scope.LineInSelection.length; i++) {
            if ($scope.LineInSelection[i].toString().indexOf("li") >= 0 || $scope.LineInSelection[i].toString().indexOf("re") >= 0) {
                var idOUT = $scope.formatId($scope.LineInSelection[i].toString().match(/\d+/)[0]);
                var lire = $scope.LineInSelection[i].toString().slice(-2);
                $http.get('api/helper.php?setlinein&card_in=' + idIN + '&card_out=' + idOUT + '&mode='+lire);
            } else {
                idOUT = $scope.formatId($scope.LineInSelection[i]);
                $http.get('api/helper.php?setlinein&card_in=' + idIN + '&card_out=' + idOUT);
            }
        }
        $interval($scope.getLineInStatus, 200, 1);
    };

    $scope.toggleSelection = function toggleSelection(id) {
        $scope.sortDevices();
        var idx = $scope.LineInSelection.indexOf(id);
        var isChecked = document.getElementById("checkbox" + id).checked;
        // Is currently selected
        if (isChecked !== undefined) {
            if (isChecked) {
                if (idx == -1) {
                    $scope.LineInSelection.push(id);
                }
            } else {
                if (idx > -1) {
                    $scope.LineInSelection.splice(idx, 1);
                }
            }
        } else {
            if (idx > -1) {
                $scope.LineInSelection.splice(idx, 1);
            }
            // Is newly selected
            else {
                $scope.LineInSelection.push(id);
            }
        }
    };

    $scope.stoplinein = function () {
        $scope.sortDevices();
        for (var i = 0; i < $scope.LineInSelection.length; i++) {
            $http.get('api/helper.php?setlinein&card_out=' + $scope.formatId($scope.LineInSelection[i]));
        }
        $interval($scope.getLineInStatus, 200, 2);
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

    // Section USB-Mount
    $scope.onChangeUsbMount = function () {
        $scope.usbmount = $scope.usbmount == 1 ? 0 : 1;

        if ($scope.usbmount == 1) {
            $http.get('subpages/settings.php?start_usbmount');
        } else {
            $http.get('subpages/settings.php?stop_usbmount');
        }
    };

    $scope.showUSBSwitch = function () {
        $http.get('api/helper.php?get_usbmount')
            .success(function (data) {
                if (data == 1) {
                    $scope.usbmount = 1;
                } else {
                    $scope.usbmount = 0;
                }
            });
    };

    $scope.testWlan = function () {
        $scope.network.test = "-1";
        $scope.network.ipwlan = "";
        $http.get('api/helper.php?testwlan' +
                  '&ssid=' + $scope.network.ssid +
                  '&psk=' + $scope.network.psk)
                  .success(function (data) {
                      $scope.network.test = data;
                      $http.get('api/helper.php?testwlanip')
                        .success(function (dataIp) {
                            $scope.network.ipwlan = dataIp;
                        });
                  });
    };

    $scope.setNetworkSettings = function () {
        //noinspection JSUnresolvedFunction,JSValidateTypes
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Server wird neugestartet.')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?setnet' +
                '&dhcp=' + $scope.network.dhcp +
                '&ip=' + $scope.network.ip +
                '&subnet=' + $scope.network.subnet +
                '&gate=' + $scope.network.gate +
                '&dns1=' + $scope.network.dns1 +
                '&dns2=' + $scope.network.dns2 +
                '&wlan=' + $scope.network.wlan +
                '&ssid=' + $scope.network.ssid +
                '&psk=' + $scope.network.psk)
                .success(function () {
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
            //Volume
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
            //Equalizer
            $http.get('api/helper.php?eq&dev=' + $scope.formatId(id))
                .success(function (data) {
                    var arr = data.split(";");
                    if (data != 0) {
                        for (var i = 0; i < $scope.devices.length; i++) {
                            if ($scope.devices[i].id == id) {
                                $scope.devices[i].eq.low = Math.round(parseInt(arr[0]) / 10);
                                $scope.devices[i].eq.mid = Math.round(parseInt(arr[1]) / 10);
                                $scope.devices[i].eq.high = Math.round(parseInt(arr[2]) / 10);
                            }
                        }
                    }
                });
            //Line-In Status
            $scope.getLineInStatus();

            for (var i = 0; i < $scope.devices.length; i++) {
                if ($scope.devices[i].id == id) {
                    $scope.selectedDevice = $scope.devices[i];
                }
            }
            $scope.selectDeviceDefaultValues();
        }
    };

    $scope.restoreDefault = function() {
      $scope.editMacs = false;
      var id = $scope.formatId($scope.selectedDevice.id);
      if ($scope.selectedDevice.betrieb == 'normalbetrieb') {
        $scope.selectedDevice.name = "InnoAmp " + id;
        $scope.selectedDevice.nameL = "";
        $scope.selectedDevice.nameR = "";
        $scope.selectedDevice.mac = "00:00:00:00:00:" + id;
        $scope.selectedDevice.macL = "";
        $scope.selectedDevice.macR = "";
      } else if ($scope.selectedDevice.betrieb == 'geteilterbetrieb') {
        $scope.selectedDevice.name = "";
        $scope.selectedDevice.nameL = "InnoAmp " + id + " Links";
        $scope.selectedDevice.nameR = "InnoAmp " + id + " Rechts";
        $scope.selectedDevice.mac = "";
        $scope.selectedDevice.macL = "00:00:00:00:01:" + id;
        $scope.selectedDevice.macR = "00:00:00:00:02:" + id;
      } else {
        $scope.selectedDevice.name = "";
        $scope.selectedDevice.nameL = "";
        $scope.selectedDevice.nameR = "";
        $scope.selectedDevice.mac = "";
        $scope.selectedDevice.macL = "";
        $scope.selectedDevice.macR = "";
      }
      $scope.selectedDevice.changed = true;
    };

    $scope.selectDeviceDefaultValues = function() {
      $scope.editMacs = false;
      if ($scope.selectedDevice != null) {
        var id = $scope.formatId($scope.selectedDevice.id);
        if ($scope.selectedDevice.betrieb == 'normalbetrieb') {
            if(!$scope.selectedDevice.name) {
              $scope.selectedDevice.name = "InnoAmp " + id;
              $scope.selectedDevice.changed = true;
            }
            if (!$scope.selectedDevice.mac) {
              $scope.selectedDevice.mac = "00:00:00:00:00:" + id;
              $scope.selectedDevice.changed = true;
            } else {
              if ($scope.selectedDevice.mac !== "00:00:00:00:00:" + id) {
                $scope.editMacs = true;
              }
            }
        } else if ($scope.selectedDevice.betrieb == 'geteilterbetrieb') {
            if(!$scope.selectedDevice.nameL) {
              $scope.selectedDevice.nameL = "InnoAmp " + id + " Links";
              $scope.selectedDevice.changed = true;
            }
            if(!$scope.selectedDevice.nameR) {
              $scope.selectedDevice.nameR = "InnoAmp " + id + " Rechts";
              $scope.selectedDevice.changed = true;
            }
            if (!$scope.selectedDevice.macL) {
              $scope.selectedDevice.macL = "00:00:00:00:01:" + id;
              $scope.selectedDevice.changed = true;
            } else {
              if ($scope.selectedDevice.macL !== "00:00:00:00:01:" + id) {
                $scope.editMacs = true;
              }
            }
            if (!$scope.selectedDevice.macR) {
              $scope.selectedDevice.macR = "00:00:00:00:02:" + id;
              $scope.selectedDevice.changed = true;
            } else {
              if ($scope.selectedDevice.macR !== "00:00:00:00:02:" + id) {
                $scope.editMacs = true;
              }
            }
        }
      }
    };

    $scope.getAllVol = function () {
        for (var i = 0; i < $scope.devices.length; i++) {
            $scope.getVolHttp($scope.devices[i].id);
        }
    };

    $scope.getVolHttp = function (id) {
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
    }

    $scope.changeLineInVol = function (device) {
        var id = $scope.formatId(device.id);
        var value = device.vol.linein * 10;
        $http.get('api/helper.php?vol_set&dev=' + id + '&player=LineIn&value=' + value);
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

    $scope.changeEq = function (freq) {
        var value = 0;
        var id = $scope.formatId($scope.selectedDevice.id);

        switch (freq) {
            case 'low':
                value = $scope.selectedDevice.eq.low * 10;
                break;
            case 'mid':
                value = $scope.selectedDevice.eq.mid * 10;
                break;
            case 'high':
                value = $scope.selectedDevice.eq.high * 10;
                break;
        }
        $http.get('api/helper.php?eq_set&dev=' + id + '&freq=' + freq + '&value=' + value);
    }

    $scope.resetEqSettings = function () {
        var value = 66;
        var id = $scope.formatId($scope.selectedDevice.id);
        $http.get('api/helper.php?eq_set&dev=' + id + '&freq=low&value=' + value);
        $http.get('api/helper.php?eq_set&dev=' + id + '&freq=mid&value=' + value);
        $http.get('api/helper.php?eq_set&dev=' + id + '&freq=high&value=' + value);
        $scope.selectedDevice.eq.low = 7;
        $scope.selectedDevice.eq.mid = 7;
        $scope.selectedDevice.eq.high = 7;
    }

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
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            location.href = "scripts/reboot.php";
        }, function () {

        });
    };

    $scope.resetLogs = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Alle Logs werden gelöscht!')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?reset_logs')
                .success(function (data) {
                    location.href = "index.php#/docs";
                });
        }, function () {

        });
    };

    $scope.resetMapping = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Die Gerätereihenfolge kann sich verändern und dadurch alle Zonen beinflussen!')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?reset_usb_mapping')
                .success(function (data) {
                    location.href = "scripts/reboot.php";
                });
        }, function () {

        });
    };

    $scope.genAudioConf = function (event) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Server wird neugestartet.')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
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
            .targetEvent()
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
                    $scope.network.wlan = arr[7];
                    $scope.network.ssid = arr[8];
                    $scope.network.psk = arr[9];
                    $scope.network.wlanfailed = arr[10];
                    $scope.network.macwlan = arr[11];
                }
                $scope.onChangeDHCP();
            });
        $http.get('api/helper.php?wifi')
            .success(function (data) {
                $scope.network.wifilist = Array.from(new Set(data.split(";")));
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
        var errorState = 0;
        if ($scope.settings.password === undefined || $scope.settings.password.trim().length === 0) {
            errorState = 1;
        }
        if ($scope.settings.port === undefined || $scope.settings.port < 80 || $scope.settings.port > 65535) {
            errorState = errorState + 2;
        }


        if (errorState === 0) {
            $http.get('api/helper.php?web_settings_set&password=' + $scope.settings.password.trim() + '&port=' + $scope.settings.port);
            $http.post('index.php')
                .success(function () {
                    location.href = "/login.php";
                });
        } else if (errorState === 1) {
            console.log('Passwort kann nicht gesetzt werden');
            var confirm = $mdDialog.confirm()
                .title('Neues Passwort ungültig!')
                .textContent('Das neue Passwort enthält Zeichen die nicht gültig sind. Bitte geben Sie ein anderes ein.')
                .targetEvent()
                .ok('Verstanden')
            $mdDialog.show(confirm);
        } else if (errorState === 2) {
            console.log('Port kann nicht gesetzt werden');
            var confirm = $mdDialog.confirm()
                .title('Neuer Port ungültig!')
                .textContent('Der Port muss zwischen 80 und 65535 liegen!')
                .targetEvent()
                .ok('Verstanden')
            $mdDialog.show(confirm);
        } else if (errorState === 3) {
            console.log('Passwort und Port können nicht gesetzt werden');
            var confirm = $mdDialog.confirm()
                .title('Neues Passwort ungültig!')
                .textContent('Der Port muss zwischen 80 und 65535 liegen und das neue Passwort enthält Zeichen die nicht gültig sind. Bitte geben Sie ein anderes ein.')
                .targetEvent()
                .ok('Verstanden')
            $mdDialog.show(confirm);
        }
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
                        $scope.playlists.push({id: i, name: arr[i], vol_dev: []});
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
                    $scope.playlists[id].vol_dev = [];
                    for (var i = 1; i < 11; i++) {
                        if (arr[i].indexOf("/") != -1) {
                            var tmparr = arr[i].split("/");
                            $scope.playlists[id].vol_dev.push({
                                id: i - 1,
                                volumeL: parseInt(tmparr[0]),
                                volumeR: parseInt(tmparr[1])
                            })
                        } else {
                            $scope.playlists[id].vol_dev.push({id: i - 1, volume: parseInt(arr[i])})
                        }
                    }
                }

                $scope.devices.sort(function (a, b) {
                    return a.id > b.id;
                });
            });
    };

    $scope.deletePlaylist = function (id) {
        $http.get('api/helper.php?deleteplaylist&ID=' + (id + 1));
        $scope.playlists.splice(id, 1);
    };

    $scope.sortDevicesList = function () {
        $scope.devices.sort(function (a, b) {
            if (a.id > b.id) {
              return 1;
            }
            if (a.id < b.id) {
              return -1;
            }
            return 0;
        });
    };

    $scope.savePlaylist = function (id) {
        $scope.selectedPlaylist.volchanged = false;
        var volStr = "";
        for (var i = 0; i < 10; i++) {
            if ($scope.devices[i] != null && $scope.devices[i].betrieb == "geteilterbetrieb") {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=" + $scope.playlists[id].vol_dev[i].volumeL + "/" + $scope.playlists[id].vol_dev[i].volumeR);
            } else if ($scope.devices[i] != null && $scope.devices[i].betrieb == "deaktiviert") {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=0");
            } else {
                volStr += ("&VOL_DEV" + $scope.formatId(i + 1) + "=" + $scope.playlists[id].vol_dev[i].volume);
            }

        }

        $http.get('api/helper.php?saveplaylist' +
            '&ID=' + (id + 1) +
            '&VOL_BACKGROUND=' + $scope.playlists[id].vol_background + volStr)
            .success(function () {
                $scope.makeToast("Erfolgreich gespeichert!");
                //location.reload();
            });
    };

    $scope.savePlaylistName = function (id) {
        $scope.playlists[id].changed = false;
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
        if ($scope.playlists[$scope.playlists.length - 1] == undefined) {
            $scope.playlists.push({id: 0, name: ""});
        } else {
            $scope.playlists.push({id: ($scope.playlists[$scope.playlists.length - 1].id + 1), name: ""});
        }
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

    $scope.setAudioConfigurationDeactivated = function () {
        var id = $scope.formatId($scope.selectedDevice.id);
        $scope.selectedDevice = null;

        $http.get('api/helper.php?set_audio_configuration&dev=' + id + '&mode=' + 0)
            .success(function () {
                $scope.audioConfChanged = 1;
                $scope.showToast();
            });

        $scope.getDevices();
    };

    $scope.setLinkConfiguration = function () {
        var id = $scope.formatId($scope.selectedDevice.id);
        var linkedid = 10 + parseInt($scope.formatId($scope.selectedDevice.linktoDevice));


        $http.get('api/helper.php?set_audio_configuration&dev=' + id + '&mode=' + linkedid)
            .success(function () {
                $scope.audioConfChanged = 1;
                $scope.showToast();
            });

        $scope.getDevices();
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
        $scope.selectedDevice.changed = false;
        var id = $scope.formatId($scope.selectedDevice.id);

        if ($scope.selectedDevice.betrieb == 'normalbetrieb') {
            $http.get('api/helper.php?device_set&dev=' + id +
                '&NAME_NORMAL=' + $scope.selectedDevice.name +
                '&MAC_NORMAL=' + $scope.selectedDevice.mac +
                '&AP_NORMAL=' + $scope.checkAirplay($scope.selectedDevice.airplay) +
                '&SP_NORMAL=' + $scope.checkSpotify($scope.selectedDevice.spotify) +
                '&oac=' + $scope.selectedDevice.oac);
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
                '&SPre_GETEILT=' + $scope.checkSpotify($scope.selectedDevice.spotifyR) +
                '&oac=' + $scope.selectedDevice.oac);
            $scope.playerConfChanged = 1;
        }

    };

    $scope.getDevices = function () {
        $http.get('api/helper.php?activedevices')
            .success(function (data) {
                var arr = data.split(";");
                $http.get('api/helper.php?mappeddevices')
                    .success(function (mapdata) {
                        var mapped = mapdata.split(";");
                        $scope.devicestmp = [];
                        for (var i = 0; i < arr.length; i++) {
                            (function (e) {
                                const tmp_id = (i + 1);
                                var offline = arr[i] != 1;
                                $http.get('api/helper.php?getdevice&dev=' + $scope.formatId(tmp_id))
                                    .success(function (data) {
                                        var dev = data.split(";");
                                        if ((offline && dev[0] != 0) || (!offline)) {
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
                                                    offline: offline,
                                                    betrieb: $betrieb,
                                                    name: dev[1],
                                                    mac: dev[4],
                                                    airplay: $airplayString,
                                                    spotify: $spotifyString,
                                                    vol: {},
                                                    eq: {},
                                                    path: dev[14],
                                                    oac: parseInt(dev[15]),
                                                    display: null,
                                                    manualOperation: 0,
                                                    isMuted: 0,
                                                    knx: {
                                                        gpstatus: null,
                                                        gpvolume: null
                                                    }
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
                                                    offline: offline,
                                                    betrieb: $betrieb,
                                                    nameL: dev[2],
                                                    nameR: dev[3],
                                                    macL: dev[5],
                                                    macR: dev[6],
                                                    airplayL: $airplayStringL,
                                                    airplayR: $airplayStringR,
                                                    spotifyL: $spotifyStringL,
                                                    spotifyR: $spotifyStringR,
                                                    vol: {},
                                                    eq: {},
                                                    path: dev[14],
                                                    oac: parseInt(dev[15]),
                                                    display: null,
                                                    manualOperation: 0,
                                                    isMuted: 0,
                                                    knx: {
                                                        gpstatusL: null,
                                                        gpvolumeL: null,
                                                        gpstatusR: null,
                                                        gpvolumeR: null
                                                    }
                                                });
                                            } else if (parseInt(dev[0]) > 10 && parseInt(dev[0]) <= 20) {
                                                $betrieb = "gekoppelt";
                                                $scope.devicestmp.push({
                                                    id: tmp_id,
                                                    offline: offline,
                                                    betrieb: $betrieb,
                                                    linktoDevice: parseInt(dev[0]) - 10,
                                                    vol: {},
                                                    eq: {},
                                                    display: null,
                                                    manualOperation: 0,
                                                    isMuted: 0,
                                                    knx: {
                                                        gpstatus: null,
                                                        gpvolume: null
                                                    }
                                                });
                                            } else {
                                                $betrieb = "deaktiviert";
                                                $scope.devicestmp.push({
                                                    id: tmp_id,
                                                    offline: offline,
                                                    betrieb: $betrieb,
                                                    vol: {},
                                                    eq: {},
                                                    path: dev[14],
                                                    oac: 1,
                                                    display: null,
                                                    manualOperation: 0,
                                                    isMuted: 0,
                                                    knx: {
                                                        gpstatus: null,
                                                        gpvolume: null
                                                    }
                                                });
                                            }

                                            $scope.devicestmp.sort(function (a, b) {
                                                return a.id > b.id;
                                            });

                                            if ($scope.devicestmp !== $scope.devices) {
                                                $scope.devices = $scope.devicestmp;
                                            }
                                        }
                                    });
                            })(i);
                        }

                    setTimeout(function afterXSeconds() {
                        console.log('waited 100 ms');
                        $scope.sortDevices();
                        $scope.createDeviceTree();
                        $scope.getDeviceOnlineCount();
                        $scope.getAllMuteStates();
                        $scope.getAllVol();
                        $scope.getKnxCallbacks();
                    }, 100);
                    });
            });

        $http.get('api/helper.php?getchangedconf')
            .success(function (data) {
                var arr = data.split(";");
                $scope.audioConfChanged = arr[0] == 1;

                $scope.playerConfChanged = arr[1] == 1;

            });

    };

    $scope.getDeviceOnlineCount = function() {
        var count = 0;
        for (var i = 0; i < $scope.devices.length; i++) {
            if (!$scope.devices[i].offline) {
                count++;
            }
        }
        $scope.deviceOnlineCount = count;
    };

    $scope.sortDevices = function() {
        if ($scope.devices !== undefined) {
            $scope.devices.sort(function(a, b) {
                return a.id - b.id;
            });
        }
    };

    $scope.createDeviceTree = function () {
        var devicetree = {p1: null, p2: null, p3: null, p4: null};
        $scope.devices.filter(function (elem, index, self) {
            return self[index].id == self.indexOf(elem).id;
        });
        for (var i = 0; i < $scope.devices.length; i++) {
            var fullpath = $scope.devices[i].path;
            var shortpath = fullpath.substring(fullpath.lastIndexOf("/1-1.") + 5, fullpath.length).slice(0, -6);
            var ports = shortpath.split('.');
            var leaf = null;
            for (var y = 0; y < ports.length; y++) {
                switch (ports[y]) {
                    case "1":
                        if (leaf == null) {
                            if (devicetree.p1 == null) {
                                $scope.devices[i].display = "InnoServer, Port: 1";
                                devicetree.p1 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = devicetree.p1;
                            }
                        }
                        break;
                    case "2":
                        if (leaf == null) {
                            if (devicetree.p2 == null) {
                                $scope.devices[i].display = "InnoServer, Port: 2";
                                devicetree.p2 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = devicetree.p2;
                            }
                        } else {
                            if (leaf.p2 == null) {
                                $scope.devices[i].display = "InnoAmp " + $scope.formatId(leaf.dev.id) + ", Port: 2";
                                leaf.p2 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = leaf.p2;
                            }
                        }
                        break;
                    case "3":
                        if (leaf == null) {
                            if (devicetree.p3 == null) {
                                $scope.devices[i].display = "InnoServer, Port: 3";
                                devicetree.p3 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = devicetree.p3;
                            }
                        } else {
                            if (leaf.p3 == null) {
                                $scope.devices[i].display = "InnoAmp " + $scope.formatId(leaf.dev.id) + ", Port: 3";
                                leaf.p3 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = leaf.p3;
                            }
                        }
                        break;
                    case "4":
                        if (leaf == null) {
                            if (devicetree.p4 == null) {
                                $scope.devices[i].display = "InnoServer, Port: 4";
                                devicetree.p4 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = devicetree.p4;
                            }
                        } else {
                            if (leaf.p4 == null) {
                                $scope.devices[i].display = "InnoAmp " + $scope.formatId(leaf.dev.id) + ", Port: 4";
                                leaf.p4 = {dev: $scope.devices[i], p2: null, p3: null, p4: null};
                            } else {
                                leaf = leaf.p4;
                            }
                        }
                        break;
                }
            }
        }
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

    $scope.getShairplayInstance = function () {
      $http.get('api/helper.php?getshairplayinstance')
        .success(function (data) {
          $scope.shairinstances = data;
        });
    };

    $scope.getSysInfo = function () {
        $http.get('api/helper.php?getsysinfo')
            .success(function (data) {
                var arr = data.split(";");
                if (data != 0 && arr[0] != "none") {
                    $scope.sysinfo.cpu = arr[0];
                    $scope.sysinfo.ram = arr[1];
                    if (!arr[2].indexOf(":") != -1) {
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
                    $scope.sysinfo.cputemp = arr[6];
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
                        if (arr[i + 1].indexOf("/") != -1) {
                            var tmparr = arr[i + 1].split("/");
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
        $scope.devices.sort(function (a, b) {
            return a.id > b.id;
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
        var update = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Update auf neue Version! Der Server wird neu gestartet, dies kann mehrere Minuten dauern!.')
            .ariaLabel('Update!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(update).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
            $scope.updatestatus = '0% - starting update';
            $http.get('api/helper.php?update').success(function () {
                location.href = "/scripts/reboot.php?update=true"
            });
        });
    };

    $scope.deleteGeneratedTTS = function () {
        document.getElementById("loadingsymbol").style.display = "block";
        $http.get('api/helper.php?deleteGeneratedTTS').success(function () {
            document.getElementById("loadingsymbol").style.display = "none";
        });
    };

    $scope.fullUpdate = function () {
        var update = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Es werden alle Updates erneut installiert! Der Server wird neu gestartet, dies kann mehrere Minuten dauern!.')
            .ariaLabel('Update!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(update).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
            $scope.updatestatus = '0% - starting update';
            $http.get('api/helper.php?fullupdate').success(function () {
                location.href = "/scripts/reboot.php?update=true"
            });
        });
    };

    $scope.latestUpdate = function () {
        var update = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Es werden das letzte Update erneut installiert! Der Server wird neu gestartet, dies kann mehrere Minuten dauern!.')
            .ariaLabel('Update!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(update).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";
            $scope.updatestatus = '0% - starting update';
            $http.get('api/helper.php?latestupdate').success(function () {
                location.href = "/scripts/reboot.php?update=true"
            });
        });
    };

    $scope.getUpdateRunning = function() {
        $http.get('api/helper.php?updaterunning').success(function (data) {
            if (data === "\n" || data === "0\n") {
                document.getElementById("loadingsymbol").style.display = "none";
            } else {
                document.getElementById("loadingsymbol").style.display = "block";
                $http.get('api/helper.php?updatestatus').success(function (status) {
                    $scope.updatestatus = status;
                });
            }
        });
    };

    $scope.fixDependencies = function () {
        document.getElementById("loadingsymbol").style.display = "block";
        $http.get('api/helper.php?fixDependencies').success(function () {
            location.reload();
        });
    };

    $scope.updateKernel = function () {
        var update = $mdDialog.confirm()
            .title('Achtung!')
            .textContent('Der Kernel wird aktualisiert. Hier können in seltenen Fällen Komplikationen auftreten ' +
            'und dadurch zu einem fehlerhaften System führen. Das Kernel-Update muss nicht ausgeführt werden und ' +
            'durch das Drücken des "Verstanden"-Button erklären Sie, dass Sie selbst die Verantwortung für ein ' +
            'fehlerhaftes System nach dem Update tragen.')
            .ariaLabel('Update!')
            .targetEvent()
            .ok('Verstanden')
            .cancel('Abbrechen');
        $mdDialog.show(update).then(function () {
            document.getElementById("loadingsymbol").style.display = "block";

            $http.get('api/helper.php?updateKernel').success(function () {
                location.href = "/scripts/reboot.php?update=true"
            });
        });
    };

    $scope.updateBeta = function () {
      var update = $mdDialog.confirm()
          .title('Bist du sicher?')
          .textContent('Hiermit wird eine Beta-Version des Systems installiert, diese enthaltet neueste ' +
            'Features kann aber auch vereinzelte Bugs beinhalten.')
          .ariaLabel('Update!')
          .targetEvent()
          .ok('Update')
          .cancel('Abbrechen');
      $mdDialog.show(update).then(function () {
          document.getElementById("loadingsymbol").style.display = "block";

          $http.get('api/helper.php?updateBeta').success(function () {
              location.href = "/scripts/reboot.php?update=true"
          });
      });
  };


    $scope.saveNetworkMount = function () {
        document.getElementById("loadingsymbol").style.display = "block";
        if ($scope.options == undefined) {
            $scope.options = "";
        }
        $http.get('api/helper.php?savenetworkmount' +
            '&path=' + $scope.networkmount.path +
            '&mountpoint=' + $scope.networkmount.mountpoint +
            '&type=' + $scope.networkmount.type +
            '&options=' + $scope.networkmount.options)
            .success(function (data) {
                console.log(data);
                if (data.includes("error")) {
                  document.getElementById("loadingsymbol").style.display = "none";
                  var error = $mdDialog.confirm()
                      .title('Fehler!')
                      .textContent('Bei der Einbindung des Netzwerkspeichers ist ein Fehler aufgetreten!' +
                        ' Vergewissern Sie sich ob Ihre Daten korrekt sind. Error: ' + data)
                      .ariaLabel('Fehler')
                      .targetEvent()
                      .ok('OK');
                  $mdDialog.show(error);
                } else {
                  location.reload();
                }
            });
    };

    $scope.getNetworkMount = function () {
        $http.get('api/helper.php?netfs')
            .success(function (data) {
                $scope.netfs = data.split('\n');
                $scope.netfs.splice(-1,1);
                for (var i = 0; i < $scope.netfs.length; i++) {
                  $scope.netfs[i] = $scope.netfs[i].trim();
                }
            });
        $http.get('api/helper.php?get_netmount')
            .success(function (data) {
                $scope.networkmount.list = [];
                var rawtext = data.split('\n');
                for (var i = 0; i < rawtext.length - 1; i++) {
                    if (rawtext[i] != "") {
                        var rawline = rawtext[i].split(';');
                        $scope.networkmount.list.push({
                            dir: rawline[0],
                            net: rawline[1],
                            fs: rawline[2],
                            fstab: rawline[3]
                        });
                    }
                }
            });
    };

    $scope.removeNetworkMount = function (entry) {
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Der Netzwerkspeicher wird entfernt.')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            $http.get('api/helper.php?removenetworkmount' +
                '&path=' + entry.net +
                '&mountpoint=' + entry.dir +
                '&type=' + entry.fs +
                '&fstab=' + entry.fstab)
                .success(function () {
                    location.reload();
                });
        }, function () {

        });
    };

    $scope.saveItunes = function() {
      document.getElementById("loadingsymbol").style.display = "block";
      $http.get('api/helper.php?saveitunesmount' +
                '&path=' + $scope.ituneslib.path +
                '&user=' + $scope.ituneslib.user +
                '&pass=' + $scope.ituneslib.password)
                .success(function (data) {
                    document.getElementById("loadingsymbol").style.display = "none";
                    if(data.includes("error")) {
                      var error = $mdDialog.confirm()
                          .title('Fehler!')
                          .textContent('Bei der Einbindung von iTunes ist ein Fehler aufgetreten!' +
                            ' Vergewissern Sie sich ob Ihre Daten korrekt sind. Error: ' + data)
                          .ariaLabel('Fehler')
                          .targetEvent()
                          .ok('OK');
                      $mdDialog.show(error);
                    }
                });
    };

    $scope.refreshItunes = function() {
      document.getElementById("loadingsymbol").style.display = "block";
      $http.get('api/helper.php?refreshitunes')
          .success(function(data) {
            document.getElementById("loadingsymbol").style.display = "none";
          });
    };

    $scope.deleteItunes = function() {
      document.getElementById("loadingsymbol").style.display = "block";
      $http.get('api/helper.php?removeitunesmount')
          .success(function(data) {
            document.getElementById("loadingsymbol").style.display = "none";
          });
    };


    $scope.reset = function () {
        //noinspection JSUnresolvedFunction,JSValidateTypes
        var confirm = $mdDialog.confirm()
            .title('Bist du sicher?')
            .textContent('Die Konfiguration wird zurückgesetzt.')
            .ariaLabel('Der Server ist die Zeit nicht erreichbar!')
            .targetEvent()
            .ok('Ok')
            .cancel('Abbrechen');
        $mdDialog.show(confirm).then(function () {
            var resetstr = "";


            if ($scope.resetcb.network == true) {
                resetstr += "&network";
            }
            if ($scope.resetcb.usb == true) {
                resetstr += "&usb";
            }
            if ($scope.resetcb.playlists == true) {
                resetstr += "&playlists"
            }
            $http.get('api/helper.php?reset' + resetstr)
                .success(function (data) {

                    if (data.indexOf("network") != -1) {
                        location.href = "scripts/reboot.php?dhcp=dhcp";
                    } else {
                        location.href = "scripts/reboot.php";
                    }
                });

        }, function () {

        });
    };

    //Interval für System Info
    $scope.getUpdateValidation();
    $scope.getShairplayInstance();
    $scope.getUpdateRunning();
    $scope.readSystemStates();
    // 4 sec
    $interval($scope.getSysInfo, 4000);
    // 5 sec
    $interval($scope.readSystemStates, 5000);
    // 10 sec
    $interval($scope.getUpdateRunning, 10000);
    // 10 sec
    $interval($scope.getShairplayInstance, 10000);
    // 30 sec
    $interval($scope.checkLmsStatus, 30000);
    $scope.getDevices();
    $scope.getLogPorts();
});

if (!String.prototype.startsWith) {
    String.prototype.startsWith = function (searchString, position) {
        position = position || 0;
        return this.indexOf(searchString, position) === position;
    };
}

//polyfill string includes
if (!String.prototype.includes) {
  String.prototype.includes = function(search, start) {
    'use strict';
    if (typeof start !== 'number') {
      start = 0;
    }

    if (start + search.length > this.length) {
      return false;
    } else {
      return this.indexOf(search, start) !== -1;
    }
  };
}

if (!Array.from) {
  Array.from = (function () {
    var toStr = Object.prototype.toString;
    var isCallable = function (fn) {
      return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
    };
    var toInteger = function (value) {
      var number = Number(value);
      if (isNaN(number)) { return 0; }
      if (number === 0 || !isFinite(number)) { return number; }
      return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
    };
    var maxSafeInteger = Math.pow(2, 53) - 1;
    var toLength = function (value) {
      var len = toInteger(value);
      return Math.min(Math.max(len, 0), maxSafeInteger);
    };

    // The length property of the from method is 1.
    return function from(arrayLike/*, mapFn, thisArg */) {
      // 1. Let C be the this value.
      var C = this;

      // 2. Let items be ToObject(arrayLike).
      var items = Object(arrayLike);

      // 3. ReturnIfAbrupt(items).
      if (arrayLike == null) {
        throw new TypeError("Array.from requires an array-like object - not null or undefined");
      }

      // 4. If mapfn is undefined, then let mapping be false.
      var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
      var T;
      if (typeof mapFn !== 'undefined') {
        // 5. else
        // 5. a If IsCallable(mapfn) is false, throw a TypeError exception.
        if (!isCallable(mapFn)) {
          throw new TypeError('Array.from: when provided, the second argument must be a function');
        }

        // 5. b. If thisArg was supplied, let T be thisArg; else let T be undefined.
        if (arguments.length > 2) {
          T = arguments[2];
        }
      }

      // 10. Let lenValue be Get(items, "length").
      // 11. Let len be ToLength(lenValue).
      var len = toLength(items.length);

      // 13. If IsConstructor(C) is true, then
      // 13. a. Let A be the result of calling the [[Construct]] internal method of C with an argument list containing the single item len.
      // 14. a. Else, Let A be ArrayCreate(len).
      var A = isCallable(C) ? Object(new C(len)) : new Array(len);

      // 16. Let k be 0.
      var k = 0;
      // 17. Repeat, while k < len… (also steps a - h)
      var kValue;
      while (k < len) {
        kValue = items[k];
        if (mapFn) {
          A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
        } else {
          A[k] = kValue;
        }
        k += 1;
      }
      // 18. Let putStatus be Put(A, "length", len, true).
      A.length = len;
      // 20. Return A.
      return A;
    };
  }());
}
