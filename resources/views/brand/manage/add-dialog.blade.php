<div ng-controller="BrandAddController as brandAddCtrl" class="lw-dialog">
	
    <!--  Main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Add New Brand' )  ?></h3>
    </div>
    <!--  /Main heading  -->

	<!--  form section  -->
	<form class="lw-form lw-ng-form" 
			name="brandAddCtrl.[[ brandAddCtrl.ngFormName ]]" 
			ng-submit="brandAddCtrl.submit()" 
			novalidate>

			<!--  Name  -->
			<lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
				<input type="name" 
					class="lw-form-field form-control"
					name="name"
					ng-required="true" 
					autofocus
					ng-model="brandAddCtrl.brandData.name" 
				/>
			</lw-form-field>
			<!--  /Name  -->

			<!--  Select Logo  -->
			<div class="form-group">
		        <lw-form-selectize-field field-for="logo" label="<?= __tr( 'Logo' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge">[[brandAddCtrl.images_count]]</span>
		            <selectize config='brandAddCtrl.imagesSelectConfig' class="lw-form-field" name="logo" ng-model="brandAddCtrl.brandData.logo" options='brandAddCtrl.image_files' placeholder="<?= __tr( 'Select Logo' ) ?>" ng-required="true"></selectize>
		        </lw-form-selectize-field>
                <div class="lw-form-append-btns">
                    <span class="btn btn-primary btn-sm lw-btn-file">
                        <i class="fa fa-upload"></i> 
                                <?=   __tr('Upload New Images')   ?>
                        <input type="file" nv-file-select="" uploader="brandAddCtrl.uploader" multiple/>
                    </span>
                    <button class="btn btn-light lw-btn btn-sm" title="<?= __tr('Uploaded Images')  ?>" 
                        ng-click="brandAddCtrl.showUploadedMediaDialog()"  type="button"><?=  __tr("Uploaded Images")  ?>
                    </button> 
                </div>
	        </div>
	        <!--  /Select Logo  -->

	        <!--  Description  -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="brandAddCtrl.brandData.description"></textarea>
            </lw-form-field>
	        <!--  /Description  -->

			<!--  Status  -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="brandAddCtrl.brandData.status"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
			<!--  /Status  -->
			
			<div class="lw-dotted-line"></div>

			<!--  Action button  -->
			<div class="modal-footer">
				<!--  add button  -->
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
				<!--  /add button  -->

				<!--  cancel button  -->
				<button type="button" class="lw-btn btn btn-light" ng-click="brandAddCtrl.close()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
				<!--  /cancel button  -->
			</div>
			<!--  /Action button  -->

		</form>
		<!--  /form section  -->

</div>

<!--  image path  -->
<script type="text/_template" id="logoListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
 <!--  /image path  -->

<!--  image name  -->
<script type="text/_template" id="logoListOptionTemplate">
	<div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
    <!--  image name  -->
</script>
<!--  image name  -->