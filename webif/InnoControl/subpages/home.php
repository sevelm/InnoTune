<?php
// Deprecated (Functions are in helper.php now)
if (isset($_GET['stop_lms'])) {
    exec("sudo /var/www/sudoscript.sh stop_lms");
}
if (isset($_GET['start_lms'])) {
    exec("sudo /var/www/sudoscript.sh start_lms");
}

$actual_kernel = (shell_exec("uname -r"));
$tb_kernel  = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}

?>

<!--suppress CssUnknownTarget -->
<style>
    .welcome-card-wide > .mdl-card__title {
        color: #fff;
        height: 200px;
        background: url('images/cover.png') center / cover;
    }

</style>

<div class="mdl-grid mdl-cell--6-col no-spacing">
    <div class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
        <div class="mdl-card__title"></div>
        <div class="mdl-card__menu">
            <b ng-if="sysinfo.cpu<'90'" style="color: white; font-size: 18px">Gut</b>
            <b ng-if="sysinfo.cpu>'90'" style="color: red; font-size: 18px">Ausgelastet</b>
        </div>
        <div ng-init="getSysInfo()" class="mdl-card__supporting-text">
            <ul class="demo-list-icon mdl-list" style="padding: 0">
                <li class="mdl-list__item">
                  <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         version="1.1" baseProfile="full" width="32"
                                                                         height="32" viewBox="0 0 32.00 32.00"
                                                                         enable-background="new 0 0 32.00 32.00"
                                                                         xml:space="preserve" style="vertical-align: unset">
                          <path d="M0 0h24v24H0z" fill="none"/>
                          <path fill="#757575" fill-opacity="1" stroke-linejoin="round"
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                      </svg></i>

                      Kernel:&nbsp;
                      <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                      <b><?php echo $actual_kernel; ?></b>
                  </span>
                </li>
                <li class="mdl-list__item">
                  <span class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                         version="1.1" baseProfile="full" width="32"
                                                                         height="32" viewBox="0 0 32.00 32.00"
                                                                         enable-background="new 0 0 32.00 32.00"
                                                                         xml:space="preserve" style="vertical-align: unset">
                          <path d="M0 0h24v24H0z" fill="none"/>
                          <path fill="#757575" fill-opacity="0" stroke-linejoin="round"
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                      </svg></i>

                      System:&nbsp;
                      <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                      <b>{{systemType}}</b>
                  </span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                           xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                           version="1.1" baseProfile="full" width="32"
                                                                           height="32" viewBox="0 0 32.00 32.00"
                                                                           enable-background="new 0 0 32.00 32.00"
                                                                           xml:space="preserve" style="vertical-align: unset">
	<path fill="#757575" fill-opacity="1" stroke-linejoin="round"
          d="M 6,4L 18,4L 18,5L 21,5L 21,7L 18,7L 18,9L 21,9L 21,11L 18,11L 18,13L 21,13L 21,15L 18,15L 18,17L 21,17L 21,19L 18,19L 18,20L 6,20L 6,19L 3,19L 3,17L 6,17L 6,15L 3,15L 3,13L 6,13L 6,11L 3,11L 3,9L 6,9L 6,7L 3,7L 3,5L 6,5L 6,4 Z M 11,15L 11,18L 12,18L 12,15L 11,15 Z M 13,15L 13,18L 14,18L 14,15L 13,15 Z M 15,15L 15,18L 16,18L 16,15L 15,15 Z "></path>
</svg></i>

                        CPU Auslastung:&nbsp;
                        <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                        <b>{{sysinfo.cpu}}%</b>
                    </span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon"><svg style="width:32px;height:32px; vertical-align: unset;" viewBox="0 0 32 32">
    <path fill="#757575" d="M17,17A5,5 0 0,1 12,22A5,5 0 0,1 7,17C7,15.36 7.79,13.91 9,13V5A3,3 0 0,1 12,2A3,3 0 0,1 15,5V13C16.21,13.91 17,15.36 17,17M11,8V14.17C9.83,14.58 9,15.69 9,17A3,3 0 0,0 12,20A3,3 0 0,0 15,17C15,15.69 14.17,14.58 13,14.17V8H11Z" />
