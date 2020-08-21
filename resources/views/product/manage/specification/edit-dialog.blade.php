<div ng-controller="ProductSpecificationEditController as editSpecificationCtrl">
	<!-- main heading -->
	 <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Edit Specification") ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
        name="editSpecificationCtrl.[[ editSpecificationCtrl.ngFormName ]]" 
        novalidate>

        <div class="row">
    		<div class="col-sm-6 form-group">
                <!-- Name -->
                <lw-form-field field-for="[[ $index ]].name" label="<?= __tr( 'Name' ) ?>"> 
                    <input type="text" 
                      class="lw-form-field form-control"
                      name="[[ $index ]].name"
                      ng-required="true" 
                      autofocus
                      ng-model="editSpecificationCtrl.specificationData.name" />
                </lw-form-field>
                <!-- Name -->
    		</div>

            <!-- Value -->
            <div class="col-sm-6 form-group">

                <!-- Value -->
                <lw-form-field field-for="[[ $index ]].value" label="<?= __tr( 'Value' ) ?>"> 
                    <input type="text" 
                        class="lw-form-field form-control"
                        name="[[ $index ]].value"
                        ng-required="true" 
                        autofocus
                        ng-model="editSpecificationCtrl.specificationData.value" />
                </lw-form-field>
                <!-- Value -->
            </div>
            <!-- /Value -->
        </div>
        
		<!-- action button -->
		<div class="modal-footer">
            <button type="submit" ng-click="editSpecificationCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="submit" ng-click="editSpecificationCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- action button -->
	</form>
	<!-- /form action -->
</div>