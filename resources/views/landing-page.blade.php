@section('page-image', getStoreSettings('logo_image_url'))
@section('twitter-card-image', getStoreSettings('logo_image_url'))
@section('page-url', url()->current())
@section('page-title', getStoreSettings('store_name'))
@section('description', getStoreSettings('store_name'))

@if(isset($landingPage))
<div>   
    @foreach ($landingPage as $key => $landingPageDetail )
        @if($landingPageDetail['identity'] == 'Slider' 
            and !__isEmpty($landingPageDetail['title'])
            and $landingPageDetail['isEnable'] == true)
            <div class="">
                @if(!__isEmpty(getImageSlider($landingPageDetail['title'])))
                    <?= getImageSlider($landingPageDetail['title']) ?>
                @endif
            </div>

        @elseif($landingPageDetail['identity'] == 'PageContent' && !__isEmpty($landingPageDetail['pageContent']))
            <div class="lw-landing-page-blocks">
                <?= $landingPageDetail['pageContent'] ?>
            </div>

        @elseif($landingPageDetail['identity'] == 'latestProduct' 
            and !__isEmpty($landingPageDetail['latestProduct'])
            and $landingPageDetail['isEnable'] == true)
            <div class="lw-landing-page-blocks">
                <div>
                    <h4><?=  __tr('Latest Products')  ?></h4>
                </div>

                <hr>
                <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                    @foreach($landingPageDetail['latestProduct'] as $product)
                        <div class="text-center lw-product-box">
                            <a href="<?=  route('product.details', [$product['id'], $product['slugName']])  ?>">
                                <div style="background: url('<?=  getProductImageURL($product['id'], $product['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                </div>
                            </a>
                            <div class="lw-product-meta">
                                <!--  To show product name  -->
                                <a href="<?=  route('product.details', [$product['id'], $product['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $product['id'], 'name', $product['name']) ?></h6></a>
                                <!-- / To show product name  -->

                                <!-- product rating -->
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
                                <!-- / product rating -->

                                <!--  To show price & options  -->
                                <h4 class="lw-product-price">
                                    <?=  $product['price']  ?>
                                    @if($product['oldPriceExist'])
                                        <small class="lw-old-price"><del><?= $product['formatProductOldprice'] ?></del></small>
                                    @endif
                                </h4>
                                <!--  /To show price & options  -->

                                <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                    <!--  To show Quick view dialog  -->
                                    <a href="" 
                                        class="btn btn-outline-primary lw-landing-page-detail-btn btn-sm lw-product-id" 
                                        ng-click="publicCtrl.showDetailsDialog($event,'<?=  $product['id']  ?>')" 
                                        title="<?= __tr('Quick View') ?>">
                                        <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                    </a>
                                    <!-- / To show Quick view dialog  -->

                                    <!-- Add To Compare Btn --> 
                                    @if($product['productSpecExists'])
                                        <a href ng-click="publicCtrl.addProductCompare(<?= $product['id'] ?>)" 
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
            </div>

        @elseif($landingPageDetail['identity'] == 'featuredProduct' 
            and !__isEmpty($landingPageDetail['latestFeaturedProduct'])
            and $landingPageDetail['isEnable'] == true)
            <div class="lw-landing-page-block">
                <div>
                    <h4><?=  __tr('Featured Products')  ?></h4>
                </div>
                <hr>
                <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                    @foreach ($landingPageDetail['latestFeaturedProduct'] as $productData)
                        <div class="text-center lw-product-box">
                            <a href="<?=  route('product.details', [$productData['id'], $productData['slugName']])  ?>">
                                <div style="background: url('<?=  getProductImageURL($productData['id'], $productData['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                </div>
                            </a>

                            <div class="lw-product-meta">
                                <!--  To show product name  -->
                                <a href="<?=  route('product.details', [$productData['id'], $productData['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $productData['id'], 'name', $productData['name']) ?></h6></a>
                                <!-- / To show product name  -->

                                <!-- product rating -->
                                <div id="lw-product-star-rating-<?= $productData['id'] ?>">
                                    @if(isset($productData['productRating']) && isset($productData['productRating']['itemRating']))     
                                    @if($productData['productRating']['itemRating'] > 0)   
                                    <div id="lw-product-rating-<?= $productData['id'] ?>">
                                        <span class="lw-product-rating-avg">
                                        <?= $productData['productRating']['formatItemRating'] ?>
                                        </span>
                                        <span class="text-muted">
                                            <?= $productData['productRating']['itemRating'] ?> <small>
                                                (<?= $productData['productRating']['totalVote']?>) 
                                            </small>
                                        </span>      
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <!-- / product rating -->

                                <!--  To show price & options  -->
                                <h4 class="lw-product-price">
                                    <?=  $productData['price']  ?>
                                    @if($productData['oldPriceExist'])
                                        <small class="lw-old-price"><del><?= $productData['formatProductOldprice'] ?></del></small>
                                    @endif
                                </h4>
                                <!--  /To show price & options  -->

                                <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                    <!--  To show Quick view dialog  -->
                                    <a href="" 
                                        class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn" 
                                        ng-click="publicCtrl.showDetailsDialog($event,'<?=  $productData['id']  ?>')" 
                                        title="<?= __tr('Quick View') ?>">
                                        <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                    </a>
                                    <!-- / To show Quick view dialog  -->

                                    <!-- Add To Compare Btn --> 
                                    @if($productData['productSpecExists'])
                                        <a href ng-click="publicCtrl.addProductCompare(<?= $productData['id'] ?>)" 
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
            </div>

        @elseif($landingPageDetail['identity'] == 'popularProduct' 
            and !__isEmpty($landingPageDetail['popularSaleProduct'])
            and $landingPageDetail['isEnable'] == true)
            <div class="lw-landing-page-block">
                <div>
                    <h4><?=  __tr('Popular Products')  ?></h4>
                </div>
                <hr>
                <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                    @foreach ($landingPageDetail['popularSaleProduct'] as $popularProduct)
                        <div class="text-center lw-product-box">
                            <a href="<?=  route('product.details', [$popularProduct['id'], $popularProduct['slugName']])  ?>">
                                <div style="background: url('<?=  getProductImageURL($popularProduct['id'], $popularProduct['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                </div>
                            </a>

                            <div class="lw-product-meta">
                                <!--  To show product name  -->
                                <a href="<?=  route('product.details', [$popularProduct['id'], $popularProduct['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $popularProduct['id'], 'name', $popularProduct['name']) ?></h6></a>
                                <!-- / To show product name  -->

                                <!-- product rating -->
                                <div id="lw-product-star-rating-<?= $popularProduct['id'] ?>">
                                    @if(isset($popularProduct['productRating']) && isset($popularProduct['productRating']['itemRating']))     
                                    @if($popularProduct['productRating']['itemRating'] > 0)   
                                    <div id="lw-product-rating-<?= $popularProduct['id'] ?>">
                                        <span class="lw-product-rating-avg">
                                            <?= $popularProduct['productRating']['formatItemRating'] ?>
                                            </span>
                                            <span class="text-muted">
                                                <?= $popularProduct['productRating']['itemRating'] ?> <small>
                                                    (<?= $popularProduct['productRating']['totalVote']?>) 
                                                </small>
                                            </span>      
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <!-- / product rating -->

                                <!--  To show price & options  -->
                                <h4 class="lw-product-price">
                                    <?=  $popularProduct['price']  ?>
                                    @if($popularProduct['oldPriceExist'])
                                        <small class="lw-old-price"><strike><?= $popularProduct['formatProductOldprice'] ?></strike></small>
                                    @endif
                                </h4>
                                <!--  /To show price & options  -->

                                <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                    <!--  To show Quick view dialog  -->
                                    <a href="" 
                                        class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn" 
                                        ng-click="publicCtrl.showDetailsDialog($event,'<?=  $popularProduct['id']  ?>')" 
                                        title="<?= __tr('Quick View') ?>">
                                        <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                    </a>
                                    <!-- / To show Quick view dialog  -->

                                    <!-- Add To Compare Btn --> 
                                    @if($popularProduct['productSpecExists'])
                                        <a href ng-click="publicCtrl.addProductCompare(<?= $popularProduct['id'] ?>)" 
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
            </div>
        @elseif($landingPageDetail['identity'] == 'bannerContent1'
                and $landingPageDetail['isEnable'] == true)
            <div class="row mt-4">
                <div class="col-lg-7">
                    <div class="card lw-banner-card text-black">
                        <img src="<?= $landingPageDetail['banner_1_section_1_image_thumb'] ?>" alt="">
                        <div class="card-img-overlay lw-banner-captions" style="background: <?= array_get($landingPageDetail, 'banner_1_section_1_background_color') ?>">
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'banner_1_section_1_heading_1_color') ?>"><?= $landingPageDetail['banner_1_section_1_heading_1'] ?></div>
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'banner_1_section_1_heading_2_color') ?>"><?= $landingPageDetail['banner_1_section_1_heading_2'] ?></div>
                            <div class="lw-banner-caption"><?= $landingPageDetail['banner_1_section_1_description'] ?></div>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-5">
                    <div class="card lw-banner-side-card text-black">
                        <img src="<?= $landingPageDetail['banner_1_section_2_image_thumb'] ?>" alt="">
                        <div class="card-img-overlay lw-banner-captions" style="background: <?= array_get($landingPageDetail, 'baner_1_section_2_background_color') ?>">
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_1_section_2_heading_1_color') ?>"><?= $landingPageDetail['baner_1_section_2_heading_1'] ?></div>
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_1_section_2_heading_2_color') ?>"><?= $landingPageDetail['baner_1_section_2_heading_2'] ?></div>
                            <div class="lw-banner-caption"><?= $landingPageDetail['baner_1_section_2_description'] ?></div>
                        </div>
                    </div>
                    <div class="card mt-2 lw-banner-side-card text-black">
                        <img src="<?= $landingPageDetail['banner_1_section_3_image_thumb'] ?>" alt="">
                        <div class="card-img-overlay lw-banner-captions" style="background: <?= array_get($landingPageDetail, 'baner_1_section_3_background_color') ?>">
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_1_section_3_heading_1_color') ?>"><?= $landingPageDetail['baner_1_section_3_heading_1'] ?></div>
                            <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_1_section_3_heading_2_color') ?>"><?= $landingPageDetail['baner_1_section_3_heading_2'] ?></div>
                            <div class="lw-banner-caption"><?= $landingPageDetail['baner_1_section_3_description'] ?></div>
                        </div>
                    </div>
                </div>           
            </div>
        @elseif($landingPageDetail['identity'] == 'bannerContent2'
                and $landingPageDetail['isEnable'] == true)
        <div class="row">
            <div class="col-lg-4">
                <div class="card lw-banner-inline-card text-black">
                    <img src="<?= $landingPageDetail['banner_2_section_1_image_thumb'] ?>" alt="">
                    <div class="card-img-overlay lw-banner-captions" style="background: <?= array_get($landingPageDetail, 'banner_2_section_1_background_color') ?>">
                        <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'banner_2_section_1_heading_1_color') ?>"><?= $landingPageDetail['banner_2_section_1_heading_1'] ?></div>
                        <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'banner_2_section_1_heading_2_color') ?>"><?= $landingPageDetail['banner_2_section_1_heading_2'] ?></div>
                        <div class="lw-banner-caption"><?= $landingPageDetail['banner_2_section_1_description'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card lw-banner-inline-card text-black">
                    <img src="<?= $landingPageDetail['banner_2_section_2_image_thumb'] ?>" alt="">
                    <div class="card-img-overlay lw-banner-captions" style="background: <?= array_get($landingPageDetail, 'baner_2_section_2_background_color') ?>">
                        <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_2_section_2_heading_1_color') ?>"><?= $landingPageDetail['baner_2_section_2_heading_1'] ?></div>
                        <div class="lw-banner-caption" style="color: #<?= array_get($landingPageDetail, 'baner_2_section_2_heading_2_color') ?>"><?= $landingPageDetail['baner_2_section_2_heading_2'] ?></div>
                        <div class="lw-banner-caption"><?= $landingPageDetail['baner_2_section_2_description'] ?></div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($landingPageDetail['identity'] == 'productTabContent'
                and $landingPageDetail['isEnable'] == true)
            <div class="row mt-4 lw-landing-page-block">
                <div class="col-lg-12">
                    <h4>
                           <?= $landingPageDetail['tab_section_title'] ?>
                        <ul class="nav nav-pills lw-landing-page-nav-pills" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="first-tab" href="#<?= slugIt($landingPageDetail['raw_tab_1_title']) ?>" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                    <?= $landingPageDetail['tab_1_title'] ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="second-tab" href="#<?= slugIt($landingPageDetail['raw_tab_2_title']) ?>" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                                    <?= $landingPageDetail['tab_2_title'] ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="third-tab" href="#<?= slugIt($landingPageDetail['raw_tab_3_title']) ?>" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                                    <?= $landingPageDetail['tab_3_title'] ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fourth-tab" href="#<?= slugIt($landingPageDetail['raw_tab_4_title']) ?>" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                                    <?= $landingPageDetail['tab_4_title'] ?>
                                </a>
                            </li>
                        </ul>
                         </h4>
                        <hr>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="<?= slugIt($landingPageDetail['raw_tab_1_title']) ?>" role="tabpanel" aria-labelledby="first-tab">
                            <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                                @foreach ($landingPageDetail['productTabsData']['tab1Data'] as $tab1)
                                    <div class="text-center lw-product-box">
                                        <a href="<?=  route('product.details', [$tab1['id'], $tab1['slugName']])  ?>">
                                            <div style="background: url('<?=  getProductImageURL($tab1['id'], $tab1['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                            </div>
                                        </a>

                                        <div class="lw-product-meta">
                                            <!--  To show product name  -->
                                            <a href="<?=  route('product.details', [$tab1['id'], $tab1['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $tab1['id'], 'name', $tab1['name']) ?></h6></a>
                                            <!-- / To show product name  -->

                                            <!-- product rating -->
                                            <div id="lw-product-star-rating-<?= $tab1['id'] ?>">
                                                @if(isset($tab1['productRating']) && isset($tab1['productRating']['itemRating']))     
                                                @if($tab1['productRating']['itemRating'] > 0)   
                                                <div id="lw-product-rating-<?= $tab1['id'] ?>">
                                                    <span class="lw-product-rating-avg">
                                                        <?= $tab1['productRating']['formatItemRating'] ?>
                                                        </span>
                                                        <span class="text-muted">
                                                            <?= $tab1['productRating']['itemRating'] ?> <small>
                                                                (<?= $tab1['productRating']['totalVote']?>) 
                                                            </small>
                                                        </span>      
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                            <!-- / product rating -->

                                            <!--  To show price & options  -->
                                            <h4 class="lw-product-price">
                                                <?=  $tab1['price']  ?>
                                                @if($tab1['oldPriceExist'])
                                                    <small class="lw-old-price"><strike><?= $tab1['formatProductOldprice'] ?></strike></small>
                                                @endif
                                            </h4>
                                            <!--  /To show price & options  -->

                                            <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                                <!--  To show Quick view dialog  -->
                                                <a href="" 
                                                    class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn"
                                                    ng-click="publicCtrl.showDetailsDialog($event,'<?=  $tab1['id']  ?>')" 
                                                    title="<?= __tr('Quick View') ?>">
                                                    <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                                </a>
                                                <!-- / To show Quick view dialog  -->

                                                <!-- Add To Compare Btn --> 
                                                @if($tab1['productSpecExists'])
                                                    <a href ng-click="publicCtrl.addProductCompare(<?= $tab1['id'] ?>)" 
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
                        </div>
                        <div class="tab-pane fade" id="<?= slugIt($landingPageDetail['raw_tab_2_title']) ?>" role="tabpanel" aria-labelledby="second-tab">
                            <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                                @foreach ($landingPageDetail['productTabsData']['tab2Data'] as $tab2)
                                    <div class="text-center lw-product-box">
                                        <a href="<?=  route('product.details', [$tab2['id'], $tab2['slugName']])  ?>">
                                            <div style="background: url('<?=  getProductImageURL($tab2['id'], $tab2['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                            </div>
                                        </a>

                                        <div class="lw-product-meta">
                                            <!--  To show product name  -->
                                            <a href="<?=  route('product.details', [$tab2['id'], $tab2['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $tab2['id'], 'name', $tab2['name']) ?></h6></a>
                                            <!-- / To show product name  -->

                                            <!-- product rating -->
                                            <div id="lw-product-star-rating-<?= $tab2['id'] ?>">
                                                @if(isset($tab2['productRating']) && isset($tab2['productRating']['itemRating']))     
                                                @if($tab2['productRating']['itemRating'] > 0)   
                                                <div id="lw-product-rating-<?= $tab2['id'] ?>">
                                                    <span class="lw-product-rating-avg">
                                                        <?= $tab2['productRating']['formatItemRating'] ?>
                                                        </span>
                                                        <span class="text-muted">
                                                            <?= $tab2['productRating']['itemRating'] ?> <small>
                                                                (<?= $tab2['productRating']['totalVote']?>) 
                                                            </small>
                                                        </span>      
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                            <!-- / product rating -->

                                            <!--  To show price & options  -->
                                            <h4 class="lw-product-price">
                                                <?=  $tab2['price']  ?>
                                                @if($tab2['oldPriceExist'])
                                                    <small class="lw-old-price"><strike><?= $tab2['formatProductOldprice'] ?></strike></small>
                                                @endif
                                            </h4>
                                            <!--  /To show price & options  -->

                                            <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                                <!--  To show Quick view dialog  -->
                                                <a href="" 
                                                    class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn"
                                                    ng-click="publicCtrl.showDetailsDialog($event,'<?=  $tab2['id']  ?>')" 
                                                    title="<?= __tr('Quick View') ?>">
                                                    <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                                </a>
                                                <!-- / To show Quick view dialog  -->

                                                <!-- Add To Compare Btn --> 
                                                @if($tab2['productSpecExists'])
                                                    <a href ng-click="publicCtrl.addProductCompare(<?= $tab2['id'] ?>)" 
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
                        </div>
                        <div class="tab-pane fade" id="<?= slugIt($landingPageDetail['raw_tab_3_title']) ?>" role="tabpanel" aria-labelledby="third-tab">
                            <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                               @foreach ($landingPageDetail['productTabsData']['tab3Data'] as $tab3)
                                    <div class="text-center lw-product-box">
                                        <a href="<?=  route('product.details', [$tab3['id'], $tab3['slugName']])  ?>">
                                            <div style="background: url('<?=  getProductImageURL($tab3['id'], $tab3['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                            </div>
                                        </a>

                                        <div class="lw-product-meta">
                                            <!--  To show product name  -->
                                            <a href="<?=  route('product.details', [$tab3['id'], $tab3['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $tab3['id'], 'name', $tab3['name']) ?></h6></a>
                                            <!-- / To show product name  -->

                                            <!-- product rating -->
                                            <div id="lw-product-star-rating-<?= $tab3['id'] ?>">
                                                @if(isset($tab3['productRating']) && isset($tab3['productRating']['itemRating']))     
                                                @if($tab3['productRating']['itemRating'] > 0)   
                                                <div id="lw-product-rating-<?= $tab3['id'] ?>">
                                                    <span class="lw-product-rating-avg">
                                                        <?= $tab3['productRating']['formatItemRating'] ?>
                                                        </span>
                                                        <span class="text-muted">
                                                            <?= $tab3['productRating']['itemRating'] ?> <small>
                                                                (<?= $tab3['productRating']['totalVote']?>) 
                                                            </small>
                                                        </span>      
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                            <!-- / product rating -->

                                            <!--  To show price & options  -->
                                            <h4 class="lw-product-price">
                                                <?=  $tab3['price']  ?>
                                                @if($tab3['oldPriceExist'])
                                                    <small class="lw-old-price"><strike><?= $tab3['formatProductOldprice'] ?></strike></small>
                                                @endif
                                            </h4>
                                            <!--  /To show price & options  -->

                                            <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                                <!--  To show Quick view dialog  -->
                                                <a href="" 
                                                    class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn"
                                                    ng-click="publicCtrl.showDetailsDialog($event,'<?=  $tab3['id']  ?>')" 
                                                    title="<?= __tr('Quick View') ?>">
                                                    <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                                </a>
                                                <!-- / To show Quick view dialog  -->

                                                <!-- Add To Compare Btn --> 
                                                @if($tab3['productSpecExists'])
                                                    <a href ng-click="publicCtrl.addProductCompare(<?= $tab3['id'] ?>)" 
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
                        </div>
                        <div class="tab-pane fade" id="<?= slugIt($landingPageDetail['raw_tab_4_title']) ?>" role="tabpanel" aria-labelledby="fourth-tab">
                            <div class="lw-landing-page-products-owl owl-carousel owl-theme">
                                @foreach ($landingPageDetail['productTabsData']['tab4Data'] as $tab4)
                                    <div class="text-center lw-product-box">
                                        <a href="<?=  route('product.details', [$tab4['id'], $tab4['slugName']])  ?>">
                                            <div style="background: url('<?=  getProductImageURL($tab4['id'], $tab4['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
                                            </div>
                                        </a>

                                        <div class="lw-product-meta">
                                            <!--  To show product name  -->
                                            <a href="<?=  route('product.details', [$tab4['id'], $tab4['slugName']])  ?>"><h6  class="h6" class="lw-product-name"><?= __transliterate('product', $tab4['id'], 'name', $tab4['name']) ?></h6></a>
                                            <!-- / To show product name  -->

                                            <!-- product rating -->
                                            <div id="lw-product-star-rating-<?= $tab4['id'] ?>">
                                                @if(isset($tab4['productRating']) && isset($tab4['productRating']['itemRating']))     
                                                @if($tab4['productRating']['itemRating'] > 0)   
                                                <div id="lw-product-rating-<?= $tab4['id'] ?>">
                                                    <span class="lw-product-rating-avg">
                                                        <?= $tab4['productRating']['formatItemRating'] ?>
                                                        </span>
                                                        <span class="text-muted">
                                                            <?= $tab4['productRating']['itemRating'] ?> <small>
                                                                (<?= $tab4['productRating']['totalVote']?>) 
                                                            </small>
                                                        </span>      
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                            <!-- / product rating -->

                                            <!--  To show price & options  -->
                                            <h4 class="lw-product-price">
                                                <?=  $tab4['price']  ?>
                                                @if($tab4['oldPriceExist'])
                                                    <small class="lw-old-price"><strike><?= $tab4['formatProductOldprice'] ?></strike></small>
                                                @endif
                                            </h4>
                                            <!--  /To show price & options  -->

                                            <div class="btn-group lw-quick-view-btn" role="group" aria-label="">
                                            <!--  To show Quick view dialog  -->
                                            <a href="" 
                                                class="btn btn-outline-primary btn-sm lw-product-id lw-landing-page-detail-btn"
                                                ng-click="publicCtrl.showDetailsDialog($event,'<?=  $tab4['id']  ?>')" 
                                                title="<?= __tr('Quick View') ?>">
                                                <i class="fa fa-bolt"></i> <?= __tr('Quick View') ?>
                                            </a>
                                            <!-- / To show Quick view dialog  -->
                                            
                                            <!-- Add To Compare Btn --> 
                                                @if($tab4['productSpecExists'])
                                                    <a href ng-click="publicCtrl.addProductCompare(<?= $tab4['id'] ?>)" 
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
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endif

@push('appScripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var owl = $('.lw-landing-page-products-owl');

            owl.owlCarousel({
                stagePadding: 0,
                loop:false,
                nav:true,
                dots:false,
                margin:10,
                checkVisible:false,
                onInitialized : function() {
                    // $('.lw-landing-page-products-owl .owl-item').show();
                },
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

        });
    </script>
@endpush
