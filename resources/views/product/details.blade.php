<div ng-controller="ProductDetailsController as productDetailsCtrl" >
 <div class="lw-zoom-detail"></div>
    @if(!empty($product))
    @section('page-title', strip_tags($product['name']))

    @if(!__isEmpty($product['seoMeta']['keywords']))
        @section('keywordName', strip_tags($product['seoMeta']['keywords']))
    	@section('keyword', strip_tags($product['seoMeta']['keywords']))
    @endif
    @if(!__isEmpty($product['seoMeta']['description']))
    	@section('description', strip_tags($product['seoMeta']['description']))
    @endif
	
	@section('page-image', getProductImageURL($images[0]['products_id'], $images[0]['file_name']))
	@section('twitter-card-image', getProductImageURL($images[0]['products_id'], $images[0]['file_name']))
	@section('page-url', url()->current())

    <!--  /This msg display only to admin, when product is invalid  -->
     <div class="row">
    <div class="col-12">
        <h3 class="title float-left">
            <?= __transliterate('product', $product['id'], 'name', $product['name']) ?> 
            <!--  show edit button for admin  -->
            @if(canAccessManageApp())
            <small>
                <a ng-if="canAccess('manage.product.edit')" title="<?= __tr('Edit') ?>" 
                href="<?=  ngLink('manage.app','product_edit', [], [ ':productID' => $product['id']])  ?>" 
                class="btn btn-light btn-sm lw-btn"> 
            <i class="fa fa-pencil-square-o"></i> <?=  __tr("Edit")  ?> 
            </a>
            </small>
            @endif
            <div class="lw-produt-id">
                <?= __tr('Product ID') ?>: <?= $product['custom_product_id'] ?>
        </div>
        </h3>
        
        <div class="float-right">
            <!-- Enable wishlist field start -->
            @if(getStoreSettings('enable_wishlist') and canAccess('product.wishlist.add_process'))
            <span class="pr-1" ng-if="productDetailsCtrl.isAddedInWishlist">
            	<button ng-disabled="productDetailsCtrl.inActiveCategory" type="button" title="<?= __tr('Remove from wishlist.') ?>" ng-click="productDetailsCtrl.removeFromWishlist()" class="btn btn-light lw-btn"><i class="fa fa-heart lw-add-to-wishlist-btn"></i></button>
            </span>

            <span class="pr-1" ng-if="!productDetailsCtrl.isAddedInWishlist">
				<button ng-disabled="productDetailsCtrl.inActiveCategory" type="button" title="<?= __tr('Add to wishlist.') ?>" ng-click="productDetailsCtrl.addToWishlist()" class="btn btn-light lw-btn"><i class="fa fa-heart-o lw-add-to-wishlist-btn"></i></button>
            </span>
            @endif

            <div class="btn-group float-right" role="group" aria-label="Social sharing">
	            <!-- Enable wishlist field stop -->
	            <!-- Share option field start -->
	            @if(getStoreSettings('facebook'))    
	            <!-- Facebook -->        
	            <a ng-disabled="productDetailsCtrl.inActiveCategory" href="https://www.facebook.com/sharer.php?u={!! route('product.details', ['id'  => $product['id'], 'name' => str_slug($product['name'])]) !!}" target="_blank" title="<?= __tr( 'Share Item on Facebook' ) ?>" class="btn btn-light lw-btn lw-product-share-btn">
	            <i class="fa fa-facebook fa-lg lw-facebook-color" aria-hidden="true"></i>
	            </a>
	            <!-- /Facebook -->
	            @endif
	            @if(getStoreSettings('twitter'))
	            <!-- Twitter -->
	            <a ng-disabled="productDetailsCtrl.inActiveCategory" href="https://twitter.com/share?url={!! route('product.details', ['id'  => $product['id'], 'name' => str_slug($product['name'])]) !!}&amp;text={!! $product['name'] !!}" target="_blank" title="<?= __tr( 'Share this Item on Twitter' ) ?>" class="btn btn-light lw-btn lw-product-share-btn">
	            <i class="fa fa-twitter fa-lg lw-twitter-color" aria-hidden="true"></i></a>
	            <!-- /Twitter -->
	            @endif

                @if(getStoreSettings('enable_whatsapp'))
                <!-- Whats Up -->
                <a href="https://wa.me?text={!! urlencode(route('product.details', ['id'  => $product['id'], 'name' => str_slug($product['name'])])) !!}" target="_blank" data-action="share/whatsapp/share" title="<?= __tr( 'Share this Item on WhatsApp' ) ?>" class="btn btn-light lw-btn lw-product-share-btn">
                <i class="fa fa-whatsapp fa-lg lw-whats-app-color" aria-hidden="true"></i></a>
                <!-- /Whats Up -->
                @endif
	            <!-- Share option field stop -->
	        </div> 
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-md-6 col-12">
        <div class="col-12">
        <!--  / get categories keywords for metadata  -->
            @if (!__isEmpty($product['brand']))
            <div class="lw-brand-logo">
                <a title="<?= __tr('View __brandName__ Brand Products', ['__brandName__' => $product['brand']['name']]) ?>" href="<?=  route('product.related.by.brand', [$product['brand']['id'], slugIt($product['brand']['name'])])  ?>" class=""><img class="lw-brand-thumb" src="<?= $product['brand']['logoImageURL'] ?>" alt=""></a>
            </div>
            @endif
            <!-- Brand Information -->
        </div>

        <!-- Place somewhere in the <body> of your page -->
        @if (!empty($images))
            <div class="lw-main-slider-container">
                <div class="lw-details-page-main-image col-12 lw-main-slider-preview">

                    <a href ng-click="productDetailsCtrl.showImagesInColorBox()">
                        <img ng-if="!productDetailsCtrl.previewImageUrl" data-zoom="<?= getProductZoomImageURL($images[0]['products_id'], $images[0]['file_name']) ?>" ng-src="<?= getProductImageURL($images[0]['products_id'], $images[0]['file_name']) ?>" class="img-responsive lw-quick-view-thumbnail lw-zoom-image" alt="">
                    </a>

                    <a href ng-click="productDetailsCtrl.showImagesInColorBox()">
                        <img ng-if="productDetailsCtrl.previewImageUrl" ng-src="[[ productDetailsCtrl.previewImageUrl ]]" data-zoom="[[ productDetailsCtrl.previewImageUrl ]]" class="img-responsive lw-quick-view-thumbnail lw-zoom-image" alt="">
                    </a>
                </div>
                <div class="lw-product-details-thumbnail-list col-12 lw-main-slider-row">
                @foreach ($images as $imageKey => $image)
                    
                    <a href="<?= getProductZoomImageURL($image['products_id'], $image['file_name']) ?>" rel="lw-product-images" ng-click="productDetailsCtrl.changeImage('<?= getProductZoomImageURL($image['products_id'], $image['file_name']) ?>', $event, '<?= $imageKey ?>')"><img src="<?= getProductImageURL($image['products_id'], $image['file_name']) ?>"></a>

                @endforeach
                </div>
            </div>
        @endif
        <div class="lw-product-details col-12">
            <h5 class="lw-sub-title">
                <?=  __tr('Product Description')  ?>
            </h5>
            <p><?=  $product['description']  ?> </p>
            <!--  set description for metadata  -->
                @section('description', str_limit(strip_tags($product['description'])), 20)
            <!--  set description for metadata  -->

            <!--  youtubeVideoCode  -->
            @if (!empty($youtubeVideoCode))   
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" allowfullscreen src="<?= e( getYoutubeUrl($youtubeVideoCode) ) ?>"></iframe>
                </div>
            @endif
            <!--  / youtubeVideoCode  -->

            <!--  specification  -->
            @if (!empty($specifications))
            <h5 class="lw-sub-title">
                <?=  __tr('Product Specifications')  ?>
            </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            @foreach($specifications as $specData)
                                <tr>
                                    <td><?= $specData['name'] ?></td>
                                    <td><?= $specData['value'] ?></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <!--  /specification  -->

            <!--  Faqs  -->
            <h5 class="lw-sub-title"><?=  __tr('Question & Answers')  ?></h5>
            <div id="accordion">
                @if (!empty($productQuestionData))
                <ol>
                    @foreach($productQuestionData as $faq)
                        <li>
                            <a class="btn btn-link collapsed text-primary" href role="button" data-toggle="collapse" data-target="#collapse-<?= $faq['id'] ?>" aria-expanded="false" aria-controls="collapse-<?= $faq['id'] ?>">
                                <strong><?= $faq['question'] ?></strong>
                            </a>
                            <div id="collapse-<?= $faq['id'] ?>" class="collapse pl-3" data-parent="#accordion">
                                <p><?= $faq['answer'] ?></p>
                            </div>
                        </li>
                    @endforeach
                </ol>
                @if($productQuestionCount > 5)
                    <a href="<?= $product['faqDetailUrl'] ?>"><?= __tr('Load More') ?></a>
                @endif
                @endif
            </div>    
            @if (empty($productQuestionData)) 
                <p><?= __tr('There are no FAQs.') ?></p>
            @endif
            <!--  /Faqs  -->

        </div>
    </div>
    <div class="col-md-6 col-12">
        <div>
            @if(!empty($categories))
            <span><?= __tr('Categories:') ?></span>
            @foreach ($categories as $category)
                <a class="btn btn-light btn-sm" title="<?= $category['name'] ?>" href="<?= e( $category['categoryUrl'] ) ?>"><?= $category['name'] ?></a>
                @endforeach
                <!--  get categories keywords for metadata  -->
                @section('keywordDescription')
                <?= strip_tags(getKeywords($categories)) ?>
                @endsection
            @endif
            <div class="float-right">
                <!--  Add to compare  -->
                <div ng-if="productDetailsCtrl.productSpecExists && !productDetailsCtrl.productDetails.addedInCompareList">
                    <button
                        ng-disabled="productDetailsCtrl.inActiveCategory"
                        ng-click="productDetailsCtrl.addProductCompare(<?= $product['product_id'] ?>)"
                        title="<?= __tr('Add to Compare') ?>" 
                        class="lw-btn btn btn-light btn-sm">
                        <i class="fa fa-plus"></i> <?=  __tr('Add to Compare')  ?>
                    </button>
                </div>
                <!-- /Add to compare -->
                
                <!--  Remove from compare  -->
                <div ng-if="productDetailsCtrl.productSpecExists && productDetailsCtrl.productDetails.addedInCompareList">
                    <button
                        ng-disabled="productDetailsCtrl.inActiveCategory"
                        ng-click="productDetailsCtrl.removeProductCompare(<?= $product['product_id'] ?>)"
                        title="<?= __tr('Remove from Compare') ?>" 
                        class="lw-btn btn btn-light btn-sm">
                        <i class="fa fa-remove"></i> <?=  __tr('Remove from Compare')  ?>
                    </button>
                </div>
                <!-- /Remove from compare -->
            </div>
        </div>
        <hr>

        @if(isAdmin()) @if(__isEmpty($isActiveCategory))
        <div class="alert alert-warning" role="alert">
            <?=  __tr("<strong>Category</strong> of this product is inactive therefore product is not available publicly.")  ?>
        </div>
        @endif @endif

        <!-- spinner -->
        <div class="d-flex justify-content-center" ng-show="!productDetailsCtrl.pageStatus">
            <div class="spinner-border" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
        <!-- / spinner -->

        <div ng-class="{ 'dimmer-content' : productDetailsCtrl.pageStatus == false}">
        
            <!--  Form for add to cart section -->
            <form class="lw-form lw-ng-form lw-add-to-cart-form" name="productDetailsCtrl.[[ productDetailsCtrl.ngFormName ]]" novalidate>
                
                <!--  Include add to cart functionality form  -->
                <div ng-show="productDetailsCtrl.pageStatus">
                
                    <!--   Show the product options -->
                    <div ng-repeat="option in productDetailsCtrl.productDetails.option track by $index" ng-switch="option.optionValueExist">

                        <span ng-switch-when="true">

                            <!-- Drop down Selection Option -->
                            <lw-form-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 1 || [[option.type]] == '' ">

                                <select 
                                    ng-init="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name] = option.option_values[0]" 
                                    ng-model="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name]"
                                    class="form-control" 
                                    name="options[option.name]" 
                                    ng-options='(value.addon_price != 0 ? (value.name+" (+"+value.addon_price_format+")") : value.name) for value in option.option_values' ng-change="productDetailsCtrl.updateCartRow(productDetailsCtrl.productDetails.id, true)"
                                    ></select>
                            </lw-form-field>
                            <!-- Drop down Selection Option -->

                            <!-- Image Selection Option -->
                            <lw-form-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 2">

                                <div class="btn-group-toggle" data-toggle="buttons">

                                    <span class="btn mb-4 lw-img-radio-btn lw-product-detail-img-option" ng-class="{ 'active' : value.name == option.option_values[0].name}"  ng-repeat="value in option.option_values track by $index">

                                        <input type="radio" 
                                            name="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]" 
                                            ng-model="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name]" 
                                            ng-init="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name] = option.option_values[0]" 
                                            ng-change="productDetailsCtrl.updateCartRow(productDetailsCtrl.productDetails.id, true)"
                                            ng-value="value"
                                         /> 

                                        <div class="lw-image-option">
                                            <span class="image-name lw-image-thumbnail">
                                            <img ng-src=[[value.thumbnailURL]]>
                                        </span>
                                        <small class="lw-image-option-text">
                                            [[(value.addon_price != 0 ? (value.name+" (+"+value.addon_price_format+")") : value.name)]]
                                        </small>
                                        </div>
                                    </span>

                                </div>
                            </lw-form-field>
                            <!-- Image Selection Option -->

                            <!-- Radio Selection Option -->
                            <lw-form-radio-field field-for="options[option.name]" label="[[option.optionName]] : " ng-if="[[option.type]] == 3">
                                <span>[[option.optionName]] :</span>
                                <br>

                                <span ng-repeat="value in option.option_values track by $index">
                               
                                    <div class="custom-control custom-radio radio-inline">
                                        <input type="radio" id="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]" 
                                        class="custom-control-input" 
                                        ng-init="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name] = option.option_values[0]"
                                        style="margin: 10px;"
                                        name="lw_[[ value.product_option_labels_id ]]_[[ value.id ]]"
                                        ng-model="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name]"
                                        ng-change="productDetailsCtrl.updateCartRow(productDetailsCtrl.productDetails.id, true)"
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
                    <!--  / show the product options -->
                    <!--  Price  -->
                    <div ng-if="productDetailsCtrl.productDiscount.productPrice > 0">
                        <h3 class="lw-effective-price">
                            <span ng-if="productDetailsCtrl.productDiscount.isDiscountExist">
                            <small title="<?= __tr( 'Price based on options selections' ) ?>"></small>
                            <span ng-bind="productDetailsCtrl.productDiscount.formattedProductPrice"></span> 
                            <small> 
                            <a lw-popup data-message="[[productDetailsCtrl.discountDetailsHtml]]" role="button" data-toggle="popover" data-trigger="hover" data-placement="top" data-trigger="focus"><del class="lw-price lw-discount-price" ng-bind="productDetailsCtrl.productDetails.basePriceWithAddonPrice"></del></a>
                            </small>
                            </span>
                            <span ng-if="!productDetailsCtrl.productDiscount.isDiscountExist">
                            <small title="<?= __tr( 'Price based on options selections' ) ?>"></small>
                            <span ng-bind="productDetailsCtrl.productDetails.basePriceWithAddonPrice"></span> 
                            </span>
                            <!--  To show old price  -->
                            <span ng-if="productDetailsCtrl.productDetails.old_price">
                            <small>
                                <del class="lw-price lw-old-price" ng-bind="productDetailsCtrl.productDetails.oldPrice"></del>
                            </small>
                            </span>
                            <!--  To show old price   -->
                        </h3>
                        <div ng-if="productDetailsCtrl.optionLength" ng-switch="productDetailsCtrl.optionLength" class="w-price-details">
                            <?= __tr('Price details: ') ?>
                            <span ng-bind="productDetailsCtrl.productDetails.priceDetails.base_price"></span>
                            <!--  To show old price  -->
                            <span ng-if="productDetailsCtrl.productDetails.priceDetails.option"> + </span>
                            <span ng-repeat="productOption in productDetailsCtrl.productDetails.priceDetails.option" ng-show="productOption.addon_price >= 0">[[ productOption.translatedOptionName ]] <em ng-bind="productOption.name"></em> [[productOption.addon_price_format]] 
                            	<span  ng-if="!$last && productOption.addon_price >= 0"> + </span>
                             	<span  ng-if="!$last && productOption.addon_price < 0"> - </span>
                         	</span>
                        </div>
                    </div>
                    <!--  /Price  -->
                    <!--  show quantity field if product available else show out of stock   -->
                    <div ng-if="productDetailsCtrl.productDiscount.productPrice > 0" ng-switch="productDetailsCtrl.productDetails.out_of_stock">
                        <!--  Quantity show when the product is in stock -->
                        <div class="row" ng-if="productDetailsCtrl.productDetails.out_of_stock == 0 || productDetailsCtrl.productDetails.out_of_stock == 4">
                            <lw-form-field class="col-md-6 col-12" field-for="quantity" label="<?= __tr( 'Quantity' ) ?>">
                                <div class="input-group">
                                    <!--  decrement of qunatity btn  -->
                                    <div class="input-group-prepend">
                                        <button title="<?=  __tr('Decrement')  ?>" type="button" class="lw-btn btn btn-light btn-number lw-vxs-hidden" ng-click="productDetailsCtrl.getQtyAction(false, productDetailsCtrl.productData.quantity)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- / decrement of qunatity btn  -->
                                    <input style="text-align:center" type="number" class="lw-form-field w-50 form-control" name="quantity" ng-required="true" min="1" max="99999" ng-model="productDetailsCtrl.productData.quantity" />
                                    <!--  show add & Update cart btn  -->
                                    <div class="input-group-append" ng-switch="productDetailsCtrl.productData.isCartExist">

                                        <!--  increment of qunatity btn  -->
                                        <button title="<?=  __tr('Increment')  ?>" type="button" ng-click="productDetailsCtrl.getQtyAction(true, productDetailsCtrl.productData.quantity)" class="lw-btn btn btn-light btn-number lw-vxs-hidden">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <!-- / increment of qunatity btn  -->
                                    </div>
                                    <!--  /show add & Update cart btn  -->
                                </div>
                            </lw-form-field>
                            <div class="col-md-6 col-12 mt-4">
                                <!--  Add cart btn  -->
                                <button class="lw-btn btn btn-primary btn-lg lw-xs-dblock-btn" ng-disabled="productDetailsCtrl.inActiveCategory" type="submit" ng-click="productDetailsCtrl.addToCart()">
                                    <i class="fa fa-cart-plus"></i>
                                    <span ng-if="!productDetailsCtrl.productData.isCartExist"><?=  __tr("Add to Cart")  ?></span>
                                    <span ng-if="productDetailsCtrl.productData.isCartExist"><?=  __tr("Update Cart")  ?></span>
                                </button>
                                <!--  / Add cart btn  -->
                            </div>
                        </div>
                        <!--  /Quantity show when the product is in stock  -->
                         @if(!isAdmin())
                        <!--  show out of stock alert msg  -->
                        <div ng-switch-when="1" class="alert alert-warning">
                            <?=  __tr('Out of Stock')  ?>
                        </div>
                        <!--  / show out of stock alert box  -->
                        @endif
                    </div>

                    <div class="mt-4">
                    <!--  This msg display only to admin, when product is invalid  -->
                    @if($product['status'] == 2)
                    <div class="alert alert-warning" role="alert">
                        <?=  __tr("This product is inactive & will not display in public until you change status to active")  ?>
                    </div>
                    @endif @if($product['isBrandInValid'] == true)
                    <div class="alert alert-warning" role="alert">
                        <?=  __tr("As this product's __brandName__ brand is inactive .This product is not publicly viewable.", ['__brandName__' => $product['brand']['name'] ])  ?>
                            <a title="<?= __tr('Edit Brand') ?>" href="<?=  ngLink('manage.app','brand_edit', [], [':brandID' => $product['brand']['id']])  ?>" class="btn btn-default btn-xs">
                                <?=  __tr("Edit")  ?>
                                    <i class="fa fa-pencil-square-o"></i>
                            </a>
                    </div>
                    @endif
                    <div ng-if="productDetailsCtrl.productDetails.out_of_stock == 1" class="alert alert-warning" role="alert">
                        <?=  __tr("Out of Stock") ?>
                    </div>
                    <div ng-if="productDetailsCtrl.productDetails.out_of_stock == 2" class="alert alert-warning" role="alert">
                        <?=  __tr("Coming Soon") ?>
                    </div>
                    <div ng-if="productDetailsCtrl.productDetails.out_of_stock == 3 && productDetailsCtrl.launchingDate" class="alert alert-info" role="alert">
                        <?=  __tr('Launching On : ')  ?> 
                        <span ng-bind="productDetailsCtrl.launchingDate"></span>
                    </div>

                    <!-- / show ... else show out of stock   -->
                    <div ng-if="productDetailsCtrl.productDiscount.productPrice < 0">
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
                    </div>
                </div>
            </form>
            <!--  Form for add to cart section -->

            <!-- Notify Me Customer Field -->
            <div ng-if="productDetailsCtrl.productDetails.out_of_stock == 2">
                <lw-form-field field-for="notifyUserEmail" label="<?= __tr( 'Notify Me' ) ?>">
                    <div class="input-group mb-3">
                        <input type="text" 
                        name="notifyUserEmail" 
                        class="form-control" 
                        ng-model="productDetailsCtrl.notifyUserEmail" placeholder="Email" 
                        aria-label="Email">
                        <div class="input-group-append">
                            <button ng-click="productDetailsCtrl.notifyUser(productDetailsCtrl.notifyUserEmail)" class="btn btn-outline-secondary" type="button" value="Submit"><?= __tr( 'Notify Me' ) ?></button>
                        </div>
                    </div>
                <lw-form-field>
            </div>
            <!-- / Notify Me Customer Field -->

            <!-- Rating And Reviews -->
            <div ng-show="productDetailsCtrl.enableRating">
                <h5 class="lw-sub-title"><?=  __tr('Ratings & Reviews')  ?></h5>
                <div class="row">
                    <div class="col-12 col-sm-6  pl-0 text-center">
                    <span class="title lw-current-rating">
                        <span class="lw-user-vote-label" ng-bind="productDetailsCtrl.itemRating.rate" title="<?= __tr('Average Rating') ?>" ng-if="productDetailsCtrl.itemRating.rate"></span> <br>
                        <span ng-if="productDetailsCtrl.itemRating.rate">
                            <?= __tr('Average Rating based on __voteCount__ vote(s)', [
                                '__voteCount__' => '[[ productDetailsCtrl.itemRating.totalVotes ]]'
                            ]) ?></span>
                        <span ng-if="productDetailsCtrl.itemRating.totalVotes == 0">
                            <em><?= __tr('No rating received yet') ?></em> 
                        </span>
                    </span>
                    <select id="lw-bar-rating" name="rating" class="mt-2"  autocomplete="off">
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 ">
                    <ul class="list-group lw-rating-starts">
                            <li class="list-group-item">
                                <strong><?= __tr('5') ?></strong><i ng-repeat="n in [] | range:5" class="fa fa-star lw-color-gold" ></i>
                                <span><?= __tr('__voteCount__ vote(s)',[
                                '__voteCount__' => '[[productDetailsCtrl.itemRating.productUserRatings.fiveStarRating.countVote]]'
                                ]) ?></span>
                            </li>
                            <li class="list-group-item">
                                <strong><?= __tr('4') ?></strong><i ng-repeat="n in [] | range:4" class="fa fa-star lw-color-gold" ></i><i ng-repeat="n in [] | range:1" class="fa fa-star-o" ></i>
                                <span><?= __tr('__voteCount__ vote(s)',[
                                '__voteCount__' => '[[productDetailsCtrl.itemRating.productUserRatings.fourStarRating.countVote]]'
                                ]) ?></span>
                            </li>
                            <li class="list-group-item">
                                <strong><?= __tr('3') ?></strong><i ng-repeat="n in [] | range:3" class="fa fa-star lw-color-gold" ></i><i ng-repeat="n in [] | range:2" class="fa fa-star-o" ></i>
                                <span><?= __tr('__voteCount__ vote(s)',[
                                '__voteCount__' => '[[productDetailsCtrl.itemRating.productUserRatings.threeStarRating.countVote]]'
                                ]) ?></span>
                            </li>
                            <li class="list-group-item">
                                <strong><?= __tr('2') ?></strong><i ng-repeat="n in [] | range:2" class="fa fa-star lw-color-gold" ></i><i ng-repeat="n in [] | range:3" class="fa fa-star-o" ></i>
                                <span><?= __tr('__voteCount__ vote(s)',[
                                '__voteCount__' => '[[productDetailsCtrl.itemRating.productUserRatings.twoStarRating.countVote]]'
                                ]) ?></span>
                            </li>
                            <li class="list-group-item">
                                <strong><?= __tr('1') ?></strong><i ng-repeat="n in [] | range:1" class="fa fa-star lw-color-gold" ></i><i ng-repeat="n in [] | range:4" class="fa fa-star-o" ></i>
                                <span><?= __tr('__voteCount__ vote(s)',[
                                '__voteCount__' => '[[productDetailsCtrl.itemRating.productUserRatings.oneStarRating.countVote]]'
                                ]) ?></span>
                            </li>
                        </ul>
                </div>
                </div>

                <h5 class="lw-sub-title" ng-if="productDetailsCtrl.itemRatingAndReview.length > 0"><?=  __tr('User Reviews')  ?></h5>
                <div class="row">
                    <div class="col-12" ng-if="productDetailsCtrl.itemRatingAndReview.length > 0 && review.review.length > 0" ng-repeat="review in productDetailsCtrl.itemRatingAndReview">
                        <div class="media">
                            <div class="media-body">
                                <div class="col-12 text-right"> 
                                    <span class="badge" title="[[review.userRatingReview]]" 
                                ng-class="{
                                    'badge-danger' :  review.rating == '1',
                                    'badge-warning' :  review.rating == '2',
                                    'badge-info' :  review.rating == '3',
                                    'badge-success' :  review.rating == '5' ||  review.rating == '4',
                                }">
                                    [[ review.rating ]]  <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                                </div>
                                <p ng-bind="review.review"></p>
                                <div class="col-12 text-right"> 
                                    <small><em>[[review.humanReadableDate]] </em>
                                            by [[review.fullName]]</small> 
                                </div>
                            
                            </div>

                        </div>
                        <hr/>
                    
                    </div>
                    <div class="col-12">
                        <div ng-if="productDetailsCtrl.itemRatingAndReview.length > 0 && productDetailsCtrl.originalRatingAndReviewCount > 5">
                            <a href="<?= $product['productReviewUrl'] ?>" ng-if="productDetailsCtrl.totalReview == productDetailsCtrl.configReviewCount">
                                <?= __tr('Show All Reviews') ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <p ng-show="productDetailsCtrl.reviewCount == 0">
                    <?= __tr('There are no Reviews.') ?>
                </p>
            </div>
            <!--/Rating And Reviews  -->
        </div>
    </div>
                <!-- col.// -->
    </div>
    <div cals="conatiner">
        
        <!--  related products image slider -->
        @if (!empty($relatedProductData))
            <h5 class="lw-sub-title"><?=  __tr('Related Products')  ?></h5>
            <div class="lw-inline-products-owl owl-carousel owl-theme">
                @foreach ($relatedProductData as $relatedProduct)
                    <div class="">
                        <a href="<?=  route('product.details', [$relatedProduct['id'], $relatedProduct['slugName']])  ?>">
                            <div style="background: url('<?=  getProductImageURL($relatedProduct['id'], $relatedProduct['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                            </div>
                        </a>
                        <div>
                            <span><a  href="<?=  route('product.details', [$relatedProduct['id'], $relatedProduct['slugName']])  ?>" title="<?= __tr('View Details') ?>"><?=  $relatedProduct['name']  ?></a></span><br>
                            <span class="price-text-color"><?=  $relatedProduct['related_product_price']  ?></span>
                        </div>
                        <!-- / show product details btn  -->
                    </div>
                @endforeach
            </div>
        @endif
        <!--  /related products image slider  -->
    </div>

    <!--  / description, youtube_video_code,  related products, recent view products  -->
    @endif @push('appScripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#myTab a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            var owl = $('.lw-inline-products-owl');

            owl.owlCarousel({
                stagePadding: 0,
                loop:false,
                nav:true,
                dots:false,
                margin:10,
                checkVisible:false,
                responsive:{
                    0:{
                        items:1,
                        // nav:false
                    },
                    600:{
                        items:2,
                        // nav:false
                    },
                    1000:{
                        items:3,
                        // nav:true,
                    },
                    1200:{
                        items:4,
                        // nav:true
                    },
                    1600:{
                        items:6,
                        // nav:true
                    }
                }
            });

            owl.on('mousewheel', '.owl-stage', function (e) {
                if (e.deltaY>0) {
                    owl.trigger('next.owl');
                } else {
                    owl.trigger('prev.owl');
                }
                e.preventDefault();
            });

            //this script add for main product image slider
            $(".lw-main-product-slider").owlCarousel({
                loop: true,
                nav: true,
                slideSpeed: 300,
                autoPlay: false,
                paginationSpeed: 400,
                items: 1,
                touchDrag: true,
                mouseDrag: true
            });

            $('.lw-discount-details').popover({
                html: true
            });
        });
    </script>

    <script type="text/_template" id="discountDetailsTemplate">
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        <?= __tr('Discount Description') ?>
                    </th>
                    <th class="text-right">
                        <?= __tr('Amount') ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <% _.forEach(__tData.discountDetails, function(item) { %>
                    <tr>
                        <td>
                            <div>
                                <strong><%= item.title %></strong> (
                                <?= __tr('__discount__ Off', [
            '__discount__' => '<%= item.formattedDiscountAmt %>'
            ]) ?>)
                            </div>
                            <div>
                                <%= item.description %>
                                    (
                                    <?= __tr('Max Discount __maxDiscount__', [
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
                        <img  data-zoom="<%= __tData.firstImage.zoomURL %>" src="<%= __tData.firstImage.getProductImageURL %>" class="img-responsive lw-quick-view-thumbnail lw-zoom-image" alt="">
                    </a>

                </div>

                <div class="lw-product-details-thumbnail-list col-12 lw-main-slider-row">
                    <% counter = 0 %>                    
                    <%  _.forEach(__tData.images, function(row, index) { %>
                    
                        <a href="<%= row.zoomURL %>" rel="lw-product-images" ng-click="productDetailsCtrl.changeImage('<%= row.getProductImageURL %>', $event, '<%= counter++ %>')"><img src="<%= row.getProductImageURL %>"></a>
                    
                    <%  })  %>

                </div>

            </div>

        </div>
    
    </script>

    @endpush
</div>
