<div ng-controller="ProductOptionEditController as editOptionCtrl" class="lw-dialog">
	<!-- main heading -->
    <div class="lw-section-heading-block">
         <h3 class="lw-header"> <?= __tr("Edit Option") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="editOptionCtrl.[[ editOptionCtrl.ngFormName ]]" 
        novalidate>

        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>">
            <div class="input-group">
                <input type="text" 
                  class="lw-form-field form-control"
                  name="name"
                  ng-required="true" 
                  autofocus
                  ng-model="editOptionCtrl.optionData.name" />
                <div class="input-group-append">
                    <button type="btton" class="lw-btn btn btn-secondary" lw-transliterate entity-type="option" entity-id="[[ editOptionCtrl.optionID ]]" entity-key="name" entity-string="[[ editOptionCtrl.optionData.name ]]" input-type="1">
                        <i class="fa fa-globe"></i>
                    </button>
                </div>
            </div>
        </lw-form-field>
        <!-- /Name -->
                 
		<!-- button -->
        <div class="modal-footer">
            <button type="submit" ng-click="editOptionCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
            <button type="submit" ng-click="editOptionCtrl.cancel()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        </div>
		<!-- /button -->

    </form>
	<!-- /form action -->
</div>
