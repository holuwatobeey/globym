<div ng-controller="AlertDialogController as alertCtrl">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Are you sure' )  ?></h3>
    </div>
    <!-- /main heading -->

    <div ng-if="alertCtrl.pageStatus">
       <span class="lw-danger" ng-bind="alertCtrl.message"></span>
    </div>
    <!-- action button -->
    <div class="lw-form-actions">

        <button type="submit" 
            class="lw-btn btn btn-primary" 
            ng-click="alertCtrl.yesSubmitIt()" 
            title="<?= __tr('Yes update the changes in order.') ?>"><?= __tr('Yes Update It') ?>
        </button>

        <button 
            type="button" 
            class="lw-btn btn btn-light" 
            ng-click="alertCtrl.closeDialog()" 
            title="<?= __tr('Go Back') ?>"><?= __tr('Go Back') ?>
        </button>
    </div>
    <!-- /action button -->
</div>