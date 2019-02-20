<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 11.08.2016
 * Time: 16:15
 */
$actual_kernel = (shell_exec("uname -r"));
$tb_kernel = '4.4.73-rockchip';
$pos1 = strcasecmp($actual_kernel, $tb_kernel);

if ($pos1 == 1) {
    $tinkerboard = true;
}


?>
<style>
    label.input-checkbox {
        display: inline-block;
        line-height: 0;
        font-size: 0;
        margin: 0;
        cursor: pointer;
    }

    label.input-checkbox > svg {
        transition: -webkit-transform 0.2s ease;
        transition: transform 0.2s ease;
        transition: transform 0.2s ease, -webkit-transform 0.2s ease;
        pointer-events: none;
    }

    label.input-checkbox:active > svg {
        -webkit-transform: scale3d(0.9, 0.9, 1);
        transform: scale3d(0.9, 0.9, 1);
    }

    label.input-checkbox > input + svg .indeterminate {
        display: none;
    }

    label.input-checkbox > input + svg .checked {
        display: none;
    }

    label.input-checkbox > input:checked + svg .unchecked {
        display: none;
    }

    label.input-checkbox > input:checked + svg .checked {
        display: initial;
    }

    label.input-checkbox > input:checked + svg path {
        fill: #40c4ff;
    }

    label.input-checkbox > input:indeterminate + svg .unchecked,
    label.input-checkbox > input:checked:indeterminate + svg .unchecked {
        display: none;
    }

    label.input-checkbox > input:indeterminate + svg .checked,
    label.input-checkbox > input:checked:indeterminate + svg .checked {
        display: none;
    }

    label.input-checkbox > input:indeterminate + svg .indeterminate,
    label.input-checkbox > input:checked:indeterminate + svg .indeterminate {
        display: block;
    }

    label.input-checkbox > input:disabled + svg {
        cursor: default;
    }

    label.input-checkbox > input:disabled + svg:active {
        -webkit-transform: none;
        transform: none;
    }

    label.input-checkbox > input:disabled + svg path {
        fill: #40c4ff;
    }

    label.input-checkbox > input {
        display: block;
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
</style>

<div ng-init="selectDevice()" class="welcome-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--top">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">Geräte</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <devices></devices>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--8-col">
    <div class="mdl-card__title">
        <?php if ($tinkerboard) {
            echo "<h2 ng-if=\"selectedDevice.id==1\" class=\"mdl-card__title-text\">HDMI-Audio</h2>" .
                "<h2 ng-if=\"selectedDevice.id!=1&&selectedDevice!=null\" class=\"mdl-card__title-text\">InnoAmp {{formatId(selectedDevice.id-1)}}</h2>" .
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        } else {
            echo "<h2 class=\"mdl-card__title-text\" ng-if=\"selectedDevice!=null\">InnoAmp {{formatId(selectedDevice.id)}}</h2>" .
                "<h2 ng-if=\"selectedDevice==null\" class=\"mdl-card__title-text\">Soundkarte auswählen..</h2>";
        } ?>
    </div>
    <div class="mdl-card__supporting-text">
        <div ng-if="!selectedDevice" class="mdl-grid">
            <h2>Wählen sie ein Gerät aus!</h2>
        </div>

        <div ng-if="selectedDevice">
            <div class="mdl-grid">
                <?php /*
                        if($tinkerboard){
                            echo "<md-option ng-if=\"opt.id!=1\" ng-value=\"opt.id\" ng-repeat=\"opt in devices\">InnoAmp {{formatId(opt.id-1)}}</md-option>";
                        } else{
                            echo "<md-option ng-value=\"opt.id\" ng-repeat=\"opt in devices\">InnoAmp {{formatId(opt.id)}}</md-option>";
                        }*/ ?>
                <h5 class="mdl-cell mdl-cell--4-col" style="padding-top: 10px">Wiedergabe auf Gerät:</h5>
                <ul class="demo-list-control mdl-list mdl-cell--4-col md-no-underline" style="padding: 0;margin: 0">
                    <span ng-repeat="opt in devices | orderBy : 'id'">
                        <!-- Normalbetrieb -->
                        <li class="mdl-list__item" ng-if="opt.betrieb=='normalbetrieb'">
                            <span class="mdl-list__item-primary-content">
                                <span>
                                    {{opt.name +"&nbsp"}}
                                </span>
                                <span ng-if="opt.lineinStatus.trim()==formatId(selectedDevice.id).trim()"
                                      style="color: greenyellow;">(Play)</span>
                            </span>
                            <span ng-if="opt.betrieb=='normalbetrieb'" class="mdl-list__item-secondary-action">
                                <label class="input-checkbox" for="checkbox{{opt.id}}">
                                    <input id="checkbox{{opt.id}}" class="mdl-checkbox__input"
                                           type="checkbox"
                                           name="selectedDevices[]"
                                           value="{{opt.id}}"
                                           ng-click="toggleSelection(opt.id)">
                                    <svg width="18" height="18">
                                          <path class="checked"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M7,14L2,9l1.4-1.4L7,11.2l7.6-7.6L16,5L7,14z"/>
                                          <path class="indeterminate"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M14,10H4V8h10V10z"/>
                                          <path class="unchecked"
                                                d="M16,2v14H2V2H16 M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z"/>
                                    </svg>
                                </label>
                            </span>
                        </li>


                        <!--Geteilter Betrieb -->
                        <li class="mdl-list__item" ng-if="opt.betrieb=='geteilterbetrieb'">
                            <span class="mdl-list__item-primary-content">
                                <span>
                                   {{opt.nameL +"&nbsp"}}
                                </span>
                                <span ng-if="opt.lineinStatusli.trim()==formatId(selectedDevice.id).trim()"
                                      style="color: greenyellow;">(Play)</span>
                            </span>
                            <span ng-if="opt.betrieb=='geteilterbetrieb'" class="mdl-list__item-secondary-action"
                                  style="float: right">
                                <label class="input-checkbox" for="checkbox{{opt.id}}li">
                                    <input id="checkbox{{opt.id}}li" class="mdl-checkbox__input"
                                           type="checkbox"
                                           name="selectedDevices[]"
                                           value="{{opt.id}}li"
                                           ng-click="toggleSelection(opt.id+'li')">
                                    <svg width="18" height="18">
                                          <path class="checked"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M7,14L2,9l1.4-1.4L7,11.2l7.6-7.6L16,5L7,14z"/>
                                          <path class="indeterminate"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M14,10H4V8h10V10z"/>
                                          <path class="unchecked"
                                                d="M16,2v14H2V2H16 M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z"/>
                                    </svg>
                                </label>
                            </span>
                        </li>
                        <li class="mdl-list__item" ng-if="opt.betrieb=='geteilterbetrieb'">
                            <span class="mdl-list__item-primary-content">
                                <span>
                                   {{opt.nameR +"&nbsp"}}
                                </span>
                                <span ng-if="opt.lineinStatusre.trim()==formatId(selectedDevice.id).trim()"
                                      style="color: greenyellow;">(Play)</span>
                            </span>
                            <span ng-if="opt.betrieb=='geteilterbetrieb'" class="mdl-list__item-secondary-action"
                                  style="float: right">
                                <label class="input-checkbox" for="checkbox{{opt.id}}re">
                                    <input id="checkbox{{opt.id}}re" class="mdl-checkbox__input"
                                           type="checkbox"
                                           name="selectedDevices[]"
                                           value="{{opt.id}}re"
                                           ng-click="toggleSelection(opt.id+'re')">
                                    <svg width="18" height="18">
                                          <path class="checked"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M7,14L2,9l1.4-1.4L7,11.2l7.6-7.6L16,5L7,14z"/>
                                          <path class="indeterminate"
                                                d="M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z M14,10H4V8h10V10z"/>
                                          <path class="unchecked"
                                                d="M16,2v14H2V2H16 M16,0H2C0.9,0,0,0.9,0,2v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V2C18,0.9,17.1,0,16,0z"/>
                                    </svg>
                                </label>
                            </span>
                        </li>
                    </span>
                </ul>
            </div>
            <hr>
            <button ng-click="playlinein(selectedDevice.id)" ng-disabled="LineInSelection.length==0"
                    class="mdl-button mdl-js-button mdl-js-ripple-effect" style="height: 100%; left: 30%;">
                <i class="material-icons md-48" style="font-size: 60px">play_arrow</i>
            </button>
            <button ng-click="stoplinein()" ng-disabled="LineInSelection.length==0"
                    class="mdl-button mdl-js-button mdl-js-ripple-effect"
                    style="height: 100%; float: right; right: 30%">
                <i class="material-icons md-48 md-inactive" style="font-size: 60px">stop</i>
            </button>
        </div>
    </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">API LineIn:</h2>
    </div>
    <div class="mdl-card__supporting-text">
        <table>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone02:</b></td>
                <td style="padding:0 50px 0 50px;"></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_in=01&card_out=02</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone01:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_in=01&card_out=01</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 Stop:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_out=01</td>
            </tr>
            <tr>
                <td><b>Lautstärke von Line-In Zone01:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_out=01&volume=V</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone2 Links:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_in=01&card_out=02&mode=li</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone2 Rechts:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_in=01&card_out=02&mode=re</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone2 Links Stop:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_out=02li</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone2 Rechts Stop:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/api/helper.php?setlinein&card_out=02re</td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td>Alte Befehle (können weiterhin benutzt werden)<td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 auf Zone02:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?card_in=01&card_out=02</td>
            </tr>
            <tr>
                <td><b>Wiedergabe von Line-In Zone01 Stop:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?card_out=01</td>
            </tr>
            <tr>
                <td><b>Lautstärke von Line-In Zone01:</b></td>
                <td></td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?>/phpcontrol/linein.php?card_out=01&volume=V</td>
            </tr>
        </table>
    </div>
</div>
