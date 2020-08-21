
	{{-- /if coupon code is invalid then show this message --}}
  	<div class="alert alert-danger" ng-if="CartOrderCtrl.couponStatus == 2 && CartOrderCtrl.couponMessage == true">
		{{-- coupon invalid message --}}
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    <div>
	    	<?= __tr('Please check your coupon code and enter again.') ?>
	    </div>
	    {{-- coupon invalid message --}}
	</div>
	{{-- /if coupon code is invalid then show this message --}}
	
	{{-- /if coupon code is invalid then show this message --}}
  	<div class="alert alert-danger" ng-if="CartOrderCtrl.couponStatus == 9 && CartOrderCtrl.couponMessage == true">
  		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    <div>
	    	<span ng-bind="CartOrderCtrl.validCouponAmtMessage"></span>
	    </div>
	</div>
    {{-- /if coupon code is invalid then show this message --}}	
    
	<div class="card p-2" ng-hide="CartOrderCtrl.couponStatus == 1">

        <lw-form-field field-for="couponCode"  label="" v-label="<?= __tr( 'Discount Coupon Code' ) ?>">

            <div class="input-group">
                <input type="text" class="form-control lw-form-field" name="couponCode" placeholder="<?= __tr('Coupon code') ?>" autocomplete="off" ng-model="CartOrderCtrl.orderData.code">
                <div class="input-group-append">
                    <button type="button" 
                        class="btn btn-secondary" 
                        ng-disabled="!CartOrderCtrl.orderData.code"
                        ng-click="CartOrderCtrl.applyCoupon(CartOrderCtrl.orderData.code, CartOrderCtrl.orderData.cartTotalPrice)" title="<?=  __tr('Apply')  ?>"
                    ><?=  __tr('Apply')  ?></button>
                </div>
            </div>

        </lw-form-field>
    </div>

    <script type="text/_template" id="couponDetailsTemplate">
        <div>
            <strong><?= __tr('Title') ?> : </strong> <%= __tData.title %><br>
            <strong><?= __tr('Description') ?> : </strong> <%= __tData.description %>
        </div>
    </script>