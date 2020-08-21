<div ng-controller="ManageUsersController as manageUsersCtrl">

    <div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __tr( 'Manage Users' ) ?></h3>
        <!--  /main heading  -->
            
		<div class="float-right">
            <!-- New User button -->   
			<a href="" ng-if="canAccess('manage.user.add') && canAccess('manage.user.list')" ng-click="manageUsersCtrl.addNewUser()" title="<?=  __tr('Add New User')  ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-plus"></i> <?=  __tr('Add New User') ?></a>
            <!-- /New User button -->

            <!-- user Role button -->
           <!--  <a ng-if="canAccess('manage.user.role_permission.read.list')" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Go to User Roles' ) ?>" ui-sref="role_permission"><?= __tr( 'User Roles' ) ?></a> -->
            <!--/user Role button -->
        </div>
        
    </div>

    <div class="alert alert-info" ng-if="manageUsersCtrl.isDemoMode">
        <?= __tr('To prevent spamming user email addresses are masked using ***.') ?>
    </div>    
    <!--  User Tabs  -->
    <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageUsersTabs">
        <li role="presentation" class="activeTab nav-item active">
            <a href="#activeTab" class="nav-link" aria-controls="activeTab" role="tab" data-toggle="tab" title="<?=  __tr('Active')  ?>"><?=  __tr('Active')  ?></a>
        </li>
        <li class="activeTab nav-item">
            <a href="#deleted" class="nav-link" aria-controls="deleted" role="tab" data-toggle="tab" title="<?=  __tr('Deleted')  ?>"><?=  __tr('Deleted')  ?></a>
        </li>
        <li class="neverActivated nav-item">
            <a href="#neverActivated" class="nav-link" aria-controls="never_activated" role="tab" title="<?=  __tr('Never Activated')  ?>" data-toggle="tab"><?=  __tr('Never Activated')  ?></a>
        </li>
    </ul>
    <br>
    <!--  /User Tabs  -->

    <!--  Tab panes  -->
    <div class="tab-content lw-tab-content">

        <!--  Active Users Tab  -->
        <div role="tabpanel" class="tab-pane fade in activeTab" id="activeTab">
            <!--  datatable container  -->
            
                <table class="table table-striped table-bordered" id="activeUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __tr( 'Name' ) ?></th>
                        <th><?= __tr( 'Email' ) ?></th>
                        <th><?= __tr( 'Role' ) ?></th>
                        <th><?= __tr( 'Since' ) ?></th>
                        <th><?= __tr( 'Last Login' ) ?></th>
                        <th><?= __tr( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            <!--  /datatable container  -->
        </div>
        <!--  /Active Users Tab  -->
        
        <!--  Deleted Users Tab  -->
        <div role="tabpanel" class="tab-pane fade" id="deleted">
            <!--  datatable container  -->
                <table class="table table-striped table-bordered" id="deletedUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __tr( 'Name' ) ?></th>
                        <th><?= __tr( 'Email' ) ?></th>
                        <th><?= __tr( 'Role' ) ?></th>
                        <th><?= __tr( 'Deleted on' ) ?></th>
                        <th><?= __tr( 'Last Login' ) ?></th>
                        <th><?= __tr( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            <!--  /datatable container  -->
        </div>
        <!--  /Deleted Users Tab  -->

        <!--  Never Activated Users Tab  -->
        <div role="tabpanel" class="tab-pane fade" id="neverActivated">
            <!--  datatable container  -->
                <table class="table table-striped table-bordered" id="neverActivatedUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __tr( 'Name' ) ?></th>
                        <th><?= __tr( 'Email' ) ?></th>
                        <th><?= __tr( 'Role' ) ?></th>
                        <th><?= __tr( 'Since' ) ?></th>
                        <th><?= __tr( 'Last Login' ) ?></th>
                        <th><?= __tr( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            <!--  /datatable container  -->
        </div>
        <!--  Never Activated Users Tab  -->

    </div>

</div>

<!-- User creation date Column Template -->
<script type="text/_template" id="creationDateColumnTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
    <%-__tData.formatCreatedData %></span>

</script>
<!-- /User List creation date Column Template -->

<!--  user name column template  -->
<script type="text/template" id="userNameColumnTemplate"> 
     <%= __tData.name %>
</script>
<!--  user name column template  -->

<!--  user name column template  -->
<script type="text/template" id="userLastLoginColumnTemplate"> 
    <span class="custom-page-in-menu" title="<%-__tData.last_login %>">
    <%-__tData.formatLastLoginData %></span>
    <% if(__tData.isRegisteredBy !== false) { %>
        <span class="label label-info" title="<%= __tData.isRegisteredBy %>"> <%= __tData.isRegisteredBy %> </span>
    <% }  %>
</script>
<!--  user name column template  -->

<!--  user action column template  -->
<script type="text/template" id="userActionColumnTemplate"> 
    <% if(__tData.role !== 1) { %>
		
        <% if(__tData.status === 5) { %> <!--  5 (Deleted)  -->
            <% if(__tData.canAccessRestore) { %>  
                <a title="<?=  __tr('Restore')  ?>" class="lw-btn btn btn-light btn-sm" href="" ng-click='manageUsersCtrl.restore("<%- __tData.id %>", "<%- __tData.name %>")'> <?= __tr('Restore') ?></a>
            <% } %>
        <% } else { %>
            <% if(__tData.canAccessDelete) { %>    
                <a title="<?=  __tr('Delete')  ?>" class="lw-btn btn btn-danger btn-sm delete-sw" href="" ng-click='manageUsersCtrl.delete("<%- __tData.id %>","<%- escape(__tData.name) %>")'><i class="fa fa-trash-o"></i> <?=  __tr('Delete')  ?></a>
            <% } %> 
        <% } %>
		<% if(__tData.status != 4) { %> <!--  4 (Never Activated)  -->
            <% if(__tData.canAccessChangePassword) { %>
         	    <a title="<?=  __tr('Change Password')  ?>" class="lw-btn btn btn-light btn-sm" href="" ng-click='manageUsersCtrl.changePassword("<%- __tData.id %>","<%- escape(__tData.name) %>")'> <?= __tr('Change Password') ?></a>
			<% } %> 

            <% if(__tData.canAccessUserOrder) { %>
			    <a title="<?=  __tr('User Orders')  ?>" class="lw-btn btn btn-light btn-sm" href="" ui-sref="orders.active({userID:<%= __tData.id %>})"> <?= __tr('User Orders') ?></a>
            <% } %> 
        <% } %>

        <% if(__tData.canAccessUserDetail) { %>
            <a title="<?=  __tr('User Details')  ?>" class="lw-btn btn btn-light btn-sm" href="" ng-click='manageUsersCtrl.getUserDetails(<%- __tData.id %>)'> <?= __tr('User Details') ?></a>
	    <% } %> 

        <% if(__tData.canAccessUserPermission) { %>
            <a title="<?=  __tr('Permissions')  ?>" class="lw-btn btn btn-light btn-sm" href="" ng-click="manageUsersCtrl.openPermissionDialog(<%- __tData.id %>,'<%- __tData.name %>')"> <i class="fa fa-shield" aria-hidden="true"></i>
 		    <?= __tr('Permissions') ?></a>
        <% } %>

        <% if(__tData.canAccessUserContact) { %>    
            <a title="<?=  __tr('Contact')  ?>" class="lw-btn btn btn-primary btn-sm" href="" ng-click='manageUsersCtrl.contactDialog(<%- __tData.id %>)'> <?= __tr('Contact') ?></a>
        <% } %> 
    <% } %>
</script>
<!--  /user action column template ui-sref="orders.active" -->