<?php 
/*
*  Component  : Product
*  View       : FaqDetailController Controller
*  Engine     : FaqDetailController  
*  File       : detail-dialog.blade.php  
*  Controller : FaqDetailController as faqDetailCtrl 
----------------------------------------------------------------------------- */
?>
<div class="lw-dialog" ng-controller="FaqDetailController as faqDetailCtrl">
    
    <!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'FAQ Details' )  ?></h3>
    </div>
    <!--  /main heading  -->

    <!--  Labels  --> 
    <div class="card" ng-if="faqDetailCtrl.faqDetailData.length != 0">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?= __tr('Created By') ?>
                <span ng-bind="faqDetailCtrl.faqDetailData.createdBy" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Created On') ?>
                <span ng-bind="faqDetailCtrl.faqDetailData.createdAt" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Question') ?>
                <span ng-bind="faqDetailCtrl.faqDetailData.question" class="pull-right"></span>
            </li>
            <li class="list-group-item"><?= __tr('Answer') ?>
            <br>
                <span ng-bind-html="faqDetailCtrl.faqDetailData.answer"></span>
            </li>
        </ul>
    </div>
    <!-- / Labels  --> 

    <div class="alert alert-info" role="alert" ng-if="faqDetailCtrl.faqDetailData.length == 0">
        <?= __tr('There are no Faqs.') ?>
    </div>

    <!--  close dialog button  -->
    <div class="modal-footer">
        <button type="button" class="lw-btn btn btn-light" ng-click="faqDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!--  /close dialog button  -->
</div>