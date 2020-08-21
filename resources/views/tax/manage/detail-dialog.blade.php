<div class="lw-dialog" ng-controller="TaxDetailController as taxDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Tax Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	
	<div class="card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?= __tr('Country') ?>
                <span ng-bind="taxDetailCtrl.taxData.country" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Tax Type') ?>
                <span ng-bind="taxDetailCtrl.taxData.type" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Label') ?>
                <span ng-bind="taxDetailCtrl.taxData.label" class="pull-right"></span>
            </li>
            <li class="list-group-item" ng-if="taxDetailCtrl.taxData.applicable_tax != null"><?= __tr('Applicable Tax') ?>
                <span ng-bind="taxDetailCtrl.taxData.applicable_tax" class="pull-right"></span>
                <span ng-bind="taxDetailCtrl.currencySymbol" ng-if="taxDetailCtrl.taxData.taxType == 1" class="pull-right"></span>
                <span ng-if="taxDetailCtrl.taxData.taxType == 2" class="pull-right">%</span>
            </li>
            <li class="list-group-item"><?= __tr('Status') ?>
                <span ng-if="taxDetailCtrl.taxData.active == true" class="pull-right">
                    <?=  __tr('Active')  ?>
                </span>
                <span ng-if="taxDetailCtrl.taxData.active == false" class="pull-right">
                    <?=  __tr('Inactive')  ?>
                </span>
            </li>
        </ul>
	</div>

	<!--  Notes  --> 
	<div class="card" ng-if="taxDetailCtrl.taxData.notes != null">
	   <div class="card-header"><strong><?=  __tr('Notes')  ?></strong></div>
	   <div class="card-body" ng-bind="taxDetailCtrl.taxData.notes"></div>
	</div>
	<!-- / Notes  --> 

	<!--  close dialog button  -->
	<div class="modal-footer">
   		<button type="button" class="lw-btn btn btn-light" ng-click="taxDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!--  /close dialog button  -->
</div>