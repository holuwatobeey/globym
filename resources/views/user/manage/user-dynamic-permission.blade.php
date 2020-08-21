<?php
/*
*  Component  : Manage User
*  View       : User Dynamic Permission
*  Engine     : ManageUsersController  
*  File       : user-dynamic-permission.blade.php  
*  Controller : ManageUsersDynamicPermissionController 
----------------------------------------------------------------------------- */ 
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <?= __tr( 'Manage Permission of __fullName__', [
                '__fullName__' => '<strong>[[ manageUsersDynamicPermissionCtrl.fullName ]]</strong> ([[ manageUsersDynamicPermissionCtrl.userRoleTitle ]])', 
            ]) ?>
        </h3>
    </div>
    <!-- /Modal Heading -->
   
    <!-- form start -->
    <form role="form" 
        class="lw-ng-form" 
        name="manageUsersDynamicPermissionCtrl.[[ manageUsersDynamicPermissionCtrl.ngFormName ]]"
        novalidate>
        <!-- Modal Body -->
        <div class="modal-body">
            <br>
            <div>
                <input class="form-control" placeholder="<?= __tr('Filter Permission') ?>" ng-model="search" placeholder="Filter Permission" >
            </div>
            <br>

            <div class="table-responsive">
                <table class="table small lw-table-borderless" width="100%">
                    <tbody ng-repeat="parentpermission in manageUsersDynamicPermissionCtrl.permissions | filter:search" class="hover-permission">
                        <tr>
                            <td width="30%" ng-bind="parentpermission.title"></td>
                            <td>
                                <table class="table table-hover lw-table-borderless" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr ng-repeat="child in parentpermission.children">
                                            <td width="35%" >[[child.title]]</td>
                                            <td>

                                            	<div class="form-check form-check-inline" ng-repeat="option in child.options">
													<input class="form-check-input" type="radio" ng-model="manageUsersDynamicPermissionCtrl.checkedPermission[child.id]" ng-click="manageUsersDynamicPermissionCtrl.checkPermission(child.id, option.status)"  value="[[option.status]]"
	                                                id="[[child.id]]_[[$index]]" name="[[child.id]]">
													<label class="form-check-label" for="[[child.id]]_[[$index]]">[[option.title]]</label>
												</div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr ng-repeat="subchildpermission in parentpermission.children_permission_group" ng-if="parentpermission.children_permission_group">
                            <td width="30%">&nbsp;&nbsp; &#8735; [[subchildpermission.title]]</td>
                            <td>
                                <table class="table table-hover lw-table-borderless" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr ng-repeat="childpermission in subchildpermission.children">
                                            <td width="35%"> [[childpermission.title]]</td>
                                            <td>

                                            	<div class="form-check form-check-inline" ng-repeat="option in childpermission.options">
													<input class="form-check-input" type="radio" ng-model="manageUsersDynamicPermissionCtrl.checkedPermission[childpermission.id]" ng-click="manageUsersDynamicPermissionCtrl.checkPermission(childpermission.id, option.status)"  id="[[childpermission.id]]_[[$index]]" value="[[option.status]]"  name="[[childpermission.id]]">
													<label class="form-check-label" for="[[childpermission.id]]_[[$index]]">[[option.title]]</label>
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

            <a href class="btn btn-primary lw-btn" title="<?=  __tr('Update')  ?>" ng-click="manageUsersDynamicPermissionCtrl.submit()"><?=  __tr('Update')  ?> </a>
            <!--  /add button -->

            <!--  close button -->
            <a href ng-click="manageUsersDynamicPermissionCtrl.closeDialog()" class="btn btn-light lw-btn" title="<?=  __tr('Cancel')  ?>"><?=  __tr('Cancel')  ?></a>

            <!--  close button -->
        </div>
        <!-- /Modal footer -->
    </form>
    <!-- /End Form -->
</div>