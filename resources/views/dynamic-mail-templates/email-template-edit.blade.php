<?php
/*
*  Component  : Configuration
*  View       : email-template-edit.blade.php
*  Engine     : ConfigurationEngine  
*  File       : dynamic-email-template.email-template-list.blade.php  
*  Controller : EmailTemplateEditController as emailTemplateEditCtrl 
----------------------------------------------------------------------------- */ 
?>
<div>
    
  
	<div class="lw-section-heading-block">
        <!-- main heading -->
	   <div class="lw-breadcrumb">
	        <a class="lw-breadcrumb" ui-sref="email_templates" title="<?= __tr('Go To Email Template List') ?>">
				<?= __tr('Manage Email Template') ?>	
			</a> &raquo; 
        </div>

        <!-- main heading -->
        <div class="lw-section-heading">
            <span> [[ emailTemplateEditCtrl.emailTemplateData.title ]] </span>
        </div>
        <!-- /main heading -->
	       
    </div>

	<input type="hidden" id="lwTemlateDeleteConfirmTextMsg" data-message="<?= __tr( 'you want to load default template for __title__.') ?>" data-delete-button-text="<?= __tr('Yes, load it') ?>" data-success-text="<?= __tr('Loaded!') ?>">

	<div ng-include src="'lw-settings-update-reload-button-template.html'"></div>

	<div class="row">
   		
	    <!-- email template view update -->
	    <div class="col-lg-8 col-md-8 mb-4">
	    	<!-- form action -->
			<form class="lw-form lw-ng-form" name="emailTemplateEditCtrl.[[ emailTemplateEditCtrl.ngFormName ]]" ng-submit="emailTemplateEditCtrl.submit()" novalidate>

	    	<!-- Subject -->
			<lw-form-field field-for="emailSubject" label="<?= __tr( 'Subject' ) ?>" ng-if="emailTemplateEditCtrl.emailSubject != null && canAccess('store.settings.get.edit.email-template.data')">
				<div class="input-group">
				    <input type="text"
				           class="lw-form-field form-control" 
				           ng-model="emailTemplateEditCtrl.dynamicEmailData.emailSubject"
				           name="emailSubject">
                    <div class="input-group-prepend" id="basic-addon1">
                        <span class="input-group-text" id="basic-addon1">
                            <a href ng-click="emailTemplateEditCtrl.useDefaultEmailSubject(emailTemplateEditCtrl.emailSubjectKey)" title="<?= __tr('Reset this subject and use default subject') ?>" ><?= __tr('Use Default') ?></a>
                        </span>
                    </div>
                </div>
		    </lw-form-field>
			<!-- /Subject -->
				
	    	<!-- email_template setting editor-->
	        <lw-form-field field-for="email_template_editor" label="<?= __tr( 'Email Template Editor' ) ?>" ng-if="emailTemplateEditCtrl.pageStatus == true"> 
	            <textarea 
	                name="email_template_editor" 
	                id="email_template_editor"
	                class="lw-form-field form-control" 
	                cols="30" 
	                rows="10" 
	                ng-minlength="6" 
	                ng-model="emailTemplateEditCtrl.dynamicEmailData.templateViewData" 
	                lw-ck-editor 
	                options="[[ emailTemplateEditCtrl.editorConfig ]]"
	                >
	            </textarea>
	        </lw-form-field>
	        <!-- /email_template setting editor-->
			<!-- / Modal Body -->

			<button ng-if="canAccess('store.settings.get.edit.email-template.data')" type="button" ng-click="emailTemplateEditCtrl.defaultEmailTemlate(emailTemplateEditCtrl.emailTemplateData.title)" class="lw-btn btn btn-light btn-sm" title="<?= __tr('Use Default Template') ?>" ng-disabled="emailTemplateEditCtrl.templateDataExist == false"><?= __tr('Use Default Template') ?></button>

			<!-- /Modal footer -->
		    <div class="pull-right">
			  	<button ng-if="canAccess('store.settings.get.edit.email-template.data')" type="submit" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>

			  	<button ng-if="canAccess('store.settings.get.edit.email-template.data')" type="button" ng-click="emailTemplateEditCtrl.resetEmailTemlate()" class="lw-btn btn btn-light btn-sm" title="<?= __tr('Reset') ?>"><?= __tr('Reset') ?></button>

			   	<button ng-if="canAccess('store.settings.get.edit.email-template.data')" type="button" class="lw-btn btn btn-light btn-sm" ui-sref="email_templates" title="<?= __tr('Back') ?>"><?= __tr('Back') ?></button>

		 	</div>
			<!-- /Modal footer -->

			</form>
		</div>
		<!-- / email template view update -->

		<div class=" col-lg-4 col-md-4">
            <!-- replace string list -->
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <?= __tr('Replace Variables') ?>
                    </div>

                    <div class="list-group">
                        <div class="list-group-item"  ng-repeat="variable in emailTemplateEditCtrl.replaceString">

                            <span id="replaceString" data-clipboard-text="[[variable]]" ng-bind="variable" class="lw-copy-action" title="Copy Variable"></span>
                            <a href data-clipboard-text="[[variable]]" class="pull-right lw-copy-action" title="Copy String"><i class="fa fa-clone" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="card">
                    <div class="card-header">
                        <?= __tr('Replace Variable Info') ?>
                    </div>
                    <div class="list-group-item">
                        <span>To add above keys in email template.</span>
                    </div>
                </div>
            </div>
            <!-- / replace string list -->
        </div>
	</div>
</div>