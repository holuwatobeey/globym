<?php
/*
*  Component  : FileManager
*  View       : Upload File Dialog  
*  Engine     : FileManagerEngine  
*  File       : upload-file-dialog.blade.php  
*  Controller : UploadFileDialogController 
----------------------------------------------------------------------------- */ 
?>

<div class="lw-dialog" ng-controller='UploadFileDialogController as UploadFileDialogCtrl'>
	
	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<?= __tr( 'Upload New File' ) ?>
	    </h3>
	    <!-- /main heading -->
	</div>

	<!-- loader -->
    <div class="lw-main-loader lw-show-till-loading" ng-if="UploadFileDialogCtrl.showLoader == true">
    	<div class="loader"><?=  __tr('Uploading...')  ?></div>
    </div>
    <!-- / loader -->
	
	<!-- modal content -->
	<div class="content">

		<!-- error notification -->
		<div class="alert alert-danger" role="alert" ng-show="UploadFileDialogCtrl.errorMessage" ng-bind="UploadFileDialogCtrl.errorMessage">
		</div>
		<!-- /error notification -->

		<!-- success notification -->
	    <div class="alert alert-success" role="alert" ng-show="UploadFileDialogCtrl.successMessage" ng-bind="UploadFileDialogCtrl.successMessage">
	    </div>
	    <!-- /success notification -->

		<!-- Upload File -->
		<div class="lw-form-append-btns">
			<span class="btn btn-primary btn-sm lw-btn-file">
				<i class="fa fa-upload"></i> 
				<?=   __tr('Upload New File')   ?>
				<input id="file-input" type="file" nv-file-select="" uploader="UploadFileDialogCtrl.uploader" multiple />
			</span> 
		</div>
		<!-- / Upload File -->
        <br>
        <br>
		<!-- actions -->
		<div class="modal-footer">

			<button type="button" title="<?= __tr('Cancel')?>" class="lw-btn btn btn-light" ng-click="UploadFileDialogCtrl.closeDialog()"><?= __tr('Cancel') ?></button>

		</div>
		<!-- /actions -->

	</div>
	<!-- /modal content -->
</div>