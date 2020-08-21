<div ng-controller="MyOrderListController as MyOrderListCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?=  __tr( 'Orders' ) ?>
        	@section('page-title') 
	        	<?= 'Orders' ?>
	        @endsection
	    </h3>
        <!-- /main heading -->
    </div>

	<!--  users tabs section -->
	<div>
	    <!-- Order for user Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="adminOrderList">
	        <li role="presentation" class="activeTab nav-item active">
	            <a href="#activeTab" class="nav-link" lwOnLoadClicker aria-controls="activeTab" role="tab" data-toggle="tab" title="<?=  __tr('Active')  ?>"><?=  __tr('Active')  ?></a>
	        </li>
	        <li role="presentation" class="cancelled nav-item">
	            <a href="#cancelled" class="nav-link" lwOnLoadClicker aria-controls="cancelled" role="tab" data-toggle="tab" title="<?=  __tr('Cancelled')  ?>"><?=  __tr('Cancelled')  ?></a>
	        </li>
	        <li role="presentation" class="completed nav-item">
	            <a href="#completed" class="nav-link" lwOnLoadClicker aria-controls="completed" role="tab" title="<?=  __tr('Completed')  ?>" data-toggle="tab"><?=  __tr('Completed')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Order user Tabs -->

		<div class="tab-content">
	    	<!-- Active Tab -->
	        <div role="tabpanel" class="tab-pane fade in activeTab" id="activeTab">
				<!-- datatable -->
		        <table id="activeTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Status')  ?></th>
		                    <th><?=  __tr('Placed On')  ?></th>
		                    <th><?=  __tr('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Active Tab -->
	        <!-- Cancelled Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="cancelled">
				<!-- datatable -->
		        <table id="cancelledTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Status')  ?></th>
		                    <th><?=  __tr('Canceled On')  ?></th>
		                    <th><?=  __tr('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Cancelled Tab -->
	        <!-- Completed Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="completed">

				<!-- datatable -->
		        <table id="completedTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __tr('Order ID')  ?></th>
		                    <th><?=  __tr('Status')  ?></th>
		                    <th><?=  __tr('Completed On')  ?></th>
		                    <th><?=  __tr('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	        <!-- /Completed Tab -->
	        </div>
		</div>
	</div>
	<!--  /users tabs section -->

	<!-- orderColumnIdTemplate -->
	<script type="text/template" id="orderColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
	</script>
	<!-- orderColumnIdTemplate -->

	<!-- userStatusColumnIdTemplate -->
	<script type="text/template" id="orderStatusColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.formated_status %></span>
	</script>
	<!-- userStatusColumnIdTemplate -->

	<!-- orderColumnTimeTemplate -->
	<script type="text/template" id="orderColumnTimeTemplate">
	    <span class="custom-page-in-menu"><%- __tData.creation_date %></span>
	</script>
	<!-- orderColumnTimeTemplate -->

	<!-- orderActionColumnTemplate -->
	<script type="text/_template" id="orderActionColumnTemplate">
			 
		
	    <a title="<?= __tr('View Details') ?>" class="btn btn-light btn-sm lw-btn" href="<%= __tData.get_order_details_Route %>"><i class="fa fa-info"></i> <?= __tr('View Details') ?></a>
		
		<!-- 2 = (Completed) -->
		<% if (__tData.payment_status == 2 && __tData.status == 6) { %>
        <a title="<?= __tr('Download Invoice') ?>" href="<%- __tData.invoiceDownloadURL %>" class="btn btn-light btn-sm lw-btn" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __tr('Download Invoice') ?></a>
        <% } %>
	</script>
	<!-- /orderActionColumnTemplate -->

</div>