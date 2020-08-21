<?php
/*
*  Component  : ManageItem
*  View       : Awating User Email List
*  File       : list.blade.php  
*  Controller : ProductAwatingUserListController as ProductAwatingUserListCtrl 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller='ProductAwatingUserListController as ProductAwatingUserListCtrl'>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Waiting List' ) ?></h3>
        <!-- /main heading -->
    </div>
    <br>

    <input type="hidden" id="lwUserDeleteSelectedConfirmTextMsg" data-message="<?= __tr( 'You want to delete all selected user') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">

    <input type="hidden" id="lwUserDeleteConfirmTextMsg" data-message="<?= __tr( 'You want to delete selected user') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">

    <div class="float-right" ng-if="ProductAwatingUserListCtrl.selectedRows.length > 0 && ProductAwatingUserListCtrl.awatingUserList.length > 0">
        <button ng-if="canAccess('manage.product.awating_user.notify_mail.send') && canAccess('manage.product.awating_user.read.list') && (ProductAwatingUserListCtrl.productOutOfStock == 0)"  title="<?= __tr('Send Notification') ?>" class="btn btn-sm btn-primary lw-btn" href ng-click="ProductAwatingUserListCtrl.sendNotificationMailDialog(1)"><i class="fa fa-paper-plane-o"></i> <?= __tr('Send Notification') ?> </button>

        <button ng-if="canAccess('manage.product.awating_user.delete.multipleUser') && canAccess('manage.product.awating_user.read.list') && (ProductAwatingUserListCtrl.productOutOfStock == 0)" title="<?= __tr('Delete Selected') ?>" class="btn btn-sm btn-danger lw-btn"  ng-click="ProductAwatingUserListCtrl.deleteAllAwatingUser()"><i class="fa fa-trash-o"></i> <?= __tr('Delete Selected') ?> </button>
        
    </div>

    <div class="float-right" ng-if="ProductAwatingUserListCtrl.selectedRows.length == 0 && ProductAwatingUserListCtrl.awatingUserList.length > 0">

        <button ng-if="canAccess('manage.product.awating_user.notify_mail.send') && canAccess('manage.product.awating_user.read.list') && (ProductAwatingUserListCtrl.productOutOfStock == 4)" title="<?= __tr('Send Notification') ?>" ng-disabled="true" class="btn btn-sm btn-primary lw-btn"><i class="fa fa-paper-plane-o"></i> <?= __tr('Send Notification') ?> </button>

        <button ng-if="canAccess('manage.product.awating_user.delete.multipleUser') && canAccess('manage.product.awating_user.read.list') && (ProductAwatingUserListCtrl.productOutOfStock == 4)" title="<?= __tr('Delete Selected') ?>" class="btn btn-sm btn-danger lw-btn"  ng-disabled="true"><i class="fa fa-trash-o"></i> <?= __tr('Delete Selected') ?> </button>
    </div>

    <div class="table-responsive">
    	
		<table class="table table-bordered">
		  <thead>
		     <tr>
		        <th ng-if="ProductAwatingUserListCtrl.awatingUserList.length > 0">
		            <lw-select-all-checkbox 
		                checkboxes="ProductAwatingUserListCtrl.awatingUserList" 
		                all-selected="ProductAwatingUserListCtrl.allSelectedItems"
		                id="selectedStatus"
		                ng-click="ProductAwatingUserListCtrl.checkIsSelected()"
		                all-clear="ProductAwatingUserListCtrl.noSelectedItems"
		                selectedrows="ProductAwatingUserListCtrl.selectedRows"
		                >
		            </lw-select-all-checkbox>
		            
		        </th>
		        <th><?= __tr('Email') ?></th>
		        <th><?= __tr('Created On') ?></th>
		        <th><?= __tr('Action') ?></th>
		     </tr>
		  </thead>
		  <tbody>
		     <tr ng-repeat="user in ProductAwatingUserListCtrl.awatingUserList">
		        <td>
		            <input  type="checkbox" id="lw_[[user.id]]" ng-click="ProductAwatingUserListCtrl.checkIsSelected()" ng-model="user.isSelected"/><label for="lw_[[user.id]]"></label>
		        </td>
		        <td ng-bind="user.email"></td>
		        <td ng-bind="user.createdOn"></td>
		        <td>
		            <a ng-if="canAccess('manage.product.awating_user.notify_mail.send') && canAccess('manage.product.awating_user.read.list')" href ng-show="ProductAwatingUserListCtrl.productOutOfStock == 0" title="<?= __tr('Send') ?>" class="btn btn-sm btn-primary lw-btn" href ng-click="ProductAwatingUserListCtrl.sendNotificationMailDialog(2, user.id)"><i class="fa fa-paper-plane-o"></i> <?= __tr('Send') ?> </a>

		            <a ng-if="canAccess('manage.product.awating_user.delete') && canAccess('manage.product.awating_user.read.list')" class="btn btn-sm btn-danger lw-btn" title="<?= __tr('Delete') ?>" href ng-click="ProductAwatingUserListCtrl.deleteAwatingUser(user.id)"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></a>
		        </td>
		     </tr>
		     <tr ng-if="ProductAwatingUserListCtrl.awatingUserList.length == 0">
		         <td colspan="4" class="text-center">
		             <?= __tr('There are no users.') ?>
		         </td>
		     </tr>
		  </tbody>
		</table>
    </div>
	<div ui-view></div>
</div>
