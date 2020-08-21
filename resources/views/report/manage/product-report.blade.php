<?php 
/*
*  Component  : Report
*  View       : Report Controller
*  Engine     : ManageReportEngine  
*  File       : product-report.blade.php  
*  Controller : ProductReportListController as productReportListCtrl
----------------------------------------------------------------------------- */
?>
<div ng-controller="ProductReportListController as productReportListCtrl">
   <div class="lw-section-heading-block">
      <!-- main heading -->
      <h3 class="lw-section-heading">
         <span>
         <?= __tr('Product Report') ?>
         </span>
      </h3>
   </div>

   <div class="mb-3">
        <!-- form section -->
        <form class="lw-form lw-ng-form lw-ng-form" 
            name="productReportListCtrl.[[ productReportListCtrl.ngFormName ]]" 
            novalidate>

            <div class="row">
              
                <div class="col-lg-2">
                    <!-- duration -->
                     <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
                           <select class="lw-form-field form-control" 
                            name="duration" ng-model="productReportListCtrl.duration" ng-options="type.id as type.name for type in productReportListCtrl.reportDuration" ng-required="true" ng-change="productReportListCtrl.durationChange(productReportListCtrl.duration)">
                        </select>  
                    </lw-form-field>
                    <!-- /duration -->
                </div>
                
                <div class="col-lg-2">
                    <!-- Start Date -->
                    <lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>"> 
                        <input type="text" 
                                class="lw-form-field form-control lw-readonly-control"
                                name="start"
                                id="start"
                                lw-bootstrap-md-datetimepicker
                                ng-required="true" 
                                ng-change="productReportListCtrl.endDateUpdated(productReportListCtrl.productReportData.start)"
                                options="[[ productReportListCtrl.dateConfig]]"
                                readonly
                                ng-model="productReportListCtrl.productReportData.start" 
                            />
                    </lw-form-field>
                    <!-- /Start Date -->
                </div>

                <div class="col-lg-2">
                    <!-- end Date -->
                    <lw-form-field field-for="end" label="<?= __tr( 'End Date' ) ?>"> 
                        <input type="text" 
                                class="lw-form-field form-control lw-readonly-control"
                                name="end"
                                id="end"
                                lw-bootstrap-md-datetimepicker
                                ng-change="productReportListCtrl.endDateUpdated(productReportListCtrl.productReportData.end)"
                                options="[[ productReportListCtrl.dateConfig]]"
                                ng-required="true" 
                                readonly
                                ng-model="productReportListCtrl.productReportData.end" 
                            />
                    </lw-form-field>
                    <!-- /end Date -->
                </div>

                <!-- order -->  
                <div class="col-lg-2"> 
                    <lw-form-field field-for="order" label="<?= __tr( 'Product Dates As Per' ) ?>"> 
                       <select class="lw-form-field form-control" 
                            name="order" ng-model="productReportListCtrl.productReportData.order" ng-options="type.id as type.name for type in productReportListCtrl.orderList" ng-required="true">
                        </select> 
                    </lw-form-field>
                </div>
                <!-- /order-->
     
                <!-- show button for show order-->
                <div class="col-lg-2 lw-show-btn">
                     <button type="submit" ng-click="productReportListCtrl.getProductReportData()"   class="btn btn-primary btn-sm lw-btn" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
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
                <div class="card-header text-center"><?= __tr('Product Report') ?></div>
                <br>
                <div class="lw-chart-Area-Wrapper" id="lw-chart-Area-Wrapper">
                    <div class="lw-chart-wrapper">
                    <canvas ng-show="productReportListCtrl.productChartData.productCount > 0" id="lw-product-report-bar-chart" width="800" height="180"></canvas>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-info" ng-if="productReportListCtrl.productChartData.productCount == 0">
                         <?= __tr('No Product Report Found') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

   <!-- /main heading -->
   <table class="table table-striped table-bordered" id="lwProductReportList" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th><?= __tr('Title') ?></th>
            <th><?= __tr('Created On') ?></th>
            <th><?= __tr('Updated On') ?></th>
            <th><?= __tr('Qty') ?></th>
         </tr>
      </thead>
      <tbody></tbody>
   </table>
   <div ui-view></div>
</div>


<!-- product report creation date Column Template -->
<script type="text/_template" id="createDateColumnTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.created_at %>">
    <%-__tData.created_at %></span>

</script>
<!-- /product report creation date Column Template -->

<!-- product report update date Column Template -->
<script type="text/_template" id="updateDateColumnTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.updated_at %>">
    <%-__tData.updated_at %></span>

</script>
<!-- /product report update date Column Template -->