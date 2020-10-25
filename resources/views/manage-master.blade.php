<!DOCTYPE html>
<html lang="<?php echo substr(CURRENT_LOCALE, 0, 2); ?>" class="lw-has-disabled-block">
<head>
    <title>
        <?= e( __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ) ) ?> : <?= __tr('Manage Store') ?>
    </title>
    <!-- Head Content -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" property="og:description" content="@yield('description')">
    <meta name="keywordDescription" property="og:keywordDescription" content="@yield('keywordDescription')">
    <meta name="keywordName" property="og:keywordName" content="@yield('keywordName')">
    <meta name="keyword" content="@yield('keyword')">
    <meta name="title" content="@yield('page-title')">
    <meta name="store" content="<?= e( __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ) ) ?>">
    <style>
        .lw-page-content, .hide-till-load, .lw-main-loader, .lw-page-loader{
            display: none;
        }

        .lw-zero-opacity {
            -webkit-opacity: 0;
            -moz-opacity:0
            -o-opacity:0
            opacity:0: 0;
        }

        .lw-hidden {
            display: none;
        }

        .lw-show-till-loading {
            display: block;
        }

        .lw-loader-text-primary {
            color: #1867d6 !important;
        }
        .lw-loader-text-success {
            color: #5eba00 !important;
        }
        .lw-loader-text-danger {
            color: #cd201f !important;
        }
        .lw-loader-spinner-grow {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            vertical-align: text-bottom;
            background-color: currentColor;
            border-radius: 50%;
            opacity: 0;
            animation: spinner-grow .75s linear infinite;
        }
        .lw-page-loader {
            position:fixed;
            width: 100%;
            text-align: center;
            top:50vh;
            z-index:99999;
        }

    </style>
    
    <link rel="shortcut icon" type="image/x-icon" href="<?=  getStoreSettings('favicon_image_url')  ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
    <!-- / Head Content -->
    <?= __yesset([
        'dist/libs/bootstrap/css/bootstrap.min.css',
        'dist/css/vendorlibs-jquery-typeahead.css',
        // 'dist/libs/bootstrap-offcanvas-sidebar/css/bootstrap-offcanvas-sidebar.min.css',
        'dist/libs/fontawesome/css/font-awesome.min.css',
        'dist/libs/codemirror/lib/codemirror.css',
        'dist/libs/trumbowyg/dist/ui/trumbowyg.min.css',
        'dist/libs/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css',
        'dist/css/vendorlibs-smartmenus.css',    
        'dist/css/vendorlibs-fancytree.css', 
        'dist/css/vendorlibs-datatable.css',          
        'dist/css/vendorlibs-angular.css',
        'dist/css/vendorlibs-ngdialog.css',   
        'dist/css/vendorlibs-selectize.css',     
        'dist/css/vendorlibs-switchery.css',        
        'dist/css/vendorlibs-other-common.css',
        'dist/css/vendorlibs-manage.css', 
        'dist/css/application*.css',
        'dist/libs/jquery/jquery.min.js',
        'dist/libs/lodash/lodash.min.js',
        'dist/libs/jquery-colorbox/example3/colorbox.css'
    ]) ?>
    <style type="text/css">
        .lw-manage-navbar {
          background-color: #<?= (!__isEmpty(getStoreSettings('selected_theme_color')) ? getStoreSettings('selected_theme_color') : getStoreSettings('logo_background_color')) ?>!important;
        }
    </style>
