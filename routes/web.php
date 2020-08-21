<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| LivelyCart PRO Routes 
|--------------------------------------------------------------------------
*/

Route::group([
        'namespace' => '\App\Yantrana\Components',
    ], function () {

    // verify installation
    Route::get('/app-configuration', [
        'as'    => 'installation.verify',
        'uses'  => 'Installation\InstallationVerification@verify'
    ]);

Route::get('/home-igniter', [
    'as' => 'public.app',
    'uses' => '__Igniter@index',
]);

Route::get('/change-language/{localeID}', [
    'as' => 'locale.change',
    'uses' => 'Home\Controllers\HomeController@changeLocale',
]);

Route::get('/change-currency/{currency}', [
    'as' => 'currency.change',
    'uses' => 'Home\Controllers\HomeController@changeCurrency',
]);

// Custom CSS Style View Request
Route::get('/css-styles.css', [
    'as'    => 'css_style',
    'uses'  => 'Home\Controllers\HomeController@cssStyle'
]);

// Change Theme Color
Route::get('/{colorName}/change-theme-color', [
    'as'    => 'theme_color',
    'uses'  => 'Home\Controllers\HomeController@changeThemeColor'
]);


// landing Page
Route::get('/landing-page', [
    'as' => 'landing_page',
    'uses' => 'Home\Controllers\HomeController@prepareLandingPage',
]);

Route::get('/error-not-found', [
    'as' => 'error.public-not-found',
    'uses' => '__Igniter@errorNotFound',
]);

// fetch products
Route::get('/products', [
    'as' => 'public.app',
    'uses' => 'ProductController@all',
]);

// fetch products
Route::get('/compare-product', [
    'as' => 'public.product.compare',
    'uses' => 'ProductController@productCompare',
]);

Route::get('/', [
    'as' => 'home.page',
    'uses' => 'Home\Controllers\HomeController@home',
]);

//404 error-page
Route::get('/error-page', [
    'as' => 'error.page',
    'uses' => 'Home\Controllers\HomeController@errorPage',
]);

Route::get('/home', [
    'as' => 'home',
    'uses' => '__Igniter@index',
]);

// captcha generate url
Route::get('/generate-captcha', [
    'as' => 'security.captcha',
    function () {

        // ob_clean();
        
        return Captcha::create('flat');
    },
]);


/*
  Dashboard Component Routes End Here
  ------------------------------------------------------------------- */

// get all application template using this route
Route::get('/get-template/{viewName}', [
    'as' => 'template.get',
    'uses' => '__Igniter@getTemplate',
]);

// get all application template using this route
Route::get('/email-view/{viewName}', [
    'as' => 'template.email',
    function ($viewName) {
        //return __loadView($viewName);
        return view('emails.index', [
                'emailsTemplate' => $viewName,
            ]);
    },
]);

/*
  Product Components Public Section Related Routes
  ------------------------------------------------------------------ */

Route::group([
        'namespace' => 'Product\Controllers',
    ], function () {
    // fetch products
    Route::get('/products', [
        'as' => 'products',
        'uses' => 'ProductController@all',
    ]);

    // fetch products
    Route::get('/compare-product', [
        'as' => 'compare_product',
        'uses' => 'ProductController@productCompare',
    ]);

    // fetch products
    Route::get('/products/featured', [
        'as' => 'products.featured',
        'uses' => 'ProductController@all',
    ]);

    // fetch category products
    Route::get('/products/{categoryID}/{categoryName?}', [
        'as' => 'products_by_category',
        'uses' => 'ProductController@all',
    ])->where('categoryID', '[0-9]+');

    // Search Products
    Route::get('/products/search', [
        'as' => 'product.search',
        'uses' => 'ProductController@search',
    ]);

     // Search Products
    Route::get('/products/{searchQuery}/search-suggest-list', [
        'as' => 'product.suggest.search',
        'uses' => 'ProductController@searchSuggestList',
    ]);

    // filter supported data
    Route::get('/product/filter/{search_term?}', [
        'as' => 'product.filter',
        'uses' => 'ProductController@filterAll',
    ]);

    // brand related filter dialog
    Route::get('/product/filter/brand/{brandID}', [
        'as' => 'product.filter.brand',
        'uses' => 'ProductController@filterBrandRelatedProduct',
    ]);

    // get quick view support data
    Route::get('/product/{productID}/quick-view-dialog/{categoryID?}', [
        'as' => 'product.quick.view.details.support_data',
        'uses' => 'ProductController@quickViewDetailsSupportData',
    ])->where('productID', '[0-9]+');

    // get product details
    Route::get('/product/{productID}/details-support-data', [
        'as' => 'product.details.support_data',
        'uses' => 'ProductController@detailsSupportData',
    ])->where('productID', '[0-9]+');

        Route::get('/product-details/{productID}/{productName?}/{categoryID?}', [
        'as' => 'product.details',
        'uses' => 'ProductController@details',
    ])->where('productID', '[0-9]+');

        Route::get('/product-reviews/{productID}/{pageType?}', [
        'as' => 'product.user.all_review',
        'uses' => 'ProductController@productReviews',
    ]);

        // list of product review pagination data
        Route::get('/reviews-pagination-list/{productId}', [
        'as' => 'product.user.all_review.paginate_data',
        'uses' => 'ProductController@productReviewsPaginateData',
    ]);

        Route::get('/product-questions/{productID}/{pageType?}', [
        'as' => 'product.user.product_question',
        'uses' => 'ProductController@productQuestions',
    ]);

        // list of product review pagination data
        Route::get('/product-question-pagination-list/{productId}', [
        'as'   => 'product.user.question.paginate_data',
        'uses' => 'ProductController@productQuestionPaginateData',
    ]);

        Route::get('/product/{brandID}/{brandName?}/brand', [
        'as' => 'product.related.by.brand',
        'uses' => 'ProductController@brandRelatedProducts',
    ])->where('brandID', '[0-9]+');

    // get list product ratings & reviews
    Route::get('product-ratings/{productId}/review-support-data', [
        'as'    => 'product.rating_review.get_support_data',
        'uses'  => 'ProductController@getRatingAndReviewSupportData'
    ]);

    // Get My Wish details
    Route::get('/my-wishlist-details', [
        'as'    => 'product.my_wishlist.details',
        'uses'  => 'ProductController@myWishListDetails'
    ]);

    // Get My Wish details
    Route::get('/my-rating-details', [
        'as'    => 'product.my_rating.details',
        'uses'  => 'ProductController@myRatingListDetails'
    ]);

    // Search Products
    Route::get('/products/{searchQuery}/search-terms-list', [
        'as' => 'product.search.term',
        'uses' => 'ProductController@prepareSearchData',
    ]);

});

Route::group([
        'namespace' => 'Brand\Controllers',
    ], function () {
        Route::get('/brands', [
        'as' => 'fetch.brands',
        'uses' => 'BrandController@fetchActiveRecord',
    ]);
    });

/*
  Pages Components Public Section Related Routes
  ----------------------------------------------------------------------- */

Route::group([
        'namespace' => 'Pages\Controllers',
        'prefix' => 'page',
    ], function () {
        // get details of page
    Route::get('{pageID}/{pageName?}', [
        'as' => 'display.page.details',
        'uses' => 'PagesController@displayPageDetails',
    ])->where('pageID', '[0-9]+');
    });

/*
  ShoppingCart Components Public Section Related Routes
  ----------------------------------------------------------------------- */

Route::group([
    'namespace' => 'ShoppingCart\Controllers',
    'prefix' => 'shopping-cart', ], function () {
        // cart view
    Route::get('', [
        'as' => 'cart.view',
        'uses' => 'ShoppingCartController@cartView',
    ]);

    // add new item in cart
    Route::post('/{productID}/add-item', [
        'as' => 'cart.add.item',
        'uses' => 'ShoppingCartController@addItem',
    ])->where('productID', '[0-9]+');

    // get cart
    Route::get('/getData', [
        'as' => 'cart.get.data',
        'uses' => 'ShoppingCartController@getCartDetails',
    ]);

    // update cart quantity
    Route::post('/qty-update/{itemID}', [
        'as' => 'cart.update.qty',
        'uses' => 'ShoppingCartController@updateItemQty',
    ]);

    // refresh product from cart
    Route::post('/refresh-cart-product', [
        'as' => 'cart.refresh.product',
        'uses' => 'ShoppingCartController@refreshCartProduct',
    ]);

    // update cart quantity
    Route::post('/check-product-cart/{productID}', [
        'as' => 'cart.product.qty.update',
        'uses' => 'ShoppingCartController@checkProductCart',
    ])->where('productID', '[0-9]+');

    // remove item from cart
    Route::post('/remove-item/{itemID}', [
        'as' => 'cart.remove.item',
        'uses' => 'ShoppingCartController@removeItem',
    ]);

    // remove item from cart
    Route::post('/remove-invalid-item', [
        'as' => 'cart.remove.invalid.item',
        'uses' => 'ShoppingCartController@removeInvalidItems',
    ]);

    // remove  all items form to the cart
    Route::post('/remove-item', [
        'as' => 'cart.remove.all.items',
        'uses' => 'ShoppingCartController@removeAllItems',
    ]);

    // get cart btn string
    Route::get('/cart-string', [
        'as' => 'cart.update.cart.string',
        'uses' => 'ShoppingCartController@updateCartBtnString',
    ]);
    }); 

/*
  UploadManager Components Related Routes
  ----------------------------------------------------------------------- */

Route::group([
        'namespace' => 'UploadManager\Controllers',
        'prefix' => 'upload-manager',
    ], function () {
        // files
    Route::get('/files', [
        'as' => 'upload_manager.files',
        'uses' => 'UploadManagerController@files',
    ]);

    // upload
    Route::post('/upload', [
        'as' => 'upload_manager.upload',
        'uses' => 'UploadManagerController@upload',
    ]);

    // delete
    Route::post('/{fileName}/delete', [
        'as' => 'upload_manager.delete',
        'uses' => 'UploadManagerController@delete',
    ]);
    });

    /*
      User Components Public Section Related Routes
      ----------------------------------------------------------------------- */
Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {

    // contact form
    Route::get('/contact', [
        'as' => 'get.user.contact',
        'uses' => 'UserController@contact',
    ]);

    // process contact form
    Route::post('/post-contact', [
        'as' => 'user.contact.process',
        'uses' => 'UserController@contactProcess',
    ]);

    // privacy policy
    Route::get('/privacy-policy', [
        'as' => 'privacy.policy',
        'uses' => 'UserController@privacyPolicy',
    ]);

    // terms & conditions
    Route::get('/terms-conditions', [
        'as' => 'terms.conditions',
        'uses' => 'UserController@termsAndConditions',
    ]);
        });

/*
  Guest Auth Routes
  -------------------------------------------------------------------------- */

Route::group(['middleware' => 'guest'], function () {
    /*
      User Components Public Section Related Routes
      ----------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {
            // login
        Route::get('/login', [
            'as' => 'user.login',
            'uses' => 'UserController@login',
        ]);

        // login process
        Route::post('/login', [
            'as' => 'user.login.process',
            'uses' => 'UserController@loginProcess',
        ]);

        // register
        Route::get('/register', [
            'as' => 'user.register',
            'uses' => 'UserController@register',
        ]);

        // register support Data
        Route::get('/user-register-supportData', [
            'as' => 'user.register.supportData',
            'uses' => 'UserController@userRegisterSupportData',
        ]);

        // register process
        Route::post('/register', [
            'as' => 'user.register.process',
            'uses' => 'UserController@registerProcess',
        ]);

        // register success
        Route::get('/register/success', [
            'as' => 'user.register.success',
            'uses' => 'UserController@registerSuccess',
        ]);

        // account activation
        Route::get('/{userID}/{activationKey}/account-activation', [
            'as' => 'user.account.activation',
            'uses' => 'UserController@accountActivation',
        ])->where('userID', '[0-9]+');

        // login attempts
        Route::get('/login-attempts', [
            'as' => 'user.login.attempts',
            'uses' => 'UserController@loginAttempts',
        ]);

        // forgot password
        Route::get('/forgot-password', [
            'as' => 'user.forgot_password',
            'uses' => 'UserController@forgotPassword',
        ]);

        // forgot password
        Route::post('/forgot-password', [
            'as' => 'user.forgot_password.process',
            'uses' => 'UserController@forgotPasswordProcess',
        ]);

        // forgot password success
        Route::get('/forgot-password-success', [
            'as' => 'user.forgot_password.success',
            'uses' => 'UserController@forgotPasswordSuccess',
        ]);

        // reset password
        Route::get('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password',
            'uses' => 'UserController@restPassword',
        ]);

        // reset password process
        Route::post('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password.process',
            'uses' => 'UserController@restPasswordProcess',
        ]);

        // resend activation email
        Route::get('/resend-activation-email', [
            'as' => 'user.resend.activation.email.fetch.view',
            'uses' => 'UserController@resendActivationEmail',
        ]);

        // resend activation email success
        Route::get('/resend-activation-email-success', [
            'as' => 'user.resend_activation_email.success',
            'uses' => 'UserController@resendActivationEmailSuccess',
        ]);

        /*new user activation process*/
        Route::post('/resend-activation-email', [
            'as' => 'user.resend.activation.email.proccess',
            'uses' => 'UserController@resendActivationEmailProccess',
        ]);
    });

    Route::group([
        'namespace' => 'User\Controllers',
        'prefix'    => 'user/social-login',
    ], function () {

        // social user login
        Route::get('/request/{provider}', [
            'as'   => 'social.user.login',
            'uses' => 'SocialAccessController@redirectToProvider',
        ]);

        // social user login callback
        Route::get('/response/{provider}', [
            'as'   => 'social.user.login.callback',
            'uses' => 'SocialAccessController@handleProviderCallback',
        ]);
    });
});

