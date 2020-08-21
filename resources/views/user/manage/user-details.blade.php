<div ng-controller="ManageUserDetailsDialog as manageUserDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'User Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	<!-- Card  -->  
    <div class="card">
        <div class="card-header">
            <strong ng-bind="manageUserDetailCtrl.userDetails.fullName"></strong>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?= __tr('Email') ?>
                <!-- email Id -->
                <span ng-if="manageUserDetailCtrl.userDetails.email != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.email"></span>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.email == ''" class="pull-right">
                    <?= __tr('NA') ?>
                </span>
                <!-- /email Id  -->
            </li>
            <li class="list-group-item"><?= __tr('Last login') ?>
                <!-- last login by user -->
                <span ng-if="manageUserDetailCtrl.userDetails.lastLogin != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.lastLogin"></span>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.lastLogin == ''" class="pull-right">
                    <?= __tr('NA') ?>
                </span>
                <!-- /last login by user -->
            </li>
            <li class="list-group-item"><?= __tr('Last logged in IP') ?>
                <!-- last ip of user -->
                <span ng-if="manageUserDetailCtrl.userDetails.lastIp != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.lastIp"></span>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.lastIp == ''" class="pull-right">
                    <?= __tr('NA') ?>
                </span>
                <!-- /last ip of user -->
            </li>
            <li class="list-group-item"><?= __tr('Created On') ?>
                <!-- user created on -->
                <span ng-if="manageUserDetailCtrl.userDetails.creationDate != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.creationDate"></span>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.creationDate == ''" class="pull-right">
                    <?= __tr('NA') ?>
                </span>
                <!-- /user created on -->
            </li>
            <li class="list-group-item"><?= __tr('Last Order Placed On') ?>
                <!-- Last order placed on -->
                <span ng-if="manageUserDetailCtrl.userDetails.lastOrder != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.lastOrder"></span>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.lastOrder == ''" class="pull-right">
                    <?= __tr('NA') ?>
                </span>
                <!-- /Last order placed on  -->
            </li>
            <li class="list-group-item"><?=  __tr('Last Order ID')  ?>
                <!-- Last Order UID  --> 
                <span ng-if="!manageUserDetailCtrl.userDetails.lastOrderUID" class="pull-right">
                    <?=  __tr('NA')  ?>
                </span>
                <span ng-if="manageUserDetailCtrl.userDetails.lastOrderUID != ''" class="pull-right">
                    <span ng-bind="manageUserDetailCtrl.userDetails.lastOrderUID"></span>
                </span>
                <!-- /Last Order UID  --> 
            </li>
            <li class="list-group-item"><?=  __tr('Total Orders')  ?>
                <span ng-bind="manageUserDetailCtrl.userDetails.totalOrder" class="pull-right"></span>
            </li>
        </ul>
    </div>
    <!-- / Card  -->

    <br>

    <!--  close button  -->
    <div class="modal-footer">
    	<button type="button" class="lw-btn btn btn-light" ng-click="manageUserDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
    <!--  /close button  -->
</div>