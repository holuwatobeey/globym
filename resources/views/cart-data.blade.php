<div id="list-popover">
    <div class="shopping-cart">
        <div class="lw-shopping-cart-header" ng-show="publicCtrl.cartData.length != 0">
            <i class="fa fa-shopping-cart lw-cart-icon" ></i> <span class="badge badge-primary badge-pill" ng-bind="publicCtrl.totalItems"></span>
            <div class="float-right">
                <span class="lighter-text"><?=  __tr('Total:') ?></span>
                <span class="main-color-text text-primary" ng-bind="publicCtrl.cartTotal"></span>
            </div>
        </div> <!--end shopping-cart-header -->

        <ul class="list-group list-group-flush lw-popover-shopping-cart">
            <li class="list-group-item" ng-repeat="item in publicCtrl.cartData">
                <span class="float-left">
                    <img ng-src="[[ item.thumbnail_url ]]" class="lw-popover-item-img">
                </span>
                <span class="float-right">
                    <span class="text-center">
                        <h6><span ng-bind="item.productName"></span></h6>
                    </span>
                    <span>
                        <span class="text-primary" ng-bind="item.formated_price"></span>
                        <?=  __tr('Qty:') ?> : <span ng-bind="item.qty"></span>
                    </span>

                </span>
            </li>
        </ul>
    </div> 

    <!--  if cart is empty  -->
    <div class="alert alert-info" ng-if="publicCtrl.cartData.length == 0">
        <?=  __tr("Your cart is empty.")  ?>
    </div>
    <!--  /if cart is empty -->
</div> 