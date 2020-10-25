(function() {
'use strict';

  angular.module('app.service', []).
    service("appServices", [
        '$rootScope', '$state', 'appNotify', 'ngDialog','__DataStore', appServices ]);

  
    /**
      * Various App services.
      * 
      *
      * @inject $rootScope
      *
      * @return void
      *-------------------------------------------------------- */
   
    function appServices( $rootScope, $state, appNotify, ngDialog, __DataStore ) {


    	/**
	      * Delay action for particuler time
	      *
	      * @return object
	      *---------------------------------------------------------------- */

	    this.delayAction = function( callbackFunction, delayInitialLoading) {

	      var delayInitialLoading = (delayInitialLoading 
	                                  && _.isNumber(delayInitialLoading) ) 
	                                  ? delayInitialLoading
	                                  : __globals.delayInitialLoading;


	        setTimeout(function(){
	            
	            callbackFunction.call( this );

	      }, delayInitialLoading);
	    };

    
        /**
          * Actions on Response (improved version of doReact) - 03 Sep 2015
          *
          * @return void
          *---------------------------------------------------------------- */

        this.processResponse = function( responseData, callback, successCallback ) {

            var message,
              preventRedirect,
              preventRedirectOn,
              options      = responseData.options,
              reactionCode = responseData.reaction;

            if (responseData.data && responseData.data.message) {
                message = responseData.data.message;
            }

            if ( _.isString(options) ) {
                message = options;
            }

            if ( _.isObject(options) && _.has(options, 'message')) {

                message = options.message;

                preventRedirect   =  options.preventRedirect ? options.preventRedirect : null;
                preventRedirectOn =  options.preventRedirectOn ? options.preventRedirectOn : null;

            }

            if ( !options || !options.preventRedirect ) {

                switch ( reactionCode ) {

                    case 8:
                        if( preventRedirectOn !== 8  ) {
                          $state.go('not_found');
                        }            
                        break;

                    case 7:
                        if( preventRedirectOn !== 7  ) {
                          $state.go('invalid_request');
                        }
                        break;

                    case 5:
                    if( preventRedirectOn !== 5  ) {
                          $state.go('unauthorized');
                        }
                        break;

                    case 18:
                        if( preventRedirectOn !== 18  ) {
                          $state.go('not_exist');
                        }
                        break;

                }
            }


            if ( message &&  ( reactionCode === 1 ) ) {

              appNotify.success( message );

            } else if( message &&  ( reactionCode === 14 ) ) {

              appNotify.warn( message );

            } else if( message &&  ( reactionCode != 1 ) ) {
              
              appNotify.error( message );

            }

            var callBackReturn = {};

            if (callback) {

                if (_.isFunction(callback)) {

                    callBackReturn.then = 
                            callback.call( this, reactionCode );

                } else if(_.isObject(callback)) {

                    if (_.has(callback, 'then') && _.isFunction(callback.then)) {
                        callBackReturn.then = 
                            callback.then.call( this, reactionCode );
                    }

                    if (_.has(callback, 'error') && _.isFunction(callback.error)) {
                        
                        if (reactionCode === 2) {
                            callBackReturn.error = 
                                callback.error.call(this, reactionCode);
                        }
                    }

                    if (_.has(callback, 'success') && _.isFunction(callback.success)) {
                        
                        if (reactionCode === 1) {
                            callBackReturn.success = 
                                callback.success.call(this, reactionCode);
                        }
                    }

                    if (_.has(callback, 'otherError') && _.isFunction(callback.otherError)) {
                        
                        if (reactionCode !== 1) {
                            callBackReturn.otherError = 
                                callback.otherError.call(this, reactionCode);
                        }
                    }

                }

            }

            if (successCallback && _.isFunction(successCallback)) {

                if (reactionCode === 1) {
                    callBackReturn.success = successCallback.call(this, reactionCode);
                }
            }
            
            return callBackReturn;

        };


        /**
      	  * Close all dialog
      	  *
      	  * @return void
      	  *---------------------------------------------------------------- */
    
	    this.closeAllDialog = function() {
	        ngDialog.closeAll();
	    };

	    /**
	      * Handle dialog show & close methods
	      *
	      * @param object transmitedData
	      * @param object options
	      * @param object closeCallback
	      *
	      * @return object
	      *---------------------------------------------------------------- */
	    
	    this.showDialog = function( transmitedData, options , closeCallback ) {

	        return ngDialog.open({

	          template        : options.templateUrl,
	          controller      : options.controller,
	          controllerAs    : options.controllerAs,
	          closeByEscape   : true,
	          closeByDocument : false,
               overlay        : true,
	          data            : transmitedData,
			  resolve         : options.resolve,
	        }).closePromise.then(function ( data ) {

	            return closeCallback.call( this, data );

	        });

	    };

        /**
          * Handle dialog show & close methods
          *
          * @param object transmitedData
          * @param object options
          * @param object closeCallback
          *
          * @return object
          *---------------------------------------------------------------- */
        
        this.createBarChart = function(element, chartData, options ) {
           
            var myPieChart = new Chart(element.elementId,{
                type: element.type,
                data: {
                    datasets: chartData.data,
                    labels: chartData.labels
                },
                options: options.options
            });
               
            return myPieChart;
        };

	    /**
	      * Handle dialog show & close methods
	      *
	      * @param object options
	      * @param object closeCallback
	      *
	      * @return object
	      *---------------------------------------------------------------- */
	    
	    this.confirmDialog = function( options , closeCallback ) {

	        return ngDialog.openConfirm({

	            template  : options.templateUrl,
	            className : 'ngdialog-theme-default',
	            showClose : true

	        }, function( value ) {

	            return closeCallback.call( this, value );

	        });

	    };

	    /**
          * Check if user allowed given authority ID permission of not
          *
          * @param string authorityId
          *
          * @return boolean
          *---------------------------------------------------------------- */

        $rootScope.canAccess = function(str) {

			var arr = __globals.appImmutable('availableRoutes');
 
	        // If there are no items in the array, return an empty array
	        if(typeof arr === 'undefined' || arr.length === 0) return false;
	        // If the string is empty return all items in the array
	        if(typeof arr === 'str' || str.length === 0) return false;

	        // Create a new array to hold the results.
	        var res = [];
	     
	        // Check where the start (*) is in the string
	        var starIndex = str.indexOf('*');
	    		
	        // If the star is the first character...
	        if(starIndex === 0) {
	            
	            // Get the string without the star.
	            str = str.substr(1);
	            for(var i = 0; i < arr.length; i++) {
	                
	                // Check if each item contains an indexOf function, if it doesn't it's not a (standard) string.
	                // It doesn't necessarily mean it IS a string either.
	                if(!arr[i].indexOf) continue;
	                
	                // Check if the string is at the end of each item.
	                if(arr[i].indexOf(str) === arr[i].length - str.length) {                    
	                    // If it is, add the item to the results.
	                    return true;
	                }
	            }
	        }
	        // Otherwise, if the star is the last character
	        else if(starIndex === str.length - 1) {
	            // Get the string without the star.
	            str = str.substr(0, str.length - 1);
	            for(var i = 0; i < arr.length; i++){
	                // Check indexOf function                
	                if(!arr[i].indexOf) continue;
	                // Check if the string is at the beginning of each item
	                if(arr[i].indexOf(str) === 0) {
	                    // If it is, add the item to the results.
	                    return true;
	                }
	            }
	        }
	        // In any other case...
	        else {            
	            for(var i = 0; i < arr.length; i++){
	                // Check indexOf function
	                if(!arr[i].indexOf) continue;
	                // Check if the string is anywhere in each item
	                if(arr[i].indexOf(str) !== -1) {
	                    // If it is, add the item to the results
	                    return true;
	                }
	            }
	        }
	        
	        // Return the results as a new array.
	        return false;

	    /*var birds = ['bird1','somebird','bird5','bird-big','abird-song'];

	    var res = searchArray(birds, 'bird.*');
	    alert(res.join('\n'));
	    // Results: bird1, bird5, bird-big
	    var res = searchArray(birds, '*bird');
	    alert(res.join('\n'));
	    // Results: somebird
	    var res = searchArray(birds, 'bird');
	    alert(res.join('\n'));*/
	    // Results: bird1, somebird, bird5, bird-big, abird-song

	            /*if (_.includes(authorityId, '*')) {

	                var prevIndex = -1,
	                    array = authorityId.split('*'), // Split the search string up in sections.
	                    result = true,
	                    availableRoutes = __globals.appImmutable('availableRoutes');

                // For each search section
                for(var i = 0; i < array.length && result; i++){

                    _.forEach(availableRoutes, function(value, key) {

                        // Find the location of the current search section
                        var index = value.indexOf(array[i]);

                        // If the section isn't found, or it's placed before the previous section...
                        if (index == -1 || index < prevIndex){
                            return false;
                        }

                    });
                }
                return result;

            } else {

                // check if routes available for access
                if (_.includes(__globals.appImmutable('availableRoutes'), authorityId) === false) {

                    return false;

                }
                return true;
            }*/

        };

	    /**
	    * Fire update breadcrumbs event
	    *
	    * @param mixed breadcrumbCollection
	    *
	    * @return void
	    *---------------------------------------------------------------- */

	    this.updateBreadcrumbs = function(breadcrumbCollection, $currentScope) {

	        $currentScope.$emit('lw.breadcrumb.update.event', breadcrumbCollection);
	    };

		/**
          * Handle Login required dialog show & close methods
          *
          * @param object string
          * @param object callback
          *
          * @return object
          *---------------------------------------------------------------- */

        this.loginRequiredDialog = function(from, options, callback) {

            this.showDialog(options,
            {
                templateUrl : __globals.getTemplateURL('user.login-dialog')
            },
            function(promiseObj) {
				
                if (_.has(promiseObj.value, 'login_success')
                    && promiseObj.value.login_success === true) {
					
                    callback.call(this, true);

                }

				callback.call(this, false);

            });

        };

	 	

    }

})();;
(function() {
'use strict';

  angular.module('app.http', []).

    // register the interceptor as a service, intercepts -
    // all angular ajax http reuest called
    config([ 
      '$httpProvider', 
      function ($httpProvider) {

        $httpProvider.interceptors.push('errorInterceptor');
        var proccessSubmit = function (data, headersGetter) {

           return data;

        };

        $httpProvider.defaults.transformRequest.push( proccessSubmit );
        $httpProvider.interceptors.push('loadingHttpInterceptor');

    }]).
    factory('errorInterceptor', [ 
      '$q',
      '__Auth',
      '__Utils',
      '$rootScope',
      errorInterceptor
    ]). 
    factory('loadingHttpInterceptor', [
        '$q',
        '$rootScope',function($q, $rootScope) {
      return {
        request: function(config) {
          $('.lw-disabling-block').addClass('lw-disabled-block');
          $('html').addClass('lw-has-disabled-block');
          return config || $q.when(config);
        },
        response: function(response) {

            $('.lw-disabling-block').removeClass('lw-disabled-block lw-has-processing-window');
            $('html').removeClass('lw-has-disabled-block');
            return response || $q.when(response);

        },
        responseError: function(rejection) {
            $('.lw-disabling-block').removeClass('lw-disabled-block lw-has-processing-window');
            $('html').removeClass('lw-has-disabled-block');

          return $q.reject(rejection);
        }
      };
}]);

  
  /**
   * errorInterceptor factory.
   * 
   * Make a response for all http request
   *
   * @inject $q - for return promise
   * @inject __Auth - for set authentication object
   * @inject $location - for redirect on another page
   *
   * @return void
   *-------------------------------------------------------- */
   
  function errorInterceptor($q, __Auth, __Utils, $rootScope) {

      return {

          request: function (config) {

            return config || $q.when(config);

          },
          requestError: function( request ) {

              return $q.reject(request);

          },
          response: function ( response ) {

            var requestData = response.data,
                publicApp   = __globals.isPublicApp();
 
            // If is Public App & Server return Not foun Reaction Then Redirect on Not Found Page
            if (publicApp == true && requestData.reaction == 18) {

                window.location = __Utils.apiURL('error.not_found');
                
            }

            if (requestData.additional && requestData.additional.orderData) {

            	var order = requestData.additional.orderData;
            	// update the total new order placed count
            	$rootScope.$broadcast('update.new.order.placed.count', order);
            }

             // if auth_info response exist
            if (requestData.data && _.has(requestData.data, 'auth_info')) {
                
                var authObj       = requestData.data.auth_info,
                    reactionCode  = authObj.reaction_code;

                __Auth.checkIn(authObj, function() {

                    switch (reactionCode) {

	                    case 11:  // access denied

                            // Check if current app is public the redirect to Home View
                            if (publicApp == true) {

                                window.location = window.appConfig.appBaseURL;

                            } else {

                                __Auth.state.go("unauthorized");

                            }
                            // window.location = window.appConfig.appBaseURL;

	                        break;

	                    case 9:  // if unauthorized

                            // Check if current app is public the redirect to Login View
                            // if (publicApp == true) {

                            //     window.location = __Utils.apiURL('user.login');

                            // } else {

                            //     __Auth.registerIntended( {
                            //         name    : __Auth.state.current.name,
                            //         params  : __Auth.state.params,
                            //         except  : [ 'user_login', 'user_logout' ]
                            //     }, function() {

                            //         $rootScope.$broadcast('lw-open-login-dialog', {'data' : response});

                            //     }); 

                            // }

                            __Auth.registerIntended( {
                                name    : __Auth.state.current.name,
                                params  : __Auth.state.params,
                                except  : [ 'user_login', 'user_logout' ]
                            }, function() {

                                $rootScope.$broadcast('lw-open-login-dialog', {'data' : response});

                            }); 
                            //window.location = __Utils.apiURL('user.login');
							// $rootScope.$broadcast('lw-open-login-dialog', {'data' : response});

	                        break;

	                    case 6:  // if invalid request                        
	                        __Auth.state.go("invalid_request");

	                        break;

	                    case 10:  
	                        // if already authenticated                        
	                       // __Auth.redirectIntended('user_profile_edit');

	                        break;

                    }

                });
               

            }

            return response || $q.when( response );
            
          },
          responseError: function ( response ) {

            return $q.reject(response);

          }

      };

  };

})();;
(function() {
'use strict';

  angular.module('app.notification', []).
    service("appNotify", [
        'ngNotify', appNotify ]);

  
  /**
     * appNotify service.
     *
     * Show notification
     *
     * @inject ngNotify
     *
     * @return object
     *-------------------------------------------------------- */
   
  function appNotify( ngNotify ) {


      /*
       Notification Default Option Object
      -------------------------------------------------------------------------- */
      
      this.optionsObj = {
        position      : 'top',
        type          : 'success',
        theme         : 'pure',
        dismissQueue  : true,
        duration      : 3000,
        sticky        : false
      };

      /**
        * Show success notification message
        *
        * @param string - message
        * @param object - options  
        *
        * @return object
        *---------------------------------------------------------------- */

      this.success  =  function( message, options ) {

          if ( _.isEmpty( options ) ) {  // Check for if options empty
              var options = {};
          }

          options.type = 'success';

          this.notify( message, options );

      };

        /**
          * Show error notification message
          *
          * @param string - message
          * @param object - options 
          *
          * @return object
          *---------------------------------------------------------------- */

        this.error  =  function( message, options ) {

            if ( _.isEmpty( options ) ) {  // Check for if options empty
                var options = {};
            }

            options.type = 'error';

            this.notify( message, options );

        };

        /**
          * Show information notification message
          *
          * @param string - message
          * @param object - options  
          *
          * @return object
          *---------------------------------------------------------------- */

        this.info  =  function( message, options ) {

            if ( _.isEmpty( options ) ) {  // Check for if options empty
                var options = {};
            }

            options.type = 'info';

            this.notify( message, options );

        };

        /**
          * Show warning notification message
          *
          * @param string - message
          * @param object - options  
          *
          * @return object
          *---------------------------------------------------------------- */

        this.warn  =  function( message, options ) {

            if ( _.isEmpty( options ) ) {  // Check for if options empty
                  var options = {};
            }

            options.type = 'warn';

            this.notify( message, options );

        };

        /**
          * Show notification
          *
          * @param string msg
          * @param object options
          *
          * @return void
          *---------------------------------------------------------------- */

        this.notify = function( message, options ) {
          
            // show notification
            ngNotify.set( message, _.assign( this.optionsObj, options ) );

        };
      
  };

})();;
(function() {
'use strict';

  angular.module('app.fancytree', []).
    service("appFancytree", [
        '$rootScope', 'appServices', '__DataStore', appFancytree ]);

  
    /**
      * Tree data service.
      * 
      *
      * @inject $rootScope
      * @inject appServices
      * @inject __DataStore
      * 
      * @return void
      *-------------------------------------------------------- */
   
    function appFancytree($rootScope, appServices, __DataStore ) {
	 	
    	var scope = this;
    	
		/**
	 	* remove item from fancytree
	 	*
	 	* @param object collectionObject
	 	* @param  listingFor
	 	* @param number keyID
	 	* @param string formType
	 	* @param number expandItem
	 	*
	 	* @return object
	 	*---------------------------------------------------------------- */

	 	scope.removeTreeItem = function(collectionObject, keyID, 
	 		formType, expandItem) {

	 		var self = this;

	 			_.forEach(collectionObject, function(object, key) {

		 			if( _.has(object, 'key') &&  object.key == keyID) {

		 				// remove current item when category edit
		 				if (formType == 'catEdit') {
		 				  	return _.remove(collectionObject, function(object) {
								return object.key === parseInt(keyID);
							});	

		 				} else if(formType == 'catAdd' && object.key == expandItem) {

		 					object.active   = true;

		 				} else if(formType == 'productAdd' && object.key == keyID) {
		 					object.selected   = true;
		 					
		 				}

		 			} else if( _.has(object, 'children')) {

		 				if (formType == 'catEdit') {
		 					
			 				if (object.key == expandItem) {
			 					object.active   = true;
			 				}

			 				if (object.key == expandItem) {
			 					object.expanded = true;
			 				}	

		 				} else if(formType == 'catAdd') {

		 					if (object.key == expandItem) {
			 					object.active   = true;
			 				}
			 				
			 				if (object.key == expandItem) {
			 					object.expanded = true;
			 				}

		 				} else if(formType == 'productAdd') {

		 					if (object.key == keyID) {
		 						object.selected   = true;	
		 					}

		 					if (keyID) {
		 						object.expanded  = true;
		 					}

		 				}
		 				self.removeTreeItem(object.children, keyID, formType, expandItem);
		 			}

				});

	 		return collectionObject;
	 			
	 	};

	 	/**
	 	* update expand & selected true 
	 	* on match IDs
	 	*
	 	* @param array hirarchicalObj
	 	* @param array nodeIDs
	 	* 
	 	* @return array
	 	*---------------------------------------------------------------- */

	 	var updateMultSelectedValue = function (hirarchicalObj, nodeIDs) {

	        if (hirarchicalObj && hirarchicalObj.length > 0) {

	            for (var i = 0; i < hirarchicalObj.length; i++) {

	                var item;
	               		
	               	_.forEach(nodeIDs, function(ID, key) {
	 				
		                if (ID == hirarchicalObj[i].key) {
		                    item = hirarchicalObj[i];
		                    item = hirarchicalObj[i]['selected'] = true;

		                    //item = hirarchicalObj[i]['expanded'] = true;
		                    if (!_.isEmpty(hirarchicalObj[i].parent_id)) {
		                		item = hirarchicalObj[i]['expanded'] = true;
		                	}

		                } else {
		                	
		                	if ((ID) && _.isEmpty(hirarchicalObj[i].parent_id)) {
		                		item = hirarchicalObj[i]['expanded'] = true;
		                	}
		                }
	                });

	                updateMultSelectedValue(hirarchicalObj[i].children, nodeIDs);

	            };

	        }

	        return item = hirarchicalObj;
	    };

	    /**
	 	* add expand & selected true 
	 	* on match ID
	 	*
	 	* @param array hirarchicalObj
	 	* @param array nodeID
	 	* 
	 	* @return array
	 	*---------------------------------------------------------------- */

	 	var addMultSelectedValue = function (hirarchicalObj, nodeID) {

	        if (hirarchicalObj && hirarchicalObj.length > 0) {

	            for (var i = 0; i < hirarchicalObj.length; i++) {

	                var item, pID;
	               		
	               	if (nodeID === hirarchicalObj[i].key) {
	                    item = hirarchicalObj[i];
	                    item = hirarchicalObj[i]['selected'] = true;

	                    if (!_.isEmpty(hirarchicalObj[i].parent_id)) {
	                		item = hirarchicalObj[i]['expanded'] = true;
	                	}

	                } else {

	                	if ((nodeID) && _.isEmpty(hirarchicalObj[i].parent_id)) {
	                		item = hirarchicalObj[i]['expanded'] = true;
	                	}
	                }

                 	

	                addMultSelectedValue(hirarchicalObj[i].children, nodeID);

	            };

	        }

	        return item = hirarchicalObj;
	    };	

	 	/**
	 	* perform fancytree action
	 	*
	 	* @param object param1 type 
	 	*
	 	* @return void
	 	*---------------------------------------------------------------- */

	 	scope.getFancytreeTreeFormate = function(treeSource, listingFor, formType, categoryID) 
	 	{	
	 		scope.expandItem = 0;

	 		_.forEach(treeSource, function(object, index) {

 				if (!_.isArray(categoryID) && object.key == categoryID) {
 					
					if (formType == 'catEdit') {

						scope.expandItem = object.parent_id;

					} else if (formType == 'catAdd' && categoryID) {

						scope.expandItem = object.key;
					} 
				} 
 			});
	 		
 			return this.addItems(
 					treeSource,
 				 	listingFor,
 				  	formType,
 				   	categoryID,
 				    scope.expandItem
 				);
	 		
		};



		/**
	 	* making fancytree format on form request 
	 	*
	 	* @param object array
	 	* @param string listingFor
	 	* @param string formType
	 	* @param int categoryID
	 	* @param int expandItem
	 	*
	 	* @return object
	 	*---------------------------------------------------------------- */

		this.addItems =  function (array, listingFor, formType, categoryID, expandItem) {
			
			scope.treeFormating = __globals.buildTree(array);
 			
 			// this portion belogs to categories fancytree
 			if (_.isString(listingFor) &&  
 				listingFor == 'categories' || listingFor == 'pages') {

 				// murge object in dyanmic object of fancytree soruce 
 				// for make category direct parent
 				scope.treeFormating.splice(0, 0, {
					'title'     : 'Make a Parent',
		            'key'       : 0,
		            'parent_id' : null
				});

				if (formType == 'catAdd') {

					if (!categoryID) {

						scope.fancytreeSource = scope.treeFormating;

					} else {

						scope.fancytreeSource = scope.removeTreeItem(scope.treeFormating, categoryID, formType, expandItem);
					}
					

				} else if (_.isString(formType) &&  formType == 'catEdit') {

					// call function for remove current category fancytree item
	 				scope.fancytreeSource = scope.removeTreeItem(scope.treeFormating, categoryID, formType, expandItem);	
				}

 				
 			} else if (_.isString(listingFor) &&  listingFor == 'products') {
 				
 				if (_.isString(formType) &&  formType == 'productEdit') {
 					scope.fancytreeSource = updateMultSelectedValue(scope.treeFormating, categoryID);
 				} else {
 					scope.fancytreeSource = addMultSelectedValue(scope.treeFormating, categoryID);
 					//scope.fancytreeSource = scope.removeTreeItem(scope.treeFormating, categoryID, formType, expandItem);
 				}
 			} 

 			return scope.fancytreeSource;
		};

    }

})();;
(function() {
'use strict';

	angular.module('app.pagination', [])
    		.filter("forLoop", forLoop)
    		.filter("paginate", ['Paginator', paginate ])
    		.service("Paginator", ['$rootScope', Paginator ]);

  	
  	/**
      * paginate filter 
      * 
      * @inject Paginator service
      * 
      * @return void
      *-------------------------------------------------------- */
   
    function paginate(Paginator) {
	 	
    	return function(input, rowsPerPage) {

            if (!input) {
                return input;
            }

            if (rowsPerPage) {
                Paginator.rowsPerPage = rowsPerPage;
            }
            
            Paginator.itemCount = input.length;

            return input.slice(parseInt(Paginator.page * Paginator.rowsPerPage), parseInt((Paginator.page + 1) * Paginator.rowsPerPage + 1) - 1);
        }

    };


    /**
      * forLoop filter 
      * 
      * @return void
      *-------------------------------------------------------- */
   
    function forLoop() {
    	return function(input, start, end) {

            input = new Array(end - start);
            
            for (var i = 0; start < end; start++, i++) {
                input[i] = start;
            }

            return input;
        }
    };

    /**
      * pagination  service 
      * 
      * @inject $rootScope
      * 
      * @return void
      *-------------------------------------------------------- */
   
    function Paginator($rootScope) {
	 	
    	var scope 			= this;
    	scope.page 			= 0;
        scope.rowsPerPage 	= 50;
        scope.itemCount 	= 0;
        scope.limitPerPage  = 5;

        this.setPage = function (page) {
            if (page > scope.pageCount()) {
                return;
            }
			scope.page = page;
        };

        this.nextPage = function () {
            if (scope.isLastPage()) {
                return;
            }

            scope.page++;
        };

        this.perviousPage = function () {
            if (scope.isFirstPage()) {
                return;
            }

            scope.page--;
        };

        this.firstPage = function () {
            scope.page = 0;
        };

        this.lastPage = function () {
            scope.page = scope.pageCount() - 1;
        };

        this.isFirstPage = function () {
            return scope.page == 0;
        };

        this.isLastPage = function () {
            return scope.page == scope.pageCount() - 1;
        };

        this.pageCount = function () {
            return Math.ceil(parseInt(scope.itemCount) / parseInt(scope.rowsPerPage));
        };
        
        this.lowerLimit = function() { 
            var pageCountLimitPerPageDiff = scope.pageCount() - scope.limitPerPage;
            
            if (pageCountLimitPerPageDiff < 0) { 
                return 0; 
            }
            
            if (scope.page > pageCountLimitPerPageDiff + 1) { 
                return pageCountLimitPerPageDiff; 
            } 
            
            var low = scope.page - (Math.ceil(scope.limitPerPage/2) - 1); 
            
            return Math.max(low, 0);
        };

    }

})();;
(function() {
'use strict';

	angular.module('app.directives', [])
	  	.directive("lwNgColorbox", lwNgColorbox)
	  	.directive("lwColorPicker", lwColorPicker)
		.directive("lwRateIt", lwRateIt)
        .directive("lwPopup", lwPopup)
        .directive("lwTransliterate", ['appServices', 'TransliterateDataServices', lwTransliterate])
        .directive("lwExpander", lwExpander)
	  	.directive("lwFancytree",[ 'appFancytree', lwFancytree ])
        .directive("lwChart", lwChart)
        .directive('lwFilterList', [ '$timeout', function($timeout) {
		    return {
		        link: function(scope, element, attrs) {

		            var li 			= Array.prototype.slice.call(element[0].children),
		                searchTerm  = attrs.lwFilterList;

		            function filterBy(value) {

		                li.forEach(function(el) {

		                	var $ele       = $(el),
		                	    searchTags = $ele.attr('data-tags') ? $ele.attr('data-tags') : '',
		                	    existClass = $ele.attr('class');

	                	    existClass = existClass.replace('ng-hide', '');

		                    el.className = searchTags.toLowerCase().indexOf(value.toLowerCase()) !== -1 ? existClass : existClass+' ng-hide';

		                });

		            }

		            scope.$watch(attrs.lwFilterList, function(newVal, oldVal) {
		                if (newVal !== oldVal) {
		                    filterBy(newVal);
		                }
		            });

		        }
		    };
		}])
        .filter('range', function() {
            return function(input, total) {
                total = parseInt(total);
                for (var i=0; i<total; i++)
                input.push(i);
                return input;
            };
        });


	/**
      * lwRateIt Directive.
      * 
      * For apply jquery Rate-It property on attribute
      *
      *
      * @return void
      *-------------------------------------------------------- */

    function lwRateIt() {
        
        return {
            restrict    : 'A',

            link : function (scope, element, attrs) {
                var rating = attrs.lwRateIt * 32;
                $(element).css('width', rating + "px", 'height', 32);
            }
        };
    };

    /**
      * lwExpander Directive.
      * 
      * For apply jquery expander property on attribute
      *
      *
      * @return void
      *-------------------------------------------------------- */

    function lwExpander() {
        
        return {
            restrict    : 'A',
            link : function (scope, element, attrs) {

                _.defer(function(text) {
                    $(element).expander(
                        {
                            slicePoint: attrs.sliceCount
                        }
                    );
                });
            }
        };
    };

    /**
      * lwPopup Directive.
      *
      * For apply jquery expander property on attribute
      *
      * @return void
      *-------------------------------------------------------- */

    function lwPopup() {

        return {
            restrict    : 'A',
            link : function (scope, element, attrs) {

                $(element).popover({
                    html: true, 
                    content: function() {
                      return attrs.message;
                    }
                });
            }
        };
    };

    /**
      * lwPopup Directive.
      *
      * For apply jquery expander property on attribute
      *
      * @return void
      *-------------------------------------------------------- */

    function lwTransliterate(appServices, TransliterateDataServices) {

        return {
            restrict    : 'A',
            link : function (scope, element, attrs) {
                element.click(function() {
                    appServices.showDialog(
                    {
                        entityType: attrs.entityType,
                        entityId: attrs.entityId,
                        entityKey: attrs.entityKey,
                        entityString: attrs.entityString,
                        inputType: attrs.inputType
                    }, 
                    {
                        templateUrl : __globals.getTemplateURL('translate-dialog'),
                        controller: 'TransliterateDialogController as TransliterateDialogCtrl',
                        resolve: {
                            getTransliterateData: ['TransliterateDataServices', function(TransliterateDataServices) {
                                return TransliterateDataServices
                                        .getTransliterateSupportData(attrs.entityType, attrs.entityId, attrs.entityKey);
                            }]
                        }
                    }, 
                    function() {

                    });
                });
            }
        };
    };

    /**
	  * lwNgColorbox Directive.
	  * 
	  * For apply jquery color box property on element
	  *
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function lwNgColorbox() {

		return {
            restrict    : 'A',
            link        : function(scope, element, attrs) {

                element.click(function( e ) {
                    e.preventDefault();
                    $.colorbox({
                        maxwidth    : "90%",
                        maxHeight   : "90%",
                        href        :this.href
                    });
                });

            }
        };

	};

	/**
	  * lwColorPicker Directive.
	  * 
	  * For apply jquery color box property on element
	  *
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function lwColorPicker() {

		return {
            restrict    : 'A',
            scope: {
                ngModel : "="
            },
            link        : function(scope, element, attrs) {

                element.focus(function( e ) {
                	
                    e.preventDefault();
                    
                    $(element).colpick({
						flat:false,
						layout:'hex',
						submit:true,
                        open :true,
						onChange:function(hsb,hex,rgb,el,bySetColor) {
	                        // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
	                       
							scope.$evalAsync(function () {
							  scope.ngModel = hex;
                              $('#lwchangeHeaderColor').css('background', "#"+hex);
							});
							
						},
                        onSubmit:function() {

                            $(element).colpickHide();
                        }

					});
                });

            }
        };

	};

    /**
      * lwPiaChart Directive.
      *
      * For apply jquery Rate-It property on attribute
      *
      *
      * @return void
      *-------------------------------------------------------- */

    function lwChart() {

        return {
            restrict    : 'A',
            scope : {
                source  : "=",
                labels  : "=",
                colors  : "=",
                options : "="
            },
            link : function (scope, element, attrs) {

            var ctx = element[0].getContext("2d");
            
            var options = {
                    responsive: true,
                    legend: {
                        onClick: function (e) {
                            e.stopPropagation();
                        }
                    }
                };
                
                if (_.has(attrs, 'options') && !_.isUndefined(attrs.options) && _.isEmpty(attrs.options)) {

                    options = attrs.options;
                }

                
                var myPieChart = new Chart(ctx,{
                    type: attrs.lwChart,
                    data: {
                        datasets: [{
                            data: __globals.makeToArrayWithEval(attrs.source),
                            backgroundColor: __globals.makeToArrayWithEval(attrs.colors)
                        }],
                        labels: __globals.makeToArrayWithEval(attrs.labels)
                    },
                    options: options
                }); 
            }
        };
    };


	/**
	  * lwFancytree Directive.
	  * 
	  * For apply jquery fancytree property on element
	  *
	  *
	  * @return void
	  *-------------------------------------------------------- */

	function lwFancytree(appFancytree) {

		return {
            restrict: 'A',
            scope: {
                fieldFor    : '@',
                ngModel     : '=',
				source      : '='
            },
            link: function (scope, element, attrs) {

  				attrs.$observe('source', function() {

	            	var treeData = appFancytree.getFancytreeTreeFormate(
	            						eval(attrs.source),
				            			attrs.listingFor, 
				            			attrs.formType, 
				            			eval(attrs.formId)
				            		);

	            	var tree       = element.fancytree(),
	                    formData   = element.parents('form.lw-ng-form')
	                                      .data('$formController'),
	                    formField  =  formData[scope.fieldFor];


		                treeData = appFancytree.getFancytreeTreeFormate(
	            						eval(attrs.source),
				            			attrs.listingFor, 
				            			attrs.formType, 
				            			eval(attrs.formId)
				            		);
	                		
	                if (tree) {
	                   tree.fancytree('destroy');
	                }

	            	if (attrs.listingFor === 'categories' || attrs.listingFor === 'pages') {
	            	
	            		tree.fancytree({
		                	source          : treeData,
		                    extensions      : ["glyph", "dnd"],
					        autoActivate    : true, // Automatically activate a node when it is focused (using keys).
					        autoCollapse    : true, // Automatically collapse all siblings, when a node is expanded.
					        selectMode      : 1,
					        activate        : function(event, data) {
					        	scope.$apply(function() {
							        scope.ngModel = data.node.key;
							    });
					        },
					        debugLevel      : 1,
					        autoScroll      : true,
					        generateIds     : false,
					        activeVisible   : true,
					        clickFolderMode : 3,
					        icon            : true,
					        idPrefix        : "cat",
					        dataType        : "json",
					        dnd             : {
					               dragStart: function() {}
					        },
					        glyph           : {
					          map             : {
					              doc             : "fa fa-file",
					              docOpen         : "fa fa-file",
					              error           : "fa fa-warning-sign",
					              expanderClosed  : "fa fa-plus-circle",
					              expanderOpen    : "fa fa-minus-circle",
					              folder          : "fa fa-folder",
					              folderOpen      : "fa fa-folder-open",
					              loading         : "fa fa-refresh",
					          }
					        }
		                    
		                });
	            	} else {
	            		
	            		tree.fancytree({
		                	source          : treeData,
		                    extensions      : ["glyph", "dnd"],
					        autoCollapse    : false,
					        selectMode      : 2,
					        checkbox        : true,
					        debugLevel      : 1,
					        autoScroll      : true,
					        generateIds     : false,
					        activeVisible   : true,
					        clickFolderMode : 3,
					        cache           : true,
					        idPrefix        : "cat",
					        dataType        : "json",
					        select: function(event, data) {

								// Display list of selected nodes
								var selNodes     = data.tree.getSelectedNodes(),
	                                selectedIDs  = [],
	                                formField    = formData[scope.fieldFor];

								scope.ngModel = [];

								// convert to title/key array
								$.map(selNodes, function(node) {
										
								    selectedIDs.push(node.key);

								});

	                            scope.$apply(function() {

	                                scope.ngModel = selectedIDs;

	                            });
								
							},
					        dnd             : {
					                dragStart: function() {}
					        },
					        glyph           : {
					          	map             : {
						            doc             : "fa fa-file",
					                docOpen         : "fa fa-file",
					                error           : "fa fa-warning-sign",
					                expanderClosed  : "fa fa-plus-circle",
					                expanderLazy    : "fa fa-plus-circle",
					                expanderOpen    : "fa fa-minus-circle",
					                folder          : "fa fa-folder",
					                folderOpen      : "fa fa-folder-open",
					                loading         : "fa fa-refresh",
					                checkbox        : "fa fa-square-o",
					                checkboxSelected: "fa fa-check-square",
					                checkboxUnknown : "fa fa-share",
					          	}
					        },
		                    
		                });
	            	}

	            });
				
			}
        }

	};
    
})(); ;
"use strict";
// App Global Resources

