<?php
/*
*  Component  : FileManager
*  View       : Rename Folder Dialog  
*  Engine     : FileManagerEngine  
*  File       : add-folder-dialog.blade.php  
*  Controller : RenameFolderDialogController 
----------------------------------------------------------------------------- */ 
?>

<div class="lw-dialog" ng-controller='RenameFolderDialogController as RenameFolderDialogCtrl'>
	
	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Rename  __name__ Folder', ['__name__' => '[[ RenameFolderDialogCtrl.folderData.existing_name ]]' ] ) ?>
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>
	
	<!-- modal content -->
	<div class="content">

		<!-- error notification -->
		<div class="alert alert-danger" role="alert" ng-show="RenameFolderDialogCtrl.errorMessage" ng-bind="RenameFolderDialogCtrl.errorMessage">
		</div>
		<!-- /error notification -->

		<!-- success notification -->
	    <div class="alert alert-success" role="alert" ng-show="RenameFolderDialogCtrl.successMessage" ng-bind="RenameFolderDialogCtrl.successMessage">
	    </div>
	    <!-- /success notification -->

		<!-- rename folder form -->
		<form class="lw-form lw-ng-form" name="RenameFolderDialogCtrl.[[RenameFolderDialogCtrl.ngFormName]]" ng-submit="RenameFolderDialogCtrl.submit()" novalidate >

			<!-- Name -->
	        <lw-form-field field-for="name" label="<?= __tr('Name') ?>"> 
	            <input type="text" class="lw-form-field form-control" name="name" ng-required="true" ng-model="RenameFolderDialogCtrl.folderData.name" />
	        </lw-form-field>
	        <!-- /Name -->
            <br>
			<!-- actions -->
			<div class="modal-footer">

				<button type="submit" title="<?= __tr( 'Rename folder name' ) ?>" class="lw-btn btn btn-primary"><?= __tr( 'Rename' ) ?></button>

				<button type="button" title="<?= __tr('Cancel')?>" class="lw-btn btn btn-light" ng-click="RenameFolderDialogCtrl.closeDialog()"><?= __tr('Cancel') ?></button>

			</div>
			<!-- /actions -->

		</form>
		<!-- /rename folder form -->

	</div>
	<!-- /modal content -->
</div>