<div ng-controller="ShippingEditController as shippingEditCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Edit Shipping Rule' )  ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="shippingEditCtrl.[[ shippingEditCtrl.ngFormName ]]" 
			ng-submit="shippingEditCtrl.submit()" 
			novalidate>
			
			<div class="form-row">
				<div class="col-lg-12 col-mg-12 col-sm-12">
		            <!-- Select Shipping Method -->
		            <lw-form-field field-for="shipping_type" label="<?= __tr( 'Select Shipping Method ' ) ?>">
		                <a href ng-click="shippingEditCtrl.goToShippingType()"><?= __tr( '(Manage Shipping Method)' ) ?></a>
		                <selectize config='shippingEditCtrl.shipping_type_select_config' class="lw-form-field form-control" name="shipping_type" ng-model="shippingEditCtrl.shippingData.shipping_type" options='shippingEditCtrl.shippingTypeList' placeholder="<?= __tr( 'Select Shipping Method' ) ?>" ></selectize>
		            </lw-form-field>
		            <!-- /Select Shipping Method -->
	            </div>

                <div class="col-lg-6 col-mg-6 col-sm-6">
    				<!-- Country -->
    				<lw-form-field field-for="country" label="<?= __tr( 'Country' ) ?>"> 
    					<input type="text" 
    						class="lw-form-field form-control"
    						name="country"
    						disabled="true" 
    						value="[[shippingEditCtrl.shippingData.country]]" 
    					/>
    				</lw-form-field>
    				<!-- /Country -->
				</div>

                <div class="col-lg-6 col-mg-6 col-sm-6">
    				<!-- Type -->
    	            <lw-form-field field-for="type" label="<?= __tr('Charge Type / Availability') ?>"> 
    	                <select class="lw-form-field form-control" 
    		                name="type" ng-model="shippingEditCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingEditCtrl.discountType" ng-required="true">
    		            </select>
    	            </lw-form-field>
    		        <!-- /Type -->
                </div>
			</div>
			
            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
        			<!-- Charges -->
        			<lw-form-field field-for="charges" label="<?= __tr( 'Charges' ) ?>" ng-if="shippingEditCtrl.shippingData.type != 3 && shippingEditCtrl.shippingData.type != 4"> 
        				<div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1" ng-if="shippingEditCtrl.shippingData.type == 1">
                                <span class="input-group-text">[[shippingEditCtrl.currencySymbol]]
                                </span>
                            </div>
        				  	<input type="number" 
        						class="lw-form-field form-control"
        						name="charges"
        						ng-required="true"
        						ng-model="shippingEditCtrl.shippingData.charges" 
        					/>
                            <div class="input-group-append" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon2" ng-if="shippingEditCtrl.shippingData.type == 1">[[shippingEditCtrl.currency]]</span>
                                <span class="input-group-text" id="basic-addon2" ng-if="shippingEditCtrl.shippingData.type == 2">%</span>
                            </div>
        				</div>
        			</lw-form-field>
        			<!-- /Charges -->
            </div>
 
            <div class="col-lg-6 col-mg-6 col-sm-6">
    			<!-- Free After Amount -->
    			<lw-form-field field-for="free_after_amount" label="<?= __tr( 'Free Shipping if Order Amount More than' ) ?>" v-label="<?= __tr( 'Shipping Free After Amount' ) ?>" ng-if="shippingEditCtrl.shippingData.type == 1"> 
    				<div class="input-group">
                        <div class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">[[shippingEditCtrl.currencySymbol]]
                            </span>
                        </div>
    					<input type="number" 
    						class="lw-form-field form-control"
    						name="free_after_amount"
    						ng-model="shippingEditCtrl.shippingData.free_after_amount" 
    					/>
                        <div class="input-group-append" id="basic-addon1">
                            <span class="input-group-text" id="basic-addon2">[[shippingEditCtrl.currency]]</span>
                        </div>
    				</div>
    			</lw-form-field>
    			<!-- /Free After Amount -->

    			<!-- Amount Cap -->
    			<lw-form-field field-for="amount_cap" label="<?= __tr( 'Maximum Shipping Amount' ) ?>" ng-if="shippingEditCtrl.shippingData.type == 2"> 
    				<div class="input-group">
                        <div class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">[[shippingEditCtrl.currencySymbol]]
                            </span>
                        </div>
    					<input type="number" 
    						class="lw-form-field form-control"
    						name="amount_cap"
    						min="0.1"
    						ng-model="shippingEditCtrl.shippingData.amount_cap" 
    					/>
                        <div class="input-group-append" id="basic-addon1">
                            <span class="input-group-text" id="basic-addon2">[[shippingEditCtrl.currency]]</span>
                        </div>
    				</div>
    			</lw-form-field>
    			<!-- /Amount Cap -->
            </div>
        </div>
			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __tr( 'Notes' ) ?>"> 
                <a href lw-transliterate entity-type="shipping" entity-id="[[ shippingEditCtrl.shippingData._id ]]" entity-key="notes" entity-string="[[ shippingEditCtrl.shippingData.notes ]]" input-type="2">
                <i class="fa fa-globe"></i></a>

				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-minlength="3" ng-maxlength="255" ng-model="shippingEditCtrl.shippingData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="shippingEditCtrl.shippingData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
            		
			<!-- action -->
            <div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="shippingEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>