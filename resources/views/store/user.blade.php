<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('User Settings') ?></h4>
</div>

<div ng-controller="userSettingsController as userSettingCtrl" >
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="userSettingCtrl.[[ userSettingCtrl.ngFormName ]]" novalidate>

        <div>
        	<!-- Register New User -->
	        <lw-form-checkbox-field field-for="register_new_user" label="<?= __tr( 'Register New User' ) ?>" advance="true" ng-if="userSettingCtrl.pageStatus">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="register_new_user"
	                ng-model="userSettingCtrl.editData.register_new_user"
	                ui-switch="" />
	        </lw-form-checkbox-field>
	        <!-- /Register New User -->

	        <!-- Activation Required For New User -->
	        <lw-form-checkbox-field field-for="activation_required_for_new_user" label="<?= __tr( 'Activation Required For New User' ) ?>" advance="true" ng-if="userSettingCtrl.pageStatus">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="activation_required_for_new_user"
	                ng-model="userSettingCtrl.editData.activation_required_for_new_user"
	                ui-switch="" />
	        </lw-form-checkbox-field>
	        <!-- /Activation Required For New User -->

	        <!-- Activation Required For Change Email -->
	        <lw-form-checkbox-field field-for="activation_required_for_change_email" label="<?= __tr( 'Activation Required For Change Email' ) ?>" advance="true"  ng-if="userSettingCtrl.pageStatus">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="activation_required_for_change_email"
	                ng-model="userSettingCtrl.editData.activation_required_for_change_email"
	                ui-switch="" />
	        </lw-form-checkbox-field>
	        <!-- /Activation Required For Change User -->

	        <!-- Enable Product Wishlist -->
	        <lw-form-checkbox-field field-for="enable_wishlist" label="<?= __tr( 'Enable Product Wishlist' ) ?>" advance="true"  ng-if="userSettingCtrl.pageStatus">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="enable_wishlist"
	                ng-model="userSettingCtrl.editData.enable_wishlist"
	                ui-switch="" />
	        </lw-form-checkbox-field>
	        <!-- /Enable Product Wishlist -->


	        <fieldset class="lw-fieldset-2 mb-3">
	            <legend class="lw-fieldset-legend-font"><?= __tr('Login settings') ?></legend>

	            <div class="form-inline">
	                <div class="p-md-2"><?= __tr('Show captcha after') ?></div>

	                <!-- Show Captcha after login attempt -->
	                <lw-form-field field-for="show_captcha" v-label="<?= __tr( 'Show Captcha' ) ?>"> 
	                    <input type="number" 
	                            class="lw-form-field form-control w-100"
	                            autofocus
	                            name="show_captcha"
	                            ng-required="true"
	                            min="1"
	                            ng-model="userSettingCtrl.editData.show_captcha" />
	                </lw-form-field>
	                <!-- /Show Captcha after login attempt -->
					<div class="col-sm-3"><?= __tr(' login attempts') ?></div>
	            </div>

                <br>

                <!-- Enable Google Re-Captcha -->
                <lw-form-checkbox-field field-for="enable_recaptcha" label="<?= __tr( 'Enable Recaptcha' ) ?>" advance="true"  ng-if="userSettingCtrl.pageStatus">
                    <input type="checkbox" 
                        class="lw-form-field js-switch"
                        name="enable_recaptcha"
                        ng-model="userSettingCtrl.editData.enable_recaptcha"
                        ui-switch="" />
                </lw-form-checkbox-field>
                <!-- /Enable Google Re-Captcha -->

                 <!-- show after added live recaptcha key exists information -->
                <div class="btn-group" ng-if="userSettingCtrl.editData.isRecaptchaKeyExist">
                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Recaptcha keys are installed.') ?></button>
                  <button type="button" ng-click="userSettingCtrl.addRecaptchaKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                </div>
                <!-- show after added live recaptcha key exists information -->

                <!-- Google recaptcha Site Key -->
                <lw-form-field field-for="recaptcha_site_key" label="<?= __tr( 'Google Recaptcha Site Key' ) ?>" ng-if="userSettingCtrl.editData.enable_recaptcha == true && !userSettingCtrl.editData.isRecaptchaKeyExist"> 
                    <input type="text" 
                          class="lw-form-field form-control"
                          name="recaptcha_site_key"
                          ng-required="userSettingCtrl.editData.enable_recaptcha" 
                          ng-model="userSettingCtrl.editData.recaptcha_site_key" />
                </lw-form-field>
                <!-- Google Recaptcha Site Key -->

                <!-- Google Recaptcha Secret Key -->
                <lw-form-field field-for="recaptcha_secret_key" label="<?= __tr( 'Google Recaptcha Secret Key' ) ?>" ng-if="userSettingCtrl.editData.enable_recaptcha == true  && !userSettingCtrl.editData.isRecaptchaKeyExist"> 
                    <input type="text" 
                          class="lw-form-field form-control"
                          name="recaptcha_secret_key"
                          ng-required="userSettingCtrl.editData.enable_recaptcha" 
                          ng-model="userSettingCtrl.editData.recaptcha_secret_key" />
                </lw-form-field>
                <!-- Google Recaptcha Secret Key -->
	        </fieldset>
	    	
	    	<!-- terms and condition ck editor -->
			<div ng-if="userSettingCtrl.pageStatus">
		        <lw-form-field field-for="term_condition" label="<?= __tr( 'Terms & Conditions For User Registration' ) ?>" >
                    <a href lw-transliterate entity-type="users_setting" entity-id="null" entity-key="term_condition" entity-string="[[ userSettingCtrl.editData.term_condition ]]" input-type="3"><i class="fa fa-globe"></i></a>

			        <textarea 
			            name="term_condition" 
			            class="lw-form-field form-control" 
			            cols="30" 
			            rows="10" 
			            ng-minlength="6" 
			            ng-model="userSettingCtrl.editData.term_condition" 
			            lw-ck-editor 
			            >
			        </textarea>
		    	</lw-form-field>
			  	<!-- /terms and condition ck editor -->   
		    </div>
        </div>
	    <div class="modal-footer">
			<button type="submit" ng-click="userSettingCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>