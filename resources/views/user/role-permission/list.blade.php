<?php 
/*
*  Component  : RolePermission
*  View       : Role Permission Controller
*  Engine     : RolePermissionEngine  
*  File       : rolePermission.list.blade.php  
*  Controller : RolePermissionListController 
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage User Roles') ?>
            </div>
        </h3>

        <!-- New Role Button -->
        <div class="lw-section-right-content pull-right">
            <a ng-if="canAccess('manage.user.role_permission.read.add_support_data') && canAccess('manage.user.role_permission.read.list')" title="<?=  __tr('New User Role')  ?>" class="lw-btn btn btn-light btn-sm" ng-click="rolePermissionListCtrl.showAddNewDialog()" href><i class="fa fa-plus"></i> <?=  __tr('New User Role')   ?></a>

            <!-- <a class="lw-btn btn btn-light btn-sm" ui-sref-active="active-nav" title="<?=  __tr('Back')  ?>" ui-sref="users">
                  <i class="fa fa-arrow-left"></i> <?=  __tr('Back')  ?>
                </a> -->
        </div><br><br>
        <!-- New Role Button -->
     </div>
    <!-- /main heading -->

    <input type="hidden" id="lwRolePermissionDeleteTextMsg" data-message="<?= __tr( 'you want to delete __name__ user role') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>" data-error-text="<?= __tr( 'Unable to Delete!') ?>">
    
    <table class="table table-striped table-bordered" id="lwrolePermissionList" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="80%"><?= __tr('Title') ?></th>
                <th><?= __tr('Action') ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>
            
</div> 

    <!-- action template -->
    <script type="text/_template" id="rolePermissionActionColumnTemplate">
    <% if(__tData._id !== 1 && __tData._id !== 2) { %>

        <% if((__tData.can_delete)) { %>
            <a class="lw-btn btn btn-danger btn-sm" title="<?= __tr('Delete') ?>" href="" ng-click="rolePermissionListCtrl.delete('<%- __tData._id %>', '<%- _.escape(__tData.title) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></a>
        <% } %>

        <% if(__tData.can_manage_permission) { %>
            <a title="<?=  __tr('Permissions')  ?>" class="btn btn-light lw-btn btn-sm" href ng-click="rolePermissionListCtrl.rolePermissionDialog('<%- __tData._id %>', '<%- _.escape(__tData.title) %>')"><i class="fa fa-shield"></i> <?=  __tr('Permissions')  ?></a>
        <% } %>
    <% } %>
    </script>
    <!-- /action template -->

    <!-- Role Permission delete dialog template -->
    <script type="text/ng-template" id="lw-role-permission-delete-dialog.ngtemplate">
        <p><?= __tr('Are you sure you want to delete this Role Permission?') ?></p>
        <div class="ngdialog-buttons">
            <button type="button" title="<?= __tr('Yes') ?>" class="lw-btn btn btn-primary btn-sm" ng-click="confirm()"><i class="trash icon"></i> <?= __tr('Yes') ?>            </button>
            <button type="button" title="<?= __tr('No') ?>" class="btn btn-light lw-btn btn-sm" ng-click="closeThisDialog()"><?= __tr('No') ?></button>
        </div>
    </script>
    <!-- /Role Permission delete dialog template -->