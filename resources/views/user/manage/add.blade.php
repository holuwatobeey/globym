<div ng-controller="UserAddController as UserAddCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?=  __tr( 'Add New User' )  ?></h3>
        <!--  /main heading  -->
    </div>
    
    <form class="lw-form lw-ng-form" 
        name="UserAddCtrl.[[ UserAddCtrl.ngFormName ]]" 
        ng-submit="UserAddCtrl.submit()" 
        novalidate>
        
        <div class="form-row">
            <div class="col-lg-6 col-md-6 col-sm-12">
    	        <!--  First Name  -->
    	        <lw-form-field field-for="fname" label="<?=  __tr( 'First Name' )  ?>"> 
    	            <input type="text" 
    	              class="lw-form-field form-control"
    	              name="fname"
    	              ng-required="true"
    	              ng-minlength="2"
    	              ng-maxlength="30"
    	              ng-model="UserAddCtrl.userData.fname" />
    	        </lw-form-field>
    	        <!--  /First Name  -->
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
    	        <!--  Last Name  -->
    	        <lw-form-field field-for="lname" label="<?=  __tr( 'Last Name' )  ?>"> 
    	            <input type="text" 
    	              class="lw-form-field form-control"
    	              name="lname"
    	              ng-required="true" 
    	              ng-minlength="2"
    	              ng-maxlength="30"
    	              ng-model="UserAddCtrl.userData.lname" />
    	        </lw-form-field>
    	        <!--  /Last Name  -->
            </div>
        </div>
        
        <div class="form-row">    
            <div class="col-lg-6 col-md-6 col-sm-12">
    	        <!--  Email  -->
    	        <lw-form-field field-for="email" label="<?=  __tr( 'Email' )  ?>"> 
    	            <input type="email" 
    	              class="lw-form-field form-control"
    	              name="email"
    	              ng-required="true" 
    	              ng-model="UserAddCtrl.userData.email" />
    	        </lw-form-field>
    	        <!--  /Email  -->
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <!-- User Roles -->
                <lw-form-field field-for="role" label="<?= __tr( 'Role' ) ?>"> 
                   <select class="form-control" 
                        name="role" ng-model="UserAddCtrl.userData.role" ng-options="userRole.id as userRole.name for userRole in UserAddCtrl.userRoles" ng-required="true">
                        <option value='' disabled selected><?=  __tr('-- Select Role --')  ?></option>
                    </select> 
                </lw-form-field>
                <!-- /User Roles-->
            </div>
	    </div>

	    <div class="form-row">
            <div class="form-group col-md-6">
    	        <!--  Password  -->
    	        <lw-form-field field-for="password" label="<?=  __tr( 'Password' )  ?>"> 
    	            <input type="password" 
    	              class="lw-form-field form-control"
    	              name="password"
    	              ng-minlength="6"
    	              ng-maxlength="30"
    	              ng-required="true" 
    	              ng-model="UserAddCtrl.userData.password" />
    	        </lw-form-field>
    	        <!--  /Password  -->
            </div>
            
            <div class="form-group col-md-6">    
    	        <!--  Password Confirmation  -->
    	        <lw-form-field field-for="password_confirmation" label="<?=  __tr( 'Password Confirmation' )  ?>"> 
    	            <input type="password" 
    	                  class="lw-form-field form-control"
    	                  name="password_confirmation"
    	                  ng-minlength="6"
    	                  ng-maxlength="30"
    	                  ng-required="true" 
    	                  ng-model="UserAddCtrl.userData.password_confirmation" />
    	        </lw-form-field>
    	        <!--  /Password Confirmation  -->
            </div>
    </div>

    <!--  Action button  -->
    <div class="modal-footer">
        <!--  add button  -->
        <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?> <span></span></button>
        <!--  /add button  -->

        <!--  cancel button  -->
        <button type="button" class="lw-btn btn btn-light" ng-click="UserAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>
        <!--  /cancel button  -->
    </div>
    <!--  /Action button  -->

    </form>

</div>