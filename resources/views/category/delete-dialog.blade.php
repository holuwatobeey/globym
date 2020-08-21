<div class="lw-dialog" ng-controller="CategoryDeleteController as categoryDeleteCtrl">

	<!-- main heading -->
	<div class="lw-text-center">
	<strong><h1><?=  __tr( 'Are You Sure ?' )  ?></h1></strong>
        <h5><?=  __tr( 'You want to Delete ' )  ?> <strong ng-bind="categoryDeleteCtrl.categoryName"></strong><?=  __tr(' Category, all the products belongs to this single category will be deleted as well, Please enter password to confirm..!!')  ?></h5>
    </div>
	<!-- /main heading -->
	
	 <form class="lw-form lw-ng-form"
	 	name="categoryDeleteCtrl.[[ categoryDeleteCtrl.ngFormName ]]" 
        ng-submit="categoryDeleteCtrl.submit(categoryDeleteCtrl.categoryID)" 
        novalidate>

		<!-- Password -->
        <lw-form-field field-for="password" label="<?=  __tr('Password')  ?>"> 
        	<div class="input-group">
	            <input type="password" 
	                  class="lw-form-field form-control"
	                  name="password"
	                  ng-minlength="6"
	                  ng-maxlength="30"
	                  ng-required="true" 
	                  ng-model="categoryDeleteCtrl.categoryData.password" />
            </div>
        </lw-form-field>
        <!-- Password -->

		<!-- action button -->
		<div class="lw-form-actions lw-text-center">
			<button type="submit" class="lw-btn btn btn-danger btn-sm" title="<?= __tr('Delete') ?>"><?= __tr('Yes, Delete it') ?> <span></span></button>
			<button type="button" class="lw-btn btn btn-light btn-sm" ng-click="categoryDeleteCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
		</div>
		<!-- /action button -->
	</form>

</div>