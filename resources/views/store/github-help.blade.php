<?php
/*
*  Component  : Configuration
*  View       : Github Help dialog
*  Engine     : ConfigurationEngine  
*  File       : Github-help.blade.php  
*  Controller : HelpController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="HelpController as helpCtrl">
    

	<div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __tr('Steps to set-up') ?> <strong><?= __tr('Github App') ?></strong></h3>
        <!--  /main heading  -->
    </div>
    
    <!-- Modal Body -->
    <div>
        <p><?= __tr('To grab Github Client ID and Secret follow the below steps') ?></p>
        
        <ol>
            <li>
                <p>
                    <?= __tr('Go to the __link__ and login with your account', [
                        '__link__' =>  '<a href="https://github.com" target="_blank"> Github Apps </a>'
                ]) ?>
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Go to profile menu click on <strong>Settings</strong>, Then click on <strong> Developer settings </strong> which is present in sidebar, Register New App by clicking __createNewApp__ button, It will take you to the application Register Form",[
                        '__createNewApp__' => '<strong>New OAuth App</strong>'
                    ]) ?>
                </p>
                <p>
                <p>
                    <strong><?= __tr("Use the below URL for __callbackURL__",[
                        '__callbackURL__' => '<strong>Authorization callback URL</strong>'
                    ]) ?></strong>
                </p>
                <p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly="true" value="<?= route('social.user.login.callback', [getSocialProviderKey('github')]) ?>">
                        <span class="input-group-btn">
                            <button data-clipboard-text="<?= route('social.user.login.callback', [getSocialProviderKey('github')]) ?>" class="btn btn-default lw-copy-action" type="button"><i class="fa fa-clipboard" aria-hidden="true"></i></button>
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
            </li>
            <li>
                <p>
                    <?= __tr("Once all the form details are filled, Click on __createAppBtn__",[
                        '__createAppBtn__' => "<strong>Register Application</strong>"
                    ]) ?>
                </p>
                <p>
                    <?= __tr("It will register your application, you can add an icon for your App or more information via __settingTab__ Tab",[
                        '__settingTab__' => '<strong>Settings</strong>'
                    ]) ?>
                </p>
            </li>
            <li>
                <p>
                    <?= __tr("Now copy Github Client ID and Secret and add those keys to __twSection__. Its Done!!", [
                        '__twSection__' => '<strong>Github login section</strong>'
                    ]) ?>
                </p>
            </li>
        </ol>
    </div>
    <!-- /Modal Body -->
        
</div>