<div ng-controller="ProductOptionValuesController as productOptionValuesCtrl" class="lw-dialog">
    <!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Add / Edit Values") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="productOptionValuesCtrl.[[ productOptionValuesCtrl.ngFormName ]]" 
        novalidate>
		<!-- note -->
        <div class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?> </strong><span ng-bind="productOptionValuesCtrl.notification_message"></span></div>

        <div ng-if="!productOptionValuesCtrl.isOptionValueImagesUsed" class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?> </strong> Use <a ui-sref="product_edit.images({'productID' : productOptionValuesCtrl.productID})" target="_blank">Product Images</a> to set the value specific images.</div>
        <!-- /note -->

        <div ng-if="productOptionValuesCtrl.isOptionValueImagesUsed" class="alert alert-info alert-dismissible" role="alert">
            <strong><?= __tr( 'Note : ' ) ?> </strong> <?= __tr('As the product images already used for other Option, you can not assign images in this option.') ?>
        </div>
       
       <div class="card mb-3" ng-repeat="value in productOptionValuesCtrl.optionData.values track by $index">
           	<div class="card-body">
                <div class="form-row">
                    <div class="col">
                        <!-- Name -->
                        <lw-form-field field-for="values.[[ $index ]].name" label="<?= __tr( 'Value Name' ) ?>" ng-if="value.id"> 
                            <div class="input-group">
                                <input type="text" 
                                  class="lw-form-field form-control"
                                  name="values.[[ $index ]].name"
                                  ng-change="productOptionValuesCtrl.checkUnique($index, productOptionValuesCtrl.optionData.values[$index]['name'])"
                                  ng-required="true" 
                                  ng-model="productOptionValuesCtrl.optionData.values[$index]['name']" />
                                <div class="input-group-append">
                                    <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="value" entity-id="[[ value.id ]]" entity-key="name" entity-string="[[ productOptionValuesCtrl.optionData.values[$index]['name'] ]]" input-type="1">
                                        <i class="fa fa-globe"></i>
                                    </button>
                                </div>
                            </div>
                        </lw-form-field>
                        <!-- /Name -->

                        <!-- Name -->
                        <lw-form-field field-for="values.[[ $index ]].name" label="<?= __tr( 'Value Name' ) ?>" ng-if="!value.id"> 
                            <input type="text" 
                              class="lw-form-field form-control"
                              name="values.[[ $index ]].name"
                              ng-change="productOptionValuesCtrl.checkUnique($index, productOptionValuesCtrl.optionData.values[$index]['name'])"
                              ng-required="true" 
                              ng-model="productOptionValuesCtrl.optionData.values[$index]['name']" />
                        </lw-form-field>
                        <!-- /Name -->
                    </div>

                    <div class="col">
                        <!-- Addon Price and button -->
                        <lw-form-field field-for="values.[[ $index ]].addon_price" label="<?= __tr( 'Addon Price' ) ?>"> 
                            <div class="input-group">
                                <div class="input-group-prepend" id="basic-addon1">
                                        <span class="input-group-text"><?= getStoreSettings('currency_symbol') ?></span>
                                </div>
                                <input type="number" 
                                      class="lw-form-field form-control"
                                      name="values.[[ $index ]].addon_price"
                                      min="0"
                                      ng-model="productOptionValuesCtrl.optionData.values[$index]['addon_price']" />
                                    <div class="input-group-prepend" id="basic-addon1">
                                        <span class="input-group-text"><?= getStoreSettings('currency_value') ?></span>
                                    </div>  
                                <span ng-hide="value.id">
                                    <button ng-disabled="$first" ng-click="productOptionValuesCtrl.remove($index)" title="<?= __tr( 'Remove' ) ?>" class="lw-btn btn btn-secondary btn btn-light lw-remove-button"><i class="fa fa-times"></i> </button>
                                </span>
                                <span input-group-btn" ng-if="value.id">
                                    <button ng-disabled="productOptionValuesCtrl.optionData.values.length == 1"ng-click="productOptionValuesCtrl.delete(value.id, productOptionValuesCtrl.optionType)" title="<?= __tr( 'Remove' ) ?>" class="btn btn-secondary btn btn-light lw-btn lw-remove-button"><i class="fa fa-times"></i> </button>
                                </span>
                            </div>
                        </lw-form-field>
                        <!-- /Addon Price and button -->
                    </div>   
                </div>

                <!-- Slider Image -->
                <div ng-if="!productOptionValuesCtrl.isOptionValueImagesUsed">
                    <lw-form-selectize-field 
                        field-for="values.[[ $index ]].images" 
                        label="<?= __tr( 'Value Specific Images' ) ?>" 
                        class="lw-selectize">

                        <selectize config='productOptionValuesCtrl.productImagesSelectConfig' class="lw-form-field" name="values.[[ $index ]].images" ng-model="productOptionValuesCtrl.optionData.values[$index]['slider_images']" options='productOptionValuesCtrl.productImages' placeholder="<?= __tr( 'Value Specific Images' ) ?>" ></selectize>

                    </lw-form-selectize-field>
                </div>
                <!-- /Slider Image -->

                <!-- /Select Image -->
                <div class="row">

                	<div class="col-md-3" ng-if="productOptionValuesCtrl.optionType === 2"> 
            		   	<div class="lw-form-append-btns">
                            <span class="btn btn-primary btn-sm lw-btn-file">
                                <i class="fa fa-upload"></i> 
                                        <?=   __tr('Upload New Images')   ?>
                                <input type="file" nv-file-select="" ng-click="productOptionValuesCtrl.addImages($index)" uploader="productOptionValuesCtrl.uploader" multiple/>
                            </span>
                		</div>

                        <!-- image validation msg -->
                        <lw-form-checkbox-field field-for="values.[[ $index ]].image" v-label="<?= __tr('Image')   ?>" class="text-center" ng-if="!value.existingThumbnailURL">
                            <input type="hidden" ng-required="true" class="lw-form-field hidden" name="values.[[ $index ]].image" ng-model="productOptionValuesCtrl.optionData.values[$index]['image']"/>
                        </lw-form-checkbox-field>
                        <!-- / image validation msg -->
                	</div>
                	<div class="col-md-9">
                		<div class="row">
                			<div class="col-md-6 p-0" ng-if="value.existingThumbnailURL">
								<div class="small"><strong><?= __tr('Existing Image') ?></strong>:</div>
		                        <div ng-if="value.existingThumbnailURL">
		                            <img width="30%" ng-src="[[value.existingThumbnailURL]]" class="border">
		                            <div class="small" ng-bind="value.existingImage"></div>
		                        </div>
		                	</div>
		                	<div class="col-md-6 p-0" ng-if="value.thumbnailURL">
		                		<div class="small"><strong><?= __tr('New Image') ?></strong>:</div>
		                        <div>
		                            <img width="30%" ng-src="[[value.thumbnailURL]]" class="border">
		                            <div class="small" ng-bind="value.image"></div>
		                        </div>
		                	</div>
                		</div>
                	</div>
                </div>
            </div>
       </div>
            
		<!-- action button -->
        <div class="form-group">
            <button class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Add More' ) ?>" ng-click="productOptionValuesCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __tr( 'Add More' ) ?></button>
        </div>		

        <div class="modal-footer">
            <button type="submit" ng-click="productOptionValuesCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="submit" ng-click="productOptionValuesCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
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
