<!--     
    View        : Report List 
    Component   : Report
    Engine      : ManageReportEngine 
    Controller  : ReportController
---------------------------------------------------------------------------  -->
<div ng-controller="ReportController as reportCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr( 'Order Reports' ) ?>
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
                
                <div class="col-lg-3">
                    <!-- duration -->
                     <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
                           <select class="lw-form-field form-control" 
                            name="duration" ng-model="reportCtrl.duration" ng-options="type.id as type.name for type in reportCtrl.reportDuration" ng-required="true" ng-change="reportCtrl.durationChange(reportCtrl.duration)">
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
                                ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.start)"
                                options="[[ reportCtrl.dateConfig]]"
                                readonly
                                ng-model="reportCtrl.reportData.start" 
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
                                ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.end)"
                                options="[[ reportCtrl.dateConfig]]"
                                ng-required="true" 
                                readonly
                                ng-model="reportCtrl.reportData.end" 
                            />
                    </lw-form-field>
                    <!-- /end Date -->
                </div>
                    
                
                <!-- status --> 
                <div class="col-lg-3"> 
                    <lw-form-field field-for="status" label="<?= __tr( 'Order Status' ) ?>"> 
                       <select class="lw-form-field form-control" 
                            name="status" ng-model="reportCtrl.reportData.status" ng-options="type.id as type.name for type in reportCtrl.statuses" ng-required="true" ng-change="reportCtrl.statusChange(reportCtrl.reportData.status)">
                        </select> 
                    </lw-form-field>
                </div>
                <!-- /status-->
               
                <!-- order -->  
                <div class="col-lg-3"> 
                    <lw-form-field field-for="order" label="<?= __tr( 'Order Dates As Per' ) ?>"> 
                       <select class="lw-form-field form-control" 
                            name="order" ng-model="reportCtrl.reportData.order" ng-options="type.id as type.name for type in reportCtrl.orderList" ng-required="true">
                        </select> 
                    </lw-form-field>
                </div>
                <!-- /order-->

                <div class="col-lg-3">
                    <!-- Currency -->
                    <lw-form-field field-for="select_currency" label="<?= __tr( 'Select Currency' ) ?>"> 
                       <select class="form-control" 
                            name="select_currency" ng-model="reportCtrl.reportData.select_currency" ng-options="currencyValue for (currencyKey, currencyValue) in reportCtrl.currencyList">
                            <option value='' disabled selected><?=  __tr('-- Select Currency --')  ?></option>
                        </select> 
                    </lw-form-field>
                    <!-- /Currency-->
                </div>
     
                <!-- show button for show order-->
                <div class="col-lg-4 lw-show-btn">
                     <button type="submit" ng-click="reportCtrl.getReports(reportCtrl.selectedType, reportCtrl.reportData.select_currency)"   class="btn btn-primary btn-sm lw-btn" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
                     
                     <a ng-if="canAccess('manage.report.excel_download')" ng-href="[[ reportCtrl.reportExcelDownloadURL ]]" target="_self" class="lw-btn btn btn-light btn-sm" ng-show="reportCtrl.tableStatus.length > 0" title="<?= __tr('Generate Excel File') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __tr(' Generate Excel File') ?></a>
                </div>
                <!-- /show button for show order-->
            </div>
        </form>
        <!-- / form section -->
    </div>


    <div class="row" ng-cloak>
        <!-- order report -->
        <div class="col-sm-6 col-md-6">
            <div class="card">
                <div class="card-header text-center"><?= __tr('Order Report') ?></div>
                <canvas ng-show="reportCtrl.orderReportData.orderCount > 0" id="lw-order-report-chart" ></canvas>
                <div class="card-body">
                    <div class="alert alert-info" ng-if="reportCtrl.orderReportData.orderCount == 0">
                         <?= __tr('No Order Found') ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- / order report -->

        <!-- order payment report -->
        <div class="col-sm-6 col-md-6">
            <div class="card">
                <div class="card-header text-center"><?= __tr('Order Payment Report') ?></div>
                <canvas ng-show="reportCtrl.orderReportData.orderCount > 0" id="lw-order-payment-chart"></canvas>
                <div class="card-body">
                    <div class="alert alert-info" ng-if="reportCtrl.orderReportData.orderCount == 0">
                         <?= __tr('No Order Payment Found') ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- / order payment report -->
    </div>
    <br>

    <!-- table for total amount by currency -->
    <div class="">
        <div>
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
    <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
    <%-__tData.creation_date %></span>
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

