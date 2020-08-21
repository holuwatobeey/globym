<div ng-controller="ManageProductEditDetailsController as editDetailsCtrl">
    <div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Send Notify Customer Mail' ) ?></h3>
        <!-- /main heading -->
    </div>
  
    <div>
        <div class="alert alert-info" role="alert">
            <?= __tr('Notify Mail (do you want to send mail to all notify customer)')  ?>
        </div>

        <br>
        <!-- button action -->
        <div class="modal-footer">
            <button type="submit" ui-sref="product_edit.awating_user" ng-click="editDetailsCtrl.closeDialog()" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Yes') ?>"><?= __tr('Yes') ?> <span></span></button>

            <button type="button" class="lw-btn btn btn-light btn-sm" ng-click="editDetailsCtrl.closeDialog()" title="<?= __tr('No') ?>"><?= __tr('No') ?></button>
        </div>
        <!-- /button action -->
    </div>
</div>