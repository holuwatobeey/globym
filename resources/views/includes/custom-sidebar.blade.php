<div class="bg-light pr-0 pt-3 border-right lw-sidebar-height">
    <div class="lw-links-container">
    	<h5 class="mt-2 mb-4">
	        <a class="nav-link" ui-sref="dashboard" ui-sref-active="active-nav">
	        <i class="fa fa-tachometer"></i> <?= __tr("Dashboard") ?></a>
	    </h5>

	    <h6 class="nav-link text-muted"><i class="fa fa-cogs"></i> <?= __tr("Manage Shop") ?></h6>
	    <ul class="nav flex-column mb-4">
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Basic Configuration')  ?>" ui-sref="store_settings_edit">
	                <i class="fa fa-cog"></i> <?=  __tr('Basic Configuration')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Shipping Rules')  ?>" ui-sref="shippings">
	                <i class="fa fa-plane"></i> <?=  __tr('Shipping Rules')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Shipping Rules')  ?>" ui-sref="css_styles">
	                <i class="fa fa-asterisk"></i> <?=  __tr('CSS Styles')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a ui-sref-active="active-nav" class="nav-link" title="<?=  __tr('Tax Rules')  ?>" ui-sref="taxes">
	                -<i class="fa fa-percent"></i> <?=  __tr('Tax Rules')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Coupons / Discounts')  ?>" ui-sref="coupons.current">
	                <sub><i class="fa fa-scissors"></i></sub><i class="fa fa-usd"></i> <?=  __tr('Coupons / Discounts')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Email Templates')  ?>" ui-sref="email_templates">
	                <i class="fa fa-envelope-o"></i> <?=  __tr('Email Templates')  ?>
	            </a>
	        </li>
	    </ul>

	    <h6 class="nav-link text-muted"><?= __tr("Manage Products ") ?></h6>
	    <ul class="nav flex-column lw-links-container mb-4">
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Brands')  ?>" ui-sref="brands">
	                <i class="fa fa-bold"></i> <?=  __tr('Brands')  ?>
	            </a>
	        </li>
	        <li class="nav-item" ng-if="canAccess('manage.product.list') || canAccess('manage.category.list')">
	            <a ng-if="canAccess('manage.category.list')" class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Categories & Products')  ?>" ui-sref="categories({mCategoryID:''})">
	                <i class="fa fa-th-large"></i> <?=  __tr('Categories & Products')  ?>
	            </a>
                <a ng-if="canAccess('manage.product.list') && !canAccess('manage.category.list')" class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Categories & Products')  ?>" ui-sref="products({mCategoryID:''})">
                    <i class="fa fa-th-large"></i> <?=  __tr('Products')  ?>
                </a>
	        </li>
	    </ul>
	    <h6 class="nav-link text-muted"><?= __tr("Reports & Orders ") ?></h6>
	    <ul class="nav flex-column lw-links-container mb-4">
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Orders')  ?>" ui-sref="orders.active">
	                <i class="fa fa-list"></i> <?=  __tr('Orders')  ?>
	                <span ng-show="manageCtrl.newOrderPlacedCount != 0" class="badge badge-warning" ng-bind="manageCtrl.newOrderPlacedCount"></span>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Reports')  ?>" ui-sref="reports"><i class="fa fa-list-alt" ></i> <?=  __tr('Reports')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Order Payments')  ?>" ui-sref="payments"><i class="fa fa-money" ></i> <?=  __tr('Order Payments')  ?>
	            </a>
	        </li>
	    </ul>
	    <h6 class="nav-link text-muted"><?= __tr("Others ") ?></h6>
	    <ul class="nav flex-column lw-links-container mb-4">
	        <li class="nav-item">
				<a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Pages')  ?>" ui-sref="pages({parentPageID:''})">
	                <i class="fa fa-files-o"></i> <?=  __tr('Pages')  ?>
	            </a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" ui-sref-active="active-nav" title="<?=  __tr('Users')  ?>" ui-sref="users">
	              <i class="fa fa-users"></i> <?=  __tr('Users')  ?>
	            </a>
	        </li>
	    </ul>
    </div>
</div>