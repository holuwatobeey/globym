<div ng-controller="CouponListController as couponListCtrl">
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Manage Coupons / Discounts' ) ?>
	        </span>
        </h3>
        <!-- /main heading -->

        <!-- action button -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.coupon.add') && canAccess('manage.coupon.list')" class="lw-btn btn btn-sm btn-light pull-right" title="<?= __tr( 'Add New Coupon / Discount' ) ?>" ng-click="couponListCtrl.openCouponDialog()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Coupon / Discount' ) ?></button>
        </div>
        <!-- action button -->
    </div>

    <div ng-show="canAccess('manage.coupon.list')">
    	<!-- Coupons for Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageCouponList">
	        <li role="presentation" ui-sref-active="active" class="nav-item active">
	            <a href="#current" class="nav-link" ng-click="couponListCtrl.tabClick($event,'coupons.current')" aria-controls="current" role="tab" data-toggle="tab" title="<?=  __tr('Current')  ?>"><?=  __tr('Current')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active" class="nav-item">
	            <a href="#expired" class="nav-link" ng-click="couponListCtrl.tabClick($event,'coupons.expired')" aria-controls="expired" role="tab" data-toggle="tab" title="<?=  __tr('Expired')  ?>"><?=  __tr('Expired')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active" class="nav-item">
	            <a href="#upcoming" class="nav-link" ng-click="couponListCtrl.tabClick($event,'coupons.upcoming')" aria-controls="upcoming" role="tab" title="<?=  __tr('Up-coming')  ?>" data-toggle="tab"><?=  __tr('Up-coming')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Coupons Tabs -->
		<div class="tab-content">
		    <!-- current Tab -->
	        <div role="tabpanel" class="tab-pane fade in active" id="current">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="currentCoupon" cellspacing="0" width="100%" ng-show="canAccess('manage.coupon.list')">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __tr('Title')  ?></th>
			                    <th><?=  __tr('Code')  ?></th>
			                    <th><?=  __tr('Start Date')  ?></th>
			                    <th><?=  __tr('End Date')  ?></th>
			                    <th><?=  __tr('status')  ?></th>
			                    <th><?=  __tr('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /current Tab -->

	        <!-- expired Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="expired">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="expiredCoupon" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __tr('Title')  ?></th>
			                    <th><?=  __tr('Code')  ?></th>
			                    <th><?=  __tr('Start Date')  ?></th>
			                    <th><?=  __tr('End Date')  ?></th>
			                    <th><?=  __tr('status')  ?></th>
			                    <th><?=  __tr('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /expired Tab -->

	        <!-- upcoming Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="upcoming">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="upcomingCoupon" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __tr('Title')  ?></th>
			                    <th><?=  __tr('Code')  ?></th>
			                    <th><?=  __tr('Start Date')  ?></th>
			                    <th><?=  __tr('End Date')  ?></th>
			                    <th><?=  __tr('status')  ?></th>
			                    <th><?=  __tr('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /upcoming Tab -->
	    </div>
    </div>

    <!-- place on date column _template -->
<script type="text/template" id="titleColumnTemplate">
    <% if(__tData.canAccessDetail) { %>
        <span class="custom-page-in-menu"><a href ng-click="couponListCtrl.detailDialog('<%-__tData._id %>')" title="<%-__tData.title %>"><%-__tData.title %></a></span>
    <% }  %>

    <% if(!__tData.canAccessDetail) { %>
        <span class="custom-page-in-menu"><a title="<%-__tData.title %>"><%-__tData.title %></a></span>
    <% }  %>
</script>
<!-- /place on date column _template -->

<!-- place on date column _template -->
<script type="text/template" id="startDateColumnTemplate">
    <span class="custom-page-in-menu" title="<%-__tData.start_date %>">
    <%-__tData.start_date %></span>
   
</script>
<!-- /place on date column _template -->

<!-- place on date column _template -->
<script type="text/template" id="endDateColumnTemplate">

    <span class="custom-page-in-menu" title="<%-__tData.end_date %>">
    <%-__tData.end_date %></span>
</script>
<!-- /place on date column _template -->

<!--  list row status column  _template -->
<script type="text/template" id="statusColumnTemplate">
 	<% if (__tData.status === 1) { %> 
        <span title="<?= __tr( 'Active' ) ?>"><i class="fa fa-eye lw-success"></i></span>
   <% } else { %>
    <span title="<?= __tr( 'Inactive' ) ?>"><i class="fa fa-eye-slash lw-danger"></i></span>
   <% } %>
</script>
<!--  list row status column  _template -->

<!-- list row action column  _template -->
<script type="text/template" id="columnActionTemplate">
    <% if(__tData.canAccessEdit) { %>
     	<a href ng-click="couponListCtrl.openEditCouponDialog('<%-__tData._id %>')" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Edit' ) ?>">
     		<i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?>
     	</a>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
     	<a href="" ng-click="couponListCtrl.delete('<%-__tData._id %>', '<%- escape(__tData.title) %>')" class="lw-btn btn btn-danger btn-sm" title="<?= __tr( 'Delete' ) ?>">
     		<i class="fa fa-trash-o"></i> <?= __tr( 'Delete' ) ?>
     	</a>
    <% }  %>
</script>
<!-- list row action column  _template -->
</div>
