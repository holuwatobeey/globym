<?php
/*
*  Component  : FileManager
*  View       : File Manager Master  
*  Engine     : FileManagerEngine  
*  File       : file-manager-master.blade.php  
*  Controller : FileManagerController 
----------------------------------------------------------------------------- */ 
?>
<!DOCTYPE html>
<html lang="en">
    
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <?= __yesset([
            'dist/css/vendorlibs-first.css',
            'dist/css/vendor-second.css',
            'dist/css/vendorlibs-manage.css',
            'dist/css/application*.css'
        ], true) ?>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <title><?= e( __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ) ) ?> : <?= __tr('File Manager') ?></title>
        <style>

            .lw-page-content, .hide-till-load, .lw-main-loader{
                display: none;
            }

            .lw-zero-opacity {
                -webkit-opacity:0;
                -moz-opacity:0;
                -o-opacity:0;
                opacity:0;
            }

            .lw-hidden {
                display: none;
            }

            .lw-show-till-loading {
                display: block;
            }
            .loader:before,
            .loader:after,
            .loader {
                border-radius: 50%;
                width: 2.5em;
                height: 2.5em;
                -webkit-animation-fill-mode: both;
                animation-fill-mode: both;
                -webkit-animation: load7 1.8s infinite ease-in-out;
                animation: load7 1.8s infinite ease-in-out;
            }
            .loader {
                color: #f6827f;
                font-size: 10px;
                margin: 80px auto;
                position: relative;
                text-indent: -9999em;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation-delay: -0.16s;
                animation-delay: -0.16s;
            }
            .loader:before {
                left: -3.5em;
                -webkit-animation-delay: -0.32s;
                animation-delay: -0.32s;
            }
            .loader:after {
                left: 3.5em;
            }
            .loader:before,
            .loader:after {
                content: '';
                position: absolute;
                top: 0;
            }
            @-webkit-keyframes load7 {
                0%,
                80%,
                100% {
                box-shadow: 0 2.5em 0 -1.3em;
                }
                40% {
                box-shadow: 0 2.5em 0 0;
                }
            }
            @keyframes load7 {
                0%,
                80%,
                100% {
                    box-shadow: 0 2.5em 0 -1.3em;
                }
                40% {
                    box-shadow: 0 2.5em 0 0;
                }
            }

            .thumbnail a>img, .thumbnail>img {
                height: 150px;
                max-width: 100%;
            }
        
        </style>

    </head>

    <body  ng-app="FileManagerApp" ng-controller='FileManagerController as FileManagerCtrl' class="lw-hide-till-load ng-cloak"  ng-class="{ 'lw-loading' : FileManagerCtrl.showLoader }">

        <!-- <nav class="navbar navbar-default">
            
            <div class="container-fluid">
                
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">: @yield('page-title')File Manager</a>
                </div>
        
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav pull-right">
                        <li>
                            <a href="" title="<?= __tr( 'Add New Folder' ) ?>" class="lw-link" ng-click="FileManagerCtrl.showAddFolderDialog()"><i class="fa fa-plus" aria-hidden="true"></i> <?= __tr( 'Add New Folder' ) ?></a>
                        </li>
                        <li>
                            <a  href="" title="<?= __tr( 'Upload New File' ) ?>" class="lw-link" ng-click="FileManagerCtrl.showUploadNewFileDialog()"><i class="fa fa-upload" aria-hidden="true"></i> <?= __tr( 'Upload New File' ) ?></a>
                        </li>
                    </ul>
        
                </div>
        
            </div>
        
        </nav> -->

        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <a class="navbar-brand" href="#"><?= e( __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') ) ) ?> : @yield('page-title')File Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <div class="ml-auto">
                    <a class="btn btn-sm btn-secondary" href="" title="<?= __tr( 'Add New Folder' ) ?>" class="lw-link" ng-click="FileManagerCtrl.showAddFolderDialog()"><i class="fa fa-plus" aria-hidden="true"></i> <?= __tr( 'Add New Folder' ) ?></a>
                    
                    <a class="btn btn-sm btn-secondary"  href="" title="<?= __tr( 'Upload New File' ) ?>" class="lw-link" ng-click="FileManagerCtrl.showUploadNewFileDialog()"><i class="fa fa-upload" aria-hidden="true"></i> <?= __tr( 'Upload New File' ) ?></a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid pt-4 pb-4">

            <div class="row">

                <div class="col-md-12">

                    <nav aria-label="breadcrumb" ng-if="FileManagerCtrl.currentFolderData.currentDirTree">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" ng-repeat="dirTree in  FileManagerCtrl.currentFolderData.currentDirTree" ng-class="{ 'active' : $last }">
                                <a ng-if="!$last" href="" ng-click="FileManagerCtrl.backToParent(dirTree.path)" ng-bind="dirTree.name"></a>
                                <span ng-if="$last" ng-bind="dirTree.name"></span>
                            </li>
                        </ol>
                    </nav>

                    <div class="row" ng-if="FileManagerCtrl.currentFolderData.name">
                        
                        <div class="col-md-11">
                            <h4>
                                <?= __tr( '__folderName__ Folder Files', [
                                    '__folderName__' => '[[ FileManagerCtrl.currentFolderData.name ]]'
                                ] ) ?>      
                            </h4>
                        </div>
                        <div class=" col-md-1">
                            <button type="button" ng-click="FileManagerCtrl.backToParent(FileManagerCtrl.currentFolderData.parentDirectoryPath)" class="lw-btn btn btn-light pull-right" title="<?= __tr('Back to Parent Folder') ?>"><?= __tr('Back') ?></button>
                        </div>


                    </div>
 

                    <div class="row">

                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" ng-if="FileManagerCtrl.files.length > 0" 
                        ng-repeat="file in FileManagerCtrl.files">
                            <div class="thumbnail">

                                <span ng-if="file.isDirectory">

                                    <a href="" ng-click="FileManagerCtrl.showFolderFiles( file.relativePath)" title="<?= __tr( 'View Folder Files' ) ?>"><img ng-src="[[ file.previewUrl ]]"></a>

                                </span>

                                <span ng-if="!file.isDirectory && file.isImageFile">
                                    <a href="" ng-click="FileManagerCtrl.useFile(file)" title="<?= __tr( 'View Image File' ) ?>"><img ng-src="[[ file.previewUrl ]]"></a>
                                </span>

                                <span ng-if="!file.isDirectory && !file.isImageFile">
                                    <img class="ui avatar image" ng-src="[[ file.previewUrl ]]">
                                </span>

                                <div class="caption">
                                    <div class="btn-group btn-block dropup">

                                        <button title="[[ file.name ]]" class="lw-btn btn btn-light btn-xs btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span ng-bind="file.shortName"></span> <span class="caret"></span>
                                        </button>

                                        <div class="dropdown-menu">

                                            <a class="disabled btn btn-link dropdown-item" ng-bind="file.name | limitTo:30"></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" ng-if="file.isDirectory" href ng-click="FileManagerCtrl.showRenameFolderDialog(file.name,  file.relativePath)" title="<?= __tr( 'Rename Folder' ) ?>"><?= __tr('Rename') ?></a>
                                            <a ng-if="!file.isDirectory" class="dropdown-item" href ng-click="FileManagerCtrl.showRenameFileDialog(file)" title="<?= __tr( 'Rename File' ) ?>"><?= __tr('Rename') ?></a>
                                            <a ng-if="!file.isDirectory" class="dropdown-item" ng-href="[[ FileManagerCtrl.downloadFileUrl( file.relativePath) ]]" title="<?= __tr( 'Download File' ) ?>"><?= __tr('Download') ?></a>
                                            <a ng-if="file.isDirectory" class="dropdown-item" href ng-click="FileManagerCtrl.deleteFolder(file.name, file.relativePath)" title="<?= __tr( 'Delete Folder' ) ?>"><?= __tr('Delete') ?></a>
                                            <a ng-if="!file.isDirectory" class="dropdown-item" href="#" ng-click="FileManagerCtrl.deleteFile(file.name, file.relativePath)"  class="lw-btn" title="<?= __tr( 'Delete File' ) ?>"><?= __tr('Delete') ?></a>
                                          

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-12 mt-4" ng-if="FileManagerCtrl.files.length == 0">
                            <div class="alert alert-info">There are no uploaded files.</div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- /.container -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        @include('includes.javascript-content')
        @include('includes.form-template')
        <!--[if lte IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/Base64/1.0.0/base64.min.js"></script>
        <![endif]-->

        <?= __yesset([
            'dist/js/vendorlibs-first.js',
            'dist/js/vendor-second.js',
            'dist/js/vendorlibs-jquery-ui.js',
            'dist/js/vendorlibs-manage.js',
            'dist/js/application.*.js',
            'dist/js/file-manager-app.*.js'
        ], true) ?>

    </body>

    <!-- file delete dialog template -->
    <script type="text/ng-template" id="lw-file-delete-dialog.ngtemplate">
        <p><?= __tr( 'Are you sure you want to delete this file ?' ) ?></p>
        <div class="ngdialog-buttons">
            <button type="button" title="<?= __tr( 'Yes' ) ?>" class="lw-btn btn btn-primary" ng-click="confirm()"><i class="trash icon"></i> <?= __tr( 'Yes' ) ?>
            </button>
            <button type="button" title="<?= __tr( 'No' ) ?>" class="lw-btn btn btn-light" ng-click="closeThisDialog()"><?= __tr( 'No' ) ?></button>
        </div>
    </script>
    <!-- /file delete dialog template -->

    <!-- folder delete dialog template -->
    <script type="text/ng-template" id="lw-folder-delete-dialog.ngtemplate">
        <p><?= __tr( 'Are you sure you want to delete this folder ?. If you are deleting folder, its containing all the files and nested folders will be deleted.' ) ?></p>
        <div class="ngdialog-buttons">
            <button type="button" title="<?= __tr( 'Yes' ) ?>" class="lw-btn btn btn-primary" ng-click="confirm()"><i class="trash icon"></i> <?= __tr( 'Yes' ) ?>
            </button>
            <button class="lw-btn btn btn-light" type="button" title="<?= __tr( 'No' ) ?>" class="ui tiny button" ng-click="closeThisDialog()"><?= __tr( 'No' ) ?></button>
        </div>
    </script>
    <!-- /folder delete dialog template -->

</html>