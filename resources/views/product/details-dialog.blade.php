<div class="lw-dialog">
	<!--   main heading  -->
	<div class="lw-section-heading-block" ng-if="productDetailsDialogCtrl.pageStatus">
        <h3 class="lw-header" ng-bind="::productDetailsDialogCtrl.productDetails.transProductName"></h3>
    </div>
	<!--  /main heading  -->
	
	<!--  loader  -->
	<div ng-hide="productDetailsDialogCtrl.pageStatus" class="text-center">
		<div class="loader"><?=  __tr('Loading...')  ?></div>
	</div>
	<!--  / loader  -->

	<div ng-if="productDetailsDialogCtrl.pageStatus">
		<!--  dialog body section  -->
		<div class="row">

			<!--  to show the product image  -->
		  	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 lw-quick-view-thumbnail-holder">
                <div class="lw-main-slider-container">
    				<!-- <a href ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" title="<?= __tr('Product Details') ?>" class=""> <img ng-src="[[ productDetailsDialogCtrl.productDetails.productImage ]]" class="img-responsive lw-quick-view-thumbnail" alt=""></a> -->

                    <a href ng-if="!productDetailsDialogCtrl.previewImageUrl" ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" title="<?= __tr('Product Details') ?>" class=""> <img ng-src="[[ productDetailsDialogCtrl.primaryImages[0]['getProductImageURL'] ]]" class="img-responsive lw-quick-view-thumbnail" alt=""></a>

                    <a href ng-if="productDetailsDialogCtrl.previewImageUrl" ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" title="<?= __tr('Product Details') ?>" class=""> <img ng-src="[[ productDetailsDialogCtrl.previewImageUrl ]]" class="img-responsive lw-quick-view-thumbnail" alt=""></a>

                    <div class="lw-product-details-thumbnail-list col-12 lw-main-slider-row">
                        <span ng-repeat="image in productDetailsDialogCtrl.primaryImages track by $index">
                            <a href rel="lw-product-images" ng-click="productDetailsDialogCtrl.changeImage(image.getProductImageURL, $event, $index)"><img ng-src="[[ image.getProductImageURL ]]"></a>
                        </span>
                    </div>
                </div>
            </div>
            
		  	<!--  /to show the product image  -->
          
			<!--  product details section like price, qty etc  -->
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
				<!--  To show product ID  -->
				<div>
					<?= __tr('ID') ?> : [[ productDetailsDialogCtrl.productDetails.product_id ]]
                    <div class="float-right">
                        @if(getStoreSettings('enable_wishlist') and canAccess('product.wishlist.add_process'))
                            <span ng-if="productDetailsDialogCtrl.isAddedInWishlist" >
                                <button type="button" title="<?= __tr('Remove from wishlist.') ?>" ng-click="productDetailsDialogCtrl.removeFromWishlist()" class="btn btn-light lw-btn mb-0"><i class="fa fa-heart lw-add-to-wishlist-btn"></i></button>
                            </span>
                            <span ng-if="!productDetailsDialogCtrl.isAddedInWishlist">
                                <button type="button" title="<?= __tr('Add to wishlist.') ?>" ng-click="productDetailsDialogCtrl.addToWishlist()" class="btn btn-light lw-btn mb-0"><i class="fa fa-heart-o lw-add-to-wishlist-btn"></i></button>
                            </span>
                        @endif

                        <div class="btn-group" role="group" aria-label="Social sharing">
                        <!-- Share option field start -->
                        @if(getStoreSettings('facebook'))    

                            <!-- Facebook -->        
                            <a href="https://www.facebook.com/sharer.php?u=[[productDetailsDialogCtrl.productDetails.facebookShareUrl]]" target="_blank" title="<?= __tr( 'Share Item on Facebook' ) ?>" class="btn btn-light lw-btn">
                                <i class="fa fa-facebook fa-lg lw-facebook-color" aria-hidden="true"></i>
                            </a>
                            <!-- /Facebook -->
                        @endif


                        @if(getStoreSettings('twitter'))
                            <!-- Twitter -->
                            <a 
                                href="https://twitter.com/share?url=[[productDetailsDialogCtrl.productDetails.twitterShareUrl]]" 
                                    target="_blank" 
                                    title="<?= __tr( 'Share this Item on Twitter' ) ?>" class="btn btn-light lw-btn">
                                <i class="fa fa-twitter fa-lg lw-twitter-color" aria-hidden="true"></i>
                            </a>
                            <!-- /Twitter -->
                        @endif

                        @if(getStoreSettings('enable_whatsapp'))
                        <!-- Wats Up -->
                        <a href="https://wa.me?text=[[productDetailsDialogCtrl.productDetails.watsAppShareUrl]]" target="_blank" data-action="share/whatsapp/share" title="<?= __tr( 'Share this Item on WhatsaApp' ) ?>" class="btn btn-light lw-btn">
                        <i class="fa fa-whatsapp fa-lg lw-whats-app-color" aria-hidden="true"></i></a>
                        <!-- /Wats Up -->
                        @endif
                        <!-- Share option field stop -->
                        </div>
                    </div>
                </div>		
				<!--  / To show product ID  -->

                

				<!--  product categories section  -->
				<div>
	        		<?=  __tr('Available in these categories')  ?> :
					<!--   categories section   -->
					<span ng-repeat="category in productDetailsDialogCtrl.productDetails.productCategories track by $index">
	                <a href="[[ category.categoryUrl ]]">
					  [[ category.name ]] </a><span ng-show=" ! $last ">,</span>
	                
					</span>
					<!--  / categories section  -->
				</div>
				<!-- Rating on item -->
		        <div class="stars lw-stars" ng-show="productDetailsDialogCtrl.enableRating">
					
		            <div id="lwRateIt">
						<hr>
		                <h6>
		                    <span class="title lw-current-rating">
		                        <span ng-bind="productDetailsDialogCtrl.itemRating.rate" title="<?= __tr('Average ratings') ?>" ng-if="productDetailsDialogCtrl.itemRating.rate"></span>
		                        <span title="<?= __tr('Average ratings') ?>" ng-if="!productDetailsDialogCtrl.itemRating.rate"></span>
		                        <small ng-if="productDetailsDialogCtrl.itemRating.totalVotes == 0">
		                            <em><?= __tr('No rating received yet.') ?></em> 
		                        </small>
		                        <small ng-if="productDetailsDialogCtrl.itemRating.totalVotes > 0">
		                            <em><?= __tr('__voteCount__ vote(s)',[
		                            '__voteCount__' => '[[ productDetailsDialogCtrl.itemRating.totalVotes ]]'
		                            ]) ?>
		                            </em> 
		                        </small>
		                    </span>
		                    <small>
		                        <em ng-show="productDetailsDialogCtrl.itemRating.selfRating">- <?= __tr('You have rated __yourRating__', [
		                            '__yourRating__' => '[[ productDetailsDialogCtrl.itemRating.selfRating ]]'
		                            ]) ?> 
									<!-- <span ng-if="productDetailsDialogCtrl.enableRatingReview">
						               <a href="" 
						                    ng-click="productDetailsDialogCtrl.showRatingAndReviews()" 
						                    title="<?= __tr('Show Rating & Reviews') ?>">
						                    <?= __tr('Show Rating & Reviews') ?></a>
						            </span>  -->
		                        </em>
		                    </small>
		                </h6>
		                <div class="stars">
                            <select id="lw-bar-rating" name="rating" autocomplete="off">
                                <option value=""></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
		            </div>
		        </div>
		        <!-- / Rating on item -->
				<hr>
				<!--  /product categories section  -->

				<!--  Form for add to cart section -->
				<form 
					class="lw-form lw-ng-form" 
					name="productDetailsDialogCtrl.[[ productDetailsDialogCtrl.ngFormName ]]" 
					novalidate>
					
					<!--   Show the product options -->
				    <div ng-repeat="option in productDetailsDialogCtrl.productDetails.option track by $index" ng-switch="option.optionValueExist">
				        <span  ng-switch-when="true">

                        <!-- Drop down Selection Option -->
                        <lw-form-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 1 || [[option.type]] == '' ">
							<select 
				                ng-init="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name] = option.option_values[0]"  
				                ng-model="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name]" 
				                class="form-control col-md-6" 
				                name="options[option.name]" 
				                ng-options='(value.addon_price != 0 ? (value.name +" (+"+value.addon_price_format+")") : value.name) for value in option.option_values'
				                ng-change="productDetailsDialogCtrl.updateCartItem(productDetailsDialogCtrl.productDetails.id, true)"

				            ></select>
						</lw-form-field>
                        <!-- Drop down Selection Option -->

                        <!-- Image Selection Option -->
                        <lw-form-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 2">
                            <div class="btn-group-toggle mb-4" data-toggle="buttons">
                                <span class="btn lw-img-radio-btn lw-product-detail-img-option" ng-class="{ 'active' : value.name == option.option_values[0].name}" ng-repeat="value in option.option_values track by $index">
                                    <input type="radio" 
                                    name="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]" 
                                    ng-model="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name]" 
                                    ng-init="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name] = option.option_values[0]" 
                                    ng-change="productDetailsDialogCtrl.updateCartItem(productDetailsDialogCtrl.productDetails.id, true)"
                                    ng-value="value"
                                    checked> 
                                    <span class="image-name lw-image-thumbnail"><img ng-src="[[value.thumbnailURL]]"></span> 
                                    <small class="lw-image-option-text">
                                        [[(value.addon_price != 0 ? (value.name) : value.name)]]
                                    </small>
                                </span>
                            </div>
                        </lw-form-field>   
                        <!-- Image Selection Option -->

                        <!-- Radio Selection Option -->
                        <lw-form-radio-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 3"> 
                            <span>[[option.optionName]] :</span><br>
                            <span ng-repeat="value in option.option_values track by $index">
                                <div class="custom-control custom-radio radio-inline">
                                    <input 
                                    type="radio"
                                    id="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]" 
                                    class="custom-control-input" 
                                    ng-init="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name] = option.option_values[0]"
                                    style="margin: 10px;"
                                    name="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]"
                                    ng-model="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name]"
                                    ng-change="productDetailsDialogCtrl.updateCartItem(productDetailsDialogCtrl.productDetails.id, true)"
                                    ng-value="value">
                                    <label class="custom-control-label" for="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]">
                                    [[(value.addon_price != 0 ? (value.name+" (+"+value.addon_price_format+")") : value.name)]]
                                    </label>
                                </div>
                            </span>&nbsp;
                        </lw-form-radio-field>
                        <!-- / Radio Selection Option -->
                       
						</span>
				    </div>

				    <!--  Price  -->
				    <div ng-if="productDetailsDialogCtrl.productDiscount.productPrice > 0" class="mt-4">
                        <h3 class="lw-effective-price">

                        <span ng-if="productDetailsDialogCtrl.productDiscount.isDiscountExist">
                            <small title="<?= __tr( 'Price based on options selections' ) ?>"></small>
                            <span ng-bind="productDetailsDialogCtrl.productDiscount.formattedProductPrice"></span>

                            <small class="lw-discount-price">
                                <a lw-popup data-message="[[productDetailsDialogCtrl.discountDetailsHtml]]" role="button" data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="focus"><strike ng-bind="productDetailsDialogCtrl.productDetails.basePriceWithAddonPrice"></strike></a>
                            </small>
                        </span>

                        <span ng-if="!productDetailsDialogCtrl.productDiscount.isDiscountExist">
                            <small title="<?= __tr( 'Price based on options selections' ) ?>"></small>
                        <span ng-bind="productDetailsDialogCtrl.productDetails.basePriceWithAddonPrice"></span>
                        </span>					         
				        
				        <!--  To show old price  -->
				    	<span ng-if="productDetailsDialogCtrl.productDetails.old_price">
				    		<small class="lw-price lw-old-price"><strike>[[productDetailsDialogCtrl.productDetails.oldPrice]] </strike> </small>
				    	</span>
				    	<!--  To show old price   -->
				    </h3></div>
				    <!--  /Price  -->
				    <!--  / show the product options -->
					<div ng-if="productDetailsDialogCtrl.optionLength" ng-switch="productDetailsDialogCtrl.optionLength" class="w-price-details">
						<?= __tr('Price details: ') ?>

						<span ng-bind="productDetailsDialogCtrl.productDetails.priceDetails.base_price"></span>
                        <!--  To show old price  -->
                        <span ng-if="productDetailsDialogCtrl.productDetails.priceDetails.option"> + </span>
                        <span ng-repeat="productOption in productDetailsDialogCtrl.productDetails.priceDetails.option" ng-show="productOption.addon_price  >= 0">[[ productOption.translatedOptionName ]] <em ng-bind="productOption.name"></em> [[productOption.addon_price_format]] 
                        	<span  ng-if="!$last && productOption.addon_price >= 0"> + </span>
                         	<span  ng-if="!$last && productOption.addon_price < 0"> - </span>
                     	</span>
					</div>
				    <!--  price details table  -->
				  <!--   <div ng-switch="productDetailsDialogCtrl.optionLength" >
				    	<table  ng-switch-when="true" class='table table-bordered table-striped table-hover table-rounded'>
					        <tbody>
					            <tr>
					                <td><strong><?=  __tr('Base Price')  ?> </strong></td>
					                <td class="text-right">
					                   <span ng-bind="productDetailsDialogCtrl.productDetails.priceDetails.base_price"></span>
					                
					                	<span ng-if="productDetailsDialogCtrl.productDetails.old_price">
					                		<small><strike class="lw-price">[[productDetailsDialogCtrl.productDetails.oldPrice]] </strike> </small>
					                	</span>
					                
					                </td>
					            </tr>
					            <tr ng-repeat="productOption in productDetailsDialogCtrl.productDetails.priceDetails.option">
					                <td>[[ productOption.optionName ]] <em ng-bind="productOption.name"></em> </td>
					                <td class="text-right">
			                			<sapn ng-show="productOption.addon_price != 0">+ [[productOption.addon_price_format]]</sapn>
				                    	<sapn ng-show="productOption.addon_price == 0"> - </sapn>
						            </td>
					            </tr>
					        </tbody>
					    </table>
				    </div> -->
				    
				    <!--  / price details table  -->

                   
				    <!--  show quantity field if product available else show out of stock   -->
				    <div ng-if="productDetailsDialogCtrl.productDiscount.productPrice > 0" ng-switch="productDetailsDialogCtrl.productDetails.out_of_stock">
						
						<!--  Quantity show when the product is in stock -->
						<div ng-if="productDetailsDialogCtrl.productDetails.out_of_stock === 0 || productDetailsDialogCtrl.productDetails.out_of_stock === 4"  class="row">
							<div class="col-md-7 col-12">

					            <lw-form-field field-for="quantity" label="<?= __tr( 'Quantity' ) ?>">
					                
					                <div class="input-group">
					                   <!--  decrement of qunatity btn  -->
					                    <span class="input-group-prepend">
					                        <button title="<?=  __tr('Decrement')  ?>" type="button" class="btn btn-light lw-btn btn-number lw-vxs-hidden" ng-click="productDetailsDialogCtrl.getQtyAction(false, productDetailsDialogCtrl.productData.quantity)">
					                            <i class="fa fa-minus"></i>
					                        </button>
					                    </span>
					                    <!-- / decrement of qunatity btn  -->

					                    <input style="text-align:center" type="number" 
					                      class="lw-form-field form-control"
					                      name="quantity"
					                      ng-required="true"
					                      min="1" 
					                      max="99999"
					                      ng-model="productDetailsDialogCtrl.productData.quantity" />
					                    
					                    <!--  show add & Update cart btn  -->
					                    <span class="input-group-append">
					                        <!--  increment of quantity btn  -->
					                        <button title="<?=  __tr('Increment')  ?>" type="button" ng-click="productDetailsDialogCtrl.getQtyAction(true, productDetailsDialogCtrl.productData.quantity)" class="lw-btn btn btn-light btn-number lw-vxs-hidden">
					                          <i class="fa fa-plus"></i>
					                        </button>
					                        <!-- / increment of quantity btn  -->
					                    </span>                                    
					                    <!--  /show add & Update cart btn  -->
					                </div>
					                
					            </lw-form-field>
							</div>

							<div class="col-md-5 col-12 mt-4">
                                <!--  Add cart btn  -->
                                <button class="lw-btn btn btn-primary btn-lg lw-xs-dblock-btn"  type="submit" ng-click="productDetailsDialogCtrl.addToCart()">
                                    <i class="fa fa-cart-plus"></i>
                                    <span ng-if="!productDetailsDialogCtrl.productData.isCartExist"><?=  __tr("Add to Cart")  ?></span>
                                    <span ng-if="productDetailsDialogCtrl.productData.isCartExist"><?=  __tr("Update Cart")  ?></span>
                                </button>
                                <!--  / Add cart btn  -->
                            </div>
				        <!--  /Quantity show when the product is in stock  -->
				        </div>
				      	<!--  show out of stock alert msg  -->
				        <div ng-switch-when="1" class="alert alert-warning">
				            <?=  __tr('Out of Stock')  ?>
				        </div>
                        <div ng-switch-when="2" class="alert alert-warning">
                            <?=  __tr('Comming Soon')  ?>
                        </div>
                        <div ng-switch-when="3" class="alert alert-info">
                            <?=  __tr('Launching On : ')  ?> <span ng-bind="productDetailsDialogCtrl.launchingDate"></span>
                        </div>
						<!--  / show out of stock alert box  -->
				        
				    </div>
				    <!-- / show ... else show out of stock   -->
                    <div ng-if="productDetailsDialogCtrl.productDiscount.productPrice < 0">
                        @if(!isAdmin())
                        <div class="alert alert-warning">
                            <?=  __tr('Discount / pricing conflicts, please contact store owner.')  ?>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <?=  __tr('As per applicable coupon the product price has been drop below zero. Thus this product not available or add to cart.')  ?>
                        </div>
                        @endif
                    </div>
				</form>
				<!--  Form for add to cart section -->
			</div>
			<!-- / product details section like price, qty etc  -->

		</div>
		<!-- / dialog body section  -->


		<!-- dialog action btns  -->
		<div>

			<div class="lw-section-heading-block"></div>
			
			<!--  show product details btn  -->
			<a href 
				ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" 
				title="<?= __tr('Product Details') ?>" 
				class="lw-btn btn btn-light btn-sm lw-show-process-action lw-xs-dblock border">
				<i class="fa fa-search icon-white"></i> <?=  __tr('Product Details')  ?>
			</a>
			<!-- /show product details btn  -->

            <!--  Add Compare  -->
            <span ng-if="productDetailsDialogCtrl.productSpecExists && !productDetailsDialogCtrl.productDetails.addedInCompareList">
                <button
                    ng-click="productDetailsDialogCtrl.addProductCompare( productDetailsDialogCtrl.productID)"
                    title="<?= __tr('Add Compare') ?>" 
                    class="lw-btn btn btn-light btn-sm">
                    <i class="fa fa-plus"></i> <?=  __tr('Add Compare')  ?>
                </button>
            </span>
            <!-- /Add Compare -->

            <!--  Removed Compare  -->
            <span ng-if="productDetailsDialogCtrl.productSpecExists && productDetailsDialogCtrl.productDetails.addedInCompareList">
                <button
                    ng-click="productDetailsDialogCtrl.removeProductCompare( productDetailsDialogCtrl.productID)"
                    title="<?= __tr('Remove from Compare') ?>" 
                    class="lw-btn btn btn-light btn-sm">
                    <i class="fa fa-remove"></i> <?=  __tr('Remove from Compare')  ?>
                </button>
            </span>
            <!-- /Removed Compare -->

			{{-- Disabled btn when any cart item is invalid then display btn conditionally --}}
	    	<span ng-switch="productDetailsDialogCtrl.productData.isCartExist">

	        	<!--  Go to Shopping Cart btn  -->		
				<a ng-switch-when="true" title="<?=  __tr('Go to Shopping Cart')  ?>" class="lw-btn btn btn-warning btn-sm pull-right lw-show-process-action lw-xs-dblock border" href ng-click="productDetailsDialogCtrl.openCartDialog(false)"><i class="fa fa-shopping-cart icon-white"></i> <?=  __tr('Go to Shopping Cart')  ?></a>
				<!-- / Go to Shopping Cart btn  -->	


	        	<!--  Go to Shopping Cart btn  -->
				<a ng-switch-when="false" title="<?=  __tr('Go to Shopping Cart')  ?>" class="lw-btn btn btn-light btn-sm pull-right lw-show-process-action lw-xs-dblock border" ng-click="productDetailsDialogCtrl.openCartDialog(false)"><i class="fa fa-shopping-cart icon-white"></i> <?=  __tr('Go to Shopping Cart')  ?></a>
				<!-- / Go to Shopping Cart btn  -->	
			</span>
			{{--/ Disabled btn ....... conditionally  --}}

		</div>
		<!-- / dialog action btns -->
		
	</div>

