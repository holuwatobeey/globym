<div ng-controller="ShippingListController as shippingListCtrl">

		<div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading">
				<span>
		        	<?= __tr( 'Manage Shipping Rules' ) ?>
		        </span>
	        </h3>
	        <!-- /main heading -->
            <!-- button -->
            <div class="pull-right">
                <button  ng-if="canAccess('manage.shipping.fetch.contries')  && canAccess('manage.shipping.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Shipping Rule' ) ?>" ui-sref="shippings.add()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Shipping Rule' ) ?></button>

                <button ng-if="canAccess('manage.shipping_type.write.create') && canAccess('manage.shipping_type.read.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add Shipping Method' ) ?>" ui-sref="shippingType.add"><i class="fa fa-plus"></i> <?= __tr( 'Add Shipping Method' ) ?></button>
            </div>
            <!-- /button -->

	    </div>
		<!-- tab heading -->
		<ul class="nav nav-tabs lw-tabs" role="tablist" id="manageShippingTab">
			<li role="presentation" class="specificCountry nav-item active">
				<a href="#specificCountry" class="nav-link" role="tab" title="<?= __tr( 'Specific Countries' ) ?>" aria-controls="specificCountry" data-toggle="tab">
					<?=  __tr('Specific Countries')  ?>
				</a>
			</li>
				
			<li class="allOtherCountries nav-item">
				<a href="#allOtherCountries" class="nav-link" role="tab" title="<?= __tr( 'All Other Countries' ) ?>" aria-controls="allOtherCountries" data-toggle="tab">
					<?=  __tr('All Other Countries')  ?>
				</a>
			</li>
		</ul>

		<br>
		<!-- /tab heading -->
		<div class="tab-content lw-tab-content">

			<div role="tabpanel" class="tab-pane fade in specificCountry" id="specificCountry">
			    <!-- datatable container -->
			    <div>
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="manageShippingList" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __tr('Country')  ?></th>
			                    <th><?=  __tr('Charge Type')  ?></th>
			                    <th><?=  __tr('Charges')  ?></th>
                                <th><?=  __tr('Shipping Method')  ?></th>
			                    <th><?=  __tr('Date')  ?></th>
			                    <th><?=  __tr('Status')  ?></th>
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
			<div role="tabpanel" class="tab-pane fade" id="allOtherCountries">
				<!-- form action -->
				<form class="lw-form lw-ng-form col-lg-6" 
						name="shippingListCtrl.[[ shippingListCtrl.ngFormName ]]" 
						ng-submit="shippingListCtrl.submit()" 
						novalidate>
                       
						<!-- Note -->
						<div class="alert alert-info alert-dismissible">
							<span>
								<strong><?= __tr( 'Please Note : ' ) ?> </strong>
								<?=  __tr('This Shipping Rule will be applicable to all other countries which are not listed in __specificCountry__ tab.', [
                                    '__specificCountry__' => '<strong>'.__('Specific Countries').'</strong>'
                                ]) ?>
							</span>
						</div>
						<!-- /Note -->

						<!-- Type -->
		        		<lw-form-field field-for="type" label="<?= __tr('Type / Availability') ?>"> 
			                <select class="lw-form-field form-control" 
				                name="type" ng-model="shippingListCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingListCtrl.shippingType" ng-required="true">
				            </select>
		            	</lw-form-field>
		        		<!-- /Type -->

		        		<!-- Charges -->
						<lw-form-field field-for="charges" label="<?= __tr( 'Charges' ) ?>" ng-if="shippingListCtrl.shippingData.type != 3 && shippingListCtrl.shippingData.type != 4"> 
							<div class="input-group">
                                <div class="input-group-prepend" id="basic-addon1" ng-if="shippingListCtrl.shippingData.type == 1">
                                    <span class="input-group-text">[[shippingListCtrl.currencySymbol]]
                                    </span>
                                </div>
							  	<input type="number" 
									class="lw-form-field form-control"
									name="charges"
									ng-required="true"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.charges" 
								/>
                                <div class="input-group-append" id="basic-addon2">
                                    <span class="input-group-text" ng-if="shippingListCtrl.shippingData.type == 1">[[shippingListCtrl.currency]]
                                    </span>
                                    <span class="input-group-text" id="basic-addon2" ng-if="shippingListCtrl.shippingData.type == 2">%
                                    </span>
                                </div>

							</div>
						</lw-form-field>
						<!-- /Charges -->

						<!-- Free After Amount -->
						<lw-form-field field-for="free_after_amount" label="<?= __tr( 'Free Shipping if Order Amount More than' ) ?>" ng-if="shippingListCtrl.shippingData.type == 1"> 
							<div class="input-group">
                                <div class="input-group-prepend" id="basic-addon1">
                                    <span class="input-group-text">[[shippingListCtrl.currencySymbol]]
                                    </span>
                                </div>
								<input type="number" 
									class="lw-form-field form-control"
									name="free_after_amount"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.free_after_amount" 
								/>
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text">[[shippingListCtrl.currency]]
                                    </span>
                                </div>
							</div>
						</lw-form-field>
						<!-- /Free After Amount -->

						<!-- Maximum Shipping Amount -->
						<lw-form-field field-for="amount_cap" label="<?= __tr( 'Maximum Shipping Amount' ) ?>" ng-if="shippingListCtrl.shippingData.type == 2"> 
							<div class="input-group">
                                <div class="input-group-prepend" id="basic-addon1">
                                    <span class="input-group-text">[[shippingListCtrl.currencySymbol]]
                                    </span>
                                </div>
								<input type="number" 
									class="lw-form-field form-control"
									name="amount_cap"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.amount_cap" 
								/>
                                <div class="input-group-append" id="basic-addon1">
                                    <span class="input-group-text">[[shippingListCtrl.currency]]
                                    </span>
                                </div>
							</div>
						</lw-form-field>
						<!-- /Maximum Shipping Amount -->
						<!-- notes -->
						<lw-form-field field-for="notes" label="<?= __tr( 'Notes' ) ?>">
                            <a href lw-transliterate entity-type="aoc_shipping" entity-id="[[ shippingListCtrl.shippingData._id ]]" entity-key="notes" entity-string="[[ shippingListCtrl.shippingData.notes ]]" input-type="2"><i class="fa fa-globe"></i></a> 

							<textarea name="notes" class="lw-form-field form-control"
			                 cols="10" rows="3" ng-model="shippingListCtrl.shippingData.notes"></textarea>
						</lw-form-field>
						<!-- /notes -->
						
						<!-- action -->
			            <div class="modal-footer">
							<button type="submit" class="lw-btn btn btn-primary btn-sm" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
						</div>
						<!-- /action -->

				</form>
				<!-- form action -->
			</div>
		</div>

</div>

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
	<% if(!__tData.canAccessDetail) { %>
	   <span><%-__tData.name %></span>
    <% }  %>

    <% if(__tData.canAccessDetail) { %>
    	<span class="custom-page-in-menu">
    		<a href ng-click="shippingListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.country %>"><%-__tData.name %></a>
    	</span>
   <% }  %>
</script>
<!-- /country column _template -->

<!-- charges column _template -->
<script type="text/template" id="chargesColumnTemplate">

   <span class="lw-datatable-price-align"><%- __tData.charges %></span>
   
</script>
<!-- /charges column _template -->


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
     	<a href="" ui-sref="shippings.edit({shippingID:<%- __tData._id %>})" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Edit' ) ?>">
     		<i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?>
     	</a>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
     	<a href="" ng-click="shippingListCtrl.delete('<%-__tData._id %>','<%-__tData.country %>')" class="lw-btn btn btn-danger btn-sm" title="<?= __tr( 'Delete' ) ?>">
     		<i class="fa fa-trash-o"></i> <?= __tr( 'Delete' ) ?>
     	</a>
    <% }  %>
</script>
<!-- list row action column  _template -->