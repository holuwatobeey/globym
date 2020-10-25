<!DOCTYPE html>
<html lang="<?php echo substr(CURRENT_LOCALE, 0, 2); ?>" class="lw-has-disabled-block">
<head>
	<title>
		<?= e( __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ) ) ?> : @yield('page-title')
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

	<!-- Primary Meta Tags -->
	<meta name="title" content="@yield('page-title')">
	<meta name="description" content="@yield('description')">

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="@yield('page-url')">
	<meta property="og:title" content="@yield('page-title')">
	<meta property="og:description" content="@yield('description')">
	<meta property="og:image" content="@yield('page-image')">

	<!-- Twitter -->
	<meta property="twitter:card" content="@yield('twitter-card-image')">
	<meta property="twitter:url" content="@yield('page-url')">
	<meta property="twitter:title" content="@yield('page-title')">
	<meta property="twitter:description" content="@yield('description')">
	<meta property="twitter:image" content="@yield('page-image')">

    <link rel="shortcut icon" type="image/x-icon" href="<?=  getStoreSettings('favicon_image_url')  ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= __yesset([
        'dist/libs/bootstrap/css/bootstrap.min.css',
        'dist/libs/bootstrap-offcanvas-sidebar/css/bootstrap-offcanvas-sidebar.min.css',
        'dist/libs/fontawesome/css/font-awesome.min.css',
        'dist/css/vendorlibs-smartmenus.css',
        'dist/libs/jquery-colorbox/example3/colorbox.css',
        'dist/css/vendorlibs-jquery-typeahead.css',
        'dist/css/vendorlibs-datatable.css',
        'dist/css/vendorlibs-angular.css',
        'dist/css/vendorlibs-ngdialog.css',
        'dist/css/vendorlibs-selectize.css',
        'dist/css/vendorlibs-switchery.css',
        'dist/css/vendorlibs-other-common.css',
        'dist/css/vendorlibs-public.css',
        'dist/css/public-custom-app*.css',
        'dist/libs/jquery/jquery.min.js',
        'dist/libs/lodash/lodash.min.js'
    ]) ?>
        <style>
            body{
                background:#fafafa;
            }
            .jumbotron {
            background-image: url('dist/imgs/visual4.jpg');
            background-size: cover;
            color: white;
            font-weight: bolder;
    
        }
        
        .vertical-center {
        min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
        min-height: 100vh; /* These two lines are counted as one :-)       */
        
        min-height: calc(100% - 64px);  /*  */
        min-height: calc(100vh - 64px); /* */
        
        display: flex;
        align-items: center;
        }
        
        .btn-jumbotron{
            /* background-color: black;
            border-color: white; */
        }
        </style>
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

        div.lw-store-header, div.lw-current-logo-container {
          background-color: #<?= (!__isEmpty(getStoreSettings('selected_theme_color')) ? getStoreSettings('selected_theme_color') : getStoreSettings('logo_background_color')) ?>;
        }
        
        h3.lw-effective-price, .lw-product-box .lw-product-price, .lw-product-price {
            color: #<?= (!__isEmpty(getStoreSettings('selected_theme_color')) ? getStoreSettings('selected_theme_color') : getStoreSettings('logo_background_color')) ?>;
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

    <link href="{{ route('css_style') }}" rel="stylesheet" type="text/css">

</head>
<body ng-app="PublicApp">
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

  <div ng-controller="PublicController as publicCtrl" ng-csp ng-strict-di>
    <noscript>
        <style>
            .nojs-msg {
                width: 50%;
                margin:20px auto;
            }
        </style>
        <div class="custom-noscript">
            <div class="bs-callout bs-callout-danger nojs-msg">
            <h4><?= __tr('Oh dear... we are sorry') ?></h4>
            <em><strong><?= __tr('Javascript') ?></strong> <?= __tr('is disabled in your browser, To use this application please enable javascript &amp; reload page again.') ?></em>
            </div>
        </div>
    </noscript>

    <div id="lwStoreHeader" class="lw-store-header shadow lw-side-space"> 
        @include('includes.top-menu')
        <div class="row lw-header-other-section">
            <div class="col-12 col-md-3">
                <button class="navbar-toggler btn btn-link float-left text-white d-block d-md-none mt-2" type="button" data-toggle="offcanvas"><i class="fa fa-bars"></i></button>
                <a class="lw-show-process-action" href="<?=  route('home.page')  ?>">
                    <img style="margin-top:0.5%" class="logo-image" src="<?=  getStoreSettings('logo_image_url')  ?>" alt="">
                </a>
            </div>
            <div class="col-12 col-md-5 lw-header-search-panel">
                @include('includes.search-panel')
            </div>


            <div class="col-12 col-md-4">
                @if (!isCurrentRoute('cart.view') and !isCurrentRoute('order.summary.view'))
                    <button  ng-if="publicCtrl.loadCartStatus" 
                    ng-click="publicCtrl.openCartDialog(publicCtrl.routeStatus)" 
                        class="btn btn-danger btn-md lw-dynamic-shopping-cart-btn">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span ng-bind-html="publicCtrl.cart_string"></span>
                        <span ng-if="!publicCtrl.loadCartStatus">
                            <i class="fa fa-spinner fa-spin"></i> <?=  __tr('Loading ..')  ?>
                        </span>
                    </button>
                @endif

                <div class="btn-group lw-xs-dblock-btn lw-shopping-cart-btn" role="group"  ng-cloak aria-label="Your Account">
                    <!-- Shopping Cart Button -->

                    <button ng-show="publicCtrl.loadPage == 1" <?php if(isCurrentRoute('order.summary.view') || isCurrentRoute('cart.view')) { echo 'disabled'; } ?>  ng-click="publicCtrl.openCartDialog(publicCtrl.routeStatus)"
                        class="btn btn-light btn lw-shopping-cart-dialog-btn"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span class="main-color-text text-primary" ng-bind-html="publicCtrl.cart_string"></span>
                        <span ng-if="!publicCtrl.loadPage">
                            <i class="fa fa-spinner fa-spin"></i> <?=  __('Loading ..')  ?>
                        </span>
                    </button>
 
                    <!-- / Shopping Cart Button -->

                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1"  type="button" class="btn btn-secondary"  ng-if="!publicCtrl.auth_info.authorized" ng-click='publicCtrl.showloginDialog()'>
                            <?= __tr('Login') ?>
                        </button>
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-if="publicCtrl.auth_info.authorized">
                            <?= __tr('My Account') ?>
                    </button>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1" ng-if="publicCtrl.auth_info.authorized">
                        
                            <h6 class="dropdown-header">
                                <i class="fa fa-user"></i> <span ng-bind="publicCtrl.auth_info.profile.full_name"></span>
                            </h6>

                            <a href="<?=  route('cart.order.list')  ?>" class="dropdown-item" title="<?= __tr('My Orders') ?>"><?= __tr('My Orders') ?></a>
                            @if(getStoreSettings('enable_wishlist'))
                            <a href="<?=  route('product.my_wishlist.list')  ?>" class="dropdown-item" title="<?= __tr('My Wish List') ?>"><?= __tr('My Wish List') ?></a>
                            @endif
                            @if(getStoreSettings('enable_rating'))
                            <a href="<?=  route('product.ratings.read.view')  ?>" class="dropdown-item" title="<?= __tr('My Ratings') ?>"><?= __tr('My Ratings') ?></a>
                            @endif
                            <a href="<?=  route('user.address.list')  ?>" class="dropdown-item" title="<?= __tr('My Addresses') ?>"><?= __tr('My Addresses') ?></a>
                            <hr>
                            <a href="<?=  route('user.profile')  ?>" title="<?=  __tr('Profile')  ?>" class="dropdown-item">
                                    <?=  __tr('Profile')  ?></a>
                            <a href="<?=  route('user.change_password')  ?>" class="dropdown-item" title="<?=  __tr('Change Password')  ?>"><?=  __tr('Change Password')  ?></a>
                            <a href="<?=  route('user.change_email')  ?>" class="dropdown-item" title="<?= __tr('Change Email') ?>"><?= __tr('Change Email') ?></a>
                            <a href="<?=  route('user.logout')  ?>" class="dropdown-item" title="<?= __tr('Logout') ?>"><?= __tr('Logout') ?> <i class="fa fa-sign-out"></i></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>








       
    </div>

    <div class="clearfix"></div>
    <div class="lw-small-screen-search-container visible-xs">
       {{-- @include('includes.search-panel') --}}
    </div>

    <div class="lw-sidebar-overlay" data-toggle="offcanvas"></div>
    <div class="lw-public-sidebar-overlay" ></div>
    @if(url()->current() == url('/') || url()->current() == url(''))
        
    <div class="jumbotron jumbotron-fluid vertical-center">
        <div class="container-fluid">
            <h1 class="display-3">Welcome To Globym</h1>
            <p class="lead">Feed your skin from outside within!</p>
            <hr class="my-4">
            {{-- <p>Description text</p> --}}
            <p class="lead">
                <a class="btn btn-success btn-lg btn-jumbotron" href="#" role="button">Order Now</a>
            </p>
        </div>
    </div>
    @endif
<!-- container -->
<!-- section -->
    <div class="container-fluid mb-5 lw-side-space lw-main-conent-container">
        <div class="row lw-container-row">
            @if(isDemo())
                <style>
                    .lw-theme-color-container {
                        width: 90px;
                        position: fixed;
                        top: 130px;
                        left: -95px;
                        display: block;
                        z-index: 99999;
                        background: #fff;
                        border-color: #696969;
                        transition: .2s ease-in-out;
                        background-color: rgba(0, 0, 0, 0.45);
                        padding: 10px 8px;
                        border-bottom-right-radius: 4px;
                        border-top-right-radius: 4px;
                    }

                    .lw-theme-container-active {
                        position: fixed;
                        left: 0px;
                    }

                    .lw-theme-color-container .lw-color {
                        height: 25px;
                        width: 25px;
                        margin: 5px;
                        float: left;
                        border-radius: 3px;
                        cursor: pointer;
                    }

                    .lw-switch {
                        position: absolute;
                        /* top: -1px; */
                        right: -40px;
                        background: #fff;
                        border: 1px solid;
                        border-color: #ababab;
                        height: 40px;
                        width: 40px;
                        color:#ffffff;
                        border: none;
                        background-color: rgba(0, 0, 0, 0.35);
                        padding-left: 14px;
                        padding-top: 6px;
                        font-size: 1.2em;
                        border-bottom-right-radius: 4px;
                        border-top-right-radius: 4px;
                    }
                    .lw-switch:hover > i.fa-cog {
                        animation: fa-spin 2s infinite linear;
                    }

                    a.lw-switch, a.lw-switch:hover {
                        text-decoration: none!important;
                        color:#ffffff;
                    }
                    .lw-theme-title {
                        color:#fff;
                    }
                </style>
                <div class="lw-theme-color-container">
                    <a href class="lw-switch" ng-click="publicCtrl.showHideThemeContainer()">
                        <i class="fa fa-cog"></i>
                    </a>
                    <h6 class="text-center m-1 lw-theme-title"><?= __tr('Demo Themes') ?></h6>
                    <div ng-repeat="(index, themeColor) in publicCtrl.themeColors">
                        <span ng-if="index != 'default'">
                            <a href="" class="lw-color" ng-style="{'background-color': '#[[ themeColor ]]' }" ng-click="publicCtrl.setThemeColor(index)"></a>
                        </span>
                        <span ng-if="index == 'default'" class="ml-2">
                            <button type="button" class="btn btn-light btn-sm" ng-click="publicCtrl.setThemeColor(index)"><?= __tr('Reset') ?></button>
                        </span>
                    </div>
                </div>
            @endif

            @if (isset($hideSidebar) and ($hideSidebar === true) and isset($showFilterSidebar) and ($showFilterSidebar === false))
                <div role="main" class="col-md-12 lw-main-component-page-container" ng-cloak>
            @else

                @include('includes.sidebar')

                <div role="main" class="col-lg-10 col-md-9 ml-sm-auto lw-main-component-page-container" ng-cloak>

            @endif

            <!-- if sidewide_notification available then show them  -->
            @if(getStoreSettings('global_notification'))
            <div class="alert alert-warning">
                <?= 
                    __transliterate('misc_setting', null, 'global_notification', getStoreSettings('global_notification') );
                ?>
            </div>
            @endif
            <!-- if sidewide_notification available then show them  -->
            <!-- content -->
            
                @if(session('success'))
                    <!--  success message when email sent  -->
                    <div class="alert alert-success">
                        <?= session('message') ?>
                    </div>
                    <!--  /success message when email sent  -->
                @endif 
            
                @if (session('error'))
                    <!--  error message when user not successfully activated  -->
                    <div class="alert alert-danger alert-dismissible">
                        <?=  session('message')  ?>
                    </div>
                    <!--  /error message when user not successfully activated  -->
                @endif

                @if (session('notify'))
                    <!--  error message when user not successfully activated  -->
                    <div ng-init="publicCtrl.showCurrencyNotification('<?=  session('currencyMessage')  ?>')"></div>
                    <!--  /error message when user not successfully activated  -->
                @endif

                <!-- animated slideInRight -->
                <div class="lw-sub-component-page" id="elementtoScrollToID">
                    @include('includes.breadcrumb') 

                    @if(isset($pageRequested))
                        <?php echo $pageRequested ; ?>
                    @endif
                </div>
 
            </div>
            <!-- /content -->
        </div>
    </div>
    <!-- /section -->


   <!-- back-top-link-block -->
    <div>
       <a href="#top" class="btn lw-btn btn-primary btn-sm lw-go-top-btn" id="lw-go-top-btn" title="<?=  __tr('Go to top')  ?>" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
           <i class="fa fa-arrow-up fa-2x lw-top-arrow-icon"></i>
       </a>
    </div>

    @if (!isActiveRoute('compare_product') == true )
        <div ng-if="publicCtrl.totalProductCompare > 0" role="group" class="btn-group lw-compare-btn" ng-cloak>
            <a href="<?=  compareProduct() ?>" class="lw-btn btn btn-outline-primary">
                <?= __tr('Compare') ?>
                <span class="badge badge-light" ng-bind="publicCtrl.totalProductCompare"></span>
            </a>
            <a href ng-click="publicCtrl.removeAllProductInComapre()" class="lw-btn btn btn-outline-primary" title="<?= __tr('Remove All Products') ?>">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
        </div>
    @endif
   
    <!--  footer template -->
    <div class="lw-footer"  ng-cloak>
        @if(getStoreSettings('addtional_page_end_content'))
            <div class="clearfix">
                <div class="col-lg-12">
                    <?= getStoreSettings('addtional_page_end_content') ?>
                </div>
            </div>
        @endif
        @if(getStoreSettings('public_footer_template'))
            <?= 
            __transliterate('footer_setting', null, 'public_footer_template', getStoreSettings('public_footer_template') );
             ?>
        @else
            @include('includes.public-footer')
        @endif
        <div class="lw-sub-footer">
            <div class="col-12">
               <span class="float-left lw-copyright-text">
                    <?= __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ). ' - ' ?> &copy; <?= date("Y") ?>
                </span>
                 <span style="display: none" class="pull-right">
                    @if(getStoreSettings('social_twitter'))
                        <a class="btn btn-link" title="Twitter" target="_blank" href="https://twitter.com/<?= getStoreSettings('social_twitter') ?>"> <i class="fa fa-twitter"></i></a>
                    @endif

                    @if(getStoreSettings('social_facebook'))
                        <a class="btn btn-link" title="Facebook" target="_blank" href="https://www.facebook.com/<?= getStoreSettings('social_facebook') ?>"><i class="fa fa-facebook"></i></a>
                    @endif

                    @if (getStoreSettings('privacy_policy'))
                        <!<span>
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
    <!--  footer template -->

    @include('includes.javascript-content')
    @include('includes.form-template')

    <?= __yesset([
        'dist/libs/bootstrap/js/bootstrap.bundle.min.js',
        'dist/libs/bootstrap-offcanvas-sidebar/js/bootstrap-offcanvas-sidebar.min.js',
        'dist/libs/headroom/headroom.min.js',
        'dist/libs/headroom/jQuery.headroom.min.js',
        'dist/js/vendorlibs-smartmenus.js',
        'dist/js/vendorlibs-jquery-typeahead.js',
        'dist/libs/jquery-colorbox/jquery.colorbox-min.js',
        'dist/js/vendorlibs-datatable.js',
        'dist/js/vendorlibs-angular.js',
        'dist/js/vendorlibs-ngdialog.js',
        'dist/js/vendorlibs-selectize.js',
        'dist/js/vendorlibs-switchery.js',
        'dist/js/vendorlibs-other-common.js',
        'dist/js/vendorlibs-public.js',
        'dist/js/ngware-app*.js',
        'dist/js/app-support-app*.js',
    ]) ?>
    @stack('vendorScripts')
    <?= __yesset([
        'dist/js/public-app*.js'
    ]) ?>

	@if(getStoreSettings('enable_recaptcha')) 
	<script defer async src='https://www.google.com/recaptcha/api.js'></script>
	@endif
	
    @stack('appScripts')

    <!-- got to top button script -->
    <script>
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("lw-go-top-btn").style.display = "block";
            } else {
                document.getElementById("lw-go-top-btn").style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <!-- / got to top button script -->

    <!-- container -->
    <script type="text/javascript">
        $(document).ready(function () {
            $htmlDoc = $('html');
            // initialise
            $("div#lwStoreHeader").headroom({
                "offset": 100,
                "tolerance": 5,
                // callback when pinned, `this` is headroom object
                onPin : function() {
                    $htmlDoc.removeClass('lw-headroom-unpinned').addClass('lw-headroom-pinned');
                },
                // callback when unpinned, `this` is headroom object
                onUnpin : function() {
                    $htmlDoc.addClass('lw-headroom-unpinned').removeClass('lw-headroom-pinned');
                },
                // callback when above offset, `this` is headroom object
                onTop : function() {
                },
                // callback when below offset, `this` is headroom object
                onNotTop : function() {
                },
                // callback when at bottom of page, `this` is headroom object
                onBottom : function() {
                },
                // callback when moving away from bottom of page, `this` is headroom object
                onNotBottom : function() {
                }
            }); 

             $('.lw-sm-menu-instance').smartmenus({
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
        });

        $( window ).resize(windowResized);
        function windowResized() {
            $('.lw-main-content-container').css('padding-top', $('div#lwStoreHeader').height());
        }

        setTimeout(function() {
            windowResized();
        }, 1000);

        windowResized();

        <?php  $sliderConfiguration = config('__tech.slider_configurations'); ?>
            @if (!__isEmpty($sliderConfiguration)) 
                @foreach ($sliderConfiguration as $sliderKey => $slider)  
                   $('.lw-main-product-slider-<?= $sliderKey ?>').owlCarousel({
                        nav: true,
                        // autoHeight:true,
                        stagePadding:0,
                        // animateOut: 'flipOutX',
                        // animateIn: 'flipInX',
                        checkVisible:false,
                        mouseDrag: true,
                        dots:false,
                        autoplay: '<?= $slider['auto_play'] ?>',
                        autoplayTimeout: '<?= $slider['autoPlayTimeout'] ?>',
                        // paginationSpeed: 1,
                        items: 1,
                        // checkVisible:true,
                        loop : true,
                        onInitialized: function(event) {
                            $('.lw-main-product-slider-<?= $sliderKey ?>').css('display', 'block');
                            var $sliderElement = $('.lw-main-product-slider-<?= $sliderKey ?> .owl-item');
                            
                            _.forEach($sliderElement, function(item) {
                                var bgColorValue = $(item).children().data('bg-color');
                                $(item).css('background', '#'+bgColorValue);
                           });
                        }
                    });
                @endforeach
            @endif
   </script> 
</div>
</body>
</html>

