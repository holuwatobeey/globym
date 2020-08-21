<div ng-controller="CouponEditController as couponEditCtrl" class="lw-dialog">

    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Edit Coupon / Discount' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="couponEditCtrl.[[ couponEditCtrl.ngFormName ]]" 
			ng-submit="couponEditCtrl.submit()" 
			novalidate>

            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>">
                <div class="input-group"> 
                    <input type="text" 
                        class="lw-form-field form-control"
                        name="title"
                        ng-required="true" 
                        autofocus
                        ng-model="couponEditCtrl.couponData.title" 
                    />
                    <div class="input-group-append">
                        <a href class="lw-btn btn btn-secondary" lw-transliterate entity-type="coupons" entity-id="[[ couponEditCtrl.couponData._id ]]" entity-key="title" entity-string="[[ couponEditCtrl.couponData.title ]]" input-type="1"><i class="fa fa-globe"></i></a>
                    </div>
                </div>
            </lw-form-field>
            <!-- /Title -->

            <div class="alert alert-warning">
                <?= __tr("Please cross check all your discounts, if the product value goes below zero then user won't be able to add to cart.") ?>
            </div>

            <lw-form-radio-field field-for="product_discount_type" label="<?= __tr( 'Product Discount Type' ) ?>">

                <strong><?= __tr('Discount Type') ?> : </strong>

                <label ng-repeat="(TypeKey, productDiscountType) in couponEditCtrl.productDiscountTypes" class="radio-inline">
                    &nbsp;
                    <input ng-model="couponEditCtrl.couponData.product_discount_type" type="radio" name="product_discount_type" ng-change="couponEditCtrl.getProductDiscountData(couponEditCtrl.couponData.product_discount_type)" ng-value="[[ TypeKey ]]">

                    <span ng-show="TypeKey == 1">
                        [[ productDiscountType ]]
                    </span>

                    <span ng-show="TypeKey == 2">
                        [[ productDiscountType ]]
                    </span>

                </label>
            </lw-form-radio-field>

            <lw-form-selectize-field field-for="selected_product" label="<?= __tr( 'Products' ) ?>" class="lw-selectize" ng-if="couponEditCtrl.couponData.product_discount_type == 2">
                <selectize 
                    config='couponEditCtrl.product_select_config' 
                    class="lw-form-field" 
                    name="selected_product" 
                    ng-model="couponEditCtrl.couponData.selected_product" 
                    options='couponEditCtrl.products' 
                    placeholder="<?= __tr( 'Select Product' ) ?>" 
                    ng-required="couponEditCtrl.couponData.product_discount_type == 2"  
                    >
                </selectize>
            </lw-form-selectize-field>
            <br ng-if="couponEditCtrl.couponData.product_discount_type == 2">

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
                                    ng-change="couponEditCtrl.clearTextField(2)"
                                    ng-model="couponEditCtrl.couponData.coupon_code_required"
                                    ui-switch="" />
                            </lw-form-checkbox-field>
                            <!-- /Coupon Code Required -->
                        </span>
                    </div>
    				<input type="text" 
    					class="lw-form-field form-control"
    					name="code"
                        ng-disabled="!couponEditCtrl.couponData.coupon_code_required"
                        placeholder="<?= __tr('Enter coupon code') ?>"
    					min="3"
                        max="10" 
                        ng-required="couponEditCtrl.couponData.coupon_code_required == true" 
    					ng-model="couponEditCtrl.couponData.code" 
    				/>
                </div>
			</lw-form-field>
			<!-- /Code -->

            <!-- Usage User Per -->
            <lw-form-field field-for="uses_per_user" v-label="<?= __tr( 'Usage Per User' ) ?>" ng-show="couponEditCtrl.couponData.coupon_code_required"> 
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text lw-checkbox-group-text">
                            <!-- Coupon Code Required -->
                            <lw-form-checkbox-field class="lw-addon-field" field-for="user_per_usage" label="<?= __tr( 'Limit this coupon uses' ) ?>" advance="true">
                                <input type="checkbox" 
                                    class="lw-form-field js-switch lw-switchery-change"
                                    name="user_per_usage"
                                    ng-model="couponEditCtrl.couponData.user_per_usage"
                                    ng-change="couponEditCtrl.clearTextField(1)"
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
                        ng-change="couponEditCtrl.setNumberFieldValidation(couponEditCtrl.couponData.uses_per_user)"
                        ng-disabled="!couponEditCtrl.couponData.user_per_usage"
                        placeholder="<?= __tr('Enter discount usage per user') ?>"
                        ng-model="couponEditCtrl.couponData.uses_per_user" 
                    />
                    <div class="input-group-append" id="basic-addon1">
                        <span class="input-group-text">
                            <?= __tr('time(s) per customer') ?>
                        </span>
                    </div>
                </div>
            </lw-form-field>
            <!-- /Usage User Per -->

            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
        			<!-- Start Dates -->
        			<lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>"> 
        				<input type="text" 
        						class="lw-form-field form-control lw-readonly-control"
        						name="start"
        						lw-bootstrap-md-datetimepicker
        						ng-required="true" 
        						ng-change="couponEditCtrl.endDateUpdated(couponEditCtrl.couponData.start)"
        						options="[[ couponEditCtrl.startDateConfig ]]"
        						readonly
        						ng-model="couponEditCtrl.couponData.start" 
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
        						lw-bootstrap-md-datetimepicker
        						ng-change="couponEditCtrl.endDateUpdated(couponEditCtrl.couponData.end)"
        						options="[[ couponEditCtrl.endDateConfig ]]"
        						readonly
        						ng-model="couponEditCtrl.couponData.end" 
        					/>
        			</lw-form-field>
        			<!-- /end Dates -->
                </div>
            </div>

            <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Discount Type -->
                    <lw-form-field field-for="discount_type" label="<?= __tr('Discount Type') ?>"> 
                        <select class="lw-form-field form-control" name="discount_type" ng-options="type.id as type.name for type in couponEditCtrl.discountType" ng-model="couponEditCtrl.couponData.discount_type" ng-required="true">
                        </select>
                    </lw-form-field>
                    <!-- /Discount Type -->
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Discount Type Amount-->
                    <div ng-if="couponEditCtrl.couponData.discount_type == 1">
                    <lw-form-field field-for="discount" label="<?= __tr('Discount') ?>"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
                            </div>                  
                            <input type="number" 
                                class="lw-form-field form-control"
                                name="discount"
                                min="0.1"
                                ng-required="true" 
                                ng-model="couponEditCtrl.couponData.discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    </div>
                    <!-- /Discount Type Amount-->

                     <!-- Discount Type percentage-->
                    <div ng-if="couponEditCtrl.couponData.discount_type == 2">
                    <lw-form-field field-for="discount" label="<?= __tr('Discount') ?>"> 
                        <div class="input-group">
                            <input type="number" 
                                class="lw-form-field form-control"
                                name="discount"
                                min="0.1"
                                max="99"
                                ng-required="true" 
                                ng-model="couponEditCtrl.couponData.discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1">
                                        <span>%</span>
                                    </span>
                                </div>
                        </div>
                    </lw-form-field>
                    </div>
                    <!-- /Discount Type percentage-->
                </div>
            </div>

	        <div class="form-row">
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Max Discount Amount-->
                    <div  ng-if="couponEditCtrl.couponData.discount_type == 2">
                    <lw-form-field field-for="max_discount" label="<?= __tr('Max Discount') ?>"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
                            </div>
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                name="max_discount"
                                ng-model="couponEditCtrl.couponData.max_discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    </div>
                    <!-- /Max Discount Amount-->

                    <!-- Max Discount percentage-->
                    <div ng-if="couponEditCtrl.couponData.discount_type == 1" v-label="<?= __tr('Max Discount') ?>">
                    <lw-form-field field-for="max_discount" label="<?= __tr('Max Discount in % of order price') ?>"> 
                        <div class="input-group">
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                max="99"
                                ng-required="couponEditCtrl.couponData.discount_type == 1"
                                name="max_discount"
                                ng-model="couponEditCtrl.couponData.max_discount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1">
                                        <span>%</span>
                                    </span>
                                </div>
                        </div>
                    </lw-form-field>
                    </div>
                    <!-- /Max Discount percentage-->
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">
                    <!-- Minimum Order Amount -->
                    <lw-form-field field-for="minimum_order_amount" label="<?= __tr('Minimum Order Amount') ?>" ng-if="couponEditCtrl.couponData.product_discount_type == 1 || couponEditCtrl.couponData.product_discount_type == null"> 
                        <div class="input-group">
                            <div class="input-group-prepend" id="basic-addon1">
                                <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
                            </div>
                            <input type="number" 
                                class="lw-form-field form-control"
                                min="0.1"
                                name="minimum_order_amount"
                                ng-required="true"
                                ng-model="couponEditCtrl.couponData.minimum_order_amount" />
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
                                </div>
                        </div>
                    </lw-form-field>
                    <!-- /Minimum Order Amount -->
                </div>
            </div>

			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>">
                <a href lw-transliterate entity-type="coupons" entity-id="[[ couponEditCtrl.couponData._id ]]" entity-key="description" entity-string="[[ couponEditCtrl.couponData.description ]]" input-type="2"> 
                <i class="fa fa-globe"></i></a>
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="couponEditCtrl.couponData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Publically Available' ) ?>" title="<?= __tr( 'Status' ) ?>" advance="true" v-label="<?= __tr( 'Status' ) ?>" off-label="<?= __tr( 'Publically Unavailable' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="couponEditCtrl.couponData.active"
                    ui-switch="" />
            </lw-form-checkbox-field>
			<!-- /Status -->

            <div ng-if="couponEditCtrl.couponData.active" class="alert alert-info">
                <span ng-if="!couponEditCtrl.couponData.end">
                    <?= __tr('This coupon / discount is available from __fromDate__.', [
                        '__fromDate__' => '[[ couponEditCtrl.couponData.start ]]'
                    ]) ?>
                </span>
                <span ng-if="couponEditCtrl.couponData.end">
                    <?= __tr('This coupon / discount is available from __fromDate__ to __toDate__.', [
                        '__fromDate__'  => '[[ couponEditCtrl.formatDateTime(couponEditCtrl.couponData.start) ]]',
                        '__toDate__'    => '[[ couponEditCtrl.formatDateTime(couponEditCtrl.couponData.end) ]]'
                    ]) ?>
                </span>
                <span>
                <span ng-if="couponEditCtrl.couponData.code">
                    <?= __tr('Using __code__ coupon code.', ['__code__' => '[[ couponEditCtrl.couponData.code ]]']) ?>
                </span>
            </div>
			
		<div class="lw-dotted-line"></div>

			<!-- action button -->
			<div class="modal-footer">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-light" ng-click="couponEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
			</div>
			<!-- /action button -->
		</form>
		<!-- /form section -->
</div>