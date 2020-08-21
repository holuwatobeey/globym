<div ng-controller="ManageProductAddController as addProductCtrl">
    
    <div ng-if="addProductCtrl.fancytree_categories">
    <div class="lw-section-heading-block">

            <!-- main heading when parent are selected -->
            <span ng-show="!addProductCtrl.parentCategoryExist">
                 <!-- first level -->
                <a ui-sref="categories({mCategoryID:''})">
                    <?= __tr( 'Manage Categories & Products' ) ?>
                </a>
                <!-- /first level -->
            </span>
            <!-- /main heading when parent are selected -->

        <div class="lw-breadcrumb">
            <span ng-show="addProductCtrl.parentCategoryExist">

                <!-- first level -->
                <a ui-sref="categories({mCategoryID:''})" ng-show="addProductCtrl.currentStateName == 'category_product_add' ">
                    <?= __tr( 'Manage Categories' ) ?> &raquo;
                </a> 
                <!-- /first level -->

                <!-- first level -->
                <a ui-sref="products" ng-show="addProductCtrl.currentStateName == 'product_add' ">
                    <?= __tr( 'Manage Products' ) ?> &raquo;
                </a> 
                <!-- /first level -->

                <!-- second level -->
                <span ng-if="addProductCtrl.parentData.parentCateId != null">
                    <a ui-sref="categories({mCategoryID:addProductCtrl.parentData.parentCateId})" href>  ...
                    </a> &raquo;
                </span>
                <!-- /second level -->

                <!-- third level -->
                <span>
                    <a ui-sref="categories({mCategoryID:addProductCtrl.parentData.id})" href>
                        <span ng-bind="addProductCtrl.parentData.title"></span>
                    </a>
                </span>
                <!-- /third level -->
                
            </span>

            <span ng-show="addProductCtrl.isParentExist">
                <h3><div><?= __tr('Add Product for __category__', [
                '__category__' => ' <a ui-sref="categories({mCategoryID:addProductCtrl.categoryDetail.catId})" href><span ng-bind="addProductCtrl.categoryDetail.name"></span>
                    </a>'
                ]) ?>
                    </div>
                </h3>
            </span>

            <span ng-show="!addProductCtrl.isParentExist">
                <h3><div><?= __tr('Add New Product') ?>
                    </div>
                </h3>
            </span>

        </div>
    </div>

    <div class="row">
	<!-- form action -->
    <form class="lw-form lw-ng-form col-lg-7" 
        name="addProductCtrl.[[ addProductCtrl.ngFormName ]]" 
        novalidate>

        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control "
              name="name"
              ng-required="true" 
              autofocus
              ng-model="addProductCtrl.productData.name" />
        </lw-form-field>
        <!-- Name -->

        <!-- Product ID -->
        <lw-form-field field-for="product_id" label="<?= __tr( 'Product ID' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="product_id"
              ng-required="true" 
              ng-model="addProductCtrl.productData.product_id" />
        </lw-form-field>
        <!-- Product ID -->

        <!-- Featured -->
        <lw-form-checkbox-field field-for="featured" label="<?= __tr( 'Mark as Featured' ) ?>" class="lw-form-item-box">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="featured"
                ng-model="addProductCtrl.productData.featured" 
                ui-switch=''/>
        </lw-form-checkbox-field>
        <!-- /Featured -->

        <!-- Out Of Stock Status -->
        <lw-form-radio-field field-for="out_of_stock" label="<?= __tr( 'Out of Stock' ) ?>"> 
            <label class="radio-inline">
                <input ng-model="addProductCtrl.productData.out_of_stock" class="lw-tax-setting-radio-btn" type="radio" name="available" ng-value="0"><?= __tr('Available') ?>
            </label>
            
            <label class="radio-inline">
                <input ng-model="addProductCtrl.productData.out_of_stock" class="lw-tax-setting-radio-btn" type="radio" name="out_of_stock" ng-value="1"><?= __tr('Mark Out of Stock') ?>
            </label>

            <label class="radio-inline">
                <input ng-model="addProductCtrl.productData.out_of_stock" class="lw-tax-setting-radio-btn" type="radio" name="comming_soon" ng-value="2"><?= __tr('Coming Soon') ?>
            </label>

            <label class="radio-inline">
                <input ng-model="addProductCtrl.productData.out_of_stock" class="lw-tax-setting-radio-btn" type="radio" name="launching_on" ng-value="3"><?= __tr('Launching On') ?>
            </label>

        </lw-form-radio-field>
        <!-- /Out Of Stock Status -->

        <!-- Launching Date -->
        <div ng-if="addProductCtrl.productData.out_of_stock == 3">
            <!-- Relase Date -->
            <lw-form-field field-for="launching_date" label="<?= __tr( 'Launching Date' ) ?>" ng-if="addProductCtrl.productData.out_of_stock == 3"> 
                <input type="text" 
                        class="lw-form-field form-control lw-readonly-control"
                        name="launching_date"
                        id="launching_date"
                        lw-bootstrap-md-datetimepicker
                        ng-required="addProductCtrl.productData.out_of_stock == 3"
                        options="[[ addProductCtrl.releaseDateConfig ]]"
                        readonly
                        ng-model="addProductCtrl.productData.launching_date" 
                    />
            </lw-form-field>
            <!-- /Relase Date -->
            <br>
        </div>
        <!-- /Launching Date -->

        <!-- Categories -->  
		<div>

	        <lw-form-field field-for="categories" label="<?= __tr( 'Categories' ) ?>">
	    		
				<button ng-if="canAccess('manage.category.add')" ng-click="addProductCtrl.addCategory()" title="<?=  __tr('Add New Category')  ?>" class="lw-btn btn btn-sm btn-light pull-right lw-inline-input-button"> <i class="fa fa-plus"></i> <?=  __tr('Add New Category')  ?></button>

	            <div 
	                ng-model="addProductCtrl.productData.categories"
	                class="select fancytree-list clearfix lw-fancytree-inline-btn" 
	                name="temp_row_id"
	                lw-fancytree
	                source='[[ addProductCtrl.fancytree_categories ]]'
	                field-for="categories" 
	                listing-for='products'
	                form-type='productAdd'
	                form-id='[[ addProductCtrl.productData.categoryID ]]'
	            >
	            </div> 

	            <input type="text" 
	              	class="lw-form-field form-control"
	              	name="categories"
	              	ng-required="true"
	              	readonly="readonly"
	              	style="display:none;" 
	              	ng-model="addProductCtrl.productData.categories" />

	        </lw-form-field>
		</div>
        <!-- Categories -->
        <!-- Brand ID -->   
        <div class="form-group">
            <lw-form-selectize-field field-for="brands__id" label="<?= __tr( 'Brand' ) ?>" class="lw-selectize">
                <div class="input-group">
                    <selectize config='addProductCtrl.brandsSelectConfig' class="lw-form-field form-control lw-form-field-brand" name="brands__id" ng-model="addProductCtrl.productData.brands__id" options='addProductCtrl.activeBrands' placeholder="<?= __tr( 'Select Brand' ) ?>"></selectize>
                    <div ng-if="canAccess('manage.brand.add')" class="input-group-append lw-selectize-custom-addon-btn">
                       <a href  ng-click="addProductCtrl.addBrand()" title="<?=  __tr('Add New brand')  ?>" class="lw-btn btn-sm btn btn-light"><?=  __tr('Add New brand')  ?></a>
                    </div>
                </div>
            </lw-form-selectize-field>  
        </div>
        <!-- Brand ID -->

       	<!-- Select Image -->
       	<div class="form-group">
       		<lw-form-selectize-field field-for="image" label="<?= __tr( 'Image' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge">[[addProductCtrl.images_count]]</span>
	            <selectize config='addProductCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="addProductCtrl.productData.image" options='addProductCtrl.image_files' placeholder="<?= __tr( 'Select Image' ) ?>" ng-required="true"></selectize> 
	        </lw-form-selectize-field>
            <div class="lw-form-append-btns">
                <span class="lw-btn btn btn-primary btn-sm lw-btn-file">
                    <i class="fa fa-upload"></i> 
                            <?=   __tr('Upload New Images')   ?>
                    <input type="file" nv-file-select="" uploader="addProductCtrl.uploader" multiple/>
                </span>
                <button class="lw-btn btn btn-light btn-sm" title="<?= __tr('Uploaded Images')  ?>" 
                ng-click="addProductCtrl.showUploadedMediaDialog()"  type="button">
                <?=  __tr("Uploaded Images")  ?></button>
            </div>
       	</div>
        <!-- Select Image -->
        
        <!-- Youtube Video Code -->
        <lw-form-field field-for="youtube_video" label="<?= __tr( 'Youtube Video' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control "
              name="youtube_video"
              autofocus
              ng-model="addProductCtrl.productData.youtube_video" />
        </lw-form-field>
        <!-- Youtube Video Code -->

        <div class="alert alert-info">
          <small ><strong><?=  __tr('Please Note: ')  ?></strong> <?=  __tr('Use only youtube video code, as in following eg. __sampleYoutubeLink__',[
            '__sampleYoutubeLink__' => 'https://www.youtube.com/watch?v=10r9ozshGVE' ])  ?></small>
        </div>

        <!-- Old Price -->
        <lw-form-field field-for="old_price" label="<?= __tr( 'Old Price' ) ?>"> 
            <div class="input-group">
                <div class="input-group-prepend" id="basic-addon1">
                    <span class="input-group-text" ng-bind-html="addProductCtrl.store_currency_symbol"></span>
                </div>
                <input type="number" 
                  class="lw-form-field form-control"
                  name="old_price"
                  min="0"
                  ng-model="addProductCtrl.productData.old_price" />
                <div class="input-group-append" id="basic-addon1">
                    <span class="input-group-text" ng-bind-html="addProductCtrl.store_currency"></span>
                </div>
            </div>
        </lw-form-field>
        <!-- Old Price -->

        <!-- Price -->
        <lw-form-field field-for="price" label="<?= __tr( 'Price' ) ?>"> 
            <div class="input-group">
                <div class="input-group-prepend" id="basic-addon1">
                    <span class="input-group-text" ng-bind-html="addProductCtrl.store_currency_symbol"></span>
                </div>
                <input type="number" 
                  class="lw-form-field form-control"
                  name="price"
                  ng-required="true"
                  min="0.1"
                  ng-model="addProductCtrl.productData.price" />
                <div class="input-group-append" id="basic-addon1">
                    <span class="input-group-text" ng-bind-html="addProductCtrl.store_currency"></span>
                </div>
            </div>
        </lw-form-field>
        <!-- Price -->

        <!-- Description -->
        <lw-form-field field-for="description" label="<?= __tr( 'Description' ) ?>"> 
            <textarea name="description" class="lw-form-field form-control" ng-required="true"
             cols="30" rows="10" lw-ck-editor ng-minlength="10" ng-model="addProductCtrl.productData.description"></textarea>
         </lw-form-field>
        <!-- Description -->

        <!-- Related Products -->
        <div class="form-group">
	        <lw-form-selectize-field field-for="related_products" label="<?= __tr( 'Related Products' ) ?>" class="lw-selectize">
	            <selectize config='addProductCtrl.relatedProductsSelectConfig' class="lw-form-field" name="related_products" ng-model="addProductCtrl.productData.related_products" options='addProductCtrl.related_products' placeholder="<?= __tr( 'Select Related Products' ) ?>" ></selectize>
	        </lw-form-selectize-field>
        </div>
        <!-- Related Products -->

   		<hr>
        <div class="text-right">

            <button type="submit" 
            	ng-click="addProductCtrl.saveAndAddOptions()" 
            	class="lw-btn btn btn-primary btn-sm" 
            	title="<?= __tr('Save for now &amp; continue to add options (Publically not available until mark as active.)') ?>">
            <?= __tr('Save &amp; Continue') ?> <span></span></button>

            <button type="submit" 
            	ng-click="addProductCtrl.saveAndPublish()" 
            	class="lw-btn btn btn-light btn-sm" 
            	title="<?= __tr('Save this item &amp; mark as active.') ?>">
            <?= __tr('Save &amp; Publish') ?> <span></span></button>

            <button type="button" 
            		ui-sref="products" 
            		class="lw-btn btn btn-light btn-sm" 
            		title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>

    </form>
	<!-- form action -->
	</div>

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

</div>
