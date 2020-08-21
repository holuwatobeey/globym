<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Footer Settings') ?></h4>
</div>
<input type="hidden" id="lwTemlateDeleteConfirmTextMsg" data-message="<?= __tr( 'you want to load default template for __title__.') ?>" data-delete-button-text="<?= __tr('Yes, load it') ?>" data-success-text="<?= __tr('Loaded!') ?>">

<div ng-controller="ManageFooterSettingController as manageFooterSettingCtrl">
    
    <!-- form action -->
    <form class="lw-form lw-ng-form" name="manageFooterSettingCtrl.[[ manageFooterSettingCtrl.ngFormName ]]"
        ng-submit="manageFooterSettingCtrl.submit()" novalidate>

        <!-- email_template setting editor-->
        <lw-form-field field-for="public_footer_template" label="<?= __tr( 'Footer Editor' ) ?>" ng-if="manageFooterSettingCtrl.pageStatus == true">
            <textarea 
                name="public_footer_template" 
                id="public_footer_template"
                class="lw-form-field form-control" 
                cols="30" 
                rows="10" 
                ng-minlength="6" 
                ng-model="manageFooterSettingCtrl.editData.public_footer_template" 
                lw-ck-editor
                options="[[ manageFooterSettingCtrl.editorConfig ]]"
                >
            </textarea>
        </lw-form-field>
        <!-- /email_template setting editor-->
        <!-- / Modal Body -->
        <hr>
        <!-- /Modal footer -->
        <div class="float-left">
            <button type="button" ng-click="manageFooterSettingCtrl.defaultEmailTemlate(manageFooterSettingCtrl.footerTemplateData.title)" class="lw-btn btn btn-light btn-sm" title="<?= __tr('Use Default Template') ?>" ng-disabled="manageFooterSettingCtrl.templateDataExist == false"><?= __tr('Use Default Template') ?></button>
        </div>
        
        <div class="float-right">    
            <button type="submit" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>

            <button type="button" ng-click="manageFooterSettingCtrl.resetFooterTemlate()" class="lw-btn btn btn-light btn-sm" title="<?= __tr('Reset') ?>"><?= __tr('Reset') ?></button>
        </div>
        <!-- /Modal footer -->

        
    </form>
    <!-- /form action -->
</div>