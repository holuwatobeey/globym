<?php

// default time zone
$timeZone = 'UTC';

// set time timezone for store
if (getStoreSettings('timezone')) {
    $timeZone = getStoreSettings('timezone');
}

/* Locale Setup to use GETTEXT - 22 DEC 2017
   Change locale functionality in HomeController
----------------------------------------------------------------------------- */
$localeConfig = require_once __DIR__.'../../../../config/locale.php';
require_once __DIR__.'../../../../app-boot-helper.php';
changeAppLocale(null, $localeConfig);

// set default time zone
date_default_timezone_set($timeZone);

if (!getStoreSettings('use_env_default_email_settings')) {
    $mailFromAddress    = getStoreSettings('mail_from_address');
    $mailFromName       = getStoreSettings('mail_from_name');
} else {
    $mailFromAddress    = env('MAIL_FROM_ADD') ?: getStoreSettings('business_email');
    $mailFromName       = getStoreSettings('store_name');
}

// set configuration items
config([
    'app.timezone'          => $timeZone,
    'lfm.images_url'        => url('/media-storage/upload-manager-assets'),
    'lfm.files_url'         => url('/media-storage/upload-manager-assets'),
    'mail.from.address'     => $mailFromAddress,
    'mail.from.name'        => $mailFromName,
    '__tech.mail_from'      => [
        env('MAIL_FROM_ADD', getStoreSettings('business_email')),
        env('MAIL_FROM_NAME', getStoreSettings('store_name')),
    ],
	'services.facebook.client_id'     => getStoreSettings('facebook_client_id'),
    'services.facebook.client_secret' => getStoreSettings('facebook_client_secret'),
    'services.facebook.redirect'      => route('social.user.login.callback', [getSocialProviderKey('facebook')]),
    'services.google.client_id'       => getStoreSettings('google_client_id'),
    'services.google.client_secret'   => getStoreSettings('google_client_secret'),
    'services.google.redirect'        => route('social.user.login.callback', [getSocialProviderKey('google')]),
    'services.twitter.client_id'       => getStoreSettings('twitter_client_id'),
    'services.twitter.client_secret'   => getStoreSettings('twitter_client_secret'),
    'services.twitter.redirect'        => route('social.user.login.callback', [getSocialProviderKey('twitter')]),

    'services.github.client_id'       => getStoreSettings('github_client_id'),
    'services.github.client_secret'   => getStoreSettings('github_client_secret'),
    'services.github.redirect'        => route('social.user.login.callback', [getSocialProviderKey('github')]),

    '__tech.recaptcha'	=>  [
    	'site_key' =>  env('RECAPTCHA_PUBLIC_KEY', getStoreSettings('recaptcha_site_key')),
        'secret_key' => env('RECAPTCHA_PRIVATE_KEY', getStoreSettings('recaptcha_secret_key')),
    ],

    '__tech.env_settings' => [
        'paypal_test_mode'  => env('USE_PAYPAL_SANDBOX', getStoreSettings('use_paypal')), 
        'stripe_test_mode'  => env('STRIPE_TEST_MODE', getStoreSettings('use_stripe')),
        'iyzipay_test_mode' => env('USE_IYZIPAY_SANDBOX', getStoreSettings('use_iyzipay')),
        'razorpay_test_mode'=> env('USE_RAZORPAY_SANDBOX', getStoreSettings('use_razorpay')),
        'paytm_test_mode'   => env('USE_PAYTM_SANDBOX', getStoreSettings('use_paytm')),
        'instamojo_test_mode'   => env('USE_INSTAMOJO_SANDBOX', getStoreSettings('use_instamojo')),
        'paystack_test_mode'   => env('USE_PAYSTACK_SANDBOX', getStoreSettings('use_payStack'))
    ]

]);

if (getStoreSettings('use_env_default_email_settings') == false) {
   
    config([
        // Mail driver
        'mail.driver' => getStoreSettings('mail_driver'),

        // Mail Setting for SMTP and Mandrill
        'mail.port' => getEmailConfiguration('port'),
        'mail.host' => getEmailConfiguration('host'),
        'mail.username' => getEmailConfiguration('username'),
        'mail.encryption' => getEmailConfiguration('encryption'),
        'mail.password' => getEmailConfiguration('password'),

        // Mail Setting for Sparkpost
        'services.sparkpost.secret' => getStoreSettings('sparkpost_mail_password_or_apikey'),

        'services.mailgun.domain' => getStoreSettings('mailgun_domain'),
        'services.mailgun.secret' => getStoreSettings('mailgun_mail_password_or_apikey'),
        'services.mailgun.endpoint' => getStoreSettings('mailgun_endpoint'),
    ]);
}

