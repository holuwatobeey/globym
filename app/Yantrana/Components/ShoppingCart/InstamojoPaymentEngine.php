<?php

/*
* InstamojoPaymentEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Core\BaseEngine;

use Instamojo;
use Exception;
use Auth;

class InstamojoPaymentEngine extends BaseEngine
{

    /**
      * Constructor
      *
      * @param InstamojoPaymentRepository $instamojoPaymentRepository - InstamojoPayment Repository
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct()
    {
        if (configItem('env_settings.instamojo_test_mode') == true) {
            $instamojoApiKey = getStoreSettings('instamojo_testing_api_key');
            $instamojoTokenKey = getStoreSettings('instamojo_testing_auth_token_key');
            $instamojoRedirectUrl = config('__tech.instamojo_urls.sandbox');
        } else {
            $instamojoApiKey = getStoreSettings('instamojo_live_api_key');
            $instamojoTokenKey = getStoreSettings('instamojo_live_auth_token_key');
            $instamojoRedirectUrl = config('__tech.instamojo_urls.production');
        }
        
        $this->instamojoApi = new Instamojo\Instamojo($instamojoApiKey, $instamojoTokenKey, $instamojoRedirectUrl);
    }

    /**

     * @param  string $ordderData - Order ID
     * @param  string -$stripeToken - Stripe Token

     * request to Stripe checkout
     *---------------------------------------------------------------- */
    public function processInstamojoChargeRequest($orderDetails)
    {   
        $user = Auth::user($orderDetails['userId']);
        $orderUId = encrypt($orderDetails['orderUID']);
        try {
            $paymentRequest = $this->instamojoApi->paymentRequestCreate(array(
                "purpose" => 'Order / '.$orderDetails['orderUID'],
                "amount" => $orderDetails['totalOrderAmount'],
                "send_email" => false,
                'buyer_name' => $user['fname'].' '.$user['lname'],
                "email" => $user['email'],
                //'phone' => '9665899685',
                'webhook' => 'http://instamojo.com/webhook/',
                'allow_repeated_payments' => false,
                "redirect_url" => route( 'order.instamojo.callback_url', ['encryptOrderID' => $orderUId]),
            ));
            
           return __engineReaction(1, $paymentRequest);
          
        }
        catch (Exception $e) {
   
            return __engineReaction(2, null, 'Error: ' . $e->getMessage());
        }
    }

     /**

     * @param  string $ordderData - Order ID
     * @param  string -$stripeToken - Stripe Token

     * request to Stripe checkout
     *---------------------------------------------------------------- */
    public function preparePaymentRequestStatus($inputData, $orderUid)
    {  
        try {
            $paymentRequestDetails = $this->instamojoApi->paymentRequestStatus($inputData['payment_request_id']);

            $paymentDetails = $this->instamojoApi->paymentDetail($inputData['payment_id']);
         
            $paymentDetails = [
                'paymentRequestDetails' => $paymentRequestDetails,
                'paymentDetails'        => $paymentDetails
            ];

            return __engineReaction(1, $paymentDetails);

        }
        catch (Exception $e) {
            return __engineReaction(2, null, 'Error: ' . $e->getMessage());
        }
    }

}
