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
	    	<a ng-click="addressListCtrl.addAddressDialog()" title="<?= __tr('Add New Address') ?>" class="pull-right btn btn-light btn-sm lw-btn"><?= __tr('Add New Address') ?></a> <br><br>
	    </div>
	    <!--  /add address button  -->

		<!--  panel for address list  -->
			<div class="card" 
				 ng-hide="addressListCtrl.addressData.addresses.length == 0">

				<!--  panel heading  -->
				<div class="card-header" >
					<h6 class="card-title"><?= __tr( 'Addresses' ) ?></h6>
				</div>
				<!--  /panel heading  -->

				<div class="list-group-item" id="address_[[address.id]]" 
					ng-repeat="address in addressListCtrl.addressData.addresses track by address.id">
				
					<!--  primary label  -->
					<span class="label label-primary pull-right" ng-if="address.primary == 1">
						<?= e( __tr( 'Primary' ) ) ?>
					</span>
					<!--  /primary label  -->
					<label>
						<!--  select address for order  -->
						<span>
							<input 
							type="radio" 
							name="[[ addressListCtrl.selectAddress ]]" 
							ng-model="addressListCtrl.selectAddress" 
							value="[[ address.id ]]"
							ng-click="addressListCtrl.selectAddressForOrder(addressListCtrl.addressData.addresses, address.id)"
							>
						</span>
						<!--  /select address for order  -->
					
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
							<?= __tr( 'Pincode' ) ?> : <span ng-bind="address.pin_code"></span>
						</address>
						<!--  /address  -->
					</label>
				

					<!--  make primary radio button  -->
					<div class="radio pull-right" ng-if="address.primary == false">
						<label>
							<input type="radio" name="primary" id="primary" ng-model="address.addressprimary" ng-change="addressListCtrl.makePrimaryAddress(address.id)" value="[[address.primary]]">  <?= __tr('Make Primary') ?>
						</label>
					</div>
					<!--  make primary radio button  -->
					
		            <!--  edit Action button  -->
		            <div>
			            <a ng-click="addressListCtrl.editAddressDialog(address.id)" title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></a> 
			            <!--  / edit Action button  -->

			            <!--  delete Action button  -->
			            <a href ng-click="addressListCtrl.delete(address.id)" title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw"><i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete')  ?></a>
		            </div>
		            <!--  / delete Action button  -->
	        	</div>
			</div>

			<!--  no adress found message  -->
	        <div class="alert alert-info" ng-show="addressListCtrl.addressData.addresses.length == 0">
	            <?= __tr('There are no address found') ?>
	        </div>
	       	<!--  /no adress found message  -->
	       	
	       	<!--  Action button  --> 
			<div class="modal-footer">

				<!--  close button  --> 
		        <button type="button" ng-click="addressListCtrl.close()" class="lw-btn btn btn-light" title="<?=  __tr('Close') ?>"><?= __tr('Close') ?></button>
		        <!--  close button  --> 

			</div>
			<!--  /Action button  --> 
	</form>
</div>