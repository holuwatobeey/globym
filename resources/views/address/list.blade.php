<div ng-controller="AddressListController as addressListCtrl">
	<!--  form action  -->
	<form class="lw-form lw-ng-form"
        name="addressListCtrl.[[ addressListCtrl.ngFormName ]]"
        novalidate> 
        <div class="lw-section-heading-block">
	        <!--  main heading  -->
	        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Addresses' )) <?= __tr( 'Addresses' ) ?></h3>
	        <!--  /main heading  -->
	    </div>

	    <!--  add address button  -->
	    <div class="">
	    	<a ng-click="addressListCtrl.addAddressDialog()" title="<?= __tr('Add New Address') ?>" class="pull-right btn btn-light btn-sm lw-btn"><i class="fa fa-plus"></i> <?= __tr('Add New Address') ?></a> <br><br>
	    </div>
	    <!--  /add address button  -->

		<!--  panel for address list  -->
		<div class="card" ng-hide="addressListCtrl.addressData.addresses.length === 0" ng-if="addressListCtrl.pageStatus == true">
			<!--  panel heading  -->
			<div class="card-header" >
				<h3 class="card-title"><?= __tr( 'Addresses' ) ?></h3>
			</div>
			<!--  /panel heading  -->
			<div class="list-group-item" id="address_[[address.id]]" ng-repeat="address in addressListCtrl.addressData.addresses track by address.id">
				
				<!--  primary label  -->
				<span class="label label-primary pull-right" ng-if="address.primary == 1">
					<?= __tr( 'Primary' ) ?>
				</span>
				<!--  /primary label  -->
				
				<!--  address  -->
				<address class="lw-address">
                    <strong>
                    	<span ng-bind="address.type"></span>
                    </strong><br>
					<span ng-bind="address.address_line_1"></span><br>
	                <span ng-bind="address.address_line_2"></span><br>
	                <span ng-bind="address.city"></span>,
	                <span ng-bind="address.state"></span>,
	                <span ng-bind="address.country"></span><br>
					<?= __tr( 'Pin Code' ) ?> : <span ng-bind="address.pin_code"></span>
				</address>
				<!--  /address  -->
				
                <div class="pull-right">
                    <!--  edit Action button  -->
                    <button ng-click="addressListCtrl.editAddressDialog(address.id)" title="<?= __tr('Edit') ?>" class="btn btn-light btn-sm lw-btn"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button> 
                    <!--  / edit Action button  -->

                    <!--  delete Action button  -->
                    <button href ng-click="addressListCtrl.delete(address.id)" title="<?= __tr('Delete') ?>" class="btn btn-danger btn-sm lw-btn"><i class="fa fa-trash-o"></i> <?= __tr('Delete')  ?></button>
                    <!--  / delete Action button  -->
                </div>
                
            </div>
		</div>

		<!--  no addresses found message  -->
        <div class="alert alert-info" ng-show="addressListCtrl.addressData.addresses.length === 0">
            <?= __tr('There are no addresses found.') ?>
        </div>
       	<!--  /no addresses found message  -->
       	
    </form>
	<!--  /form action  -->

</div>