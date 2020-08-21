<?php
/*
*  Component  : Configuration
*  View       : Google Help dialog
*  Engine     : ConfigurationEngine  
*  File       : google-help.blade.php  
*  Controller : HelpController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="HelpController as helpCtrl">

    <div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __tr('Steps to set-up') ?> <strong><?= __tr('Google App') ?></strong></h3>
        <!--  /main heading  -->
    </div>
    
    <!-- Modal Body -->
    <div>
        
        <p><?= __tr('To grab Google Client ID and Secret follow the below steps') ?></p>
        <ol>
            <li>
                <p>
                    <?= __tr('Go to the __link__ and login with your account', [
                        '__link__' =>  '<a href="https://console.developers.google.com" target="_blank"> Google API Console</a>'
                ]) ?>
                </p>
            </li>
            <li>
                <p>
                    <strong><?= __tr("Create New Project") ?></strong>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('create-project.png', 'google') ?>" alt="Creating new project" />
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Once the project is created, you will be redirected you to project page.") ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('project-page.png', 'google') ?>" alt="Project Page" />
                </p>
            </li>
            <li>
               <p>
                   <?= __tr("Now click on __credLabel__ link at Sidebar, It will show page with tabs.", [
                        '__credLabel__' => '<strong>Credentials</strong>'
                   ]) ?>
               </p> 
               <p>
                   <?= __tr("Now go to __oAuthConsent__ tab, and fill the required fields", [
                        '__oAuthConsent__' => '<strong>oAuth Consent screen</strong>'
                   ]) ?>
               </p>
               <p>
                   <img class="img-fluid" src="<?= getResourcesAssetsPath('oauth-consent-screen.png', 'google') ?>" alt="oAuth Consent Screen" />
               </p>
               <p>
                   <?= __tr("Save the information to __createAppID__",[
                    '__createAppID__' => '<strong>Create App ID</strong>'
                   ]) ?>
               </p>
            </li>
            <li>  
              <p>
                   <img class="img-fluid" src="<?= getResourcesAssetsPath('create-client-id.png', 'google') ?>" alt="<?= __tr("Create Client ID") ?>" />
               </p>              
               <p>
                   <?= __tr("You need to select __webApp__ and give it __webAppName__",[
                    '__webApp__' => '<strong>Web Application</strong>',
                    '__webAppName__' => '<strong>Name</strong>'
                   ]) ?>
               </p>
               <p>
                   <?= __tr("Now copy following URL and add it in __ARU__", [
                    '__ARU__' => '<strong>Authorized redirect URIs</strong>'
                   ]) ?>
               </p>
               <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?= route('social.user.login.callback', [getSocialProviderKey('google')]) ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?= route('social.user.login.callback', [getSocialProviderKey('google')]) ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                        </span>
                    </div>
               </p>
            </li>
            <li>
            <p>
                   <?= __tr("Click on Create will generate __CID__ and __CSecret__", [
                    '__CID__' => '<strong>Client ID</strong>',
                    '__CSecret__' => '<strong>Client Secret</strong>'
                   ]) ?>
               </p>
            <p>
                <img class="img-fluid" src="<?= getResourcesAssetsPath('oauth-client.png', 'google') ?>" alt="<?= __tr("Get your keys") ?>" /> 
            </p>
               <p>
                    <?= __tr("Now close this dialog and add those details to __googleSection__.", [
                        '__googleSection__' => '<strong>Google login section</strong>'
                    ]) ?>
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Then you need to click on __lbLink__ link in sidebar, It will show you list of Gmail APIs. you need to click on __gplusApiBtn__", [
                        '__lbLink__' => '<strong>Library</strong>',
                        '__gplusApiBtn__' => '<strong>Gmail API</strong>'
                    ]) ?>
                </p>
                <p>
                   <img class="img-fluid" src="<?= getResourcesAssetsPath('api-list.png', 'google') ?>" alt="<?= __tr("List of Gmail APIs") ?>" />  
                </p>
                <p>
                    <?= __tr("It will take you to the page where you can enable this api. Click on __enableBtn__ will button to do so.",[
                        '__enableBtn__' => 'ENABLE'
                    ]) ?>
                </p>
                <p>
                   <img class="img-fluid" src="<?= getResourcesAssetsPath('enable-api.png', 'google') ?>" alt="<?= __tr("Enable API") ?>" />  
                </p>
                <p>
                    <strong><?= __tr("Done!!") ?></strong>
                </p>
            </li>
        </ol>

    </div>
    <!-- /Modal Body -->

</div>