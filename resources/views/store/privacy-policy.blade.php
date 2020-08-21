<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Privacy Policy Settings') ?></h4>
</div>
<div ng-controller="PrivacyPolicySettingsController as privacyPolicySettingsCtrl">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="privacyPolicySettingsCtrl.[[ privacyPolicySettingsCtrl.ngFormName ]]"
        novalidate>

		<div ng-if="privacyPolicySettingsCtrl.pageStatus">
			<lw-form-field field-for="privacy_policy" label="<?= __tr( 'Privacy' ) ?>" ng-if="privacyPolicySettingsCtrl.pageStatus">
                <a href lw-transliterate entity-type="privacy_policy_setting" entity-id="null" entity-key="privacy_policy" entity-string="[[privacyPolicySettingsCtrl.editData.privacy_policy]]" input-type="3"><i class="fa fa-globe"></i></a>
                
	            <textarea name="privacy_policy" class="lw-form-field form-control" cols="30" rows="10" ng-minlength="6" ng-model="privacyPolicySettingsCtrl.editData.privacy_policy" lw-ck-editor></textarea>
	        </lw-form-field>
        </div>
        <div class="modal-footer">
			<button type="submit" ng-click="privacyPolicySettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="btn btn-light lw-btn" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
	</form>
	<!-- /form action -->
</div>