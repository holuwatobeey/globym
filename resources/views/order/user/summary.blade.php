<?= __yesset('dist/css/vendorlibs-smartwizard*.css', true) ?>

<div ng-controller="OrderSummaryController as CartOrderCtrl">

    <div class="container">

        {{--  heading section --}}
    	<div class="lw-section-heading-block">
            {{-- main heading --}}
            <h3 class="lw-section-heading">
            	@section('page-title',  __tr('Order Summary - Complete your order') ) <?=  __tr('Order Summary -')  ?> <small><?=  __tr(' Complete your order')  ?></small>
            </h3>
            {{-- /main heading --}}
        </div>
        {{-- / heading section --}}

        {{-- Validation message element --}}
        <input type="hidden" id="lwValidationMsg" data-message="<?= __tr( 'The __validationMessage__ field is required.') ?>">
        <input type="hidden" id="lwValidationCustomMsg" data-message="<?= __tr( '__validationMessage__') ?>">
        {{-- / Validation message element --}}

        <div class="row">

            <div class="col-md-4 order-md-2 mb-4">
                @include('order.user.checkout-cart')
            </div>

            <div class="col-md-8 order-md-1">
                    

                {{-- main container --}}
                <div ng-show="CartOrderCtrl.pageStatus == true">

                    <form class="lw-form lw-ng-form" 
                    name="CartOrderCtrl.[[ CartOrderCtrl.ngFormName ]]" 
                    novalidate>

                    {{-- Order Summary Smart Wizard --}}
                    <div id="lw-smart-wizard" class="card sw-main sw-theme-default">
                        
                        <ul class="nav nav-pills nav-fill step-anchor">
                            <li class="nav-item"><a href="#step-1"><?=  __tr('Personal Detail') ?><br /><small><?=  __tr('Name, Addresses etc') ?></small></a></li>
                            <li class="nav-item"><a href="#step-2"><?=  __tr('Order') ?><br /><small><?=  __tr('Order Details') ?></small></a></li>
                            <li class="nav-item"><a href="#step-3"><?=  __tr('Payment') ?><br /><small><?=  __tr('Complete Your Payment') ?></small></a></li>
                        </ul>
                    
                        <div>


                            {{-- Personal Detail block --}}    
                            <div id="step-1" class="mt-3">
                                {{-- To show the shipping & billing address of login user --}}
                                <div>
                                    <fieldset class="lw-fieldset-2 mb-3">
                                    
                                        <div role="form">
                                            {{-- Name --}}
                                            <lw-form-field field-for="fullName" label="<?=  __tr('Full Name')  ?>"> 
                                                <input type="fullName" 
                                                    class="lw-form-field form-control"
                                                    name="fullName"
                                                    id="lw-full-name"
                                                    ng-required="true"
                                                    ng-model="CartOrderCtrl.orderData.fullName"
                                                />
                                            </lw-form-field>
                                            {{-- Name --}}
                                        </div>

                                        {{-- If Order Process By Guest Order --}}
                                        <div ng-if="!CartOrderCtrl.isLoggedIn">
                                            {{-- Email --}}
                                            <lw-form-field field-for="email" label="<?=  __tr('Email')  ?>"> 
                                                <input type="email" 
                                                    class="lw-form-field form-control"
                                                    name="email"
                                                    ng-blur="CartOrderCtrl.checkUserEmail()"
                                                    ng-required="true"
                                                    ng-model="CartOrderCtrl.orderData.email"
                                                />
                                            </lw-form-field>
                                            {{-- Email --}}

                                            <div ng-if="CartOrderCtrl.userIsExist" class="alert alert-info">
                                                <?= __tr("It's seems that you already have an account, please __click__ to login or continue as guest.", ['__click__' => '<a href ng-click="CartOrderCtrl.openLoginDialog()">click here</a>']) ?>
                                            </div><br>

                                            <div>
                                                <!-- Shipping Address -->
                                                <div class="card mb-4">
                                                    <div class="card-header">
                                                        <strong><?= __tr('Shipping Address') ?> :</strong>

                                                        <div class="lw-form-inline-elements">
                                                            {{--  same address  --}}
                                                            <lw-form-checkbox-field field-for="use_as_billing" label="<?= __tr('Use this address as billing address') ?>">
                                                                <input type="checkbox" 
                                                                    class="lw-form-field"
                                                                    name="use_as_billing" 
                                                                    ng-change="CartOrderCtrl.changeAddressType(CartOrderCtrl.orderData.use_as_billing)"
                                                                    ng-model="CartOrderCtrl.orderData.use_as_billing"/>
                                                            </lw-form-checkbox-field>
                                                            {{--  /same address  --}}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <!--  address Type  -->
                                                        <lw-form-field field-for="shipping_address_type" label="<?= __tr( 'Type' ) ?>"> 
                                                        <select class="form-control" 
                                                                name="shipping_address_type" ng-model="CartOrderCtrl.orderData.shipping_address_type" ng-options="type.id as type.name for type in CartOrderCtrl.addressTypes" ng-required="true">
                                                                <option value='' disabled selected>-- <?= __tr('Select') ?> --</option>
                                                            </select> 
                                                        </lw-form-field>
                                                        <!--  /address Type  -->

                                                        <div class="form-row lw-card-form-layout">
                                                            <div class="form-group col-md-6">
                                                                <!--  Address Line 1  -->
                                                                <lw-form-field field-for="shipping_address_line_1" label="<?= __tr( 'Address 1' ) ?>"> 
                                                                    <input type="text"  
                                                                    class="lw-form-field form-control"
                                                                    name="shipping_address_line_1"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.shipping_address_line_1" />
                                                                </lw-form-field>
                                                                <!--  /Address Line 1  -->
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <!--  Address Line 2  -->
                                                                <lw-form-field field-for="shipping_address_line_2" label="<?= __tr( 'Address 2' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="shipping_address_line_2"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.shipping_address_line_2" />
                                                                </lw-form-field>
                                                                <!--  /Address Line 2  -->
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <!--  City  -->
                                                                <lw-form-field field-for="shipping_city" label="<?= __tr( 'City' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="shipping_city"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.shipping_city" />
                                                                </lw-form-field>
                                                                <!--  /City  -->
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <!--  State  -->
                                                                <lw-form-field field-for="shipping_state" label="<?= __tr( 'State' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="shipping_state"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.shipping_state" />
                                                                </lw-form-field>
                                                                <!--  /State  -->
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <!--  Pin Code  -->
                                                                <lw-form-field field-for="shipping_pin_code" label="<?= __tr( 'Pin Code' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="shipping_pin_code"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.shipping_pin_code" />
                                                                </lw-form-field>
                                                                <!--  /Pin Code  -->
                                                            </div>

                                                            <div class="form-group col-md-6">

                                                                <!--  Country  -->
                                                                <lw-form-field field-for="shipping_country" label="<?= __tr( 'Country' ) ?>"> 
                                                                <selectize config='CartOrderCtrl.countries_select_config' class="lw-form-field form-control" 
                                                                name="shipping_country"
                                                                ng-required="true" 
                                                                ng-model="CartOrderCtrl.orderData.shipping_country" 
                                                                ng-change="CartOrderCtrl.getCartOrderDetails(null, null, CartOrderCtrl.couponCode, CartOrderCtrl.orderData.shipping_country, CartOrderCtrl.orderData.billing_country)" options='CartOrderCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>"></selectize>
                                                                </lw-form-field>
                                                                <!--  /Country  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Billing Address -->
                                                <div class="card" ng-if="!CartOrderCtrl.orderData.use_as_billing">
                                                    <div class="card-header">
                                                        <strong><?= __tr('Billing Address') ?> :</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <!--  address Type  -->
                                                        <lw-form-field field-for="billing_address_type" label="<?= __tr( 'Type' ) ?>"> 
                                                        <select class="form-control" 
                                                                name="billing_address_type" ng-model="CartOrderCtrl.orderData.billing_address_type" ng-options="type.id as type.name for type in CartOrderCtrl.addressTypes" ng-required="true">
                                                                <option value='' disabled selected>-- <?= __tr('Select') ?> --</option>
                                                            </select> 
                                                        </lw-form-field>
                                                        <!--  /address Type  -->

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <!--  Address Line 1  -->
                                                                <lw-form-field field-for="billing_address_line_1" label="<?= __tr( 'Address 1' ) ?>"> 
                                                                    <input type="text"  
                                                                    class="lw-form-field form-control"
                                                                    name="billing_address_line_1"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.billing_address_line_1" />
                                                                </lw-form-field>
                                                                <!--  /Address Line 1  -->
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <!--  Address Line 2  -->
                                                                <lw-form-field field-for="billing_address_line_2" label="<?= __tr( 'Address 2' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="billing_address_line_2"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.billing_address_line_2" />
                                                                </lw-form-field>
                                                                <!--  /Address Line 2  -->
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <!--  City  -->
                                                                <lw-form-field field-for="billing_city" label="<?= __tr( 'City' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="billing_city"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.billing_city" />
                                                                </lw-form-field>
                                                                <!--  /City  -->
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <!--  State  -->
                                                                <lw-form-field field-for="billing_state" label="<?= __tr( 'State' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="billing_state"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.billing_state" />
                                                                </lw-form-field>
                                                                <!--  /State  -->
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <!--  Pin Code  -->
                                                                <lw-form-field field-for="billing_pin_code" label="<?= __tr( 'Pin Code' ) ?>"> 
                                                                    <input type="text" 
                                                                    class="lw-form-field form-control"
                                                                    name="billing_pin_code"
                                                                    ng-required="true" 
                                                                    ng-model="CartOrderCtrl.orderData.billing_pin_code" />
                                                                </lw-form-field>
                                                                <!--  /Pin Code  -->
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <!--  Country  -->
                                                                <lw-form-field field-for="billing_country" label="<?= __tr( 'Country' ) ?>"> 
                                                                <selectize config='CartOrderCtrl.countries_select_config' class="lw-form-field form-control" name="billing_country" ng-model="CartOrderCtrl.orderData.billing_country" ng-change="CartOrderCtrl.getCartOrderDetails(null, null, CartOrderCtrl.couponCode, CartOrderCtrl.orderData.shipping_country, CartOrderCtrl.orderData.billing_country)" options='CartOrderCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ng-required="true"></selectize>
                                                                </lw-form-field>
                                                                <!--  /Country  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- If Order Process By Guest Order --}}
                                        
                                        {{-- Start Shipping address panel --}}
                                        <div ng-if="CartOrderCtrl.isLoggedIn">
                                            <div class="">
                                                
                                                <div class="card mb-3">

                                                        <div class="card-header">

                                                            <strong><?= __tr('Shipping Address') ?> :</strong>
                                                            
                                                            <div class="lw-form-inline-elements">
                                                                {{--  same address  --}}
                                                                <lw-form-checkbox-field field-for="sameAddress" label="<?= __tr('Use this address as billing address') ?>">
                                                                    <input type="checkbox" 
                                                                        class="lw-form-field"
                                                                        name="sameAddress" 
                                                                        ng-change="CartOrderCtrl.sameAsAddress(CartOrderCtrl.orderSupportData.sameAddress, CartOrderCtrl.orderSupportData.shippingAddress)"
                                                                        ng-model="CartOrderCtrl.orderSupportData.sameAddress"/>
                                                                </lw-form-checkbox-field>
                                                                {{--  /same address  --}}
                                                            </div>
                                                        </div>
                                                
                                                        <div class="card-body">

                                                            <!-- shipping id -->
                                                            <lw-form-field field-for="addressID" label=""> 
                                                                <input type="hidden" 
                                                                    class="lw-form-field form-control"
                                                                    name="addressID"
                                                                    ng-model="CartOrderCtrl.orderData.addressID" 
                                                                />
                                                            </lw-form-field>
                                                            <!-- /shipping id -->

                                                            {{-- shipping address --}}
                                                            <div ng-show="CartOrderCtrl.orderSupportData.shippingAddress">

                                                                <address ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1" class="lw-address" id="lw-shipping">
                                                                    <strong ng-if="CartOrderCtrl.orderSupportData.shippingAddress.type" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.type"></strong><br>
                                                                    <span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1"></span><br>
                                                                    <span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_2" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.address_line_2"></span><br>
                                                                    <span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.city"></span>
                                                                    <span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.state"></span>
                                                                    <span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.country"></span><br>
                                                                    <span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.pin_code"><?= __tr('Pincode') ?> : <span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.pin_code"></span></span>
                                                                </address>
                                                            </div>
                                                            {{-- shipping address --}}

                                                            {{-- message for shipping address--}}
                                                            <div class="alert alert-danger" 
                                                                ng-show="!CartOrderCtrl.orderSupportData.shippingAddress">
                                                                <?= __tr('Address is required') ?>
                                                            </div>
                                                            {{-- /message for shipping address--}}
                                                            
                                                            {{-- change address button--}}
                                                            <a  href="" 
                                                                ng-show="CartOrderCtrl.orderSupportData.shippingAddress"
                                                                class="lw-btn btn btn-light btn-sm pull-right" 
                                                                ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'shipping')" title="<?= __tr('Change Address') ?>">
                                                                <i class="fa fa-pencil-square-o"></i> <?= __tr('Change Address') ?>
                                                            </a>
                                                            {{-- /change address button--}}

                                                            {{-- select address button--}}
                                                            <a href="" 
                                                                ng-hide="CartOrderCtrl.orderSupportData.shippingAddress.id" 
                                                                class="lw-btn btn btn-light btn-sm pull-right" 
                                                                ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'shipping')" 
                                                                title="<?= __tr('Select Address') ?>">
                                                                    <?= __tr('Select Address') ?>
                                                            </a>
                                                            {{-- select address button--}}
                                                        </div>
                                                    {{-- End Shipping address panel --}}
                                                </div>
                                                
                                            </div>

                                            {{-- Billing address--}}
                                            <div class=" " ng-show="CartOrderCtrl.orderSupportData.sameAddress == false">

                                                <div class="card">

                                                    <div class="card-header">

                                                        <strong><?= __tr('Billing Address') ?> :</strong>
                                                        
                                                    </div>

                                                    <div class="card-body">

                                                        <!-- Billing id -->
                                                        <lw-form-field field-for="addressID1" label=""> 
                                                            <input type="hidden" 
                                                                class="lw-form-field form-control"
                                                                name="addressID1"
                                                                ng-model="CartOrderCtrl.orderData.addressID1" 
                                                            />
                                                        </lw-form-field>
                                                        <!-- /Billing id -->

                                                        <address class="lw-address"  ng-if="CartOrderCtrl.orderSupportData.billingAddress"  id="lw-billing">
                                                            <strong ng-if="CartOrderCtrl.orderSupportData.billingAddress.type" 
                                                                ng-bind="CartOrderCtrl.orderSupportData.billingAddress.type">
                                                            </strong>
                                                            <br>
                                                            <span 
                                                                ng-if="CartOrderCtrl.orderSupportData.billingAddress.address_line_1" ng-bind="CartOrderCtrl.orderSupportData.billingAddress.address_line_1">
                                                            </span><br>

                                                            <span ng-if="CartOrderCtrl.orderSupportData.billingAddress.address_line_2" ng-bind="CartOrderCtrl.orderSupportData.billingAddress.address_line_2"></span>
                                                                <br>
                                                            <span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.city"></span>
                                                            <span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.state"></span>
                                                            <span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.country"></span><br>
                                                            <span ng-if="CartOrderCtrl.orderSupportData.billingAddress.pin_code"><?= __tr('Pincode') ?> : <span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.pin_code"></span></span>
                                                        </address>
                                                        
                                                        {{-- message for billing address required--}}
                                                        <div class="alert alert-danger" 
                                                            ng-show="CartOrderCtrl.orderSupportData.billingAddress == ''">
                                                            <?= __tr('Address is required') ?>
                                                        </div>
                                                        {{-- /message for billing address required--}}

                                                        {{-- change address button--}}
                                                        <a href="" 
                                                            ng-show="CartOrderCtrl.orderSupportData.billingAddress && !CartOrderCtrl.orderSupportData.sameAddress"
                                                            class="lw-btn btn btn-light btn-sm pull-right" 
                                                            ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'billing')" title="<?= __tr('Change Address') ?>">
                                                            <i class="fa fa-pencil-square-o"></i> <?= __tr('Change Address') ?>
                                                        </a>
                                                        {{-- /change address button--}}
                                                        
                                                        {{-- select address button --}}
                                                        <a ng-show="CartOrderCtrl.orderSupportData.sameAddress == false &&!CartOrderCtrl.orderSupportData.billingAddress" 
                                                            href="" 
                                                            class="lw-btn btn btn-light btn-sm pull-right" 
                                                            ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'billing')" 
                                                            title="<?= __tr('Select Address') ?>"><?= __tr('Select Address') ?></a>
                                                        {{-- /select address button --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- /billing address--}}

                                    </fieldset>
                                </div>
                                {{-- /To show the shipping & billing address of login user --}}

                            </div>
                            {{-- / Personal Detail block --}}

                            {{-- Cart Summary & apply coupan block --}}
                            <div id="step-2" class="mt-3">

                                <table class="table table-borderless lw-order-summery-table-container" cellspacing="0" width="100%">   
                                    {{-- /tbody --}}
                                    <tbody class="ng-scope">
                                        {{--/  cart Total --}}
                                        <tr>
                                            <td colspan="3">
                                                <h5><strong><?=  __tr('Subtotal')  ?></strong></h5>
                                            </td>   
                                            <td class="text-right">
                                            <h5><span ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true"> [[ CartOrderCtrl.orderSupportData.shipping.formettedDiscountPrice ]]</span>
                                                    <span ng-if="!CartOrderCtrl.orderSupportData.shipping.discountAddedPrice"> [[ CartOrderCtrl.orderSupportData.formatedCartTotalPrice ]]</span> </h5>
                                            </td>
                                        </tr>
                                        {{--/  cart Total --}}
                                    </tbody>
                                    {{-- /tbody --}}
                                </table>

                                {{-- To show the tax related information --}}
                                <div class="card mb-3" ng-if="CartOrderCtrl.orderSupportData.taxses != ''">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                               <tr> <th colspan="2"><?= __tr('Tax') ?></th></tr>
                                            </thead>
                                            <tbody>
                                                {{-- tax section --}} 
                                                <tr ng-repeat="tax in CartOrderCtrl.orderSupportData.taxses">
                                                    <td>
                                                        <span ng-bind="tax.label"></span><br>
                                                        <span ng-bind="tax.notes"></span>
                                                    </td>   
                                                    <td class="lw-amount-td text-right" ng-switch="tax.type">
                                                        {{-- Tax type flat  --}}
                                                        + <span ng-switch-when="1">
                                                            <span ng-bind="tax.formatedTax"></span>
                                                        </span>
                                                        {{--/ Tax type flat  --}}

                                                        {{-- Tax type percent  --}}
                                                        <span ng-switch-when="2">
                                                            <span ng-bind="tax.formatedTax"></span>
                                                        </span>
                                                        {{--/ Tax type percent  --}}
                                                    </td>
                                                </tr>
                                                {{--/ tax section --}}

                                            </tbody>{{-- end ngIf: CartCtrl.cartDataStatus === true --}}
                                            {{-- ngIf: CartCtrl.cartDataStatus === false --}}
                                        </table>
                                    </div>
                                </div>
                                {{-- To show the tax related information --}}

                                {{-- shipping and total payable amount section --}}


                                <div class="card mb-3" ng-if="CartOrderCtrl.orderSupportData.shipping.info || CartOrderCtrl.shippingMethods.length > 0">
                                    <div class="table-responsive">

                                        <table class="table borderless">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <?= __tr('Shipping') ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- shipping section --}} 
                                                <tr>
                                                    <td colspan="2">
                                                        <lw-form-field field-for="shipping_method" label="" v-label="<?= __tr( 'Shipping Method' ) ?>" ng-if="CartOrderCtrl.shippingCount > 1"> 
                                                            <select class="form-control" 
                                                                name="shipping_method" ng-model="CartOrderCtrl.orderData.shipping_method" ng-options="type._id as type.title for type in CartOrderCtrl.shippingMethods" ng-change="CartOrderCtrl.getShipping(CartOrderCtrl.orderData.shipping_method)" ng-required="!CartOrderCtrl.orderSupportData.shipping.info && CartOrderCtrl.shippingCount > 1">
                                                                <option value='' disabled selected>-- <?= __tr('Select Shipping Method') ?> --</option>
                                                            </select> 
                                                        </lw-form-field>
                                                    </td>
                                                </tr>
                                                <tr ng-repeat="shipping in CartOrderCtrl.orderSupportData.shipping.info">
                                                    <td>
                                                        <p ng-if="shipping.shipping_method_title">
                                                            <strong><?= __tr('Shipping Method') ?> :</strong> ([[ shipping.shipping_method_title ]])
                                                        </p>
                                                        <p ng-bind="shipping.notes"></p>
                                                    </td>   
                                                    <td class="lw-amount-td lw-shipping-amount text-right" ng-switch="shipping.type">
                                                        {{-- shipping type flat and amount null  --}}
                                                        <div ng-switch-when="1">

                                                            <span ng-show="shipping.shippingAmt == 0"><?=  __tr('Free')  ?></span>

                                                            <span 
                                                                ng-show="shipping.shippingAmt != 0">
                                                                + <span 
                                                                    ng-bind="shipping.formattedShippingAmt">
                                                                    </span>
                                                            </span>
                                                        </div>
                                                        {{--/ shipping type flat and amount null  --}}

                                                        {{-- shipping type percent  --}}
                                                        <div ng-switch-when="2">
                                                            + <span ng-bind="shipping.formattedShippingAmt"></span>
                                                        </div>
                                                        {{--/ shipping type percent  --}}

                                                        {{-- shipping type free  --}}
                                                        <div ng-switch-when="3">
                                                            <?=  __tr('Free')  ?>
                                                        </div>
                                                        {{--/ shipping type free  --}}

                                                        {{-- shipping type not shippable  --}}
                                                        <div ng-switch-when="4" >
                                                            <span class="lw-danger"><?=  __tr('Sorry shipping not available in your country.')  ?></span>
                                                        </div>
                                                        {{--/ shipping type not shippable  --}}
                                                        
                                                    </td>
                                                </tr>
                                                {{--/ shipping section --}}

                                            </tbody>{{-- end ngIf: CartCtrl.cartDataStatus === true --}}
                                            {{-- ngIf: CartCtrl.cartDataStatus === false --}}
                                        </table>
                                    </div>
                                </div>
                                {{-- shipping and total payable amount section --}}

                                <table class="table table-borderless lw-order-summery-table-container" cellspacing="0" width="100%">   
                                    {{-- /tbody --}}
                                    <tbody class="ng-scope">
                                        {{--/ total payable amount --}}
                                        <tr>
                                            <td colspan="3">
                                                <h5><strong><?=  __tr('Total Payable Amount')  ?></strong></h5>
                                            </td>   
                                            <td align="right">
                                                <h5><strong ng-bind="CartOrderCtrl.orderSupportData.totalPayableAmountFormated"></strong></h5>
                                            </td>
                                        </tr>
                                        {{--/ total payable amount --}}
                                    </tbody>
                                    {{-- /tbody --}}
                                </table>
                            </div>
                            {{-- / Cart Summary & apply coupan block --}}
                            
                            <div id="step-3" class="mt-3">
                                {{-- total payable order amount table--}}
                                <div class="table-responsive">
                                    
                                    <table class="table table-borderless lw-order-summery-table-container" cellspacing="0" width="100%">   
                                        {{-- /tbody --}}
                                        <tbody class="ng-scope">
                                            {{--/ total payable amount --}}
                                            <tr>
                                                <td colspan="3">
                                                    <h5><strong><?=  __tr('Total Payable Amount')  ?></strong></h5>
                                                </td>   
                                                <td align="right">
                                                    <h5><strong ng-bind="CartOrderCtrl.orderSupportData.totalPayableAmountFormated"></strong></h5>
                                                </td>
                                            </tr>
                                            {{--/ total payable amount --}}
                                        </tbody>
                                        {{-- /tbody --}}
                                    </table>
                                        
                                    </div>
                                    {{-- /total payable order amount table--}}

                                    {{-- radio button for order payment method --}}
                                    <div class="card lw-checkout-methods mb-3">
                                        <div class="card-header">
                                            <h5><strong><?=  __tr('Checkout')  ?>  - <small><?=  __tr(' Please choose your payment gateway')  ?></small></strong></h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="form-check form-check-inline">
                                                <lw-form-radio-field ng-if="CartOrderCtrl.orderSupportData.paymentMethod.length != 0"  field-for="checkout_method" label="<?= __tr( 'Checkout Method' ) ?>">

                                                    <label class="form-check-label" ng-repeat="(key, method) in CartOrderCtrl.orderSupportData.paymentMethod">
                                                        <fieldset class="lw-fieldset-2 mr-3 mb-3">
                                                            <legend class="lw-fieldset-legend-font">  
                                                                <input class="form-check-input" type="radio" name="checkout_method" ng-value="[[method]]" ng-required="true" ng-model="CartOrderCtrl.orderData.checkout_method" ng-change="CartOrderCtrl.checkValidDataForSubmit(CartOrderCtrl.orderData.checkout_method)">

                                                                {{-- paypal logo --}}
                                                                <span ng-show="method == 1" >
                                                                   <img class="lw-payment-gateway-icon" alt="<?= __tr( 'PayPal' ) ?>" src="<?= url('resources/assets/imgs/payment/paypal-small.png') ?>">
                                                                </span>
                                                                {{-- paypal logo --}}

                                                                {{-- Stripe logo --}}
                                                                <span ng-show="method == 6">
                                                                   <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Use Stripe' ) ?>" src="<?= url('resources/assets/imgs/payment/stripe-small.png') ?>">
                                                                </span>
                                                                {{-- Stripe logo --}}

                                                                {{-- Razorpay logo --}}
                                                                <span ng-show="method == 11">
                                                                  <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Razorpay Payment (Indian payment gateway)' ) ?>" src="<?= url('resources/assets/imgs/payment/razorpay-small.png') ?>">
                                                                </span>
                                                                {{-- Razorpay logo --}}

                                                                {{-- Iyzico logo --}}
                                                                <span ng-show="method == 14">
                                                                   <img class="lw-payment-gateway-icon" alt="<?= __tr( 'Iyzico Payment' ) ?>" src="<?= url('resources/assets/imgs/payment/iyzico-small.png') ?>">
                                                                </span>
                                                                {{-- Iyzico logo --}}


                                                            {{-- check logo --}}
                                                            <span ng-show="method == 2">
                                                                <?= __('Bank Check') ?>
                                                            </span>
                                                            {{-- /check logo --}}

                                                            {{-- bank payment logo --}}
                                                            <span ng-show="method == 3">
                                                              <?= __('Bank Transfer') ?>
                                                            </span>
                                                            {{-- /bank payment logo --}}

                                                            {{-- cod payment logo --}}
                                                            <span ng-show="method == 4">
                                                               <?= __('COD - Cash on Delivery') ?> 
                                                            </span>
                                                            {{-- /cod payment logo --}}

                                                            {{-- Other logo --}}   
                                                            <span ng-show="method == 5">
                                                              <?= __('Other Payment Method') ?>
                                                            </span>
                                                            {{-- Other logo --}}

                                                            </legend>

                                                            {{-- paypal --}}
                                                            <span ng-show="method == 1" >
                                                               <img alt="<?= __('Checkout with PayPal') ?>" src="<?= url('resources/assets/imgs/payment/paypal-big.jpg') ?>">
                                                            </span>
                                                            {{-- paypal --}}

                                                            {{-- Stripe --}}
                                                            <span ng-show="method == 6">
                                                               <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/stripe-big.jpg') ?>">
                                                            </span>
                                                            {{-- Stripe --}}

                                                            {{-- Razorpay --}}
                                                            <span ng-show="method == 11">
                                                               <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/razorpay-big.jpg') ?>">
                                                            </span>
                                                            {{-- Razorpay --}}

                                                            {{-- Iyzico --}}
                                                            <span ng-show="method == 14">
                                                               <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/iyzico-big.jpg') ?>">
                                                            </span>
                                                            {{-- Iyzico --}}

                                                            {{-- Paytm --}}
                                                            <span ng-show="method == 16">
                                                               <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/paytm-big.png') ?>">
                                                            </span>
                                                            {{-- Paytm --}}

                                                            {{-- Instamojo --}}
                                                            <span ng-show="method == 18">
                                                                <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/instamojo-big.png') ?>">
                                                            </span>
                                                            {{-- Instamojo --}}

                                                            {{-- Paystack --}}
                                                            <span ng-show="method == 20">
                                                               <img alt="<?= __('Pay with Card') ?>" src="<?= url('resources/assets/imgs/payment/paystack-big.jpg') ?>">
                                                            </span>
                                                            {{-- Paystack --}}

                                                            {{-- check payment --}}
                                                            <span ng-show="method == 2">
                                                                <img title="<?= __('Bank Check') ?>" alt="<?= __('Bank Check') ?>" src="<?= url('resources/assets/imgs/payment/cheque-big.png') ?>"> 
                                                            </span>
                                                            {{-- /check payment --}}

                                                            {{-- bank payment --}}
                                                            <span ng-show="method == 3">
                                                                <img title="<?= __('Bank Transfer') ?>" alt="<?= __('Bank Transfer') ?>" src="<?= url('resources/assets/imgs/payment/bank-transfer-big.png') ?>"> 
                                                            </span>
                                                            {{-- /bank payment --}}

                                                            {{-- cod payment --}}
                                                            <span ng-show="method == 4">
                                                                <img title="<?= __('COD - Cash on Delivery') ?>" alt="<?= __('COD - Cash on Delivery') ?>" src="<?= url('resources/assets/imgs/payment/cod-big.png') ?>"> 
                                                            </span>
                                                            {{-- /cod payment --}}

                                                            {{-- Other --}}   
                                                            <span ng-show="method == 5">
                                                                <img title="<?= __('Other Payment Method - You can submit this order without payment for Now, Admin will contact you for payment.') ?>" alt="<?= __('Other Payment Method') ?>" src="<?= url('resources/assets/imgs/payment/other-big.png') ?>"> 
                                                            </span>
                                                            {{-- Other --}}
                                                        </fieldset>
                                                    </label>
                                                </lw-form-radio-field>
                                            </div>
                                            <!-- Payment Options -->
                                            <br>

                                            <div>
                                                {{-- check payment description --}}
                                                <div ng-if="CartOrderCtrl.orderData.checkout_method == 2">
                                                    <fieldset class="lw-fieldset-2">
                                                        <legend>
                                                            <?=  __tr('Check Payment Description')  ?> 
                                                        </legend>
                                                        <span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.checkText"></span>
                                                    </fieldset>
                                                </div>
                                                {{-- check payment description --}}

                                                {{-- bank payment description --}}
                                                <div ng-if="CartOrderCtrl.orderData.checkout_method == 3">
                                                    <fieldset class="lw-fieldset-2">
                                                        <legend>
                                                            <?=  __tr('Bank Payment Description')  ?> 
                                                        </legend>
                                                        <span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.bankText"></span>
                                                    </fieldset>
                                                </div>
                                                {{-- bank payment description --}}

                                                {{-- cod payment description --}}
                                                <div ng-if="CartOrderCtrl.orderData.checkout_method == 4">
                                                    <fieldset class="lw-fieldset-2">
                                                        <legend>
                                                            <?=  __tr('COD Payment Description')  ?> 
                                                        </legend>
                                                        <span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.codText"></span>
                                                    </fieldset>
                                                </div>
                                                {{-- cod payment description --}}

                                                {{-- Other payment description --}}
                                                <div ng-if="CartOrderCtrl.orderData.checkout_method == 5">
                                                    <fieldset class="lw-fieldset-2">
                                                        <legend>
                                                            <?=  __tr('Other Payment Description')  ?> 
                                                        </legend>
                                                        <span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.otherText"></span>
                                                    </fieldset>
                                                </div>
                                                {{-- Other payment description --}}
                                            </div>
                                            <br>

                                            {{-- Iyzico payment Method fill details of card --}}
                                            <div class="card" ng-if="CartOrderCtrl.orderData.checkout_method == 14">
                                                <div class="card-header">
                                                    <h6 class="panel-title display-td" ><?= __tr('Iyzico Card Details') ?> <small><?= __tr('All fields are required.') ?></small></h6>
                                                </div>

                                                <div class="card-body">
                                                    <!-- Card Number -->
                                                    <lw-form-field field-for="card_number" label="<?= __tr( 'Card Number' ) ?>"> 
                                                        <div class="input-group">
                                                            <input type="number"
                                                                name="card_number"
                                                                class="form-control"
                                                                ng-model="CartOrderCtrl.orderData.card_number"
                                                                ng-required="true"
                                                                placeholder="<?= __tr('1234 5678 9012 3456') ?>"/>
                                                                <div class="input-group-append" id="basic-addon1">
                                                                    <span class="input-group-text"><i class="fa fa-credit-card"></i>
                                                                    </span>
                                                                </div>
                                                        </div>
                                                    </lw-form-field>
                                                    <!-- /Card Number -->

                                                    <div class="form-row lw-card-form-layout">
                                                        <div class="form-group col-md-6">
                                                            <!-- Set Expiry Month / Year -->
                                                            <lw-form-field field-for="expiry_date" label="<?= __tr( 'Expiry Date' ) ?>"> 
                                                                <input type="text"
                                                                    name="expiry_date"
                                                                    class="form-control"
                                                                    id="expiry_date"
                                                                    ng-required="true"
                                                                    ng-model="CartOrderCtrl.orderData.expiry_date"
                                                                    placeholder="<?= __tr('mm/yy') ?>"/>
                                                            </lw-form-field>
                                                            <!-- /Set Expiry Month / Year -->
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <!-- Set Cvv -->
                                                            <lw-form-field field-for="cvv" label="<?= __tr( 'CVV Code' ) ?>"> 
                                                                <input type="number"
                                                                    name="cvv"
                                                                    class="form-control"
                                                                    id="cvv"
                                                                    ng-required="true"
                                                                    ng-model="CartOrderCtrl.orderData.cvv"
                                                                    placeholder="<?= __tr('123') ?>"/>
                                                            </lw-form-field>
                                                            <!-- /Set Cvv -->
                                                        </div>
                                                    </div>

                                                    <!-- Set Name of Card Holder -->
                                                    <lw-form-field field-for="name_on_card" label="<?= __tr( 'Name on card' ) ?>"> 
                                                        <input type="text"
                                                            name="name_on_card"
                                                            class="form-control"
                                                            ng-required="true"
                                                            ng-model="CartOrderCtrl.orderData.name_on_card"
                                                            placeholder="<?= __tr('Name on card') ?>"/>
                                                    </lw-form-field>
                                                    <!-- /Set Name of Card Holder -->
                                                </div>
                                            </div>
                                            {{-- Iyzipay payment Method fill details of card --}}

                                            {{-- Alert Payment option If Currency Not available --}}
                                            <div class="alert alert-info" ng-if="CartOrderCtrl.orderData.checkout_method == 16 && CartOrderCtrl.orderSupportData.currency != 'INR' || CartOrderCtrl.orderData.checkout_method == 18 && CartOrderCtrl.orderSupportData.currency != 'INR'">
                                                <?= __tr('This payment method is available only for Indian Currency.') ?>
                                            </div>
                                           
                                            <div class="alert alert-danger" ng-if="CartOrderCtrl.orderSupportData.paymentMethod.length == 0">
                                                <?= __tr('No payment methods available, Please contact store administrator.') ?>
                                            </div>
                                            {{-- / Alert Payment option If Currency Not available --}}

                                        </div>

                                    {{-- radio button for order payment method --}}
                                    </div>

                                    {{-- order action buttons --}}
                                    <div class="lw-form-actions mb-3">
                                        {{-- back to shopping cart button --}}
                                        <a  href="<?= e( route('cart.view') ) ?>" class="btn btn-light lw-btn lw-show-process-action lw-redirect-action" title="<?= __tr('Back To Shopping Cart') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __tr('Back To Shopping Cart') ?></a>
                                        {{-- back to shopping cart button --}}
                                        
                                        {{-- process order button if logged in --}}
                                        <span ng-if="CartOrderCtrl.isLoggedIn">

                                            <button id="lwOrderSubmit" ng-disabled="!CartOrderCtrl.disabledStatus && !CartOrderCtrl.orderData.checkout_method || (CartOrderCtrl.orderSupportData.currency == 'TRY' && CartOrderCtrl.orderData.checkout_method == 16) || (CartOrderCtrl.orderSupportData.currency != 'INR' && CartOrderCtrl.orderData.checkout_method == 16)  || (CartOrderCtrl.orderSupportData.currency == 'TRY' && CartOrderCtrl.orderData.checkout_method == 18) || (CartOrderCtrl.orderSupportData.currency != 'INR' && CartOrderCtrl.orderData.checkout_method == 18) || (CartOrderCtrl.orderSupportData.currency == 'USD' && CartOrderCtrl.orderData.checkout_method == 16) || (CartOrderCtrl.orderSupportData.currency == 'USD' && CartOrderCtrl.orderData.checkout_method == 18)" type="submit" class="btn btn-success pull-right lw-btn btn-lg lw-btn-process" title="<?= __tr('Submit Order') ?>" ng-click="CartOrderCtrl.orderSubmit()" ><i class="fa fa-check-square-o"></i> <?= __tr('Submit Order') ?> <span></span></button>
                                        </span>
                                        {{-- /process order button if logged in --}}
                                        
                                        {{-- process order button if not logged in --}}
                                        <span  ng-if="!CartOrderCtrl.isLoggedIn">
                                            <button id="lwOrderSubmit" href ng-disabled="!CartOrderCtrl.disabledStatus && !CartOrderCtrl.orderData.checkout_method || (CartOrderCtrl.orderSupportData.currency == 'TRY' && CartOrderCtrl.orderData.checkout_method == 16) || (CartOrderCtrl.orderSupportData.currency != 'INR' && CartOrderCtrl.orderData.checkout_method == 16) || (CartOrderCtrl.orderSupportData.currency == 'TRY' && CartOrderCtrl.orderData.checkout_method == 18) || (CartOrderCtrl.orderSupportData.currency != 'INR' && CartOrderCtrl.orderData.checkout_method == 18) || (CartOrderCtrl.orderSupportData.currency == 'USD' && CartOrderCtrl.orderData.checkout_method == 16) || (CartOrderCtrl.orderSupportData.currency == 'USD' && CartOrderCtrl.orderData.checkout_method == 18)" type="submit" class="btn btn-success pull-right lw-btn btn-lg lw-btn-process" title="<?= __tr('Submit Order') ?>" ng-click="CartOrderCtrl.orderSubmit()" ><i class="fa fa-check-square-o"></i> <?= __tr('Submit Order') ?> <span></span></button>
                                        </span>
                                        {{-- /process order button if not logged in --}}

                                    </div>
                                    {{-- order action buttons --}}
                            </div>
                        </div>
                    </div>
                    {{-- / Order Summary Smart Wizard --}}
                        
                    </form>

                </div>
                {{--/ main container --}}

            </div>

        </div>
    </div>

