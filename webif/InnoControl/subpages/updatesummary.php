<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell--top mdl-cell mdl-cell--6-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">Installationsstatus</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <div>
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
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
            ng-click="rebootAndValidate()">
        Reboot
    </button>
  </div>
</div>

<div class="demo-card-wide mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col">
  <div class="mdl-card__title">
    <h2 class="mdl-card__title-text">Update-Log</h2>
  </div>
  <div class="mdl-card__supporting-text">
    <?php echo str_replace("\n", "<br>", shell_exec("cat /var/www/InnoControl/log/update.log")); ?>
  </div>
  <div class="mdl-card__actions mdl-card--border">
    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect"
       href="/log/update.log">
        Speichern
    </a>
  </div>
</div>
