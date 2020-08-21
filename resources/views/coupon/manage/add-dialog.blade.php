<div ng-controller="CouponAddController as couponAddCtrl" class="lw-dialog">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Add New Coupon / Discount' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="couponAddCtrl.[[ couponAddCtrl.ngFormName ]]" 
			ng-submit="couponAddCtrl.submit()" 
			novalidate>
            
            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>"> 
                <input type="text" 
                    class="lw-form-field form-control"
                    name="title"
                    ng-required="true" 
                    autofocus
                    ng-model="couponAddCtrl.couponData.title" 
                />
            </lw-form-field><br>
            <!-- /Title -->

            <div class="alert alert-warning">
                <?= __tr("Please cross check all your discounts, if the product value goes below zero then user won't be able to add to cart.") ?>
            </div>

            <lw-form-radio-field field-for="product_discount_type" label="<?= __tr( 'Product Discount Type' ) ?>">

                <strong><?= __tr('Discount Type') ?> : </strong>

                <label ng-repeat="(TypeKey, productDiscountType) in couponAddCtrl.productDiscountTypes" class="radio-inline">
                    &nbsp;
                    <input ng-model="couponAddCtrl.couponData.product_discount_type" type="radio" name="product_discount_type" ng-value="[[ TypeKey ]]" ng-change="couponAddCtrl.getProductDiscountData(couponAddCtrl.couponData.product_discount_type)">

                    <span ng-show="TypeKey == 1">
                        [[ productDiscountType ]]
                    </span>

                    <span ng-show="TypeKey == 2">
                        [[ productDiscountType ]]
                    </span>

                </label>
            </lw-form-radio-field>

            <lw-form-selectize-field field-for="selected_product" label="<?= __tr( 'Products' ) ?>" class="lw-selectize" ng-if="couponAddCtrl.couponData.product_discount_type == 2">
                <selectize 
                    config='couponAddCtrl.product_select_config' 
                    class="lw-form-field" 
                    name="selected_product" 
                    ng-model="couponAddCtrl.couponData.selected_product" 
                    options='couponAddCtrl.products' 
                    placeholder="<?= __tr( 'Select Product' ) ?>" 
                    ng-required="couponAddCtrl.couponData.product_discount_type == 2"  
                    >
                </selectize>
            </lw-form-selectize-field>
            <br ng-if="couponAddCtrl.couponData.product_discount_type == 2">

			<!-- Code -->
			<lw-form-field field-for="code" v-label="<?= __tr( 'Code' ) ?>"> 
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text lw-checkbox-group-text">
                            <!-- Coupon Code Required -->
                              <lw-form-checkbox-field class="lw-addon-field" field-for="coupon_code_required" label="<?= __tr( 'Coupon Code Required' ) ?>" title="<?= __tr( 'Coupon Code Required' ) ?>" advance="true">
                                    <input type="checkbox" 
                                        class="lw-form-field js-switch"
                                        name="coupon_code_required"
                                        ng-change="couponAddCtrl.clearTextField(2)"
                                        ng-model="couponAddCtrl.couponData.coupon_code_required"
                                        ui-switch="" />
                            </lw-form-checkbox-field>
                            <!-- /Coupon Code Required -->
                        </span>
                    </div>
    				<input type="text" 
    					class="lw-form-field form-control"
    					name="code"
                        ng-disabled="!couponAddCtrl.couponData.coupon_code_required"
                        placeholder="<?= __tr('Enter coupon code') ?>"
    					min="3"
    					max="10" ng-required="couponAddCtrl.couponData.coupon_code_required == true"
    					ng-model="couponAddCtrl.couponData.code" 
    				/>
                </div>
			</lw-form-field>
			<!-- /Code -->
            
            <!-- Usage Per User -->
            <lw-form-field field-for="uses_per_user" v-label="<?= __tr( 'Usage Per User' ) ?>" ng-show="couponAddCtrl.couponData.coupon_code_required"> 
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text lw-checkbox-group-text">
                            <!-- Coupon Code Required -->
                            <lw-form-checkbox-field class="lw-addon-field" field-for="user_per_usage" label="<?= __tr( 'Limit this coupon uses' ) ?>" advance="true">
                                <input type="checkbox" 
                                    class="lw-form-field js-switch lw-switchery-change"
                                    name="user_per_usage"
                                    ng-model="couponAddCtrl.couponData.user_per_usage"
                                    ng-change="couponAddCtrl.clearTextField(1)"
                                    ui-switch="" />
                            </lw-form-checkbox-field>
                            <!-- /Coupon Code Required -->
                        </span>
                    </div>
                    <input type="number" 
                        class="lw-form-field form-control"
                        name="uses_per_user"
                        min="0"
                        max="99999"
                        ng-disabled="!couponAddCtrl.couponData.user_per_usage"
                        placeholder="<?= __tr('Enter discount usage per user') ?>"
                        ng-change="couponAddCtrl.setNumberFieldValidation(couponAddCtrl.couponData.uses_per_user)"
                        ng-model="couponAddCtrl.couponData.uses_per_user" 
                    />
                    <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text">
                            <?= __tr('time(s) per customer') ?>
                        </span>
                    </div>
                </div>
            </lw-form-field>
            <!-- /Usage Per User -->

            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Start Dates -->
                    <lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>"> 
                        <input type="text" 
                                class="lw-form-field form-control lw-readonly-control"
                                name="start"
                                id="start"
                                lw-bootstrap-md-datetimepicker
                                ng-required="true" 
                                ng-change="couponAddCtrl.endDateUpdated(couponAddCtrl.couponData.start)"
                                options="[[ couponAddCtrl.startDateConfig ]]"
                                readonly
                                ng-model="couponAddCtrl.couponData.start" 
                            />
                    </lw-form-field>
                    <!-- /Start Dates -->
                </div>

                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- end Dates -->
                    <lw-form-field field-for="end" label="<?= __tr( 'End Date' ) ?>"> 
                        <input type="text" 
                                class="lw-form-field form-control lw-readonly-control"
                                name="end"
                                id="end"
                                lw-bootstrap-md-datetimepicker
                                ng-change="couponAddCtrl.endDateUpdated(couponAddCtrl.couponData.end)"
                                options="[[ couponAddCtrl.endDateConfig ]]"
                                readonly
                                ng-model="couponAddCtrl.couponData.end" 
                            />
                    </lw-form-field>
                    <!-- /end Dates -->
                </div>
            </div>

            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Discount Type -->
                    <lw-form-field field-for="discount_type" label="<?= __tr('Amount / Percentage') ?>"> 
                        <select class="lw-form-field form-control" 
                            name="discount_type" ng-model="couponAddCtrl.couponData.discount_type" ng-options="type.id as type.name for type in couponAddCtrl.discountType" ng-required="true">
                        </select>
                    </lw-form-field>
                    <!-- /Discount Type -->
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Discount Type Amount-->
                    <lw-form-field field-for="discount" label="<?= __tr('Discount') ?>" ng-if="couponAddCtrl.couponData.discount_type == 1"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
                            </div>
                            <input type="number" 
                                class="lw-form-field form-control"
                                name="discount"
                                min="0.1"
                                ng-required="true" 
                                ng-model="couponAddCtrl.couponData.discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    <!-- /Discount Type Amount-->

                    <!-- Discount Type Percentage-->
                    <lw-form-field field-for="discount" label="<?= __tr('Discount') ?>" ng-if="couponAddCtrl.couponData.discount_type == 2"> 
                        <div class="input-group">
                        <input type="number" 
                            class="lw-form-field form-control"
                            name="discount"
                            min="0.1"
                            max="99"
                            ng-required="true" 
                            ng-model="couponAddCtrl.couponData.discount" />
                            <div class="input-group-append" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">
                                    <span>%</span>
                                </span>
                            </div>
                    </div>
                    </lw-form-field>
                    <!-- /Discount Type Percentage-->
                </div>
            </div>

            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Max Discount amount-->
                    <lw-form-field field-for="max_discount" label="<?= __tr('Max Discount') ?>" ng-if="couponAddCtrl.couponData.discount_type == 2"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
                            </div>
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                name="max_discount"
                                ng-model="couponAddCtrl.couponData.max_discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    <!-- /Max Discount amount-->

                    <!-- Max Discount percentage-->
                    <lw-form-field field-for="max_discount" label="<?= __tr('Max Discount in % of Order Price') ?>" ng-if="couponAddCtrl.couponData.discount_type == 1"> 
                        <div class="input-group">
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                max="99"
                                ng-required="true"
                                name="max_discount"
                                ng-model="couponAddCtrl.couponData.max_discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1">
                                        <span>%</span>
                                    </span>
                                </div>
                        </div>
                    </lw-form-field>
                    <!-- /Max Discount percentage-->
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Minimum Order Amount -->
                    <lw-form-field field-for="minimum_order_amount" label="<?= __tr('Minimum Order Amount') ?>"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
                            </div>
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                name="minimum_order_amount"
                                ng-disabled="couponAddCtrl.couponData.product_discount_type == 2"
                                ng-required="couponAddCtrl.couponData.product_discount_type == 1 || couponAddCtrl.couponData.product_discount_type == null"
                                ng-model="couponAddCtrl.couponData.minimum_order_amount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    <!-- /Minimum Order Amount -->
                </div>
            </div>
	        
			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="couponAddCtrl.couponData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Publically Available' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true" v-label="<?= __tr( 'Status' ) ?>" off-label="<?= __tr( 'Publically Unavailable' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="couponAddCtrl.couponData.active"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->

            <div ng-if="couponAddCtrl.couponData.active" class="alert alert-info">
                <span ng-if="!couponAddCtrl.couponData.end">
                    <?= __tr('This coupon / discount will be available from __fromDate__.', [
                        '__fromDate__' => '[[ couponAddCtrl.couponData.start ]]'
                    ]) ?>
                </span>
                <span ng-if="couponAddCtrl.couponData.end">
                    <?= __tr('This coupon / discount will be available from __fromDate__ to __toDate__.', [
                        '__fromDate__'  => '[[ couponAddCtrl.formatDateTime(couponAddCtrl.couponData.start) ]]',
                        '__toDate__'    => '[[ couponAddCtrl.formatDateTime(couponAddCtrl.couponData.end) ]]'
                    ]) ?>
                </span>
                <span>
                <span ng-if="couponAddCtrl.couponData.code">
                    <?= __tr('Using __code__ coupon code.', ['__code__' => '[[ couponAddCtrl.couponData.code ]]']) ?>
                </span>
            </div>

			<!-- action button -->
			<div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="couponAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action button -->
		</form>
		<!-- /form section -->

</div>