</div>
<?php $availablePaymentMethods = getStoreSettings('select_payment_option') ?: []; ?>
@if(in_array(6, $availablePaymentMethods))
	<script src="https://checkout.stripe.com/checkout.js"></script>
@endif

@if(in_array(11, $availablePaymentMethods))
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

@if(in_array(20, $availablePaymentMethods))
     <script src="https://js.paystack.co/v1/inline.js"></script>
@endif

<script type="text/_template" id="cartDiscountDetailsTemplate">
<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= __tr('Discount Description') ?></th>
                <th class="text-right"><?= __tr('Amount') ?></th>
            </tr>                    
        </thead>
        <tbody>
            <% _.forEach(__tData.discountDetails, function(item) { %>
            <tr>
                <td>
                    <div>
                        <strong><%= item.title %></strong>
                        (<?= __tr('__discount__ Off', [
                            '__discount__' => '<%= item.formattedDiscountAmt %>'
                        ]) ?>)
                    </div>
                    <div>
                        <%= item.description %> 
                        (<?= __tr('Max Discount __maxDiscount__', [
                            '__maxDiscount__' => '<%= item.formattedMaxAmount %>'
                        ]) ?>)
                    </div>
                </td>
                <td>
                    <span class="pull-right">
                        <%= item.formattedSingleDiscount %>
                    </span>
                </td>
            </tr>
            <% }); %>
            <tr>
                <td>
                    <strong><?= __tr('Total Discount') ?></strong>
                </td>
                <td width="50%">
                    <strong class="pull-right">
                        <%= __tData.formattedDiscount %>
                    </strong>
                </td>
            <tr>
        </tbody>
    </table>
</div>
</script>

@push('appScripts')
<?= __yesset('dist/js/vendorlibs-smartwizard*.js', true) ?>
@endpush