</div>
@push('appScripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.lw-discount-details').popover({
            html: true
        });        
    });
</script>
@endpush
<script type="text/_template" id="discountDetailsTemplate">
<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= __tr('Discount Description') ?></th>
                <th class="text-right"><?= __tr('Amount') ?></th>
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
                <td width="50%">
                    <strong class="pull-right">
                        <%= __tData.discount %>
                    </strong>
                </td>
            <tr>
        </tbody>
    </table>
</div>
</script>
<script type="text/_template" id="lwMainSliderRow">                                
    <div class="lw-main-slider-container">
        <div class="lw-details-page-main-image col-12 lw-main-slider-preview">
            <a href ng-click="productDetailsCtrl.showImagesInColorBox()">
                <img src="<%= __tData.firstImage.getProductImageURL %>" class="img-responsive lw-quick-view-thumbnail lw-zoom-image" alt="">
            </a>
        </div>
        <div class="lw-product-details-thumbnail-list col-12 lw-main-slider-row">
            <% counter = 0 %>                    
            <%  _.forEach(__tData.images, function(row, index) { %>                
                <a href rel="lw-product-images" ng-click="productDetailsDialogCtrl.changeImage('<%= row.getProductImageURL %>', $event, '<%= counter++ %>')"><img src="<%= row.getProductImageURL %>"></a>                
            <%  })  %>
        </div>
    </div>
</script>