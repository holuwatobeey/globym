
<h4 class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted"><?= __tr('Your Cart ') ?></span>
    <span class="badge badge-secondary badge-pill">[[ CartOrderCtrl.orderSupportData.totalQuantity ]]</span>
</h4>

<ul class="list-group mb-3">

    <li class="list-group-item d-flex justify-content-between lh-condensed" ng-repeat="item in CartOrderCtrl.orderSupportData.cartItems track by $index" id="rowid_[[item.rowid]]">
        <div>
        <h6 class="my-0"><a href ng-href="[[item.productDetailURL]]" ng-bind="item.name"> </a></h6>
            <small class="text-muted">
            <div><strong><?= __tr('QTY') ?> : </strong> [[item.qty]]</div>
            {{-- product option name and value --}}
            <div ng-repeat="(key, option) in item.options">
                <span ng-show="option.addonPrice != 0">
                    <strong>[[option.optionName]] : </strong> [[option.valueName]] ( +[[option.formated_addon_price]])<br>
                </span>
                <span ng-show="option.addonPrice == 0">
                    <strong>[[option.optionName]] : </strong> [[option.valueName]]<br>
                </span>
            </div>
            {{-- product option name and value --}}
            </small>
        </div>
        <span class="text-muted" ng-bind="item.new_subTotal"></span>
    </li>

    <li class="list-group-item d-flex justify-content-between"  ng-if="!CartOrderCtrl.orderSupportData.cartDiscount.isOrderDiscountExist && !CartOrderCtrl.orderSupportData.isCurrencyMismatch">
        <span><?= __tr('Cart Total') ?> </span>
        <strong ng-bind="CartOrderCtrl.orderSupportData.formatedCartTotalPrice"></strong>
    </li>

    <li class="list-group-item d-flex justify-content-between"  ng-if="CartOrderCtrl.orderSupportData.cartDiscount.isOrderDiscountExist && !CartOrderCtrl.orderSupportData.isCurrencyMismatch">
        <span><?= __tr('Cart Total') ?> </span>
        <strong ng-bind="CartOrderCtrl.orderSupportData.cartTotalBeforeDiscount.base_price"></strong>
    </li>

    <li class="list-group-item d-flex justify-content-between bg-light" ng-if="(CartOrderCtrl.orderSupportData.cartDiscount.isOrderDiscountExist && !CartOrderCtrl.orderSupportData.isCurrencyMismatch) && (CartOrderCtrl.orderSupportData.cartDiscount.formattedDiscount)">
        <div class="text-success">
        <h6 class="my-0">
            <a lw-popup data-message="[[CartOrderCtrl.cartDiscountDetailsHtml]]" class="lw-pink-color" role="button" data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="focus"><?= __tr('Cart / Product Discount') ?></a>
        </h6>
        </div>
        <span class="text-success"> - [[ CartOrderCtrl.orderSupportData.cartDiscount.formattedDiscount ]]</span>
    </li>

    <li class="list-group-item d-flex justify-content-between bg-light" ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true">
        <div class="text-success">
            <h6 class="my-0">
            <a lw-popup data-message="[[CartOrderCtrl.couponDetailsHtml]]" class="lw-pink-color" role="button" data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="focus" ng-bind="CartOrderCtrl.couponData.title"></a> 
            <i class="float-right fa fa-remove ml-1 lw-cursor-pointer lw-red-color" title="<?= __tr("If you don't want discount remove") ?>" ng-click="CartOrderCtrl.removeCoupon()"> </i> 
            </h6>
            <?= __tr('Code') ?> : <small ng-bind="CartOrderCtrl.couponData.couponCode"></small> 
        </div> 
        <span class="text-success"> - [[CartOrderCtrl.couponData.formattedDiscount]] </span>
    </li>

    <li class="list-group-item d-flex justify-content-between" ng-if="( !CartOrderCtrl.orderSupportData.isCurrencyMismatch) && (CartOrderCtrl.orderSupportData.cartDiscount.isOrderDiscountExist || (CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true))">
        <span><?= __tr('Subtotal') ?> </span>
        <strong ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true"> [[ CartOrderCtrl.orderSupportData.shipping.formettedDiscountPrice ]]</strong>
        <strong ng-if="!CartOrderCtrl.orderSupportData.shipping.discountAddedPrice"> [[ CartOrderCtrl.orderSupportData.formatedCartTotalPrice ]]</strong>
    </li>

    
    
</ul>

@include('order.user.apply-discount-field')