<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';


/*
|--------------------------------------------------------------------------
| LivelyCart Specific changes
|--------------------------------------------------------------------------
*/

// Set Custom Public Folder  - 4 MAR 2016
// set the public path to this directory
$app->bind('path.public', function() {
    return __DIR__;
});

/* Locale Setup to use GETTEXT - 24 MAY 2016 
   Committed on 9 Jan 2018
   Change locale functionality in HomeController
----------------------------------------------------------------------------- */
/*$localeConfig = require_once __DIR__.'/config/locale.php';
require_once __DIR__.'/app-boot-helper.php';
changeAppLocale(null, $localeConfig);*/

/* END Modifications ---------------------------------------------------------*/

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