// check if browser is ie - http://hsrtech.com/psd-to-html/conditional-comments-ie11/
var isInternetExplorer = false;
var ua = window.navigator.userAgent;
var oldIE = ua.indexOf('MSIE ');
var newIE = ua.indexOf('Trident/');
    
if ((oldIE > -1) || (newIE > -1)) {
    isInternetExplorer = true;
}

// Promise Polyfill for IE - https://github.com/taylorhakes/promise-polyfill
if(isInternetExplorer) {

	!function(t){function e(){}function n(t,e){return function(){t.apply(e,arguments)}}function o(t){if("object"!=typeof this)throw new TypeError("Promises must be constructed via new");if("function"!=typeof t)throw new TypeError("not a function");this._state=0,this._handled=!1,this._value=void 0,this._deferreds=[],s(t,this)}function r(t,e){for(;3===t._state;)t=t._value;return 0===t._state?void t._deferreds.push(e):(t._handled=!0,void a(function(){var n=1===t._state?e.onFulfilled:e.onRejected;if(null===n)return void(1===t._state?i:f)(e.promise,t._value);var o;try{o=n(t._value)}catch(r){return void f(e.promise,r)}i(e.promise,o)}))}function i(t,e){try{if(e===t)throw new TypeError("A promise cannot be resolved with itself.");if(e&&("object"==typeof e||"function"==typeof e)){var r=e.then;if(e instanceof o)return t._state=3,t._value=e,void u(t);if("function"==typeof r)return void s(n(r,e),t)}t._state=1,t._value=e,u(t)}catch(i){f(t,i)}}function f(t,e){t._state=2,t._value=e,u(t)}function u(t){2===t._state&&0===t._deferreds.length&&a(function(){t._handled||d(t._value)});for(var e=0,n=t._deferreds.length;n>e;e++)r(t,t._deferreds[e]);t._deferreds=null}function c(t,e,n){this.onFulfilled="function"==typeof t?t:null,this.onRejected="function"==typeof e?e:null,this.promise=n}function s(t,e){var n=!1;try{t(function(t){n||(n=!0,i(e,t))},function(t){n||(n=!0,f(e,t))})}catch(o){if(n)return;n=!0,f(e,o)}}var l=setTimeout,a="function"==typeof setImmediate&&setImmediate||function(t){l(t,0)},d=function(t){"undefined"!=typeof console&&console&&console.warn("Possible Unhandled Promise Rejection:",t)};o.prototype["catch"]=function(t){return this.then(null,t)},o.prototype.then=function(t,n){var o=new this.constructor(e);return r(this,new c(t,n,o)),o},o.all=function(t){var e=Array.prototype.slice.call(t);return new o(function(t,n){function o(i,f){try{if(f&&("object"==typeof f||"function"==typeof f)){var u=f.then;if("function"==typeof u)return void u.call(f,function(t){o(i,t)},n)}e[i]=f,0===--r&&t(e)}catch(c){n(c)}}if(0===e.length)return t([]);for(var r=e.length,i=0;i<e.length;i++)o(i,e[i])})},o.resolve=function(t){return t&&"object"==typeof t&&t.constructor===o?t:new o(function(e){e(t)})},o.reject=function(t){return new o(function(e,n){n(t)})},o.race=function(t){return new o(function(e,n){for(var o=0,r=t.length;r>o;o++)t[o].then(e,n)})},o._setImmediateFn=function(t){a=t},o._setUnhandledRejectionFn=function(t){d=t},"undefined"!=typeof module&&module.exports?module.exports=o:t.Promise||(t.Promise=o)}(this);

        // Check if IE then hide slider navigations
        var $sliderNavigations = document.getElementsByClassName('owl-nav');
        _.delay(function() {
            _.forEach($sliderNavigations, function(item) {
                $(item).hide();
            });
        }, 500);
}

