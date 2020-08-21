<div class="lw-dialog" ng-controller="UserContactController as contactCtrl">
    
    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Contact' )) <?= __tr( 'Contact' ) ?></h3>
        <!--  /main heading  -->
    </div>

    <div class="alert alert-success" ng-if="contactCtrl.requestSuccess">
        <?= __tr('Your message has been submitted successfully. We will back to you soon.') ?>
    </div>

	<!--  form action  -->
    <form class="lw-form lw-ng-form" 
        name="contactCtrl.[[ contactCtrl.ngFormName ]]" 
        ng-submit="contactCtrl.submit(2)" 
        novalidate>

        <div class="mb-5">
            <!--  user order id  -->
            <lw-form-field field-for="orderUID" label="<?= __tr( 'Order ID' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="orderUID"
                  ng-required="true" 
                  readonly
                  ng-model="contactCtrl.userData.orderUID" readonly/>
            </lw-form-field>
            <!--  /user order id  -->

            <!--  user full name  -->
            <lw-form-field field-for="fullName" label="<?= __tr( 'Full Name' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="fullName"
                  ng-required="true" 
                  ng-model="contactCtrl.userData.fullName" readonly/>
            </lw-form-field>
            <!--  /user full name  -->

            <!--  user Email  -->
            <lw-form-field field-for="email" label="<?= __tr( 'Email' ) ?>"> 
                <input type="email" 
                  class="lw-form-field form-control"
                  name="email"
                  ng-required="true" 
                  ng-model="contactCtrl.userData.email" readonly/>
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
         
        <!--  message Description  -->
            <lw-form-field field-for="message" label="<?= __tr('Message') ?>"> 
                <textarea name="message" class="lw-form-field form-control"
                 cols="10" rows="3" ng-required="true" ng-model="contactCtrl.userData.message"></textarea>
            </lw-form-field>
        <!--  /message Description  -->
        </div>
        
		<!--  subit buton  -->
        <div class="modal-footer">
        	<!-- submit button -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?></button>
           	<!-- /submit button -->

           	<!-- cancel button -->
            <button type="button" class="lw-btn btn btn-light" ng-click="contactCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
            <!-- /cancel button -->
        </div>
        <!--  /subit buton  -->
    </form>
    <!--  form action  -->

    <!--  subit buton  -->
    <div ng-if="contactCtrl.requestSuccess" class="modal-footer">
        <!-- cancel button -->
        <button type="button" class="lw-btn btn btn-light" ng-click="contactCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
        <!-- /cancel button -->
    </div>
</div>