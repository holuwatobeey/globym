<?php 
/*
*  Component  : ShippingType
*  View       : ShippingType Controller
*  Engine     : ShippingTypeEngine  
*  File       : list.blade.php  
*  Controller : ShippingTypListController 
----------------------------------------------------------------------------- */
?>
<div ng-controller="ShippingTypListController as shippingTypeListCtrl">
   <div class="lw-section-heading-block">
      <!-- main heading -->
      <h3 class="lw-section-heading">
         <span>
         <?= __tr('Manage Shipping Method') ?>
         </span>
      </h3>

      <!-- button -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.shipping_type.write.create') && canAccess('manage.shipping_type.read.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Shipping Method' ) ?>" ui-sref="shippingType.add"><i class="fa fa-plus"></i> <?= __tr( 'Add Shipping Method' ) ?></button>

             <input type="hidden" id="lwShippingTypeDeleteTextMsg" data-message="<?= __tr( 'you want to delete __title__ shipping method') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>" data-error-text="<?= __tr( 'Unable to Delete!') ?>">
        </div>
        <!-- /button -->
   </div>
   <!-- /main heading -->
   <table class="table table-striped table-bordered" id="lwShippingTypeList" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th><?= __tr('Title') ?></th>
            <th><?= __tr('Created On') ?></th>
            <th><?= __tr('Action') ?></th>
         </tr>
      </thead>
      <tbody></tbody>
   </table>
   <div ui-view></div>
</div>

<!-- Manage Shipping date Column Template -->
<script type="text/_template" id="creationDateColumnTemplate">
   <span class="custom-page-in-menu" title="<%-__tData.createdOn %>">
    <%-__tData.formatCreatedData %></span>
</script>
<!-- /Manage Shipping date Column Template -->

<!-- action template -->
<script type="text/_template" id="shippingTypeActionColumnTemplate">
    <% if(__tData.canAccessEdit) { %>
        <button type="button" class="lw-btn btn btn-light btn-sm"  ui-sref="shippingType.edit({'shippingTypeId' : <%- __tData._id %>})"><i class="fa fa-pencil-square-o"></i> Edit</button>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
        <button type="button" class=" lw-btn btn btn-danger btn-sm" ng-click="shippingTypeListCtrl.delete('<%-__tData._id %>', '<%-__tData.title %>')"><i class="fa fa-trash-o"></i> Delete</button>
    <% }  %>
</script>
<!-- /action template -->