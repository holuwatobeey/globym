<div ng-controller="UserContactController as contactCtrl" class="col-lg-6">

     <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Contact' )) <?= __tr( 'Contact' ) ?></h3>
        <!--  /main heading  -->
    </div>

	<div ng-if="contactCtrl.requestSuccess">
        <div class="alert alert-success" role="alert"><?= __tr('Your contact request has been submitted successfully we will set back to you shortly') ?></div>
    </div>

	<!--  form action  -->
    <form class="lw-form lw-ng-form" 
        name="contactCtrl.[[ contactCtrl.ngFormName ]]" 
        ng-submit="contactCtrl.submit(1)" 
        novalidate>

        <div class="mb-5">
            <!--  user full name  -->
            <lw-form-field field-for="fullName" label="<?= __tr( 'Full Name' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="fullName"
                  ng-required="true" 
                  ng-model="contactCtrl.userData.fullName" />
            </lw-form-field>
            <!--  /user full name  -->

            <!--  user Email  -->
            <lw-form-field field-for="email" label="<?= __tr( 'Email' ) ?>"> 
                <input type="email" 
                  class="lw-form-field form-control"
                  name="email"
                  ng-required="true" 
                  ng-model="contactCtrl.userData.email" />
            </lw-form-field>
            <!--  /user Email  -->

            <!--  subject  -->
            <lw-form-field field-for="subject" label="<?= __tr( 'Subject' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="subject"
                  ng-required="true" 
                  ng-model="contactCtrl.userData.subject" />
            </lw-form-field>
            <!--  /subject  -->

             <div>
            <!--  message Description  -->
                <lw-form-field field-for="message" label="<?= __tr('Message') ?>"> 
                    <textarea name="message" class="lw-form-field form-control"
                     cols="10" rows="3" ng-required="true" ng-model="contactCtrl.userData.message" limited-options="true"></textarea>
                </lw-form-field>
            <!--  /message Description  -->
            </div>
		</div>
		<!--  submit button  -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?> <span></span></button>
        </div>
        <!--  /submit button  -->
    </form>
    <!--  form action  -->
</div>