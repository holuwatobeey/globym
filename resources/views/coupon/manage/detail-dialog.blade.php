<div class="lw-dialog" ng-controller="CouponDetailDialogController as couponDetailCtrl">
	
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Coupon / Discount Details' )  ?></h3>
    </div>
    <!-- /main heading -->

    <div class="card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?= __tr('Title') ?>
                <span ng-bind="couponDetailCtrl.couponDate.title" class="pull-right"></span>
            </li>
            <li class="list-group-item" ng-if="couponDetailCtrl.couponDate.code != null"><?= __tr('Code') ?>
                <span ng-bind="couponDetailCtrl.couponDate.code" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Discount') ?>
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
                    <span ng-bind="couponDetailCtrl.couponDate.currencySymbol" class="pull-right"></span>
                    <span ng-bind="couponDetailCtrl.couponDate.discount" class="pull-right"></span>
                </span>
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
                    <span class="pull-right">%</span> 
                    <span ng-bind="couponDetailCtrl.couponDate.discount" class="pull-right"></span>
                    
                </span>
            </li>
            <li class="list-group-item">
                <!-- Max Discount -->
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
                    <?=  __tr('Max Discount in % of product price')  ?>
                </span>
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
                    <?=  __tr('Max Discount')  ?>
                </span>
                <!-- /Max Discount -->

                <!-- Discount type -->
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
                    <span ng-bind="couponDetailCtrl.couponDate.max_discount" class="pull-right"></span>
                    <span ng-bind="couponDetailCtrl.couponDate.currencySymbol" class="pull-right"></span>
                </span>
                <span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
                    <span class="pull-right">%</span>
                    <span ng-bind="couponDetailCtrl.couponDate.max_discount" class="pull-right"></span>
                </span>
                <!-- /Discount type -->
            </li>
            <li class="list-group-item"><?= __tr('Minimum Order Amount') ?>
                <span ng-bind="couponDetailCtrl.couponDate.minimum_order_amount" class="pull-right"></span>
                <span ng-bind="couponDetailCtrl.couponDate.currencySymbol" class="pull-right"></span>
            </li>
            <li class="list-group-item" ng-if="couponDetailCtrl.couponDate.productScope != null"><?=  __tr('Cart Discount Type')  ?>
                <span ng-if="couponDetailCtrl.couponDate.productScope == 1">
                    <strong ng-bind="couponDetailCtrl.couponDate.formattedProductScope" class="pull-right">
                    </strong>
                </span>
                
                <div ng-if="couponDetailCtrl.couponDate.productScope == 2">
                    <strong>
                        [[ couponDetailCtrl.couponDate.formattedProductScope ]] : 
                    </strong>                            
                    <span ng-repeat="product in couponDetailCtrl.couponDate.products" class="pull-right">
                        [[ product ]]<span ng-if="!$last">,</span>
                    </span>
                </div>
            </li>
            <li class="list-group-item" ng-if="couponDetailCtrl.couponDate.description">
            	<?= __tr('Description') ?>:
            	<p ng-bind="couponDetailCtrl.couponDate.description"></p>
            </li>
        </ul>
    </div>
	<!-- Table -->
	<br>
	<!-- action button -->
	<div class="modal-footer">
   		<button type="button" class="lw-btn btn btn-light" ng-click="couponDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!-- /action button -->
</div>