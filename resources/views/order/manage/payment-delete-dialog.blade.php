<div ng-controller="ManagePaymentDeleteController as paymentDeleteCtrl">

    <!-- main heading -->
    <div class="lw-text-center">
    <strong><h1><?=  __tr( 'Are You Sure ?' )  ?></h1></strong>
        <h5><?=  __tr( 'You want to Delete __orderUid__ order payment, all the order related to this payment will deleted as well.', [
            '__orderUid__' => '<strong>[[ paymentDeleteCtrl.orderUid ]]</strong>'
        ])  ?></h5>
    </div>
    <!-- /main heading -->
    
     <form class="lw-form lw-ng-form"
        name="paymentDeleteCtrl.[[ paymentDeleteCtrl.ngFormName ]]" 
        ng-submit="paymentDeleteCtrl.submit(paymentDeleteCtrl.categoryID)" 
        novalidate>

        <!-- Password -->
        <lw-form-field field-for="password" label="<?=  __tr('Password')  ?>"> 
            <div class="input-group">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><?=  __tr('Password')  ?></button>
            </span>
                <input type="password" 
                      class="lw-form-field form-control"
                      name="password"
                      ng-minlength="6"
                      ng-maxlength="30"
                      ng-required="true" 
                      ng-model="paymentDeleteCtrl.paymentData.password" />
            </div>
        </lw-form-field>
        <!-- Password -->

        <!-- action button -->
        <div class="lw-form-actions lw-text-center">
            <button type="submit" class="lw-btn btn btn-danger" title="<?= __tr('Delete') ?>"><?= __tr('Yes, Delete it') ?> <span></span></button>
            <button type="button" class="lw-btn btn btn-light" ng-click="paymentDeleteCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /action button -->
    </form>

</div>