// a container to hold underscore template data
_.templateSettings.variable = "__tData"; 
__globals.baseKCFinderPath = './upload-manager/';

/**
  * ckEditor link target customization
  *
  *-------------------------------------------------------- */
/* Here we are latching on an event ... in this case, the dialog open event */
if(window.CKEDITOR) {
    CKEDITOR.on('dialogDefinition', function(ev) {

    try {

        /* this just gets the name of the dialog */

    var dialogName = ev.data.name;

    /* this just gets the contents of the opened dialog */

    var dialogDefinition = ev.data.definition;   

    /* Make sure that the dialog opened is the link plugin ... otherwise do nothing */

    if(dialogName == 'link') {
        /* Getting the contents of the Target tab */

        var informationTab = dialogDefinition.getContents('target');

        /* Getting the contents of the dropdown field "Target" so we can set it */

        var targetField = informationTab.get('linkTargetType');

        // Set target options removed for forum comment editor
        if (_.has(CKEDITOR.instances, 'comment')) {

            targetField.items = [];
            targetField.items.unshift(["_default", "_default"]);

        } else {
             // Add new
            targetField.items.unshift(["_default", "_default"]);
        }
       

        /* Now that we have the field, we just set the default to _blank

        A good modification would be to check the value of the URL field

        and if the field does not start with "mailto:" or a relative path,

        then set the value to "_blank" */

       // targetField['default'] = '_default';

    }
        } catch(exception) {

            alert('Error ' + ev.message);
        }
});
}

