<div ng-controller="ProductImageAddController as addImageCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?= __tr("Add New Image") ?></h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="addImageCtrl.[[ addImageCtrl.ngFormName ]]" 
        novalidate>
        
        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true" 
              ng-model="addImageCtrl.imageData.title" />
        </lw-form-field>
        <!-- /Title -->

        <!-- Select Image -->
        <div class="form-group">
	        <lw-form-selectize-field field-for="image" label="<?= __tr( 'Image' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge">[[addImageCtrl.images_count]]</span>
	            <selectize config='addImageCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="addImageCtrl.imageData.image" options='addImageCtrl.image_files' placeholder="<?= __tr( 'Select Image' ) ?>" ng-required="true"></selectize>
	        </lw-form-selectize-field>
	        <div class="lw-form-append-btns">
	            <span class="btn btn-primary btn-sm lw-btn-file lw-btn">
	            	<i class="fa fa-upload"></i> 
							<?=   __tr('Upload New Images')   ?>
	                <input type="file" nv-file-select="" uploader="addImageCtrl.uploader" multiple/>
	            </span>
            	<button class="btn btn-light btn-sm lw-btn" title="<?= __tr('Uploaded Images')  ?>" 
                	ng-click="addImageCtrl.showUploadedMediaDialog()"  type="button"><?=  __tr("Uploaded Images")  ?>
            	</button> 
          	</div>
        </div>
        <!-- /Select Image -->

		<!-- action button -->
        <div class="modal-footer">
            <button type="submit" ng-click="addImageCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
            <button ng-click="addImageCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /action button -->

    </form>
	<!-- /form action -->
</div>

<!-- image path and name -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->

<!-- image path and name -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->
