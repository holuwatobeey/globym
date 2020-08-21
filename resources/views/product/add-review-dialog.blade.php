<?php
/*
*  Component  : Item
*  View       : Add Review
*  Engine     : ItemEngine  
*  File       : add-review-dialog.blade.php  
*  Controller : AddReviewDialogController 
----------------------------------------------------------------------------- */ 
?>
<div>

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr('Your Review For Rating') ?></h3>
        <!-- /main heading -->
    </div>

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="AddReviewDialogCtrl.[[ AddReviewDialogCtrl.ngFormName ]]" 
        novalidate>

        <!-- loader -->
        <div class="lw-main-loader lw-show-till-loading" ng-if="AddReviewDialogCtrl.pageStatus == false">
            <div class="loader"><?=  __tr('Loading...')  ?></div>
        </div>
        <!-- / loader -->

        <div ng-if="AddReviewDialogCtrl.pageStatus">

            <!-- Review -->
            <lw-form-field field-for="review" label="<?= __tr( 'Review' ) ?>"> 
                <textarea name="review" limited-options="true" class="lw-form-field form-control" ng-required="true" ng-model="AddReviewDialogCtrl.reviewData.review"></textarea>
            </lw-form-field>
            <!-- /Review -->
            
            <!-- action button -->
            <div class="lw-form-actions">
                <button type="submit" ng-click="AddReviewDialogCtrl.submitReview()" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
                <button type="submit" ng-click="AddReviewDialogCtrl.closeDialog()" class="lw-btn btn btn-light" title="<?= __tr('Not Now') ?>"><?= __tr('Not Now') ?></button>
            </div>
             <!-- /action button -->

        </div>

    </form>
    <!-- /form action -->
</div>