</head> 
<body ng-app="ManageApp" >
    <div class="lw-page-loader lw-show-till-loading">
        <div class="lw-loader-spinner-grow lw-loader-text-primary" role="status">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="lw-loader-spinner-grow lw-loader-text-success" role="status">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="lw-loader-spinner-grow lw-loader-text-danger" role="status">
        <span class="sr-only">Loading...</span>
        </div>
        <div class="lw-loader-spinner-grow text-warning" role="status">
        <span class="sr-only">Loading...</span>
        </div>
    </div>    
    <div ng-controller="ManageController as manageCtrl" ng-csp ng-cloak ng-strict-di>
        {{-- Disabled loading block --}}
    <div class="lw-disabling-block">
        <div class="lw-processing-window lw-hidden">
            <div class="loader"><?=  __tr('Loading...')  ?></div>
            <div><?= __tr( 'Please wait while we are processing your request...' ) ?></div>
        </div>
    </div>
    {{-- /Disabled loading block --}}
    <noscript>
        <style>.nojs-msg { width: 50%; margin:20px auto}</style>
        <div class="custom-noscript">
            <div class="bs-callout bs-callout-danger nojs-msg">
              <h4><?= __tr('Oh dear... we are sorry') ?></h4>
              <em><strong><?= __tr('Javascript') ?></strong> <?= __tr('is disabled in your browser, To use this application please enable javascript &amp; reload page again.') ?></em>
            </div>
        </div>
    </noscript>
    
    <div class="lw-sidebar-overlay" data-toggle="offcanvas"></div>

    <!-- manage side top menu -->
    <div>
        @include('includes.manage-top-menu')
    </div>
    <!-- manage side top menu -->
    
    <div>
        <!-- container -->
        <div class="container-fluid">
            <div class="row-offcanvas row-offcanvas-left hide-till-load">
                <div class="row">
                	<!-- Sidebar  -->
			        <nav id="lwToggleSidebar">
			            <button type="button" id="sidebarCollapse" class="btn btn-light">
	                        <i class="fa fa-bars" aria-hidden="true"></i>
	                    </button>

			            <div class="lw-sidebar-content">
							<div class="input-group input-group-sm py-4 px-3">
								<input type="text" class="form-control" placeholder="<?= __tr('Type to filter menu') ?>" ng-model="manageSearch" ng-change="manageCtrl.searchSidebarFilter(manageSearch)">
								<div class="input-group-append">
									<button ng-disabled="manageSearch == null || manageSearch == ''" ng-click="manageSearch = ''" class="btn btn-outline-light" type="button" id="button-addon2"><strong>&nbsp;&times;&nbsp;</strong></button>
								</div>
							</div>

                            <ul class="list-unstyled" lw-filter-list="manageSearch">
                            	<li class="" data-tags="dashboard">
		                            <a ui-sref="dashboard"  ui-sref-active="active-nav"  title="<?= __tr('Dashboard') ?>"><i class="fa fa-dashboard"></i> <?= __tr('Dashboard') ?></a>
		                        </li>

		                        <li class="" data-tags="General Settings">
                                    <a ui-sref-active="active-nav" title="<?=  __tr('General Settings')  ?>" ui-sref="store_settings_edit.general"><i class="fa fa-cog"></i> <?=  __tr('General Settings')  ?></a>
                                </li>

		                        <!-- Products Section -->
				               	<li class="" data-tags="Products Section Categories Products Brands Specification Preset">
				                    <a href data-target="#productSection" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?= __tr('Products Section') ?></a>

				                    <ul class="list-unstyled collapse" id="productSection" lw-filter-list="manageSearch">
				                        <li class=""  data-tags="Categories" >
				                            <a ui-sref="categories({mCategoryID:''})"  title="<?=  __tr('Categories')  ?>" ng-if="canAccess('manage.category.list')" ui-sref-active="active-nav"><i class="fa fa-th-large"></i> <?=  __tr('Categories')  ?></a>
				                        </li>
				                        <li class="" data-tags="Products">
				                            <a  ui-sref="products({mCategoryID:''})" ng-if="canAccess('manage.product.list')" ui-sref-active="active-nav" title="<?=  __tr('Products')  ?>"><i class="fa fa-th-large"></i> <?=  __tr('Products') ?></a>
				                        </li>
				                        <li class="" data-tags="Brands">
				                            <a   ng-if="canAccess('manage.brand.list')" ui-sref-active="active-nav" title="<?=  __tr('Brands')  ?>" ui-sref="brands"><i class="fa fa-bold"></i> <?=  __tr('Brands')  ?></a>
				                        </li>
				                        <li class="" data-tags="Specification Preset" >
				                            <a  ng-if="canAccess('manage.specification_preset.read.list')" ui-sref-active="active-nav" title="<?=  __tr('Specification Preset')  ?>" ui-sref="specificationsPreset"><i class="fa fa-th-large"></i> <?=  __tr('Specification Preset')  ?></a>
				                        </li>
                                        <li class="" data-tags="Product Ratings" >
                                            <a ui-sref-active="active-nav" title="<?=  __tr('Product Ratings')  ?>" ui-sref="productRating"><i class="fa fa-star-o"></i> <?=  __tr('Product Ratings')  ?></a>
                                        </li>
				                    </ul>
				                </li>

				                <!-- Sale Settings -->
				                <li class="" data-tags="Shipping Rules Method Tax Rules Coupons Discounts">
				                    <a href data-target="#saleSettings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?=  __tr('Sale Settings') ?></a>
				                    <ul class="collapse list-unstyled" id="saleSettings" lw-filter-list="manageSearch">
				                        <li class="" data-tags="Shipping Rules">
				                            <a ng-if="canAccess('manage.shipping.list')" ui-sref-active="active-nav" title="<?=  __tr('Shipping Rules')  ?>" ui-sref="shippings"><i class="fa fa-plane"></i> <?=  __tr('Shipping Rules')  ?></a>
				                        </li>
				                        <li class="" data-tags="Shipping Method">
				                            <a ng-if="canAccess('manage.shipping_type.read.list')" ui-sref-active="active-nav" title="<?=  __tr('Shipping Method')  ?>" ui-sref="shippingType"><i class="fa fa-th-large"></i> <?=  __tr('Shipping Method')  ?></a>
				                        </li>
				                        <li class="" data-tags="Tax Rules">
				                            <a ng-if="canAccess('manage.tax.list')" ui-sref-active="active-nav"  title="<?=  __tr('Tax Rules')  ?>" ui-sref="taxes"><i class="fa fa-percent"></i> <?=  __tr('Tax Rules')  ?></a>
				                        </li>
				                        <li class="" data-tags="Coupons Discounts">
				                            <a ng-if="canAccess('manage.coupon.list')" ui-sref-active="active-nav" title="<?=  __tr('Coupons / Discounts')  ?>" ui-sref="coupons.current"><i class="fa fa-usd"></i> <?=  __tr('Coupons / Discounts')  ?></a>
				                        </li>
				                    </ul>
				                </li>

				                <!-- Template and Content -->
				                <li class="" data-tags="CSS Styles Contact Placement & Misc Info Template Content Slider Email Templates Footer Home Landing Page Privacy Policy">
				                    <a href data-tags="Template Content" data-target="#templateAndContent" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
				                    	<?=  __tr('Template & Content') ?>
			                    	</a>
				                    <ul class="collapse list-unstyled" id="templateAndContent" lw-filter-list="manageSearch">
                                        <li class="" data-tags="Configure Home Page">
                                            <a ui-sref-active="active-nav" title="<?=  __tr('Configure Home Page')  ?>" ui-sref="store_settings_edit.landing_page"><i class="fa fa-files-o"></i> <?=  __tr('Configure Home Page')  ?></a>
                                        </li>
				                        <li class="" data-tags="Slider">
				                            <a ng-if="canAccess('store.settings.slider.read.list')" ui-sref-active="active-nav" title="<?=  __tr('Slider')  ?>" ui-sref="slider_setting"><i class="fa fa-television"></i> <?= __tr('Slider')  ?></a>
				                        </li>
				                        <li class="" data-tags="Email Templates">
				                            <a ng-if="canAccess('store.settings.get.email-template.data')" ui-sref-active="active-nav" title="<?=  __tr('Email Templates')  ?>" ui-sref="email_templates"><i class="fa fa-envelope-o"></i> <?=  __tr('Email Templates') ?></a>
				                        </li>
                                        <li class="" data-tags="CSS Styles">
                                            <a ng-if="canAccess('store.settings.edit.supportdata')" ui-sref-active="active-nav" title="<?=  __tr('CSS Styles')  ?>" ui-sref="css_styles"><i class="fa fa-asterisk"></i> <?=  __tr('CSS Styles')  ?></a>
                                        </li>
                                        <li class="" data-tags="Placement Misc Settings">
                                            <a ui-sref-active="active-nav" title="<?=  __tr('Placement & Misc Settings')  ?>" ui-sref="store_settings_edit.placement"><i class="fa fa-align-justify"></i> <?=  __tr('Placement & Misc Settings')  ?></a>
                                        </li>
				                        <li class="" data-tags="Footer">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Footer')  ?>" ui-sref="store_settings_edit.manage_footer_settings"><i class="fa fa-square-o"></i> <?=  __tr('Footer')  ?></a>
										</li>
				                        <li class="" data-tags="Privacy Policy">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Privacy Policy')  ?>" ui-sref="store_settings_edit.privacy_policy"><i class="fa fa-lock"></i> <?=  __tr('Privacy Policy')  ?></a>
						                </li>
				                        <li class="" data-tags="Contact Info">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Contact Info')  ?>" ui-sref="store_settings_edit.contact"><i class="fa fa-address-card"></i> <?=  __tr('Contact Info')  ?></a>
										</li>
				                    </ul>
				                </li>

				                <!-- Other Settings -->
				                <li class="" data-tags="Payment Currency Product Email Order User Social Accounts Social Login Links">
				                    <a href data-target="#storeSettings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?=  __tr('Other Settings') ?></a>
				                    <ul class="collapse list-unstyled" id="storeSettings" lw-filter-list="manageSearch">
				                        <li class="" data-tags="Payment Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Payment Settings')  ?>" ui-sref="store_settings_edit.payment"><i class="fa fa-credit-card-alt"></i> <?=  __tr('Payment Settings')  ?></a>
					                    </li>
				                        <li class="" data-tags="Currency Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Currency Settings')  ?>" ui-sref="store_settings_edit.currency"><i class="fa fa-money"></i> <?=  __tr('Currency Settings')  ?></a>
										</li>
				                        <li class="" data-tags="Product Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Product Settings')  ?>" ui-sref="store_settings_edit.product"><i class="fa fa-th-large"></i> <?=  __tr('Product Settings')  ?></a>
					                    </li>
				                        <li class="" data-tags="Email Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Email Settings')  ?>" ui-sref="store_settings_edit.email_settings"><i class="fa fa-envelope-o"></i> <?=  __tr('Email Settings')  ?></a>
										</li>
				                        <li class="" data-tags="Order Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Order Settings')  ?>" ui-sref="store_settings_edit.order"><i class="fa fa-cog"></i> <?=  __tr('Order Settings')  ?></a>
				                        </li>
				                        <li class="" data-tags="User Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('User Settings')  ?>" ui-sref="store_settings_edit.user"><i class="fa fa-share-square-o"></i> <?=  __tr('User Settings')  ?></a>
				                        </li>
				                        <li class="" data-tags="Social Accounts Links Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Social Links')  ?>" ui-sref="store_settings_edit.social"><i class="fa fa-plane"></i> <?=  __tr('Social Links')  ?></a>
				                        </li>
				                        <li class="" data-tags="Social Login Settings">
						                    <a ui-sref-active="active-nav" title="<?=  __tr('Social Login Settings')  ?>" ui-sref="store_settings_edit.social_authentication_setup"><i class="fa fa-sign-in"></i> <?=  __tr('Social Login Settings')  ?></a>
				                        </li>
				                    </ul>
				                </li>
                                <!-- / Other Settings -->
                                
				                <!-- Order & Payments -->
				                <li class="" data-tags="Order Payments">
				                    <a href data-target="#orderCollapse" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?=  __tr('Order & Payments') ?></a>
				                    <ul class="collapse list-unstyled" id="orderCollapse" lw-filter-list="manageSearch">
				                        <li class="" data-tags="Orders">
				                            <a  ng-if="canAccess('manage.order.list')" ui-sref-active="active-nav" title="<?=  __tr('Orders')  ?>" ui-sref="orders.active"> <i class="fa fa-list"></i> <?=  __tr('Orders')  ?><span ng-show="manageCtrl.newOrderPlacedCount != 0" class="badge badge-warning" ng-bind="manageCtrl.newOrderPlacedCount"></span></a>
				                        </li>
				                        <li class="" data-tags="Order Payments" > 
				                            <a ng-if="canAccess('manage.order.payment.list')" ui-sref-active="active-nav" title="<?=  __tr('Order Payments')  ?>" ui-sref="payments"><i class="fa fa-money" ></i> <?=  __tr('Order Payments') ?></a>
				                        </li>
				                    </ul>
				                </li>
				                <li class="" data-tags="Order Report Payment Product" >
				                    <a href data-target="#reportsCollapse" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?=  __tr('Reports') ?></a>
				                    <ul class="collapse list-unstyled" id="reportsCollapse" lw-filter-list="manageSearch">
				                        <li class="" data-tags="Order Report">
				                            <a ng-if="canAccess('manage.report.list')" ui-sref-active="active-nav" title="<?=  __tr('Order Report')  ?>" ui-sref="order_report"><i class="fa fa-wpforms"></i> <?=  __tr('Order Report')  ?></a>
				                        </li>
				                        <!-- <li class="" data-tags="Payment Report">
				                            <a ng-if="canAccess('manage.payment_report.list')" ui-sref-active="active-nav" title="<?=  __tr('Payment Report')  ?>" ui-sref="payment_report"><i class="fa fa-usd"></i><i class="fa fa-file-text-o"></i> <?=  __tr('Payment Report')  ?></a>
				                        </li> -->
				                        <li class="" data-tags="Product Report">
				                            <a ng-if="canAccess('manage.product_report.list')" ui-sref-active="active-nav" title="<?=  __tr('Product Report')  ?>" ui-sref="product_report"><i class="fa fa-file-text-o"></i> <?=  __tr('Product Report')  ?></a>
				                        </li>
				                    </ul>
				                </li>

				                <!-- Others -->
				                <li class="" data-tags="Others Pages Users User Roles">
				                    <a href data-target="#otherSettings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?=  __tr('Others') ?></a>
				                    <ul class="collapse list-unstyled" id="otherSettings" lw-filter-list="manageSearch">
				                        <li class="" data-tags="Pages Menu">
				                            <a ng-if="canAccess('manage.pages.fetch.datatable.source')" ui-sref-active="active-nav" title="<?=  __tr('Pages & Menu')  ?>" ui-sref="pages({parentPageID:''})"><i class="fa fa-files-o"></i> <?=  __tr('Pages & Menu')  ?></a>
				                        </li>
				                        <li class="" data-tags="Users">
				                            <a ng-if="canAccess('manage.user.list')" ui-sref-active="active-nav" title="<?=  __tr('Users')  ?>" ui-sref="users"><i class="fa fa-users"></i> <?=  __tr('Users') ?></a>
				                        </li>
				                        <li class="" data-tags="User Roles">
				                            <a ng-if="canAccess('manage.user.role_permission.read.list')" ui-sref-active="active-nav" title="<?=  __tr('User Roles')  ?>" ui-sref="role_permission"><i class="fa fa-users"></i> <?=  __tr('User Roles')  ?></a>
				                        </li>
				                    </ul>
				                </li>
			            	</ul>
                        </div>
			        </nav>
			        <!--/ Sidebar  -->
                    <div class="col-12 offset-lg-2 col-lg-10 px-lg-5 px-md-3 px-sm-3" id="lw-main-component-view">
                        <div class="typeahead__container lw-typehead-container">
                            <div class="typeahead__field">
                                <div class="typeahead__query">
                                    <input class="lw-module-search" name="search-term" type="search" placeholder="Spotlight search like Products, Categories, Settings etc" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="lw-main-component-page">
                            <div class="lw-sub-component-page ui-view-container">
                                <div class="lw-component-content master-view" ui-view></div>
                            </div>
                        </div>
                    </div>
                    <!-- Main page component-->   
                </div>
            </div>
        </div>
    </div>
   
   

    <!-- settings update reload button template -->
	<script type="text/ng-template" 
			id="lw-settings-update-reload-button-template.html">
	        <input type="hidden" id="lwReloadBtnText" data-message="<i class='fa fa-refresh' aria-hidden='true'></i> <?= __tr("Reload") ?>">
	</script>
	<!-- /settings update reload button template -->

    <!-- Footer layout -->
    @if(getStoreSettings('addtional_page_end_content'))
        <div class="clearfix">
            <div class="col-lg-12">
                <?= getStoreSettings('addtional_page_end_content') ?>
            </div>
        </div>
    @endif

    <div class="lw-footer">
        <div class="row pt-1">
            <div class="col-sm-6 pl-5">
                <span class="float-left">
                    <?= __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ). ' - ' ?> &copy; <?= date("Y") ?>
                </span>
            </div>
            <div class="col-sm-6 pr-5">

                 <span class="pull-right">
                    @if(getStoreSettings('social_twitter'))
                        <a class="mx-1" title="Twitter" target="_blank" href="https://twitter.com/<?= getStoreSettings('social_twitter') ?>"> <i class="fa fa-twitter"></i></a>
                    @endif

                    @if(getStoreSettings('social_facebook'))
                        <a class="mx-1" title="Facebook" target="_blank" href="https://www.facebook.com/<?= getStoreSettings('social_facebook') ?>"><i class="fa fa-facebook"></i></a> |
                    @endif

                    @if (getStoreSettings('privacy_policy'))
                        <span>
                            <a title="<?= __tr('Privacy Policy') ?>" target="_new" href="<?=  route('privacy.policy')  ?>">
                            <?=  __tr('Privacy Policy')  ?>
                            </a>
                        </span>
                    @endif
                    <span><?= e( (__transliterate('privacy_policy_setting', null, 'privacy_policy', getStoreSettings('privacy_policy') ) and  getStoreSettings('credit_info')) ? '.' : '') ?></span>
               

                </span>
            </div>
        </div>
    </div>
    @include('includes.javascript-content')
    @include('includes.form-template')

    <!-- <script async defer src='https://www.google.com/recaptcha/api.js'></script> -->

    <?= __yesset([
        'dist/libs/bootstrap/js/bootstrap.bundle.min.js',
        'dist/js/vendorlibs-jquery-typeahead.js',
        'dist/libs/codemirror/lib/codemirror.js',
        'dist/libs/jquery-colorbox/jquery.colorbox-min.js',
        'dist/libs/trumbowyg/dist/trumbowyg.min.js',
        'dist/libs/trumbowyg/dist/plugins/upload/trumbowyg.upload.min.js',
        'dist/libs/trumbowyg/dist/plugins/table/trumbowyg.table.min.js',
        'dist/libs/trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js',
        'dist/libs/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js',
        'dist/libs/trumbowyg/dist/plugins/noembed/trumbowyg.noembed.min.js',
        'dist/libs/trumbowyg/dist/plugins/pasteembed/trumbowyg.pasteembed.min.js',
        'dist/libs/trumbowyg/dist/plugins/preformatted/trumbowyg.preformatted.min.js',
        // 'dist/libs/bootstrap-offcanvas-sidebar/js/bootstrap-offcanvas-sidebar.min.js',
        // 'dist/libs/headroom/headroom.min.js',
        // 'dist/libs/headroom/jQuery.headroom.min.js',
        'dist/js/vendorlibs-jquery-ui.js',  
        'dist/js/vendorlibs-fancytree.js',     
        'dist/js/vendorlibs-smartmenus.js',     
        'dist/js/vendorlibs-datatable.js',          
        'dist/js/vendorlibs-angular.js',
        'dist/js/vendorlibs-ngdialog.js',   
        'dist/js/vendorlibs-selectize.js',     
        'dist/js/vendorlibs-switchery.js',                 
        'dist/js/vendorlibs-other-common.js',
        'dist/js/vendorlibs-manage.js',
        'dist/js/ngware-app*.js',
        'dist/js/app-support-app*.js',        
    ]) ?>
    @stack('vendorScripts')
        <script src="<?= __yesset('dist/js/manage-app*.js') ?>"></script> 
    @stack('appScripts')

    <!-- container -->
    <script type="text/javascript">
        $(document).ready(function () {
             $('.top-horizental-menu').smartmenus({
                mainMenuSubOffsetX: -1,
                mainMenuSubOffsetY: 4,
                subMenusSubOffsetX: 6,
                subMenusSubOffsetY: -6
            });

            $('body').on('click','.lw-prevent-default-action', function(e) {
                e.preventDefault();
             });

            $('.lw-locale-change-action').on('click', function(e) {
                e.preventDefault();
                __globals.redirectBrowser($(this).attr('href') + window.location.hash);
            });

            $('html').removeClass('lw-has-disabled-block');

            $('html body').on('click','.lw-show-process-action', function(e) {
                $('html').addClass('lw-has-disabled-block');

                setTimeout(function() { 

                    $('.lw-disabling-block').addClass('lw-disabled-block lw-has-processing-window');

                }, 3000);

            });

            $('.hide-till-load').removeClass('hide-till-load');
            $('.lw-show-till-loading').removeClass('lw-show-till-loading');

            $('[data-toggle="offcanvas"]').click(function () {
                $('.lw-sidebar-overlay').toggleClass('active');
                $('html').toggleClass('lw-turn-off-y-scroll-sidebar');
            });

            $('.lw-sidebar-menu a.lw-item-link, .lw-sidebar-menu button').on('click', function() {
                $('.row-offcanvas').toggleClass('active');
                $('.lw-sidebar-overlay').toggleClass('active');
                $('html').toggleClass('lw-turn-off-y-scroll-sidebar');
            });
            
            // Only enable if the document has a long scroll bar
            // Note the window height + offset
            if ( ($(window).height() + 100) < $(document).height() ) {
                // $('.lw-top-link-block').removeClass('hidden').affix({
                //     // how far to scroll down before link "slides" into view
                //     offset: {top:100}
                // });
            }


            $('#sidebarCollapse').on('click', function () {
                $('#lwToggleSidebar').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');

                $('#lw-main-component-view').toggleClass('col-lg-10 offset-lg-2');
                $('#lwToggleSidebar .active').css('left', '0');
            });
        });
    </script>
    <!-- / Footer layout -->

</body>
</html>
