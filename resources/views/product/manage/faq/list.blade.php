<?php
/*
*  Component  : ManageItem
*  View       : Faqs List
*  File       : list.blade.php  
*  Controller : ManageProductFaqsListController as manageItemFaqsListCtrl 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller='ManageProductFaqsListController as manageItemFaqsListCtrl'>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'FAQs' ) ?></h3>
        <!-- /main heading -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.product.faq.add') && canAccess('manage.product.faq.read.list')" class="lw-btn btn btn-light pull-right btn-sm" title="<?= __tr( 'Add New FAQs' ) ?>" ng-href="" ng-click="manageItemFaqsListCtrl.addFaqs()"><i class="fa fa-plus"></i> <?= __tr( 'Add New FAQs' ) ?></button>
        </div>
    </div>
    <br>

    <input type="hidden" id="lwItemFaqDeleteConfirmTextMsg" data-message="<?= __tr( 'you want to delete __name__ rating.') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr('Deleted !') ?>">

    <!-- datatable item faqs -->
    <div>
        <table id="lwItemFaqsTable" class=" table table-bordered" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?= __tr('Question') ?></th>
                    <th><?= __tr('Created On') ?></th>
                    <th><?= __tr('Action') ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
    <!-- /datatable item faqs -->
</div>

<!-- action template -->
<script type="text/_template" id="questionColumnActionTemplate">
    <a href="" ng-click="manageItemFaqsListCtrl.showDetailDialog('<%= __tData._id %>')"><%= __tData.question %></a>
</script>
<!-- /action template -->

<!-- faqs action column template -->
<script type="text/template" id="answerColumnActionTemplate">
    <span lw-expander slice-count="50">
        <%= __tData.answer %>
    </span>
</script>
<!-- /faqs action column template -->

<!-- faqs action column template -->
<script type="text/template" id="typeColumnActionTemplate">
   <%= __tData.formattedType %>
</script>
<!-- /faqs action column template -->

<!-- faqs action column template -->
<script type="text/template" id="itemfaqsColumnActionTemplate">
    <% if(__tData.canAccessEdit) { %>
        <button title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm" ng-click="manageItemFaqsListCtrl.edit('<%- __tData._id %>')" ng-href> 
            <i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% }  %>        

    <% if(__tData.canAccessDelete) { %>
        <button title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw" href="" ng-click="manageItemFaqsListCtrl.delete('<%- __tData._id %>','<%- __tData.title %>')"><i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete') ?></button> 
    <% }  %>   
</script>
<!-- /faqs action column template -->