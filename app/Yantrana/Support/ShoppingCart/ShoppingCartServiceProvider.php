<?php

namespace App\Yantrana\Support\ShoppingCart;

/*
 * Service Provider for ShoppingCart
 *-------------------------------------------------------- */

use Illuminate\Support\ServiceProvider;
use App\Yantrana\Support\NativeSession\NativeSession;

class ShoppingCartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Register 'shoppingCart' instance container to our ShoppingCart object

        $this->app->singleton('ShoppingCart', function ($app) {

            return new ShoppingCart(new NativeSession());
        });

        // Register Alias
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('ShoppingCart', ShoppingCartFacade::class);
        });
    }
}
