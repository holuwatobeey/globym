<?php
/*
*  Component  : Configuration
*  View       : Email
*  Engine     : ConfigurationEngine  
*  File       : email.blade.php  
*  Controller : EmailSettingDialogController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="EmailSettingDialogController as emailCtrl" class="lw-dialog">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h4 class="lw-header"><?= __tr('Email Settings') ?></h4>
    </div>
    <!-- /main heading -->

    <div ng-include src="'lw-settings-update-reload-button-template.html'"></div>

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="emailCtrl.[[ emailCtrl.ngFormName ]]" 
        ng-submit="emailCtrl.submit()" 
        novalidate>
    
        <!-- Use Email Settings From .env File -->
        <lw-form-checkbox-field field-for="use_env_default_email_settings" label="<?= __tr( ' Use Email Settings From .env File ' ) ?>"  ng-if="emailCtrl.pageStatus">
            <input type="checkbox"
                class="lw-form-field"
                name="use_env_default_email_settings"
                ng-model="emailCtrl.editData.use_env_default_email_settings"
                ui-switch="[[emailCtrl.uiSwitch]]" />
        </lw-form-checkbox-field>
        <!-- /Use Email Settings From .env File -->
        
        <div ng-if="emailCtrl.editData.use_env_default_email_settings  == false">

            <div class="form-row">  
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail From Address -->
                    <lw-form-field field-for="mail_from_address" label="<?= __tr( 'Mail From Address' ) ?>"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="mail_from_address"
                            ng-required="true"
                            ng-model="emailCtrl.editData.mail_from_address" />
                    </lw-form-field>
                    <!-- Mail From Address-->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail From Name -->
                    <lw-form-field field-for="mail_from_name" label="<?= __tr( 'Mail From Name' ) ?>"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="mail_from_name"
                            ng-required="true"
                            ng-model="emailCtrl.editData.mail_from_name" />
                    </lw-form-field>
                    <!-- Mail From Name-->
                </div>
            </div>

            <!-- mail_driver -->
            <lw-form-field field-for="mail_driver" label="<?= __tr( 'Mail Driver' ) ?>" > 
                <selectize 
                    config='emailCtrl.email_driver_config' 
                    class="lw-form-field" 
                    name="mail_driver" 
                    ng-model="emailCtrl.editData.mail_driver" 
                    options='emailCtrl.maildrivers' 
                    placeholder="<?= __tr( 'Select Mail Driver' ) ?>" 
                    ng-required="true"></selectize>
            </lw-form-field>
            <!-- / mail_driver -->
            
            <div class="form-row" ng-if="emailCtrl.editData.mail_driver  == 'smtp'"> 
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Port -->
                    <lw-form-field field-for="smtp_mail_port" label="<?= __tr( 'Mail Port' ) ?>" 
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="number" 
                            class="lw-form-field form-control"
                            name="smtp_mail_port"
                            ng-required="true"
                            min="0" 
                            ng-model="emailCtrl.editData.smtp_mail_port" />
                    </lw-form-field>
                <!-- Mail Port -->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Host -->
                    <lw-form-field field-for="smtp_mail_host" label="<?= __tr( 'Mail Host' ) ?>" 
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="smtp_mail_host"
                            ng-required="true"
                            ng-model="emailCtrl.editData.smtp_mail_host" />
                    </lw-form-field>
                    <!-- Mail Host -->
                </div>
            </div>

            <div class="form-row" ng-if="emailCtrl.editData.mail_driver == 'mandrill'">  
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Port -->
                    <lw-form-field field-for="mandrill_mail_port" label="<?= __tr( 'Mail Port' ) ?>" 
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="number" 
                            class="lw-form-field form-control"
                            name="mandrill_mail_port"
                            ng-required="true"
                            min="0" 
                            ng-model="emailCtrl.editData.mandrill_mail_port" />
                    </lw-form-field>
                    <!-- Mail Port -->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Host -->
                    <lw-form-field field-for="mandrill_mail_host" label="<?= __tr( 'Mail Host' ) ?>" 
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="mandrill_mail_host"
                            ng-required="true"
                            ng-model="emailCtrl.editData.mandrill_mail_host" />
                    </lw-form-field>
                    <!-- Mail Host -->
                </div>
            </div>

            <div class="form-row" ng-if="emailCtrl.editData.mail_driver == 'smtp'">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Username -->
                    <lw-form-field field-for="smtp_mail_username" label="<?= __tr( 'Mail Username' ) ?>"
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="smtp_mail_username"
                            ng-required="true"
                            ng-model="emailCtrl.editData.smtp_mail_username" />
                    </lw-form-field>
                    <!-- Mail Username -->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Encryption -->
                    <lw-form-field field-for="smtp_mail_encryption" label="<?= __tr( 'Mail Encryption' ) ?>"
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'">
                        <selectize 
                        config='emailCtrl.email_driver_config' 
                        class="lw-form-field form-control" 
                        name="smtp_mail_encryption" 
                        ng-model="emailCtrl.editData.smtp_mail_encryption" 
                        options='emailCtrl.mail_encryption_types' 
                        placeholder="<?= __tr( 'Select Mail Encryption' ) ?>" 
                        ng-required="true"></selectize>
                    </lw-form-field>
                    <!-- Mail Encryption-->
                </div>
            </div>

            <div class="form-row" ng-if="emailCtrl.editData.mail_driver == 'mandrill'">  
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Username -->
                    <lw-form-field field-for="mandrill_mail_username" label="<?= __tr( 'Mail Username' ) ?>"
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'"> 
                        <input type="text" 
                            class="lw-form-field form-control"
                            name="mandrill_mail_username"
                            ng-required="true"
                            ng-model="emailCtrl.editData.mandrill_mail_username" />
                    </lw-form-field>
                    <!-- Mail Username -->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- Mail Encryption -->
                    <lw-form-field field-for="mandrill_mail_encryption" label="<?= __tr( 'Mail Encryption' ) ?>"
                        ng-if="emailCtrl.editData.mail_driver == 'smtp' || emailCtrl.editData.mail_driver == 'mandrill'">
                        <selectize 
                        config='emailCtrl.email_driver_config' 
                        class="lw-form-field form-control" 
                        name="mandrill_mail_encryption" 
                        ng-model="emailCtrl.editData.mandrill_mail_encryption" 
                        options='emailCtrl.mail_encryption_types' 
                        placeholder="<?= __tr( 'Select Mail Encryption' ) ?>" 
                        ng-required="true"></selectize>
                    </lw-form-field>
                    <!-- Mail Encryption-->
                </div>
            </div>

            <!-- Mail Domain -->
            <lw-form-field field-for="mailgun_domain" label="<?= __tr( 'Mail Domain' ) ?>"
                ng-if="emailCtrl.editData.mail_driver == 'mailgun'"> 
                <input type="text" 
                    class="lw-form-field form-control"
                    name="mailgun_domain"
                    ng-required="true"
                    ng-model="emailCtrl.editData.mailgun_domain" />
            </lw-form-field>
            <!-- Mail Domain-->

            <!-- Mail Password/API Key -->
            <lw-form-field 
                ng-if="emailCtrl.editData.mail_driver  == 'smtp'" 
                field-for="smtp_mail_password_or_apikey" label="<?= __tr( 'Mail Password/API Key' ) ?>">
                <input type="Password" 
                    class="lw-form-field form-control"
                    name="smtp_mail_password_or_apikey"
                    ng-required="true"
                    ng-model="emailCtrl.editData.smtp_mail_password_or_apikey" />
            </lw-form-field>
            <!-- Mail Password/API Key -->

            <!-- Mail Password/API Key -->
            <lw-form-field 
                ng-if="emailCtrl.editData.mail_driver  == 'sparkpost'" 
                field-for="sparkpost_mail_password_or_apikey" label="<?= __tr( 'Mail Password/API Key' ) ?>">
                <input type="Password" 
                    class="lw-form-field form-control"
                    name="sparkpost_mail_password_or_apikey"
                    ng-required="true"
                    ng-model="emailCtrl.editData.sparkpost_mail_password_or_apikey" />
            </lw-form-field>
            <!-- Mail Password/API Key -->

            <!-- Mail Password/API Key -->
            <lw-form-field 
                ng-if="emailCtrl.editData.mail_driver  == 'mandrill'" 
                field-for="mandrill_mail_password_or_apikey" label="<?= __tr( 'Mail Password/API Key' ) ?>">
                <input type="Password" 
                    class="lw-form-field form-control"
                    name="mandrill_mail_password_or_apikey"
                    ng-required="true"
                    ng-model="emailCtrl.editData.mandrill_mail_password_or_apikey" />
            </lw-form-field>
            <!-- Mail Password/API Key -->

            <!-- Mail Password/API Key -->
            <lw-form-field 
                ng-if="emailCtrl.editData.mail_driver  == 'mailgun'" 
                field-for="mailgun_mail_password_or_apikey" label="<?= __tr( 'Mailgun Password/API Key' ) ?>">
                <input type="Password" 
                    class="lw-form-field form-control"
                    name="mailgun_mail_password_or_apikey"
                    ng-required="true"
                    ng-model="emailCtrl.editData.mailgun_mail_password_or_apikey" />
            </lw-form-field>
            <!-- Mail Password/API Key -->

            <!-- Mail Password/API Key -->
            <lw-form-field 
                ng-if="emailCtrl.editData.mail_driver  == 'mailgun'" 
                field-for="mailgun_endpoint" label="<?= __tr( 'Mail Endpoint' ) ?>">
                <input type="text" 
                    class="lw-form-field form-control"
                    name="mailgun_endpoint"
                    ng-model="emailCtrl.editData.mailgun_endpoint" />
            </lw-form-field>
            <!-- Mail Password/API Key -->
        </div>
        <!-- button -->

        <!-- append email message -->
            <div>
                <lw-form-field field-for="append_email_message" label="<?= __tr('Append Email Message') ?>" ng-if="emailCtrl.pageStatus">
                    <a href lw-transliterate entity-type="misc_setting" entity-id="null" entity-key="append_email_message" entity-string="[[ emailCtrl.editData.append_email_message ]]" input-type="3"><i class="fa fa-globe"></i></a>
                    
                 <div class="alert alert-info lw-alert-mini">
                    <?= __tr('This Message will be inserted at end of email body content before the footer for every email send by the system. eg : Order Email , Activation email.') ?>
                </div>
                    <textarea 
                        name="append_email_message" 
                        class="lw-form-field form-control" 
                        cols="30" 
                        rows="10" 
                        ng-minlength="6" 
                        ng-model="emailCtrl.editData.append_email_message" 
                        lw-ck-editor >
                        </textarea>
                </lw-form-field>
            </div>
            <!-- append email message -->

        <br>
        <div class="modal-footer mt-3">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
            <?= __tr('Update') ?></button>
            <!-- <button type="button" class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>">
            <?= __tr('Cancel') ?></button> -->
        </div>
        <!-- /button -->
    </form>
</div>