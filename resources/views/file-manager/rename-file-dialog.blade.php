<?php
/*
*  Component  : FileManager
*  View       : Rename Folder Dialog  
*  Engine     : FileManagerEngine  
*  File       : rename-file-dialog.blade.php  
*  Controller : RenameFileDialogController 
----------------------------------------------------------------------------- */ 
?>

<div class="lw-dialog" ng-controller='RenameFileDialogController as RenameFileDialogCtrl'>
	
	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Rename  __name__ File', ['__name__' => '[[ RenameFileDialogCtrl.fileData.existing_name ]]' ] ) ?>
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>
	
	<!-- modal content -->
	<div class="content">

		<!-- error notification -->
		<div class="alert alert-danger" role="alert" ng-show="RenameFileDialogCtrl.errorMessage" ng-bind="RenameFileDialogCtrl.errorMessage">
		</div>
		<!-- /error notification -->

		<!-- success notification -->
	    <div class="alert alert-success" role="alert" ng-show="RenameFileDialogCtrl.successMessage" ng-bind="RenameFileDialogCtrl.successMessage">
	    </div>
	    <!-- /success notification -->

		<!-- rename file form -->
		<form class="lw-form lw-ng-form" name="RenameFileDialogCtrl.[[RenameFileDialogCtrl.ngFormName]]" ng-submit="RenameFileDialogCtrl.submit()" novalidate >

			<!-- Name -->
	        <lw-form-field field-for="name" label="<?= __tr('Name') ?>"> 
	        	<div class="input-group">
					<input type="text" class="lw-form-field form-control" name="name" ng-required="true" ng-model="RenameFileDialogCtrl.fileData.name" />
					<span class="input-group-addon" id="basic-addon1" ng-bind="RenameFileDialogCtrl.fileExtension"></span>
				</div>
	        </lw-form-field>
	        <!-- /Name -->
            <br>
			<!-- actions -->
			<div class="modal-footer">

				<button type="submit" title="<?= __tr( 'Rename file name' ) ?>" class="lw-btn btn btn-primary"><?= __tr( 'Rename' ) ?></button>

				<button type="button" title="<?= __tr('Cancel')?>" class="lw-btn btn btn-light" ng-click="RenameFileDialogCtrl.closeDialog()"><?= __tr('Cancel') ?></button>

			</div>
			<!-- /actions -->

		</form>
		<!-- /rename file form -->

	</div>
	<!-- /modal content -->
</div>