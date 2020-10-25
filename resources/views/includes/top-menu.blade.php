 <div class="lw-sidebar-overlay" data-toggle="offcanvas"></div>
<div class="lw-smart-menu-container">
    <nav class="navbar navbar-expand-md lw-custom-navbar d-none d-md-block">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        	<!-- left nav -->
        	<ul class="nav navbar-nav mr-auto lw-sm-menu-instance sm sm-clean lw-sm-menu hide-till-load lw-main-header-menu d-none d-sm-block">
        		@include('includes.dynamic-nevigation-menu')
        	</ul>	
           
    	  	 <!-- Right nav -->
            <ul class="nav navbar-nav lw-sm-menu-instance sm sm-clean lw-sm-menu lw-main-header-menu d-none d-sm-block">
                @if(getStoreSettings('display_multi_currency') and (!__isEmpty(getStoreSettings('multi_currencies'))))    
                    <li class="nav-item dropdown">
                       @include('includes.currency-menu')
                    </li>
                @endif

                <li>@include('locale-menu')</li>

                <li class="<?= isActiveRoute('manage.app') ?> lw-show-process-action nav-item" ng-if="publicCtrl.auth_info.authorized && publicCtrl.auth_info.canAccessManageApp" ng-cloak>
                    <a href="<?=  route('manage.app')  ?>" title="<?= __tr( 'Manage Store' ) ?>">
                        <i class="fa fa-wrench"></i> <?=  __tr('Admin Area')  ?>
                    </a>
                </li>
            
            </ul>
        </div>

    </nav>
   
</div>
 

<div class="row-offcanvas row-offcanvas-left hide-till-load lw-custom-navbar d-block d-md-none">
	<div class="row">	

	    <!-- sidebar-offcanvas-->   
	    <div class="sidebar-offcanvas ml-0 pl-0 pr-0"> 
	        <div class="lw-sidebar-menu lw-custom-sidebar-menu">
	        	<ul class="nav flex-column navbar-nav lw-sm-menu-instance sm sm-clean">
	        		<?= buildNevigationMenu($menuData['navMenuData']) ?>
	        	</ul>
	            <hr>

	            <ul class="nav navbar-nav lw-sm-menu-instance sm sm-clean lw-sm-menu lw-main-header-menu d-block d-md-none">
	                @if(getStoreSettings('display_multi_currency') and (!__isEmpty(getStoreSettings('multi_currencies'))))    
	                    <li class="nav-item dropdown">
	                       @include('includes.currency-menu')
	                    </li>
	                @endif

	                <li>@include('locale-menu')</li>

	                <!-- Menu List -->
	                @if (isLoggedIn())
	                <li class="nav-item">
	                <!--   <a class="nav-link dropdown-toggle" href="#">Dropdown</a> -->
	                <a href>
	                    <i class="fa fa-user"></i> <span ng-bind="publicCtrl.auth_info.profile.full_name"></span> 
	                </a>
	                    <ul>
	                        <li class="<?= isActiveRoute('user.profile.update') ?>">
	                            <a href="<?=  route('user.profile')  ?>" title="<?=  __tr('Profile')  ?>" class="dropdown-item">
	                                <?=  __tr('Profile')  ?>
	                            </a>
	                        </li>
	                        <li class="<?= isActiveRoute('user.change_password') ?>">
	                            <a href="<?=  route('user.change_password')  ?>" class="dropdown-item" title="<?=  __tr('Change Password')  ?>"><?=  __tr('Change Password')  ?></a>
	                        </li>
	                        <li class="<?= isActiveRoute('user.change_email') ?>">
	                            <a href="<?=  route('user.change_email')  ?>" class="dropdown-item" title="<?= __tr('Change Email') ?>"><?= __tr('Change Email') ?></a>
	                        </li>
	                        <li class="<?= isActiveRoute('user.address.list') ?>">
	                            <a href="<?=  route('user.address.list')  ?>" class="dropdown-item" title="<?= __tr('Addresses') ?>"><?= __tr('Addresses') ?></a>
	                        </li>
	                        <li class="<?= isActiveRoute('cart.order.list') ?>">
	                            <a href="<?=  route('cart.order.list')  ?>" class="dropdown-item" title="<?= __tr('My Orders') ?>"><?= __tr('My Orders') ?></a>
	                        </li>
	                        @if(getStoreSettings('enable_wishlist'))
	                        <li class="<?= isActiveRoute('product.my_wishlist.list') ?>">
	                            <a href="<?=  route('product.my_wishlist.list')  ?>" class="dropdown-item" title="<?= __tr('My Wish List') ?>"><?= __tr('My Wish List') ?></a>
	                        </li>
	                        @endif
	                        @if(getStoreSettings('enable_rating'))
	                        <li class="<?= isActiveRoute('product.ratings.read.view') ?>">
	                            <a href="<?=  route('product.ratings.read.view')  ?>" class="dropdown-item" title="<?= __tr('My Ratings') ?>"><?= __tr('My Ratings') ?></a>
	                        </li>
	                        @endif
	                        <li class="<?= isActiveRoute('user.logout') ?>">
	                            <a href="<?=  route('user.logout')  ?>" class="dropdown-item" title="<?= __tr('Logout') ?>"><?= __tr('Logout') ?> <i class="fa fa-sign-out"></i></a>
	                        </li>
	                    </ul>
	                </li>
	                @endif

	                <li class="<?= isActiveRoute('manage.app') ?> lw-show-process-action nav-item" ng-if="publicCtrl.auth_info.authorized && publicCtrl.auth_info.canAccessManageApp" ng-cloak>
	                    <a href="<?=  route('manage.app')  ?>" title="<?= __tr( 'Manage Store' ) ?>">
	                        <i class="fa fa-cogs"></i> <?=  __tr('Manage Store')  ?>
	                    </a>
	                </li>
	            </ul>
				<hr>
	            <ul class="nav flex-column lw-sm-menu-instance sm sm-clean sm-vertical">
	                <li class="nav-item">
	                    <a class="nav-link" href="<?=  productsFeatureRoute()  ?>"><?= __tr('Featured Products') ?></a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link" href="<?=  productsRoute()  ?>"><?= __tr('All Products') ?></a>

	                    <ul>
	                    	@include('includes.dynamic-sidebar-menu')
	                    </ul>
	                </li>

	                @if(!__isEmpty($menuData['sibeBarBrandMenuData']))                     
	                	<li class="nav-item">
	                        <a class="nav-link" href="<?=  route('fetch.brands')  ?>">
	                        	<?= __tr("Shop by Brands") ?>
	                        </a>
	                        <ul>
	                        	@foreach($menuData['sibeBarBrandMenuData'] as $brand)
	                        	<li>
		                            <a class="nav-link" href="<?=  route('product.related.by.brand', [$brand['_id'], $brand['slugName']])  ?>" title="<?=  $brand['name']  ?>" ><?=  $brand['name']  ?></a>
		                        </li>
	                    		@endforeach
	                        </ul>
	                    </li>
	                 
		            @endif
	            </ul>
	        </div>
	    </div>
	    <!--/.sidebar-offcanvas-->   
	</div>
</div>