<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Contact Info') ?></h4>
</div>
<div ng-controller="ContactSettingsController as contactSettingsCtrl">
		
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="contactSettingsCtrl.[[ contactSettingsCtrl.ngFormName ]]"
        novalidate>
		
		<div>
	    	
	    	<div ng-if="contactSettingsCtrl.pageStatus">
				<!-- Contact -->
		       	<lw-form-field field-for="contact_email" label="<?= __tr( 'Email Address For Contact Form' ) ?>"> 
		            <input type="email" 
	                      placeholder="[[ contactSettingsCtrl.editData.contact_email_placeholder ]]" 
		                  class="lw-form-field form-control"
		                  autofocus
		                  name="contact_email"
		                  ng-required="true" 
		                  ng-model="contactSettingsCtrl.editData.contact_email" />
		        </lw-form-field>
		        <!-- /Contact -->

		        <!-- Contact Address -->
		        <lw-form-field field-for="contact_address" label="<?= __tr('Address, Telephone For Contact Page') ?>" ng-if="contactSettingsCtrl.pageStatus"> 
                    <a href lw-transliterate entity-type="contact_setting" entity-id="null" entity-key="contact_address" entity-string="[[contactSettingsCtrl.editData.contact_address]]" input-type="3"><i class="fa fa-globe"></i></a>
		            <textarea name="contact_address" class="lw-form-field form-control" cols="10" rows="3" ng-minlength="6" ng-required="true" ng-model="contactSettingsCtrl.editData.contact_address" lw-ck-editor></textarea>
		        </lw-form-field>
		        <!-- Contact Address --> 
	        </div>
		</div>
		<div class="modal-footer">
			<button type="submit" ng-click="contactSettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>