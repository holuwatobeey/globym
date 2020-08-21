<!-- sidebar -->
@if(isset($showFilterSidebar) and ($showFilterSidebar === true))
<div class="col-md-3 col-lg-2 lw-public-sidebar-mobile-optimized d-md-block shadow lw-public-filter-sidebar mt-1">
    @include('includes.dynamic-sidebar-filter')
</div>
<div class="lw-public-sidebar-btn-container btn-group">
<a href="#" class="btn btn-primary lw-btn-action-public-sidebar-open">
    <?= __tr('Filters') ?>
</a>
</div>
@endif

@if(isset($hideSidebar) and ($hideSidebar === false) and isset($showFilterSidebar) and ($showFilterSidebar === false))
<div class="col-md-3 col-lg-2 lw-public-sidebar-mobile-optimized d-md-block pl-3 pr-0 shadow lw-public-sidebar mt-1">
<div class="lw-public-sidebar-btn-container btn-group">
<a href="#" class="btn btn-primary lw-btn-action-public-sidebar-open">
    <?= __tr('Categories & Brands') ?>
</a>
</div>
  <div>
    @if(getStoreSettings('categories_menu_placement') != 4)

        <div class="<?= (!__isEmpty($menuData['sideBarCategoriesMenuData']) and (getStoreSettings('categories_menu_placement') == 2)) ? 'd-block d-lg-none' : '' ?> lw-links-container">

            <h6 class="d-flex justify-content-between align-items-center mt-4 mb-1 text-muted">
                <?= __tr("Product Categories") ?>
            </h6>
            <ul class="nav flex-column mb-4 lw-sm-menu-instance sm sm-clean sm-vertical lw-sidebar-style">
                <li class="nav-item">
                    <a class="nav-link" href="<?=  productsFeatureRoute()  ?>"><?= __tr('Featured Products') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=  productsRoute()  ?>"><?= __tr('All Products') ?></a>
                </li>
                <?= buildSidebarMenu($menuData['sideBarCategoriesMenuData']) ?>
            </ul>
            <hr>
        </div>
         
    @endif
    
        @if(getStoreSettings('brand_menu_placement') != 4 && getStoreSettings('brand_menu_placement') != 2) <!-- dont't show  brand menu-->
            @if(!__isEmpty($menuData['sibeBarBrandMenuData'])) 
            
                <div class="<?= getStoreSettings('brand_menu_placement') == 2 ? 'visible-xs' : '' ?> lw-links-container lw-brands-link" >
                    <h6 class="d-flex justify-content-between align-items-center mt-4 mb-1 text-muted">
                       <?= __tr("Shop by Brands") ?>
                    </h6>
                    <ul class="nav flex-column mb-4 lw-sidebar-style">
                        <li class="nav-item">
                            <a class="nav-link" href="<?=  route('fetch.brands')  ?>"><?= __tr("All Brands") ?></a>
                        </li>
                        @foreach($menuData['sibeBarBrandMenuData'] as $brand)
                            <li class="nav-item">
                                <a class="nav-link"  href="<?=  route('product.related.by.brand', [$brand['_id'], $brand['slugName']])  ?>" title="<?=  $brand['name']  ?>" ><?=  $brand['name']  ?></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>
</div>
@endif
<!-- /sidebar -->

<script>
$('body').on('click','.lw-btn-action-public-sidebar-open', function(e) {
    $('html').addClass('lw-public-sidebar-opened');
});

$('body').on('click','.lw-public-sidebar-overlay', function(e) {
    $('html').removeClass('lw-public-sidebar-opened');
});

</script>