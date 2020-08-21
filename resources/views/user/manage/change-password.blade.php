<div class="lw-dialog" ng-controller="ManageUserChangePasswordController as userChangePassword">
	
	<div class="lw-section-heading-block">
        <!--  main heading -->
        <h3 class="lw-section-heading"  ng-bind="userChangePassword.title">
        @section('page-title', __tr('Change Password'))</h3>
        <!--  /main heading -->
    </div>

    <!--  form action -->
    <form class="lw-form lw-ng-form" 
        name="userChangePassword.[[ userChangePassword.ngFormName ]]" 
        ng-submit="userChangePassword.submit()" 
        novalidate>

        <div class="form-row">
            <div class="form-group col-md-6">
                <!--  New Password -->
                <lw-form-field field-for="new_password" label="<?=  __tr( 'New Password' )  ?>"> 
                    <input type="password" 
                          class="lw-form-field form-control"
                          name="new_password"
                          ng-minlength="6"
                          ng-maxlength="30"
                          ng-required="true" 
                          ng-model="userChangePassword.changePasswordData.new_password" />
                </lw-form-field>
                <!--  /New Password -->
            </div>

            <div class="form-group col-md-6">
                <!--  New Password Confirmation -->
                <lw-form-field field-for="new_password_confirmation" label="<?=  __tr( 'New Password Confirmation' )  ?>">
                    <input type="password" 
                          class="lw-form-field form-control"
                          name="new_password_confirmation"
                          ng-minlength="6"
                          ng-maxlength="30"
                          ng-required="true" 
                          ng-model="userChangePassword.changePasswordData.new_password_confirmation" />
                </lw-form-field>
                <!--  /New Password Confirmation -->
		    </div>
        </div>

        <div class="modal-footer">
        	<!--  update password button -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Update Password')  ?>"><?=  __tr('Update Password')  ?> <span></span></button>
			<!--  /update password button -->

			<!--  close button -->
            <button type="button" ng-click="userChangePassword.closeDialog()" class="lw-btn btn btn-light" title="<?=  __tr('Cancel')  ?>"><?=  __tr('Cancel')  ?></button>
            <!--  close button -->
        </div>
		

    </form>
	<!--  /form action -->

</div>