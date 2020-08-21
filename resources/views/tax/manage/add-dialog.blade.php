<div ng-controller="TaxAddController as taxAddCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Add New Tax' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="taxAddCtrl.[[ taxAddCtrl.ngFormName ]]" 
			ng-submit="taxAddCtrl.submit()" 
			novalidate>
			
			<div class="form-row">
                <div class="form-group col-md-4">
    				<!-- Country -->
    				<lw-form-field field-for="country" label="<?= __tr( 'Country' ) ?>"> 
    					<selectize config='taxAddCtrl.countries_select_config' class="lw-form-field form-control" name="country" ng-model="taxAddCtrl.taxData.country" options='taxAddCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ng-required="true"></selectize>
    				</lw-form-field>
    				<!-- /Country -->
				</div>

                <div class="form-group col-md-4">
    				<!-- Label -->
    				<lw-form-field field-for="label" label="<?= __tr( 'Label' ) ?>"> 
    					<input type="text" 
    						class="lw-form-field form-control"
    						name="label"
    						ng-required="true"
    						ng-model="taxAddCtrl.taxData.label" 
    					/>
    				</lw-form-field>
    				<!-- /Label -->
				</div>

                <div class="form-group col-md-4">
    				<!-- Type -->
    	            <lw-form-field field-for="type" label="<?= __tr('Type') ?>"> 
    	                <select class="lw-form-field form-control" 
    		                name="type" ng-model="taxAddCtrl.taxData.type" ng-options="type.id as type.name for type in taxAddCtrl.taxType" ng-required="true">
    		            </select>
    	            </lw-form-field>
    		        <!-- /Type -->
                </div>
		    </div>

			<!-- Charges -->
			<lw-form-field field-for="applicable_tax" label="<?= __tr( 'Applicable Tax' ) ?>" ng-if="taxAddCtrl.taxData.type != 3"> 
				<div class="input-group">
                    <div class="input-group-prepend" id="basic-addon1" ng-if="taxAddCtrl.taxData.type == 1">
                        <span class="input-group-text">[[taxAddCtrl.currencySymbol]]
                        </span>
                    </div>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="applicable_tax"
						ng-required="true"
						min="0.1"
						ng-model="taxAddCtrl.taxData.applicable_tax" 
					/>
                    <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text" id="basic-addon2" ng-if="taxAddCtrl.taxData.type == 1">[[taxAddCtrl.currency]]</span>
                        <span class="input-group-text" id="basic-addon2" ng-if="taxAddCtrl.taxData.type == 2">%</span>
                    </div>
				</div>
			</lw-form-field>
			<!-- /Charges -->

			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __tr( 'Notes' ) ?>"> 
				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="taxAddCtrl.taxData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="taxAddCtrl.taxData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
		<div class="lw-dotted-line"></div> 			
			<!-- action -->
            <div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="taxAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>