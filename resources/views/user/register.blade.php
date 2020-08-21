<div ng-controller="UserRegisterController as registerCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Register' )) <?=  __tr( 'Register' )  ?></h3>
        <!--  /main heading  -->
    </div>

    <div class="alert alert-danger" ng-if="registerCtrl.isInActive">
       <?= __tr("Account with this email id seems to be non-active please __link__", [
            '__link__' => "<a href='".route('get.user.contact')."'>Contact</a>"
      ]) ?>
    </div>

    <div class="alert alert-danger" ng-if="!registerCtrl.isInActive && registerCtrl.errorMessage != false" ng-bind="registerCtrl.errorMessage"></div>
	
    <div class="row">
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="registerCtrl.[[ registerCtrl.ngFormName ]]" 
        ng-submit="registerCtrl.submit()" 
        novalidate>
        
        <!--  First Name  -->
        <lw-form-field field-for="first_name" label="<?=  __tr( 'First Name' )  ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="first_name"
              ng-required="true"
              ng-minlength="2"
              ng-maxlength="30"
              ng-model="registerCtrl.userData.first_name" />
        </lw-form-field>
        <!--  /First Name  -->

        <!--  Last Name  -->
        <lw-form-field field-for="last_name" label="<?=  __tr( 'Last Name' )  ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="last_name"
              ng-required="true" 
              ng-minlength="2"
              ng-maxlength="30"
              ng-model="registerCtrl.userData.last_name" />
        </lw-form-field>
        <!--  /Last Name  -->

        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __tr( 'Email' )  ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="registerCtrl.userData.email" />
        </lw-form-field>
        <!--  /Email  -->

        <!--  Password  -->
        <lw-form-field field-for="password" label="<?=  __tr( 'Password' )  ?>"> 
            <input type="password" 
              class="lw-form-field form-control"
              name="password"
              ng-minlength="6"
              ng-maxlength="30"
              ng-required="true" 
              ng-model="registerCtrl.userData.password" />
        </lw-form-field>
        <!--  /Password  -->

        <!--  Password Confirmation  -->
        <lw-form-field field-for="password_confirmation" label="<?=  __tr( 'Password Confirmation' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="password_confirmation"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="registerCtrl.userData.password_confirmation" />
        </lw-form-field>
        <!--  /Password Confirmation  -->
		
        @if(!getStoreSettings('enable_recaptcha'))
		<!--  Confirmation Code  -->
        <lw-form-field field-for="confirmation_code" label="<?=  __tr( 'Prove you are not robot' )  ?>" v-label="<?=  __tr( 'Confirmation Code' )  ?>"> 
            <div class="input-group">
                <div class="input-group-prepend" id="basic-addon1">
                    <span class="input-group-text"><img ng-src="[[ registerCtrl.captchaURL ]]" alt="" class="lw-login-captcha">
                    </span>
                </div>
                <input type="text" 
                  class="lw-form-field form-control input-lg"
                  name="confirmation_code"
                  ng-required="true" 
                  ng-model="registerCtrl.userData.confirmation_code"/>
                 <span class="input-group-append" id="basic-addon1">
                    <span class="input-group-text" >  
                        <a href="" title="<?=  __tr('Refresh Captcha')  ?>" ng-click="registerCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
                    </span>
                </span>
            </div>
        </lw-form-field>
        <!--  Confirmation Code  -->
        @endif

        @if(getStoreSettings('enable_recaptcha'))
        <div ng-if="registerCtrl.show_captcha == true">
        	<lw-form-field  class="lw-recaptcha" field-for="recaptcha" v-label="Captcha" label="<?= __tr('Verify you are not robot') ?>">
                <lw-recaptcha class="lw-form-field g-recaptcha" 
                    ng-model='registerCtrl.userData.recaptcha' 
                    name="recaptcha" 
                    sitekey="[[registerCtrl.site_key]]" ng-required="true">
                </lw-recaptcha>
            </lw-form-field>
        </div>
        @endif

		<!--  terms and conditions  -->
		@if(getStoreSettings('term_condition')) 
			<div class="lw-form-inline-elements">
				<!--  read and accept checkbox  -->
				<lw-form-checkbox-field field-for="term_condition" v-label="<?=  __tr( 'I have read and accept' )  ?>" class="lw-margin-link">
		            <input type="checkbox" 
		                class="lw-form-field"
		                name="term_condition" 
		                ng-model="registerCtrl.userData.term_condition"/>
	                <?=  __tr( 'I have read and accept' )  ?>
					<!--  /read and accept checkbox  -->
					<a  title="<?=  __tr('Terms &amp; Conditions')  ?>" 
	    				href="" ng-click="registerCtrl.showTermsAndConditionsDialog()">
						| <?=  __tr('Terms &amp; Conditions')  ?>
	        	</lw-form-checkbox-field>
				</a>
			</div>
			<!--  /terms and conditions  -->

			<!--  register button  -->
	        <div class="form-group lw-form-actions">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Register')  ?>" ng-disabled="registerCtrl.userData.term_condition != true"><?=  __tr('Register')  ?> <span></span></button>
	        </div>
	        <!--  /register button  -->
	    @else

	    	<div class="form-group lw-form-actions">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Register')  ?>"><?=  __tr('Register')  ?> <span></span></button>
	        </div>
			<!--  /action button  -->
        @endif


    </form>
</div>
</div>