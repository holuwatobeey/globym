<?php
/*
*  Component  : ManageItem
*  View       : Faqs Add
*  File       : add-dialog.blade.php  
*  Controller : ProductFaqsAddController as productFaqsAddCtrl
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="ProductFaqsAddController as productFaqsAddCtrl">
    <!-- main heading -->
     <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Add New FAQ") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
     <form class="lw-form lw-ng-form" 
        name="productFaqsAddCtrl.[[ productFaqsAddCtrl.ngFormName ]]" 
        novalidate>

       
        <div class="">
             <!-- Add Question -->
            <lw-form-field field-for="question" label="<?= __tr( 'Question' ) ?>"> 
                <input type="text" 
                    class="lw-form-field form-control"
                    name="question"
                    ng-required="true"
                    ng-model="productFaqsAddCtrl.addFaqData.question" 
                />
            </lw-form-field>
            <!-- /Add Question -->

            <!-- Add Answer -->
            <lw-form-field field-for="answer" label="<?= __tr( 'Answer') ?>"> 
                <textarea 
                    name="answer" 
                    class="lw-form-field form-control" 
                    ng-required="true" 
                    ng-model="productFaqsAddCtrl.addFaqData.answer" 
                    lw-ck-editor>
                </textarea>
            </lw-form-field>
            <!-- /Add Answer -->
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer mt-2">
            <button type="submit" ng-click="productFaqsAddCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
            <button type="submit" ng-click="productFaqsAddCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /button -->
    </form>
    <!-- /form action -->
</div>