</svg></i>

                        CPU Temperatur:&nbsp;
                        <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                        <b>{{sysinfo.cputemp}}°C</b>
                    </span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                        <i class="material-icons mdl-list__item-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                           xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                           version="1.1" baseProfile="full" width="32"
                                                                           height="32" viewBox="0 0 32.00 32.00"
                                                                           enable-background="new 0 0 32.00 32.00"
                                                                           xml:space="preserve" style="vertical-align: unset">
	<path fill="#757575" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round"
          d="M 16.9994,16.9981L 6.9994,16.9981L 6.9994,6.99807L 16.9994,6.99807M 20.9994,10.9981L 20.9994,8.99807L 18.9994,8.99807L 18.9994,6.99807C 18.9994,5.89406 18.1034,4.99807 16.9994,4.99807L 14.9994,4.99807L 14.9994,2.99807L 12.9994,2.99807L 12.9994,4.99807L 10.9994,4.99807L 10.9994,2.99807L 8.9994,2.99807L 8.9994,4.99807L 6.9994,4.99807C 5.8944,4.99807 4.9994,5.89406 4.9994,6.99807L 4.9994,8.99807L 2.9994,8.99807L 2.9994,10.9981L 4.9994,10.9981L 4.9994,12.9981L 2.9994,12.9981L 2.9994,14.9981L 4.9994,14.9981L 4.9994,16.9981C 4.9994,18.1031 5.8944,18.9981 6.9994,18.9981L 8.9994,18.9981L 8.9994,20.9981L 10.9994,20.9981L 10.9994,18.9981L 12.9994,18.9981L 12.9994,20.9981L 14.9994,20.9981L 14.9994,18.9981L 16.9994,18.9981C 18.1034,18.9981 18.9994,18.1031 18.9994,16.9981L 18.9994,14.9981L 20.9994,14.9981L 20.9994,12.9981L 18.9994,12.9981L 18.9994,10.9981M 12.9994,12.9981L 10.9994,12.9981L 10.9994,10.9981L 12.9994,10.9981M 14.9994,8.99807L 8.9994,8.99807L 8.9994,14.9981L 14.9994,14.9981L 14.9994,8.99807 Z "/>
</svg></i>
                        Ram Auslastung:&nbsp;
                        <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                        <b>{{sysinfo.ram}}%</b>
                    </span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon">storage</i>
                        Speicher belegt:&nbsp;
                        <div class="mdl-cell--hide-phone"><b>{{formatSizeUnits(sysinfo.diskspace)}} von {{formatSizeUnits(sysinfo.disksize)}} /&nbsp; </b></div>
                        <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                        <b>{{100 - sysinfo.diskpercent}}%</b>
                  </span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon">access_time</i>
                        Uptime:&nbsp;
                        <div class="mdl-layout-spacer mdl-cell--hide-desktop mdl-cell--hide-tablet"></div>
                        <b>{{sysinfo.uptime}}</b>
                  </span>
                </li>
            </ul>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="rebootDialog($event)"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Reboot
            </button>
        </div>
    </div>
    <div ng-init="showLmsSwitch()"
         class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell  mdl-cell--12-col mdl-cell--top">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text mdl-cell--hide-phone">Steuerung Logitech Media Server</h2>
            <h2 class="mdl-card__title-text mdl-cell--hide-desktop mdl-cell--hide-tablet">Steuerung LMS</h2>
        </div>
        <div class="mdl-card__supporting-text">
            Systemlink Logitech Media Server:
            <?php
            $host = 'localhost';
            if ($socket = @ fsockopen($host, 9000, $errno, $errstr, 30)) {
                echo "<a href=\"http://";
                if ($_SERVER['SERVER_ADDR'] == '::1')
                    echo "127.0.0.1:9000\" target=\"_blank\">";
                else
                    echo $_SERVER['SERVER_ADDR'] . ':9000" target="_blank">';
                ?>
                <?php
                if ($_SERVER['SERVER_ADDR'] == '::1')
                    echo "127.0.0.1:9000";
                else
                    echo $_SERVER['SERVER_ADDR'] . ':9000';
            } else {
                echo '<b>offline.</b>';
            }
            ?>
            </a>
            <br>
            <md-switch ng-change="changedLmsSwitch()" ng-model="lmsCB" ng-true-value="1" ng-false-value="0"
                       aria-label="Switch 1">
                LMS bei Systemstart
            </md-switch>

        </div>
        <div class="mdl-card__actions mdl-card--border">
            <button ng-click="stop_lms()" name="lms_stop"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Stop LMS
            </button>
            <button ng-click="start_lms()" name="lms_start"
                    class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                Start LMS
            </button>
        </div>
        <div class="mdl-card__menu">
            <a onclick="location.reload()" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                <i class="material-icons">refresh</i>
                <md-tooltip md-direction="bottom">Refresh</md-tooltip>
            </a>
        </div>
    </div>
