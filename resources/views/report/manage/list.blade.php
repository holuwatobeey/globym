<!--     
    View        : Report List 
    Component   : Report
    Engine      : ReportEngine 
    Controller  : ReportController
---------------------------------------------------------------------------  -->
<div ng-controller="ReportController as reportCtrl">

	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Reports' ) ?>
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>

	<div class="mb-3">
		<!-- form section -->
		<form class="lw-form lw-ng-form lw-ng-form" 
			name="reportCtrl.[[ reportCtrl.ngFormName ]]" 
			novalidate>

            <div class="row">
                
    			<div class="col-lg-4">
    			    <!-- duration -->
    		         <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
    		               <select class="lw-form-field form-control" 
    		                name="duration" ng-model="reportCtrl.duration" ng-options="type.id as type.name for type in reportCtrl.reportDuration" ng-required="true" ng-change="reportCtrl.durationChange(reportCtrl.duration)">
    		            </select>  
    		        </lw-form-field>
    		        <!-- /duration -->
    	        </div>
    			
    			<div class="col-lg-4">
    		        <!-- Start Date -->
    				<lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>"> 
    					<input type="text" 
    							class="lw-form-field form-control lw-readonly-control"
    							name="start"
    							id="start"
    							lw-bootstrap-md-datetimepicker
    							ng-required="true" 
    							ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.start)"
    							options="[[ reportCtrl.startDateConfig ]]"
    							readonly
    							ng-model="reportCtrl.reportData.start" 
    						/>
    				</lw-form-field>
    				<!-- /Start Date -->
    			</div>

    			<div class="col-lg-4">
    				<!-- end Date -->
    				<lw-form-field field-for="end" label="<?= __tr( 'End Date' ) ?>"> 
    					<input type="text" 
    							class="lw-form-field form-control lw-readonly-control"
    							name="end"
    							id="end"
    							lw-bootstrap-md-datetimepicker
    							ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.end)"
    							options="[[ reportCtrl.endDateConfig ]]"
    							ng-required="true" 
    							readonly
    							ng-model="reportCtrl.reportData.end" 
    						/>
    				</lw-form-field>
    				<!-- /end Date -->
    			</div>
    				
    			
    		    <!-- status -->	
    	        <div class="col-lg-4"> 
    				<lw-form-field field-for="status" label="<?= __tr( 'Status' ) ?>"> 
    	               <select class="lw-form-field form-control" 
    	                    name="status" ng-model="reportCtrl.reportData.status" ng-options="type.id as type.name for type in reportCtrl.statuses" ng-required="true" ng-change="reportCtrl.statusChange(reportCtrl.reportData.status)">
    	                </select> 
    	            </lw-form-field>
    	        </div>
    	        <!-- /status-->
    			
    	        <!-- order -->	
    	        <div class="col-lg-4"> 
    				<lw-form-field field-for="order" label="<?= __tr( 'Order' ) ?>"> 
    	               <select class="lw-form-field form-control" 
    	                    name="order" ng-model="reportCtrl.reportData.order" ng-options="type.id as type.name for type in reportCtrl.orderList" ng-required="true">
    	                </select> 
    	            </lw-form-field>
    	        </div>
    	        <!-- /order-->
     
    			<!-- show button for show order-->
    			<div class="col-lg-4 lw-show-btn">
    				 <button type="submit" ng-click="reportCtrl.getReports(reportCtrl.selectedType)"   class="btn btn-primary btn-sm lw-btn" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
    				 
    				 <a ng-if="canAccess('manage.report.excel_download') || canAccess('manage.report.payment_excel_download')" ng-href="[[ reportCtrl.excelDownloadURL ]]" target="_self" class="lw-btn btn btn-light btn-sm" ng-show="reportCtrl.tableStatus.length > 0" title="<?= __tr('Generate Excel file') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __tr(' Generate Excel file') ?></a>
    			</div>
    			<!-- /show button for show order-->
            </div>
		</form>
		<!-- / form section -->
	</div>

    <div class="mb-3">
        <ul class="nav nav-tabs lw-tabs" role="tablist" id="managePaymentTab">
            <li role="presentation" class="payments nav-item active">
                <a href="#payments" class="nav-link" role="tab" title="<?= __tr( 'Payments' ) ?>" aria-controls="payments" data-toggle="tab">
                    <?=  __tr('Payments')  ?>
                </a>
            </li>
                
            <li class="orders nav-item active">
                <a href="#orders" class="nav-link" role="tab" title="<?= __tr( 'Orders' ) ?>" aria-controls="orders" data-toggle="tab">
                    <?=  __tr('Orders')  ?>
                </a>
            </li>
        </ul>
	</div>

	<!-- table for total amount by currency -->
    <div class="tab-content lw-tab-content">
        <div role="tabpanel" class="tab-pane fade in payments lw-tabs-top-section" id="payments">
            <div class="" ng-if="reportCtrl.totalAmounts != ''">
               
                <div class="table-responsive">
                    <!--  <div class=""> -->
                    <table class="table table-bordered">
                    	<thead class="thead-light">
                            <tr>
                                <th colspan="4"><?=  __tr('Total order Payments ')  ?></th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <!-- Currency Code and Order Amount -->
                                <th><?=  __tr('Currency')  ?></th>
                                <th class="lw-text-right"><?=  __tr('Credit Amount')  ?></th>
                                <th class="lw-text-right"><?=  __tr('Debit Amount')  ?></th>
                                <th class="lw-text-right"><?=  __tr('Difference Amount')  ?></th>
                                <!-- /Currency Code and Order Amount -->
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="amountDetail in reportCtrl.totalAmounts">
                                <!-- Currency Code and Order Amount -->
                                <td ng-bind="amountDetail.currencyCode"></td>
                                <td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.credit < 0}" ng-bind="amountDetail.formattedCredit"></td>
                                <td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.debit < 0}" ng-bind="amountDetail.formattedDebit"></td>
                                <td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.total < 0}" ng-bind="amountDetail.formattedTotal"></td>
                                <!-- /Currency Code and Order Amount -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info" role="alert" ng-if="reportCtrl.totalAmounts == ''">
                <?= __tr('No payments found.') ?>
            </div>
        	<!-- /table for total amount by currency -->
	    </div>

        <div role="tabpanel" class="tab-pane fade lw-tabs-top-section" id="orders">
        	<!-- datatable container -->
            <div>
                <!-- datatable -->
                <table class="table table-striped table-bordered" id="manageReportList" cellspacing="0" width="100%">
                    <thead class="page-header">
                        <tr>
                        	<th><?=  __tr('Order ID')  ?></th>
                            <th><?=  __tr('Name')  ?></th>
                            <th><?=  __tr('Order Status')  ?></th>
                            <th><?=  __tr('Payment Status')  ?></th>
                            <th><?=  __tr('Placed On')  ?></th>
                            <th class="text-right"><?=  __tr('Total')  ?></th>
                            <th><?=  __tr('Action')  ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!-- /datatable -->
            </div>
            <!-- /datatable container -->
        </div>
        <div ui-view></div>
    </div>
