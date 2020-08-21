<div ng-controller="TaxEditController as taxEditCtrl" class="lw-dialog">
   	<!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Edit Tax' )  ?></h3>
    </div>
	<!-- /main heading --> 

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="taxEditCtrl.[[ taxEditCtrl.ngFormName ]]" 
			ng-submit="taxEditCtrl.submit()" 
			novalidate>

			<div class="form-row">
                <div class="form-group col-md-4">
				<!-- Country -->
				<lw-form-field field-for="country" label="<?= __tr( 'Country' ) ?>"> 
					<selectize config='taxEditCtrl.countries_select_config' class="lw-form-field form-control" name="country" ng-model="taxEditCtrl.taxData.country" options='taxEditCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ng-required="true"></selectize>
				</lw-form-field>
				<!-- /Country -->
                </div>

                <div class="form-group col-md-4">
    				<!-- Label -->
    				<lw-form-field field-for="label" label="<?= __tr( 'Label' ) ?>">
                        <div class="input-group"> 
        					<input type="text" 
        						class="lw-form-field form-control"
        						name="label"
        						ng-required="true"
        						autofocus
        						ng-model="taxEditCtrl.taxData.label" 
        					/>
                            <div class="input-group-append">
                            <a href class="lw-btn btn btn-secondary" lw-transliterate entity-type="taxes" entity-id="[[ taxEditCtrl.taxData._id ]]" entity-key="label" entity-string="[[ taxEditCtrl.taxData.label ]]" input-type="1">
                                <i class="fa fa-globe"></i>
                            </a>
                        </div>
                    </div>
    				</lw-form-field>
    				<!-- /Label -->
				</div>

                <div class="form-group col-md-4">
    				<!-- Type -->
    	            <lw-form-field field-for="type" label="<?= __tr('Type') ?>"> 
    	                <select class="lw-form-field form-control" 
    		                name="type" ng-model="taxEditCtrl.taxData.type" ng-options="type.id as type.name for type in taxEditCtrl.taxType" ng-required="true">
    		            </select>
    	            </lw-form-field>
    		        <!-- /Type -->
                </div>
		    </div>

			<!-- Charges -->
			<lw-form-field field-for="applicable_tax" label="<?= __tr( 'Applicable Tax' ) ?>" ng-if="taxEditCtrl.taxData.type != 3"> 
				<div class="input-group">
                    <div class="input-group-prepend" id="basic-addon1" ng-if="taxEditCtrl.taxData.type == 1">
                        <span class="input-group-text">[[taxEditCtrl.currencySymbol]]
                        </span>
                    </div>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="applicable_tax"
						ng-required="true"
						min="0.1"
						ng-model="taxEditCtrl.taxData.applicable_tax" 
					/>
                    <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text" id="basic-addon2" ng-if="taxEditCtrl.taxData.type == 1">[[taxEditCtrl.currency]]</span>
                        <span class="input-group-text" id="basic-addon2" ng-if="taxEditCtrl.taxData.type == 2">%</span>
                    </div>
				</div>
			</lw-form-field>
			<!-- /Charges -->

			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __tr( 'Notes' ) ?>">
                <a href lw-transliterate entity-type="taxes" entity-id="[[ taxEditCtrl.taxData._id ]]" entity-key="notes" entity-string="[[ taxEditCtrl.taxData.notes ]]" input-type="2">
                <i class="fa fa-globe"></i></a> 
				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="taxEditCtrl.taxData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="taxEditCtrl.taxData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
		<div class="lw-dotted-line"></div> 			
			<!-- action -->
            <div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="taxEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>