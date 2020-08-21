<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Currency Settings') ?></h4>
</div>
<div ng-controller="CurrencySettingsController as currencySettingsCtrl">

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="currencySettingsCtrl.[[ currencySettingsCtrl.ngFormName ]]" 
        novalidate>
		<div>

            <!-- Currency Edit Tabs -->
            <div class="row mt-4" ng-if="currencySettingsCtrl.pageStatus">
                <div class="col-lg-3 col-sm-3 col-md-4 col-12 mb-2">
                    <div class="list-group lw-payment-list-group" id="list-tab" role="tablist">
                        <!-- Currency -->
                        <a class="list-group-item list-group-item-action active" id="currency-list" data-toggle="list" href="#currency" role="tab" aria-controls="currency" title="<?=  __tr('Currency')  ?>" ng-click="currencySettingsCtrl.manageCurrecnyTab($event)"><?= __tr('Currency') ?></a>
                        <!-- /Currency -->

                        <!-- Multi-Currency -->
                        <a class="list-group-item list-group-item-action" id="multiCurrency-list" data-toggle="list" title="<?=  __tr('Multi-Currency')  ?>" href="#multiCurrency" role="tab" aria-controls="multiCurrency" ng-click="currencySettingsCtrl.manageCurrecnyTab($event)"><?= __tr('Multi-Currency') ?></a>
                        <!-- /Multi-Currency -->      
                    </div>
                </div>
                <!-- / Currency Edit Tabs -->

                <div class="col-lg-9 col-sm-9 col-md-8 mt-1">
                   
                    <div class="tab-content" id="nav-tabContent">

                        <!-- Currency Block -->
                        <div class="tab-pane fade show active" id="currency" role="tabpanel" aria-labelledby="currency-list">
                            <div class="form-group">
                                <lw-form-selectize-field field-for="currency" label="<?= __tr( 'Transaction Currency' ) ?>" class="lw-selectize">
                                    <selectize 
                                        config='currencySettingsCtrl.currencies_select_config' 
                                        class="lw-form-field" 
                                        name="currency" 
                                        ng-model="currencySettingsCtrl.editData.currency" 
                                        options='currencySettingsCtrl.currencies_options' 
                                        placeholder="<?= __tr( 'Select Currency' ) ?>" 
                                        ng-required="true"  
                                        ng-change="currencySettingsCtrl.currencyChange(currencySettingsCtrl.editData.currency,
                                        currencySettingsCtrl.editData.paymentOptionList,
                                        currencySettingsCtrl.currencies_options,
                                        currencySettingsCtrl.editData.currency_symbol, currencySettingsCtrl.editData.currency_value
                                        )">
                                    </selectize>
                                </lw-form-selectize-field>
                            </div>
                            <!-- /Currency -->

                            <div class="alert alert-info" ng-if="currencySettingsCtrl.currencyExist">
                                <strong><?= __tr('Please cross check that selected payment gateway support your __baseCurrency__  __currencyCode__ currency.', [
                                        '__baseCurrency__' => '[[currencySettingsCtrl.currenyLabel]]',
                                        '__currencyCode__' => '([[currencySettingsCtrl.editData.currency]])',
                                    ]) ?></strong>
                            </div>
                            
                            <!-- <div ng-hide="currencySettingsCtrl.isPaypalSupport" class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?= __tr( 'Currency may not supported by PayPal' ) ?></div> -->

                            <span class="pull-right"><a href="https://developer.paypal.com/docs/classic/api/currency_codes/" target="__blank" title="<?= __tr('You can see PayPal Supported Currencies more information in detail.') ?>"><?= __tr('PayPal Supported Currencies') ?></a></span><br>

                            <span class="pull-right"><a href="https://support.stripe.com/questions/which-currencies-does-stripe-support " target="__blank" title="<?= __tr('You can see Stripe Supported Currencies more information in detail.') ?>"><?= __tr('Stripe Supported Currencies') ?></a></span><br>
                            
                            <div class="form-row">
                                <div class="col">
                                    <!-- Currency Value -->
                                    <lw-form-field field-for="currency_value" label="<?= __tr( 'Currency Code' ) ?>"> 
                                        <input type="text" 
                                            class="lw-form-field form-control"
                                            name="currency_value"
                                            ng-required="true"
                                            ng-change="currencySettingsCtrl.currencyValueChange( currencySettingsCtrl.editData.currency_value)" 
                                            ng-model="currencySettingsCtrl.editData.currency_value"/>
                                    </lw-form-field>
                                    <!-- Currency Value -->
                                </div>

                                <div class="col">
                                    <!-- Currency Symbol -->
                                    <lw-form-field field-for="currency_symbol" label="<?= __tr( 'Currency Symbol' ) ?>"> 
                                        <div class="input-group">
                                            <input type="text" 
                                              class="lw-form-field form-control"
                                              name="currency_symbol"
                                              ng-required="true" 
                                              ng-model="currencySettingsCtrl.editData.currency_symbol"
                                              ng-change="currencySettingsCtrl.updateCurrencyPreview(currencySettingsCtrl.editData.currency_symbol, currencySettingsCtrl.editData.currency_value)" />                        
                                            <div class="input-group-append">
                                                <span class="input-group-text" data="[[currencySettingsCtrl.editData.currency_symbol]]" ng-bind-html="currencySettingsCtrl.editData.currency_symbol"></span>
                                            </div>
                                        </div>
                                    </lw-form-field>
                                    <!-- Currency Symbol -->
                                </div>
                            </div>

                            <span ng-show="currencySettingsCtrl.isZeroDecimalCurrency">
                                <!-- Round Zero Decimal Currency -->
                                <lw-form-checkbox-field field-for="round_zero_decimal_currency" label="<?= __tr( 'Round Zero Decimal Currency' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="round_zero_decimal_currency"
                                        ng-model="currencySettingsCtrl.editData.round_zero_decimal_currency"
                                        ui-switch="" />
                                </lw-form-checkbox-field>
                                <!-- /Round Zero Decimal Currency -->

                                <div class="alert alert-warning" ng-show="currencySettingsCtrl.editData.round_zero_decimal_currency">
                                    <?= __tr('All the price and amount will be rounded. Eg : 10.25 It will become 10 , 10.57 It will become 11.') ?>
                                </div>

                                <div class="alert alert-danger" ng-show="!currencySettingsCtrl.editData.round_zero_decimal_currency">
                                    <i class="fa fa-exclamation-triangle"></i>  <?= __tr("This currency doesn't support Decimal values it may create error at payment.") ?>
                                </div>

                            </span>

                            <span class="pull-right"><?= __tr('Refer for') ?> <a href="http://goo.gl/zRJRq" target="_blank"><?= __tr('ASCII Codes') ?></a></span>

                            <input type="hidden" id="lwOrderUpdateTextMsg" data-message="<?= __tr( 'Order Payment Update successfully') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" order-status-change="<?= __tr( 'Do you want to change order status also') ?>"><br><br>

                            <div class="alert alert-info">
                                <?= __tr("Do not change contains in the curly braces.") ?>
                            </div>

                            <!-- Currency Format -->
                            <lw-form-field field-for="currency_format" label="<?= __tr( 'Currency Format' ) ?>"> 
                                <div class="input-group">
                                    <input type="text" 
                                        class="lw-form-field form-control"
                                        name="currency_format"
                                        ng-required="true" 
                                        ng-model="currencySettingsCtrl.editData.currency_format"
                                        ng-keyup="currencySettingsCtrl.updateCurrencyPreview(currencySettingsCtrl.currencySymbol, currencySettingsCtrl.editData.currency_value, true)"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text" 
                                            data-format="[[currencySettingsCtrl.editData.currency_format]]"
                                            title="<?= __tr('This is currency format preview, Which is display in publically.') ?>" 
                                            id="lwCurrencyFormat" ng-bind="currencySettingsCtrl.currency_format_preview"></span>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href ng-click="currencySettingsCtrl.useDefaultFormat(currencySettingsCtrl.default_currency_format, currencySettingsCtrl.currencySymbol, currencySettingsCtrl.editData.currency_value)" title="<?= __tr('Reset this format and use default format') ?>"><?= __tr('Use Default') ?></a>
                                            </span>
                                        </div>
                                    </div>
                            </lw-form-field>
                            <!-- Currency Format -->
                        </div>
                        <!-- /Currency Block -->

                        <!-- Multi Currency Block -->
                        <div class="tab-pane fade" id="multiCurrency" role="tabpanel" aria-labelledby="multiCurrency-list">
                            <fieldset class="lw-fieldset-2">
                                <legend class="lw-fieldset-legend-font">
                                    <lw-form-checkbox-field field-for="display_multi_currency" label="<?= __tr( ' Display Multiple Currencies' ) ?>">
                                        <input type="checkbox"
                                            class="lw-form-field js-switch"
                                            name="display_multi_currency"
                                            ng-model="currencySettingsCtrl.editData.display_multi_currency"
                                            ui-switch="" />
                                    </lw-form-checkbox-field>
                                </legend>

                                <!-- Auto Refresh In -->
                                <div ng-show="currencySettingsCtrl.editData.display_multi_currency">
                                    <lw-form-field field-for="auto_refresh_in" label="<?= __tr( 'Auto Refresh In' ) ?>"> 
                                       <select class="form-control" 
                                            name="auto_refresh_in" ng-model="currencySettingsCtrl.editData.auto_refresh_in" ng-options="autoRefreshKey as autoRefreshValue for (autoRefreshKey, autoRefreshValue) in currencySettingsCtrl.autoRefreshList" ng-required="true">
                                           <!--  <option value='' disabled selected><?=  __tr('-- Select Language --')  ?></option> -->
                                        </select> 
                                    </lw-form-field>
                                </div>
                                <!-- /Auto Refresh In-->

                                <div ng-show="currencySettingsCtrl.editData.display_multi_currency">
                                    <div class="alert alert-info mt-3">
                                        <?= __tr('These currencies are only for display purpose and for order processing Base Currency (__baseCurrency__) will be used.', [
                                            '__baseCurrency__' => getCurrency()
                                            ]) ?>
                                    </div>  
                                    <div class="alert alert-info">
                                        <?= __tr('Currency conversion powered by __fixerUrl__', [
                                                '__fixerUrl__' => '<a href="http://exchangeratesapi.io/" target="__blank">Exchangeratesapi.io</a>'
                                            ]) ?>
                                    </div>  
                              
                                    <!-- Multi-currency -->
                                    <lw-form-selectize-field field-for="multi_currencies" label="<?= __tr( 'Multi Currency' ) ?>" class="lw-selectize" ng-if="currencySettingsCtrl.pageStatus == true">
                                        <selectize config='currencySettingsCtrl.multi_currencies_select_config' class="lw-form-field" name="multi_currencies" ng-model="currencySettingsCtrl.editData.multi_currencies" options='currencySettingsCtrl.multi_curency_list' ng-required="currencySettingsCtrl.editData.display_multi_currency" placeholder="<?= __tr( 'Select Currency' ) ?>"></selectize>
                                    </lw-form-selectize-field><br>
                                    <!-- /Multi-currency -->

                                    <!-- Currency Markup -->
                                    <lw-form-field field-for="currency_markup" label="<?= __tr( 'Currency Markup' ) ?>">
                                        <div class="input-group">
                                            <input type="number" 
                                              class="lw-form-field form-control"
                                              name="currency_markup"
                                              min="0"
                                              ng-model="currencySettingsCtrl.editData.currency_markup"
                                              />
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </lw-form-field>
                                    <!-- Currency Markup -->
                                </div>
                            </fieldset>
                        </div>
                        <!-- /Multi Currency Block -->
                        
                    </div>
                    <br>

                    <div class="modal-footer">
                        <button type="submit" ng-click="currencySettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
                            <?= __tr('Update') ?><span></span> 
                        </button>
                        <!-- <button href="" class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /Product Edit Tabs -->
 
	</form>
	<!-- /form action -->
</div>