<!-- 
    View        : payment-list 
    Component   : Order
    Engine      : ManagePaymentEngine 
    Controller  : PaymentListController 
    ----------------------------------------------- -->

<div ng-controller="PaymentListController as paymentListCtrl">
	
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Manage Order Payments' ) ?>
	        </span>
        </h3>
        <!-- /main heading -->
    </div>

    <div class="modal-body">

		<!-- form section -->
		<form class="lw-form lw-ng-form lw-ng-form" 
			name="paymentListCtrl.[[ paymentListCtrl.ngFormName ]]" 
			novalidate>
			
            <div class="row">
    			<div class="col-lg-3">
    			    <!-- duration -->
    		         <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
    		               <select class="lw-form-field form-control" 
    		                name="duration" ng-model="paymentListCtrl.paymentData.duration" ng-options="type.id as type.name for type in paymentListCtrl.paymentDuration" ng-required="true" ng-change="paymentListCtrl.durationChange(paymentListCtrl.paymentData.duration)">
    		            </select>  
    		        </lw-form-field>
    		        <!-- /duration -->
    	        </div>
    			
    			<div class="col-lg-3">
    		        <!-- Start Date -->
    				<lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>"> 
    					<input type="text" 
    							class="lw-form-field form-control lw-readonly-control"
    							name="start"
    							id="start"
    							lw-bootstrap-md-datetimepicker
    							ng-required="true" 
    							ng-change="paymentListCtrl.endDateUpdated(paymentListCtrl.paymentData.start)"
    							options="[[ paymentListCtrl.startDateConfig ]]"
    							readonly
    							ng-model="paymentListCtrl.paymentData.start" 
    						/>
    				</lw-form-field>
    				<!-- /Start Date -->
    			</div>
    		
    			<div class="col-lg-3">
    				<!-- end Date -->
    				<lw-form-field field-for="end" label="<?= __tr( 'End Date' ) ?>"> 
    					<input type="text" 
    							class="lw-form-field form-control lw-readonly-control"
    							name="end"
    							id="end"
    							lw-bootstrap-md-datetimepicker
    							ng-change="paymentListCtrl.endDateUpdated(paymentListCtrl.paymentData.end)"
    							options="[[ paymentListCtrl.endDateConfig ]]"
    							ng-required="true" 
    							readonly
    							ng-model="paymentListCtrl.paymentData.end" 
    						/>
    				</lw-form-field>
    				<!-- /end Date -->
    			</div>
    			
    			<div class="col-lg-3 lw-show-btn">
    				<!-- show data button -->
    				<button ng-click="paymentListCtrl.getPaymentList()"   class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
    				<!-- show data button -->
    				
    				<!-- generate Excel sheet button -->
    				<span ng-if="paymentListCtrl.tableStatus != '' && canAccess('manage.order.payment.excel_download')">
    					<a ng-href="[[ paymentListCtrl.excelDownloadURL ]]" target="_self" class="lw-btn btn btn-light btn-sm" ng-show="reportCtrl.tableStatus != ''" title="<?= __tr('Generate Excel File') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __tr(' Generate Excel File') ?></a>
    				</span>
    				<!-- generate Excel sheet button -->
    			</div>
            </div>
		</form>
	</div>

	    <!-- datatable container -->
	    <div>
	        <!-- datatable -->
	        <table class="table table-striped table-bordered" id="managePaymentList" cellspacing="0" width="100%">
	            <thead class="page-header">
	                <tr>
	                    <th><?=  __tr('Order ID')  ?></th>
	                    <th><?=  __tr('Transaction ID')  ?></th>
	                    <th><?=  __tr('Fee / Charges')  ?></th>
	                    <th><?=  __tr('Created On')  ?></th>
	                    <th><?=  __tr('Payment Method')  ?></th>
	                    <th><?=  __tr('Total Amount')  ?></th>
                        <th><?=  __tr('Action')  ?></th>
	                </tr>
	            </thead>
	            <tbody></tbody>
	        </table>
	        <!-- /datatable -->
	    </div>
	    <!-- /datatable container -->
	<div ui-view></div>
