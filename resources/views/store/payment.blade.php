<div class="lw-section-heading-block"> 
    <h4 class="lw-header"><span><?= __tr('Payment Settings') ?></h4>
</div>
<div ng-controller="PaymentSettingsController as paymentSettingsCtrl">

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="paymentSettingsCtrl.[[ paymentSettingsCtrl.ngFormName ]]" 
        novalidate>

        <!-- Payments Fieldset -->
        <div ng-if="paymentSettingsCtrl.pageStatus">
            <div class="row">
                <div class="col-lg-3 col-sm-3 col-md-4 col-12 mb-2">
                    <div class="list-group lw-payment-list-group" id="list-tab" role="tablist">
                        <!-- Paypal -->
                        <a class="list-group-item list-group-item-action active" id="paypal-list" data-toggle="list" href="#paypal" role="tab" aria-controls="paypal" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Paypal') ?></a>
                        <!-- /Paypal -->

                        <!-- Stripe -->
                        <a class="list-group-item list-group-item-action" id="stripe-list" data-toggle="list" href="#stripe" role="tab" aria-controls="stripe" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Stripe') ?></a>
                        <!-- /Stripe -->

                        <!-- Paytm -->
                        <a class="list-group-item list-group-item-action" id="paytm-list" data-toggle="list" href="#paytm" role="tab" aria-controls="paytm" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Paytm') ?></a>
                        <!-- /Paytm -->

                        <!-- Razorpay -->
                        <a class="list-group-item list-group-item-action" id="razorpay-list" data-toggle="list" href="#razorpay" role="tab" aria-controls="razorpay" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Razorpay') ?></a>
                        <!-- /Razorpay -->

                        <!-- Paystack -->
                        <a class="list-group-item list-group-item-action" id="paystack-list" data-toggle="list" href="#paystack" role="tab" aria-controls="paystack" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Paystack') ?></a>
                        <!-- /Paystack -->

                        <!-- Iyzico -->
                        <a class="list-group-item list-group-item-action" id="iyzico-list" data-toggle="list" href="#iyzico" role="tab" aria-controls="iyzico" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Iyzico') ?></a>
                        <!-- /Iyzico -->

                        <!-- Instamojo -->
                        <a class="list-group-item list-group-item-action" id="instamojo-list" data-toggle="list" href="#instamojo" role="tab" aria-controls="instamojo" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Instamojo') ?></a>
                        <!-- /Instamojo -->

                        <!-- COD -->
                        <a class="list-group-item list-group-item-action" id="cod-list" data-toggle="list" href="#cod" role="tab" aria-controls="cod" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('COD') ?></a>
                        <!-- /COD -->

                        <!-- Check -->
                        <a class="list-group-item list-group-item-action" id="check-list" data-toggle="list" href="#check" role="tab" aria-controls="check" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Check') ?></a>
                        <!-- /Check -->

                        <!-- Bank Transfer -->
                        <a class="list-group-item list-group-item-action" id="bank-transfer-list" data-toggle="list" href="#bank-transfer" role="tab" aria-controls="bank-transfer" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Bank Transfer') ?></a>
                        <!-- /Bank Transfer -->

                        <!-- Other -->
                        <a class="list-group-item list-group-item-action" id="other-list" data-toggle="list" href="#other" role="tab" aria-controls="other" ng-click="paymentSettingsCtrl.showPaymentPageTab($event)"><?= __tr('Other') ?></a>
                        <!-- /Other -->
                    </div>
                </div>
                <div class="col-lg-9 col-sm-9 col-md-8 mt-1">
                    <div class="tab-content" id="nav-tabContent">
                        <!-- Paypal Start here -->
                        <div class="tab-pane fade show active" id="paypal" role="tabpanel" aria-labelledby="paypal-list">
                            <lw-form-radio-field field-for="use_paypal" label="<?= __tr('Use Paypal' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">  
                                        <img class="lw-payment-gateway-icon" alt="<?= __tr( 'PayPal' ) ?>" src="<?= url('resources/assets/imgs/payment/paypal-small.png') ?>">
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_paypal"
                                            name="enable_paypal"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_paypal, 1)"
                                            ng-model="paymentSettingsCtrl.enable_paypal">
                                        <label class="custom-control-label" for="enable_paypal"><?= __tr('Enable Paypal') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_paypal}">
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">          
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_paypal" 
                                                        id="use_paypal_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_paypal" ng-value="true"> <label for="use_paypal_testing_[[$index]]"><?= __tr('Use Testing') ?>
                                                        </label>
                                                </legend>

                                                <!-- show after added live paypal information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingPaypalKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing PayPal Sandbox Email are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPaypalKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live paypal information -->

                                                <!-- Paypal Sandbox Email -->
                                                <lw-form-field field-for="paypal_sandbox_email" label="<?= __tr( 'PayPal Sandbox Email (Testing) ' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingPaypalKeysExist"> 
                                                    <input type="email" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.paypal_sandbox_email_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="paypal_sandbox_email"
                                                          ng-required="paymentSettingsCtrl.editData.use_paypal && paymentSettingsCtrl.enable_paypal" 
                                                          ng-model="paymentSettingsCtrl.editData.paypal_sandbox_email"/>
                                                </lw-form-field>
                                                <!-- /Paypal Sandbox Email -->

                                            </fieldset>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">      
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_paypal" 
                                                        id="use_paypal_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_paypal" ng-value="false"> <label for="use_paypal_live_[[$index]]"><?= __tr('Use Live') ?>
                                                        </label>
                                                </legend>

                                                <!-- show after added live paypal information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLivePaypalKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live PayPal Email are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPaypalKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live paypal information -->

                                                <!-- Paypal Email -->
                                                <lw-form-field field-for="paypal_email" label="<?= __tr( 'PayPal Email' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLivePaypalKeysExist"> 
                                                    <input type="email" 
                                                        placeholder="[[ paymentSettingsCtrl.editData.paypal_email_placeholder ]]" 
                                                        class="lw-form-field form-control"
                                                        name="paypal_email"
                                                        ng-required="!paymentSettingsCtrl.editData.use_paypal && paymentSettingsCtrl.enable_paypal" 
                                                        ng-model="paymentSettingsCtrl.editData.paypal_email"/>
                                                </lw-form-field>
                                                <!-- Paypal Email -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Paypal end here -->
                        <!-- Stripe Start here -->
                        <div class="tab-pane fade" id="stripe" role="tabpanel" aria-labelledby="stripe-list">
                            <lw-form-radio-field field-for="use_stripe" label="<?= __tr('Use Stripe' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">  
                                        <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Use Stripe' ) ?>" src="<?= url('resources/assets/imgs/payment/stripe-small.png') ?>">
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_stripe"
                                            name="enable_stripe"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_stripe, 6)"
                                            ng-model="paymentSettingsCtrl.enable_stripe">
                                        <label class="custom-control-label" for="enable_stripe"><?= __tr('Enable Stripe') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_stripe}">
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">           
                                                    <!-- Use stripe -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_stripe"
                                                        id="use_stripe_testing[[$index]]" 
                                                        ng-model="paymentSettingsCtrl.editData.use_stripe" ng-value="true"> 
                                                        <label for="use_stripe_testing[[$index]]"><?= __tr('Use Testing') ?></label>
                                                    <!-- /Use stripe -->
                                                </legend>

                                                <!-- show after added testing stripe information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingStripeKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Stripe keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addStripeKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing stripe information -->

                                                <!-- Testing Secret Live Key -->
                                                <lw-form-field field-for="stripe_testing_secret_key" label="<?= __tr( 'Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingStripeKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.stripe_testing_secret_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="stripe_testing_secret_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_stripe && paymentSettingsCtrl.enable_stripe" 
                                                          ng-model="paymentSettingsCtrl.editData.stripe_testing_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Testing Stripe Live Key -->

                                                <!-- Testing Publishable Key -->
                                                <lw-form-field field-for="stripe_testing_publishable_key" label="<?= __tr( 'Publishable Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingStripeKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.stripe_testing_publishable_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="stripe_testing_publishable_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_stripe && paymentSettingsCtrl.enable_stripe" 
                                                          ng-model="paymentSettingsCtrl.editData.stripe_testing_publishable_key"/>
                                                </lw-form-field>
                                                <!-- /Testing Publishable Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">    
                                                    <!-- Use stripe -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_stripe" 
                                                        id="use_stripe_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_stripe" ng-value="false"> <label for="use_stripe_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Use stripe -->
                                                </legend>

                                                <!-- show after added live stripe information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLiveStripeKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live Stripe keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addStripeKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added live stripe information -->

                                                <!-- Stripe Live Key -->
                                                <lw-form-field field-for="stripe_live_secret_key" label="<?= __tr( 'Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveStripeKeysExist"> 
                                                    <input type="text" 
                                                        placeholder="[[ paymentSettingsCtrl.editData.stripe_live_secret_key_placeholder ]]" 
                                                        class="lw-form-field form-control"
                                                        name="stripe_live_secret_key"
                                                        ng-required="!paymentSettingsCtrl.editData.use_stripe && paymentSettingsCtrl.enable_stripe" 
                                                        ng-model="paymentSettingsCtrl.editData.stripe_live_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Stripe Live Key -->

                                                <!-- Publishable Key -->
                                                <lw-form-field field-for="stripe_live_publishable_key" label="<?= __tr( 'Publishable Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveStripeKeysExist"> 
                                                    <input type="text" 
                                                        placeholder="[[ paymentSettingsCtrl.editData.stripe_live_publishable_key_placeholder ]]"
                                                        class="lw-form-field form-control"
                                                        name="stripe_live_publishable_key"
                                                        ng-required="!paymentSettingsCtrl.editData.use_stripe && paymentSettingsCtrl.enable_stripe" 
                                                        ng-model="paymentSettingsCtrl.editData.stripe_live_publishable_key"/>
                                                </lw-form-field>
                                                <!-- /Publishable Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Stripe end here -->
                        <!-- Paytm Start here -->
                        <div class="tab-pane fade" id="paytm" role="tabpanel" aria-labelledby="paytm-list">
                            <lw-form-radio-field field-for="use_paytm" label="<?= __tr('Use Paytm' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">  
                                        <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Paytm Payment (Indian payment gateway)' ) ?>" src="<?= url('resources/assets/imgs/payment/paytm-small.png') ?>"> <?= __tr( ' (Indian payment gateway)' ) ?>
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_paytm"
                                            name="enable_paytm"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_paytm, 16)"
                                            ng-model="paymentSettingsCtrl.enable_paytm">
                                        <label class="custom-control-label" for="enable_paytm"><?= __tr('Enable Paytm') ?></label>
                                    </div> 

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_paytm}">
                                        <div class="mt-3">                                      
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">      
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_paytm" 
                                                        id="use_paytm_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_paytm" ng-value="true"> <label for="use_paytm_testing_[[$index]]"><?= __tr('Use Testing') ?></label>
                                                </legend>

                                                <!-- show after added testing Paytm information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingPaytmKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Paytm keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPaytmKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing Paytm information -->

                                                <!-- Paytm Key -->
                                                <lw-form-field field-for="paytm_testing_merchant_key" label="<?= __tr( 'Paytm Merchant Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingPaytmKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.paytm_testing_merchant_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="paytm_testing_merchant_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_paytm && paymentSettingsCtrl.enable_paytm"
                                                          ng-model="paymentSettingsCtrl.editData.paytm_testing_merchant_key"/>
                                                </lw-form-field>
                                                <!--/ Paytm Key -->

                                                <!-- Paytm Secret Key -->
                                                <lw-form-field field-for="paytm_testing_merchant_mid_key" label="<?= __tr( 'Paytm Merchant Mid Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingPaytmKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.paytm_testing_merchant_mid_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="paytm_testing_merchant_mid_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_paytm && paymentSettingsCtrl.enable_paytm"
                                                          ng-model="paymentSettingsCtrl.editData.paytm_testing_merchant_mid_key"/>
                                                </lw-form-field>
                                                <!-- /Paytm Secret Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">     
                                                    <!-- Use razorpay -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_paytm" 
                                                        id="use_paytm_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_paytm" ng-value="false"> <label for="use_paytm_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Use razorpay -->
                                                </legend>

                                                <!-- show after added live paytm information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLivePaytmKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live Paytm keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPaytmKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live paytm information -->

                                                <!-- Paytm Key -->
                                                <lw-form-field field-for="paytm_live_merchant_key" label="<?= __tr( 'Paytm Merchant Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLivePaytmKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.paytm_live_merchant_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="paytm_live_merchant_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_paytm && paymentSettingsCtrl.enable_paytm"
                                                          ng-model="paymentSettingsCtrl.editData.paytm_live_merchant_key"/>
                                                </lw-form-field>
                                                <!--/ Paytm Key -->

                                                <!-- Paytm Secret Key -->
                                                <lw-form-field field-for="paytm_live_merchant_mid_key" label="<?= __tr( 'Paytm Merchant Mid Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLivePaytmKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.paytm_live_merchant_mid_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="paytm_live_merchant_mid_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_paytm && paymentSettingsCtrl.enable_paytm"
                                                          ng-model="paymentSettingsCtrl.editData.paytm_live_merchant_mid_key"/>
                                                </lw-form-field>
                                                <!-- /Paytm Secret Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Paytm end here -->
                        <!-- Razorpay Start here -->
                        <div class="tab-pane fade" id="razorpay" role="tabpanel" aria-labelledby="razorpay-list">
                            <lw-form-radio-field field-for="use_razorpay" label="<?= __tr('Use Razorpay' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">  
                                        <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Razorpay Payment (Indian payment gateway)' ) ?>" src="<?= url('resources/assets/imgs/payment/razorpay-small.png') ?>"> <?= __tr( ' (Indian payment gateway)' ) ?>
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_razorpay"
                                            name="enable_razorpay"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_razorpay, 11)"
                                            ng-model="paymentSettingsCtrl.enable_razorpay">
                                        <label class="custom-control-label" for="enable_razorpay"><?= __tr('Enable Razorpay') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_razorpay}">
                                        <div class="mt-3">  
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">          
                                                    <!-- Use razorpay -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_razorpay" 
                                                        id="use_razorpay_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_razorpay" ng-value="true"> <label for="use_razorpay_testing_[[$index]]"><?= __tr('Use Testing') ?></label>
                                                    <!-- /Use razorpay -->
                                                </legend>

                                                <!-- show after added testing stripe information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingRazorPayKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Razorpay keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addRazorpayKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing stripe information -->

                                                <!-- Razorpay Key -->
                                                <lw-form-field field-for="razorpay_testing_key" label="<?= __tr( 'Razorpay Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingRazorPayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.razorpay_testing_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="razorpay_testing_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_razorpay && paymentSettingsCtrl.enable_razorpay"
                                                          ng-model="paymentSettingsCtrl.editData.razorpay_testing_key"/>
                                                </lw-form-field>
                                                <!--/ Razorpay Key -->

                                                <!-- Razorpay Secret Key -->
                                                <lw-form-field field-for="razorpay_testing_secret_key" label="<?= __tr( 'Razorpay Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingRazorPayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.razorpay_testing_secret_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="razorpay_testing_secret_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_razorpay && paymentSettingsCtrl.enable_razorpay"
                                                          ng-model="paymentSettingsCtrl.editData.razorpay_testing_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Razorpay Secret Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">    
                                                    <!-- Use razorpay -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_razorpay" 
                                                        id="use_razorpay_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_razorpay" ng-value="false"> <label for="use_razorpay_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Use razorpay -->
                                                </legend>

                                                <!-- show after added live razorpay information -->
                                                <div class="btn-group" ng-if=" paymentSettingsCtrl.editData.isLiveRazorPayKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live Razorpay keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addRazorpayKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live razorpay information -->

                                                <!-- Razorpay Key -->
                                                <lw-form-field field-for="razorpay_live_key" label="<?= __tr( 'Razorpay Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveRazorPayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.razorpay_live_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="razorpay_live_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_razorpay && paymentSettingsCtrl.enable_razorpay"
                                                          ng-model="paymentSettingsCtrl.editData.razorpay_live_key"/>
                                                </lw-form-field>
                                                <!--/ Razorpay Key -->

                                                <!-- Razorpay Secret Key -->
                                                <lw-form-field field-for="razorpay_live_secret_key" label="<?= __tr( 'Razorpay Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveRazorPayKeysExist"> 
                                                    <input type="text" 
                                                        placeholder="[[ paymentSettingsCtrl.editData.razorpay_live_secret_key_placeholder ]]"
                                                        class="lw-form-field form-control"
                                                        name="razorpay_live_secret_key"
                                                        ng-required="!paymentSettingsCtrl.editData.use_razorpay && paymentSettingsCtrl.enable_razorpay"
                                                        ng-model="paymentSettingsCtrl.editData.razorpay_live_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Razorpay Secret Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Razorpay end here -->
                        <!-- Paystack Start here -->
                        <div class="tab-pane fade" id="paystack" role="tabpanel" aria-labelledby="paystack-list">
                            <lw-form-radio-field field-for="use_payStack" label="<?= __tr('Use Paystack' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">
                                       <img class="lw-payment-gateway-icon" alt="Paystack" src="<?= url('resources/assets/imgs/payment/paystack-small.jpg') ?>">
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_paystack"
                                            name="enable_paystack"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_paystack, 20)"
                                            ng-model="paymentSettingsCtrl.enable_paystack">
                                        <label class="custom-control-label" for="enable_paystack"><?= __tr('Enable Paystack') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_paystack}">
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_payStack" 
                                                        id="use_payStack_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_payStack" ng-value="true"> <label for="use_payStack_testing_[[$index]]"><?= __tr('Use Testing') ?></label>
                                                </legend>

                                                <!-- show after added testing Paystack information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingPayStackKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Paystack keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPayStackKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing Paystack information -->

                                                <!-- Paystack Secret Key -->
                                                <lw-form-field field-for="payStack_testing_secret_key" label="<?= __tr( 'Paystack Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingPayStackKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.payStack_testing_secret_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="payStack_testing_secret_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_payStack && paymentSettingsCtrl.enable_paystack"
                                                          ng-model="paymentSettingsCtrl.editData.payStack_testing_secret_key"/>
                                                </lw-form-field>
                                                <!--/ Paystack Secret Key -->

                                                <!-- Paystack Public Key -->
                                                <lw-form-field field-for="payStack_testing_public_key" label="<?= __tr( 'Paystack Public key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingPayStackKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.payStack_testing_public_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="payStack_testing_public_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_payStack && paymentSettingsCtrl.enable_paystack"
                                                          ng-model="paymentSettingsCtrl.editData.payStack_testing_public_key"/>
                                                </lw-form-field>
                                                <!-- /Paystack Public Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">    
                                                    <!-- Paystack Money Live -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_payStack" 
                                                        id="use_payStack_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_payStack" ng-value="false"> <label for="use_payStack_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Paystack Money Live -->
                                                </legend>

                                                <!-- show after added live Paystack information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLivePayStackKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live Paystack keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addPayStackKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live Paystack information -->

                                                <!-- Paystack Secret -->
                                                <lw-form-field field-for="payStack_live_secret_key" label="<?= __tr( 'Paystack Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLivePayStackKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.payStack_live_secret_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="payStack_live_secret_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_payStack && paymentSettingsCtrl.enable_paystack"
                                                          ng-model="paymentSettingsCtrl.editData.payStack_live_secret_key"/>
                                                </lw-form-field>
                                                <!--/ Paystack Secret -->

                                                <!-- Paystack Public Key -->
                                                <lw-form-field field-for="payStack_live_public_key" label="<?= __tr( 'Paystack Public Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLivePayStackKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.payStack_live_public_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="payStack_live_public_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_payStack && paymentSettingsCtrl.enable_paystack"
                                                          ng-model="paymentSettingsCtrl.editData.payStack_live_public_key"/>
                                                </lw-form-field>
                                                <!-- /Paystack Second Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Paystack end here -->
                        <!-- Iyzico Start here -->
                        <div class="tab-pane fade" id="iyzico" role="tabpanel" aria-labelledby="iyzico-list">
                            <lw-form-radio-field field-for="use_iyzipay" label="<?= __tr('Use Iyzico' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">  
                                        <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Iyzico Payment' ) ?>" src="<?= url('resources/assets/imgs/payment/iyzico-small.png') ?>">
                                    </legend>

                                    <div class="custom-control custom-checkbox m-2">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_iyzico"
                                            name="enable_iyzico"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_iyzico, 14)"
                                            ng-model="paymentSettingsCtrl.enable_iyzico">
                                        <label class="custom-control-label" for="enable_iyzico"><?= __tr('Enable Iyzico') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_iyzico}">
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_iyzipay" 
                                                        id="use_iyzipay_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_iyzipay" ng-value="true"> <label for="use_iyzipay_testing_[[$index]]"><?= __tr('Use Testing') ?></label>
                                                </legend>

                                                <!-- show after added testing Iyzipay information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingIyzipayKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Iyzico keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addIyzipayKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing Iyzipay information -->

                                                <!-- Iyzico Key -->
                                                <lw-form-field field-for="iyzipay_testing_key" label="<?= __tr( 'Iyzico Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingIyzipayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.iyzipay_testing_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="iyzipay_testing_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_iyzipay && paymentSettingsCtrl.enable_iyzico"
                                                          ng-model="paymentSettingsCtrl.editData.iyzipay_testing_key"/>
                                                </lw-form-field>
                                                <!--/ Iyzico Key -->

                                                <!-- Iyzico Secret Key -->
                                                <lw-form-field field-for="iyzipay_testing_secret_key" label="<?= __tr( 'Iyzico Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingIyzipayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.iyzipay_testing_secret_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="iyzipay_testing_secret_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_iyzipay && paymentSettingsCtrl.enable_iyzico"
                                                          ng-model="paymentSettingsCtrl.editData.iyzipay_testing_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Iyzico Secret Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">         
                                                    <!-- Use razorpay -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_iyzipay" 
                                                        id="use_iyzipay_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_iyzipay" ng-value="false"> <label for="use_iyzipay_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Use razorpay -->
                                                </legend>

                                                <!-- show after added live iyzipay information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLiveIyzipayKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live iyzico keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addIyzipayKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live iyzipay information -->

                                                <!-- Iyzico Key -->
                                                <lw-form-field field-for="iyzipay_live_key" label="<?= __tr( 'Iyzico Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveIyzipayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.iyzipay_live_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="iyzipay_live_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_iyzipay && paymentSettingsCtrl.enable_iyzico"
                                                          ng-model="paymentSettingsCtrl.editData.iyzipay_live_key"/>
                                                </lw-form-field>
                                                <!--/ Iyzico Key -->

                                                <!-- Iyzico Secret Key -->
                                                <lw-form-field field-for="iyzipay_live_secret_key" label="<?= __tr( 'Iyzico Secret Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveIyzipayKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.iyzipay_live_secret_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="iyzipay_live_secret_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_iyzipay && paymentSettingsCtrl.enable_iyzico"
                                                          ng-model="paymentSettingsCtrl.editData.iyzipay_live_secret_key"/>
                                                </lw-form-field>
                                                <!-- /Iyzico Secret Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Iyzico end here -->
                        <!-- Instamojo Start here -->
                        <div class="tab-pane fade" id="instamojo" role="tabpanel" aria-labelledby="instamojo-list">
                            <lw-form-radio-field field-for="use_paytm" label="<?= __tr('Use Instamojo' ) ?>">
                                <fieldset class="lw-fieldset-2">
                                    <legend class="lw-fieldset-legend-font">
                                       <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Indian payment gateway' ) ?>" src="<?= url('resources/assets/imgs/payment/instamojo-small.png') ?>"> <?= __tr( '(Indian payment gateway)' ) ?>
                                    </legend>

                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" 
                                            class="custom-control-input" 
                                            id="enable_instamojo"
                                            name="enable_instamojo"
                                            ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_instamojo, 18)"
                                            ng-model="paymentSettingsCtrl.enable_instamojo">
                                        <label class="custom-control-label" for="enable_instamojo"><?= __tr('Enable Instamojo') ?></label>
                                    </div>

                                    <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_instamojo}">
                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">       
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_instamojo" 
                                                        id="use_instamojo_testing_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_instamojo" ng-value="true"> <label for="use_instamojo_testing_[[$index]]"><?= __tr('Use Testing') ?></label>
                                                </legend>

                                                <!-- show after added testing Instamojo information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isTestingInstamojoKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Testing Instamojo keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addInstamojoKeys(2)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>
                                                <!-- show after added testing Instamojo information -->

                                                <!-- Instamojo Key -->
                                                <lw-form-field field-for="instamojo_testing_api_key" label="<?= __tr( 'Instamojo Api Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingInstamojoKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.instamojo_testing_api_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="instamojo_testing_api_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_instamojo && paymentSettingsCtrl.enable_instamojo"
                                                          ng-model="paymentSettingsCtrl.editData.instamojo_testing_api_key"/>
                                                </lw-form-field>
                                                <!--/ Instamojo Key -->

                                                <!-- Instamojo Secret Key -->
                                                <lw-form-field field-for="instamojo_testing_auth_token_key" label="<?= __tr( 'Instamojo Auth Token Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isTestingInstamojoKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.instamojo_testing_auth_token_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="instamojo_testing_auth_token_key"
                                                          ng-required="paymentSettingsCtrl.editData.use_instamojo && paymentSettingsCtrl.enable_instamojo"
                                                          ng-model="paymentSettingsCtrl.editData.instamojo_testing_auth_token_key"/>
                                                </lw-form-field>
                                                <!-- /Instamojo Secret Key -->
                                            </fieldset>
                                        </div>

                                        <div class="mt-3">
                                            <fieldset class="lw-fieldset-2">
                                                <legend class="lw-fieldset-legend-font">     
                                                    <!-- Use Instamojo -->
                                                    <input type="radio" class="lw-form-field" 
                                                        name="use_instamojo" 
                                                        id="use_instamojo_live_[[$index]]"
                                                        ng-model="paymentSettingsCtrl.editData.use_instamojo" ng-value="false"> <label for="use_instamojo_live_[[$index]]"><?= __tr('Use Live') ?></label>
                                                    <!-- /Use Instamojo -->
                                                </legend>

                                                <!-- show after added live instamojo information -->
                                                <div class="btn-group" ng-if="paymentSettingsCtrl.editData.isLiveInstamojoKeysExist">
                                                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Live Instamojo keys are installed.') ?></button>
                                                  <button type="button" ng-click="paymentSettingsCtrl.addInstamojoKeys(1)" class="btn btn-light lw-btn"><?= __tr('Update') ?></button>
                                                </div>  
                                                <!-- show after added live Instamojo information -->

                                                <!-- Instamojo Key -->
                                                <lw-form-field field-for="instamojo_live_api_key" label="<?= __tr( 'Instamojo Api Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveInstamojoKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.instamojo_live_api_key_placeholder ]]" 
                                                          class="lw-form-field form-control"
                                                          name="instamojo_live_api_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_instamojo && paymentSettingsCtrl.enable_instamojo"
                                                          ng-model="paymentSettingsCtrl.editData.instamojo_live_api_key"/>
                                                </lw-form-field>
                                                <!--/ Instamojo Key -->

                                                <!-- Instamojo Secret Key -->
                                                <lw-form-field field-for="instamojo_live_auth_token_key" label="<?= __tr( 'Instamojo Auth Token Key' ) ?>" ng-if="!paymentSettingsCtrl.editData.isLiveInstamojoKeysExist"> 
                                                    <input type="text" 
                                                          placeholder="[[ paymentSettingsCtrl.editData.instamojo_live_auth_token_key_placeholder ]]"
                                                          class="lw-form-field form-control"
                                                          name="instamojo_live_auth_token_key"
                                                          ng-required="!paymentSettingsCtrl.editData.use_instamojo && paymentSettingsCtrl.enable_instamojo"
                                                          ng-model="paymentSettingsCtrl.editData.instamojo_live_auth_token_key"/>
                                                </lw-form-field>
                                                <!-- /Instamojo Secret Key -->
                                            </fieldset>
                                        </div>
                                    </span>
                                </fieldset>
                            </lw-form-radio-field>
                        </div>
                        <!-- /Instamojo end here -->
                        <!-- Cod Start here -->
                        <div class="tab-pane fade" id="cod" role="tabpanel" aria-labelledby="cod-list">
                            <fieldset class="lw-fieldset-2">
                                <legend class="lw-fieldset-legend-font">
                                    <?= __tr('COD Payment Information' ) ?>
                                </legend>

                                <div class="custom-control custom-checkbox m-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="enable_cod"
                                        name="enable_cod"
                                        ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_cod, 4)"
                                        ng-model="paymentSettingsCtrl.enable_cod">
                                    <label class="custom-control-label" for="enable_cod"><?= __tr('Enable COD') ?></label>
                                </div>

                                <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_cod}">
                                    <lw-form-field field-for="payment_cod_text" v-label="<?= __tr( 'COD Payment Information' ) ?>"> 
                                        <textarea name="payment_cod_text" class="lw-form-field form-control" ng-required="true && paymentSettingsCtrl.enable_cod" cols="30" rows="10" ng-model="paymentSettingsCtrl.editData.payment_cod_text" lw-ck-editor></textarea>
                                    </lw-form-field>
                                 </span>
                            </fieldset>
                        </div>
                        <!-- /Cod end here -->
                        <!-- Check Start here -->
                        <div class="tab-pane fade" id="check" role="tabpanel" aria-labelledby="check-list">
                            <fieldset class="lw-fieldset-2">
                                <legend class="lw-fieldset-legend-font">
                                    <?= __tr('Check (Cheque) Payment' ) ?>
                                </legend>

                                <div class="custom-control custom-checkbox m-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="enable_check"
                                        name="enable_check"
                                        ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_check, 2)"
                                        ng-model="paymentSettingsCtrl.enable_check">
                                    <label class="custom-control-label" for="enable_check"><?= __tr('Enable Check Payment') ?></label>
                                </div>

                                <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_check}">
                                    <lw-form-field field-for="payment_check_text" v-label="<?= __tr( 'Check (Cheque) Payment Information' ) ?>"> 
                                        <textarea name="payment_check_text" class="lw-form-field form-control" ng-required="true && paymentSettingsCtrl.enable_check" cols="30" rows="10" ng-model="paymentSettingsCtrl.editData.payment_check_text" lw-ck-editor></textarea>
                                    </lw-form-field>
                                 </span>
                            </fieldset>
                        </div>
                        <!-- /Check end here -->
                        <!-- Bank Transfer Start here -->
                        <div class="tab-pane fade" id="bank-transfer" role="tabpanel" aria-labelledby="bank-transfer-list">
                            <fieldset class="lw-fieldset-2">
                                <legend class="lw-fieldset-legend-font">
                                    <?= __tr('Bank Payment Information' ) ?>
                                </legend>

                                <div class="custom-control custom-checkbox m-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="enable_bank"
                                        name="enable_bank"
                                        ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_bank, 3)"
                                        ng-model="paymentSettingsCtrl.enable_bank">
                                    <label class="custom-control-label" for="enable_bank"><?= __tr('Enable Bank Payment') ?></label>
                                </div>

                                <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_bank}">
                                    <lw-form-field field-for="payment_bank_text" v-label="<?= __tr( 'Bank Payment Information' ) ?>"> 
                                        <textarea name="payment_bank_text" class="lw-form-field form-control" ng-required="true && paymentSettingsCtrl.enable_bank" cols="30" rows="10" ng-model="paymentSettingsCtrl.editData.payment_bank_text" lw-ck-editor></textarea>
                                    </lw-form-field>
                                 </span>
                            </fieldset>
                        </div>
                        <!-- /Bank Transfer end here -->
                        <!-- Other Start here -->
                        <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-list">
                            <fieldset class="lw-fieldset-2">
                                <legend class="lw-fieldset-legend-font">
                                    <?= __tr('Other Payment Information' ) ?>
                                </legend>

                                <div class="custom-control custom-checkbox m-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="enable_other"
                                        name="enable_other"
                                        ng-change="paymentSettingsCtrl.changePaymentSetting(paymentSettingsCtrl.enable_other, 5)"
                                        ng-model="paymentSettingsCtrl.enable_other">
                                    <label class="custom-control-label" for="enable_other"><?= __tr('Enable Other Payment') ?></label>
                                </div>

                                <span ng-class="{'lw-disabled-block-content': !paymentSettingsCtrl.enable_other}">
                                    <lw-form-field field-for="payment_other_text" v-label="<?= __tr( 'Other Order Payment Information' ) ?>"> 
                                        <textarea name="payment_other_text" class="lw-form-field form-control" ng-required="true && paymentSettingsCtrl.enable_other" cols="30" rows="10" ng-model="paymentSettingsCtrl.editData.payment_other_text" lw-ck-editor></textarea>
                                     </lw-form-field>
                                 </span>
                            </fieldset>
                        </div>
                        <!-- /Other end here -->
                    </div>
                </div>
            </div>
        </div>
        <!-- / Payments Fieldset -->
        <br>
        <div class="modal-footer">
            <button type="submit" ng-click="paymentSettingsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
                <?= __tr('Update') ?><span></span> 
            </button>
           <!--  <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
        </div>

    </form>
    <!-- /form action -->

</div>