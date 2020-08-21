<?php
/*
*  Component  : Configuration
*  View       : Twitter Help dialog
*  Engine     : ConfigurationEngine  
*  File       : twitter-help.blade.php  
*  Controller : HelpController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="HelpController as helpCtrl">
    
	<div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __tr('Steps to set-up') ?> <strong><?= __tr('Twitter App') ?></strong></h3>
        <!--  /main heading  -->
    </div>
    
    <!-- Modal Body -->
    <div>
        <p><?= __tr('To grab Twitter Consumer Key and Secret follow the below steps') ?></p>
        
        <ol>
            <li>
                <p>
                    <?= __tr('Go to the __link__ and login with your account', [
                        '__link__' =>  '<a href="https://apps.twitter.com" target="_blank"> Twitter Apps </a>'
                ]) ?>
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Create New App by clicking on __createNewApp__ button, It will take you to the application creation form",[
                        '__createNewApp__' => '<strong>Create New App</strong>'
                    ]) ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('create-app.png', 'twitter') ?>" alt="<?= __tr("Create App") ?>" />
                </p>
                <p>
                    <strong><?= __tr("Use the below URL for __callbackURL__",[
                        '__callbackURL__' => '<strong>Callback URL</strong>'
                    ]) ?></strong>
                </p>
                <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?= route('social.user.login.callback', [getSocialProviderKey('twitter')]) ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?= route('social.user.login.callback', [getSocialProviderKey('twitter')]) ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                        </span>
                    </div>
                </p>
				<p>
                    <strong><?= __tr("Use the below URL for __callbackURL__ If Required",[
                        '__callbackURL__' => '<strong>Privacy Policy URL</strong>'
                    ]) ?></strong>
                </p>
                <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?=  route('privacy.policy')  ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?=  route('privacy.policy')  ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                        </span>
                    </div>
               </p>
				<p>
                    <strong><?= __tr("Use the below URL for __callbackURL__ If Required",[
                        '__callbackURL__' => '<strong>Terms of Service URL</strong>'
                    ]) ?></strong>
                </p>
                <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?=  route('terms.conditions')  ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?=  route('terms.conditions')  ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                        </span>
                    </div>
               </p>
            </li>
            <li>
                <p>
                    <?= __tr("Once all the form details are filled, Click on __createAppBtn__",[
                        '__createAppBtn__' => "<strong>Create your Twitter Application</strong>"
                    ]) ?>
                </p>
                <p>
                    <?= __tr("It will create your application, you can add an icon for your App or more information via __settingTab__ Tab",[
                        '__settingTab__' => '<strong>Settings</strong>'
                    ]) ?>
                </p>
            </li>
			<li>
                <p>
                    <?= __tr("Now you need to go to __keysTab__ to set required permissions for communication.", [
                    '__keysTab__' => '<strong>Permissions</strong>'
                ]) ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('permissions.png', 'twitter') ?>" alt="<?= __tr("Select Read only") ?>" />
                </p>
				<p>
                    <?= __tr("In the __access__ section, select __keysTab__.", [
					'__access__'  => '<strong>Access</strong>',
                    '__keysTab__' => '<strong>Read only</strong>'
                ]) ?>
                </p>
				<p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('access.png', 'twitter') ?>" alt="<?= __tr("Select Additional Permissions") ?>" />
                </p>
				<p>
                    <?= __tr("In __access__ section, select __keysTab__.", [
					'__access__'  => '<strong>Additional Permissions</strong>',
                    '__keysTab__' => '<strong>Request email addresses from users</strong>'
                ]) ?>
                </p>
				<p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('additional.png', 'twitter') ?>" alt="<?= __tr("Get your required keys") ?>" />
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Now go  to __keysTab__ to get Api Key and Secret.", [
                    '__keysTab__' => '<strong>Keys and Access Tokens</strong>'
                ]) ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('keys.png', 'twitter') ?>" alt="<?= __tr("Get your required keys") ?>" />
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Now copy Twitter Consumer Keys and Secret and add those keys to __twSection__. Its Done!!", [
                        '__twSection__' => '<strong>Twitter login section</strong>'
                    ]) ?>
                </p>
            </li>
        </ol>
    </div>
    <!-- /Modal Body -->
        
</div>