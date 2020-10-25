(function() {
'use strict';

  angular.module('FileManagerApp', [
    'ngMessages',
    'ngAnimate',
    'ngSanitize',
    'ui.router',
    'angular-loading-bar',
    'angularFileUpload',
    'selectize',
    'ngNotify',
    'ngDialog',
    'lw.core.utils',
    'lw.auth',
    'lw.data.datastore',
    'lw.data.datatable',
    'lw.security.main',
    'lw.form.main',
    'app.service',
    'app.notification',
    'app.form',
    'FileManagerAppEngine',
    'app.FileManagerEngine'
  //constant('__ngSupport', window.__ngSupport).
]).
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

   /* $urlRouterProvider
       .otherwise('/');

    //state configurations
    $stateProvider
        
        // home
        .state('home', 
             __globals.stateConfig('/', 'home')
        )

        // author account registration
        .state('author_account_registration', __globals.stateConfig( '/registration', 'account/account-registration', {
	        access  : { 
	        	guestOnly : true
	        }
        } ))
        ;*/

    };

})();;
(function() {
'use strict';
	
	/*
	 FileManagerAppEngine
	-------------------------------------------------------------------------- */
	
	angular
        .module('FileManagerAppEngine', [])

        /**
          * FileManagerService :- to manage file manager common methods
          * 
          *
          * @inject $rootScope
          *
          * @return object
          *-------------------------------------------------------- */

        .service("FileManagerService", [
        	'$rootScope', 
        	'$state', 
        	'appNotify',
        	'ngDialog', 
        	function ($rootScope, $state, appNotify, ngDialog) {

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
			          closeByEscape   : false,
			          closeByDocument : false,
			          data            : transmitedData

			        }).closePromise.then(function ( data ) {

			            return closeCallback.call( this, data );

			        });

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
			            showClose : true,
			            plain 	 : options.plain ? true : false

			        }, function( value ) {

			            return closeCallback.call( this, value );

			        });

			    };

    		}

		])

        .controller('FileManagerController', 	[
			'$rootScope',
            '__DataStore', 
            '$scope', 
            '__Auth', 
            'appServices', 
            'appNotify',
            '__Utils',
            'FileManagerService', 
            FileManagerController 
	 	])
        ;

 /**
	* MainController for manage page application
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

	function FileManagerController($rootScope, __DataStore, $scope, __Auth,  appServices, appNotify, __Utils, FileManagerService) {

	 	var scope 		= this,
	 		configData 	= __globals.getAppJSItem('file_manager_config');

        scope.pageStatus    = false;

	 	__Auth.refresh(function(authInfo) {

	 		scope.auth_info = authInfo;
        	
	 	});

        scope.unhandledError = function() {

            appNotify.error(__globals.getReactionMessage(19)); // Unhanded errors

        };

        scope.currentDir		 = '';
        scope.currentFolderData  = '';

        $rootScope.$on('lw.page.css_styles', function (event, data) {

            scope.pageCSSStyles = data.header_bg_color;

        });

        $rootScope.$on('lw.events.state.change_start', function () {
	 		appServices.closeAllDialog(); 
        });

        $rootScope.$on('lw.form.event.fetch.started', __globals.showFormLoader );

        $rootScope.$on('lw.datastore.event.fetch.finished', __globals.hideFormLoader );

        $rootScope.$on('lw.form.event.process.error', scope.unhandledError );

        $rootScope.$on('lw.datastore.event.fetch.error', scope.unhandledError );

        /**
          * Show upload new file dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.showUploadNewFileDialog  =  function() {

            FileManagerService.showDialog( {'currentDir' : scope.currentDir}, {
                templateUrl : __globals.getTemplateURL('file-manager.upload-file-dialog')
            },
            function(promiseObj) {

            	scope.retrieveFiles();

            });
        };

        /**
          * Show image file dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.showImageFileDialog  =  function(fileData) {

            FileManagerService.showDialog( {'fileData' : fileData}, {
                templateUrl : __globals.getTemplateURL('file-manager.image-file-dialog')
            },
            function(promiseObj) {

            });
            
        };

        /**
          * Show upload new file dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.useFile  =  function(fileData) {

        	// Check if dialog exist
			if (window.opener && !window.opener.closed) {

				var gp 	=	new Array();
				var loc = location.search, 
				nameValue;

				if (loc) {

					loc = loc.substring(1);

					var parms=loc.split('&');

					for (var i=0;i<parms.length;i++) {

						nameValue=parms[i].split('=');
        				gp[nameValue[0]]=unescape(nameValue[1]);

					}

				}

				var editor = gp['CKEditor'];

				/* I had multiple CKEDITORS using the same nav bar so there might be a little more code than you would normally need here. */
				var sourceElement 			= window.opener.CKEDITOR.instances[editor]._.filebrowserSe;
				var dialog 					= sourceElement.getDialog();
				var targetElement 			= sourceElement.filebrowser.target || null;

				/*  here is where I did stuff to the url */

				// If there is a reference to targetElement, update it.
				if ( targetElement )
				{
					var target 		= targetElement.split( ':' );
					var element 	= dialog.getContentElement( target[ 0 ], target[ 1 ] );

					if ( element )
					{
						element.setValue( fileData.previewUrl );
						dialog.selectPage( target[ 0 ] );
					}

				}

				window.close();
				
			} else {

				scope.showImageFileDialog(fileData);

			}

        };

        /**
          * Show add new folder dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.showAddFolderDialog  =  function() {

            FileManagerService.showDialog( {'currentDir' : scope.currentDir}, {
                templateUrl : __globals.getTemplateURL('file-manager.add-folder-dialog')
            },
            function(promiseObj) {

            	scope.retrieveFiles();

            });
        };

        /**
          * Show edit new folder dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.showRenameFolderDialog  =  function(name, folder_relative_path) {

            FileManagerService.showDialog( { 
            	'currentDir' 			: scope.currentDir,
            	'name' 		 			: name,
            	'folder_relative_path' 	: folder_relative_path
            }, {
                templateUrl : __globals.getTemplateURL('file-manager.edit-folder-dialog')
            },
            function(promiseObj) {

            	scope.retrieveFiles();

            });

        };

        /**
          * Show rename file dialog
          *
          * @return void 
          *---------------------------------------------------------------- */

        scope.showRenameFileDialog  =  function(fileData) {

            FileManagerService.showDialog( { 
            	'currentDir' 			: scope.currentDir,
            	'fileData' 				: fileData
            }, {
                templateUrl : __globals.getTemplateURL('file-manager.rename-file-dialog')
            },
            function(promiseObj) {

            	scope.retrieveFiles();

            });

        };

        /**
	 	  * Retrieve files required
	 	  *
	 	  * @return void
	 	  *---------------------------------------------------------------- */
	 	
	 	scope.retrieveFiles  =  function() {

	 		scope.showLoader = false;

	 		__DataStore.fetch(
	 			__Utils.apiURL('file_manager.files')+'?currentDir='+scope.currentDir+'&type='+configData.type,
	 			{ 'fresh' : true } )
	 			.success(function(responseData) {
                
                var requestData = responseData.data;

                appServices.processResponse(responseData, null, function() {

                   	scope.files 				= requestData.files;
                   	scope.currentDir		 	= requestData.currentDir;
        			scope.currentFolderData  	= requestData.currentFolderData;
                   	scope.showLoader 			= false;

                }); 

            });

	 	};

	 	scope.showFolderFiles = function(relativePath) {

	 		if (relativePath) {

	 			scope.currentDir 	= relativePath;

	 			scope.retrieveFiles();

	 		}

	 	};

	 	scope.backToParent = function(parentDirectoryPath) {

	 		scope.currentDir 	= parentDirectoryPath;

	 		scope.retrieveFiles();

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
        
        scope.isAdmin = function() {
            return scope.isLoggedIn() && scope.auth_info.designation === 1;   //check if is admin
        };

        /**
          * Get user full name
          *
          * @return boolean 
          *---------------------------------------------------------------- */
        
        scope.getFullName = function() {
            if (scope.isLoggedIn()) {
                return scope.auth_info.profile.full_name;
            }
        };

        /**
          * Delete file
          *
          * @param string filename
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.deleteFile = function(filename, relativePath) {

        	appServices.confirmDialog({
                templateUrl : 'lw-file-delete-dialog.ngtemplate',
            }).then(function(promiseObj) {

                __DataStore.post({
                    'apiURL'		: 'file_manager.file.delete',
                    'filename'   	:  relativePath
                }, {'filename' : relativePath}).success(function(responseData) {
                    
                    appServices.processResponse(responseData, null, function() {
                        scope.retrieveFiles();
                    }); 

                });

            });
            
        };

        /**
          * Delete folder
          *
          * @param string filename
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.deleteFolder = function(folderName, relativePath) {

        	appServices.confirmDialog({
                templateUrl : 'lw-folder-delete-dialog.ngtemplate',
            }).then(function(promiseObj) {

                __DataStore.post({
                    'apiURL'		: 'file_manager.folder.delete',
                    'folder'   		:  relativePath
                }, {'folderName' : relativePath}).success(function(responseData) {
                    
                    appServices.processResponse(responseData, null, function() {
                        scope.retrieveFiles();
                    }); 

                });

            });
            
        };

        var downloadFileUrl = __Utils.apiURL('file_manager.file.download');

        /**
          * Download file
          *
          * @param string filename
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.downloadFileUrl = function(relativePath) {

        	return downloadFileUrl+'?filename='+relativePath;
            
        };

        scope.retrieveFiles();

	};

})();;
/*!
*  Component  : FileManager
*  File       : FileManagerEngine.js  
*  Engine     : FileManagerEngine 
----------------------------------------------------------------------------- */

(function(window, angular, undefined) {

    'use strict';

    angular
        .module('app.FileManagerEngine', [])

        /**
          * File Manager Controller 
          *
          * @inject object $scope
          * @inject object __DataStore
          * @inject object __Form
          * @inject object $stateParams
          *
          * @return void
          *---------------------------------------------------------------- */

        /*.controller('FileManagerController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])*/
  

        /**
          * File Manager Master Controller 
          *
          * @inject object $scope
          * @inject object __DataStore
          * @inject object __Form
          * @inject object $stateParams
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('FileManagerMasterController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])  

        /**
          * Add Folder Dialog Controller 
          *
          * @inject object $scope
          * @inject object __Form
          * @inject object appServices
          * @inject object appNotify
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('AddFolderDialogController', [
            '$scope',
            '__Form',
            'appServices', 
            'appNotify',
            function ($scope, __Form, appServices, appNotify) {

                var scope = this,
                ngDialogData 	= $scope.ngDialogData;

                scope   = __Form.setup(scope, 'add_folder_form', 'folderData');

	            /*
	              Submit form action
	              -------------------------------------------------------------------------- */
	            
	            scope.submit = function() {

	                __Form.process('file_manager.folder.add', scope).success(function(responseData) {
	                        
	                    appServices.processResponse(responseData, null, function(reactionCode) {

	                        $scope.closeThisDialog( {'folder_added' : true} );

	                    }); 

	                });

	            };

	            scope.folderData.currentDir = ngDialogData.currentDir; 

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
          * Upload File Dialog Controller 
          *
          * @inject object $scope
          * @inject object __DataStore
          * @inject object $stateParams
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('UploadFileDialogController', [
           '$scope',
            '__DataStore',
            'appServices', 
            'appNotify',
            '__Utils',
            'FileUploader', 
            function ($scope, __DataStore, appServices, appNotify, __Utils, FileUploader) {

                var scope = this,
                	uploader,
                	ngDialogData 	= $scope.ngDialogData,
                	currentDir 		= ngDialogData.currentDir;

                uploader    = scope.uploader = new FileUploader(
                        {
                            url         : __Utils.apiURL('file_manager.upload')+'?currentDir='+currentDir,
                            autoUpload  : true,
                            headers     : {
                                'X-XSRF-TOKEN': __Utils.getXSRFToken()
                            }
                        }
                    );

                uploader.filters.push({
	                name: 'uploaderFilter',
	                fn: function() {
	                    return this.queue.length < 1000;
	                }
            	}); 

                scope.currentUploadedFileCount = 0;

                uploader.onAfterAddingAll = function() {
                    scope.showLoader = true;
                };

                uploader.onSuccessItem = function(fileItem, responseData) {
                	
                    appServices.processResponse( 
                        responseData,
                        null,
                        function() {

                            var requestData = responseData.data;

                            scope.currentUploadedFileCount++

                        }

                    );

                };

                uploader.onCompleteAll  = function() {

                	scope.showLoader = false;
                	
                    if (scope.currentUploadedFileCount > 0) {
                        appNotify.success(scope.currentUploadedFileCount+' file uploaded');
                    }

                    $scope.closeThisDialog();

                    scope.currentUploadedFileCount = 0;

                };

                /**
	              * Close dialog
	              *
	              *---------------------------------------------------------------- */

	            scope.closeDialog = function() {
	                $scope.closeThisDialog();
	            };

            }
        ])

        /**
          * Image File Dialog Controller 
          *
          * @inject object $scope
          * @inject object $stateParams
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('ImageFileDialogController', [
           '$scope',
            '__DataStore',
            'appServices', 
            function ($scope, appServices) {

                var scope = this,
                	ngDialogData 	= $scope.ngDialogData;

            	scope.fileData = ngDialogData.fileData;

               /**
	              * Close dialog
	              *
	              *---------------------------------------------------------------- */

	            scope.closeDialog = function() {
	                $scope.closeThisDialog();
	            };

            }
        ])

        /**
          * Rename Folder Dialog Controller 
          *
          * @inject object $scope
          * @inject object __Form
          * @inject object appServices
          * @inject object appNotify
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('RenameFolderDialogController', [
            '$scope',
            '__Form',
            'appServices', 
            'appNotify',
            function ($scope, __Form, appServices, appNotify) {

                var scope = this,
                ngDialogData 	= $scope.ngDialogData;

                scope   = __Form.setup(scope, 'rename_folder_form', 'folderData');

	            /*
	              Submit form action
	              -------------------------------------------------------------------------- */
	            
	            scope.submit = function() {

	                __Form.process('file_manager.folder.rename', scope).success(function(responseData) {
	                        
	                    appServices.processResponse(responseData, null, function(reactionCode) {

	                        $scope.closeThisDialog( {'folder_updated' : true} );

	                    }); 

	                });

	            };

	            scope   = __Form.updateModel(scope, {
	            		'name' 					: 	ngDialogData.name,
	            		'existing_name'			: 	ngDialogData.name,
	            		'currentDir'  			:  	ngDialogData.currentDir,
	            		'folder_relative_path'	:   ngDialogData.folder_relative_path
	            	});

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
          * Rename File Dialog Controller 
          *
          * @inject object $scope
          * @inject object __Form
          * @inject object appServices
          * @inject object appNotify
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('RenameFileDialogController', [
            '$scope',
            '__Form',
            'appServices', 
            'appNotify',
            function ($scope, __Form, appServices, appNotify) {

                var scope 			= this,
	                ngDialogData 	= $scope.ngDialogData,
	                fileData 		= ngDialogData.fileData;

                scope   = __Form.setup(scope, 'rename_file_form', 'fileData');

	            /*
	              Submit form action
	              -------------------------------------------------------------------------- */
	            
	            scope.submit = function() {

	                __Form.process('file_manager.file.rename', scope).success(function(responseData) {
	                        
	                    appServices.processResponse(responseData, null, function(reactionCode) {

	                        $scope.closeThisDialog( {'file_updated' : true} );

	                    }); 

	                });

	            };

	            scope.fileExtension = fileData.extension;

	            scope   = __Form.updateModel(scope, {
	            		'name' 					: 	fileData.baseName,
	            		'existing_name'			: 	fileData.name,
	            		'currentDir'  			:  	ngDialogData.currentDir,
	            		'file_relative_path'	:   fileData.relativePath
	            	});

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

})(window, window.angular);
//# sourceMappingURL=../source-maps/file-manager-app.src.js.map
