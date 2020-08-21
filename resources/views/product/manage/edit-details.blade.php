<div ng-controller="ManageProductEditDetailsController as editDetailsCtrl">
    <div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Details' ) ?></h3>
        <!-- /main heading -->
    </div>

    <div ng-hide="editDetailsCtrl.pageStatus" class="text-center">
		<div class="loader"><?=  __tr('Loading...')  ?></div>
	</div>
	
    <div ng-show="editDetailsCtrl.pageStatus" ng-if="editDetailsCtrl.fancytree_categories">
        <div class="row">   
    	<!-- form action -->
    	<form class="lw-form lw-ng-form col-lg-12 col-md-12 col-sm-12 col-sm-12" 
        name="editDetailsCtrl.[[ editDetailsCtrl.ngFormName ]]" 
        novalidate>
        
	        <!-- Name -->
	        <lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>">
                <div class="input-group">
    	            <input type="text" 
    	              class="lw-form-field form-control"
    	              name="name"
    	              ng-required="true" 
    	              autofocus
    	              ng-model="editDetailsCtrl.productData.name"
                    />
                    <div class="input-group-append">
                        <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="product" entity-id="[[ editDetailsCtrl.productId ]]" entity-key="name" entity-string="[[ editDetailsCtrl.productData.name ]]" input-type="1">
                            <i class="fa fa-globe"></i>
                        </button>
                    </div>
                </div>
	        </lw-form-field>
	        <!-- /Name -->

	        <!-- Product ID -->
	        <lw-form-field field-for="product_id" label="<?= __tr( 'Product ID' ) ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="product_id"
	              ng-required="true" 
	              ng-model="editDetailsCtrl.productData.product_id" />
	        </lw-form-field>
	        <!-- /Product ID -->

	        <div class="lw-form-inline-elements">

	            <!-- Featured -->
	            <lw-form-checkbox-field field-for="featured" label="<?= __tr( 'Mark as Featured' ) ?>" class="lw-form-item-box">
	                <input type="checkbox" 
		                class="lw-form-field js-switch"
		                name="featured"
		                ng-model="editDetailsCtrl.productData.featured" 
		                ui-switch=""/>
	            </lw-form-checkbox-field>
	            <!-- /Featured -->

	            <!-- Out of Stock -->
	            <!-- <lw-form-checkbox-field field-for="outOfStock" label="<?= __tr( 'Mark Out of Stock' ) ?>" class="lw-form-item-box">
	                <input type="checkbox" 
		                class="lw-form-field js-switch"
		                name="outOfStock"
		                ng-model="editDetailsCtrl.productData.outOfStock" 
		                ui-switch="{color: '#E43B11'}"/>
	            </lw-form-checkbox-field> -->
	            <!-- /Out of Stock -->
	        </div>

            <!-- Out Of Stock Status -->
            <lw-form-radio-field field-for="outOfStock" label="<?= __tr( 'Out of Stock' ) ?>"> 
                <label class="radio-inline">
                    <input ng-model="editDetailsCtrl.productData.outOfStock" ng-click="editDetailsCtrl.sendNotifyMailDialog(0)" class="lw-tax-setting-radio-btn" type="radio" name="available" ng-value="0"><?= __tr('Available') ?>
                </label>
                
                <label class="radio-inline">
                    <input ng-model="editDetailsCtrl.productData.outOfStock" class="lw-tax-setting-radio-btn" type="radio" name="outOfStock" ng-value="1"><?= __tr('Mark Out of Stock') ?>
                </label>

                <label class="radio-inline">
                    <input ng-model="editDetailsCtrl.productData.outOfStock" class="lw-tax-setting-radio-btn" type="radio" name="commingSoon" ng-value="2"><?= __tr('Coming Soon') ?>
                </label>

                <label class="radio-inline">
                    <input ng-model="editDetailsCtrl.productData.outOfStock" class="lw-tax-setting-radio-btn" type="radio" name="launchingOn" ng-value="3"><?= __tr('Launching On') ?>
                </label>

            </lw-form-radio-field>
            <!-- /Out Of Stock Status -->

            <!-- Launching Date -->
            <div ng-if="editDetailsCtrl.productData.outOfStock == 3">
                <!-- Relase Date -->
                <lw-form-field field-for="launching_date" label="<?= __tr( 'Launching Date' ) ?>" ng-if="editDetailsCtrl.productData.outOfStock == 3"> 
                    <input type="text" 
                            class="lw-form-field form-control lw-readonly-control"
                            name="launching_date"
                            id="launching_date"
                            lw-bootstrap-md-datetimepicker
                            ng-required="editDetailsCtrl.productData.outOfStock == 3"
                            options="[[ editDetailsCtrl.releaseDateConfig ]]"
                            readonly
                            ng-model="editDetailsCtrl.productData.launching_date" 
                        />
                </lw-form-field>
                <!-- /Relase Date -->
                <br>
            </div>
        <!-- /Launching Date -->

	        <!-- Categories -->
	        <lw-form-field field-for="categories" label="<?= __tr( 'Categories' ) ?>">

				<button ng-if="canAccess('manage.category.add')" ng-click="editDetailsCtrl.addCategory()" title="<?=  __tr('Add New Category')  ?>" class="btn btn-sm btn-light float-right lw-inline-input-button"> <i class="fa fa-plus"></i> <?=  __tr('Add New Category')  ?></button>

	            <div ng-if="editDetailsCtrl.categories"
	                ng-model="editDetailsCtrl.productData.categories"
		        	class="select fancytree-list lw-fancytree-inline-btn" 
		        	name="temp_row_id"
		         	lw-fancytree
		         	source='[[ editDetailsCtrl.fancytree_categories ]]' 
		         	listing-for="products"
		         	form-type='productEdit'
					form-id='[[ editDetailsCtrl.categories ]]'
	            >
	            </div>

	            <input type="text" 
	              class="lw-form-field form-control"
	              name="categories"
	              ng-required="true"
	              readonly="readonly"
	              style="display:none;" 
	              ng-model="editDetailsCtrl.productData.categories" />

	        </lw-form-field>
	        <!-- /Categories -->

	        <!-- Brand ID -->
	        <div class="form-group">
		        <lw-form-selectize-field field-for="brands__id" label="<?= __tr( 'Brand' ) ?>" class="lw-selectize">
		        	<div class="input-group lw-brand-selectize ml-0">
		            	<selectize config='editDetailsCtrl.brandsSelectConfig' class="lw-form-field form-control lw-form-field-brand ml-0" name="brands__id" ng-model="editDetailsCtrl.productData.brands__id" options='editDetailsCtrl.activeBrands' placeholder="<?= __tr( 'Select Brand' ) ?>"></selectize>
		            	<div class="input-group-append" ng-if="canAccess('manage.brand.add')">
                           <button href  ng-click="editDetailsCtrl.addBrand()" title="<?=  __tr('Add New brand')  ?>" class="lw-btn btn-sm btn btn-light"><?=  __tr('Add New brand')  ?></button>
						</div>
		            </div>
		        </lw-form-selectize-field>	
			</div>
			<!-- /Brand ID -->
			
			<!-- image thumbnil -->
	        <div class="form-group">
				<div class="lw-thumb-logo" ng-if="editDetailsCtrl.productData.thumbnail">
		        	<a href="[[editDetailsCtrl.productData.thumbnailURL]]/[[editDetailsCtrl.productData.thumbnail]]" lw-ng-colorbox class="lw-image-thumbnail"><img  ng-src="[[editDetailsCtrl.productData.thumbnailURL]]/[[editDetailsCtrl.productData.thumbnail]]" alt=""></a>
		        </div>
	        </div>
			<!-- /image thumbnil -->

	       	<!-- Select Image -->
	       	<div class="form-group">
		        <lw-form-selectize-field field-for="image" label="<?= __tr( 'Image' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge">[[editDetailsCtrl.images_count]]</span>
		            <selectize config='editDetailsCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="editDetailsCtrl.productData.image" options='editDetailsCtrl.image_files' placeholder="<?= __tr( 'Select Image' ) ?>"></selectize>
		        </lw-form-selectize-field> 
                <div class="lw-form-append-btns">
                    <span class="btn btn-primary btn-sm lw-btn-file">
                        <i class="fa fa-upload"></i> 
                                <?=   __tr('Upload New Images')   ?>
                        <input type="file" nv-file-select="" uploader="editDetailsCtrl.uploader" multiple/>
                </span>
                <button class="lw-btn btn btn-light btn-sm" title="<?= __tr('Uploaded Images')  ?>" 
                    ng-click="editDetailsCtrl.showUploadedMediaDialog()"  type="button"><?=  __tr("Uploaded Images")  ?>
                </button>
                </div>
	        </div>
	        <!-- /Select Image -->
	        
	        <!-- Youtube Video Code -->
	        <lw-form-field field-for="youtube_video" label="<?= __tr( 'Youtube Video Code' ) ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control "
	              name="youtube_video"
                  placeholder="10r9ozshGVE" 
	              autofocus
	              ng-model="editDetailsCtrl.productData.youtube_video" />
	        </lw-form-field>
	        <!-- /Youtube Video Code -->
            
            <div class="alert alert-info">
                <small ><strong><?=  __tr('Please Note: ')  ?></strong> <?=  __tr('Use only youtube video code, as in following eg. __sampleYoutubeLink__',[
                '__sampleYoutubeLink__' => 'https://www.youtube.com/watch?v=<strong>10r9ozshGVE</strong>' ])  ?></small>
            </div>

			<!-- Old Price -->
	        <lw-form-field field-for="old_price" label="<?= __tr( 'Old Price' ) ?>"> 
	            <div class="input-group">
                    <div class="input-group-prepend" id="basic-addon1">
                        <span class="input-group-text" ng-bind-html="editDetailsCtrl.store_currency_symbol"></span>
                    </div>
	                <input type="number" 
	                  class="lw-form-field form-control"
	                  name="old_price"
	                  min="0"
	                  ng-model="editDetailsCtrl.productData.old_price" />
                      <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text" ng-bind-html="editDetailsCtrl.store_currency"></span>
                    </div>
	            </div>
	        </lw-form-field>
	        <!-- /Old Price -->

	        <!-- Price -->
	        <lw-form-field field-for="price" label="<?= __tr( 'Price' ) ?>"> 
	            <div class="input-group">
                    <div class="input-group-prepend" id="basic-addon1">
                        <span class="input-group-text" ng-bind-html="editDetailsCtrl.store_currency_symbol"></span>
                    </div>
	                <input type="number" 
	                  class="lw-form-field form-control"
	                  name="price"
	                  min="0.01"
	                  ng-required="true" 
	                  ng-model="editDetailsCtrl.productData.price" />
                      <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text" ng-bind-html="editDetailsCtrl.store_currency"></span>
                    </div>
	            </div>
	        </lw-form-field>
	        <!-- /Price -->

	        <!-- Description -->
	        <lw-form-field field-for="description" label="<?= __tr( 'Description' ) ?>"> 
                <a href lw-transliterate entity-type="product" entity-id="[[ editDetailsCtrl.productId ]]" entity-key="description" entity-string="[[ editDetailsCtrl.productData.description ]]" input-type="3">
                <i class="fa fa-globe"></i></a>

	            <textarea name="description" class="lw-form-field form-control" ng-required="true"
	             cols="30" rows="10" lw-ck-editor ng-model="editDetailsCtrl.productData.description"></textarea>
	         </lw-form-field>
	        <!-- /Description -->

	        <!-- Related Products -->
	        <div class="form-group">
		        <lw-form-selectize-field field-for="related_products" label="<?= __tr( 'Related Products' ) ?>" class="lw-selectize">
		            <selectize config='editDetailsCtrl.relatedProductsSelectConfig' class="lw-form-field" name="related_products" ng-model="editDetailsCtrl.productData.related_products" options='editDetailsCtrl.related_products' placeholder="<?= __tr( 'Select Related Products' ) ?>" ></selectize>
		        </lw-form-selectize-field>
	        </div>
	        <!-- /Related Products -->

			<!-- button action -->
	        <div class="modal-footer">
	            <button type="submit" ng-click="editDetailsCtrl.submit()" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>

	            <button type="button" ui-sref="products" class="lw-btn btn btn-light btn-sm" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>

	            <a class="lw-btn btn btn-light btn-sm" href="[[editDetailsCtrl.productData.viewPage]]" 
                        target="_new" title="<?= __tr('View Page') ?>"><?= __tr('View Page') ?> <i class="fa fa-external-link"></i></a>
	        </div>
			<!-- /button action -->
	    </form>
	    <!-- /form action -->
        </div>
    </div>
</div>
</div>

<!-- imageListItemTemplate -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /imageListItemTemplate -->

<!-- imageListOptionTemplate -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /imageListOptionTemplate -->

