<?php

namespace App\Yantrana\Support;

use Razorpay\Api\Api as RazorpayAPI;

/**
 * This MailService class for manage globally -
 * mail service in application.
 *---------------------------------------------------------------- */
class RazorpayService
{
    /**
     * Constructor.
     *
  	 *-----------------------------------------------------------------------*/
    public function __construct()
    {
    	if (configItem('env_settings.razorpay_test_mode') == true) {
    		$razorpayKey = getStoreSettings('razorpay_testing_key');
    		$razorpaySecret = getStoreSettings('razorpay_testing_secret_key');
    	} else {
    		$razorpayKey = getStoreSettings('razorpay_live_key');
    		$razorpaySecret = getStoreSettings('razorpay_live_secret_key');
    	}

        $this->razorpayAPI = new RazorpayAPI($razorpayKey, $razorpaySecret);

    }

    /**
     * This method use for capturing payment.
     *
     * @param string $paymentId
     *
     * @return paymentRecieved
     *---------------------------------------------------------------- */
    public function capturePayment($paymentId)
    {	
    	// fetch a particular payment
    	$payment  = $this->razorpayAPI->payment->fetch($paymentId);

    	// Captures a payment
		$paymentRecieved  = $this->razorpayAPI->payment->fetch($paymentId)->capture(array( 'amount'=> $payment['amount']));
		 
		return $paymentRecieved->toArray();
    }


    /**
     * This method use for refunding payment
     *
     * @param string $paymentId
     *
     * @return paymentRecieved
     *---------------------------------------------------------------- */
    public function refundPayment($paymentId)
    {	
    	// fetch a particular payment
    	$payment  = $this->razorpayAPI->payment->fetch($paymentId);
    	 
    	if ($payment->status != 'refunded') {
    		// Creates partial refund for a payment
	    	$refund = $this->razorpayAPI->refund->create(array('payment_id' => $paymentId, 'amount' => $payment['amount'])); 

	    	// Returns a particular refund
			$refundData = $this->razorpayAPI->refund->fetch($refund['id']);
	  		return $refundData->toArray();
    	}
    	return false;
    }
 
}
