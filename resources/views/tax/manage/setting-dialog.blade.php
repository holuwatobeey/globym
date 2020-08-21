<div ng-controller="OrderSettingsController as orderSettingsCtrl" class="lw-dialog" >
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Tax Settings' )  ?></h3>
    </div>
    <!-- /main heading -->
	
    <!-- page Loader -->
    <div class="lw-main-loader lw-show-till-loading" ng-if="orderSettingsCtrl.pageStatus == false">
        <div class="loader"><?=  __tr('Loading...')  ?></div>
    </div>
    <!-- /page Loader -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" ng-if="orderSettingsCtrl.pageStatus"
        name="orderSettingsCtrl.[[ orderSettingsCtrl.ngFormName ]]" 
        ng-submit="orderSettingsCtrl.submit()" 
        novalidate>

        <fieldset class="lw-fieldset-2">
            <legend><?= __tr('Tax') ?></legend>

            <!-- Apply Tax -->
            <lw-form-radio-field field-for="apply_tax_after_before_discount" label="<?= __tr( 'Apply Tax' ) ?>"> 
                <strong><?= __tr('Apply Tax') ?> : </strong>
                <label class="radio-inline">
                    <input ng-model="orderSettingsCtrl.editData.apply_tax_after_before_discount" class="lw-tax-setting-radio-btn" type="radio" name="apply_tax_after_before_discount" ng-value="1" ng-required="true"><?= __tr('Before Discount') ?>
                </label>

                <label class="radio-inline">
                    <input ng-model="orderSettingsCtrl.editData.apply_tax_after_before_discount" type="radio" class="lw-tax-setting-radio-btn" name="apply_tax_after_before_discount" ng-value="2" ng-required="true"><?= __tr('After Discount') ?>
                </label>
            </lw-form-radio-field>
            <!-- /Apply Tax -->

            <!-- Calculate Tax as per -->
            <lw-form-radio-field field-for="calculate_tax_as_per_shipping_billing" label="<?= __tr( 'Calculate Tax as per' ) ?>"> 
                <strong><?= __tr('Calculate Tax as per') ?> : </strong>

                    <label class="radio-inline">
                        <input ng-model="orderSettingsCtrl.editData.calculate_tax_as_per_shipping_billing" type="radio" class="lw-tax-setting-radio-btn" name="calculate_tax_as_per_shipping_billing" ng-value="1" ng-required="true"><?= __tr('Shipping Address') ?>
                    </label>
                    <label class="radio-inline">
                        <input ng-model="orderSettingsCtrl.editData.calculate_tax_as_per_shipping_billing" type="radio" class="lw-tax-setting-radio-btn" name="calculate_tax_as_per_shipping_billing" ng-value="2" ng-required="true"><?= __tr('Billing Address') ?>
                    </label>
            </lw-form-radio-field>
            <!-- /Calculate Tax as per --> 

        </fieldset>

        <!-- action -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?> <span></span></button>

            <button type="button" class="lw-btn btn btn-light" ng-click="orderSettingsCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
        </div>
        <!-- /action -->
    </form>
</div>