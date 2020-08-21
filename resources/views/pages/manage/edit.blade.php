<div ng-controller="ManagePagesEditController as editPageCtrl" class="lw-dialog">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Edit Page & Menu' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
    <form class="lw-form lw-ng-form" 
        name="editPageCtrl.[[ editPageCtrl.ngFormName ]]" 
        novalidate>
      
        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>">
            <div class="input-group"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="title"
                  ng-required="true"
                  autofocus 
                  ng-model="editPageCtrl.pageData.title" />
                <div class="input-group-append">
                    <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="menu_pages" entity-id="[[ editPageCtrl.pageData.id ]]" entity-key="title" entity-string="[[ editPageCtrl.pageData.title ]]" input-type="1"><i class="fa fa-globe"></i></button>
                </div>
        </div>
        </lw-form-field>
        <!-- Title -->

        <div ng-if="editPageCtrl.pageData.type != 3">
            
            <!-- Description -->
           	<div ng-if="editPageCtrl.descriptionRequired">
            	<lw-form-field field-for="description" label="<?= __tr( 'Description' ) ?>"> 
                    <a href lw-transliterate entity-type="menu_pages" entity-id="[[ editPageCtrl.pageData.id ]]" entity-key="description" entity-string="[[ editPageCtrl.pageData.description ]]" input-type="3">
                    <i class="fa fa-globe"></i></a> 
                	<textarea name="description" class="lw-form-field form-control" ng-required="[[editPageCtrl.descriptionRequired ]]"
                 	cols="30" rows="10" min-length="6" ng-model="editPageCtrl.pageData.description" lw-ck-editor></textarea>
            	</lw-form-field>
            </div>
            <!-- Description -->
    		
    		<!-- Type -->
    		<div ng-if="editPageCtrl.openAsRequired">
    	        <lw-form-field field-for="type" label="<?= __tr( 'Type' ) ?>"> 
    	            <select class="form-control" 
    	                name="type" ng-model="editPageCtrl.pageData.open_as" ng-options="open_as.value as open_as.text for open_as in editPageCtrl.pageLink" ng-required="true">
    	            </select> 
    	        </lw-form-field>
    	    </div>
            <!-- /Type -->
        
    		<!-- Link -->
    		<div ng-if="editPageCtrl.externalLinkRequired">
            	<lw-form-field field-for="link" label="<?= __tr( 'Link' ) ?>"> 
                	<input type="text" 
    	              class="lw-form-field form-control"
    	              name="link"
    	              ng-required="[[ editPageCtrl.externalLinkRequired ]]" 
    	              ng-model="editPageCtrl.pageData.link" />
            	</lw-form-field>
            </div>
            <!-- Link -->

    		<!-- pages tree -->
    	    <div class="form-group">
    	        <label for="parent_id" class="control-label"><?= __tr("Parent Page") ?></label>
    	        <div 
    			    ng-model="editPageCtrl.pageData.parent_page" 
    			    class="select fancytree-list" 
    			    name="temp_row_id" 
    			    lw-fancytree 
    			    source='[[ editPageCtrl.pages ]]'
    			    listing-for='pages'
    			    form-type='catEdit'
    			    form-id='[[ editPageCtrl.pageData.id ]]'
    	        >
    	        </div>
    	    </div>
    		<!-- pages tree -->
    		
    		<div class="form-inline">

    			<!-- Active -->
    	        <lw-form-checkbox-field ng-if="editPageCtrl.pageData.id !== 1" field-for="active" label="<?= __tr( 'Active' ) ?>" advance="true">
    	            <input type="checkbox" 
    	                class="lw-form-field js-switch"
    	                name="active"
    	                ng-model="editPageCtrl.pageData.status"
    	                ui-switch="" />
    	        </lw-form-checkbox-field>
    	        <!-- /Active -->
                &nbsp;&nbsp;
    	        <!-- Add to menu -->
    	        <lw-form-checkbox-field field-for="add_to_menu" label="<?= __tr( 'Add to menu' ) ?>" advance="true">
    	            <input type="checkbox" 
    	                class="lw-form-field js-switch"
    	                name="add_to_menu"
    	                ng-model="editPageCtrl.pageData.add_to_menu"
    	                ui-switch="" />
    	        </lw-form-checkbox-field>
    	        <!-- /Add to menu -->
                &nbsp;&nbsp;
    	        <!-- Hide Sidebar -->
    	        <lw-form-checkbox-field ng-if="editPageCtrl.pageData.type == 1" field-for="hide_sidebar" label="<?= __tr( 'Hide Sidebar' ) ?>" advance="true">
    	            <input type="checkbox" 
    	                class="lw-form-field js-switch"
    	                name="hide_sidebar"
    	                ng-model="editPageCtrl.pageData.hide_sidebar"
    	                ui-switch="" />
    	        </lw-form-checkbox-field>
    	        <!-- /Hide Sidebar -->
    		</div><br>

        </div>

        <div ng-if="editPageCtrl.pageData.type == 3">
            <!-- Add to menu -->
            <lw-form-checkbox-field field-for="add_to_menu" label="<?= __tr( 'Add to menu' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="add_to_menu"
                    ng-model="editPageCtrl.pageData.add_to_menu"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Add to menu -->
        </div>

        <!-- Action -->
        <div class="modal-footer">
            <button type="submit" ng-click="editPageCtrl.update()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="button" ng-click="editPageCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /Action -->

    </form>
    <!-- /form section -->

</div>