_.assign(__globals, {

      authConfig  : {
        redirects   : {
          guestOnly     : 'user_profile_edit',
          authorized    : 'user_login',
          accessDenied  : 'unauthorized'
        }
      },

        getAuthorizationToken : function() {
            return window.__appImmutables.auth_info.authorization_token;
        },

        getAppImmutables: function(immutableID) {

            if (immutableID) {
                return window.__appImmutables[immutableID];
            } else {
                return window.__appImmutables;
            }            
        },

        getAppJSItem: function(key) {

            return window[key];          
        },

        getJSString : function(stringID) {

            var messages = this.getAppImmutables('messages');
            return messages.js_string[stringID];

        },

        getReplacedString : function(element, replaceKey, replaceValue) {

            return element.attr('data-message')
                    .replace(replaceKey , '<strong>'+unescape(replaceValue)+'</strong>');

        },

        /**
          * Show action confirmation
          *
          * @param object options
          * @param function callback
          *
          * @return void
          *---------------------------------------------------------------- */

        showConfirmation : function(options, callback) {

            var defaultOptions       = {
                title              : this.getJSString('confirm_title'),
                showCancelButton   : true,
                cancelButtonText   : this.getJSString('confirm_cancel_button_text'),
                closeOnConfirm     : false,
                allowEscapeKey     : false,
  				allowOutsideClick  : false,
                confirmButtonColor :  "#c9302c",
                confirmButtonClass : 'btn-success',
                onOpen: function() {
                    $('html').addClass('lw-disable-scroll');
                },
                onClose: function() {
                    $('html').removeClass('lw-disable-scroll');
                }
            };

            // Check if callback exist 
            if (callback && _.isFunction(callback)) {

                _.assign(defaultOptions, options);
                
                swal(defaultOptions).then(function(result) {
                    // handle Confirm button click
                    // result is an optional parameter, needed for modals with input
                    if (result.value) {
                        return callback.call(this);
                    }

                  }, function(dismiss) {

                        // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                        if (closeCallback && _.isFunction(closeCallback)) {
                            return closeCallback.call( this, dismiss );
                        }
                        
                  });

            } else {
            
                // show only simple confirmation
                swal(options.title, options.text, options.type);
            }

        },
        
        buildTree: function (arry, labels) {

        	var childrenLabel = 'children';

        	if (labels && _.isString(labels)) {
        		childrenLabel = labels;
        	}

		    var roots = [], children = {}, parentID = null;

		    // find the top level nodes and hash the children based on parent
		    for (var i = 0, len = arry.length; i < len; ++i) {
		        var item = arry[i],
		            p = item.parent_id;
		            var target = !p ? roots : (children[p] || (children[p] = []));
		        	target.push(item);
		    }

		    // function to recursively build the tree
		    var findChildren = function(parent) {
		    	
		        if (children[parent.key]) {
		            parent[childrenLabel] = children[parent.key];
		            parent['folder']      = true;
		            for (var i = 0, len   = parent[childrenLabel].length; i < len; ++i) {
		                findChildren(parent[childrenLabel][i]);
		            }
		        }
		    };

		    // enumerate through to handle the case where there are multiple roots
		    for (var i = 0, len = roots.length; i < len; ++i) {
		        findChildren(roots[i]);
		    }

		       
		    return roots;
		},

        /**
          * Get selectize configration options
          *
          * @inject object options
          *
          * @return object
          *---------------------------------------------------------------- */
  
        /*getSelectizeOptions : function (options) {

            this.defaultOptions = {   
                maxItems    : 1,
                valueField  : 'id',
                labelField  : 'name',
                searchField : [ 'name' ]  
            };

            return _.assign(this.defaultOptions, options);

        },*/
        
        // Check is Numeric vlaue or pure number
        isNumeric: function(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },

        getSelectizeOptions : function (options) {

            this.defaultOptions = {
                maxItems        : 1,
                valueField      : 'id',
                labelField      : 'name',
                searchField     : ['name'],
                onInitialize    : function(selectize) {

                var currentValue = selectize.getValue();
                
                if (_.isEmpty(currentValue) === false &&
                    (_.isArray(currentValue) === false &&
                        _.isObject(currentValue) === false &&
                        _.isString(currentValue) === true)) {
                   
                        if (_.includes(currentValue, ',')) {

                            var currentValues = currentValue.split(",");
                           
                            for(var a in currentValues) {
                               
                                currentValues[a] = (__globals.isNumeric(currentValues[a])) ? Number(currentValues[a]) : currentValues[a];
                            }

                            selectize.setValue(currentValues);

                        } else {

                            if (__globals.isNumeric(currentValue)) {

                                selectize.setValue(Number(currentValue));

                            } else {

                                selectize.setValue(currentValue);
                            }
                        }
                    }
                }
            };
            
            return _.assign(this.defaultOptions, options);
        },

        /**
         * Parse amount to required
         * 
         * @param array $items
         *-------------------------------------------------------- */

         parseAmount : function(amount) {
            
            return parseFloat(amount)
         },

        /**
         * Make a price format
         * 
         * @param array $items
         *-------------------------------------------------------- */

        priceFormat : function(amount, currencySymbol, currency) {
            var currencyFormat =  __globals.getAppImmutables('config')['currency_format'];

            if(! _.isUndefined(currencySymbol) || ! _.isUndefined(currency)) {

                var rawFormat = currencyFormat['raw'];
                    
                    return rawFormat.replace("{__amount__}", amount)
                                    .replace("{__currencySymbol__}", currencySymbol)
                                    .replace("{__currencyCode__}", currency);

            } else if(_.isUndefined(currency)) {

                var fullFormat = currencyFormat['full'];

                return fullFormat.replace("{__amount__}", amount);

            } else if(currency == false) {

                var shortFormat = currencyFormat['short'];

                return shortFormat.replace("{__amount__}", amount);

            } else {

                return currency;
            }
        },

        /**
         * Redirect browser
         * 
         * @param array $url
         *-------------------------------------------------------- */

         showProcessingDialog : function(url) {

            $('html').addClass('lw-has-disabled-block');
            $('.lw-disabling-block').addClass('lw-disabled-block lw-has-processing-window');
         },

        /**
         * Redirect browser
         * 
         * @param array $url
         *-------------------------------------------------------- */

         hideProcessingDialog : function(url) {

            $('html').removeClass('lw-has-disabled-block');
            $('.lw-disabling-block').removeClass('lw-disabled-block lw-has-processing-window');
         },

	    /**
	     * get categories data
	     *
	     *-------------------------------------------------------- */
	    getCategoriesData : function() {

	        return _.cloneDeep(window.__appImmutables['categories']);
	    },

	    /**
	     * get active categories data
	     *
	     *-------------------------------------------------------- */
	    getActiveCategoriesData : function() {
	        return _.cloneDeep(window.__appImmutables['active_categories']);
	    },

	    /**
	     * get pages data
	     *
	     *-------------------------------------------------------- */
	    getPagesData : function() {

	        return _.cloneDeep(window.__appImmutables['pages']);
	    },

        /**
         * get pages data
         *
         *-------------------------------------------------------- */
        makeToArrayWithEval : function(object) {

            return _.toArray(eval(object));
        },

	    /**
	     * get active pages data
	     *
	     *-------------------------------------------------------- */
	    getActivePagesData : function() {

	        return _.cloneDeep(window.__appImmutables['active_pages']);
	    },

	    /**
	     * get pages type
	     *
	     *-------------------------------------------------------- */
	    getPagesTypes : function() {

	        return window.__appImmutables['pageType'];
	    },

        /**
         * Check if current app is Public or manage app
         *
         *-------------------------------------------------------- */
        isPublicApp : function() {

            return window.__appImmutables['publicApp'];
        },

	    /**
	     * get pages type
	     *
	     *-------------------------------------------------------- */
	    getPagesLinks : function() {

	        return window.__appImmutables['pageLink'];
	    },

	    /**
	     * get pages type
	     *
	     *-------------------------------------------------------- */
	    findParents : function(itemCollection, findItem, existingCollection) {

			if(!existingCollection) {
				var existingCollection = new Array();
			}

			for(var item in itemCollection) {
				var thisItem = itemCollection[item];

				if(thisItem.key === parseInt(findItem)) {
					existingCollection.push(thisItem);

					if(thisItem.parent_id) {
						this.findParents(itemCollection, thisItem.parent_id, existingCollection);
					}
				}
			}

			return existingCollection;
		},

		/**
	     * slug text
	     *
	     *-------------------------------------------------------- */
	    slug : function(str) {

			var $slug   = '';
		    var trimmed = $.trim(str);
		    	$slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
		    	replace(/-+/g, '-').
		    	replace(/^-|-$/g, '');
		    return $slug.toLowerCase();
		},

		/**
	      * get config items
	      *
	      * @return bool
	      *---------------------------------------------------------------- */

	    configItem : function(key) {
	        return window.__appImmutables.config[key];
	    },

        /**
          * Get Scroll Offset
          *
          * @return bool
          *---------------------------------------------------------------- */
        getScrollOffsets : function() {
            var doc = document, w = window;
            var x, y, docEl;
            
            if ( typeof w.pageYOffset === 'number' ) {
                x = w.pageXOffset;
                y = w.pageYOffset;
            } else {
                docEl = (doc.compatMode && doc.compatMode === 'CSS1Compat')?
                        doc.documentElement: doc.body;
                x = docEl.scrollLeft;
                y = docEl.scrollTop;
            }
            return {x:x, y:y};
        },

	    /**
	     * Generate key vlaue page formate for
	     * 
	     * @param array $data
	     *-------------------------------------------------------- */

	     generateKeyValueOption : function(configKey) {
		
			var items = window.__appImmutables.config[configKey];
	        var option   = [];

	      _.forEach(items, function(value, key) {

            option.push({
                id : parseInt(key),
                name : value
	        });

	      });

	      return option;
         },
    
    
        filterArrayValue : function(items) {

            var option = [];
            
            _.each(items, function(value, key) {
                
                option.push(parseInt(value));

            });

            return option;
        },

	   /**
	     * Generate key value option for items
	     * 
	     * @param array $items
	     *-------------------------------------------------------- */

	     generateKeyValueItems : function(items) {
			
			var option   = [];

			_.forEach(items, function(value, key) {

            option.push({
                id : parseInt(key),
                name : value
	        });

	      });

	      return option;
	     },

    /**
         * Redirect browser
         * 
         * @param array $url
         *-------------------------------------------------------- */

         redirectBrowser : function(url) {

            $('html').addClass('lw-has-disabled-block');
            $('.lw-disabling-block').addClass('lw-disabled-block lw-has-processing-window');
            
            window.location = url;
         },

	    

        /**
          * Set ckEditor options
          *
          *-------------------------------------------------------- */
        
        ckEditorConfig: function() {

            return {
                contentsCss :[
                    window.__appImmutables.static_assets.vendor_css,
                    window.__appImmutables.static_assets.application_css,
                    window.__appImmutables.static_assets.public_css,
                    window.__appImmutables.static_assets.css_style
                ],
                //filebrowserImageBrowseUrl : __globals.baseKCFinderPath+'browse.php?type=images&authorize_token='+__globals.getAuthorizationToken(),
                //filebrowserImageUploadUrl : __globals.baseKCFinderPath+'upload.php?type=images&authorize_token='+__globals.getAuthorizationToken(),
                filebrowserImageBrowseUrl: window.__appImmutables.ckeditor.filebrowserImageBrowseUrl,
                filebrowserImageUploadUrl: window.__appImmutables.ckeditor.filebrowserImageUploadUrl,
                filebrowserBrowseUrl: window.__appImmutables.ckeditor.filebrowserBrowseUrl,
                filebrowserUploadUrl: window.__appImmutables.ckeditor.filebrowserUploadUrl,
                allowedContent : true,
                toolbar : [
                    { 
                        name    : 'basicstyles',
                        items   : [
                            'Bold',
                            'Italic',
                            'Underline'
                        ] 
                    },
                    { 
                        name    : 'ClipboardJS',
                        items   : [ 
                            'Cut',
                            'Copy',
                            'Paste', 
                            '-',
                            'Undo',
                            'Redo'
                        ]
                    },   // '/',
                    { 
                        name    : 'links',
                        items   : [ 
                            'Link',
                            'Unlink',
                            'Anchor'
                        ]
                    },
                    { 
                        name    : 'insert',
                        items   : [ 'Image'/*, 'UploadManager'*/]
                    },
                    { 
                        name    : 'tools',
                        items   : [ 'Maximize']
                    },
                    {   name: 'paragraph',
                     	items : [ 
	                     	'NumberedList',
	                     	'BulletedList',
	                     	'-','Outdent',
	                     	'Indent',
	                     	'-',
	                     	'Blockquote',
	                     	'CreateDiv',
							'-','JustifyLeft',
							'JustifyCenter','JustifyRight',
							'JustifyBlock','-',
							'BidiLtr',
							'BidiRtl' 
						] 
					},
                    { 
                        name    : 'styles',
                        items   : [ 
                        ]
                    },
                    { 
                        name    : 'colors',
                        items   : [ 
                            'TextColor',
                            'BGColor' 
                        ] 
                    },
                    { 
                        name    : 'document',
                        items   : [ 
                        ]
                    },
                    { 
                    name    : 'source',
                    items   : [
                        'Source'
                    ]
                }
                ],
                //removeButtons     : 'Underline, Subscript, Superscript', // remove buttons
                format_tags         : 'p;h1;h2;h3;pre', // required formatting tags
                removeDialogTabs    : 'editing;link:upload;image:Upload',     // required remove dialog tabs,
                extraPlugins        : 'UploadManager,justify'
            };

        },
        
        /**
          * Set ckEditor options for limited options
          *
          *-------------------------------------------------------- */
        
        ckEditorLimitedOptionsConfig: function() {

            return {
            
                toolbar : [
                    { 
                        name    : 'basicstyles',
                        items   : [
                            'Bold',
                            'Italic',
                            'Underline'
                        ] 
                    },
                    { 
                        name    : 'ClipboardJS',
                        items   : [ 
                            'Cut',
                            'Copy',
                            'Paste', 
                            '-',
                            'Undo',
                            'Redo'
                        ]
                    },
                    { 
                        name    : 'tools',
                        items   : [ 'Maximize']
                    },
                    { 
                        name: 'paragraph',
                        items : [ 
                            'NumberedList',
                            'BulletedList',
                            'Blockquote'
                        ]
                    }
                ],
                //removeButtons     : 'Underline, Subscript, Superscript', // remove buttons
                format_tags         : 'p;h1;h2;h3;pre' // required formatting tags
            };

        }

});    


