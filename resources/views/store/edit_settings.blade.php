<?php
/*
*  Component  : Configuration
*  View       : Parent file
*  Engine     : ManageStoreEngine  
*  File       : edit_settings.blade.php  
*  Controller : EditStoreSettingsController 
----------------------------------------------------------------------------- */ 
?>

<div ng-controller="EditStoreSettingsController as settingsCtrl">

    <input type="hidden" id="lwReloadBtnText" data-message="<i class='fa fa-refresh' aria-hidden='true'></i> <?= __tr("Reload") ?>">

<!--     <div ng-include src="'lw-settings-update-reload-button-template.html'"></div>
 -->

 <div class="row">
  <!--   <div class="col-lg-3 col-md-4">
        <div class="card side-menu-container lw-setting-sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="General Settings Logo Image Store Name Timezone Country Header Background Color Home Page Business Email Language" ui-sref="store_settings_edit.general" ui-sref-active="active-nav" title="<?=  __tr('General Settings')  ?>"><i class="fa fa-cog fa-1x"></i> <span><?=  __tr('General Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Orders Settings Guest required Tax Cancellation" ui-sref="store_settings_edit.order" ui-sref-active="active-nav" title="<?=  __tr('Orders Settings')  ?>"><i class="fa fa-rss fa-1x"></i> <span><?=  __tr('Order Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Payment Settings Moderation Paypal Iyzico Check Bank transfer COD Other Stripe Razorpay" ui-sref="store_settings_edit.payment" ui-sref-active="active-nav" title="<?=  __tr('Payment Settings')  ?>"><i class="fa fa-credit-card-alt fa-1x"></i> <span><?=  __tr('Payment Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Currency Settings Currency Configuration Currency Code Currency Symbol Dollar" ui-sref="store_settings_edit.currency" ui-sref-active="active-nav" title="<?=  __tr('Currency Settings')  ?>"><i class="fa fa-money fa-1x"></i> <span><?=  __tr('Currency Settings')  ?></span></a>     
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Product Settings Pagination Ratings FAQs Social Sharing Facebook Twitter" ui-sref="store_settings_edit.product" ui-sref-active="active-nav" title="<?=  __tr('Product Settings')  ?>"><i class="fa fa-th-large fa-1x"></i> <span><?=  __tr('Product Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="User's Settings User New User Change Email Activation Required Login Terms and Conditions For User Registration Recaptcha Wishlist" ui-sref="store_settings_edit.user" ui-sref-active="active-nav" title="<?=  __tr("User Settings")  ?>"><i class="fa fa-users fa-1x"></i> <span><?=  __tr("User Settings")  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Social Settings Accounts Facebook Username Twitter Handle" ui-sref="store_settings_edit.social" ui-sref-active="active-nav" title="<?=  __tr('Social Accounts Settings')  ?>"><i class="fa fa-share-square-o fa-1x"></i> <span><?=  __tr('Social Accounts Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Contact Form email and Contact details for Page Address Email Telephone" ui-sref="store_settings_edit.contact" ui-sref-active="active-nav" title="<?=  __tr('Configure Contact Form email and Contact details for Page')  ?>"><i class="fa fa-address-card fa-1x"></i> <span><?=  __tr('Contact Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Placement and Misc Elements Settings Enable Credit Info Tag Cloud Additional Footer Text After Name and Copyright Google Analytics" ui-sref="store_settings_edit.placement" ui-sref-active="active-nav" title="<?=  __tr('Placement & Misc Settings')  ?>"><i class="fa fa-align-justify fa-1x"></i> <span><?=  __tr('Placement & Misc Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Social Login Settings Allow Facebook Login Allow Google Login Allow Twitter Login Allow Github Login Allow Slack Login" ui-sref="store_settings_edit.social_authentication_setup" ui-sref-active="active-nav" title="<?=  __tr('Social Login Settings')  ?>"><i class="fa fa-sign-in fa-1x"></i> <span><?=  __tr('Social Login Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Privacy Policy Settings Agreement" ui-sref="store_settings_edit.privacy_policy" ui-sref-active="active-nav" title="<?=  __tr('Privacy Policy Settings')  ?>"><i class="fa fa-lock fa-1x"></i> <span><?=  __tr('Privacy Policy Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Email Settings For Mandrill SMTP Sparkpost Mailgun" ui-sref="store_settings_edit.email_settings" ui-sref-active="active-nav" title="<?=  __tr('Email Settings For Mandrill, Smtp, Sparkpost, Mailgun')  ?>"><i class="fa fa-envelope-o fa-1x"></i> <span><?=  __tr('Email Settings')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Footer Setting" ui-sref="store_settings_edit.manage_footer_settings" ui-sref-active="active-nav" title="<?=  __tr('Footer Settings')  ?>"><i class="fa fa-envelope-o fa-1x"></i> <span><?=  __tr('Footer Setting')  ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lw-conf-item" data-tags="Landing Page Settings" ui-sref="store_settings_edit.landing_page" ui-sref-active="active-nav" title="<?=  __tr('Home Landing Page')  ?>"><i class="fa fa-files-o fa-1x"></i> <span><?=  __tr('Home Landing Page')  ?></span></a>
                </li>
            </ul>
        </div>
    </div> -->

    <div class="col-lg-12 col-md-12">
        <div ui-view></div>
    </div>

</div>

    
</div>