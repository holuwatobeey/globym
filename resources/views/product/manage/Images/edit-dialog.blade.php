<div ng-controller="ProductImageEditController as editImageCtrl" class="lw-dialog">
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Edit Image") ?></h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="editImageCtrl.[[ editImageCtrl.ngFormName ]]" 
        novalidate>
        
        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true" 
              ng-model="editImageCtrl.imageData.title" />
        </lw-form-field>
        <!-- /Title -->

		<!-- action button -->
        <div class="modal-footer">
            <button type="submit" ng-click="editImageCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button ng-click="editImageCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /action button -->
    </form>
	<!-- /form action -->
</div>
