<div ng-controller="BrandEditController as brandEditCtrl">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Edit Brand' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="brandEditCtrl.[[ brandEditCtrl.ngFormName ]]" 
			ng-submit="brandEditCtrl.submit()" 
			novalidate>

			<!-- Name -->
			<lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
                <div class="input-group">
    				<input type="name" 
    					class="lw-form-field form-control"
    					name="name"
    					ng-required="true" 
    					autofocus
    					ng-model="brandEditCtrl.brandData.name" 
    				/>
                    <div class="input-group-append">
                        <a href class="lw-btn btn btn-secondary" lw-transliterate entity-type="brand" entity-id="[[ brandEditCtrl.brandId ]]" entity-key="name" entity-string="[[ brandEditCtrl.brandData.name ]]" input-type="1">
                            <i class="fa fa-globe"></i>
                        </a>
                    </div>
                </div>
			</lw-form-field>
			<!-- /Name -->
			
			<!-- thumbnail -->
	        <div class="row mb-3">
                <div class="col-lg-3" ng-if="brandEditCtrl.brandData.logo">
                    <img ng-src="[[brandEditCtrl.brandData.logo_url]]" class="img-thumbnail" alt="Cinque Terre">
                </div>
            </div>
			<!-- /thumbnail -->
	        
			<!-- Select Logo -->
			<div class="form-group">
		        <lw-form-selectize-field field-for="logo" label="<?= __tr( 'Logo' ) ?>" class="lw-selectize"><span class="badge badge-dark">[[brandEditCtrl.images_count]]</span>
		            <selectize config='brandEditCtrl.imagesSelectConfig' class="lw-form-field" name="logo" ng-model="brandEditCtrl.brandData.logo" options='brandEditCtrl.image_files' placeholder="<?= __tr( 'Select Logo' ) ?>" ng-required="true"></selectize>
		        </lw-form-selectize-field>
                <div class="lw-form-append-btns">
                    <span class="btn btn-primary btn-sm lw-btn-file">
                        <i class="fa fa-upload"></i> 
                                <?=   __tr('Browse')   ?>
                        <input type="file" nv-file-select="" uploader="brandEditCtrl.uploader" multiple/>
                    </span>
                    <button class="btn btn-light lw-btn btn-sm" title="<?= __tr('Uploaded files')  ?>" 
                        ng-click="brandEditCtrl.showUploadedMediaDialog()"  type="button"><?=  __tr("Uploaded files")  ?>
                    </button> 
                </div>
	        </div>
	        <!-- /Select Logo -->

			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="brandEditCtrl.brandData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Status' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="brandEditCtrl.brandData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
			<!-- /Status -->
			
			<div class="lw-dotted-line"></div>

			<!-- action button -->
			<div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="brandEditCtrl.close()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action button -->

		</form>
		<!-- form section -->

</div>

<!-- image path -->
<script type="text/_template" id="logoListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path -->

<!-- image name -->
<script type="text/_template" id="logoListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image name -->