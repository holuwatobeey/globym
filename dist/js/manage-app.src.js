(function() {
'use strict';

  angular.module('ManageApp', [
    'ngMessages',
    'ngAnimate',
    'ngSanitize',
    'ui.router',
    'ngNotify',
    'ngDialog',
    'angularFileUpload',
    'angular-loading-bar',
    'selectize',
    'NgSwitchery',
    'lw.core.utils',
    'lw.security.main',
    'lw.auth',
    'lw.data.datastore',
    'lw.data.datatable',
    'lw.form.main',
    'app.service',
    'app.http',
    'app.notification',
    'app.form',
    'app.fancytree',
    'app.directives',
    'ManageApp.master',
    'UserApp.manage',
    'UserApp.manageUserChangePassword',
    'ManageApp.userDetailDialog',
    'UserApp.profile',
    'UserApp.profileEdit',
    'UserApp.changePassword',
    'UserApp.changeEmail',
    'ManageApp.category',
    'ManageApp.categoryAddDialog',
    'ManageApp.categoryAdd',
    'ManageApp.categoryEditDialog',
    'ManageApp.categoryEdit',
    'ManageApp.categoryDelete',
    'ManageApp.brand',
    'ManageApp.brandDetailDialog',
    'ManageApp.brandAddDialog',
    'ManageApp.brandAdd',
    'ManageApp.brandEditDialog',
    'ManageApp.brandEdit',
    'ManageApp.coupon',
    'ManageProductApp.details',
    'ManageProductApp.add',
    'ManageProductApp.uploadedMedia',
    'ManageProductApp.edit',
    'ManageProductApp.editDetails',
    'ManageProductApp.notifyMailCustomer',
    'ManageApp.shipping',
    'ManageApp.tax',
    'ManageApp.report',
    'ManageProductApp.images',
    'ManageProductApp.addImage',
    'ManageProductApp.editImage',
    'ManageProductApp.options',
    'ManageProductApp.addOption',
    'ManageProductApp.editOption',
    'ManageProductApp.addOptionValues',
    'ManageProductApp.optionValues',
    'ManageProductApp.specification',
    'ManageProductApp.addSpecification',
    'ManageProductApp.awatingUser',
    'ManageProductApp.addFaq',
    'ManageProductApp.editFaq',
    'ManageProductApp.editSpecification',
	'ManageProductApp.ratings',
    'ManageProductApp.faqs',
    'ManagePagesApp.list',
    'ManagePagesApp.add',
    'ManagePagesApp.add.dialog',
    'ManagePagesApp.edit.dialog',
    'ManagePagesApp.edit',
    'ManagePagesApp.page.details',
    'manageApp.storeSettingsEdit',
    'manageApp.GeneralSettings',
    'manageApp.CurrencySettings',
    'manageApp.OrderSettings',
    'manageApp.PaymentSettings',
    'manageApp.ProductSettings',
    'manageApp.PlacementSettings',
    'manageApp.ContactSettings',
    'manageApp.SliderSettings',
    'manageApp.userSettings',
    'manageApp.emailSettings',
    'manageApp.privacyPolicySettings',
    'manageApp.socialSettings',
    'ManageApp.orderList',
    'ManageApp.orderUpdate',
    'ManageApp.orderCancel',
    'ManageApp.orderLogList',
    'ManageApp.orderDialogList',
    'ManageApp.payment',
    'ManageApp.paymentDetailsDialog',
    'ManageApp.rawDataDialog',
    'ManageApp.orderPaymentUpdate',
    'ManageApp.orderPaymentRefund',
    'ManageApp.orderContact',
    'uploadManagerEngine',
    'DashboardApp.main',
    'ManageApp.brandDeleteDialog',
    'ManageApp.productList',
    'ManageApp.orderDetails',
    'ManageApp.orderDelete',
    'ManageApp.paymentDelete',
    'manageApp.CssStyleSettings',
    'manageApp.EmailTemplateSettings',
	'UserApp.login',
    'UserApp.logout',
    'manageApp.landingPageSettings',
    'manageApp.manageFooterSettings',
    'ManageApp.RolePermissionDataServices',
    'ManageApp.RolePermissionEngine',
    'ManageApp.specificationPresetList',
    'ManageApp.ShippingTypeEngine',
    'ManageProductApp.seoMeta',
    'ManageApp.productRatingList',
    'ManageApp.TransliterateDataServices',
    'ManageApp.transliterateDialog'
  ]).
  //constant('__ngSupport', window.__ngSupport).
  run([
    '__Auth', '$state', '$rootScope', function(__Auth, $state, $rootScope) {
      
        // Verify Route Permissions
        __Auth.verifyRoute($state);

         $rootScope.__ngSupport = window.__ngSupport;
       
    }
  ]).
  config([ 
    '$stateProvider', '$urlRouterProvider', '$interpolateProvider', routes
  ]);

  
  /**
    * Application Routes Configuration
    *
    * @inject $stateProvider
    * @inject $urlRouterProvider
    * @inject $interpolateProvider
    *
    * @return void
    *---------------------------------------------------------------- */



  function routes($stateProvider, $urlRouterProvider, $interpolateProvider) {     

    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

    $urlRouterProvider
       .otherwise('/dashboard');

    //state configurations
    $stateProvider
        
        // home
        .state('home', 
             __globals.stateConfig('/home', 'home', {
                access  : { 
                }
              }
            )
        )

        // dashboard
        .state('dashboard', 
             __globals.stateConfig('/dashboard', 'dashboard/details', {
                access  : { 
                }
              }
            )
        )

        // profile
        .state('profile', 
             __globals.stateConfig('/profile', 'user/manage-profile', {
                access  : { 
                }
              }
            )
        )

        // profile edit
        .state('profileEdit', 
             __globals.stateConfig('/profile/edit', 'user/profile-edit', {
                access  : { 
                }
              }
            )
        )

        // change password
        .state('changePassword', 
             __globals.stateConfig('/change-password', 'user/change-password', {
                access  : { 
                }
              }
            )
        )

        // change email
        .state('changeEmail', 
             __globals.stateConfig('/change-email', 'user/change-email', {
                access  : { 
                }
              }
            )
        )

        // users
        .state('users', 
             __globals.stateConfig('/users', 'user/manage/list', {
                access  : { 
                }
              }
            )
        )

         // categories
        .state('categories', 
             __globals.stateConfig('/categories/:mCategoryID?', 'category/list', {
                access  : { 
                }
              }
            )
        )

        // productsList
        .state('products', 
             __globals.stateConfig('/products/:mCategoryID?/:brandID?', 'product/manage/list', {
                access  : { 
                },
                params: {
                    mCategoryID: { squash: true, value: null },
                    brandID: { squash: true, value: null }
                }
              }
            )
        )

        // category add 
        .state('categories.add', 
             __globals.stateConfig('^/categories/add/:mCategoryID?', null, {
             	controller  : 'CategoryAddDialogController',
                access  : { 
                },
                params: {
                    mCategoryID: { squash: true }
                }
              }
            )
        )

          // category edit
        .state('categories.edit', 
             __globals.stateConfig('^/categories/:catID/edit', null, {
                controller  : 'CategoryEditDialogController',
                access  : { 
                },
                params: {
                    catID: { squash: true }
                }
              }
            )
        )

        // specificationList
        .state('specificationsPreset', 
             __globals.stateConfig('/specifications-preset', 'specification-preset/manage/list', {
                access  : { 
                },
              }
            )
        )

        // specificationsPreset add dialog
        .state('specificationsPreset.add', 
            __globals.stateConfig('/add', null, {
            access  : {},
            controller : 'PresetAddDialogController'
        } ))

        // specificationsPreset edit dialog
        .state('specificationsPreset.edit', 
            __globals.stateConfig('/:presetId/edit', null, {
            access  : {},
            controller : 'PresetEditDialogController'
        } ))

         // shipping type list
        .state('shippingType', 
             __globals.stateConfig('/shipping-type', 'shipping-type/manage/list', {
                access  : { 
                },
              }
            )
        )

        // shipping type add dialog
        .state('shippingType.add', 
            __globals.stateConfig('/add', null, {
            access  : {},
            controller : 'ShippingTypeAddDialogController'
        } ))

        // shippingType edit dialog
        .state('shippingType.edit', 
            __globals.stateConfig('/:shippingTypeId/edit', null, {
            access  : {},
            controller : 'ShippingTypeEditDialogController'
        } ))


        /*// edit 
        .state("categories.edit", 
            __globals.appStateConfig('categories.edit', {
            access      : { 
                designation : 1
            }
        }))*/

        // list productRating
        .state('productRating', 
             __globals.stateConfig('/product-rating', 'product-rating/list', {
                access  : { 
                }
              }
            )
        )

		// list brands
        .state('brands', 
             __globals.stateConfig('/brands', 'brand/manage/list', {
                access  : { 
                }
              }
            )
        )

        // add brands 
        .state('brands.add', 
             __globals.stateConfig('/add', null, {
             	controller  : 'BrandAddDialogController',
                access  : { 
                }
              }
            )
        )

          // category edit
        .state('brands.edit', 
             __globals.stateConfig('/:brandID/edit', null, {
                controller  : 'BrandEditDialogController',
                access  : { 
                }
              }
            )
        )

        // list coupons
        .state('coupons', 
             __globals.stateConfig('/coupons', 'coupon/manage/list', {
                access  : { 
                }
              }
            )
        )


        // list coupons current
        .state('coupons.current', 
             __globals.stateConfig('/current', 'coupon/manage/list', {
                access  : { 
                }
              }
            )
        )

        // list coupons expired
        .state('coupons.expired', 
             __globals.stateConfig('/expired', 'coupon/manage/list', {
                access  : { 
                }
              }
            )
        )

        // list coupons upcoming
        .state('coupons.upcoming', 
             __globals.stateConfig('/up-coming', 'coupon/manage/list', {
                access  : { 
                }
              }
            )
        )

        // list shippings
        .state('shippings', 
             __globals.stateConfig('/shipping', 'shipping/manage/list', {
                access  : { 
                }
              }
            )
        )

        // add shipping 
        .state('shippings.add', 
             __globals.stateConfig('/add', null, {
             	controller  : 'ShippingAddDialogController',
                access  : { 
                }
              }
            )
        )

        // edit coupon
        .state('shippings.edit', 
             __globals.stateConfig('/:shippingID/edit', null, {
                controller  : 'ShippingEditDialogController',
                access  : { 
                }
              }
            )
        )

        // list taxes
        .state('taxes', 
             __globals.stateConfig('/taxes', 'tax/manage/list', {
                access  : { 
                }
              }
            )
        )

        // add tax 
        .state('taxes.add', 
             __globals.stateConfig('/add', null, {
             	controller  : 'TaxAddDialogController',
                access  : { 
                }
              }
            )
        )

        // edit tax
        .state('taxes.edit', 
             __globals.stateConfig('/:taxID/edit', null, {
                controller  : 'TaxEditDialogController',
                access  : { 
                }
              }
            )
        )

        // add product
        .state('product_add', 
             __globals.stateConfig('/product/add', 'product/manage/add', {
                access  : { 
                }
              }
            )
        )

        // add category product
        .state('category_product_add', 
             __globals.stateConfig('/product/add/:categoryID', 'product/manage/add', {
                access  : { 
                }
              }
            )
        )
        
        // products
        /*.state('products', 
             __globals.stateConfig('/products', 'product/manage/list', {
                access  : { 
                }
              }
            )
        )*/

        // products by category id
       /*.state('category_products', 
             __globals.stateConfig('/:categoryID/category-products', 'product/manage/list', {
                access  : { 
                }
              }
            )
        )

       // products by brand id
       .state('brand_products', 
             __globals.stateConfig('/:brandId/brand-products', 'product/manage/list', {
                access  : { 
                }
              }
            )
        )*/


        // edit product
        .state('product_edit', 
             __globals.stateConfig('/product/:productID/edit', 'product/manage/edit', {
                access  : { 
                }
              }
            )
        )

        // edit product
        .state('product_edit.details', 
             __globals.stateConfig('/details', 'product/manage/edit-details', {
                access  : { 
                }
              }
            )
        )

        // product options
        .state('product_edit.options', 
             __globals.stateConfig('/options', 'product/manage/options/list', {
                access  : { 
                }
              }
            )
        )

         // product specification
        .state('product_edit.specification', 
             __globals.stateConfig('/specification', 'product/manage/specification/list', {
                access  : { 
                }
              }
            )
        )

		// edit product ratings
        .state('product_edit.ratings', 
             __globals.stateConfig('/ratings', 'product/manage/ratings/list', {
                access  : { 
                }
              }
            )
        )

        // edit product ratings
        .state('product_edit.faq', 
             __globals.stateConfig('/faqs', 'product/manage/faq/list', {
                access  : { 
                }
              }
            )
        )

        // edit product ratings
        .state('product_edit.awating_user', 
             __globals.stateConfig('/awating-user', 'product/manage/awating-user/list', {
                access  : { 
                }
              }
            )
        )

        // edit product seo meta
        .state('product_edit.seo_meta', 
             __globals.stateConfig('/seo-meta', 'product/manage/seo-meta/edit', {
                access  : { 
                }
              }
            )
        )

        // pages
        .state('pages', 
             __globals.stateConfig('/pages/:parentPageID?', 'pages/manage/list', {
                access  : { 
                },
                params: {
                    parentPageID: { squash: true, value: null }
                }
              }
            )
        )


        // add page
        .state('pages.add', 
             __globals.stateConfig('^/pages/add/', null, {
                controller  : 'ManagePagesAddDialogController',
                access  : { 
                }
              }
            )
        )

        // edit page
        .state('pages.edit', 
             __globals.stateConfig('^/pages/:pageID/edit', null, {
                controller  : 'ManagePagesEditDialogController',
                access  : { 
                }
              }
            )
        )

       // page_details
        .state('page_details', 
             __globals.stateConfig('/details/:pageID/:pageTitle', 'pages/manage/details', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit
        .state('store_settings_edit', 
             __globals.stateConfig('/setting', 'store/edit_settings', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit general 
        .state('store_settings_edit.general', 
             __globals.stateConfig('/general', 'store.general', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit products
        .state('store_settings_edit.product', 
             __globals.stateConfig('/product', 'store.product', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit placements
        .state('store_settings_edit.placement', 
             __globals.stateConfig('/placements', 'store.placement', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit currency
        .state('store_settings_edit.currency', 
             __globals.stateConfig('/currency', 'store.currency', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit order
        .state('store_settings_edit.order', 
             __globals.stateConfig('/order', 'store.order', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit order
        .state('store_settings_edit.payment', 
             __globals.stateConfig('/payment', 'store.payment', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit contact
        .state('store_settings_edit.contact', 
             __globals.stateConfig('/contact', 'store.contact', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit term_condition
        .state('store_settings_edit.user', 
             __globals.stateConfig('/users', 'store.user', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit privacy_policy
        .state('store_settings_edit.privacy_policy', 
             __globals.stateConfig('/privacy-policy', 'store.privacy-policy', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit social
        .state('store_settings_edit.social', 
             __globals.stateConfig('/social', 'store.social', {
                access  : { 
                }
              }
            )
        )

		// store_settings_edit.social_authentication_setup
        .state('store_settings_edit.social_authentication_setup', 
             __globals.stateConfig('/social-authentication-setup', 'store.social-login', {
                access  : { 
                }
              }
            )
        )

        // store_settings_edit language
        .state('store_settings_edit.language', 
             __globals.stateConfig('/language', null, {
             	controller  : 'LanguageController',
                access  : { 
                }
              }
            )
        )

        // configuration blog
        .state('store_settings_edit.email_settings', 
             __globals.stateConfig('/email-settings', 'store.email', {
                access  : {},
              }
            )
        )

        // configuration footer
        .state('store_settings_edit.manage_footer_settings', 
             __globals.stateConfig('/manage-footer', 'store.manage-footer', {
                access  : {},
              }
            )
        )

        // configuration footer
        .state('store_settings_edit.landing_page', 
             __globals.stateConfig('/landing-page', 'store.landing-page-settings', {
                controller  : 'LandingPageSettingController as landingPageSettingCtrl',
                access  : {},
              }
            )
        )

        // store_settings_edit css style
        .state('css_styles', 
             __globals.stateConfig('/css-style', 'store/css-style', {
                access  : { 
                }
              }
            )
        )

        // slider_setting 
        .state('slider_setting', 
             __globals.stateConfig('/slider-setting', 'slider/manage/list', {
                access  : { 
                }
              }
            )
        )

        // slider_setting add
        .state('slider_setting_add', 
             __globals.stateConfig('/slider-setting/add', 'slider/manage/add', {
                access  : { 
                }
              }
            )
        )

        // slider_setting edit Page
        .state('slider_setting_edit', 
            __globals.stateConfig('/slider-setting/:sliderID/edit', 'slider/manage/edit', {
            access  : {}
        } ))

        // product images
        .state('product_edit.images', 
             __globals.stateConfig('/images', 'product/manage/Images/list', {
                access  : { 
                }
              }
            )
        )

        // manage orders
        .state('orders', 
             __globals.stateConfig('/orders', 'order/manage/list', {
                access  : { 
                }
              }
            )
        )

        // manage order active
        .state('orders.active', 
            __globals.stateConfig('/active/:userID?', 'order/manage/list', {
                access  : { 
                },
                params: {
                    userID: { squash: true, value: null }
                }
              }
            )
        )

        // manage order invalid-cancelled
        .state('orders.cancelled', 
            __globals.stateConfig('/cancelled/:userID?', 'order/manage/list', {
                access  : { 
                },
                params: {
                    userID: { squash: true, value: null }
                }
              }
            )
        )

        // manage order active
        .state('orders.completed', 
            __globals.stateConfig('/completed/:userID?', 'order/manage/list', {
                access  : { 
                }
              }
            )
        )

        // order details
        .state('order_details', 
             __globals.stateConfig('/order/:orderId/details', 'order/manage/details-view', {
                access  : { 
                }
              }
            )
        )
        
        // manage report active
        .state('reports', 
            __globals.stateConfig('/report', 'report/manage/list', {
                access  : { 
                }
              }
            )
        )

        // manage order report active
        .state('order_report', 
            __globals.stateConfig('/order-report', 'report/manage/order-report', {
                access  : { 
                }
              }
            )
        )

        // manage payment report active
        .state('payment_report', 
            __globals.stateConfig('/payment-report', 'report/manage/payment-report', {
                access  : { 
                }
              }
            )
        )

        // manage product report active
        .state('product_report', 
            __globals.stateConfig('/product-report', 'report/manage/product-report', {
                access  : { 
                }
              }
            )
        )

        // manage payments list
        .state('payments', 
            __globals.stateConfig('/payments', 'order/manage/payment-list', {
                access  : { 
                }
              }
            )
        )

        
        // invalid request
        .state('invalid_request', __globals.stateConfig('/invalid-request',
            'errors/invalid-request'
        ))

        // not found
        .state('not_found', __globals.stateConfig('/not-found',
            'errors.manage-not-exist'
        ))

        // not exist
        .state('not_exist', __globals.stateConfig('/not-exist',
            'errors.manage-not-exist'
        ))

        // unauthorized
        .state('unauthorized', __globals.stateConfig('/unauthorized',
            'errors.unauthorized', {
            access      : {}
        }))


		 // google_social_doc
        .state('google_social_doc', 
             __globals.stateConfig('/google-setup-steps', 'store.google-help', {
                access  : { 
                    
                }
              }
            )
        )

        // facebook_social_doc
        .state('facebook_social_doc', 
             __globals.stateConfig('/facebook-setup-steps', 'store.facebook-help', {
                access  : {}
              }
            )
        )

        // twitter_social_doc
        .state('twitter_social_doc', 
             __globals.stateConfig('/twitter-setup-steps', 'store.twitter-help', {
                access  : {}
              }
            )
        )

		 // github_social_doc
        .state('github_social_doc', 
             __globals.stateConfig('/github-setup-steps', 'store.github-help', {
                access  : {}
              }
            )
        )

        // list discounts current
        .state('email_templates', 
             __globals.stateConfig('/email-templates', 'dynamic-mail-templates/email-template-list', {
             	controller  : 'EmailTemplateListController as emailTemplateListCtrl',
              }
            )
        )

        // list discounts current
        .state('show_template', 
             __globals.stateConfig('/:emailTemplateId/templates', 'dynamic-mail-templates/email-template-edit', {
             	controller  : 'EmailTemplateEditController as emailTemplateEditCtrl',
             	resolve: {
                    // editTemplateData: ["ConfigurationDataService", "$stateParams", function(ConfigurationDataService, $stateParams) {

                    //    return ConfigurationDataService
                    //             .fetchEmailTemplate($stateParams.emailTemplateId) // Dynamic Email Data
                    //             .then(function(response) { 
                    //                 return response;
                    //             });
                    // }]
                }
              }
            )
        )

        // RolePermission list
        .state('role_permission',
            __globals.stateConfig('/role-permissions', 'user/role-permission/list', {
            access  : {
                // authority:'manage.user.role_permission.read.list'
            },
            controller : 'RolePermissionListController as rolePermissionListCtrl'
        } ))

		;

    };

})();;
(function() {
'use strict';
	
	/*
	 ManageController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.master', [])
        .controller('ManageController', 	[
			'$rootScope',
            '__DataStore', 
            '$scope', 
            '__Auth', 
            'appServices', 
            'appNotify', 
			'$state',
            '$transitions',
			'__Utils',
            ManageController 
	 	]).controller('HelpController',    [
            '$rootScope',
            '$scope', 
            HelpController 
        ]);

  /**
	* ManageController for manage page application
	*
	* @inject $rootScope
	* @inject __DataStore
	* @inject $scope
	* @inject __Auth
	* @inject appServices
	* @inject appNotify
	* 
	* @return void
	*-------------------------------------------------------- */

	function ManageController($rootScope, __DataStore, $scope, __Auth,  appServices, appNotify, $state, $transitions, __Utils) {

	 	var scope 	= this;

        scope.pageStatus    = false;

	 	__Auth.refresh(function(authInfo) {

	 		scope.auth_info = authInfo;
        	
	 	});

	 	// update the total new order placed count
	 	$rootScope.$on('update.new.order.placed.count', function(event, data) {
	 		
			scope.newOrderPlacedCount = data.newOrderPlacedCount;
		});
			
        scope.unhandledError = function() {

              appNotify.error(__globals.getReactionMessage(19)); // Unhandled errors

        };

        // for toggling options in side bar filter
        scope.searchSidebarFilter = function(searchterm) {
        	if (_.isEmpty(searchterm)) {
        		$('#lwToggleSidebar .collapse').removeClass('show');
        	} else {
        		$('#lwToggleSidebar .collapse').addClass('show');
        	}
        }

        $rootScope.$on('lw.page.css_styles', function (event, data) {

            scope.pageCSSStyles = data.header_bg_color;

        });

	 	$rootScope.$on('lw.events.state.change_start', function () {
	 		appServices.closeAllDialog(); 
	 		var sw =  document.querySelector(".show-sweet-alert");
            
            if(sw) {
             	var okButton = $('.sweet-close');
             	$(okButton).trigger("click");  
            }
            scope.currentStateStatus = false;
        });

	 	$rootScope.$on('lw.auth.event.reset', function (event, authInfo) {
	 		scope.auth_info = authInfo;             
        });

	 	$rootScope.$on('lw.form.event.process.started', function (event, data) {

	 		$('button[type="submit"] span').addClass('fa fa-spinner fa-spin');
	 		$('a, button').prop("disabled", true);
        	$('button[type="submit"]').prop("disabled", true);

    	});

	 	$rootScope.$on('lw.form.event.process.finished', function (event, data) {

        	$('button[type="submit"] span').removeClass('fa fa-spinner fa-spin');
        	$('button[type="submit"]').prop("disabled", false);
        	$('a, button').prop("disabled", false);

    	} );

        $rootScope.$on('lw.form.event.fetch.started', __globals.showFormLoader );

        $rootScope.$on('lw.datastore.event.fetch.finished', __globals.hideFormLoader );

        $rootScope.$on('lw.form.event.process.error', scope.unhandledError );

        $rootScope.$on('lw.datastore.event.fetch.error', scope.unhandledError );

        scope.showUploadManagerDialog  =  function() {
            appServices.showDialog(scope, {
                templateUrl : __globals.getTemplateURL('upload-manager.upload-manager-dialog')
            },
            function(promiseObj) {

            });
        };

        $transitions.onSuccess({}, function($stateEvent) {
            var $stateInfo = $stateEvent.router.stateService.current;
            var exceptStates = [
                'store_settings_edit',
                'store_settings_edit.general',
                'store_settings_edit.product',
                'store_settings_edit.placement',
                'store_settings_edit.currency',
                'store_settings_edit.order',
                'store_settings_edit.contact',
                'store_settings_edit.term_condition',
                'store_settings_edit.privacy_policy',
                'store_settings_edit.social',
                'store_settings_edit.social_authentication_setup',
                'store_settings_edit.language',
                'store_settings_edit.css-style'
            ],
            scrollOffsets  = __globals.getScrollOffsets(),
            yOffset = Math.round(scrollOffsets.y);

            if(!_.includes(exceptStates, $stateInfo.name)) {
                $('html, body').animate({scrollTop:0}, yOffset < 500 ? 500 : yOffset);
            };
        });
        
        /**
          * Open help dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.openHelpDailog = function(templateUrl, templateTitle) {

            scope.templateTitle = templateTitle;

            appServices.showDialog(scope,
            {
                templateUrl  : __globals.getTemplateURL(templateUrl)
            },
            function(promiseObj) {
               
               

            });

        };

		$rootScope.$on('lw-open-login-dialog', function (event, response) {

			event.preventDefault();

			if (!($("html").hasClass("lw-manage-login-page-active"))) {

        		$('html').addClass('lw-manage-login-page-active');
	            
	            appServices.loginRequiredDialog('login-dialog', response.data, function(result, newData) {
	            	
	            	$('html').removeClass('lw-manage-login-page-active');
	                __DataStore.reset();
					
					if (result) {
				
						$state.reload();

					}

				});
        	}	

        });

        
        $.typeahead({
            input: '.lw-module-search',
            minLength: 1,
            order: "asc",
            hint : false,
            dynamic: true,
            group: true,
			delay: 500,
			searchOnFocus: false,
            emptyTemplate: "no result found",
            source: {
                'Application Settings': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "General Settings",
                            "state"    : "store_settings_edit.general",
                            "keys"     : "Store Name, Logo, Invoice Logo, Theme Colors, Business Email, Default Home Page Content, Language, TimeZone, Store Address"
                        },
                        {
                            "keywords" : "Order Settings",
                            "state"    : "store_settings_edit.order",
                            "keys"     : "Enable Guest Order, Apply Tax, Calculate Tax, Customer Order"
                        },
                        {
                            "keywords" : "Payment Settings",
                            "state"    : "store_settings_edit.payment",
                            "keys"     : "Paypal, Check, Bank Transfer, Cod, Stripe, Razorpay, Iyzico, Other, Paytm"
                        },
                        {
                            "keywords" : "Currency Settings",
                            "state"    : "store_settings_edit.currency",
                            "keys"     : "Currency, Code, Symbol, Format, Multiple Currencies, Auto Refresh, Currency Markup"
                        },
                        {
                            "keywords" : "Product Settings",
                            "state"    : "store_settings_edit.product",
                            "keys"     : "Out Of Stock, Paginated Product, Per Page On List, Enable Rating, Review, Buyers Can Add Rating, Facebook, Twitter, Social Sharing Links"
                        },
                        {
                            "keywords" : "User Settings",
                            "state"    : "store_settings_edit.user",
                            "keys"     : "Register, New User, Change Email, Product Wishlist, Recaptcha, captcha, Terms & Conditions"
                        },
                        {
                            "keywords" : "Social Links Settings",
                            "state"    : "store_settings_edit.social",
                            "keys"     : "Facebook Username, Twitter Handle, Social Links"
                        },
                        {
                            "keywords" : "Contact Settings",
                            "state"   : "store_settings_edit.contact",
                            "keys"     : "Contact Form, Address, Telephone, Contact Page"
                        },
                        {
                            "keywords" : "Placement and Misc Settings",
                            "state"   : "store_settings_edit.placement",
                            "keys"     : "Categories Menu, Brand Menu, Credit Info, Google Analytics, Addition Footer, Website Notification, Append Email Message"
                        },
                        {
                            "keywords" : "Social Login Settings",
                            "state"   : "store_settings_edit.social_authentication_setup",
                            "keys"     : "Facebook Login, Google Login, Twitter Login, Github Login"
                        },
                        {
                            "keywords" : "Privacy Policy Settings",
                            "state"   : "store_settings_edit.privacy_policy",
                            "keys"     : "Privacy"
                        },
                        {
                            "keywords" : "Email Settings",
                            "state"   : "store_settings_edit.email_settings",
                            "keys"     : "Mail From Address, Mail From Name, Mail Driver, Port, Host, Mail Username, Mail Encryption, Mail Password, Mail Domain, Mailgun Password, Mail Endpoint"
                        },
                        {
                            "keywords" : "Footer Settings",
                            "state"    : "store_settings_edit.manage_footer_settings",
                            "keys"     : "Header Advertisement, Footer Advertisement",
                            "keys"     : "Footer Editor"
                        },
                        {
                            "keywords" : "Landing Page Settings",
                            "state"    : "store_settings_edit.landing_page",
                            "keys"     : "Slider, Page Content, Latest Product, Featured Product"
                        },
                        {
                            "keywords" : "Slider Settings",
                            "state"   : "slider_setting",
                            "keys"     : "Add New Slider, Image Slide, Enable autoplay, Autoplay Speed Product, Edit New Slider"
                        },
                        {
                            "keywords" : "CSS Style",
                            "state"    : "css_styles",
                            "keys"     : "CSS Style"
                        },
                        {
                            "keywords" : "Manage Email Templates",
                            "state"    : "css_styles",
                            "keys"     : "Template, Name, Email"
                        }
                    ]
                },
                'Manage Products': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Brands",
                            "state"    : "brands",
                            "keys"     : "Add Brand, Edit Brand, Status, Logo"
                        },
                        {
                            "keywords" : "Manage Categories",
                            "state"    : "categories",
                            "keys"     : "Add Category, Parent Category, Status, Edit Category, Subcategories, Add Products"
                        },
                        {
                            "keywords" : "Manage Products",
                            "state"    : "products",
                            "keys"     : "Add Product, Product ID, Mark as Featured, Out of Stock, Comming Soon, Launching On, Available, Categories, Brand, Image, Youtube Video, Old Price, Description, Related Products, Thumbnail, Manage Options, Manage Images, Manage Specifications, Manage Ratings, Manage FAQs, Manage Awating User, Send Notification"
                        },
                        {
                            "keywords" : "Manage Sepcification Presets",
                            "state"    : "specificationsPreset",
                            "keys"     : "Add New Preset, Edit Preset"
                        }
                    ]
                },
                'Manage Reports and Orders': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Orders",
                            "state"    : "orders.active",
                            "keys"     : "Order ID, Contact User, Order Log, Contact User, Update Payment, Payment Method, Order Status, Refund, Transaction ID, Comment, Completed, Active, Cancelled"
                        },
                        {
                            "keywords" : "Manage Order Payments",
                            "state"    : "payments",
                            "keys"     : "Order ID, Transaction ID, Payment Method, Fee, Total Amount"
                        },
                        {
                            "keywords" : "Order Reports",
                            "state"    : "order_report",
                            "keys"     : "Order ID, Name, Order Status, Payment Status, Placed On, Total Amount"
                        },
                        {
                            "keywords" : "Payment Reports",
                            "state"    : "payment_report",
                            "keys"     : "Credit Amount, Debit Amount, Difference Amount"
                        },
                        {
                            "keywords" : "Product Report",
                            "state"    : "product_report",
                            "keys"     : "Product Report, Qty, Title"
                        }
                    ]
                },
                'Other': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Pages",
                            "state"    : "pages",
                            "keys"     : "Add Page, Title, Type, Description, Parent Page, Add to menu, Hide Sidebar, Active, List Order"
                        },
                        {
                            "keywords" : "Manage Users",
                            "state"    : "users",
                            "keys"     : "New User, First Name, Last Name, Email, Role, Password, Password Confirmation, Change Password, User Order, User Details, Permission, Contact, Active, Deleted, Never Activated"
                        },
                        {
                            "keywords" : "Manage Roles",
                            "state"    : "role_permission",
                            "keys"     : "New User Role, Title, Preset, Permission"
                        }
                    ]
                },
                'Manage Reports': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Order Report",
                            "state"    : "order_report",
                            "keys"     : "Order Report, Order Payment Report, Order ID, Payment Status, Total, Name, Generate Excel"
                        },
                        {
                            "keywords" : "Payment Report",
                            "state"    : "payment_report",
                            "keys"     : "Payment Report, Credit Amount, Debit Amount, Currency, Difference Amount, Generate Excel"
                        },
                        {
                            "keywords" : "Product Report",
                            "state"    : "product_report",
                            "search_term"   : "paypal",
                            "keys"     : "Product Report, Title, Qty"
                        }
                    ]
                },
                'Manage Coupons / Discounts': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Coupons",
                            "state"    : "coupons",
                            "keys"     : "Add New Coupon, Discount, Title, Coupon Code, Discount Type"
                        },
                        {
                            "keywords" : "Expired Coupons",
                            "state"    : "coupons.expired",
                            "keys"     : "Expired Coupons"
                        },
                        {
                            "keywords" : "Up-coming Coupons",
                            "state"    : "coupons.upcoming",
                            "keys"     : "Up-coming Coupons, Upcoming Coupons"
                        },
                    ]
                },
                'Manage Shipping Rules': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Shipping Rules",
                            "state"    : "shippings",
                            "keys"     : "Add Shipping Rule, Edit Shipping Rule, Countries, Charges, Charge Type"
                        }
                    ]
                },
                'Manage Shipping Method': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Shipping Method",
                            "state"    : "shippingType",
                            "keys"     : "Add Shipping Method, Edit Shipping Method"
                        }
                    ]
                },
                'Manage Taxes': {
                    display: ["keywords", "keys"],
                    data: [
                        {
                            "keywords" : "Manage Taxes",
                            "state"    : "taxes",
                            "keys"     : "Add Tax, Edit Tax, Tax Settings,  Apply Tax"
                        }
                    ]
                },
                
                'search results' : {
                    display: "name",
                    ajax: function (query, callback) {

                        return {
                            type : "GET",
                            path : "searchResult",
                            url  : __Utils.apiURL({
                                        'apiURL'        : 'product.search.term',
                                        'searchQuery'   : query
                                    }),
                            callback: {
                                done: function (responseData) {

                                	_.map(responseData.data.searchResult, function(result) {

                                		var state = '',
                                			params = {};
										switch(result.type) {
											case 1:
												state = 'product_edit.details';
												params = {productID : result.id};
											break;
											case 2:
												state = 'categories';
												params = {mCategoryID : result.id};
											break;
											case 3:
												state = 'brands.edit';
												params = {brandID : result.id};
											break;
											default:
												state = '';
												params = {};
										} 
 										result['state'] = state;
 										result['params'] = params;
                                	});

                            		return responseData.data;
                                }
                            }
                        }
                    },
                }
            },
            callback: {
                onClick: function (node, a, item, event) {
                    // You can do a simple window.location of the item.href
                    $state.go(item.state, item.params);
        
                }
            },
            debug: true
        });

	};

    /**
    * HelpController for helping information
    *
    * @inject $rootScope
    * @inject $scope
    * 
    * @return void
    *-------------------------------------------------------- */

    function HelpController($rootScope, $scope) {

        var scope  = this;

		if (_.has($scope.ngDialogData, 'templateTitle')) {
			   scope.templateTitle =  $scope.ngDialogData.templateTitle;
		}

		var $lwCopyToClipboardJS = new ClipboardJS('.lw-copy-action');

		$lwCopyToClipboardJS.on('success', function(ele) {

			$(ele.trigger).attr("title", "Copied!");

			ele.clearSelection();

		});
     

        /**
          * Close dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
            $scope.closeThisDialog();
        };

    };

})();;
/*!
 *  Engine      : UploadManagerEngine 
 *  Component   : UploadManager
----------------------------------------------------------------------------- */
(function( window, angular, undefined ) {

'use strict';

    /**
      * UploadManagerEngine
      *
      * @inject object __DataStore       -  js-utils data store service for sent http request
      * @inject object appServices       -  app common service
      * 
      * @return void
      *---------------------------------------------------------------------- */

    angular
        .module('uploadManagerEngine', [])

        /**
          * UploadManagerController
          *
          * @inject object __DataStore       -  js-utils data store service for sent http request
          * @inject object appServices       -  app common service
          * 
          * @return void
          *---------------------------------------------------------------------- */

        .controller('UploadManagerController',[
            '__DataStore',
            'appServices',
            function (__DataStore, appServices) {
                

            }
        ])

        /**
          * UploadManagerDialogController
          *
          * @inject object __DataStore       -  js-utils data store service for sent http request
          * @inject object appServices       -  app common service
          * 
          * @return void
          *---------------------------------------------------------------------- */
          
        .controller('UploadManagerDialogController',[
            '__DataStore',
            'appServices',
            'FileUploader',
            '__Utils',
            '$scope',
            function (__DataStore, appServices, FileUploader, __Utils, $scope) {
                
                var scope    = this,
                    uploader = scope.uploader = new FileUploader({
                    url         : __Utils.apiURL('upload_manager.upload'),
                    autoUpload  : true,
                    headers     : {
                        'X-XSRF-TOKEN': __Utils.getXSRFToken()
                    }
                });

                scope.close = function() {
                    $scope.closeThisDialog();
                };

                /**
                  * Fetch upload manager files
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.getFiles = function() {

                    __DataStore.fetch('upload_manager.files', { fresh : true })
                        .success(function(responseData) {
                            
                        appServices.processResponse(responseData, null, function() {
                            scope.files = responseData.data.files
                        });    

                    });

                };

                scope.getFiles();

                // FILTERS
                uploader.filters.push({
                    name: 'customFilter',
                    fn: function(item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < 1000;
                    }
                });

                // CALLBACKS
                uploader.onCompleteItem = function(fileItem, response, status, headers) {
                    appServices.processResponse(response, null, function() {
                    });
                    
                };

                uploader.onCompleteAll = function() {
                    scope.getFiles();
                };


                /**
                  * Select image
                  *
                  * @return void
                  *-------------------------------------------------------- */

                scope.selectImage = function(imgURL) {

                    var imgTag = '<img src="'+imgURL+'"/>',
                        editor = CKEDITOR.instances['description'];
                    editor.insertHtml(imgTag);

                    scope.close(); // close dialog

                };

                /**
                  * Select document
                  *
                  * @return void
                  *-------------------------------------------------------- */

                scope.selectDocument = function(fileURL, fileName) {

                    var linkTag = '<a href="'+fileURL+'"/>'+fileName+'</a>',
                        editor  = CKEDITOR.instances['description'];

                    editor.insertHtml(linkTag);

                    scope.close(); // close dialog

                };

                /**
                  * Delete file
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.delete = function(fileName) {

                    __DataStore.post({
                        'apiURL'    : 'upload_manager.delete',
                        'fileName'  : fileName
                    })
                    .success(function(responseData) {
                            
                        appServices.processResponse(responseData, null, function() {
                            scope.getFiles();
                        });    

                    });

                };

            }
        ])

        ;

})(window, window.angular);;
(function() {
'use strict';
    
    /*
       ManageUsersController Module
      ----------------------------------------------------------------------- */
    
    angular
        .module('UserApp.manage', [])
        .controller('ManageUsersController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            ManageUsersController 
        ]).controller('UserAddController',   [
            '$scope', 
            '__Form',
            'appServices','__DataStore',
            UserAddController 
        ]).controller('ContactUserController',   [
            '$scope', 
            '__Form',
            'appServices','__DataStore',
            ContactUserController 
        ])
        

        /**
          * Manage User Dynamic Permissions
          *
          * @inject $scope
          * @inject __Form
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('ManageUsersDynamicPermissionController',   [
            '$scope',
            '__Form',
            '__DataStore',
            'appServices',
            function ManageUsersDynamicPermissionController($scope, __Form, __DataStore, appServices) {

                var scope   = this;
                
                scope  = __Form.setup(scope, 'user_dynamic_access', 'accessData', {
                    secured : true,
                    unsecuredFields : []
                });
                
                scope.ngDialogData  = $scope.ngDialogData;
                scope.userId  = scope.ngDialogData.userId;
                scope.fullName = scope.ngDialogData.userFullName;
                scope.requestData   = scope.ngDialogData.requestData;
                scope.permissions = scope.requestData.permissions;
                scope.userRoleTitle  = scope.ngDialogData.requestData.userRoleTitle;
            
             	scope.accessData.allow_permissions = scope.requestData.allow_permissions;
				scope.accessData.deny_permissions = scope.requestData.deny_permissions;
				scope.accessData.inherit_permissions = scope.requestData.inherit_permissions;

                scope.disablePermissions = function(eachPermission, permissionID) {

                    _.map(eachPermission.children, function(key) {
                        if (_.includes(key.dependencies, permissionID)) {
                            _.delay(function(text) {
                                $('input[name="'+key.id+'"]').attr('disabled', true);
                            }, 500);
                        }
                    });

                }

				scope.checkedPermission = {};

				_.map(scope.accessData.allow_permissions, function(permission) {
					scope.checkedPermission[permission] = "2";
				});

				_.map(scope.accessData.deny_permissions, function(permission) {
					scope.checkedPermission[permission] = "3";


                    _.map(scope.permissions, function(eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');
                        
                        if (_.includes(pluckedIDs, permission)) {
                            scope.disablePermissions(eachPermission, permission)
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {
                             
                            _.map(eachPermission.children_permission_group, function(groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');
                        
                                if (_.includes(pluckedIDs, permission)) {
                                    scope.disablePermissions(groupchild, permission)
                                }
                            });
                        }
                    });

				});

				_.map(scope.accessData.inherit_permissions, function(permission) {
					scope.checkedPermission[permission] = "1";

                    _.map(scope.permissions, function(eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');
                         
                        if (_.includes(pluckedIDs, permission) && eachPermission.children[0].inheritStatus == false && eachPermission.children[0].result == "1") {
                            scope.disablePermissions(eachPermission, permission);
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {
                             
                            _.map(eachPermission.children_permission_group, function(groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');
                        
                                if (_.includes(pluckedIDs, permission) && groupchild.children[0].inheritStatus == false && groupchild.children[0].result == "1") {
                                    scope.disablePermissions(groupchild, permission);
                                }

                            });
                        }
                    });
				});
                    
                //for updating permissions
                scope.checkPermission = function(childId, status) {
 					
 					if (!_.isString(status)) {
 						status = status.toString();
 					}

 					scope.checkedPermission[childId] = status;
 					
                 	if (status == "2") {
                		if(!_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions.push(childId);
                		}
                 		if (_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                 		}
                	} else if (status == "3")  {

	                   	if(!_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions.push(childId);
                		}
                 		if (_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                 		}
                	} else {
                		if (_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                 		}
						if (_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                 		}
                	}

                	_.map(scope.permissions, function(permission) {

                        var pluckedIDs = _.pluck(permission.children, 'id'), 
                        keyPermissions = [];

                        if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {
                            
                            _.map(permission.children, function(key) {
                                if (_.includes(key.dependencies, childId) && status == "3") {
                                    
                                    $('input[name="'+key.id+'"]').attr('disabled', true);

                                }  else if (_.includes(key.dependencies, childId) && status == "1" && permission.children[0].result && permission.children[0].inheritStatus == false) {
               
                                            $('input[name="'+key.id+'"]').attr('disabled', true);

                                        }
                                else {
                                    $('input[name="'+key.id+'"]').attr('disabled', false);
                                }

                            });

                        }
                        
						if (_.has(permission, 'children_permission_group')) {
 			 		 		_.map(permission.children_permission_group, function(groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'),
                                keyPermissionsGroup = [];

                                //for disabling options if read option  in denied
                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {
                            
                                    _.map(groupchild.children, function(groupchildkey) {
                                        if (_.includes(groupchildkey.dependencies, childId) && status == "3") {
                                            $('input[name="'+groupchildkey.id+'"]').attr('disabled', true);
 
                                        } else if (_.includes(groupchildkey.dependencies, childId) && status == "1" && groupchild.children[0].result && groupchild.children[0].inheritStatus == false) {
               
                                            $('input[name="'+groupchildkey.id+'"]').attr('disabled', true);

                                        }  else {
                                            $('input[name="'+groupchildkey.id+'"]').attr('disabled', false);
                                        }
                                        
                                         
                                    });

                                }
							})
					 	}
					})
              	}
              	
            
                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function() {
                    __Form.process({
                        'apiURL' : 'manage.user.write.user_permissions',
                        'userId' : scope.userId
                    }, scope)
                        .success(function(responseData) {
                        appServices.processResponse(responseData, null, function() {
                            // close dialog
                            $scope.closeThisDialog();
                        });    
                    });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
                        
            }
        ])


    /**
      * ManageUsersController - show all store users & we can perform actions -
      * on this users
      *
      * @inject __Form
      * @inject __DataStore
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageUsersController($scope, __DataStore, appServices) {

        var dtUsersColumnsData = [
            {
                "name"      : "name",
                "template"  : "#userNameColumnTemplate",
                "orderable" : true
            },
            {
                "name"      : "email",
                "orderable" : true
            },
            {
                "name"      : "role_title",
                "orderable" : true
            },
            {
                "name"      : "creation_date",
                "orderable" : true,
                "template"    : "#creationDateColumnTemplate"
            },
            {
            	"name"		: "last_login",
            	"orderable" : true,
                "template"  : "#userLastLoginColumnTemplate"
            },
            {
                "name"      : null,
                "template"  : "#userActionColumnTemplate"
            }
        ],
        dtDeletedUserColumndata = [
            {
                "name"      : "name",
                "template"  : "#userNameColumnTemplate",
                "orderable" : true
            },
            {
                "name"      : "email",
                "orderable" : true
            },
            {
                "name"      : "role_title",
                "orderable" : true
            },
            {
                "name"      : "deleted_on",
                "orderable" : true
            },
            {
                "name"      : "last_login",
                "orderable" : true,
                "template"  : "#userLastLoginColumnTemplate"
            },
            {
                "name"      : null,
                "template"  : "#userActionColumnTemplate"
            }
        ],
        tabs    = {
            'activeTab'    : {
                id      : 'activeUsersTabList',
                status  : 1
            },
            'deleted'    : {
                id      : 'deletedUsersTabList',
                status  : 5
            },
            'never_activated'    : {
                id      : 'neverActivatedUsersTabList',
                status  : 4
            }
        },
        scope   = this;


        // Manage users tab action
        // When clicking on tab, its related tab data load on same page

        $('#manageUsersTabs a').click(function (e) {

            e.preventDefault();

            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];

            // Check if selected tab exist    
            if (!_.isEmpty(selectedTab)) {

                $(this).tab('show')

                scope.getUsers(selectedTab.id, selectedTab.status);

            }
            
        });

        /**
          * Get users as a datatable source  
          *
          * @param string tableID
          * @param number status
          *
          * @return void
          *---------------------------------------------------------------- */
                        
        scope.getUsers   = function(tableID, status) {

            // destroy if existing instatnce available
            if (scope.usersListDataTable) {
                scope.usersListDataTable.destroy();
            }

            scope.usersListDataTable = __DataStore.dataTable('#'+tableID, { 
                url         : {
                    'apiURL'    : 'manage.user.list',
                    'status'    : status
                }, 
                dtOptions   : {
                    "searching" : true,
					"order"     : [[ 2, "desc" ]]
                },
                columnsData : status === 5 ? dtDeletedUserColumndata : dtUsersColumnsData, 
                scope       : $scope

            }, null, function(options) {
                scope.isDemoMode = options._options.isDemoMode;
            });

        };

        // load initial data for first tab
        //scope.getUsers('activeUsersTabList', 1);
        var selectedTab = $('.nav li a[href="#activeTab"]');

        selectedTab.triggerHandler('click', true);

        /*
          Reload current datatable
          ------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.usersListDataTable);
        };

        /**
          * Delete user 
          *
          * @param number userID
          * @param string userName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(userID, userName) {
        	
            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('user_delete_confirm_text'), {
                        '__name__'    : unescape(userName)
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            },
            function() {

                __DataStore.post({
                    'apiURL'  : 'manage.user.delete',
                    'userID'  : userID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {

                            error : function() {

                                __globals.showConfirmation({
                                    title   : 'Deleted!',
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                title   : 'Deleted!',
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });

        };

        /**
          * Restore deleted user 
          *
          * @param number userID
          * @param string userName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.restore = function(userID, userName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('user_restore_confirm_text'),{
                        '__name__'    : userName
                    }
                ),
                confirmButtonText   : __globals.getJSString('restore_action_button_text')
            },
            function() {

                __DataStore.post({
                    'apiURL'  : 'manage.user.restore',
                    'userID'  : userID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {

                            error : function() {
                                __globals.showConfirmation({
                                    title   : 'Restore!',
                                    text    : message,
                                    type    : 'error'
                                });
                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                    title   : 'Restore!',
                                    text    : message,
                                    type    : 'success'
                                });
                            scope.reloadDT();   // reload datatable

                        });    

                    });
                }
            );

        };

        /**
          * Change password of user by Admin 
          *
          * @param number userID
          * @param number name
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.changePassword = function (userID, name) {
        	
        	// open change password dialog
			appServices.showDialog({
					userID : userID,
        			name   : unescape(name)
        		},
		        {	
		            templateUrl : __globals.getTemplateURL('user.manage.change-password')
		        },
		        function(promiseObj) {

		        });
        };

        /**
          * Change password of user by Admin 
          *
          * @param number userID
          * @param number name
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.getUserDetails =function (userID) {

        	__DataStore.fetch({
        		apiURL : 'manage.users.get.detail',
        		userID : userID
        	})
        	.success(function(responseData) {

        		var requestData = responseData.data;
        		
        		appServices.processResponse(responseData, null, function() {
        			
        			appServices.showDialog(responseData.data, {

        				templateUrl : __globals.getTemplateURL('user.manage.user-details')
        			},
        			function(promiseObj){

        			});
        		});
        	});
        };


        /**
          * user permission dialog
          * 
          * @return void
          *---------------------------------------------------------------- */
		scope.openPermissionDialog = function(userID, userFullName) {

			__DataStore.fetch({
	        	'apiURL'	: 'manage.user.read.user_permissions',
	        	'userId' : userID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

    	   		appServices.processResponse(responseData, null, function() {

			    	appServices.showDialog({
	    	   			'requestData' : requestData,
	    	   			'userId'	: userID,
	    	   			'userFullName' : userFullName
	    	   		},
			        {	
			            templateUrl : __globals.getTemplateURL('user.manage.user-dynamic-permission'),
			            controller: 'ManageUsersDynamicPermissionController as manageUsersDynamicPermissionCtrl',
			        },
			        function(promiseObj) {

			        });
			    });
	       });

		}

        /**
          * Contact user dialog
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.contactDialog = function (id) {

        	appServices.showDialog({
        		'userId': id},
            {
                templateUrl : __globals.getTemplateURL(
                    'user.manage.contact'
                )
            },
            function(promiseObj) {

            });
        };

        /**
          * Add new user dialog
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.addNewUser = function (id) {

            appServices.showDialog(scope,
            {
                templateUrl : __globals.getTemplateURL(
                    'user.manage.add'
                )
            },
            function(promiseObj) {
                  
                if (_.has(promiseObj.value, 'user_added') 
                    && promiseObj.value.user_added == true) {
                    scope.reloadDT();
                }
                
            });
        };

    };

    function UserAddController($scope, __Form, appServices, __DataStore) {

        var scope  = this;
            scope  = __Form.setup(scope, 'add_new_user_form', 'userData');

            __DataStore.fetch('manage.user.add.read.supportData')
            .success(function(responseData) {

                var requestData = responseData.data;
                
                appServices.processResponse(responseData, null, function () {

                     scope.userRoles = requestData.userRoles;
                    
                });
            });
            
            /**
              * Submit register form action
              *
              * @return void
              *---------------------------------------------------------------- */
            
            scope.submit = function() {

                 __Form.process('manage.user.add', scope)
                    .success(function(responseData) {
                        
                    appServices.processResponse(responseData, null, function() {
                        
                        $scope.closeThisDialog({'user_added':true});
                    });    

                });

            };

            /**
            * Close dialog
            *
            * @return void
            *---------------------------------------------------------------- */
            scope.closeDialog = function() {
                $scope.closeThisDialog({'user_added':false});
            };
    };

    function ContactUserController($scope, __Form, appServices, __DataStore) {

    	var scope 	 = this;

    	 	scope    = __Form.setup(scope, 'contact_form', 'userData', {
		            secured : true,
                    unsecuredFields : ['message'],
		        });

	        // get ng dialog data
	        scope.ngDialogData = $scope.ngDialogData;

	        __DataStore.fetch({
        		'apiURL' : 'manage.user.contact.info',
        		'userId' : scope.ngDialogData.userId
        	})
        	.success(function(responseData) {

        		var requestData = responseData.data;

        		appServices.processResponse(responseData, null, function () {

        			scope.userData = requestData;

    				scope   = __Form.updateModel(scope, scope.userData)
        			
        		});
        	});
    		
	        /**
	          * Submit register form action
	          *
	          * @return void
	          *---------------------------------------------------------------- */
	        
	        scope.submit = function() {

	        	 __Form.process('manage.user.contact.process', scope)
	                .success(function(responseData) {
	                    
	                appServices.processResponse(responseData, null, function() {
	                	
	                	$scope.closeThisDialog();
	                });    

	            });

	        };

	        /**
	  	  	* Close dialog
	  	  	*
	  	  	* @return void
	  	  	*---------------------------------------------------------------- */
			scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};
	};

})();;
(function() {
'use strict';
    
    /*
     ManageUserChangePasswordController module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.manageUserChangePassword', [])
        .controller('ManageUserChangePasswordController',   [
        	'$scope',
            '__Form', 
            'appServices',
            ManageUserChangePasswordController 
        ]);

    /**
      * ManageUserChangePasswordController handle change password by admin
      * 
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageUserChangePasswordController($scope,__Form, appServices) {

        var scope   = this;

        scope = __Form.setup(scope, 'change_password_form', 'changePasswordData', {
        			secured : true
                });
        
        scope.ngDialogData = $scope.ngDialogData;

        scope.title = __ngSupport.getText(
                    __globals.getJSString('user_change_password_title_text'), {
                        '__name__'    : unescape(scope.ngDialogData.name)
                    });

        // get id of user
        scope.userID = scope.ngDialogData.userID;


        /*
	 	 Submit form action
	 	-------------------------------------------------------------------------- */

	 	scope.submit = function() {
            
            __Form.process({
                'apiURL'    : 'manage.user.change_password.process',
                'userID' : scope.userID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                	
                	// close dialog
	      			$scope.closeThisDialog();

                });    

            });

	  	};

	  	/**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

  	  	scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};
    };

})();;
(function() {
'use strict';
    
    /*
     ManageUserDetailsDialog module
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.userDetailDialog', [])
        .controller('ManageUserDetailsDialog',   [
            '$scope',
            ManageUserDetailsDialog 
        ]);

    /**
      * ManageUserDetailsDialog for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
    function ManageUserDetailsDialog($scope) {

        var scope   = this;
       
        scope.ngDialogData  = $scope.ngDialogData;
        scope.userDetails 	= scope.ngDialogData;
        
	/**
	  * Close dialog
	  *
	  * @return void
	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
	  		$scope.closeThisDialog();
	  	};
	            
    };

})();;
(function() {
'use strict';
    
    /*
     UserProfileController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.profile', [])

        /**
          * UserProfileController - edit user profile
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserProfileController',   [
            '__Form', 
            'appServices',
            function (__Form, appServices) {

                var scope   = this;

                scope = __Form.setup(scope, 'user_profile__form', 'profileData');

                scope.request_completed = false;

                __Form.fetch('user.profile.details').success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        __Form.updateModel(scope, responseData.data.profile);
                        scope.request_completed = true;
                    });

                });

            }

        ]);

})();;
(function() {
'use strict';
    
    /*
     UserProfileEditController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.profileEdit', [])

        /**
          * UserProfileEditController - edit user profile
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserProfileEditController',   [
            '__Form', 
            'appServices',
            function (__Form, appServices) {

                var scope   = this;

                scope = __Form.setup(scope, 'user_profile_edit_form', 'profileData');

                scope.request_completed = false;

                __Form.fetch('user.profile.details').success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        __Form.updateModel(scope, responseData.data.profile);
                        scope.request_completed = true;
                    });    

                });

                /**
                  * Submit profile edit form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function() {
                    
                    __Form.process('user.profile.update', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                        });    

                    });

                };

            }
             
        ]);

})();;
(function() {
'use strict';
    
    /*
     UserChangePasswordController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.changePassword', [])

        /**
          * UserChangePasswordController - change user password
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserChangePasswordController',   [
            '__Form', 
            'appServices',
            '__Utils',
            '$state',
            function (__Form, appServices, __Utils, $state) {

                var scope   = this;

                scope = __Form.setup(scope, 'user_password_update_form', 'userData', {
                    secured : true
                });

                /**
                  * Submit update password form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    __Form.process('user.change_password.process', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            scope.userData = {};
                            
                            if (document.location.href == responseData.data.passwordRoute) {
                            	window.location = window.appConfig.appBaseURL;
                            } else {
                            	$state.go('dashboard');
                            }
                            
                        });    

                    });

                };

            } 
        ]);

})();;
(function() {
'use strict';
    
    /*
     UserChangeEmailController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.changeEmail', [])

        /**
          * UserChangeEmailController - handle chnage email form view js scope
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserChangeEmailController',   [
            '__Form', 
            'appServices',
            function (__Form, appServices) {

                var scope   = this;

                scope.requestSuccess  = false;
                scope.pageStatus = false;

                scope = __Form.setup(scope, 'user_change_email_form', 'userData', {
                    secured : true
                });

                /**
                  * Fetch support data
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                __Form.fetch('user.change_email.support_data')
                    .success(function(responseData) {
                
                    var requestData     = responseData.data;
                    
                    appServices.processResponse(responseData, null, function() {
                            
                        scope.changeEmail = requestData.newEmail;
                        scope.formattedDate = requestData.formattedDate;
                        scope.humanReadableDate = requestData.humanReadableDate;

                        scope.pageStatus = true;
                        
                    });    

                });
                
                /**
                  * Submit change email form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    __Form.process('user.change_email.process', scope)
                    .success(function(responseData) {

                        var requestData = responseData.data;

                        appServices.processResponse(responseData, null,
                        function() {
                            scope.userData = {};
                            if (responseData.reaction == 1) {
                                scope.activationRequired = requestData.activationRequired;

                                scope.requestSuccess = true;

                                $('.lw-form').slideUp();
                            }
                        });    

                    });

                };

            }
            
        ]);

})();;
(function() {
'use strict';
	
	/*
	 CategoryController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.category', [])
        .controller('CategoryController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '$state',
            '__Utils',
            '$rootScope',
            CategoryController 
	 	]);

	/**
	 * CategoryController for admin.
	 *
	 * @inject __DataStore
	 * @inject $scope
	 * @inject $state
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function CategoryController($scope, __DataStore, appServices, $state, __Utils, $rootScope) {

	 	var scope   			= this,
		 	catID 				= _.isEmpty($state.params.mCategoryID)
			 				 		? null 
			 				 		: $state.params.mCategoryID,
		 	currentStateName 	= $state.current.name;

		// Get current state name
	 	scope.currentStateName 			= currentStateName;

	 	scope.pageContentLoaded 		= false;
	 	scope.parentCategoryExist 		= false;

	 	// Get category ID
		scope.categoryID = catID;
		 	
 		scope.dtCategoriesColumnsData = [
	        {
	            "name"      : "name",
	            "orderable" :  true,
	            "template"  : "#categoriesColumnActionSubcategories"
	        },
	        {
	            "name"      : "status",
	            "orderable" :  true,
	            "template"  : "#categoriesColumnStatus"
	        },
	        {
	            "name"      : null,
	            "template"  : "#categoriesColumnActionAddcategory"
	        },
	        {
	            "name"      : null,
	            "template"  : "#categoriesColumnActionAddProduct"
	        },
	        {
	            "name"      : null,
	            "template"  : "#categoriesColumnActionTemplate"
	        }
    	];
        // var tabs    = {
        //     'manageCategories'    : {
        //             id      : 'categoriesTabList',
        //             route   : 'categories'
        //     },
        // };

        // Fired when clicking on tab    
        // $('#manageCategoryTab a').click(function (e) {

        //     e.preventDefault();

        //     var $this       = $(this),
        //         tabName     = $this.attr('aria-controls'),
        //         selectedTab = tabs[tabName];

        //     // Check if selectedTab exist    
        //     if (!_.isEmpty(selectedTab)) {

        //         $(this).tab('show')
        //         //scope.getCategories(selectedTab.id);

        //     }
            
        // });

        // if ($rootScope.canAccess('manage.category.list')) {
        //     var selectedTab = $('.nav li a[href="#manageCategories"]');

        //     selectedTab.triggerHandler('click', true);
        // }
        

	 	/**
	      * Get datatable source data.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	      
	    scope.getCategories  = function() {
	    	
	    	// destroy instance of datatable
	    	if (scope.categoriesDataTable) {
	            scope.categoriesDataTable.destroy();
	        }

            scope.categoriesDataTable = __DataStore.dataTable('#categoriesTabList', {
	            url : {
                    'apiURL'        : 'manage.category.list',
                    'categoryID?'   : catID
                },
	            dtOptions   : {
	                "searching" : true
	            },
	            columnsData : scope.dtCategoriesColumnsData, 
	            scope       : $scope,

	        });
            
            // if category ID exist
	        if (catID) {
	        	__DataStore.fetch({
	                'apiURL' : 'category.get.supportData',
	                'catID'  : catID
              	}).success(function(responseData) {
                    
                    appServices.processResponse(responseData, null, function() {
                        scope.parentCategory        = responseData.data.categoryData;
                        scope.isParentInactive      = scope.parentCategory.status;
                        scope.pageContentLoaded     = true;
                        scope.parentCategoryExist   = true;
                        scope.parentData            =  responseData.data.parentData;
                        scope.isInactiveParent = responseData.data.isInactiveParent;
                    });

	            });
 			} else if (!_.isEmpty($state.params.brandID)) {
 				
	            url = {
	              'apiURL'     : 'manage.brand.product.list',
	               'brandId'   : $state.params.brandID
	            };


	        } else {
	        	scope.pageContentLoaded = true;
	        }
		};


        if (_.isEmpty($state.params.mCategoryID)) {
            _.defer(function() {
				scope.getCategories();
			});
        }

        if (!_.isEmpty($state.params.mCategoryID)) {
            scope.getCategories();
        }


	    /**
          * Go to categories URL
          *
          * @param $event
          *
          * @return void
          *---------------------------------------------------------------- */
     //    scope.goToCategories = function ($event) {
	    //     $event.preventDefault(); 
	    //     $state.go('categories', {'mCategoryID' : catID});
	    // };
       

	    /**
          * Go to products URL 
          *
          * @param $event
          *
          * @return void
          *---------------------------------------------------------------- */
     //    scope.goToProducts = function ($event) {
	    //     $event.preventDefault();
	    //     $state.go('products', {'mCategoryID' : catID});
	    // };


	    /*
	     * Reload current datatable
	     *
	     *------------------------------------------------------------------ */
	    
	    scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.categoriesDataTable);
	    };

        /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (productID) {

	    	__DataStore.fetch({
	        	'apiURL'	: 'manage.product.detailSupportData',
	        	'productID'	: productID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

		    	appServices.showDialog(requestData,
		        {	
		            templateUrl : __globals.getTemplateURL(
		                    'product.manage.detail-dialog'
		                )
		        },
		        function(promiseObj) {

		        });
	       });
	    }

	    /**
          * Add new category
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.add   = function(catID) {
        
        	this.catID = catID;

        	__DataStore.fetch('category.fancytree.support-data')
					.success(function(responseData) {

          			scope.categories = responseData.data.categories;
          		
	          		appServices.showDialog(scope,
	                {
	                    templateUrl : __globals.getTemplateURL(
	                            'category.add-dialog'
	                        )
	                },
	                function(promiseObj) {

	                    // Check if category updated
	                    if (_.has(promiseObj.value, 'category_added') 
	                        && promiseObj.value.category_added === true) {
	                        scope.reloadDT();
	                    }

	                });
	    	});
            

        };

        /**
          * Fetch category details & show edit category dialog
          *
          * @param number catID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.edit   = function(catID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.category.get.details',
                    'catID'     :  catID
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {

                    	scope.categoryData = responseData.data;
                    	
                    	if (responseData.data.status === 0)
						{
                       		scope.categoryData.status = false;
						} else {
							scope.categoryData.status = true;
						}

                        appServices.showDialog({
                            'id'        :scope.categoryData.id,
		  					'name'      :scope.categoryData.name,
		  					'status'    :scope.categoryData.status,
		  					'parent_cat':scope.categoryData.parent_id
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'category.edit-dialog'
                                )
                        },
                        function(promiseObj) {

                            // Check if category updated
                            if (_.has(promiseObj.value, 'category_updated') 
                                && promiseObj.value.category_updated === true) {
                                scope.reloadDT();
                            	scope.getProducts();

                            }

                        }
                    );

                });    

            });

        };

	    /**
	    * delete category
	    *
	    * @param int categoryID 
	    * @param string  name 
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
	    scope.deleteDialog  =  function(categoryID, name) 
	    { 	
	    	appServices.showDialog({
	    		categoryID : categoryID,
	    		name 	   : name
	    	},
	        {	
	            templateUrl : __globals.getTemplateURL(
	                'category.delete-dialog'
	            )
	        }, function(promiseObj) {
	        	
	        	if (_.has(promiseObj.value, 'category_deleted') 
                    && promiseObj.value.category_deleted === true) {
	        		
	        		scope.reloadDT();

                }
	        });
				    
		};

    };

})();;
(function() {
'use strict';
    
    /*
     CategoryAddDialogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.categoryAddDialog', [])
        .controller('CategoryAddDialogController',   [
        	'$scope',
        	'$state',
            'appServices',
            '__DataStore',
            CategoryAddDialogController 
        ]);

    /**
      * CategoryAddDialogController open add category form in dialog
      * @inject $scope
      * @inject $state
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function CategoryAddDialogController($scope, $state, appServices, __DataStore) {

    	var scope   = this;

    	__DataStore.fetch('category.fancytree.support-data')
				.success(function(responseData) {
				
          		scope.categories = responseData.data.categories;
                scope.specificationPreset = responseData.data.specificationPreset;
          		appServices.showDialog(scope,
		        {	
		            templateUrl : __globals.getTemplateURL(
		                    'category.add-dialog'
		                )
		        },
		        function(promiseObj) {
		            // Check if category added
		            if (_.has(promiseObj.value, 'category_added') 
		                && promiseObj.value.category_added === true) {
		            	$scope.$parent.categoryCtrl.reloadDT();
		            }
		            $state.go('categories');
		        });
	    });
        

    };

})();;
(function() {
'use strict';
    
    /*
     CategoryAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.categoryAdd', [])
        .controller('CategoryAddController',   [
        	'$scope',
            '__Form', 
            '$state',
            'appServices',
            '__DataStore',
            CategoryAddController 
        ]);

    /**
      * CategoryAddController handle add category form
      * 
      * @inject $scope
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */

    function CategoryAddController($scope,__Form, $state, appServices, __DataStore) {

        var scope   = this;

        scope = __Form.setup(scope, 'category_add_form', 'categoryData');

        scope.categoryStatus		  = false;
        scope.categoryData.status     = true;

        scope.assign_specification_select_config = __globals.getSelectizeOptions({
            valueField  : '_id',
            labelField  : 'title',
            searchField : [ 'title' ]
        });

        if ($scope.ngDialogData.catID) {
        	
        	scope.categoryData.parent_cat = $scope.ngDialogData.catID;
        } else {
        	scope.categoryData.parent_cat = $state.params.mCategoryID;
        }

		scope.categoryData.categories = $scope.ngDialogData.categories;

		 _.forEach(scope.categoryData.categories, function(value) {

			if (value.key == scope.categoryData.parent_cat) {

				scope.categoryStatus = true;

				scope.categoryName = __ngSupport.getText(
                    __globals.getJSString('category_add_title_text'), {
                        '__name__'    : value.title
                    });
			}
        });

		/*
	 	 Submit form action
	 	-------------------------------------------------------------------------- */

	 	scope.submit = function() {
          
	 		__Form.process('manage.category.add', scope)
	 						.success(function(responseData) {
		      		
				appServices.processResponse(responseData, null, function(reactionCode) {

	                // close dialog
	      			$scope.closeThisDialog({ category_added : true });

	            });

		    });

	  	};

	  	/**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

  	  	scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};
    };

})();;
(function() {
'use strict';
	
	/*
	 CategoryEditDialogController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.categoryEditDialog', [])
        .controller('CategoryEditDialogController', 	[
            '$scope', 
            '$state', 
            'appServices',
            '__DataStore',
            CategoryEditDialogController 
	 	]);

	/**
	 * Handle category edit dialog scope
	 *
     * @inject $scope
	 * @inject __Form
	 * @inject $scope
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function CategoryEditDialogController($scope, $state, appServices, __DataStore) {

	 	var scope   = this;

        __DataStore.fetch({
        	'apiURL': 'manage.category.get.details',
        	'catID': $state.params.catID
        })
	   .success(function(responseData) {

			appServices.processResponse(responseData,null, function(reactionCode) {

				appServices.showDialog(responseData.data,{
	                templateUrl : __globals.getTemplateURL(
	                        'category.edit-dialog'
	                    )
	            },
	            function(promiseObj) {
						
					// Check if category updated
                    if (_.has(promiseObj.value, 'category_updated') 
                        && promiseObj.value.category_updated === true) {
                        $scope.$parent.categoryCtrl.reloadDT();
                    	//$scope.$parent.categoryCtrl.productReloadDT();
                    }
                    $state.go('categories');
				
                });
			});
		});
	};

})();;
(function() {
'use strict';
	
	/*
	 CategoryController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.categoryEdit', [])
        .controller('CategoryEditController', [
            '$scope',
            '__Form',
            '$state',
            'appServices',
            CategoryEditController 
	 	]);

	/**
	 * CategoryController for admin.
	 *
	 * @inject __DataStore
	 * @inject $scope
	 * @inject $state
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function CategoryEditController($scope, __Form, $state, appServices) {

	 	var scope = this;
	 		scope.categoryStatus		  = false;
            
            scope.assign_specification_select_config = __globals.getSelectizeOptions({
                valueField  : '_id',
                labelField  : 'title',
                searchField : [ 'title' ]
            });

            scope.categoryId = $state.params.catID;
	 		
			scope.updateURL = {
				'apiURL'	:'manage.category.update',
				'catID' 	: $state.params.catID
			};

			scope 	= __Form.setup(scope, 'form_category_edit', 'categoryData');
			scope   = __Form.updateModel(scope, $scope.ngDialogData);

			scope.categoryData.parent_cat = $scope.ngDialogData.parent_id;

			scope.categoryData.categories = $scope.ngDialogData.categories;
			

			 _.forEach(scope.categoryData.categories, function(value) {

				if (value.key == scope.categoryData.parent_cat) {
					scope.categoryStatus = true;
					scope.categoryName = value.title;
				}
        	});

			/**
			 * Update category.
			 *
			 * 
			 * @return void
			 *-------------------------------------------------------- */
			scope.update = function() {

		 		// post form data
		 		__Form.process(scope.updateURL, scope )
		 						.success( function( responseData ) {
			      		
					appServices.processResponse(responseData, function(reactionCode) {

		                if (reactionCode === 1) {
		                	// close dialog
		      				$scope.closeThisDialog( { category_updated : true } );
		      				
		                }

		            });

			    });

		  	};

		  	/**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

  	  	scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  		$state.go('categories');
  	  	};


	};

})();;
(function() {
'use strict';
    
    /*
     Login Controller
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.categoryDelete', [])
        .controller('CategoryDeleteController',   [
            '$scope',
            '__Form',  
            'appServices',
            '$state',
            '__DataStore',
            CategoryDeleteController 
        ]);

    /**
      * CategoryDeleteController for delete category
      * @inject __Form
      * @inject appServices
      * @inject $state
      * 
      * @return void
      *-------------------------------------------------------- */

    function CategoryDeleteController($scope, __Form, appServices, $state, __DataStore) {

        var scope   = this;

        scope = __Form.setup(scope, 'form_category_delete', 'categoryData', {
            secured : true
        });
        
        scope.categoryID    = $scope.ngDialogData.categoryID;
        scope.categoryName	= _.unescape($scope.ngDialogData.name);

        /**
          * Submit delete action
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.submit = function(categoryID) {

        	__Form.process({
                    'apiURL'     : 'manage.category.delete',
                    'categoryID' : categoryID,
                }, scope).success(function(responseData) {
		      		
				appServices.processResponse(responseData, function(reactionCode) {

	                if (reactionCode === 1) {
	                	// close dialog
	      				$scope.closeThisDialog({category_deleted : true});
					}

	            });

		    });
        };

        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
        	$scope.closeThisDialog();
		}
	};

})();;
(function() {
'use strict';
	
	/*
	 BrandListController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.brand', [])
        .controller('BrandListController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            BrandListController 
	 	]);

	/**
	 * BrandListController for admin.
	 *
	 * @inject $scope
	 * @inject __DataStore
	 * @inject $appServices
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function BrandListController($scope, __DataStore, appServices) {

		var scope   = this,
			dtProductsColumnsData = [
                {
                    "name"      : "logo_url",
                    "template"  : "#brandLogoColumnTemplate"
                },
                {
                    "name"      : "name",
                    "orderable" : true,
                    "template"  : "#brandNameColumnTemplate"
                },
                {
	                "name"      : "creation_date",
	                "orderable" : true,
	                "template"  : "#brandCreatedDateColumnTemplate"
	            },
                {
	                "name"      : "status",
	                "orderable" :  true,
	                "template"  : "#statusColumnTemplate"
	            },
	            {
	                "name"      : null,
	                "template"  : "#brandColumnActionTemplate"
	            }
            ];
			
         scope.brandsListDataTable = __DataStore.dataTable('#manageBrandList', {
            url         : "manage.brand.list",
            dtOptions   : {
                "searching": true,
                "order": [[ 1, "asc" ]],
                "rowCallback": function(row, data, dataIndex) {
                    $('td:eq(0)', row).css("text-align", "center");
                    $('td:eq(1)', row).css("text-align", "center");
                }
            },
            columnsData : dtProductsColumnsData, 
            scope       : $scope

        });

	    /*
	     Reload current datatable
	    -------------------------------------------------------------------- */
	    
	    scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.brandsListDataTable);
	    };

	    /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (brandID) {

	    	__DataStore.fetch({
	        	'apiURL'	: 'manage.brand.detailSupportData',
	        	'brandID'	: brandID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;
    	   		
    	   		appServices.processResponse(responseData, null, function() {

			    	appServices.showDialog(requestData,
			        {	
			            templateUrl : __globals.getTemplateURL(
			                    'brand.manage.detail-dialog'
			                )
			        },
			        function(promiseObj) {

			        });
			    });
	       });
	    }

	    /**
	     * delete brand
	     * @param brandID
	     * @param brandName
	     * 
	    -------------------------------------------------------------------- **/
	    scope.delete = function(brandID, brandName) {
	    	/*__globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('brand_delete_text'), {
                        '__name__'     : unescape(brandName)
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL' 	: 'manage.brand.delete',
                    'brandID' 	: brandID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                    	
                            error : function(data) {
                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function(data) {
                        	
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });*/

            appServices.showDialog({
                    brandId     : brandID,
                    brandName   : unescape(brandName)
                },
                {   
                    templateUrl : __globals.getTemplateURL('brand.manage.delete-dialog')
                },
                function(promiseObj) {
                    
                    if (_.has(promiseObj.value, 'brand_deleted') && promiseObj.value.brand_deleted) {
                        scope.reloadDT();
                    }

            });

	    }

	};

})();;
(function() {
'use strict';
    
    /*
     BrandDetailDialogController module
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandDetailDialog', [])
        .controller('BrandDetailDialogController',   [
            '$scope',
            BrandDetailDialogController 
        ]);

    /**
      * BrandDetailDialogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
    function BrandDetailDialogController($scope) {

        var scope   = this;
       
        scope.ngDialogData  = $scope.ngDialogData;
	    scope.brandData 	= scope.ngDialogData;

	/**
	  * Close dialog
	  *
	  * @return void
	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
	  		$scope.closeThisDialog();
	  	};
	            
    };

})();;
(function() {
'use strict';
    
    /*
     BrandAddDialogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandAddDialog', [])
        .controller('BrandAddDialogController',   [
        	'$scope',
        	'$state',
            'appServices',
            BrandAddDialogController 
        ]);

    /**
      * BrandAddDialogController open add brand form in dialog
      * @inject $scope
      * @inject $state
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function BrandAddDialogController($scope, $state, appServices) {

    	var scope   = this;

    	appServices.showDialog(scope,
        {	
            templateUrl : __globals.getTemplateURL(
                    'brand.manage.add-dialog'
                )
        },
        function(promiseObj) {

            // Check if brand added
            if (_.has(promiseObj.value, 'brand_added') 
                && promiseObj.value.brand_added === true) {
            	$scope.$parent.brandListCtrl.reloadDT();
            }
            $state.go('brands');
        });
        

    };

})();;
(function() {
'use strict';
    
    /*
     BrandAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandAdd', [])
        .controller('BrandAddController',   [
        	'$scope',
        	'__Form',
        	'appServices',
        	'FileUploader',
        	'__Utils',
        	'appNotify',
            BrandAddController 
        ]);

    /**
      * BrandAddController handle add category form
      * 
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function BrandAddController($scope, __Form, appServices, FileUploader, __Utils, appNotify) {

        var scope   = this;

        scope = __Form.setup(scope, 'manage_brand_add', 'brandData',
					        { 
					            secured : false
					        });
        scope.brandData.status  = true;

        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#logoListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#logoListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });
        
        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', { fresh : true })
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files
                    if (responseData.data.files.length > 0) {
                    	scope.images_count = responseData.data.files.length;
					};
                });    

            });

        };

        scope.getTempImagesMedia();
        
        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.image'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });


        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
		scope.loadingStatus     	   = false;

		/**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {

            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };

        /**
          * Submit brand form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
        	 
            __Form.process('manage.brand.add', scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { brand_added : true, brand : responseData.data.brand } );
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.uploaded-media'
                )
            }, function(promiseObj) {
            	// Check if upload files updated
                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files 	= promiseObj.value.files;
                    scope.images_count 	= promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });

        };

        /**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
	  	 

  	  	scope.close = function() {
  	  		$scope.closeThisDialog();
  	  	};
    };

})();;
(function() {
'use strict';
    
    /*
     BrandEditDialogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandEditDialog', [])
        .controller('BrandEditDialogController',   [
        	'$scope',
        	'$state',
            '__DataStore',
            'appServices',
            BrandEditDialogController 
        ]);

    /**
      * BrandEditDialogController open add brand form in dialog
      * @inject $scope
      * @inject $state
      * @inject __DataStore
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function BrandEditDialogController($scope, $state, __DataStore, appServices) {

    	var scope   = this;

        __DataStore.fetch({
	        	'apiURL'	: 'manage.brand.editSupportData',
	        	'brandID'	: $state.params.brandID
	        })
    	   .success(function(responseData) {

				appServices.processResponse(responseData,null, function(reactionCode) {
					
	            	appServices.showDialog(responseData.data,
	                {
	                    templateUrl : __globals.getTemplateURL(
	                            'brand.manage.edit-dialog'
	                        )
	                },
	                function(promiseObj) {

	                	 // Check if brand updated
	                    if (_.has(promiseObj.value, 'brand_updated') 
	                        && promiseObj.value.brand_updated === true) {
	                    	
	                    	$scope.$parent.brandListCtrl.reloadDT();
	                    }
	                    
	                    $state.go('brands');
	                   
	                });
	        });
     	});
        

    };

})();;
(function() {
'use strict';
    
    /*
     BrandEditController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandEdit', [])
        .controller('BrandEditController',   [
        	'$scope',
        	'__Form',
        	'appServices',
        	'$state',
        	'FileUploader',
        	'__Utils',
        	'appNotify',
            BrandEditController 
        ]);

    /**
      * BrandEditController handle add brand form
      * 
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $state
      * @inject FileUploader
      * @inject __Utils
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function BrandEditController($scope, __Form, appServices, $state, FileUploader, __Utils, appNotify) {

        var scope   = this,
        	ngDialogData = $scope.ngDialogData;
            scope.brandId = $state.params.brandID;
       		scope.updateURL = {
				'apiURL' :'manage.brand.edit.process',
				'brandID' : $state.params.brandID
			};

        scope 	= __Form.setup(scope, 'manage_brand_add', 'brandData', { 
					            secured : false
					        });
        scope   = __Form.updateModel(scope, $scope.ngDialogData);

        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#logoListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#logoListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', { fresh : true })
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files
                    if (responseData.data.files.length > 0) {
                    	scope.images_count = responseData.data.files.length;
					};
                });    

            });

        };

        scope.getTempImagesMedia();
        
        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.image'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
		scope.loadingStatus     	   = false;

		/**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {

            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };


        /**
          * Submit brand form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process(scope.updateURL, scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { brand_updated : true } );
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.uploaded-media'
                )
            }, function(promiseObj) {
            	// Check if files added
                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files 	= promiseObj.value.files;
                    scope.images_count 	= promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });

        };


        /**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
	  	
	  	scope.close = function() {
  	  		$scope.closeThisDialog();
  	  	};
    };

})();;
(function() {
    'use strict';
    
    /**
      * BrandDeleteDialogController - handle a delete brand confirmation dialog
      * 
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
    
    angular
        .module('ManageApp.brandDeleteDialog', [])
        .controller('BrandDeleteDialogController', [
        	'$scope',
        	'__Form',
        	'appServices',
            function ($scope, __Form, appServices) {

                var scope       = this,
                    brandId     = $scope.ngDialogData.brandId,
                    brandName   = $scope.ngDialogData.brandName;

                scope = __Form.setup(scope, 'delete_brand_form', 'brandData',{ secured : true });

                scope.brandData.delete_related_products = false;

                scope.notificationMessage = __ngSupport.getText(
                    __globals.getJSString('brand_delete_text'), {
                        '__name__'     : unescape(brandName)
                    }
                );

                /**
                  * Submit delete brand form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                
                scope.submit = function() {
                     
                    __Form.process({
                        'apiURL'    : 'manage.brand.delete',
                        'brandID'   : brandId
                    }, scope)
                        .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            $scope.closeThisDialog({ 'brand_deleted' : true });
                        });    

                    });

                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                 

                scope.close = function() {
                    $scope.closeThisDialog();
                }; 

        }]);

})();;
(function() {
'use strict';
    
    /*
     ManageProductAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.add', [])
        .controller('ManageProductAddController',   [
            '__Form', 
			'$state', 
			'appServices',
     		'FileUploader', 
			'__Utils', 
			'$stateParams', 
			'appNotify', 
			'__DataStore',
            '$rootScope',
            ManageProductAddController 
        ]);

    /**
      * ManageProductAddController handle add product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductAddController(__Form, $state, appServices,
     FileUploader, __Utils, $stateParams, appNotify, __DataStore, $rootScope) {

        var scope       = this;
           	scope.categoryStatus = false;
            scope.currentStateName = $state.current.name;

        scope = __Form.setup(scope, 'form_product_add', 'productData', 
            { secured : false }
        );

        scope.productData.out_of_stock = 0;

        var today = moment().format('YYYY-MM-DD HH:mm');

        scope.releaseDateConfig = {
        	'format' : 'YYYY-MM-DD HH:mm',
        };
        
        scope.productData.launching_date = today;

        scope.productData.categories = [];

        if (_.has($stateParams, 'categoryID') && !_.isEmpty($stateParams.categoryID)) {

            var categoryID  = $stateParams.categoryID;

            scope.productData.categoryID = categoryID;
            scope.productData.categories = [ categoryID ];
        }
        
        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#imageListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#imageListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });

        scope.brandsSelectConfig = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'name',
            searchField : [ 'name' ]  
        });

        scope.relatedProductsSelectConfig = __globals.getSelectizeOptions({
            maxItems        : 1000,
            searchField     : ['name', 'product_id']  
        });

        /**
          * Fetch support data
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.getAddProductData = function() {
        	__Form.fetch({
                'apiURL'     : 'manage.product.add.supportdata',
                'categoryId?' : scope.productData.categoryID
            })
                .success(function(responseData) {
            var requestData = responseData.data;

	            appServices.processResponse(responseData, null, function() {
	                scope.related_products      = requestData.related_products;
	                scope.store_currency_symbol = requestData.store_currency_symbol;
	                scope.store_currency        = requestData.store_currency;
	                scope.fancytree_categories  = requestData.categories;
	                scope.activeBrands  		= requestData.activeBrands;
                    scope.parentData            = requestData.parentData;
                    scope.parentCategoryExist   = requestData.isParentExist;
                    scope.categoryDetail        = requestData.categoryDetail;
                    scope.specificationPreset   = requestData.specificationPreset;

                    scope.isParentExist = true;
                    
                    if (_.isUndefined(scope.productData.categoryID)) {
                        scope.isParentExist = false;
                    }
    	               
	                _.forEach(requestData.categories, function(value) {
	                	
	                	if (value.key == scope.productData.categoryID) {

							scope.categoryStatus = true;

							scope.categoryName = __ngSupport.getText(
			                    			__globals.getJSString('product_add_title_text'), {
			                        '__name__'    : value.title
			                    });
						}
	                });

	            });    

	        });
        }
        scope.getAddProductData();

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', {fresh : true})
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files;
                    if (responseData.data.files.length > 0) {
                    	scope.images_count = responseData.data.files.length;
					};
                });    

            });

        };
        scope.getTempImagesMedia();
        
        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.image'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        }); 

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
        scope.loadingStatus     	   = false;

		/**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {

            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };

		/**
          * Add new category
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.addCategory   = function(catID) {

        	this.catID = catID;

        	__DataStore.fetch('category.fancytree.support-data', {fresh:true})
					.success(function(responseData) {

      			scope.categories = responseData.data.categories;
      		
          		appServices.showDialog(scope,
                {
                    templateUrl : __globals.getTemplateURL(
                            'category.add-dialog'
                        )
                },
                function(promiseObj) {
						
                    // Check if category updated
                   if (_.has(promiseObj.value, 'category_added') 
                        && promiseObj.value.category_added == true) {

                        __DataStore.fetch('category.fancytree.support-data', {fresh:true}).success(function(responseData) {
						
							scope.fancytree_categories = responseData.data.categories;	
							
						});
                  	}

                });

	    	});
            
        };

        /**
          * Submit prodcut add form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function(string) {
            //get client timeZoneDate
            scope.productData.getClientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        
            if (scope.productData.out_of_stock != 3) {
                scope.productData.launching_date = null;
            }
            
            __Form.process('manage.product.add', scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                	
                	scope.productId = responseData.data.productId;
                	
                    if ($rootScope.canAccess('manage.product.list') && $rootScope.canAccess('manage.product.option.list') && scope.productData.publish === false) {
                      
                        $state.go('product_edit.options', {'productID' : scope.productId});
                    } else {

                        $state.go('products', { 'mCategoryID' : categoryID });
                    }

                });    

            });

        };

        /**
        * Click on this btn submit product mark as ative
        * & show publically & redirect page on manage products
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.saveAndPublish = function() {
            scope.productData.publish = true;
            scope.submit(null);
        };

		/**
        * Click on this btn submit product mark as inative
        * & publically not show & mark as inactive & redirect page on manage options
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.saveAndAddOptions = function() {
            scope.productData.publish = false;
            scope.submit(null);
        };

        
        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL('product.manage.uploaded-media')
            }, function(promiseObj) {

                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files = promiseObj.value.files;
                    scope.images_count = promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });
        };

        /**
          * add brand dialog
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addBrand = function() {
        	// show add brand dialog
	        appServices.showDialog(scope,
	        {	
	            templateUrl : __globals.getTemplateURL(
	                    'brand.manage.add-dialog'
	                )
	        },
	        function(promiseObj) {
	        	
				// Check if brand added
	            if (_.has(promiseObj.value, 'brand_added') 
	                && promiseObj.value.brand_added === true) {

	            	scope.getAddProductData();
	            }

	        });
        }
    };

})();;
(function() {
'use strict';
    
    /*
     ManageProductUploadedMediaController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.uploadedMedia', [])
        .controller('ManageProductUploadedMediaController',   [
            '$scope',
            '__DataStore', 
            'appServices',
            '$state',
            '$rootScope',
            ManageProductUploadedMediaController 
        ]);

    /**
      * ManageProductUploadedMediaController handle uploaded media files
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductUploadedMediaController($scope, __DataStore, appServices, $state, $rootScope) {

        var scope       = this;

        scope.image_files               = [];
        scope.files                     = $scope.ngDialogData.image_files;
        scope.uploadedMediaFileCount    = scope.files.length;
        scope.any_file_selected         = false;

        if (!_.isEmpty(scope.files)) {

            _.forEach(scope.files, function(value, key) {

                scope.image_files.push({
                    'name'  : value.name,
                    'path'  : value.path,
                    'exist' : false,
                });

            });

        }
        scope.all_files_selected    = false;

        /**
          * Select or unselect all uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.selectAll  = function() {

            // Check if all files selected 
            if (scope.all_files_selected) {

                scope.any_file_selected    = true;
                scope.unSelectedFilesCount = 0;
                
            } else {

                scope.any_file_selected    = false;
                scope.unSelectedFilesCount = scope.image_files.length;

            }

            angular.forEach(scope.image_files, function(value, index) {
                scope.image_files[index]['exist'] = scope.all_files_selected;
            });

        };

        /**
          * Select any media image file 
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.select = function() {

            scope.unSelectedFilesCount = 0;

            _.forEach(scope.image_files, function(value) {

                if (value.exist == false) {
                    scope.unSelectedFilesCount++;
                }

            });

            if (scope.unSelectedFilesCount == 0 && scope.uploadedMediaFileCount != 0) {

                scope.all_files_selected    = true;
                scope.any_file_selected     = true;

            } else if (scope.unSelectedFilesCount > 0 
                && scope.uploadedMediaFileCount != 0) {

                scope.all_files_selected    = false;
                scope.any_file_selected     = true;

            } else {

                scope.all_files_selected    = false;
                scope.any_file_selected        = false;

            }

        };

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.getTempImagesMedia = function() {

            __DataStore.fetch('media.uploaded.images', {fresh : true})
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {

                    scope.files         = responseData.data.files
                    scope.image_files   = [];

                    if (!_.isEmpty(scope.files)) {
                        //if current state is general setting
                        if ($state.current.name == 'store_settings_edit.general') {
                          
                            _.forEach(scope.files, function(value, index) {
                                
                                scope.imagesExtention = value.name.split(".").pop();
                                
                                if (scope.imagesExtention == 'png') {
                                    scope.image_files.push({
                                        'name'  : value.name,
                                        'path'  : value.path,
                                        'exist' : false,
                                    });
                                } else if (scope.imagesExtention == 'ico') {
                                    scope.image_files.push({
                                        'name'  : value.name,
                                        'path'  : value.path,
                                        'exist' : false,
                                    });
                                }
                            });

                        } else {
                            _.forEach(scope.files, function(value, key) {

                                scope.image_files.push({
                                    'name'  : value.name,
                                    'path'  : value.path,
                                    'exist' : false,
                                });

                            });
                        }

                    }/*

                    $rootScope.$emit('remove.uploded.temp', {'status':true});*/

                });    

            });

        };

        /**
          * Delete media file 
          *
          * @param string fileName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(fileName) {

            __DataStore.post({
                'apiURL'    : 'media.delete',
                'fileName'  : fileName
            })
            .success(function(responseData) {
                scope.all_files_selected    = false;
                appServices.processResponse(responseData, null, function() {
                     
                    //check current state 
                    if ($state.current.name == 'store_settings_edit.general') {
                        $rootScope.$broadcast('lw-upload-image-deleted', true);
                        scope.getTempImagesMedia();

                    } else {
                        scope.getTempImagesMedia();
                    }

                });    

            });

        };

        /**
          * Delete multiple uploaded teparary media Files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.deleteMultipleFiles = function() {

            var selectedUploadedFiles = [];

            angular.forEach(scope.image_files, function(value, index) {
               
                if (value.exist) {
                    selectedUploadedFiles.push(value.name);
                }

            });

            // Check if files exist
            if (!_.isEmpty(selectedUploadedFiles)) {

                __DataStore.post('media.delete.multiple',
                        { 'files' : selectedUploadedFiles })
                    .success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        scope.getTempImagesMedia();
                    });  

                });

            }

        };
       
       /**
          * Close current dialog
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.close = function() {
            if ($state.current.name == 'store_settings_edit.general') {
                $scope.closeThisDialog();
            } else {
                $scope.closeThisDialog({ files : scope.files });
            }
        }; 

    };

})();;
(function() {
'use strict';
    
    /*
     ManageEditProductDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.details', [])
        .controller('ManageEditProductDetailsController',   [
        	'__DataStore',
            'appServices',
            '$stateParams',
            '__Form',
            '$state',
            '$rootScope',
            ManageEditProductDetailsController 
        ]);

    /**
      * ManageEditProductDetailsController for update tabs
      *
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageEditProductDetailsController(__DataStore, appServices, $stateParams, __Form, $state, $rootScope) {

        var scope       = this,
            productID   = $stateParams.productID;

        var tabs    = {
            'detailsTab'    : {
                    id      : 'detailsTabList',
            },
            'optionsTab'    : {
                    id      : 'optionsTabList',
            },
            'imagesTab'    : {
                    id      : 'imagesTabList',
            },
            'specificationsTab'    : {
                    id      : 'specificationsTabList',
            },
            'ratingsTab'    : {
                    id      : 'ratingsTabList',
            },
            'faqTab'        : {
                    id      : 'faqTabList',
            },
            'awatingUserTab': {
                    id      : 'awatingUserTabList',
            }
        };

        // Fired when clicking on tab    
        $('#manageProductEditTabs a').click(function (e) {

            e.preventDefault();

            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];

            // Check if selectedTab exist    
            if (!_.isEmpty(selectedTab)) {

                $(this).tab('show')
                //scope.getCategories(selectedTab.id);

            }
            
        });

        scope = __Form.setup(scope, 'update_product_status_form', 'productData');

        scope.initialContentLoaded = false;

        $rootScope.$on('productData', function(data, item) {
            scope.productName = item.name;
        });

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */

        __DataStore.fetch({
            'apiURL'    : 'manage.product.fetch.name',
            'productID' : productID
        }).success(function(responseData) {
                    
            appServices.processResponse(responseData, null, function() {

                scope.productName = __ngSupport.getText(
                    __globals.getJSString('product_edit_title_text'), {
                        '__name__'    : responseData.data.productName.name
                    });

                var requestData = responseData.data;

                scope.isMultipleCategory = requestData.isMultipleCategory;

                scope.categoryData = requestData.categoryData;

                scope.detailsUrl = requestData.detailsUrl;

                scope.initialContentLoaded = true;

                scope = __Form.updateModel(scope, {'active' : responseData.data.status});
                
            });    

        });

        //active tabs
        _.defer(function(text) {
            if ($state.current.name == 'product_edit.details') {

                var selectedTab = $('.nav li a[href="#detailsTab"]');
                    selectedTab.triggerHandler('click', true);

            } else if ($state.current.name == 'product_edit.options') {

                var selectedTab = $('.nav li a[href="#optionsTab"]');
                    selectedTab.triggerHandler('click', true);

            } else if ($state.current.name == 'product_edit.images') {
                
                var selectedTab = $('.nav li a[href="#imagesTab"]');
                    selectedTab.triggerHandler('click', true);

            } else if ($state.current.name == 'product_edit.specification') {
                
                var selectedTab = $('.nav li a[href="#specificationsTab"]');
                    selectedTab.triggerHandler('click', true);

            } else if ($state.current.name == 'product_edit.ratings') {
                
                var selectedTab = $('.nav li a[href="#ratingsTab"]');
                    selectedTab.triggerHandler('click', true);

            } else if ($state.current.name == 'product_edit.faq') {
                
                var selectedTab = $('.nav li a[href="#faqTab"]');
                    selectedTab.triggerHandler('click', true);
                    
            } else if ($state.current.name == 'product_edit.awating_user') {
                
                var selectedTab = $('.nav li a[href="#awatingUserTab"]');
                    selectedTab.triggerHandler('click', true);
            }
             
        }, 0);

        /**
          * Submit update product status form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process({ 'apiURL' : 'manage.product.update_status', 'productID' : productID }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {

                });    

            });

        };

    };

})();;
(function() {
'use strict';
    
    /*
     ManageProductEditController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.edit', [])
        .controller('ManageProductEditController',   [
            '__DataStore', 
            'appServices',
            '$stateParams',
            ManageProductEditController
        ]);

    /**
      * ManageProductEditController handle edit product details form
      *
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductEditController(__DataStore, appServices, $stateParams) {

        var scope   = this;

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */

        __DataStore.fetch({
            'apiURL'    : 'manage.product.details',
            'productID' : $stateParams.productID
        }).success(function(responseData) {

            appServices.processResponse(responseData, null, function() {

                scope.product = responseData.data.productName;

            });    

        });
    };

})();;
(function() {
'use strict';
    
    /*
     ManageProductEditDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.editDetails', [])
        .controller('ManageProductEditDetailsController',   [
            '__Form', 
            '$state',
            'appServices',
            'FileUploader',
            '__Utils',
            '$stateParams',
            'appNotify',
            '$rootScope',
			'__DataStore',
            '$scope',
            ManageProductEditDetailsController 
        ]);

    /**
      * ManageProductEditDetailsController handle edit product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductEditDetailsController(__Form, $state, appServices, 
        FileUploader, __Utils, $stateParams, appNotify, $rootScope, __DataStore, $scope) {

        var scope       = this,
            productID   = $stateParams.productID;

        scope.productId = productID;
        scope = __Form.setup(scope, 'form_product_edit', 'productData', {
            secured : false
        });

        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#imageListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#imageListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });

        scope.relatedProductsSelectConfig = __globals.getSelectizeOptions({
            maxItems        : 1000,
            searchField     : ['name', 'product_id']  
        });

        scope.brandsSelectConfig = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'name',
            searchField : [ 'name' ]  
        });

        scope.pageStatus = false;
        scope.productAvailableExist = false;

        scope.releaseDateConfig = {
        	'format' : 'YYYY-MM-DD HH:mm',
        };

        /**
          * Fetch support data
          *
          * @return void
          *---------------------------------------------------------------- */
          
        scope.getProductEditDetails = function() {
        	__Form.fetch({
	            'apiURL'    : 'manage.product.edit.details.supportdata',
	            'productID' : productID
	        })
            .success(function(responseData) {
	                    
	            appServices.processResponse(responseData, null, function() {

	            	appServices.delayAction(function() {
	               		var requestData         	= responseData.data;
	               		var productName = { 
	               			name : requestData.product.name 
	               		};
 
                        var today = moment().format('YYYY-MM-DD HH:mm');

	                	scope.related_products  	= requestData.related_products;
	                	scope.categories        	= requestData.product.categories;
	                    scope.store_currency_symbol = requestData.store_currency_symbol;
	                    scope.store_currency        = requestData.store_currency;
	                	scope.fancytree_categories 	= responseData.data.categories;
	                	scope.activeBrands  		= requestData.activeBrands;
                        scope.out_of_stock          = requestData.product.outOfStock;
	                
	                	scope.pageStatus = true;
	                	__Form.updateModel(scope, requestData.product);
                    
                        if (_.isEmpty(requestData.product.__data) && _.isEmpty(scope.productData.launching_date)) {
                            scope.productData.launching_date = today;
                        }

	                	$rootScope.$emit('productData', productName);
	           		});

	                
	            });    

	        });
        }
        scope.getProductEditDetails();


        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', {fresh : true})
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files
                    if (responseData.data.files.length > 0) {
                    	scope.images_count = responseData.data.files.length;
					};
                });    

            });

        };

        scope.getTempImagesMedia();
        
        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.image'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
        scope.loadingStatus     	   = false;

		/**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {

            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };

		/**
          * Add new category
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.addCategory   = function(catID) {

        	this.catID = catID;

        	__DataStore.fetch('category.fancytree.support-data', {fresh:true})
					.success(function(responseData) {

      			scope.categories = responseData.data.categories;
      		
          		appServices.showDialog(scope,
                {
                    templateUrl : __globals.getTemplateURL(
                            'category.add-dialog'
                        )
                },
                function(promiseObj) {
						
                    // Check if category updated
                   if (_.has(promiseObj.value, 'category_added') 
                        && promiseObj.value.category_added == true) {

                        __DataStore.fetch('category.fancytree.support-data', {fresh:true}).success(function(responseData) {
						
							scope.fancytree_categories = responseData.data.categories;	
							
						});
                  	}

                });

	    	});
            
        };


        /**
          * Submit product edit form submit action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            //clientTimeZone Name
            scope.productData.getClientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
           
        
            if (scope.productData.outOfStock != 3) {
                scope.productData.launching_date = null;
            }
        
            __Form.process({
                'apiURL'    : 'manage.product.edit',
                'productID' : productID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                   
                    if (responseData.reaction == 1) {
                       scope.getProductEditDetails();
                    }
                    
                    if (responseData.reaction == 1 && scope.productData.outOfStock == 0 && scope.out_of_stock != 0) {
                        appServices.showDialog(scope,
                            {
                                templateUrl : __globals.getTemplateURL(
                                        'product.manage.send-mail-dialog'
                                    )
                            },
                            function(promiseObj) {
                                    
                        });
                    }
                   // scope.productData.image = '';
                    
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL('product.manage.uploaded-media')
            }, function(promiseObj) {

                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files = promiseObj.value.files;
                    scope.images_count = promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });
        };

        /**
          * add brand
          *
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addBrand = function() {
        	// show add brand dialog
	        appServices.showDialog(scope,
	        {	
	            templateUrl : __globals.getTemplateURL(
	                    'brand.manage.add-dialog'
	                )
	        },
	        function(promiseObj) {
	        	
				// Check if brand added
	            if (_.has(promiseObj.value, 'brand_added') 
	                && promiseObj.value.brand_added === true) {

	            	scope.getProductEditDetails();
	            }

	        });
        }

        /**
          * Close dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
            $scope.closeThisDialog();
        };

    };

})();;
(function() {
'use strict';
   
    /*
     ManageProductNotifyMailController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.notifyMailCustomer', [])
        .controller('ManageProductNotifyMailController',   [
            '__Form', 
            '$state',
            '__Utils',
            '$stateParams',
			'__DataStore',
            '$scope',
            'appServices',
            '$rootScope',
            ManageProductNotifyMailController 
        ]);

    /**
      * ManageProductNotifyMailController handle edit product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductNotifyMailController(__Form, $state, __Utils, $stateParams, __DataStore, $scope, appServices, $rootScope) {

        var scope       = this,
            productID   = $stateParams.productID;
        
        var notifyUserId = $scope.ngDialogData.notifyUserId;
        var mailType = $scope.ngDialogData.mailType;

        scope = __Form.setup(scope, 'product_notify_mail_form', 'productNotifyMailData', {
            secured : false
        });

        if (mailType == 1) {
            scope.productNotifyMailData['awatingUserList'] = $scope.ngDialogData.awatingUserList;
        } else {
            scope.productNotifyMailData['awatingUserList'] = null;
        }
        
        scope.productNotifyMailData['mailType'] = mailType;

        /**
          * Submit product edit form submit action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
    
            __Form.process({
                'apiURL'    : 'manage.product.awating_user.notify_mail.send',
                'productId' : productID,
                'notifyUserId' : notifyUserId ? notifyUserId : null
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    // close dialog
                    $rootScope.$broadcast('awating_user_added_or_updated', true);
                    $scope.closeThisDialog({ send_mail_to_customer : true });
                });    

            });

        };

        /**
          * Close dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
            $scope.closeThisDialog();
        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductImagesController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.images', [])
        .controller('ProductImagesController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            ProductImagesController 
        ]);

    /**
      * ProductImagesController for manage product image list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductImagesController($scope, __DataStore, appServices,
     $stateParams) {

        var dtProductImagesColumnsData = [
                {
                    "name"      : "list_order",
                    "orderable" : true,
                    "template"  : "#productImageColumnListOrderTemplate"
                },
                {
                    "name"      : "thumbnail",
                    "template"  : "#productImageThumbnailColumnTemplate"
                },
                {
                    "name"      : "title",
                    "orderable" : true
                },
                {
                    "name"      : null,
                    "template"  : "#productImageActionColumnTemplate"
                }
            ],
            scope       = this,
            productID   = $stateParams.productID;

        /**
          * Get images  
          *
          * @return void
          *---------------------------------------------------------------- */
                        
        scope.imagesListDataTable = __DataStore.dataTable('#productImagesList', {
            url         : {
                'apiURL'    : 'manage.product.image.list',
                'productID' : productID
            },
            dtOptions   : {
                "searching": true,
                "rowReorder" : true,
                "paginate"  :false,
                // "order": [[ 1, "asc" ]]
            },
            columnsData : dtProductImagesColumnsData, 
            scope       : $scope
        });

        //Set Image order list in pages start
        $('#productImagesList').on('row-reorder.dt', function (e, data, edit) {
            var productImageListOrderData = [];
            
            _.forEach(data, function(item, key) {
                
                productImageListOrderData.push({
                    _id          : _.trim(item.node.id.replace('rowid_', '')),
                    newPosition  : item.newPosition,
                    oldPosition  : item.oldPosition
                })
              
            }); 

            //set Image order list start
            __DataStore.post('manage.product.image.update.list.order',{ 
                'productImageListOrder' : productImageListOrderData
            }).success(function(responseData) {
                    
                appServices.processResponse(
                    responseData,
                    {
                        error : function() {
                            scope.reloadDT();   // reload datatable
                        }
                    },
                    function() {
                        scope.reloadDT();
                    }
                ); 

            });
        });
        //Set Image order list end

        /*
         Reload current datatable
        -------------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.imagesListDataTable);
        };

        /**
          * Add new image of product
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.add   = function() {

            appServices.showDialog({},
                {
                    templateUrl : __globals.getTemplateURL(
                            'product.manage.Images.add-dialog'
                        )
                },
                function(promiseObj) {

                    // Check if image added
                    if (_.has(promiseObj.value, 'image_added') 
                        && promiseObj.value.image_added === true) {
                        scope.reloadDT();
                    }

                });

        };

        /**
          * Edit prdouct existing image
          *
          * @param number imageID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.edit   = function(imageID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.product.image.edit.supportdata',
                    'imageID'   : imageID,
                    'productID' : productID,
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {

                        appServices.showDialog({
                            'image_id'      : imageID,
                            'prdouct_id'    : productID,
                            'imageData'     : responseData.data.prdouct_image
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'product.manage.Images.edit-dialog'
                                )
                        },
                        function(promiseObj) {

                            // Check if image updated
                            if (_.has(promiseObj.value, 'image_updated') 
                                && promiseObj.value.image_updated === true) {
                                scope.reloadDT();
                            }

                        }
                    );

                });    

            });

        };

        /**
          * Delete image 
          *
          * @param number imageID
          * @param string imageName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(imageID, imageName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString(
                        'product_image_delete_confirm_text'
                    ), {
                        '__title__'    : imageName
                    }
                ),
                confirmButtonText   : __globals.getJSString(
                    'delete_action_button_text'
                )
            }, function() {

                __DataStore.post({
                    'apiURL'    : 'manage.product.image.delete',
                    'imageID'   : imageID,
                    'productID' : productID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {
                            error : function() {

                                __globals.showConfirmation({
                                    title   : 'Deleted!',
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                title   : 'Deleted!',
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });

        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductImageAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.addImage', [])
        .controller('ProductImageAddController',   [
            '$scope',
            '__Form', 
            'appServices',
            'FileUploader',
            '__Utils',
            '$stateParams',
            'appNotify',
            ProductImageAddController 
        ]);

    /**
      * ProductImageAddController for add product new image
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductImageAddController($scope, __Form, appServices, 
        FileUploader, __Utils, $stateParams, appNotify) {

        var scope   = this;

        scope = __Form.setup(scope, 'form_product_add_image',
            'imageData',
            { 
                secured : false
            }
        );

        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#imageListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#imageListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });
        
        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', { fresh : true })
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files
                    if (responseData.data.files.length > 0) {
                    	scope.images_count = responseData.data.files.length;
					};
                });    

            });

        };

        scope.getTempImagesMedia();
        
        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.image'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
		scope.loadingStatus     	   = false;

		/**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {

            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };

        /**
          * Submit image form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process({
                'apiURL'    : 'manage.product.image.add',
                'productID' : $stateParams.productID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'image_added' : true });
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.uploaded-media'
                )
            }, function(promiseObj) {

                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files 	= promiseObj.value.files;
                    scope.images_count 	= promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductImageEditController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.editImage', [])
        .controller('ProductImageEditController',   [
            '$scope',
            '__Form', 
            'appServices',
            ProductImageEditController 
        ]);

    /**
      * ProductImageEditController for edit prdouct image
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductImageEditController($scope, __Form, appServices) {

        var scope       = this,
            dialogData  = $scope.ngDialogData;

        scope = __Form.setup(scope, 'form_product_edit_image',
            'imageData',
            { 
                secured : false
            }
        );

        scope = __Form.updateModel(scope, dialogData.imageData);

        /**
          * Submit prdouct image edit form
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process({
                'apiURL'        : 'manage.product.image.edit',
                'productID'     : dialogData.prdouct_id,
                'imageID'       : dialogData.image_id
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'image_updated' : true });
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductOptionsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.options', []) 
        .service('ImageDataService',[
            '$q', 
            '__DataStore', 
            '__Form',
            'appServices',
            function ImageDataService($q, __DataStore,__Form, appServices) {

                /*
                Get Images 
                -----------------------------------------------------------------*/
                this.getImages = function(productID) {
    
                    //create a differed object          
                    var defferedObject = $q.defer();   
       
                    __DataStore.fetch({
                            'apiURL'    : 'manage.product.image.read.data_list',
                            'productID' : productID
                        }).success(function(responseData) {
                                
                        appServices.processResponse(responseData, null, function(reactionCode) {
    
                            //this method calls when the require        
                            //work has completed successfully        
                            //and results are returned to client        
                            defferedObject.resolve(responseData.data);  
    
                        }); 
    
                    });       
    
                   //return promise to caller          
                   return defferedObject.promise; 
                };
            }
        ])
        .controller('ProductOptionsController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            'ImageDataService',
            ProductOptionsController 
        ]);

    /**
      * ProductOptionsController for manage product options
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductOptionsController($scope, __DataStore, appServices,
     $stateParams, ImageDataService) {

        var dtProductOptionsColumnsData = [
                {
                    "name"          : "name",
                     "orderable"    : true
                },
                {
                    "name"          : "type",
                    "orderable"     : true,
                    "template"      : "#typeColumnTemplate"
                },
                {
                    "name"      : null,
                    "template"  : "#productOptionValuesColumnTemplate"
                },
                {
                    "name"      : null,
                    "template"  : "#productOptionActionColumnTemplate"
                }
            ],
            scope       = this,
            productID   = $stateParams.productID;

        /**
          * Get products  
          *
          * @return void
          *---------------------------------------------------------------- */
                        
        scope.optionListDataTable = __DataStore.dataTable('#productOptionList', {
            url         : {
                'apiURL'    : 'manage.product.option.list',
                'productID' : productID
            },
            dtOptions   : {
                "searching": true,
                "order": [[ 0, "asc" ]]
            },
            columnsData : dtProductOptionsColumnsData, 
            scope       : $scope
        });

        /*
         Reload current datatable
        -------------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.optionListDataTable);
        };

        /**
          * Add product new option
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.selectAddOption   = function(type) {

            appServices.showDialog({
                'type' : type
            },
            {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.options.add-dialog'
                ),
                controller : 'ProductOptionAddController as addOptionCtrl',
                resolve: {
                    GetImages: function() {
                        return ImageDataService.getImages(productID);
                    }
                }
            },
            function(promiseObj) {

                // Check if option added
                if (_.has(promiseObj.value, 'option_added') 
                    && promiseObj.value.option_added === true) {
                    scope.reloadDT();
                }

            });

        };

        /**
          * Edit prdouct existing option
          *
          * @param number optionID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.edit   = function(optionID) {

            __DataStore.fetch({
                    'apiURL'        : 'manage.product.option.edit.supportdata',
                    'optionID'      : optionID,
                    'productID'     : productID,
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {

                        appServices.showDialog({
                            'option_id'      : optionID,
                            'prdouct_id'     : productID,
                            'optionData'     : responseData.data.product_option
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'product.manage.options.edit-dialog'
                                )
                        },
                        function(promiseObj) {

                            // Check if option updated
                            if (_.has(promiseObj.value, 'option_updated') 
                                && promiseObj.value.option_updated === true) {
                                scope.reloadDT();
                            }

                        }
                    );

                });    

            });

        };

        /**
          * Show product option values dialog
          *
          * @param number optionID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.values   = function(optionID, optionName) {

            __DataStore.fetch({
                    'apiURL'        : 'manage.product.option.value.list',
                    'productID'     : productID,
                    'optionID'      : optionID
                 }, {
                    fresh : true
                 })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                        
                        appServices.showDialog({
                            'option_id'         : optionID,
                            'prdouct_id'        : productID,
                            'option_name'       : optionName,
                            'option_type'       : responseData.data.optionType,
                            'option_values'     : responseData.data.option_values,
                            'productImages'     : responseData.data.images,
                            'isOptionValueImagesUsed': responseData.data.isOptionValueImagesUsed
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'product.manage.options.values.list-dialog'
                                )
                        },
                        function(promiseObj) {

                        }
                    );

                });    

            });

        };

        /**
          * Add option values
          *
          * @param number optionID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.addValues = function(optionID, optionName) {

            appServices.showDialog({
                'option_id'         : optionID,
                'option_name'       : optionName,
                'prdouct_id'        : productID
            },
            {
                templateUrl : __globals.getTemplateURL(
                        'product.manage.options.values.add-dialog'
                    )
            },
            function(promiseObj) {

            });

        };
        

        /**
          * Delete option 
          *
          * @param number optionID
          * @param string optionName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(optionID, optionName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('product_option_delete_confirm_text'), {
                        '__name__'    : optionName
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL'        : 'manage.product.option.delete',
                    'optionID'      : optionID,
                    'productID'     : productID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {
                            error : function() {

                                __globals.showConfirmation({
                                    title   : 'Deleted!',
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                title   : 'Deleted!',
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });

        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductOptionAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.addOption', [])
        .controller('ProductOptionAddController',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            'FileUploader',
            '__Utils',
            'appNotify',
            'GetImages',
            ProductOptionAddController 
        ]);

    /**
      * ProductOptionAddController for add product option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductOptionAddController($scope, __Form, appServices, $stateParams, FileUploader, __Utils, appNotify, GetImages) {

        var scope       = this;
        scope.addOptionType = $scope.ngDialogData.type;
        scope.productID = $stateParams.productID;
        scope = __Form.setup(scope, 'form_product_add_option',
            'optionData',
            { 
                secured : false
            }
        );
        
        scope.productImages = GetImages.images;
        scope.isOptionValueImagesUsed = GetImages.isOptionValueImagesUsed;
            
        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#imageListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {

                    return  __Utils.template('#imageListItemImageTemplate', item);
                }
            },
            searchField : ['name']  
        });


        scope.productImagesSelectConfig     = __globals.getSelectizeOptions({
            valueField      : 'id',
            labelField      : 'title',
            searchField     : ['title'],
            maxItems		: 100,
            plugins			: ['remove_button'],
            onDropdownOpen : function ($dropdown) {
                this.renderCache = {};
            },
            onDropdownClose : function ($dropdown) {
                this.renderCache = {};  // clear the html template cache
            },
            render      : {
                item: function(item, escape) {
                   
                    return  __Utils.template('#productImageItemTemplate', {
                        'item' : item,
                        'itemId' : item.id,
                        'selectedIds' :  __globals.filterArrayValue(_.flatten(_.pluck(scope.optionData.values, 'slider_images')))
                    });
                },
                option: function(item, escape) {

                    var selectedIds =  __globals.filterArrayValue(_.flatten(_.pluck(scope.optionData.values, 'slider_images')));

                    if (_.includes(selectedIds, item.id)) {
                        return '<div class="lw-selectize-item lw-disabled-selectize-option"><span class="lw-selectize-item-thumb"> <img src="'+item.thumbnail_url+'"/> '+escape(item.title) + '- <small> Already Selected</small> </span></div>';
                    }
                   
                    return '<div class="lw-selectize-item"><span class="lw-selectize-item-thumb"> <img src="'+item.thumbnail_url+'"/> '+escape(item.title) + '</span></div>';
                }
            }
        });


        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', { fresh : true })
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files
                 
                    // scope.optionData.image = scope.image_files;
                    // scope.optionData.values.push({
                    //     name        : '',
                    //     addon_price : '',
                    //     image       : scope.image_files
                    // });
                   
                    if (responseData.data.files.length > 0) {
                        scope.images_count = responseData.data.files.length;
                    };
                });    

            });

        };

        scope.getTempImagesMedia();

        if (scope.addOptionType == 2) {
            scope.optionData.values  = [
                {
                    'name'          : '',
                    'addon_price'   : '',
                    'image'         : '',
                    'slider_images' : []
                }
            ];
        } else {
            scope.optionData.values  = [
                {
                    'name'          : '',
                    'addon_price'   : '',
                    'slider_images' : []
                }
            ];
        }
        

        /**
          * Add new value in option value
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.addNewValue = function() {
            if (scope.addOptionType == 2) {
                scope.optionData.values.push({
                    name        : '',
                    addon_price : '',
                    image       : '',
                    slider_images : []
                });
            } else {
                scope.optionData.values.push({
                    name        : '',
                    addon_price : '',
                    slider_images : []
                });
            }

        };

        /**
          * Remove current option value row
          *
          * @param number index
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.remove = function(index) {

            if (!_.isEmpty(scope.optionData.values)
             && scope.optionData.values.length > 1) {

                _.remove(scope.optionData.values, function(value, key) {
                    return index == key;
                });

            }
            
        };

        /**
          * Check if option value already taken
          *
          * @param valueNameFieldIndex
          * @param valueNameFieldValue
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.checkUnique = function(valueNameFieldIndex, valueNameFieldValue) {

            // Check if option values length greater 
            if (scope.optionData.values.length > 1) {
                _.forEach(scope.optionData.values,

                    function(optionValue, keyIndex) {

                    var optionValueName         = optionValue.name,
                        optionValueNameField    = 'values.'+keyIndex+'.name';
                    if (!_.isEmpty(optionValueName)) {

                        _.find(scope.optionData.values,
                             function(value, key) {

                             	if (!_.isEmpty(optionValueName)) {
                             		var newOptionValue = optionValueName.toLowerCase();
                             	} else {
                             		var newOptionValue = optionValueName;
                             	}

                             	if (!_.isEmpty(value.name)) {
                             		var valueNmae = value.name.toLowerCase();
                             	} else {
                             		var valueNmae = value.name;
                             	}


                            if (valueNmae == newOptionValue 
                                && keyIndex != key) {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', false);

                            } else {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', true);
                            }

                        });

                    } else {

                        scope
                            .form_product_add_option[optionValueNameField]
                             .$setValidity('unique', true);

                    }


              
                });

            } else {

                var valueField = 'values.'+valueNameFieldIndex+'.name';
                scope.form_product_add_option[valueField]
                        .$setValidity('unique', true);
            }
            
        };

        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.process'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
        scope.loadingStatus            = false;

        /**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {  
            //scope.thumbnailURL = response.data.thumbnailURL;
            scope.optionData.values[scope.newImageIndex].thumbnailURL = response.data.thumbnailURL;
            scope.imageFile = response.data.fileName;
            scope.optionData.values[scope.newImageIndex].image = scope.imageFile;
            scope.newImageIndex = null;
      
            appServices.processResponse(response, { 
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        scope.newImageIndex = null;
        scope.addImages = function(index, data) {

            scope.newImageIndex = index;

        };
       
        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };
        
        /**
          * Submit product add option form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            scope.optionData.type = scope.addOptionType;
       
            __Form.process({
                'apiURL'        : 'manage.product.option.add',
                'productID'     : $stateParams.productID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'option_added' : true });
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.uploaded-media'
                )
            }, function(promiseObj) {

                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files   = promiseObj.value.files;
                    scope.images_count  = promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductOptionEditController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.editOption', [])
        .controller('ProductOptionEditController',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            ProductOptionEditController 
        ]);

    /**
      * ProductOptionEditController for edit prdouct option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductOptionEditController($scope, __Form, appServices, $stateParams) {

        var scope       = this,
            dialogData  = $scope.ngDialogData;

        scope.optionID = dialogData.option_id;
        scope = __Form.setup(scope, 'form_product_edit_option',
            'optionData',
            { 
                secured : false
            }
        );

        scope = __Form.updateModel(scope, dialogData.optionData);

        /**
          * Submit prdouct edit option form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process({
                'apiURL'        : 'manage.product.option.edit',
                'productID'     : dialogData.prdouct_id,
                'optionID'      : dialogData.option_id
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'option_updated' : true });
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductOptionValuesController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.optionValues', [])
        .controller('ProductOptionValuesController',   [
            '$scope',
            '__Form',
            '__DataStore',
            'appServices',
            'FileUploader',
            '__Utils',
            'appNotify',
            ProductOptionValuesController 
        ]);

    /**
      * ProductOptionValuesController for manage product option values
      *
      * @inject $scope
      * @inject __Form
      * @inject __DataStore
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductOptionValuesController($scope, __Form, __DataStore,  appServices, FileUploader, __Utils, appNotify) {

        var scope       = this,
            dialogData  = $scope.ngDialogData,
            productID   = dialogData.prdouct_id,
            optionID    = dialogData.option_id;
            scope.optionLableID = dialogData.prdouct_id;
            scope.optionType = dialogData.option_type;
            scope.productImages = dialogData.productImages;
            scope.productID = productID;
        scope.isOptionValueImagesUsed = dialogData.isOptionValueImagesUsed;
        scope = __Form.setup(scope, 'form_product_edit_option_values',
            'optionData',
            { 
                secured : false
            }
        );

        scope.notification_message = __ngSupport.getText(
            __globals.getJSString('product_option_value_add_form_notification'),
             {
                '__option_name__'    : dialogData.option_name
             }
        );

        
        scope.imagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            render      : {
                item: function(item, escape) {
                    return  __Utils.template('#imageListItemTemplate',
                    item
                    );
                },
                option: function(item, escape) {
                    return  __Utils.template('#imageListOptionTemplate',
                    item
                    );
                }
            }, 
            searchField : ['name']  
        });
        
        var existingImagesIds = __globals.filterArrayValue(_.flatten(_.pluck(dialogData.option_values, 'slider_images')));
      
        scope.productImagesSelectConfig     = __globals.getSelectizeOptions({
            valueField      : 'id',
            labelField      : 'title',
            searchField     : ['title'],
            maxItems		: 100,
            plugins			: ['remove_button'],
            onDelete : function(values) {
                
                if (!_.isEmpty(_.intersection(existingImagesIds, __globals.filterArrayValue(values)))) {
                    __DataStore.post({
                        'apiURL'    : 'manage.product.image.read.process_unset',
                        'productID' : productID,
                        'imageId'   : values
                    }).success(function(responseData) {

                        appServices.processResponse(responseData, null,function(data) {
                            
                        });   

                    });   
                }
            },
            onDropdownOpen : function ($dropdown) {
                this.renderCache = {};
            },
            onDropdownClose : function ($dropdown) {
                this.renderCache = {};  // clear the html template cache
            },
            render      : {
                item: function(item, escape) {
                   
                    return  __Utils.template('#productImageItemTemplate', {
                        'item' : item,
                        'itemId' : item.id,
                        'selectedIds' :  __globals.filterArrayValue(_.flatten(_.pluck(scope.optionData.values, 'slider_images')))
                    });
                },
                option: function(item, escape) {

                    var selectedIds =  __globals.filterArrayValue(_.flatten(_.pluck(scope.optionData.values, 'slider_images')));

                    if (_.includes(selectedIds, item.id)) {
                        return '<div class="lw-selectize-item lw-disabled-selectize-option"><span class="lw-selectize-item-thumb"> <img src="'+item.thumbnail_url+'"/> '+escape(item.title) + '- <small> Already Selected</small> </span></div>';
                    }
                   
                    return '<div class="lw-selectize-item"><span class="lw-selectize-item-thumb"> <img src="'+item.thumbnail_url+'"/> '+escape(item.title) + '</span></div>';
                }
            }
        });

        /**
          * Fetch uploaded temp images media files
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.images_count = 0;
        scope.getTempImagesMedia = function() {

            __Form.fetch('media.uploaded.images', { fresh : true })
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    scope.image_files = responseData.data.files

                    if (responseData.data.files.length > 0) {
                        scope.images_count = responseData.data.files.length;
                    };
                });    

            });

        };

        scope.getTempImagesMedia();
        
        scope.optionData.values = [];
        scope.optionData.optionID = optionID;   
        scope = __Form.updateModel(scope, { values : dialogData.option_values });

        /**
          * Add new value in options value
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.addNewValue = function() {
            scope.optionData.values.push({
                name        : '',
                addon_price : '',
                image       : '',
                slider_images : []

            });
        };

        /**
          * Remove current option value row
          *
          * @param number index
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.remove = function(index) {

            if (!_.isEmpty(scope.optionData.values)
             && scope.optionData.values.length > 1) {

                _.remove(scope.optionData.values, function(value, key) {
                    return index == key;
                });

            }
            
        };

        /**
          * Check if option value already taken
          *
          * @param valueNameFieldIndex
          * @param valueNameFieldValue
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.checkUnique = function(valueNameFieldIndex, valueNameFieldValue) {

            // Check if option values length greater 
            if (scope.optionData.values.length > 1) {
                _.forEach(scope.optionData.values,

                    function(optionValue, keyIndex) {

                    var optionValueName         = optionValue.name,
                        optionValueNameField    = 'values.'+keyIndex+'.name';
                    if (!_.isEmpty(optionValueName)) {

                        _.find(scope.optionData.values,
                             function(value, key) {

                                if (!_.isEmpty(optionValueName)) {
                                    var newOptionValue = optionValueName.toLowerCase();
                                } else {
                                    var newOptionValue = optionValueName;
                                }

                                if (!_.isEmpty(value.name)) {
                                    var valueNmae = value.name.toLowerCase();
                                } else {
                                    var valueNmae = value.name;
                                }


                            if (valueNmae == newOptionValue 
                                && keyIndex != key) {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', false);

                            } else {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', true);
                            }

                        });

                    } else {

                        scope
                            .form_product_add_option[optionValueNameField]
                             .$setValidity('unique', true);

                    }


              
                });

            } else {

                var valueField = 'values.'+valueNameFieldIndex+'.name';
                scope.form_product_add_option[valueField]
                        .$setValidity('unique', true);
            }
            
        };

        var uploader = scope.uploader = new FileUploader({
            url         : __Utils.apiURL('media.upload.process'),
            autoUpload  : true,
            headers     : {
                'X-XSRF-TOKEN': __Utils.getXSRFToken()
            }
        });

        // FILTERS
        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1000;
            }
        });

        scope.currentUploadedFileCount = 0;
        scope.loadingStatus            = false;

        /**
        * uploading msg
        *
        * @return void
        *---------------------------------------------------------------- */
        uploader.onAfterAddingAll = function() {

            scope.loadingStatus = true;
            appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

        };

        /**
        * Uploading on process
        *
        * @return void
        *---------------------------------------------------------------- */

        uploader.onBeforeUploadItem = function(item) {
            scope.loadingStatus = true;
        };


        /**
        * on success counter of uploaded image
        *
        * @param object fileItem
        * @param object response
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onSuccessItem = function( fileItem, response ) {
            // if (!_.isEmpty(response.data.thumbnailURL)) {
            //     scope.thumbnailURL = response.data.thumbnailURL;
            // }
            scope.optionData.values[scope.newImageIndex].thumbnailURL = response.data.thumbnailURL;
            scope.imageFile = response.data.fileName;
            scope.optionData.values[scope.newImageIndex].image = scope.imageFile;
            scope.newImageIndex = null;
        
            appServices.processResponse(response, {
                error : function() {
                },
                otherError : function(reactionCode) {
                  
                    // If reaction code is Server Side Validation Error Then 
                    if (reactionCode == 3) {

                        appNotify.error(response.data.message,{sticky : false});

                    }

                }
            },
            function() {

                scope.currentUploadedFileCount++
                
            });   

        };

        scope.newImageIndex = null;
        scope.addImages = function(index, data) {

            scope.newImageIndex = index;

        };

        /**
        * uploaded all image then call function
        *
        * @return void
        *---------------------------------------------------------------- */
        
        uploader.onCompleteAll  = function() {

           scope.loadingStatus  = false;
            if (scope.currentUploadedFileCount > 0) {
                appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

            }
            scope.getTempImagesMedia();
            scope.currentUploadedFileCount = 0;

        };


        /**
          * Fetch product option values 
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.fetchOptionValues = function() {

            __Form.fetch({
                    'apiURL'        : 'manage.product.option.value.list',
                    'productID'     : productID,
                    'optionID'      : optionID, 
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {

                        scope = __Form.updateModel(scope, {
                            values : responseData.data.option_values
                        });

                    });

                });
        };

        /**
          * Remove current option value row
          *
          * @param number index
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.remove = function(index) {

            if (!_.isEmpty(scope.optionData.values)
             && scope.optionData.values.length > 1) {

                _.remove(scope.optionData.values, function(value, key) {
                    return index == key;
                });

            }
            
        };
        

        /**
          * Delete option value
          *
          * @param number optionValueID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(optionValueID, optionType) {
    
         __DataStore.post({
                'apiURL'        : 'manage.product.option.value.delete',
                'optionID'      : optionID,
                'productID'     : productID,
                'optionValueID' : optionValueID,
                'optionType'    : optionType
            })
            .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    /*scope.fetchOptionValues();*/
                    _.remove(scope.optionData.values, function(value) {
                      return value.id == optionValueID;
                    });

                });    

            });
        
        };

        /**
          * Check if option value already taken
          *
          * @param valueNameFieldIndex
          * @param valueNameFieldValue
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.checkUnique = function(valueNameFieldIndex, valueNameFieldValue) {

            // Check if option values length greater 
            if (scope.optionData.values.length > 1) {

                _.forEach(scope.optionData.values,
                    function(optionValue, keyIndex) {

                    var optionValueName         = optionValue.name,
                        optionValueNameField    = 'values.'+keyIndex+'.name';

                    if (!_.isEmpty(optionValueName)) {

                        _.find(scope.optionData.values,
                             function(value, key) {

                            if (!_.isEmpty(optionValueName)) {
                         		var newOptionValue = optionValueName.toLowerCase();
                         	} else {
                         		var newOptionValue = optionValueName;
                         	}

                         	if (!_.isEmpty(value.name)) {
                         		var valueNmae = value.name.toLowerCase();
                         	} else {
                         		var valueNmae = value.name;
                         	}
                         	
                            if (valueNmae == newOptionValue 
                                && keyIndex != key) {

                                scope
                                .form_product_edit_option_values[optionValueNameField]
                                    .$setValidity('unique', false);

                            } else {

                                scope
                                .form_product_edit_option_values[optionValueNameField]
                                    .$setValidity('unique', true);
                            }

                        });

                    } else {
                        scope
                            .form_product_edit_option_values[optionValueNameField]
                             .$setValidity('unique', true);
                    }

                });

            } else {

                var valueField = 'values.'+valueNameFieldIndex+'.name';
                scope.form_product_edit_option_values[valueField]
                        .$setValidity('unique', true);
            }
            
        };

        /**
          * Submit prdouct option edit values form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            scope.optionData.type = scope.optionType;
            
            __Form.process({
                'apiURL'        : 'manage.product.option.value.edit',
                'productID'     : productID,
                'optionID'      : optionID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog();
                });    

            });

        };

        /**
          * Show uploaded media files
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.showUploadedMediaDialog = function() {

            appServices.showDialog({ 'image_files' : scope.image_files }, {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.uploaded-media'
                )
            }, function(promiseObj) {

                if (_.has(promiseObj.value, 'files')) {
                    scope.image_files   = promiseObj.value.files;
                    scope.images_count  = promiseObj.value.files.length;
                } else {
                    scope.getTempImagesMedia();
                }

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductOptionValuesAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.addOptionValues', [])
        .controller('ProductOptionValuesAddController',   [
            '$scope',
            '__Form', 
            'appServices',
            ProductOptionValuesAddController 
        ]);

    /**
      * ProductOptionValuesAddController for add prdouct option values
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductOptionValuesAddController($scope, __Form, appServices) {

        var scope       = this,
            dialogData  = $scope.ngDialogData;

        scope = __Form.setup(scope, 'form_product_add_option',
            'optionData',
            { 
                secured : false
            }
        );
        scope.optionData.values  = [
            {
                'name'          : '',
                'addon_price'   : ''
            }
        ];

        scope.notification_message = __ngSupport.getText(
            __globals.getJSString('product_option_value_add_form_notification'),
             {
                '__option_name__'    : dialogData.option_name
             }
        );

        /**
          * Add new value in options value
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.addNewValue = function() {
            scope.optionData.values.push({
                name        : '',
                addon_price : ''
            });
        };

        /**
          * Remove current option value row
          *
          * @param number index
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.remove = function(index) {

            if (!_.isEmpty(scope.optionData.values)
             && scope.optionData.values.length > 1) {

                _.remove(scope.optionData.values, function(value, key) {
                    return index == key;
                });

            }
            
        };

        /**
          * Check if option value already taken
          *
          * @param valueNameFieldIndex
          * @param valueNameFieldValue
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.checkUnique = function(valueNameFieldIndex, valueNameFieldValue) {

            // Check if option values length greater 
            if (scope.optionData.values.length > 1) {

                _.forEach(scope.optionData.values,
                    function(optionValue, keyIndex) {

                    var optionValueName         = optionValue.name,
                        optionValueNameField    = 'values.'+keyIndex+'.name';
                    if (!_.isEmpty(optionValueName)) {

                        _.find(scope.optionData.values,
                             function(value, key) {

                            if (!_.isEmpty(optionValueName)) {
                         		var newOptionValue = optionValueName.toLowerCase();
                         	} else {
                         		var newOptionValue = optionValueName;
                         	}

                         	if (!_.isEmpty(value.name)) {
                         		var valueNmae = value.name.toLowerCase();
                         	} else {
                         		var valueNmae = value.name;
                         	}
                            
                            if (valueNmae == newOptionValue 
                                && keyIndex != key) {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', false);

                            } else {

                                scope
                                .form_product_add_option[optionValueNameField]
                                    .$setValidity('unique', true);
                            }

                        });

                    } else {

                        scope
                            .form_product_add_option[optionValueNameField]
                             .$setValidity('unique', true);

                    }


              
                });

            } else {

                var valueField = 'values.'+valueNameFieldIndex+'.name';
                scope.form_product_add_option[valueField]
                        .$setValidity('unique', true);
            }
            
        };
        
        
        /**
          * Submit prdouct add option form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            
            __Form.process({
                'apiURL'        : 'manage.product.option.value.add',
                'productID'     : dialogData.prdouct_id,
                'optionID'      : dialogData.option_id
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog();
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductSpecificationController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.specification', [])
        .controller('ProductSpecificationController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            '__Form',
            ProductSpecificationController 
        ]);

    /**
      * ProductSpecificationController for manage product options
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductSpecificationController($scope, __DataStore, appServices, $stateParams, __Form) {

        var scope       = this,
            productID   = $stateParams.productID;

        scope.initialPageContentLoaded = false;

        scope = __Form.setup(scope, 'form_specification_values_add', 'specificationData');

        scope.assign_specification_select_config = __globals.getSelectizeOptions({
            valueField  : '_id',
            labelField  : 'title',
            searchField : [ 'title' ]
        });

        scope.specification_select_config = __globals.getSelectizeOptions({
                                                valueField  : 'label',
                                                labelField  : 'label',
                                                searchField : [ 'label' ],
                                                plugins: ['restore_on_backspace', 'remove_button'],
                                                maxItems : 1000,
                                                delimiter: ',',
                                                persist: true,
                                                create: function(input) {
                                                    return {
                                                        _id : input,
                                                        label: input
                                                    }
                                                }
                                            });
   

        /**
          * Get List of specification
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.getSpecificationList = function() {
            __DataStore.fetch({
                'apiURL'    : 'manage.product.specification.list',
                'productID' : productID
            }).success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    var requestData = responseData.data;
                
                    scope.specification_presets__id = requestData.presetId;
                    scope.isSpecificationExist = requestData.isSpecificationExist;
                    scope.specificationData.specifications = requestData.specifications;
                    scope.presetTitle = requestData.presetTitle;
                    scope.specificationList = requestData.specificationList;
                    scope.otherSpecifications = requestData.otherSpecifications;
                    scope.oldSpecification = requestData.oldSpecification;
                      
                    scope.initialPageContentLoaded = true;
                });
            });
        }

        scope.getSpecificationList();

        /**
          * Add Category specification
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addSpecification = function(presetType, specification) {
          
           __Form.process({
                'apiURL': 'manage.product.specification.add',
                'productID' : productID,
                'presetType': presetType
           }, scope).success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    scope.getSpecificationList();
                });
           });
        }

        // Change Specification Preset
        scope.changeSpecificationPreset = function(presetId) {
            if (!_.isUndefined(presetId) || !_.isEmpty(presetId)) {
                var updateData = {
                    presetId: presetId,
                    isDelete: (presetId == 'delete') ? true : false
                }
                __DataStore.post({
                    'apiURL': 'manage.product.specification.change_preset',
                    'productID' : productID,
               }, updateData).success(function(responseData) {
                    appServices.processResponse(responseData, null, function() {
                        scope.getSpecificationList();
                    });
               });
           }
        }

        /**
          * Add product new specification
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.add   = function() {
            appServices.showDialog(
            {
                'otherSpecifications': scope.otherSpecifications
            },
            {
                templateUrl : __globals.getTemplateURL(
                    'product.manage.specification.add-dialog'
                )
            },
            function(promiseObj) {
                // Check if option added
                if (_.has(promiseObj.value, 'specification_added') 
                    && promiseObj.value.specification_added === true) {
                    scope.getSpecificationList();
                }
            });
        };

        /**
          * Edit prdouct existing specification
          *
          * @param number specificationID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.edit   = function(specificationID) {
        	
            __DataStore.fetch({
                    'apiURL'          : 'manage.product.specification.edit',
                    'productID'       : productID,
                    'specificationID' : specificationID,
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                    
                        appServices.showDialog({

                            'specificationValues'     : responseData.data.secificationValues,
                            'specificationCollection' : responseData.data.specificationCollection,
                            'assignSpecificationData' : responseData.data.assignSpecificationData
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'product.manage.specification.edit-dialog'
                                )
                        },
                        function(promiseObj) {

                            // Check if option updated
                            if (_.has(promiseObj.value, 'specification_updated') 
                                && promiseObj.value.specification_updated === true) {
                                scope.getSpecificationList();
                            }

                        }
                    );

                });    

            });

        };

        /**
          * Delete product specification 
          *
          * @param number specificationID
          * @param string specificationName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(specificationID, specificationName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('product_specification_delete_confirm_text'), {
                        '__name__'    : specificationName
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL'        	: 'manage.product.specification.delete',
                    'specificationID'   : specificationID,
                    'productID'     	: productID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {
                            error : function() {

                                __globals.showConfirmation({
                                    title   : 'Deleted!',
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                title   : 'Deleted!',
                                text    : message,
                                type    : 'success'
                            });
                            scope.getSpecificationList();
                        }
                    );    

                });

            });

        };

    };

})();;
(function() {
'use strict';
    
    /*
     ProductSpecificationAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.addSpecification', [])
        .controller('ProductSpecificationAddController',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            '__DataStore',
            ProductSpecificationAddController 
        ]);

    /**
      * ProductSpecificationAddController for add product option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductSpecificationAddController($scope, __Form, appServices, $stateParams, __DataStore) {

        var scope       = this;

        scope = __Form.setup(scope, 'form_product_add_specification',
            'specificationData',
            { 
                secured : false
            }
        );

        scope.specification_label_select_config = __globals.getSelectizeOptions({
            valueField  : 'id',
            labelField  : 'label',
            searchField : [ 'label' ],
		    create: function(input) {
		        return {
		            id: input,
		            label: input
		        }
		    }
        });

        scope.specification_value_select_config = __globals.getSelectizeOptions({
            valueField  : 'label',
            labelField  : 'label',
            searchField : [ 'label' ],
            plugins: ['restore_on_backspace', 'remove_button'],
            maxItems : 1000,
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    label : String(input),
                    label: input
                }
            }
        });

        scope.specificationCollection = $scope.ngDialogData.otherSpecifications;
        scope.specValues = [];

        //selected spec values
        scope.getSelectedSpecification = function(specId) {
            var specificationId = parseInt(specId);
           
            if (!_.isNaN(specificationId)) {
               
                _.forEach(scope.specificationCollection, function(value, key) {
               
                    if (value.id == specId) {
                        scope.specValues = value.specValues;
                    }
                });
            }
            
        };
     
        /**
          * Submit product add specification form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            
            __Form.process({
                'apiURL'        : 'manage.product.specification.add',
                'productID'     : $stateParams.productID,
                'presetType'    : 2
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'specification_added' : true });
                });    

            });

        };
        
        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductFaqsAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.addFaq', [])
        .controller('ProductFaqsAddController',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            ProductFaqsAddController 
        ]);

    /**
      * ProductFaqsAddController for add product option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductFaqsAddController($scope, __Form, appServices, $stateParams) {

        var scope       = this;

        scope = __Form.setup(scope, 'product_faq_add_form', 'addFaqData');
        
       
        /**
          * Submit product add specification form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
         
            __Form.process({
                'apiURL'        : 'manage.product.faq.add',
                'productId'     : $stateParams.productID
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'faq_added' : true });
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductFaqsEditontroller
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.editFaq', [])
        .controller('ProductFaqsEditontroller',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            ProductFaqsEditontroller 
        ]);

    /**
      * ProductFaqsEditontroller for add product option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductFaqsEditontroller($scope, __Form, appServices, $stateParams) {

        var scope       = this,
            dialogData  = $scope.ngDialogData;

        scope = __Form.setup(scope, 'product_faq_edit_form', 'EditFaqData');
        
        scope   = __Form.updateModel(scope, dialogData.faqData);

        /**
          * Submit product edit faq form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
            __Form.process({
                'apiURL'        : 'manage.product.faq.update',
                'productId'     : $stateParams.productID,
                'faqID'         : scope.EditFaqData._id
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'faq_updated' : true });
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
(function() {
'use strict';
    
    /*
     ProductSpecificationEditController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.editSpecification', [])
        .controller('ProductSpecificationEditController',   [
            '$scope',
            '__Form', 
            'appServices',
            '$stateParams',
            ProductSpecificationEditController 
        ]);

    /**
      * ProductSpecificationEditController for edit prdouct option
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductSpecificationEditController($scope, __Form, appServices, $stateParams) {

        var scope       = this,
            dialogData  = $scope.ngDialogData;
            //specificationCollection
        	scope 	= __Form.setup(scope, 'form_specification_edit', 'specificationData');
			scope   = __Form.updateModel(scope, dialogData.specificationValues);
		
			$stateParams

			scope.specification_name_select_config = __globals.getSelectizeOptions({
            valueField  : 'name',
            labelField  : 'name',
            searchField : [ 'name' ],

		    create: function(input) {
		        return {
		             _id: input,
		            name: input
		        }
		    }
        });

        scope.specification_value_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'value',
            searchField : [ 'value' ],

		    create: function(input) {
		        return {
		            value: input
		        }
		    }
        });
      
        /**
          * Submit prdouct edit specification form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            __Form.process({
                'apiURL'        	   : 'manage.product.specification.update',
                'productID'			   : $stateParams.productID,
                'specificationID'      : scope.specificationData._id
            }, scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog({ 'specification_updated' : true });
                });    

            });

        };

        /**
          * Cancel current dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.cancel = function() {
            $scope.closeThisDialog();
        };
        
    };

})();;
/*!
*  Component  : Product
*  File       : ProductRatingListController.js  
*  Engine     : Product 
------------------------------------------------------------------- */

(function() {
'use strict';
    
    /*
     ProductListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.productRatingList', [])
        .controller('ProductRatingListController',     [
            '$scope', 
            '__DataStore', 
            'appServices',
            '$state',
            '__Utils',
            '$rootScope',
            ProductRatingListController 
         ]);


     /**
      * ProductRatingListController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
    function ProductRatingListController($scope, __DataStore, appServices, $state, __Utils, $rootScope) {

        var scope   = this;
        var dtColumnsData = [
            {
                "name"      : "productName",
                "orderable" : true
            },
            {
                "name"      : "formatRating",
                "orderable" : true
            },
            {
                "name"      : "createdAt",
                "orderable" : true,
                "template"  : "#creationDateColumnTemplate"
            }
        ],
        scope   = this;

        /**
        * Request to server
        *
        * @return  void
        *---------------------------------------------------------- */

        scope.productRatingDataTable = __DataStore.dataTable('#lwProductRatingList', {
            url         : 'manage.product.rating.read.list', 
            dtOptions   : {
                "searching": true
            },
            columnsData : dtColumnsData, 
            scope       : $scope
        });

        /*
        Reload current datatable
        ------------------------------------------------------------ */
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.productRatingDataTable);
        };
        
        // when add new record 
        $scope.$on('product_rating_added_or_updated', function (data) {
            
            if (data) {
                scope.reloadDT();
            }

        });
            
    };


})();;
(function() {
'use strict';
	
	/*
	 ProductListController
	-------------------------------------------------------------------------- */
	
	angular
        .module('ManageApp.productList', [])
        .controller('ProductListController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '$state',
            '__Utils',
            '$rootScope',
            ProductListController 
	 	])
	 	.controller('ProductDetailDialogController',   [
            '$scope',
            ProductDetailDialogController 
        ]);

	/**
	 * CategoryController for admin.
	 *
	 * @inject __DataStore
	 * @inject $scope
	 * @inject $state
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function ProductListController($scope, __DataStore, appServices, $state, __Utils, $rootScope) {

	 	var scope   			= this,
		 	catID 				= _.isEmpty($state.params.mCategoryID)
			 				 		? null 
			 				 		: $state.params.mCategoryID,
		 	currentStateName 	= $state.current.name;

		// Get current state name
	 	scope.currentStateName 			= currentStateName;

	 	scope.pageContentLoaded 		= false;
	 	scope.parentCategoryExist 		= false;

	 	// Get category ID
		scope.categoryID = catID;


	    var dtProductsColumnsData = [
                {
                    "name"      : "thumbnail",
                    "template"  : "#productThumbnailColumnTemplate"
                },
                {
                    "name"      : "name",
                    "orderable" : true,
                    "template"	: "#nameColumnTemplate"
                },
                {
                    "name"      : "featured",
                    "orderable" : true,
                    "template"	: "#featuredProductColumnTemplate"
                },
                {
                    "name"      : "out_of_stock",
                    "orderable" : true,
                    "template"	: "#outOfStockColumnTemplate"
                },
                {
                    "name"      : "status",
                    "orderable" : true,
                    "template"  : "#productStatusColumnTemplate"
                },
                {
                    "name"      : "creation_date",
                    "orderable" : true,
                    "template"	: "#creationDateColumnTemplate"
                },
                {
                    "name"      : "categories_id",
                    "template"  : "#productCategoriesColumnTemplate"
                },
                {
                    "name"      : "brand",
                    "template"  : "#productBrandColumnTemplate"
                },
                {
                    "name"      : null,
                    "template"  : "#productActionColumnTemplate"
                }
            ],
	            // tabs    = {
	            //     'manageProducts'    : {
	            //            id      : 'productsTabList',
	            //            route   : 'products'
	            //     }
	            // },
            scope   = this,
            url;
         
        // get category and brand
        if (!_.isEmpty(catID)) {

            url = {
                'apiURL'        : 'manage.category.product.list',
                'categoryID'    : catID
            };

        }  else if (!_.isEmpty($state.params.brandID)) {
				
            url = {
              'apiURL'     : 'manage.brand.product.list',
               'brandId'   : $state.params.brandID
            };


	    } else {

            url = 'manage.product.list';

        }

        scope.category 			= {};
        scope.categoryStatus 	= false;
        scope.pageStatus 		= false;
        scope.brandStatus		= false;

         // Fired when clicking on tab    
        // $('#manageProductTab a').click(function (e) {

        //     e.preventDefault();

        //     var $this       = $(this),
        //         tabName     = $this.attr('aria-controls'),
        //         selectedTab = tabs[tabName];

        //     // Check if selectedTab exist    
        //     if (!_.isEmpty(selectedTab)) {

        //         $(this).tab('show')
        //         //scope.getProducts(selectedTab.id);

        //     }
            
        // });

        if ($rootScope.canAccess('manage.product.list') || $rootScope.canAccess('manage.brand.product.list') || $rootScope.canAccess('manage.category.product.list')) {

            var selectedTab = $('.nav li a[href="#manageProducts"]');

            selectedTab.triggerHandler('click', true);
        }
        

        /**
          * Get products  
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.getProducts  = function() {

        	// destroy instance of datatable
	    	if (scope.productsListDataTable) {
	            scope.productsListDataTable.destroy();
	        }

	        scope.productsListDataTable = __DataStore.dataTable('#productsTabList', {
	            url         : url,
	            dtOptions   : {
	                "searching"    : true,
	                "order"        : [[ 1, "asc" ]]
	            },
	            columnsData : dtProductsColumnsData, 
	            scope       : $scope

	        },null, function(responseData) {
	        
	        	scope.category 	 = responseData._options.category;
	        	scope.parentData = responseData._options.parentData;
                
	        	//scope.isParentCategory  = _.isNull(scope.category.parent_id) ? false : true;
	        	
	        	// Check if category exist
	        	if (_.isEmpty(scope.category)) {
	        		scope.categoryStatus 	= false;
	        	} else {
	        		scope.categoryStatus 	= true;
	        	}

	        	// Check if Brand Exist
	        	scope.brand = responseData._options.brand;

	        	if (_.isEmpty(scope.brand)) {
	        		scope.brandStatus = false;
	        	} else {
	        		scope.brandStatus = true;
	        	}

	        	scope.pageStatus = true;

	        });
	       
	    };

		_.defer(function() {

			scope.getProducts();

		});

	    /**
          * Go to categories URL
          *
          * @param $event
          *
          * @return void
          *---------------------------------------------------------------- */
     //    scope.goToCategories = function ($event) {
	    //     $event.preventDefault();
	    //     $state.go('categories', {'mCategoryID' : catID});
	    // };

	    /**
          * Go to products URL 
          *
          * @param $event
          *
          * @return void
          *---------------------------------------------------------------- */
     //    scope.goToProducts = function ($event) {
	    //     $event.preventDefault();
	    //     $state.go('products', {'mCategoryID' : catID});
	    // };

	    /*
	     * Reload current datatable
	     *
	     *------------------------------------------------------------------ */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.productsListDataTable);
        };

        /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (productID) {

	    	__DataStore.fetch({
	        	'apiURL'	: 'manage.product.detailSupportData',
	        	'productID'	: productID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

		    	appServices.showDialog(requestData,
		        {	
		            templateUrl : __globals.getTemplateURL(
		                    'product.manage.detail-dialog'
		                )
		        },
		        function(promiseObj) {

		        });
	       });
	    }

	    /**
          * Add new category
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.add   = function(catID) {

        	this.catID = catID;

        	__DataStore.fetch('category.fancytree.support-data')
					.success(function(responseData) {

          			scope.categories = responseData.data.categories;
          		
	          		appServices.showDialog(scope,
	                {
	                    templateUrl : __globals.getTemplateURL(
	                            'category.add-dialog'
	                        )
	                },
	                function(promiseObj) {

	                    // Check if category updated
	                    if (_.has(promiseObj.value, 'category_added') 
	                        && promiseObj.value.category_added === true) {
	                        scope.reloadDT();
	                    }

	                });
	    	});
            

        };

        /**
          * Delete product 
          *
          * @param number productID
          * @param string productName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.productDelete = function(productID, productName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('product_delete_confirm_text'), {
                        '__name__'    : unescape(productName)
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL'    : 'manage.product.delete',
                    'productID' : productID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;

                    appServices.processResponse(responseData, {
                            error : function() {

                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function() {

                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });

        };

    };

    /**
      * ProductDetailDialogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
    function ProductDetailDialogController($scope) {

        var scope   = this;
       
        scope.ngDialogData  	= $scope.ngDialogData;
	    scope.productData 		= scope.ngDialogData;
	    scope.currencySymbol	= scope.ngDialogData.currencySymbol;
	/**
	  * Close dialog
	  *
	  * @return void
	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
	  		$scope.closeThisDialog();
	  	};
	            
    };

})();;
(function() {
'use strict';
    
    /*
     ProductSeoMetaController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.seoMeta', [])
        .controller('ProductSeoMetaController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            '__Form',
            ProductSeoMetaController 
        ]);

    /**
      * ProductSeoMetaController for manage product options
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductSeoMetaController($scope, __DataStore, appServices, $stateParams, __Form) {

        var scope       = this,
            productID   = $stateParams.productID;

        scope.initialPageContentLoaded = false;

        scope = __Form.setup(scope, 'form_seo_meta', 'seoMeta');

     
        /**
          * Get List of specification
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.fetchSeoMeta = function() {
            __DataStore.fetch({
                'apiURL'    : 'manage.product.seo_meta.read',
                'productID' : productID
            }).success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    var requestData = responseData.data;

                    __Form.updateModel(scope, requestData.seo_meta);
                    
                    scope.initialPageContentLoaded = true;
                });
            });
        }

        scope.fetchSeoMeta();

        /**
          * Add Category specification
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.submit = function() {
          
           __Form.process({
                'apiURL': 'manage.product.seo_meta.write',
                'productID' : productID,
           }, scope).success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    scope.fetchSeoMeta();
                });
           });
        }
    };

})();;
(function() {
'use strict';
    
    /*
     ManageProductRatingsListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.ratings', [])
        .controller('ManageProductRatingsListController',   [
            '$scope',
            '__DataStore',
            'appServices',
            '$stateParams',
            ManageProductRatingsListController 
        ]);

    /**
      * ManageProductRatingsListController handle add product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductRatingsListController($scope, __DataStore, appServices, $stateParams) {

		
		var dtItemCommentColumnsData = [
            {
                "name"      : "fname",
                "orderable" : true,
                "template"  : "#itemFullNameTemplate"
            },
            {
                "name"      : "formatRating",
                "orderable" : false
            },
            {
                "name"      : "review",
                "orderable" : true
            },
            {
                "name"      : "rated_on",
                "template"  : "#itemRatedOnTemplate"
            },
            {
                "name"      : null,
                "template"  : "#itemRatingsColumnActionTemplate"
            }
        ],
        scope  = this;
        scope.contentLoaded = false;
        scope.ratingListDataTable = __DataStore.dataTable('#lwItemRatingsTable', {
            url     : {
                'apiURL'    : 'manage.product.ratings.read.list',
                'productId' : $stateParams.productID
            },
            columnsData     : dtItemCommentColumnsData, 
            dtOptions   : {
                "searching" : true
            },
            scope : $scope
        }, null, function(ratingData) {
            
            scope.totalRatingAvg = ratingData._options.totalRatingAvg;

            scope.contentLoaded = true;
        });

        /*
         Reload current datatable
        -------------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.ratingListDataTable);
        };

        /**
          * delete rating request
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function (ratingId, title) {

            __globals.showConfirmation({
                html                : __globals.getJSString('delete_rating'),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            },
            function() {

                __DataStore.post({
                    'apiURL'    : 'manage.product.rating.write.delete',
                    'productId' : $stateParams.productID,
                    'ratingId'  : ratingId
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                        
                        error : function(data) {
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'error'
                            });

                        }

                    },
                    function(data) {
                            
                        __globals.showConfirmation({
                            title   : __globals.getJSString('confirm_error_title'),
                            text    : message,
                            type    : 'success'
                        });

                        scope.reloadDT();

                    });    

                });

            });
        };
        

    };

})();;
(function() {
'use strict';
    
    /*
     ManageProductFaqsListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.faqs', [])
        .controller('ManageProductFaqsListController',   [
            '$scope',
            '__DataStore',
            'appServices',
            '$stateParams',
            ManageProductFaqsListController 
        ])
        .controller('FaqDetailController',   [
            '$scope',
            '__DataStore',
            'appServices',
            '$stateParams',
            FaqDetailController 
        ])

    /**
      * ManageProductFaqsListController handle add product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageProductFaqsListController($scope, __DataStore, appServices, $stateParams) {

		
		var dtItemFaqColumnsData = [
            {
                "name"      : "question",
                "orderable" : true,
                "template"  : "#questionColumnActionTemplate"
            },
            {
                "name"      : "created_at",
                "orderable" : true
            },
            {
                "name"      : null,
                "template"  : "#itemfaqsColumnActionTemplate"
            }
        ],
        scope  = this;

        scope.faqListDataTable = __DataStore.dataTable('#lwItemFaqsTable', {
            url     : {
                'apiURL'    : 'manage.product.faq.read.list',
                'productId' : $stateParams.productID
            },
            columnsData     : dtItemFaqColumnsData, 
            dtOptions   : {
                "searching" : true
            },
            scope : $scope
        }, null, function(ratingData) {
            
        });

        /*
         Reload current datatable
        -------------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.faqListDataTable);
        };

        //show Detail Dialog
        scope.showDetailDialog = function(faqId) {
            __DataStore.fetch({
                'apiURL' : 'manage.product.faq.detail.supportData',
                'productId' : $stateParams.productID,
                'faqID'  : faqId
            })
           .success(function(responseData) {
               var requestData = responseData.data;

                appServices.showDialog({
                    'faqDetailData' : requestData
                }, {
                    templateUrl : __globals.getTemplateURL(
                            'product.manage.faq.detail-dialog'
                        )
                }, function(promiseObj) {
                    //Check if option updated
                    if (_.has(promiseObj.value, 'faq_updated') 
                        && promiseObj.value.faq_updated === true) {
                        scope.reloadDT();
                    }
                    //$state.go('specificationsPreset');

                }); 
            });   
             
        };

        /**
          * Add product new Faqs
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.addFaqs   = function() {

            appServices.showDialog({},
                {
                    templateUrl : __globals.getTemplateURL(
                        'product.manage.faq.add-dialog'
                    )
                },
                function(promiseObj) {

                // Check if option added
                if (_.has(promiseObj.value, 'faq_added') 
                    && promiseObj.value.faq_added === true) {
                    scope.reloadDT();
                }

            });

        };

        /**
          * Edit prdouct existing specification
          *
          * @param number specificationID
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.edit   = function(faqID) {
            
            __DataStore.fetch({
                    'apiURL'          : 'manage.product.faq.editData',
                    'productId'       : $stateParams.productID,
                    'faqID'           : faqID,
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                    
                        appServices.showDialog({
                            'faqData' : responseData.data.faqData
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                    'product.manage.faq.edit-dialog'
                                )
                        },
                        function(promiseObj) {

                            // Check if option updated
                            if (_.has(promiseObj.value, 'faq_updated') 
                                && promiseObj.value.faq_updated === true) {
                                scope.reloadDT();
                            }

                        }
                    );

                });    

            });

        };


        /**
          * delete rating request
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function (faqID, title) {

            __globals.showConfirmation({
                html                : __globals.getJSString('delete_faq'),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            },
            function() {

                __DataStore.post({
                    'apiURL'    : 'manage.product.faq.delete',
                    'productId' : $stateParams.productID,
                    'faqID'     : faqID
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                        
                        error : function(data) {
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'error'
                            });

                        }

                    },
                    function(data) {
                            
                        __globals.showConfirmation({
                            title   : __globals.getJSString('confirm_error_title'),
                            text    : message,
                            type    : 'success'
                        });

                        scope.reloadDT();

                    });    

                });

            });
        };

    };

    /**
      * ManageProductFaqsListController handle add product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function FaqDetailController($scope, __DataStore, appServices, $stateParams) {
        var scope   = this;
            scope.ngDialogData   = $scope.ngDialogData;
            scope.faqDetailData     = scope.ngDialogData.faqDetailData.faqDetailData;
       
         /**
         * Close dialog
         *
         * @return void
         *---------------------------------------------------------------- */
            scope.closeDialog = function() {
                $scope.closeThisDialog();
            };
    };

})();;
(function() {
'use strict';
    
    /*
     ProductAwatingUserListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.awatingUser', [])
        .controller('ProductAwatingUserListController',   [
            '$scope',
            '__DataStore',
            'appServices',
            '$stateParams',
            ProductAwatingUserListController 
        ])

    /**
      * ProductAwatingUserListController handle add product form
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject FileUploader
      * @inject __Utils
      * @inject $stateParams
      * @inject appNotify
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductAwatingUserListController($scope, __DataStore, appServices, $stateParams) {

        var scope = this;
        scope.isShowDeleteBtn = false;
        scope.selectedRows = [];

        scope.getAwatingUserList = function() {

            __DataStore.fetch({
                'apiURL'    : 'manage.product.awating_user.read.list',
                'productId' : $stateParams.productID
            }).success(function(responseData) {

                appServices.processResponse(responseData, null, function(reactionCode) {
                   
                    var requestData = responseData.data;
                    scope.awatingUserList = requestData.awatingUserData;
                    scope.productOutOfStock = requestData.productOutOfStock;
                });

            });
        };
        scope.getAwatingUserList();

        scope.checkIsSelected = function() {
                
            scope.anySelected = false;

            _.defer(function() {
                _.forEach(scope.awatingUserList, function(takValue) {

                    if (takValue.isSelected) {
                        scope.anySelected = true;
                    }
                });

                scope.isShowDeleteBtn = scope.anySelected;
            })
          
        }

        scope.sendNotificationMailDialog = function(type, notifyUserId) {
           
            appServices.showDialog({
                'awatingUserList' : scope.awatingUserList,
                'mailType' : type,
                'notifyUserId' : notifyUserId
            },
            {
                templateUrl : __globals.getTemplateURL('product.manage.awating-user.customer-mail-dialog')
            },
            function(promiseObj) {
                        
            });
        }
 
        /*
         Delete selected User
        -----------------------------------------------------------------------*/
        scope.deleteAllAwatingUser = function() {

            var $lwUserDeleteSelectedConfirmTextMsg = $('#lwUserDeleteSelectedConfirmTextMsg');

            __globals.showConfirmation({
                html              : $lwUserDeleteSelectedConfirmTextMsg.attr('data-message'),
                confirmButtonText : $lwUserDeleteSelectedConfirmTextMsg.attr('data-delete-button-text')
            },
            function() {
                
            __DataStore.post({
                'apiURL'    : 'manage.product.awating_user.delete.multipleUser',
                'productId' : $stateParams.productID
            }, scope).success(function(responseData) {
                
                var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                        
                        error : function(data) {
                            __globals.showConfirmation({
                                title   : $lwUserDeleteSelectedConfirmTextMsg.attr('data-success-text'),
                                text    : message,
                                type    : 'error'
                            });

                        }
                    },
                    function(data) {
                            
                        __globals.showConfirmation({
                            title   : $lwUserDeleteSelectedConfirmTextMsg.attr('data-success-text'),
                            text    : message,
                            type    : 'success'
                        });
                        scope.getAwatingUserList();
                        scope.isShowDeleteBtn = false;
                    }); 
               })       
            })
        }

        /**
          * delete blog request
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.deleteAwatingUser = function (notifyUserId) {

            var $lwUserDeleteConfirmTextMsg = $('#lwUserDeleteConfirmTextMsg');

            __globals.showConfirmation({
                html              : __globals.getReplacedString(
                                        $lwUserDeleteConfirmTextMsg),
                confirmButtonText : $lwUserDeleteConfirmTextMsg.attr('data-delete-button-text')
            },
            function() {

                __DataStore.post({
                    'apiURL'       : 'manage.product.awating_user.delete',
                    'productId'    : $stateParams.productID,
                    'notifyUserId' : notifyUserId
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                        
                        error : function(data) {
                            __globals.showConfirmation({
                                title   : $lwUserDeleteConfirmTextMsg.attr('data-success-text'),
                                text    : message,
                                type    : 'error'
                            });

                        }

                    },
                    function(data) {
                            
                        __globals.showConfirmation({
                            title   : $lwUserDeleteConfirmTextMsg.attr('data-success-text'),
                            text    : message,
                            type    : 'success'
                        });
                        scope.getAwatingUserList();
                        scope.isShowDeleteBtn = false;
                    });    

                });

            });
        };

        // when add new record 
        $scope.$on('awating_user_added_or_updated', function(data) {

            if (data) {
                scope.getAwatingUserList();
            }

        });

    };

})();;
(function() {
'use strict';
    
    /*
     ManageOrderDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderDetails', [])

        /**
          * ManageOrderDetailsController - Handle order details view scope
          *
          * @inject __DataStore
          * @inject $stateParams
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('ManageOrderDetailsController', [
            '__DataStore',
            '$stateParams',
            'appServices',
            function (__DataStore, $stateParams, appServices) {

               var scope   = this,
                   orderId = $stateParams.orderId;
               
                scope.discountStatus = false;

                scope.initialContendLoaded = false;

                __DataStore.fetch({
                    'apiURL'    : 'manage.order.details.dialog',
                    'orderID'   :  orderId
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                        
                        var orderDetails = responseData.data.orderDetails.data;

                        scope.billingAddress    = orderDetails.address.billingAddress;
                        scope.shippingAddress   = orderDetails.address.shippingAddress;
                        scope.sameAddress       = orderDetails.address.sameAddress;

                        scope.user              = orderDetails.user;
                        scope.order             = orderDetails.order;
                        scope.orderProducts     = orderDetails.orderProducts;
                        scope.coupon            = orderDetails.coupon;
                        scope.taxes             = orderDetails.taxes;
                        scope.shipping          = orderDetails.shipping;

                    });

                    scope.initialContendLoaded = true;

                });

                /**
                  * Contact user dialog
                  * 
                  * @return void
                  *---------------------------------------------------------------- */

                scope.contactUserDialog = function () {

                    __DataStore.fetch({
                        'apiURL' : 'manage.order.get.user.details',
                        'orderID': orderId
                    })
                    .success(function(responseData) {

                        scope.userData = responseData.data;
                        
                        appServices.processResponse(responseData, null, function () {

                            appServices.showDialog(responseData.data,
                            {
                                templateUrl : __globals.getTemplateURL(
                                    'order.manage.contact-user'
                                )
                            },
                            function(promiseObj) {

                            });
                        });
                    });
                };

            }

        ]);

})();;
(function() {
'use strict';
    
    /*
      ManagePagesApp Module
      -------------------------------------------------------------------------- */
    
    angular
        .module('ManagePagesApp.list', [])
        .controller('ManagePagesListController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$rootScope',
            '$state',
            ManagePagesListController 
        ]);

    /**
      * ManagePagesListController - show all pages list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $state
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManagePagesListController($scope, __DataStore, appServices, $rootScope, $state) {


        var dtPageColumnsData = [
            {
                "name"      : "title",
                "orderable" : true,
                "template"  : "#pagesColumnTitleTemplate"
            },
            {
                "name"      : "list_order",
                "orderable" : true,
                "template"  : "#pagesColumnListOrderTemplate"
            },
            {
                "name"      : "type",
                "orderable" : true,
                "template"  : "#pagesColumnTypeTemplate"
            },
            {
                "name"      : "created_at",
                "orderable" : true,
                "template"  : "#pagesColumnTimeTemplate"
            },
            {
                "name"      : "updated_at",
                "orderable" : true,
                "template"  : "#pagesColumnUpdatedTimeTemplate"
            },
            {
                "name"      : "add_to_menu",
                "template"  : "#pagesColumnAddToMenuTemplate"
            },
            {
                "name"      : "status",
                "orderable" : true,
                "template"  : "#pagesColumnActiveTemplate"
            },
            {
                "name"      : null,
                "template"  : "#pagesColumnActionTemplate"
            }
        ],

        scope               = this;
        scope.parentPageID  = $state.params.parentPageID;
        scope.parent        = false;

        var reorderOptions = {
            "searching" : true,
            "order"     : [[1, 'asc']],
            "paginate"  :false,
        };

        if ($rootScope.canAccess('manage.page.update.list.order')) {
            reorderOptions = {
                "searching" : true,
                "order"     : [[1, 'asc']],
                "paginate"  :false,
                'rowReorder'  : true,
                'rowReorder'  : { 'selector': "td:nth-child(2)" }
            };
        }


        scope.pagesListDataTable = __DataStore.dataTable('#lwPagesTable', {
            url             : {
              'apiURL'          : 'manage.pages.fetch.datatable.source',
              'parentPageID?'   :  (scope.parentPageID) 
                                    ? scope.parentPageID 
                                    : null
            },
            columnsData     : dtPageColumnsData, 
            dtOptions   : reorderOptions,
            scope : $scope
        });

        //Set order list in pages start
        $('#lwPagesTable').on('row-reorder.dt', function (e, data, edit) {
            var pageListOrderData = [];
            
            _.forEach(data, function(item, key) {
                
                pageListOrderData.push({
                    _id          : _.trim(item.node.id.replace('rowid_', '')),
                    newPosition  : item.newPosition,
                    oldPosition  : item.oldPosition
                })
              
            }); 

            //set order list of Pages start
            __DataStore.post('manage.page.update.list.order',{ 
                'pages_list_order' : pageListOrderData
            }).success(function(responseData) {
                    
                appServices.processResponse(
                    responseData,
                    {
                        error : function() {
                            scope.reloadDT();   // reload datatable
                        }
                    },
                    function() {
                        scope.reloadDT();
                    }
                ); 

            });
        });
        //Set order list in pages end
        
        // Check if parent page id exist
		if (scope.parentPageID) {

            __DataStore.fetch({
                'apiURL'    	: 'manage.pages.get.parent.page',
                'parentPageID'  : scope.parentPageID
              })
                .success(function(responseData) {

                    scope.parentPage    = responseData.data;
                
                    scope.parent        = true;
                    scope.parentPageID  = scope.parentPageID;

                });
        }

        /*
         Reload current datatable
        -------------------------------------------------------------------------- */
        
        scope.reloadDT = function() {
            __DataStore.reloadDT(scope.pagesListDataTable);
        };

        /**
          * Delete page 
          *
          * @param number pageID
          * @param string pageName
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.delete = function(pageID, pageName) {

            __globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('page_delete_confirm_text'), {
                        '__name__'     : unescape(pageName)
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL' : 'manage.pages.delete',
                    'pageID' : pageID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                            error : function(data) {
                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function(data) {

                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });

        };

    };

})();;
(function() {
'use strict';
    
    /*
      ManagePagesAddDialogController Module
      -------------------------------------------------------------------------- */
    
    angular
        .module('ManagePagesApp.add.dialog', [])
        .controller('ManagePagesAddDialogController',   [
        	'$scope',
        	'$state',
            'appServices',
            '__DataStore',
            ManagePagesAddDialogController 
        ]);

    /**
      * ManagePagesAddDialogController - for show add dialog form
      *
      * @inject $scope
      * @inject $state
      * @inject appServices
      * @inject __DataStore
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManagePagesAddDialogController($scope, $state, appServices, __DataStore) {

        var scope   = this;
  
        __DataStore.fetch('manage.pages.get.page_type')
            .success(function(responseData) {

            	scope.page = responseData.data;

            	appServices.showDialog(scope,
                {	
                    templateUrl : __globals.getTemplateURL('pages.manage.add')
                },
                function(promiseObj) {
                	
                    // Check if page added
                    if (_.has(promiseObj.value, 'page_added') 
                        && promiseObj.value.page_added === true) {
                    	$scope.$parent.managePagesListCtrl.reloadDT();
                    }

                    $state.go('pages');

                });
     	});

    };

})();;
(function() {
'use strict';
    
    /*
      ManagePagesAddController Module
      -------------------------------------------------------------------------- */
    
    angular
        .module('ManagePagesApp.add', [])
        .controller('ManagePagesAddController',   [
        	'$scope',
            '__Form', 
            '$state',
            'appServices',
            ManagePagesAddController 
        ]);

    /**
      * ManagePagesAddController - handle add page dialog scope
      * 
      * @inject $scope
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManagePagesAddController($scope,__Form, $state, appServices) {

        var scope   = this;

        scope = __Form.setup(scope, 'manage_pages_add', 'pageData');

        scope.pagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : ['text']  
        });
        
        // Check if ngDialogData exist
        if ($scope.ngDialogData) {
        	
        	var page = $scope.ngDialogData.page;

        	scope.pageType  	= page.type;
        	scope.pageLink  	= page.link;
        	scope.pages  		= page.fancytree_data;

        }

        scope.pageData.add_to_menu  = true;
        scope.pageData.hide_sidebar = false;
      	scope.pageData.active       = true;
      	scope.pageData.type         = '1';
      	scope.pageData.open_as      = '_blank';
      	scope.descriptionRequired   = true;
      	scope.externalLinkRequired  = false;
      	scope.openAsRequired        = false;
        
        scope.pageTypeChanged = function() {
	    	
	        var pageType = scope.pageData.type;

	        if (pageType == 1) {
	          // page.
	          scope.descriptionRequired  = true;
	          scope.externalLinkRequired = false;
	          scope.openAsRequired       = false;

	        } else if (pageType == 2) {
	          // link.
	          scope.descriptionRequired  = false;
	          scope.externalLinkRequired = true;
	          scope.openAsRequired       = true;

          }

	    };

        /**
          * Submit add page form
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {
        	 
            __Form.process('manage.pages.add', scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { page_added : true } );
                });    

            });

        };

        /**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
	  	 
        scope.close = function() {
  	  		$scope.closeThisDialog();
  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
      ManagePagesEditDialogController Module
      -------------------------------------------------------------------------- */
    
    angular
        .module('ManagePagesApp.edit.dialog', [])
        .controller('ManagePagesEditDialogController',   [
        	'$scope',
        	'$state',
            'appServices',
            '__DataStore',
            ManagePagesEditDialogController 
        ]);

    /**
      * ManagePagesEditDialogController - show edit page dialog
      *
      * @inject $scope
      * @inject $state
      * @inject appServices
      * @inject __DataStore
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManagePagesEditDialogController($scope, $state, appServices, __DataStore) {

        var scope   = this;

        __DataStore.fetch({
	        	'apiURL'  : 'manage.pages.get.details',
	        	'pageID'  : $state.params.pageID
	        })
        	.success(function(responseData) {

			appServices.processResponse(responseData,null, function(reactionCode) {
				
				appServices.showDialog(responseData.data,
                {
                    templateUrl : __globals.getTemplateURL('pages.manage.edit')
                },
                function(promiseObj) {

                    // Check if page updated
                    if (_.has(promiseObj.value, 'page_updated') 
                        && promiseObj.value.page_updated === true) {
                    	
                    	$scope.$parent.managePagesListCtrl.reloadDT();
                    }
                    
                    $state.go('pages');
                    
                });
			});
            	
     	});

    };

})();;
(function() {
'use strict';
	
	/*
	  ManagePagesEditController Module
	  -------------------------------------------------------------------------- */
	
	angular
        .module('ManagePagesApp.edit', [])
        .controller('ManagePagesEditController', 	[
            '$scope', 
            '__Form', 
            'appServices',
            '$state',
            ManagePagesEditController 
	 	]);

	/**
	  * ManagePagesEditController - handle scope of edit page dialog
	  *
	  * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $state
	  * 
	  * @return void
	  *-------------------------------------------------------- */

	function ManagePagesEditController($scope, __Form, appServices, $state) {

	 	var scope = this;

		scope.updateURL = {
			'apiURL' :'manage.pages.update',
			'pageID' : $state.params.pageID
		};

		scope 	= __Form.setup(scope, 'form_page_edit', 'pageData');
		
		scope   = __Form.updateModel(scope, $scope.ngDialogData.pageDetails);
		
		scope.pagesSelectConfig     = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : ['text']  
        });

        scope.ngDialogData = $scope.ngDialogData;

        scope.type = scope.ngDialogData.pageDetails.type;

        if (scope.type != 3) {

            scope.pageType 	= scope.ngDialogData.configItems.pageType;
            scope.pageLink 	= scope.ngDialogData.configItems.pageLinks;
           
    		scope.getPageType = function() {

    	    	if (scope.ngDialogData) {

            		scope.pages  = scope.ngDialogData.pageDetails.fancytree_data;
    	        	var pageType = scope.ngDialogData.pageDetails.type;

    		        if ( pageType == 1 ) {
    		            // Internal page.
    		            scope.descriptionRequired  = true;
    		            scope.externalLinkRequired = false;
    		            scope.openAsRequired       = false;

    		        } else if ( pageType == 2) {
    		            // External link.
    		            scope.descriptionRequired  = false;
    		            scope.externalLinkRequired = true;
    		            scope.openAsRequired       = true; 

    		        } 
    	        } 
    	    };

            scope.getPageType();

        }
	    

		/*
	 	 Submit edit form action
	 	-------------------------------------------------------------------------- */
	 	
	 	scope.update = function() {

	 		// post form data
	 		__Form.process(scope.updateURL, scope )
	 						.success( function( responseData ) {
		      		
				appServices.processResponse(responseData, null, function(reactionCode) {

	                $scope.closeThisDialog({ page_updated : true });

	            });

		    });

	  	};

	  	/**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

  	  	scope.cancel = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

})();;
(function() {
'use strict';
    
    /*
     PageDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManagePagesApp.page.details', [])
        .controller('PageDetailsController',   [
        	'$state',
            'appServices',
            '__DataStore',
            PageDetailsController 
        ]);

    /**
      * PageDetailsController handle add pages form
      * @inject $scope
      * @inject $state
      * @inject appServices
      * @inject __DataStore
      * 
      * @return void
      *-------------------------------------------------------- */

    function PageDetailsController($state, appServices, __DataStore) {
    	
        var scope   = this;
        		
      	/**
      	* get pages info
      	* 
      	* @return void
      	*-------------------------------------------------------- */

        scope.getPageInfo = function() {

        	__DataStore.fetch({	
        			'apiURL' :'manage.display.page.details',
        			'pageID' : $state.params.pageID
        		}, scope)
            	.success(function(responseData) {

            	appServices.processResponse(responseData, null, function() {

            	 	scope.pageDetails = responseData.data;
            	});    

     		});
        };
        scope.getPageInfo();
        
    };

})();;
(function() {
'use strict';
    
    /*
     EditStoreSettingsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('manageApp.storeSettingsEdit', [])
        .controller('EditStoreSettingsController',   [
        	'$state',
            '$scope',
            EditStoreSettingsController 
        ]);

    /**
      * EditStoreSettingsController for update store settings
      *
      * @inject __Form
      * @inject appServices
      * @inject $state
      * @inject FileUploader
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */

    function EditStoreSettingsController($state, $scope) {

    	var scope   = this;

        $scope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            
            _.defer(function(){
                var stateName = toState.name;
            
                // if current route is general then open general panel
                if (stateName == 'store_settings_edit.general') {

                    scope.generalPanelClass = 'panel-collapse collapse in';
                    scope.generalPanelStatus = true;

                } else {

                    scope.generalPanelClass = 'panel-collapse collapse';
                }

                // if current route is currency then open general panel
                if (stateName == 'store_settings_edit.currency') {

                    scope.currencylPanelClass = 'panel-collapse collapse in';
                    scope.currencyPanelStatus = true;

                } else {

                    scope.currencylPanelClass = 'panel-collapse collapse';
                }

                // if current route is order then open general panel
                if (stateName == 'store_settings_edit.order') {

                    scope.orderPanelClass = 'panel-collapse collapse in';
                    scope.orderPanelStatus = true;

                } else {

                    scope.orderPanelClass = 'panel-collapse collapse';
                } 

                // if current route is products then open general panel
                if (stateName == 'store_settings_edit.product') {

                    scope.productsPanelClass = 'panel-collapse collapse in';
                    scope.productPanelStatus = true;

                } else {

                    scope.productsPanelClass = 'panel-collapse collapse';
                }

                // if current route is placement then open general panel
                if (stateName == 'store_settings_edit.placement') {

                    scope.placementPanelClass = 'panel-collapse collapse in';
                    scope.placementPanelStatus = true;

                } else {

                    scope.placementPanelClass = 'panel-collapse collapse';
                }

                // if current route is contact then open general panel
                if (stateName == 'store_settings_edit.contact') {

                    scope.contactPanelClass = 'panel-collapse collapse in';
                    scope.contactPanelStatus = true;

                } else {

                    scope.contactPanelClass = 'panel-collapse collapse';
                }

                // if current route is terms and condition then open general panel
                if (stateName == 'store_settings_edit.term_condition') {

                    scope.termsConditionsPanelClass = 'panel-collapse collapse in';
                    scope.termsConditionPanelStatus = true;

                } else {

                    scope.termsConditionsPanelClass = 'panel-collapse collapse'; 
                } 

                // if current route is privacy policy then open general panel
                if (stateName == 'store_settings_edit.privacy_policy') {

                    scope.privacyPolicyPanelClass = 'panel-collapse collapse in';
                    scope.privacyPolicyPanelStatus = true;

                } else {

                    scope.privacyPolicyPanelClass = 'panel-collapse collapse';
                }

                // if current route is social then open general panel
                if (stateName == 'store_settings_edit.social')  {

                    scope.socialPanelClass = 'panel-collapse collapse in';
                    scope.socialPanelStatus = true;

                } else {

                    scope.socialPanelClass = 'panel-collapse collapse';
                }

				// if current route is social then open general panel
                if (stateName == 'store_settings_edit.social_authentication_setup')  {

                    scope.socialLoginPanelClass = 'panel-collapse collapse in';
                    scope.socialLoginPanelStatus = true;

                } else {

                    scope.socialLoginPanelClass = 'panel-collapse collapse';
                }

                // if current route is social then open language panel
                if (stateName == 'store_settings_edit.language')  {

                    scope.languagePanelClass = 'panel-collapse collapse in';
                    scope.languagePanelStatus = true;

                } else {

                    scope.languagePanelClass = 'panel-collapse collapse';
                }

                // if current route is css style then open item tab
                if (stateName == 'store_settings_edit.css-style') {

                    scope.cssStylePanelClass    = 'panel-collapse collapse in';
                    scope.cssStylePanelStatus   = true;

                } else {

                    scope.cssStylePanelClass  = 'panel-collapse collapse';
                }

            });

        });

    	scope.getPage = function (pageType) {

    		if (pageType) {

                _.defer(function(){

    			switch(pageType) {

    				case 1: // general

    					$state.go('store_settings_edit.general');
    					scope.generalPanelStatus = true;

    					break;

    				case 2: // currency

    					$state.go('store_settings_edit.currency');
    					scope.currencyPanelStatus = true;

    					break;

    				case 3: // order

    					$state.go('store_settings_edit.order');
    					scope.orderPanelStatus = true;

    					break;

    				case 4: // products

    					$state.go('store_settings_edit.product');
    					scope.productPanelStatus = true;

    					break;

    				case 5: // Placement

    					$state.go('store_settings_edit.placement');
    					scope.placementPanelStatus = true;

    					break;

    				case 6: // Contact

    					$state.go('store_settings_edit.contact');
    					scope.contactPanelStatus = true;

    					break;

    				case 7: // Terms and Conditions

    					$state.go('store_settings_edit.term_condition');
    					scope.termsConditionPanelStatus = true;

    					break;

    				case 8: // Privacy Policy

    					$state.go('store_settings_edit.privacy_policy');
    					scope.privacyPolicyPanelStatus = true;

    					break;

    				case 9: // Social

    					$state.go('store_settings_edit.social');
    					scope.socialPanelStatus = true;

    					break;

                    case 10: // css style

                        $state.go('store_settings_edit.css-style');
                        scope.cssStylePanelStatus = true;

                        break;

					case 11: // Social Authentication

                        $state.go('store_settings_edit.social_authentication_setup');
                        scope.socialLoginPanelStatus = true;

                        break;

    			}

            });
    		}
    	}

    	// if current route is general then open general panel
        if ($state.current.name == 'store_settings_edit.general') {

        	scope.generalPanelClass = 'panel-collapse collapse in';
        	scope.generalPanelStatus = true;

        } else {

        	scope.generalPanelClass = 'panel-collapse collapse';
        }

        // if current route is currency then open general panel
        if ($state.current.name == 'store_settings_edit.currency') {

        	scope.currencylPanelClass = 'panel-collapse collapse in';
        	scope.currencyPanelStatus = true;

        } else {

        	scope.currencylPanelClass = 'panel-collapse collapse';
        }

        // if current route is order then open general panel
        if ($state.current.name == 'store_settings_edit.order') {

        	scope.orderPanelClass = 'panel-collapse collapse in';
        	scope.orderPanelStatus = true;

        } else {

        	scope.orderPanelClass = 'panel-collapse collapse';
        } 

        // if current route is products then open general panel
        if ($state.current.name == 'store_settings_edit.product') {

        	scope.productsPanelClass = 'panel-collapse collapse in';
        	scope.productPanelStatus = true;

        } else {

        	scope.productsPanelClass = 'panel-collapse collapse';
        }

        // if current route is placement then open general panel
        if ($state.current.name == 'store_settings_edit.placement') {

        	scope.placementPanelClass = 'panel-collapse collapse in';
        	scope.placementPanelStatus = true;

        } else {

        	scope.placementPanelClass = 'panel-collapse collapse';
        }

        // if current route is contact then open general panel
        if ($state.current.name == 'store_settings_edit.contact') {

        	scope.contactPanelClass = 'panel-collapse collapse in';
        	scope.contactPanelStatus = true;

        } else {

        	scope.contactPanelClass = 'panel-collapse collapse';
        }

        // if current route is terms and condition then open general panel
        if ($state.current.name == 'store_settings_edit.term_condition') {

        	scope.termsConditionsPanelClass = 'panel-collapse collapse in';
        	scope.termsConditionPanelStatus = true;

        } else {

        	scope.termsConditionsPanelClass = 'panel-collapse collapse'; 
        } 

        // if current route is privacy policy then open general panel
        if ($state.current.name == 'store_settings_edit.privacy_policy') {

        	scope.privacyPolicyPanelClass = 'panel-collapse collapse in';
        	scope.privacyPolicyPanelStatus = true;

        } else {

        	scope.privacyPolicyPanelClass = 'panel-collapse collapse';
        }

        // if current route is social then open general panel
        if ($state.current.name == 'store_settings_edit.social')  {

        	scope.socialPanelClass = 'panel-collapse collapse in';
        	scope.socialPanelStatus = true;

        } else {

        	scope.socialPanelClass = 'panel-collapse collapse';
        }

		 if ($state.current.name  == 'store_settings_edit.social_authentication_setup')  {

            scope.socialLoginPanelClass = 'panel-collapse collapse in';
            scope.socialLoginPanelStatus = true;

        } else {

            scope.socialLoginPanelClass = 'panel-collapse collapse';
        }

        // if current route is social then open language panel
        if ($state.current.name == 'store_settings_edit.language')  {

        	scope.languagePanelClass = 'panel-collapse collapse in';
        	scope.languagePanelStatus = true;

        } else {

        	scope.languagePanelClass = 'panel-collapse collapse';
        }

        // if current route is css-style then open css-style
        if ($state.current.name == 'store_settings_edit.css-style') {

            scope.cssStylePanelClass    = 'panel-collapse collapse in';
            scope.cssStylePanelStatus = true;

        } else {

            scope.cssStylePanelClass  = 'panel-collapse collapse';
        }
        
       
            

    };

})();;
(function() {
'use strict';
	
	/*
	  general setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.GeneralSettings', 		[])

        /**
         * GeneralController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
		 *
         * @return void
         *-------------------------------------------------------- */
        .controller('GeneralController', [
            '$scope',
            '$state',
            'appServices',
         function GeneralController($scope, $state, appServices) {

            var scope  = this;
 
			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.general')
			}, function(promiseObj) {

				$state.go('store_settings_edit');

			});
        }
        ])

         /**
	      * GeneralSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('GeneralSettingsController', [
			'__Form', 
            '$scope',
            'appServices',
            '$state',
            'FileUploader',
            '__Utils',
            'appNotify',
            '$rootScope',
            function (__Form, $scope, appServices, $state,
     			FileUploader, __Utils, appNotify, $rootScope) {

                var scope   = this;
 				scope.pageStatus = false;
                scope.icoFiles = [];
		        scope = __Form.setup(scope, 'form_general_settings', 'editData', {
		        	secured : true,
		        	modelUpdateWatcher:false,
                    unsecuredFields : ['store_name']
		        });

		        scope.logo_images_select_config = __globals.getSelectizeOptions({
		            valueField  : 'name',
		            labelField  : 'name',
		            render      : {
		                item: function(item, escape) {
		                    return  __Utils.template('#imageListItemTemplate',
		                    item
		                    );
		                },
		                option: function(item, escape) {
		                    return  __Utils.template('#imageListOptionTemplate',
		                    item
		                    );
		                }
		            }, 
		            searchField : ['name']
		        });

		        scope.timezone_select_config = __globals.getSelectizeOptions({
		            valueField  : 'value',
		            labelField  : 'text',
		            searchField : [ 'text' ]  
		        });
		        			      
				var prev_bg_color  = '';

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 1
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
	            	prev_bg_color = requestData.store_settings.logo_background_color;
                    scope.themeColors = requestData.store_settings.theme_colors;
		            scope.homePageList    = requestData.store_settings.home_page_setting;
		            scope.timezoneData 	  = requestData.store_settings.timezone_list;
                    scope.languages       = requestData.store_settings.locale_list;

		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                	
		                    __Form.updateModel(scope, requestData.store_settings);
		                    scope.editData.logo_background_color = prev_bg_color;
		                }
		                
  						scope.pageStatus = true;
                        
		            });    

		        });

                scope.selectThemeColor = function(colorCode) {
                    scope.editData.logo_background_color = colorCode;
                    $('#lwchangeHeaderColor').css('background', "#"+colorCode);
                }

                scope.imageChange = function(type) {
                    if (scope.editData.invoice_image == scope.editData.logo_image) {
                        if (type == 1) {
                            scope.editData.logo_image = '';
                        } else if (type == 2) {
                            scope.editData.invoice_image = '';
                        } 
                    }
                };

		        /**
		          * Fetch uploaded temp images media files
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.images_count = 0;
                scope.faviconFilesCount = 0;
		        scope.getTempImagesMedia = function() {

		            __Form.fetch('media.uploaded.images', {fresh : true})
		                .success(function(responseData) {
		                    
		                appServices.processResponse(responseData, null, function() {
		                  
		                    scope.logoImages = responseData.data.files;

		                    var selectizeImages = [],
                            icoSelectizeImages = [];

		                    _.forEach(scope.logoImages, function(value, index) {
		                    	
		                    	scope.imagesExtention = value.name.split(".").pop();
				            	
				            	if (scope.imagesExtention == 'png') {

				            		selectizeImages.push(value);
				            	} else if (scope.imagesExtention == 'ico') {

                                    icoSelectizeImages.push(value);
                                }
				            });

				            scope.logo_images = selectizeImages;
                            scope.icoFiles = icoSelectizeImages;
  
		                    if (scope.logo_images.length > 0 || scope.icoFiles.length > 0) {
		                    	scope.images_count = scope.logo_images.length;
                                scope.faviconFilesCount = scope.icoFiles.length;
							} else {
								scope.images_count = 0;
                                scope.faviconFilesCount = 0;
							};
                         
		                });    
		                
		            });

		        };

		        scope.getTempImagesMedia();

                $rootScope.$on('lw-upload-image-deleted', function(event, data) {
                    if (data) {
                        scope.getTempImagesMedia();
                    }
                });
		        
		        var uploader = scope.uploader = new FileUploader({
		            url         : __Utils.apiURL('media.upload.image'),
		            autoUpload  : true,
		            headers     : {
		                'X-XSRF-TOKEN': __Utils.getXSRFToken()
		            }
		        });

		        // FILTERS
		        uploader.filters.push({
		            name: 'customFilter',
		            fn: function(item /*{File|FileLikeObject}*/, options) {

		            	if (item.type == 'image/png' || item.type == 'image/x-icon') {
		                	return this.queue.length < 1000;
		            	} 
		            }
		        });


		        scope.currentUploadedFileCount = 0;
		        scope.loadingStatus     	   = false;

				/**
                * uploading msg
                *
                * @return void
                *---------------------------------------------------------------- */
                uploader.onAfterAddingAll = function() {

                    scope.loadingStatus = true;
                    appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

                };

                /**
                * Uploading on process
                *
                * @return void
                *---------------------------------------------------------------- */

                uploader.onBeforeUploadItem = function(item) {
                    scope.loadingStatus = true;
                };

		        /**
		        * on success counter of uploaded image
		        *
		        * @param object fileItem
		        * @param object response
		        *
		        * @return void
		        *---------------------------------------------------------------- */
		        
		        uploader.onSuccessItem = function( fileItem, response ) {

					appServices.processResponse(response, null, function() {
		            	scope.currentUploadedFileCount++
		            }); 

		        };

		        /**
                * on success counter of uploaded image
                *
                * @param object fileItem
                * @param object response
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onSuccessItem = function( fileItem, response ) {

                    appServices.processResponse(response, null, function() {

                        if (fileItem._file.name.split('.').pop() === 'png' || fileItem._file.name.split('.').pop() === 'ico') {
                            scope.currentUploadedFileCount++
                        }
                        
                    }); 

                };

                /**
                * uploaded all image then call function
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onCompleteAll  = function() {

                    scope.loadingStatus  = false;
                    
                    if (scope.currentUploadedFileCount === 0) {
                        appNotify.warn(__globals.getJSString('logo_empty_file_uploaded_text'),{sticky : false});
                    }

                    if (scope.currentUploadedFileCount > 0) {
                        appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'),{sticky : false});
                    }

                    scope.getTempImagesMedia();
                    scope.currentUploadedFileCount = 0;
                };
		        
		        /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :1
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
		                    
                            if (responseData.data.showRealodButton == true) {
    		                  	__globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }
		                });    

		            });

		        };

		        /**
		          * Show uploaded media files
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.showUploadedMediaDialog = function() {
		            appServices.showDialog({ 'image_files' : _.merge(scope.logo_images, scope.icoFiles) }, {
		                templateUrl : __globals.getTemplateURL('product.manage.uploaded-media')
		            }, function(promiseObj) {

		            	if (_.has(promiseObj.value, 'files')) {
		                    scope.logo_images = promiseObj.value.files;
		                    scope.images_count = promiseObj.value.files.length;

		                } else if(promiseObj.value == '$closeButton') {

		                    scope.getTempImagesMedia();
		                }

		            });
		        };

	        /**
              * Close dialog
              *
              * @return void
              *---------------------------------------------------------------- */

            scope.closeDialog = function() {

            	if (scope.editData.logo_background_color != prev_bg_color) {
            		$('#lwchangeHeaderColor').css('background', "#"+prev_bg_color);
            	}

                $scope.closeThisDialog();
            };
		}]);

})();;
(function() {
'use strict';
	
	/*
	  currency & setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.CurrencySettings', 		[])

        /**
         * CurrencyController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
		 *
         * @return void
         *-------------------------------------------------------- */
        .controller('CurrencyController', [
            '$scope',
            '$state',
            'appServices',
         function CurrencyController($scope, $state, appServices) {

            var scope  = this;
 
			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.currency')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
        }
        ])

         /**
	      * CurrencySettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('CurrencySettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form, appServices, $scope) {

                var scope = this;

            scope.isZeroDecimalCurrency = false;
            scope.currencyExist = false;
            
            //manage currency tab on click
            scope.manageCurrecnyTab = function(event) {
                event.preventDefault();
            };
            
            /**
              * Generate key value
              * 
              * @param bool responseKeyValue
              * 
              * @return void
              *---------------------------------------------------------------- */
            
            scope.generateCurrenciesArray = function(currencies, responseKeyValue) {
                
                if (!responseKeyValue) {
                    return currencies;
                }

                var currenciesArray = [];

                _.forEach(currencies, function(value, key) {
                    
                    currenciesArray.push({
                        'currency_code'     : key,
                        'currency_name'     : value.name   
                    });

                });

                currenciesArray.push({
                    'currency_code'  : 'other',
                    'currency_name'  :  __globals.getJSString('other')
                });

                return currenciesArray;
             
            };

            /**
              *  Check the the currency match with zero decimal
              *
              * @param array zeroDecimalCurrecies
              * @param string selectedCurrencyValue 
              * 
              * @return void
              *---------------------------------------------------------------- */
            
            scope.checkIsZeroDecimalCurrency = function(zeroDecimalCurrecies, selectedCurrencyValue) {
      
                var isMatch = _.filter(zeroDecimalCurrecies, function(value, key) {
                    
                        return  (key === selectedCurrencyValue);
                    });

                scope.isZeroDecimalCurrency = Boolean(isMatch.length);
                
            };

            /**
              * Check if current currency is Paypal supported or not
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.checkIsPaypalSupported = function (currencyValue) {

                var isPaypalSupported = _.filter(scope.options, function(value, key) {
                    
                    return  (key == currencyValue);
                });

                scope.isPaypalSupport = Boolean(isPaypalSupported.length);
            };

            /**
              * format currency symbol and currency value
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.formatCurrency = function (currencySymbol, currency) {

                _.defer(function() {

                    var $lwCurrencyFormat = $('#lwCurrencyFormat');

                    var string = $lwCurrencyFormat.attr('data-format');

                    scope.currency_format_preview  =  string.split('{__currencySymbol__}').join(currencySymbol)
                                                            .split('{__amount__}').join(100)
                                                            .split('{__currencyCode__}').join(currency);
                });
            };

            scope.pageStatus = false;

            scope  = __Form.setup(scope, 'edit_currency_configuration', 'editData', {
                secured : true,
                unsecuredFields : [
                    'currency_symbol'
                ]
            });

            scope.currencies_select_config = __globals.getSelectizeOptions({
                valueField  : 'currency_code',
                labelField  : 'currency_name',
                searchField : [ 'currency_code', 'currency_name' ]  
            });

            scope.multi_currencies_select_config = __globals.getSelectizeOptions({
                valueField  : 'currency_code',
                labelField  : 'currency_name',
                searchField : [ 'currency_code', 'currency_name' ],
                plugins     : ['remove_button'],
                maxItems    : 1000,
                delimiter   : ','
            });

            scope.is_support_paypal = true;


            __Form.fetch({
                    'apiURL'   :'store.settings.edit.supportdata',
                    'formType' : 2
                }).success(function(responseData) {
                
                appServices.processResponse(responseData, null, function() {

                        var requestData     = responseData.data,
                            currenciesData  = requestData.store_settings.currencies;

                        scope.options     = currenciesData.options;
                        scope.currencies  = currenciesData.details;
                        scope.zeroDecimal = currenciesData.zero_decimal;
                        scope.autoRefreshList  = requestData.store_settings.autoRefreshList;
                        scope.paymentOptionList = requestData.paymentOptionList;

                        _.defer(function() {
                            scope.currencies_options  
                                = scope.generateCurrenciesArray(currenciesData.details, true);

                            scope.multi_curency_list = scope.prepareMultiCurrencies(scope.currencies_options);

                            scope.currentSelectCurrency(scope.editData.currency, scope.editData.paymentOptionList, scope.currencies_options);
                        });
                                
                        if (!_.isEmpty(requestData.store_settings)) {

                            scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, requestData.store_settings.currency_value);

                            scope.checkIsPaypalSupported(requestData.store_settings.currency);

                            scope.default_currency_format = requestData.store_settings.default_currency_format;

                            scope = __Form.updateModel(scope, requestData.store_settings);

                            _.forEach(scope.currencies, function(currencyObj, key) {

                                if (key == scope.editData.currency_value) {
                                    scope.currencySymbol = currencyObj.symbol;
                                }
                            });

                            if (requestData.store_settings.currency == 'other') {
                                scope.currencySymbol = requestData.store_settings.currency_symbol;
                            }
                            
                            scope.formatCurrency(scope.currencySymbol, scope.editData.currency_value);

                        }

                        scope.pageStatus = true;

                        /*_.defer(function() {
                            scope.currencies_options  
                                = scope.generateCurrenciesArray(currenciesData.details, true);
                        });*/                    
                });    

            });

            /*
             selected Currency For show alert message
            -------------------------------------------------------------------------- */        
            scope.currentSelectCurrency = function(selectCurrency, paymentOption, currenyValues) {
                scope.currencyExist = false;   
                scope.currenyLabel = '';

                _.map(currenyValues, function(value, key) { 
                    if (selectCurrency == value.currency_code) {
                        scope.currenyLabel = value.currency_name;
                    }
                });

                if (_.includes(paymentOption, '16') || _.includes(paymentOption, '18') || _.includes(paymentOption, '11')) {
                    if (selectCurrency != 'INR') {
                        scope.currencyExist = true;
                    }
                }

                if(_.includes(paymentOption, '1') || _.includes(paymentOption, '14') || _.includes(paymentOption, '20')) {
                    if (selectCurrency == 'INR') {
                        scope.currencyExist = true;
                    }
                }  

                if(selectCurrency != 'TRY' && _.includes(paymentOption, '14')) {
                    scope.currencyExist = true;
                }

                if (selectCurrency != 'NGN' && _.includes(paymentOption, '20')) {
                    scope.currencyExist = true;
                }

            }

            /*
             information
            -------------------------------------------------------------------------- */
            scope.prepareMultiCurrencies = function(currenciesOptions) {
                var multiCurrencies = [];// _.dropRight(currenciesOptions);
                
                _.forEach(currenciesOptions, function(item) {
                    if (item.currency_code != 'TWD' && item.currency_code != 'other') {
                        multiCurrencies.push(item);
                    }
                });

                return multiCurrencies;
            }

            
            /**
              * Use default format for currency
              *
              * @param string defaultCurrencyFormat
              * 
              * @return string
              *---------------------------------------------------------------- */
            scope.useDefaultFormat = function(defaultCurrencyFormat, currency_symbol, currency_value) {

                scope.editData.currency_format = defaultCurrencyFormat;

                var lwSymbol = $('#lwSymbol'),
                    currency_symbol = lwSymbol.html(lwSymbol.attr('data')).text();
                    
                var string = scope.editData.currency_format;
                
                scope.currency_format_preview  =  string.split('{__currencySymbol__}').join(currency_symbol)
                                                    .split('{__amount__}').join(100)
                                                    .split('{__currencyCode__}').join(currency_value);
            };


            /**
              * Use default format for currency
              *
              * @param string defaultCurrencyFormat
              * 
              * @return string
              *---------------------------------------------------------------- */
            scope.updateCurrencyPreview = function(currency_symbol, currency_value, isSymbol) {
                
                if (isSymbol == true) {
                    var lwSymbol = $('#lwSymbol'),
                    currency_symbol = lwSymbol.html(lwSymbol.attr('data')).text();
                }

                if (_.isUndefined(currency_symbol)) {
                    currency_symbol = '';
                }

                if (_.isUndefined(currency_value)) {
                    currency_value = '';
                }

                var $lwCurrencyFormat = $('#lwCurrencyFormat');

                var string = $lwCurrencyFormat.attr('data-format');

                scope.currency_format_preview  =  string.split('{__currencySymbol__}').join(currency_symbol)
                                                        .split('{__amount__}').join(100)
                                                        .split('{__currencyCode__}').join(currency_value);
                
            };

            /**
              * Submit currency Data
              *
              * @return void
              *---------------------------------------------------------------- */
            
            scope.submit = function() {

                __Form.process({
                    'apiURL'   :'store.settings.edit',
                    'formType' : 2
                }, scope)
                    .success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        
                        if (responseData.data.showRealodButton == true) {
                            __globals.showConfirmation({
                                title               : responseData.data.message,
                                text                : responseData.data.textMessage,
                                type                : "success",
                                showCancelButton    : true,
                                confirmButtonClass  : "btn-success",
                                confirmButtonText   : __globals.getJSString('reload_text'),
                                confirmButtonColor :  "#337ab7"
                            }, function() {

                               location.reload();

                            });
                        }
                    });    

                });
            };    


            /**
              * currency change
              *
              * @param selectedCurrency
              * @return void
              *---------------------------------------------------------------- */
            scope.currencyChange = function(selectedCurrency, paymentOption, currencyOptionList) {

              scope.currentSelectCurrency(selectedCurrency, paymentOption,currencyOptionList);

                scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, selectedCurrency);

                if (!_.isEmpty(selectedCurrency) && selectedCurrency != 'other') {

                    _.forEach(scope.currencies, function(currencyObj, key) {
                        
                        if (key == selectedCurrency) {
                            scope.editData.currency_value   = selectedCurrency;
                            scope.editData.currency_symbol  = currencyObj.ASCII;
                            scope.currencySymbol            = currencyObj.symbol;
                        }

                    });

                    scope.is_support_paypal = true;

                } else {

                    scope.editData.currency_value   = '';
                    scope.editData.currency_symbol  = '';

                }

                scope.updateCurrencyPreview(scope.currencySymbol, scope.editData.currency_value);

                scope.checkIsPaypalSupported(scope.editData.currency_value);

            };

            /**
              * currency value change
              *
              * @param currencyValue
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.currencyValueChange = function(currencyValue) {
                
                scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, currencyValue);

                if (!_.isEmpty(currencyValue) && currencyValue != 'other') {

                    var currency = {};
                    _.forEach(scope.currencies, function(currencyObj, key) {

                        if (key == currencyValue) {
                            currency = currencyObj;
                        }

                    });

                    if (_.isEmpty(currency)) {
                        //scope.is_support_paypal = false;
                        scope.editData.currency  = 'other';
                    } else {
                        //scope.is_support_paypal     = true;
                        scope.editData.currency     = currencyValue;
                        scope.editData.currency_symbol  = currency.ASCII;
                        scope.currencySymbol           = currency.symbol;
                    }

                } else if (!_.isEmpty(currencyValue)) {

                    //scope.is_support_paypal     = false;
                    scope.editData.currency     = 'other';

                } else {

                    //scope.is_support_paypal  = true;
                    scope.editData.currency  = '';

                }

                scope.checkIsPaypalSupported(currencyValue);

                if (_.isUndefined(scope.editData.currency_value)) {
                    scope.currencySymbol = '';
                }

                scope.updateCurrencyPreview(scope.currencySymbol, scope.editData.currency_value);
            };

            /**
              * Close dialog
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.closeDialog = function() {
                $scope.closeThisDialog();
            }

		}]);

})();;
(function() {
'use strict';
	
	/*
	  order setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.OrderSettings',[])

        /**
         * OrderController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
		 *
         * @return void
         *-------------------------------------------------------- */
        .controller('OrderController', [
            '$scope',
            '$state',
            'appServices',
         function OrderController($scope, $state, appServices) {

            var scope  = this;
 
			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.order')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
        }
        ])

         /**
	      * OrderSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('OrderSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            '$state',
            function (__Form, appServices, $scope, $state) {

                var scope   = this;

                scope = __Form.setup(scope, 'form_order_and_currency_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
		            unsecuredFields : [
						               'payment_check_text', 
						               'payment_bank_text', 
						               'payment_cod_text',
						               'payment_other_text'
						            ],
		        });

		        scope.pageStatus = false;

                scope.cancellation_statuses_config = __globals.getSelectizeOptions({
                    maxItems        : 1000,
                    searchField     : ['name', 'product_id'],
                    plugins         : ['remove_button']
                });

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 3
		        	}).success(function(responseData) {
		            
		            var requestData    = responseData.data;
		          
		            appServices.processResponse(responseData, null, function() {
		            
						if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
                       
                        scope.order_statuses = __globals.generateKeyValueItems(requestData.store_settings.order_statuses);

		                scope.pageStatus = true;

		            });    

		        });

                /**
                  * Add stripe key 
                  *---------------------------------------------------------------- */
                scope.addStripeKeys = function(stripeKeysFor) {

                    if (stripeKeysFor == 1) {
                        scope.editData.isLiveStripeKeysExist = false;
                    } else {
                        scope.editData.isTestingStripeKeysExist = false;
                    }
                };

                 /**
                  * Add Razorpay key 
                  *---------------------------------------------------------------- */
                scope.addRazorpayKeys = function(razorPayKeysFor) {

                    if (razorPayKeysFor == 1) {
                        scope.editData.isLiveRazorPayKeysExist = false;
                    } else {
                        scope.editData.isTestingRazorPayKeysExist = false;
                    }
                };

                /**
                  * Add stripe key 
                  *---------------------------------------------------------------- */
                scope.addIyzipayKeys = function(stripeKeysFor) {

                    if (stripeKeysFor == 1) {
                        scope.editData.isLiveIyzipayKeyExist = false;
                    } else {
                        scope.editData.isTestingIyzipayKeyExist = false;
                    }
                };
		        
		        /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :3
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
		                    	
							scope.editData.logo_image = '';
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }
                            
                            if ($state.current.name == 'taxes') {
                                $scope.closeThisDialog();
                            }

		                });    

		            });

		        };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
		}]);

})();;
(function() {
'use strict';
	
	/*
	  order setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.PaymentSettings',[])

        /**
         * PaymentController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
		 *
         * @return void
         *-------------------------------------------------------- */
        .controller('PaymentController', [
            '$scope',
            '$state',
            'appServices',
         function PaymentController($scope, $state, appServices) {

            var scope  = this;
 
			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.payment')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
        }
        ])

         /**
	      * PaymentSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('PaymentSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            '$state',
            function (__Form, appServices, $scope, $state) {

                var scope   = this;

                scope = __Form.setup(scope, 'form_payment_and_currency_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
		            unsecuredFields : [
						               'payment_check_text', 
						               'payment_bank_text', 
						               'payment_cod_text',
						               'payment_other_text'
						            ],
		        });

		        scope.pageStatus = false;
                scope.enable_paypal = false;
                scope.enable_stripe = false;
                scope.enable_paytm = false;
                scope.enable_razorpay = false;
                scope.enable_paystack = false;
                scope.enable_iyzico = false;
                scope.enable_instamojo = false;
                scope.enable_cod = false;
                scope.enable_check = false;
                scope.enable_bank = false;
                scope.enable_other = false;

                scope.paymentOptionConfig = __globals.getSelectizeOptions({
                    maxItems        : 1000,
                    searchField     : ['name'],
                    plugins         : ['remove_button'],
                });

                /**
                  * Show Payment Tab
                  *---------------------------------------------------------------- */
                scope.showPaymentPageTab = function(event) {
                    event.preventDefault();
                }

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 13
		        	}).success(function(responseData) {
		            
		            var requestData    = responseData.data;
		           
		            appServices.processResponse(responseData, null, function() {
		            
						if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
                        __pr(requestData);
                        var selectedPaymentOptions = scope.editData.select_payment_option;
                        scope.enable_paypal = _.includes(selectedPaymentOptions, 1);
                        scope.enable_stripe = _.includes(selectedPaymentOptions, 6);
                        scope.enable_paytm = _.includes(selectedPaymentOptions, 16);
                        scope.enable_razorpay = _.includes(selectedPaymentOptions, 11);
                        scope.enable_paystack = _.includes(selectedPaymentOptions, 20);
                        scope.enable_iyzico = _.includes(selectedPaymentOptions, 14);
                        scope.enable_instamojo = _.includes(selectedPaymentOptions, 18);
                        scope.enable_cod = _.includes(selectedPaymentOptions, 4);
                        scope.enable_check = _.includes(selectedPaymentOptions, 2);
                        scope.enable_bank = _.includes(selectedPaymentOptions, 3);
                        scope.enable_other = _.includes(selectedPaymentOptions, 5);

                        scope.paymentOptions = __globals.generateKeyValueItems(requestData.store_settings.paymentOptions);
                        
		                scope.pageStatus = true;

		            });    

		        });

                /**
                  * Change Payment Setting
                  *---------------------------------------------------------------- */
                scope.changePaymentSetting = function(value, method) {
                    if (_.isEmpty(scope.editData.select_payment_option)) {
                        scope.editData.select_payment_option = [];
                    }

                    if (value) {
                        if (!_.includes(scope.editData.select_payment_option, method)) {
                            scope.editData.select_payment_option.push(method);
                        }
                    } else if (!value) {
                        if (_.includes(scope.editData.select_payment_option, method)) {
                            _.remove(scope.editData.select_payment_option, function(item) {
                                return item == method;
                            });
                        }
                    }
                }
                
                /**
                  * Select Payment Option
                  *---------------------------------------------------------------- */
                scope.selectPaymentOptions = function(paymentOption) {   
                    scope.optionId = paymentOption;
                }

                /**
                  * Add stripe key 
                  *---------------------------------------------------------------- */
                scope.addStripeKeys = function(stripeKeysFor) {

                    if (stripeKeysFor == 1) {
                        scope.editData.isLiveStripeKeysExist = false;
                    } else {
                        scope.editData.isTestingStripeKeysExist = false;
                    }
                };

                 /**
                  * Add Razorpay key 
                  *---------------------------------------------------------------- */
                scope.addRazorpayKeys = function(razorPayKeysFor) {

                    if (razorPayKeysFor == 1) {
                        scope.editData.isLiveRazorPayKeysExist = false;
                    } else {
                        scope.editData.isTestingRazorPayKeysExist = false;
                    }
                };

                /**
                  * Add Iyzipay Key 
                  *---------------------------------------------------------------- */
                scope.addIyzipayKeys = function(iyzipayKeysFor) {

                    if (iyzipayKeysFor == 1) {
                        scope.editData.isLiveIyzipayKeysExist = false;
                    } else {
                        scope.editData.isTestingIyzipayKeysExist = false;
                    }
                };

                /**
                  * Add Paytm Key 
                  *---------------------------------------------------------------- */
                scope.addPaytmKeys = function(paytmKeysFor) {

                    if (paytmKeysFor == 1) {
                        scope.editData.isLivePaytmKeysExist = false;
                    } else {
                        scope.editData.isTestingPaytmKeysExist = false;
                    }
                };

                /**
                  * Add Instamojo Key 
                  *---------------------------------------------------------------- */
                scope.addInstamojoKeys = function(instamojoKeysFor) {

                    if (instamojoKeysFor == 1) {
                        scope.editData.isLiveInstamojoKeysExist = false;
                    } else {
                        scope.editData.isTestingInstamojoKeysExist = false;
                    }
                };

                /**
                  * Add Paystack Key 
                  *---------------------------------------------------------------- */
                scope.addPayStackKeys = function(payStackKeysFor) {

                    if (payStackKeysFor == 1) {
                        scope.editData.isLivePayStackKeysExist = false;
                    } else {
                        scope.editData.isTestingPayStackKeysExist = false;
                    }
                };

                
                /**
                  * Add Instamojo Key 
                  *---------------------------------------------------------------- */
                 scope.addPaypalKeys = function(isLivePaypalKeysExist) {

                    if (isLivePaypalKeysExist == 1) {
                        scope.editData.isLivePaypalKeysExist = false;
                    } else {
                        scope.editData.isTestingPaypalKeysExist = false;
                    }
                };
		        
		        /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :13
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
		                    	
							scope.editData.logo_image = '';
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }
                            
                            if ($state.current.name == 'taxes') {
                                $scope.closeThisDialog();
                            }

		                });    

		            });

		        };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
		}]);

})();;
(function() {
'use strict';
	
	/*
	  general setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.PlacementSettings', 		[])

        /**
		 * PlacementsController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('PlacementsController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function PlacementsController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.placement')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])

         /**
	      * PlacementSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('PlacementSettingsController', [
			'__Form',
			'$scope',
            'appServices',
            function (__Form, $scope, appServices) {

                var scope   = this;
  				scope.pageStatus = false;
		        scope = __Form.setup(scope, 'form_placement_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
                    unsecuredFields : ['addtional_page_end_content', 'global_notification', 'append_email_message'],
		        });

		        scope.categories_menu_placement_select_config = __globals.getSelectizeOptions({
		            valueField  : 'value',
		            labelField  : 'name',
		            searchField : [ 'name' ]  
		        });

		        scope.brand_menu_placement_select_config = __globals.getSelectizeOptions({
		            valueField  : 'value',
		            labelField  : 'name',
		            searchField : [ 'name' ]  
		        });

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 5
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            scope.menu_placement = requestData.store_settings.menu_placement;
		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings); 
		                }  
		                scope.pageStatus = true;

		            });    

		        });

		        /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' : 5
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
		                    	
							scope.editData.logo_image = '';
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

	        /**
			  * Close dialog
			  *
			  * @return void
			  *---------------------------------------------------------------- */
			scope.closeDialog = function() {
			    $scope.closeThisDialog();
			};
		}]);

})();;
(function() {
'use strict';
	
	/*
	  general setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.ProductSettings', 		[])

        /**
		 * ProductController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('ProductController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function ProductController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.product')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])
         /**
	      * ProductSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * @inject $state
	      * @inject FileUploader
	      * @inject __Utils
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('ProductSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form, appServices, $scope) {

                var scope   = this;
                scope.pageStatus = false;
		        scope = __Form.setup(scope, 'form_product_settings_edit', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false
		        });

		        scope.editData.credit_info = true;
                
                //manage product setting sidebar tab on click
                scope.manageProductSettingTab = function(event) { 
                    event.preventDefault();
                };

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 4
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
 						scope.pageStatus = true;
		            });    

		        });

		       /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' : 4
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

		        /**
				  * Close dialog
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.closeDialog = function() {
				    $scope.closeThisDialog();
				};
		}]);

})();;
(function() {
'use strict';
	
	/*
	  contact setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.ContactSettings', 		[])

        /**
         * ContactController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
		 *
         * @return void
         *-------------------------------------------------------- */
        .controller('ContactController', [
            '$scope',
            '$state',
            'appServices',
         function ContactController($scope, $state, appServices) {

            var scope  = this;
 
			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.contact')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
        }
        ])
        
         /**
	      * ContactSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('ContactSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form,appServices, $scope) {

                var scope   = this;
  				scope.pageStatus = false;
		        scope = __Form.setup(scope, 'form_contact_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
		            unsecuredFields : ['contact_address'],
		        });


		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 6
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {
		            	
		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
  						scope.pageStatus = true;
		            });    

		        });
		        
		        /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :6
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
		                    	
							scope.editData.logo_image = '';
							if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

		        /**
	              * Close dialog
	              *
	              * @return void
	              *---------------------------------------------------------------- */
	            scope.closeDialog = function() {
	                $scope.closeThisDialog();
	            };
		}]);

})();;
(function() {
'use strict';
	
	/*
	  contact setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.SliderSettings', [])

         /**
	      * ContactSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('SliderListController', [
			'__Form', 
            'appServices',
            '__DataStore',
            '$scope',
            function (__Form, appServices, __DataStore, $scope) {

                var scope = this;

                scope.getSliderData = function() {

                    __DataStore.fetch('store.settings.slider.read.list')
                        .success(function(responseData) {

                        appServices.processResponse(responseData, null, function(reactionCode) {

                            var requestData = responseData.data;
                            scope.sliderList = requestData.sliderDataCollection;

                        });

                    });
                };
                scope.getSliderData();

                // when add new record 
                $scope.$on('alider_added_or_updated', function(data) {

                    if (data) {
                        scope.getSliderData();
                    }

                });

                scope.delete  = function(sliderID, title) {
                   
                    var $lwSliderDeleteTextMsg = $('#lwSliderDeleteTextMsg');

                    __globals.showConfirmation({
                        html : __globals.getReplacedString($lwSliderDeleteTextMsg,
                                    '__title__',
                                    _.unescape(title)
                                ),
                        confirmButtonText : $lwSliderDeleteTextMsg.attr('data-delete-button-text')
                    }, function() {

                        __DataStore.post({
                            'apiURL' : 'store.settings.slider.write.delete',
                            'sliderID'  : sliderID,
                            'title' : title
                        }).success(function(responseData) {
                            
                            var message = responseData.data.message;
                            
                            appServices.processResponse(responseData, {

                                error : function(data) {
                                __globals.showConfirmation({
                                    title   : $lwSliderDeleteTextMsg .attr('data-error-text'),
                                    text    : message,
                                    type    : 'error'
                                });
                            }

                            }, function(data) {
                                __globals.showConfirmation({
                                    title   : $lwSliderDeleteTextMsg .attr('data-success-text'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.getSliderData();
                            }); 

                        });

                    });
                };

            }
        ])

         /**
         * comment here
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         * @inject __Form
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('SliderAddController', [
            '$scope',
            '__DataStore',
            'appServices',
            'FileUploader',
            '__Utils',
            'appNotify',
            '$state',
            '__Form',
            function SliderAddController( $scope, __DataStore, appServices, FileUploader, __Utils , appNotify, $state, __Form ) {
                var scope   = this;
                
                // Show loader
                scope.showLoader = true;
                

                // Setup form
                scope = __Form.setup(scope, 'slider_add_form', 'sliderAddData', {
                    secured : true
                });

                scope.imagesSelectConfig     = __globals.getSelectizeOptions({
                    valueField  : 'name',
                    labelField  : 'name',
                    render      : {
                        item: function(item, escape) {
                            return  __Utils.template('#imageListItemTemplate',
                            item
                            );
                        },
                        option: function(item, escape) {

                            return  __Utils.template('#imageListItemImageTemplate', item);
                        }
                    },
                    searchField : ['name']  
                });

                //by default auto play true
                scope.sliderAddData.auto_play = true;
                scope.sliderAddData.autoPlayTimeout = 6; 

                //product slider sortable start
                var el = document.getElementById('lw-slides-wrapper');
                var sortable = Sortable.create(el, {
                    animation: 150,
                    handle: '.lw-handle-div',
                    onEnd: function(/**Event*/evt) { 
                        var sortableArray = sortable.toArray();

                        _.map(sortableArray, function(value, key) {
                            scope.sliderAddData.slides[value]['orderIndex'] = parseInt(key);
                        })   

                    }

                });
                //product slider sortable end
                 

                 /**
                  * Fetch uploaded temp images media files
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.images_count = 0;
                scope.getTempImagesMedia = function() {

                    __Form.fetch('media.uploaded.images', { fresh : true })
                        .success(function(responseData) {
                            
                        appServices.processResponse(responseData, null, function() {
                            scope.image_files = responseData.data.files
                         
                            if (responseData.data.files.length > 0) {
                                scope.images_count = responseData.data.files.length;
                            };
                        });    

                    });

                };

                scope.getTempImagesMedia();

                scope.sliderAddData.slides  = [
                    {
                        'caption_1'        : '',
                        'caption_1_color'  : 'ffffff',
                        'caption_2'        : '',
                        'caption_2_color'  : 'ffffff',
                        'caption_3'        : '',
                        'bg_color'         : '282828',
                        'image'            : '',
                        'orderIndex'       : 0  
                    }
                ];

                /**
                  * Add new value in option value
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                
                scope.addNewValue = function() {
                    var lastOrderIndex = _.last(scope.sliderAddData.slides)['orderIndex'];
                
                    scope.sliderAddData.slides.push({
                        caption_1         : '',
                        caption_1_color   : 'ffffff',
                        caption_2         : '',
                        caption_2_color   : 'ffffff',
                        caption_3         : '',
                        bg_color          : '282828',
                        image             : '',
                        orderIndex        : lastOrderIndex + 1
                    });
                };



                /**
                  * Remove current option value row
                  *
                  * @param number index
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.remove = function(index) {

                    if (!_.isEmpty(scope.sliderAddData.slides)
                     && scope.sliderAddData.slides.length > 1) {

                        _.remove(scope.sliderAddData.slides, function(value, key) {
                            return index == key;
                        });

                    }
                    
                };

                var uploader = scope.uploader = new FileUploader({
                    url         : __Utils.apiURL('media.upload.image'),
                    autoUpload  : true,
                    headers     : {
                        'X-XSRF-TOKEN': __Utils.getXSRFToken()
                    }
                });

                // FILTERS
                uploader.filters.push({
                    name: 'customFilter',
                    fn: function(item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < 1000;
                    }
                });

                scope.currentUploadedFileCount = 0;
                scope.loadingStatus            = false;

                /**
                * uploading msg
                *
                * @return void
                *---------------------------------------------------------------- */
                uploader.onAfterAddingAll = function() {
                   
                    scope.loadingStatus = true;
                    $("#lw-spinner-widget-"+scope.newImageIndex).show();
                    $("#lwFileupload-"+scope.newImageIndex).attr("disabled", true);
                    appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

                };

                /**
                * Uploading on process
                *
                * @return void
                *---------------------------------------------------------------- */

                uploader.onBeforeUploadItem = function(item) {
                    scope.loadingStatus = true;
                };

                /**
                * on success counter of uploaded image
                *
                * @param object fileItem
                * @param object response
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onSuccessItem = function( fileItem, response ) {
                    $("#lw-spinner-widget-"+scope.newImageIndex).hide();
                    $("#lwFileupload-"+scope.newImageIndex).attr("disabled", false);

                    scope.thumbnailURL = response.data.thumbnailURL;
                    scope.sliderAddData.slides[scope.newImageIndex].thumbnailURL = response.data.thumbnailURL;
                    scope.imageFile = response.data.fileName;
                    scope.sliderAddData.slides[scope.newImageIndex].image = scope.imageFile;
                    scope.newImageIndex = null;
              
                    appServices.processResponse(response, { 
                        error : function() {
                        },
                        otherError : function(reactionCode) {
                          
                            // If reaction code is Server Side Validation Error Then 
                            if (reactionCode == 3) {

                                appNotify.error(response.data.message,{sticky : false});

                            }

                        }
                    },
                    function() {

                        scope.currentUploadedFileCount++
                        
                    });   

                };

                scope.newImageIndex = null;
                scope.addImages = function(index, data) {

                    scope.newImageIndex = index;

                };
               
                /**
                * uploaded all image then call function
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onCompleteAll  = function() {

                   scope.loadingStatus  = false;
                    if (scope.currentUploadedFileCount > 0) {
                        appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

                    }
                    scope.getTempImagesMedia();
                    scope.currentUploadedFileCount = 0;

                };

                /**
                  * Submit form method
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    // post route declared in web.php file
                    __Form.process({
                        'apiURL'   :'store.settings.slider.write.addSlider',
                        'formType' : 16
                    }, scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function(reactionCode) {
                            if (reactionCode == 1) {
                                var requestData = responseData.data;
                                $state.go('slider_setting_edit', {'sliderID': requestData.title});
                            }
                        });    

                    });
                };


        }])



        /**
        * Sample Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('SliderEditController', [
            '$scope',                
            '__DataStore',                
            '__Form',
            'FileUploader',               
            '$state',                
            'appServices',
            '__Utils',
            'appNotify',              
            '$rootScope',                
        function ( $scope,  __DataStore,  __Form, FileUploader,  $state,  appServices, __Utils, appNotify, $rootScope) {

            var scope       = this;
            scope.SliderId = $state.params.sliderID;

            scope.showLoader = true;

            scope = __Form.setup(scope, 'slider_edit_form', 'sliderEditData', {
                secured : true
            });

            scope.imagesSelectConfig     = __globals.getSelectizeOptions({
                valueField  : 'name',
                labelField  : 'name',
                render      : {
                    item: function(item, escape) {
                        return  __Utils.template('#imageListItemTemplate',
                        item
                        );
                    },
                    option: function(item, escape) {
                        return  __Utils.template('#imageListOptionTemplate',
                        item
                        );
                    }
                }, 
                searchField : ['name']  
            });

            //product slider sortable start
            var el = document.getElementById('lw-slides-wrapper');
            var sortable = Sortable.create(el, {
                animation: 150,
                handle: '.lw-handle-div',
                onEnd: function(/**Event*/evt) { 
                    var sortableArray = sortable.toArray();

                    _.map(sortableArray, function(value, key) {
                        scope.sliderEditData.slides[value]['orderIndex'] = parseInt(key);
                    })   

                }

            });
            //product slider sortable end

            /**
              * Fetch uploaded temp images media files
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.images_count = 0;
            scope.getTempImagesMedia = function() {

                __Form.fetch('media.uploaded.images', { fresh : true })
                    .success(function(responseData) {
                        
                    appServices.processResponse(responseData, null, function() {
                        scope.image_files = responseData.data.files

                        if (responseData.data.files.length > 0) {
                            scope.images_count = responseData.data.files.length;
                        };
                    });    

                });

            };

            scope.getTempImagesMedia();

            scope.getSliderEditData = function() {
                __DataStore.fetch({
                    'apiURL'   : 'store.settings.slider.read.update.data',
                    'sliderID' : scope.SliderId
                }).success(function(responseData) {

                    var requestData = responseData.data;

                    appServices.processResponse(responseData, null, function() {
                       scope.sliderEditData   = requestData.sliderCollection;
                       scope.sliderTitle      = requestData.sliderTitle;
                      
                        scope.showLoader = false;

                        scope = __Form.updateModel(scope, scope.sliderEditData);
                    });
                });
            }
            scope.getSliderEditData();
            
            /**
              * Add new value in options value
              *
              * @return void
              *---------------------------------------------------------------- */
            
            scope.addNewValue = function() {
                var lastOrderIndex = 0;
                if (!_.isEmpty(scope.sliderEditData.slides)) {
                    lastOrderIndex = _.last(scope.sliderEditData.slides)['orderIndex'];
                }
                
                scope.sliderEditData.slides.push({
                    caption_1         : '',
                    caption_1_color   : 'ffffff',
                    caption_2         : '',
                    caption_2_color   : 'ffffff',
                    caption_3         : '',
                    bg_color          : '282828',
                    image             : '',
                    orderIndex        : lastOrderIndex + 1
                });
            };

            /**
              * Remove current option value row
              *
              * @param number index
              *
              * @return void
              *---------------------------------------------------------------- */

            scope.remove = function(index) {

                if (!_.isEmpty(scope.sliderEditData.slides) || !_.isEmpty(scope.sliderEditData.configuration) && scope.sliderEditData.slides.length > 1 || scope.sliderEditData.configuration.length > 1) {

                    _.remove(scope.sliderEditData.slides, function(value, key) {
                        return index == key;
                    });

                    _.remove(scope.sliderEditData.configuration, function(value, key) {
                        return index == key;
                    });

                }
                
            };

            var uploader = scope.uploader = new FileUploader({
                url         : __Utils.apiURL('media.upload.image'),
                autoUpload  : true,
                headers     : {
                    'X-XSRF-TOKEN': __Utils.getXSRFToken()
                }
            });

            // FILTERS
            uploader.filters.push({
                name: 'customFilter',
                fn: function(item /*{File|FileLikeObject}*/, options) {
                    return this.queue.length < 1000;
                }
            });

            scope.currentUploadedFileCount = 0;
            scope.loadingStatus            = false;

            /**
            * uploading msg
            *
            * @return void
            *---------------------------------------------------------------- */
            uploader.onAfterAddingAll = function() {

                scope.loadingStatus = true;

                //upload button disabled and show spinner
                $("#lw-spinner-widget-"+scope.newImageIndex).show();
                $("#lwFileupload-"+scope.newImageIndex).attr("disabled", true);

                appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

            };

            /**
            * Uploading on process
            *
            * @return void
            *---------------------------------------------------------------- */

            uploader.onBeforeUploadItem = function(item) {
                scope.loadingStatus = true;
            };


            /**
            * on success counter of uploaded image
            *
            * @param object fileItem
            * @param object response
            *
            * @return void
            *---------------------------------------------------------------- */
            
            uploader.onSuccessItem = function( fileItem, response ) {
                //upload button enable and hide spinner
                $("#lw-spinner-widget-"+scope.newImageIndex).hide();
                $("#lwFileupload-"+scope.newImageIndex).attr("disabled", false);

                if (!_.isEmpty(response.data.thumbnailURL)) {
                    scope.thumbnailURL = response.data.thumbnailURL;
                }
                scope.sliderEditData.slides[scope.newImageIndex].thumbnailURL = response.data.thumbnailURL;
                scope.sliderEditData.slides[scope.newImageIndex].newImageExist = true; 
                scope.sliderEditData.slides[scope.newImageIndex].oldImageName = scope.sliderEditData.slides[scope.newImageIndex].image; 
                scope.imageFile = response.data.fileName;
                scope.sliderEditData.slides[scope.newImageIndex].image = scope.imageFile;
                scope.newImageIndex = null;
            
                appServices.processResponse(response, {
                    error : function() {
                    },
                    otherError : function(reactionCode) {
                      
                        // If reaction code is Server Side Validation Error Then 
                        if (reactionCode == 3) {

                            appNotify.error(response.data.message,{sticky : false});

                        }

                    }
                },
                function() {

                    scope.currentUploadedFileCount++
                    
                });   

            };

            scope.newImageIndex = null;
            scope.addImages = function(index, data) {

                scope.newImageIndex = index;

            };

            /**
            * uploaded all image then call function
            *
            * @return void
            *---------------------------------------------------------------- */
            
            uploader.onCompleteAll  = function() {

               scope.loadingStatus  = false;
                if (scope.currentUploadedFileCount > 0) {
                    appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

                }
                scope.getTempImagesMedia();
                scope.currentUploadedFileCount = 0;

            };

          /**
            * Submit form
            *
            * @return  void
            *---------------------------------------------------------------- */

            scope.submit = function() {
             
                __Form.process({
                    'apiURL'    : 'store.settings.slider.write.update',
                    'sliderID'  : scope.SliderId,
                    'formType' : 16
                }, scope).success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        scope.getSliderEditData();
                        //$state.go('slider_setting');
                    });    
                });
            };

        }])

})();;
(function() {
'use strict';
	
	/*
	  privacy setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.privacyPolicySettings', [])

        /**
		 * PrivacyPolicyController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('PrivacyPolicyController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function PrivacyPolicyController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.privacy-policy')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])

         /**
	      * PrivacyPolicySettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('PrivacyPolicySettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form, appServices, $scope) {

                var scope   = this;
                scope.pageStatus = false;
		        scope 		= __Form.setup(scope, 'form_privacy_policy_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
		            unsecuredFields : ['privacy_policy']
		        });

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 8
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
  						scope.pageStatus = true;
		            });    

		        });

		       /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   : 'store.settings.edit',
		        		'formType' : 8
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

	        /**
			  * Close dialog
			  *
			  * @return void
			  *---------------------------------------------------------------- */
			scope.closeDialog = function() {
			    $scope.closeThisDialog();
			};
		}]);

})();;
(function() {
'use strict';
	
	/*
	  ads setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.landingPageSettings', [])


         /**
	      * LandingPageSettingController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('LandingPageSettingController', [
			'__Form', 
            'appServices',
            '$scope',
            'FileUploader',
            '__Utils',
            'appNotify',
            function (__Form, appServices, $scope, FileUploader, __Utils, appNotify) {

                var scope   = this;
                scope.pageStatus              = false;
                scope.landingPageEditData     = [];
                scope.sliderIndex             = null;                
                scope.pageContentIndex        = null;
                scope.latestProductIndex      = null;
                scope.featureProductIndex     = null;
                scope.popularPageIndex        = null;
                scope.banner1Index            = null;
                scope.banner2Index            = null;
                scope.productTabIndex         = null;
                scope.selectedItem            = 0;

		        scope 		= __Form.setup(scope, 'form_landing_page_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false
		        });

                scope.slider_option_config = __globals.getSelectizeOptions({
                    valueField  : 'title',
                    labelField  : 'title',
                    searchField : ['title']  
                });

                scope.productSelectConfig = __globals.getSelectizeOptions({
                    valueField  : 'id',
                    labelField  : 'name',
                    searchField : ['name'],
                    plugins     : ['remove_button'],
                    maxItems    : 1000,
                    delimiter   : ','
                });

                scope.home_page_select_config = __globals.getSelectizeOptions({
		            valueField  : 'id',
		            labelField  : 'name',
		            searchField : [ 'name' ]  
		        });

                var uploader = scope.uploader = new FileUploader({
                    url         : __Utils.apiURL('media.upload.image'),
                    autoUpload  : true,
                    headers     : {
                        'X-XSRF-TOKEN': __Utils.getXSRFToken()
                    }
                });

                // FILTERS
                uploader.filters.push({
                    name: 'customFilter',
                    fn: function(item /*{File|FileLikeObject}*/, options) {
                        return this.queue.length < 1000;
                    }
                });

                scope.currentUploadedFileCount = 0;
                scope.loadingStatus            = false;

                /**
                * uploading msg
                *
                * @return void
                *---------------------------------------------------------------- */
                uploader.onAfterAddingAll = function() {

                    scope.loadingStatus = true;
                    appNotify.info(__globals.getJSString('loading_text'),{sticky : true});

                };

                /**
                * Uploading on process
                *
                * @return void
                *---------------------------------------------------------------- */

                uploader.onBeforeUploadItem = function(item) {
                    scope.loadingStatus = true;
                };

                /**
                * on success counter of uploaded image
                *
                * @param object fileItem
                * @param object response
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onSuccessItem = function( fileItem, response ) {  
                    scope.thumbnailURL = response.data.thumbnailURL;
                    scope.imageFile = response.data.fileName;
                    scope.editData.landingPageData[scope.newImageIndex][scope.newImageItemThumb] = scope.thumbnailURL;
                    scope.editData.landingPageData[scope.newImageIndex][scope.newImageItemName] = scope.imageFile;
                    
                    scope.newImageIndex = null;
                    scope.newImageItemThumb = null;
                    scope.newImageItemName = null;
                    appServices.processResponse(response, { 
                        error : function() {
                        },
                        otherError : function(reactionCode) {
                          
                            // If reaction code is Server Side Validation Error Then 
                            if (reactionCode == 3) {

                                appNotify.error(response.data.message,{sticky : false});

                            }

                        }
                    },
                    function() {

                        scope.currentUploadedFileCount++
                        
                    });   

                };

                scope.newImageIndex = null;
                scope.newImageItemThumb = null;
                scope.newImageItemName = null;
                scope.addImages = function(index, itemThumb, itemName) {
                    scope.newImageIndex = index;
                    scope.newImageItemThumb = itemThumb;
                    scope.newImageItemName = itemName;
                };
               
                /**
                * uploaded all image then call function
                *
                * @return void
                *---------------------------------------------------------------- */
                
                uploader.onCompleteAll  = function() {

                   scope.loadingStatus  = false;
                    if (scope.currentUploadedFileCount > 0) {
                        appNotify.success(scope.currentUploadedFileCount+' '+__globals.getJSString('file_uploaded_text'), {sticky : false});

                    }
                    scope.currentUploadedFileCount = 0;

                };

                // Show active tab
                scope.showActiveTab = function(selectedItem) {
                    _.delay(function() {
                        var firstTabIdentity = scope.editData.landingPageData[selectedItem]['identity'];
                        var tabSelector = $('#lwListTab span a[href="#'+firstTabIdentity+'"]');
                        var $listItemGroup = $('.list-group-item');
                        _.forEach($listItemGroup, function(item) {
                            $(item).removeClass('active');
                        });

                        $(tabSelector[0]).tab('show')
                    }, 500);
                }


		        /**
                  * Fetch support data
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.getLandingPageData = function() {
                    __Form.fetch({  
                            'apiURL'   :'store.settings.landing_page.edit.supportData',
                            'formType' : 18
                        },{'fresh': true}).success(function(responseData) {                 
                       
                        appServices.processResponse(responseData, null, function() {
                            scope.editData.landingPageData = [];
                            var requestData = responseData.data;
                            scope.landingPageEditData = [];
                            
                            scope.sliderList = requestData.sliderList;
                            scope.productList = requestData.productList; 
                          
                            _.defer(function() {
                                scope.editData.landingPageData = requestData.landingPageEditData;
                                  
                                _.forEach(scope.editData.landingPageData, function(item) {
                                if (item.identity == 'Slider') {
                                    scope.sliderIndex = item.orderIndex;
                                } else if (item.identity == 'PageContent') {
                                    scope.pageContentIndex = item.orderIndex;
                                } else if (item.identity == 'latestProduct') {
                                    scope.latestProductIndex = item.orderIndex;
                                } else if (item.identity == 'featuredProduct') {
                                    scope.featureProductIndex = item.orderIndex;
                                } else if (item.identity == 'popularProduct') {
                                    scope.popularPageIndex = item.orderIndex;
                                } else if (item.identity == 'bannerContent1') {
                                    scope.banner1Index = item.orderIndex;
                                } else if (item.identity == 'bannerContent2') {
                                    scope.banner2Index = item.orderIndex;
                                } else if (item.identity == 'productTabContent') {
                                    scope.productTabIndex = item.orderIndex;
                                }
                            });
                        });
                           

                            scope.homePageSetting = __globals.generateKeyValueItems(requestData.home_page_setting);
                            scope.editData.home_page = requestData.home_page;
                            scope.showActiveTab(scope.selectedItem);
                            
                            scope.pageStatus = true;
                        });
                    });
                };

                scope.getLandingPageData();


                // Prepare Title for tabs
                scope.prepareTitle = function(title) {
                    var newTitle = title.replace(/([A-Z])/g, ' $1').trim();
                    return newTitle.charAt(0).toUpperCase() + newTitle.slice(1);
                }

                // Show setting when click on tab
                scope.showLandingPageTab = function(event, index) {
                    scope.selectedItem = index;
                    event.preventDefault();
                    var $listItemGroup = $('.list-group-item');
                    _.forEach($listItemGroup, function(item) {
                        $(item).removeClass('active');
                    });
                }

                var lwLandingSettingSort = document.getElementById('lwListTab');
                var sortable = new Sortable(lwLandingSettingSort, {
                    animation: 150,
                    onEnd: function(evt) {
                    var $listItemGroup = $('.list-group-item');
                    _.forEach($listItemGroup, function(item, index) {
                        if ($(item).hasClass('active')) {
                            scope.selectedItem = index;
                        };
                    });

                        _.delay(function() {
                            var sortableArray = sortable.toArray();

                            _.map(sortableArray, function(value, key) {
                               
                                scope.editData.landingPageData[value]['orderIndex'] = parseInt(key);
                                 
                            }, 1000);
                           
                        });                        
                    }
                });


		       /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {
		            __Form.process({	
		        		'apiURL'   : 'store.settings.landing_page.write.edit',
		        		'formType' : 18
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
							scope.getLandingPageData();
		                });    

		            });

		        };

	        /**
			  * Close dialog
			  *
			  * @return void
			  *---------------------------------------------------------------- */
			scope.closeDialog = function() {
			    $scope.closeThisDialog();
			};
		}]);

})();;
(function() {
'use strict';
	
	/*
	  ads setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.manageFooterSettings', [])

         /**
	      * ManageFooterSettingController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('ManageFooterSettingController', [
			'__Form', 
            'appServices',
            '$scope',
            '__DataStore',
            function (__Form, appServices, $scope, __DataStore) {

                var scope   = this;
                scope.pageStatus = false;

		        scope 		= __Form.setup(scope, 'manage_footer_settings', 'editData', {
		            secured : true,
		        });

                 
                /**
                * Fetch support data
                *
                * @return void
                *---------------------------------------------------------------- */
                scope.getFooterTemplateData = function () {

                    __Form.fetch({  
                            'apiURL':'store.settings.get.edit.footer_template.data',
                            'formType' : 17
                        }).success(function(responseData) {
                        
                        var requestData = responseData.data.footerTemplateData;
                       
                        appServices.processResponse(responseData, null, function() {
                          
                            scope.footerViewData = requestData.public_footer_template;
                            scope.footerTemplateData = requestData;
                            scope.replaceString     = requestData.replaceString;
                            scope.templateDataExist = requestData.templateDataExist;
                         
                            //update form
                            __Form.updateModel(scope, requestData);

                            scope.pageStatus = true;
                        });    

                    });

                };
                
                scope.getFooterTemplateData();

                //when add new record 
                $scope.$on('footer_template_added_or_updated', function (data) {
                   
                    if (data) {
                        scope.getEmailTemplateData();
                    }

                });

                //reset default email template
                scope.resetFooterTemlate = function() {
                    scope.editData.public_footer_template = scope.footerViewData;
                    // var htmlData =  scope.editData.public_footer_template;
                };

                //default template view data
                scope.defaultEmailTemlate = function(title) {
                    
                    var $lwTemlateDeleteConfirmTextMsg = $('#lwTemlateDeleteConfirmTextMsg');

                    __globals.showConfirmation({
                        html                : __globals.getReplacedString($lwTemlateDeleteConfirmTextMsg , 
                                                '__title__', 
                                                unescape(title)
                                             ),
                        confirmButtonText   : $lwTemlateDeleteConfirmTextMsg.attr('data-delete-button-text')
                    }, function() {

                        __DataStore.post({
                            'apiURL'    : 'store.settings.footer_template.delete',
                            'footerTemplateId' : 'public_footer_template'
                        })
                        .success(function(responseData) {
                        
                            var message = responseData.data.message;
                   
                            appServices.processResponse(responseData, {
                                
                                    error : function(data) {
                                        __globals.showConfirmation({
                                            title   : $lwTemlateDeleteConfirmTextMsg .attr('data-success-text'),
                                            text    : message,
                                            type    : 'error'
                                        });

                                    }
                                },
                                function(data) {
                                    
                                    __globals.showConfirmation({
                                        title               : responseData.data.message,
                                        text                : responseData.data.textMessage,
                                        type                : "success",
                                        showCancelButton    : true,
                                        confirmButtonClass  : "btn-success",
                                        confirmButtonText   : $("#lwReloadBtnText")
                                                                .attr('data-message'),
                                        confirmButtonColor :  "#337ab7"
                                    }, function() {

                                       location.reload();

                                    });
                                   
                                }

                            );  

                        });

                    });
                };

                //copy text string form dekstop
                var $lwCopyToClipboardJS = new ClipboardJS('.lw-copy-action');

                $lwCopyToClipboardJS.on('success', function(ele) {

                    $(ele.trigger).attr("title", "Copied!");

                    ele.clearSelection();

                });

                 /**
                  * update email data
                  *
                  * @return void
                  *---------------------------------------------------------------- */
               
                scope.submit = function() {
                  
                __Form.process({
                    'apiURL':'store.settings.footer_template.edit',
                    'formType' : 17,
                    'footerTemplateId' : 'public_footer_template'
                }, scope)
                    .success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                       
                        __globals.showConfirmation({
                            title               : responseData.data.message,
                            text                : responseData.data.textMessage,
                            type                : "success",
                            showCancelButton    : true,
                            confirmButtonClass  : "btn-success",
                            confirmButtonText   : $("#lwReloadBtnText")
                                                    .attr('data-message'),
                            confirmButtonColor :  "#337ab7"
                        }, function() {

                           location.reload();

                        });
                    });    

                });
            };

		}]);

})();;
(function() {
'use strict';
	
	/*
	  term & condition setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.userSettings',[])

        /**
		 * UserSettingEditController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('UserSettingEditController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function UserSettingEditController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.user')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])

         /**
	      * userSettingsController for update store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('userSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form, appServices, $scope) {

                var scope   = this;
  				scope.pageStatus = false;
		        scope = __Form.setup(scope, 'form_user_settings', 'editData', {
		            secured : true,
		        	modelUpdateWatcher:false,
		            unsecuredFields : [
		            					'term_condition'
								    ]
		        });

                /**
                  * Add stripe key 
                  *---------------------------------------------------------------- */
                scope.addRecaptchaKeys = function(recaptchaKeysFor) {
                    scope.editData.isRecaptchaKeyExist = false;
                };

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' :7
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }

		                scope.pageStatus = true;

		            });    

		        });

		       /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :7
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

		        /**
				  * Close dialog
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.closeDialog = function() {
				    $scope.closeThisDialog();
				};
		}]);

})();;
(function() {
'use strict';
    
    /*
      CssStyleSettingsController setting Related Controllers
      ---------------------------------------------------------------------- */
    
    angular
        .module('manageApp.CssStyleSettings',        [])

    /** 
     * CssStyleSettingsController for manage custom css
     *
     * @inject $scope
     * @inject __Form
     * @inject appServices
     *
     * @return void
     *-------------------------------------------------------- */
    .controller('CssStyleSettingsController', [
        '$scope',
        '__Form',
        'appServices',      
    function CssStyleSettingsController( $scope, __Form, appServices ) {

        var scope = this;

        scope.pageStatus = false;

        scope  = __Form.setup(scope, 'edit_css_style_configuration', 'editData', {
            secured : true,
            modelUpdateWatcher:false,
            unsecuredFields : ['custom_css']
        });
       
        var cssTextEditorEle  = document.getElementById('custom_css');
            
            var $cssTextEditorInstance  = CodeMirror.fromTextArea(cssTextEditorEle, {
                lineNumbers     : true,
                mode            : 'css',
                readOnly        : false,
                autofocus       : true,
                lineWrapping    : true
            });

          __Form.fetch({  
              'apiURL'   :'store.settings.edit.supportdata',
              'formType' : 9
            }).success(function(responseData) {
              
              var requestData    = responseData.data;

                appServices.processResponse(responseData, null, function() {

                    var cssStyleData =  requestData.store_settings;

                    __Form.updateModel(scope, cssStyleData);
                    
                    if (!_.isEmpty(cssStyleData.custom_css)) {
                        $cssTextEditorInstance.setValue(cssStyleData.custom_css);

                    }
                    _.defer(function() {
                        $cssTextEditorInstance.setCursor({line:0, ch:0});
                    });

                }); 
                scope.pageStatus = true;
          });

        /**
          * Submit privacyPolicy Data
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

            scope.editData.custom_css = $cssTextEditorInstance.getValue();

            __Form.process({
               'apiURL'   :'store.settings.edit',
               'formType' : 9
            }, scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    
                    if (responseData.data.showRealodButton == true) {
                        __globals.showConfirmation({
                            title               : responseData.data.message,
                            text                : responseData.data.textMessage,
                            type                : "success",
                            showCancelButton    : true,
                            confirmButtonClass  : "btn-success",
                            confirmButtonText   : __globals.getJSString('reload_text'),
                            confirmButtonColor :  "#337ab7",
                            closeOnConfirm      : false
                        }, function() {

                           location.reload();

                        });
                    }
                });    

            });
        };

     }]);

})();;
(function() {
'use strict';
	
	/*
	  Social setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.socialSettings', [])
 

        /**
		 * SocialController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('SocialController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function SocialController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.social')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])

         /**
	      * SocialSettingsController for update social store settings
	      *
	      * @inject __Form
	      * @inject appServices
	      * 
	      * @return void
	      *-------------------------------------------------------- */

		.controller('SocialSettingsController', [
			'__Form', 
            'appServices',
            '$scope',
            function (__Form,appServices,$scope) {

                var scope   = this;
                scope.pageStatus = false;
		        scope 		= __Form.setup(scope, 'form_social_settings', 'editData', {
		        	secured : true,
		        	modelUpdateWatcher:false
		        });

		        /**
		          * Fetch support data
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.edit.supportdata',
		        		'formType' : 10
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {

		                if (!_.isEmpty(requestData.store_settings)) {
		                    __Form.updateModel(scope, requestData.store_settings);
		                }
  						scope.pageStatus = true;
		            });    

		        });

		       /**
		          * Submit store settings edit form
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        
		        scope.submit = function() {

		            __Form.process({	
		        		'apiURL'   :'store.settings.edit',
		        		'formType' :10
		        	}, scope)
		                .success(function(responseData) {
		                	
		                appServices.processResponse(responseData, null, 
		                    function() {
							
                            if (responseData.data.showRealodButton == true) {
    		                    __globals.showConfirmation({
    				                title               : responseData.data.message,
    				                text                : responseData.data.textMessage,
    				                type                : "success",
    		                        showCancelButton    : true,
    		                        confirmButtonClass  : "btn-success",
    		                        confirmButtonText   : __globals.getJSString('reload_text'),
    								confirmButtonColor :  "#337ab7",
    		                        closeOnConfirm      : false
    				            }, function() {

    				               location.reload();

    				            });
                            }

		                });    

		            });

		        };

		        /**
				  * Close dialog
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.closeDialog = function() {
				    $scope.closeThisDialog();
				};
		}])

		/**
		 * SocialLoginController for update request
		 *
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 *
		 * @return void
		 *-------------------------------------------------------- */
		.controller('SocialLoginController', [
		    '$scope',
		    '$state',
		    'appServices',
		 function SocialLoginController($scope, $state, appServices) {

		    var scope  = this;

			appServices.showDialog({}, {
				templateUrl : __globals.getTemplateURL('store.social-login')
			}, function(promiseObj) {
				$state.go('store_settings_edit');
			});
		}
		])
		/**
         * SocialLoginSetupSettingsController
         *
         * @inject $scope
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('SocialLoginSetupSettingsController', [
            '$scope',
            '$state',
            '__Form',
            'appServices',
        function ConfigurationController($scope, $state, __Form, appServices) {

            var scope   = this;

            scope.pageStatus = false;

            scope  = __Form.setup(scope, 'edit_social_login_configuration', 'editData', {
                secured : true
            });

			/**
	          * Fetch support data
	          *
	          * @return void
	          *---------------------------------------------------------------- */

	        __Form.fetch({	
	        		'apiURL'   :'store.settings.edit.supportdata',
	        		'formType' : 11
	        	}).success(function(responseData) {
	            
	            var requestData = responseData.data;
	            
	            appServices.processResponse(responseData, null, function() {

	                if (!_.isEmpty(requestData.store_settings)) {
	                    __Form.updateModel(scope, requestData.store_settings);
	                }

					scope.pageStatus = true;

	            });    

	        });

            /**
              * show details of keys
              *---------------------------------------------------------------- */
            scope.showDetails = function(keyId) {

                if (keyId == 1) {
                    scope.editData.isFacebookKeyExist = false;
                } else if (keyId == 2) {
                    scope.editData.isGoogleKeyExist   = false;
                } else if (keyId == 3) {
                    scope.editData.isTwitterKeyExist  = false;
                } else if (keyId == 4) {
                    scope.editData.isGithubKeyExist   = false;
                }
            };

            /**
	          * Submit store settings edit form
	          *
	          * @return void
	          *---------------------------------------------------------------- */
	        
	        scope.submit = function() {

	            __Form.process({	
	        		'apiURL'   :'store.settings.edit',
	        		'formType' :11
	        	}, scope)
	                .success(function(responseData) {
	                	
	                appServices.processResponse(responseData, null, 
	                    function() {
						
                        if (responseData.data.showRealodButton == true) {
		                    __globals.showConfirmation({
				                title               : responseData.data.message,
				                text                : responseData.data.textMessage,
				                type                : "success",
		                        showCancelButton    : true,
		                        confirmButtonClass  : "btn-success",
		                        confirmButtonText   : __globals.getJSString('reload_text'),
								confirmButtonColor :  "#337ab7",
		                        closeOnConfirm      : false
				            }, function() {

				               location.reload();

				            });
                        }

	                });    

	            });

	        };

            /**
              * Close dialog
              *
              * @return void
              *---------------------------------------------------------------- */

            scope.closeDialog = function() {
                $scope.closeThisDialog();
            };

        }])

})();;
(function() {
'use strict';
	
	/*
	  contact setting Related Controllers
	  ---------------------------------------------------------------------- */
	
	angular
        .module('manageApp.EmailTemplateSettings', 		[])

        /**
         * EmailTemplateListController for add or update email related items
         *
         * @inject $scope
         * @inject __Form
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('EmailTemplateListController', [
            '$scope',
            '__Form',
            'appServices',	
            '$http',	
            '__Utils',
        function EmailTemplateListController( $scope, __Form, appServices, $http, __Utils ) {
        		var scope = this;
               
    		  	/**
	          	* Fetch support data
	          	*
	          	* @return void
	          	*---------------------------------------------------------------- */

		        __Form.fetch({	
		        		'apiURL'   :'store.settings.get.email-template.data'
		        	}).success(function(responseData) {
		            
		            var requestData = responseData.data;
		            
		            appServices.processResponse(responseData, null, function() {
		            	scope.emailTemplateData = requestData.emailTemplateData;
		            });    

		        });
        		
         	}

        ])

         /**
         * EmailTemplateEditController for add or update email related items
         *
         * @inject $scope
         * @inject __Form
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('EmailTemplateEditController', [
            '$scope',
            '__Form',
            'appServices',	
            '$http',	
            '__Utils',
            '$stateParams',
            '__DataStore',
            '$rootScope',
        function EmailTemplateEditController( $scope, __Form, appServices, $http, __Utils, $stateParams, __DataStore, $rootScope) {

        		var scope = this;
                scope.pageStatus = false;
        		var emailTemplateId = $stateParams.emailTemplateId;

        		scope  = __Form.setup(scope, 'email_template_form', 'dynamicEmailData', {
	        		secured : true
	        	});
        	

				/**
	          	* Fetch support data
	          	*
	          	* @return void
	          	*---------------------------------------------------------------- */
	          	scope.getEmailTemplateData = function () {

			        __Form.fetch({	
			        		'apiURL':'store.settings.get.edit.email-template.data',
	                		'templateId' : emailTemplateId
			        	}).success(function(responseData) {
			            
			            var requestData = responseData.data.emailTemplateData;
			           
			            appServices.processResponse(responseData, null, function() {
                            
								scope.emailTemplateView = requestData.templateViewData;
				         		scope.emailTemplateData = requestData;
				         		scope.emailSubjectKey 	= requestData.emailSubjectKey;
				        		scope.replaceString 	= requestData.replaceString;
								scope.subjectExists 	= requestData.subjectExists;
								scope.templateDataExist = requestData.templateDataExist;
								scope.emailSubject 		= requestData.emailSubject;

					        	// update form
					        	__Form.updateModel(scope, requestData);

                                scope.pageStatus = true;
			            });    

			        });

		        };
				
				scope.getEmailTemplateData();

		        //when add new record 
                $scope.$on('template_added_or_updated', function (data) {
                   
                    if (data) {
                        scope.getEmailTemplateData();
                    }

                });

		        //reset default email template
	        	scope.resetEmailTemlate = function() {
	        		scope.dynamicEmailData.templateViewData = scope.emailTemplateView;
        			var htmlData =  scope.dynamicEmailData.templateViewData;
	        	};

	        	//set default email subject
	        	scope.useDefaultEmailSubject = function(emaiSubjectId) {
	        		
	        		if (scope.subjectExists === true) {
	        		
	        			__DataStore.post({
		                    'apiURL' 		: 'store.settings.email_subject.delete',
		                    'emaiSubjectId' 	: emaiSubjectId,
		                })
		                .success(function(responseData) {
		                
		                    var message = responseData.data.message;
	               
			                appServices.processResponse(responseData, {});  
			                $rootScope.$broadcast('template_added_or_updated', true);
			                // scope.getEmailTemplateData();
		                });
	        		}	
	        		
	        	};

	        	//default template view data
	        	scope.defaultEmailTemlate = function(title) {
	        		
	        		var $lwTemlateDeleteConfirmTextMsg = $('#lwTemlateDeleteConfirmTextMsg');

		        	__globals.showConfirmation({
		                html                : __globals.getReplacedString($lwTemlateDeleteConfirmTextMsg , 
		                                        '__title__', 
		                                        unescape(title)
		                                     ),
		                confirmButtonText   : $lwTemlateDeleteConfirmTextMsg.attr('data-delete-button-text')
		            }, function() {

		        		__DataStore.post({
		                    'apiURL' 	: 'store.settings.email_template.delete',
		                    'emailTemplateId' 	: emailTemplateId,
		                })
		                .success(function(responseData) {
		                
		                    var message = responseData.data.message;
                   
			                appServices.processResponse(responseData, {
			                	
			                        error : function(data) {
			                            __globals.showConfirmation({
			                                title   : $lwTemlateDeleteConfirmTextMsg .attr('data-success-text'),
			                                text    : message,
			                                type    : 'error'
			                            });

			                        }
			                    },
			                    function(data) {
			                    	
			                        __globals.showConfirmation({
			                            title               : responseData.data.message,
			                            text                : responseData.data.textMessage,
			                            type                : "success",
			                            showCancelButton    : true,
			                            confirmButtonClass  : "btn-success",
			                            confirmButtonText   : $("#lwReloadBtnText")
			    												.attr('data-message'),
			                            confirmButtonColor :  "#337ab7"
			                        }, function() {

			                           location.reload();

			                        });
			                       
			                    }

			                );  

		                });

	                });
	        	};

	        	//copy text string form dekstop
	        	var $lwCopyToClipboardJS = new ClipboardJS('.lw-copy-action');

				$lwCopyToClipboardJS.on('success', function(ele) {

					$(ele.trigger).attr("title", "Copied!");

					ele.clearSelection();

				});
           		
                /**
	              * update email data
	              *
	              * @return void
	              *---------------------------------------------------------------- */
	            
	            scope.submit = function() {
	            	
	                __Form.process({
	                    'apiURL'    : 'store.settings.email_template.edit',
	                    'emailTemplateId' : emailTemplateId
	                }, scope)
	                    .success(function(responseData) {

	                    appServices.processResponse(responseData, null, function() {
	                       
	                        __globals.showConfirmation({
	                            title               : responseData.data.message,
	                            text                : responseData.data.textMessage,
	                            type                : "success",
	                            showCancelButton    : true,
	                            confirmButtonClass  : "btn-success",
	                            confirmButtonText   : $("#lwReloadBtnText")
	    												.attr('data-message'),
	                            confirmButtonColor :  "#337ab7"
	                        }, function() {

	                           location.reload();

	                        });
	                    });    

	                });
	            };
        	
         	}

        ])
        
        ;

})();;
(function() {
'use strict';
    
    /*
      term & condition setting Related Controllers
      ---------------------------------------------------------------------- */
    
    angular
        .module('manageApp.emailSettings',[])

        /**
         * EmailSettingEditController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('EmailSettingEditController', [
            '$scope',
            '$state',
            'appServices',
         function EmailSettingEditController($scope, $state, appServices) {

            var scope  = this;

            appServices.showDialog({}, {
                templateUrl : __globals.getTemplateURL('store.email')
            }, function(promiseObj) {
                $state.go('store_settings_edit');
            });
        }
        ])

         /**
          * EmailSettingDialogController for update store settings
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('EmailSettingDialogController', [
            '__Form', 
            'appServices',
            '$scope',
            function (__Form, appServices, $scope) {

                var scope   = this;
                scope.pageStatus = false;
                scope = __Form.setup(scope, 'form_email_settings', 'editData', {
                    secured : true,
                    modelUpdateWatcher:false,
                    unsecuredFields : ['append_email_message'],
                });

                scope.uiSwitch      = { size: 'normal'};

                scope.email_driver_config = __globals.getSelectizeOptions({
                    valueField  : 'id',
                    labelField  : 'name'
                });

                /**
                  * Fetch support data
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                __Form.fetch({  
                        'apiURL'   :'store.settings.edit.supportdata',
                        'formType' :15
                    }).success(function(responseData) {
                    
                    var requestData = responseData.data;
                   
                    appServices.processResponse(responseData, null, function() {

                        scope.maildrivers = requestData.store_settings.mail_drivers;
                        var mail_encryption_types = requestData.store_settings.mail_encryption_types;

                        scope.mail_encryption_types = [];
                        _.forEach(mail_encryption_types, function(encryption, key) {
                            scope.mail_encryption_types.push({
                                'id' : key,
                                'name'   : encryption
                            });
                        });

                        if (!_.isEmpty(requestData.store_settings)) {
                            __Form.updateModel(scope, requestData.store_settings);
                        }

                        scope.pageStatus = true;

                    });    

                });

               /**
                  * Submit store settings edit form
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                
                scope.submit = function() {

                    __Form.process({    
                        'apiURL'   :'store.settings.edit',
                        'formType' :15
                    }, scope)
                        .success(function(responseData) {
                            
                        appServices.processResponse(responseData, null, 
                            function() {
                            
                            if (responseData.data.showRealodButton == true) {
                                __globals.showConfirmation({
                                    title               : responseData.data.message,
                                    text                : responseData.data.textMessage,
                                    type                : "success",
                                    showCancelButton    : true,
                                    confirmButtonClass  : "btn-success",
                                    confirmButtonText   : __globals.getJSString('reload_text'),
                                    confirmButtonColor :  "#337ab7",
                                    closeOnConfirm      : false
                                }, function() {

                                   location.reload();

                                });
                            }

                        });    

                    });

                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
        }]);

})();;
/*!
 *  Engine      : ManagePaymentEngine
 *  Component   : Order
----------------------------------------------------------------------------- */

(function( window, angular, undefined ) {

	'use strict';
	
	/*
	  Manage Payment Engine
	  -------------------------------------------------------------------------- */
	
	angular.module('ManageApp.payment', 		[])

		/**
    	  * PaymentListController for list of address
    	  *
    	  * @inject __Utils
    	  * @inject __Form
    	  * @inject $state
    	  * @inject appServices
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('PaymentListController', [ 
            '__Utils',
            '__Form',
            '$state',
            'appServices',
            '$scope',
            '__DataStore',
            function (__Utils, __Form , $state, appServices, $scope, __DataStore) {

            	var scope   	= this;

            	scope = __Form.setup(scope, 'manage_payment_list', 'paymentData');


            	scope.paymentData.duration 	= 5; // today

            	var monthFirstDay       = moment().startOf('month')
                                            .format('YYYY-MM-DD'),
                    monthLastDay        = moment().endOf('month').format('YYYY-MM-DD'),

                    lastMonthFirstDay   = moment().subtract(1, 'months')
                                            .startOf('month').format('YYYY-MM-DD'),
                    lastMonthLastDay    = moment().subtract(1, 'months')
                                            .endOf('month').format('YYYY-MM-DD'),
                    
                    currentWeekFirstDay = moment().startOf('week').format('YYYY-MM-DD'),
                    currentWeekLastDay  = moment().endOf('week').format('YYYY-MM-DD'),

                    lastWeekFirstDay    = moment().weekday(-7).format('YYYY-MM-DD'),
                    lastWeekLastDay     = moment().weekday(-1).format('YYYY-MM-DD'),
                    today               = moment().format('YYYY-MM-DD'),
                    yesterday           = moment().subtract(1, 'day').format('YYYY-MM-DD'),
                    lastYearFirstDay    = moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD'),
                    lastYearLastDay     = moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD'),
                    currentYearFirstDay = moment().startOf('year').format('YYYY-MM-DD'),
                    currentYearLastDay  = moment().endOf('year').format('YYYY-MM-DD'),
                    last30Days          = moment().subtract(30, 'day').format('YYYY-MM-DD');


                // date and time
            	var today = moment().format('YYYY-MM-DD');

				scope.paymentData.start = today;
				scope.paymentData.end   = today;

				scope.startDateConfig = {
					time    : false
				};

				scope.endDateConfig = {
					minDate : moment().format('YYYY-MM-DD'),
					time    : false
				};

				$scope.$watch('paymentListCtrl.paymentData.start', 
	                function(currentValue, oldValue) {

	                var $element = angular.element('#end');
	          
	                // Check if currentValue exist
	                if (_.isEmpty(currentValue)) {
	      
	                    $element.bootstrapMaterialDatePicker('setMinDate', '');

	                } else {

	                    $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
	                }
				});

				/**
				  * Call when start date updated
				  *
				  * @param startDate
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.startDateUpdated = function(startDate) {

					if (scope.paymentData.duration) {

                        if ( startDate != monthFirstDay 
                            &&  startDate != lastMonthFirstDay 
                            && startDate != currentWeekFirstDay
                            && startDate != lastWeekFirstDay ) {

                            scope.paymentData.duration = 7; // custom duration
                        }
                    }

					scope.paymentData.start = startDate;
				};

				/**
				  * Call when start date updated
				  *
				  * @param endDate
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.endDateUpdated = function(endDate) {
					
					if (scope.paymentData.duration) {

                        if ( endDate != monthLastDay 
                            &&  endDate != lastMonthLastDay 
                            && endDate != currentWeekLastDay
                            && endDate != lastWeekLastDay ) {

                            scope.paymentData.duration = 7; // custom duration
                        }
                    }

					if (scope.paymentData.start > scope.paymentData.end) { 
						scope.paymentData.end = endDate;
					}
					scope.paymentData.end = endDate;
				};

				/**
				  * When changed in duration start and end date changed
				  *
				  * @param {number} duration
				  *
				  * @return void
				  *---------------------------------------------------------------- */

				scope.durationChange = function(duration) {

					if (duration) {

						switch(duration) {

							case 1:

								scope.paymentData.start = monthFirstDay;
								scope.paymentData.end   = monthLastDay

								break;

							case 2:

								scope.paymentData.start = lastMonthFirstDay;
								scope.paymentData.end   = lastMonthLastDay

								break;

							case 3:

								scope.paymentData.start = currentWeekFirstDay;
								scope.paymentData.end   = currentWeekLastDay

								break;

							case 4:

								scope.paymentData.start = lastWeekFirstDay;
								scope.paymentData.end   = lastWeekLastDay

								break;

							case 5:

								scope.paymentData.start = today;
								scope.paymentData.end   = today

								break;

							case 6:

								scope.paymentData.start = yesterday;
								scope.paymentData.end   = yesterday

								break;

                            case 7:

                                scope.paymentData.start = lastYearFirstDay;
                                scope.paymentData.end   = lastYearLastDay

                                break;

                            case 8:

                                scope.paymentData.start = currentYearFirstDay;
                                scope.paymentData.end   = currentYearLastDay

                                break;

                            case 9:

                                scope.paymentData.start = last30Days;
                                scope.paymentData.end   = today

                                break;
						}
					}
				}


				var dtCartOrderColumnsData = [
		            {
		                "name"      : "order_uid",
		                "orderable" : true,
		                "template"  : "#orderPaymentColumnUIDTemplate"
		            },
		            {
		                "name"      : 'txn',
		                "orderable" : true,
		                "template"  : "#orderPaymentTransactionIDTemplate"
		            },
		            {
		                "name"      : 'formattedFee',
		                "orderable" : true,
		                "template"  : "#orderPaymentFeeTemplate"
		            },
		            {
		                "name"      : 'formattedPaymentOn',
		                "orderable" : true,
                        "template"    : "#creationDateColumnTemplate"
		            },
		            {
		                "name"      : 'formattedPaymentMethod',
		                "orderable" : true
		            },
		            {
		                "name"      : 'totalAmount',
		                "orderable" : true,
		                "template"  : "#orderPaymentTotalAmountTemplate"
		            },
                    {
                        "name"      : null,
                        "orderable" : false,
                        "template"  : "#orderPaymentActionTemplate"
                    }
		        ];

		        scope.getPaymentList = function () {

		        	// distroy instance of datatable
			    	if (scope.orderPaymentsListDataTable) {
			            scope.orderPaymentsListDataTable.destroy();
			        }

		        	scope.orderPaymentsListDataTable = __DataStore.dataTable('#managePaymentList', {
		        		url : {
			                    'apiURL'      : 'manage.order.payment.list',
			                    'startDate'   : scope.paymentData.start, // start date
			                    'endDate'	  : scope.paymentData.end,   // end date
		                	},
			            dtOptions   : {
			                "searching" : true,
                            rowCallback : function(row, data, index) {
                            
                                // Highlight sandbox orders 
                                if (data.isTestOrder) {
                                   $(row).addClass('lw-sandbox-order');
                                }
                            }
			            },
			            columnsData : dtCartOrderColumnsData, 
			            scope       : $scope
			            
			        }, null, scope.tableData = function(dataTableCollection) {

						// Check table status
			        	scope.tableStatus = dataTableCollection.data;

			        	// Get report duration key value array
            			scope.paymentDuration 	= __globals.generateKeyValueItems(dataTableCollection._options.duration);

			        	// Excel download URL
			        	scope.excelDownloadURL = dataTableCollection._options.excelDownloadURL;

                        scope.isEmptySanboxRecords = dataTableCollection._options.isEmptySanboxRecords;

			        });
		        }

		        scope.getPaymentList();

		/**
          * list dialog
          *
          * @param number orderID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.orderDetailsDialog = function(orderID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.order.details.dialog',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                        
                        var requestData = responseData.data;
                        
                        appServices.showDialog({
                           'orderDetails'    : requestData.orderDetails
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'order.manage.details'
                            )
                        },
                        function(promiseObj) {

                        	// Check if order updated
                            if (_.has(promiseObj.value, 'order_updated') 
                                && promiseObj.value.order_updated === true) {
                                scope.reloadDT();
                            }

                        });

                    });    

                });

        };


		/**
          * payment detail dialog
          * 
          * @param {number} orderID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.paymentDetailsDialog = function (orderPaymentID) {

        	__DataStore.fetch({
                'apiURL'    		: 'manage.order.payment.detail.dialog',
                'orderPaymentID'   	:  orderPaymentID
            })
            .success(function(responseData) {

                scope.orderPaymentData = responseData.data;
                
                appServices.processResponse(responseData, null, function() {
                	// show payment detail dialog
                    appServices.showDialog(scope.orderPaymentData,
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.payment-details-dialog'
                        )
                    },
                    function(promiseObj) {

                    });

                });    

            });

        };

        /**
          * Delete SandBox Order 
          *
          * @param number paymentId
          * @param string orderUid
          * @param bool isTestOrder
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.deleteSandBoxOrder = function(paymentId, orderUid, isTestOrder) {

            if (isTestOrder == 2) {

                appServices.showDialog(
                {
                    paymentId   : paymentId,
                    orderUid    : orderUid
                },
                {
                    templateUrl : __globals.getTemplateURL(
                        'order.manage.payment-delete-dialog'
                    )
                },
                function(promiseObj) {

                    // Check if order updated
                    if (_.has(promiseObj.value, 'payment_deleted') 
                        && promiseObj.value.payment_deleted === true) {
                        scope.getPaymentList();
                    }

                });
            }

            if (isTestOrder == 1) {
        
                __globals.showConfirmation({
                    text: __globals.getJSString('delete_sandbox_payment_msg'),
                    confirmButtonText  : __globals.getJSString('delete_action_button_text')
                }, function() {

                    __DataStore.post({
                        'apiURL'  :'manage.order.payment.delete.sandbox',
                        'paymentId' : paymentId
                    })
                    .success(function(responseData) {
                    
                        var message = responseData.data.message;
                        appServices.processResponse(responseData, {
                                error : function() {

                                    __globals.showConfirmation({
                                        title   : __globals.getJSString('confirm_error_title'),
                                        text    : message,
                                        type    : 'error'
                                    });

                                }
                            },
                            function() {

                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.getPaymentList();   // reload datatable

                            }
                        );    

                    });

                });

            }

        };
            }
        ])

})( window, window.angular );;
(function() {
'use strict';
    
    /*
     ManageOrderListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderList', [])
        .controller('ManageOrderListController',   [
            '$scope',
            '__DataStore',
            'appServices',
            '$state',
            '__Auth',
            '$rootScope',
            ManageOrderListController 
        ]);

    /**
      * ManageOrderListController for manage product list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $state
      * @inject __Auth
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageOrderListController($scope, __DataStore, appServices, $state, __Auth, $rootScope) {
	 	
    	var scope   	= this;
	 	scope.userInfo 	= __Auth.authInfo().designation;

	 	// get user id from param if exist
    	var userId = null;

    	if (!_.isEmpty($state.params.userID)) {
    		userId = $state.params.userID;
    	}

		var dtCartOrderColumnsData = [
            {
                "name"      : "order_uid",
                "orderable" : true,
                "template"  : "#orderColumnIdTemplate"
            },
            {
                "name"      : 'fname',
                "orderable" : true,
                "template"  : "#userNameColumnIdTemplate"
            },
            {
                "name"      : "status",
                "orderable" : true,
                "template"  : "#orderStatusColumnIdTemplate"
            },
            {
                "name"      : "paymentStatus",
                "orderable" : true,
                "template"  : "#paymentActionColumnTemplate"
            },
            {
                "name"      : "paymentMethod",
                "template"  : "#orderPaymentMethodColumnIdTemplate"
            },
            {
                "name"      : "creation_date",
                "orderable" : true,
                "template"  : "#orderColumnTimeTemplate"
            },
            {
                "name"      : "total_amount",
                "orderable" : true,
                "template"  : "#orderColumnTotalAmountTemplate"
            },
            {
                "name"      : null,
                "template"  : "#orderActionColumnTemplate"
            }
        ],
        tabs    = {
            'active'    : {
                id      : 'activeTabList',
                route   : 'orders.active',
                status  : 1
            },
            'cancelled'    : {
                id      : 'cancelledTabList',
                route   : 'orders.cancelled',
                status  : 3
            },
            'completed'    : {
                id      : 'completedTabList',
                route   : 'orders.complete',
                status  : 6
            }
        };

    	$('#adminOrderList a').click(function (e) {

        	e.preventDefault();
        	
            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];
            if (!_.isEmpty(selectedTab)) {
                $(this).tab('show');
                scope.getOrders(selectedTab.id, selectedTab.status);
            }

    	});
    		

        /**
          * get orders list
          *
          * @param number tableID
          * @param number status
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.getOrders   = function(tableID, status) {

            // destroy if existing instatnce available
            if (scope.cartOrderListDataTable) {
                scope.cartOrderListDataTable.destroy();
            }

            scope.cartOrderListDataTable = __DataStore.dataTable('#'+tableID, {
	            url : {
	    			'apiURL' : 'manage.order.list',
		            'status' : status,
		            'userID?': userId
    			},
	            dtOptions   : {
	                "searching" : true,
	                "order"     : [[ 5, "desc" ]],
	                "columnDefs": [
			            {
			                "targets"	: [1],
			                "visible"	: (scope.userInfo === 1) ? true:false,
			                "searchable": false,

			            }
			        ],
                    rowCallback : function(row, data, index) {
                        
                        // Highlight sandbox orders 
                        if (data.isTestOrder) {
                           $(row).addClass('lw-sandbox-order');
                        }
                    }
	            },
	            columnsData : dtCartOrderColumnsData, 
	            scope       : $scope
	        }, null, scope.userData = function (dataTableCollection){

	        	// get full name of user
	        	scope.userFullName = dataTableCollection._options.userFullName;

                scope.isEmptySanboxRecords = dataTableCollection._options.isEmptySanboxRecords;
	        	
	        	// get title of manage order list
	        	scope.manageOrdersTitle 	= __ngSupport.getText(
						                  __globals.getJSString('manage_order_title'), {
						                        '__name__'     : scope.userFullName,
						                    });

	        });
        };

        _.defer(function(text) {
			if ($state.current.name == 'orders.active') {

	        	var selectedTab = $('.nav li a[href="#active"]');
	        		selectedTab.triggerHandler('click', true);

	        } else if ($state.current.name == 'orders.cancelled') {

	        	var selectedTab = $('.nav li a[href="#cancelled"]');
	        		selectedTab.triggerHandler('click', true);

	        } else if ($state.current.name == 'orders.completed') {
	        	
	        	var selectedTab = $('.nav li a[href="#completed"]');
	        		selectedTab.triggerHandler('click', true);
	        }
			 
		}, 0);
        

      /**
        * When click on active tab so active template open in url
        *
        * @param  $event
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.goToActiveTab = function ($event) {
	        $event.preventDefault();
	        $state.go('orders.active', {'userID' : userId});
	    };

	    /**
        * When click on cancelled tab so cancelled template open in url
        *
        * @param  $event
        *
        * @return void
        *---------------------------------------------------------------- */

	    scope.goToCancelledTab = function ($event) {
	        $event.preventDefault();
	        $state.go('orders.cancelled', {'userID' : userId});
	    };
		
		/**
        * When click on completed tab so completed template open in url
        *
        * @param  $event
        *
        * @return void
        *---------------------------------------------------------------- */

	    scope.goToCompletedTab = function ($event) {
	        $event.preventDefault();
	        $state.go('orders.completed', {'userID' : userId});
	    };
	    
        /*
	     Reload current datatable
	    -------------------------------------------------------------------- */
	    
	    scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.cartOrderListDataTable);
	    };

        /**
          * list dialog
          *
          * @param number orderID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.orderDetailsDialog = function(orderID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.order.details.dialog',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                        
                        var requestData = responseData.data;
                        
                        appServices.showDialog({
                           'orderDetails'    : requestData.orderDetails
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'order.manage.details'
                            )
                        },
                        function(promiseObj) {

                        	// Check if order updated
                            if (_.has(promiseObj.value, 'order_updated') 
                                && promiseObj.value.order_updated === true) {
                                scope.reloadDT();
                            }

                        });

                    });    

                });

        };

        /**
          * log dialog
          *
          * @param number orderID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.logDetailsDialog = function(orderID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.order.log.details.dialog',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                
                appServices.processResponse(responseData, null, function() {

                    var requestData = responseData.data;
                    
                    appServices.showDialog({
                        'order'         : requestData.cartOrder,
                        'orderLog'      : requestData.orderLog
                    },
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.log'
                        )
                    },
                    function(promiseObj) {
					
					});

                });    

            });

        };



        /**
          * update order dialog
          * 
          * @param orderID
          * @param orderUID
          * 
          * @return void
          *---------------------------------------------------------------- */
        scope.updateDialog = function(orderID, orderUID) {

            __DataStore.fetch({
                    'apiURL'    : 'manage.order.update.support.data',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                	
                    var message = responseData.data

                    appServices.processResponse(responseData, null, function() {
                    	
                        appServices.showDialog({
                            'order'   : responseData.data.order,
                            'orderID' : orderID
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'order.manage.update'
                            )
                        },
                        function(promiseObj) {
                        	// Check if order updated		
                        	if (_.has(promiseObj.value, 'order_updated') 
			                    && promiseObj.value.order_updated === true) {

                        		// Get order status and Payment status of order
                        		scope.orderStatus 	= promiseObj.value.updateData.status;
				        		scope.paymentStatus	= promiseObj.value.updateData.paymentStatus;

				        		if (scope.orderStatus == 3 // Cancelled
				        			&& scope.paymentStatus == 2) { // Completed

				        			scope.showConfirmationOnOrderUpdate(orderID, orderUID);
				        		}

				        		scope.reloadDT();
				        	}
                            
                        });

                    });    

                });

        };

        /**
          * Show confirmation on order Update
          * 
          * @param orderID
          * @param orderUID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.showConfirmationOnOrderUpdate = function (orderID, orderUID) {

        	// Show confirmation message on succefful update
        	__globals.showConfirmation({
        	 		title	: __globals.getJSString('order_refund_string'),
	                text 	: __globals.getJSString
	                			('order_refund_change_status_string'),
	                type                : "success",
	                confirmButtonClass  : "btn-success",
					confirmButtonColor :  "#337ab7",
	                confirmButtonText   : __globals.getJSString('order_payment_confirm_text')
	            },
	            function(data) {
	            	// when update successfully then open update dialog
	            	scope.reloadDT();
	            	scope.refundPaymentDialog(orderID, orderUID);
	            }
	        );
        };

        /**
          * Refund order payment
          * 
          * @param orderID
          * @param orderUID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.refundPaymentDialog = function (orderID, orderUID) {

        	__DataStore.fetch({
        		'apiURL'	: 'manage.order.payment.refund.detail.dialog',
        		'orderID'	: orderID
        	})
        	.success(function(responseData) {
        		var message = responseData.data;

        		appServices.processResponse(responseData, null, function() {
        			
        			appServices.showDialog(
        			{
        				orderDetails 		: responseData.data,
        				orderUID 			: orderUID
        			}, 
        			{
        				templateUrl : __globals.getTemplateURL('order.manage.refund-dialog')
        			},
        			function(promiseObj) {
        				// Check if order updated		
                    	if (_.has(promiseObj.value, 'order_updated') 
		                    && promiseObj.value.order_updated === true) {
			        		
			        		scope.reloadDT();
			        	}
        			})
        		})
        	})
        }


        /**
          * payment detail dialog
          * 
          * @param {number} orderID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.paymentDetailsDialog = function (orderPaymentID) {

        	__DataStore.fetch({
                'apiURL'    		: 'manage.order.payment.detail.dialog',
                'orderPaymentID'   	:  orderPaymentID
            })
            .success(function(responseData) {

                scope.orderPaymentData = responseData.data;
                
                appServices.processResponse(responseData, null, function() {
                	// show payment detail dialog
                    appServices.showDialog(scope.orderPaymentData,
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.payment-details-dialog'
                        )
                    },
                    function(promiseObj) {

                    });

                });    

            });

        }

        /**
          * Update payment detail dialog
          * 
          * @param {number} orderID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.updatePaymentDetailsDialog = function (orderID) {
        	
        	__DataStore.fetch({
                'apiURL'    : 'manage.order.payment.update.detail.dialog',
                'orderID'   :  orderID
            })
            .success(function(responseData) {

                scope.orderData = responseData.data;
                
                appServices.processResponse(responseData, null, function() {
                	// show payment detail dialog
                    appServices.showDialog(scope.orderData,
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.update-payment-dialog'
                        )
                    },
                    function(promiseObj) {

                    	// Check if order updated
	                    if (_.has(promiseObj.value, 'order_updated') 
	                        && promiseObj.value.order_updated === true) {

	                    	// Show confirmation message on succefful update
	                    	 __globals.showConfirmation({
	                    	 		title	: __globals.getJSString('order_payment_update_string'),
					                text 	: __globals.getJSString
					                			('order_payment_change_status_string'),
					                type                : "success",
					                confirmButtonClass  : "btn-success",
									confirmButtonColor :  "#337ab7",
					                confirmButtonText   : __globals.getJSString('order_payment_confirm_text')
					            },
					            function(data) {
					            	// when update successfully then open update dialog
					            	scope.reloadDT();
					            	scope.updateDialog(orderID);
					            }
					        );

	                    	scope.reloadDT();
	                    }

                    });

                });    

            });

        };

        /**
          * Contact user dialog
          * 
          * @param {number} orderID
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.contactUserDialog = function (orderID) {

        	__DataStore.fetch({
        		'apiURL' : 'manage.order.get.user.details',
        		'orderID': orderID
        	})
        	.success(function(responseData) {

        		scope.userData = responseData.data;
        		
        		appServices.processResponse(responseData, null, function () {

        			appServices.showDialog(responseData.data,
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.contact-user'
                        )
                    },
                    function(promiseObj) {

                    });
        		});
        	});
        };

        /**
          * Delete SandBox Order 
          *
          * @param number orderId
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.deleteSandBoxOrder = function(orderId, isTestOrder, orderUid) {

            // Check if delete order is not test order
            if (isTestOrder == 2) {
                appServices.showDialog(
                {
                    orderId     : orderId,
                    orderUid    : orderUid
                },
                {
                    templateUrl : __globals.getTemplateURL(
                        'order.manage.order-delete-dialog'
                    )
                },
                function(promiseObj) {

                    // Check if order updated
                    if (_.has(promiseObj.value, 'order_deleted') 
                        && promiseObj.value.order_deleted === true) {
                        scope.reloadDT();
                    }

                });
            }

            // Check if test order delete or not
            if (isTestOrder == 1) {
                
                __globals.showConfirmation({
                    text: __globals.getJSString('delete_sandbox_order_msg'),
                    confirmButtonText  : __globals.getJSString('delete_action_button_text')
                }, function() {

                    __DataStore.post({
                        'apiURL'        : 'manage.order.sandbox_order.delete',
                        'orderId'       : orderId,
                        'isTestOrder'   : 1
                    })
                    .success(function(responseData) {
                    
                        var message = responseData.data.message;
                        appServices.processResponse(responseData, {
                                error : function() {

                                    __globals.showConfirmation({
                                        title   : __globals.getJSString('confirm_error_title'),
                                        text    : message,
                                        type    : 'error'
                                    });

                                }
                            },
                            function() {

                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.reloadDT();   // reload datatable

                            }
                        );    

                    });

                });

            }

        };

	 }
})();;
(function() {
'use strict';
    
    /*
     ManageUpdateOrderController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderUpdate', [])
        .controller('ManageUpdateOrderController',   [
            '$scope', 
            '__Form',
            'appServices',
            ManageUpdateOrderController 
        ]);

    /**
      * ManageUpdateOrderController for update order status
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageUpdateOrderController($scope, __Form, appServices) {

        var scope  = this;
			scope  = __Form.setup(scope, 'form_order_update', 'orderData');

			scope.ngDialogData = $scope.ngDialogData.order;
			
           	scope.orderData = {
           		'statusName' 			: scope.ngDialogData.statusName,
           		'currentPaymentStatus'	: scope.ngDialogData.currentPaymentStatus,
           		'checkMail'				: true
           	};
           
            scope.statuses  = scope.ngDialogData.statusCode;

           	scope.updateDialogTitle 	= __ngSupport.getText(
						                  __globals.getJSString('update_order_dialog_title_text'), {
						                        '__name__'     : scope.ngDialogData.name,
						                        '__orderUID__' : scope.ngDialogData.orderUID
						                    });

			scope = __Form.updateModel(scope, scope.orderData);


            /**
		  	  * process update order
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.update = function() {

		 		// post form data
		 		__Form.process({
						'apiURL'   :'manage.order.update',
						'orderID'  : scope.ngDialogData._id 
					}, scope ).success( function( responseData ) {
			      		
					appServices.processResponse(responseData, function(reactionCode) {

		                if (reactionCode === 1) {
		                	// close dialog
		      				$scope.closeThisDialog( { order_updated : true, updateData : scope.ngDialogData } );
		                }

		            });

			    });

		  	};

			/**
		  	  * Close dialog and return promise object
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageCancelOrderController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderCancel', [])
        .controller('ManageCancelOrderController',   [
            '$scope', 
            '__Form',
            'appServices',
            ManageCancelOrderController 
        ]);

    /**
      * ManageCancelOrderController for update order status
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageCancelOrderController($scope, __Form, appServices) {

       var scope  = this;
			scope = __Form.setup(scope, 'form_order_cancel', 'orderData');
			scope.ngDialogData = $scope.ngDialogData;
			scope = __Form.updateModel(scope, scope.orderData);
			
			// Make a object for when order cancelled then show confirmation and show 
			// refund payment dialog to amin
			scope.updateData = {
				approveStatus : scope.orderData,
				paymentStatus : scope.ngDialogData.order.paymentStatus
			};

            /**
		  	  * process cancel order
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.update = function(status) {
				scope.orderData.approveStatus = status;
		 		// post form data
		 		__Form.process({
						'apiURL'   :'manage.order.cancel',
						'orderID'  : scope.ngDialogData.order._id 
					}, scope ).success( function( responseData ) {
						
					appServices.processResponse(responseData, function(reactionCode) {

		                if (reactionCode === 1) {
		                	// close dialog
		      				$scope.closeThisDialog( { order_updated : true, orderStatus : scope.updateData } );
		      				
		                }

		            });

			    });

		  	};

			/**
		  	  * Close dialog and return promise object
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageOrderDialogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderDialogList', [])
        .controller('ManageOrderDialogController',   [
            '$scope', 
            '__Form',
            ManageOrderDialogController 
        ]);

    /**
      * ManageOrderDialogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageOrderDialogController($scope, __Form) {

       var scope   = this;
       
       		scope.discountStatus = false;

	        scope = __Form.setup(scope, 'cart_order_dialog_form', 'cartData', {
	            secured : true
	        });

       		scope.ngDialogData   = $scope.ngDialogData;
                    
            var requestedData 		= scope.ngDialogData.orderDetails.data;
        	
	        scope.billingAddress   	= requestedData.address.billingAddress;
	        scope.shippingAddress   = requestedData.address.shippingAddress;
	        scope.sameAddress   	= requestedData.address.sameAddress;

	        scope.user				= requestedData.user;
	        scope.order				= requestedData.order;
	        scope.orderProducts		= requestedData.orderProducts;
	        scope.coupon			= requestedData.coupon;
	        scope.taxes				= requestedData.taxes;
	        scope.shipping			= requestedData.shipping;

	        scope.pageStatus = true; 

            
        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

    	scope.close = function() {
            $scope.closeThisDialog();
        }

    };

})();;
(function() {
'use strict';
    
    /*
     ManageOrderLogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderLogList', [])
        .controller('ManageOrderLogController',   [
            '$scope', 
            '__Form',
            ManageOrderLogController 
        ]);

    /**
      * ManageOrderLogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageOrderLogController($scope, __Form) {

       var scope   = this;

        scope = __Form.setup(scope, 'cart_order_log_dialog_form', 'cartData', {
            secured : true
        });
            
            scope.ngDialogData   = $scope.ngDialogData;
            scope.order          = scope.ngDialogData.order;
            scope.orderLog       = scope.ngDialogData.orderLog;
            

            
        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.close = function() {
            $scope.closeThisDialog();
        }

    };

})();;
(function() {
'use strict';
    
    /*
     ManagePaymentDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.paymentDetailsDialog', [])
        .controller('ManagePaymentDetailsController',   [
            '$scope', 
            'appServices',
            ManagePaymentDetailsController 
        ]);

    /**
      * ManagePaymentDetailsController for order payment details
      *
      * @inject $scope
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManagePaymentDetailsController($scope, appServices) {

        var scope  = this;

			// get ng-dialog data
			scope.ngDialogData = $scope.ngDialogData;

			// get payment details
			scope.paymentDetail = scope.ngDialogData.orderPaymentDetails;

			/**
		  	  * Raw data dialog
		  	  *
		  	  * @param rawData
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.rawDataDialog = function (rawData) {

				appServices.showDialog(
					{
						rawDetail : rawData
					},
                    {
                        templateUrl : __globals.getTemplateURL(
                            'order.manage.raw-data-dialog'
                        )
                    },
                    function(promiseObj) {

                    });   
			}

			/**
		  	  * Close dialog and return promise object
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageRawDataController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.rawDataDialog', [])
        .controller('ManageRawDataController',   [
            '$scope', 
            'appServices',
            ManageRawDataController 
        ]);

    /**
      * ManageRawDataController for payment order raw data
      *
      * @inject $scope
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageRawDataController($scope, appServices) {

        var scope  = this;

			// get raw data
			scope.ngDialogData  = $scope.ngDialogData;
			scope.rawData = scope.ngDialogData.rawDetail;
			
            // Check if pass variable is object or not 
            scope.isObject = function(rawData) {
                return _.isObject(rawData);
            };
                    
			/**
		  	  * Close dialog and return promise object
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageUpdateOrderPaymentController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderPaymentUpdate', [])
        .controller('ManageUpdateOrderPaymentController',   [
            '$scope', 
            '__Form',
            'appServices',
            ManageUpdateOrderPaymentController 
        ]);

    /**
      * ManageUpdateOrderPaymentController for update order payment detail
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageUpdateOrderPaymentController($scope, __Form, appServices) {

        var scope  = this;
			scope  = __Form.setup(scope, 'form_order_payment_update', 'orderData');
			scope.ngDialogData = $scope.ngDialogData;
			
			// get order details
			scope.orderDetails 		= scope.ngDialogData.orderDetails;

			// get list of payment method
			scope.paymentMethodList = scope.ngDialogData.paymentMethod;

			scope = __Form.updateModel(scope, scope.orderDetails);

            /**
		  	  * process update order
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.update = function() {

		 		// post form data
		 		__Form.process({
						'apiURL'   :'manage.order.payment.update.process',
						'orderID'  : scope.orderDetails.orderID 
					}, scope ).success( function( responseData ) {
			      		
					appServices.processResponse(responseData, null, function() {
						$scope.closeThisDialog({order_updated : true});
		            });

			    });

		  	};

			/**
		  	  * Close dialog
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageRefundOrderPaymentController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderPaymentRefund', [])
        .controller('ManageRefundOrderPaymentController',   [
            '$scope', 
            '__Form',
            'appServices',
            ManageRefundOrderPaymentController 
        ]);

    /**
      * ManageRefundOrderPaymentController for refund order payment
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function ManageRefundOrderPaymentController($scope, __Form, appServices) {

        var scope  = this;
			scope  = __Form.setup(scope, 'form_order_payment_refund', 'orderData');
			
			// get order payment data for refund detail dialog
			scope.ngDialogData   	 = $scope.ngDialogData;
			
			scope.paymentDetails 	 = scope.ngDialogData.orderDetails.orderPaymentDetails;
			scope.paymentMethodList  = scope.ngDialogData.orderDetails.paymentMethodList;
			scope.paymentDetails.orderUID = scope.ngDialogData.orderUID;
			
			scope = __Form.updateModel(scope, scope.paymentDetails);

            /**
		  	  * process update order
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.update = function() {

		 		// post form data
		 		__Form.process({
						'apiURL'   :'manage.order.payment.refund.process',
						'orderID'  : scope.paymentDetails.orderID 
					}, scope)
		 		.success( function( responseData ) {
			      		
					appServices.processResponse(responseData, null, function() {
						$scope.closeThisDialog({order_updated : true});
		            });

			    });

		  	};

			/**
		  	  * Close dialog
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
	  	  	scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageContactUserController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderContact', [])
        .controller('ManageContactUserController',   [
            '__Form', 
            'appServices',
            '$scope',
            ManageContactUserController 
        ]);

    /**
      * ManageContactUserController handle register form & send request to server
      * to submit form data. 
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageContactUserController(__Form, appServices, $scope) {

        var scope   = this;
       
        scope = __Form.setup(scope, 'manage_contact_form', 'userData');
        // get ng dialog data
        scope.ngDialogData = $scope.ngDialogData;
      	// get user information and order UID
        scope.userData.fullName 	= scope.ngDialogData.fullname;
        scope.userData.email	   	= scope.ngDialogData.email;
        scope.userData.id 			= scope.ngDialogData.id;
        scope.userData.orderUID 	= scope.ngDialogData.orderUID;

        scope   = __Form.updateModel(scope, scope.userData)

        /**
          * Submit register form action
          *
          * @return void
          *---------------------------------------------------------------- */
        
        scope.submit = function() {

        	 __Form.process('manage.order.user.contact', scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {
                	
                	$scope.closeThisDialog();
                });    

            });

        };

        /**
  	  	* Close dialog
  	  	*
  	  	* @return void
  	  	*---------------------------------------------------------------- */
		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

    };

})();;
(function() {
'use strict';
    
    /*
     ManageOrderDeleteController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.orderDelete', [])
        .controller('ManageOrderDeleteController',   [
            '$scope',
            '__Form',
            'appServices',
            '$state',
            '__Auth',
            '$rootScope',
            ManageOrderDeleteController 
        ]);

    /**
      * ManageOrderDeleteController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $state
      * @inject __Auth
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManageOrderDeleteController($scope, __Form, appServices, $state, __Auth, $rootScope) {
        
        var scope       = this;

        scope = __Form.setup(scope, 'form_manage_order_delete', 'orderData', {
            secured : true
        });

        scope.ngDialogData  = $scope.ngDialogData;
        scope.orderUid      = scope.ngDialogData.orderUid;
        
        /**
          * Submit delete action
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.submit = function() {

            __Form.process({
                    'apiURL'      : 'manage.order.delete',
                    'orderId'     : scope.ngDialogData.orderId
                }, scope).success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {

                    // close dialog
                    $scope.closeThisDialog({order_deleted : true});
                    

                });

            });
        };

        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
            $scope.closeThisDialog();
        }
    }

})();;
(function() {
'use strict';
    
    /*
     ManagePaymentDeleteController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.paymentDelete', [])
        .controller('ManagePaymentDeleteController',   [
            '$scope',
            '__Form',
            'appServices',
            '$state',
            '__Auth',
            '$rootScope',
            ManagePaymentDeleteController 
        ]);

    /**
      * ManagePaymentDeleteController for delete payments
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * @inject $state
      * @inject __Auth
      * 
      * @return void
      *-------------------------------------------------------- */

    function ManagePaymentDeleteController($scope, __Form, appServices, $state, __Auth, $rootScope) {
        
        var scope       = this;

        scope = __Form.setup(scope, 'form_manage_payment_delete', 'paymentData', {
            secured : true
        });

        scope.ngDialogData  = $scope.ngDialogData;
        scope.orderUid      = scope.ngDialogData.orderUid;
        
        /**
          * Submit delete action
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.submit = function() {

            __Form.process({
                    'apiURL'      : 'manage.order.payment.delete',
                    'paymentId'   : scope.ngDialogData.paymentId
                }, scope).success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {

                    // close dialog
                    $scope.closeThisDialog({payment_deleted : true});
                    

                });

            });
        };

        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function() {
            $scope.closeThisDialog();
        }
    }

})();;
(function() {
'use strict';
	
	/*
	 DashboardController
	-------------------------------------------------------------------------- */
	
	angular
        .module('DashboardApp.main', [])
        .controller('DashboardController', 	[ 
            '$scope',
            'appServices',
            '__DataStore',
            '$rootScope',
            '__Form',
            DashboardController 
	 	]);

	/**
	 * DashboardController for admin 
	 *
	 * @inject $scope
	 * @inject appServices
	 * @inject __DataStore
	 * @inject $rootScope
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	 function DashboardController($scope, appServices, __DataStore, $rootScope, __Form) {

	 	var scope 	= this,
        productPieChart = null,
        orderPieChart = null,
        brandPieChart = null;

		scope.pageStatus = false;
        scope.duration = 5; // current year

        scope = __Form.setup(scope, 'dashboard_form', 'dashboardFilterData');

        // set date
        scope.monthFirstDay       = moment().startOf('month')
                                    .format('YYYY-MM-DD');

        scope.monthLastDay        = moment().endOf('month')
                                    .format('YYYY-MM-DD');


        scope.lastMonthFirstDay   = moment().subtract(1, 'months')
                                    .startOf('month')
                                    .format('YYYY-MM-DD');

        scope.lastMonthLastDay    = moment().subtract(1, 'months')
                                    .endOf('month')
                                    .format('YYYY-MM-DD');
            
        scope.currentWeekFirstDay = moment().startOf('week')
                                            .format('YYYY-MM-DD');

        scope.currentWeekLastDay  = moment().endOf('week')
                                            .format('YYYY-MM-DD');


        scope.lastWeekFirstDay    = moment().weekday(-7)
                                            .format('YYYY-MM-DD');

        scope.lastWeekLastDay     = moment().weekday(-1)
                                            .format('YYYY-MM-DD');

        scope.today               = moment().format('YYYY-MM-DD');

        scope.yesterday           = moment().subtract(1, 'day')
                                            .format('YYYY-MM-DD');

        scope.lastYearFirstDay    = moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD');

        scope.lastYearLastDay     = moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD');

        scope.currentYearFirstDay = moment().startOf('year').format('YYYY-MM-DD');

        scope.currentYearLastDay  = moment().endOf('year').format('YYYY-MM-DD');

        scope.last30Days          = moment().subtract(30, 'day').format('YYYY-MM-DD');

        // date and time
        var today = moment().format('YYYY-MM-DD');

        scope.dashboardFilterData.start = today;
        scope.dashboardFilterData.end   = today;


        /**
            * get date and time according to duration 
            *
            * @param duration
            *
            *---------------------------------------------------------------- */
        scope.durationChange = function (duration) {
            
            if (duration == 1) { // current month

                scope.dashboardFilterData.start      = scope.monthFirstDay;
                scope.dashboardFilterData.end   = scope.monthLastDay;
                      
            }  else if (duration == 2) { // current week

                scope.dashboardFilterData.start   = scope.currentWeekFirstDay;
                scope.dashboardFilterData.end   = scope.currentWeekLastDay;

            } else if (duration == 3) { // today

                scope.dashboardFilterData.start   = scope.today;
                scope.dashboardFilterData.end   = scope.today;

            } else if (duration == 4) { // last year

                scope.dashboardFilterData.start   = scope.lastYearFirstDay;
                scope.dashboardFilterData.end   = scope.lastYearLastDay;

            } else if (duration == 5) { // current year

                scope.dashboardFilterData.start   = scope.currentYearFirstDay;
                scope.dashboardFilterData.end   = scope.currentYearLastDay;

            } else if (duration == 6) { // last 30 days

                scope.dashboardFilterData.start   = scope.last30Days;
                scope.dashboardFilterData.end   = scope.today;

            }
        }
        scope.durationChange(scope.duration);

        //get dashboard data as per duration
        scope.getDashboardData = function() {
            if (!_.isEmpty(orderPieChart)) {
               orderPieChart.destroy();
            }
            
            __DataStore.fetch({
                'apiURL'      : 'manage.dashboard.count_support_data',
                'startDate'   : scope.dashboardFilterData.start, // start date
                'endDate'     : scope.dashboardFilterData.end,   // end date
                'durationType' : scope.duration
            }, {
                fresh : true
            })
            .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {

                    var requestData = responseData.data.dashboard;
                
                    scope.orders = requestData.orders;
                    scope.latestProductsRating = requestData.latestProductsRating;
                    scope.latestSaleProducts = requestData.latestSaleProducts;
                    scope.totalCategories = requestData.totalCategories;
                    scope.dashboardDuration = __globals.generateKeyValueItems(requestData.duration);
                    scope.orderChartData = scope.orders.orderChartData;
                    scope.currentCoupons = requestData.currentCoupans.currentActiveCoupan;
                  
                    scope.products = requestData.products;
                    scope.brands = requestData.brands;
                    
                    if ($rootScope.canAccess('manage.product.list')) {
                        //create product report pie chart start
                        var productChart = document.getElementById("lw-product-chart");
                      
                        // var myPieChart = new Chart(orderPayment,{
                        productPieChart = appServices.createBarChart({
                            'elementId' : productChart,
                            'type' : 'pie'
                        },
                        {
                            'data': scope.products.productChartData,
                            'labels' : scope.products.productChartLabels
                        },
                        {
                            'options' : {
                                responsive: true,
                                legend: {
                                    onClick: function (e) {
                                        e.stopPropagation();
                                    }
                                }
                            }
                        });
                        //create product report pie chart end
                    }
                    
                    if ($rootScope.canAccess('manage.brand.list')) {
                        //create brand report pie chart start
                        var brandChart = document.getElementById("lw-brand-chart");
                  
                        // var myPieChart = new Chart(orderPayment,{
                        brandPieChart = appServices.createBarChart({
                            'elementId' : brandChart,
                            'type' : 'pie'
                        },
                        {
                            'data': scope.brands.brandChartData,
                            'labels' : scope.brands.brandChartLabels
                        },
                        {
                            'options' : {
                                responsive: true,
                                legend: {
                                    onClick: function (e) {
                                        e.stopPropagation();
                                    }
                                }
                            }
                        });
                        //create brand report pie chart end
                    }
                     
                    if ($rootScope.canAccess('manage.order.list')) {
                         //create order report pie chart start
                        var orderChart = document.getElementById("lw-order-chart");
                  
                        // var myPieChart = new Chart(orderPayment,{
                        orderPieChart = appServices.createBarChart({
                            'elementId' : orderChart,
                            'type' : 'pie'
                        },
                        {
                            'data': scope.orderChartData.orderData,
                            'labels' : scope.orderChartData.orderStatusLabel
                        },
                        {
                            'options' : {
                                responsive: true,
                                legend: {
                                    onClick: function (e) {
                                        e.stopPropagation();
                                    }
                                }
                            }
                        });
                        //create order report pie chart end
                    }
                    
                });

            });
        };
        scope.getDashboardData();
        //get dashboard data as per duration
	};

})();;
(function() {
'use strict';
	
	/*
	Coupon related controllers
	-------------------------------------------------------------------------- */
	
	angular.module('ManageApp.coupon', 		[]).
		controller('CouponListController', [
			'$scope', '__DataStore', 'appServices','$state', CouponListController
		]).controller('CouponDetailDialogController', [
			'$scope', '__DataStore', 'appServices','$state', CouponDetailDialogController
		]).controller('CouponAddController', [
			'$scope', '__Form','appServices', '$state', '__DataStore', CouponAddController
		]).controller('CouponEditController', [
			'$scope', '__Form','appServices', '$state', '__DataStore', CouponEditController
		]);

    /**
	 * CouponListController for get list of coupon & manage it.
	 *
	 * @inject $scope
	 * @inject __DataStore
	 * @inject appServices
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function CouponListController($scope, __DataStore, appServices, $state) {

		var scope   = this,
		dtProductsColumnsData = [
            {
                "name"      : "title",
                "orderable"  : true,
                "template"	: "#titleColumnTemplate"
            },
            {
                "name"      : "code",
                "orderable"  : true
            },
            {
                "name"      : "start_date",
                "orderable" : true,
                "template"	: "#startDateColumnTemplate"
            },
            {
                "name"      : "end_date",
                "orderable" : true,
                "template"  : "#endDateColumnTemplate"
            },
            {
                "name"      : "status",
                "orderable" :  true,
                "template"  : "#statusColumnTemplate"
            },
            {
                "name"      : null,
                "template"  : "#columnActionTemplate"
            }
        ],
        tabs    = {
            'current'    : {
                id      : 'currentCoupon',
                route   : 'coupons.current',
                status  : 1
            },
            'expired' : {
                id      : 'expiredCoupon',
                route   : 'coupons.expired',
                status  : 2
            },
            'upcoming' : {
                id      : 'upcomingCoupon',
                route   : 'coupons.upcoming',
                status  : 3
            }
        };

        $('#manageCouponList a').click(function (e) {

        	e.preventDefault();
        	
            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];
            if (!_.isEmpty(selectedTab)) {
                $(this).tab('show');
                scope.getCouponsList(selectedTab.id, selectedTab.status);
            }

    	});

     /**
        * get coupon data base on status
        *
        * @param string tableID
        * @param int status
        * @return void
        *---------------------------------------------------------------- */
        
        scope.getCouponsList  =  function(tableID, status) 
        { 
        	// distroy instance of datatable
	    	if (scope.couponsListDataTable) {
	            scope.couponsListDataTable.destroy();
	        }
        	scope.couponsListDataTable = __DataStore.dataTable('#'+tableID, {
	            url         : {
	            	'apiURL' : "manage.coupon.list",
	            	'status' : status
	            },
	            dtOptions   : {
	                "searching": true,
	                "order": [[ 1, "asc" ]]
	            },
	            columnsData : dtProductsColumnsData, 
	            scope       : $scope
        	});
        }; 

        _.defer(function(text) {
			if ($state.current.name == 'coupons.current') {

	        	var selectedTab = $('.nav li a[href="#current"]');
	        		selectedTab.triggerHandler('click', true);

	        } else if ($state.current.name == 'coupons.expired') {

	        	var selectedTab = $('.nav li a[href="#expired"]');
	        		selectedTab.triggerHandler('click', true);

	        } else if ($state.current.name == 'coupons.upcoming') {
	        	
	        	var selectedTab = $('.nav li a[href="#upcoming"]');
	        		selectedTab.triggerHandler('click', true);
	        }
			 
		}, 0);
		

		 /**
        * When click on tab so template open in url
        *
        * @param  $event
        * @param  url
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.tabClick = function ($event, url) {
	        $event.preventDefault();
	        $state.go(url);
	    };
	    
	    /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (couponID) {

	    	__DataStore.fetch({
	        	'apiURL'	: 'manage.coupon.detailSupportData',
	        	'couponID'	: couponID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

    	   		appServices.processResponse(responseData, null, function() {

			    	appServices.showDialog(requestData,
			        {	
			            templateUrl : __globals.getTemplateURL(
			                    'coupon.manage.detail-dialog'
			                )
			        },
			        function(promiseObj) {

			        });
			    });
	       });
	    }

	   

        /**
	      * Get datatable source data.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
        scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.couponsListDataTable);
	    };

	    /**
	      * delete coupon.
	      *
	      * @param couponID
	      * @param couponName
	      *
	      * @return void
	      *---------------------------------------------------------------- */
        scope.delete = function(couponID, couponName) {
	    	__globals.showConfirmation({
                text                : __ngSupport.getText(
                    __globals.getJSString('coupon_delete_text'), {
                        '__name__'     : unescape(couponName)
                    }
                ),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL' 	: 'manage.coupon.delete',
                    'couponID' 	: couponID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                    	
                            error : function(data) {
                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function(data) {
                        	
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }
                    );    

                });

            });
	    };


	    /**
	    * open 
	    *
	    * @param object param1 type 
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
	    scope.openCouponDialog  =  function() 
	    { 	
	    	__DataStore.fetch('manage.coupon.fetch.couponDiscountType')
    	   		.success(function(responseData) {

    	   		var requestData = responseData.data;
    	   		
			    appServices.showDialog(requestData,
		        {	
		            templateUrl : __globals.getTemplateURL(
		                    'coupon.manage.add-dialog'
		                )
		        },
		        function(promiseObj) {

		            // Check if brand added
		            if (_.has(promiseObj.value, 'coupon_added') 
		                && promiseObj.value.coupon_added === true) {
		            	scope.reloadDT();   // reload datatable
		            }
		        });
		    });
	    };

	    /**
	    * edit coupon edit dialog
	    *
	    * @param int couponID
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
	    scope.openEditCouponDialog  =  function(couponID) 
	    { 
	    	 __DataStore.fetch({
	        	'apiURL'	: 'manage.coupon.editSupportData',
	        	'couponID'	: couponID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

    	   		requestData.start = moment(requestData.start)
    	   							.format('YYYY-MM-DD HH:mm:ss');

    	   		requestData.end = moment(requestData.end)
    	   							.format('YYYY-MM-DD HH:mm:ss');

	        	appServices.showDialog(requestData,
	            {
	                templateUrl : __globals.getTemplateURL(
	                        'coupon.manage.edit-dialog'
	                    )
	            },
	            function(promiseObj) {

	            	 // Check if coupon updated
	                if (_.has(promiseObj.value, 'coupon_updated') 
	                    && promiseObj.value.coupon_updated === true) {
	                	
	                	scope.reloadDT();   // reload datatable
	                }
	               
	            });
	     	});	
	    };

	};

	 /**
	   * CouponDetailDialogController for get detail dialog.
	   *
	   * @inject $scope
	   * @inject __DataStore
	   * @inject appServices
	   * @inject state
	   *
	   * @return void
	   *-------------------------------------------------------- */
	    function CouponDetailDialogController($scope, __DataStore, appServices, state) {

	    	var scope   = this;
	    	scope.ngDialogData   = $scope.ngDialogData;
	    	scope.couponDate = scope.ngDialogData;


	     /**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
			scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

		};

	/**
	 * CouponAddntroller for get dialog for add coupon & manage it.
	 *
	 * @inject $scope
	 * @inject __Form
	 * @inject appServices
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function CouponAddController($scope, __Form, appServices, $state, __DataStore) {

		var scope   = this;
		scope.ngDialogData   = $scope.ngDialogData;
        scope.productCouponData = [];
        scope.couponDetails = [];
        scope.product_select_config = __globals.getSelectizeOptions({
            maxItems        : 1000,
            searchField     : ['name', 'id'],
            plugins         : ['remove_button']
        });
		
		scope = __Form.setup(scope, 'manage_coupon_add', 'couponData',
					        { 
					            secured : false
					        });
		
		scope.discountType 				= scope.ngDialogData.discountType;
        scope.productDiscountTypes      = scope.ngDialogData.productDiscountTypes;
        scope.products                  = scope.ngDialogData.products;
		scope.couponData.amountSymbol 	= scope.ngDialogData.currencySymbol;
		scope.couponData.currency 		= scope.ngDialogData.currency;
		scope.couponData.discount_type 	= 2;
		scope.couponData.active 		= true;
		
		var today = moment().format('YYYY-MM-DD HH:mm:ss');
        scope.couponData.getClientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

		scope.couponData.start = today;
		scope.couponData.end   = today;

		scope.startDateConfig = {
			minDate         : moment().format('YYYY-MM-DD HH:mm:ss'),
		};

        scope.endDateConfig = {
            minDate         : moment().format('YYYY-MM-DD HH:mm:ss'),
            clearButton     : true
        };

		$scope.$watch('couponAddCtrl.couponData.start', 
                function(currentValue, oldValue) {

                var $element = angular.element('#end');
               
                // Check if currentValue exist
                if (_.isEmpty(currentValue)) {
      
                    $element.bootstrapMaterialDatePicker('setMinDate', today);

                } else {

                    $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
                }

        });

        scope.formatDateTime = function(dateTime) {
            return moment(dateTime).format('Do MMMM YYYY h:mm:ss a');
        };

        /**
          * Set Number Field Validation
          *
          * @param number number
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.setNumberFieldValidation = function(number) {

            var number = (number) ? number.toString() : '';
            if (_.includes(number, '.')) {
                scope.manage_coupon_add['uses_per_user'].$setValidity('number', false);
            } else {
                scope.manage_coupon_add['uses_per_user'].$setValidity('number', true);
            }
        };

        /**
          * Clear text field
          *
          * @param number fieldNo
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.clearTextField = function(fieldNo) {
            if (fieldNo == 1) {
                scope.couponData.uses_per_user = '';
            } else {
                scope.couponData.code = '';
                scope.couponData.uses_per_user = '';
                if (scope.couponData.user_per_usage == true) {
                   _.defer(function(){
                        $('.lw-switchery-change').trigger('click');
                    }); 
                }                
            }
        };

	    /**
		  * Call when start date updated
		  *
		  * @param date date
		  *
		  * @return void
		  *---------------------------------------------------------------- */
		scope.endDateUpdated = function(date) {
	
			if (scope.couponData.start > scope.couponData.end) { 
				scope.couponData.end = date;
			}      
		};

	    /**
		  * submit add form.
		  *
		  *
		  * @return void
		  *-------------------------------------------------------- */

		scope.submit = function() {

		    __Form.process('manage.coupon.add', scope)
                .success(function(responseData) {
                appServices.processResponse(responseData, null, function(reactionCode) {
                    $scope.closeThisDialog( { coupon_added : true } );               
                });    
            });
		}

		/**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};
	};


	/**
	 * CouponEditController for get dialog for add coupon & manage it.
	 *
	 * @inject $scope
	 * @inject __Form
	 * @inject appServices
	 * @inject $state
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function CouponEditController($scope, __Form, appServices, $state, __DataStore) {

		var scope   = this,
        	ngDialogData = $scope.ngDialogData;
            scope.productCouponData = [];
            scope.couponDetails = [];

            scope.product_select_config = __globals.getSelectizeOptions({
                maxItems        : 1000,
                searchField     : ['name', 'id'],
                plugins         : ['remove_button']
            });
                
       		scope.updateURL = {
				'apiURL'   :'manage.coupon.edit.process',
				'couponID' : ngDialogData.couponData._id
			};


		scope = __Form.setup(scope, 'manage_coupon_edit', 'couponData');
		
		scope   = __Form.updateModel(scope, $scope.ngDialogData.couponData);

        scope.productDiscountTypes      = ngDialogData.configItems.productDiscountTypes;
        scope.products                  = ngDialogData.configItems.products;
		scope.discountType 				= ngDialogData.configItems.discountType;
		scope.couponData.amountSymbol 	= ngDialogData.configItems.currencySymbol;
		scope.couponData.currency 		= ngDialogData.configItems.currency;
		
		scope.startDateConfig = {
			minDate : moment().format('YYYY-MM-DD HH:mm:ss'),
		};

        scope.endDateConfig = {
            minDate : moment().format('YYYY-MM-DD HH:mm:ss'),
            clearButton     : true
        };

        scope.couponData.getClientTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

		$scope.$watch('couponEditCtrl.couponData.end', 
                function(currentValue, oldValue) {
				
                var $element = angular.element('#end');
                
                // Check if currentValue exist
                if (_.isEmpty(currentValue)) {
      
                    $element.bootstrapMaterialDatePicker('setMinDate', scope.couponData.start);

                } else {

                    $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
                }

			//$element.bootstrapMaterialDatePicker('setMinDate', currentValue);

        });

		/**
		* Call when start date updated
		*
		* @param date date
		*
		* @return void
		*---------------------------------------------------------------- */
		scope.endDateUpdated = function(date) {
		
			if (scope.couponData.start > scope.couponData.end) { 
				scope.couponData.end = date;
			}

            if (_.isEmpty(scope.couponData.end)) {
                var $element = angular.element('#end');
                $element.bootstrapMaterialDatePicker('setMinDate', scope.couponData.start);
            }
		};

        /**
          * Clear text field
          *
          * @param number fieldNo
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.clearTextField = function(fieldNo) {
            if (fieldNo == 1) {
                scope.couponData.uses_per_user = '';
            } else {
                scope.couponData.code = '';
                scope.couponData.uses_per_user = '';
                if (scope.couponData.user_per_usage == true) {
                   _.defer(function(){
                        $('.lw-switchery-change').trigger('click');
                    }); 
                }  
            }
        };

        scope.formatDateTime = function(dateTime) {
            return moment(dateTime).format('Do MMMM YYYY h:mm:ss a');
        };


		/**
		* submit coupon update form.
		*
		*
		* @return void
		*---------------------------------------------------------------- */
		scope.submit = function() {

		    __Form.process(scope.updateURL, scope)
                .success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { coupon_updated : true } );
                });    

            });
		}

		/**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};
	};

})();;
(function() {
'use strict';
	
	/*
	  Manage shipping component related controllers
	  ----------------------------------------------------------------------- */
	
	angular.module('ManageApp.shipping', 		[])

        .controller('ShippingListController', [
			'$scope',
            '__DataStore',
            'appServices',
            '__Form',
            ShippingListController
		]).controller('ShippingDetailController', [
			'$scope', 
			'__DataStore', 
			'appServices',
			'$state', 
			ShippingDetailController
        ]).controller('ShippingAddDialogController', [
			'$scope',
            '__DataStore',
            'appServices',
            '$state',
            ShippingAddDialogController
		])
        .controller('ShippingAddController', [
			'$scope',
            '__Form',
            'appServices',
            '$state',
            ShippingAddController
		])
        .controller('ShippingEditDialogController', [
			'$scope',
            '__DataStore',
            'appServices',
            '$state',
            ShippingEditDialogController
		])
        .controller('ShippingEditController', [
			'$scope',
            '__Form',
            'appServices',
            '$state',
            ShippingEditController
		]);

    /**
	  * ShippingListController - for get list of shipping rules & manage it.
	  *
	  * @inject $scope
	  * @inject __DataStore
	  * @inject appServices
	  * @inject __Form
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function ShippingListController($scope, __DataStore, appServices, __Form) {

		var scope                 = this,
		    dtShippingColumnsData = [
                {
                    "name"      : "name",
                    "orderable"  : true,
                    "template"	: "#countryColumnTemplate"
                },
                {
                    "name"      : "type",
                    "orderable"  : true,
                    "template"	: "#typeColumnTemplate"
                },
                {
                    "name"      : "charges",
                    "orderable" : true,
                    "template"  : "#chargesColumnTemplate"
                },
                {
                    "name"      : "shippingTypeTitle",
                    "orderable" : true,
                },
                {
                    "name"      : "creation_date",
                    "orderable" : true,
                    "template"	: "#creationDateColumnTemplate"
                },
                {
	                "name"      : "status",
	                "orderable" :  true,
	                "template"  : "#statusColumnTemplate"
	            },
	            {
	                "name"      : null,
	                "template"  : "#columnActionTemplate"
	            }
            ],
            tabs    = {
                'specificCountry'    : {
	                    id      : 'manageShippingList'
                },
                'allOtherCountries'    : {
                       id      : 'allOtherCountriesTabList'
                }
            };

        // Fired when clicking on tab    
        $('#manageShippingTab a').click(function (e) {

            e.preventDefault();

            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];

            // Check if selectedTab exist    
            if (!_.isEmpty(selectedTab)) {
                $(this).tab('show')
                scope.getShipping(selectedTab.id);

            }
            
        });

        /**
	  	  * Shipping list according to datatable id 
	  	  *
	  	  * @param tableID
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

        scope.getShipping = function (tableID) {
        	
	        scope.shippingListDataTable = __DataStore.dataTable('#'+tableID, {
	            url            : "manage.shipping.list",
	            dtOptions      : {
	                "searching": true,
	                "order": [[ 1, "asc" ]]
	            },
	            columnsData    : dtShippingColumnsData, 
	            scope          : $scope

	        });
        };

        var selectedTab = $('.nav li a[href="#specificCountry"]');

	    selectedTab.triggerHandler('click', true);

	    /**
	  	  * Reload datatable
	  	  *
	  	  *---------------------------------------------------------------- */

        scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.shippingListDataTable);
	    };


	    /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (shippingID) {

	    	__DataStore.fetch({
	        	'apiURL'		: 'manage.shipping.detailSupportData',
	        	'shippingID'	: shippingID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

    	   		appServices.processResponse(responseData, null, function() {

			    	appServices.showDialog(requestData,
			        {	
			            templateUrl : __globals.getTemplateURL(
			                    'shipping.manage.detail-dialog'
			                )
			        },
			        function(promiseObj) {

			        });
			    });
	       });
	    }

	    /**
	  	  * Delete shipping rule sending http request using __DataStore post
          * function
          *
	  	  * @param int    shippingID
	  	  * @param string country
	  	  *
	  	  * return void
	  	  *---------------------------------------------------------------- */

        scope.delete = function(shippingID, country) {

	    	__globals.showConfirmation({
                text                : __ngSupport.getText(
							        __globals.getJSString('shipping_delete_text'), {
							            '__country__'    : country
							        }
						    	),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            },
            function() {

                __DataStore.post({
                    'apiURL' 		: 'manage.shipping.delete',
                    'shippingID' 	: shippingID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                    	
                        error : function(data) {
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'error'
                            });

                        }

                    },
                    function(data) {
                        	
                        __globals.showConfirmation({
                            title   : __globals.getJSString('confirm_error_title'),
                            text    : message,
                            type    : 'success'
                        });
                        scope.reloadDT();   // reload datatable

                    });    

                });

            });

	    };

        // scope.shipping_type_select_config = __globals.getSelectizeOptions({
        //     valueField  : '_id',
        //     labelField  : 'title',
        //     searchField : [ 'title' ]  
        // });

        // AOC stands for All Other Countries
        // AOC shipping related
        
	    scope 	= __Form.setup(scope, 'manage_aoc_edit', 'shippingData');
		
        __DataStore.fetch('manage.shipping.aoc.editSupportData')
    	   	.success(function(responseData) {

 				appServices.processResponse(responseData, null, function() {

                    var requestData = responseData.data;
                    scope.shippingType    = requestData.configItems.shippingType;
					scope.currencySymbol  = requestData.configItems.storeCurrencySymbol;
					scope.currency        = requestData.configItems.currency;
                  
                    scope   = __Form.updateModel(scope, requestData.shipping);

                }); 
     	});

        
        /**
	  	  * Submit aoc form data
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

        scope.submit = function() {

		    __Form.process('manage.shipping.aoc.update', scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    
                });    

            });

		};

	};

	/**
	   * ShippingDetailController for get detail dialog.
	   *
	   * @inject $scope
	   * @inject __DataStore
	   * @inject appServices
	   * @inject state
	   *
	   * @return void
	   *-------------------------------------------------------- */
	    function ShippingDetailController($scope, __DataStore, appServices, state) {

	    	var scope   = this;
	    	scope.ngDialogData   = $scope.ngDialogData;
	    	scope.currencySymbol = scope.ngDialogData.currencySymbol;
	    	scope.shippingDetail = scope.ngDialogData.shippingData;
	    	
	     /**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
			scope.closeDialog = function() {
	  	  		$scope.closeThisDialog();
	  	  	};

		};

	/**
	  * ShippingAddDialogController - show add shipping rule dialog
	  *
	  * @inject $scope
	  * @inject __DataStore
	  * @inject appServices
	  * @inject $state
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function ShippingAddDialogController($scope, __DataStore, appServices, $state) {

		var scope   = this;

        __DataStore.fetch('manage.shipping.fetch.contries')
    	   	.success(function(responseData) {

    	   		var requestData = responseData.data;
    	   		// show add dialog
	        	appServices.showDialog(requestData,
	            {
	                templateUrl : __globals.getTemplateURL(
	                        'shipping.manage.add-dialog'
	                    )
	            },
	            function(promiseObj) {
                    if (_.has(promiseObj.value, 'goToManageShippingType')) {
                        $state.go('shippingType');
                    }
                    
	            	// Check if shipping added
		            if (_.has(promiseObj.value, 'shipping_added') 
		                && promiseObj.value.shipping_added === true && !_.has(promiseObj.value, 'goToManageShippingType')) {
		            	$scope.$parent.shippingListCtrl.reloadDT();
		            }
                    if (!_.has(promiseObj.value, 'goToManageShippingType')) {
                        $state.go('shippings');
                    }
                    
	            });
     	});

	};

	/**
	  * ShippingAddController - handle scope of add shipping rule dialog
	  *
	  * @inject $scope
	  * @inject __Form
	  * @inject appServices
      * @inject $state
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function ShippingAddController($scope, __Form, appServices, $state) {

		var scope  			= this,
			ngDialogData 	= $scope.ngDialogData;

		scope     = __Form.setup(scope, 'manage_shipping_add', 'shippingData');
		
		scope.countries_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : [ 'text' ]  
        });

        scope.shipping_type_select_config = __globals.getSelectizeOptions({
            valueField  : '_id',
            labelField  : 'title',
            searchField : [ 'title' ]  
        });

        scope.countries 			= ngDialogData.countries;
        scope.shippingTypeList      = ngDialogData.shippingTypeList;
        scope.shippingType 			= ngDialogData.shippingType;
        scope.shippingData.active 	= true;
        scope.shippingData.type 	= 1;
        scope.freeAfterAmount  		= true;
      	scope.charges 		 		= true;
      	scope.amountCap 			= false;
        scope.currencySymbol 		= ngDialogData.currencySymbol;
        scope.currency 				= ngDialogData.currency;

        scope.goToShippingType = function() {
            $scope.closeThisDialog( { 'goToManageShippingType' : true } );
        };


        /**
        * description
        *
        * @param object param1 type 
        *
        * @return void
        *---------------------------------------------------------------- */
        
        scope.onChangeType = function(type) {
	    	
	        var shippingType = ngDialogData.shippingType;
	        
	        if (type == 1) {
	          	// flat.
	          	scope.freeAfterAmount  	= true;
	          	scope.charges 		 	= true;
	          	scope.amountCap 		= false;

	        } else if (type == 2) {
	          	// percentage.
	          	scope.freeAfterAmount  	= false;
	          	scope.charges 		 	= true;
	          	scope.amountCap 		= true;

         	} else if (type == 3 || type == 4) {
         	 // free or Not Shippable
         	 	scope.freeAfterAmount  	= false;
	          	scope.charges 		 	= false;
	          	scope.amountCap 		= false;

         	} 
	    };

        /**
	  	  * add new shiiping
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

        scope.submit = function() {

		    __Form.process('manage.shipping.add', scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { shipping_added : true } );
                });

            });

		};

        /**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

	/**
	  * ShippingEditDialogController - show edit shipping rule dialog
	  *
	  * @inject $scope
	  * @inject __DataStore
	  * @inject appServices
	  * @inject $state
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function ShippingEditDialogController($scope, __DataStore, appServices, $state) {

		var scope   = this;
		
        __DataStore.fetch({
        	'apiURL'		: 'manage.shipping.editSupportData',
        	'shippingID'	: $state.params.shippingID
	    })
    	   .success(function(responseData) {

    	   	appServices.processResponse(responseData,null, function(reactionCode) {

    	   		var requestData = responseData.data;

	        	appServices.showDialog(requestData,
	            {
	                templateUrl : __globals.getTemplateURL(
	                        'shipping.manage.edit-dialog'
	                    )
	            },
	            function(promiseObj) {
	            	if (_.has(promiseObj.value, 'goToManageShippingType')) {
                        $state.go('shippingType');
                    }

	            	 // Check if coupon updated
	                if (_.has(promiseObj.value, 'shipping_updated') 
	                    && promiseObj.value.shipping_updated === true) {
	                	$scope.$parent.shippingListCtrl.reloadDT();
	                }

                    if (!_.has(promiseObj.value, 'goToManageShippingType')) {
                        $state.go('shippings');
                    }
	               
	            });
			});
     	});

	};

	/**
	  * ShippingEditController - handle edit shipping dialog scope
	  *
	  * @inject $scope
	  * @inject __Form
	  * @inject appServices
      * @inject $state
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function ShippingEditController($scope, __Form, appServices, $state) {

		var scope              = this,
        	ngDialogData       = $scope.ngDialogData;
       		scope.updateURL    = {
				'apiURL' :'manage.shipping.edit.process',
				'shippingID' : $state.params.shippingID
			};
		
		scope     = __Form.setup(scope, 'manage_shipping_edit', 'shippingData');

        scope.shipping_type_select_config = __globals.getSelectizeOptions({
            valueField  : '_id',
            labelField  : 'title',
            searchField : [ 'title' ]  
        });

		scope.shippingData = ngDialogData;
        scope.shippingTypeList      = ngDialogData.shippingTypeList;
		scope.discountType      = ngDialogData.shippingType;
        scope.shippingData 		= ngDialogData.shippingData;
        scope.currencySymbol 	= ngDialogData.currencySymbol;
        scope.currency 			= ngDialogData.currency;
        
        scope.goToShippingType = function() {
            $scope.closeThisDialog( { 'goToManageShippingType' : true } );
        };

		scope = __Form.updateModel(scope, scope.shippingData)


		/**
		  * submit edit form data
		  *
		  * @return void
		  *---------------------------------------------------------------- */
		
		scope.submit = function() {

		    __Form.process(scope.updateURL, scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { shipping_updated : true } );
                });    

            });
		}

		/**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

})();;
(function() {
'use strict';
	
	/*
	  Manage tax component related controllers
	  -------------------------------------------------------------------------- */
	
	angular.module('ManageApp.tax', 		[]).
		controller('TaxListController', [
			'$scope',
            '__DataStore',
            'appServices',
            TaxListController
		])

		.controller('TaxDetailController', [
			'$scope', 
			'__DataStore', 
			'appServices',
			TaxDetailController
		])

        .controller('TaxAddDialogController', [
			'$scope',
            '__DataStore',
            'appServices',
            '$state',
            TaxAddDialogController
		])

        .controller('TaxAddController', [
			'$scope',
            '__Form',
            'appServices',
            '$state',
            TaxAddController
		])

        .controller('TaxEditDialogController', [
			'$scope',
            '__DataStore',
            'appServices',
            '$state',
            TaxEditDialogController
		])

        .controller('TaxEditController', [
			'$scope',
            '__Form',
            'appServices',
            '$state',
            TaxEditController
		]);

    /**
	  * TaxListController for get list of tax & manage it.
	  *
	  * @inject $scope
	  * @inject __DataStore
	  * @inject appServices
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function TaxListController($scope, __DataStore, appServices) {

		var scope   = this,
		dttaxColumnsData = [
            {
                "name"      : "label",
                "orderable" : true,
                "template"	: "#labelColumnTemplate"
            },
            {
                "name"      : "name",
                "orderable" : true,
                "template"	: "#countryColumnTemplate"
            },
            {
                "name"      : "type",
                "orderable" : true,
                "template"	: "#typeColumnTemplate"
            },
            {
                "name"      : "applicable_tax",
                "orderable" : true,
                "template"  : "#applicableTaxColumnTemplate"
            },
            {
                "name"      : "creation_date",
                "orderable" : true,
                "template"	: "#creationDateColumnTemplate"
            },
            {
                "name"      : "status",
                "orderable" :  true,
                "template"  : "#statusColumnTemplate"
            },
            {
                "name"      : null,
                "template"  : "#columnActionTemplate"
            }
        ];
		
        scope.taxListDataTable = __DataStore.dataTable('#manageTaxList', {
            url             : "manage.tax.list",
            dtOptions       : {
                "searching"     : true,
                "order"         : [[ 0, "asc" ]]
            },
            columnsData     : dttaxColumnsData, 
            scope           : $scope

        });

        /**
    	  * reload data table
    	  *
    	  *-------------------------------------------------------- */

        scope.reloadDT = function () {
            __DataStore.reloadDT(scope.taxListDataTable);
        };

        /**
	      * Get detail dialog.
	      *
	      * @return void
	      *---------------------------------------------------------------- */
	    scope.detailDialog = function (taxID) {

	    	__DataStore.fetch({
	        	'apiURL' : 'manage.tax.detailSupportData',
	        	'taxID'	 : taxID
	        })
    	   .success(function(responseData) {

    	   		var requestData = responseData.data;

    	   		appServices.processResponse(responseData, null, function() {

			    	appServices.showDialog(requestData,
			        {	
			            templateUrl : __globals.getTemplateURL(
			                    'tax.manage.detail-dialog'
			                )
			        },
			        function(promiseObj) {

			        });
			    });
	       });
	    }

        /**
          * Get detail dialog.
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.taxSettingDialog = function () {

            appServices.showDialog({},
            {   
                templateUrl : __globals.getTemplateURL(
                    'tax.manage.setting-dialog'
                )
            },
            function(promiseObj) {

            });
        }

        /**
    	  * delete tax by sending http request.
    	  *
    	  * @param number taxID
    	  *
    	  * @return void
    	  *-------------------------------------------------------- */

        scope.delete = function(taxID) {

        	__globals.showConfirmation({
                text                : __globals.getJSString('tax_delete_text'),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL' 	: 'manage.tax.delete',
                    'taxID' 	: taxID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                    	
                            error : function(data) {
                                __globals.showConfirmation({
                                    title   : __globals.getJSString('confirm_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function(data) {
                        	
                            __globals.showConfirmation({
                                title   : __globals.getJSString('confirm_error_title'),
                                text    : message,
                                type    : 'success'
                            });
                            scope.reloadDT();   // reload datatable

                        }

                    );    

                });

            });

        }

	};

	/**
     * TaxDetailController for get detail dialog.
     *
     * @inject $scope
     * @inject __DataStore
     * @inject appServices
     *
     * @return void
     *-------------------------------------------------------- */
    function TaxDetailController($scope, __DataStore, appServices) {

    	var scope   = this;
    	scope.ngDialogData   = $scope.ngDialogData;
    	scope.currencySymbol = scope.ngDialogData.currencySymbol;
    	scope.taxData 		 = scope.ngDialogData.taxData;
    	
 	/**
	 * Close dialog
	 *
	 * @return void
	 *---------------------------------------------------------------- */
		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

	/**
	 * TaxAddDialogController - for show add tax dialog with getting its -
     * support dialog data
	 *
	 * @inject $scope
	 * @inject __DataStore
	 * @inject appServices
	 * @inject $state
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function TaxAddDialogController($scope, __DataStore, appServices, $state) {

		var scope   = this;

        __DataStore.fetch('manage.tax.fetch.contries')
    	   	.success(function(responseData) {

    	   		var requestData = responseData.data;

	        	appServices.showDialog(requestData,
	            {
	                templateUrl : __globals.getTemplateURL(
	                        'tax.manage.add-dialog'
	                    )
	            },
	            function(promiseObj) {

	            	// Check if tax added
		            if (_.has(promiseObj.value, 'tax_added') 
		                && promiseObj.value.tax_added == true) {
		            	$scope.$parent.taxListCtrl.reloadDT();
		            }
		            $state.go('taxes');
	               
	            });
     	});

	};

	/**
	 * TaxAddController - handle scope of add dialog & also responsible for -
     * form handling & dialog closing
	 *
	 * @inject $scope
	 * @inject __Form
	 * @inject appServices
	 * @inject $state
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function TaxAddController($scope, __Form, appServices, $state) {

		var scope  			= this,
			ngDialogData 	= $scope.ngDialogData;

		scope 	= __Form.setup(scope, 'manage_tax_add', 'taxData');
		
		scope.countries_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : [ 'text' ]
        });

        scope.countries 		= ngDialogData.countries;
        scope.taxType 			= __globals.generateKeyValueItems(ngDialogData.taxType);
        scope.taxData.active 	= true;
        scope.taxData.type 		= 1;
        scope.currencySymbol 	= ngDialogData.currencySymbol;
        scope.currency 	        = ngDialogData.currency;

        /**
	  	  * Submit form using form service process method
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

        scope.submit = function() {

		    __Form.process('manage.tax.add', scope)
                .success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { tax_added : true } );
                });    

            });
		}


		/**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */

		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

	/**
	 * TaxEditDialogController - Getting edit tax data from server & if tax -
     * exist on server then show tax edit dialog 
	 *
	 * @inject $scope
     * @inject __DataStore
	 * @inject appServices
	 * @inject $state
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function TaxEditDialogController($scope, __DataStore, appServices, $state) {

		var scope   = this;

        __DataStore.fetch({
        	'apiURL'		: 'manage.tax.editSupportData',
        	'taxID'			: $state.params.taxID
	    })
    	   .success(function(responseData) {

    	   	appServices.processResponse(responseData,null, function(reactionCode) {

    	   		var requestData = responseData.data;

	        	appServices.showDialog(requestData,
	            {
	                templateUrl : __globals.getTemplateURL(
	                        'tax.manage.edit-dialog'
	                    )
	            },
	            function(promiseObj) {

	            	// Check if tax updated
	                if (_.has(promiseObj.value, 'tax_updated') 
	                    && promiseObj.value.tax_updated === true) {
	                	
	                	$scope.$parent.taxListCtrl.reloadDT();
	                }
	                
	                $state.go('taxes');
	               
	            });
	        });
     	});

	};

	/**
	 * TaxEditController - handle scope of edit dialog & also responsible for -
     * form handling & dialog closing
	 *
	 * @inject $scope
	 * @inject __Form
	 * @inject appServices
     * @inject $state
	 *
	 * @return void
	 *-------------------------------------------------------- */

	function TaxEditController($scope, __Form, appServices, $state) {

		var scope              = this,
        	ngDialogData       = $scope.ngDialogData;
       		scope.updateURL    = {
				'apiURL' :'manage.tax.edit.process',
				'taxID'  : $state.params.taxID
			};

		scope = __Form.setup(scope, 'manage_tax_edit', 'taxData');

        scope.countries_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : [ 'text' ]  
        });
        
		scope.countries = ngDialogData.countries.data.countries;
		
		scope   = __Form.updateModel(scope, $scope.ngDialogData)

		scope.taxType 			= __globals.generateKeyValueItems(ngDialogData.taxType);
        scope.taxData 			= ngDialogData.taxData;
        scope.currencySymbol 	= ngDialogData.currencySymbol;
        scope.currency 			= ngDialogData.currency;

		/**
		  * Submit edit tax form
		  *
		  * @return void
		  *---------------------------------------------------------------- */
		
		scope.submit = function() {

		    __Form.process(scope.updateURL, scope)
                .success(function(responseData) {

                appServices.processResponse(responseData, null, function() {
                    $scope.closeThisDialog( { tax_updated : true } );
                });    

            });

		}

		/**
	  	  * Close dialog
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
  	  		$scope.closeThisDialog();
  	  	};

	};

})();;
/*!
 *  Engine      : ManageReportEngine 
 *  Component   : Manage/Report
----------------------------------------------------------------------------- */

(function( window, angular, undefined ) {

	'use strict';
	
	/*
	  Manage report Engine
	  -------------------------------------------------------------------------- */
	
	angular.module('ManageApp.report', [])

		/**
    	  * ReportController for list of order
    	  *
    	  * @inject __Utils
    	  * @inject __Form
    	  * @inject $state
    	  * @inject appServices
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('ReportController', [ 
            '__Utils',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            '$scope',
            '__DataStore',
            function (__Utils, __Form , $state, appServices, $rootScope, $scope, __DataStore) {

            	var scope = this,
                orderReportPieChart = null,
                orderPaymentePieChart = null,
                paymentReportPieChart = null,
                currentStateName = $state.current.name;
            	scope = __Form.setup(scope, 'manage_report_list', 'reportData');

            	scope.reportData.status = 1; // new
                scope.reportData.paymentStatus = 2; // new
            	scope.reportData.order 	= 1; // placed
            	scope.duration 			= 8; // current year

            	//scope.statuses 			= __globals.configItem('order_status');
            	
                scope.dateConfig = {
                    'format' : 'YYYY-MM-DD',
                    'time'    : false
                };
                
            	// set date
				scope.monthFirstDay       = moment().startOf('month')
				                            .format('YYYY-MM-DD');

				scope.monthLastDay        = moment().endOf('month')
											.format('YYYY-MM-DD');


				scope.lastMonthFirstDay   = moment().subtract(1, 'months')
				                            .startOf('month')
											.format('YYYY-MM-DD');

				scope.lastMonthLastDay    = moment().subtract(1, 'months')
				                            .endOf('month')
											.format('YYYY-MM-DD');
				    
				scope.currentWeekFirstDay = moment().startOf('week')
													.format('YYYY-MM-DD');

				scope.currentWeekLastDay  = moment().endOf('week')
													.format('YYYY-MM-DD');


				scope.lastWeekFirstDay    = moment().weekday(-7)
													.format('YYYY-MM-DD');

				scope.lastWeekLastDay     = moment().weekday(-1)
													.format('YYYY-MM-DD');

				scope.today               = moment().format('YYYY-MM-DD');

				scope.yesterday           = moment().subtract(1, 'day')
													.format('YYYY-MM-DD');

                scope.lastYearFirstDay    = moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD');

                scope.lastYearLastDay     = moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD');

                scope.currentYearFirstDay = moment().startOf('year').format('YYYY-MM-DD');

                scope.currentYearLastDay  = moment().endOf('year').format('YYYY-MM-DD');

                scope.last30Days          = moment().subtract(30, 'day').format('YYYY-MM-DD');

            	// date and time
            	var today = moment().format('YYYY-MM-DD');

				scope.reportData.start = today;
				scope.reportData.end   = today;

				// scope.startDateConfig = {
				// 	time    : false
				// };

				// scope.endDateConfig = {
				// 	minDate : moment().format('YYYY-MM-DD'),
				// 	time    : false
				// };

				$scope.$watch('reportCtrl.reportData.start', 
	                function(currentValue, oldValue) {

	                var $element = angular.element('#end');
	          
	                // Check if currentValue exist
	                if (_.isEmpty(currentValue)) {
	      
	                    $element.bootstrapMaterialDatePicker('setMinDate', '');

	                } else {

	                    $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
	                }
				});

				/**
				  * Call when start date updated
				  *
				  * @param startDate
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.startDateUpdated = function(startDate) {

					scope.reportData.start = startDate;
                    scope.duration = 10; // Custom
				};

				/**
				  * Call when start date updated
				  *
				  * @param endDate
				  *
				  * @return void
				  *---------------------------------------------------------------- */
				scope.endDateUpdated = function(endDate) {
					
					if (scope.reportData.start > scope.reportData.end) { 
						scope.reportData.end = endDate;
					}
					scope.reportData.end = endDate;
                    scope.duration = 10; // Custom
				};


				/**
			  	  * get date and time according to duration 
			  	  *
			  	  * @param duration
			  	  *
			  	  *---------------------------------------------------------------- */
		        scope.durationChange = function (duration) {
		        	
		        	if (duration == 1) { // current month

		        		scope.reportData.start 	 = scope.monthFirstDay;
		        		scope.reportData.end   = scope.monthLastDay;
		        		      
		        	} else if (duration == 2) { // last month

		        		scope.reportData.start   = scope.lastMonthFirstDay;
		        		scope.reportData.end   = scope.lastMonthLastDay;

		        	} else if (duration == 3) { // current week

		        		scope.reportData.start   = scope.currentWeekFirstDay;
		        		scope.reportData.end   = scope.currentWeekLastDay;

		        	} else if (duration == 4) { // last week

		        		scope.reportData.start   = scope.lastWeekFirstDay;
		        		scope.reportData.end   = scope.lastWeekLastDay;

		        	} else if (duration == 5) { // today

		        		scope.reportData.start   = scope.today;
		        		scope.reportData.end   = scope.today;

		        	} else if (duration == 6) { // yesterday

		        		scope.reportData.start   = scope.yesterday;
		        		scope.reportData.end   = scope.yesterday;

		        	} else if (duration == 7) { // last year

                        scope.reportData.start   = scope.lastYearFirstDay;
                        scope.reportData.end   = scope.lastYearLastDay;

                    } else if (duration == 8) { // current year

                        scope.reportData.start   = scope.currentYearFirstDay;
                        scope.reportData.end   = scope.currentYearLastDay;

                    } else if (duration == 9) { // last 30 days

                        scope.reportData.start   = scope.last30Days;
                        scope.reportData.end   = scope.today;

                    }
		        }

                scope.durationChange(scope.duration);


				var dtReportColumnsData = [
		            {
		                "name"      : "order_uid",
		                "orderable" : true,
		                "template"  : "#orderColumnIdTemplate"
		            },
		            {
		                "name"      : 'fname',
		                "orderable" : true,
		                "template"  : "#userNameColumnIdTemplate"
		            },
		            {
		                "name"      : "status",
		                "orderable" : true,
		                "template"  : "#orderStatusColumnIdTemplate"
		            },
                    {
                        "name"      : "formated_payment_status",
                        "orderable" : false
                    },
		            {
		                "name"      : "creation_date",
		                "orderable" : true,
		                "template"  : "#orderColumnTimeTemplate"
		            },
		            {
		                "name"      : "totalAmount",
		                "orderable" : true,
		                "template"  : "#orderColumnTotalAmountTemplate"
		            },
		            {
		                "name"      : null,
		                "template"  : "#orderActionColumnTemplate"
		            }
		        ];

                scope.getReportData = function(currency) {
                    var filterStatus = 0;
                   
                    if (currentStateName == 'order_report') { 
                        filterStatus = scope.reportData.status;
                    } else if (currentStateName == 'payment_report') { 
                        filterStatus = scope.reportData.paymentStatus;
                    }

                    __DataStore.fetch({
                        'apiURL'      : 'manage.report.get.order_config_data',
                        'startDate'   : scope.reportData.start, // start date
                        'endDate'     : scope.reportData.end,   // end date
                        'status'      : filterStatus, // status
                        'order'       : scope.reportData.order  // order
                    }).success(function(responseData) {

                        appServices.processResponse(responseData, null, function(){
                            var requestData = responseData.data;
                            scope.defaultCurrencyExist = requestData.defaultCurrencyExist;

                            _.defer(function() {
                                scope.currencyList = requestData.currencyList;

                                scope.paymentCurrencyList = requestData.paymentCurrencyList;
                            })

                            _.defer(function() {
                                if (!_.isEmpty(currency)) {
                                    scope.reportData.select_currency = currency;
                                }

                                if ((scope.defaultCurrencyExist) && (_.isEmpty(currency))) {
                                    scope.reportData.select_currency = requestData.currentCurrency;
                                }
                             })
                            
                            // list of status
                            scope.statuses  = __globals.generateKeyValueItems(requestData.orderConfigStatusItems);
                            scope.itemName = __ngSupport.getText(__globals.getJSString('status_array_item_in_report'));

                            // Push one more items in status array
                            scope.statuses.push({
                               id         : 9,
                               name     : scope.itemName
                            });

                            // list of status
                            scope.paymentStatuses  = __globals.generateKeyValueItems(requestData.payment_status);

                            // Push one more items in status array
                            scope.paymentStatuses.push({
                               id         : 9,
                               name     : scope.itemName
                           });

                           scope.orderList = __globals.generateKeyValueItems(requestData.orderConfigDateItems);
                        });

                    });
                };
                //scope.getReportData();

		    	scope.getReports = function (type, currency) {

                    if ($rootScope.canAccess('manage.report.list') && currentStateName == 'order_report') {

                        scope.getReportData(currency);

                        if (!_.isEmpty(orderReportPieChart) || !_.isEmpty(orderPaymentePieChart)) {
                           orderReportPieChart.destroy();
                           orderPaymentePieChart.destroy();
                        }

                        // distroy instance of datatable
                        if (scope.reportListDataTable) {
                            scope.reportListDataTable.destroy();
                        }
                    
                        scope.reportListDataTable = __DataStore.dataTable('#manageReportList', {
                            url : {
                                'apiURL'      : 'manage.report.list',
                                'startDate'   : scope.reportData.start, // start date
                                'endDate'      : scope.reportData.end,   // end date
                                'status'      : scope.reportData.status, // status
                                'order'          : scope.reportData.order,  // order
                                'currency'    : currency ? currency : null // Type
                            },
                            dtOptions       : {
                                "searching"     : true
                            },
                            columnsData     : dtReportColumnsData, 
                            scope           : $scope

                        }, {'fresh' : true}, scope.countData = function (dataTableCollection) {
                            // Check table status
                            scope.tableStatus = dataTableCollection.data;

                            //scope.currencyList = dataTableCollection._options.currencyList;
                            
                            // Get order total amount by currency code
                            scope.totalAmounts = dataTableCollection._options.totalAmounts.orderAmountByType;

                        /*    scope.debitAmount = scope.totalAmounts.debit 
                            scope.creditAmount = scope.totalAmounts.credit*/

                            // Excel download URL
                            scope.reportExcelDownloadURL = dataTableCollection._options.excelDownloadURL;
                          
                            scope.reportDuration   =__globals.generateKeyValueItems(dataTableCollection._options.duration);

                            scope.orderReportData = dataTableCollection._options.orderReportData;
                            scope.orderPaymentData = dataTableCollection._options.orderPaymentData;
                            
                            //create order report pie chart start
                            var orderReportChart = document.getElementById("lw-order-report-chart");
                      
                            // var myPieChart = new Chart(orderPayment,{
                            orderReportPieChart = appServices.createBarChart({
                                'elementId' : orderReportChart,
                                'type' : 'pie'
                            },
                            {
                                'data': scope.orderReportData.orderData,
                                'labels' : scope.orderReportData.orderStatusLabel
                            },
                            {
                                'options' : {
                                    responsive: true,
                                    legend: {
                                        onClick: function (e) {
                                            e.stopPropagation();
                                        }
                                    }
                                }
                            });
                            //create order report pie chart end

                            //create order report pie chart start
                            var orderPaymentChart = document.getElementById("lw-order-payment-chart");

                            // var myPieChart = new Chart(orderPayment,{
                            orderPaymentePieChart = appServices.createBarChart({
                                'elementId' : orderPaymentChart,
                                'type' : 'pie'
                            },
                            {
                                'data': scope.orderReportData.orderPaymentData,
                                'labels' : scope.orderReportData.orderPaymentLabel
                            },
                            {
                                'options' : {
                                    responsive: true,
                                    legend: {
                                        onClick: function (e) {
                                            e.stopPropagation();
                                        }
                                    }
                                }
                            });
                            //create order report pie chart end

                        });
                    }
                    
		    	};
		    	
		    	scope.getReports();

                /**
                  * get PaymentData
                  *
                  * @param number orderID
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.getPaymentData = function(currency) {
                     
                    if ($rootScope.canAccess('manage.payment_report.list') && currentStateName == 'payment_report') {
                        scope.getReportData();
                    
                        if (!_.isEmpty(paymentReportPieChart)) {
                            paymentReportPieChart.destroy();
                        }

                        __DataStore.fetch({
                            'apiURL'      : 'manage.payment_report.list',
                            'startDate'   : scope.reportData.start, // start date
                            'endDate'     : scope.reportData.end,   // end date
                            'status'      : scope.reportData.paymentStatus, // status
                            'order'       : scope.reportData.order,  // order
                            'currency'    : currency ? currency : null // Type
                        })
                        .success(function(responseData) {
                            
                            if (!_.isEmpty(currency)) {
                                scope.reportData.select_currency = currency; 
                            }

                            appServices.processResponse(responseData, null, function() {
                                
                                var requestData = responseData.data;
                                scope.excelDownloadURL = requestData.excelDownloadURL;
                                
                                //scope.currencyList = requestData.currencyList;
                                
                                scope.reportDuration   =__globals.generateKeyValueItems(requestData.duration);

                                // Get order total amount by currency code
                                scope.totalAmounts = requestData.orderAmountByType;
                                scope.paymentReportData = requestData.paymentReportChartData;

                                // scope.debitAmount = scope.totalAmounts.debit; 
                                // scope.creditAmount = scope.totalAmounts.credit;

                                //create order report pie chart start
                                var lwPaymentReportChart = document.getElementById("lw-payment-report-bar-chart");

                               if (!_.isNull(lwPaymentReportChart)) {
                                    paymentReportPieChart = appServices.createBarChart({
                                        'elementId' : lwPaymentReportChart,
                                        'type' : 'bar'
                                    },
                                    {
                                        'data': scope.paymentReportData.paymentChartDataSet,
                                        'labels' : scope.paymentReportData.currencyLabel
                                    },
                                    {
                                        'options' : {
                                            responsive: true,
                                            legend: {
                                                onClick: function (e) {
                                                    e.stopPropagation();
                                                }
                                            },
                                            scales: {
                                                xAxes: [{
                                                    barPercentage: 1.5,
                                                    categoryPercentage: 0.1,
                                                }]
                                            }
                                        }
                                    });
                                }
                                //create order report pie chart end
                                
                            });    

                        });   
                    }
     
                };
                scope.getPaymentData();

				/**
		          * order detail dialog
		          *
		          * @param number orderID
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.orderDetailsDialog = function(orderID) {

		            __DataStore.fetch({
	                    'apiURL'    : 'manage.order.details.dialog',
	                    'orderID'   :  orderID
	                })
	                .success(function(responseData) {
	                
	                    appServices.processResponse(responseData, null, function() {
	                        
	                        var requestData = responseData.data;
	                        
	                        appServices.showDialog({
	                           'orderDetails'    : requestData.orderDetails
	                        },
	                        {
	                            templateUrl : __globals.getTemplateURL(
	                                'report.manage.details-dialog'
	                            )
	                        },
	                        function(promiseObj) {
	                        	
	                        });

	                    });    

	                });
				};

			}
    	])

		/**
    	  * OrderReportController for list of order
    	  *
    	  * @inject __Form
    	  * @inject $scope
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('OrderReportController', [ 
            '$scope',
            function ($scope) {

            	var scope = this;

            	scope.ngDialogData   = $scope.ngDialogData;
                    
	            var requestedData 		= scope.ngDialogData.orderDetails.data;
	        	
		        scope.billingAddress   	= requestedData.address.billingAddress;
		        scope.shippingAddress   = requestedData.address.shippingAddress;
		        scope.sameAddress   	= requestedData.address.sameAddress;

		        scope.user				= requestedData.user;
		        scope.order				= requestedData.order;
		        scope.orderProducts		= requestedData.orderProducts;
		        scope.coupon			= requestedData.coupon;
		        scope.taxes				= requestedData.taxes;
		        scope.shipping			= requestedData.shipping;
            	
	            /**
		          * Close dialog
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		    	scope.close = function() {
		            $scope.closeThisDialog();
		        }
            }
        ])

        /**
        * Product Report List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ProductReportListController', [
            '$scope',
            '__DataStore',
            '$state',   
            'appServices',           
            '$rootScope',
            '__Form',     
        function ( $scope, __DataStore, $state, appServices, $rootScope, __Form) {

            var dtColumnsData = [
                    {
                        "name"      : "name",
                        "orderable" : true,
                    },
                    {
                        "name"      : "created_at",
                        "orderable" : true,
                        "template"  : "#createDateColumnTemplate"
                    },
                    {
                        "name"      : "updated_at",
                        "orderable" : true,
                        "template"  : "#updateDateColumnTemplate"
                    },
                    {
                        "name"      : "testData",
                        "orderable" : true,
                    },
                ],
                scope   = this;
                var productReportBarChart = null;
                scope.duration             = 8; // current year

                scope = __Form.setup(scope, 'manage_product_report_list', 'productReportData');

                scope.productReportData.order = 1; // created on

                scope.dateConfig = {
                	'format' : 'YYYY-MM-DD',
                	'time'    : false
                };

                // set date
                scope.monthFirstDay       = moment().startOf('month')
                                            .format('YYYY-MM-DD');

                scope.monthLastDay        = moment().endOf('month')
                                            .format('YYYY-MM-DD');


                scope.lastMonthFirstDay   = moment().subtract(1, 'months')
                                            .startOf('month')
                                            .format('YYYY-MM-DD');

                scope.lastMonthLastDay    = moment().subtract(1, 'months')
                                            .endOf('month')
                                            .format('YYYY-MM-DD');
                    
                scope.currentWeekFirstDay = moment().startOf('week')
                                                    .format('YYYY-MM-DD');

                scope.currentWeekLastDay  = moment().endOf('week')
                                                    .format('YYYY-MM-DD');


                scope.lastWeekFirstDay    = moment().weekday(-7)
                                                    .format('YYYY-MM-DD');

                scope.lastWeekLastDay     = moment().weekday(-1)
                                                    .format('YYYY-MM-DD');

                scope.today               = moment().format('YYYY-MM-DD');

                scope.yesterday           = moment().subtract(1, 'day')
                                                    .format('YYYY-MM-DD');

                scope.lastYearFirstDay    = moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD');

                scope.lastYearLastDay     = moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD');

                scope.currentYearFirstDay = moment().startOf('year').format('YYYY-MM-DD');

                scope.currentYearLastDay  = moment().endOf('year').format('YYYY-MM-DD');

                scope.last30Days          = moment().subtract(30, 'day').format('YYYY-MM-DD');

                // date and time
                var today = moment().format('YYYY-MM-DD');

                scope.productReportData.start = today;
                scope.productReportData.end   = today;
 

                $scope.$watch('reportCtrl.productReportData.start', 
                    function(currentValue, oldValue) {
    				
                    var $element = angular.element('#end');
              
                    // Check if currentValue exist
                    if (_.isEmpty(currentValue)) {
 
                        $element.bootstrapMaterialDatePicker('setMinDate', null);

                    } else {
 
                        $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
                    }
                });

                /**
                  * Call when start date updated
                  *
                  * @param startDate
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.startDateUpdated = function(startDate) {

                    scope.productReportData.start = startDate;
                    scope.duration = 10; // Custom
                };

                /**
                  * Call when start date updated
                  *
                  * @param endDate
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.endDateUpdated = function(endDate) {
                    
                    if (scope.productReportData.start > scope.productReportData.end) { 
                        scope.productReportData.end = endDate;
                    }
                    scope.productReportData.end = endDate;
                    scope.duration = 10; // Custom
                };


                /**
                    * get date and time according to duration 
                    *
                    * @param duration
                    *
                    *---------------------------------------------------------------- */
                scope.durationChange = function (duration) {
                    
                    if (duration == 1) { // current month

                        scope.productReportData.start      = scope.monthFirstDay;
                        scope.productReportData.end   = scope.monthLastDay;
                              
                    } else if (duration == 2) { // last month

                        scope.productReportData.start   = scope.lastMonthFirstDay;
                        scope.productReportData.end   = scope.lastMonthLastDay;

                    } else if (duration == 3) { // current week

                        scope.productReportData.start   = scope.currentWeekFirstDay;
                        scope.productReportData.end   = scope.currentWeekLastDay;

                    } else if (duration == 4) { // last week

                        scope.productReportData.start   = scope.lastWeekFirstDay;
                        scope.productReportData.end   = scope.lastWeekLastDay;

                    } else if (duration == 5) { // today

                        scope.productReportData.start   = scope.today;
                        scope.productReportData.end   = scope.today;

                    } else if (duration == 6) { // yesterday

                        scope.productReportData.start   = scope.yesterday;
                        scope.productReportData.end   = scope.yesterday;

                    } else if (duration == 7) { // last year

                        scope.productReportData.start   = scope.lastYearFirstDay;
                        scope.productReportData.end   = scope.lastYearLastDay;

                    } else if (duration == 8) { // current year

                        scope.productReportData.start   = scope.currentYearFirstDay;
                        scope.productReportData.end   = scope.currentYearLastDay;

                    } else if (duration == 9) { // last 30 days

                        scope.productReportData.start   = scope.last30Days;
                        scope.productReportData.end   = scope.today;

                    }
                }

                scope.durationChange(scope.duration);
                var isChartWidthSet = false;

                /**
                * Request to server
                *
                * @return  void
                *---------------------------------------------------------- */
               
                scope.getProductReportData = function() {
                    if (!_.isEmpty(productReportBarChart)) {

                       productReportBarChart.destroy();
                    }

                    // distroy instance of datatable
                    if (scope.productReportDataTable) {
                        scope.productReportDataTable.destroy();
                    }    

                    scope.productReportDataTable = __DataStore.dataTable('#lwProductReportList', {
                        url : {
                            'apiURL'      : 'manage.product_report.list',
                            'startDate'   : scope.productReportData.start, // start date
                            'endDate'     : scope.productReportData.end,   // end date
                            'order'       : scope.productReportData.order,  // order
                        },
                        dtOptions   : {
                            "searching": true
                        },
                        columnsData : dtColumnsData, 
                        scope       : $scope

                    }, {'fresh' : true}, scope.countData = function (dataTableCollection) {

                        if (!_.isEmpty(productReportBarChart)) {

                           productReportBarChart.destroy();
                        }

                        scope.reportDuration   =__globals.generateKeyValueItems(dataTableCollection._options.duration);

                        scope.orderList   =__globals.generateKeyValueItems(dataTableCollection._options.orderDateList);

                        scope.productChartData = dataTableCollection._options.productChartData;

                        if (scope.productChartData.productCount == 0) {
                            $('#lw-chart-Area-Wrapper').removeClass('lw-chart-Area-Wrapper');
                        } else {
                            $('#lw-chart-Area-Wrapper').addClass('lw-chart-Area-Wrapper');
                        }
                    
                         //create order report pie chart start
                        var lwProductReportChart = document.getElementById("lw-product-report-bar-chart");

                       
                        function addData(numData, chart) {

                            if (!isChartWidthSet) {
                                for (var i = 0; i < numData; i++) {
                                    var newidth = $('.lw-chart-wrapper').width() + 60;
                                    $('.lw-chart-wrapper').width(newidth);
                                }
                                isChartWidthSet = true;
                            }                            
                        }
                    
                        productReportBarChart = appServices.createBarChart({
                            'elementId' : lwProductReportChart,
                            'type' : 'bar'
                        },
                        {
                            'data': scope.productChartData.chartData,
                            'labels' : scope.productChartData.labels,
                        },
                        {
                            'options' : {
                                responsive: true,
                                legend: {
                                    display: false,
                                    onClick: function (e) {
                                        e.stopPropagation();
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        barPercentage: 1.5,
                                        categoryPercentage: 0.1,
                                    }]
                                }
                            }
                        });
                        addData(10, productReportBarChart);
                        //create order report pie chart end
                    });
                };
                scope.getProductReportData();
               
                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function() {
                    __DataStore.reloadDT(scope.productReportDataTable);
                };
                
                // when add new record 
                $scope.$on('product_report_added_or_updated', function (data) {
                    
                    if (data) {
                        scope.reloadDT();
                    }

                });
        
        }])
        // Sample List Controller ends here


})( window, window.angular );;
(function() {
'use strict';
    
    /*
      Login Controller Module
      -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.login', [])

        /**
          * UserLoginController - login a user in application
          *
          * @inject __Form
          * @inject __Auth
          * @inject appServices
          * @inject __Utils
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserLoginController',   [
            '__Form', 
            '__Auth', 
            'appServices',
            '__Utils',
			'$scope',
            function (__Form, __Auth, appServices, __Utils, $scope) {

                var scope   = this;

                scope = __Form.setup(scope, 'form_user_login', 'loginData', {
                    secured : true
                });

                scope.show_captcha      = false;
                scope.request_completed = false;
                scope.guestLogin        = false;
                scope.site_key          = __globals.getAppImmutables('recaptcha_site_key');

                if (_.has($scope.ngDialogData, 'guestOrder')
                    && $scope.ngDialogData.guestOrder == true) {
                    scope.guestLogin = true;
                }

                /**
                  * Get login attempts for this client ip
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                __Form.fetch('user.login.attempts').success(function(responseData) {
                    
                    appServices.processResponse(responseData, null, function(reactionCode) {
						scope.show_captcha      = responseData.data.show_captcha;
                        scope.selectUser        = responseData.data.selectUser;
                        scope.request_completed = true;
                        
                    });                   
                });

                
                scope.addUserData = function(value) {
                    
                    if (value) {
                        scope.loginData.email    = scope.selectUser[value]['email'];
                        scope.loginData.password = scope.selectUser[value]['password']
                    }
                };
                
                /**
                  * Fetch captch url
                  *
                  * @return string
                  *---------------------------------------------------------------- */

                scope.getCaptchaURL = function() {
                    return __Utils.apiURL('security.captcha')+'?ver='+Math.random();
                };

                /**
                  * Refresh captch 
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.refreshCaptcha = function() {
                    scope.captchaURL = scope.getCaptchaURL();
                };

                scope.captchaURL  = scope.getCaptchaURL();

                /**
                * Submit login form action
                *
                * @return void
                *---------------------------------------------------------------- */

                scope.submit = function() {

                    scope.isInActive = false;

                    __Form.process('user.login', scope).success(function(responseData) {

                        var requestData = responseData.data;
       
                        appServices.processResponse(responseData, {
                                error : function() {

                                   // scope.isInActive = requestData.isInActive;

                                    scope.show_captcha = requestData.show_captcha;

                                    // reset password field
                                    scope[scope.ngFormModelName].password   = "";

                                    // Check if show captcha exist then refresh captcha
                                    if (scope.show_captcha) {
                                        scope[scope.ngFormModelName].confirmation_code   = "";
                                        scope.refreshCaptcha();
                                    }

                                },
                                otherError : function(reactionCode) {

                                    scope.isInActive = requestData.isInActive;
                                    
                                    // If reaction code is Server Side Validation Error Then 
                                    // Unset the form fields
                                    if (reactionCode == 3) {
                                        // Check if show captcha exist then refresh captcha
                                        if (scope.show_captcha) {
                                            scope.refreshCaptcha();
                                        }
                                    } else if (reactionCode == 10) {
                                        if (requestData.auth_info.authorized) {
                                            window.location = window.appConfig.appBaseURL;
                                        }
                                    }

                                }
                            },
                            function() {

                                __Auth.checkIn(requestData.auth_info, function() {

                                    if (requestData.intendedUrl) {

                                        __globals.redirectBrowser(requestData.intendedUrl);

                                    } else {
                                        __globals.redirectBrowser(window.appConfig.appBaseURL);
                                    }

                                });
                            });    

                    });

                };



				/**
                * Submit login form action
                *
                * @return void
                *---------------------------------------------------------------- */

                scope.submitDialogLogin = function() {

                    scope.isInActive = false;
                  
                    __Form.process('user.login', scope).success(function(responseData) {

                        var requestData = responseData.data;

                        appServices.processResponse(responseData, {
                                error : function() {

                                   // scope.isInActive = requestData.isInActive;

                                    scope.show_captcha = requestData.show_captcha;

                                    // reset password field
                                    scope[scope.ngFormModelName].password   = "";

                                    // Check if show captcha exist then refresh captcha
                                    if (scope.show_captcha) {
                                        scope[scope.ngFormModelName].confirmation_code   = "";
                                        scope.refreshCaptcha();
                                    }

                                },
                                otherError : function(reactionCode) {

                                    scope.isInActive = requestData.isInActive;
                                    
                                    // If reaction code is Server Side Validation Error Then 
                                    // Unset the form fields
                                    if (reactionCode == 3) {

                                        // Check if show captcha exist then refresh captcha
                                        if (scope.show_captcha) {
                                            scope.refreshCaptcha();
                                        }

                                    } else if (reactionCode == 10) {
                                        if (requestData.auth_info.authorized) {
                                            window.location = window.appConfig.appBaseURL;
                                        }
                                    }

                                }
                            },
                            function() {

                                __Auth.checkIn(requestData.auth_info, function() {
                                    
                                    if (scope.guestLogin == true) {
                                        __globals.redirectBrowser(__Utils.apiURL('order.summary.view'));
                                    } else {
                                        $scope.closeThisDialog({'login_success' : true});
                                    }
                                });

                            });    

                    });

                };

            }

        ]);

})();;
(function() {
'use strict';
    
    /*
     UserLogoutController
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.logout', [])
        .controller('UserLogoutController',   [
            '__DataStore', 
            '__Auth', 
            'appServices', 
            UserLogoutController 
        ]);

    /**
      * UserLogoutController for login logout
      *
      * @inject __DataStore
      * @inject __Auth
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function UserLogoutController(__DataStore, __Auth, appServices) {

        var scope   = this;

        __DataStore.post('user.logout').success(function(responseData) {

            appServices.processResponse(responseData, function(reactionCode) {

                // set user auth information
                __Auth.checkIn(responseData.data.auth_info);  

            });

        });

    };

})();;
/*!
*  Component  : RolePermission
*  File       : RolePermissionDataServices.js  
*  Engine     : RolePermissionServices 
----------------------------------------------------------------------------- */

(function(window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.RolePermissionDataServices', [])
        .service('RolePermissionDataService',[
            '$q', 
            '__DataStore',
            'appServices',
            RolePermissionDataService
        ])

        /*!
         This service use for to get the promise on data
        ----------------------------------------------------------------------------- */

        function RolePermissionDataService($q, __DataStore, appServices) {

            /*
            Get Permissions
            -----------------------------------------------------------------*/
            this.getPermissions = function(roleId) {
                
                //create a differed object          
                var defferedObject = $q.defer();   
   
                __DataStore.fetch({
                    'apiURL' : 'manage.user.role_permission.read',
                    'roleId' : roleId
                }).success(function(responseData) {
                            
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);  

                    }); 

                });       

               //return promise to caller          
               return defferedObject.promise; 
            };

            /*
            Get Add Role Support Data
            -----------------------------------------------------------------*/
            this.getAddSupportData = function() {
                
                //create a differed object          
                var defferedObject = $q.defer();   
   
                __DataStore.fetch('manage.user.role_permission.read.add_support_data')
                    .success(function(responseData) {
                            
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);  

                    }); 

                });       

               //return promise to caller          
               return defferedObject.promise; 
            };

            /*
            Get add support Data 
            -----------------------------------------------------------------*/
            this.getAllPermissionsById = function(roleId) {
                
                //create a differed object          
                var defferedObject = $q.defer();   
   
                __DataStore.fetch({
                        'apiURL' : 'manage.user.role_permission.read.using_id',
                        'roleId' : roleId
                    }).success(function(responseData) {
                            
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);  

                    }); 

                });       

               //return promise to caller          
               return defferedObject.promise; 
            };
        };

})(window, window.angular);
;
/*!
*  Component  : RolePermission
*  File       : RolePermission.js  
*  Engine     : RolePermission 
----------------------------------------------------------------------------- */
(function(window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.RolePermissionEngine', [])

        /**
          * Role Permission Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('RolePermissionController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])

         /**
        * Role Permission List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object RolePermissionDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('RolePermissionListController', [
            '$scope',                
            '__DataStore',                
            '__Form',                
            '$state',                
            'appServices',                
            '$rootScope',
            'RolePermissionDataService',
        function ( $scope, __DataStore, __Form, $state, appServices, $rootScope, RolePermissionDataService) {
            var dtColumnsData = [
                    {
                        "name"      : "title",
                        "orderable" : true,
                    },
                    {
                        "name"      : null,
                        "template"  : "#rolePermissionActionColumnTemplate"
                    }
                ],
                scope   = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.rolePermissionDataTable = __DataStore.dataTable('#lwrolePermissionList', {
                    url         : 'manage.user.role_permission.read.list', 
                    dtOptions   : {
                        "searching": true,
                        "pageLength" : 25
                    },
                    columnsData : dtColumnsData, 
                    scope       : $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function() {
                    __DataStore.reloadDT(scope.rolePermissionDataTable);
                };

                /**
                * rolePermission delete 
                *
                * inject rolePermissionIdUid
                *
                * @return    void
                *---------------------------------------------------------------- */

                scope.delete = function(rolePermissionIdOrUid, name) {
                   
                    var $lwRolePermissionDeleteTextMsg = $('#lwRolePermissionDeleteTextMsg');

                    __globals.showConfirmation({
                        html : __globals.getReplacedString($lwRolePermissionDeleteTextMsg,
                                    '__name__',
                                    _.unescape(name)
                                ),
                        confirmButtonText : $lwRolePermissionDeleteTextMsg.attr('data-delete-button-text')
                    }, function() {

                        __DataStore.post({
                            'apiURL' : 'manage.user.role_permission.write.delete',
                            'rolePermissionIdOrUid' : rolePermissionIdOrUid
                        }).success(function(responseData) {
                            
                            var message = responseData.data.message;
                            
                            appServices.processResponse(responseData, {

                                error : function(data) {
                                __globals.showConfirmation({
                                    title   : $lwRolePermissionDeleteTextMsg .attr('data-error-text'),
                                    text    : message,
                                    type    : 'error'
                                });
                            }

                            }, function(data) {
                                __globals.showConfirmation({
                                    title   : $lwRolePermissionDeleteTextMsg .attr('data-success-text'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.reloadDT();
                            }); 

                        });

                    });
                };

                /**
                * Show add new role dialog 
                *
                * @return    void
                *---------------------------------------------------------------- */
                scope.showAddNewDialog = function() {

                    appServices.showDialog({},
                    {
                        templateUrl : __globals.getTemplateURL(
                                'user.role-permission.add-dialog'
                            ),
                        controller: 'AddRoleController as addRoleCtrl',
                        resolve : {
                            addSupportData : function() {
                                return RolePermissionDataService
                                        .getAddSupportData();
                            }
                        }
                    },
                    function(promiseObj) {
                        if (_.has(promiseObj.value, 'role_Added') 
                            && (promiseObj.value.role_Added === true)) {
                            scope.reloadDT();
                        }
                    }); 
                };

                 /**
                * Role Permission Dialog 
                *
                * inject roleId
                *
                * @return    void
                *---------------------------------------------------------------- */
                scope.rolePermissionDialog = function(roleId, title) {
                    
                    appServices.showDialog({
                        'roleId' : roleId,
                        'title'  : _.unescape(title)
                    },
                    {
                        templateUrl : __globals.getTemplateURL(
                                'user.role-permission.dynamic-role-permissions'
                            ),
                        controller: 'DynamicRolePermissionController as DynamicRolePermissionCtrl',
                        resolve : {
                            permissionData : function() {
                                return RolePermissionDataService
                                        .getPermissions(roleId);
                            }
                        }
                    },
                    function(promiseObj) {

                    }); 
                };

            }
        ])
        // Role Permission List Controller ends here

        /**
          * Dynamic Role Permission Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('DynamicRolePermissionController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'permissionData',
            function ($scope, __DataStore, __Form, $stateParams, appServices, permissionData) {
                var scope    = this,
                    ngDialog = $scope.ngDialogData,
                    roleId   = ngDialog.roleId;

                scope  = __Form.setup(scope, 'user_role_dynamic_access', 'accessData', {
                    secured : true,
                    unsecuredFields : []
                });

                scope.title = ngDialog.title;
                scope.permissions = permissionData.permissions;
              
			 	scope.accessData.allow_permissions = permissionData.allow_permissions;
				scope.accessData.deny_permissions = permissionData.deny_permissions;
 				scope.checkedPermission = {};

				scope.disablePermissions = function(eachPermission, permissionID) {

                    _.map(eachPermission.children, function(key) {
                        if (_.includes(key.dependencies, permissionID)) {
                            _.delay(function(text) {
                                $('input[name="'+key.id+'"]').attr('disabled', true);
                            }, 500);
                        }
                    });

                }
 
				_.map(scope.accessData.allow_permissions, function(permission) {
				 	scope.checkedPermission[permission] = "2";
				})
				_.map(scope.accessData.deny_permissions, function(permission) {
				 	scope.checkedPermission[permission] = "3";

				 	_.map(scope.permissions, function(eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');
                        
                        if (_.includes(pluckedIDs, permission)) {
                            scope.disablePermissions(eachPermission, permission)
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {
                             
                            _.map(eachPermission.children_permission_group, function(groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');
                        
                                if (_.includes(pluckedIDs, permission)) {
                                    scope.disablePermissions(groupchild, permission)
                                }
                            });
                        }
                    });
				})

                scope = __Form.updateModel(scope, scope.accessData);
 
 				//for updating permissions
                scope.checkPermission = function(childId, status) {
 					
 					if (!_.isString(status)) {
 						status = status.toString();
 					}

 					scope.checkedPermission[childId] = status;

                 	if (status == "2") {
                		if(!_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions.push(childId);
                		}
                 		if (_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                 		}
                	} else if (status == "3")  {

	                   	if(!_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions.push(childId);
                		}
                 		if (_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                 		}
                	} else {

                		if (_.includes(scope.accessData.deny_permissions, childId)) {
                 			scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                 		}
                 		if (_.includes(scope.accessData.allow_permissions, childId)) {
                 			scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                 		}
                	}

                	_.map(scope.permissions, function(permission) {
                        
                        var pluckedIDs = _.pluck(permission.children, 'id'),
                        keyPermissions = [];
                        if (_.includes(pluckedIDs, childId) && permission.children[0].id != childId) {
                            // _.map(permission.children, function(key) {

                            // 	if (permission.children[0].id != key.id && !_.isUndefined(scope.checkedPermission[key.id])) {
                            // 		keyPermissions.push(scope.checkedPermission[key.id]);
                            // 	}
                            //     // if (key.id == childId && permission.children[0].id != childId) {
                            //     //     _.map(key.dependencies, function(dependency) {
                            //     //         scope.checkedPermission[dependency] = "2";
                            //     //     })
                            //     // }
                            // });

                            // scope.checkedPermission[permission.children[0].id] = "3";
 
                            // if (_.includes(keyPermissions, "2")) {
                            // 	scope.checkedPermission[permission.children[0].id] = "2";
                            // }

                        } else if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {

                            _.map(permission.children, function(key) {
 
                                if (key.id != permission.children[0].id) {
                                    _.map(key.dependencies, function(dependency) {
                                        
                                        if (_.includes(key.dependencies, childId) && status == "3") {
                                            
                                            $('input[name="'+key.id+'"]').attr('disabled', true);

                                        } else {
                                            $('input[name="'+key.id+'"]').attr('disabled', false);

                                        }
                                    });
                                }
                            })
                        }
 
                        if (_.has(permission, 'children_permission_group')) {
                            _.map(permission.children_permission_group, function(groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'), 
                                keyPermissionsGroup = [];

                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id != childId) {
         //                            _.map(groupchild.children, function(groupchildkey) {
         //                                // if (groupchildkey.id == childId && groupchild.children[0].id != childId) {
         //                                //     _.map(groupchildkey.dependencies, function(dependency) {
         //                                //         scope.checkedPermission[dependency] = "2";
         //                                //     })
         //                                // }

         //                                if (groupchild.children[0].id != groupchildkey.id && !_.isUndefined(scope.checkedPermission[groupchildkey.id])) {
		       //                      		keyPermissionsGroup.push(scope.checkedPermission[groupchildkey.id]);
		       //                      	}

         //                            });

         //                            scope.checkedPermission[groupchild.children[0].id] = "3";

									// if (_.includes(keyPermissionsGroup, "2")) {
									// 	scope.checkedPermission[groupchild.children[0].id] = "2";
									// }

                                } else if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {
 
                                    _.map(groupchild.children, function(key2) {
                                                                  
                                        if (key2.id != groupchild.children[0].id) {
                                            _.map(key2.dependencies, function(dependency) {
                                                
                                                if (_.includes(key2.dependencies, childId) && status == "3") {
                                                    
                                                    $('input[name="'+key2.id+'"]').attr('disabled', true);

                                                } else {
                                                    $('input[name="'+key2.id+'"]').attr('disabled', false);

                                                }
                                            })
                                        }
                                    });
                                }
                            })
                        } 
					})
              	}
 			 
                // scope.preparePermissionData = function() {
                //     scope.accessData.allow_permissions = [];
                //     scope.accessData.deny_permissions = [];
                    
                //     if (!_.isEmpty(scope.accessData.selected_permissions)) {
                //         _.forEach(scope.accessData.selected_permissions, function(item) {
                //             var number = item.split("_").pop();
                //             if (number == 2) {
                //                 scope.accessData.allow_permissions.push(_.trimRight(item, '_'+number));
                //             } else if (number == 3) {
                //                 scope.accessData.deny_permissions.push(_.trimRight(item, '_'+number));
                //             }
                //         });
                //     }                    
                // }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */
      //           scope.filterPermissions = function(match) {
 
      //         		var treeInstance = $("#permissionTree").fancytree("getTree"),
      //         			filteredNodes,
      //           		filteredbranches,
 				 //        opts = {
				  //       	'autoApply' : true,
						// 	'autoExpand' : true,
						// 	'fuzzy' : false,
						// 	'hideExpanders' : true,
						// 	'highlight' : true,
						// 	'leavesOnly' : true,
						// 	'nodata' : 'No results found.',
						// 	'mode' : "hide",
						// 	'counter': true,
				  //       };

 					// 	// Pass function to perform match
						// filteredNodes = treeInstance.filterNodes(match, opts);
						// filteredbranches = treeInstance.filterBranches(match, opts);
      //             }
                  
                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function() {
                  
                    __Form.process({
                        'apiURL' : 'manage.user.role_permission.write.create',
                        'roleId' : roleId
                    }, scope)
                        .success(function(responseData) {
                        appServices.processResponse(responseData, null, function() {
                            // close dialog
                            $scope.closeThisDialog();
                        });    
                    });
                };

                /*
                 * Check if value updated then enable and disable radio button according to 
                 * current radio button
                 *
                 * @param string name  
                 * @param number value
                 * @param array dependencies
                 * @param bool inheritStatus
                 *
                 * return array
                 * -------------------------------------------------------------------------- */
                scope.valueUpdated = function(name, value, dependencies, inheritStatus) {

                    _.forEach(scope.accessData.permissions, function(permission) {
                        if (permission[0].name == name) {

                            if (permission[0].allow == 2) { //Allow

                                _.map(permission, function(item) {
                                    if (!_.isEmpty(item.dependencies)) {
                                        item.disabled = false;
                                    }
                                });

                            } else if (permission[0].allow == 3) { // Deny

                                _.map(permission, function(item) {
                                    if (!_.isEmpty(item.dependencies)) {
                                        item.disabled = true;
                                        item.allow = 3;
                                    }
                                });

                            } else if (permission[0].allow == 1) { // Inherited

                                if (permission[0].currentStatus) {

                                    _.map(permission, function(item) {
                                        if (!_.isEmpty(item.dependencies)) {
                                            item.disabled = false;
                                            item.allow = 1;
                                        }
                                    });

                                } else {

                                    _.map(permission, function(item) {
                                        if (!_.isEmpty(item.dependencies)) {
                                            item.disabled = true;
                                            item.allow = 1;
                                        }
                                    });
                                }
                            }
                        }
                    });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
            }
        ])


        
                /**
          * Add new Role Permission Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('AddRoleController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'addSupportData',
            'appServices',
            'RolePermissionDataService',
            function ($scope, __DataStore, __Form, $stateParams, addSupportData, appServices, RolePermissionDataService) {

                var scope  = this;
 
                scope  = __Form.setup(scope, 'add_role', 'roleData', {
                    secured : true,
                    unsecuredFields : []
                });

                scope.userRoles = addSupportData.userRoles; 
                scope.permissions = addSupportData.permissions;
                scope.roleData.allow_permissions = [];
				scope.roleData.deny_permissions = [];
				scope.checkedPermission = {};


                /*
                 Get Permission basis on the role id
                -------------------------------------------------------------------------- */
                scope.getPermissions = function(roleId) {

                    RolePermissionDataService
                        .getAllPermissionsById(roleId)
                        .then(function(responseData) {
                         
                            scope.permissions = responseData.permissionData;
                            scope.roleData.selected_permissions = responseData.allowedData;

                            scope.roleData.allow_permissions = responseData.allow_permissions;
 							scope.roleData.deny_permissions = responseData.deny_permissions;
 							scope.checkedPermission = {};
			  				
							_.map(scope.roleData.allow_permissions, function(permission) {
							 	scope.checkedPermission[permission] = "2";
							})
							_.map(scope.roleData.deny_permissions, function(permission) {
							 	scope.checkedPermission[permission] = "3";
							})
                        })
                };

 				//for updating permissions
                scope.checkPermission = function(childId, status) {
 					
 					if (!_.isString(status)) {
 						status = status.toString();
 					}

 					scope.checkedPermission[childId] = status;

                 	if (status == "2") {

                		if(!_.includes(scope.roleData.allow_permissions, childId)) {
                 			scope.roleData.allow_permissions.push(childId);
                		}
                 		if (_.includes(scope.roleData.deny_permissions, childId)) {
                 			scope.roleData.deny_permissions = _.without(scope.roleData.deny_permissions, childId);
                 		}

                	} else if (status == "3")  {

	                   	if(!_.includes(scope.roleData.deny_permissions, childId)) {
                 			scope.roleData.deny_permissions.push(childId);
                		}
                 		if (_.includes(scope.roleData.allow_permissions, childId)) {
                 			scope.roleData.allow_permissions = _.without(scope.roleData.allow_permissions, childId);
                 		}
                	}

                	_.map(scope.permissions, function(permission) {
 	 
                        var pluckedIDs = _.pluck(permission.children, 'id'), 
                        keyPermissions = [];
                        if (_.includes(pluckedIDs, childId) && permission.children[0].id != childId) {

                            // _.map(permission.children, function(key) {
                            // 	if (permission.children[0].id != key.id && !_.isUndefined(scope.checkedPermission[key.id])) {
                            // 		keyPermissions.push(scope.checkedPermission[key.id]);
                            // 	}
                            		
                            //     // if (key.id == childId && permission.children[0].id != childId) {
                            //     //     _.map(key.dependencies, function(dependency) {
                            //     //         scope.checkedPermission[dependency] = "2";
                            //     //     });
                            //     // }
                            // });

                            // scope.checkedPermission[permission.children[0].id] = "3";

                            // if (_.includes(keyPermissions, "2")) {
                            // 	scope.checkedPermission[permission.children[0].id] = "2";
                            // }
 
                        } else if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {

                            _.map(permission.children, function(key) {
 
                                if (key.id != permission.children[0].id) {
                                    _.map(key.dependencies, function(dependency) {
                                        
                                        if (_.includes(key.dependencies, childId) && status == "3") {
                                            
                                            $('input[name="'+key.id+'"]').attr('disabled', true);

                                        } else {
                                            $('input[name="'+key.id+'"]').attr('disabled', false);

                                        }
                                    });
                                }
                            })
                        }
                        
                        if (_.has(permission, 'children_permission_group')) {
                            _.map(permission.children_permission_group, function(groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'),
                                keyPermissionsGroup = [];
 
                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id != childId) {
         //                            _.map(groupchild.children, function(groupchildkey) {

         //                            	if (groupchild.children[0].id != groupchildkey.id && !_.isUndefined(scope.checkedPermission[groupchildkey.id])) {
		       //                      		keyPermissionsGroup.push(scope.checkedPermission[groupchildkey.id]);
		       //                      	}

         //                                // if (groupchildkey.id == childId && groupchild.children[0].id != childId) {
         //                                //     _.map(groupchildkey.dependencies, function(dependency) {
         //                                //         scope.checkedPermission[dependency] = "2";
         //                                //     })
         //                                // }
         //                            });

         //                            scope.checkedPermission[groupchild.children[0].id] = "3";

									// if (_.includes(keyPermissionsGroup, "2")) {
									// 	scope.checkedPermission[groupchild.children[0].id] = "2";
									// }
 
                                } else if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {
 
                                    _.map(groupchild.children, function(key2) {
										  
                                        if (key2.id != groupchild.children[0].id) {
                                            _.map(key2.dependencies, function(dependency) {
                                                
                                                if (_.includes(key2.dependencies, childId) && status == "3") {
                                                    
                                                    $('input[name="'+key2.id+'"]').attr('disabled', true);

                                                } else {
                                                    $('input[name="'+key2.id+'"]').attr('disabled', false);

                                                }
                                            })
                                        }
                                    });
                                }

                            });
                        }
					})
              	}

                /*
                 Prepare Permissions
                -------------------------------------------------------------------------- */
                // scope.preparePermissions = function() {
                //     scope.roleData.allow_permissions = [];
                //     scope.roleData.deny_permissions = [];
                    
                //     if (!_.isEmpty(scope.roleData.selected_permissions)) {
                //         _.forEach(scope.roleData.selected_permissions, function(item) {
                //             var number = item.split("_").pop();
                            
                //             if (number == 2) {
                //                 scope.roleData.allow_permissions.push(_.trimRight(item, '_'+number));
                //             } else if (number == 3) {
                //                 scope.roleData.deny_permissions.push(_.trimRight(item, '_'+number));
                //             }
                //         });
                //     }  
                // }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */
                scope.submit = function() {
                    // scope.preparePermissions();
                    __Form.process('manage.user.role_permission.write.role.create', scope)
                        .success(function(responseData) {
                        appServices.processResponse(responseData, null, function() {
                            // close dialog
                            $scope.closeThisDialog({'role_Added' : true});
                        });    
                    });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
            }
        ])
    ;

})(window, window.angular);;
/*!
*  Component  : SpecificationPreset
*  File       : SpecificationPresetEngine.js  
*  Engine     : SpecificationPreset 
----------------------------------------------------------------------------- */

(function(window, angular, undefined) {

    'use strict';

    angular.module('ManageApp.specificationPresetList', [])

        /**
        * SpecificationPreset List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('SpecificationPresetListController', [
            '$scope',                
            '__DataStore',      
            '$state',                
            'appServices',                
            '$rootScope',       
        function ( $scope, __DataStore, $state, appServices, $rootScope) {

            var dtColumnsData = [
                    {
                        "name"      : "title",
                        "orderable" : true,
                        "template"  : "#specificationLabelColumnTemplate"
                    },
                    {
                        "name"      : null,
                        "template"  : "#specificationActionColumnTemplate"
                    }
                ],
                scope   = this;

                /**
                * Request to server
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.specificationDataTable = __DataStore.dataTable('#lwSpecificationList', {
                    url         : 'manage.specification_preset.read.list', 
                    dtOptions   : {
                        "searching": true
                    },
                    columnsData : dtColumnsData, 
                    scope       : $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function() {
                    __DataStore.reloadDT(scope.specificationDataTable);
                };
                
                // when add new record 
                $scope.$on('specification_preset_added_or_updated', function (data) {
                    
                    if (data) {
                        scope.reloadDT();
                    }

                });

                //show preset Dialog
                scope.showPresetDialog = function(presetId) {
                    __DataStore.fetch({
                        'apiURL' : 'manage.specification_preset.read.presetLabel',
                        'presetId'  : presetId
                    })
                   .success(function(responseData) {
                       var requestData = responseData.data;

                        appServices.showDialog({
                            'presetData' : requestData
                        }, {
                            templateUrl : __globals.getTemplateURL(
                                    'specification-preset.manage.preset-detail-dialog'
                                )
                        }, function(promiseObj) {

                            if (_.has(promiseObj.value, 'specification_preset_added_or_updated') && promiseObj.value.specification_preset_added_or_updated) {

                                $rootScope.$broadcast('specification_preset_added_or_updated', true);
                            }

                            $state.go('specificationsPreset');

                        }); 
                    });   
                     
                };

                /**
                  * Delete shipping rule sending http request using __DataStore post
                  * function
                  *
                  * @param int    shippingID
                  * @param string country
                  *
                  * return void
                  *---------------------------------------------------------------- */

                scope.delete = function(presetId, presetTitle) {
                   
                    var $lwSpecificationPresetDeleteTextMsg = $('#lwSpecificationPresetDeleteTextMsg');

                    __globals.showConfirmation({
                        html : __globals.getReplacedString($lwSpecificationPresetDeleteTextMsg,
                                    '__title__',
                                    _.unescape(presetTitle)
                                ),
                        confirmButtonText : $lwSpecificationPresetDeleteTextMsg.attr('data-delete-button-text')
                    }, function() {

                        __DataStore.post({
                            'apiURL' : 'manage.specification_preset.write.delete',
                            'presetId'  : presetId
                        }).success(function(responseData) {
                            
                            var message = responseData.data.message;
                            
                            appServices.processResponse(responseData, {

                                error : function(data) {
                                __globals.showConfirmation({
                                    title   : $lwSpecificationPresetDeleteTextMsg .attr('data-error-text'),
                                    text    : message,
                                    type    : 'error'
                                });
                            }

                            }, function(data) {
                                __globals.showConfirmation({
                                    title   : $lwSpecificationPresetDeleteTextMsg .attr('data-success-text'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.reloadDT();
                            }); 

                        });

                    });
                };

        }])
        // SpecificationPreset List Controller ends here

        /**
        * PresetDetailController Detail Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('PresetDetailController', [
            '$scope',                
            '__DataStore',      
            '$state',                
            'appServices',                
            '$rootScope',       
        function ( $scope, __DataStore, $state, appServices, $rootScope) {
            var scope   = this;
            scope.ngDialogData   = $scope.ngDialogData;
            scope.presetData     = scope.ngDialogData.presetData.presetLabels;
          
         /**
         * Close dialog
         *
         * @return void
         *---------------------------------------------------------------- */
            scope.closeDialog = function() {
                $scope.closeThisDialog();
            };

        }])


        /**
        * SpecificationPreset Add Dialog Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('PresetAddDialogController', [
            '$scope',        
            '__DataStore',        
            '__Form',        
            '$state',        
            'appServices',        
            '$rootScope',        
        function ( $scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                appServices.showDialog({}, {
                    templateUrl : __globals.getTemplateURL(
                            'specification-preset.manage.add-dialog'
                        )
                }, function(promiseObj) {

                    if (_.has(promiseObj.value, 'specification_preset_added_or_updated') && promiseObj.value.specification_preset_added_or_updated) {

                        $rootScope.$broadcast('specification_preset_added_or_updated', true);
                    }

                    $state.go('specificationsPreset');

                });
            }
        ])

        /**
         * comment here
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         * @inject __Form
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('PresetAddController', [
            '$scope',
            '__DataStore',
            'appServices',
            '__Form',
            function PresetAddController( $scope, __DataStore, appServices, __Form ) {

                // Declare a local scope here
                var scope = this;

                // Show loader
                scope.showLoader = true;

                // Setup form
                scope = __Form.setup(scope, 'specification_preset_form', 'presetData');

                scope.presetData.specficationLabels = [
                    {
                        'label': '',
                        'use_for_filter': false
                    }
                ];

                /**
                  * Add More Row
                  *---------------------------------------------------------------- */
                scope.addMoreRow = function() {
                    scope.presetData.specficationLabels.push({
                        'label': '',
                        'use_for_filter': false
                    });
                }

                /**
                  * Remove Row
                  *---------------------------------------------------------------- */
                scope.removeRow = function(index) {
                    _.remove(scope.presetData.specficationLabels, function(item, key) {
                        return index == key;
                    })
                }

                /**
                  * Submit form method
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    // post route declared in web.php file
                    __Form.process('manage.specification_preset.write.add', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            $scope.closeThisDialog( {'specification_preset_added_or_updated' : true} );
                        });    

                    });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
        * Preset Edit Dialog Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object userEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('PresetEditDialogController', [
            '$scope',        
            '__DataStore',        
            '__Form',        
            '$state',        
            'appServices',        
            '$rootScope',        
        function ( $scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                __DataStore.fetch({
                    'apiURL'   : 'manage.specification_preset.read.editSupportadd',
                    'presetId' : $state.params.presetId
                }).success(function(responseData) {

                    var requestData = responseData.data;

                    appServices.processResponse(responseData, null, function() {

                        appServices.showDialog(
                        {
                            'presetData' : requestData.presetData
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'specification-preset.manage.edit-dialog'
                            )
                        },
                        function(promiseObj) {
                           
                            if (_.has(promiseObj.value, 'specification_preset_added_or_updated') && promiseObj.value.specification_preset_added_or_updated) {

                                $rootScope.$broadcast('specification_preset_added_or_updated', true);
                            }

                            $state.go('specificationsPreset');

                        }); 

                    });
                });
            }
        ])

        /**
        * Preset Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('PresetEditController', [
            '$scope',                
            '__DataStore',                
            '__Form',                
            '$state',                
            'appServices',                
            '$rootScope',                
        function ( $scope,  __DataStore,  __Form,  $state,  appServices,  $rootScope) {

            var scope       = this,
                requestData = $scope.ngDialogData.presetData;

            scope.showLoader = true;
            scope = __Form.setup(scope, 'preset_edit_form', 'presetEditData');
            
            scope = __Form.updateModel(scope, requestData);
            scope.showLoader = false;

            /**
              * Add More Row
              *---------------------------------------------------------------- */
            scope.addMoreRow = function() {
                scope.presetEditData.specficationLabels.push({
                    'label': '',
                    'use_for_filter': false
                });
            }

            /**
              * Remove Row
              *---------------------------------------------------------------- */
            scope.removeRow = function(index) {
                _.remove(scope.presetEditData.specficationLabels, function(item, key) {
                    return index == key;
                })
            }

            /**
              * Delete Specification
              *---------------------------------------------------------------- */
            scope.deleteSpecification = function(specificationId, specificationIndex) {
                __DataStore.post({
                    'apiURL': 'manage.specification_preset.specification.delete',
                    'specificationId': specificationId
                 }).success(function(responseData) {
                    appServices.processResponse(responseData, null, function(data) {
                        scope.removeRow(specificationIndex);
                        $rootScope.$broadcast('specification_preset_added_or_updated', true);
                    });
                });
            }

          /**
            * Submit form
            *
            * @return  void
            *---------------------------------------------------------------- */

            scope.submit = function() {
               
                __Form.process({
                    'apiURL'    : 'manage.specification_preset.write.update',
                    'presetId'  : requestData._id
                }, scope).success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        $scope.closeThisDialog( {'specification_preset_added_or_updated' : true} );
                    });    
                });
            };

          /**
            * Close dialog
            *
            * @return  void
            *---------------------------------------------------------------- */

            scope.closeDialog = function() {
                $scope.closeThisDialog();
            };

        }])
    ;

})(window, window.angular);;
/*!
*  Component  : ShippingType
*  File       : ShippingTypeEngine.js  
*  Engine     : ShippingTypeE
----------------------------------------------------------------------------- */

(function(window, angular, undefined) {

    'use strict';

    angular.module('ManageApp.ShippingTypeEngine', [])

         /**
        *  shipping type List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ShippingTypListController', [
            '$scope',                
            '__DataStore',      
            '$state',                
            'appServices',                
            '$rootScope',       
        function ( $scope, __DataStore, $state, appServices, $rootScope) {

            var dtColumnsData = [
                    {
                        "name"      : "title",
                        "orderable" : true,
                    },
                    {
                        "name"      : "createdOn",
                        "orderable" : true,
                        "template"    : "#creationDateColumnTemplate"
                    },
                    {
                        "name"      : null,
                        "template"  : "#shippingTypeActionColumnTemplate"
                    }
                ],
                scope   = this;

                /**
                * Request to server
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.shippingTypeDataTable = __DataStore.dataTable('#lwShippingTypeList', {
                    url         : 'manage.shipping_type.read.list', 
                    dtOptions   : {
                        "searching": true
                    },
                    columnsData : dtColumnsData, 
                    scope       : $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function() {
                    __DataStore.reloadDT(scope.shippingTypeDataTable);
                };
                
                // when add new record 
                $scope.$on('shipping_type_added_or_updated', function (data) {
                    
                    if (data) {
                        scope.reloadDT();
                    }

                });

                 /**
                  * Delete shipping type sending http request using __DataStore post
                  * function
                  *
                  * @param int    shippingID
                  * @param string country
                  *
                  * return void
                  *---------------------------------------------------------------- */

                scope.delete = function(shippingTypeId, title) {
                   
                    var $lwShippingTypeDeleteTextMsg = $('#lwShippingTypeDeleteTextMsg');

                    __globals.showConfirmation({
                        html : __globals.getReplacedString($lwShippingTypeDeleteTextMsg,
                                    '__title__',
                                    _.unescape(title)
                                ),
                        confirmButtonText : $lwShippingTypeDeleteTextMsg.attr('data-delete-button-text')
                    }, function() {

                        __DataStore.post({
                            'apiURL' : 'manage.shipping_type.write.delete',
                            'shippingTypeId'  : shippingTypeId
                        }).success(function(responseData) {
                            
                            var message = responseData.data.message;
                            
                            appServices.processResponse(responseData, {

                                error : function(data) {
                                __globals.showConfirmation({
                                    title   : $lwShippingTypeDeleteTextMsg .attr('data-error-text'),
                                    text    : message,
                                    type    : 'error'
                                });
                            }

                            }, function(data) {
                                __globals.showConfirmation({
                                    title   : $lwShippingTypeDeleteTextMsg .attr('data-success-text'),
                                    text    : message,
                                    type    : 'success'
                                });
                                scope.reloadDT();
                            }); 

                        });

                    });
                };
        
        }])
        //  shipping type List Controller ends here

        /**
        *  shipping type Add Dialog Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ShippingTypeAddDialogController', [
            '$scope',        
            '__DataStore',        
            '__Form',        
            '$state',        
            'appServices',        
            '$rootScope',        
        function ( $scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                appServices.showDialog({}, {
                    templateUrl : __globals.getTemplateURL(
                            'shipping-type.manage.add'
                        )
                }, function(promiseObj) {

                    if (_.has(promiseObj.value, 'shipping_type_added_or_updated') && promiseObj.value.shipping_type_added_or_updated) {

                        $rootScope.$broadcast('shipping_type_added_or_updated', true);
                    }

                    $state.go('shippingType');

                });
            }
        ])
        //  shipping type Add Dialog Controller ends here

        /**
         * comment here
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         * @inject __Form
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('ShippingTypeAddController', [
            '$scope',
            '__DataStore',
            'appServices',
            '__Form',
            function ShippingTypeAddController( $scope, __DataStore, appServices, __Form ) {

                // Declare a local scope here
                var scope = this;

                // Show loader
                scope.showLoader = true;

                // Setup form
                scope = __Form.setup(scope, 'shipping_type_form', 'shippingTypeData');

                /**
                  * Submit form method
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    // post route declared in web.php file
                    __Form.process('manage.shipping_type.write.create', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            $scope.closeThisDialog( {'shipping_type_added_or_updated' : true} );
                        });    

                    });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function() {
                    $scope.closeThisDialog();
                };
            }
        ])
        //  shipping type Add Controller ends here

        /**
        * Shipping type Edit Dialog Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object userEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ShippingTypeEditDialogController', [
            '$scope',        
            '__DataStore',        
            '__Form',        
            '$state',        
            'appServices',        
            '$rootScope',        
        function ( $scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                __DataStore.fetch({
                    'apiURL'   : 'manage.shipping_type.read.update.data',
                    'shippingTypeId' : $state.params.shippingTypeId
                }).success(function(responseData) {

                    var requestData = responseData.data;

                    appServices.processResponse(responseData, null, function() {

                        appServices.showDialog(
                        {
                            'shippingTypeData' : requestData.shippingTypeData
                        },
                        { 
                            templateUrl : __globals.getTemplateURL(
                                'shipping-type.manage.edit'
                            )
                        },
                        function(promiseObj) {
                           
                            if (_.has(promiseObj.value, 'shipping_type_added_or_updated') && promiseObj.value.shipping_type_added_or_updated) {

                                $rootScope.$broadcast('shipping_type_added_or_updated', true);
                            }

                            $state.go('shippingType');

                        }); 

                    });
                });
            }
        ])
        //  shipping type Add Controller ends here

        /**
        * Shipping type Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ShippingTypeEditController', [
            '$scope',                
            '__DataStore',                
            '__Form',                
            '$state',                
            'appServices',                
            '$rootScope',                
        function ( $scope,  __DataStore,  __Form,  $state,  appServices,  $rootScope) {

            var scope       = this,
                requestData = $scope.ngDialogData.shippingTypeData;

            scope.showLoader = true;

            scope = __Form.setup(scope, 'shipping_type_edit_form', 'shippingTypeEditData');

            scope = __Form.updateModel(scope, requestData);
            scope.showLoader = false;


          /**
            * Submit form
            *
            * @return  void
            *---------------------------------------------------------------- */

            scope.submit = function() {

                __Form.process({
                    'apiURL'    : 'manage.shipping_type.write.update',
                    'shippingTypeId'  : requestData.id
                }, scope).success(function(responseData) {

                    appServices.processResponse(responseData, null, function() {
                        $scope.closeThisDialog( {'shipping_type_added_or_updated' : true} );
                    });    
                });
            };

          /**
            * Close dialog
            *
            * @return  void
            *---------------------------------------------------------------- */

            scope.closeDialog = function() {
                $scope.closeThisDialog();
            };
        }
    ])
    ;

})(window, window.angular);;
(function() {
'use strict';
    
    /*
     TransliterateDialogController module
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageApp.transliterateDialog', [])
        .controller('TransliterateDialogController',   [
            '$scope',
            'getTransliterateData',
            '__Form',
            'appServices',
            'TransliterateDataServices',
            '__DataStore',
            '$http',
            TransliterateDialogController 
        ]);

    /**
      * TransliterateDialogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
    function TransliterateDialogController($scope, getTransliterateData, __Form, appServices, TransliterateDataServices, __DataStore, $http) {

        var scope   = this,
            requestData    = getTransliterateData,
            ngDialogData   = $scope.ngDialogData,
            entityType     = ngDialogData.entityType,
            entityId       = ngDialogData.entityId,
            entityKey      = ngDialogData.entityKey;

        scope.inputType = ngDialogData.inputType;
        scope.availableLocale = requestData.availableLocale;
        scope.availableTransliterate = requestData.availableTransliterate;
        scope.isDefaultLangEng = requestData.isDefaultLangEng;
        scope.storeDefaultLanguage = requestData.storeDefaultLanguage;
        scope.entityString = ngDialogData.entityString;
        scope.showAutoTransliterate = false;
        scope.showTranslateLink = false;
        scope.showError = false;
        scope  = __Form.setup(scope, 'form_transliterate_update', 'transliterateData');

        /**
        * Get Auto Transliterate
        * 
        * @return void
        *---------------------------------------------------------------- */
        scope.getAutoTransliterate = function(string, translateTo, inputType) {
            var translateTo = translateTo.substr(0, 2);
            $.post('https://www.google.com/inputtools/request', {
                'text': string,
                'ime': 'transliteration_en_'+translateTo,
                'num': 5,
                'cp': 0,
                'ie': 'utf-8',
                'oe': 'utf-8',
                'app': 'jsapi'
            }, function(responseData) {
                if (responseData[0] == 'SUCCESS') {
                    scope.transliterateData.translate_text = _.get(responseData, '1.0.1.0');

                    _.defer(function() {
                    if (inputType != 3) {
                        angular.element('.lw-translate-text').val(scope.transliterateData.translate_text);
                        angular.element('.lw-translate-text').trigger('input '); // Use for Chrome/Firefox/Edge
                        angular.element('.lw-translate-text').trigger('change'); // Use for Chrome/Firefox/Edge + IE11
                    } else {
                        angular.element('.lw-translate-text').trumbowyg('html', scope.transliterateData.translate_text);
                    }
                });
                }
            });
        }

        /**
        * Get Auto Translate Data
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.getAutoTranslate = function(string, translateTo, inputType) {
            var inputData = {
                'string': string
            }, 
            translateTo = translateTo.substr(0, 2);
            __DataStore.post('manage.transliterate.write.get_original_text', inputData)
                .success(function(responseData) {
                appServices.processResponse(responseData, null, function() {
                    var requestData = responseData.data;
                    $.post('https://translate.googleapis.com/translate_a/single', {
                        'client':'gtx',
                        'sl':scope.storeDefaultLanguage,
                        'tl':translateTo,
                        'dt':'t',
                        'q':requestData.originalText
                    }, function(responseData) {

                        if (inputType == 2 || inputType == 3) {
                            var translatedDescription = '';
                            _.forEach(responseData[0], function(item) {
                                translatedDescription += _.first(item);
                            });
                            scope.transliterateData.translate_text = translatedDescription;
                        } else {
                            scope.transliterateData.translate_text = _.get(responseData, '0.0.0'); 
                        }

                        _.defer(function() {
                            if (inputType != 3) {
                                angular.element('.lw-translate-text').val(scope.transliterateData.translate_text);
                		        angular.element('.lw-translate-text').trigger('input '); // Use for Chrome/Firefox/Edge
                                angular.element('.lw-translate-text').trigger('change'); // Use for Chrome/Firefox/Edge + IE11
                            } else {
                                angular.element('.lw-translate-text').trumbowyg('html', scope.transliterateData.translate_text);
                            }
                        });

                    }, 'JSON').fail(function(response) {
                        // alert('Error: ' + response.responseText);
                        scope.showError = true;
                    });
                });        
            });
        }

        /**
        * Get Translate Data
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.getTranslateData = function(language) {
            scope.showTranslateLink = true;
            if (scope.isDefaultLangEng && scope.inputType != 3) {
                var langShortCode = language.substr(0, 2);
                if (_.includes(scope.availableTransliterate, langShortCode)) {
                    scope.showAutoTransliterate = true;
                } else {
                    scope.showAutoTransliterate = false;
                }
            }

            TransliterateDataServices
            .getTranslateDataViaLanguage(entityType, entityId, entityKey, language)
            .then(function(responseData) {
                scope.transliterateData.translate_text = '';
                if (!_.isEmpty(responseData.transliterateData.translate_text)) {
                    scope.transliterateData.translate_text = responseData.transliterateData.translate_text;
                }
            });
        }

       /**
        * process update order
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.update = function() {
            __Form.process({
                'apiURL'        :'manage.transliterate.write.update',
                'entityType'    : entityType,
                'entityId'      : entityId,
                'entityKey'     : entityKey
            }, scope ).success( function( responseData ) {                          
                appServices.processResponse(responseData, null, function() {
                    //$scope.closeThisDialog();
                    scope.transliterateData = {};
                });
            });
        };

    	/**
    	  * Close dialog
    	  *
    	  * @return void
    	  *---------------------------------------------------------------- */
		scope.closeDialog = function() {
	  		$scope.closeThisDialog();
	  	};
	            
    };

})();;
/*!
*  Component  : Transliterate
*  File       : TransliterateDataServices.js  
*  Engine     : TransliterateDataServices 
----------------------------------------------------------------------------- */

(function(window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.TransliterateDataServices', [])
        .service('TransliterateDataServices',[
            '$q', 
            '__DataStore',
            'appServices',
            TransliterateDataServices
        ])

        /*!
         This service use for to get the promise on data
        ----------------------------------------------------------------------------- */

        function TransliterateDataServices($q, __DataStore, appServices) {

            /*
            Get Permissions
            -----------------------------------------------------------------*/
            this.getTransliterateSupportData = function(entityType, entityId, entityKey) {
                
                //create a differed object          
                var defferedObject = $q.defer();   
   
                __DataStore.fetch({
                    'apiURL' : 'manage.transliterate.read.support_data',
                    'entityType' : entityType,
                    'entityId': entityId,
                    'entityKey': entityKey
                }).success(function(responseData) {
                            
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);  

                    }); 

                });       

               //return promise to caller          
               return defferedObject.promise; 
            };

            /*
            Get Permissions
            -----------------------------------------------------------------*/
            this.getTranslateDataViaLanguage = function(entityType, entityId, entityKey, language) {
                
                //create a differed object          
                var defferedObject = $q.defer();   
   
                __DataStore.fetch({
                    'apiURL' : 'manage.transliterate.read.translate_data',
                    'entityType' : entityType,
                    'entityId': entityId,
                    'entityKey': entityKey,
                    'language' : language
                }).success(function(responseData) {
                            
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);  

                    }); 

                });       

               //return promise to caller          
               return defferedObject.promise; 
            };
        };

})(window, window.angular);

//# sourceMappingURL=../source-maps/manage-app.src.js.map
