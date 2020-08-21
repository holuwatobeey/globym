<div ng-controller="UserChangeEmailController as changeEmailCtrl"  class="col-lg-6">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">
        @section('page-title', __tr('Change Email'))        
        <?=  __tr( 'Change Email' )  ?></h3>
        <!--  /main heading  -->
    </div>
	 
    <!--  note  -->
    <div ng-if="changeEmailCtrl.activationRequired && changeEmailCtrl.requestSuccess">
        <div class="alert alert-success">
            <div class="header">
                <strong><?=  __tr("Activate your new email address") ?></strong>
            </div>
            <p><?=  __tr("Almost finished... You need to confirm your email address. To complete the activation process, please click the link in the email we just sent you.")  ?></p>
        </div>
    </div>
   
    <div class="alert alert-info" ng-if="changeEmailCtrl.changeEmail !== false && changeEmailCtrl.pageStatus">
        <?= __tr('You requested for change email __email__ on __humanRedableDate__ is pending. You can still requested for new one.', [
            '__email__' => '<strong>[[ changeEmailCtrl.changeEmail ]]</strong>',
            '__humanRedableDate__' => '<span title="[[ changeEmailCtrl.formattedDate ]]">[[ changeEmailCtrl.humanReadableDate ]]</span>'
        ]) ?>
    </div>

    <div ng-if="!changeEmailCtrl.activationRequired && changeEmailCtrl.requestSuccess">
        <div class="alert alert-success" role="alert"><strong><?=  __tr( 'Well done!' )  ?></strong> 
        [[ changeEmailCtrl.successMessage ]]</div>
    </div>
    <!--  /note  -->

	<!--  form action  -->
    <form class="lw-form lw-ng-form" 
        name="changeEmailCtrl.[[ changeEmailCtrl.ngFormName ]]" 
        ng-submit="changeEmailCtrl.submit()" 
        novalidate>
 
        <!--  Current Email  -->
		<lw-form-field field-for="current_email" label="<?=  __tr( 'Current Email' )  ?>"> 
			<input type="text" 
					class="lw-form-field form-control lw-readonly-control"
					name="current_email"
					readonly
					value="<?=  maskEmailId(Auth::user()->email)  ?>" 
				/>
		</lw-form-field>
		<!--  /Current Email  -->

        <!--  Current Password  -->
        <lw-form-field field-for="current_password" label="<?=  __tr( 'Current Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="current_password"
                  min-length="6"
                  max-length="30"
                  ng-required="true"
                  autofocus 
                  ng-model="changeEmailCtrl.userData.current_password" autofocus />
        </lw-form-field>
        <!--  /Current Password  -->

        <!--  New Email  -->
        <lw-form-field field-for="new_email" label="<?=  __tr( 'New Email' )  ?>"> 
            <input type="email" 
                  class="lw-form-field form-control"
                  name="new_email"
                  ng-required="true" 
                  ng-model="changeEmailCtrl.userData.new_email" />
        </lw-form-field>
        <!--  /New Email  -->
		<br>
		<!--  update button  -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Update Request')  ?>"><?=  __tr('Update Request')  ?> <span></span></button>
        </div>
		<!--  /update button  -->
    </form>
	<!--  /form action  -->
</div>