<div>

<!-- Manage Order payment date Column Template -->
<script type="text/_template" id="creationDateColumnTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.formattedPaymentOn %>">
    <%-__tData.formatPaymentData %></span>

</script>
<!-- /Manage Order payment date Column Template -->

<!-- Order UID column template -->
<script type="text/template" id="orderPaymentColumnUIDTemplate">

    <span class="custom-page-in-menu">
        <% if (__tData.canAccessOrderDetail) { %>
    	   <a href ng-if="canAccess('manage.order.details.dialog')"  ng-click="paymentListCtrl.orderDetailsDialog(<%- __tData._id %>)"><%- __tData.order_uid %></a>
        <% } %>

        <% if (!__tData.canAccessOrderDetail) { %>
    	   <span ng-if="!canAccess('manage.order.details.dialog')" ><%- __tData.order_uid %></span>
        <% } %>
    </span>
</script>
<!-- Order UID column template -->

<!-- Order UID column template -->
<script type="text/template" id="orderPaymentTransactionIDTemplate">

    <span class="custom-page-in-menu">
        <% if (__tData.canAccessTxnDetail) { %>
        	<a href ng-if="canAccess('manage.order.payment.detail.dialog')" ng-click="paymentListCtrl.paymentDetailsDialog(<%- __tData.order_payment_id %>)"><%- __tData.txn %>
        	</a>
        <% } %>

        <% if (!__tData.canAccessTxnDetail) { %>
    	   <span ng-if="!canAccess('manage.order.payment.detail.dialog')"><%- __tData.txn %></span>
        <% } %>
    </span>
</script>
<!-- Order UID column template -->

<!-- order payment fee column -->
<script type="text/template" id="orderPaymentFeeTemplate">

    <span class="lw-datatable-price-align"><%- __tData.formattedFee %></span>
</script>
<!-- order payment fee column -->

<!-- Order refund and payment total amount column -->
<script type="text/template" id="orderPaymentTotalAmountTemplate">

	<% if (__tData.type == 2) { %> <!-- Refund -->
		<span class="lw-danger lw-datatable-price-align"><%- __tData.totalAmount %></span>
		<span class="label label-danger"><?= __tr('Refunded')  ?></span>
	<% } else { %>
        <span class="lw-datatable-price-align"><%- __tData.totalAmount %></span>
	<% } %>
</script>

<!-- Order refund and payment total amount column -->
<script type="text/template" id="orderPaymentActionTemplate">
    <% if(__tData.canAccessDelete) { %>
        <% if(__tData.isTestOrder) { %>
            <!-- Delete Sandbox Order button -->
            <a ng-if="canAccess('manage.order.payment.delete')" title="<?= __tr('Delete Sandbox Order') ?>" href="" ng-click="paymentListCtrl.deleteSandBoxOrder('<%- __tData.order_payment_id %>', '<%- __tData.order_uid %>', 1)" class="btn btn-danger btn-sm lw-btn"><i class="fa fa-trash" aria-hidden="true"></i> <?= __tr('Delete') ?></a>
            <!-- Delete Sandbox Order button -->  
        <% } else { %>
            <!-- Delete Sandbox Order button -->
            <a ng-if="canAccess('manage.order.payment.delete')" title="<?= __tr('Delete Sandbox Order') ?>" href="" ng-click="paymentListCtrl.deleteSandBoxOrder('<%- __tData.order_payment_id %>', '<%- __tData.order_uid %>', 2)" class="btn btn-danger btn-sm lw-btn"><i class="fa fa-trash" aria-hidden="true"></i> <?= __tr('Delete') ?></a>
            <!-- Delete Sandbox Order button -->  
        <% } %>
    <% } %>
</script>