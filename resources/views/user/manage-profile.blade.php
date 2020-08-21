<div ng-controller="UserProfileController as profileCtrl" class="">
   
    <div class="lw-section-heading-block">
    	<!--  main heading  -->
        <h3 class="lw-section-heading"> @section('page-title',  __tr( 'Profile' ))<?= __tr( 'Profile' ) ?></h3>
        <!--  /main heading  -->
    </div>

	<div ng-if="profileCtrl.request_completed" class="form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12">
		<!--  first name field  -->
        <div class="form-group ">
	      	<label for="fname" class="control-label"><?= __tr('First Name') ?></label>
		      <input readonly type="text" class="form-control" id="fname" value="[[ profileCtrl.profileData.first_name ]]">
	    </div>
	    <!--  first name field  -->

	    <!--  last name field  -->
		<div class="form-group">
	      	<label  for="lname" class="control-label"><?= __tr('Last Name') ?></label>
		    <input readonly type="text" class="form-control" id="lname" value="[[ profileCtrl.profileData.last_name ]]">
	    </div>
	    <!--  last name field  -->

		<!--  edit profile button  -->
      	<div class="modal-footer">
        	<a ui-sref="profileEdit" title="<?= __tr('Edit') ?>" class="btn btn-primary"><?=  __tr('Edit')  ?></a>
        </div>    
        <!--  /edit profile button  -->
    </div>
</div>