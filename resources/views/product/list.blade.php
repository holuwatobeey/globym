@section('page-image', getStoreSettings('logo_image_url'))
@section('twitter-card-image', getStoreSettings('logo_image_url'))
@section('page-url', url()->current())

<!--  To show the products list   -->
<div ng-controller="ProductsController as productsCtrl">

<?php $featuredProductsRoute = ($currentRoute === route("products.featured") || ($customRoute === "products.featured")) ? true : false; ?>

	<!--  To show the heading of product  -->
	<div class="lw-image-width">
		@if(empty($brand))
		    <div class="lw-section-heading-block">	
		        <!--  main heading  -->
		        <h3 class="lw-section-heading">

		            @if(isset($category) and !__isEmpty($category))

			            <?=  $category->name  ?>
						<!--  page title set in browser tab  -->
			            @section('page-title') 
				        	<?= $category->name ?>
				        @endsection
				        <!--  / page title set in browser tab  -->
					
		            @elseif($currentRoute === "products.featured" || $customRoute === "products.featured") 
						<!--  page title set in browser tab  -->
	                    @section('page-title') 
				        	<?= __tr('Featured Products') ?>
				        @endsection
				        <!--  / page title set in browser tab  -->
	                    <?= __tr('Featured Products') ?>

		            @elseif(!empty($searchTerm)) 
						<!--  page title set in browser tab  -->							
	           			@section('page-title') 
				        	<?= e( __tr('__productCount__ Products found for : __searchTerm__  term', [
                                '__productCount__' => $productCount,
                                '__searchTerm__' => $searchTerm 
                            ])) ?>
				        @endsection
						<!--  / page title set in browser tab  -->	
		             	<?= e( __tr('Products Search') ) ?>

		              <div>
			              <small>
				              <?= __tr('found __productCount__ results for', [
				              		'__productCount__' => $productCount
				              ]) ?>
			              </small>
			              <div class="btn-group" role="group">
				              	<a href="" class="lw-btn btn btn-light btn-sm" title="<?=  $searchTerm  ?>">
				              		<?= e( $searchTerm ) ?>
				              	</a>
				              	<a  href="<?= route('products') ?>" 
									class="lw-btn btn btn-light btn-sm" title="<?=  e( __tr('Remove __searchTerm__', ['__searchTerm__' => $searchTerm]) )  ?>">&times;
								</a>
			              </div>
		              </div><br>
		                
		            @else
						<!--  page title set in browser tab  -->
		                @section('page-title') <?= __tr('Products') ?> @endsection
						<!--  / page title set in browser tab  -->
				        <?= __tr('Products') ?>
				        
		            @endif
		        </h3>
		        <!--  /main heading  -->

		    </div>
		@else 
			<!--  To show brand related heading like name, logo  -->
		  	<div class="lw-section-heading-block">
		        <!--  main heading  -->
		        <h3 class="lw-section-heading">
		            <?= $brand->name ?>
					<!--  page title set in browser tab  -->
		            @section('page-title') 
			        	<?= $brand->name ?>
			        @endsection
			        <!--  / page title set in browser tab  -->
		        </h3>
		        <!--  /main heading  -->
			</div>

			<!--  To show brand logo  -->
		    @if(!empty($brand->logo))
				<div class="col-lg-12 text-center">
					<div class="lw-brand-logo">
						<img class="lw-thumb-logo" src="<?=  getBrandLogoURL($brand->_id, $brand->logo)  ?>">
					</div>
				</div>
			@endif
			<!-- / To show brand logo  -->

			<div class="lw-section-heading-block">
		        <!--  main heading  -->
		        <h3 class="lw-section-heading">
		            <?=  __tr('Products')  ?>
		        </h3>
		        <!--  /main heading  -->
			</div>
		    <!-- /To show brand related heading like name, logo  -->
		@endif
		<!-- / To show the heading of product  -->
	</div>

	<div class="clearfix lw-filter-row">
		<!--  brand section  -->
		@if(!empty($brands))
	 		<?=  __tr('Showing Products for ')  ?>
		 	@foreach($brands as $brand)
				<div class="btn-group m-1" role="group">
					<!--  To show the brand name  -->
					<a  href="<?=  route('product.related.by.brand', [$brand->brandID, slugIt($brand->brandName)])  ?>" 
						class="lw-btn btn btn-light btn-sm" title="<?=  $brand->brandName  ?>">
						  <?=  $brand->brandName  ?>
					</a>
					<!-- / To show the brand name  -->
                    @if(!isActiveRoute('products_by_category') && !isActiveRoute('product.search') && !isActiveRoute('product.related.by.brand'))
					<!--  To show Remove brand filter btn  -->
					<a  href="<?= sortOrderURL(null, ['sbid' => $brand->brandID]) ?>" 
						class="lw-btn btn btn-light btn-sm" title="<?=  __tr('Remove ')  ?><?=  $brand->brandName  ?>">&times;
					</a>
					<!-- / To show Remove brand filter btn  -->
                    @endif
				</div>
			@endforeach
		@endif
		<!--  /brand section  -->
		
		<!--  filter section  -->
		@if(!empty($showFilterPrice))
			<?=  __tr(' In price between ')  ?> 
			<div class="btn-group" role="group">
				
                @if(isActiveRoute('products_by_category') || isActiveRoute('product.search') || isActiveRoute('product.related.by.brand'))
                    <a href="" class="btn btn-light btn-sm lw-btn" title="<?=  __tr('Min and Max Price')  ?>"><strong><?= $showFilterPrice ?></strong>
                    </a>
                @else
				    <a href="" class="btn btn-light btn-sm lw-btn" ng-click="productsCtrl.filterDailogProduct(productsCtrl.filterUrl)" title="<?=  __tr('Min and Max Price')  ?>">
					<strong><?= $showFilterPrice ?></strong>
				    </a>

                    <!--  To show Remove brand filter btn  -->
                    <a  href="<?= e(removePriceFilter()) ?>" 
                        class="btn btn-light btn-sm lw-btn" title="<?=  __tr('Remove ')  ?>">&times;
                    </a>
                    <!-- / To show Remove brand filter btn  -->
                @endif
			</div>
		@endif
		<!--  / filter section  -->

		<!--  Clear all Filters  -->
		@if(!empty($brands) or !empty($showFilterPrice))
            <div class="btn-group" role="group">
			@if(empty($searchTerm))
				<a href="<?= Request::url() ?>" class="btn btn-danger btn-sm lw-btn" title="<?=  __tr('Clear all Filters')  ?>"><?=  __tr('Clear all Filters')  ?></a>
			@else
				<a href="<?= e(Request::url().'?search_term='.$searchTerm) ?>" class="btn btn-danger btn-sm lw-btn" title="<?=  __tr('Clear all Filters')  ?>"><?=  __tr('Clear all Filters')  ?></a>
			@endif
            </div>
			<hr>
		@endif
		<!--  / Clear all Filters  -->

        @if(!empty($products))
		<!--  To show Product filter dialog click & sort by product drop-down  -->
		<div class="row mb-4">
            <div class="col-lg-12">
			<!--  /filter section  -->
                <ul class="nav nav-pills lw-custom-nav-pills float-right" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link @if(!empty($sortBy) && $sortBy == 'name') active @endif" href="<?=  sortOrderUrl('name') ?>">
                            @if(!empty($sortBy) && $sortBy == 'name')
                               <?=  ((!empty($sortOrder)) and $sortOrder == 'desc') ? '<i class="fa fa-sort-amount-desc"></i>' : '<i class="fa fa-sort-amount-asc"></i>'  ?>
                            @endif
                            <?=   __tr('Sort By Name')  ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(!empty($sortBy) && $sortBy == 'price') active  @endif" href="<?=  sortOrderUrl('price')  ?>">
                            @if(!empty($sortBy) && $sortBy == 'price')
                               <?=  ((!empty($sortOrder)) and $sortOrder == 'desc') ? '<i class="fa fa-sort-amount-desc"></i>' : '<i class="fa fa-sort-amount-asc"></i>'  ?>
                            @endif
                            <?=   __tr('Sort By Price')  ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(!empty($sortBy) && $sortBy == 'created_at') active  @endif" href="<?=  sortOrderUrl('created_at')  ?>">
                            @if(!empty($sortBy) && $sortBy == 'created_at')
                               <?=  ((!empty($sortOrder)) and $sortOrder == 'desc') ? '<i class="fa fa-sort-amount-desc"></i>' : '<i class="fa fa-sort-amount-asc"></i>'  ?>
                            @endif
                            <?=   __tr('Sort By Created Date')  ?>
                        </a>
                    </li>
                    @if(!isActiveRoute('products_by_category') && !isActiveRoute('product.search') && !isActiveRoute('product.related.by.brand'))

                    <li class="nav-item">
                    	<a class="nav-link  lw-filter-border-left" href ng-click="productsCtrl.filterDailogProduct(productsCtrl.filterUrl)" title="<?= __tr('Filter') ?>"> <i class="fa fa-filter"></i>
                    		@if(!empty($brands) || !empty($showFilterPrice))
                    		<?= __tr('Filtered') ?>
                    		@else
                    		<?= __tr('Filter') ?>
                    		@endif
                    	</a>
                    </li>
                    @endif
                </ul>
            </div>
		</div>
		<!-- / To show Product ..... drop-down  -->
            @endif
	</div>
	<!--  To show product image & quick view & display product details btn  -->
	<div class="lw-main-container">

		@if(!empty($products))
			<div class="lw-products-container">
                <div class="lw-gutter-sizer"></div>
				@foreach($products as $product)
					<div class="text-center lw-product-box lw-fade-out lw-nondescript-content">
                            <!--  Show featured product ribbon  -->
                             @if(!$featuredProductsRoute and $product['featured'] == 1)
                             <div class="ribbon lw-zero-opacity"></div>
                                <div class="lw-ribbon-wrapper-green lw-zero-opacity" title="<?= __tr('Featured Product') ?>"><div class="lw-ribbon-green"><?= __tr('Featured') ?></div></div>
                             @endif
                            <!--  /Show featured product ribbon  -->
                            <!--  To show product image  -->
                            <!--  If the product is out of stock to show out of stock  -->
                                @if($product['out_of_stock'] == 1)
                                    <span class="lw-out-of-stock-label"><i class="fa fa-warning"></i>  <?= __tr('Out of stock') ?></span>
                                @elseif($product['out_of_stock'] == 2)
                                    <span class="lw-out-of-stock-label"><i class="fa fa-warning"></i>  <?= __tr('Coming Soon') ?></span>
                                @elseif($product['out_of_stock'] == 3)

                                	@if(isset($product['launchingDate']))
                                    	<span class="lw-out-of-stock-label"><?= __tr('Launching On : __launchingDate__', [
	                                        '__launchingDate__' => $product['launchingDate']
	                                    ])  ?></span>
                                    @endif
                                @endif
                                <!-- / If the .... stock  -->
                                @if(getStoreSettings('enable_wishlist')) 
                                    <div class="float-left">
                                        <div ng-init="productsCtrl.getProductWislistData('<?= $product['isAddedInWishlist'] ?>', '<?= $product['id'] ?>')" class="lw-remove-wishlist-<?= $product['id'] ?>">
                                            <a class="btn btn-link lw-add-to-wishlist-btn" title="<?= __tr('Remove from wishlist.') ?>" ng-click="productsCtrl.removeFromWishlist(<?= $product['id'] ?>)"><i class="fa fa-heart"></i></a>
                                        </div>
                                       
                                        <div ng-init="productsCtrl.getProductWislistData('<?= $product['isAddedInWishlist'] ?>', '<?= $product['id'] ?>')" class="lw-add-wishlist-<?= $product['id'] ?>">
                                            <a class="btn btn-link lw-add-to-wishlist-btn" href title="<?= __tr('Add to wishlist.') ?>" ng-click="productsCtrl.addToWishlist(<?= $product['id'] ?>)" class=""><i class="fa fa-heart-o"></i></a>
                                        </div>
                                       
                                    </div>
                                    @endif
                                <!--  Thumbnail  -->
                                <div class="lw-product-thumbnail">
                                    <a class="lw-prevent-default-action" title="[[ productsCtrl.unescapeProductName('<?= urlencode($product['productName']) ?>', 1) ]]" href="<?=  productsDetailsRoute($product['id'], $product['slugName'], (!empty($category->id))? $category->id:'') ?><?=  (!empty($pageType))? $pageType:''   ?>" ng-click="productsCtrl.showDetailsDialog($event, '<?=  $product['id']  ?>', '<?=  (!empty($pageType))?$pageType:''  ?>')">
                                        <img class="product-item-thumb-image lazy" data-src="<?=  $product['thumbnailURL']  ?>" alt="" >

                                        <img class="lw-product-item-thumb-loader-image" src="<?= asset('dist/imgs/ajax-loader.gif') ?>" alt="" >
                            
                                    </a>
                                </div>
                                <!-- / Thumbnail  -->
                            <!-- / To show product image  -->

                            <div class="lw-product-meta">
                                <!--  To show product name  -->
                                <a href="<?=  productsDetailsRoute($product['id'], $product['slugName'], (!empty($category->id))? $category->id:'') ?><?=  (!empty($pageType))? $pageType:''   ?>" title="[[ productsCtrl.unescapeProductName('<?= urlencode($product['productName']) ?>', 1) ]]"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $product['id'], 'name', $product['name']) ?></h6></a>
                                <!-- / To show product name  -->

                                <!-- show product rating avg & total votes -->
                                <div id="lw-product-star-rating-<?= $product['id'] ?>">
                                    @if(isset($product['productRating']) && isset($product['productRating']['itemRating']))     
                                    @if($product['productRating']['itemRating'] > 0)   
                                    <div id="lw-product-rating-<?= $product['id'] ?>">
                                        <span class="lw-product-rating-avg">
                                        <?= $product['productRating']['formatItemRating'] ?>           
                                        </span>
                                        <span class="text-muted">
                                            <?= $product['productRating']['itemRating'] ?> <small>
                                                (<?= $product['productRating']['totalVote']?>) 
                                            </small>
                                        </span>      
                                    </div>
                                    @endif
                                    @endif
                                </div>
                               
                            <!-- / show product rating avg & total votes -->

                            <!--  To show price & options  -->
                            <h4 class="lw-product-price">

                                <?= $product['formate_price'] ?> 
                                @if($product['oldPriceExist'])
                                    <small class="lw-old-price"><strike><?= $product['formatProductOldprice'] ?></strike></small>
                                @endif
                                
                                <!--  To show when the product contain option then display + sign  -->
                                @if($product['addonPriceExists'] == true)
                                   <sup> <a href="#" title="" data-container="body" data-toggle="popover" data-trigger="hover"  data-placement="top" class="lw-product-list-popover"><span class="fa fa-plus lw-color-warn"></span></a>
                                </sup>
                                @endif
                                <!-- / To show ... + sign  -->
                            </h4>
                            <!--  /To show price & options  -->

                            <div class="btn-group lw-quick-view-btn" role="group" aria-label="">

                                <!--  To show Quick view dialog  -->
                                <a href="<?=  productsDetailsRoute($product['id'], $product['slugName'], (!empty($category->id))? $category->id:'') ?><?=  (!empty($pageType))? $pageType:''   ?>" ng-click="productsCtrl.showDetailsDialog($event,'<?=  $product['id']  ?>','<?=  (!empty($pageType))?$pageType:''  ?>')" 
                                    class="btn btn-outline-primary btn-sm lw-product-id lw-prevent-default-action" 
                                    title="<?= __tr('Quick View') ?>">
                                    <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                </a>
                                <!-- / To show Quick view dialog  -->

								<!--  Add To Cart Button -->
								@if($product['options'] == false and $product['out_of_stock'] != 1 and $product['out_of_stock'] != 2 and $product['out_of_stock'] != 3)
									<a href="" data-pid="<?= $product['id'] ?>" data-quantity="<?= $product['cartInfo']['quantity'] ?>" title="<?= __tr('Quick Add To Cart') ?>" ng-click="productsCtrl.addToCart($event, '<?= $product["id"] ?>', '<?= urlencode($product["productName"]) ?>')" class="btn btn-primary btn-sm lw-quick-cart-btn lw-product-<?= $product['id'] ?>" ng-disabled="<?= $product['productDiscount']['productPrice'] < 0 ?>"><i class="fa fa-cart-plus"></i> <sup><i class="fa fa-bolt"></i></sup>
                                	</a>
								@else
									<a href="" data-pid="<?= $product['id'] ?>" data-quantity="<?= $product['cartInfo']['quantity'] ?>" title="<?= __tr('Add To Cart') ?>" ng-click="productsCtrl.showDetailsDialog($event,'<?=  $product['id']  ?>','<?=  (!empty($pageType))?$pageType:''  ?>')" class="btn btn-primary btn-sm lw-quick-cart-btn lw-product-<?= $product['id'] ?>"><i class="fa fa-cart-plus"></i>
                                	</a>
								@endif
                                <!-- Add To Cart Button -->	
                               
                                <!-- Add To Compare Btn --> 
                                @if($product['productSpecExists'])
                                    <a href ng-click="productsCtrl.addProductCompare(<?= $product['id'] ?>)" 
                                        class="btn btn-outline-secondary btn-sm lw-product-id lw-prevent-default-action" 
                                        title="<?= __tr('Add to Compare') ?>">
                                        <i class="fa fa-bar-chart"></i>
                                    </a>
                                @endif
                                <!-- Add To Compare Btn --> 
                            </div>
                            </div>

                        </div>

				@endforeach
			</div>

			<!--  when load content display loder & msg  -->
			
			<!--  loader  -->
	        <div ng-if="productsCtrl.itemLoadType == 1 && productsCtrl.remainingItems && productsCtrl.hasMorePages" class="lw-loader-position text-right">
	            <div class="loader"></div>
	        </div>
	        <!-- / loader  -->

	        <!--  end products msg  -->
	        <div ng-if="productsCtrl.itemLoadType != 3 && !productsCtrl.remainingItems && !productsCtrl.hasMorePages" class="text-center alert alert-info">
	            <?=  __tr("Looks like you've reached the end.")  ?>
	        </div>
	        <!-- / end products msg  -->
	        
	    	<!-- / when load content display loader & msg   -->

	    	<!-- show this button when item load on button  -->
            <div ng-if="productsCtrl.itemLoadType == 2 && productsCtrl.remainingItems && productsCtrl.hasMorePages">

                <button type="button" class="lw-load-more-btn lw-btn btn btn-light btn-lg btn-block" style="background-color: #<?= getStoreSettings('logo_background_color') ?>;" ng-click="productsCtrl.loadProductsOnBtnClick()">
                   
                    <span ng-show="productsCtrl.remainingItems> productsCtrl.paginationData.perPage"><?=  __tr('Load __item__ more items out of __remainingCount__.', ['__item__' => '[[ productsCtrl.paginationData.formattedPerPage ]]', '__remainingCount__' => '[[ productsCtrl.formattedRemainingItem ]]']) ?></span>

                    <span ng-show="productsCtrl.remainingItems <= productsCtrl.paginationData.perPage"><?=  __tr('Load __remainingCount__ more items.', ['__remainingCount__' => '[[ productsCtrl.formattedRemainingItem ]]']) ?></span>

                </button>

            </div><br>
		    <!-- / show this button when item load on button  -->

			<!-- show this button when item load on button  -->
			<div class="lw-pagination-container lw-pagination-product-center"></div>
		    <!-- / show this button when item load on button -->
		@else
			<!--  when the product not available then display msg  -->
			<div>

				<div class="alert alert-info">

					<?=  __tr('No products available here.') ?>

				</div>

			</div>
        	<!--  / when the ... msg  -->
        @endif

		
	</div>
    <!-- / To show product image ...  btn  -->

	<!--  render this script when scroll page  -->
	<script type="text/_template" id="productListItemTemplate">
	   <!-- opacity:0 given to remove flicker issue -->
       <div class="text-center lw-product-box lw-fade-out" style="opacity:0">
        <!--  Show featured product ribbon  -->
         @if(!$featuredProductsRoute)
          <% if (__tData.featured == 1) { %> 
            <div class="lw-ribbon-wrapper-green lw-zero-opacity" title="<?= __tr('Featured Product') ?>"><div class="lw-ribbon-green"><?= __tr('Featured') ?>
                </div>
            </div>
        <% } %>
         @endif
        <!--  /Show featured product ribbon  -->

        <!--  If the product is out of stock to show out of stock  -->
        <% if (__tData.out_of_stock === 1) { %> 
            <span class="lw-out-of-stock-label"><i class="fa fa-warning"></i> <?= __tr('Out of stock') ?></span>
        <% } else if(__tData.out_of_stock === 2) { %> 
            <span class="lw-out-of-stock-label"><i class="fa fa-warning"></i> <?= __tr('Coming Soon') ?></span>
        <% } else if(__tData.out_of_stock === 3)  { %> 
            <span class="lw-out-of-stock-label"><i class="fa fa-warning"></i> <?= __tr('Launching On') ?></span>
        <% } %>
        <!-- / If the .... stock  -->   

        <div class="lw-product-thumbnail">
            <!--  Product Wishlist  -->
            <div class="float-left">    
                <div class="lw-remove-wishlist-<%= __tData.id %>">
                    <a class="lw-add-to-wishlist-btn" title="<?= __tr('Remove from wishlist.') ?>" ng-click="productsCtrl.removeFromWishlist(<%= __tData.id %>)"><i class="fa fa-heart lw-red-color"></i></a>
                </div>
               
                <div class="lw-add-wishlist-<%= __tData.id %>">
                    <a href title="<?= __tr('Add to wishlist.') ?>" ng-click="productsCtrl.addToWishlist(<%= __tData.id %>)" class="lw-add-to-wishlist-btn"><i class="fa fa-heart-o"></i></a>
                </div>
            </div>
            <!--  Product Wishlist  -->
                                    
        <!--  Thumbnail  -->
            <a class="lw-prevent-default-action" title="[[productsCtrl.unescapeProductName('<%- _.escape(__tData.productName) %>', 2)]]" href="<%= __tData.detailURL %>?page_type=products" ng-click="productsCtrl.showDetailsDialog($event,'<%= __tData.id %>','<?=  (!empty($pageType))?$pageType:''  ?>')"><img class="product-item-thumb-image lazy lw-product-img-thumb-style" ng-src="<%= __tData.thumbnailURL %>" alt="" data-productID="<%= __tData.id %>" >
            </a>
        <!-- /Thumbnail  -->
        </div>
        <div class="lw-product-meta">
        <!--  To show product name  -->
            <a href="<%= __tData.detailURL %>" 
                title="[[productsCtrl.unescapeProductName('<%- _.escape(__tData.productName) %>', 2)]]"><h6 class="lw-product-name"><%= __tData.translatedProductName %></h6></a>
        <!--  / To show product name  -->

        <!-- show product rating avg & total votes -->
        <div id="lw-product-star-rating-<%= __tData.id %>">
            <% if(__tData.productRating && __tData.productRating.itemRating) { %>
                <% if(__tData.productRating.itemRating > 0) { %>
                <div id="lw-product-rating-<%= __tData.id %>">
                    <span class="lw-product-rating-avg">
                        <%= __tData.productRating.formatItemRating %>
                    </span>
                    <span class="text-muted">
                        <%= __tData.productRating.itemRating %>
                        <small>(<%= __tData.productRating.totalVote %>)</small>
                    </span>
                </div>
                <% } %>
            <% } %>
        </div>
        <!-- / show product rating avg & total votes -->

        <!--  To show price & options  -->
        <h4 class="lw-product-price">

            <%= __tData.formate_price %>
            <% if(__tData.oldPriceExist) { %>
                <small class="lw-old-price"><strike><%= __tData.formatProductOldprice %></strike></small>
            <% } %>

            <!--  To show when the product contain option then display + sign  -->
	        <% if(__tData.options) { %>
	            <sup> 
	           		<a href="#" title="" data-container="body" data-toggle="popover" data-trigger="hover"  data-placement="top" class="lw-product-list-popover"><span class="fa fa-plus lw-color-warn"></span></a>
	        	</sup>
	        <% } %>
	        <!-- / To show ... + sign  -->
	    </h4>
        <!--  /To show price & options  -->

        <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
            <!--  To show Quick view dialog  -->
            <a href="<%= __tData.detailURL %>" data-productID="<%= __tData.id %>" ng-click="productsCtrl.showDetailsDialog($event,'<%= __tData.id %>','<?=  (!empty($pageType))?$pageType:''  ?>')" class="btn btn-outline-primary btn-sm lw-product-id lw-prevent-default-action" title="<?= __tr('Quick View') ?>"><i class="fa fa-bolt"></i> <?= __tr('Quick View') ?></a>
            <!-- / To show Quick view dialog  -->
            
			<!--  Add To Cart Button -->
			<% if(!__tData.options && __tData.out_of_stock != 1  && __tData.out_of_stock != 2  && __tData.out_of_stock != 3) { %>
			  <a href="" data-pid="<%= __tData.id %>" data-quantity="<%= __tData.quantity %>" title="<?= __tr('Quick Add To Cart') ?>" ng-click="productsCtrl.addToCart($event, <%= __tData.id %>, '<%- escape(__tData.productName) %>')" class="btn btn-primary btn-sm lw-quick-cart-btn lw-product-<%= __tData.id %>" ng-disabled="<%= __tData.productDiscount.productPrice < 0 %>"><i class="fa fa-cart-plus"></i> <sup><i class="fa fa-bolt"></i></sup>
            </a>
            <% } else { %>	
            <a href="" data-pid="<%= __tData.id %>" data-quantity="<%= __tData.quantity %>" title="<?= __tr('Add To Cart') ?>" ng-click="productsCtrl.showDetailsDialog($event, '<%= __tData.id %>', '<?=  (!empty($pageType))?$pageType:''  ?>')" class="btn btn-primary btn-sm lw-quick-cart-btn lw-product-<%= __tData.id %>"><i class="fa fa-cart-plus"></i></a>
			<% } %>
            <!-- Add To Cart Button -->	
            <!-- Product Compare button -->
           
            <% if(__tData.productSpecExists) { %>
                <a href ng-click="productsCtrl.addProductCompare(<%= __tData.id %>)" 
                    class="btn btn-outline-secondary btn-sm lw-product-id lw-prevent-default-action" 
                    title="<?= __tr('Add to Compare') ?>">
                    <i class="fa fa-bar-chart"></i>
                </a>
                <!-- / Product Compare button -->
            <% } %>

        </div>
        </div>
    </div>
	</script>	
	<!--  render this script when scroll page  -->
</div>

<!-- /To show the products list   -->
@push('appScripts')
	<script  type="text/javascript">

	var $masonryInstance;

	    $(document).ready(function(){

	       $masonryInstance = $('.lw-products-container');
 
			_.defer(function () {
				
				$masonryInstance.masonry({
	                itemSelector    : '.lw-product-box',
	                percentPosition: true,
	                columnWidth: '.lw-product-box',                    
	                gutter:'.lw-gutter-sizer',
	                horizontalOrder: true,
	                visibleStyle: { transform: 'translateY(0)', opacity: 1 },
	                hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
              	});
	            
	            $('.product-item-thumb-image').Lazy({
	                
	                beforeLoad: function(element) {
	                    $('.lw-product-item-thumb-loader-image').hide();
	                },
	                afterLoad: function(element) {
	                    
                    // called after an element was successfully handled
                        $masonryInstance.masonry('layout');
                        $('.lw-ribbon-wrapper-green').removeClass('lw-zero-opacity');
                    },
	            });

		        $('.lw-product-list-popover').popover({
			    	html: true, 
					content: function() {
				          return "<?=  __tr('Addon option may affect price.')  ?>";
				        }
			    }); 

			})    
	    });
	</script>
@endpush