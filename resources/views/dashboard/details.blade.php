<div ng-controller="DashboardController as dashboardCtrl">
    <div>
        <!-- main heading -->
        <div class="lw-section-heading-block">
            <h3 class="lw-section-heading">
            <i class="fa fa-tachometer"></i> <?= __tr('Dashboard') ?>
            </h3>
        </div>
    
        <br>

        <div class="row">
            <!-- product total count -->
            <div class="col-lg-4">
                <div class="card mb-3 text-center">
                    <div class="card-body">
                        <span class="lw-dashboard-count" ng-bind="dashboardCtrl.products.active"></span>
                    </div>
                    <div class="card-footer">
                         <span class="lw-dashboard-label"><?= __tr('Products') ?></span>
                    </div>
                </div>
            </div>
            <!-- product total count -->

            <!-- category total count -->
            <div class="col-lg-4">
                <div class="card mb-3 text-center">
                    <div class="card-body">
                        <span class="lw-dashboard-count" ng-bind="dashboardCtrl.totalCategories"></span>
                    </div>
                    <div class="card-footer">
                        <span class="lw-dashboard-label"><?= __tr('Categories') ?></span>
                    </div>
                </div>
            </div>
            <!-- category total count -->

            <!-- brand total count -->
            <div class="col-lg-4">
                <div class="card mb-3 text-center">
                    <div class="card-body">
                        <span class="lw-dashboard-count" ng-bind="dashboardCtrl.brands.active"></span>
                    </div>
                    <div class="card-footer">
                        <span class="lw-dashboard-label"><?= __tr('Brands') ?></span>
                    </div>
                </div>
            </div>
            <!-- brand total count -->
        </div>

        <div class="row">
            <!-- product count chart -->
            <div class="col-lg-6" ng-if="canAccess('manage.product.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        
                        <strong><?= __tr('Products') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="products" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.product.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <canvas ng-show="dashboardCtrl.products.baseTotalProductCount > 0" id="lw-product-chart" width="150px" height="65px"></canvas>
                        <div class="card-body">
                            <div ng-if="dashboardCtrl.products.baseTotalProductCount == 0">
                                 <?= __tr('No Product Found') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / product count chart -->

            <!-- brand count chart -->
            <div class="col-lg-6" ng-if="canAccess('manage.brand.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Brands') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="brands" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.brand.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <canvas ng-show="dashboardCtrl.brands.baseTotalBrand > 0" id="lw-brand-chart" width="150px" height="65px"></canvas>
                        <div class="card-body">
                            <div ng-if="dashboardCtrl.brands.baseTotalBrand == 0">
                                 <?= __tr('No Brand Found') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / brand count chart -->

            <!-- latest product rating list -->
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Latest Product Ratings') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="productRating" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right"  ng-if="canAccess('manage.product.rating.read.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <ul class="list-group" ng-if="dashboardCtrl.latestProductsRating.length > 0">
                            <li class="list-group-item" ng-repeat="rating in dashboardCtrl.latestProductsRating">
                                <span ng-bind="rating.productName"></span>
                                <span class="pull-right" ng-bind-html="rating.formatRating"></span>
                                <span ng-if="rating.review.length > 0">
                                    <br>
                                    <small><em><?= __tr('Review') ?> - </em></small>
                                    [[rating.formatReview]]
                                </span>
                            </li>
                        </ul>
                   
                        <ul class="list-group" ng-if="dashboardCtrl.latestProductsRating.length == 0">
                            <li class="list-group-item">
                                <?= __tr('No Latest Review Found.') ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / latest product rating list -->

            <!-- Curent active coupans -->
            <div class="col-lg-6" ng-if="canAccess('manage.coupon.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Current Active Coupons') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="coupons.current" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.coupon.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <ul class="list-group" ng-if="dashboardCtrl.currentCoupons.length > 0">
                            <li class="list-group-item" ng-repeat="coupons in dashboardCtrl.currentCoupons">
                               <span ng-bind="coupons.title"></span>
                            </li>
                        </ul>
                        <ul class="list-group" ng-if="dashboardCtrl.currentCoupons.length == 0">
                            <li class="list-group-item">
                                <?= __tr('No Recent Coupon.') ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / Curent active coupans -->
        </div>

        <!-- Duration Filer -->
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <!-- form section -->
                <form class="lw-form lw-ng-form lw-ng-form" 
                    name="dashboardCtrl.[[ dashboardCtrl.ngFormName ]]" 
                    novalidate>

                    <div class="form-row">
                        <div class="col">
                            <!-- duration -->
                            <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
                                   <select class="lw-form-field form-control" 
                                    name="duration" ng-model="dashboardCtrl.duration" ng-options="type.id as type.name for type in dashboardCtrl.dashboardDuration" ng-required="true" ng-change="dashboardCtrl.durationChange(dashboardCtrl.duration)">
                                </select>  
                            </lw-form-field>
                            <!-- /duration -->
                        </div>
                        <div class="col">
                            <!-- show button for show order-->
                            <div class="lw-show-btn">
                                 <button type="submit" ng-click="dashboardCtrl.getDashboardData()"   class="btn btn-primary btn-sm lw-btn" title="<?= __tr('Show') ?>"><?= __tr('Show') ?></button>
                            </div>
                            <!-- /show button for show order-->
                        </div>
                    </div>
                </form>
                <!-- / form section -->
            </div>
        </div>
        <!-- / Duration Filer -->
        <br>

        <div class="row">
            <!-- highest selling products -->
            <div class="col-lg-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <strong><?= __tr('Highest Selling Products') ?></strong>
                    </div>
                    <div class="lw-dashboard-card">
                        <ul class="list-group list-group-flush" ng-if="dashboardCtrl.latestSaleProducts.length != 0">
                            <li class="list-group-item">
                                <span class="float-left"><strong><?= __tr('Product') ?></strong></span>
                                <span class="float-right"><strong><?= __tr('Sale') ?></strong></span>
                            </li>
                            <li class="list-group-item" ng-repeat="product in dashboardCtrl.latestSaleProducts">
                                <span class="float-left">[[product.productName]]</span>
                                <span class="badge badge-primary float-right">[[product.qty]]</span>
                            </li>
                        </ul>
                        <ul class="list-group" ng-if="dashboardCtrl.latestSaleProducts.length == 0">
                            <li class="list-group-item text-center">
                                <?= __tr('No Latest Sale Product Found.') ?>
                            </li>
                        </ul>
                    </div>
                </div>    
            </div>
            <!-- / highest selling products -->

            <!-- new order -->
            <div class="col-lg-4" ng-if="canAccess('manage.order.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('New Orders') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="orders.active" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right"  ng-if="canAccess('manage.order.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <ul class="list-group" ng-if="dashboardCtrl.orders.recentNewOrders.length > 0">
                            <li class="list-group-item" ng-repeat="order in dashboardCtrl.orders.recentNewOrders">[[order.formattedStatus]]
                                [[ order.orderId ]] - 
                                <span ng-switch="order.status">
                                    <span ng-switch-when="1" class="badge badge-primary" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="2" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="3" class="badge badge-danger" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="4" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="5" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="6" class="badge badge-success" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="7" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="11" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                </span> - 
                                <span title="[[ order.formattedCreatedOn ]]" ng-bind="order.humanFormatCreatedOn"></span>
                                <small><em>
                                    <?= __tr('by __ownerName__', ['__ownerName__' => '[[ order.ownerName ]]']) ?>
                                </em></small>
                            </li>
                        </ul>
                   
                        <ul class="list-group" ng-if="dashboardCtrl.orders.recentNewOrders.length == 0">
                            <li class="list-group-item">
                                <?= __tr('No New Orders Found.') ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / new order -->

            <!-- complete order -->
            <div class="col-lg-4" ng-if="canAccess('manage.order.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Completed Orders') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="orders.active" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.order.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <ul class="list-group" ng-if="dashboardCtrl.orders.recentCompletedOrders.length > 0">
                            <li class="list-group-item" ng-repeat="order in dashboardCtrl.orders.recentCompletedOrders">
                                [[ order.orderId ]] - 
                                <span ng-switch="order.status">
                                    <span ng-switch-when="1" class="badge badge-primary" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="2" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="3" class="badge badge-danger" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="4" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="5" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="6" class="badge badge-success" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="7" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                    <span ng-switch-when="11" class="badge badge-warning" ng-bind="order.formattedStatus"></span>
                                </span> - 
                                <span title="[[ order.formattedCreatedOn ]]" ng-bind="order.humanFormatCreatedOn"></span>
                                <small><em>
                                    <?= __tr('by __ownerName__', ['__ownerName__' => '[[ order.ownerName ]]']) ?>
                                </em></small>
                            </li>
                        </ul>
                        <ul class="list-group" ng-if="dashboardCtrl.orders.recentCompletedOrders.length == 0">
                            <li class="list-group-item">
                                <?= __tr('No Completed Orders Found.') ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / complete order -->

        </div>
  
        <div class="row">
            <!-- Incomplete Payments -->
            <div class="col-lg-6" ng-if="canAccess('manage.order.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Incomplete Payment') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="orders.active" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.order.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <div class="row">
                            <div class="col-lg-6" ng-repeat="payment in dashboardCtrl.orders.awaitingOrderPayments" ng-if=" dashboardCtrl.orders.awaitingOrderPayments.length > 0">
                                <div class="lw-statistic">
                                    <small><div class="lw-statistic-value lw-warn-color" ng-bind="payment.formatted_amount"></div></small>
                                    <div class="lw-statistic-label"><?= __tr('of __orderCount__ Order', ['__orderCount__' => '[[ payment.order_count ]]']) ?></div>
                                </div>
                            </div>
                            <div class="ml-3" ng-if="dashboardCtrl.orders.awaitingOrderPayments.length == 0">
                                <span><?= __tr('No Incomplete Payment Found.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Incomplete Payments -->

            <!-- Product Order status -->
            <div class="col-lg-6" ng-if="canAccess('manage.order.list')">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= __tr('Orders') ?></strong>
                        <span class="pull-right">
                            <a ui-sref="orders.active" title="<?= __tr('Go to Details') ?>"><i class="fa fa-chevron-circle-right" ng-if="canAccess('manage.order.list')"></i></a>
                        </span>
                    </div>
                    <div class="lw-dashboard-card">
                        <div class="card-body">
                            <canvas ng-show="dashboardCtrl.orderChartData.orderCount > 0" id="lw-order-chart" width="150px" height="75px"></canvas>
                           
                            <div class="text-center" ng-if="dashboardCtrl.orderChartData.orderCount == 0">
                                <?= __tr('No Orders Found') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Product Order status -->
        </div> 
 
    </div>
</div>