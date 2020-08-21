<?php
/*
*  Component  : ManageItem
*  View       : Faqs Edit  
*  Engine     : ManageItemEngine  
*  File       : edit-dialog.blade.php  
*  Controller : ProductFaqsEditontroller as productFaqsEditCtrl 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="ProductFaqsEditontroller as productFaqsEditCtrl">
    <!-- main heading -->
     <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Edit FAQ") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
     <form class="lw-form lw-ng-form" 
        name="productFaqsEditCtrl.[[ productFaqsEditCtrl.ngFormName ]]" 
        novalidate>

        <!-- Add Question -->
        <lw-form-field field-for="question" label="<?= __tr( 'Question' ) ?>">
            <div class="input-group">
                <input type="text" 
                    class="lw-form-field form-control"
                    name="question"
                    ng-required="true"
                    ng-model="productFaqsEditCtrl.EditFaqData.question" 
                />
                <div class="input-group-append">
                    <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="faq" entity-id="[[ productFaqsEditCtrl.EditFaqData._id ]]" entity-key="question" entity-string="[[ productFaqsEditCtrl.EditFaqData.question ]]" input-type="1">
                        <i class="fa fa-globe"></i>
                    </button>
                </div>
            </div>
        </lw-form-field>
        <!-- /Add Question -->

        <!-- Add Answer -->
        <lw-form-field field-for="answer" label="<?= __tr( 'Answer') ?>">
            <a href lw-transliterate entity-type="faq" entity-id="[[ productFaqsEditCtrl.EditFaqData._id ]]" entity-key="answer" entity-string="[[ productFaqsEditCtrl.EditFaqData.answer ]]" input-type="3">
                <i class="fa fa-globe"></i></a>
            <textarea 
                name="answer" 
                class="lw-form-field form-control" 
                ng-required="true" 
                ng-model="productFaqsEditCtrl.EditFaqData.answer" 
                lw-ck-editor>
            </textarea>
        </lw-form-field>
        <!-- /Add Answer -->
        <br>

        <div class="modal-footer">
            <button type="submit" ng-click="productFaqsEditCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="submit" ng-click="productFaqsEditCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /button -->
    </form>
    <!-- /form action -->
</div>