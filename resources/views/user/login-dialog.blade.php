<div ng-controller="UserLoginController as loginCtrl">
    @if(!empty(Session::get('invalidUserMessage')))
    <div class="alert alert-danger">
        <!--  invalid user message  -->
        <?= Session::get('invalidUserMessage') ?>
        <!--  /invalid user message  -->
    </div>
    @endif
    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Login' )) <?=  __tr( 'Login' )  ?></h3>
        <!--  /main heading  -->
    </div>
    <div class="alert alert-danger" ng-if="loginCtrl.isInActive">
        <?= __tr("Account with this email id seems to be non-active please __link__", [
            '__link__' => "<a href='".route('get.user.contact')."'>Contact</a>"
            ]) ?>
    </div>

    <div class="row">
    	<div class="col-lg-6 col-md-12 col-sm-12 col-12">
	        <form class="lw-form lw-ng-form" 
	            name="loginCtrl.[[ loginCtrl.ngFormName ]]" 
	            ng-submit="loginCtrl.submitDialogLogin()" 
	            novalidate>

	            @if(isDemo())
	            <!-- Select User -->
	            <lw-form-field field-for="select_demo_user" label="<?= __tr('Choose Demo User') ?>"> 
	                <select class="form-control" 
	                    name="select_demo_user" ng-model="loginCtrl.select_demo_user" ng-change="loginCtrl.addUserData(loginCtrl.select_demo_user)">
	                    <option value='' disabled selected><?=  __tr('-- Choose Demo User --')  ?></option>
	                    <option value="1"><?=  __tr( 'Demo Admin' )  ?></option>
                        <option value="2"><?=  __tr( 'Demo Customer' )  ?></option>
	                </select> 
	            </lw-form-field>
	            <!-- /Select User -->
	            @endif
	            
	            <!--  Email  -->
	            <lw-form-field field-for="email" label="<?=  __tr( 'Email' )  ?>"> 
	                <input type="email" 
	                    class="lw-form-field form-control"
	                    name="email"
	                    ng-required="true" 
	                    ng-model="loginCtrl.loginData.email" />
	            </lw-form-field>
	            <!--  Email  -->
	            <!--  Password  -->
	            <lw-form-field field-for="password" label="<?=  __tr( 'Password' )  ?>">
	                <div class="input-group">
	                    <input type="password" 
	                        class="lw-form-field form-control"
	                        name="password"
	                        ng-minlength="6"
	                        ng-maxlength="30"
	                        ng-required="true" autocomplete="off"
	                        ng-model="loginCtrl.loginData.password" />
	                    <span class="input-group-append">
	                        <span class="input-group-text"> <a href="<?=  route('user.forgot_password')  ?>" title="<?=  __tr('Forgot Password?')  ?>"><?=  __tr('Forgot Password?')  ?></a>
	                        </span>
	                    </span>
	                </div>
	            </lw-form-field>
	            <!--  Password  -->

	            @if(!getStoreSettings('enable_recaptcha'))
	            <div ng-if="loginCtrl.show_captcha == true">
	                <!--  Confirmation Code  -->
	                <lw-form-field field-for="confirmation_code" label="<?=  __tr( 'I know you are a human' )  ?>">
	                    <div class="input-group">
	                        <div class="input-group-prepend" id="basic-addon1">
	                            <span class="input-group-text"><img ng-src="[[ loginCtrl.captchaURL ]]" alt="" class="lw-login-captcha">
	                            </span>
	                        </div>
	                        <input type="text" 
	                            class="lw-form-field form-control input-lg"
	                            name="confirmation_code"
	                            ng-required="true" 
	                            ng-model="loginCtrl.loginData.confirmation_code" />
	                        <span class="input-group-append" id="basic-addon1">
	                            <span class="input-group-text" >  
	                                <a href="" title="<?=  __tr('Refresh Captcha')  ?>" ng-click="loginCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
	                            </span>
	                        </span>
	                    </div>
	                </lw-form-field>
	                <!--  Confirmation Code  -->
	            </div>
	            @endif

	            @if(getStoreSettings('enable_recaptcha')) 
	            <lw-form-field  ng-if="loginCtrl.show_captcha == true" class="lw-recaptcha lw-dialog-recaptcha" field-for="recaptcha" v-label="Captcha" label="<?= __tr('Verify you are not robot') ?>">
	                <lw-recaptcha class="lw-form-field g-recaptcha" 
	                    ng-model='loginCtrl.loginData.recaptcha' 
	                    name="recaptcha" 
	                    sitekey="[[loginCtrl.site_key]]" ng-required="loginCtrl.show_captcha == true">
	                </lw-recaptcha>
	            </lw-form-field>
	            @endif

	            <div class="form-row mb-3">
	            	<div class="col-lg-12 col-md-12">
	            		<div class="form-check">
							<input type="checkbox" 
	                            class="lw-form-field form-check-input"
	                            name="remember_me"
	                            ng-model="loginCtrl.loginData.remember_me" />
							<label class="form-check-label" for="remember_me">
								<?=  __tr( 'Remember me' )  ?>
							</label>

							<span class="small">  <a href="<?=  route('user.resend.activation.email.fetch.view')  ?>"> | <?= __tr("Didn't received activation key yet? Request again") ?></a></span>
						</div>
	            	</div>
	            </div>

	    		<!--  button  -->
	            <div class="form-group">

	                <button type="submit" class="lw-btn btn btn-success" title="<?=  __tr('Login')  ?>">
	                	<?=  __tr('Login')  ?></button>
	                       
	            </div>
	    		<!--  /button  -->
	        </form>
	        
	        <div ng-if="loginCtrl.guestLogin">
	        	@if(getStoreSettings('enable_guest_order') and getStoreSettings('register_new_user'))
					<div class="">
		                <div class="mb-3">
		                    <span class="text-primary">
		                        <?= __tr('OR') ?>
		                    </span>
		                </div>
		                <div class="form-group"> 
		                    <a class="lw-btn btn btn-success" title="<?=  __tr('Continue As Guest')  ?>" href="<?= route('order.summary.view') ?>"><?=  __tr('Continue As Guest')  ?></a>
		                </div>
		            </div> 
		        @endif 
		    </div>
    	</div>


        <!--  /form action  -->
        <div class="col-lg-6 col-md-12 col-sm-12 col-12 lw-social-login-btn text-center">
            @if(getStoreSettings('register_new_user'))
            <h6><?= __tr( 'Not Registered Yet?' ) ?></h6>
            <a class="lw-btn lw-register-now-btn lw-responsive-btn btn btn-lg btn-warning lw-show-process-action" title="<?=  __tr('Register Now')  ?>" href="<?=  route('user.register')  ?>"><?=  __tr('Register Now')  ?></a>
            <!--  button  -->

                @if(getStoreSettings('allow_facebook_login')
                or getStoreSettings('allow_google_login')
                or getStoreSettings('allow_twitter_login'))
                <h5> <?= __tr('OR') ?></h5>
                @endif
            @endif
            
            <!-- check if terms and condition enabled or disabled -->
            @if (getStoreSettings('allow_facebook_login'))
            <a class="btn btn-primary lw-responsive-btn lw-show-process-action" href="{!! route('social.user.login', [getSocialProviderKey('facebook')]) !!}"><i class="fa fa-facebook"></i> <?= __tr('Sign in with Facebook') ?></a>
            @endif
            @if (getStoreSettings('allow_google_login'))
            <a class="btn btn-primary lw-responsive-btn lw-btn-google lw-show-process-action" href="{!! route('social.user.login', [getSocialProviderKey('google')]) !!}"> <i class="fa fa-google"></i> <?= __tr('Sign in with Google') ?></a>
            @endif
            @if (getStoreSettings('allow_twitter_login'))
            <a class="btn btn-primary lw-responsive-btn lw-btn-twitter lw-show-process-action" href="{!! route('social.user.login', [getSocialProviderKey('twitter')]) !!}"> <i class="fa fa-twitter"></i> <?= __tr('Sign in with Twitter') ?></a>
            @endif
            @if (getStoreSettings('allow_github_login'))
            <a class="btn btn-primary lw-responsive-btn lw-btn-github lw-show-process-action" href="{!! route('social.user.login', [getSocialProviderKey('github')]) !!}"> <i class="fa fa-github"></i> <?= __tr('Sign in with Github') ?></a>
            @endif
        </div>
    </div>
</div>