</div>
<div class="mdl-grid mdl-cell--6-col no-spacing">
  <div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">InnoServer</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <?php if ($tinkerboard) {
            echo "<span class=\"mdl-badge\" data-badge=\"{{deviceOnlineCount-1}}\">InnoAmp's erkannt </span>";
        } else {
            echo "<span class=\"mdl-badge\" data-badge=\"{{deviceOnlineCount}}\">InnoAmp's erkannt </span>";
        } ?>

        <ul class="device-list mdl-list">
            <li ng-repeat="device in devices | orderBy : 'id'" class="mdl-list__item mdl-list__item--two-line">
                <span ng-if="!device.offline" class="mdl-list__item-primary-content">
                    <img ng-src="./images/{{device.betrieb}}.png" class="mdl-list__item-avatar"
                         style="border-radius: 0; background-color: transparent;">

                    <?php if ($tinkerboard) {
                        echo "<span ng-if=\"device.id!=1\">InnoAmp {{formatId(device.id-1)}}</span>
                    <span ng-if=\"device.id==1\">HDMI-Audio</span>";
                    } else {
                        echo "<span>InnoAmp {{formatId(device.id)}}</span>";
                    } ?>

                    <span ng-if="device.betrieb=='normalbetrieb'"
                          class="mdl-list__item-sub-title">{{device.name}}</span>
                    <span ng-if="device.betrieb=='geteilterbetrieb'" class="mdl-list__item-sub-title">{{device.nameL}} - {{device.nameR}}</span>
                    <span ng-if="device.betrieb=='gekoppelt'" class="mdl-list__item-sub-title">Mit <strong>{{devices[device.linktoDevice-1].name}}</strong> gekoppelt</span>
                    <span ng-if="device.betrieb=='deaktiviert'" class="mdl-list__item-sub-title">Deaktiviert</span>
                </span>
                <span ng-if="!device.offline" class="mdl-list__item-secondary-content">
                    <span class="mdl-list__item-secondary-info">Mac-Adresse</span>
                    <span ng-if="device.betrieb=='normalbetrieb'"
                          class="mdl-list__item-secondary-action">{{device.mac}}</span>
                    <span ng-if="device.betrieb=='geteilterbetrieb'" class="mdl-list__item-secondary-action">{{device.macL}} - {{device.macR}}</span>
                    <span ng-if="device.betrieb=='gekoppelt'" class="mdl-list__item-secondary-action">{{devices[device.linktoDevice-1].mac}}</span>
                    <span ng-if="device.betrieb=='deaktiviert'" class="mdl-list__item-secondary-action">-</span>
                </span>

                <span ng-if="device.offline" class="mdl-list__item-primary-content">
                    <img ng-src="./images/deaktiviert.png" class="mdl-list__item-avatar"
                         style="border-radius: 0; background-color: transparent;">

                    <?php if ($tinkerboard) {
                        echo "<span ng-if=\"device.id!=1\">InnoAmp {{formatId(device.id-1)}}</span>
                    <span ng-if=\"device.id==1\">HDMI-Audio</span>";
                    } else {
                        echo "<span>InnoAmp {{formatId(device.id)}} (Offline)</span>";
                    } ?>

                    <span ng-if="device.betrieb=='normalbetrieb'"
                          class="mdl-list__item-sub-title">{{device.name}}</span>
                    <span ng-if="device.betrieb=='geteilterbetrieb'" class="mdl-list__item-sub-title">{{device.nameL}} - {{device.nameR}}</span>
                    <span ng-if="device.betrieb=='gekoppelt'" class="mdl-list__item-sub-title">Mit <strong>{{devices[device.linktoDevice-1].name}}</strong> gekoppelt</span>
                    <span ng-if="device.betrieb=='deaktiviert'" class="mdl-list__item-sub-title">Deaktiviert</span>
                </span>
                <span ng-if="device.offline" class="mdl-list__item-secondary-content">
                    <span class="mdl-list__item-secondary-info">Mac-Adresse</span>
                    <span ng-if="device.betrieb=='normalbetrieb'"
                          class="mdl-list__item-secondary-action">{{device.mac}}</span>
                    <span ng-if="device.betrieb=='geteilterbetrieb'" class="mdl-list__item-secondary-action">{{device.macL}} - {{device.macR}}</span>
                    <span ng-if="device.betrieb=='gekoppelt'" class="mdl-list__item-secondary-action">{{devices[device.linktoDevice-1].mac}}</span>
                    <span ng-if="device.betrieb=='deaktiviert'" class="mdl-list__item-secondary-action">-</span>
                </span>
            </li>
        </ul>
    </div>
    <div class="mdl-card__menu">
        <a ng-click="getDevices()" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">refresh</i>
            <md-tooltip md-direction="bottom">Geräte neu laden</md-tooltip>
        </a>
    </div>
  </div>
</div>