</div>

<!-- categories list row Action column _template order UID column -->
<script type="text/template" id="orderColumnIdTemplate">
    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
</script>
<!-- order UID column -->

<!-- userNameColumnIdTemplate -->
<script type="text/template" id="userNameColumnIdTemplate">
    <span class="custom-page-in-menu"><%- __tData.formated_name %></span>
</script>
<!-- userNameColumnIdTemplate -->

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

<!-- order total amount column template -->
<script type="text/template" id="orderColumnTotalAmountTemplate">
    <span class="lw-datatable-price-align"><%- __tData.totalAmount %></span>
</script>
<!-- order total amount column template -->

<!-- orderActionColumnTemplate -->
<script type="text/_template" id="orderActionColumnTemplate">
	<% if (__tData.canAccessDetail) { %>
	   <a title="<?= __tr('View Details') ?>" class="lw-btn btn btn-light btn-sm" ng-click="reportCtrl.orderDetailsDialog(<%- __tData._id %>)"><i class="fa fa-info"></i> <?= __tr('View Details') ?></a>
	<% } %>

	<% if (__tData.payment_status == 2 && __tData.canAccessDownloadInvoice) { %> <!-- 2 = Completed -->
	   <a title="<?= __tr('Download Invoice') ?>" href="<%- __tData.pfdDownloadURL %>" class="btn btn-light btn-sm lw-btn" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __tr('Download Invoice') ?></a>
	<% } %>
</script>
<!-- /orderActionColumnTemplate -->

