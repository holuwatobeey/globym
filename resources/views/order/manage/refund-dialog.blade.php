<div class="lw-dialog" ng-controller="ManageRefundOrderPaymentController as refundOrderPaymentCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Refund Order Payment' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="refundOrderPaymentCtrl.[[ refundOrderPaymentCtrl.ngFormName ]]" 
        novalidate>
		
		<fieldset class="lw-fieldset-2 mb-4">
			<legend>
				<?=  __tr('Order Payment Details')  ?> 
			</legend>
			<!-- order detail table -->
            <ul class="list-group mt-3">
                <li class="list-group-item"><?= __tr('Order ID') ?>
                    <span class="pull-right" ng-bind="refundOrderPaymentCtrl.paymentDetails.orderUID">
                    </span>
                </li>
                <li class="list-group-item"><?= __tr('Transaction ID') ?>
                    <span class="pull-right" ng-bind="refundOrderPaymentCtrl.paymentDetails.txn">
                    </span>
                </li>
                <li class="list-group-item"><?= __tr('Fee') ?>
                    <span class="pull-right" ng-bind="refundOrderPaymentCtrl.paymentDetails.fee">
                    </span>
                </li>
                <li class="list-group-item"><?= __tr('Total Amount') ?>
                    <span class="pull-right" ng-bind="refundOrderPaymentCtrl.paymentDetails.grossAmount">
                    </span>
                </li>
                <li class="list-group-item"><?= __tr('Payment On') ?>
                    <span class="pull-right" ng-bind="refundOrderPaymentCtrl.paymentDetails.paymentOn">
                    </span>
                </li>
            </ul>
            <!-- / order detail table -->
		</fieldset>

		<fieldset class="lw-fieldset-2 mb-3">
			<legend>
				<?=  __tr('Refund Payment Options')  ?> 
			</legend>

			<!-- payment method -->
		    <div> 
		        <lw-form-field field-for="paymentMethod" label="<?= __tr( 'Payment Method' ) ?>"> 
		           <select class="form-control" 
		                name="paymentMethod" ng-model="refundOrderPaymentCtrl.paymentDetails.paymentMethod" ng-options="type.id as type.name for type in refundOrderPaymentCtrl.paymentMethodList" ng-required="true">
		                <option value='' disabled selected><?=  __tr('-- Select Payment Method --')  ?></option>
		            </select> 
		        </lw-form-field>
		    </div>
		    <!-- /payment method-->

		    <!-- Checkout Options -->
	        <div class="form-group checkbox">
	            <label>
	                <input type="checkbox" 
	                    class="lw-form-field"
	                    name="checkMail"
	                    ng-model="refundOrderPaymentCtrl.paymentDetails.checkMail" /> </span> <?= __tr('Notify Customer') ?>
	            </label>
	        </div>
	        <!-- /Checkout Options -->

	        <!-- Description -->
			<div>
		        <lw-form-field field-for="description" label="<?= __tr('Additional Notes') ?>"> 
		            <textarea name="description" class="lw-form-field form-control"
		             cols="10" rows="3" ng-model="refundOrderPaymentCtrl.paymentDetails.description"></textarea>
		        </lw-form-field>
			</div>
			<!-- Description -->

		    <!-- Action -->
	        <div class="modal-footer">
	        	<!-- Submit Button -->
	            <button type="submit" ng-click="refundOrderPaymentCtrl.update()" class="lw-btn btn btn-primary lw-btn-process" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> </button>
	            <!-- /Submit Button -->
				
				<!-- Cancel Button -->
	            <button type="button" ng-click="refundOrderPaymentCtrl.closeDialog()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
	            <!-- /Cancel Button -->
	        </div>
			<!-- /Action -->

		</fieldset>

    </form>

</div>