var __ngSupport = {
        getText:function(string, replaceObj) {

            if (replaceObj && _.isObject(replaceObj)) {

                _.forIn(replaceObj, function(value, key) {
                    string = string.replace(key, value);
                });
            }

             return string;
        }
};

//Datatables Defaults
 $.extend( $.fn.dataTable.defaults, {
    "serverSide"      : true,
    "searchDelay"     : 1800,
    "iCookieDuration" : 60,
    "paging"          : true,
    "processing"      : true,
    "responsive"      : true,
   "pageLength"      : 25,
    "destroy"         : true,
    "retrieve"        : true,
    "lengthChange"    : true,
    "language"        : {
                          "emptyTable": "There are no records to display."
                        },
    "searching"       : false,
    "ajax"            : {
      // any additional data to send
      "data"          : function ( additionalData ) {
        additionalData.page = (additionalData.start / additionalData.length) + 1;
      }
    }
  });;
(function() {
'use strict';

	angular.module('app.form', [])
	  	.directive("lwFormSelectizeField", [ 
            '__Form', lwFormSelectizeField
        ])
        .directive("lwFormCheckboxField", [ 
            '__Form', lwFormCheckboxField
        ])
        .directive("lwRecaptcha", lwRecaptcha)
        .directive('lwBootstrapMdDatetimepicker', lwBootstrapMdDatetimepicker)
        .directive('lwSelectAllCheckbox', function () {
            return {
                replace: true,
                restrict: 'E',
                scope: {
                    checkboxes: '=',
                    allselected: '=allSelected',
                    allclear: '=allClear',
                    selectedrows: '='
                },
                templateUrl:'lw-select-all-checkbox-field.ngtemplate',
                link: function ($scope, $element) {
             
                    $scope.masterChange = function () {
                        if ($scope.master) {
                            angular.forEach($scope.checkboxes, function (cb, index) {
                                cb.isSelected = true;
                            });
                        } else {
                            angular.forEach($scope.checkboxes, function (cb, index) {
                                cb.isSelected = false;
                            });
                        }
                    };
         
                    $scope.$watch('checkboxes', function () {
                        var allSet = true,
                            allClear = true,
                            collectSelectedRows = [];

                        angular.forEach($scope.checkboxes, function (cb, index) {

                            if (cb.isSelected) {
                                allClear = false;
                            } else {
                                allSet = false;
                            }

                            // collect here selected rows
                            if (cb.isSelected)  {
                                collectSelectedRows.push(cb); 
                            }
                        });

                        $scope.selectedrows = collectSelectedRows;

                        if ($scope.allselected !== undefined) {
                            $scope.allselected = allSet;
                        }
                        if ($scope.allclear !== undefined) {
                            $scope.allclear = allClear;
                        }
                    
                        $element.prop('indeterminate', false);
                        if (allSet) {
                            $scope.master = true;
                        } else if (allClear) {
                            $scope.master = false;
                        } else {
                            $scope.master = false;
                            $element.prop('indeterminate', true);
                        }
                        
                    }, true);
                }
            };
        })

        /**
          * lwFormRadioField Directive.
          * 
          * Form Field Radio Directive -
          * App Level Customise Directive
          *
          * @inject __Form
          *
          * @return void
          *-------------------------------------------------------- */

        .directive("lwFormRadioField", [
            '__Form',
            function ( __Form ) {

              return {

                restrict    : 'E',
                replace     : true,
                transclude  : true,
                scope       : {
                    fieldFor : '@'
                },
                templateUrl     : 'lw-form-radio-field.ngtemplate',
                link            : function(scope, elem, attrs, transclude) {

                    if(elem.hasClass('lw-remove-transclude-tag')) {
                        elem.find('ng-transclude').children().unwrap();
                    }

                    var formData    = elem.parents('form.lw-ng-form')
                                        .data('$formController'),
                    inputElement    = elem.find('.lw-form-field ');

                    //inputElement.prop('id', scope.fieldFor);

                    scope.formField                 = {};
                    scope.formField[scope.fieldFor] = attrs;

                    scope.lwFormData = { formCtrl:formData };

                    // get validation message
                    scope.getValidationMsg = function( key, labelName ) {

                        return __Form.getMsg(key, labelName);

                    };

                }

                }

            }
        ])

        /**
          * lwCkEditor Directive
          * App level ck editor directice for show ck editor 
          *
          * @return void
          *---------------------------------------------------------------- */

        .directive('lwCkEditor', ['__Utils', function(__Utils) {

            return {
                transclude: true,
                restrict: 'EA',
                require: '?ngModel',
                link: function (scope, element, attrs, ngModelCtrl) {

                    var options = angular.extend({
                        fullscreenable: true,
                        imageWidthModalEdit: true,
                        semantic: false,
                        closable: false,autogrow: true,
                        btnsDef: {
                            // Create a new dropdown
                            image: {
                                dropdown: ['insertImage', 'upload', 'noembed'],
                                ico: 'insertImage'
                            }
                        },
                        plugins: {
                            table: {
                                rows : 8,
                                columns : 8,
                                styler : 'table'
                            },
                            // Add imagur parameters to upload plugin for demo purposes
                            upload: {
                                serverPath:  __Utils.apiURL('file_manager.upload')+'?currentDir=',
                                fileFieldName: 'file',
                                headers     : {
                                    'X-XSRF-TOKEN': __Utils.getXSRFToken()
                                },
                                urlPropertyName: 'url'
                            }
                        },
                        btns: [
                            ['formatting', 'table'],
                            ['foreColor', 'backColor', 'underline','strikethrough','|','removeformat','viewHTML'],
                            ['strong', 'em', 'del'],
                            ['superscript', 'subscript'],
                            ['link'],
                            ['image'], // Our fresh created dropdown
                            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                            ['unorderedList', 'orderedList'],
                            ['horizontalRule'],
                            ['fullscreen']
                        ]
                    }, scope.$eval(attrs.editorConfig));

                    ngModelCtrl.$render = function() {
                        angular.element(element).trumbowyg('html', ngModelCtrl.$viewValue);
                    };

                    angular.element(element).trumbowyg(options).on('tbwchange', function () {
                        ngModelCtrl.$setViewValue(angular.element(element).trumbowyg('html'));
                    }).on('tbwpaste', function () {
                        ngModelCtrl.$setViewValue(angular.element(element).trumbowyg('html'));
                    });

                    scope.$parent.$watch(attrs.ngDisabled, function(newVal){
                        angular.element(element).trumbowyg(newVal ? 'disable' : 'enable');
                    });
                }
            };

        }]);
    
    /**
     * lwFormSelectizeField Directive.
     * 
     * App level customise directive for angular selectize as form field
     *
     * @inject __Form
     *
     * @return void
     *-------------------------------------------------------- */

    function lwFormSelectizeField(__Form) {

        return {

            restrict    : 'E',
            replace     : true,
            transclude  : true,
            scope       : {
                fieldFor : '@'
            },
            templateUrl : 'lw-form-selectize.ngtemplate',
            link        : function(scope, elem, attrs, transclude) {

                var formData        = elem.parents('form.lw-ng-form')
                                      .data('$formController'),
                    selectElement   = elem.find('.lw-form-field');

                selectElement.prop('id', scope.fieldFor);
              
                scope.formField                 = {};
                scope.formField[scope.fieldFor] = attrs;

                scope.lwFormData = { formCtrl : formData };

                // get validation message
                scope.getValidationMsg = function(key, labelName) {

                    return __Form.getMsg(key, labelName);

                };

            }

        };

    };

    /**
      * Custom directive for bootstrap-material-datetimepicker
      *
      * @return void
      *---------------------------------------------------------------- */
    
    function lwRecaptcha() {
        
         return {
                restrict: 'AE',
                scope   : {
                    sitekey : '='
                },
                require : 'ngModel',
                link : function(scope, elm, attrs, ngModel) {
                    var id;
                    ngModel.$validators.captcha = function(modelValue, ViewValue) {
                        // if the viewvalue is empty, there is no response yet,
                        // so we need to raise an error.
                        return !!ViewValue;
                    };

                    function update(response) {
                        ngModel.$setViewValue(response);
                        ngModel.$render();
                    }
                    
                    function expired() {
                        grecaptcha.reset(id);
                        ngModel.$setViewValue('');
                        ngModel.$render();
                        // do an apply to make sure the  empty response is 
                        // proaganded into your models/view.
                        // not really needed in most cases tough! so commented by default
                        // scope.$apply();
                    }

                    function iscaptchaReady() {
                        if (typeof grecaptcha !== "object") {
                            // api not yet ready, retry in a while
                            return setTimeout(iscaptchaReady, 0);
                        }
                        id = grecaptcha.render(
                            elm[0], {
                                // put your own sitekey in here, otherwise it will not
                                // function.
                                "sitekey": attrs.sitekey,
                                callback: update,
                                "expired-callback": expired
                            }
                        );
                    }
                    iscaptchaReady();
                }
            };

    };

    /**
     * lwFormCheckboxField Directive.
     * 
     * App level customise directive for checkbox form field
     *
     * @inject __Form
     *
     * @return void
     *-------------------------------------------------------- */

    function lwFormCheckboxField(__Form) {

        return {

            restrict    : 'E',
            replace     : true,
            transclude  : true,
            scope       : {
                fieldFor : '@'
            },
            templateUrl : 'lw-form-checkbox-field.ngtemplate',
            link        : function(scope, elem, attrs, transclude) {

                var formData        = elem.parents('form.lw-ng-form')
                                      .data('$formController'),
                    selectElement   = elem.find('.lw-form-field');

                selectElement.prop('id', scope.fieldFor);
              
                scope.formField                 = {};
                scope.formField[scope.fieldFor] = attrs;

                scope.lwFormData = { formCtrl : formData };

                // get validation message
                scope.getValidationMsg = function(key, labelName) {
                    
                    return __Form.getMsg(key, labelName);

                };

            }

        };

    };

    /**
      * Custom directive for bootstrap-material-datetimepicker
      *
      * @return void
      *---------------------------------------------------------------- */
    
    function lwBootstrapMdDatetimepicker() {

        return {
            restrict    : 'A',
            replace     : false,
            link        : function(scope, elem, attrs) {
                
                var dateTimePickerOptions       = {
                        time    : true,
                        okText  : 'Select'
                    };
                
                if( dateTimePickerOptions.time === true ) {
                    dateTimePickerOptions.format = 'YYYY-MM-DD HH:mm:ss';
                }
                    
                if (attrs.options) {                
                    _.assign(dateTimePickerOptions, 
                            eval('('+attrs.options+')')
                        );
                    
                }

                $(elem).bootstrapMaterialDatePicker(dateTimePickerOptions);

                angular.element('.dtp-btn-ok')
                    .addClass('btn btn-primary btn-sm lw-btn');
                angular.element('.dtp-btn-cancel')
                    .addClass('btn btn-sm lw-btn');

                angular.element(".dtp a:contains('clear')")
                    .addClass('lw-btn-icon')
                    .html('<i class="fa fa-times"></i>');

                angular.element(".dtp a:contains('chevron_left')")
                    .addClass('lw-btn-icon')
                    .html('<i class="fa fa-chevron-left"></i>');
                
                angular.element(".dtp a:contains('chevron_right')")
                    .addClass('lw-btn-icon')
                    .html('<i class="fa fa-chevron-right"></i>');

            }

        };

    };

    
})(); 
//# sourceMappingURL=../source-maps/app-support-app.src.js.map
