<?php 
/*
*  Component  : SpecificationPreset
*  View       : SpecificationPreset Controller
*  Engine     : SpecificationPresetEngine  
*  File       : list.blade.php  
*  Controller : SpecificationPresetListController 
----------------------------------------------------------------------------- */
?>
<div ng-controller="SpecificationPresetListController as SpecificationPresetListCtrl">
   <div class="lw-section-heading-block">
      <!-- main heading -->
      <h3 class="lw-section-heading">
         <span>
         <?= __tr('Manage Specification Preset') ?>
         </span>
      </h3>

        <!-- action button -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.specification_preset.write.add') && canAccess('manage.specification_preset.read.list')" class="lw-btn btn btn-sm btn-light pull-right" title="<?= __tr( 'Add New Preset' ) ?>" ui-sref="specificationsPreset.add"><i class="fa fa-plus"></i> <?= __tr( 'Add New Preset' ) ?></button>
        </div>
        <!-- action button -->

        <input type="hidden" id="lwSpecificationPresetDeleteTextMsg" data-message="<?= __tr( 'you want to delete __title__ specification preset') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>" data-error-text="<?= __tr( 'Unable to Delete!') ?>">
   </div>
   <!-- /main heading -->
   <table class="table table-striped table-bordered" id="lwSpecificationList" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th width="30%"><?= __tr('Title') ?></th>
            <th width="16%"><?= __tr('Action') ?></th>
         </tr>
      </thead>
      <tbody></tbody>
   </table>
   <div ui-view></div>
</div>

<!-- action template -->
<script type="text/_template" id="specificationLabelColumnTemplate">
    <a href="" ng-click="SpecificationPresetListCtrl.showPresetDialog('<%= __tData._id %>')"><%= __tData.title %></a>
</script>
<!-- /action template -->

<!-- action template -->
<script type="text/_template" id="specificationActionColumnTemplate">
    <% if(__tData.canAccessEdit) { %>
        <button type="button" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Edit' ) ?>"  ui-sref="specificationsPreset.edit({'presetId' : <%- __tData._id %>})"> <i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?></button>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
        <button type="button" class="lw-btn btn btn-danger btn-sm"  title="<?= __tr( 'Delete' ) ?>" ng-click="SpecificationPresetListCtrl.delete('<%-__tData._id %>', '<%-__tData.title %>')"><i class="fa fa-trash-o"></i> <?= __tr( 'Delete' ) ?></button>
    <% }  %>
</script>
<!-- /action template -->