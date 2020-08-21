<div class="lw-dialog" ng-controller="ManageContactUserController as manageContactCtrl">

     <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Contact' )) <?= __tr( 'Contact' ) ?></h3>
        <!--  /main heading  -->
    </div>
	
	<!--  form action  -->
    <form class="lw-form lw-ng-form" 
        name="manageContactCtrl.[[ manageContactCtrl.ngFormName ]]" 
        ng-submit="manageContactCtrl.submit(2)" 
        novalidate>
		
		<!--  user order id  -->
        <lw-form-field field-for="orderUID" label="<?= __tr( 'Order ID' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="orderUID"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.orderUID" readonly/>
        </lw-form-field>
        <!--  /user order id  -->

        <!--  user full name  -->
        <lw-form-field field-for="fullName" label="<?= __tr( 'Full Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="fullName"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.fullName" readonly/>
        </lw-form-field>
        <!--  /user full name  -->

        <!--  user Email  -->
        <lw-form-field field-for="email" label="<?= __tr( 'Email' ) ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.email" readonly/>
        </lw-form-field>
        <!--  /user Email  -->

        <!--  subject  -->
        <lw-form-field field-for="subject" label="<?= __tr( 'Subject' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="subject"
              ng-required="true" 
              ng-model="manageContactCtrl.userData.subject" />
        </lw-form-field>
        <!--  /subject  -->

         <div>
        <!--  message Description  -->
            <lw-form-field field-for="message" label="<?= __tr('Message') ?>"> 
                <textarea name="message" class="lw-form-field form-control"
                 cols="10" rows="3" ng-required="true" ng-model="manageContactCtrl.userData.message"></textarea>
            </lw-form-field>
        <!--  /message Description  -->
        </div>
        <br>
        
		<!--  subit buton  -->
        <div class="modal-footer">
        	<!-- submit button -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?></button>
           	<!-- /submit button -->

           	<!-- cancel button -->
            <button type="button" class="lw-btn btn btn-light" ng-click="manageContactCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
            <!-- /cancel button -->
        </div>
        <!--  /subit buton  -->
    </form>
    <!--  form action  -->
</div>