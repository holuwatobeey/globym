<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Product Settings') ?></h4>
</div>
<div ng-controller="ProductSettingsController as productSettingsCtrl">

	<!-- form action -->
	<form class="lw-form lw-ng-form" name="productSettingsCtrl.[[ productSettingsCtrl.ngFormName ]]" novalidate>
        <!-- Product Edit Tabs -->
        <div class="row mt-4" ng-if="productSettingsCtrl.pageStatus">
            <div class="col-lg-3 col-sm-3 col-md-4 col-12 mb-2">
                <div class="list-group lw-payment-list-group" id="list-tab" role="tablist">
                    <!-- Product -->
                    <a class="list-group-item list-group-item-action active" id="product-list" data-toggle="list" href="#product" role="tab" aria-controls="product" title="<?=  __tr('Product')  ?>" ng-click="productSettingsCtrl.manageProductSettingTab($event)"><?= __tr('Product') ?></a>
                    <!-- /Product -->

                    <!-- Ratings -->
                    <a class="list-group-item list-group-item-action" id="rating-list" data-toggle="list" title="<?=  __tr('Ratings')  ?>" href="#rating" role="tab" aria-controls="rating" ng-click="productSettingsCtrl.manageProductSettingTab($event)"><?= __tr('Ratings') ?></a>
                    <!-- /Ratings -->

                    <!-- Social Sharing Links -->
                    <a class="list-group-item list-group-item-action" id="socialLink-list" data-toggle="list" title="<?=  __tr('Social Sharing Links')  ?>" href="#socialLink" role="tab" aria-controls="socialLink" ng-click="productSettingsCtrl.manageProductSettingTab($event)"><?= __tr('Social Sharing Links') ?></a>
                    <!-- /Social Sharing Links -->                   
                </div>

            </div>

            <div class="col-lg-9 col-sm-9 col-md-8 mt-1">
                <div class="tab-content" id="nav-tabContent">
                    
                    <!-- Product Setting Block -->
                    <div class="tab-pane fade show active" id="product" role="tabpanel" aria-labelledby="product-list">
                        <div id="lwProductBlock">
                            <!--Show Out of Stock Products    -->
                            <lw-form-checkbox-field field-for="show_out_of_stock" label="<?= __tr( 'Show Out Of Stock Products' ) ?>" ng-if="productSettingsCtrl.pageStatus">
                                <input type="checkbox" 
                                     class="lw-form-field js-switch"
                                    ui-switch=""
                                    name="show_out_of_stock"
                                    ng-model="productSettingsCtrl.editData.show_out_of_stock"/>
                            </lw-form-checkbox-field>
                            <!-- /Show Out of Stock Products -->

                            <!-- Enable Staticaly CDN for Images  -->
                            <lw-form-checkbox-field field-for="enable_staticaly_cdn" label="<?= __tr( "Enable Staticaly CDN for Images" ) ?>" ng-if="productSettingsCtrl.pageStatus">
                                <input type="checkbox" 
                                    class="lw-form-field js-switch"
                                    ui-switch=""
                                    name="enable_staticaly_cdn"
                                    ng-model="productSettingsCtrl.editData.enable_staticaly_cdn"/>
                            </lw-form-checkbox-field>
                            <!-- /Enable Staticaly CDN for Images -->

                            <div class="alert alert-info">
                                <?= __tr('For more detail please check: ') ?><a target="_blank" href="https://www.staticaly.com">https://www.staticaly.com</a>
                            </div>

                            <fieldset class="lw-fieldset-2 mb-3">

                                <legend>
                                      <?=  __tr('Paginated Product Loading Type')  ?>
                                </legend>

                                <!-- item load type -->
                                <lw-form-radio-field field-for="item_load_type" label="<?= __tr('Load Type' ) ?>">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input"  name="item_load_type" ng-model="productSettingsCtrl.editData.item_load_type" ng-value="1"><?= __tr('Use Scroll') ?>
                                        </label>
                                    </div>

                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input"  name="item_load_type" ng-model="productSettingsCtrl.editData.item_load_type" ng-value="2"><?= __tr('Use Button') ?>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input"  name="item_load_type" ng-model="productSettingsCtrl.editData.item_load_type" ng-value="3"><?= __tr('Use Pagination') ?>
                                        </label>
                                    </div>
                                </lw-form-radio-field>
                                <!-- /item load type -->
                                

                                <div class="alert alert-info" ng-if="productSettingsCtrl.editData.item_load_type == 1 || productSettingsCtrl.editData.item_load_type == 3">
                                  <?= __tr('<strong>Note </strong>Minimum 20 products per page in the list is required to use scroll / pagination.') ?>
                                </div>

                                <div class="alert alert-info" ng-if="productSettingsCtrl.editData.item_load_type == 2">
                                  <?= __tr('<strong>Note </strong>Minimum 5 products per page in the list is required to use button.') ?>
                                </div>

                                <!-- Products per page -->
                                <lw-form-field field-for="pagination_count" ng-if="productSettingsCtrl.editData.item_load_type == 1 || productSettingsCtrl.editData.item_load_type == 3" label="<?= __tr( 'Products Per Page On List' ) ?>"> 
                                    <input type="number" 
                                        class="lw-form-field form-control"
                                        autofocus
                                        name="pagination_count"
                                        ng-required="true"
                                        min="20"
                                        max="100" 
                                        step="1"
                                        ng-model="productSettingsCtrl.editData.pagination_count" />
                                </lw-form-field>
                                <!-- Products per page -->

                                <!-- Products per page -->
                                <lw-form-field field-for="pagination_count" ng-if="productSettingsCtrl.editData.item_load_type == 2" label="<?= __tr( 'Products Per Page On List' ) ?>"> 
                                    <input type="number" 
                                        class="lw-form-field form-control"
                                        autofocus
                                        name="pagination_count"
                                        ng-required="true"
                                        min="5"
                                        max="100" 
                                        step="1"
                                        ng-model="productSettingsCtrl.editData.pagination_count" />
                                </lw-form-field>
                                <!-- Products per page -->
                            </fieldset>
                        </div>
                    </div>
                    <!-- Product Setting Block -->

                    <!-- Rating Setting Block -->
                    <div class="tab-pane fade" id="rating" role="tabpanel" aria-labelledby="rating-list">
                        <div id="lwRatingBlock">
                            <fieldset class="lw-fieldset-2 mb-3">
                                <legend>
                                      <?=  __tr('Ratings')  ?>
                                </legend>

                                <!-- Enable Rating -->
                                <lw-form-checkbox-field field-for="enable_rating" label="<?= __tr( 'Enable Rating' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="enable_rating"
                                        ng-model="productSettingsCtrl.editData.enable_rating"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Enable Rating -->

                                <!-- Enable Rating Review -->
                                <lw-form-checkbox-field field-for="enable_rating_review" label="<?= __tr( 'Enable Rating Review' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="enable_rating_review"
                                        ng-model="productSettingsCtrl.editData.enable_rating_review"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Enable Rating Review -->

                                <!-- Restrict add rating to item purchased Users -->
                                <lw-form-checkbox-field field-for="restrict_add_rating_to_item_purchased_users" label="<?= __tr('Only Buyers Can Add Rating' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="restrict_add_rating_to_item_purchased_users"
                                        ng-model="productSettingsCtrl.editData.restrict_add_rating_to_item_purchased_users"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Restrict add rating to item purchased Users -->

                                <!-- Enable Rating Modification -->
                                <lw-form-checkbox-field field-for="enable_rating_modification" label="<?= __tr( 'Enable Rating & Review Modification' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="enable_rating_modification"
                                        ng-model="productSettingsCtrl.editData.enable_rating_modification"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Enable Rating Modification -->

                            </fieldset>
                        </div>
                    </div>
                    <!-- Rating Setting Block -->

                    <!-- Share Link Setting Block -->
                    <div class="tab-pane fade" id="socialLink" role="tabpanel" aria-labelledby="socialLink-list">
                        <div id="lwShareLinkBlock">
                            <fieldset class="lw-fieldset-2 mb-3">
                                <legend>
                                      <?=  __tr('Product Social Sharing Links')  ?>
                                </legend>

                                <!-- Facebook -->
                                <lw-form-checkbox-field field-for="facebook" label="<?= __tr( 'Facebook' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="facebook"
                                        ng-model="productSettingsCtrl.editData.facebook"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Facebook -->

                                <!-- Twitter -->
                                <lw-form-checkbox-field field-for="twitter" label="<?= __tr( 'Twitter' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="twitter"
                                        ng-model="productSettingsCtrl.editData.twitter"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Twitter -->

                                <!--Enable  Whats App -->
                                <lw-form-checkbox-field field-for="enable_whatsapp" label="<?= __tr( 'WhatsApp' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="enable_whatsapp"
                                        ng-model="productSettingsCtrl.editData.enable_whatsapp"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Enable Whats App -->
                            </fieldset>
                        </div>
                    </div>
                    <!-- Share Link Setting Block -->
                </div>

                <div class="modal-footer">
                    <button type="submit" ng-click="productSettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
                        <?= __tr('Update') ?><span></span> 
                    </button>
                    <!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
                </div>
            </div>
        </div>
	</form>
	<!-- /form action -->
</div>