	{{-- if coupon code applied successfully then show this message --}}
	<div class="" ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true">
        <strong>
            <a lw-popup data-message="[[CartOrderCtrl.couponDetailsHtml]]" class="lw-pink-color" role="button" data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="focus">[[ CartOrderCtrl.couponData.couponCode ]]</a>
            <span class="lw-success">
                </i><?= __tr('coupon code is applied') ?>
            </span>            
        </strong><br>

        <span ng-if="CartOrderCtrl.couponData.productScope == 2 && CartOrderCtrl.couponData.discountProductDetails.length > 0">
            <?= __tr(' you got a discount for') ?>
            (<span ng-repeat="product in CartOrderCtrl.couponData.discountProductDetails">
                [[ product.name ]] : [[ product.p_discount ]]<span ng-if="!$last">,</span>
            </span>)
        </span><br>
        
        <a href class="btn btn-danger btn-sm lw-btn" ng-click="CartOrderCtrl.removeCoupon()" title="<?=  __tr('Remove')  ?>"><i class="fa fa-trash-o"></i> <?=  __tr('Remove')  ?></a>

  	</div>
  	{{-- /if coupon code applied successfully then show this message --}}
	
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
	
<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12 mt-2" ng-hide="CartOrderCtrl.couponStatus == 1">
	
	{{-- Input-group for coupon code --}}
	<div >

		<!-- Code here -->
        <lw-form-field field-for="couponCode" label="<?= __tr( 'Discount Coupon Code' ) ?>">

				<div class="input-group">

					<input type="text" 
		              	class="lw-form-field form-control"
		              	name="couponCode"	  
			  		   	placeholder="<?= __tr('Code here.') ?>" 
			  		   	autocomplete="off"
			  		   	ng-model="CartOrderCtrl.orderData.code" />
                        <div class="input-group-append">
                            {{-- apply button --}}
                            <button  
                            ng-disabled="!CartOrderCtrl.orderData.code" 
                            class="btn btn-warning lw-btn" 
                            ng-click="CartOrderCtrl.applyCoupon(CartOrderCtrl.orderData.code, CartOrderCtrl.orderData.cartTotalPrice)" 
                            title="<?=  __tr('Apply')  ?>"><?=  __tr('Apply')  ?>
                            </button>
                            {{-- /apply button --}}
                        </div>
                </div>
            
        </lw-form-field>
        <!-- /Code here -->
		
	</div>
	{{-- /Input-group for coupon code --}}
</div>
{{-- formatted discount --}}
<div class="pull-right" ng-if="CartOrderCtrl.couponStatus == 1">
	- <span ng-bind="CartOrderCtrl.couponData.formattedDiscount"></span>
</div>
{{-- /formatted discount --}}

<script type="text/_template" id="couponDetailsTemplate">
    <div>
        <strong><?= __tr('Title') ?> : </strong> <%= __tData.title %><br>
        <strong><?= __tr('Description') ?> : </strong> <%= __tData.description %>
    </div>
</script>