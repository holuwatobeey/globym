<div ng-controller="ManageOrderListController as orderListCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <div class="lw-section-heading">
		    <span ng-if="orderListCtrl.userFullName == ' '">
		    	<h3><span><?= __tr( 'Manage Orders' ) ?></span> </h3>
		    </span>
		    <span ng-if="orderListCtrl.userFullName != ' '">
		    	<h3><span ng-bind="orderListCtrl.manageOrdersTitle"></span></h3>
		    </span>
        </div>
        
        <!-- /main heading -->
    </div>
	<!--  admin tabs section -->
	<div>

	    <!-- Order for admin Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="adminOrderList">
	        <li role="presentation" ui-sref-active="active" class="nav-item active">
	            <a href="#active" class="nav-link" ng-click="orderListCtrl.goToActiveTab($event,'orders.active')" aria-controls="active" role="tab" data-toggle="tab" title="<?=  __tr('Active')  ?>"><?=  __tr('Active')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active" class="nav-item">
	            <a href="#cancelled" class="nav-link" ng-click="orderListCtrl.goToCancelledTab($event,'orders.cancelled')" aria-controls="cancelled" role="tab" data-toggle="tab" title="<?=  __tr('Cancelled')  ?>"><?=  __tr('Cancelled')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active" class="nav-item">
	            <a href="#completed" class="nav-link" ng-click="orderListCtrl.goToCompletedTab($event,'orders.completed')" aria-controls="completed" role="tab" title="<?=  __tr('Completed')  ?>" data-toggle="tab"><?=  __tr('Completed')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Order admin Tabs -->

		<div class="tab-content mb-4">
	    	<!-- Active Tab -->
	        <div role="tabpanel" class="tab-pane fade in active" id="active">
				<!-- datatable -->
		        <table id="activeTabList" class="table table-striped table-bordered " cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Name')  ?></th>
		                    <th><?=  __tr('Order Status')  ?></th>
		                    <th><?=  __tr('Payment Status')  ?></th>
		                    <th><?=  __tr('Payment Method')  ?></th>
		                    <th><?=  __tr('Placed On')  ?></th>
		                    <th><?=  __tr('Amount')  ?></th>
		                    <th><?=  __tr('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Active Tab -->
	        <!-- Cancelled Tab -->
	        <div role="tabpanel" class="tab-pane fade table-reponsive" id="cancelled">
				<!-- datatable -->
		        <table id="cancelledTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Name')  ?></th>
		                    <th><?=  __tr('Order Status')  ?></th>
		                    <th><?=  __tr('Payment Status')  ?></th>
		                    <th><?=  __tr('Payment Method')  ?></th>
		                    <th><?=  __tr('Canceled On')  ?></th>
		                    <th><?=  __tr('Amount')  ?></th>
		                    <th><?=  __tr('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Cancelled Tab -->
	        <!-- Completed Tab -->
	        <div role="tabpanel" class="tab-pane fade table-reponsive" id="completed">

				<!-- datatable -->
		        <table id="completedTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Name')  ?></th>
		                    <th><?=  __tr('Order Status')  ?></th>
		                    <th><?=  __tr('Payment Status')  ?></th>
		                     <th><?=  __tr('Payment Method')  ?></th>
		                    <th><?=  __tr('Completed On')  ?></th>
		                    <th><?=  __tr('Amount')  ?></th>
		                    <th><?=  __tr('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	        <!-- /Completed Tab -->
	        </div>
		</div>
	</div>
	<!--  /admin tabs section -->

</div>

<!-- orderColumnIdTemplate -->
	<script type="text/template" id="orderColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
	</script>
<!-- orderColumnIdTemplate -->

<!-- orderColumnTotalAmountTemplate -->
	<script type="text/template" id="orderColumnTotalAmountTemplate">
	    <span class="lw-datatable-price-align" style="float-right"><%- __tData.totalAmount %></span>
	</script>
<!-- orderColumnTotalAmountTemplate -->

<!-- orderPaymentStatusColumnIdTemplate -->
	<script type="text/template" id="paymentActionColumnTemplate">
		
		<!-- Payment Status -->
		<% if(__tData.payment_status == 2) {%> <!-- 4(Pending) -->
		<a title="<?= __tr('Show Payment Details') ?>" 
			ng-click="orderListCtrl.paymentDetailsDialog(<%- __tData.orderPaymentID %>)" 
			href="" ><%- __tData.paymentStatus %> </a>
		<% } else { %>
			<%- __tData.paymentStatus %>
		<% } %>
		<!-- /Payment Status -->
		
		<!-- Update Payment Button -->
	    <% if(__tData.payment_status != 2 && __tData.status != 3 && __tData.payment_method != 1) { %>

        <% if(__tData.canAccessUpdatePayment) { %>
    	    <a title="<?= __tr('Update Payment') ?>" 
    			ng-click="orderListCtrl.updatePaymentDetailsDialog(<%- __tData._id %>)" 

    			class="btn btn-success btn-sm lw-btn" href="" ><?= __tr('Update Payment') ?></a>

    		<% } %>
        <% }  %>
		<!-- /Update Payment Button -->

	</script>
<!-- orderPaymentStatusColumnIdTemplate -->

<!-- userStatusColumnIdTemplate -->
	<script type="text/template" id="orderStatusColumnIdTemplate">
	
		<%- __tData.formated_status %>
		
		<!-- Update Button -->
        <% if(__tData.canAccessEdit) { %>
    		<% if (__tData.status !== 3 && __tData.status !== 6) { %> 
    	    	<button title="<?= __tr('Update') ?>" ng-click="orderListCtrl.updateDialog('<%- __tData._id %>','<%- __tData.order_uid %>')" class="btn btn-warning btn-sm lw-btn"><i class="fa fa-pencil-square-o"></i> <?= __tr('Update') ?></button>
    	    <% } %>
        <% }  %>
	    <!-- /Update Button -->

	</script>
	<!-- userStatusColumnIdTemplate -->

	<!-- userTypeColumnIdTemplate -->
	<script type="text/template" id="orderPaymentMethodColumnIdTemplate">
<!-- <%- __tData.status %> -->
		<%- __tData.paymentMethod %> 

		<!-- Cancelled order & payment Completed then show this btn -->
		<% if (__tData.payment_status == 2 && __tData.status == 3) { %> 
			
			<!-- Update Refund button -->
            <% if(__tData.canAccessRefundPayment) { %>
			     <a title="<?= __tr('Refund') ?>" ng-click="orderListCtrl.refundPaymentDialog('<%- __tData._id %>', '<%- __tData.order_uid %>')" class="btn btn-success btn-sm lw-btn" href="" ><?= __tr('Refund') ?></a>
            <% }  %>
			<!-- /Update Refund button -->

		<% } %>
		<!-- /Cancelled order & payment Completed then show this btn -->
		
	</script>
	<!-- userTypeColumnIdTemplate -->

	<!-- userNameColumnIdTemplate -->
	<script type="text/template" id="userNameColumnIdTemplate">
	  <span title="<?= __tr('name') ?>" class="tch-name word-wrap"><%- __tData.formated_name %></span>
	</script>
	<!-- userNameColumnIdTemplate -->

	<!-- orderColumnTimeTemplate -->
	<script type="text/template" id="orderColumnTimeTemplate">
        <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
    <%-__tData.formatCreatedData %></span>
	</script>
	<!-- orderColumnTimeTemplate -->

	<!-- orderActionColumnTemplate -->
	<script type="text/_template" id="orderActionColumnTemplate">

	<!-- View Details button -->
    <% if(__tData.canAccessOrderDetail) { %>
	    <a title="<?= __tr('View Details') ?>" ng-click="orderListCtrl.orderDetailsDialog(<%- __tData._id %>)" class="btn btn-light btn-sm lw-btn lw-btn" href="" ><i class="fa fa-info"></i> <?= __tr('View Details') ?></a>
    <% }  %>
	<!-- /View Details button -->
	
	<!-- Order Log button -->
    <% if(__tData.canAccessOrderLog) { %>
        <a title="<?= __tr('Order Log') ?>" ng-click="orderListCtrl.logDetailsDialog(<%- __tData._id %>)" class="btn btn-light btn-sm lw-btn" href="" ><i class="fa fa-info"></i> <?= __tr('Order Log') ?></a>
    <% }  %>
    <!-- Order Log button -->
    
    <!-- Contact User button -->
    <% if(__tData.canAccessUserContact) { %>
        <a title="<?= __tr('Contact User') ?>" ng-click="orderListCtrl.contactUserDialog(<%- __tData._id %>)" class="btn btn-primary btn-sm lw-btn" href="" ><i class="fa fa-envelope" aria-hidden="true"></i> <?= __tr('Contact User') ?></a>
    <% }  %>
    <!-- Contact User button -->
	
	<!-- Download Invoice -->
	<% if (__tData.payment_status == 2) { %> <!-- 2 = Completed -->

    
        <% if(__tData.canAccessDownloadInvoice) { %>
    	     <a title="<?= __tr('Download Invoice') ?>" href="<%- __tData.pfdDownloadURL %>" class="btn btn-light btn-sm lw-btn" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __tr('Download Invoice') ?></a>
        <% }  %>
    <% } %>
    <!-- Download Invoice -->

    <% if(__tData.isTestOrder) { %>

        <!-- Delete Sandbox Order button -->
        <% if(__tData.canAccessDeleteSandboxOrder) { %>
            <a ng-show="canAccess('manage.order.sandbox_order.delete')" title="<?= __tr('Delete Sandbox Order') ?>" href="" ng-click="orderListCtrl.deleteSandBoxOrder('<%- __tData._id %>', 1, '<%- __tData.order_uid %>')" class="btn btn-danger btn-sm lw-btn"><i class="fa fa-trash" aria-hidden="true"></i> <?= __tr('Delete') ?></a>
        <% }  %>
        <!-- Delete Sandbox Order button -->  

    <% } else { %>

        <!-- Delete Sandbox Order button -->
        <% if(__tData.canAccessDeleteSandboxOrder) { %>
            <a  ng-show="canAccess('manage.order.sandbox_order.delete')" title="<?= __tr('Delete Sandbox Order') ?>" href="" ng-click="orderListCtrl.deleteSandBoxOrder('<%- __tData._id %>', 2, '<%- __tData.order_uid %>')" class="btn btn-danger btn-sm lw-btn"><i class="fa fa-trash" aria-hidden="true"></i> <?= __tr('Delete') ?></a>
        <% }  %>
        <!-- Delete Sandbox Order button -->  

    <% } %>

	</script>
	<!-- /orderActionColumnTemplate -->

