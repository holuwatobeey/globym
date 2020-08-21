<!--     
    View        : Detail Dialog 
    Component   : Report
    Engine      : ManageReportEngine 
    Controller  : OrderReportController
---------------------------------------------------------------------------  -->
<div ng-controller="OrderReportController as orderDialogCtrl"  ng-if="canAccess('manage.order.report.details.dialog')">
	
	<div class="lw-section-heading-block">
	    <!--  main heading  -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Order Details' ) ?>
	        </span>
	    </h3>
	    <!--  /main heading  -->
	</div>
	

	<div><strong><?= __tr( 'Customer Name' ) ?></strong> : [[ orderDialogCtrl.user.fullName ]] ( <small><em> [[ orderDialogCtrl.user.email ]] </em></small> )</div><br>

    {{-- order & payment --}}
        <div class="row">
            {{-- order section--}}  
                <div class="col-sm-6">

                     <fieldset class="lw-fieldset-2">
                        <legend>
                            <?=  __tr('Details')  ?> 
                        </legend><br>

                        <ul class="list-group">
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="orderDialogCtrl.order.orderUID"></span>
                            <strong><?= __tr('ID') ?></strong>
                          </li>
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="orderDialogCtrl.order.formatedOrderPlacedOn"></span>
                            <strong><?= __tr('Placed On') ?></strong>
                          </li>
                          <li class="list-group-item" ng-switch="orderDialogCtrl.order.orderStatus">

                            <span  ng-switch-when="1" 
                                   class="label label-primary pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="2" 
                                    class="label label-warning pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="3" 
                                    class="label label-danger pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="4" 
                                    class="label label-warning pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="5" 
                                    class="label label-warning pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="6" 
                                    class="label label-success pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="7" 
                                    class="label label-warning pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="11" 
                                    class="label label-warning pull-right" 
                                   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

                            <strong><?= __tr('Status') ?></strong>
                          </li>
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="orderDialogCtrl.order.formatedOrderType"></span>
                            <strong><?= __tr('Type') ?></strong>
                          </li>
                        </ul>

                    </fieldset>
                </div>
            {{-- / order section--}} 

            {{-- payment section--}}
                <div class="col-sm-6">

                     <fieldset class="lw-fieldset-2">
                        <legend>
                            <?=  __tr('Payment Details')  ?> 
                        </legend><br>

                        <ul class="list-group">

                            <li class="list-group-item">
                                <span class="pull-right" ng-bind="orderDialogCtrl.order.formatedPaymentMethod"></span>
                                <strong><?= __tr('Method') ?></strong>
                            </li>

                            <li class="list-group-item" ng-switch="orderDialogCtrl.order.paymentStatus">
                                <span 
                                    ng-switch-when="1"  
                                    class="label label-warning pull-right" 
                                    ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="2"  
                                    class="label label-success pull-right" 
                                    ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="3"  
                                    class="label label-danger pull-right" 
                                    ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="4"  
                                    class="label label-warning pull-right" 
                                    ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="5"  
                                    class="label label-info pull-right" 
                                    ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

                                <strong><?= __tr('Status') ?></strong>
                            </li>

                            {{-- PaymentCompletedOn --}}
                            <li class="list-group-item" ng-if="orderDialogCtrl.order.paymentCompletedOn">
                                <strong><?= __tr('Completed On') ?></strong>
                                <span class="pull-right" ng-bind="orderDialogCtrl.order.paymentCompletedOn"></span>
                            </li>
                            {{-- /PaymentCompletedOn --}}

                        </ul>

                    </fieldset>
                </div>

            {{-- / payment section--}}
        </div>
    {{-- / order & payment --}}

	{{-- cart summary table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h4><strong><?=  __tr('Cart summary')  ?></strong></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive lw-shopping-cart">
                        <table class="lw-order-cart-table table table-condensed">
                            <thead class="page-header">
				                <tr>
				                	<th class="text-center"><?=  __tr('Thumbnail')  ?></th>
				                    <th><?=  __tr('Item Description')  ?></th>
				                    <th class="text-center"><?=  __tr('Qty')  ?></th>
				                    <th class="text-right"><?=  __tr('Price')  ?></th>
				                    <th class="text-center"><?=  __tr('Subtotal')  ?></th>
				                </tr>
				            </thead>
                            <tbody>
                            
			            	{{-- products image, option and price come in this section --}}
				                <tr ng-repeat="item in orderDialogCtrl.orderProducts.products">
				              
				                    <td class="lw-product-thumbnail-column">
				                    	<a class="lw-product-thumbnail" href ng-href="[[item.detailURL]]"><img ng-src="[[ item.imagePath ]]"></a>
				                    </td>
				                    <td>
				                    	{{-- product name and price --}}
				                    	<strong> <span ng-bind="item.productName"> </span> : </strong> (<span>+ [[ item.formatedPrice ]]</span>) <br>
				                    	{{-- product name and price --}}
										<div>
				                            <span ng-repeat="(key, option) in item.option">
				                            	<strong>[[option.optionName]] : </strong>[[option.valueName]] <span ng-show="option.addonPrice">( +[[option.formatedOptionPrice]])</span><br>
				                            </span>
				                        </div>
				                    </td>
				                    <td class="text-center" ng-bind="item.quantity"></td>
				                    <td align="right" ng-bind="item.formatedProductPrice"></td>
				                    <td align="right" ng-bind="item.formatedTotal"></td>
				                </tr>
								
				                <tr>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-amount-td lw-highrow text-right"><h4><strong><?=  __tr('Subtotal')  ?></strong></h4></td>
                                    <td class="lw-highrow lw-amount-td">
                                    	<h4><span ng-bind="orderDialogCtrl.orderProducts.formatedSubtotal"></span></h4>
							        </td>
                                </tr>
                                <tr ng-if="orderDialogCtrl.order.cartDiscount.isOrderDiscountExist">
                                    <td colspan="3" class="lw-highrow"></td>
                                    <td class="lw-amount-td lw-highrow text-right">
                                        <h4> <?=  __tr('Discount')  ?></h4>
                                    </td>
                                    <td class="lw-highrow lw-amount-td text-right">
                                        <h4>- <span ng-bind="orderDialogCtrl.order.cartDiscount.shortFormatDiscount"></span></h4>
                                    </td>
                                </tr>
                                <tr ng-if="orderDialogCtrl.order.cartDiscount.isOrderDiscountExist">
                                    <td colspan="3" class="lw-highrow text-right"></td>
                                    <td class="lw-amount-td lw-highrow text-right">
                                        <h4> <?=  __tr('Total')  ?></h4>
                                    </td>
                                    <td class="lw-highrow lw-amount-td text-right">
                                        <h4><span ng-bind="orderDialogCtrl.order.cartDiscount.formatNewCartTotal"></span></h4>
                                    </td>
                                </tr>
				            </tbody>
			            	{{--/ products image, option and price come in this section --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	{{--/ cart summary table --}}
		
    {{-- coupon details section --}}
    <div class="row" ng-show="orderDialogCtrl.coupon">

        <div class="col-md-12">

			<ul class="list-group">

				<li class="list-group-item">
					<span class="pull-right"> - [[ orderDialogCtrl.order.formatedOrderDiscount ]]</span>
                	<strong ng-bind="orderDialogCtrl.coupon.title"></strong> :- <em><strong> <?= __tr('Applied Coupon Code')  ?></strong></em>
					<span class="label label-success"  ng-bind="orderDialogCtrl.coupon.code"></span>
			  	</li>


			  	<li class="list-group-item" ng-if="orderDialogCtrl.coupon.description">
			    	<span ng-bind="orderDialogCtrl.coupon.description"></span>
			  	</li>

			</ul>

        </div>

    </div>
	{{--/ coupon details section --}}
	
	{{-- Address section --}}
    <div class="row">

        <div ng-if="orderDialogCtrl.sameAddress == false">
			{{-- Shipping Address section --}}
        	<div class="col-lg-6">
		        <div class="card mb-3">
	                <div class="card-header">
	                    <strong><?= __tr('Shipping Address')  ?></strong>
	                </div>
	                <div class="card-body">
	                    <address class="lw-address">
	                        <strong ng-bind="orderDialogCtrl.user.fullName"></strong><br>
			            	<strong><?= __tr('Address')  ?></strong> (<em ng-bind="orderDialogCtrl.shippingAddress.type"></em>)
			                <br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.addressLine1"></span><br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.addressLine2"></span><br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.city"></span>, 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.state"></span>, 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.country"></span><br>
	                        <span title="<?=  __tr('Pin Code')  ?>"><?=  __tr('Pin Code')  ?> : </span> 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.pinCode"></span>
	                    </address>
	                </div>
		        </div>
	        </div>
			{{-- Shipping Address section --}}

			{{-- Billing Address section --}}
			<div class="col-lg-6">
		        <div class="card mb-3">
	                <div class="card-header">
	                    <strong><?= __tr('Billing Address')  ?></strong>
	                </div>
	                <div class="card-body">
	                    <address class="lw-address">
	                        <strong>
	                        	<span ng-bind="orderDialogCtrl.billingAddress.type"></span>
	                        </strong><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.addressLine1"></span><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.addressLine2"></span><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.city"></span>, 
	                        <span ng-bind="orderDialogCtrl.billingAddress.state"></span>, 
	                        <span ng-bind="orderDialogCtrl.billingAddress.country"></span><br>
	                        <span title="<?=  __tr('Pin Code')  ?>"><?=  __tr('Pin Code')  ?> : </span> 
	                        <span ng-bind="orderDialogCtrl.billingAddress.pinCode"></span>
	                    </address>
	                </div>
		        </div>
	        </div>
	    </div>
		{{-- Billing Address section --}}

		{{-- Shipping / Billing Address section --}}
        <div class="col-lg-12">
			<div class="card mb-3" ng-if="orderDialogCtrl.sameAddress == true"> 
		        <div class="card-header">
		            <strong><?= __tr('Shipping / Billing Address')  ?></strong>
		        </div>
		        <div class="card-body">
		            <address class="lw-address">
		            	<strong ng-bind="orderDialogCtrl.user.fullName"></strong><br>
		            	<strong><?= __tr('Address')  ?></strong> (<em ng-bind="orderDialogCtrl.shippingAddress.type"></em>)
		                <br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.addressLine1"></span><br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.addressLine2"></span><br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.city"></span>, 
		                <span ng-bind="orderDialogCtrl.shippingAddress.state"></span>, 
		                <span ng-bind="orderDialogCtrl.shippingAddress.country"></span><br>
		                <span title="<?=  __tr('Pin Code')  ?>"><?=  __tr('Pin Code')  ?> : </span> 
		                <span ng-bind="orderDialogCtrl.shippingAddress.pinCode"></span>
		            </address>
		        </div>
		    </div>
        </div>
		{{-- Shipping / Billing Address section --}}
    </div>
	{{--/ Address section --}}

	{{-- Shipping details section --}}
    <div class="row mb-3">

        <div class="col-md-12">
				
			<ul class="list-group">

				<li class="list-group-item">
                	<strong><?= __tr('Shipping Charges') ?></strong>
			    	<span class="pull-right" ng-show="orderDialogCtrl.order.shippingAmount"> 
                		+ <span ng-bind="orderDialogCtrl.order.formatedShippingAmount"></span>
	                </span>
					<span class="pull-right" ng-show="!orderDialogCtrl.order.shippingAmount">
                		<?=  __tr('Free')  ?>
                	</span>
			  	</li>
                <li class="list-group-item" ng-repeat="shippingData in orderDialogCtrl.shipping" ng-if="orderDialogCtrl.shipping.length > 0">
                    <span ng-if="shippingData.shipping_method_title">
                        ( <?= __tr('Method') ?> : [[ shippingData.shipping_method_title ]] )
                    </span>
                    <p ng-bind="shippingData.notes"></p>
                </li>
			</ul>

        </div>

    </div>
	{{--/ Shipping details section --}}

	{{-- Tax details section --}}
    <div class="row" ng-show="orderDialogCtrl.taxes">

        <div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr ng-repeat="tax in orderDialogCtrl.taxes">
							<td colspan="3">
								<sapn>
							   		<strong ng-show="tax.label" ng-bind="tax.label"></strong><br>
							   		<sapn ng-show="tax.notes" ng-bind="tax.notes"></sapn>
							   	</sapn>
							   	<strong ng-show="!tax.label"><?=  __tr('Tax')  ?></strong>
			               	</td>
			               	<td class="lw-amount-td">
							   	<!-- Tax Amount -->
			                	<span ng-show="tax.taxAmount" >
			                		+ <span ng-bind="tax.formatedTaxAmount"></span>
			                	</span>
			                	<!-- /Tax Amount -->
			                </td>
						</tr>
					</thead>
				</table>
			</div>
        </div>

    </div>
	{{--/ Tax details section --}}
		
	{{-- Total payable amount --}}
	<div class="">
		<div class="table-responsive">
			<table class="table table-bordered" border="1">
				<thead>
					<tr>
						<td class="lw-emptyrow text-center"><h3><?= __tr('Total Amount') ?></h3></td>
			            <td class="lw-emptyrow lw-amount-td text-right">
			            	<h3><span ng-bind="orderDialogCtrl.order.formatedTotalOrderAmount"></span>
						</td>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	{{-- /Total payable amount --}}
	<div class="lw-dotted-line"></div> 
    <!--  close dialog button -->
	<div class="form-group lw-form-actions">
   		<button type="button" class="lw-btn btn btn-light pull-right" ng-click="orderDialogCtrl.close()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
	<!--  /close dialog button -->
	
</div>