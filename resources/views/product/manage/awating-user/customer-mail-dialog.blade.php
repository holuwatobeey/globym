<div ng-controller="ManageProductNotifyMailController as productNotifyMailCtrl">
    <div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Send Notify Customer Mail' ) ?></h3>
        <!-- /main heading -->
    </div>
  
    <div>
        <form class="lw-form lw-ng-form" 
        name="productNotifyMailCtrl.[[ productNotifyMailCtrl.ngFormName ]]" 
        novalidate>

            <!--  Mail Description  -->
            <lw-form-field field-for="mail_description" v-label="<?= __tr('Mail Description') ?>" label="<?= __tr('Mail Description') ?>"> 
                <textarea name="mail_description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-required="true" ng-model="productNotifyMailCtrl.productNotifyMailData.mail_description"></textarea>
            </lw-form-field>
            <!--  /Mail Description  -->

            <br>
            <!-- button action -->
            <div class="modal-footer">
                <button type="submit" ng-click="productNotifyMailCtrl.submit()" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Send') ?>"><?= __tr('Send') ?> <span></span></button>

                <button type="button" class="lw-btn btn btn-light btn-sm" ng-click="productNotifyMailCtrl.closeDialog()" title="<?= __tr('Not Now') ?>"><?= __tr('Not Now') ?></button>
            </div>
            <!-- /button action -->
        </form>
    </div>
</div>