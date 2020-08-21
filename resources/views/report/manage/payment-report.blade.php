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
                <?= __tr( 'Payment Reports' ) ?>
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
                    <lw-form-field field-for="status" label="<?= __tr( 'Status' ) ?>"> 
                       <select class="lw-form-field form-control" 
                            name="status" ng-model="reportCtrl.reportData.paymentStatus" ng-options="type.id as type.name for type in reportCtrl.paymentStatuses" ng-required="true" ng-change="reportCtrl.statusChange(reportCtrl.reportData.status)">
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
                            name="select_currency" ng-model="reportCtrl.reportData.select_currency" ng-options="currencyValue for (currencyKey, currencyValue) in reportCtrl.paymentCurrencyList">
                            <option value='' disabled selected><?=  __tr('-- Select Currency --')  ?></option>
                        </select> 
                    </lw-form-field>
                    <!-- /Currency-->
                </div>
     
                <!-- show button for show order-->
                <div class="col-lg-3 lw-show-btn">
                     <button type="submit" ng-click="reportCtrl.getPaymentData(reportCtrl.reportData.select_currency)"   class="btn btn-primary btn-sm lw-btn" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
                     
                     <a ng-if="canAccess('manage.payment_report.payment_excel_download')" ng-href="[[ reportCtrl.excelDownloadURL ]]" target="_self" class="lw-btn btn btn-light btn-sm" ng-show="reportCtrl.paymentReportData.paymentOrderCount > 0" title="<?= __tr('Generate Excel File') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __tr(' Generate Excel File') ?></a>
                </div>
                <!-- /show button for show order-->
            </div>
        </form>
        <!-- / form section -->
    </div>

    <div class="row" ng-cloak>
        <!-- order report -->
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-header text-center"><?= __tr('Payment Report') ?></div>
                <canvas ng-show="reportCtrl.paymentReportData.paymentOrderCount > 0" id="lw-payment-report-bar-chart" width="900" height="250"></canvas>
                <div class="card-body">
                    <div class="alert alert-info" ng-if="reportCtrl.paymentReportData.paymentOrderCount == 0">
                         <?= __tr('No Payment Report Found') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- table for total amount by currency -->
    <div class="">
        <div>
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
                                <!-- <th><?=  __tr('Currency')  ?></th> -->
                                <th class="lw-text-right"><?=  __tr('Credit Amount')  ?></th>
                                <th class="lw-text-right"><?=  __tr('Debit Amount')  ?></th>
                                <th class="lw-text-right"><?=  __tr('Difference Amount')  ?></th>
                                <!-- /Currency Code and Order Amount -->
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="amountDetail in reportCtrl.totalAmounts">
                                <!-- Currency Code and Order Amount -->
                              <!--   <td ng-bind="amountDetail.currencyCode"></td> -->
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

        <div ui-view></div>
    </div>
</div>

