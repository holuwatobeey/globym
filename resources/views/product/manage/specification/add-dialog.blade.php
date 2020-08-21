<div ng-controller="ProductSpecificationAddController as addSpecificationCtrl">
	<!-- main heading -->
	 <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __tr("Add Specification") ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	 <form class="lw-form lw-ng-form" 
        name="addSpecificationCtrl.[[ addSpecificationCtrl.ngFormName ]]" 
        novalidate>
        
        <div class="form-row">
            <div class="col">
                <!-- Label -->
                <lw-form-selectize-field field-for="label" label="<?= __tr( 'Label' ) ?>"> 
                    <selectize 
                        config='addSpecificationCtrl.specification_label_select_config' 
                        class="lw-form-field form-control" 
                        name="label" 
                        ng-model="addSpecificationCtrl.specificationData.label"
                        options='addSpecificationCtrl.specificationCollection'
                        ng-change="addSpecificationCtrl.getSelectedSpecification(addSpecificationCtrl.specificationData.label)"
                        ng-required="true"
                        placeholder="<?= __tr( 'Select / Add Label' ) ?>" 
                    ></selectize>
                </lw-form-selectize-field>
                <!-- /Label -->
            </div>
     
            <div class="col">
                <!-- Value -->
                <lw-form-selectize-field field-for="value" label="<?= __tr( 'Value' ) ?>"> 
                    <selectize 
                        config='addSpecificationCtrl.specification_value_select_config' 
                        class="lw-form-field form-control" 
                        name="value" 
                        ng-model="addSpecificationCtrl.specificationData.value" 
                        options='addSpecificationCtrl.specValues' 
                        ng-required="true" 
                        placeholder="<?= __tr( 'Add Value' ) ?>" >
                    </selectize>
                </lw-form-selectize-field>  
                <!-- Value -->
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" ng-click="addSpecificationCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
            <button type="submit" ng-click="addSpecificationCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /button -->
	</form>
	<!-- /form action -->
</div>