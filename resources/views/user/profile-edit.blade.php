<div ng-controller="UserProfileEditController as profileEditCtrl" class="col-lg-6">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Profile Update' ))<?=  __tr( 'Profile Update' )  ?></h3>
        <!--  /main heading  -->
    </div>
	<div ng-if="profileEditCtrl.request_completed">
	    <form class="lw-form lw-ng-form" 
	        name="profileEditCtrl.[[ profileEditCtrl.ngFormName ]]" 
	        ng-submit="profileEditCtrl.submit()" 
	        novalidate>

	        <!--  First Name  -->
	        <lw-form-field field-for="first_name" label="<?=  __tr( 'First Name' )  ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="first_name"
	              ng-required="true" 
	              ng-model="profileEditCtrl.profileData.first_name" />
	        </lw-form-field>
	        <!--  First Name  -->

	        <!--  Last Name  -->
	        <lw-form-field field-for="last_name" label="<?=  __tr( 'Last Name' )  ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="last_name"
	              ng-required="true" 
	              ng-model="profileEditCtrl.profileData.last_name" />
	        </lw-form-field>
	        <!--  Last Name  -->

			<!--  update button  -->
	        <div class="modal-footer">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Update')  ?>"><?=  __tr('Update')  ?> <span></span> </button>
	        </div>
	        <!--  /update button  -->

	    </form>
    </div>

</div>