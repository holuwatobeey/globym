<?php
// WARNING!! DO NOT CHANGE HERE, It may break auto update etc.
$lwSystemConfig = [
    'product_name' => 'LivelyCart Pro',
    'product_uid' => '035c15a4-7f87-4368-bf63-4313bd798ac5',
    'your_email' => '',
    'registration_id' => '', 
    'name' => 'LivelyCart PRO',
    "version" => "3.4.0",
    'app_update_url' => env('APP_UPDATE_URL', 'https://product-central.livelyworks.net')
];
$versionInfo = [];

if(file_exists(config_path('.lw_registration.php'))) {
    $versionInfo = require config_path('.lw_registration.php');
}

return array_merge($lwSystemConfig, $versionInfo);