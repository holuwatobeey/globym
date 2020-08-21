<?php
/*
*  Component  : Settings
*  View       : Social Login
*  FileName   : SocialSettingsController
*  File       : social-login.blade.php
*  Controller : SocialLoginSetupSettingsController
----------------------------------------------------------------------------- */
?>

<div class="lw-section-heading-block">
    <h4 class="lw-header"><?= __tr('Social Login Settings') ?></h4>
</div>
<div ng-controller="SocialLoginSetupSettingsController as SocialLoginCtrl">

    <!-- Start Form -->
    <form class="lw-form lw-ng-form"
        name="SocialLoginCtrl.[[ SocialLoginCtrl.ngFormName ]]" novalidate>

        <div class="alert alert-info">
            The below HELP document provides instructions on how to properly setup a application so that users can login/register via selected provider (Facebook, Google, Twitter, Github) on your site.
        </div>

        <!-- Modal Body -->
        <div ng-if="SocialLoginCtrl.pageStatus">

            <fieldset class="lw-fieldset-2 mb-3">

                <legend class="lw-fieldset-legend-font">
                    <!-- Allow facebook login -->
                    <lw-form-checkbox-field field-for="allow_facebook_login" label="<?= __tr( 'Allow Facebook Login' ) ?>" advance="true">
                        <input type="checkbox"
                            class="lw-form-field js-switch"
                            name="allow_facebook_login"
                            ng-model="SocialLoginCtrl.editData.allow_facebook_login"
                            ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Allow facebook login -->
                </legend>

                <!-- Facebook Help dialog button -->
                <a target="_blank" class="pull-right btn btn-light btn-sm lw-btn" ui-sref="facebook_social_doc"><i class="fa fa-info"></i> <?= __tr('Facebook Help') ?></a><br>
                <!-- / Facebook Help dialog button -->

                <!-- show after added facebook login information -->
                <div class="btn-group" ng-if="SocialLoginCtrl.editData.allow_facebook_login && SocialLoginCtrl.editData.isFacebookKeyExist">
                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Facebook keys are installed.') ?></button>
                  <button type="button" ng-click="SocialLoginCtrl.showDetails(1)" class="lw-btn btn btn-light"><?= __tr('Update') ?></button>
                </div>
                <!-- show after added facebook login information -->

                <!-- Facebook Client Id -->
                <lw-form-field field-for="facebook_client_id" label="<?= __tr( 'Facebook App ID' ) ?>" ng-if="SocialLoginCtrl.editData.allow_facebook_login && !SocialLoginCtrl.editData.isFacebookKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          autofocus
                          name="facebook_client_id"
                          ng-model="SocialLoginCtrl.editData.facebook_client_id"
                          ng-required="[[SocialLoginCtrl.editData.allow_facebook_login]]" />
                </lw-form-field>
                <!-- /Facebook Client Id -->

                <!-- Facebook Client Secret -->
                <lw-form-field field-for="facebook_client_secret" label="<?= __tr( 'Facebook App Secret' ) ?>" ng-if="SocialLoginCtrl.editData.allow_facebook_login && !SocialLoginCtrl.editData.isFacebookKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          name="facebook_client_secret"
                          ng-model="SocialLoginCtrl.editData.facebook_client_secret"
                          ng-required="[[SocialLoginCtrl.editData.allow_facebook_login]]" />
                </lw-form-field>
                <!-- /Facebook Client Secret -->

            </fieldset>

            <fieldset class="lw-fieldset-2 mb-3">

                <legend class="lw-fieldset-legend-font">
                    <!-- Allow Google login -->
                    <lw-form-checkbox-field field-for="allow_google_login" label="<?= __tr( 'Allow Google Login' ) ?>" advance="true">
                        <input type="checkbox"
                            class="lw-form-field js-switch"
                            name="allow_google_login"
                            ng-model="SocialLoginCtrl.editData.allow_google_login"
                            ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Allow Google login -->
                </legend>

                <!-- Google Help dialog button -->
				<a target="_blank" class="pull-right btn btn-light btn-sm lw-btn" ui-sref="google_social_doc"><i class="fa fa-info"></i> <?= __tr('Google Help') ?></a><br>
                <!-- / Google Help dialog button -->

                <!-- show after added Google login information -->
                <div class="btn-group" ng-if="SocialLoginCtrl.editData.allow_google_login && SocialLoginCtrl.editData.isGoogleKeyExist">
                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Google keys are installed.') ?></button>
                  <button type="button" ng-click="SocialLoginCtrl.showDetails(2)" class="lw-btn btn btn-light"><?= __tr('Update') ?></button>
                </div>
                <!-- show after added Google login information -->

                <!-- Google Client Id -->
                <lw-form-field field-for="google_client_id" label="<?= __tr( 'Google Client ID' ) ?>" ng-if="SocialLoginCtrl.editData.allow_google_login && !SocialLoginCtrl.editData.isGoogleKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          autofocus
                          name="google_client_id"
                          ng-model="SocialLoginCtrl.editData.google_client_id"
                          ng-required="[[SocialLoginCtrl.editData.allow_google_login]]"/>
                </lw-form-field>
                <!-- /Google Client Id -->

                <!-- Google Client Secret -->
                <lw-form-field field-for="google_client_secret" label="<?= __tr( 'Google Client Secret' ) ?>" ng-if="SocialLoginCtrl.editData.allow_google_login && !SocialLoginCtrl.editData.isGoogleKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          name="google_client_secret"
                          ng-model="SocialLoginCtrl.editData.google_client_secret"
                          ng-required="[[SocialLoginCtrl.editData.allow_google_login]]"/>
                </lw-form-field>
                <!-- /Google Client Secret -->

            </fieldset>

            <fieldset class="lw-fieldset-2 mb-3">

                <legend class="lw-fieldset-legend-font">
                    <!-- Allow Twitter login -->
                    <lw-form-checkbox-field field-for="allow_twitter_login" label="<?= __tr( 'Allow Twitter Login' ) ?>" advance="true">
                        <input type="checkbox"
                            class="lw-form-field js-switch"
                            name="allow_twitter_login"
                            ng-model="SocialLoginCtrl.editData.allow_twitter_login"
                            ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Allow Twitter login -->
                </legend>

                <!-- Twitter Help dialog button -->
				<a target="_blank" class="pull-right btn btn-light btn-sm lw-btn" ui-sref="twitter_social_doc"><i class="fa fa-info"></i> <?= __tr('Twitter Help') ?></a><br>
                <!-- / Twitter Help dialog button -->

                <!-- show after added twitter login information -->
                <div class="btn-group" ng-if="SocialLoginCtrl.editData.allow_twitter_login && SocialLoginCtrl.editData.isTwitterKeyExist">
                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Twitter keys are installed.') ?></button>
                  <button type="button" ng-click="SocialLoginCtrl.showDetails(3)" class="lw-btn btn btn-light"><?= __tr('Update') ?></button>
                </div>
                <!-- show after added twitter login information -->

                <!-- Twitter Client Id -->
                <lw-form-field field-for="twitter_client_id" label="<?= __tr( 'Twitter Consumer Key (API Key)' ) ?>" ng-if="SocialLoginCtrl.editData.allow_twitter_login && !SocialLoginCtrl.editData.isTwitterKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          autofocus
                          name="twitter_client_id"
                          ng-model="SocialLoginCtrl.editData.twitter_client_id"
                          ng-required="[[SocialLoginCtrl.editData.allow_twitter_login]]"/>
                </lw-form-field>
                <!-- /Twitter Client Id -->

                <!-- Twitter Client Secret -->
                <lw-form-field field-for="twitter_client_secret" label="<?= __tr( 'Twitter Consumer Secret (API Secret)' ) ?>" ng-if="SocialLoginCtrl.editData.allow_twitter_login && !SocialLoginCtrl.editData.isTwitterKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          name="twitter_client_secret"
                          ng-model="SocialLoginCtrl.editData.twitter_client_secret"
                          ng-required="[[SocialLoginCtrl.editData.allow_twitter_login]]"/>
                </lw-form-field>
                <!-- /Twitter Client Secret -->

            </fieldset>

            <fieldset class="lw-fieldset-2 mb-3">

                <legend class="lw-fieldset-legend-font">
                    <!-- Allow Github login -->
                    <lw-form-checkbox-field field-for="allow_github_login" label="<?= __tr( 'Allow Github Login' ) ?>" advance="true">
                        <input type="checkbox"
                            class="lw-form-field js-switch"
                            name="allow_github_login"
                            ng-model="SocialLoginCtrl.editData.allow_github_login"
                            ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Allow Github login -->
                </legend>

                <!-- Github Help dialog button -->
				 <a target="_blank" class="pull-right btn btn-light btn-sm lw-btn" ui-sref="github_social_doc"><i class="fa fa-info"></i> <?= __tr('Github Help') ?></a><br>
                <!-- / Github Help dialog button -->

                <!-- show after added twitter login information -->
                <div class="btn-group" ng-if="SocialLoginCtrl.editData.allow_github_login && SocialLoginCtrl.editData.isGithubKeyExist">
                  <button type="button" disabled="true" class="btn btn-success lw-btn"><?= __tr('Github Keys are installed.') ?></button>
                  <button type="button" ng-click="SocialLoginCtrl.showDetails(4)" class="lw-btn btn btn-light"><?= __tr('Update') ?></button>
                </div>
                <!-- show after added twitter login information -->

                <!-- Github Client Id -->
                <lw-form-field field-for="github_client_id" label="<?= __tr( 'Github Client ID' ) ?>" ng-if="SocialLoginCtrl.editData.allow_github_login && !SocialLoginCtrl.editData.isGithubKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          autofocus
                          name="github_client_id"
                          ng-model="SocialLoginCtrl.editData.github_client_id"
                          ng-required="[[SocialLoginCtrl.editData.allow_github_login]]"/>
                </lw-form-field>
                <!-- /Github Client Id -->

                <!-- Github Client Secret -->
                <lw-form-field field-for="github_client_secret" label="<?= __tr( 'Github Client Secret' ) ?>" ng-if="SocialLoginCtrl.editData.allow_github_login && !SocialLoginCtrl.editData.isGithubKeyExist">
                    <input type="text"
                          class="lw-form-field form-control"
                          name="github_client_secret"
                          ng-model="SocialLoginCtrl.editData.github_client_secret"
                          ng-required="[[SocialLoginCtrl.editData.allow_github_login]]"/>
                </lw-form-field>
                <!-- /Github Client Secret -->

            </fieldset>

        </div>
        <!-- /Modal Body -->
        <div class="modal-footer">
			<button type="submit" ng-click="SocialLoginCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
				<?= __tr('Update') ?><span></span> 
			</button>
			<!-- <button class="lw-btn btn btn-light" ui-sref="store_settings_edit" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button> -->
		</div>
    </form>
</div>