/*
  User Components Public Section Related Routes Start
  ----------------------------------------------------------------------- */
Route::group([
        'namespace' => 'User\Controllers',
        'prefix' => 'user',
    ], function () {
        // logout
    Route::get('/logout', [
        'as' => 'user.logout',
        'uses' => 'UserController@logout',
    ]);
});
/*User Components Public Section Related Routes End*/

    /*
    Start Product Component public Section Related Routes
    ------------------------------------------------------------------- */

    Route::group([
        'namespace'     => 'Product\Controllers',
    ], function () {

        // notify user to email
        Route::post('/{productId}/notify-user/add', [
            'as'    => 'product.notify_user.add',
            'uses'  => 'ProductController@addNotifyUser'
        ]);

        // Add to wishlist
        Route::post('{productId}/add-to-wishlist', [
            'as'    => 'product.wishlist.add_process',
            'uses'  => 'ProductController@addToWishlistProcess'
        ]);

        // Get My Wish list view
        Route::get('/my-wishlist', [
            'as'    => 'product.my_wishlist.list',
            'uses'  => 'ProductController@myWishListView'
        ]);

        // Add to wishlist
        Route::post('{productId}/remove-from-wishlist', [
            'as'    => 'product.wishlist.remove_process',
            'uses'  => 'ProductController@removeFromWishlistProcess'
        ]);

         // add ratting
        Route::post('/product-ratings/{productId}/rating/{rate}/add', [
            'as'    => 'product.rating.add',
            'uses'  => 'ProductController@addRating'
        ]);

        // get product compare data
        Route::get('/get-product-compare-count', [
            'as'    => 'product.compare.read.product_count',
            'uses'  => 'ProductController@prepareProductCompareCount'
        ]);

        // get product compare data
        Route::get('/get-product-compare-data', [
            'as'    => 'product.compare.read.data',
            'uses'  => 'ProductController@prepareProductCompareData'
        ]);

        // add compare
        Route::post('/add-product-compare/{productId}/add', [
            'as'    => 'product.compare.add',
            'uses'  => 'ProductController@addCompare'
        ]);

        // remove compare product
        Route::post('/add-product-compare/{productId}/remove-product', [
            'as'    => 'product.compare.remove',
            'uses'  => 'ProductController@removeCompareProduct'
        ]);

        // remove All compare product
        Route::post('/remove-all-compare-product/remove-all-product', [
            'as'    => 'product.compare.remove_all',
            'uses'  => 'ProductController@removeAllCompareProduct'
        ]);

        // My ratings view
        Route::get('/ratings', [
            'as'    => 'product.my-rating.view',
            'uses'  => 'ProductController@myRatingView'
        ]);

        // My ratings list
        Route::get('/ratings-list', [
            'as'    => 'product.my-rating.list',
            'uses'  => 'ProductController@myRatingList'
        ]);

        // Get review support data
        Route::get('/product-review/{productId}/get-supportData', [
            'as'   => 'product.review.support.data',
            'uses' => 'ProductController@getReviewSupportData',
        ]);

        // process of add to review on product
        Route::post('/product-review/{productId}/add', [
            'as'   => 'product.process.add.review',
            'uses' => 'ProductController@addReview',
        ]);

        // My Ratings
        Route::get('/my-ratings', [
            'as'   => 'product.ratings.read.view',
            'uses' => 'ProductController@myRatings',
        ]);

        // process of add to faqs on product
        Route::post('{productId}/product-add-question/{type}', [
            'as'   => 'product.process.add.faqs',
            'uses' => 'ProductController@addProductFaqs',
        ]);

    });
    /*
      End Product Component public Section Related Routes
    ------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'ShoppingCart\Controllers',
        ], function () {
            Route::group(['prefix' => 'order-process',
        ], function () {
            Route::get('', [
                'as' => 'order.summary.view',
                'uses' => 'OrderController@displayOrderSummary',
            ]);

            // get order details
            Route::get('/details/{addressID}/{addressID1}/{couponCode}/{shippingCountryId}/{billingCountryId}/{useAsBilling}/{shippingMethod}', [
                'as' => 'order.summary.details',
                'uses' => 'OrderController@cartOrderDetails',
            ]);

            // coupon apply
            Route::post('/apply-coupon', [
                'as' => 'order.coupon.apply',
                'uses' => 'OrderController@applyCouponProcess',
            ]);

            Route::get('/{email}/{couponId}/check-user-email', [
                'as' => 'order.check.user_email',
                'uses' => 'OrderController@checkUserEmail',
            ]);

            // order submit
            Route::post('/submit', [
                'as' => 'order.process',
                'uses' => 'OrderController@orderProcess',
            ]);

            // iyzipay Renewal Process
            Route::post('/{encryptOrderID}/iyzipaypayment-process', [
                'as'    => 'order.iyzipay.payment_process',
                'uses'  => 'OrderController@processIyzipayPayment'
            ]);

            Route::get('/paypal-checkout/{orderUID}', [
                'as' => 'order.paypal.checkout',
                'uses' => 'OrderController@preparePaypalOrder',
            ]);

            Route::get('/paytm-checkout/{orderUID}', [
                'as' => 'order.paytm.checkout',
                'uses' => 'OrderController@preparePaytmOrder',
            ]);

            Route::post('/{encryptOrderID}/paytm-callback', [
                'as' => 'order.paytm.callback_url',
                'uses' => 'OrderController@paytmCallback',
            ]);

            Route::get('/instamojo-checkout/{orderUID}', [
                'as' => 'order.instamojo.checkout',
                'uses' => 'OrderController@prepareInstamojoOrder',
            ]);

            Route::get('/{encryptOrderID}/instamojo-callback', [
                'as' => 'order.instamojo.callback_url',
                'uses' => 'OrderController@instamojoCallback',
            ]);

            Route::post('/stripe-checkout', [
                'as' => 'order.stripe.checkout',
                'uses' => 'OrderController@stripeCheckout',
            ]);

            Route::post('/razorpay-checkout', [
                'as' => 'order.razorpay.checkout',
                'uses' => 'OrderController@razorpayCheckout',
            ]);

            Route::post('/paystack-checkout', [
                'as' => 'order.paystack.checkout',
                'uses' => 'OrderController@paystackCheckout',
            ]);

            Route::get('/guest-order-success', [
                'as' => 'order.guest_order_success',
                'uses' => 'OrderController@orderSuccess',
            ]);
        });

        /*
          Order Components Public Section Related Routes
        ----------------------------------------------------------------------- */

        Route::group(['prefix' => '/orders'], function () {

            // my order list view
            Route::get('', [
                'as' => 'cart.order.list',
                'uses' => 'OrderController@userOrdersList',
            ]);

             // get orders related to users
            Route::get('/get-list-with-status/{status}', [
                'as' => 'cart.get.orders.data',
                'uses' => 'OrderController@index',
            ])->where('status', '[0-9]+');

            // my order view detail page
            Route::get('/{orderUID}/details', [
                'as' => 'my_order.details',
                'uses' => 'OrderController@orderDetail',
            ]);

            // shipping address change in order
            Route::get('/address/{addressID}/details', [
                'as' => 'change_address.in.order',
                'uses' => 'OrderController@changeAddressInOrder',
            ]);

            // order log dialog
            Route::get('/get/order-log-details/{orderID}', [
                'as' => 'order.log.dialog',
                'uses' => 'OrderController@userLogDetails',
            ])->where('orderID', '[0-9]+');

            // invoice download for user
            Route::get('/{orderID}/invoice-download', [
                'as' => 'order.user.invoice.download',
                'uses' => 'OrderController@invoiceDownload',
            ]);

            // Order cancellation request
            Route::post('/{orderID}/order-cancellation', [
                'as' => 'order.user.cancellation_process',
                'uses' => 'OrderController@orderCancel',
            ]);
        });
    });

