<?php

/*
* StripePaymentEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Core\BaseEngine;

use Yabacon\Paystack;
use Exception;

class PaystackPaymentEngine extends BaseEngine
{

    /**
      * Constructor
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct()
    {  
        if (configItem('env_settings.paystack_test_mode') == true) {
            $paystackSecretKey = getStoreSettings('payStack_testing_secret_key');
            $paystackPublicKey = getStoreSettings('payStack_testing_public_key');
        } else {
            $paystackSecretKey = getStoreSettings('payStack_live_secret_key');
            $paystackPublicKey = getStoreSettings('payStack_live_public_key');
        }

        if (!is_string($paystackSecretKey) || !(substr($paystackSecretKey, 0, 3) ==='sk_')) {
            $paystackSecretKey = substr_replace($paystackSecretKey, "sk_".$paystackSecretKey, 0);
        }
       
        if (!__isEmpty($paystackSecretKey)) {
            $this->paystackAPI = new Paystack($paystackSecretKey);
        }
    }

    /**

     * @param  string $ordderData - Order ID
     * @param  string -$stripeToken - Stripe Token

     * request to Stripe checkout
     *---------------------------------------------------------------- */
    public function capturePaystackTransaction($paystackRefrenceId)
    {  
        try
        {
            // verify using the library
            $transactionDetail = $this->paystackAPI->transaction->verify([
                'reference'=>   $paystackRefrenceId, // unique to transactions
            ]);

            return $this->engineReaction(1, [
                'transactionDetail' => $transactionDetail,
            ]);
           
        } catch(\Yabacon\Paystack\Exception\ApiException $e){
       
            // print_r($e->getResponseObject());

            return $this->engineReaction(2, null, $e->getMessage());
        }
    }
}
