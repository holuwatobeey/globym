<?php
/*
*  Component  : FileManager
*  View       : Add Folder Dialog  
*  Engine     : FileManagerEngine  
*  File       : add-folder-dialog.blade.php  
*  Controller : AddFolderDialogController 
----------------------------------------------------------------------------- */ 
?>

<div class="lw-dialog" ng-controller='AddFolderDialogController as AddFolderDialogCtrl'>
	
	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Add New Folder' ) ?>
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>
	
	<!-- modal content -->
	<div class="content">

		<!-- error notification -->
		<div class="alert alert-danger" role="alert" ng-show="AddFolderDialogCtrl.errorMessage" ng-bind="AddFolderDialogCtrl.errorMessage">
		</div>
		<!-- /error notification -->

		<!-- add folder form -->
		<form class="lw-form lw-ng-form" name="AddFolderDialogCtrl.[[AddFolderDialogCtrl.ngFormName]]" ng-submit="AddFolderDialogCtrl.submit()" novalidate >

			<!-- Name -->
	        <lw-form-field field-for="name" label="<?= __tr('Name') ?>"> 
	            <input type="text" class="lw-form-field form-control" name="name" ng-required="true" ng-model="AddFolderDialogCtrl.folderData.name" />
	        </lw-form-field>
	        <!-- /Name -->
            <br>
			<!-- actions -->
			<div class="modal-footer">

				<button type="submit" title="<?= __tr( 'Add new folder' ) ?>" class="lw-btn btn btn-primary"><?= __tr( 'Add' ) ?></button>

				<button type="button" title="<?= __tr('Cancel')?>" class="lw-btn btn btn-light" ng-click="AddFolderDialogCtrl.closeDialog()"><?= __tr('Cancel') ?></button>

			</div>
			<!-- /actions -->

		</form>
		<!-- /add folder form -->

	</div>
	<!-- /modal content -->

</div>