/*
  After Authentication Accessible Routes
  -------------------------------------------------------------------------- */

Route::group(['middleware' => 'authority.checkpost'], function () {

    /*
    System Update Routes
    ----------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Installation',
        'prefix' => 'app-selfupdate',
    ], function () {
            
            Route::get('/', [
                'as'    => 'installation.version.index',
                'uses'  => 'AppUpdate@index'
            ]);

            Route::post('/check', [
                'as'    => 'installation.version.check',
                'uses'  => 'AppUpdate@check'
            ]);

            Route::post('/download-update', [
                'as'    => 'installation.version.update.download',
                'uses'  => 'AppUpdate@downloadUpdate'
            ]);
            
            Route::post('/perform-update', [
                'as'    => 'installation.version.update.perform',
                'uses'  => 'AppUpdate@performUpdate'
            ]);

            Route::post('/store-product-purchase', [
                'as'    => 'installation.version.create.registration',
                'uses'  => 'AppUpdate@storeRegistration'
            ]);
    });

    /*
    Start User Components Manage Section Related Routes
    ----------------------------------------------------------------------- */
    Route::group([
        'prefix' => 'manage',
    ], function () {

        Route::get('/', [
            'as' => 'manage.app',
            'uses' => '__Igniter@manageIndex',
        ]);
    });
    

	Route::get('/main-home', [
	    'as'    => 'main_home',
	    'uses'  => '__Igniter@index'
	]);

     /*
      File Manager Related Routes Start
      ------------------------------------------------------------------ */

    Route::get('/file-manager', [
        'as'    => 'file_manager',
        'uses'  => '__Igniter@fileManagerIndex'
    ]);

    Route::group([
        'namespace'     => 'FileManager\Controllers',
        'prefix'        => '/file-manager'
    ], function () {
        // upload common files
        Route::post('/file-upload', [
            'as'    => 'file_manager.upload',
            'uses'  => 'FileManagerController@upload'
        ]);

         // uploaded common files
        Route::get('/files', [
            'as'    => 'file_manager.files',
            'uses'  => 'FileManagerController@files'
        ]);

        // delete file
        Route::post('/file-delete', [
            'as'    => 'file_manager.file.delete',
            'uses'  => 'FileManagerController@deleteFile'
        ]);

        // download file
        Route::get('/file-download', [
            'as'    => 'file_manager.file.download',
            'uses'  => 'FileManagerController@downloadFile'
        ]);

        // add folder
        Route::post('/folder-add', [
            'as'    => 'file_manager.folder.add',
            'uses'  => 'FileManagerController@addFolder'
        ]);

        // delete folder
        Route::post('/folder-delete', [
            'as'    => 'file_manager.folder.delete',
            'uses'  => 'FileManagerController@deleteFolder'
        ]);

        // rename folder
        Route::post('/folder-rename', [
            'as'    => 'file_manager.folder.rename',
            'uses'  => 'FileManagerController@renameFolder'
        ]);

        // rename file
        Route::post('/file-rename', [
            'as'    => 'file_manager.file.rename',
            'uses'  => 'FileManagerController@renameFile'
        ]);
    });

    /*
      Specification Preset Component Routes Start from here
      ------------------------------------------------------------------- */
    Route::group([ 
        'namespace'     => 'Transliterate\Controllers',
        'prefix'        => '/transliterate' 
    ], function() {
        // Get transliterate support data
        Route::get('/{entityType}/{entityId}/{entityKey}/support-data', [
            'as'    => 'manage.transliterate.read.support_data',
            'uses'  => 'TransliterateController@getAddSupportData'
        ]);

        // transliterate update process
        Route::post('/{entityType}/{entityId}/{entityKey}/transliterate-update-process', [
            'as'    => 'manage.transliterate.write.update',
            'uses'  => 'TransliterateController@processTransliterateUpdate'
        ]);

        // Get transliterate support data
        Route::get('/{entityType}/{entityId}/{entityKey}/{language}/support-data', [
            'as'    => 'manage.transliterate.read.translate_data',
            'uses'  => 'TransliterateController@getTranslateData'
        ]);

        // Get original content
        Route::post('/get-string-for-translation', [
            'as'    => 'manage.transliterate.write.get_original_text',
            'uses'  => 'TransliterateController@getOriginalText'
        ]);
    });

    /*
      Specification Preset Component Routes Start from here
      ------------------------------------------------------------------- */
    Route::group([ 
        'namespace'     => 'SpecificationsPreset\Controllers',
        'prefix'        => '/specifications-Preset' 
    ], function() {

         
        // Specification Preset list
        Route::get('/list', [
            'as'    => 'manage.specification_preset.read.list',
            'uses'  => 'SpecificationController@specificationsPresetList'
        ]);

        // Specification Preset create process
        Route::post('/preset-add-process', [
            'as'    => 'manage.specification_preset.write.add',
            'uses'  => 'SpecificationController@processPresetCreate'
        ]);

        // Specification Preset Edit Data process
        Route::get('/{presetId}/get-update-data', [
            'as' => 'manage.specification_preset.read.editSupportadd',
            'uses' => 'SpecificationController@editSupportData',
        ]);

        // Specification Preset update process
        Route::post('/{presetId}/preset-update-process', [
            'as'    => 'manage.specification_preset.write.update',
            'uses'  => 'SpecificationController@editPreset'
        ]);

        // Specification delete process
        Route::post('/{specificationId}/delete-specification', [
            'as'    => 'manage.specification_preset.specification.delete',
            'uses'  => 'SpecificationController@processDeleteSpecification'
        ]);

        // Specification Preset delete process
        Route::post('/{presetId}/delete-specification-preset', [
            'as'    => 'manage.specification_preset.write.delete',
            'uses'  => 'SpecificationController@processDeletePreset'
        ]);

        // Specification Preset label list
        Route::get('/{presetId}/preset-label-list', [
            'as'    => 'manage.specification_preset.read.presetLabel',
            'uses'  => 'SpecificationController@getPresetLabels'
        ]);

    });
    /*Specification Preset Component Routes End from here*/

    /*
      Shipping Type Component Routes Start from here
      ------------------------------------------------------------------- */
    Route::group([ 
        'namespace'     => 'ShippingType\Controllers',
        'prefix'        => '/shipping-type' 
    ], function() {

        // Shipping Type list
        Route::get('/list', [
            'as'    => 'manage.shipping_type.read.list',
            'uses'  => 'ShippingTypeController@prepareShippingTypeList'
        ]);

        // Shipping Type create process
        Route::post('/shipping-type-add', [
            'as'    => 'manage.shipping_type.write.create',
            'uses'  => 'ShippingTypeController@processShippingTypeCreate'
        ]);

        // Shipping Type get the data
        Route::get('/{shippingTypeId}/shipping-type-update-data', [
            'as'    => 'manage.shipping_type.read.update.data',
            'uses'  => 'ShippingTypeController@updateShippingTypeData'
        ]);

        // Shipping Type update process
        Route::post('/{shippingTypeId}/shipping-type-update-process', [
            'as'    => 'manage.shipping_type.write.update',
            'uses'  => 'ShippingTypeController@processShippingTypeUpdate'
        ]);

        // Shipping Type delete process
        Route::post('/{shippingTypeId}/shipping-type-delete', [
            'as'    => 'manage.shipping_type.write.delete',
            'uses'  => 'ShippingTypeController@processDeleteShippingType'
        ]);

    });
    /*Shipping Type Component Routes End from here*/

    /*
      Dashboard Component Routes Start from here
      ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Dashboard\Controllers',
        'prefix'    => 'dashboard',
    ], function () {

        Route::get('/{startDate}/{endDate}/{durationType}/get-support-data', [
            'as'    => 'manage.dashboard.count_support_data',
            'uses'  => 'DashboardController@dashboardSupportData'
        ]);

    });

    /*
  User Components Public Section Related Routes Start
  ----------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'User\Controllers',
        'prefix' => 'user',
    ], function () {
        // profile
        Route::get('/profile', [
            'as' => 'user.profile',
            'uses' => 'UserController@profile',
        ]);

        // change password
        Route::get('/change-password', [
            'as' => 'user.change_password',
            'uses' => 'UserController@changePassword',
        ]);

        // change email
        Route::get('/change-email', [
            'as' => 'user.change_email',
            'uses' => 'UserController@changeEmail',
        ]);

        // change email support data
        Route::get('/change-email-support-data', [
            'as' => 'user.change_email.support_data',
            'uses' => 'UserController@getChangeEmailSupportData',
        ]);

        // new email activation
        Route::get('/{userID}/{activationKey}/new-email-activation', [
            'as' => 'user.new_email.activation',
            'uses' => 'UserController@newEmailActivation',
        ]);

        // profile details
        Route::get('/profile-details', [
            'as' => 'user.profile.details',
            'uses' => 'UserController@profileDetails',
        ]);

        // process contact form
        Route::get('/{userId}/get-user-info', [
            'as'   => 'user.get.info',
            'uses' => 'UserController@getInfo',
        ]);

         // process contact form
        Route::post('/process-contact', [
            'as'   => 'manage.user.contact.process',
            'uses' => 'UserController@userContactProcess',
        ]);

        // address list view
        Route::get('/addresses', [
            'as' => 'user.address.list',
            'uses' => 'AddressController@addressList',
        ]);

        // get list of address
        Route::get('/address/get', [
            'as' => 'user.get.addresses',
            'uses' => 'AddressController@getAddresses',
        ]);

        // edit user address
        Route::get('/address/fetch-edit-support-data/{addressID}', [
            'as' => 'user.fetch.address.support.data',
            'uses' => 'AddressController@editSupportData',
        ])->where('addressID', '[0-9]+');

         // get list of address for order summary page
        Route::get('/fetch-addresses', [
            'as' => 'get.addresses.for.order',
            'uses' => 'AddressController@getAddressForOrder',
        ]);

        // make address primary
        Route::post('/address/{addressID}/primary', [
            'as' => 'user.get primary.address',
            'uses' => 'AddressController@makePrimaryAddress',
        ])->where('addressID', '[0-9]+');
    
        // address process
        Route::post('/address/add', [
            'as' => 'user.address.process',
            'uses' => 'AddressController@addProcess',
        ]);

        // update user address
        Route::post('/address/update/{addressID}', [
            'as' => 'user.address.update',
            'uses' => 'AddressController@update',
        ])->where('addressID', '[0-9]+');

        // delete address
        Route::post('/address/delete/{addressID}', [
            'as' => 'user.address.delete',
            'uses' => 'AddressController@delete',
        ])->where('addressID', '[0-9]+');

        // change email process
        Route::post('/change-email', [
            'as' => 'user.change_email.process',
            'uses' => 'UserController@changeEmailProcess',
        ]);

        // change password process
        Route::post('/change-password', [
            'as' => 'user.change_password.process',
            'uses' => 'UserController@changePasswordProcess',
        ]);

        // profile update
        Route::get('/profile/edit', [
            'as' => 'user.profile.update',
            'uses' => 'UserController@updateProfile',
        ]);

        // profile update process
        Route::post('/profile/edit', [
            'as' => 'user.profile.update.process',
            'uses' => 'UserController@updateProfileProcess',
        ]);
    });
    

    /*
      Media Components Public Section Related Routes
      ----------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'Media\Controllers',
            'prefix' => 'media',
        ], function () {
            // upload image media
        Route::post('/upload-image', [
            'as' => 'media.upload.image',
            'uses' => 'MediaController@uploadImage',
        ]);

        // delete media file
        Route::post('/{fileName}/delete', [
            'as' => 'media.delete',
            'uses' => 'MediaController@delete',
        ]);

        // delete multiple media files
        Route::post('/multiple-delete', [
            'as' => 'media.delete.multiple',
            'uses' => 'MediaController@multipleDelete',
        ]);

        // upload image media detail
        Route::get('/read-uploaded-favicon-files-detail', [
            'as'   => 'media.upload.read_favicon',
            'uses' => 'MediaController@readFaviconFiles',
        ]);

        // upload image media
        Route::get('/uploaded-images-files', [
            'as' => 'media.uploaded.images',
            'uses' => 'MediaController@imagesFiles',
        ]);

    	// upload image media
        Route::post('/upload-media-file', [
            'as' => 'media.upload.process',
            'uses' => 'MediaController@processUploadImage',
        ]);
    });

    /*
      After Admin Authentication Accesiable Routes
      ---------------------------------------------------------------------- */

    Route::group([
            'prefix' => 'manage',
        ], function () {
        
        /*
          User Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'User\Controllers',
                'prefix' => 'user',
            ], function () {

            // fetch users list for datatable
            Route::get('/{status}/fetch-list', [
                'as' => 'manage.user.list',
                'uses' => 'UserController@index',
            ])->where('status', '[0-9]+');

            // delete user
            Route::post('/{userID}/delete', [
                'as' => 'manage.user.delete',
                'uses' => 'UserController@delete',
            ])->where('userID', '[0-9]+');

            // restore user
            Route::post('/{userID}/restore', [
                'as' => 'manage.user.restore',
                'uses' => 'UserController@restore',
            ])->where('userID', '[0-9]+');

            // change password by admin process
            Route::post('/{userID}/change-password', [
                'as' => 'manage.user.change_password.process',
                'uses' => 'UserController@changePasswordByAdmin',
            ])->where('userID', '[0-9]+');

            // fetch users details
            Route::get('/{userID}/fetch-details', [
                'as' => 'manage.users.get.detail',
                'uses' => 'UserController@getUserDetails',
            ])->where('status', '[0-9]+');

            // process contact form
	        Route::get('/{userId}/get-user-contact-info', [
	            'as'   => 'manage.user.contact.info',
	            'uses' => 'UserController@getInfo',
	        ]);

            // prepare user add support data
            Route::get('/get-user-supportData', [
                'as'   => 'manage.user.add.read.supportData',
                'uses' => 'UserController@prepareUserSupportData',
            ]);

            // fetch users details
            Route::get('/{userID}/contact', [
                'as' => 'manage.users.contact',
                'uses' => 'UserController@contact',
            ])->where('status', '[0-9]+');

            // add new user
            Route::post('/add', [
                'as'   => 'manage.user.add',
                'uses' => 'UserController@add',
            ]);

            // Get User available permissions
            Route::get('/{userId}/get-user-permissions', [
                'as'    => 'manage.user.read.user_permissions',
                'uses'  => 'UserController@getUserPermissions',
            ]);

            // Store user dynamic permissions
            Route::post('/{userId}/user-dynamic-permission-process', [
                'as'    => 'manage.user.write.user_permissions',
                'uses'  => 'UserController@processUserPermissions',
            ]);
        });

        /*
	    Start User Role Permission Components Manage Section Related Routes
	    ------------------------------------------------------------------- */
	    Route::group([
	        'namespace'     => 'User\Controllers',
	        'prefix'        => '/role-permission'
	    ], function() {

	         // Get Role Add Support Data
                Route::get('/role-add-support-data', [
                    'as'    => 'manage.user.role_permission.read.add_support_data',
                    'uses'  => 'RolePermissionController@getAddSuppotData'
                ]);

                // Add New Role Permissions
                Route::post('/add-process', [
                    'as'    => 'manage.user.role_permission.write.role.create',
                    'uses'  => 'RolePermissionController@addNewRole'
                ]);

                // Get role all permission using id
                Route::get('/{roleId}/get-permission', [
                    'as'    => 'manage.user.role_permission.read.using_id',
                    'uses'  => 'RolePermissionController@getPermissionById'
                ]);

                // Role Permission list
                Route::get('/list', [
                    'as'    => 'manage.user.role_permission.read.list',
                    'uses'  => 'RolePermissionController@prepareRolePermissionList'
                ]);

                // Role Permission delete process
                Route::post('/{rolePermissionIdOrUid}/delete-process', [
                    'as'    => 'manage.user.role_permission.write.delete',
                    'uses'  => 'RolePermissionController@processRolePermissionDelete'
                ]);

                // Get Role Permissions
                Route::get('/{roleId}/permissions', [
                    'as'    => 'manage.user.role_permission.read',
                    'uses'  => 'RolePermissionController@getPermissions'
                ]);

                // Create User Role Dynamic Permission
                Route::post('/{roleId}/add-dynamic-permission-process', [
                    'as'    => 'manage.user.role_permission.write.create',
                    'uses'  => 'RolePermissionController@processDynamicRolePermission'
                ]);

	    });

        /*
          Category Components Manage Section Related Routes
          ------------------------------------------------------------------- */
        Route::group([
                'namespace' => 'Category\Controllers',
                'prefix' => 'category',
            ], function () {
                // add new category
            Route::post('/add', [
                'as' => 'manage.category.add',
                'uses' => 'ManageCategoryController@add',
            ]);

            // list
            Route::get('/fetch-list/{categoryID?}', [
                'as' => 'manage.category.list',
                'uses' => 'ManageCategoryController@index',
            ]);

            // get details of category
            Route::get('/{catID}/get/details', [
                'as' => 'manage.category.get.details',
                'uses' => 'ManageCategoryController@getDetails',
            ])->where('catID', '[0-9]+');

            // get details of category
            Route::get('/{catID}/get/support-data', [
                'as' => 'category.get.supportData',
                'uses' => 'ManageCategoryController@getSupportData',
            ])->where('catID', '[0-9]+');

            // post update details of category
            Route::post('/{catID}/edit', [
                'as' => 'manage.category.update',
                'uses' => 'ManageCategoryController@update',
            ])->where('catID', '[0-9]+');

            // category update status
            Route::post('/{categoryID}/status', [
                'as' => 'category.update.status',
                'uses' => 'ManageCategoryController@updateStatus',
            ])->where('categoryID', '[0-9]+');

            // category delete
            Route::post('{categoryID}/delete', [
                'as' => 'manage.category.delete',
                'uses' => 'ManageCategoryController@delete',
            ])->where('categoryID', '[0-9]+');

            // get fancytree sourse data
            Route::get('/fancytree-support-data', [
                'as' => 'category.fancytree.support-data',
                'uses' => 'ManageCategoryController@fancytreeSupportData',
            ]);
        });

        /*
          Pages Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Pages\Controllers',
                'prefix' => 'pages',
            ], function () {
                // Get page type
            Route::get('/get/pages-type', [
                'as' => 'manage.pages.get.page_type',
                'uses' => 'ManagePagesController@getPagesType',
            ]);

            // get all pages list
            Route::get('/fetch-data/{parentPageID?}', [
                'as' => 'manage.pages.fetch.datatable.source',
                'uses' => 'ManagePagesController@index',
            ]);

            // get all pages list
            Route::get('/fetch-parent-page-data/{parentPageID}', [
                'as' => 'manage.pages.get.parent.page',
                'uses' => 'ManagePagesController@getParentPage',
            ]);

            // get all pages list
            Route::post('/update/list-order', [
                'as' => 'manage.page.update.list.order',
                'uses' => 'ManagePagesController@updateListOrder',
            ]);

            // add new page
            Route::post('/add', [
                'as' => 'manage.pages.add',
                'uses' => 'ManagePagesController@add',
            ]);

            // get details of page
            Route::get('/{pageID}/get/details', [
                'as' => 'manage.pages.get.details',
                'uses' => 'ManagePagesController@getDetails',
            ])->where('pageID', '[0-9]+');

            // update page data
            Route::post('/{pageID}/edit', [
                'as' => 'manage.pages.update',
                'uses' => 'ManagePagesController@update',
            ])->where('pageID', '[0-9]+');

            // delete page
            Route::post('/{pageID}/delete', [
                'as' => 'manage.pages.delete',
                'uses' => 'ManagePagesController@delete',
            ])->where('pageID', '[0-9]+');

            // get details of page
            Route::get('/details/{pageID}', [
                'as' => 'manage.display.page.details',
                'uses' => 'ManagePagesController@displayPageDetails',
            ])->where('pageID', '[0-9]+');
            });

        /*
          Product Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Product\Controllers',
                'prefix' => 'product',
            ], function () {
                // fetch product list as datatable source
            Route::get('/products', [
                'as' => 'manage.product.list',
                'uses' => 'ManageProductController@index',
            ]);

            // fetch category products as datatable source
            Route::get('/{categoryID}/category-products', [
                'as' => 'manage.category.product.list',
                'uses' => 'ManageProductController@index',
            ])->where('categoryID', '[0-9]+');

            // fetch brand products as datatable source
            Route::get('/{brandId}/brand-products', [
                'as' => 'manage.brand.product.list',
                'uses' => 'ManageProductController@getBrands',
            ])->where('brandId', '[0-9]+');

                Route::group(['prefix' => '/product'], function () {
                    
                    Route::get('/fetch-add-support-data/{categoryId?}', [
                    'as' => 'manage.product.add.supportdata',
                    'uses' => 'ManageProductController@addSupportData',
                ]);

                // add product
                Route::post('/add', [
                    'as' => 'manage.product.add',
                    'uses' => 'ManageProductController@add',
                ]);

                Route::get('/{productID}/detail', [
                'as' => 'manage.product.detailSupportData',
                'uses' => 'ManageProductController@getDetail',
                ])->where('productID', '[0-9]+');

                // delete product
                Route::post('/{productID}/delete', [
                    'as' => 'manage.product.delete',
                    'uses' => 'ManageProductController@delete',
                ])->where('productID', '[0-9]+');

                // details for edit
                Route::get('/{productID}/details', [
                    'as' => 'manage.product.details',
                    'uses' => 'ManageProductController@details',
                ])->where('productID', '[0-9]+');

                    Route::get('/{productID}/fetch-product-name', [
                    'as' => 'manage.product.fetch.name',
                    'uses' => 'ManageProductController@getProductName',
                ])->where('productID', '[0-9]+');

                // product edit details support data
                Route::get('/{productID}/fetch-edit-details-support-data', [
                    'as' => 'manage.product.edit.details.supportdata',
                    'uses' => 'ManageProductController@editDetailsSupportData',
                ])->where('productID', '[0-9]+');

                // edit product
                Route::post('/{productID}/edit', [
                    'as' => 'manage.product.edit',
                    'uses' => 'ManageProductController@edit',
                ])->where('productID', '[0-9]+');

                // update status
                Route::post('/{productID}/update-status', [
                    'as' => 'manage.product.update_status',
                    'uses' => 'ManageProductController@updateStatus',
                ])->where('productID', '[0-9]+');

                    Route::group(['prefix' => '{productID}/image'], function () {
                        // add image
                    Route::post('/add', [
                        'as' => 'manage.product.image.add',
                        'uses' => 'ManageProductController@addImage',
                    ])->where('productID', '[0-9]+');

                    // images list
                    Route::get('/fetch-list', [
                        'as' => 'manage.product.image.list',
                        'uses' => 'ManageProductController@imageList',
                    ])->where('productID', '[0-9]+');

                     // get all pages list
                    Route::post('/update/image-list-order', [
                        'as' => 'manage.product.image.update.list.order',
                        'uses' => 'ManageProductController@updateImageListOrder',
                    ]);

                    // delete image
                    Route::post('/{imageID}/delete', [
                        'as' => 'manage.product.image.delete',
                        'uses' => 'ManageProductController@deleteImage',
                    ])->where('productID', '[0-9]+');

                    // edit image support data
                    Route::get('/{imageID}/fetch-supportdata', [
                        'as' => 'manage.product.image.edit.supportdata',
                        'uses' => 'ManageProductController@editImageSupportData',
                    ])->where('productID', '[0-9]+');

                    // edit image
                    Route::post('/{imageID}/edit', [
                        'as' => 'manage.product.image.edit',
                        'uses' => 'ManageProductController@editImage',
                    ])->where('productID', '[0-9]+');

                    
                    // edit image support data
                    Route::get('/get-images', [
                        'as'   => 'manage.product.image.read.data_list',
                        'uses' => 'ManageProductController@readImages',
                    ])->where('productID', '[0-9]+');

                    // edit image support data
                    Route::post('/{imageId}/process-unset-option-image', [
                        'as'   => 'manage.product.image.read.process_unset',
                        'uses' => 'ManageProductController@processUnsetOptionImage',
                    ]);

                    // Product Rating list
                    Route::get('/product-rating-list', [
                        'as'    => 'manage.product.rating.read.list',
                        'uses'  => 'ManageProductController@prepareProductRatingList'
                    ]);

                });

                    Route::group(['prefix' => '{productID}/specifications'], function () {
                        // specification list
                        Route::get('/fetch-list', [
                            'as' => 'manage.product.specification.list',
                            'uses' => 'ManageProductController@specificationList',
                        ])->where('productID', '[0-9]+');

                        // add specification
                        Route::post('/{presetType}/add', [
                            'as' => 'manage.product.specification.add',
                            'uses' => 'ManageProductController@addSpecification',
                        ])->where('productID', '[0-9]+');

                        // change specification preset
                        Route::post('/change-spacification-preset', [
                            'as' => 'manage.product.specification.change_preset',
                            'uses' => 'ManageProductController@changeSpecificationPreset',
                        ])->where('productID', '[0-9]+');

                        // edit specification
                        Route::get('{specificationID}/fetch-supportdata', [
                            'as' => 'manage.product.specification.edit',
                            'uses' => 'ManageProductController@editSpecification',
                            ])->where('productID', '[0-9]+');

                        // update specification
                        Route::post('{specificationID}/update', [
                            'as' => 'manage.product.specification.update',
                            'uses' => 'ManageProductController@updateSpecification',
                        ])->where('productID', '[0-9]+');

                        // delete specification
                        Route::post('/{specificationID}/delete', [
                            'as' => 'manage.product.specification.delete',
                            'uses' => 'ManageProductController@deleteSpecification',
                        ])->where('productID', '[0-9]+');
                    });

                    /*
                 	* product seo meta route start here
                    ------------------------------------------------------------------- */
                    Route::group(['prefix' => '{productID}/seo-meta'], function () {
                     	
                     	// read product seo meta
                     	Route::get('/read-seo-meta', [
			                'as' => 'manage.product.seo_meta.read',
			                'uses' => 'ManageProductController@getSeoMeta',
		                ])->where('productID', '[0-9]+');

						// store product seo meta
		                Route::post('/update-seo-meta', [
		                    'as' => 'manage.product.seo_meta.write',
		                    'uses' => 'ManageProductController@storeSeoMeta',
		                ])->where('productID', '[0-9]+');

                    });

                    /*
                 	* product seo meta route ends here
                    ------------------------------------------------------------------- */

                    /*
                     product ratings route start here
                    ------------------------------------------------------------------- */
                    Route::group(['prefix' => 'ratings/{productId}/'], function () {

                        // get list product ratings
                        Route::get('/ratings-list', [
                            'as'    => 'manage.product.ratings.read.list',
                            'uses'  => 'ManageProductController@productRatingsTabularDataList'
                        ]);

                         // delete rating
                        Route::post('/{ratingId}/delete', [
                            'as'    => 'manage.product.rating.write.delete',
                            'uses'  => 'ManageProductController@deleteRating'
                        ]);
                    });
                    /*
                     product ratings route end here
                    ------------------------------------------------------------------*/

                    /*
                     product faqs route start here
                    ------------------------------------------------------------------- */
                    Route::group(['prefix' => 'faqs/{productId}/'], function () {

                        // get list product ratings
                        Route::get('/faq-list', [
                            'as'    => 'manage.product.faq.read.list',
                            'uses'  => 'ManageProductController@productFaqTabularDataList'
                        ]);

                         // add faq
                        Route::post('/add', [
                            'as' => 'manage.product.faq.add',
                            'uses' => 'ManageProductController@addProductFaq',
                        ])->where('productID', '[0-9]+');

                        // edit faq
                        Route::get('{faqID}/fetch-faq-supportdata', [
                            'as' => 'manage.product.faq.editData',
                            'uses' => 'ManageProductController@editFaqData',
                        ])->where('productID', '[0-9]+');

                        // update specification
                        Route::post('{faqID}/update', [
                            'as' => 'manage.product.faq.update',
                            'uses' => 'ManageProductController@updateFaq',
                        ])->where('productID', '[0-9]+');

                         // delete rating
                        Route::post('/{faqID}/delete', [
                            'as'    => 'manage.product.faq.delete',
                            'uses'  => 'ManageProductController@deleteFaq'
                        ]);

                        // edit faq
                        Route::get('{faqID}/fetch-detail-supportdata', [
                            'as'    => 'manage.product.faq.detail.supportData',
                            'uses'  => 'ManageProductController@faqDetailData',
                        ]);

                    });
                    
                    /*
                     product faqs route end here
                    ------------------------------------------------------------------*/

                      /*
                     product faqs route start here
                    ------------------------------------------------------------------- */
                    Route::group(['prefix' => 'awating-user-list/{productId}/'], function () {

                        // get list product ratings
                        Route::get('/product-awating-user-list', [
                            'as'    => 'manage.product.awating_user.read.list',
                            'uses'  => 'ManageProductController@productAwatingUserDataList'
                        ]);

                        // send notify mail customer
                        Route::post('/{notifyUserId}/send-notify-mail', [
                            'as' => 'manage.product.awating_user.notify_mail.send',
                            'uses' => 'ManageProductController@sendNotifyMailCustomer',
                        ])->where('productID', '[0-9]+');

                        // delete multiple awating user
                        Route::post('/delete-multiple-awating-user', [
                            'as'   => 'manage.product.awating_user.delete.multipleUser',
                            'uses' => 'ManageProductController@multipleDeleteUser',
                        ]);

                        // delete single awating user
                        Route::post('/{notifyUserId}/delete-awating-user', [
                            'as'   => 'manage.product.awating_user.delete',
                            'uses' => 'ManageProductController@deleteAwatingUser',
                        ]);

                    });
                    
                    /*
                     product faqs route end here
                    ------------------------------------------------------------------*/

                    Route::group(['prefix' => '{productID}/option'], function () {
                        // add option
	                    Route::post('/add', [
	                        'as' => 'manage.product.option.add',
	                        'uses' => 'ManageProductController@addOption',
	                    ])->where('productID', '[0-9]+');

	                    // option list
	                    Route::get('/fetch-list', [
	                        'as' => 'manage.product.option.list',
	                        'uses' => 'ManageProductController@optionList',
	                    ])->where('productID', '[0-9]+');

	                    // delete option
	                    Route::post('/{optionID}/delete', [
	                        'as' => 'manage.product.option.delete',
	                        'uses' => 'ManageProductController@deleteOption',
	                    ])->where('productID', '[0-9]+');

	                    // edit option support data
	                    Route::get('/{optionID}/fetch-supportdata', [
	                        'as' => 'manage.product.option.edit.supportdata',
	                        'uses' => 'ManageProductController@editOptionSupportData',
	                    ])->where('productID', '[0-9]+');

	                    // edit option
	                    Route::post('/{optionID}/edit', [
	                        'as' => 'manage.product.option.edit',
	                        'uses' => 'ManageProductController@editOption',
	                    ])->where('productID', '[0-9]+');


                        Route::group(['prefix' => '{optionID}/value'], function () {

	                        // add option values
	                        Route::post('/add', [
	                            'as' => 'manage.product.option.value.add',
	                            'uses' => 'ManageProductController@addOptionValues',
	                        ])->where('productID', '[0-9]+');

	                        // get option value list
	                        Route::get('/fetch-list', [
	                            'as' => 'manage.product.option.value.list',
	                            'uses' => 'ManageProductController@optionValues',
	                        ])->where('productID', '[0-9]+');

	                        // update option values
	                        Route::post('/multiple-edit', [
	                            'as' => 'manage.product.option.value.edit',
	                            'uses' => 'ManageProductController@editOptionValues',
	                        ])->where('productID', '[0-9]+');

	                        // delete option value delete
	                        Route::post('/{optionValueID}/{optionType}/delete', [
	                            'as' => 'manage.product.option.value.delete',
	                            'uses' => 'ManageProductController@deleteOptionValue',
                            ])->where('productID', '[0-9]+');
                            
                   		 });
                    });
                });
            });

        /*
          Store Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Store\Controllers',
                'prefix' => 'store',
            ], function () {
                Route::get('/{formType}/edit-store-settings-support-data', [
	                'as' => 'store.settings.edit.supportdata',
	                'uses' => 'ManageStoreController@settingsEditSupportData',
	            ]);

                Route::post('/{formType}/edit-store-settings', [
	                'as' => 'store.settings.edit',
	                'uses' => 'ManageStoreController@editSettings',
	            ]);

	            // process get configuration
                Route::get('/get-email-template-data', [
                    'as'   => 'store.settings.get.email-template.data',
                    'uses' => 'ManageStoreController@getEmailTemplatetData',
                ]);

                 // process get configuration
                Route::get('/{templateId}/get-edit-email-template-data', [
                    'as'   => 'store.settings.get.edit.email-template.data',
                    'uses' => 'ManageStoreController@getEditEmailTemplatetData',
                ]);

                // process email template subject configuration delete
                Route::post('/{emaiSubjectId}/email-subject-delete', [
                    'as' 	=> 'store.settings.email_subject.delete',
                    'uses' 	=> 'ManageStoreController@emailTemplateSubjectDelete',
                ]);

                // process email template configuration delete
                Route::post('/{emailTemplateId}/email-template-delete', [
                    'as' 	=> 'store.settings.email_template.delete',
                    'uses' 	=> 'ManageStoreController@emailTemplateDelete',
                ]);

                // process configuration
                Route::post('/{emailTemplateId}/email-template-edit', [
                    'as'   => 'store.settings.email_template.edit',
                    'uses' => 'ManageStoreController@emailTemplateEdit',
                ]);

                // Slider list
                Route::get('/slider-list', [
                    'as'    => 'store.settings.slider.read.list',
                    'uses'  => 'ManageStoreController@prepareSliderList'
                ]);

                // Slider create process
                Route::post('/{formType}/slider-add-process', [
                    'as'    => 'store.settings.slider.write.addSlider',
                    'uses'  => 'ManageStoreController@processSliderCreate'
                ]);

                // Slider get the data
                Route::get('/{sliderID}/slider-get-update-data', [
                    'as'    => 'store.settings.slider.read.update.data',
                    'uses'  => 'ManageStoreController@updateSliderData'
                ]);

                // Slider update process
                Route::post('/{sliderID}/{formType}/slider-update-process', [
                    'as'    => 'store.settings.slider.write.update',
                    'uses'  => 'ManageStoreController@updateSlider'
                ]);

                // Slider delete process
                Route::post('/{sliderID}/{title}/delete-slider', [
                    'as'    => 'store.settings.slider.write.delete',
                    'uses'  => 'ManageStoreController@deleteSlider'
                ]);

                // process get configuration
                Route::get('/get-edit-footer-template-data', [
                    'as'   => 'store.settings.get.edit.footer_template.data',
                    'uses' => 'ManageStoreController@getEditFooterTemplatetData',
                ]);

                 // process configuration
                Route::post('/{footerTemplateId}/fooetr-template-edit', [
                    'as'   => 'store.settings.footer_template.edit',
                    'uses' => 'ManageStoreController@footerTemplateEdit',
                ]);

                // process email template configuration delete
                Route::post('/{footerTemplateId}/fooetr-template-delete', [
                    'as'    => 'store.settings.footer_template.delete',
                    'uses'  => 'ManageStoreController@footerTemplateDelete',
                ]);

                // process get landing page configuration
                Route::get('/get-edit-landing-page-data', [
                    'as'   => 'store.settings.landing_page.edit.supportData',
                    'uses' => 'ManageStoreController@getEditLandingPageData',
                ]);

                // process landing page update
                Route::post('/landing-page-update', [
                    'as'    => 'store.settings.landing_page.write.edit',
                    'uses'  => 'ManageStoreController@landingPageUpdate',
                ]);

                // read slider media
                Route::get('/{sliderId}/read-slider-media', [
                    'as' => 'store.settings.read.slider_media',
                    'uses' => 'ManageStoreController@readSliderMedia',
                ]);
                
            });

        /*
          Brand Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Brand\Controllers',
                'prefix' => 'brand',
            ], function () {
                Route::get('/fetch', [
                'as' => 'manage.brand.list',
                'uses' => 'BrandController@index',
            ]);

                Route::post('/add', [
                'as' => 'manage.brand.add',
                'uses' => 'BrandController@addProccess',
            ]);

                Route::get('/{brandID}/detail', [
                'as' => 'manage.brand.detailSupportData',
                'uses' => 'BrandController@getDetail',
            ])->where('brandID', '[0-9]+');

                Route::get('/{brandID}/edit', [
                'as' => 'manage.brand.editSupportData',
                'uses' => 'BrandController@editSupportData',
            ])->where('brandID', '[0-9]+');

                Route::post('/{brandID}/edit', [
                'as' => 'manage.brand.edit.process',
                'uses' => 'BrandController@editProcess',
            ])->where('brandID', '[0-9]+');

                Route::post('/{brandID}/delete', [
                'as' => 'manage.brand.delete',
                'uses' => 'BrandController@delete',
            ])->where('brandID', '[0-9]+');
            });

        /*
          Coupon Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Coupon\Controllers',
                'prefix' => 'coupon',
            ], function () {
                Route::get('/{status}/fetch', [
                'as' => 'manage.coupon.list',
                'uses' => 'CouponController@index',
            ]);

                Route::get('/add', [
                'as' => 'manage.coupon.fetch.couponDiscountType',
                'uses' => 'CouponController@getCouponDiscountType',
            ]);

                Route::post('/add', [
                'as' => 'manage.coupon.add',
                'uses' => 'CouponController@addProcess',
            ]);

                Route::get('/{couponID}/edit', [
                'as' => 'manage.coupon.editSupportData',
                'uses' => 'CouponController@editSupportData',
            ]);

                Route::get('/{couponID}/detail', [
                'as' => 'manage.coupon.detailSupportData',
                'uses' => 'CouponController@getDetail',
            ]);

                Route::post('/{couponID}/edit', [
                'as' => 'manage.coupon.edit.process',
                'uses' => 'CouponController@editProcess',
            ]);

                Route::post('/{couponID}/delete', [
                'as' => 'manage.coupon.delete',
                'uses' => 'CouponController@delete',
            ]);
        });

        /*
          Shipping Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Shipping\Controllers',
                'prefix' => 'shipping',
            ], function () {
            
	            Route::get('/fetch', [
	                'as' => 'manage.shipping.list',
	                'uses' => 'ShippingController@index',
	            ]);

	            Route::get('/fetch/contries', [
	                'as' => 'manage.shipping.fetch.contries',
	                'uses' => 'ShippingController@getCountries',
	            ]);

	            Route::get('/{shippingID}/detail', [
	                'as' => 'manage.shipping.detailSupportData',
	                'uses' => 'ShippingController@getDetail',
	            ])->where('shippingID', '[0-9]+');

	            Route::post('/add', [
	                'as' => 'manage.shipping.add',
	                'uses' => 'ShippingController@addProcess',
	            ]);

	            Route::get('/{shippingID}/edit', [
	                'as' => 'manage.shipping.editSupportData',
	                'uses' => 'ShippingController@editSupportData',
	            ])->where('shippingID', '[0-9]+');

	            Route::post('/{shippingID}/edit', [
	                'as' => 'manage.shipping.edit.process',
	                'uses' => 'ShippingController@editProcess',
	            ])->where('shippingID', '[0-9]+');

	            Route::post('/{shippingID}/delete', [
	                'as' => 'manage.shipping.delete',
	                'uses' => 'ShippingController@delete',
	            ])->where('shippingID', '[0-9]+');

                Route::get('/edit-aoc', [
	                'as' => 'manage.shipping.aoc.editSupportData',
	                'uses' => 'ShippingController@getAocSupportData',
	            ]);

                Route::post('/update', [
	                'as' => 'manage.shipping.aoc.update',
	                'uses' => 'ShippingController@aocProcess',
	            ]);
            });

        /*
          Tax Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Tax\Controllers',
                'prefix' => 'tax',
            ], function () {
                Route::get('/fetch', [
                'as' => 'manage.tax.list',
                'uses' => 'TaxController@index',
            ]);

                Route::get('/fetch/contries', [
                'as' => 'manage.tax.fetch.contries',
                'uses' => 'TaxController@getCountries',
            ]);

                Route::get('/{taxID}/detail', [
                'as' => 'manage.tax.detailSupportData',
                'uses' => 'TaxController@getDetail',
            ])->where('taxID', '[0-9]+');

                Route::post('/add', [
                'as' => 'manage.tax.add',
                'uses' => 'TaxController@addProcess',
            ]);

                Route::get('/{taxID}/edit', [
                'as' => 'manage.tax.editSupportData',
                'uses' => 'TaxController@editSupportData',
            ])->where('taxID', '[0-9]+');

                Route::post('/{taxID}/edit', [
                'as' => 'manage.tax.edit.process',
                'uses' => 'TaxController@editProcess',
            ])->where('taxID', '[0-9]+');

                Route::post('/{taxID}/delete', [
                'as' => 'manage.tax.delete',
                'uses' => 'TaxController@delete',
            ])->where('taxID', '[0-9]+');
            });

        /*
          Report Components Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Report\Controllers',
                'prefix' => 'report',
            ], function () {

            // fetch order report
            Route::get('/{startDate}/{endDate}/{status}/{order}/{currency}/fetch-order-report', [
                'as' => 'manage.report.list',
                'uses' => 'ReportController@index',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // fetch order report
            Route::get('/{startDate}/{endDate}/{status}/{order}/{currency}/fetch-order-payment-report', [
                'as' => 'manage.payment_report.list',
                'uses' => 'ReportController@preapreOrderPaymentData',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // fetch order report
            Route::get('/{startDate}/{endDate}/{order}/fetch-product-report', [
                'as' => 'manage.product_report.list',
                'uses' => 'ReportController@prepareProductReportList',
            ]);

            // order report dialog
            Route::get('/{orderID}/get/order-details', [
                'as' => 'manage.order.report.details.dialog',
                'uses' => 'ReportController@orderDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // pdf download
            Route::get('/{orderID}/pdf-download', [
                'as' => 'manage.report.pdf_download',
                'uses' => 'ReportController@pdfDownload',
            ])->where('orderID', '[0-9]+');

            // generate excel sheet
            Route::get('/{startDate}/{endDate}/{status}/{order}/{currency}/excel-download', [
                'as' => 'manage.report.excel_download',
                'uses' => 'ReportController@excelDownload',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // generate excel sheet
            Route::get('/{startDate}/{endDate}/{status}/{order}/{currency}/payment-excel-download', [
                'as' => 'manage.payment_report.payment_excel_download',
                'uses' => 'ReportController@paymentExcelDownload',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // order report config items
            Route::get('/{startDate}/{endDate}/{status}/{order}/get/order-config-items', [
                'as' => 'manage.report.get.order_config_data',
                'uses' => 'ReportController@orderConfigItems',
            ])->where('orderID', '[0-9]+');

        });

        /*
          Order Components Manage Section Related Routes
          ------------------------------------------------------------------- */
        Route::group([
                'namespace' => 'ShoppingCart\Controllers',
                'prefix' => 'order',
            ], function () {

            // get orders related to users
            Route::get('/get/{status}/order-list/{userID?}', [
                'as' => 'manage.order.list',
                'uses' => 'ManageOrderController@index',
            ])->where('status', '[0-9]+');

            // pdf download
            Route::get('/{orderID}/order-pdf-download', [
                'as' => 'manage.order.report.pdf_download',
                'uses' => 'ManageOrderController@orderPdfDownload',
            ])->where('orderID', '[0-9]+');

            // get cart btn string
            Route::get('/get/{orderID}/order-update-data', [
                'as' => 'manage.order.update.support.data',
                'uses' => 'ManageOrderController@orderUpdateSupportData',
            ])->where('orderID', '[0-9]+');

            // order update
            Route::post('/order/{orderID}/update', [
                'as' => 'manage.order.update',
                'uses' => 'ManageOrderController@orderUpdate',
            ])->where('orderID', '[0-9]+');


            // order dialog
            Route::get('/get/{orderID}/order-details', [
                'as' => 'manage.order.details.dialog',
                'uses' => 'ManageOrderController@orderDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // order log dialog
            Route::get('/get/{orderID}/order-log-details', [
                'as' => 'manage.order.log.details.dialog',
                'uses' => 'ManageOrderController@orderLogDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // get order user data
            Route::get('/get/{orderID}/order-user-details', [
                'as' => 'manage.order.get.user.details',
                'uses' => 'ManageOrderController@getUserDetails',
            ])->where('orderID', '[0-9]+');

            // send mail to user
            Route::post('/order-user-details', [
                'as' => 'manage.order.user.contact',
                'uses' => 'ManageOrderController@prepareContactUser',
            ]);

            // Delete order
            Route::post('/{orderId}/delete-order', [
                'as'   => 'manage.order.delete',
                'uses' => 'ManageOrderController@deleteOrder'
            ]);

            // Delete sand box order
            Route::post('/{orderId}/delete-sandbox-order', [
                'as'   => 'manage.order.sandbox_order.delete',
                'uses' => 'ManageOrderController@deleteSandboxOrder'
            ]);
            });

        /*
          Order Payments Components Manage Section Related Routes
          ------------------------------------------------------------------- */
        Route::group([
                'namespace' => 'ShoppingCart\Controllers',
                'prefix' => 'order',
            ], function () {

		        // order payment details
		        Route::get('/get/{orderPaymentID}/order-payment-details', [
		            'as' => 'manage.order.payment.detail.dialog',
		            'uses' => 'OrderPaymentsController@orderPaymentDetails',
		        ])->where('orderPaymentID', '[0-9]+');

		        // refund order payment detail dialog
		        Route::get('/get/{orderID}/order-payment-refund', [
		            'as' => 'manage.order.payment.refund.detail.dialog',
		            'uses' => 'OrderPaymentsController@orderRefundDetails',
		        ])->where('orderID', '[0-9]+');

		        // refund order payment detail dialog
		        Route::post('/{orderID}/order-payment-refund', [
		            'as' => 'manage.order.payment.refund.process',
		            'uses' => 'OrderPaymentsController@orderRefund',
		        ])->where('orderID', '[0-9]+');

		        // update order payment detail dialog
		        Route::get('/get/{orderID}/order-payment-update', [
		            'as' => 'manage.order.payment.update.detail.dialog',
		            'uses' => 'OrderPaymentsController@orderPaymentUpdateDetails',
		        ])->where('orderID', '[0-9]+');

		        // update order payment
		        Route::post('/{orderID}/order-payment-update', [
		            'as' => 'manage.order.payment.update.process',
		            'uses' => 'OrderPaymentsController@orderPaymentUpdate',
		        ])->where('orderID', '[0-9]+');

		        // Order payment List
		        Route::get('/get/{startDate}/{endDate}/', [
		            'as' => 'manage.order.payment.list',
		            'uses' => 'OrderPaymentsController@index',
		        ]);

		        // generate excel sheet
		        Route::get('/{startDate}/{endDate}/excel-download', [
		            'as' => 'manage.order.payment.excel_download',
		            'uses' => 'OrderPaymentsController@excelDownload',
		        ]);

		        // Delete order payment
		        Route::post('/{paymentId}/delete-payment', [
		            'as'   => 'manage.order.payment.delete',
		            'uses' => 'OrderPaymentsController@deleteOrderPayment'
		        ]);

		        // Delete sand box order
		        Route::post('/{paymentId}/delete-sandbox', [
		            'as'   => 'manage.order.payment.delete.sandbox',
		            'uses' => 'OrderPaymentsController@deleteSandboxPayment'
		        ]);
            });
        });
    
    
});

