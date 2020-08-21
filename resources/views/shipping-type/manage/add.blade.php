<?php 
/*
*  Component  : ShippingType
*  View       : ShippingType Add Controller
*  Engine     : ShippingTypeEngine  
*  File       : add.blade.php  
*  Controller : ShippingTypeAddController as shippingTypeAddCtrl
----------------------------------------------------------------------------- */
?>
<div ng-controller="ShippingTypeAddController as shippingTypeAddCtrl" class="lw-dialog">
    <!-- Modal Heading -->
    <div class="lw-section-heading-block">
        <!-- main heading -->
            <h3 class="lw-header"><?= __tr( 'Add New Shipping Method' ) ?></h3>
        <!-- /main heading -->
    </div>
    <!-- /Modal Heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="shippingTypeAddCtrl.[[ shippingTypeAddCtrl.ngFormName ]]" 
        ng-submit="shippingTypeAddCtrl.submit()" 
        novalidate>

        <!-- Modal Body -->
        <div>
            
            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="title"
                  ng-required="true"
                  ng-minlength="2"
                  ng-maxlength="250"
                  ng-model="shippingTypeAddCtrl.shippingTypeData.title" />
            </lw-form-field>
            <!-- /Title -->

        </div>
        <!-- Modal Body -->
        <br>
        <!-- /Modal footer -->
        <div class="modal-footer">
            
            <button type="submit" class="btn btn-primary lw-btn" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" class="btn btn-light lw-btn" ng-click="shippingTypeAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>

        </div>
        <!-- /Modal footer -->

    </form>
    <!-- /form action -->
</div>