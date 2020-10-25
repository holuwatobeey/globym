(function() {
'use strict';

    angular.module('PublicApp', [
        'ngMessages',
        'ngAnimate',
        'ngSanitize',
        'ui.router',
        'ngNotify',
        'ngDialog',
        'angular-loading-bar',
        'selectize',
        'lw.core.utils',
        'lw.security.main',
        'lw.auth',
        'rzModule',
        'lw.data.datastore',
        'lw.data.datatable',
        'NgSwitchery',
        'lw.form.main',
        'app.service',
        'app.http',
        'app.notification',
        'app.form',
        'app.directives',
        'PublicApp.master',
        'UserApp.login',
        'UserApp.logout',
        'UserApp.forgotPassword',
        'UserApp.resendActivationEmail',
        'UserApp.resetPassword',
        'UserApp.changePassword',
        'UserApp.changeEmail',
        'UserApp.register',
        'UserApp.contact',
        'UserApp.profile',
        'UserApp.profileEdit',
        'UserApp.address',
        'UserApp.Addresslist',
        'UserApp.editAddress',
        'PublicApp.display.products',
        'PublicApp.display.featured.products',
        'PublicApp.productDetails',
        'ManageProductApp.allProductReviews',
        'ManageProductApp.allProductQuestions',
        'PublicApp.productDetailsDialog',
        'PublicApp.page.details',
        'PublicApp.ShoppingCart.cart',
        'PublicApp.ShoppingCart.orderSummary',
        'PublicApp.Order.list',
        'PublicApp.Order.details',
        'PublicApp.Order.cancel',
        'PublicApp.Order.log',
        'PublicApp.address',
        'PublicApp.myWishList',
        'PublicApp.myRatingList'
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

    };

})();;
(function() {
'use strict';
	
	/*
	 PublicController
	-------------------------------------------------------------------------- */
	
	angular
        .module('PublicApp.master', [])
        .controller('PublicController', 	[
			'$rootScope', 
            '$scope', 
            '__Auth', 
            'appServices', 
            'appNotify', 
            '__DataStore',
            '$state',
            '$compile',
            '__Utils',
            '$q',
            PublicController 
	 	]);

	/**
	 * PublicController for main page application
	 *
	 * @inject __Auth
	 * @inject $rootScope
	 * @inject appServices
	 * @inject $scope
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	 function PublicController($rootScope, $scope, __Auth,  appServices
	 	, appNotify,  __DataStore, $state, $compile, __Utils, $q) {

	 	var scope 	= this;

	 	__Auth.refresh(function(authInfo) {
	 		scope.auth_info = authInfo;

	 	});

        scope.unhandledError = function() {

              appNotify.error(__globals.getReactionMessage(19)); // Unhandled errors

        };

	 	$rootScope.$on('lw.events.state.change_start', function () {
	 		appServices.closeAllDialog();    
        });

	 	$rootScope.$on('lw.auth.event.reset', function (event, authInfo) {
	 		scope.auth_info = authInfo;             
        });

	 	$rootScope.$on('lw.form.event.process.started', function (event, data) {
	 		$('button.lw-btn-process span').addClass('fa fa-spinner fa-spin');
        	$('button.lw-btn-process').prop("disabled", true);

    	});

	 	$rootScope.$on('lw.form.event.process.finished', function (event, data) {

        	$('button.lw-btn-process span').removeClass('fa fa-spinner fa-spin');
        	$('button.lw-btn-process').prop("disabled", false);

    	} );

        $rootScope.$on('lw.form.event.fetch.started', __globals.showFormLoader );

        $rootScope.$on('lw.datastore.event.fetch.finished', __globals.hideFormLoader );

        $rootScope.$on('lw.form.event.process.error', scope.unhandledError );

        $rootScope.$on('lw.datastore.event.fetch.error', scope.unhandledError );

        $rootScope.$on('lw.update.cart.string', function (event, data) {

        	scope.cart_string = data.cart_string;
        	scope.status      = data.status;
        	
        	if (scope.status == true) {

	            $('.lw-shopping-cart-btn').fadeIn("highlight", '#FFF',5000
	                    );
                 scope.getCartString();
        	}

			if (_.has(data, 'showNotification')) {
				scope.toggleShoppinCartButtonShow();
			}

    	});

        //product compare count event
        $rootScope.$on('lw.product.compare.count', function (event, data) {
          
            if (data) {
                scope.getProductCompareCount();
            }
           
        });

    	scope.store_info       = window.store_info;
    	$rootScope.isPublicApp = window.__appImmutables.publicApp;

        scope.formatAmount = function(amount, currencySymbol, currency, decimalValue) {

           return __globals.priceFormat(amount, currencySymbol, currency);

        };

        $.typeahead({
            input: '.lw-product-search-input',
            minLength: 1,
            order: "asc",
            dynamic: true,
            delay: 500,
            hint: false,
            searchOnFocus: false,
            emptyTemplate: "no search result found",
            source: {
                searchResult : {
                    display: "name",
                    ajax: function (query, callback) {

                        return {
                            type : "GET",
                            path : "searchResult",
                            url  : __Utils.apiURL({
                                        'apiURL'        : 'product.suggest.search',
                                        'searchQuery'   : query
                                    }),
                            callback: {
                                done: function (responseData) {;
                                    return responseData.data;
                                }
                            }
                        }
                    },
                }
            },
            callback: {
                onClick: function (node, a, item, event) {
                    event.preventDefault();
                    window.location = item.searchUrl;
                },
                onSubmit: function (node, form, item, event) {
		           	event.preventDefault();

		           	var searchTerm = $('#lw-product-search-input').val();
 					if (searchTerm) {
 						window.location = __Utils.apiURL('product.search')+'?search_term='+searchTerm
 					}
		        }
            },
            debug: true
        });

        scope.dialogStatus = false;
        //product quick view dialog
        scope.showDetailsDialog = function(eventObj, productId) {
           appServices.showDialog({
                    categoryID   : null,
                    productID    : productId,
                    pageType     : '',
                    dialogStatus : scope.dialogStatus
                },
                {
                    templateUrl : __globals.getTemplateURL(
                            'product.details-dialog'
                        ),
                    controller:"ProductDetailsDialogController as productDetailsDialogCtrl",
                    resolve : {

                        GetProductDetails : function() {

                            //create a differed object          
                            var defferedObject = $q.defer();   

                            __DataStore.fetch({
                                    'apiURL'      : 'product.quick.view.details.support_data',
                                    'productID'   : productId,
                                    'categoryID?' : scope.categoryID
                                }, {'fresh':true}).success(function(responseData) {
                                                
                                appServices.processResponse(responseData, null, function(reactionCode) {

                                    //this method calls when the require        
                                    //work has completed successfully        
                                    //and results are returned to client        
                                    defferedObject.resolve(responseData);  

                                }); 

                            });       

                               //return promise to caller          
                               return defferedObject.promise;
                        }
                    }
                },
            function(promiseObj) {

            });
        };

        //add product compare function start
        scope.addProductCompare = function(productId) {

            __DataStore.post({
                'apiURL' :'product.compare.add',
                'productId' : productId
            }, scope).success(function(responseData) {
              
                var requestData = responseData.data;
                appServices.processResponse(responseData, null , function(reactionCode) {
                    if (reactionCode == 1) {
                        var countCompareProduct = requestData.countCompareProduct;
                        $scope.$emit('lw.product.compare.count', true);
                    }
                   
                });   

            });
        }
        //add product compare function end

        /**
        * set page title
        *
        * @param title
        *
        * @return string
        *---------------------------------------------------------------- */

    	scope.setDocumentPageTitle = function(title) {

	        var newTitle;

	        if (_.isEmpty(scope.store_info.value)) {

	            newTitle = title;

	        } else {
	            newTitle = scope.store_info.value+' - '+title;
	        }

	        angular.element(document).prop('title', newTitle);
        
    	};

    	scope.store_info   = window.store_info;


        $rootScope.$on('lw.current.route.name', function(event, result){ 
            scope.routeStatus = result.routeStatus;

        });

        scope.themeColors = __globals.getAppImmutables('config')['theme_colors'];

        /**
        * Set Theme color
        *---------------------------------------------------------------- */
        scope.setThemeColor = function(colorName) {
            __DataStore.fetch({
                'apiURL': 'theme_color',
                'colorName': colorName
            }).success(function(responseData) {
                $('div.lw-store-header, div.lw-current-logo-conatiner').css('background-color', '#'+responseData);
            });
        }

        scope.showCurrencyNotification = function(message) {
            appNotify.info(message, {duration: 5000});
        }

        scope.showHideThemeContainer = function() {
            if (!$('.lw-theme-color-container').hasClass('lw-theme-container-active')) {
                $('.lw-theme-color-container').addClass('lw-theme-container-active');
                $('.lw-switch i:first').replaceWith("<span>&times;</span>");
            } else {
                $('.lw-theme-color-container').removeClass('lw-theme-container-active');
                $('.lw-switch span:first').replaceWith("<i class='fa fa-cog'></i>");
            }
        }

        /**
        * open cart model
        *
        * @param object param1 type 
        *
        * @return void
        *---------------------------------------------------------------- */
        
        scope.showloginDialog  =  function() 
        {   
            var routeName = scope.auth_info.currentRouteName;
        	
        	//check if login dialog is open, if true then will not re-open again
        	if (!($("html").hasClass("lw-public-login-page-active"))) {

        		$('html').addClass('lw-public-login-page-active');
	            if (routeName != 'user.login') {
	                appServices.loginRequiredDialog('login-dialog', null, function(result) {
	                	$('html').removeClass('lw-public-login-page-active');
	                    __DataStore.reset();
	                  
	                    if (result) {
	                        $("#lwLoginBtn").hide();
	                        $("#lwRegisterBtn").hide();
	                        // $state.reload();
	                        $rootScope.$broadcast('lw.login-success', true);
	                    }

	                });
	            }
	        }
        }
 
        /**
        * open cart model
        *
        * @param object param1 type 
        *
        * @return void
        *---------------------------------------------------------------- */
        
        scope.openCartDialog  =  function(status) 
        { 	
        	$rootScope.$broadcast('lw.isCart.dialog', { dialog: status });

        	if (status == true) {

          		$('html, body').animate({
			        scrollTop: $("#elementtoScrollToID").offset().top
			    }, 200);

          	} else {

          		//check if shopping cart dialog is open, if true then will not re-open again
          		if (!($("html").hasClass("lw-shopping-cart-dialog-active"))) {
          			
          			$('html').addClass('lw-shopping-cart-dialog-active');

	                appServices.showDialog(scope,
	                {
	                    templateUrl : __globals.getTemplateURL('shoppingCart.cart-view')
	                },
	                function(promiseObj) {
	                	$('html').removeClass('lw-shopping-cart-dialog-active');
	                });

	                $rootScope.$on('ngDialog.opened', function (e, $dialog) {
	                   
	                    if ($('div.lw-shopping-cart-dialog-content').length) {
	                        $('div.ngdialog-content').addClass('lw-shopping-cart-dialog');
	                    }

	                });
          		}
          	
          	}
        };

         /**
        * product compare count event
        *
        * @return void
        *------------------------------------------------------------------------ */
        scope.loadCartStatus = false;

        scope.getProductCompareCount = function(showNotification) {

            // get data using angular $http.get() method
            __DataStore.fetch('product.compare.read.product_count')
                    .success(function(responseData) {

                var requestData = responseData.data;
                appServices.processResponse(responseData, null,
                    function(reactionCode) {
                        scope.totalProductCompare = requestData.totalProductCompare;
                    }
                ); 
                
            });

        };
        // scope.getProductCompareCount();

         /**
        * remove all product compare
        *
        * @return void
        *------------------------------------------------------------------------ */
        scope.removeAllProductInComapre = function() {
            __DataStore.post({
                'apiURL' :'product.compare.remove_all'
            }, scope).success(function(responseData) {
              
                var requestData = responseData.data;
                appServices.processResponse(responseData, null , function(reactionCode) {
                    if (reactionCode == 1) {
                        var countCompareProduct = requestData.countCompareProduct;
                        $scope.$emit('lw.product.compare.count', true);
                    }
                   
                });   

            });
        };


        /**
	    * get cart btn string
	    *
	    * @return void
	    *------------------------------------------------------------------------ */
	    scope.loadCartStatus = false;

	    scope.getCartString = function(showNotification) {

	        // get data using angular $http.get() method
	        __DataStore.fetch('cart.update.cart.string')
	        		.success(function(responseData) {

				appServices.processResponse(responseData, null,
                    function(reactionCode) {
                       
		            	scope.cart_string 	 = responseData.data.cartString;
                        scope.cartData    = responseData.data.cartItems;
                        scope.totalItems    = responseData.data.totalItems;
                        scope.cartTotal    = responseData.data.cartTotal;
		            	scope.loadPage 	 	 = responseData.reaction;
		            	scope.loadCartStatus = true;

                      	scope.totalProductCompare = responseData.data.totalProductCompare;
		            	if (scope.status === true) {

				            // $('.shopping-cart-btn').effect(
				            //             "highlight", 
				            //             '#FFF',
				            //             5000
				            //         );
			        	}

						if (showNotification) {
							scope.toggleShoppinCartButtonShow()
						}
                    }
                ); 
	            
    	    });

	    };
	    scope.getCartString();

		/**
          * Show hide shopping cart button
          *
          * @return void 
          *---------------------------------------------------------------- */
        
        scope.toggleShoppinCartButtonShow = function() {

            var $dynamicShoppingCartBtn = $('.lw-dynamic-shopping-cart-btn').addClass('lw-bring-in-light'),
                delayTimer = _.delay(function(){
                $dynamicShoppingCartBtn.removeClass('lw-bring-in-light');
            }, 3000);

            $dynamicShoppingCartBtn.hover(function(){
                clearInterval(delayTimer);
            }, function(){
                $dynamicShoppingCartBtn.removeClass('lw-bring-in-light');
            });
        };

        /**
          * Check if user logged in
          *
          * @return boolean 
          *---------------------------------------------------------------- */
        
        scope.isLoggedIn = function() {
            return scope.auth_info.authorized;     // is looged in
        };

		/**
          * Check if user logged in
          *
          * @return boolean 
          *---------------------------------------------------------------- */
        
        $rootScope.isLoggedIn = function() {
            return scope.auth_info.authorized;     // is looged in
        };

        /**
          * Check if user logged in
          *
          * @return boolean 
          *---------------------------------------------------------------- */
        
        scope.isAdmin = function() {
            return scope.isLoggedIn() && scope.auth_info.designation === 1;   //check if is admin
        };

        /**
          * Get the logged in user full name
          *
          * @return string 
          *---------------------------------------------------------------- */
        
        scope.getUserFullName = function() {

            if (scope.isLoggedIn()) {
                return scope.auth_info.profile.first_name+' '+scope.auth_info.profile.last_name;
            }

        };

		$rootScope.$on('lw-open-login-dialog', function (event, response) {

			event.preventDefault();
				
            appServices.loginRequiredDialog('login-dialog', response.data, function(result, newData) {

                __DataStore.reset();
				
				if (result) {
			
					// $state.reload();
					$rootScope.$broadcast('lw.login-success', true);
				}

			});

        });
         
    };

})();;
(function() {
'use strict';
	
	/*
	 ProductsController
	-------------------------------------------------------------------------- */
	
	angular
        .module('PublicApp.display.products', [])
        .controller('ProductsController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '__Utils',
            '$state',
            '$compile',
            'appNotify',
			'$rootScope',
			'$q',
            ProductsController 
	 	]).controller('ProductsFilterController', 	[
            '$scope', '$compile', '__Utils', '$rootScope',
            ProductsFilterController
	 	]).controller('ProductCompareController',     [
            '$scope', '$compile', '__Utils', '$rootScope', '__DataStore', 'appServices',
            ProductCompareController
	 	]).controller('SidebarFilterController',     [
            '$scope', '$compile', '__Utils', '$rootScope',
            SidebarFilterController
        ]);

	/**
	 * ProductsController for show products
	 * @inject $scope
	 * @inject __DataStore
	 * @inject appServices
	 * @inject __Utils
	 * @inject state
	 * @inject $compile
     * @inject appNotify
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	 function ProductsController($scope, __DataStore, appServices,
	  __Utils, $state, $compile, appNotify, $rootScope, $q) {

	 	var scope = this,
            requestURL;

	 	scope.pageData  	= {};
	 	scope.pageStatus  	= false;
	 	scope.products  	= [];
	 	scope.catID 		= $state.params.categoryID;

        scope.showPagination = false;

        scope.paginationData = __globals.getAppJSItem('productPaginationData');
        scope.remainingItems = scope.paginationData.remainingItems;
        scope.formattedRemainingItem = scope.paginationData.formattedRemainingItem;
        scope.hasMorePages   = scope.paginationData.hasMorePages; 
        scope.categoryData   = __globals.getAppJSItem('categoryData');
        scope.sortOrderUrl   = __globals.getAppJSItem('sortOrderUrl');
        scope.productPrices  = __globals.getAppJSItem('productPrices');
        scope.filterPrices   = __globals.getAppJSItem('filterPrices');
        scope.currentRoute   = __globals.getAppJSItem('currentRoute');
        scope.searchTerm   	 = __globals.getAppJSItem('searchTerm');
		scope.categoryID   	 = __globals.getAppJSItem('categoryID');
        scope.pageType   	 = __globals.getAppJSItem('pageType');
        scope.selectedBrandID= __globals.getAppJSItem('brandID');
        scope.currenCode 	 = __globals.getAppJSItem('currenSymbol');
        scope.filterUrl 	 = __globals.getAppJSItem('filterUrl');
        scope.itemLoadType	 = parseInt(__globals.getAppJSItem('itemLoadType'));
       // scope.productExistOrNot = false;
        var contentLoaded = false;

        //add product compare function start
        scope.addProductCompare = function(productId) {

            __DataStore.post({
                'apiURL' :'product.compare.add',
                'productId' : productId
            }, scope).success(function(responseData) {
              
                var requestData = responseData.data;
                appServices.processResponse(responseData, null , function(reactionCode) {
                    if (reactionCode == 1) {
                        var countCompareProduct = requestData.countCompareProduct;
                        $scope.$emit('lw.product.compare.count', true);
                    }
                   
                });   

            });
        }
        //add product compare function end

        scope.isAddedInWishlist = false;
        /**
          * Add to Wish-list
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addToWishlist = function(productId) {

            if (!window.__appImmutables.auth_info.authorized) {
                appServices.loginRequiredDialog('login-dialog', null, function(result) {
                   });  
            } else {
                __DataStore.post({
                    'apiURL'    : 'product.wishlist.add_process',
                    'productId' : productId
                }, scope).success(function(responseData) {
                    var requestData = responseData.data;
                    if (requestData.showLoggedInDialog) {
                        appServices.loginRequiredDialog('login-dialog', null, function(result) {});  
                    }
                
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        if (reactionCode == 1) {
                            $('.lw-remove-wishlist-'+productId).show();
                            $('.lw-add-wishlist-'+productId).hide();

                            scope.isAddedInWishlist = true;
                        }                        
                    });
                });
            }
 
        };

        /**
          * Remove from Wish-list
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.removeFromWishlist = function(productId) {

            __DataStore.post({
                'apiURL'    : 'product.wishlist.remove_process',
                'productId' : productId
            }, scope).success(function(responseData) {
                var requestData = responseData.data;
                if (requestData.showLoggedInDialog) {
                    appServices.loginRequiredDialog('login-dialog', null, function(result) {});
                }
                
                appServices.processResponse(responseData, null, function(reactionCode) {

                    if (reactionCode == 1) {
                        $('.lw-add-wishlist-'+productId).show();
                        $('.lw-remove-wishlist-'+productId).hide();
                        
                        scope.isAddedInWishlist = false;
                    }                       
                });
            });
        };

        /**
          * Hide Or Show Wish-list Icon
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.getProductWislistData = function(isAddedInWishlist, productId) {
         
            if (isAddedInWishlist) {
             
                $('.lw-add-wishlist-'+productId).hide();
                $('.lw-remove-wishlist-'+productId).show();
            } else if (!isAddedInWishlist) {
                $('.lw-remove-wishlist-'+productId).hide();  
                $('.lw-add-wishlist-'+productId).show(); 
            }
           
        }
        scope.getProductWislistData();

        /**
		 * ProductsController for show products
		 * @inject $scope
		 * @inject __DataStore
		 * @inject appServices
		 * @inject __Utils
		 * @inject state
		 * @inject $stateParams
		 * 
		 * @return void
		 *-------------------------------------------------------- */
        scope.showPagination = function(requestURL) {

            scope.isRequestInProcessing = true;

			__DataStore.fetch(requestURL, {fresh : true})
			.success(function(responseData) {

				appServices.processResponse(responseData, null, function(reactionCode) {
					
					var requestData = responseData.data;
                    scope.isRequestInProcessing  = false;
					scope.productExistOrNot = requestData.productExistOrNot;					
                    scope.endOfList = requestData.paginationData.currentPage == requestData.paginationData.lastPage; 
					scope.remainingItems = requestData.paginationData.remainingItems; 
                    scope.formattedRemainingItem = requestData.paginationData.formattedRemainingItem; 
					scope.hasMorePages   = requestData.paginationData.hasMorePages; 
                    scope.paginationData = requestData.paginationData;
					scope.pageType = requestData.pageType;

                    //show or hide wishlist icon
                    _.defer(function() {
                         _.forEach(requestData.products, function(value, key) {
                           
                            if (value.isAddedInWishlist) {  
                                
                                $('.lw-add-wishlist-'+value.id).hide();
                                $('.lw-remove-wishlist-'+value.id).show();
                            } else if (!value.isAddedInWishlist) {

                                $('.lw-remove-wishlist-'+value.id).hide();  
                                $('.lw-add-wishlist-'+value.id).show(); 
                            }
                        });
                    });
                   

                    if (scope.itemLoadType === 3) { // pagination

                        $masonryInstance.masonry( 'remove', $('.lw-product-box') ).masonry('layout'); 
                        $(window).scrollTop(0);

                        if (requestData.paginationData.currentPage > 1) {
                            $('.lw-nondescript-content').remove();
                        }
                    }

           
					if (!_.isEmpty(requestData.products)) {
					
						_.forEach(requestData.products, function(product, key) {

                        	var $items = $compile(__Utils.template('#productListItemTemplate', product))($scope);
							
                         	// append items to grid                          
                            $masonryInstance.append( $items );

                            // add and lay out newly appended items
                            $masonryInstance.masonry( 'appended', $items );
						});

                        $('.lw-product-list-popover').popover({
                            html: true, 
                            content: function() {
                                return __globals.getJSString('addon_option_affect_string')
                            }
                        });

                        $('.product-item-thumb-image').Lazy({
                            afterLoad: function(element) {
                                // called after an element was successfully handled
                                    $masonryInstance.masonry('layout');
                                     $('.lw-ribbon-wrapper-green').removeClass('lw-zero-opacity');
                                },
                            onFinishedAll: function() {
                                // called once all elements was handled
                                scope.isRequestInProcessing = false; 
                            }
                        });
                        // create pagination links
						if (scope.itemLoadType === 3) {

							var $paginationLinksElement = $(".lw-pagination-container > ul");

							if ($paginationLinksElement) { 
								$paginationLinksElement.html(requestData.paginationData.paginationLinks);
							}
						}
					}
				});
			});

			contentLoaded = true;
		};


		/**
          * scroll pagination
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.isRequestInProcessing = false;

        scope.scrollPagination = function() {

        	$(window).scroll(function() { 
                if ((scope.paginationData.currentPage < scope.paginationData.lastPage) 
                    && ($(window).scrollTop() + $(window).height()) > ($(document).height() - 750) 
                    && scope.isRequestInProcessing == false) {
					scope.paginationData.currentPage = scope.paginationData.currentPage + 1;
					scope.productExistOrNot = true;

					var requestURL = scope.sortOrderUrl+'page='+scope.paginationData.currentPage;

					scope.showPagination(requestURL);
					scope.isRequestInProcessing = true;
				}
			});

		};
		
		scope.loadProductsOnBtnClick = function() {

			if (scope.paginationData.currentPage < scope.paginationData.lastPage) {

				scope.paginationData.currentPage = scope.paginationData.currentPage + 1;
				
				var requestURL = scope.sortOrderUrl+'page='+scope.paginationData.currentPage;
				
				scope.showPagination(requestURL);
			}

		};

		if (scope.itemLoadType === 1) { // scroll

			scope.scrollPagination();

		} else if(scope.itemLoadType === 3) {  // pagination
			
			// scope.loadProductsOnBtnClick();
			
			if (contentLoaded == false) {

				var $initPaginationLinksElement = $(".lw-pagination-container");

				if ($initPaginationLinksElement) { 
					$initPaginationLinksElement.html(scope.paginationData.paginationLinks);
				}
			}

			$(".lw-pagination-container").on('click', 'a', function(event) {

                event.preventDefault();

                var $this = $(this),
                    url   = $this.attr('href');
					var requestURL = scope.sortOrderUrl+'page='+url.split('page=')[1];
					scope.showPagination(requestURL);
				
			});
		}
	
		
        /**
          * Show details dialog
          *
          * @param object eventObj
          * @return void
          *---------------------------------------------------------------- */
        scope.dialogStatus = false;
        scope.showDetailsDialog   = function(eventObj, pID, pageType) {
        	
            appServices.showDialog({
            		categoryID 	: scope.categoryID,
            		productID 	: pID,
            		pageType 	: pageType,
            		dialogStatus: scope.dialogStatus
            	},
                {
                    templateUrl : __globals.getTemplateURL(
                            'product.details-dialog'
                        ),
					controller:"ProductDetailsDialogController as productDetailsDialogCtrl",
					resolve : {

						GetProductDetails : function() {

							//create a differed object          
				            var defferedObject = $q.defer();   

				            __DataStore.fetch({
									'apiURL'      : 'product.quick.view.details.support_data',
									'productID'   : pID,
									'categoryID?' : scope.categoryID
				                }, {'fresh':true}).success(function(responseData) {
				                                
				                appServices.processResponse(responseData, null, function(reactionCode) {

				                    //this method calls when the require        
				                    //work has completed successfully        
				                    //and results are returned to client        
				                    defferedObject.resolve(responseData);  

				                }); 

				            });       

				           	//return promise to caller          
				           	return defferedObject.promise;
						}
					}
                },
            function(promiseObj) {

            });


        };

         /**
          * filter dialog product
          *
          * @param brandID
          * @param resultedProudctIds
          *
          * @return void
          *---------------------------------------------------------------- */
		scope.filterDailogProduct = function(filterUrl) {
			
			scope.brandPageType = false;

			__DataStore.fetch(filterUrl, {fresh:true})
					   .success(function(responseData) {
				
				scope.brandsData  = responseData.data.productRelatedBrand;
				scope.selectedIDs = scope.brandsData;	

				appServices.showDialog(scope,
                {
                    templateUrl : __globals.getTemplateURL(
                            'product.filter-dialog'
                        )
                },
		        function(promiseObj) {

		        });

			});
        };

		/**
        * display all active products list
        *
        * @return void
        *---------------------------------------------------------------- */
         	
        scope.getProductDetails  =  function() {

	        // send http request to server
         	__DataStore.fetch({
	                'apiURL'      : 'product.quick.view.details.support_data',
	                'productID'   : scope.productID,
	                'categoryID?' : scope.categoryID
	            }).success(function(responseData) {
            	  
            	appServices.processResponse(responseData, null, function(reactionCode) {
            			
					var requestData   = responseData.data.details;

					scope.productDetails = requestData;

					scope.productData = {
						'name'       : scope.productDetails.name, 
						'quantity'   : 1, 
						'isCartExist': false
					};

					scope = __Form.updateModel(scope, scope.productData);

		          	scope.productDetails.basePriceWithAddonPrice = requestData.newTotalPrice;

					scope.newTotalPriceCount = requestData.newTotalPriceCount;

					scope.productDetails.selectedOptions = requestData.getSelectedOptions;

					if (scope.productDetails) {
			        	scope.productData.name = requestData.name;
			        }
					
					if(!_.isEmpty(scope.productDetails.getSelectedOptions)) {
						scope.optionLength = true;
					}

					scope.productDetails.priceDetails = {
            			'option' 	 : requestData.getSelectedOptions,
            			'total' 	 : requestData.getPrice.total,
            			'base_price' : requestData.getPrice.base_price
                	};

                	// product quantity
					if (requestData.qtyCart > 0) {

						scope.productData.quantity = requestData.qtyCart;
						scope.checkNumber          = true;
						
					} else {
						scope.productData.quantity = 1;
					}

					scope.btnQuantity = requestData.qtyCart;
					scope.productData.isCartExist = requestData.isCartExist;

					scope.pageStatus = true;
					
		        });
     		});
        };
       // scope.getProductDetails();

		/**
		* Add to cart with options
		*
		* @return void
		*---------------------------------------------------------------- */
		scope.addToCartWithOptions  =  function(event, productID) {
		
			$(event.currentTarget).popover({
				html:false,
				placement:'auto',
				animation:'fadeIn',
				content: function() {
          			return $compile(__Utils.template('#lwProductOptionsTemplate', 'item'))($scope);
        		}
			});
		};

		/**
		  * Add product as a cart item
		  *
		  * @return void
		  *---------------------------------------------------------------- */
		scope.addToCart  =  function(event, productID, productName) { 

			event.preventDefault();

			var currentElement = $(event.currentTarget);
			
			scope.productCartData = {
				'name'       : decodeURIComponent(productName).split('+').join(' '), 
				'quantity'   : _.add(currentElement.data('quantity'), 1), 
				'isCartExist': false
			};

		    __DataStore.post({
		        apiURL      : 'cart.add.item',
		        productID   : productID
		    }, scope.productCartData).success(function(responseData) {
		    	
		    	scope.cartAction = true;

		    	var requestData = responseData.data;

		        appServices.processResponse(
		            responseData,
		            null, 
		            function() {
		            	
		            	scope.data = {
		                  	'cart_string': responseData.data.cartItems,
		                  	'status'     : true,
							'showNotification' : true
		                };

						currentElement.data('quantity', responseData.data.qtyCart);

		                // scope.updateCartRow(productID, true);

		                $scope.$emit('lw.update.cart.string', scope.data);
		            }
		        );

		    });
		};

	    // when the cart item remove from to cart
	    $rootScope.$on('lw.cart_update',function(events, updatedData) {
				
			if (updatedData.action == 1 || updatedData.action == 2) {
			
				var $lwElement = $('.lw-product-'+updatedData.pid),
					$lwProduct = $($lwElement[0]);
				
				if ($lwProduct) {
					$lwProduct.data('quantity', updatedData.qty);
				}

			} else if(updatedData.action == 3) {

				var $allProducts = $('.lw-products-container');

				$allProducts.each(function( index ) {

				  	$('.lw-quick-cart-btn').each(function(i) {
						
						var $everyItem = $(this),
							itemPid   = $everyItem.data('pid');	
							$everyItem.data('quantity', 0);
					});

				});
			}

	    });

	   scope.unescapeProductName = function(productName, from) {
            if (from == 1) {
                return decodeURIComponent(productName).split('+').join(' ');
            } else if(from == 2) {
                return _.unescape(productName);
            }
       };

    };

    /**
     * ProductCompareController for show products
     *
     * @inject $scope
     * @inject $compile
     * @inject __Utils
     * @inject $rootScope
     * 
     * @return void
     *-------------------------------------------------------- */

    function ProductCompareController($scope, $compile, __Utils, $rootScope, __DataStore, appServices) {
        var scope                 = this;
  
        scope.getProductCompareData = function() {
            __DataStore.fetch('product.compare.read.data', {fresh : true})
                .success(function(responseData) {
                   
                    var requestData = responseData.data;
                    appServices.processResponse(responseData, null, function(reactionCode) {
                       scope.productCompareData = requestData.productCompareData;
                       scope.specificationCollection = requestData.specificationCollection;
                    
                    });
              });
        }
        scope.getProductCompareData();

        //check compare data are blank or not.
        scope.getPaire = function(specData, specId) { 
            return _.filter(specData, function(value, key) {
                if (value.specifications__id === specId) {
                   return value.value;
                }
            });
        };
        
        //remove product compare function start
        scope.removeProduct = function(productId) {
           
            __DataStore.post({
                'apiURL' :'product.compare.remove',
                'productId' : productId
            }, scope).success(function(responseData) {
                
                appServices.processResponse(responseData, null , function() {
                   
                    if (responseData.reaction == 1) {
                        scope.getProductCompareData();

                        $scope.$emit('lw.product.compare.count', true);
                    }
                    
                });   

            });
           
        };
        //remove product compare function end
    };

    /**
	 * ProductsFilterController for show products
	 *
	 * @inject $scope
	 * @inject $compile
	 * @inject __Utils
	 * @inject $rootScope
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	 function ProductsFilterController($scope, $compile, __Utils, $rootScope) {

	 	var scope 				= this;

	 	scope.dialogData 		= $scope.ngDialogData;
	 	scope.brandsData 		= scope.dialogData.brandsData;
	 	scope.pageType 			= $scope.ngDialogData.pageType;
	 	scope.currentUrl 		= scope.dialogData.currentRoute;
	 	scope.productPrices 	= scope.dialogData.productPrices;
	 	scope.filterPrices 		= scope.dialogData.filterPrices;
	 	scope.selectedBrandID 	= scope.dialogData.selectedBrandID
	 	scope.searchTerm     	= scope.dialogData.searchTerm;
	 	scope.brandPageType     = scope.dialogData.brandPageType;
	 	scope.selectedIDs       = scope.dialogData.selectedIDs;
	 	scope.currenCode        = scope.dialogData.currenCode;

	 	scope.brandExistStatus = false;
	 	if (!_.isEmpty(scope.brandsData)) {
	 		scope.brandExistStatus = true;
	 	};

        scope.selectedRows         = [];
        scope.noSelectedItems      = false;
        scope.allSelectedItems     = false;
	 	scope.priceStatus		   = false;

	 	// select brand
	 	angular.forEach(scope.selectedBrandID, function(value, index) {
	 		
	 		if (!_.isEmpty(scope.selectedIDs)) {

				angular.forEach(scope.selectedIDs, function(brand, bIndex) {

					if (scope.selectedIDs[bIndex]['brandID'] == value) {
						
						scope.selectedIDs[bIndex]['isSelected'] = true;
					}
					
				});

	 		}

        	
        });
        
	 	$scope.$on('ngDialog.opened', function (e, $dialog) {

	 		scope.priceStatus =	false;
	 		// check product price
	 		if (scope.productPrices.min_price != scope.productPrices.max_price) {

                if (scope.pageType == 'brand') {
                    $('#manageFilterTabs a').trigger('click');
                }

		 		scope.priceStatus =	true;
 
                scope.sliderBar = {
                    minValue: scope.filterPrices.min_price,
                    maxValue: scope.filterPrices.max_price,
					minLimit: 0,
                    options: {
						minRange: 1,
						maxRange: scope.filterPrices.max_price,
						pushRange: true,
                        floor: Math.floor(scope.productPrices.min_price),
                        ceil: Math.floor(scope.productPrices.max_price),
                        onChange: function(id, newValue, highValue, pointerType) {
                            var minValue = newValue;
          
                            var maxValue = highValue;
                              
                            $('.lw-min-price').prop('value', minValue).prop('name', 'min_price');
                            $('.lw-max-price').prop('value', maxValue).prop('name', 'max_price');
                         
                        },
                        translate: function(value, sliderId, label) {
                            switch (label) {
	                            case 'model':
	                            return '<b>Min price:</b>' + scope.currenCode + value;
	                            case 'high':
	                            return '<b>Max price:</b>' + scope.currenCode + Math.floor(value);
	                            default:
	                            return scope.currenCode + value
                        	}
                    	}
                	}
                };

                scope.changeFilterPrice = function(minValue, maxValue) {
                    $('.lw-min-price').prop('value', minValue).prop('name', 'min_price');
                    $('.lw-max-price').prop('value', maxValue).prop('name', 'max_price');
                };
			}
		});

        scope.showPriceFilter = false;
        
        //sidebar range filter
        scope.loadView = function(){ 
           
            setTimeout(function() { 
                
                $scope.$broadcast('rzSliderForceRender'); 
                scope.showPriceFilter = true;
            },1500); 
        }
        scope.loadView();

        /**
         * Close dialog
         *
         * @return void
         *---------------------------------------------------------------- */
        scope.close = function() {
            $scope.closeThisDialog();
        }

        /**
	  	  * Clear Filter
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
        scope.clearFilter = function() {

        	$scope.closeThisDialog({close : true});
        	
        }

    };

    /**
     * Side bar filter controller
     *
     * @inject $scope
     * @inject $compile
     * @inject __Utils
     * @inject $rootScope
     * 
     * @return void
     *-------------------------------------------------------- */

    function SidebarFilterController($scope, $compile, __Utils, $rootScope) {

        var scope = this;
        scope.currentFilterUrl = '';
        scope.showClearButton = false;

        /**
         * Initialize Filter Data
         ***********************************************/
        scope.filterInitData = function(filterMinPrice, filterMaxPrice, productMinPrice, productMaxPrice, currenSymbol, currentFilterUrl) {
            scope.currentFilterUrl = decodeURIComponent(currentFilterUrl).split('+').join(' ');
            scope.sliderBar = {
                minValue: filterMinPrice,
                maxValue: filterMaxPrice,
                    options: {
                        floor: Math.floor(productMinPrice),
                        ceil: Math.floor(productMaxPrice),
                        onChange: function(id, newValue, highValue, pointerType) {
                            scope.sliderBar.min_price = newValue;
                            scope.sliderBar.max_price = highValue;                         
                        },
                        translate: function(value, sliderId, label) {
                            switch (label) {
                            case 'model':
                            return currenSymbol + value;
                            case 'high':
                            return currenSymbol + Math.floor(value);
                            default:
                            return currenSymbol + value
                        }
                    },
                    onEnd: function(sliderId, minValue, maxValue, pointerType) {
                        scope.filterByPrice(scope.currentFilterUrl, minValue, maxValue);
                    }
                }
            };
            
            var currentPageUrl = window.location.href;
            if (currentPageUrl.indexOf('?') > -1) {
                scope.showClearButton = true;
            }

            //sidebar range filter
            $( window ).resize(function() {
                setTimeout(function(){
                    $scope.$broadcast('reCalcViewDimensions');
                }, 200);
            });
        }
        
        // Change filter price
        scope.changeFilterPrice = function (minPrice, maxPrice, currentFilterUrl) {
            scope.sliderBar.minValue = minPrice;
            scope.sliderBar.maxValue = maxPrice;
            scope.filterByPrice(decodeURIComponent(currentFilterUrl).split('+').join(' '), minPrice, maxPrice);
        }

        // Filter url by price
        scope.filterByPrice = function(currentUrl, minPrice, maxPrice) {
            var urlParams = new URLSearchParams(currentUrl),
                minPriceString = urlParams.get('min_price'),
                maxPriceString = urlParams.get('max_price');

            currentUrl = currentUrl.replace('&min_price='+minPriceString, '&min_price='+minPrice);
            currentUrl = currentUrl.replace('&max_price='+maxPriceString, '&max_price='+maxPrice);
            __globals.redirectBrowser(currentUrl);
        }

        // Clear Price Filter
        scope.clearPriceFilter = function(currentUrl, minPrice, maxPrice) {
            currentUrl = decodeURIComponent(currentUrl).split('+').join(' ');
            var priceFilterUrl = currentUrl.replace('&min_price='+minPrice+'&max_price='+maxPrice, '');
            __globals.redirectBrowser(priceFilterUrl);
        }

        // Prepare Current Url
        scope.prepareCurrentUrl = function(currentUrl, brandId, specificationId, ratingId, status, catId) {
            currentUrl = decodeURIComponent(currentUrl).split('+').join(' ');
            var urlParams = new URLSearchParams(currentUrl),
                brandsIdString = urlParams.get('brandsIds'),
                specsIdString = urlParams.get('specsIds'),
                ratingIdString = urlParams.get('rating'),
                availabilityIdString = urlParams.get('availability'),
                categoryIdString = urlParams.get('categories'),
                specsIds = [],
                brandsIds = [],
                catsIds = [];
            
            if (!_.isEmpty(brandsIdString)) {
                brandsIds = brandsIdString.split('|');
            } 
            if (!_.isEmpty(specsIdString)) {
                specsIds = specsIdString.split('|');
            }
            if (!_.isEmpty(categoryIdString)) {
                catsIds = categoryIdString.split('|');
            } 
            
            if (!_.isEmpty(brandId)) {

                if (_.includes(brandsIds, brandId)) {
                    _.remove(brandsIds, function(item) {
                        return brandId == item;
                    });
                } else {
                    brandsIds.push(brandId);
                }
                
                brandsIds = brandsIds.join("|");
                currentUrl = currentUrl.replace('brandsIds='+brandsIdString, 'brandsIds='+brandsIds);
            }

            if (!_.isEmpty(specificationId)) {

                if (_.includes(specsIds, specificationId)) {
                    _.remove(specsIds, function(item) {
                        return specificationId == item;
                    });
                } else {
                    specsIds.push(specificationId);
                }               
                
                specsIds = specsIds.join("|");
                currentUrl = currentUrl.replace('specsIds='+specsIdString, 'specsIds='+specsIds);
            }

            if (!_.isEmpty(catId)) {

                if (_.includes(catsIds, catId)) {
                    _.remove(catsIds, function(item) {
                        return catId == item;
                    });
                } else {
                    catsIds.push(catId);
                }               
                
                catsIds = catsIds.join("|");
                currentUrl = currentUrl.replace('categories='+categoryIdString, 'categories='+catsIds);
            }

            if (!_.isEmpty(ratingId)) {
                if (ratingId == 'clear') {
                    currentUrl = currentUrl.replace('rating='+ratingIdString, 'rating=');
                } else {
                    currentUrl = currentUrl.replace('rating='+ratingIdString, 'rating='+ratingId);
                }                
            }

            if (!_.isEmpty(status)) {
                if (status == 'clear') {
                    currentUrl = currentUrl.replace('availability='+availabilityIdString, 'availability=');
                } else {
                    currentUrl = currentUrl.replace('availability='+availabilityIdString, 'availability='+status);
                }                
            }
            
            __globals.redirectBrowser(currentUrl);
        }
    }

})();;
(function() {
'use strict';
	
	/*
	 DisplayFeaturedProductsController
	-------------------------------------------------------------------------- */
	
	angular
        .module('PublicApp.display.featured.products', [])
        .controller('DisplayFeaturedProductsController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '__Utils',
            DisplayFeaturedProductsController 
	 	]);

	/**
	 * DisplayFeaturedProductsController for displau products application
	 *
	 * @inject $scope
	 * @inject __DataStore
	 * @inject appServices
	 * @inject __Utils
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	 function DisplayFeaturedProductsController($scope, __DataStore, appServices, __Utils) {

	 	var scope 			= this;
	 	scope.pageData  	= {};
	 	scope.pageStatus  	= false;
	 	scope.products  	= [];

	 	// inject breadcrumb service
	 	appServices.updateBreadcrumbs([ 
			{   items     : '',
				last_item : __globals.getJSString('featured_products') 
			}
 		], $scope);

        /**
        * display all active featured products list
        *
        * @return void
        *---------------------------------------------------------------- */

        scope.getFeaturedProducts  =  function(url) 
        {   
        	 var requestURL = url ? url : 'display.featured.products.list';

         	// send http request to server
         	__DataStore.fetch(requestURL)
            	   .success(function(responseData) {

            	appServices.processResponse(
            		responseData,
            		null, 
            		function(reactionCode) {

	               	var requestData = responseData.data;

            		_.forEach(requestData.data, function(product, key){

                        scope.products.push(product);

                    });
            		
            		scope.pageData  = {
            			"total"         : requestData.total,
			            "per_page"      : requestData.per_page,
			            "current_page"  : requestData.current_page,
			            "last_page"     : requestData.last_page,
			            "next_page_url" : requestData.next_page_url,
			            "prev_page_url" : requestData.prev_page_url,
			            "from"          : requestData.from,
			            "to"            : requestData.to
            		};

            		scope.pageStatus  		= true;
            		scope.mediaURL    		= requestData.product_assets;
            		scope.totalProductCount = requestData.count;

	            });
     		});
        };
        scope.getFeaturedProducts();

	    $(window).scroll( getFeaturedNewProducts );

	    /**
	    * get on scroll products list
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
        function getFeaturedNewProducts() {

        	if (scope.pageData.current_page < scope.pageData.last_page 
            && scope.pageStatus 
            && $(window).scrollTop() +1 >= $(document).height() - $(window).height()) {

                scope.pageData.current_page += 1;

                var url = __Utils.apiURL('display.featured.products.list')+'?page='
                            +scope.pageData.current_page;

                scope.getFeaturedProducts(url);
        	}
        
        };

    };

})();;
(function() {
'use strict';
	
	/*
	 ProductDetailsController
	-------------------------------------------------------------------------- */
	
	angular
        .module('PublicApp.productDetails', [])
        .controller('ProductDetailsController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '$stateParams',
            '__Form',
            '$rootScope',
            '$state',
            '__Utils',
			'$q',
            '$compile',
            '__Auth',
            'appNotify',
            ProductDetailsController 
	 	])
		/**
         * AddReviewDialogController
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('AddReviewDialogController', [
            '$scope',
            'appServices',
            '__Form',
			'GetReviewData',
            function AddReviewDialogController($scope, appServices, __Form, GetReviewData) {
 			
                var scope = this;
                scope.pageStatus = false;
                scope.ngDialogData = $scope.ngDialogData;
                
                scope = __Form.setup(scope, 'item_review_add_form', 'reviewData');

				var reviewData = GetReviewData.reviewData;

                scope = __Form.updateModel(scope, {
                    'review' : (_.isEmpty(reviewData)) ? '' : reviewData
                });

                scope.pageStatus = true;

                /**
                  * add review on item
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submitReview = function() {
           
                    __Form.process({
                            'apiURL' : 'product.process.add.review',
                            'productId' :  scope.ngDialogData.productId
                        }, scope)
                        .success( function( responseData ) {

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
            }
        ])

		/**
          * Item Details Dialog Controller 
          *
          * @inject object $scope
          * @inject object appServices
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('ItemRatingAndReviewDialogController', [
            '$scope',
            'appServices',
			'GetRatingAndReviewData',
            function ($scope, appServices, GetRatingAndReviewData) {

                var scope    = this,
                ngDialogData     = $scope.ngDialogData;
                scope.itemTitle  = ngDialogData.itemTitle;
                scope.pageStatus = false;

                scope.itemRatingAndReview = GetRatingAndReviewData.data.itemRatingAndReview;

                if (ngDialogData) {
                    scope.pageStatus = true;
                }

             /**
                * Close this dialog
                *
                * @return void
                *-----------------------------------------------------------------------*/
                scope.closeDialog = function() {
                   $scope.closeThisDialog();
                };
            }
        ])

        /**
          * Item Details Dialog Controller 
          *
          * @inject object $scope
          * @inject object appServices
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('ProductFaqsDialogController', [
            '$scope',
            'appServices',
            '__Form',
            function ($scope, appServices, __Form) {

                var scope    = this,
                ngDialogData     = $scope.ngDialogData;
                scope.productTitle  = ngDialogData.itemTitle;
                scope.productID  = __globals.getAppJSItem('productID');

                scope = __Form.setup(scope, 'faq_add_form', 'addFaqData');

                /**
                  * add review on item
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submitFaq = function() {
              
                    __Form.process({
                            'apiURL' : 'product.process.add.faqs',
                            'productId' :  scope.productID,
                            'type'      : 2
                        }, scope)
                        .success( function( responseData ) {

                        appServices.processResponse(responseData, null, function() {
                            $scope.closeThisDialog();
                        });

                    });

                };

             /**
                * Close this dialog
                *
                * @return void
                *-----------------------------------------------------------------------*/
                scope.closeDialog = function() {
                   $scope.closeThisDialog();
                };
            }
        ])

		/**
		 * ProductDetailsController for product details application
		 * 
		 * @inject __DataStore
		 * @inject appServices
		 * @inject $scope
		 * @inject $stateParams
		 * @inject __Form
		 * @inject $state
		 * @inject $rootScope
		 * 
		 * @return void
		 *-------------------------------------------------------- */

		function ProductDetailsController($scope, __DataStore, appServices,
		$stateParams, __Form, $rootScope, $state, __Utils, $q, $compile, __Auth, appNotify) {

			var scope = this;
                scope.isLoggedIn = __Auth.isLoggedIn();
				scope.productID  = __globals.getAppJSItem('productID');
				scope.categoryID = __globals.getAppJSItem('categoryID');
	            scope.currencySymbol   = __globals.getAppJSItem('currencySymbol');
                scope.productStatus   = __globals.getAppJSItem('productStatus');
	            scope.currencyValue    = __globals.getAppJSItem('currencyValue');
                scope.launchingDate    = __globals.getAppJSItem('launchingDate');
                scope.isAddedInWishlist = __globals.getAppJSItem('isAddedInWishlist');
                scope.images        = __globals.getAppJSItem('images');
                scope.defaultImages = __globals.getAppJSItem('defaultImages');
                scope.isActiveCategory = __globals.getAppJSItem('isActiveCategory');

				var ratingsData        = __globals.getAppJSItem('productRatings');
				
				scope.itemRating 	 = ratingsData.itemRating;
                scope.itemRatingOrReview = ratingsData.itemRating.itemRatingOrReview;
				scope.enableRating  = ratingsData.enableRating;
				scope.enableRatingReview = ratingsData.enableRatingReview;
				scope.restrictAddRating = ratingsData.restrictAddRating;
				scope.enableRatingModification = ratingsData.enableRatingModification;
				scope.isPurchsedProduct  = ratingsData.isPurchsedProduct;
               
				scope = __Form.setup(scope, 'add_product_in_cart_form', 'productData', {
		            modelUpdateWatcher:false
		        });
		        
				scope.pageStatus 	= false;
		 		scope.optionLength 	= false;
                scope.inActiveCategory = false;

                //check if category & product is inactive
                if (_.isEmpty(scope.isActiveCategory) || scope.productStatus != 1) {
                    scope.inActiveCategory = true;
                    //product share button add disabled class
                    $('.lw-product-share-btn').addClass('disabled');
                } else {
                    //product share button remove disabled class
                    $('.lw-product-share-btn').removeClass('disabled');
                }

			scope.isEnableReating = function() { 

	            scope.isEnableRatingModification = true;
	            
	            // this condition check used for enable rating modification
	            if (_.isEmpty(scope.itemRating.selfRating)
	                && scope.enableRatingModification == false) {
	            
	                scope.isEnableRatingModification = false;

	            } else if (!_.isEmpty(scope.itemRating.selfRating)
	                        && scope.enableRatingModification == true) {
	            
	                scope.isEnableRatingModification = false;

	            } else if (_.isEmpty(scope.itemRating.selfRating)
	                        && scope.enableRatingModification == true) {
	            
	                scope.isEnableRatingModification = false;
                    
	            } else if (scope.restrictAddRating == true
                            && scope.enableRatingModification == true) {
                
                    scope.isEnableRatingModification = true;
                }
	            
	            if (scope.restrictAddRating == true 
	                && scope.isPurchsedProduct == false) {
	            
	                 scope.isEnableRatingModification = true;
	            }
	            
	        };
			scope.isEnableReating();

            if (scope.enableRatingModification == true && scope.productStatus != 1) {
                scope.isEnableRatingModification = true;
            } else if (_.isEmpty(scope.isActiveCategory)) {
                scope.isEnableRatingModification = true;
            }

            //product rating bar start
            // $('#lw-bar-rating').data('current-rating')
            $(function() {
                $('#lw-bar-rating').barrating('show', {
                    theme: 'fontawesome-stars-o',
                    showValues: false,
                    allowEmpty: false,
                    readonly: scope.isEnableRatingModification,
                    initialRating: scope.itemRating.selfRating,
                    onSelect: function(value, text, event) {
                        if (typeof(event) !== 'undefined') {
                          // rating was selected by a user
                          // console.log(value);
                          if (!window.__appImmutables.auth_info.authorized) {
                                appServices.loginRequiredDialog('login-dialog', null, function(promiseObj) {
                                    if (promiseObj == true) {
                                        scope.submitRatingAndReview(value);
                                    }
                                });

                            } else if (scope.enableRating == true) {
                                scope.submitRatingAndReview(value);
                            }
                        } else {
                          // rating was selected programmatically
                          // by calling `set` method
                        }
                    }
                });
            });
            //product rating bar end

            
            var drift = '';

            scope.prepareZoomImage = function(imageURL) {
                
                if (drift)
                {
                    drift.destroy();
                }

                var triggerEl =  document.querySelector('img.lw-zoom-image');

                drift = new Drift(triggerEl, {
                    paneContainer: document.querySelector('.lw-zoom-detail'),
                    namespace: 'lw-zoom-panel',
                    inlinePane: 900,
                    inlineOffsetY: -85,
                    containInline: true,
                    zoomFactor: 4,
                    hoverBoundingBox: true
                });

                _.defer(function() {

                    if (!_.isUndefined(imageURL)) {
                        
                        drift.setZoomImageURL(imageURL);

                        $(triggerEl).attr('src', imageURL);
                        
                        triggerEl.setAttribute("data-zoom", imageURL);
                    }

                }, 100);
               
            }

            _.defer(function() {
                scope.prepareZoomImage();      
            });       

            scope.currentImageIndex = 0;
            // Show all images from selected group
            scope.showImagesInColorBox = function(showImages) {
                $('.lw-zoom-panel-bounding-box').remove();
                $('.lw-zoom-detail').html('');
                var $gallery = $("a[rel=lw-product-images]").colorbox({
                    transition:"none", width:"90%", height:"90%", fixed:true
                });
                $gallery.eq(scope.currentImageIndex).click();
            }
            
            scope.previewImageUrl = '';
            // Prepare Url for preview image
            scope.changeImage = function(imageUrl, e, index) {
                e.preventDefault();
                scope.currentImageIndex = index;
                scope.previewImageUrl = imageUrl;
                _.defer(function() {
                    scope.prepareZoomImage(imageUrl);
                });
                event.stopImmediatePropagation();
            }

            //add product compare function start
            scope.addProductCompare = function(productId) {

                __DataStore.post({
                    'apiURL' :'product.compare.add',
                    'productId' : productId
                }, scope).success(function(responseData) {
                    var requestData = responseData.data;
                    appServices.processResponse(responseData, null , function(reactionCode) {

                        if (reactionCode == 1) {
                            scope.productDetails.addedInCompareList = true;
                            var countCompareProduct = requestData.countCompareProduct;
                            $scope.$emit('lw.product.compare.count', true);
                        }
                    });   

                });
            }
            //add product compare function end

            //remove product compare function start
            scope.removeProductCompare = function(productId) {

                __DataStore.post({
                    'apiURL' :'product.compare.remove',
                    'productId' : productId
                }, scope).success(function(responseData) {
                    var requestData = responseData.data;
                    appServices.processResponse(responseData, null , function(reactionCode) {

                        if (reactionCode == 1) {
                            scope.productDetails.addedInCompareList = false;
                            var countCompareProduct = requestData.countCompareProduct;
                            $scope.$emit('lw.product.compare.count', true);
                        }
                    });   

                });
            }
            //remove product compare function end

            //notify user email functionality
            scope.notifyUser = function(userEmail) {
            
                if (!_.isEmpty(userEmail)) {
                    __DataStore.post({
                        'apiURL'        :'product.notify_user.add',
                        'productId'     : scope.productID
                    }, {
                    	'notifyUserEmail' : scope.notifyUserEmail
                    }).success(function(responseData) {
                         
                        appServices.processResponse(responseData, function() {

                        	if (_.has(responseData.data, 'validation')) {
	                        	var errorMessage = responseData.data.validation.notifyUserEmail[0];
	                        	if (errorMessage) {
	                        		appNotify.error(errorMessage);
	                        	}
                        	}

                        } , function() {
                            scope.notifyUserEmail = '';
                        });   
                    });
                }
            }
            //notify user email functionality

	    	/**
	        * display all active products list
	        *
	        * @return void
	        *---------------------------------------------------------------- */
	         	
	        scope.getProductDetails  =  function() {

		        // send http request to server
	         	__DataStore.fetch({
		                'apiURL'      : 'product.quick.view.details.support_data',
		                'productID'   : scope.productID,
		                'categoryID?' : scope.categoryID
		            }).success(function(responseData) {
	            
	            	appServices.processResponse(responseData, null, function(reactionCode) {
	            			
						var requestData   = responseData.data.details;
                     
						scope.productDetails = requestData;
                        scope.itemRatingAndReview = requestData.itemRatingOrReview.itemRatingAndReview;
                        scope.originalRatingAndReviewCount = requestData.itemRatingOrReview.originalRatingAndReviewCount;
                        scope.reviewCount = requestData.itemRatingOrReview.reviewCount;
                        scope.configReviewCount = requestData.itemRatingOrReview.configReviewCount;
                        scope.totalReview = requestData.itemRatingOrReview.totalReview;
                        scope.productSpecExists = requestData.productSpecExists;
                        scope.productQuestion = scope.productDetails.productQuestionData.productQuestionData;
                      
                        scope.productDiscount = scope.productDetails.productDiscount;
                        
                        var $discount = $compile(__Utils.template('#discountDetailsTemplate', scope.productDiscount))(scope);
                        scope.discountDetailsHtml = $discount.html();

						scope.productData = {
							'name'       : scope.productDetails.name, 
							'quantity'   : 1, 
							'isCartExist': false
						};
					
						scope = __Form.updateModel(scope, scope.productData);

			          	scope.productDetails.basePriceWithAddonPrice = requestData.newTotalPrice;
						
						scope.newTotalPriceCount = requestData.newTotalPriceCount;
						
						scope.productDetails.selectedOptions = requestData.getSelectedOptions;

						if (scope.productDetails) {
				        	scope.productData.name = requestData.name;
				        }
						
						if(!_.isEmpty(scope.productDetails.getSelectedOptions)) {
							scope.optionLength = true;
						}

						scope.productDetails.priceDetails = {
	            			'option' 	 : requestData.getSelectedOptions,
	            			'total' 	 : requestData.getPrice.total,
	            			'base_price' : requestData.getPrice.base_price
	                	};

	                	// product quantity
						if (requestData.qtyCart > 0) {

							scope.productData.quantity = requestData.qtyCart;
							scope.checkNumber          = true;
							
						} else {
							scope.productData.quantity = 1;
						}
						
						scope.isPurchsedProduct = requestData.isPurchsedProduct;
						scope.btnQuantity = requestData.qtyCart;
						scope.productData.isCartExist = requestData.isCartExist;
		
						scope.pageStatus = true;
						
			        });

					
	     		});
	        };
	        scope.getProductDetails();


			/**
	          * Submit rating action
	          *
	          * @return void
	          *---------------------------------------------------------------- */
	        scope.submitRatingAndReview = function (value) {
	            if (!_.isEmpty(value)) {
                    __DataStore.post({
                        'apiURL' :'product.rating.add',
                        'productId' : scope.productID,
                        'rate'   : value
                    }, scope).success(function(responseData) {
                        
                        appServices.processResponse(responseData, null , function() {

                            scope.itemRating = responseData.data.itemRating;
                            
                            // if is allow for review
                            if (scope.itemRating.showDialog == true) {
                                scope.showAddReviewDialog(responseData.data);
                            }

                            scope.isEnableReating();

                        });   
                        
                        scope.getProductDetails();
                    });
                }
	        };

	        /**
	          * Show user login dialog
	          *
	          * @return void
	          *---------------------------------------------------------------- */

	        scope.showAddReviewDialog = function (responseData) {
	          
	            appServices.showDialog({
	                'reviewData' : responseData,
	                'productId'  : scope.productID
	            },
	            {   
	                templateUrl : __globals.getTemplateURL('product.add-review-dialog'),
					controller : "AddReviewDialogController as AddReviewDialogCtrl",
					resolve : {
						GetReviewData : function() {

				            //create a differed object          
				            var defferedObject = $q.defer();   

				            __DataStore.fetch({
				                    'apiURL'    : 'product.review.support.data',
				                    'productId' :  scope.productID
				                 }).success(function(responseData) {
				                                
				                appServices.processResponse(responseData, null, function(reactionCode) {

				                    //this method calls when the require        
				                    //work has completed successfully        
				                    //and results are returned to client        
				                    defferedObject.resolve(responseData);  

				                }); 

				            });       

				           //return promise to caller          
				           return defferedObject.promise; 
				        }
					}
	            },
	            function(promiseObj) {

                    scope.getProductDetails();
	            });
	        
	        };

             /**
              * Create FAQs Dialog for this item
              *
              * @return void
              *---------------------------------------------------------------- */

            scope.addQuestionDialog = function () {

                appServices.showDialog({
                    'itemTitle' : scope.productData.name
                },
                {   
                    templateUrl : __globals.getTemplateURL('product.add-faqs-dialog'),
                    controller  : "ProductFaqsDialogController as ProductFaqsCtrl",
                    resolve     : {}
                },
                function(promiseObj) {

                });
            };

	        /**
	          * Show Ratings of this item
	          *
	          * @return void
	          *---------------------------------------------------------------- */

	        scope.showRatingAndReviews = function () {

                appServices.showDialog({
                    'itemTitle' : scope.productData.name
                },
                {   
                    templateUrl : __globals.getTemplateURL('product.ratings-and-reviews-dialog'),
					controller : "ItemRatingAndReviewDialogController as RatingAndReviewCtrl",
					resolve : {
						GetRatingAndReviewData : function() {

				            //create a differed object          
				            var defferedObject = $q.defer();   

				            __DataStore.fetch({
				                    'apiURL'    : 'product.rating_review.get_support_data',
				                    'productId' :  scope.productID
				                 }).success(function(responseData) {
				                                
				                appServices.processResponse(responseData, null, function(reactionCode) {

				                    //this method calls when the require        
				                    //work has completed successfully        
				                    //and results are returned to client        
				                    defferedObject.resolve(responseData);  

				                }); 

				            });       

				           //return promise to caller          
				           return defferedObject.promise; 
				        }
					}
                },
                function(promiseObj) {
                    scope.getProductDetails();
                });
	        };

 
	        /**
	        * increment decrement quantity
	        *
	        * @param boolean status 
	        * @param int quantity
	        *
	        * @return void
	        *---------------------------------------------------------------- */
		 	scope.getQtyAction = function(status, quantity) {

				// if product status is true 
				if (status == true) {
					(quantity == undefined || isNaN(quantity)) 
					? scope.productData.quantity = 1 
					: scope.productData.quantity = quantity + 1;

				} else {
					// product quantity must be greterThan -1
					var newValue = (quantity - 1);
					(newValue <= 0) 
					? scope.productData.quantity = 1 
					: scope.productData.quantity = newValue;
				}

			};
			

			/**
		  * Add product as a cart item
		  *
		  * @return void
		  *---------------------------------------------------------------- */
		scope.cartAction = false;

		scope.addToCart  =  function() { 
			
		    __Form.process({
		        apiURL      : 'cart.add.item',
		        productID   : scope.productID
		    }, scope).success(function(responseData) {
		    	
		    	scope.cartAction = true;

		    	var requestData = responseData.data;

		        appServices.processResponse(
		            responseData,
		            null, 
		            function() {
		            	
		            	scope.data = {
		                  'cart_string': responseData.data.cartItems,
		                  'status'     : true
		                }
		                scope.updateCartRow(scope.productID, true);
		                $scope.$emit('lw.update.cart.string', scope.data);
		            }
		        );

		    });
		};

        /**
          * Add to Wish-list
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addToWishlist = function() {
            if (!window.__appImmutables.auth_info.authorized) {
                    
                appServices.loginRequiredDialog('login-dialog', null, function(result) {});

            } else {
                __DataStore.post({
                    'apiURL'    : 'product.wishlist.add_process',
                    'productId' : scope.productID
                }, scope).success(function(responseData) {
                    
                    var requestData = responseData.data;
                    if (requestData.showLoggedInDialog) {
                        appServices.loginRequiredDialog('login-dialog', null, function(result) {});
                    }

                    appServices.processResponse(responseData, null, function(reactionCode) {
                        if (reactionCode == 1) {
                            scope.isAddedInWishlist = true;
                        }                        
                    });
                });
            }
        };

        /**
          * Remove from Wish-list
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.removeFromWishlist = function() {
            __DataStore.post({
                'apiURL'    : 'product.wishlist.remove_process',
                'productId' : scope.productID
            }, scope).success(function(responseData) {
                var requestData = responseData.data;
                if (requestData.showLoggedInDialog) {
                    appServices.loginRequiredDialog('login-dialog', null, function(result) {});
                }

                appServices.processResponse(responseData, null, function(reactionCode) {

                    if (reactionCode == 1) {
                        scope.isAddedInWishlist = false;
                    }                       
                });
            });
        };

	    // when the cart item remove from to cart
	    $rootScope.$on('remove.cart.row',function(events,resposeData) {

	    	if (resposeData.status) {
	    		//scope.getProductDetails();
	    		scope.updateCartRow(scope.productID, true);
	    	};
	           
	    });

		/**
		  * Add product as a cart item
		  *
		  * @return void
		  *---------------------------------------------------------------- */

		scope.updateCartRow  =  function(productID, option, options) { 
			var checkOption    = true;

			scope.addonPrices  = [];
			
			if (scope.productData.options != undefined) {
		    	// hold option of addon_price related data 
		    	scope.productDetails.selectedOptions = scope.productData.options[productID];
		    	angular.forEach(scope.productData.options[productID], 
		    		function( value, key ) {
		    			
				    	this.push({
				    			option_name:key,
				    		 	value_name :value.name,
				    		  	addon_price:value.addon_price_format
				    	});
				}, scope.addonPrices );

		    	// hold empty options data 
				angular.forEach(scope.productData.options[productID], 
					function( value, key ) {

						if (value == '' ) {
							checkOption = false;
						}
						
				}, checkOption );
			} 
            
            var selectedOptions = _.pluck(scope.productDetails.selectedOptions, 'id');

            var counter = 0,
                optionProductImages = [];

            _.forEach(selectedOptions, function(item) {

                _.forEach(scope.images, function(row, index) {
                
                    if (item === row.product_option_values_id) {
                        counter++;
                        row.counter = counter;
                        optionProductImages.push(row);
                    }
                
                 });

            })

			__Form.process({
		    	apiURL      :'cart.product.qty.update',
		    	productID   : productID
		    }, scope).success(function(responseData) {

		        appServices.processResponse(
		            responseData,
		            null, 
		            function(reactionCode) {

		            	var requestData = responseData.data;

		            	scope.productDetails.basePriceWithAddonPrice = requestData.formatedTotalPrice;
	                    
		            	scope.productDetails.priceDetails         = {
		        			'option' 	 : scope.productDetails.selectedOptions,
		        			'total' 	 : requestData.totalPrice,
		        			'base_price' : requestData.base_price
		            	}

                        scope.productDiscount = requestData.productDiscount;

                        var $discount = $compile(__Utils.template('#discountDetailsTemplate', scope.productDiscount))(scope);
                        scope.discountDetailsHtml = $discount.html();

						scope.productData.isCartExist = requestData.isCartExist;

		            	if (requestData.qtyCart > 0 ) {
							scope.productData.quantity = requestData.qtyCart;
							scope.btnQuantity          = requestData.qtyCart;
						} else {
							scope.btnQuantity = 0;
							scope.productData.quantity = 1;
						}
		            }
		        );


            });
            
 			if (optionProductImages.length > 0) {

			    angular.element('.lw-main-slider-container').replaceWith($compile(__Utils.template('#lwMainSliderRow', {
	                'images' : optionProductImages,
	                'firstImage' : _.get(optionProductImages, 0)
	            }))($scope));

                scope.prepareZoomImage();
                
 			} else if(optionProductImages.length == 0) {

                angular.element('.lw-main-slider-container').replaceWith($compile(__Utils.template('#lwMainSliderRow', {
                    'images' : scope.defaultImages,
                    'firstImage' : _.get(scope.defaultImages, 0)
                }))($scope));

                scope.prepareZoomImage();
            }

		};

	}
	 	
})();;
(function() {
'use strict';
	
	/*
	 ProductDetailsDialogController
	-------------------------------------------------------------------------- */
	
	angular
        .module('PublicApp.productDetailsDialog', [])
        .controller('ProductDetailsDialogController', 	[
            '$scope', 
            '__DataStore', 
            'appServices',
            '$stateParams',
            '__Form',
            '__Utils',
			'$q',
			'GetProductDetails',
            '$compile',
            '$rootScope',
            '__Auth',
            ProductDetailsDialogController 
	 	]);

	/**
	 * ProductDetailsDialogController for product details application
	 * 
	 * @inject $scope
	 * @inject __DataStore
	 * @inject appServices
	 * @inject $stateParams
	 * @inject __Form
	 * @inject __Utils
	 * 
	 * @return void
	 *-------------------------------------------------------- */

	function ProductDetailsDialogController($scope, __DataStore, appServices,
	  $stateParams, __Form, __Utils, $q, GetProductDetails, $compile, $rootScope, __Auth) {

		var scope = this;

        	scope.categoryID 	= $scope.ngDialogData.categoryID;
        	scope.productID 	= $scope.ngDialogData.productID;
        	scope.pageType 		= $scope.ngDialogData.pageType;
        	scope.dialogStatus  = $scope.ngDialogData.dialogStatus;
            scope.isLoggedIn    = __Auth.isLoggedIn();

            __Auth.refresh(function(authInfo) {
                scope.auth_info = authInfo;
            });

			scope = __Form.setup(scope, 'add_product_in_cart_form', 'productData', {
	            modelUpdateWatcher:false
	        });
	    
			scope.pageStatus 	= false;
	 		scope.optionLength 	= false;   

            //add product compare function start
            scope.addProductCompare = function(productId) {

                __DataStore.post({
                    'apiURL' :'product.compare.add',
                    'productId' : productId
                }, scope).success(function(responseData) {
                  
                    var requestData = responseData.data;
                    appServices.processResponse(responseData, null , function(reactionCode) {
                        if (reactionCode == 1) {
                            scope.productDetails.addedInCompareList = true;
                            var countCompareProduct = requestData.countCompareProduct;
                            $scope.$emit('lw.product.compare.count', true);
                        }
                       
                    });   

                });
            }
            //add product compare function end

            //remove product compare function start
            scope.removeProductCompare = function(productId) {
                __DataStore.post({
                    'apiURL' :'product.compare.remove',
                    'productId' : productId
                }, scope).success(function(responseData) {
                    var requestData = responseData.data;
                    appServices.processResponse(responseData, null , function(reactionCode) {

                        if (reactionCode == 1) {
                            scope.productDetails.addedInCompareList = false;
                            var countCompareProduct = requestData.countCompareProduct;
                            $scope.$emit('lw.product.compare.count', true);
                        }
                    });   

                });
            }
            //remove product compare function end


			var requestData   = GetProductDetails.data.details;
       
				scope.itemRating 	 = requestData.itemRating;
                scope.productStatus = requestData.productStatus;
				scope.enableRating  = requestData.enableRating;
				scope.enableRatingReview = requestData.enableRatingReview;
				scope.restrictAddRating = requestData.restrictAddRating;
				scope.enableRatingModification = requestData.enableRatingModification;
				scope.isPurchsedProduct  = requestData.isPurchsedProduct;
                scope.isAddedInWishlist = requestData.isAddedInWishlist;
                scope.productSpecExists = requestData.productSpecExists;
				scope.productDetails = requestData;
                scope.launchingDate = requestData.launchingDate;
                scope.images = requestData.allImages;
                scope.defaultImages = requestData.defaultImages;
                scope.primaryImages = requestData.primaryImages;
                scope.isEnableReating = function() { 

                scope.isEnableRatingModification = true;
                
                // this condition check used for enable rating modification
                if (_.isEmpty(scope.itemRating.selfRating)
                    && scope.enableRatingModification == false) {
                
                    scope.isEnableRatingModification = false;

                } else if (!_.isEmpty(scope.itemRating.selfRating)
                            && scope.enableRatingModification == true) {
                
                    scope.isEnableRatingModification = false;

                } else if (_.isEmpty(scope.itemRating.selfRating)
                            && scope.enableRatingModification == true) {
                
                    scope.isEnableRatingModification = false;

                }  else if (scope.restrictAddRating == true
                            && scope.enableRatingModification == true) {
                
                    scope.isEnableRatingModification = true;
                }
                
                if (scope.restrictAddRating == true 
                    && scope.isPurchsedProduct == false) {
                
                     scope.isEnableRatingModification = true;
                }
                
            };
            scope.isEnableReating();

            if (scope.enableRatingModification == true && scope.productStatus != 1) {
                
                scope.isEnableRatingModification = true;
            }

             //product rating bar start
            _.delay(function() {
                $(function() {
                    $('#lw-bar-rating').barrating('show', {
                        theme: 'fontawesome-stars-o',
                        showValues: false,
                        allowEmpty: false,
                        readonly: scope.isEnableRatingModification,
                        initialRating: scope.itemRating.selfRating,
                        onSelect: function(value, text, event) {
                            if (typeof(event) !== 'undefined') {
                              // rating was selected by a user
                              // console.log(value);
                                if (!window.__appImmutables.auth_info.authorized) {
                                    appServices.loginRequiredDialog('login-dialog', null, function(promiseObj) {
                                        if (promiseObj == true) {
                                            scope.submitRatingAndReview(value);
                                        }
                                    });  
                                    // appServices.showDialog({},
                                    //     {
                                    //         templateUrl : __globals.getTemplateURL('user.login-dialog')
                                    //     },
                                    // function(promiseObj) {
                                    //     if (promiseObj.value.login_success == true) {
                                    //         scope.submitRatingAndReview(value);
                                    //     }
                                    // });

                                } else if (scope.enableRating == true) {
                                    scope.submitRatingAndReview(value);
                                }
                            } 
                        }
                    });
                });
            }, 200);
            //product rating bar end  

				// hold product related categories data & make comma Separate
				scope.productDetails.categories = requestData.categories;

	          	scope.productDetails.basePriceWithAddonPrice = requestData.newTotalPrice;

                scope.productDiscount = requestData.productDiscount;

                $scope.$on('ngDialog.opened', function (e, $dialog) {
                    var $discount = $compile(__Utils.template('#discountDetailsTemplate', scope.productDiscount))(scope);
                    scope.discountDetailsHtml = $discount.html(); 
                });

                
                //__pr($discount);
                //scope.discountDetailsHtml = $discount.html();

				scope.newTotalPriceCount = requestData.newTotalPriceCount;

				scope.productDetails.selectedOptions = requestData.getSelectedOptions;

				scope.oldPrice = scope.productDetails.old_price;

				if (scope.productDetails) {
		        	scope.productData.name = requestData.name;
		        }
				
				if(!_.isEmpty(scope.productDetails.selectedOptions)) {
					scope.optionLength = true;
				}

				scope.productDetails.priceDetails = {
	    			'option' 	 : requestData.getSelectedOptions,
	    			'total' 	 : requestData.getPrice.total,
	    			'base_price' : requestData.getPrice.base_price
	        	};

	        	// product quantity
				if (requestData.qtyCart > 0) {

					scope.productData.quantity = requestData.qtyCart;
					scope.checkNumber          = true;
					
				} else {
					scope.productData.quantity = 1;
				}

				scope.btnQuantity = requestData.qtyCart;
				scope.productData.isCartExist = requestData.isCartExist;

				scope.pageStatus = true;

               /* _.delay(function() {
                    $('#lwRateIt .rateit').bind('rated reset', function (e) {
                        e.preventDefault();
                        var ri = $(this);
                        var value = ri.rateit('value');               
                    });
                }, 1000);*/
                

			/**
	          * Submit rating action
	          *
	          * @return void
	          *---------------------------------------------------------------- */
	        scope.submitRatingAndReview = function (value) {

	            if (!_.isEmpty(value)) {
                    __DataStore.post({
                        'apiURL' :'product.rating.add',
                        'productId' : scope.productID,
                        'rate'   : value
                    }, scope).success(function(responseData) {
                        
                        appServices.processResponse(responseData, null , function() {
   
                            scope.itemRating = responseData.data.itemRating;
                            scope.rating =  parseFloat(scope.itemRating.rate);

                            // manage product list rating show dynamic start
                            var ratingElement = $('#lw-product-rating-'+scope.productID).length;

                            var decimal = '',
                                formatItemRating = '',
                                rated = Math.floor(scope.rating), 
                                unrated = Math.floor(5 - rated);
                                 
                            if (scope.rating % 1 != 0) {
                                decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                                unrated = unrated - 1;
                            }
                           
                            //create format string
                            formatItemRating = (_.repeat('<i class="fa fa-star lw-color-gold"></i>', rated)+
                                    decimal+
                                    _.repeat('<i class="fa fa-star lw-color-gray"></i>', unrated));

                            //check item rating null or not
                            if (ratingElement > 0) {
                               
                                var element = $('#lw-product-rating-'+scope.productID).html("");

                                string = '<span class="lw-product-rating-avg border">'+formatItemRating+'</span><span class="text-muted">'+scope.itemRating.rate+'<small>('+scope.itemRating.totalVotes+')</small></span>';

                                $(element).html(string);

                            } else if (ratingElement == 0) {
 
                                var element = $('#lw-product-star-rating-'+scope.productID).html(""),

                                string = '<div id="lw-product-rating-'+scope.productID+'"><span class="lw-product-rating-avg border">'+formatItemRating+'</span><span class="text-muted">'+scope.itemRating.rate+'<small>('+scope.itemRating.totalVotes+')</small></span></div>';
                              
                                $(element).html(string);
                            }
                            // manage product list rating show dynamic end
                          
                            // if is allow for review
                            if (scope.itemRating.showDialog === true) {
                                scope.showAddReviewDialog(responseData.data);
                            }

                            scope.isEnableReating();

                        });   

                    });
                }
	        };

	        /**
	          * Show user login dialog
	          *
	          * @return void
	          *---------------------------------------------------------------- */

	        scope.showAddReviewDialog = function (responseData) {
	            
	            appServices.showDialog({
	                'reviewData' : responseData,
	                'productId'  : scope.productID
	            },
	            {   
	                templateUrl : __globals.getTemplateURL('product.add-review-dialog'),
					controller : "AddReviewDialogController as AddReviewDialogCtrl",
					resolve : {
						GetReviewData : function() {

				            //create a differed object          
				            var defferedObject = $q.defer();   

				            __DataStore.fetch({
				                    'apiURL'    : 'product.review.support.data',
				                    'productId' :  scope.productID
				                 }).success(function(responseData) {
				                                
				                appServices.processResponse(responseData, null, function(reactionCode) {

				                    //this method calls when the require        
				                    //work has completed successfully        
				                    //and results are returned to client        
				                    defferedObject.resolve(responseData);  

				                }); 

				            });       

				           //return promise to caller          
				           return defferedObject.promise; 
				        }
					}
	            },
	            function(promiseObj) {

	            });
	        
	        };

	        /**
	          * Show Ratings of this item
	          *
	          * @return void
	          *---------------------------------------------------------------- */

	        scope.showRatingAndReviews = function () {

                appServices.showDialog({
                    'itemTitle' : scope.productData.name
                },
                {   
                    templateUrl : __globals.getTemplateURL('product.ratings-and-reviews-dialog'),
					controller : "ItemRatingAndReviewDialogController as RatingAndReviewCtrl",
					resolve : {
						GetRatingAndReviewData : function() {

				            //create a differed object          
				            var defferedObject = $q.defer();   

				            __DataStore.fetch({
				                    'apiURL'    : 'product.rating_review.get_support_data',
				                    'productId' :  scope.productID
				                 }).success(function(responseData) {
				                                
				                appServices.processResponse(responseData, null, function(reactionCode) {

				                    //this method calls when the require        
				                    //work has completed successfully        
				                    //and results are returned to client        
				                    defferedObject.resolve(responseData);  

				                }); 

				            });       

				           //return promise to caller          
				           return defferedObject.promise; 
				        }
					}
                },
                function(promiseObj) {

                });
	        };

	         /**
			* Take product quantity action.
			*
			* @param {Number} status
			* @param {Number} qty
			* @return void
			*
			*------------------------------------------------------------------------ */

			scope.getQtyAction = function(status, quantity) {

				// if product status is true 
				if (status == true) {
					(quantity == undefined || isNaN(quantity)) 
					? scope.productData.quantity = 1 
					: scope.productData.quantity = quantity + 1;

				} else {
					// product quantity must be greterThan -1
					var newValue = (quantity - 1);
					(newValue <= 0) 
					? scope.productData.quantity = 1 
					: scope.productData.quantity = newValue;
				}

			};

			/**
			  * Add product as a cart item
			  *
			  * @return void
			  *---------------------------------------------------------------- */
			scope.cartAction = false;

			scope.addToCart  =  function() { 
				
			    __Form.process({
			        apiURL      : 'cart.add.item',
			        productID   : scope.productID
			    }, scope).success(function(responseData) {
			    	
			    	scope.cartAction = true;

			    	var requestData = responseData.data;

			        appServices.processResponse(
			            responseData,
			            null, 
			            function() {
			            	
			            	scope.data = {
			                  'cart_string': responseData.data.cartItems,
			                  'status'     : true
			                }
			                scope.updateCartItem(scope.productID, true);
			                $scope.$emit('lw.update.cart.string', scope.data);
			            }
			        );

			    });
			};

            /**
              * Add to Wish-list
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.addToWishlist = function() {

                if (!window.__appImmutables.auth_info.authorized) {
                    
                    appServices.loginRequiredDialog('login-dialog', null, function(result) {});

                } else {
                    __DataStore.post({
                        'apiURL'    : 'product.wishlist.add_process',
                        'productId' : scope.productID
                    }, scope).success(function(responseData) {
                        var requestData = responseData.data;
                        if (requestData.showLoggedInDialog) {
                            appServices.loginRequiredDialog('login-dialog', null, function(result) {});
                        }
                        appServices.processResponse(responseData, null, function(reactionCode) {
                            if (reactionCode == 1) {
                                $('.lw-remove-wishlist-'+scope.productID).show();
                                $('.lw-add-wishlist-'+scope.productID).hide();

                                scope.isAddedInWishlist = true;
                            }                        
                        });
                    });
                }

            };

            /**
              * Remove from Wish-list
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.removeFromWishlist = function() {
                __DataStore.post({
                    'apiURL'    : 'product.wishlist.remove_process',
                    'productId' : scope.productID
                }, scope).success(function(responseData) {
                    var requestData = responseData.data;
                    if (requestData.showLoggedInDialog) {
                        appServices.loginRequiredDialog('login-dialog', null, function(result) {});
                    }
                    
                    appServices.processResponse(responseData, null, function(reactionCode) {

                        if (reactionCode == 1) {
                            $('.lw-add-wishlist-'+scope.productID).show();
                            $('.lw-remove-wishlist-'+scope.productID).hide();
                            scope.isAddedInWishlist = false;
                        }                       
                    });
                });
            };

			/**
			  * Add product as a cart item
			  *
			  * @return void
			  *---------------------------------------------------------------- */

			scope.updateCartItem  =  function(productID, option) { 

				var checkOption    = true;

				scope.addonPrices  = [];
				
				if (scope.productData.options != undefined) {
			    	// hold option of addon_price related data 
			    	scope.productDetails.selectedOptions = scope.productData.options[productID];
			    	angular.forEach(scope.productData.options[productID], 
			    		function( value, key ) {
					    	this.push({
					    			option_name:key,
					    		 	value_name :value.name,
					    		  	addon_price:value.addon_price_format
					    	});
					}, scope.addonPrices );

			    	// hold empty options data 
					angular.forEach(scope.productData.options[productID], 
						function( value, key ) {

							if (value == '' ) {
								checkOption = false;
							}
							
					}, checkOption );
				}

                var selectedOptions = _.pluck(scope.productDetails.selectedOptions, 'id');

                var counter = 0,
                    optionProductImages = [];

                _.forEach(selectedOptions, function(item) {
                    _.forEach(scope.images, function(row, index) {                    
                        if (item === row.product_option_values_id) {
                            counter++;
                            row.counter = counter;
                            optionProductImages.push(row);
                        }                    
                     });
                });
                
                if (optionProductImages.length > 0) {

                    angular.element('.lw-main-slider-container').replaceWith($compile(__Utils.template('#lwMainSliderRow', {
                        'images' : optionProductImages,
                        'firstImage' : _.get(optionProductImages, 0)
                    }))($scope));

                    //scope.prepareZoomImage();
                    
                 } else if(optionProductImages.length == 0) {

                    angular.element('.lw-main-slider-container').replaceWith($compile(__Utils.template('#lwMainSliderRow', {
                        'images' : scope.defaultImages,
                        'firstImage' : _.get(scope.defaultImages, 0)
                    }))($scope));

                    //scope.prepareZoomImage();
                }

				__Form.process({
			    	apiURL      :'cart.product.qty.update',
			    	productID   : productID
			    }, scope).success(function(responseData) {

			        appServices.processResponse(
			            responseData,
			            null, 
			            function(reactionCode) {

			            	var requestData = responseData.data;

			            	scope.productDetails.basePriceWithAddonPrice = requestData.totalPrice;
			            	scope.productDetails.priceDetails         = {
			        			'option' 	 : scope.productDetails.selectedOptions,
			        			'total' 	 : requestData.totalPrice,
			        			'base_price' : requestData.base_price
			            	}

                            scope.productDiscount = requestData.productDiscount;

                            var $discount = $compile(__Utils.template('#discountDetailsTemplate', scope.productDiscount))(scope);
                            scope.discountDetailsHtml = $discount.html();

							scope.productData.isCartExist = requestData.isCartExist;

			            	if (requestData.qtyCart > 0 ) {
								scope.productData.quantity = requestData.qtyCart;
								scope.btnQuantity          = requestData.qtyCart;
							} else {
								scope.btnQuantity = 0;
								scope.productData.quantity = 1;
							}
			            }
			        );


			    });

			};

            scope.previewImageUrl = '';
            scope.changeImage = function(imageUrl, e, index) {
                e.preventDefault();
                scope.currentImageIndex = index;
                scope.previewImageUrl = imageUrl;
                var triggerEl =  document.querySelector('img.lw-zoom-image');
                $(triggerEl).attr('src', imageUrl);
                event.stopImmediatePropagation();
            }

            /**
        * open cart model
        *
        * @param object param1 type 
        *
        * @return void
        *---------------------------------------------------------------- */
        
        scope.openCartDialog  =  function(status) 
        {
            $rootScope.$broadcast('lw.isCart.dialog', { dialog: status });

            //check if shopping cart dialog is open, if true then will not re-open again
            if (!($("html").hasClass("lw-shopping-cart-dialog-active"))) {

                $('html').addClass('lw-shopping-cart-dialog-active');

                appServices.showDialog(scope,
                {
                    templateUrl : __globals.getTemplateURL('shoppingCart.cart-view')
                },
                function(promiseObj) {
                    $('html').removeClass('lw-shopping-cart-dialog-active');
                });

                $rootScope.$on('ngDialog.opened', function (e, $dialog) {

                    if ($('div.lw-shopping-cart-dialog-content').length) {
                        $('div.ngdialog-content').addClass('lw-shopping-cart-dialog');
                    }

                });
            }
        };
	};

})();;
(function() {
'use strict';
    
    /*
     DisplayPageDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.page.details', [])
        .controller('DisplayPageDetailsController',   [
        	'$scope',
        	'$state',
            'appServices',
            '__DataStore',
            DisplayPageDetailsController 
        ]);

    /**
      * DisplayPageDetailsController handle add pages form
      * @inject $scope
      * @inject $state
      * @inject appServices
      * @inject __DataStore
      * 
      * @return void
      *-------------------------------------------------------- */

    function DisplayPageDetailsController($scope, $state, appServices, __DataStore) {
    	
        var scope   = this,
        	URL     = {	
        			'apiURL' :'display.page.details',
        			'pageID' : $state.params.pageID
        		};


	 	var activepages = __globals.getActivePagesData(),
	 			pages   = __globals.findParents(activepages, $state.params.pageID),
		    allPages    = pages.reverse(),
			totalPages  = allPages.length - 1;
			
			// get last page 
			_.forEach(pages, function(value, index) {

				if (index == totalPages) {
					scope.last_page = value.title;
				}
			});

			if (totalPages === 0) {

					// inject breadcrumb service
				 	appServices.updateBreadcrumbs([ 
						{   items     : '',
							last_item : scope.last_page 
						}
			 		], $scope);

			} else {

				// initial function remove last index and return remaining indexes
				_.forEach(_.initial(allPages), function(value, index) {

					//inject breadcrumb service
				 	appServices.updateBreadcrumbs([ 
						{   items     : [{
								item: value.title,
								url : value.link 
										? value.link 
										: $state.href('display_page_details',
											{
												'pageID'   :value.key,
												'pageTitle':__globals.slug(value.title)
											})
							}],
							last_item : scope.last_page 
						}
			 		], $scope);
				});
			}

      	/**
      	* get pages info
      	* 
      	* @return void
      	*-------------------------------------------------------- */

        scope.getPageInfo = function() {

        	__DataStore.fetch(URL, scope)
            	.success(function(responseData) {

            	appServices.processResponse(responseData, null, function() {

            		var requestData    = responseData.data;
            		
            	 	scope.pageDetails = requestData;
            	});    

     		});
        };
        scope.getPageInfo();
        
    };

})();;
(function() {
'use strict';
    
    /*
     CartController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.ShoppingCart.cart', [])
        .controller('CartController',   [
        	'$rootScope',
        	'$scope',
            'appServices',
            '__DataStore',
            '__Form',
            '__Auth',
            '__Utils',
            '$compile',
            CartController 
        ]);

    /**
      * CartController handle add pages form
      *
      * @inject $rootScope
      * @inject $scope
      * @inject appServices
      * @inject __DataStore
      * @inject __Form
      * @inject 
      * 
      * @return void
      *-------------------------------------------------------- */

    function CartController($rootScope, $scope, appServices, __DataStore, __Form, __Auth, __Utils, $compile) {
    	
        var scope   	= this;
        
        scope.userInfo  = __Auth.authInfo();
        scope.isLoggedIn = __Auth.isLoggedIn();

        //authorized
        scope = __Form.setup(scope, 'update_cart_form', 'cartData', {
        	modelUpdateWatcher : false
        });

        scope.cartDataStatus = false;

        // If user is not logged in then open logged in dialog
        scope.openLoginDialog = function() {
            var responseData = {
                'guestOrder' : true
            };

            $rootScope.$broadcast('lw-open-login-dialog', {'data' : responseData});
        };

        /**
        * Open Discount details
        *
        * @return void
        *------------------------------------------------------------------------ */
        scope.openDiscountDetails = function() {
            $('#lw-discount-detail-table').slideToggle();           
        }

      	/**
	    * Take cart content action action
	    *
	    * @return void
	    *------------------------------------------------------------------------ */

	    scope.cartData = {};
	    scope.disabledStatus = false;
	    scope.dataExistStatus = false;

	    scope.getContentCart = function() {

	        __DataStore
	        	.fetch('cart.get.data', {'fresh' : true})
            	.success(
            	function(responseData) {

            		appServices.processResponse(
            			responseData,
            			null, 
            		function(reactionCode) {

            			var requestData 	 		= responseData.data;
            			scope.anyCartItemInvalid 	= requestData.isValidItem; // any cart item invalid
                        scope.cartDiscount          = requestData.orderDiscount; 
                        scope.cartTotalBeforeDiscount = requestData.cartTotalBeforeDiscount;
            			scope.cartData.items 		= requestData.cartItems;
            			scope.cartData.totalPrice  	= requestData.total;
            			scope.cartItemStatus  		= requestData.cartItemStatus;
            			scope.currentRoute			= requestData.currentRoute;
                        scope.isCurrencyMismatch    = requestData.isCurrencyMismatch;
            			scope   = __Form.updateModel(scope, scope.cartData);


            			if (scope.currentRoute == document.location.href) {
	        				var pageType	 	= true;
	        			} else {
	        				var pageType	 	= false;
	        			}

                        if (!pageType) {
                            _.defer(function () {
                                var $discount = $compile(__Utils.template('#discountDetailsDialogTemplate', scope.cartDiscount))(scope);
                                scope.discountDetailsHtml = $discount.html();
                            });
                        } else {
                            var $discount = $compile(__Utils.template('#discountDetailsDialogTemplate', scope.cartDiscount))(scope);
                            scope.discountDetailsHtml = $discount.html();
                        }
	        			
	        			scope.pageType = pageType;

            			scope.data = {
		                  'routeStatus' : pageType
		                }

            			if (_.isEmpty(requestData.cartItems) || requestData.isValidItem == false) {
            				scope.disabledStatus = false;
            			} else {
            				scope.disabledStatus = true;
            			}

            			scope.cartDataStatus = true;
            			$scope.$emit('lw.current.route.name', scope.data);
            		});
     		});
	    };
	    scope.getContentCart();

        /**
        * Refresh product 
        *
        * @return void
        * 
        *------------------------------------------------------------------------ */
        scope.refreshProduct = function(item) {
            __DataStore.post('cart.refresh.product', item)
                .success(function(responseData) {
                    appServices.processResponse(responseData, null, function() {
                        scope.getContentCart();
                    });
                });
        };

	    /**
	    * update product cart quantity in cart
	    *
	    * @return void
	    * 
	    *------------------------------------------------------------------------ */
	    var timer;
	    scope.updateQuantity = function(status, rowID, qty, newPrice) 
	    {	
	        // filter quantity
	        var filterQty = Number(Math.round(qty));
	        scope.cartData.items[rowID].qtyStatus  = false;
            scope.cartQuantity = filterQty;

	        if (status == 'eventUp') {

	        	
	            if (filterQty > 1) {

	            	if (filterQty > 99999) {
		        		scope.cartData.items[rowID].qty = filterQty;
		        	};
		        	
	            } else {
	            	
	                scope.cartData.items[rowID].qty = 1;
	                scope.cartData.items[rowID].qtyStatus  = true;
	            }

	        } else {

	          if (status == true) {
	                scope.cartData.items[rowID].qty = filterQty + 1;
	            } else {
	                var newValue = (filterQty - 1);
	                  (newValue <= 0) 
	                  ? scope.cartData.items[rowID].qty = 1 
	                  : scope.cartData.items[rowID].qty = newValue;
	          }

	        }

	        if (rowID) {
	        	// clear time
	            window.clearTimeout(timer);

	            // in cart table change quantity fire request per 3 sec
	            timer = window.setTimeout(function(){
	            	
			        __Form.process({
			        		'apiURL'  : 'cart.update.qty',
			        		'itemID'  : rowID
			        	}, scope)
			        	  .success(function(responseData) {

			                appServices.processResponse(
			                    responseData,
			                    null, 
			                    function() {

			                    	var requestData = responseData.data;

                                    scope.cartDiscount          = requestData.orderDiscount;
                                    scope.cartTotalBeforeDiscount = requestData.cartTotalBeforeDiscount;
                                    if (!scope.pageType) {
                                        _.defer(function () {
                                            var $discount = $compile(__Utils.template('#discountDetailsDialogTemplate', scope.cartDiscount))(scope);
                                            scope.discountDetailsHtml = $discount.html();
                                        });
                                    } else {
                                        var $discount = $compile(__Utils.template('#discountDetailsDialogTemplate', scope.cartDiscount))(scope);
                                        scope.discountDetailsHtml = $discount.html();
                                    }                                     

			                    	// for new price scope
			                    	scope.cartData.items[rowID].new_price    = requestData.new_price;
			                    	scope.cartData.items[rowID].new_subTotal = requestData.new_subTotal;
			                    	scope.cartData.items[rowID].qty          = requestData.qty;
			                    	scope.cartData.items[rowID].price        = requestData.price;
			                    	scope.cartData.totalPrice  = requestData.total;
			                    	scope.cartData.items[rowID].qtyStatus  = true;

			                    	scope.data = {
					                  'cart_string': requestData.cartItems,
					                  'status'     : true
					                }
					                scope.qtyStatus = true;
					               // scope.getContentCart();
					                $scope.$emit('lw.update.cart.string', scope.data);
					                $rootScope.$emit('remove.cart.row', {'status':true});

									$scope.$emit('lw.cart_update', {
										'qty' : requestData.qty,
										'pid' : scope.cartData.items[rowID].id,
										'action' : 1
									});
			                    }
			                );
		            });
              
		        },500); 
            }												
	    };

	    /**
	  	  * Close dialog and return promise object
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
	  	
  	  	scope.cancel = function() {
  	  		$scope.closeThisDialog();
  	  	};

  	  	/**
	  	  * remove cart item action
	  	  *
	  	  * @return void
	  	  *---------------------------------------------------------------- */
	  	scope.removeCartItem = function(itemID) {

	  		// get data using angular $http.get() method
			__DataStore.post({
		          'apiURL' : 'cart.remove.item',
		          'itemID' : itemID
			}, scope).success(function(responseData) {
			  
			  	appServices.processResponse(responseData, null,
			        function(reactionCode) {

						$scope.$emit('lw.cart_update', {
							'qty' : 0,
							'pid' : scope.cartData.items[itemID].id,
							'action' : 2
						});

		               	$rootScope.$emit('changeItems',{
		                   status: true
		               	});

		              	scope.data = {
			                'cart_string': responseData.data.cartItems,
			                'status'     : true
		              	}

		              	$scope.$emit('lw.update.cart.string', scope.data);

		              	$( '#rowid_'+itemID ).fadeOut('slow',function() {
		                    //$(this).remove();
		                });

		              	scope.getContentCart();
			        }
			  	); 
			  
			});
	  	};

  	  	/**
	    * remove all items from cart item action
	    *
	    * @return void
	    *------------------------------------------------------------------------ */
	    scope.removeAllItemsItem = function() {

			var removedPIds = [];

			if (!_.isEmpty()) {

				_.each(scope.cartData.items, function(value, key) {
					removedPIds.push(value);	
				});
			}

	        // get data using angular $http.get() method
	        __DataStore.post('cart.remove.all.items',scope).success(function(responseData) {
	        	
				appServices.processResponse(responseData, null,
                        function(reactionCode) {
						
						$scope.$emit('lw.cart_update', {
							'action' : 3, 'removedPIds' : removedPIds 
						});
			 			
                    	scope.cartData.items = '';

						scope.data = {
		                  'cart_string': responseData.data.cartItems,
		                  'status'     : true
		                }

		                $scope.$emit('lw.update.cart.string', scope.data);

		                $rootScope.$emit('remove.cart.row', {'status':true});
		                
		                $rootScope.$emit('changeItems',{
			 				status: true
			 			});
			 			
                    	scope.getContentCart();
                    }
                ); 
	            
    	    });

	    };
        
    };

})();;
(function() {
'use strict';
    
    /*
     OrderSummaryController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.ShoppingCart.orderSummary', [])
        .controller('OrderSummaryController',   [
        	'$scope',
            '__Form',
            '__DataStore',
            'appServices',
            '__Utils',
            '$rootScope',
            'appNotify',
            '__Auth',
            '$compile',
            OrderSummaryController 
        ])
        .controller('AlertDialogController',   [
            '$scope',
            AlertDialogController 
        ]);

    /**
      * OrderSummaryController handle cart order
      *
      * @inject $scope
      * @inject __Form
      * @inject __DataStore
      * @inject appServices
      * @inject __Utils
      * @inject $rootScope
      * 
      * @return void
      *-------------------------------------------------------- */

    function OrderSummaryController($scope, __Form, __DataStore,
    	appServices, __Utils, $rootScope, appNotify, __Auth, $compile) {
    	
        var scope   			= this;
        	scope 				= __Form.setup(scope, 'cart_order_form', 'orderData', {
        		modelUpdateWatcher : false
        	});
        
            scope.isLoggedIn = __Auth.isLoggedIn();

            $rootScope.$on('lw.auth.event.reset', function (event, authInfo) {
                scope.isLoggedIn = authInfo.authorized;   
            });
          
        	scope.couponMessage 		= false;
        	scope.orderData.addressID   = null;
        	scope.orderData.addressID1  = null;
        	scope.pageStatus  			= false;
            scope.orderData.use_as_billing = true;
            scope.orderProcessDisableStatus = true;
            scope.userIsExist = false;
            scope.shippingMethods = [];

            scope.paymentMethodConfig = __globals.getSelectizeOptions({
                maxItems        : 1,
                searchField     : ['name'],
                plugins         : ['remove_button'],
            });

            //order process wizard function
            $(document).ready(function(){
                $('#lw-smart-wizard').smartWizard({
                    transitionEffect: 'fade',
                });

                // Initialize the leaveStep event
              $("#lw-smart-wizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
          
                var lwValidationMsg = $('#lwValidationMsg');
                var lwValidationCustomMsg = $('#lwValidationCustomMsg');
                
				$('html').animate({
				scrollTop: $("#elementtoScrollToID").offset().top
				}, 500);

                	if(stepDirection == 'forward'  && stepNumber == 1) {
                        if (_.isUndefined(scope.orderData.shipping_method) && (scope.shippingMethods.length > 0 && !scope.orderSupportData.shipping.info)) {
                            var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('Shipping Method'));
                              
                            appNotify.error(message);
                                return false;
                        }
                        
                		if (_.isEmpty(scope.orderSupportData.cartItems)) {
                           var message = lwValidationCustomMsg.attr('data-message').replace('__validationMessage__' , unescape('No products in cart'));
                          
                            appNotify.error(message);
                            return false;
                       }  
                	}

                    if(stepDirection == 'forward'  && stepNumber == 0) {
                        
                        if (scope.orderSupportData.itemIsInvalid) {
                            var message = lwValidationCustomMsg.attr('data-message').replace('__validationMessage__' , unescape('Product(s) not available in cart'));
                          
                            appNotify.error(message);
                            return false;
                        }

                        if (scope.isLoggedIn) {
                            if (_.isEmpty(scope.orderData.fullName)) {
                               var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('full Name'));
                              
                                appNotify.error(message);
                                return false;

                              //shipping address field is required.
                            } else if (_.isEmpty(scope.orderSupportData.shippingAddress)) {
                               var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping address'));
                              
                                appNotify.error(message);
                                return false;
                            }   

                            //biiling address field is required.
                            if (scope.orderSupportData.sameAddress == false) {
                                if (_.isEmpty(scope.orderSupportData.billingAddress)) {
                                 
                                   var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing address'));
                              
                                    appNotify.error(message);
                                    return false;
                                }
                            }
                        } 

                        if (!scope.isLoggedIn) {
                            //shipping address validation
                            if (_.isEmpty(scope.orderData.fullName)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('full Name'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.email)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('email'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_address_type) && !_.isNumber(scope.orderData.shipping_address_type)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping type '));
                              
                                appNotify.error(message);
                                return false;
                                
                            } else if ( _.isEmpty(scope.orderData.shipping_address_line_1)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping address 1'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_address_line_2)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping address 2'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_city)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping city'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_state)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping state'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_pin_code)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping pin code'));
                              
                                appNotify.error(message);
                                return false;

                            } else if (_.isEmpty(scope.orderData.shipping_country)) {
                                var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('shipping country'));
                              
                                appNotify.error(message);
                                return false;
                            } 

                            //billing address validation
                            if (scope.orderData.use_as_billing == false) {
                                if (_.isEmpty(scope.orderData.shipping_address_type) && !_.isNumber(scope.orderData.billing_address_type)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing address type '));
                                  
                                    appNotify.error(message);
                                    return false;
                                    
                                } else if (_.isEmpty(scope.orderData.billing_address_line_1)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing address 1'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } else if (_.isEmpty(scope.orderData.billing_address_line_2)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing address 2'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } else if (_.isEmpty(scope.orderData.billing_city)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing city'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } else if (_.isEmpty(scope.orderData.billing_state)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing state'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } else if (_.isEmpty(scope.orderData.billing_pin_code)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing pin code'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } else if (_.isEmpty(scope.orderData.billing_country)) {
                                    var message = lwValidationMsg.attr('data-message').replace('__validationMessage__' , unescape('billing country'));
                                  
                                    appNotify.error(message);
                                    return false;
                                } 
                            }
                        }
                    }
                 //return confirm("Do you want to leave the step "+stepNumber+"?");
              });
            });
            //order process wizard function

          
        /**
        * get cart order detail.
        *
        * @return void
        *------------------------------------------------------------------------ */

        scope.getCartOrderDetails = function(addressID, addressID1, couponCode, shippingCountryId, billingCountryId) {

        	//  Check if country is exist
        	if (!_.isUndefined(addressID)) {
        		scope.addressID = addressID;
        	} else {
        		scope.addressID = null;
        	}

        	if (!_.isUndefined(addressID1)) {
        		scope.addressID1 = addressID1;
        	} else {
        		scope.addressID1 = null;
        	}

        	if (!_.isUndefined(couponCode)) {
        		scope.couponCode = couponCode;
        	} else {
        		scope.couponCode = null;
        	}

            if (!_.isUndefined(shippingCountryId) && !_.isEmpty(shippingCountryId)) {
                scope.shippingCountryId = shippingCountryId;
            } else {
                scope.shippingCountryId = null;
            }

            if (!_.isUndefined(billingCountryId) && !_.isEmpty(billingCountryId)) {
                scope.billingCountryId = billingCountryId;
            } else {
                scope.billingCountryId = null;
            }

            scope.countries_select_config = __globals.getSelectizeOptions({
                valueField  : 'value',
                labelField  : 'text',
                searchField : [ 'text' ]  
            });

            scope.shippingCount = 0;
      
        	__DataStore.fetch({
                'apiURL'            : 'order.summary.details',
                'addressID'         : scope.addressID,
                'addressID1'        : scope.addressID1,
                'couponCode'        : scope.couponCode,
                'shippingCountryId' : scope.shippingCountryId,
                'billingCountryId'  : scope.billingCountryId,
                'useAsBilling'      : scope.orderData.use_as_billing,
                'shippingMethod'    : !_.isUndefined(scope.orderData.shipping_method) ? scope.orderData.shipping_method : null
            },{fresh:true}).success(
            	function(responseData) { 
            		appServices.processResponse(
            			responseData,
            			null, 
            		function(reactionCode) {
            			
            			var requestData  = responseData.data.orderSummaryData.data;
                        //scope.paymentMethod = __globals.generateKeyValueItems(requestData.paymentMethod);

                        if (!scope.isLoggedIn) {
                            scope.addressTypes = requestData.addressSupportData.addressTypes;
                            scope.countries = requestData.addressSupportData.countries;
                        }

                        scope.shippingCount = requestData.shipping.shippingCount;

                    	scope.orderSupportData = {
                            'paymentMethod'              : requestData.paymentMethod,
                            'cartItems' 	 			 : requestData.cartItems,
                            'itemsCount'                 : requestData.itemsCount,
                            'totalQuantity'              : requestData.totalQuantity,
                    		'itemIsInvalid' 	 	     : requestData.itemIsInvalid,
                    		'shippingAddress' 			 : requestData.shippingAddress,
                    		'sameAddress' 			 	 : requestData.sameAddress,
                    		'billingAddress' 			 : requestData.billingAddress,
                    		'shipping' 		 			 : requestData.shipping,
                    		'formatedCartTotalPrice'	 : requestData.total.base_price,
                    		'cartTotalPrice' 			 : requestData.total.totalBasePrice,
                    		'currency' 		 			 : requestData.total.currency,
                    		'afterAddShipingTotal' 		 : requestData.shipping.totalPrice,
                    		'taxses' 					 : requestData.taxses.info,
                            'formatedTotalTaxAmount'     : requestData.taxses.formatedTotalTaxAmount,
                    		'afterAddTaxTotal' 			 : requestData.taxses.totalPrice,
                    		'totalPayableAmount' 		 : requestData.totalPayableAmount, 
                            'totalPayableAmountForStripe': requestData.totalPayableAmountForStripe,
                             'totalPayableAmountForPaystack': requestData.totalPayableAmountForPaystack,
                    		'totalPayableAmountFormated' : requestData.totalPayableAmountFormated,
                    		'fullName' 					 : requestData.user.fullName,
                    		'couponData' 				 : !_.isEmpty(requestData.couponData)
                    										? requestData.couponData.couponData
                    										: null,
                            'total'                      : requestData.total,
                    		'subtotalPrice' 			 : requestData.shipping.formettedDiscountPrice,
                    		'checkoutMethod' 			 : requestData.checkoutMethod,
                    		'checkoutMethodInfo' 		 : requestData.checkoutMethodInfo,
                            'cartDiscount'               : requestData.orderDiscount,
                            'isCurrencyMismatch'         : requestData.isCurrencyMismatch,
                            'cartTotalBeforeDiscount'    : requestData.cartTotalBeforeDiscount,
                            'shipping_method'            : scope.orderData.shipping_method
                    	};
                    
                    	var shippingAddess = !_.isEmpty(scope.addressID) 
				                    		? scope.addressID 
				                    		: !_.isEmpty(scope.orderSupportData.shippingAddress) ? scope.orderSupportData.shippingAddress.id :null,

				        	billingAddess = !_.isEmpty(scope.addressID1) 
				                    		? scope.addressID 
				                    		: !_.isEmpty(scope.orderSupportData.billingAddress) ? scope.orderSupportData.billingAddress.id :null;

                        scope.shippingMethods = requestData.shipping_methods;

                        if (scope.shippingMethods.length == 1) {
                            var shippingMethodId = scope.shippingMethods[0]['_id'];
                            if (_.isUndefined(scope.orderData.shipping_method) && scope.shippingCount == 1) {
                                _.delay(function() {
                                    scope.getShipping(shippingMethodId);
                                }, 500);
                                scope.orderData.shipping_method = shippingMethodId;
                            }
                        }
                     
                    	scope.orderData = {
                    		'fullName' 			  : (scope.isLoggedIn)
                                                    ? requestData.user.fullName
                                                    : scope.orderData.fullName,
                            'userEmail'           : (scope.isLoggedIn)
                                                    ? requestData.user.userEmail
                                                    : scope.orderData.email,
                            'shipping_country'    : (!_.isEmpty(scope.shippingCountryId))                     ? requestData.user.shippingCountry 
                                                     : '',
                            'billing_country'     : (!_.isEmpty(scope.billingCountryId))                     ? requestData.user.billingCountry 
                                                     : '', //requestData.user.billingCountry,
                            'stripeKey'           : requestData.stripeKey,
                            'paystackPublicKey'   : requestData.paystackPublicKey,
                            'razorpayKey'         : requestData.razorpayKey,
                            'description'         : requestData.description,
                    		'addressID' 		  : shippingAddess,
                            'businessEmail'       : requestData.businessEmail,
							'checkout_method'	  : scope.orderData.checkout_method,
							'sameAddress'         : scope.orderSupportData.sameAddress,
							'addressID1'		  : billingAddess,
							'totalPayableAmount'  : scope.orderSupportData.totalPayableAmount,
                            'totalPayableAmountForStripe': requestData.totalPayableAmountForStripe,
                            'totalPayableAmountForPaystack': requestData.totalPayableAmountForPaystack,
                        	'totalPayableAmountForRazorPay': requestData.totalPayableAmountForRazorPay,
							'couponCode' 		  : scope.couponCode,
							'currency' 		 	  : requestData.total.currency,
                            'totalTaxAmount'      : requestData.taxses.totalTaxAmount,
                            'totalShippingAmount' : requestData.shipping.totalShippingAmount,
                            // This is for non logged in user
                            'email'               : scope.orderData.email,
                            'use_as_billing'      : requestData.user.useAsBilling,
                            'shipping_address_type' : scope.orderData.shipping_address_type,
                            'shipping_address_line_1' : scope.orderData.shipping_address_line_1,
                            'shipping_address_line_2' : scope.orderData.shipping_address_line_2,
                            'shipping_city'         : scope.orderData.shipping_city,
                            'shipping_state'        : scope.orderData.shipping_state,
                            'shipping_pin_code'     : scope.orderData.shipping_pin_code,
                            'billing_address_type'  : scope.orderData.billing_address_type,
                            'billing_address_line_1' : scope.orderData.billing_address_line_1,
                            'billing_address_line_2' : scope.orderData.billing_address_line_2,
                            'billing_city'          : scope.orderData.billing_city,
                            'billing_state'         : scope.orderData.billing_state,
                            'billing_pin_code'      : scope.orderData.billing_pin_code,
                            'discountAddedPrice'    : requestData.shipping.discountAddedPrice,
                            'orderDiscount'         : requestData.orderDiscount.discount,
                            'shipping_method'       : scope.orderData.shipping_method
                    	};

                        scope.couponData = !_.isEmpty(requestData.couponData)
                                                            ? requestData.couponData
                                                            : null;


						scope = __Form.updateModel(scope, scope.orderData);

	            		scope.isValidItem  = requestData.isValidItem;
                        
                        if (_.isUndefined(scope.orderData.checkout_method)) {
                            scope.disabledStatus  = false;
                        }
                        
                        if (!scope.orderSupportData.shippingAddress
                            || scope.orderSupportData.shipping.isShippable === false) {
                            scope.disabledStatus  = false;
                        }
                        
	        			// check if current route is order summary
	        			if (requestData.orderRoute == document.location.href) {
	        				scope.showCartBtn = true;
	        			} else {
	        				scope.showCartBtn = false;
	        			}

	        			// send object data to shopping cart button
	        			scope.data = {
		                  'routeStatus' : scope.showCartBtn
		                };

	        			$scope.$emit('lw.current.route.name', scope.data);

                        var $discount = $compile(__Utils.template('#cartDiscountDetailsTemplate', scope.orderSupportData.cartDiscount))(scope);
                        scope.cartDiscountDetailsHtml = $discount.html();

	                    scope.pageStatus  = true;
	                    
            	});
     		});
        };
        scope.getCartOrderDetails();

      /**
        * Terms and conditions dialog
        *
        * @return void
        *-----------------------------------------------------------------------*/
        scope.checkValidDataForSubmit = function() {
            
            if (scope.isValidItem
                && scope.orderSupportData.shippingAddress
                && scope.orderSupportData.shipping.isShippable) {
                
                if (!_.isEmpty(scope.couponData)) {
                    if (scope.orderData.discountAddedPrice > 0) {
                        scope.disabledStatus  = true;
                    } else {
                        scope.disabledStatus  = false;
                    }
                } else {
                    scope.disabledStatus  = true;
                }                
            }
            //scope.orderData.discountAddedPrice > 0

            // Check if user is not logged in
            if (!scope.isLoggedIn) {
                if ((scope.isValidItem)
                    && (scope.orderSupportData.shipping.isShippable)) {

                    if (!_.isEmpty(scope.couponData)) {
                        if (scope.orderData.discountAddedPrice > 0) {
                            scope.orderProcessDisableStatus = false;
                        } else {
                            scope.orderProcessDisableStatus = true;
                        }
                    } else {
                        scope.orderProcessDisableStatus = false;
                    }
                }
            }
        };

        /*
         when user is not logged in then call this function
        -------------------------------------------------------------------------- */
        scope.changeAddressType = function(value){

            if (value) {
                scope.orderData.billing_country = null;
                scope.getCartOrderDetails(null, null, scope.couponCode, scope.orderData.shipping_country, scope.orderData.billing_country);
            } else {
                scope.getCartOrderDetails(null, null, scope.couponCode, scope.orderData.shipping_country, scope.orderData.billing_country);
            }            
        }

        /*
         when user is not logged in then call this function
        -------------------------------------------------------------------------- */
        scope.getShipping = function() {
           
            scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, scope.couponCode, scope.shippingCountryId, scope.billingCountryId);         
        }

      /**
        * apply coupon submit method
        *
        * @param string couponCode
        * @param number orderTotalPrice
        *
        * @return void
        *---------------------------------------------------------------- */
        scope.orderData.coupon = '';
        scope.newCouponCode = '';
       
        scope.applyCoupon = function(couponCode) {

        	__DataStore.post({
                'apiURL' : 'order.coupon.apply'
            }, scope).success(function(responseData) {

				appServices.processResponse(responseData, function(reactionCode) {
					
                    scope.couponData 	   			    = responseData.data.couponData;
                    scope.couponStatus 				    = reactionCode;
                    scope.invalidCouponCode			    = responseData.data.couponCode;
                    scope.couponMessage 			    = true;
                    scope.orderData.subtotalPrice       = responseData.data.totalPrice;

                    var shippingAddress = !_.isEmpty(scope.orderData.addressID) 
				                    		? scope.orderData.addressID
				                    		: !_.isEmpty(scope.orderSupportData.shippingAddress) ? scope.orderSupportData.shippingAddress.id :null,

						billingAddress  = !_.isEmpty(scope.orderData.addressID1) 
					                    	? scope.orderData.addressID1
					                    	: !_.isEmpty(scope.orderSupportData.billingAddress) ? scope.orderSupportData.billingAddress.id :null;

                    scope.getCartOrderDetails(shippingAddress, billingAddress, scope.couponData.couponCode, scope.shippingCountryId, scope.billingCountryId);

                    scope.validCouponAmtMessage = __ngSupport.getText(
                    							 __globals.getJSString('order_coupon_amount_text'), {
                    							 	'__amount__' : scope.couponData
                    							 });

                    scope.orderData.code = "";
                    scope.checkValidDataForSubmit();
             
                    var $discount = $compile(__Utils.template('#couponDetailsTemplate', scope.couponData))(scope);
                    scope.couponDetailsHtml = $discount.html(); 
            	});

            });

        };

        scope.disabledStatus = true;

      /**
		* Remove coupon 
		*
		* @return void
		*---------------------------------------------------------------- */

        scope.removeCoupon = function() {

        	//scope.orderData.addressID  = scope.orderSupportData.shippingAddress.id;
        	//scope.orderData.addressID1 = scope.orderSupportData.billingAddress.id;

        	scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, null, scope.shippingCountryId, scope.billingCountryId);
        	scope.couponMessage = false;
        	scope.couponStatus  = null;

        	// Check if discount amount exist 
        	// if exist then remove discount amount
        	if (!_.isEmpty(scope.couponData.discountFormate)) {
        		scope.couponData.discountFormate = '';
        	}

            scope.checkValidDataForSubmit
        	
        }



	    /**
	    * remove invalid item in the cart
	    *
	    * @param {Number} itemID - cart item row id.
	    * @return void
	    * 
	    *------------------------------------------------------------------------ */

	    scope.removeAllInvlidCartItem = function(status) {

	        // get data using angular $http.get() method
	        __DataStore.post('cart.remove.invalid.item', scope)
	        	.success(function(responseData) {
					appServices.processResponse(responseData, null,
                        function(reactionCode) {

                        	scope.data = {
			                  'cart_string': responseData.data.cartItems,
			                  'status'     : true
			                }

			                $scope.$emit('lw.update.cart.string', scope.data);
			                $rootScope.$emit('remove.cart.row', {'status':true});
				 			scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, scope.couponCode, scope.shippingCountryId, scope.billingCountryId);
						}
                	); 
	            
    	    });

	    };

	    /**
	    * call function if any items is invalid on process time 
	    *
	    * @param object param 1 type 
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
	    scope.updatOrderSummary = function(responseData) {
            			
			var requestData  = responseData.orderSummaryData;

            scope.shippingCount = requestData.shipping.shippingCount;
		
        	scope.orderSupportData = {
                'paymentMethod'              : requestData.paymentMethod,
                'cartItems' 	 			 : requestData.cartItems,
                'itemsCount'                 : requestData.itemsCount,
                'totalQuantity'              : requestData.totalQuantity,
        		'itemIsInvalid' 	 	     : requestData.itemIsInvalid,
        		'shippingAddress' 			 : requestData.shippingAddress,
        		'sameAddress' 			 	 : requestData.sameAddress,
                'businessEmail'              : requestData.businessEmail,
        		'billingAddress' 			 : requestData.billingAddress,
        		'shipping' 		 			 : requestData.shipping.info,
        		'formatedCartTotalPrice'	 : requestData.total.base_price,
                'formatedTotalTaxAmount'     : requestData.taxses.formatedTotalTaxAmount,
        		'cartTotalPrice' 			 : requestData.total.totalBasePrice,
        		'currency' 		 			 : requestData.total.currency,
        		'afterAddShipingTotal' 		 : requestData.shipping.totalPrice,
        		'taxses' 					 : requestData.taxses.info,
        		'afterAddTaxTotal' 			 : requestData.taxses.totalPrice,
        		'totalPayableAmount' 		 : requestData.totalPayableAmount,
        		'totalPayableAmountFormated' : requestData.totalPayableAmountFormated,
        		'fullName' 					 : requestData.user.fullName,
                'total'                      : requestData.total,
        		'couponData' 				 : !_.isEmpty(requestData.couponData)
        										? requestData.couponData.couponData
        										: null,
        		'subtotalPrice' 			 : requestData.shipping.formettedDiscountPrice,
        		'checkoutMethod' 			 : requestData.checkoutMethod,
                'checkoutMethodInfo' 		 : requestData.checkoutMethodInfo,
                'shipping_method'       : scope.orderData.shipping_method
        	};

        	var shippingAddress = !_.isEmpty(scope.orderData.addressID) 
				                    		? scope.orderData.addressID
				                    		: !_.isEmpty(scope.orderSupportData.shippingAddress) ? scope.orderSupportData.shippingAddress.id :null,

				billingAddress  = !_.isEmpty(scope.orderData.addressID1) 
			                    	? scope.orderData.addressID1
			                    	: !_.isEmpty(scope.orderSupportData.billingAddress) ? scope.orderSupportData.billingAddress.id :null;

        	scope.orderData = {
        		'fullName' 			 : (scope.isLoggedIn)
                                        ? requestData.user.fullName
                                        : scope.orderData.fullName,
                'userEmail'          : (scope.isLoggedIn)
                                        ? requestData.user.userEmail
                                        : scope.orderData.email,
                'description'         : requestData.description,
                'razorpayKey'          : requestData.razorpayKey,
                'stripeKey'          : requestData.stripeKey,
                'paystackPublicKey'  : requestData.paystackPublicKey,
        		'addressID' 		 : shippingAddress,
				'checkout_method'	 : scope.orderData.checkout_method,
				'sameAddress'        : scope.orderSupportData.sameAddress,
                'businessEmail'      : requestData.businessEmail,
				'addressID1'		 : billingAddress,
				'totalPayableAmount' : scope.orderSupportData.totalPayableAmount,
                'totalPayableAmountForStripe': requestData.totalPayableAmountForStripe,
                'totalPayableAmountForPaystack': requestData.totalPayableAmountForPaystack,
                'totalPayableAmountForRazorPay' : requestData.totalPayableAmountForRazorPay,
				'couponCode' 		 : scope.couponCode,
				'currency' 		 	 : requestData.total.currency,
                'totalTaxAmount'     : requestData.taxses.totalTaxAmount,
                'totalShippingAmount': requestData.shipping.totalShippingAmount,
                // This is for non logged in user
                'email'               : scope.orderData.email,
                'use_as_billing'      : requestData.user.useAsBilling,
                'shipping_address_type' : scope.orderData.shipping_address_type,
                'shipping_address_line_1' : scope.orderData.shipping_address_line_1,
                'shipping_address_line_2' : scope.orderData.shipping_address_line_2,
                'shipping_city'         : scope.orderData.shipping_city,
                'shipping_state'        : scope.orderData.shipping_state,
                'shipping_pin_code'     : scope.orderData.shipping_pin_code,
                'billing_address_type'  : scope.orderData.billing_address_type,
                'billing_address_line_1' : scope.orderData.billing_address_line_1,
                'billing_address_line_2' : scope.orderData.billing_address_line_2,
                'billing_city'          : scope.orderData.billing_city,
                'billing_state'         : scope.orderData.billing_state,
                'billing_pin_code'      : scope.orderData.billing_pin_code,
                'discountAddedPrice'    : requestData.shipping.discountAddedPrice,
                'orderDiscount'         : requestData.orderDiscount.discount,
                'shipping_method'       : scope.orderData.shipping_method
        	};
        	
			scope = __Form.updateModel(scope, scope.orderData);

    		if (requestData.isValidItem == false) {
                scope.isValidItem = false;
    			scope.disabledStatus = false;
                scope.orderProcessDisableStatus = true;
			} else {
				scope.disabledStatus = true;
                scope.orderProcessDisableStatus = false;
			}

			// check if current route is order summary
			if (requestData.orderRoute == document.location.href) {
				scope.showCartBtn = true;
			} else {
				scope.showCartBtn = false;
			}

			// send object data to shopping cart button
			scope.data = {
              'routeStatus' : scope.showCartBtn
            };

			$scope.$emit('lw.current.route.name', scope.data);

            scope.pageStatus  = true;
	                    
        };

	  /**
		* order submit process
		*
		* @return void
		*---------------------------------------------------------------- */
		scope.orderData.totalDiscountAmount = 0;

        scope.handleOrderValidationErrors = function(reaction, responseData) {

            // call function when any invalid data to display to user
            if (reaction == 3) {
                //scope.updatOrderSummary(responseData.data);
                
                appServices.showDialog(responseData,
                {   
                    templateUrl : __globals.getTemplateURL('order.user.alert-dialog')
                },
                function(promiseObj) {

                    if (_.has(promiseObj, 'value') 
                        && promiseObj.value.is_submit_order === true) {

                        //scope.updatOrderSummary(responseData.data);
                        var shippingAddress = !_.isEmpty(scope.orderData.addressID) 
                            ? scope.orderData.addressID
                            : !_.isEmpty(scope.orderSupportData.shippingAddress) ? scope.orderSupportData.shippingAddress.id :null,

                            billingAddress  = !_.isEmpty(scope.orderData.addressID1) 
                                                ? scope.orderData.addressID1
                                                : !_.isEmpty(scope.orderSupportData.billingAddress) ? scope.orderSupportData.billingAddress.id :null;
                        var couponCode = _.isEmpty(scope.couponData) ? null : scope.couponData.couponCode                       
                        scope.getCartOrderDetails(shippingAddress, billingAddress, couponCode, scope.shippingCountryId, scope.billingCountryId);

                    } else {

                        __globals.redirectBrowser(__Utils.apiURL('cart.view')); 

                    }

                   

                });
            }

        };

		scope.orderSubmit  =  function() 
		{    
            __globals.showProcessingDialog();

            if (_.has(scope, 'couponData') 
                && !_.isUndefined(scope.couponData)
                && !_.isEmpty(scope.couponData)) {
                scope.orderData.totalDiscountAmount = scope.couponData.discount;
            }

            if (scope.cart_order_form.$valid == false 
                && scope.orderData.checkout_method !== 6) {
                __globals.hideProcessingDialog();
            } else if (scope.cart_order_form.$valid == false 
                && scope.orderData.checkout_method === 6) {
                __globals.hideProcessingDialog();
                appNotify.error(__globals.getJSString('order_error_messages'));
            }

             if(scope.orderData.checkout_method == 11) {
 
                if (scope.cart_order_form.$valid) {
                	var options = {
					    "key": scope.orderData.razorpayKey,
					    "amount": scope.orderData.totalPayableAmountForRazorPay.toFixed(2), // 2000 paise = INR 20
                        "currency": scope.orderData.currency,
					    "name": scope.orderData.fullName,
					    //"description": "Purchase Description",
 					    "handler": function (response){
 							
 				 			if (!_.isEmpty(response.razorpay_payment_id)) {

		                        // Token is ready lets go and charge the card
		                        __DataStore.post('order.razorpay.checkout', _.assign({
		                            'razorpay_payment_id' : response.razorpay_payment_id,
		                            'checkout_method' : 11,                                
		                        }, scope.orderData), scope)
		                        .success(function(responseData) {  
 
		                            dataRecieved = responseData.data;


		                            appServices.processResponse(responseData, function(reaction) {

                                         // check response has other error
                                        if((responseData.reaction !== 1) && dataRecieved.orderPaymentToken) {
                                                __globals.redirectBrowser(__Utils.apiURL('order.payment_cancelled', {
                                                'orderToken' : dataRecieved.orderPaymentToken
                                            }));
                                        } else if(responseData.reaction !== 1) {

                                            scope.handleOrderValidationErrors(reaction, responseData);
                                            
                                            if (reaction == 2 && _.has(responseData.data, 'orderSummaryData')) {
                                                scope.updatOrderSummary(responseData.data);
                                            } else if (reaction == 9) {
                                                $rootScope.$broadcast('lw-open-login-dialog', {'data' : ''});
                                            } 
                                        }
  
		                            }, function() {
		                                // if found ok redirect customer to thank you page
		                                __globals.redirectBrowser(__Utils.apiURL('order.thank_you_razorpay', {
		                                    'orderToken' : dataRecieved.razorpayChargeRequest.orderPaymentToken
		                                }));

		                            }); 

		                        });
				 			}
					    },
					    "prefill": {
					        "name": scope.orderData.fullName,
					        "email": scope.orderData.userEmail
					    },
					    //"notes": {
					        //"address": "fweffwfwf"
					    //},
					    "theme": {
					        "color": "#050505"
					    },
                        "modal": {
                            "ondismiss": function(e){}
                        }
					};
                    __globals.hideProcessingDialog();
					var rzp1 = new Razorpay(options);
					rzp1.open();
				    // e.preventDefault();
					// document.getElementById('rzp-button1').onclick = function(e){
					//     rzp1.open();
					//     e.preventDefault();
					// }
                } 

            } else if(scope.orderData.checkout_method == 6) {

                if (scope.cart_order_form.$valid) {
                var orderData   = scope.orderData, 
                    dataRecieved = null,
                    tokenRecieved = null,
                    handler = StripeCheckout.configure({
                    key: orderData.stripeKey,
                     // image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                    locale: 'auto',
                    description: orderData.description,
                    email: orderData.userEmail,
                    amount: orderData.totalPayableAmountForStripe,
                    currency: orderData.currency,
                    allowRememberMe: false,
                    token: function(token) {

                        tokenRecieved = token.id;

                        // Token is ready lets go and charge the card
                        __DataStore.post('order.stripe.checkout', _.assign({
                            'stripeToken' : token.id,
                            'checkout_method' : 6,                                
                        }, scope.orderData), scope)
                        .success(function(responseData) {  
                            dataRecieved = responseData.data;
                           
                            appServices.processResponse(responseData, function(reaction) {
                          
                                // check response has other error
                                if((responseData.reaction !== 1) && dataRecieved.orderPaymentToken) { 
                                   
                                        __globals.redirectBrowser(__Utils.apiURL('order.payment_cancelled', {
                                        'orderToken' : dataRecieved.orderPaymentToken
                                    })+'?message='+dataRecieved.message);
                                        
                                } else if(responseData.reaction !== 1) {

                                    scope.handleOrderValidationErrors(reaction, responseData);
                                    
                                    if (reaction == 2 && _.has(responseData.data, 'orderSummaryData')) {
                                        scope.updatOrderSummary(responseData.data);
                                    } else if (reaction == 9) {
                                        $rootScope.$broadcast('lw-open-login-dialog', {'data' : ''});
                                    } 
                                }
                                
                            }, function() {
                                // if found ok redirect customer to thank you page
                                __globals.redirectBrowser(__Utils.apiURL('order.thank_you_stripe', {
                                    'orderToken' : dataRecieved.orderPaymentToken
                                }));

                            }); 

                        });

                      },
                      opened: function() {
                        __globals.hideProcessingDialog();
                      },
                      closed: function() {

                        // check if opted token if then show process dialog
                        if(tokenRecieved) {
                            __globals.showProcessingDialog();
                        } else if(!_.isEmpty(dataRecieved) && dataRecieved.orderPaymentToken) {

                            __globals.redirectBrowser(__Utils.apiURL('order.payment_cancelled', {
                                'orderToken' : dataRecieved.orderPaymentToken
                            }));
                        }
                      }
                    }).open();

                    // Close Checkout on page navigation:
                    window.addEventListener('popstate', function() {
                    if (handler) {
                        handler.close();
                    }
                    });
                } 

            } else if (scope.orderData.checkout_method == 20) {

                if (scope.cart_order_form.$valid) {
                    var handler = PaystackPop.setup({
                        key: scope.orderData.paystackPublicKey,
                        email: scope.orderData.businessEmail,
                        amount: scope.orderData.totalPayableAmountForPaystack,
                        currency:  scope.orderData.currency,
                        callback: function(response){
                            var paystackRefrenceId = response.reference;
                            if (!_.isEmpty(paystackRefrenceId)) {

                                // Token is ready lets go and charge the card
                                __DataStore.post('order.paystack.checkout', _.assign({
                                    'paystack_refrence_id' : paystackRefrenceId,
                                    'checkout_method' : 20,                                
                                }, scope.orderData), scope)
                                .success(function(responseData) {  
 
                                    dataRecieved = responseData.data;

                                    appServices.processResponse(responseData, function(reaction) {
                                        // check response has other error
                                        if((responseData.reaction !== 1) && dataRecieved.orderPaymentToken) {
                                                __globals.redirectBrowser(__Utils.apiURL('order.payment_cancelled', {
                                                'orderToken' : dataRecieved.orderPaymentToken
                                            })+'?message='+dataRecieved.message);
                                                
                                        } else if(responseData.reaction !== 1) {

                                            scope.handleOrderValidationErrors(reaction, responseData);
                                            
                                            if (reaction == 2 && _.has(responseData.data, 'orderSummaryData')) {
                                                scope.updatOrderSummary(responseData.data);
                                            } else if (reaction == 9) {
                                                $rootScope.$broadcast('lw-open-login-dialog', {'data' : ''});
                                            } 
                                        }
                                    }, function() {
                                        // if found ok redirect customer to thank you page
                                        __globals.redirectBrowser(__Utils.apiURL('order.thank_you_paystack', {
                                            'orderToken' : dataRecieved.orderPaymentToken
                                        }));

                                    }); 

                                });
                            }
                        },
                        onClose: function(){
                        }
                    });
                    handler.openIframe();
                    __globals.hideProcessingDialog();
                }
            } else { // NOT Stripe
                if (scope.orderData.checkout_method == 14) { 
                    scope.orderData.card_number = String(scope.orderData.card_number);
                }
           
                __Form.process('order.process', scope)
                .success(function(responseData) {
                 
                    appServices.processResponse(responseData, {
                        then: function(reaction) {
                            
                            if (reaction == 3 && !_.has(responseData.data, 'validation')) {

                                scope.handleOrderValidationErrors(reaction, responseData);
                            }                          
                            
                            if (reaction == 2 && _.has(responseData.data, 'orderSummaryData') ) {
                                scope.updatOrderSummary(responseData.data);
                            } else if (reaction == 9) {
                                $rootScope.$broadcast('lw-open-login-dialog', {'data' : ''});
                            }       
                        }

                    }, function() {
                        
                        if (responseData.reaction == 1 && (responseData.data.ckMethod == 1)) {
                            __globals.redirectBrowser(__Utils.apiURL('order.paypal.checkout', {
                                'orderUID' : responseData.data.orderID
                            }));

                        } else if (responseData.reaction == 1 && (responseData.data.ckMethod == 14) || (responseData.data.ckMethod == 15)) {
                           
                            $('body').html(responseData.data.htmlContent);

                            // document.downloadForm.submit()
                            // _.delay(function () {
                            //     $('.lw-loader-container form').submit();
                            // }, 300);

                        } else if (responseData.reaction == 1 && (responseData.data.ckMethod == 16) || (responseData.data.ckMethod == 17)) {
                           
                           __globals.redirectBrowser(__Utils.apiURL('order.paytm.checkout', {
                                'orderUID' : responseData.data.orderID
                            }));

                        } else if (responseData.reaction == 1 && (responseData.data.ckMethod == 18) || (responseData.data.ckMethod == 19)) {
                           
                           __globals.redirectBrowser(__Utils.apiURL('order.instamojo.checkout', {
                                'orderUID' : responseData.data.orderID
                            }));

                        } else if (responseData.reaction == 1) { 

                            if (scope.isLoggedIn) {
                                __globals.redirectBrowser(__Utils.apiURL('my_order.details', {
                                    'orderUID' : responseData.data.orderID
                                }));
                            } else if(!scope.isLoggedIn) {
                                __globals.redirectBrowser(__Utils.apiURL('order.guest_order_success'));
                            }
                            
                        }     
                    });   
                     

            });

            }

            
		};


	  /**
        * shipping & billing address not same
        *
        * @param sameAddress.
        * @param shippingAddress
        *
        * @return void
        *
        *------------------------------------------------------------------------ */
        scope.sameAsAddress = function(sameAddress, shippingAddress) {
        	
        	if (sameAddress == false) {

        		scope.orderSupportData.billingAddress = '';
        		scope.orderData.addressID1 = '';
                
        	} else {

                scope.getCartOrderDetails(
                            scope.orderData.addressID, 
                            scope.orderData.addressID, 
                            scope.couponCode);
            }

        	scope.orderData.sameAddress = sameAddress;
        };

       

	  /**
	    * if user click on change address then open dialog
	    *
	    * @param {boolean} sameAddress
	    * @param {string}  addressType
	    * @param {string}  countryCode
	    *
	    * @return void
	    *---------------------------------------------------------------- */
	    
	    scope.openAddressListDialog  =  function(sameAddress, addressType) 
	    {  
	    	appServices.showDialog(scope,
	        {	
	            templateUrl : __globals.getTemplateURL('address.list-dialog')
	        },
	        function(promiseObj) {
	        	
	        	if (!_.isUndefined(promiseObj.value) && (promiseObj.value != '$closeButton')) {

	        		var selectedaddress = promiseObj.value.selectedAddress;

	        		if (sameAddress == false && addressType == 'billing') {

		        		scope.orderSupportData.billingAddress   = selectedaddress;
		        		scope.orderData.addressID1   		    = selectedaddress.id;
		        		scope.orderSupportData.sameAddress  	= false;
                        scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, scope.couponCode);

		        	} else if(sameAddress == true && addressType == 'billing') {

		        		scope.orderSupportData.shippingAddress = selectedaddress;
		        		scope.orderSupportData.billingAddress  = selectedaddress;
		        		scope.orderData.addressID   		   = selectedaddress.id;
		        		scope.orderData.addressID1   		   = selectedaddress.id;


		        	} else if(sameAddress == true && addressType == 'shipping') {

						scope.orderSupportData.shippingAddress = selectedaddress;
		        		scope.orderSupportData.billingAddress  = selectedaddress;
		        		scope.orderData.addressID   		   = selectedaddress.id;
		        		scope.orderData.addressID1   		   = selectedaddress.id;
		        		scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, scope.couponCode);

                        if (scope.orderSupportData.shipping.isShippable) {
                            scope.disabledStatus  = true;
                        }

		        	} else if(sameAddress == false && addressType == 'shipping') {

						scope.orderSupportData.shippingAddress = selectedaddress;
						scope.orderData.addressID   		   = selectedaddress.id;
						
						scope.orderData.addressID1 = _.isEmpty(scope.orderData.addressID1) ? scope.orderSupportData.billingAddress.id : scope.orderData.addressID1;
                        
                        if (scope.orderSupportData.shipping.isShippable) {
                            scope.disabledStatus  = true;
                        }
		        		

		        		if (!_.isEmpty(scope.orderData.addressID) && scope.orderData.addressID === scope.orderData.addressID1) {
		        			scope.orderData.addressID1  = selectedaddress.id;
		        			scope.orderSupportData.sameAddress  = true;
		        		}

						scope.getCartOrderDetails(
							scope.orderData.addressID, 
							scope.orderData.addressID1, 
							scope.couponCode);

		        	} 
	        	}

                scope.checkValidDataForSubmit();
	        });

	    };


        /**
	    * remove cart item action
	    *
	    * @param {Number} itemID - cart item row id.
	    * @return void
	    * 
	    *------------------------------------------------------------------------ */

	    scope.removeCartItem = function(itemID) {

	        // get data using angular $http.get() method
	        __DataStore.post({
	        	'apiURL' : 'cart.remove.item',
                'itemID' : itemID
	        }, scope).success(function(responseData) {

				appServices.processResponse(responseData, null,
                    function(reactionCode) {
			 			
			 			scope.data = {
		                  'cart_string': responseData.data.cartItems,
		                  'status'     : true
		                }

		                $scope.$emit('lw.update.cart.string', scope.data);

			 			$('#rowid_'+itemID).fadeOut('slow',function() {
                            $(this).remove();
                        }); 
                        scope.getCartOrderDetails();

                    }
                ); 
	            
    	    });

	    };

	    /**
	    * remove cart item of product out of stock and invalid
	    *
	    * @param {Number} itemID - cart item row id.
	    * @return void
	    * 
	    *------------------------------------------------------------------------ */

	    scope.removeAllCartItem = function(status) {

	        // get data using angular $http.get() method
	        __DataStore.post('cart.remove.invalid.item', scope)
	        	.success(function(responseData) {
					appServices.processResponse(responseData, null,
                        function(reactionCode) {

                        	scope.data = {
			                  'cart_string': responseData.data.cartItems,
			                  'status'     : true
			                }

			                $scope.$emit('lw.update.cart.string', scope.data);
				 			scope.getCartOrderDetails();
						}
                	); 
	            
    	    });

	    };

        /**
        * Check user email 
        *
        * @return void
        * 
        *------------------------------------------------------------------------ */
        scope.checkUserEmail = function() {

            if (!_.isEmpty(scope.orderData.email)) {
                __DataStore.fetch({
                    'apiURL' : 'order.check.user_email',
                    'email'  : scope.orderData.email,
                    'couponId' : (!_.isEmpty(scope.couponData))
                                    ? scope.couponData.couponID
                                    : null
                }).success(function(responseData) {
                    var requestData = responseData.data;
                    scope.invalidCouponMessage = requestData.message;
                    
                    if (_.isNull(requestData.userId)) {
                        scope.userIsExist = false;
                    } else {
                        scope.userIsExist = true;
                    }

                    scope.orderData.guestLoggedInUserId = requestData.userId;
                    appServices.processResponse(responseData, {
                        then : function(reaction) {
                            if (reaction == 2) {
                                appServices.showDialog(responseData, 
                                {
                                    templateUrl : __globals.getTemplateURL('order.user.alert-dialog')
                                }, 
                                function(promiseObj) {
                                    scope.removeCoupon();
                                });
                            }
                        }
                    }, function (reaction) {
                         
                    });
                });
            }
        };

        // If user is not logged in then open logged in dialog
        scope.openLoginDialog = function() {
            var responseData = {
                'guestOrder' : false
            };

            $rootScope.$broadcast('lw-open-login-dialog', {'data' : responseData});
        };

        /**
        * Refresh product 
        *
        * @return void
        * 
        *------------------------------------------------------------------------ */
        scope.refreshProduct = function(item) {
            __DataStore.post('cart.refresh.product', item)
                .success(function(responseData) {
                    appServices.processResponse(responseData, null, function() {
                        scope.getCartOrderDetails(scope.orderData.addressID, scope.orderData.addressID1, scope.couponCode, scope.shippingCountryId, scope.billingCountryId);
                    });
                });
        };

        /**
        * Open Discount details
        *
        * @return void
        *------------------------------------------------------------------------ */
        scope.openDiscountDetails = function() {
            $('#lw-discount-detail-table').slideToggle();           
        };
	 
    };


    function AlertDialogController($scope) {

        var scope            = this;
            scope.pageStatus = false;

            if ($scope.ngDialogData) {
                scope.message    = $scope.ngDialogData.data.message;
                scope.pageStatus = true;
            }
            
      /**
        * Response for submit order
        *
        * @return void
        *-----------------------------------------------------------------------*/
    
        scope.yesSubmitIt = function()
        {
            $scope.closeThisDialog({'is_submit_order' : true})
        }; 
    
        
        /**
        * Close this dialog
        *
        * @return void
        *-----------------------------------------------------------------------*/
    
        scope.closeDialog = function()
        {
            $scope.closeThisDialog({'is_submit_order' : false})
        }; 

    };

})();;
(function() {
'use strict';
    
    /*
     ProductAllReviewsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.allProductReviews', [])
        .controller('ProductAllReviewsController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            '__Utils',
            ProductAllReviewsController 
        ]);

    /**
      * ProductAllReviewsController for manage product image list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductAllReviewsController($scope, __DataStore, appServices,
     $stateParams, __Utils) {

        var scope       = this;
        scope.productID  = __globals.getAppJSItem('productID');
        
        // product reviews pagination data start
        this.showPaginationData = function(page) {
                var requestURL = '';
                
            if (!_.isEmpty(page) && page.search("http") != -1) {
                
                requestURL = page;
                
            } else {

                requestURL = __Utils.apiURL('product.user.all_review.paginate_data', 
                    {
                        'productId' : scope.productID
                    }
                );
                
                var pageUrl = "";

                if (page != undefined) {
                  pageUrl = "?page="+page;
                }

                requestURL = requestURL+pageUrl;

            }
            
            
            __DataStore.fetch(requestURL, {'fresh' : true})
                .success(function(responseData) {
                    var requestData = responseData.data;
                
                    appServices.processResponse(responseData, null, function(reactionCode) {
                        scope.productReviews = requestData.productReviewData;
                          
                        scope.paginationLinks = requestData.paginationLinks;
                        
                        if (scope.paginationLinks) {
                            var $paginationLinksElement = $(".lw-pagination-container").html(scope.paginationLinks);
                        }
                }); 
            });
            
        };
            
        this.showPaginationData('');
        $(".lw-pagination-container").on('click', 'a', function(event) {
            event.preventDefault();
            var $this = $(this),
                url   = $this.attr('href');
            scope.showPaginationData(url);
        });
        // product reviews pagination data stop
    };

})();;
(function() {
'use strict';
    
    /*
     ProductAllQuestionsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('ManageProductApp.allProductQuestions', [])
        .controller('ProductAllQuestionsController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            '__Utils',
            ProductAllQuestionsController 
        ]);

    /**
      * ProductAllQuestionsController for manage product image list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * 
      * @return void
      *-------------------------------------------------------- */

    function ProductAllQuestionsController($scope, __DataStore, appServices,
     $stateParams, __Utils) {

        var scope       = this;
        scope.productID  = __globals.getAppJSItem('productID');
        
        // product reviews pagination data start
        this.showPaginationData = function(page) {
                var requestURL = '';
                
            if (!_.isEmpty(page) && page.search("http") != -1) {
                
                requestURL = page;
                
            } else {

                requestURL = __Utils.apiURL('product.user.question.paginate_data', 
                    {
                        'productId' : scope.productID
                    }
                );
                
                var pageUrl = "";

                if (page != undefined) {
                  pageUrl = "?page="+page;
                }

                requestURL = requestURL+pageUrl;

            }
            
            
            __DataStore.fetch(requestURL, {'fresh' : true})
                .success(function(responseData) {
                    var requestData = responseData.data;
                
                    appServices.processResponse(responseData, null, function(reactionCode) {
                        scope.productQuestions = requestData.productQuestionData;
                        
                        scope.paginationLinks = requestData.paginationLinks;
                        
                        if (scope.paginationLinks) {
                            var $paginationLinksElement = $(".lw-pagination-container").html(scope.paginationLinks);
                        }
                }); 
            });
            
        };
            
        this.showPaginationData('');
        $(".lw-pagination-container").on('click', 'a', function(event) {
            event.preventDefault();
            var $this = $(this),
                url   = $this.attr('href');
            scope.showPaginationData(url);
        });
        // product reviews pagination data stop
    };

})();;
(function() {
'use strict';
    
    /*
     MyOrderListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.Order.list', [])
        .controller('MyOrderListController',   [
            '$scope', 
            '__DataStore',
            'appServices',
            '$stateParams',
            '__Auth',
            '__Utils',
            MyOrderListController 
        ]);

    /**
      * MyOrderListController for manage product list
      *
      * @inject $scope
      * @inject __DataStore
      * @inject appServices
      * @inject $stateParams
      * @inject __Auth
      * 
      * @return void
      *-------------------------------------------------------- */

    function MyOrderListController($scope, __DataStore, appServices, $stateParams, __Auth, __Utils) {
	 	
	 	var scope   = this;

		var dtCartOrderColumnsData = [
            {
                "name"      : "order_uid",
                "orderable" : true,
                "template"  : "#orderColumnIdTemplate"
            },
            {
                "name"      : "status",
                "orderable" : true,
                "template"  : "#orderStatusColumnIdTemplate"
            },
            {
                "name"      : "creation_date",
                "orderable" : true,
                "template"  : "#orderColumnTimeTemplate"
            },
            {
                "name"      : null,
                "template"  : "#orderActionColumnTemplate"
            }
        ],
        tabs    = {
            'activeTab'    : {
                id      : 'activeTabList',
                status  : 1
            },
            'cancelled'    : {
                id      : 'cancelledTabList',
                status  : 3
            },
            'completed'    : {
                id      : 'completedTabList',
                status  : 6
            }
        };

        // Fired when clicking on tab    
    	$('#adminOrderList a').click(function (e) {

        	e.preventDefault();
        	
            var $this       = $(this),
                tabName     = $this.attr('aria-controls'),
                selectedTab = tabs[tabName];

            if (!_.isEmpty(selectedTab)) {
                $(this).tab('show')
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
	                'apiURL' : 'cart.get.orders.data',
	                'status' : status
	            },
	            dtOptions   : {
	                "searching" : true,
	                "order"     : [[ 2, "desc" ]],
	                "columnDefs": [
			            {
			                "targets": [1],
			                "searchable": false
			            }
			        ]
	            },
	            columnsData : dtCartOrderColumnsData, 
	            scope       : $scope
	        });
        };

       // scope.getOrders('activeTabList', 1);
        var selectedTab = $('.nav li a[href="#activeTab"]');

        selectedTab.triggerHandler('click', true);

        /*
	     Reload current datatable
	    -------------------------------------------------------------------- */
	    
	    scope.reloadDT = function () {
	        __DataStore.reloadDT(scope.cartOrderListDataTable);
	    };

        /**
          * log dialog
          *
          * @param number orderID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.logDialog = function(orderID) {

            __DataStore.fetch({
                    'apiURL'    : 'order.log.dialog',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                
                    appServices.processResponse(responseData, null, function() {
                    	
                    	if (responseData.reaction == 9) {
	                    	window.location = __Utils.apiURL('user.login');
	                    }

                        var requestData = responseData.data;

                        appServices.showDialog({
                            'order'         : requestData.cartOrder,
                            'orderLog'      : requestData.orderLog
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'order.user.log'
                            )
                        },
                        function(promiseObj) {});

                    });    

                });

        };



        /**
          * update order dialog
          * 
          * @param orderID
          * @param isAdmin
          * 
          * @return void
          *---------------------------------------------------------------- */
        scope.cancelDialog = function(orderID) {
            
            __DataStore.fetch({
                    'apiURL'    : 'cart.order.cancel',
                    'orderID'   :  orderID
                })
                .success(function(responseData) {
                
                   appServices.processResponse(responseData, null, function() {

                        var requestData = responseData.data;
                        
                        appServices.showDialog({
                            orderData : requestData.order
                        },
                        {
                            templateUrl : __globals.getTemplateURL(
                                'order.user.cancel'
                            )
                        },
                        function(promiseObj) {

                            // Check if category updated
                            if (_.has(promiseObj.value, 'order_updated') 
                                && promiseObj.value.order_updated === true) {
                                scope.reloadDT();
                            }

                        });

                    });    

                });

        };

    };

})();;
(function() {
'use strict';
    
    /*
     MyOrderDetailsController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.Order.details', [])
        .controller('MyOrderDetailsController',   [
            '$scope', 
            '__Form',
            '__Utils',
            'appServices',
            '__DataStore',
            MyOrderDetailsController 
        ]);

    /**
      * MyOrderDetailsController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function MyOrderDetailsController($scope, __Form, __Utils, appServices, __DataStore) {

       	var scope   		 		= this;
       	scope.pageStatus 	 		= false;

        // Get order details
        scope.orderData   		= __globals.getAppJSItem('orderDetails');

        var requestedData 		= scope.orderData.data;
        scope.billingAddress   	= requestedData.address.billingAddress;
        scope.shippingAddress   = requestedData.address.shippingAddress;
        scope.sameAddress   	= requestedData.address.sameAddress;

        scope.user				= requestedData.user;
        scope.order				= requestedData.order;
        scope.orderStatus       = requestedData.order.orderStatus;
        scope.orderProducts		= requestedData.orderProducts;
        scope.coupon			= requestedData.coupon;
        scope.taxes				= requestedData.taxes;
        scope.shipping			= requestedData.shipping;

        scope.pageStatus = true; 

	  /**
		* If user order payment is pending then he will allow to update
		* his payment using PayPal
		*
		* @param string orderUID
		*
		* @return void
		*---------------------------------------------------------------- */

        scope.updatePayment = function(orderUID) {

        	__globals.redirectBrowser(__Utils.apiURL('order.paypal.checkout', {
                	'orderUID' : orderUID
                }));
        };

        /**
          * Contact to store/Admin Dialog
          * 
          * @return void
          *---------------------------------------------------------------- */

        scope.contactStoreDialog = function(orderUID) {
        	
            appServices.showDialog({

            	orderUID : orderUID
            },
            {
                templateUrl : __globals.getTemplateURL('order.user.contact-store')
            },
            function(promiseObj) {});
        };

        /**
          * Delete address 
          *
          * @param number orderId
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.cancelOrder = function(orderId) {

            __globals.showConfirmation({
                text                : __globals.getJSString('order_cancellation_text'),
                confirmButtonText   : __globals.getJSString('cancel_action_button_text')
            }, 
            function() {

                __DataStore.post({
                    'apiURL'    : 'order.user.cancellation_process',
                    'orderID'   : orderId,
                })
                .success(function(responseData) {

                    var message = responseData.data.message;
                   
                    appServices.processResponse(responseData, {
                            error : function(data) {
                                __globals.showConfirmation({
                                    title   : __globals.getJSString('cancel_error_title'),
                                    text    : message,
                                    type    : 'error'
                                });

                            }
                        },
                        function(data) {

                            __globals.showConfirmation({
                                title   : __globals.getJSString('cancel_success_title'),
                                text    : message,
                                type    : 'success'
                            });
                            
                            scope.order.allowOrderCancellation = false;
                            scope.order.orderStatus = 3;
                            scope.order.formatedOrderStatus = 'Cancelled';

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
     MyOrderLogController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.Order.log', [])
        .controller('MyOrderLogController',   [
            '$scope', 
            '__Form',
            MyOrderLogController 
        ]);

    /**
      * MyOrderLogController for manage product list
      *
      * @inject $scope
      * @inject __Form
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function MyOrderLogController($scope, __Form) {

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
     CancelOrderController
    -------------------------------------------------------------------------- */
    
    angular
        .module('PublicApp.Order.cancel', [])
        .controller('CancelMyOrderController',   [
            '$scope', 
            '__Form',
            'appServices',
            CancelMyOrderController 
        ]);

    /**
      * CancelMyOrderController for update order status
      *
      * @inject $scope
      * @inject __Form
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */
            

    function CancelMyOrderController($scope, __Form, appServices) {

       var scope  = this;
			scope = __Form.setup(scope, 'form_order_update', 'orderData');
			scope.ngDialogData = $scope.ngDialogData;
			scope = __Form.updateModel(scope, scope.orderData);


            /**
		  	  * process update order
		  	  *
		  	  * @return void
		  	  *---------------------------------------------------------------- */
			scope.cancel = function() {

		 		// post form data
		 		__Form.process({
						'apiURL'   :'cart.order.cancel.process',
						'orderID'  : scope.ngDialogData.orderData._id 
					}, scope ).success( function( responseData ) {
			      		
					appServices.processResponse(responseData, null, function(reactionCode) {

		                $scope.closeThisDialog( { order_updated : true } );

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
/*!
 *  Engine      : PublicAddressEngine
 *  Component   : Address
----------------------------------------------------------------------------- */

(function( window, angular, undefined ) {

	'use strict';
	
	/*
	  Public Address Engine
	  -------------------------------------------------------------------------- */
	
	angular.module('PublicApp.address', 		[])

		/**
    	  * AddressListController for list of address
    	  *
    	  * @inject __Utils
    	  * @inject __Form
    	  * @inject $state
    	  * @inject appServices
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('AddressListController', [ 
            '__Utils',
            '__Form',
            '$state',
            'appServices',
            '$scope',
            '__Auth',
            '__DataStore',
			'$rootScope',
            function (__Utils, __Form , $state, appServices, $scope, __Auth, __DataStore, $rootScope) {

            	var scope   		= this;
        		scope 				= __Form.setup(scope, 'user_address_form', 'addressData');
            	scope.pageStatus    = false;
				scope.addressData   = {};
				
				/**
		          * get List of addresses
		          *
		          * @return void
		          *---------------------------------------------------------------- */
				scope.getAddresses = function() {

		        	__DataStore.fetch('user.get.addresses')
			        	.success(function(responseData) {

		        		appServices.processResponse(responseData, null, 
		        		function() {
			        		var requestData 	= responseData.data;
			        		scope.addressData 	= requestData;
			        		scope.pageStatus    = true;

						});
		 			});
		        };
		        scope.getAddresses();

		        /**
		          * Delete address 
		          *
		          * @param number addressID
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.delete = function(addressID) {

		            __globals.showConfirmation({
		                text                : __globals.getJSString('address_delete_text'),
		                confirmButtonText   : __globals.getJSString('delete_action_button_text')
		            }, 
		            function() {

		                __DataStore.post({
		                    'apiURL'    : 'user.address.delete',
		                    'addressID' : addressID,
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
		                            
		                            // use for slowly remove address from list
		                            $('#address_'+addressID).fadeOut('slow',function() {
		                                $(this).remove();
		                            	scope.getAddresses();
		                            });

		                        }
		                    );    

		                });

		            });

		        };

				$rootScope.$on('lw.login-success', function(event, data) {
					
					if (data) {
						scope.getAddresses();
					}
				});

		        /**
		          * address add dialog
		          *
		          * @param number pageType
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.addAddressDialog = function() {
					
		        	scope.addressType = scope.addressData.addressType;
		        	scope.countries   = scope.addressData.countries.data;
		        	appServices.showDialog(scope,
			        {	
			            templateUrl : __globals.getTemplateURL(
			                'address.add'
			            )
			        }, function(promiseObj) {

			        	if (_.has(promiseObj.value, 'address_added') 
		                    && promiseObj.value.address_added === true) {
							scope.getAddresses();
		                }

		        	});
		        }


		        /**
		          * address edit dialog
		          *
		          * @param number addressID
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.editAddressDialog = function(addressID) {

		        	__DataStore.fetch({
				        	'apiURL'	: 'user.fetch.address.support.data',
				        	'addressID'	: addressID
				        })
	            	   .success(function(responseData) {
	            	   	
	            	   	scope.countries 	= responseData.data.countries;
	            	   	scope.address 		= responseData.data.address;
	            	   	scope.addressType 	= responseData.data.addressType;

			            	appServices.showDialog(scope,
			                {	
			                    templateUrl : __globals.getTemplateURL(
			                            'address.edit'
			                        )
			                }, function(promiseObj) {

				        	if (_.has(promiseObj.value, 'address_updated') 
			                    && promiseObj.value.address_updated == true) {
				        		scope.getAddresses();
			                }

		        		});

	     			});
		        }

		        /**
		          * address list dialog
		          *
		          * @return void
		          *---------------------------------------------------------------- */
		        scope.addressListDialog = function() {

		        	__DataStore.fetch({'apiURL' : 'get.addresses.for.order'})
		    	   .success(function(responseData) {
		    	   	
		    	   		var requestData   = responseData.data;
		    	   		scope.addressData = requestData;

				    	appServices.showDialog(requestData,
				        {	
				            templateUrl : __globals.getTemplateURL(
				                    'address.list-dialog'
				                )
				        },
				        function(promiseObj) {

				        });
			       });
		        }

		        /**
		          * Close dialog
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		    	scope.close = function() {
		            $scope.closeThisDialog();
		        }

		        /**
		          * Select address for order
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		    	scope.selectAddressForOrder = function(addressData, selectedAddress) {

		            // get address for order by address ID
		            if (!_.isEmpty(addressData)) {

			            _.forEach(addressData, function(address, key) {

			            	// take primary address
			           		if (address.id == selectedAddress) { 

		            			$scope.closeThisDialog({'selectedAddress': address});

			           		}

			            });

			        } else {

			        	$scope.closeThisDialog({'selectedAddress': '' });
			        }
		        }

		        /**
		          * Make primary address
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		    	scope.makePrimaryAddress = function(addressID) {

		    		__Form.process({
			                'apiURL'    :'user.get primary.address',
			                'addressID' : addressID
			            }, scope)
		                .success(function(responseData) {

		                    var message 	= responseData.data.message;

		                	appServices.processResponse(responseData, null,
		                        function() {

		                        	scope.getAddresses();
		                        }
		                    );  
		                
		            });
		    	} 

			}
    	])


    	/**
    	  * AddressAddController for add new address
    	  *
    	  * @inject $scope
    	  * @inject __Form
    	  * @inject appServices
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('AddressAddController', [ 
            '$scope',
            '__Auth',
            '__Form',
            'appServices',
            function ($scope, __Auth, __Form, appServices) {
            	
            	var scope   = this;
		        scope = __Form.setup(scope, 'user_address_form', 'userData', {
		            secured : false
		        });

		        scope.ngDialogData = $scope.ngDialogData.addressData;

		        // Get address type
		        scope.addressType = scope.ngDialogData.addressType;
		        
		        
		        // Get countries List
		        scope.countriesCollection     = scope.ngDialogData.countries.data;
		        scope.countries 			  = scope.countriesCollection.countries;

		        scope.countries_select_config = __globals.getSelectizeOptions({
		            valueField  : 'value',
		            labelField  : 'text',
		            searchField : [ 'text' ]  
		        });


		        /**
		          * Submit address form action
		          *
		          * @return void
		          *---------------------------------------------------------------- */

		        scope.submit = function() {

		            __Form.process('user.address.process', scope)

		                .success(function(responseData) {

		                    appServices.processResponse(responseData, null, function() {

			                	$scope.closeThisDialog({address_added : true})

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
		        }
            }
        ])


        /**
    	  * AddressEditController for add new address
    	  *
    	  * @inject $scope
    	  * @inject __Form
    	  * @inject appServices
    	  * 
    	  * @return void
    	 *-------------------------------------------------------- */

		.controller('AddressEditController', [ 
            '$scope',
            '__Form',
            'appServices',
            function ($scope, __Form, appServices) {
            	
            	var scope = this;
        		scope     = __Form.setup(scope, 'user_address_edit_form', 'addressData');

		        // Get address type
		        scope.addressType 		  = $scope.ngDialogData.addressType;
		        
		        // Get address Data
		         scope.addressData 		  = $scope.ngDialogData.address;

		        // Get all countries
		        scope.countriesCollection = $scope.ngDialogData.countries.data;
		        scope.countries 		  = scope.countriesCollection.countries;

		        scope.countries_select_config = __globals.getSelectizeOptions({
		            valueField  : 'value',
		            labelField  : 'text',
		            searchField : [ 'text' ]  
		        });


		        /**
		          * update form action
		          *
		          * @return void
		          *------------------------------------------------------------------------ */

		        scope.update = function() {
		            
		             scope.updateURL = {
		                'apiURL'    :'user.address.update',
		                'addressID' : scope.addressData.id
		            };

		            // post form data
		            __Form.process(scope.updateURL, scope )

		                .success( function( responseData ) {

		                appServices.processResponse(responseData, null, function(reactionCode) {
		                 
		                    $scope.closeThisDialog({address_updated : true})
		                    
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
		        }
            }
        ])




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
(function() {
'use strict';
    
    /*
     UserForgotPasswordController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.forgotPassword', [])

        /**
          * UserForgotPasswordController - request to send password reminder
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserForgotPasswordController',   [
            '__Form', 
            'appServices',
            '__Utils',
            function (__Form, appServices, __Utils) {

                var scope   = this;


                scope = __Form.setup(scope, 'user_forgot_password_form', 'userData', {
                    secured : true
                });

                /**
                  * Submit form
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    __Form.process('user.forgot_password.process', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            
                           __globals.redirectBrowser(__Utils.apiURL('user.forgot_password.success'));
                           
                        });    

                    });

                };

            }

        ]);

})();;
(function() {
'use strict';
    
    /*
     UserResetPasswordController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.resetPassword', [])

        /**
          * UserResetPasswordController for reset user password
          *
          * @inject __Form
          * @inject appServices
          * @inject __Utils
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserResetPasswordController',   [
            '__Form', 
            'appServices',
            '__Utils',
            function (__Form, appServices, __Utils) {

                var scope = this;

                scope = __Form.setup(scope, 'user_reset_password_form', 'userData', {
                    secured : true
                });

                /**
                  * Submit reset password form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                
                scope.submit = function() {

                    __Form.process({
                        'apiURL'        : 'user.reset_password.process',
                        'reminderToken' : __globals.getAppJSItem('passwordReminderToken')
                    }, scope)
                        .success(function(responseData) {
                            
                        appServices.processResponse(responseData, null,
                            function(reactionCode) {
                                window.location = __Utils.apiURL('user.login');
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
     UserRegisterController
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.register', [])

        /**
          * UserRegisterController - handle register form & send request to server
          * to submit form data. 
          *
          * @inject __Form
          * @inject $state
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserRegisterController',   [
            '__Form', 
            '$state',
            'appServices',
            '__Utils',
            function (__Form, $state, appServices, __Utils) {

                var scope   = this;
                scope.show_captcha      = false;

                scope = __Form.setup(scope, 'user_register_form', 'userData', {
                    secured : true
                });

                /**
                  * Get login attempts for this client ip
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                __Form.fetch('user.register.supportData').success(function(responseData) {
                    scope.show_captcha      = true;
                    scope.site_key      = responseData.data.site_key;                   
                });


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
                scope.errorMessage = false;
                /**
                  * Submit register form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                
                scope.submit = function() {

                    scope.isInActive = false;

                    __Form.process('user.register.process', scope)
                        .success(function(responseData) {

                           var requestData = responseData.data;
                           
                        appServices.processResponse(responseData, {
                        		error : function() {

                                },
                                otherError : function() {

                                    if (responseData.reaction == 3 && !_.isEmpty(responseData.message)) {
                                        scope.errorMessage = responseData.message;
                                    }

                                    //console.log(responseData.message);
                                    scope.isInActive = requestData.isInActive;

	                                // reset password field
	                                scope[scope.ngFormModelName].password   = "";
	                                scope[scope.ngFormModelName].password_confirmation   = "";

	                                // refresh captcha
	                                scope[scope.ngFormModelName].confirmation_code   = "";
	                                scope.refreshCaptcha();

                                }
                            },
                            function() {
                            __globals.redirectBrowser(__Utils.apiURL('user.register.success'));
                        });    

                    });

                };

                /**
                  * Show terms and conditions dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.showTermsAndConditionsDialog = function() {

                    appServices.showDialog(scope,
                    {   
                        templateUrl : __globals.getTemplateURL(
                            'user.terms-and-conditions'
                        )
                    }, 
                    function(promiseObj) {
                    });

                };

            } 
        ]);
    
})();;
(function() {
'use strict';
    
    /*
     UserContactController
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.contact', [])
        .controller('UserContactController',   [
            '__Form', 
            '$state',
            'appServices',
            '__Utils',
            '__Auth',
            '$scope',
            UserContactController 
        ]);

    /**
      * UserContactController handle register form & send request to server
      * to submit form data. 
      *
      * @inject __Form
      * @inject $state
      * @inject appServices
      * 
      * @return void
      *-------------------------------------------------------- */

    function UserContactController(__Form, $state, appServices, __Utils, __Auth, $scope) {

        var scope   = this;
       
        scope = __Form.setup(scope, 'user_contact_form', 'userData', {
        	secured : true,
		    unsecuredFields : ['message'],
        });

        // get logged in user Info
        __Auth.refresh(function(authInfo) {
	 		scope.auth_info = authInfo;
	 	});

	 	if (scope.auth_info.reaction_code != 9) { // not authenticate
		 	scope.userData.email 	= scope.auth_info.profile.email;
		 	scope.userData.fullName = scope.auth_info.profile.full_name;
	 	}

	 	if (!_.isEmpty($scope.ngDialogData)) {

	 		scope.userData.orderUID = $scope.ngDialogData.orderUID;
	 	}
        	

        /**
          * Submit register form action
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.requestSuccess = false;
    
        scope.submit = function(formType) {

        	scope.userData.formType = formType;

        	 __Form.process('user.contact.process', scope)
                .success(function(responseData) {
                    
                appServices.processResponse(responseData, null, function() {

                    scope.userData 		   = '';
                    
                	// Check if form type dialog or form
                	if (scope.userData.formType == 2) { // dialog
                        
                		$scope.closeThisDialog();
                	} else {

                        $('.lw-form').slideUp();
                    }
                    
                    scope.requestSuccess = true;

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
     UserAddressAddController
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.address', [])
        .controller('UserAddressAddController',   [
        	'$scope',
            '__Form', 
            '$state',
            'appServices',
            '__Utils',
            '$rootScope',
            UserAddressAddController 
        ]);

    /**
      * UserAddressAddController handle address form & send request to server
      * to submit form data. 
      *
      * @inject $scope
      * @inject __Form
      * @inject $state
      * @inject appServices
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */

    function UserAddressAddController($scope, __Form, $state, appServices, __Utils, $rootScope) {

        var scope   = this;
        
        scope = __Form.setup(scope, 'user_address_form', 'userData', {
            secured : false
        });

        scope.addressType 			  = __globals.getAppJSItem('configGetAddressType');

        // Get countries
        scope.countriesCollection     = __globals.getAppJSItem('countries');
        scope.countries 			  = scope.countriesCollection.data.countries;

        scope.countries_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : [ 'text' ]  
        });

        //scope.countries = __globals.configItem('countries');

        /**
          * Submit address form action
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.submit = function() {

            __Form.process('user.address.process', scope)
                .success(function(responseData) {
                    appServices.processResponse(responseData, null, function() {
	                	$scope.closeThisDialog({address_added : true, selectedAddressID : $scope.ngDialogData.selectedAddressID, addressType : $scope.ngDialogData.addressType})
               		});
            });

        };

         /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */
          
        scope.close = function() {
        	
        	$scope.closeThisDialog({address_added : true, selectedAddressID : $scope.ngDialogData.selectedAddressID, addressType : $scope.ngDialogData.addressType});
        }

    };

})();;
(function() {
'use strict';
    
    /*
     UserAddressListController
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.Addresslist', [])
        .controller('UserAddressListController',   [
        	'$scope',
            '$state',
            '__DataStore',
            '__Form',
            'appServices',
            '__Utils',
            '$rootScope',
            UserAddressListController 
        ]);

    /**
      * UserAddressListController handle address form & send request to server
      * to get list of address. 
      *
      * @inject scope
      * @inject $state
      * @inject __DataStore
      * @inject __Form
      * @inject appServices
      * @inject __Utils
      * 
      * @return void
      *-------------------------------------------------------- */

    function UserAddressListController($scope, $state, __DataStore, __Form, appServices, __Utils, $rootScope) {
    	
    	var scope   			= this;
        	scope 				= __Form.setup(scope, 'user_address_form', 'addressData');
        	scope.ngDialogData  = $scope.ngDialogData;
			scope.pageStatus    = false;
			
        /**
          * get details of addresses
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.getAddresses = function() {

        	__DataStore
        	.fetch('user.get.addresses')
        	.success(
        	function(responseData) {

                //scope.message = responseData.data.message;
        		appServices.processResponse(
        			responseData,
        			null, 
	        		function(reactionCode) {

	        			var requestData      	= responseData.data;

	        			if (requestData.getRoute === document.location.href) {
	        				var pageType	 	= "noDialog";
	        			} else {
	        				var pageType	 	= "dialog";

		    				scope.selectAddress = scope.ngDialogData.selectedAddressID;
					        scope.addressType 	= scope.ngDialogData.addressType;
					        scope.addressSameAs = scope.ngDialogData.addressSameAs;
					        scope.couponAmt		= scope.ngDialogData.couponAmt;

	        			};

	        			scope.addressData 	 = requestData;
	                    scope.pageStatus     = true;
	        			scope.addressprimary = true;
		            	scope.pageType 		 = pageType;
                 
	                    if (!_.isEmpty(scope.ngDialogData)) {
	                        scope.selectAddress  = scope.ngDialogData.selectedAddressID;
	                        scope.addressType    = scope.ngDialogData.addressType;
	                        scope.addressSameAs = scope.ngDialogData.addressSameAs;
	                        scope.couponAmt		= scope.ngDialogData.couponAmt;
	                    } else {
	                        scope.selectAddress  = "";
	                        scope.addressType    = "";
	                        scope.addressSameAs  = "";
	                        scope.couponAmt		 = 0;
	                    }

				});
 			});
        }

        scope.getAddresses();


        $rootScope.$on('changeAddresses',function(events, resposeData){

        	if (resposeData.status) {
        		scope.addressType 	= resposeData.addressType;
        	};
	    
	    });
        

        /**
          * address add dialog
          *
          * @param number pageType
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.addAddress = function(pageType, selectedAddressID, addressType) {

        	if (pageType == "dialog") {

        		$scope.closeThisDialog({

	    			showAddDialog   	: true,
	    			pageType 			: pageType,
	    			selectedAddressID 	: selectedAddressID,
	    			addressType 		: addressType
	    		
	    		});

        	} else {

        		appServices.showDialog(scope,
		        {	
		            templateUrl : __globals.getTemplateURL(
		                'user.add-address'
		            )
		        }, function(promiseObj) {

		        	
		        	if (_.has(promiseObj.value, 'address_added') 
	                    && promiseObj.value.address_added === true) {	
						scope.addressType 	 = promiseObj.value.addressType;
					scope.getAddresses();
	                }

	        	});
        	}
        	
        }

        /**
          * address edit dialog
          *
          * @param number pageType
          * @param number addressID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.editAddress = function(pageType, addressID, selectedAddressID, addressType) {

        	if (pageType == "dialog") {

        		$scope.closeThisDialog({
	        		showEditDialog 		: true,
	        		addressID 			: addressID,
	        		selectedAddressID 	: selectedAddressID,
	        		addressType 		: addressType
	        	});

        	
        	} else {

        		__DataStore.fetch({
			        	'apiURL'	: 'user.fetch.address.support.data',
			        	'addressID'	: addressID
			        })
            	   .success(function(responseData) {

            	   	scope.address = responseData.data.address;
		            	appServices.showDialog(scope,
		                {	
		                    templateUrl : __globals.getTemplateURL(
		                            'user.edit-address'
		                        )
		                }, function(promiseObj) {

			        	if (_.has(promiseObj.value, 'address_updated') 
		                    && promiseObj.value.address_updated == true) {
			        		scope.getAddresses();
		                }

	        		});

     			});

        	}
        }

        /**
          * Delete address 
          *
          * @param number addressID
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.delete = function(addressID, addressType) {

            __globals.showConfirmation({
                text                : __globals.getJSString('address_delete_text'),
                confirmButtonText   : __globals.getJSString('delete_action_button_text')
            }, function() {

                __DataStore.post({
                    'apiURL'    : 'user.address.delete',
                    'addressID' : addressID,
                })
                .success(function(responseData) {
                
                    var message = responseData.data.message,
                    addressOBJ  = {
						status  : true,
						addressType  : addressType,
						addressID  : addressID
                   	};
                   
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
                            
                            $('#address_'+addressID).fadeOut('slow',function() {
                                $(this).remove();
                            	scope.getAddresses();
                            });

                            $rootScope.$emit('changeAddress', addressOBJ);   // reload datatable

                        }
                    );    

                });

            });

        };

        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.close = function() {

        	var billingAddressOBJ  = {
            	status  : false,
       		};
       		$rootScope.$emit('changeAddress', billingAddressOBJ);
        	$scope.closeThisDialog({address_list : true});

        }

        /**
          * get primary address 
          *
          * @param number addressID
          * @param number primary
          * @param number pageType
          *
          * @return void
          *---------------------------------------------------------------- */
        scope.primaryAddress = function(addressID, primary, pageType) {

        	scope.addressData.primary = primary;

        	scope.updateURL = {
                'apiURL'    :'user.get primary.address',
                'addressID' : addressID
            };

        	__Form.process(scope.updateURL, scope)
                .success(function(responseData) {

                    var message 	= responseData.data.message;
                	 	/*addressOBJ  = {
                        	status          : true
                   		};*/
                	appServices.processResponse(responseData, null,
                        function(data) {
                        	scope.getAddresses();
                        	/*if (pageType == 'dialog') {
                        		$scope.closeThisDialog({address_list : true})
                        	};*/
							//$rootScope.$emit('changePrimaryAddress', addressOBJ);
                        }
                    );  
                
            });
		}

		/**
          * select address for address 
          *
          * @param number addressID
          * @param number addressType
          * @param number addressSameAs
          *
          * @return void
          *---------------------------------------------------------------- */
		scope.selectAddressForOrder = function(addressID, addressType, addressSameAs) {
			
			__DataStore.fetch({
                    'apiURL'    : 'user.get.address.for.order',
                    'addressID' : addressID,
                    'couponAmt' : scope.couponAmt
                })
                .success(function(responseData) {

                	appServices.processResponse(responseData, null,
                        function(data) {

		               		var addressOBJ = {

		               			status 			: true,
		               			addressSameAs 	: addressSameAs,
		               			responseData 	: responseData.data,
		               			addressType 	: addressType,
		               			totalOrder 		: responseData.data.shipping
		               		};

			               	if (addressType == 'billing') {


			               		var billingAddressOBJ  = {
			                    	status  : false,
			                    	addressType : 'billing',
		               				totalOrder 		: responseData.data.shipping
			               		};
			               		$rootScope.$emit('changeAddress', billingAddressOBJ);

			               	};

			               	if (addressSameAs == false && addressType != 'billing') {
			               		$rootScope.$emit('changeAddressInOrder', addressOBJ);
			               	} else {

			               		$rootScope.$emit('changeAddressInOrder', addressOBJ);
			               	}
			               	
			               	$scope.closeThisDialog({address_list : true});
                        }
                    ); 
            	});

		}


    };

})();;
(function() {
'use strict';
    
    /*
     Category edit Controller
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.editAddress', [])
        .controller('UserAddressEditController',    [
            '$scope', 
            '__Form', 
            'appServices',
            '__Utils',
            '$state',
            UserAddressEditController 
        ]);

    /**
     * UserAddressEditController
     *
     * @inject $scope
     * @inject __Form
     * @inject appServices
     * @inject __Utils
     * @inject $state
     * 
     * @return void
     *-------------------------------------------------------- */

    function UserAddressEditController($scope, __Form, appServices, __Utils, $state) {

        var scope = this;

        scope     = __Form.setup(scope, 'user_address_edit_form', 'addressData');

        scope.addressID = __globals.getAppJSItem('addressID');
        scope.loadSelectBox = false;
        scope.addressType = __globals.getAppJSItem('configGetAddressType');

        // Get all countries
        scope.countriesCollection = __globals.getAppJSItem('countries');
        scope.countries = scope.countriesCollection.data.countries;

        scope.countries_select_config = __globals.getSelectizeOptions({
            valueField  : 'value',
            labelField  : 'text',
            searchField : [ 'text' ]  
        });

        if (_.isEmpty(scope.addressID)) {
            scope.addressData = $scope.ngDialogData.address;
                    
            if (!_.isEmpty(scope.addressData)) {
                scope.loadSelectBox = true;
            };  
            scope.addressID = scope.addressData.id;
            scope.getRoute = "";

        } else {
            scope.addressID = scope.addressData.id;
            scope           = __Form.updateModel(scope, __globals.getAppJSItem('addressData'));
            scope.getRoute  = __globals.getAppJSItem('getRoute');
        }          

        /**
          * update form action
          *
          * @return void
          *------------------------------------------------------------------------ */

        scope.update = function() {
            
             scope.updateURL = {
                'apiURL'    :'user.address.update',
                'addressID' : scope.addressID
            };

            // post form data
            __Form.process(scope.updateURL, scope )
                            .success( function( responseData ) {
                appServices.processResponse(responseData, function(reactionCode) {
                    if (reactionCode == 1) {
                        $scope.closeThisDialog({address_updated : true, selectedAddressID : $scope.ngDialogData.selectedAddressID})
                    };
                    
                });

            });

        };

        /**
          * Close dialog and return promise object
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.close = function() {
            $scope.closeThisDialog({address_updated : true, selectedAddressID : $scope.ngDialogData.selectedAddressID});
        }
    };
})();
;
(function() {
'use strict';
    
    /*
     UserResendActivationEmailController Module
    -------------------------------------------------------------------------- */
    
    angular
        .module('UserApp.resendActivationEmail', [])

        /**
          * UserResendActivationEmailController for request to send password reminder
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserResendActivationEmailController',   [
            '__Form', 
            'appServices',
            '__Utils',
            function (__Form, appServices, __Utils) {

                var scope   = this;
                
                scope = __Form.setup(scope, 'form_user_resend_activation_email', 'userData', {
                    secured : false
                });

                /**
                  * Submit forgot password form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function() {

                    __Form.process('user.resend.activation.email.proccess', scope)
                    .success(function(responseData) {

                        appServices.processResponse(responseData, null, function() {
                            window.location = __Utils.apiURL('user.resend_activation_email.success');
                        });    

                    });

                };

            }
             
        ]);
})();;
// MyWishListController
(function() {
'use strict';

    angular
        .module('PublicApp.myWishList', [])
    
    /**
     * MyWishListController
     *
     * @inject $scope
     * @inject __DataStore
     * @inject appServices
     *
     * @return void
     *-------------------------------------------------------- */
    .controller('MyWishListController', [
        '$scope',
        'appServices',
        '__Utils',
        '$compile',
        '__DataStore',
        function MyWishListController($scope, appServices, __Utils, $compile, __DataStore) {
            var scope = this;
            scope.pageStatus = false;


            //manage rating star
            scope.calculateRating = function(value) {
            	var decimal = (value - Math.floor(value));
            	var rated = 0, unrated = 0;
            	if (decimal >= 0 && decimal < 0.5) {
            		rated = Math.round(value);
            		unrated = Math.ceil(5 - value);
            		decimal  = 0;
            	} else if (decimal > 0.5 && decimal < 1 ) {
            		rated = Math.ceil(value);
            		unrated = Math.round(5 - value);
            		decimal  = 0;
            	} else {
            		rated = Math.floor(value);
            		unrated = Math.floor(5 - value);
            		decimal  = Math.round(decimal);
            	}

            	return (_.repeat('<i class="fa fa-star lw-color-gold"></i>', rated)+
    	            	_.repeat('<i class="fa fa-star-half-o lw-color-gold"></i>', decimal)+
    	            	_.repeat('<i class="fa fa-star lw-color-gray"></i>', unrated));
            };
             
             // product reviews pagination data start
        	this.getWishListPaginationData = function(page) {
                var requestURL = '';
                
	            if (!_.isEmpty(page) && page.search("http") != -1) {
	                
	                requestURL = page;
	                
	            } else {

	                requestURL = __Utils.apiURL('product.my_wishlist.details');
	                
	                var pageUrl = "";

	                if (page != undefined) {
	                  pageUrl = "?page="+page;
	                }

	                requestURL = requestURL+pageUrl;

	            }

                __DataStore.fetch(requestURL, {'fresh' : true})
                .success(function(responseData) {

                    var requestData = responseData.data;

                    appServices.processResponse(responseData, null, function() {

                        scope.wishListData = requestData.wishListData;
                        scope.totalProducts = requestData.totalProducts;
                        scope.paginationLinks = requestData.paginationLinks;
                       
                       scope.wishListCollection = [];
                        _.forEach(scope.wishListData, function(value, key) {

                            _.defer(function() {
                                $('#lwWislListProductDesc'+value.productId).html($compile('<p lw-expander slice-count="250">'+value.productDescription+'</p>')($scope));
                            });

                            scope.wishListCollection.push({
                                'productId' : value.productId,
                                'productImage' : value.productImage,
                                'createdOn' : value.createdOn,
                                'detailURL' : value.detailURL,
                                'itemRating' : value.itemRating,
                                'productDescription' : value.productDescription,
                                'productName' : value.productName,
                            });
                        });

                        if (scope.paginationLinks) {
                            var $paginationLinksElement = $(".lw-pagination-container").html(scope.paginationLinks);
                        }

                        scope.pageStatus = true;
                    });
                });
            };

            this.getWishListPaginationData('');
            $(".lw-pagination-container").on('click', 'a', function(event) {
                event.preventDefault();
                var $this = $(this),
                    url   = $this.attr('href');
                scope.getWishListPaginationData(url);
            });
            // product reviews pagination data stop

            /**
              * Remove from Wish-list
              *
              * @return void
              *---------------------------------------------------------------- */
            scope.removeFromWishlist = function(productID) {
                __DataStore.post({
                    'apiURL'    : 'product.wishlist.remove_process',
                    'productId' : productID
                }, scope).success(function(responseData) {

                    appServices.processResponse(responseData, null, function(reactionCode) {

                        if (reactionCode == 1) {
                            scope.getWishListPaginationData('');
                        }                       
                    });
                });
            };
        }
    ])

})();;
// MyRatingListController
(function() {
'use strict';

    angular
        .module('PublicApp.myRatingList', [])
    
    /**
     * MyRatingListController
     *
     * @inject $scope
     * @inject __DataStore
     * @inject appServices
     *
     * @return void
     *-------------------------------------------------------- */
    .controller('MyRatingListController', [
        '$scope',
        'appServices',
        '__Utils',
        '$compile',
        '__DataStore',
        function MyRatingListController($scope, appServices, __Utils, $compile, __DataStore) {
            var scope = this;
            scope.pageStatus = false;


             // product reviews pagination data start
        this.getRatingListPaginationData = function(page) {
                var requestURL = '';
                
            if (!_.isEmpty(page) && page.search("http") != -1) {
                
                requestURL = page;
                
            } else {

            requestURL = __Utils.apiURL('product.my_rating.details');
                
                var pageUrl = "";

                if (page != undefined) {
                  pageUrl = "?page="+page;
                }

                requestURL = requestURL+pageUrl;

            }

                __DataStore.fetch(requestURL, {'fresh' : true})
                .success(function(responseData) {

                    var requestData = responseData.data;
                   
                    appServices.processResponse(responseData, null, function() {

                        scope.ratingListData = requestData.ratingsData;
                        scope.paginationLinks = requestData.paginationLinks;
                       
                        scope.ratingData = [];
                        _.forEach(scope.ratingListData, function(value, key) {

                            _.defer(function() {
                                $('#lwProductDesc'+value.productId).html($compile('<p lw-expander slice-count="250">'+value.productDescription+'</p>')($scope));
                            });

                            scope.ratingData.push({
                                'productId' : value.productId,
                                'productImage' : value.productImage,
                                'createdOn' : value.createdOn,
                                'detailURL' : value.detailURL,
                                'itemRating' : value.itemRating,
                                'productDescription' : value.productDescription,
                                'productName' : value.productName,
                            });
                        });
                        
                        if (scope.paginationLinks) {
                            var $paginationLinksElement = $(".lw-pagination-container").html(scope.paginationLinks);
                        }
                        scope.pageStatus = true;
                    });
                });
            };

            this.getRatingListPaginationData('');
            $(".lw-pagination-container").on('click', 'a', function(event) {
                event.preventDefault();
                var $this = $(this),
                    url   = $this.attr('href');
                scope.getRatingListPaginationData(url);
            });
            // product reviews pagination data stop

            //manage rating star
            scope.calculateRating = function(value) {

                var decimal = '', 
                    rated = Math.floor(value), 
                    unrated = Math.floor(5 - rated);
                    
                if (value % 1 != 0) {
                    decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                    unrated = unrated - 1;
                }
               
            	return (_.repeat('<i class="fa fa-star lw-color-gold"></i>', rated)+
    	            	decimal+
    	            	_.repeat('<i class="fa fa-star lw-color-gray"></i>', unrated));
            };

        }
    ])

})();
//# sourceMappingURL=../source-maps/public-app.src.js.map