Route::group([
    'namespace' => 'ShoppingCart\Controllers',
    'prefix' => 'order/',
], function () {

    // PayPal IPN Request
    Route::post('ipn-request', [
        'as' => 'order.ipn_request',
        'uses' => 'OrderPaymentsController@processPaypalIpnRequest',
    ]);

    // PayPal Payment Thanks
    Route::post('thank-you', [
        'as' => 'order.thank_you',
        'uses' => 'OrderController@thanksOnPayPalOrder',
    ]);

    // Stripe Payment Thanks
    Route::get('thank-you/{orderToken}', [
        'as' => 'order.thank_you_stripe',
        'uses' => 'OrderController@stripePaymentSuccess',
    ]);

    // Razorpay Payment Thanks
    Route::get('thank-you-razorpay/{orderToken}', [
        'as' => 'order.thank_you_razorpay',
        'uses' => 'OrderController@razorpayPaymentSuccess',
    ]);

    // Paystack Payment Thanks
    Route::get('thank-you-paystack/{orderToken}', [
        'as' => 'order.thank_you_paystack',
        'uses' => 'OrderController@paystackPaymentSuccess',
    ]);

    // PayPal Payment Cancelled by user
    Route::get('payment-cancelled/{orderToken}', [
        'as' => 'order.payment_cancelled',
        'uses' => 'OrderController@paymentCancel',
    ]);
});
});