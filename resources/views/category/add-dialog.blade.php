<div class="lw-dialog" ng-controller="CategoryAddController as categoryAddCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block" ng-if="categoryAddCtrl.categoryStatus == false" >
        <h3 class="lw-header"><?=  __tr( 'Add New Category' )  ?></h3>
    </div>

    <div class="lw-section-heading-block" ng-if="categoryAddCtrl.categoryStatus == true" >
        <h3 class="lw-header" ng-bind="categoryAddCtrl.categoryName"></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
		name="categoryAddCtrl.[[ categoryAddCtrl.ngFormName ]]" 
		ng-submit="categoryAddCtrl.submit()" 
		novalidate>

		<!-- Name -->
		<lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
			<input type="name" 
				class="lw-form-field form-control"
				name="name"
				ng-required="true"
				autofocus
				ng-model="categoryAddCtrl.categoryData.name" 
			/>
		</lw-form-field>
		<!-- /Name -->

        <br>
		<!-- categories tree -->
	    <div class="form-group">
	        <label for="parent_id" class="control-label"><?= __tr("Parent Category") ?></label>
	        <div 
			    ng-model="categoryAddCtrl.categoryData.parent_cat" 
			    class="select fancytree-list" 
			    name="temp_row_id" 
			    lw-fancytree 
			    source='[[ categoryAddCtrl.categoryData.categories ]]'
			    listing-for='categories'
			    form-type='catAdd'
			    form-id='[[ categoryAddCtrl.categoryData.parent_cat ]]'
	        >
	        </div>
	    </div>
		<!-- /categories tree -->

		<!-- Status -->
        <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="status"
                ng-model="categoryAddCtrl.categoryData.status"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->
		
		<!-- action button -->
		<div class="modal-footer">
			<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Add') ?> <span></span></button>
			<button type="button" class="lw-btn btn btn-light" ng-click="categoryAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
		</div>
		<!-- /action button -->

	</form>
	<!-- /form section -->
</div>
