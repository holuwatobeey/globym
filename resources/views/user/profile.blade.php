<div ng-controller="UserProfileController as profileCtrl" class="col-lg-6">
    <div class="lw-section-heading-block"> 
        <!--  main heading  -->
        <h3 class="lw-section-heading"> @section('page-title',  __tr( 'Profile' ))<?= __tr( 'Profile' ) ?></h3>
        <!--  /main heading  -->
    </div>
	<div ng-if="profileCtrl.request_completed" >
		<!--  first name  -->
        <div class="form-group ">
	      	<label for="fname" class="control-label"><?= __tr('First Name') ?></label>
		      <input readonly type="text" class="form-control" id="fname" value="[[ profileCtrl.profileData.first_name ]]">
	    </div>
	    <!--  first name  -->

	    <!--  last name  -->
		<div class="form-group">
	      	<label  for="lname" class="control-label"><?= __tr('Last Name') ?></label>
		    <input readonly type="text" class="form-control" id="lname" value="[[ profileCtrl.profileData.last_name ]]">
	    </div>
	    <!--  last name  -->

		<!--  edit button  -->
      	<div class="form-group">
        	<a href ng-href="<?=  route('user.profile.update')  ?>" title="<?= __tr('Edit') ?>" class="btn btn-primary"><?=  __tr('Edit')  ?></a>
        </div>    
        <!--  /edit button  -->
    </div>
</div>