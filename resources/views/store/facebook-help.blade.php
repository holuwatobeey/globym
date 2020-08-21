<?php
/*
*  Component  : Configuration
*  View       : Facebook Help dialog
*  Engine     : ConfigurationEngine  
*  File       : facebook-help.blade.php  
*  Controller : ConfigurationController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="HelpController as helpCtrl">

	<div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __tr('Steps to set-up') ?> <strong><?= __tr('Facebook App') ?></strong></h3>
        <!--  /main heading  -->
    </div>
    
    <!-- Modal Body -->
    <div>
        <ol>
            <li>
            <?= __tr("Go to __developerLink__ and log in to your account.",[
                '__developerLink__' => '<a href="https://developers.facebook.com/" target="_blank" title="Open Facebook developer website.">'.__("Facebook Developer").'</a>.'
            ]) ?>
            </li>
            <li>
            <strong>
                <?= __tr("Go to My Apps > Add New App") ?>
            </strong>
            <p><?= __tr("Fill all appropriate details for creating app and Click on Create App ID button") ?></p>
            <p>
                <img class="img-fluid" src="<?= getResourcesAssetsPath('create-app.png', 'facebook') ?>" alt="Create App">
            </p>
            </li>
            <li>
                <p>
                    <?= __tr("Once App ID is created, it will take you to <strong>Select a Scenario</strong> page, where you need to choose __facebookLogin__ option from the list and then click on <strong>Confirm Button</strong> at right side of bottom.",[
                        '__facebookLogin__' => '<strong>'.__("Integrate Facebook Login").'</strong>'
                    ]) ?>
                </p>
                <p>
                   <img class="img-fluid" src="<?= getResourcesAssetsPath('product-setup.png', 'facebook') ?>" alt="<?= __tr("Integrate Facebook Login") ?>">
                </p>
            </li>
			<li>
                <p>
                    <?= __tr("Now Click on the __appReview__ link at left sidebar, will take you to the below page",[
                        '__appReview__' => '<strong> Basic Settings</strong>'
                    ]) ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('sidebar-settings.PNG', 'facebook') ?>" alt="<?= __tr("Settings") ?>">
                </p>
				<p><?= __tr("Now Fill This form", [
                    '__appId__' => '<strong>App ID</strong>',
                    '__appSecret__' => '<strong>App Secret</strong>'
                ]) ?></p> 
				<p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('application-setup.PNG', 'facebook') ?>" alt="<?= __tr("Settings") ?>">
                </p>
				<p>
                    <strong><?= __tr("Use the below URL for __callbackURL__ If Required",[
                        '__callbackURL__' => '<strong>App Domain</strong>'
                    ]) ?></strong>
                </p>
                <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?=  url('/')  ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?=  url('/')  ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
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

               <p>
                  <?= __tr("Then click on <strong>Add Platform</strong> button, You need yo choose __webItem__", [
                    '__webItem__' => '<strong>Website</strong>'
                  ]) ?>  
                </p>

                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('choose-platform.png', 'facebook') ?>" alt="<?= __tr("Choose Platform") ?>">
                </p>

                <p>
                    <?= __tr("Click on it will open below form") ?>
                </p>
                <p>
                     <img class="img-fluid" src="<?= getResourcesAssetsPath('website-url.png', 'facebook') ?>" alt="<?= __tr("Add the URL to form") ?>">
                </p>
                <p>
                    <strong><?= __tr("Click on Save Changes") ?></strong>
                </p>
                
            </li>


            <li>
                <p>
                    <?= __tr("Now from the top, change the App Status from In development to Live, as shown below") ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('app-review.png', 'facebook') ?>" alt="<?= __tr("App Review") ?>">
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Then click on confirm button in confirmation box to make the App Public.") ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('app-review-confirmation.png', 'facebook') ?>" alt="<?= __tr("App Review") ?>">
                </p>
                <p>
                    <?= __tr("Click on confirm") ?>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('app-review-status.png', 'facebook') ?>" alt="<?= __tr("App Review") ?>">
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Now go to Facebook login link at the left sidebar, add URL from below.") ?>
                </p>
                <p>
                    <div class="input-group"  data-clipboard-text="<?= route('social.user.login.callback', ['provider' => 'via-facebook']) ?>" class="btn btn-default lw-copy-action">
                    <input type="text" class="form-control" readonly="true" 
                    value="<?= route('social.user.login.callback', ['provider' => 'via-facebook']) ?>">
                    <span class="input-group-btn">
                        <button 
                        data-clipboard-text="<?= route('social.user.login.callback', ['provider' => 'via-facebook']) ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                    </span>
                </div>
                </p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('redirect-url.png', 'facebook') ?>" alt="<?= __tr("Set Redirect URL") ?>">
                </p>
                <p>
                    <strong><?= __tr("and Save the changes") ?></strong>
                </p>
            </li>
            <li>
                <p><?= __tr("Now go to Settings at left sidebar and get the __appId__ and __appSecret__", [
                    '__appId__' => '<strong>App ID</strong>',
                    '__appSecret__' => '<strong>App Secret</strong>'
                ]) ?></p>
                <p>
                    <img class="img-fluid" src="<?= getResourcesAssetsPath('app-keys.png', 'facebook') ?>" alt="<?= __tr("Grab App ID and Secret") ?>">
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Now copy Facebook Client ID and Secret and add those keys to __fbSection__. Its Done!!", [
                        '__fbSection__' => '<strong>facebook login section</strong>'
                    ]) ?>
                </p>
            </li>
        </ol>

    </div>
    <!-- /Modal Body -->

</div>