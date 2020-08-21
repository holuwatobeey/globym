<div class="lw-custom-page-jumbotron jumbotron text-center">
    <div class="lw-error-404">
    	@section('page-title',  __tr( 'Register Success' )) 
	    @if(is_null(getStoreSettings('activation_required_for_new_user')) or getStoreSettings('activation_required_for_new_user') == 1)

	    	<h1 class="font-bold"> <i class="fa fa-check-square-o fa-1x lw-success"></i> <?=  __tr("Activate your account")  ?></h1>

		    <h6 class="fa-1x">
		        <?=  __tr('Almost finished... You need to confirm your email address. To complete the activation process, please click the link in the email we just sent you.')  ?>
		    </h6>

	 		@else

	    	<h1 class="font-bold"><i class="fa fa-check-square-o fa-1x lw-success"></i> <?=  __tr("Registration successful")  ?></h1>

		    <h6 class="fa-1x">
		        <?=  __tr('Your registration process completed successfully. Click')  ?>
		        <a href="<?= route('user.login') ?>"><?=  __tr('here')  ?></a>
		        <?=  __tr('to login')  ?>
		    </h6>

	    @endif

	</div>
</div>