<div class="lw-dialog" ng-controller="BrandDetailDialogController as brandDetailCtrl">
	
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Brand Details' )  ?></h3>
    </div>
    <!-- /main heading -->

    <div class="card mb-4">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?= __tr('Name') ?>
                <span class="pull-right" ng-bind="brandDetailCtrl.brandData.name"></span>
            </li>
            <li class="list-group-item"><?= __tr('Status') ?>
                <span class="pull-right" ng-if="brandDetailCtrl.brandData.active == true"><?=  __tr('Active')  ?></span>
                <span ng-if="brandDetailCtrl.brandData.active == false" class="pull-right"><?=  __tr('Deactive')  ?></span>
            </li>
            <li ng-if="brandDetailCtrl.brandData.description != null" class="list-group-item"><?= __tr('Description - ') ?>
                <p ng-bind="brandDetailCtrl.brandData.description"></p>
            </li>
        </ul>
    </div>

	<!-- action button -->
	<div class="modal-footer">
   		<button type="button" class="lw-btn btn btn-light" ng-click="brandDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!-- /action button -->
</div>