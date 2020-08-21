<div ng-controller="TaxListController as taxListCtrl">
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Manage Taxes' ) ?>
	        </span>
        </h3>
        <!-- /main heading -->

        <!-- button -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.tax.add') && canAccess('manage.tax.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Tax' ) ?>" ui-sref="taxes.add()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Tax' ) ?></button>

            <button ng-if="canAccess('store.settings.edit.supportdata')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Settings' ) ?>" ng-click="taxListCtrl.taxSettingDialog()"><i class="fa fa-gear"></i> <?= __tr( 'Settings' ) ?></button>
        </div>
        <!-- /button -->
    </div>

    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="manageTaxList" cellspacing="0" width="100%" ng-show="canAccess('manage.tax.list')">
            <thead class="page-header">
                <tr>
                    <th><?=  __tr('Label')  ?></th>
                    <th><?=  __tr('Country')  ?></th>
                    <th><?=  __tr('Type')  ?></th>
                    <th><?=  __tr('Applicable Tax')  ?></th>
                    <th><?=  __tr('Date')  ?></th>
                    <th><?=  __tr('status')  ?></th>
                    <th><?=  __tr('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
	</div>
	<!-- /datatable container -->
    
	<div ui-view></div>
</div>

<!-- label column _template -->
<script type="text/template" id="labelColumnTemplate">
    <% if(__tData.canAccessDetail) { %>
        <span class="custom-page-in-menu"><a href ng-click="taxListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.label %>"><%-__tData.label %></a></span>
    <% }  %>

    <% if(!__tData.canAccessDetail) { %>
        <span class="custom-page-in-menu"><a title="<%-__tData.label %>"><%-__tData.label %></a></span>
    <% }  %>
</script>
<!-- /label column _template -->

<!-- label column _template -->
<script type="text/template" id="applicableTaxColumnTemplate">

   <span class="lw-datatable-price-align"><%- __tData.applicable_tax %></span>
   
</script>
<!-- /label column _template -->

<!-- place on date column _template -->
<script type="text/template" id="creationDateColumnTemplate">
   <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
    <%-__tData.formatCreatedData %></span>
</script>
<!-- /place on date column _template -->

<!-- type column _template -->
<script type="text/template" id="typeColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.type %></span>
   
</script>
<!-- /type column _template -->

<!-- country column _template -->
<script type="text/template" id="countryColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.name %></span>
   
</script>
<!-- /country column _template -->


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
     	<a href ui-sref="taxes.edit({taxID:<%- __tData._id %>})" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Edit' ) ?>">
     		<i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?>
     	</a>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
     	<a href="" ng-click="taxListCtrl.delete('<%-__tData._id %>')" class="lw-btn btn btn-danger btn-sm" title="<?= __tr( 'Delete' ) ?>">
     		<i class="fa fa-trash-o"></i> <?= __tr( 'Delete' ) ?>
     	</a>
    <% }  %>
</script>
<!-- list row action column  _template -->