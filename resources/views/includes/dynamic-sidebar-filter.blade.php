<div ng-controller="SidebarFilterController as SidebarFilterCtrl">
    <!--  form action  -->
    <form action="[[productsFilterCtrl.currentUrl]]" novalidate  name="filter"  method="get">
        <?php 
            $data = $menuData['sidebarFilterData'];
            $filterPrices = $data['filterPrices'];
            $productPrices = $data['productPrices'];
            $currentFilterUrl = prepareBaseUrl($data['brandsIds'], $data['specsIds'], $data['filterRating'], $data['availablity'], $data['catIds'], $filterPrices);
        ?>        
            @if(isFilterClearButtonShow('all'))
                @if(!__isEmpty(Request::input('search_term'))) 
                    <small class="float-right"><a href="<?= e(Request::url().'?search_term='.Request::input('search_term')) ?>"><?= __tr('Clear All Filters') ?></a></small>
                @else
                    <small class="float-right"><a href="<?= Request::url() ?>"><?= __tr('Clear All Filters') ?></a></small>
                @endif
            @endif
            <h4 class="mt-2 ml-2"><?= __tr('Filters') ?></h4>

            @if(__isEmpty($data['productIds']))
                <hr>
                <p class="ml-2"><?= __tr('No filter options.') ?></p>
            @endif

            <ul class="list-group list-group-flush">
            @if(!__isEmpty($data['categories']))
                <li class="list-group-item">
                     <strong><?= __tr('Categories') ?></strong>
                </li>
                    <li class="list-group-item">
                        @foreach($data['categories'] as $key => $category)
                        <div class="custom-control custom-checkbox lw-filter-checkbox-select">
                            <input type="checkbox" class="custom-control-input" id="category-'<?= $category['id'] ?>'" value="'<?= $category['id'] ?>'" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, null, null, '<?= $category['id'] ?>')" <?php echo in_array($category['id'], $data['catIds']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="category-'<?= $category['id'] ?>'"><?= $category['name'] ?></label>
                            <span class="float-right lw-filter-product-count">
                                <?= $category['productCount'] ?>
                            </span>
                        </div>
                        @endforeach
                    </li>
            @endif
            <li class="list-group-item">
                <strong><?= __tr('Price') ?></strong>
                @if(isFilterClearButtonShow('price'))
                <small class="float-right"><a href ng-click="SidebarFilterCtrl.clearPriceFilter('<?= $currentFilterUrl ?>', '<?= round($filterPrices['min_price']) ?>', '<?= round($filterPrices['max_price']) ?>')"><?= __tr('Clear') ?></a></small>
                @endif
            </li>
            <li class="list-group-item" ng-init="SidebarFilterCtrl.filterInitData('<?= $filterPrices['min_price'] ?>', '<?= $filterPrices['max_price'] ?>', '<?= $productPrices['min_price'] ?>', '<?= $productPrices['max_price'] ?>', '<?= $data['currenSymbol'] ?>', '<?= urlencode($currentFilterUrl) ?>')">
                <div class="col-sm-12 mt-5 lw-price-slider-container">
                    <rzslider id="slider"
                        class="lw-price-range-slider lw-sidebar-filter-slider" 
                        rz-slider-model="SidebarFilterCtrl.sliderBar.minValue"
                        rz-slider-high="SidebarFilterCtrl.sliderBar.maxValue"
                        rz-slider-options="SidebarFilterCtrl.sliderBar.options">
                    </rzslider>
                </div>

                <div class="form-row mt-4">
                    <div class="col">
                        <?= __tr('Min') ?> :<input type="number" 
                                class="form-control form-control-sm"
                                ng-init="SidebarFilterCtrl.sliderBar.min_price = <?= round($filterPrices['min_price']) ?>" 
                                ng-model="SidebarFilterCtrl.sliderBar.min_price"
                                ng-model-options="{ debounce: 1000 }"
                                ng-change="SidebarFilterCtrl.changeFilterPrice(SidebarFilterCtrl.sliderBar.min_price, SidebarFilterCtrl.sliderBar.max_price, '<?= $currentFilterUrl ?>')"
                            >
                    </div>
                    <div class="col">
                        <?= __tr('Max') ?> :<input type="number" 
                                class="form-control form-control-sm"
                                ng-init="SidebarFilterCtrl.sliderBar.max_price = <?= round($filterPrices['max_price']) ?>"
                                ng-model="SidebarFilterCtrl.sliderBar.max_price"
                                ng-model-options="{ debounce: 1000 }"
                                ng-change="SidebarFilterCtrl.changeFilterPrice(SidebarFilterCtrl.sliderBar.min_price, SidebarFilterCtrl.sliderBar.max_price, '<?= $currentFilterUrl ?>')"
                            >
                    </div>
                </div>
            </li>
            @if(!__isEmpty($data['productRatings']))
                <li class="list-group-item">
                    <strong>
                        <?= __tr('Customer Rating') ?>
                    </strong>
                    @if(isFilterClearButtonShow('rating'))
                    <small class="float-right"><a href ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, 'clear', null)"><?= __tr('Clear') ?></a></small>
                    @endif
                </li>
                <li class="list-group-item">
                    <span class="lw-product-rating-avg">
                        @foreach($data['productRatings'] as $rating => $ratings)
                            @for($i=$rating; $i <= $rating; $i++)
                            <div class="custom-control custom-radio lw-filter-checkbox-select" ng-init="SidebarFilterCtrl.ratings = '<?= $data['filterRating'] ?>'">
                                <input type="radio" class="custom-control-input" name="ratings" id="ratings-<?= $rating ?>" ng-model="SidebarFilterCtrl.ratings" ng-value="'<?= $rating ?>'" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, '<?= $rating ?>', null)">
                                <label class="custom-control-label" for="ratings-<?= $rating ?>">
                                    <?= str_repeat("<i class='fa fa-star lw-color-gold'></i>", $i) ?><?= str_repeat("<i class='fa fa-star lw-color-gray'></i>", 5 - $i) ?>
                                </label>
                                <span class="float-right"><small><?= __tr('& UP') ?></small><span class="float-right lw-filter-product-count ml-2"><?= $ratings ?></span></span>
                            </div>
                            @endfor
                        @endforeach
                    </span>
                </li>
            @endif

            @if(!__isEmpty($data['productIds']))
                <li class="list-group-item">
                    <strong><?= __tr('Availability') ?></strong>
                    @if(isFilterClearButtonShow('availability'))
                    <small class="float-right"><a href ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, null, 'clear')"><?= __tr('Clear') ?></a></small>
                    @endif
                </li>
                <li class="list-group-item" ng-init="SidebarFilterCtrl.product_availability = '<?= $data['availablity'] ?>'">
                    <div class="custom-control custom-radio lw-filter-checkbox-select">
                        <input type="radio" class="custom-control-input" name="product_availability" id="in-stock" ng-value="'1'" ng-model="SidebarFilterCtrl.product_availability" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, null, '1')">
                        <label class="custom-control-label" for="in-stock">
                            <?= __tr('In Stock') ?>
                        </label>
                        <span class="float-right lw-filter-product-count">
                            <?= $data['inStockProductCount'] ?>
                        </span>
                    </div>

                    <div class="custom-control custom-radio lw-filter-checkbox-select">
                        <input type="radio" class="custom-control-input" name="product_availability" id="out-of-stock" ng-value="'2'" ng-model="SidebarFilterCtrl.product_availability" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, null, '2')">
                        <label class="custom-control-label" for="out-of-stock">
                            <?= __tr('Out of Stock') ?>
                        </label>
                        <span class="float-right lw-filter-product-count">
                            <?= $data['outStockProductCount'] ?>
                        </span>
                    </div>

                    <div class="custom-control custom-radio lw-filter-checkbox-select">
                        <input type="radio" class="custom-control-input" name="product_availability" id="available-soon" ng-value="'3'" ng-model="SidebarFilterCtrl.product_availability" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, null, null, '3')">
                        <label class="custom-control-label" for="available-soon">
                            <?= __tr('Available Soon') ?>
                        </label>
                        <span class="float-right lw-filter-product-count">
                            <?= $data['availableSoonProductCount'] ?>
                        </span>
                    </div>
                </li>
            @endif

            @if(!__isEmpty($data['productRelatedBrand']))
                <li class="list-group-item"><strong><?= __tr('Brands') ?></strong></li>
                <li class="list-group-item">
                    @foreach($data['productRelatedBrand'] as $key => $brand)
                        <div class="custom-control custom-checkbox lw-filter-checkbox-select">
                            <input type="checkbox" class="custom-control-input" id="brandname-'<?= $brand['brandID'] ?>'" value="'<?= $brand['brandID'] ?>'" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', '<?= $brand['brandID'] ?>', null, null, null)" <?php echo in_array($brand['brandID'], $data['brandsIds']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="brandname-'<?= $brand['brandID'] ?>'"><?= __transliterate('brand', $brand['brandID'], 'name', $brand['brandName']) ?></label>
                            @if(array_key_exists($brand['brandID'], $data['brandProductCount']))
                            <span class="float-right lw-filter-product-count">
                                <?= $data['brandProductCount'][$brand['brandID']] ?>
                            </span>
                            @else
                                <span class="float-right lw-filter-product-count">
                                    0
                                </span>
                            @endif
                        </div>
                    @endforeach
                </li>
                @endif
                @if(!__isEmpty($data['specificationFilter']))
                    @foreach($data['specificationFilter'] as $specificationKey => $specifications)
                        <li class="list-group-item"><?= $specificationKey ?>
                            @foreach($specifications as $specification)
                            <?php $specCount = array_key_exists($specification['_id'], $data['specificationCount']) ? $data['specificationCount'][$specification['_id']] : 0; ?>
                                <div class="custom-control custom-checkbox lw-filter-checkbox-select">
                                    <input type="checkbox" class="custom-control-input" id="specification-'<?= $specification['_id'] ?>'" value="'<?= $specification['_id'] ?>'" ng-click="SidebarFilterCtrl.prepareCurrentUrl('<?= urlencode($currentFilterUrl) ?>', null, '<?= $specification['spec_value_id'] ?>', null, null)" <?php echo in_array($specification['spec_value_id'], $data['specsIds']) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="specification-'<?= $specification['_id'] ?>'"><?= __transliterate('specification_value', $specification['_id'], 'value', $specification['specification_value']) ?></label>

                                    <span class="float-right lw-filter-product-count">
                                        <?= $specCount ?>
                                    </span>
                                </div>
                            @endforeach
                        </li>
                    @endforeach
                @endif
            </ul>
    </form>
</div>
<?php
    // Prepare Base Url
    function prepareBaseUrl($brandsIds, $specsIds, $filterRating, $availablity, $catIds, $filterPrices) {
        $currentUrl = Request::url();
        $currentUrl .= '?&brandsIds='.implode('|', $brandsIds).'&specsIds='.implode('|', $specsIds).'&rating='.$filterRating.'&availability='.$availablity.'&categories='.implode('|', $catIds);
        if (!__isEmpty(Request::input('search_term'))) {
            $currentUrl .= '&search_term='.Request::input('search_term');
        }
        if ($filterPrices['min_price'] != $filterPrices['max_price']) {
            $currentUrl .= '&min_price='.$filterPrices['min_price'].'&max_price='.$filterPrices['max_price'];
        }
        return $currentUrl;
    }
?>
