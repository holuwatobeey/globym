<?php
/**
  *  Component  : Product
  *  View       : Add Product Faqs
  *  Engine     : ProductDetailsController.js  
  *  File       : add-faqs-dialog.blade.php  
  *  Controller : ProductFaqsDialogController as ProductFaqsCtrl 
  * ----------------------------------------------------------------------------- */
?>
<div class="lw-dialog">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h4 class="lw-section-heading">
            <?= __tr('Add Question for __title__', [
                '__title__' => '[[ ProductFaqsCtrl.productTitle ]]'
            ]) ?>
        </h4>
        <!-- /main heading -->
    </div>

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="ProductFaqsCtrl.[[ ProductFaqsCtrl.ngFormName ]]" 
        ng-submit="ProductFaqsCtrl.submitFaq()" 
        novalidate>

        <!-- Add Question -->
        <lw-form-field field-for="addQuestion" label="<?= __tr( 'Question' ) ?>"> 
            <input type="text" 
                class="lw-form-field form-control"
                name="addQuestion"
                ng-required="true"
                ng-minlength="3"
                ng-maxlength="255"
                ng-model="ProductFaqsCtrl.addFaqData.addQuestion" 
            />
        </lw-form-field>
        <!-- /Add Question -->

        <br>
        <!-- action -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
            <button type="button" class="lw-btn btn btn-light" ng-click="ProductFaqsCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
            <!-- /action -->

    </form>

</div>