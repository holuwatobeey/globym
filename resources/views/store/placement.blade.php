<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Placement & Misc Settings') ?></h4>
</div>
<div ng-controller="PlacementSettingsController as placementSettingsCtrl">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="placementSettingsCtrl.[[ placementSettingsCtrl.ngFormName ]]" 
        novalidate>

		<div ng-if="placementSettingsCtrl.pageStatus">
			<div class="form-row">
                <div class="form-group col-md-6">
    				<div class="form-group">
    					<!-- Categories Placement -->
    			         <lw-form-selectize-field field-for="categories_menu_placement" label="<?= __tr( 'Categories Menu' ) ?>" class="lw-selectize">
    			            <selectize config='placementSettingsCtrl.categories_menu_placement_select_config' class="lw-form-field" name="categories_menu_placement" ng-model="placementSettingsCtrl.editData.categories_menu_placement" options='placementSettingsCtrl.menu_placement' placeholder="<?= __tr( 'Select Categories' ) ?>" ng-required="true"></selectize>
    			        </lw-form-selectize-field>
    		        	<!-- Categories Placement -->
    				</div>
				</div>

                <div class="form-group col-md-6">
    				<div class="form-group">
    			        <!-- Brand Menu -->
    			        <lw-form-selectize-field field-for="brand_menu_placement" label="<?= __tr( 'Brand Menu' ) ?>" class="lw-selectize">
    			            <selectize config='placementSettingsCtrl.brand_menu_placement_select_config' class="lw-form-field" name="brand_menu_placement" ng-model="placementSettingsCtrl.editData.brand_menu_placement" options='placementSettingsCtrl.menu_placement' placeholder="<?= __tr( 'Select Brand' ) ?>" ng-required="true"></selectize>
    			        </lw-form-selectize-field>
    			        <!-- Brand Menu -->
    				</div>
                </div>
			</div>

			<!-- Enable Credit Info -->
	        <lw-form-checkbox-field field-for="credit_info" label="<?= __tr( 'Enable Credit Info' ) ?>" ng-if="placementSettingsCtrl.pageStatus">
	            <input type="checkbox" 
	                 class="lw-form-field js-switch"
	            	ui-switch=""
	            	name="credit_info"
	            	ng-model="placementSettingsCtrl.editData.credit_info"/>
	        </lw-form-checkbox-field>
	        <!-- /Enable Credit Info -->
		
			<div>
				 <!--  google Analytics  -->
	            <lw-form-field field-for="addtional_page_end_content" v-label="<?= __tr('Page End Additionals') ?>" label="<?= __tr('Page End Additionals, May Use For Scripts Like Google Analytics etc.') ?>"> 
	                <textarea name="addtional_page_end_content" class="lw-form-field form-control"
	                 cols="10" rows="3" ng-model="placementSettingsCtrl.editData.addtional_page_end_content"></textarea>
	            </lw-form-field>
		        <!--  /google Analytics  -->
	        </div>

            <!-- Website notification -->
	        <div>
	            <lw-form-field field-for="global_notification" label="<?= __tr('Website Notification') ?>" ng-if="placementSettingsCtrl.pageStatus">
                    <a href lw-transliterate entity-type="misc_setting" entity-id="null" entity-key="global_notification" entity-string="[[ placementSettingsCtrl.editData.global_notification ]]" input-type="3"><i class="fa fa-globe"></i></a>

	                <div class="alert alert-info">
	                    <?= __tr('The message you add here will be displayed on all the public pages below header.') ?>
	                </div>
	                <textarea 
	                    name="global_notification" 
	                    class="lw-form-field form-control" 
	                    cols="30" 
	                    rows="10" 
	                    ng-minlength="6" 
	                    ng-model="placementSettingsCtrl.editData.global_notification" 
	                    lw-ck-editor >
	                    </textarea>
	            </lw-form-field>
	        </div>
	        <!-- Website notification -->

	    </div>
        <br>
	    <div class="modal-footer">
			<button type="submit" ng-click="placementSettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>