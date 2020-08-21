<div ng-controller="AddressAddController as addressCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Add New Address' ))<?= __tr( 'Add New Address' ) ?></h3>
        <!--  /main heading  -->
    </div>
    
        <form class="lw-form lw-ng-form"
        name="addressCtrl.[[ addressCtrl.ngFormName ]]"
        ng-submit="addressCtrl.submit()"
        novalidate>

       <!--  address Type  -->
        <lw-form-field field-for="type" label="<?= __tr( 'Type' ) ?>"> 
           <select class="form-control" 
                name="type" ng-model="addressCtrl.userData.type" ng-options="type.id as type.name for type in addressCtrl.addressType" ng-required="true">
                <option value='' disabled selected>-- Select --</option>
            </select> 
        </lw-form-field>
        <!--  /address Type  -->


        <!--  Address Line 1  -->
        <lw-form-field field-for="address_line_1" label="<?= __tr( 'Address 1' ) ?>"> 
            <input type="text"  
              class="lw-form-field form-control"
              name="address_line_1"
              ng-required="true" 
              ng-model="addressCtrl.userData.address_line_1" />
        </lw-form-field>
        <!--  /Address Line 1  -->

        <!--  Address Line 2  -->
        <lw-form-field field-for="address_line_2" label="<?= __tr( 'Address 2' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="address_line_2"
              ng-required="true" 
              ng-model="addressCtrl.userData.address_line_2" />
        </lw-form-field>
        <!--  /Address Line 2  -->

        <!--  City  -->
        <lw-form-field field-for="city" label="<?= __tr( 'City' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="city"
              ng-required="true" 
              ng-model="addressCtrl.userData.city" />
        </lw-form-field>
        <!--  /City  -->

        <!--  State  -->
        <lw-form-field field-for="state" label="<?= __tr( 'State' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="state"
              ng-required="true" 
              ng-model="addressCtrl.userData.state" />
        </lw-form-field>
        <!--  /State  -->

        <!--  Country  -->
        <lw-form-field field-for="country" label="<?= __tr( 'Country' ) ?>"> 
           <selectize config='addressCtrl.countries_select_config' class="lw-form-field" name="country" ng-model="addressCtrl.userData.country" options='addressCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ng-required="true"></selectize>
        </lw-form-field>
      <!--  /Country  -->

        <!--  Pin Code  -->
        <lw-form-field field-for="pin_code" label="<?= __tr( 'Pin Code' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="pin_code"
              ng-required="true" 
              ng-model="addressCtrl.userData.pin_code" />
        </lw-form-field>
        <!--  /Pin Code  -->


        <!--  address Make Primary  -->
        <lw-form-checkbox-field field-for="make_primary" label="<?= __tr( 'Make Primary' ) ?>" title="<?= __tr('Make Primary') ?>">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="make_primary"
                ng-model="addressCtrl.userData.make_primary" 
                ui-switch=""/>
        </lw-form-checkbox-field>
        <!--  /address Make Primary  -->
        
		<div class="lw-dotted-line"></div>

		<!--  action button  -->
        <div class="modal-footer">
        	<!--  submit button  -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?> <span></span></button>
            <!--  /submit button  -->

            <!--  cancel button  -->
            <button type="button" ng-click="addressCtrl.close()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
            <!--  /cancel button  -->
        </div>
		<!--  /action button  -->


    </form>

</div>