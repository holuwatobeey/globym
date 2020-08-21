<div class="lw-dialog">
   	<!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Add New Option") ?> </h3>
    </div>
    <!-- /main heading --> 
    <!-- form action -->
    <form class="lw-form lw-ng-form " 
        name="addOptionCtrl.[[ addOptionCtrl.ngFormName ]]" 
        novalidate>

        <div ng-if="addOptionCtrl.addOptionType == 1" class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?></strong> <?= __tr( 'Adding an option will showing dropdown menu on product page.' ) ?></div>

        <div ng-if="addOptionCtrl.addOptionType == 2" class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?></strong> <?= __tr( 'Adding an option will showing image on product page.' ) ?></div>

        <div ng-if="addOptionCtrl.addOptionType == 3" class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?></strong> <?= __tr( 'Adding an option will showing radio button on product page.' ) ?></div>
        
        <div ng-if="!addOptionCtrl.isOptionValueImagesUsed" class="alert alert-info alert-dismissible" role="alert"><strong><?= __tr( 'Note : ' ) ?> </strong> Use <a target="_blank" ui-sref="product_edit.images({'productID' : addOptionCtrl.productID})">Product Images</a> to set the value specific images.</div>

        <div ng-if="addOptionCtrl.isOptionValueImagesUsed" class="alert alert-info alert-dismissible" role="alert">
            <strong><?= __tr( 'Note : ' ) ?> </strong> <?= __tr('As the product images already used for other Option, you can not assign images in this option.') ?>
        </div>
        

        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="name"
              ng-required="true"
              autofocus 
              ng-model="addOptionCtrl.optionData.name"/>
        </lw-form-field>
        <!-- Name -->

        <div class="clearfix"></div>

        <div class="card mb-3 mt-3" ng-repeat="value in addOptionCtrl.optionData.values track by $index">
            <div class="card-body">
                <div class="form-row">
                    <div class="col">
                        <!-- Name --> 
                        <lw-form-field field-for="values.[[ $index ]].name" label="<?= __tr( 'Value Name' ) ?>"> 
                            <input type="text" 
                              class="lw-form-field form-control"
                              name="values.[[ $index ]].name"
                              ng-change="addOptionCtrl.checkUnique($index, ddOptionCtrl.optionData.values[$index]['name'])"
                              ng-required="true" 
                              ng-model="addOptionCtrl.optionData.values[$index]['name']" />
                        </lw-form-field>
                        <!-- Name -->
                    </div>

                    <div class="col">
                        <!-- Addon Price -->
                        <lw-form-field field-for="values.[[ $index ]].addon_price" label="<?= __tr( 'Addon Price' ) ?>"> 
                            <div class="input-group">
                                <div class="input-group-prepend" id="basic-addon1">
                                    <span class="input-group-text"><?= getStoreSettings('currency_symbol') ?></span>
                                </div>
                                <input type="number" 
                                      class="lw-form-field form-control"
                                      name="values.[[ $index ]].addon_price"
                                      min="0"
                                      ng-model="addOptionCtrl.optionData.values[$index]['addon_price']" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text"><?= getStoreSettings('currency_value') ?></span>
                                        <button ng-disabled="$first" ng-click="addOptionCtrl.remove($index)" title="<?= __tr( 'Remove' ) ?>" class="btn btn-secondary btn btn-light lw-btn lw-remove-button"><i class="fa fa-times"></i> </button>
                                </div>      
                                    
                            </div>
                        </lw-form-field>
                        <!-- /Addon Price -->   
                    </div>

				</div>
                
                <div ng-if="!addOptionCtrl.isOptionValueImagesUsed">

                <lw-form-selectize-field 
                        field-for="values.[[ $index ]].images" 
                        label="<?= __tr( 'Value Specific Images' ) ?>" 
                        class="lw-selectize">

                        <selectize config='addOptionCtrl.productImagesSelectConfig' class="lw-form-field" name="values.[[ $index ]].images" ng-model="addOptionCtrl.optionData.values[$index]['slider_images']" options='addOptionCtrl.productImages' placeholder="<?= __tr( 'Value Specific Images' ) ?>" ></selectize>

                    </lw-form-selectize-field>
                </div>

				<div class="row mt-4">
                    <div class="col-sm-3" ng-if="addOptionCtrl.addOptionType === 2">
                        <!-- Select Image -->
                        <div class="form-group" ng-if="addOptionCtrl.addOptionType === 2">
                            <div class="lw-form-append-btns">
                                <span class="btn btn-primary btn-sm lw-btn-file">
                                    <i class="fa fa-upload"></i> 
                                            <?=   __tr('Browse Images')   ?>
                                    <input type="file" nv-file-select="" ng-click="addOptionCtrl.addImages($index)" uploader="addOptionCtrl.uploader" ng-required="true"/>
                                </span>
                            </div>

                            <!-- image validation msg -->
                            <lw-form-checkbox-field field-for="values.[[ $index ]].image" v-label="<?= __tr('Image')   ?>" class="text-center">
                                <input type="hidden" ng-required="true" class="lw-form-field hidden" name="values.[[ $index ]].image" ng-model="addOptionCtrl.optionData.values[$index]['image']"/>
                            </lw-form-checkbox-field>
                            <!-- / image validation msg -->
                        </div>
                        <!-- /Select Image -->
                    </div>
                   
                    <div class="col-sm-3">
                        <div class="image-name lw-image-thumbnail" ><img ng-src=[[value.thumbnailURL]] class="border"></div> 
                    </div>

                     <div class="col-sm-6">
                        <div style="word-wrap: break-word;">
                            [[ addOptionCtrl.optionData.values[$index]['image'] ]] 
                        </div>
                    </div>

                </div>
            </div>
        </div>

		<!-- button action -->
        <div class="form-group">
            <button class="btn btn-light btn-sm lw-btn" title="<?= __tr( 'Add More' ) ?>" ng-click="addOptionCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __tr( 'Add More' ) ?></button>
        </div>

        <div class="modal-footer">
            <button type="submit" ng-click="addOptionCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
            <button type="submit" ng-click="addOptionCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /button action -->
		
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
