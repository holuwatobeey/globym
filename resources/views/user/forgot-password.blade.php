<div class="col-lg-12" ng-controller="UserForgotPasswordController as forgotPasswordCtrl">
   
    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Forgot Password' )) <?=  __tr( 'Forgot Password' ) ?></h3>
        <!--  /main heading  -->
    </div>
 <div class="row">
	<!--  form action  -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="forgotPasswordCtrl.[[ forgotPasswordCtrl.ngFormName ]]" 
        ng-submit="forgotPasswordCtrl.submit()" 
        novalidate>
        
        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __tr( 'Enter your email address' ) ?>" v-label="<?=  __tr( 'email' ) ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="forgotPasswordCtrl.userData.email" />
        </lw-form-field>
        <!--  /Email  -->

		<!--  submit button  -->
        <div class="form-group lw-form-actions">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Submit Request') ?>"><?=  __tr('Submit Request') ?></button>
        </div>
		<!--  /submit button  -->
    </form>
	<!--  /form action  -->
</div>
</div>