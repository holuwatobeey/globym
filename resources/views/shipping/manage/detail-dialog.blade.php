<div class="lw-dialog" ng-controller="ShippingDetailController as shippingDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Shipping Rule Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	
	<div>
	  <!--  Table  -->
	  <div class="table-responsive">
		  <table class="table table-bordered" border="1">
		  	<tbody>
		    	<tr>
		    		<!-- / Country  --> 
		    		<th><?=  __tr('Country')  ?></th>
		    		<td ng-bind="shippingDetailCtrl.shippingDetail.country"></td>
		    		<!-- / Country  --> 
		    	</tr>
		    	<tr>
		    		<!-- / Shipping Type  --> 
		    		<th><?=  __tr('Shipping Type')  ?></th>
		    		<td ng-bind="shippingDetailCtrl.shippingDetail.type"></td>
		    		<!-- / Shipping Type  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.charges != null">
		    		<!-- / Charges  --> 
		    		<th><?=  __tr('Charges')  ?></th>
		    		<td>
		    			<span ng-if="shippingDetailCtrl.shippingDetail.shippingType == 1">
		    				<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		    			</span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.charges"></span>
		   				<span ng-if="shippingDetailCtrl.shippingDetail.shippingType == 2">%</span>
		    		</td>
		    		<!-- / Charges  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.free_after_amount != null">
		    		<!-- / Free after Amount  --> 
		    		<th><?=  __tr('Free Shipping if Order Amount more than')  ?></th>
		    		<td>
		    			<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.free_after_amount"></span>
		    		</td>
		    		<!-- / Free after Amount  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.amount_cap != null">
		    		<!-- / Amount Cap  --> 
		    		<th><?=  __tr('Maximum Shipping Amount')  ?></th>
		    		<td>
		    			<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.amount_cap"></span>
		    		</td>
		    		<!-- / Amount Cap  --> 
		    	</tr>
		   	</tbody>
		  </table>
		  <!--  /Table  -->  
	   </div>
	</div>
	
	<!--  Notes  --> 
	<div class="card" ng-if="shippingDetailCtrl.shippingDetail.notes != ''">
	  <div class="card-header"><strong><?=  __tr('Notes')  ?></strong></div>
	  <div class="card-body" ng-bind="shippingDetailCtrl.shippingDetail.notes"></div>
	</div>
	<!-- / Notes  --> 

	<!--  close button  -->
	<div class="modal-footer">
   		<button type="button" class="lw-btn btn btn-light" ng-click="shippingDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!--  /close button  -->
</div>