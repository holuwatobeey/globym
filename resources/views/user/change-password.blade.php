<div ng-controller="UserChangePasswordController as updatePasswordCtrl" class="col-lg-6">

    <div class="lw-section-heading-block">
        <!--  main heading -->
        <h3 class="lw-section-heading"><?=  __tr( 'Change Password' )  ?> @section('page-title', __tr('Change Password'))</h3>
        <!--  /main heading -->
    </div>

    @if(isLoggedIn())
        <div class="alert alert-info lw-row">
            <center><?= __tr('If you have logged in / registered via Social Accounts (Google, Facebook etc) & didn’t reset your password before then logged out of your account and use forgot password functionality to reset it. ') ?></center>
        </div>
    @endif

	<!--  form action -->
    <form class="lw-form lw-ng-form" 
        name="updatePasswordCtrl.[[ updatePasswordCtrl.ngFormName ]]" 
        ng-submit="updatePasswordCtrl.submit()" 
        novalidate>
        
        <!--  Current Password -->
        <lw-form-field field-for="current_password" label="<?=  __tr( 'Current Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="current_password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  autofocus
                  ng-model="updatePasswordCtrl.userData.current_password" />
        </lw-form-field>
        <!--  /Current Password -->

        <!--  New Password -->
        <lw-form-field field-for="new_password" label="<?=  __tr( 'New Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="new_password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="updatePasswordCtrl.userData.new_password" />
        </lw-form-field>
        <!--  /New Password -->

        <!--  New Password Confirmation -->
        <lw-form-field field-for="new_password_confirmation" label="<?=  __tr( 'New Password Confirmation' )  ?>">
            <input type="password" 
                  class="lw-form-field form-control"
                  name="new_password_confirmation"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="updatePasswordCtrl.userData.new_password_confirmation" />
        </lw-form-field>
        <!--  /New Password Confirmation -->
		<br>
		<!--  update password button -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Update Password')  ?>"><?=  __tr('Update Password')  ?> <span></span></button>
        </div>
		<!--  /update password button -->

    </form>
	<!--  /form action -->
</div>