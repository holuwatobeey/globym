<?php 
/*
*  Component  : RolePermission
*  View       : Dynamic Role Permission
*  Engine     : RolePermissionEngine  
*  File       : dynamic-role-permissions.blade.php  
*  Controller : DynamicRolePermissionController 
----------------------------------------------------------------------------- */
?>
<div>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h4 class="modal-title">
        <?= __tr('Permissions of __title__ User Role', [
            '__title__' => '<strong>[[ DynamicRolePermissionCtrl.title ]]</strong>'
        ]) ?></h4>
    </div>
    <!-- /Modal Heading -->

    <form role="form" 
        class="lw-ng-form" 
        name="DynamicRolePermissionCtrl.[[ DynamicRolePermissionCtrl.ngFormName ]]"
        novalidate>
        <!-- Modal Body -->
        <div class="modal-body">
            <div class="form-group">
               <input type="text" class="form-control" ng-model="searchText" placeholder="<?= __tr('Type to Filter') ?>">
            </div>
            <div class="table-responsive">
                <table class="table small" width="100%">
                    <tbody ng-repeat="parentpermission in DynamicRolePermissionCtrl.permissions | filter:searchText" class="hover-permission">
                        <tr>
                            <td width="30%" ng-bind="parentpermission.title"></td>
                            <td>
                                <table class="table table-hover lw-table-borderless" width="100%">
                                    <tbody>
                                        <tr ng-repeat="child in parentpermission.children">
                                            <td width="40%" >[[child.title]]</td>
                                            <td>
                                                <div class="form-check-inline" ng-repeat="option in child.options">
                                                    <label class="form-check-label lw-permission-label">
                                                        <input type="radio"  value="[[option.status]]" ng-model="DynamicRolePermissionCtrl.checkedPermission[child.id]" ng-click="DynamicRolePermissionCtrl.checkPermission(child.id, option.status)" 
                                                        id="[[child.id]]" name="[[child.id]]"  > [[option.title]]
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr ng-repeat="subchildpermission in parentpermission.children_permission_group" ng-if="parentpermission.children_permission_group">
                            <td width="30%">&nbsp;&nbsp; &#8735;</i> [[subchildpermission.title]]</td>
                            <td>
                                <table class="table table-hover lw-table-borderless" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr ng-repeat="childpermission in subchildpermission.children">
                                            <td width="40%" >[[childpermission.title]]</td>
                                            <td>
                                                <div class="form-check-inline" ng-repeat="option in childpermission.options">
                                                    <label class="form-check-label lw-permission-label">
                                                        <input type="radio" ng-model="DynamicRolePermissionCtrl.checkedPermission[childpermission.id]" ng-click="DynamicRolePermissionCtrl.checkPermission(childpermission.id, option.status)"  id="[[childpermission.id]]" value="[[option.status]]"  name="[[childpermission.id]]"> [[option.title]]
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>      
        </div>
        <!-- /Modal Body -->

        <!-- /Modal footer -->
        <div class="modal-footer">
            <!--  add button -->
            <a href ng-click="DynamicRolePermissionCtrl.submit()" class="btn btn-primary lw-btn" title="<?=  __tr('Update')  ?>"><?=  __tr('Update')  ?> </a>
            <!--  /add button -->

            <!--  close button -->
            <a href ng-click="DynamicRolePermissionCtrl.closeDialog()" class="btn btn-light lw-btn" title="<?=  __tr('Cancel')  ?>"><?=  __tr('Cancel')  ?></a>
            <!--  close button -->
        </div>
        <!-- /Modal footer -->
    </form>
    <!-- /End Form -->
</div>