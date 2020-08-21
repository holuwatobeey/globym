<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Order Settings') ?></h4>
</div>
<div ng-controller="OrderSettingsController as orderSettingsCtrl">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form"
        ng-submit="orderSettingsCtrl.submit()"
		name="orderSettingsCtrl.[[ orderSettingsCtrl.ngFormName ]]" 
        novalidate>

    	<div ng-if="orderSettingsCtrl.pageStatus">
    
		    <fieldset class="lw-fieldset-2 mb-3">
		        <legend>
		              <?=  __tr('Order')  ?>
		        </legend>
	            
	            <!-- Enable Guest Order -->
	            <lw-form-checkbox-field 
	                field-for="enable_guest_order" 
	                label="<?= __tr( 'Enable Guest Order' ) ?>" ng-if="orderSettingsCtrl.pageStatus">
	                <input type="checkbox" 
	                  class="lw-form-field js-switch"
	                  ui-switch=""
	                  name="enable_guest_order"
	                  ng-model="orderSettingsCtrl.editData.enable_guest_order"/>
	            </lw-form-checkbox-field>
	            <!-- /Enable Guest Order -->

	            <div class="alert alert-info">
	                <?= __tr('If __guestLogin__ enabled then any user can proceed to submit order, on the submission of the order user account will be created.', [
	                        '__guestLogin__' => '<strong>Guest Login</strong>'
	                    ]) ?>
	            </div>

		        </lw-form-checkbox-field>
		        <!-- /Hide Sidebar On Order Summary Page -->
		       
	            <fieldset class="lw-fieldset-2">
	                <legend><?= __tr('Tax') ?></legend>


	            <!-- Apply Tax -->
	            <lw-form-radio-field field-for="apply_tax_after_before_discount" label="<?= __tr( 'Apply Tax' ) ?>"> 
	            	<label for="pwd2" class="mb-2 mr-sm-2"><?= __tr('Apply Tax') ?> :</label>
	                <div class="form-check-inline">
	                    <label class="form-check-label">
	                        <input  class="form-check-input" ng-model="orderSettingsCtrl.editData.apply_tax_after_before_discount" type="radio" name="apply_tax_after_before_discount" ng-value="1" ng-required="true"><?= __tr('Before Discount') ?>
	                    </label>
	                </div>

					<div class="form-check-inline">
	                    <label class="form-check-label">
	                        <input  class="form-check-input" ng-model="orderSettingsCtrl.editData.apply_tax_after_before_discount" type="radio" name="apply_tax_after_before_discount" ng-value="2" ng-required="true"><?= __tr('After Discount') ?>
	                    </label>
	                </div>
	            </lw-form-radio-field>
	            <!-- /Apply Tax -->

	            <!-- Calculate Tax as per -->
	            <lw-form-radio-field field-for="calculate_tax_as_per_shipping_billing" label="<?= __tr( 'Apply Tax' ) ?>"> 
	            	<label for="pwd2" class="mb-2 mr-sm-2"><?= __tr('Calculate Tax as per') ?> :</label>
	                <div class="form-check-inline">
	                    <label class="form-check-label">
	                        <input class="form-check-input" ng-model="orderSettingsCtrl.editData.calculate_tax_as_per_shipping_billing" type="radio" name="calculate_tax_as_per_shipping_billing" ng-value="1" ng-required="true"><?= __tr('Shipping Address') ?>
	                    </label>
					</div>
					<div class="form-check-inline">
	                    <label class="form-check-label">
	                        <input class="form-check-input" ng-model="orderSettingsCtrl.editData.calculate_tax_as_per_shipping_billing" type="radio" name="calculate_tax_as_per_shipping_billing" ng-value="2" ng-required="true"><?= __tr('Billing Address') ?>
	                    </label>
					</div>
	            </lw-form-radio-field>
	            <!-- /Calculate Tax as per --> 
	            </fieldset>
		   </fieldset>

	        <!-- Allow Customer Order Cancellation -->
	        <lw-form-checkbox-field field-for="allow_customer_order_cancellation" label="<?= __tr( 'Allow Customer Order Cancellation' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                ui-switch=""
	                name="allow_customer_order_cancellation"
	                ng-model="orderSettingsCtrl.editData.allow_customer_order_cancellation"/>
	        </lw-form-checkbox-field>
	        <!-- /Allow Customer Order Cancellation -->

	        <fieldset class="lw-fieldset-2" ng-if="orderSettingsCtrl.editData.allow_customer_order_cancellation">

	            <legend>
	                <?=  __tr('Order Cancellation')  ?>
	            </legend>

	            <?= __tr('User will be able to cancel order if it is not __completed__ or __delivered__ or __cancelled__ or select from dropdown', [
	                '__completed__' => '<strong>Completed</strong>',
	                '__delivered__' => '<strong>Delivered</strong>',
	                '__cancelled__' => '<strong>Cancelled</strong>'
	            ]) ?>

	            <!-- Cancellation Status -->
	             <lw-form-selectize-field field-for="order_cancellation_statuses" v-label="<?= __tr( 'Cancellation Status' ) ?>" class="lw-selectize">
	                <selectize config='orderSettingsCtrl.cancellation_statuses_config' class="lw-form-field" name="order_cancellation_statuses" ng-model="orderSettingsCtrl.editData.order_cancellation_statuses" options='orderSettingsCtrl.order_statuses' placeholder="<?= __tr( 'Select Status' ) ?>"></selectize>
	            </lw-form-selectize-field>
	            <!-- Cancellation Status -->

	        </fieldset>
        </div>
        <br>
        <div class="lw-dotted-line"></div> 
        <div class="modal-footer">
			<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span>
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>