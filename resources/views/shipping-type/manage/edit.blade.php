<?php 
/*
*  Component  : ShippingType
*  View       : ShippingType Edit Controller
*  Engine     : ShippingTypeEngine  
*  File       : edit.blade.php  
*  Controller : ShippingTypeEditController as shippingTypeEditCtrl
----------------------------------------------------------------------------- */
?>
<div ng-controller="ShippingTypeEditController as shippingTypeEditCtrl" class="lw-dialog">
    <!-- Modal Heading -->
    <div class="lw-section-heading-block">
        <!-- main heading -->
            <h3 class="lw-header"><?= __tr( 'Edit Shipping Method' ) ?></h3>
        <!-- /main heading -->
    </div>
    <!-- /Modal Heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="shippingTypeEditCtrl.[[ shippingTypeEditCtrl.ngFormName ]]" 
        ng-submit="shippingTypeEditCtrl.submit()" 
        novalidate>

        <!-- Modal Body -->
        <div class="modal-body">
            
            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>">
                <div class="input-group"> 
                    <input type="text" 
                      class="lw-form-field form-control"
                      name="title"
                      ng-required="true"
                      ng-minlength="2"
                      ng-maxlength="250"
                      ng-model="shippingTypeEditCtrl.shippingTypeEditData.title" />

                    <div class="input-group-append">
                        <a href class="lw-btn btn btn-secondary" lw-transliterate entity-type="shippings_method" entity-id="[[ shippingTypeEditCtrl.shippingTypeEditData.id ]]" entity-key="title" entity-string="[[ shippingTypeEditCtrl.shippingTypeEditData.title ]]" input-type="1">
                            <i class="fa fa-globe"></i>
                        </a>
                    </div>
                </div>
            </lw-form-field>
            <!-- /Title -->
        </div>
        <!-- Modal Body -->

        <!-- /Modal footer -->
        <div class="modal-footer">
            
            <button type="submit" class="btn btn-primary lw-btn" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>

            <button type="button" class="btn btn-light lw-btn" ng-click="shippingTypeEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>

        </div>
        <!-- /Modal footer -->

    </form>
    <!-- /form action -->
</div>