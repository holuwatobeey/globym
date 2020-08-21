<?php
/*
*  Component  : FileManager
*  View       : Image File Dialog  
*  Engine     : FileManagerEngine  
*  File       : image-file-dialog.blade.php  
*  Controller : ImageFileDialogController 
----------------------------------------------------------------------------- */ 
?>

<div class="lw-dialog" ng-controller='ImageFileDialogController as ImageFileDialogCtrl'>
	
	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span ng-bind="ImageFileDialogCtrl.fileData.name">
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>
	
	<!-- modal content -->
	<div class="content">

		<div>

			<div>
				<img ng-src="[[ ImageFileDialogCtrl.fileData.url ]]" alt="[[ ImageFileDialogCtrl.fileData.name ]]">
			</div>
            <br>
			<!-- actions -->
			<div class="modal-footer">

				<button type="button" title="<?= __tr('Close')?>" class="lw-btn btn btn-light"  ng-click="ImageFileDialogCtrl.closeDialog()"><?= __tr('Close') ?></button>

			</div>
			<!-- /actions -->

		</div>

	</div>
	<!-- /modal content -->
</div>