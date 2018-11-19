<div class="mdl-cell--top mdl-cell mdl-cell--6-col">
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--12-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">Installationsstatus</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <div ng-if="updateErrors.length > 0">
      <md-list flex="" ng-init="getUpdateValidation()">
        <md-list-item ng-repeat="error in updateErrors" class="md-2-line">
          <div class="md-list-item-text">
            <h3>{{error.package}}</h3>
            <p ng-if="error.status=='installed'">{{error.status}} - Neustart erforderlich</p>
            <p ng-if="error.status!='installed'">{{error.status}}</p>
          </div>
          <md-button ng-if="error.status!='installed'" class="md-secondary"
                     ng-click="reinstallPackage(error)"
                     ng-attr-id="{{ 'button' + error.package}}">
            Installieren
          </md-button>
          <md-progress-circular md-mode="indeterminate" class="md-secondary" style="display: none" ng-attr-id="{{ 'spinner' + error.package}}">
          </md-progress-circular>
        </md-list-item>
      </md-list>
    </div>
    <div ng-if="updateErrors.length == 0">
      Alle Pakete wurden installiert.
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="rebootAndValidate()">
        Reboot
    </button>
    <button ng-click='update()' class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
        Update
    </button>
  </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">Logitech Media Server</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <h5>Status</h5>
    <?php
    $host = 'localhost';
    if ($socket = @ fsockopen($host, 9000, $errno, $errstr, 30)) {
      echo "online";
    } else {
      echo 'offline';
    }
    ?>
    <h5>Reinstallations-Log</h5>
    <div ng-if="collapseRL == false">
      <?php echo str_replace("\n", "<br>", shell_exec("head -5 /var/www/InnoControl/log/reinstall_lms.log")); ?>
    </div>
    <div ng-if="collapseRL == true">
      <?php echo str_replace("\n", "<br>", shell_exec("cat /var/www/InnoControl/log/reinstall_lms.log")); ?>
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="reinstallLms()">
            Reinstall
    </button>
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
       href="/log/reinstall_lms.log">
        Speichern
    </a>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseRL()" ng-if="collapseRL == false">
      Mehr Anzeigen
    </button>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseRL()" ng-if="collapseRL == true">
      Weniger Anzeigen
    </button>
  </div>
</div>
</div>

<div class="mdl-cell mdl-cell--6-col">
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">Update-Log</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <div ng-if="collapseUL == false">
      <?php echo str_replace("\n", "<br>", shell_exec("head -5 /var/www/InnoControl/log/update.log")); ?>
    </div>
    <div ng-if="collapseUL != false">
      <?php echo str_replace("\n", "<br>", shell_exec("cat /var/www/InnoControl/log/update.log")); ?>
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
       href="/log/update.log">
        Speichern
    </a>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseUL()" ng-if="collapseUL == false">
      Mehr Anzeigen
    </button>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseUL()" ng-if="collapseUL == true">
      Weniger Anzeigen
    </button>
  </div>
</div>
<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">LMS-Log</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <div ng-if="collapseLL == false">
      <?php echo str_replace("\n", "<br>", shell_exec("head -5 /var/log/squeezeboxserver/server.log")); ?>
    </div>
    <div ng-if="collapseLL != false">
      <?php echo str_replace("\n", "<br>", shell_exec("cat /var/log/squeezeboxserver/server.log")); ?>
    </div>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
       href="/api/helper.php?lmslog">
        Speichern
    </a>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseLL()" ng-if="collapseLL == false">
      Mehr Anzeigen
    </button>
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="setCollapseLL()" ng-if="collapseLL == true">
      Weniger Anzeigen
    </button>
  </div>
</div>
</div>
