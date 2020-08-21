<div class="lw-section-heading-block">
 	<h4 class="lw-header"><?= __tr('General Settings') ?></h4>
</div>
<div ng-controller="GeneralSettingsController as generalSettingsCtrl" id="general">

	<!-- form action -->
	<form class="lw-form lw-ng-form" name="generalSettingsCtrl.[[ generalSettingsCtrl.ngFormName ]]"
        novalidate>

		<div ng-if="generalSettingsCtrl.pageStatus">
	        <!-- Store Name -->
	        <lw-form-field field-for="store_name" label="<?= __tr( 'Store Name' ) ?>">
                <div class="input-group"> 
    	            <input type="text" 
    	                  class="lw-form-field form-control"
    	                  autofocus
    	                  name="store_name"
    	                  ng-required="true" 
    	                  ng-model="generalSettingsCtrl.editData.store_name" />
                    <div class="input-group-append">
                        <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="general_setting" entity-id="null]" entity-key="store_name" entity-string="[[ generalSettingsCtrl.editData.store_name ]]" input-type="1">
                            <i class="fa fa-globe"></i>
                        </button>
                    </div>
                </div>
	        </lw-form-field>
	        <!-- Store Name -->

            <fieldset class="lw-fieldset-2 mb-3">
                <legend>
                      <?=  __tr('Logo')  ?>
                </legend>
            
    			<div class="form-group lw-current-logo-conatiner" ng-show="generalSettingsCtrl.editData.logoURL" style="background: #[[ generalSettingsCtrl.editData.logo_background_color ]]">
    	            <label class="control-label"><?=  __tr("Current Logo")  ?></label>
    		        <div class="lw-thumb-logo">
    		        	<a href="[[generalSettingsCtrl.editData.logoURL]]" lw-ng-colorbox class="lw-thumb-logo"><img ng-src="[[generalSettingsCtrl.editData.logoURL]]" alt=""></a>
    		        </div>
    	        </div>

                <!-- Upload image -->
                <div class="form-group  text-center">
                    <div class="lw-form-append-btns">
                        <span class="btn btn-primary btn-sm lw-btn-file">
                            <i class="fa fa-upload"></i> 
                                    <?=   __tr('Upload New Images')   ?>
                            <input type="file" nv-file-select="" uploader="generalSettingsCtrl.uploader" multiple/>
                        </span>
                        <button class="lw-btn btn btn-light btn-sm" title="<?= __tr('Uploaded Images')  ?>" 
                        ng-click="generalSettingsCtrl.showUploadedMediaDialog()" type="button">
                        <?=  __tr("Uploaded Images")  ?></button> 
                    </div>
                </div>
                <!--/ Upload image -->

    	       	<!-- Select Invoice Logo -->
    	       	<div class="form-group">
                    <!-- New Favicon image -->
                    <lw-form-selectize-field field-for="favicon_image" label="<?= __tr( 'New Favicon' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge">[[ generalSettingsCtrl.faviconFilesCount ]]</span>
                        <selectize config='generalSettingsCtrl.logo_images_select_config' class="lw-form-field mb-2" name="favicon_image" ng-model="generalSettingsCtrl.editData.favicon_image" options='generalSettingsCtrl.icoFiles' placeholder="<?= __tr( 'Only ICO images are allowed' ) ?>" ></selectize>
                    </lw-form-selectize-field>
                    <!-- New Favicon image -->

                    <lw-form-selectize-field field-for="invoice_image" label="<?= __tr( 'New Invoice Logo' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge" ng-bind="generalSettingsCtrl.images_count"></span>
                        <selectize config='generalSettingsCtrl.logo_images_select_config' class="lw-form-field" name="invoice_image" ng-change="generalSettingsCtrl.imageChange(1)" ng-model="generalSettingsCtrl.editData.invoice_image" options='generalSettingsCtrl.logo_images' placeholder="<?= __tr( 'Only PNG images are expected' ) ?>"></selectize>
                    </lw-form-selectize-field><br>
                    <!-- /Select Invoice Logo -->

                    <!-- Select Logo Image -->
    		        <lw-form-selectize-field field-for="logo_image" label="<?= __tr( 'New Logo' ) ?>" class="lw-selectize"><span class="badge badge-success lw-badge" ng-bind="generalSettingsCtrl.images_count"></span>
    		            <selectize config='generalSettingsCtrl.logo_images_select_config' class="lw-form-field" name="logo_image" ng-change="generalSettingsCtrl.imageChange(2)" ng-model="generalSettingsCtrl.editData.logo_image" options='generalSettingsCtrl.logo_images' placeholder="<?= __tr( 'Only PNG images are expected' ) ?>"></selectize>
    		        </lw-form-selectize-field>
                    
    	        </div>
    	        <!-- Select Logo Image -->
            </fieldset>

            <fieldset class="lw-fieldset-2 mb-3">
                <legend>
                    <?= __tr('Theme Colors') ?>
                </legend>
                <div ng-repeat="(index, themeColor) in generalSettingsCtrl.themeColors" class="mt-2 mb-2">
                    <a href="" class="lw-theme-color-box" style="background-color: #[[ themeColor ]]" ng-click="generalSettingsCtrl.selectThemeColor(themeColor)"></a>
                </div>

                <div class="mt-5">
                    <!-- Selected Theme Color -->
                    <lw-form-field field-for="logo_background_color" label="" > 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" style="background: #[[ generalSettingsCtrl.editData.logo_background_color ]]">#
                                </span>
                            </div>
                            <input type="text" 
                                class="lw-form-field form-control"
                                autofocus
                                name="logo_background_color"
                                ng-minlength="6"
                                ng-maxlength="6"
                                lw-color-picker
                                readonly 
                                ng-model="generalSettingsCtrl.editData.logo_background_color" />
                            </div>
                    </lw-form-field>
                    <!-- Selected Theme Color -->
                </div>
            </fieldset>

	        <div class="form-row">
                <div class="form-group col-md-12">
			        <!-- Business Email -->
			        <lw-form-field field-for="business_email" label="<?= __tr( 'Business Email' ) ?>"> 
			            <input type="email" 
		                      placeholder="[[ generalSettingsCtrl.editData.business_email_placeholder ]]" 
			                  class="lw-form-field form-control"
			                  name="business_email"
			                  ng-required="true" 
			                  ng-model="generalSettingsCtrl.editData.business_email" />
			        </lw-form-field>
			        <!-- Business Email -->
				</div>
		    </div>

            <!-- show default language menu -->
            <lw-form-checkbox-field field-for="show_language_menu" label="<?= __tr( 'Show Language Menu' ) ?>" ng-if="generalSettingsCtrl.pageStatus">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    ui-switch=""
                    name="show_language_menu"
                    ng-model="generalSettingsCtrl.editData.show_language_menu"/>
            </lw-form-checkbox-field>
            <!-- /show default language menu  -->
            
            <div class="form-row">
                <div class="form-group col-md-6">
		         	<!-- Default Language -->
		            <lw-form-field field-for="default_language" label="<?= __tr( 'Default Language' ) ?>"> 
		               <select class="form-control" 
		                    name="default_language" ng-model="generalSettingsCtrl.editData.default_language" ng-options="languageKey as languageValue for (languageKey, languageValue) in generalSettingsCtrl.languages" ng-required="true">
		                    <option value='' disabled selected><?=  __tr('-- Select Language --')  ?></option>
		                </select> 
		            </lw-form-field>
		            <!-- /Default Language-->
			    </div>

                <div class="form-group col-md-6">
			        <!-- timezone -->
					<lw-form-field field-for="timezone" label="<?= __tr( 'TimeZone' ) ?>"> 
						<selectize config='generalSettingsCtrl.timezone_select_config' class="lw-form-field form-control" name="timezone" ng-model="generalSettingsCtrl.editData.timezone" options='generalSettingsCtrl.timezoneData' placeholder="<?= __tr( 'TimeZone' ) ?>" ng-required="true"></selectize>
					</lw-form-field>
					<!-- /timezone -->
                </div>
			</div>

	    </div>
        
		<div class="modal-footer">
			<button type="submit" ng-click="generalSettingsCtrl.submit()" class="btn btn-primary lw-btn" 
            		title="<?= __tr('Update') ?>"><?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>

<!-- New logo drop down list item template -->
<script type="text/_template" id="imageListItemTemplate">
    <div>
        <span class="lw-selectize-item lw-selectize-item-selected"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
    </div>
</script>
<!-- /New logo drop down list item template -->

<!-- New logo drop down list options template -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item">
        <span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
    </div>
</script>
<!-- /New logo drop down list options template -->