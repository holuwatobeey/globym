<div ng-controller="ShippingAddController as shippingAddCtrl" class="lw-dialog">
   <!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Add New Shipping Rule' )  ?></h3>
    </div>
	<!-- /main heading --> 

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="shippingAddCtrl.[[ shippingAddCtrl.ngFormName ]]" 
		ng-submit="shippingAddCtrl.submit()" 
		novalidate>

		<div class="form-row">
			<div class="col-lg-12 col-mg-12 col-sm-12">
				 <!-- Select Shipping Method -->
		        <lw-form-field field-for="shipping_type" label="<?= __tr( 'Select Shipping Method' ) ?>">
		            <a href ng-click="shippingAddCtrl.goToShippingType()"><?= __tr( '(Manage Shipping Method)' ) ?></a>
		            <selectize config='shippingAddCtrl.shipping_type_select_config' class="lw-form-field form-control" name="shipping_type" ng-model="shippingAddCtrl.shippingData.shipping_type" options='shippingAddCtrl.shippingTypeList' placeholder="<?= __tr( 'Select Shipping Method' ) ?>" ></selectize>
		        </lw-form-field>
		        <!-- /Select Shipping Method -->
	        </div>
            <div class="col-lg-6 col-mg-6 col-sm-6">
    			<!-- Country -->
    			<lw-form-field field-for="country" label="<?= __tr( 'Country' ) ?>"> 
    				<selectize config='shippingAddCtrl.countries_select_config' class="lw-form-field form-control" name="country" ng-model="shippingAddCtrl.shippingData.country" options='shippingAddCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ng-required="true"></selectize>
    			</lw-form-field>
    			<!-- /Country -->
			</div>

            <div class="col-lg-6 col-mg-6 col-sm-6">
    			<!-- Type -->
    	        <lw-form-field field-for="type" label="<?= __tr('Charge Type / Availability') ?>"> 
    	            <select class="lw-form-field form-control" 
    	                name="type" ng-model="shippingAddCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingAddCtrl.shippingType" ng-change="shippingAddCtrl.onChangeType(shippingAddCtrl.shippingData.type)" ng-required="true">
    	            </select>
    	        </lw-form-field>
    	        <!-- /Type -->
            </div>
	    </div>
	    
		<!-- Charges -->
        <div class="form-row">
            <div class="col-lg-6 col-mg-6 col-sm-6">

        		<lw-form-field field-for="charges" label="<?= __tr( 'Charges' ) ?>" ng-if="shippingAddCtrl.charges"> 
        			<div class="input-group">
                        <div class="input-group-prepend" id="basic-addon1" ng-if="shippingAddCtrl.shippingData.type == 1">
                            <span class="input-group-text">[[shippingAddCtrl.currencySymbol]]
                            </span>
                        </div>
        			  	<input type="number" 
        					class="lw-form-field form-control"
        					name="charges"
        					ng-required="true"
        					min="0.1"
        					ng-model="shippingAddCtrl.shippingData.charges" 
        				/>
                        <div class="input-group-append" id="basic-addon1">
                            <span class="input-group-text" ng-if="shippingAddCtrl.shippingData.type == 1">[[shippingAddCtrl.currency]]
                            </span>
                            <span class="input-group-text" ng-if="shippingAddCtrl.shippingData.type == 2">%
                            </span>
                        </div>
        			</div>
        		</lw-form-field>
        		<!-- /Charges -->
            </div>

            <div class="col-lg-6 col-mg-6 col-sm-6">
        		<!-- Free After Amount -->
        		<lw-form-field field-for="free_after_amount" label="<?= __tr( 'Free Shipping If Order Amount More Than' ) ?>" v-label="<?= __tr( 'Shipping Free After Amount' ) ?>" ng-if="shippingAddCtrl.freeAfterAmount"> 
        			<div class="input-group">
                        <div class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">[[shippingAddCtrl.currencySymbol]]
                            </span>
                        </div>
        				<input type="number" 
        					class="lw-form-field form-control"
        					name="free_after_amount"
        					ng-model="shippingAddCtrl.shippingData.free_after_amount" 
        				/>
                        <div class="input-group-append" id="basic-addon1">
                            <span class="input-group-text">[[shippingAddCtrl.currency]]
                            </span>
                        </div>
        			</div>
        		</lw-form-field>
        		<!-- /Free After Amount -->

                <!-- Maximum Shipping Amount -->
                <lw-form-field field-for="amount_cap" label="<?= __tr( 'Maximum Shipping Amount' ) ?>" ng-if="shippingAddCtrl.amountCap"> 
                    <div class="input-group">
                        <div class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">[[shippingAddCtrl.currencySymbol]]
                            </span>
                        </div>
                        <input type="number" 
                            class="lw-form-field form-control"
                            name="amount_cap"
                            min="0.1"
                            ng-model="shippingAddCtrl.shippingData.amount_cap" 
                        />
                        <div class="input-group-append" id="basic-addon1">
                            <span class="input-group-text">[[shippingAddCtrl.currency]]
                            </span>
                        </div>
                    </div>
                </lw-form-field>
                <!-- /Maximum Shipping Amount -->
            </div> 
        </div>

		<!-- notes -->
		<lw-form-field field-for="notes" label="<?= __tr( 'Notes' ) ?>"> 
			<textarea name="notes" class="lw-form-field form-control"
            ng-minlength="3"
            ng-maxlength="255"
             cols="10" rows="3" ng-model="shippingAddCtrl.shippingData.notes"></textarea>
		</lw-form-field>
		<!-- /notes -->

		<!-- Status -->
        <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="active"
                ng-model="shippingAddCtrl.shippingData.active"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->

		<!-- action -->
        <div class="modal-footer">
			<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
			<button type="button" class="lw-btn btn btn-light" ng-click="shippingAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
		</div>
		<!-- /action -->

	</form>
	<!-- /form action -->
</div>