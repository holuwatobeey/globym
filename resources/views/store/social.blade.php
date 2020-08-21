<?php
/*
*  Component  : Settings
*  View       : Social Account Settings
*  File       : social.blade.php
*  Controller : SocialSettingsController
----------------------------------------------------------------------------- */
?>
<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Social Links Settings') ?></h4>
</div>

<div ng-controller="SocialSettingsController as socialSettingsCtrl">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="socialSettingsCtrl.[[ socialSettingsCtrl.ngFormName ]]"   
        novalidate>
		
		<div>
	    	<div ng-if="socialSettingsCtrl.pageStatus">
				<!-- social facebook field -->
		       	<lw-form-field field-for="social_facebook" label="<?= __tr( 'Facebook Username' ) ?>"> 
		            <input type="social_facebook"
	                      placeholder="[[ socialSettingsCtrl.editData.social_facebook_placeholder ]]" 
		                  class="lw-form-field form-control"
		                  autofocus
		                  name="social_facebook"
		                  ng-model="socialSettingsCtrl.editData.social_facebook" />
		        </lw-form-field>
		        <!-- /social facebook field -->

		        <!-- social twetter field -->
		       	<lw-form-field field-for="social_twitter" label="<?= __tr( 'Twitter Handle' ) ?>"> 
		            <input type="social_twitter" 
	                      placeholder="[[ socialSettingsCtrl.editData.social_twitter_placeholder ]]"
		                  class="lw-form-field form-control"
		                  name="social_twitter"
		                  ng-model="socialSettingsCtrl.editData.social_twitter" />
		        </lw-form-field>
		        <!-- /social twetter field -->
				
				 <!-- /Modal Body -->
	        	<div class="modal-footer">
					<button type="submit" ng-click="socialSettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
						<?= __tr('Update') ?><span></span> 
					</button>
					<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
				</div>
	        </div>
		</div>
	</form>
	<!-- /form action -->
</div>