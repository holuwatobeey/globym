<?php

/*
* StripePaymentEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Core\BaseEngine;
use App\Yantrana\Components\ShoppingCart\Blueprints\StripePaymentEngineBlueprint;

use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\Charge as StripeCharge;
use Exception;

class StripePaymentEngine extends BaseEngine implements StripePaymentEngineBlueprint
{

    /**
      * Constructor
      *
      * @param StripePaymentRepository $stripePaymentRepository - StripePayment Repository
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct()
    {
        Stripe::setApiKey(
            (configItem('env_settings.stripe_test_mode') == true)
                ? getStoreSettings('stripe_testing_secret_key')
                : getStoreSettings('stripe_live_secret_key')
        );

        Stripe::setAppInfo(
          configItem('app_name'),
          configItem('lc_version')
        );

        Stripe::setApiVersion(config('services.stripe.lw_api_date'));

    }

    /**

     * @param  string $ordderData - Order ID
     * @param  string -$stripeToken - Stripe Token

     * request to Stripe checkout
     *---------------------------------------------------------------- */
    public function processStripeCharge($ordderData, $stripeToken)
    {  
        try {
            // create customer
            $customer = StripeCustomer::create(array(
              'email' => array_get($ordderData, 'jsonData.user.email'),
              'card'  => $stripeToken
            ));

            // charge the card
            $charge = StripeCharge::create(array(
              'customer' => $customer->id,
              'amount'   => $this->getAmount($ordderData['totalOrderAmount']),
              'currency' => $ordderData['currencyCode']
            ));

            return $this->engineReaction(1, [
                'chargeDetails' => $charge,
                'chargeStatus' => $charge['status'],
            ], $charge['outcome']['seller_message']);
        } catch (Exception $e) {
            return $this->engineReaction(2, null, $e->getMessage());
        }
    }

    /**

     * @param  string $ordderData - Order ID
     * @param  string -$stripeToken - Stripe Token

     * request to Stripe checkout
     *---------------------------------------------------------------- */
    public function getAmount($amount)
    {
        return $amount * 100;
    }
}
