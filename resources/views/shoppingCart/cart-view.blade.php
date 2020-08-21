<div class="lw-shopping-cart-dialog-content" ng-controller="CartController as CartCtrl">

    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header">@section('page-title',  __tr('Shopping Cart')) <?= __tr('Shopping Cart') ?></h3>
    </div>
    <!-- /main heading -->

    <div  ng-if="!CartCtrl.cartDataStatus" class="text-center">
        <div class="loader"><?=  __tr('Loading...')  ?></div>
    </div>

    <form class="lw-form lw-ng-form" name="CartCtrl.[[ CartCtrl.ngFormName ]]" novalidate>

        <div ng-if="CartCtrl.cartDataStatus">
            
            <!-- table -->
            <div class="table-responsive lw-shopping-cart" ng-hide="CartCtrl.cartData.items == ''">
                
                <table class="table table-bordered" cellspacing="0" width="100%">
                    <thead class="page-header">
                        <tr>
                            <th class="text-center"><?= __tr('Thumbnail') ?></th>
                            <th><?= __tr('Product Description') ?></th>                            
                            <th class="text-right"><?= __tr('Price') ?></th>
                            <th width="25%" class="text-center" style="min-width: 180px;"><?= __tr('Qty') ?></th>
                            <th class="text-right"><?= __tr('Subtotal') ?></th>
                            <th width="10%" class="text-center"><?= __tr('Remove') ?></th>
                        </tr>
                    </thead>
                    <tbody ng-if="CartCtrl.cartData.items">
                        
                        <tr ng-repeat="item in CartCtrl.cartData.items track by item.rowid" id="rowid_[[item.rowid]]"> 
                            <td class="lw-product-thumbnail-column">
                                <a class="lw-product-thumbnail" href ng-href="[[item.productDetailURL]]"><img ng-src="[[ item.thumbnail_url ]]"></a>
                            </td>
                            <td>
                                <strong> <a href ng-href="[[item.productDetailURL]]" ng-bind="item.name"> </a> </strong> <span ng-show="item.options.length > 0">( [[item.formated_price]] ) <br></span>
                                <span ng-repeat="(key, option) in item.options">
                                    <span ng-show="option.addonPrice != 0">
                                        <strong>[[option.optionName]] : </strong> [[option.valueName]] ( +[[option.formated_addon_price]])<br>
                                    </span>
                                    <span ng-show="option.addonPrice == 0">
                                        <strong>[[option.optionName]] : </strong> [[option.valueName]]<br>

                                    </span>
                                </span>
                                <span ng-if="item.productDiscount.isDiscountExist">
                                    <strike ng-bind="item.beforeDiscountPrice"></strike>

                                    <?= __tr('(__total__ Off)', ['__total__' => '[[ item.productDiscount.discount ]]']) ?>
                                </span>
                                <div class="lw-error-msg-order">
                                    <span class="lw-order-product-stock">
                                        <span ng-if="item.ERROR_MSG" ng-bind="item.ERROR_MSG"></span>
                                        <span ng-if="item.showRefreshButton">
                                            <a href title="<?= __tr('Refresh') ?>" ng-click="CartCtrl.refreshProduct(item)"><i class="fa fa-refresh"></i></a>
                                        </span>
                                    </span>
                                </div>
                            </td>                            
                            <td class="text-right" ng-bind="item.new_price"></td>
                            <td  width="60">

                                <!-- Quantity -->     
                                <lw-form-field field-for="items.[[ item.rowid ]].qty" class="lw-form-group" label="">

 
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button title="<?=  __tr('Decrement')  ?>" type="button" class="btn btn-light lw-btn" ng-click="CartCtrl.updateQuantity(false, item.rowid, item.qty, item.new_price)">
                                            <span class="fa fa-minus"></span>
                                            </button>
                                        </div>

                                        <input style="text-align:center;min-width:40px;" type="number"
                                        class="lw-form-field form-control"
                                        name="items.[[ item.rowid ]].qty"
                                        ng-model="item.qty"
                                        ng-blur="CartCtrl.updateQuantity('eventUp', item.rowid, item.qty, item.new_price)"  />

                                        <div class="input-group-append">
                                            <button title="<?=  __tr('Loading..')  ?>" type="button" class="btn btn-light lw-btn" ng-if="item.qtyStatus == false">
                                            <i class="fa fa-spinner fa-spin"></i> 
                                            </button>
                                            <button title="<?=  __tr('Increment')  ?>" type="button" ng-click="CartCtrl.updateQuantity(true, item.rowid, item.qty, item.new_price)" class="btn btn-light lw-btn">
                                            <span class="fa fa-plus"></span>
                                            </button>
                                        </div>
                                        
                                    </div>

                                </lw-form-field>
                                <!-- /Quantity -->

                            </td>
                            <td class="text-right" ng-bind="item.new_subTotal"></td>
                            <td class="text-center"><a class="btn btn-default" href="" title="<?= __tr('Remove') ?>" ng-click="CartCtrl.removeCartItem(item.rowid)"><span class="fa fa-trash-o fa-lg"></span></a></td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" id="shopping_cart_total" ng-hide="CartCtrl.cartData.items == ''"  class="alert text-right">
                                <span ng-if="!CartCtrl.cartDiscount.isOrderDiscountExist && !CartCtrl.isCurrencyMismatch">
                                    <strong><?=  __tr('Cart Total')  ?>: [[CartCtrl.cartData.totalPrice.total]] </strong> 
                                </span>
                                
                                <span ng-if="CartCtrl.cartDiscount.isOrderDiscountExist && !CartCtrl.isCurrencyMismatch">
                                    <div class="pull-right lw-order-discount">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td><strong><?=  __tr('Cart Total')  ?>:</strong></td>
                                                    <td><strong>[[CartCtrl.cartTotalBeforeDiscount.total]]</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a ng-click="CartCtrl.openDiscountDetails()" href>
                                                            <strong><?=  __tr('Discount')  ?>:</strong>
                                                        </a>
                                                    </td>
                                                    <td>- [[CartCtrl.cartDiscount.formattedDiscount]]</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?=  __tr('Total')  ?>:</strong></td>
                                                    <td><strong>[[CartCtrl.cartData.totalPrice.total]]</strong></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="pull-right" style="display: none;" id="lw-discount-detail-table" ng-bind-html="CartCtrl.discountDetailsHtml">
                                    </div>
                                </span> 
                                <span ng-if="CartCtrl.isCurrencyMismatch">
                                    <strong><?=  __tr('Cart Total')  ?>:</strong> N/A
                                </span>                    
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="alert alert-info">
                                <?= __tr( 'Please continue to proceed order' ) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <!-- /table -->

            <!--  if cart is empty  -->
            <div class="alert alert-info" ng-if="CartCtrl.cartData.items.length == 0">
                <?=  __tr("Your cart is empty.")  ?>
            </div>
            <!--  /if cart is empty -->

			<div class="lw-dotted-line"></div>
			
            <!-- action button -->
            <div class="lw-form-actions">
            
                <a ng-click="CartCtrl.cancel()" ng-if="CartCtrl.pageType == false" class="btn btn-light lw-btn lw-xs-dblock-btn" title="<?= __tr('Continue Shopping') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __tr('Continue Shopping') ?></a>

                <a href="<?=  route('home.page')  ?>" ng-if="CartCtrl.pageType == true" class="btn btn-light lw-btn lw-show-process-action lw-xs-dblock-btn" title="<?= __tr('Close for Now & do some more Shopping!!') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __tr('Continue Shopping') ?></a>
				
				{{-- Disabled btn when any cart item is invalid then display btn conditionally --}}
            	<span class="pull-right lw-xs-dblock-btn" ng-switch="CartCtrl.disabledStatus">

                    <span ng-if="CartCtrl.isLoggedIn">
                        <a ng-switch-when="true" 
                            href="<?=  route('order.summary.view')  ?>"
                            ng-class="{ 'disabled' : CartCtrl.cartQuantity > 9999 }" 
                            class="btn btn-success lw-btn btn-md lw-show-process-action lw-xs-dblock-btn"
                            title="<?= __tr('Complete your Order') ?>">
                            <?=  __tr('Complete your Order')  ?> 
                            <i class="fa fa-check-circle-o fa-fw"></i>
                        </a>
                    </span>
                	
                    <span ng-if="!CartCtrl.isLoggedIn">
                        <a ng-switch-when="true"
                            ng-click="CartCtrl.openLoginDialog()"
                            href="" 
                            class="btn btn-success lw-btn btn-md lw-xs-dblock-btn" 
                            title="<?= __tr('Complete your Order') ?>">
                            <?=  __tr('Complete your Order')  ?> 
                            <i class="fa fa-check-circle-o fa-fw"></i>
                        </a>
                    </span>

                	<button ng-switch-when="false" 
	                	disabled
	                	href 
	                	class="btn btn-success lw-btn btn-md lw-xs-dblock-btn" 
	                	title="<?= __tr('Complete your Order') ?>">
	                	<?= __tr('Complete your Order') ?> 
	                	<i class="fa fa-check-circle-o fa-fw"></i>
	                </button>

				</span>
				{{--/ Disabled btn ....... conditionally  --}}

                <a ng-hide="CartCtrl.cartData.items == ''" href ng-click="CartCtrl.removeAllItemsItem()" class="btn btn-danger lw-btn lw-show-process-action lw-xs-dblock-btn" title="<?= __tr('Empty Cart') ?>"><?= __tr('Empty Cart') ?></a>

            </div>
            <!-- /action button -->
            
        </div>

    </form>
</div>
<script type="text/_template" id="discountDetailsDialogTemplate">
<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="60%"><?= __tr('Discount Description') ?></th>
                <th class="text-right" width="40%"><?= __tr('Amount') ?></th>
            </tr>                    
        </thead>
        <tbody>
            <% _.forEach(__tData.discountDetails, function(item) { %>
            <tr>
                <td>
                    <div>
                        <strong><%= item.title %></strong>
                        (<?= __tr('__discount__ Off', [
                            '__discount__' => '<%= item.formattedDiscountAmt %>'
                        ]) ?>)
                    </div>
                    <div>
                        <%= item.description %> 
                        (<?= __tr('Max Discount __maxDiscount__', [
                            '__maxDiscount__' => '<%= item.formattedMaxAmount %>'
                        ]) ?>)
                    </div>
                </td>
                <td>
                    <span class="pull-right">
                        <%= item.formattedSingleDiscount %>
                    </span>
                </td>
            </tr>
            <% }); %>
            <tr>
                <td>
                    <strong><?= __tr('Total Discount') ?></strong>
                </td>
                <td>
                    <strong class="pull-right">
                        <%= __tData.formattedDiscount %>
                    </strong>
                </td>
            <tr>
        </tbody>
    </table>
</div>
</script>