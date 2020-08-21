<div class="dialog-contents" ng-controller="CategoryEditController as categoryEditCtrl" class="lw-dialog">
	
	<!-- main heading -->
    <div class="lw-section-heading-block" ng-if="categoryEditCtrl.categoryStatus == false">
		<h3 class="lw-header"><?= __tr("Edit Category") ?></h3>
	</div>

	<div class="lw-section-heading-block" ng-if="categoryEditCtrl.categoryStatus == true" >
        <h3 class="lw-header"><?=  __tr( 'Edit Category in ' )  ?>[[ categoryEditCtrl.categoryName ]]</h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
    <form class="lw-form lw-ng-form" 
        name="categoryEditCtrl.[[ categoryEditCtrl.ngFormName ]]" 
        ng-submit="categoryEditCtrl.update()" 
        novalidate>

        <!-- Name -->
		<lw-form-field field-for="name" label="<?= __tr('Name') ?>">
            <div class="input-group">
    			<input type="name" 
    				class="lw-form-field form-control"
    				name="name"
    				ng-required="true"
    				autofocus
    				ng-model="categoryEditCtrl.categoryData.name" 
    			/>
                <div class="input-group-append">
                    <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="category" entity-id="[[ categoryEditCtrl.categoryId ]]" entity-key="name" entity-string="[[ categoryEditCtrl.categoryData.name ]]" input-type="1">
                        <i class="fa fa-globe"></i>
                    </button>
                </div>
            </div>
		</lw-form-field>
		<!-- /Name -->

        <br>
		<!-- categories tree -->
	    <div class="form-group">
	        <label for="parent_id" class="control-label"><?= __tr("Parent Category") ?></label>
	        <div 
	        	ng-model="categoryEditCtrl.categoryData.parent_cat"
	         	class="select fancytree-list"
	          	name="temp_row_id"
	           	lw-fancytree 
	           	source='[[ categoryEditCtrl.categoryData.categories ]]'
	           	listing-for='categories'
				form-type='catEdit'
				form-id='[[categoryEditCtrl.categoryData.id]]'
	           >
	       </div>
	    </div>
		<!-- /categories tree -->
		
		<!-- Status -->
        <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="status"
                ng-model="categoryEditCtrl.categoryData.active"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->

		<!-- action button -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="button" class="lw-btn btn btn-light" ng-click="categoryEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- action button -->
    </form>
    <!-- /form section -->

</div>