<?php
/*
* OrderController.php - Controller file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShoppingCart\OrderEngine;
use App\Yantrana\Components\ShoppingCart\OrderPaymentsEngine;
use App\Yantrana\Components\ShoppingCart\Requests\OrderProcessRequest;

// form Requests
use App\Yantrana\Support\CommonPostRequest;
use Session;
use JavaScript;
use Breadcrumb;
use NativeSession;

class OrderController extends BaseController
{
    /**
     * @var OrderEngine - Order Engine
     */
    protected $orderEngine;

    /**
     * @var OrderPaymentsEngine - OrderPayments Engine
     */
    protected $orderPaymentsEngine;

    /**
     * Constructor.
     *
     * @param OrderEngine $orderEngine - Order Engine
     *-----------------------------------------------------------------------*/
    public function __construct(OrderEngine $orderEngine, OrderPaymentsEngine $orderPaymentsEngine)
    {
        $this->orderEngine = $orderEngine;
        $this->orderPaymentsEngine = $orderPaymentsEngine;
    }

    /**
     * To display order summary view.
     *---------------------------------------------------------------- */
    public function displayOrderSummary()
    {
        /*if (isActiveUser() || !isLoggedIn()) {
            Session::put('redirect_intended', true);

            return redirect()->route('user.login');
        }*/

        // if seesion have order summary details then delete it
        if (Session::has('ordeSummaryDataIds')) {
            Session::forget('ordeSummaryDataIds');
        }

        return $this->loadPublicView('order.user.summary', [
            'breadCrumb' => Breadcrumb::generate('cart-order'),
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * This function use for get cart details of data for order.
     *
     * @param string $country
     * @param float  $discountAddedPrice
     *
     * @return json data
     *---------------------------------------------------------------- */
    public function cartOrderDetails($addressID, $addressID1, $couponCode, $shippingCountryId, $billingCountryId, $useAsBilling, $shippingMethod)
    {
        if (Session::has('redirect_intended')) {
            Session::forget('redirect_intended');
        }

        $countryData = [
            'shippingCountryId' => $shippingCountryId,
            'billingCountryId'  => $billingCountryId,
            'useAsBilling'      => $useAsBilling,
            'shippingMethod'    => $shippingMethod
        ];

        $engineReaction = $this->orderEngine->prepareOrderSummaryData($addressID, $addressID1, $couponCode, $countryData);

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
     * order submit process.
     *
     * @param OrderProcessRequest $request
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function orderProcess(OrderProcessRequest $request)
    {
        $engineReaction = $this->orderEngine
                               ->prepareOrderProcess($request->all());

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
      * Process Account Renewal Payment
      *
      * @return json object
      *---------------------------------------------------------------- */

    public function processIyzipayPayment(CommonPostRequest $request, $encryptOrderID)
    {  
        $orderUid = decrypt($encryptOrderID);
        
        $processReaction = $this->orderPaymentsEngine->processIyzipayPayment($request->all(), $orderUid);
    
        if ($processReaction['reaction_code'] == 1) {
            $orderUid = $processReaction['data']['orderUid'];

            return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
        }

        return $this->loadPublicView('order.user.payment-cancel',[
                'orderUid' => $orderUid,
                'message'  => $processReaction['message'],
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
        
    }

    /**
     * Thanks page.
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function stripePaymentSuccess($orderToken)
    {
        // check if recent order
        $orderUid = $this->orderEngine->getOrderPaymentToken($orderToken);

        // used for same function for paypal
        if (!$orderUid or $this->orderEngine->processThanksPayPalOrder($orderUid) == false) {
            return 'invalid request';
        }

        return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
    }

    /**
     * Thanks page.
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function razorpayPaymentSuccess($orderToken)
    {
        // check if recent order
        $orderUid = $this->orderEngine->getOrderPaymentToken($orderToken);

        // used for same function for paypal
        if (!$orderUid or $this->orderEngine->processThanksPayPalOrder($orderUid) == false) {
            return 'invalid request';
        }

        return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
    }

    /**
     * Thanks page.
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function paystackPaymentSuccess($orderToken)
    {
        // check if recent order
        $orderUid = $this->orderEngine->getOrderPaymentToken($orderToken);

        // used for same function for paypal
        if (!$orderUid or $this->orderEngine->processThanksPayPalOrder($orderUid) == false) {
            return 'invalid request';
        }

        return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
    }

    /**
     * Stripe Checkout
     *
     * @param string $orderUid
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function stripeCheckout(CommonPostRequest $stripeOrderRequest)
    {
        $orderReaction = $this->orderEngine
                       ->prepareOrderProcess($stripeOrderRequest->all());

        if ($orderReaction['reaction_code'] === 1) {
            // charge Stripe customer
            $paymentEngineReaction = $this->orderPaymentsEngine->createStripeChargeRequest(
                $orderReaction['data']['orderID'],
                $stripeOrderRequest->get('stripeToken')
            );

            $paymentEngineReaction['data']['orderPaymentToken'] = $orderReaction['data']['orderPaymentToken'];

            return __processResponse($paymentEngineReaction, [], $paymentEngineReaction['data']);
        }

        return __processResponse($orderReaction, [], $orderReaction['data']);
    }

    /**
     * Razorpay Checkout
     *
     * @param string $orderUid
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function razorpayCheckout(CommonPostRequest $razorpayCheckoutOrderRequest)
    {
     	$paymentData = $razorpayCheckoutOrderRequest->all();
 
        $orderReaction = $this->orderEngine->prepareOrderProcess($paymentData);

        if ($orderReaction['reaction_code'] === 1) {
            // charge Stripe customer
            $paymentEngineReaction = $this->orderPaymentsEngine->createRazorpayChargeRequest($orderReaction['data']['orderID'], $paymentData['razorpay_payment_id']);
 
            $paymentEngineReaction['data']['razorpayChargeRequest']['orderPaymentToken'] = $orderReaction['data']['orderPaymentToken'];

            return __processResponse($paymentEngineReaction, [], $paymentEngineReaction['data']);
        }

        return __processResponse($orderReaction, [], $orderReaction['data']);
    }

    /**
     * Paystack Checkout
     *
     * @param string $orderUid
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function paystackCheckout(CommonPostRequest $paystackCheckoutOrderRequest)
    {
        $paymentData = $paystackCheckoutOrderRequest->all();
        
        $orderReaction = $this->orderEngine->prepareOrderProcess($paymentData);

        if ($orderReaction['reaction_code'] === 1) {
            // charge Stripe customer
            $paymentEngineReaction = $this->orderPaymentsEngine->createPaystackChargeRequest($orderReaction['data']['orderID'], $paymentData['paystack_refrence_id']);

            $paymentEngineReaction['data']['orderPaymentToken'] = $orderReaction['data']['orderPaymentToken'];
            
            return __processResponse($paymentEngineReaction, [], $paymentEngineReaction['data']);
        }

        return __processResponse($orderReaction, [], $orderReaction['data']);
    }

    /**
     * Prepare PayPal Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function preparePaypalOrder($orderUID)
    {
        $this->orderEngine->createPaypalOrder($orderUID);
    }

     /**
     * Prepare PayPal Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function prepareInstamojoOrder($orderUID)
    {
       $processReaction = $this->orderEngine->createInstamojoOrder($orderUID);
  
        if ($processReaction['reaction_code'] == 1) {
            $instamojoLongUrl = $processReaction['data']['longurl'];

            return redirect($instamojoLongUrl);
        } else if ($processReaction['reaction_code'] == 2) {

            return $this->loadPublicView('order.user.payment-cancel', [
                'orderUid' => $orderUID,
                'message'  => $processReaction['message'],
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
        }

        return $this->loadPublicView('order.user.payment-cancel',[
            'orderUid' => $orderUID,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * Prepare paytm callback Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */

    public function instamojoCallback(CommonPostRequest $request, $encryptOrderID) 
     {
        $orderUid = decrypt($encryptOrderID);
    
        $processReaction = $this->orderPaymentsEngine->createInstamojoPayment($request->all(), $orderUid);

        if ($processReaction['reaction_code'] == 1) {
           
            return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
        }
 
        return $this->loadPublicView('order.user.payment-cancel',[
            'orderUid' => $orderUid,
            'message' => $processReaction['message'],
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * Prepare PayPal Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function preparePaytmOrder($orderUID)
    {
       $processReaction = $this->orderEngine->createPaytmOrder($orderUID);
     
       if ($processReaction['reaction_code'] == 1) {
            $paytmData = $processReaction['data']['handlePaytmRequest'];

            $paytm_txn_url = $paytmData['paytm_txn_url'];
            $paramList = $paytmData['paramList'];
            $checkSum = $paytmData['checkSum'];

            return $this->loadPublicView( 'order.user.paytm-merchant-form', compact( 'paytm_txn_url', 'paramList', 'checkSum' ) );
       }

       return $this->loadPublicView('order.user.payment-cancel',[
            'orderUid' => $orderUID,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * Prepare paytm callback Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */

    public function paytmCallback(CommonPostRequest $request, $encryptOrderID) 
     {  
        $orderUid = decrypt($encryptOrderID);
      
        $processReaction = $this->orderPaymentsEngine->createPaytmPayment($request->all(), $orderUid);

        $respondMsg = [];
        if (isset($processReaction['message'])) {
            $respondMsg = $processReaction['message'];
        }
       
        if ($processReaction['reaction_code'] == 1) {
            $orderUid = $processReaction['data']['orderUid'];
            return $this->loadPublicView('order.user.thank-you', [
                'invoice' => $orderUid,
                'payment_status' => 'Completed',
                'hideSidebar' => true,
                'showFilterSidebar' => false
            ]);
        }
 
        return $this->loadPublicView('order.user.payment-cancel',[
            'orderUid' => $orderUid,
            'message'  => $respondMsg,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * Thanks page.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function thanksOnPayPalOrder(CommonPostRequest $paypalRequest)
    {
        if ((__ifIsset($paypalRequest) == false)
                or ($paypalRequest->has('invoice') == false)) {
            return 'invalid request';
        }

        if ($this->orderEngine->processThanksPayPalOrder(
                $paypalRequest->get('invoice')) == false) {
            return 'invalid request';
        }

        $responseData = [
			'payment_status' => $paypalRequest->get('payment_status'),
			'invoice' => $paypalRequest->get('invoice'),
			'hideSidebar' => true,
			'showFilterSidebar' => false
        ];

        return $this->loadPublicView('order.user.thank-you', $responseData);
    }

    /**
     * Thanks page.
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function paymentCancel($orderToken, CommonPostRequest $request)
    {  
        // check if recent order
        $orderUid = $this->orderEngine->getOrderPaymentToken($orderToken);

        if ($orderUid) {
            $this->orderEngine->updatePaymentFailed($orderUid);
        }

        return $this->loadPublicView('order.user.payment-cancel',[
        	'orderUid' => $orderUid,
            'message'  => isset($request->message) ? $request->message : '',
    	 	'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * order coupon process.
     *
     * @param string $code
     * @param float  $cartTotalPrice
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function applyCouponProcess(CommonPostRequest $request)
    {   
        $processReaction = $this->orderEngine
                                ->processApplyCoupon(
                                        $request->input('orderData.code'),
                                        $request->input('orderData.email')
                                    );

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Coupon apply successfully.'),
                    2 => __tr('Please Enter valid coupon code.'),
                    9 => __tr('Your Coupon not valid for this Order Amount.'),
                ], $processReaction['data']);
    }

    /**
     * check order.
     *---------------------------------------------------------------- */
    public function checkOrder()
    {
        if (!isLoggedIn()) {
            Session::put('redirect_intended', true);

            return __apiResponse([
                'success' => false,
                'error' => true,
                ]);
        }

        if (isActiveUser()) {
            Session::flash('invalidUserMessage', __tr('Invalid request please contact administrator.'));

            return __apiResponse([
                'success' => false,
                'error' => true,
                ]);
        }

        return __apiResponse([
                'success' => true,
                'error' => false,
            ]);
    }

    /**
     * View shopping cart order success page.
     *---------------------------------------------------------------- */
    public function orderSuccess()
    {

        // session have success message data then remove it
        if (NativeSession::has('successMessage')) {
            $success = NativeSession::get('successMessage');
        }     

        if (__isEmpty($success)) {
            return redirect()->route('home.page');
        }

        return $this->loadPublicView('order.user.success-page', ['success' => $success]);
    }

    /**
     * My order list view.
     *---------------------------------------------------------------- */
    public function userOrdersList()
    {
        // Check if user is logged in
        if (isActiveUser()) {
            return redirect()->route('user.login');
        }

        $breadCrumb = $this->orderEngine
                           ->breadcrumbGenerate('orders');

        $breadCrumb['data']['hideSidebar'] = true;
        $breadCrumb['data']['showFilterSidebar'] = false;
        
        // Load user list view
        return $this->loadPublicView('order.user.list', $breadCrumb['data']);
    }

    /**
     * Create a list of my-order view.
     *
     * @param number $status
     *---------------------------------------------------------------- */
    public function index($status)
    {
        $engineReaction = $this->orderEngine
                               ->prepareOrderList($status);

        $requireColumns = [

            'creation_date' => function ($key) use ($status) {
                return ($status == 1)
                    ? formatStoreDateTime($key['created_at'])
                    : formatStoreDateTime($key['updated_at']);
            },
            'formated_status' => function ($key) {
                return $this->findStatus($key['status']);
            },
            'get_order_details_Route' => function ($key) {
                return route('my_order.details', ['orderUID' => $key['order_uid']]);
            },
            'formated_name' => function ($key) {
                return $key['fname'].' '.$key['lname'];
            },
            'invoiceDownloadURL' => function ($key) {
                return route('order.user.invoice.download', $key['order_uid']);
            },
            '_id',
            'status',
            'users_id',
            'order_uid',
            'payment_status',
            'fname',
        ];
        
        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * return mathching status.
     *
     * @param int $ID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findStatus($ID)
    {
        $status = config('__tech.orders.status_codes');

        return $status[$ID];
    }

    /**
     * Order detail view.
     *
     * @param int $orderUID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function orderDetail($orderUID)
    {
        // Check if user is active or not
        if (isActiveUser()) {
            return redirect()->route('user.login');
        }

        // Check if user logged in
        if (!isLoggedIn()) {
            return redirect()->route('user.login');
        }

        $orderDetails = $this->orderEngine
                             ->getMyOrderDetails($orderUID);

        // If reaction code not exist then redirect on product page
        if ($orderDetails['reaction_code'] == 18) {
            return redirect()->route('home.page')
                        ->with([
                                'error' => true,
                                'message' => __tr('Requested order details not exist'),
                            ]);
        }

        JavaScript::put([
            'orderDetails' => $orderDetails,
        ]);

        // session have success message data then remove it
        if (NativeSession::has('orderSuccessMessage')) {
            NativeSession::remove('orderSuccessMessage');
        }

        $breadCrumb = $this->orderEngine
                           ->breadcrumbGenerate('order-details');

        $breadCrumb['data']['hideSidebar'] = true;
        $breadCrumb['data']['showFilterSidebar'] = false;

        return $this->loadPublicView('order.user.details', $breadCrumb['data']);
    }

    /**
     * Change address in order then get shipping, tax and coupon amount.
     *
     * @param int $countryCode
     *---------------------------------------------------------------- */
    public function changeAddressInOrder($addressID)
    {
        $processReaction = $this->orderEngine
                                  ->changeAddressInOrderDetails($addressID);

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * user Log detial dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function userLogDetails($orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareOrderLogDetailDiallog($orderID);

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * User Cancel order dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function cancelOrderSupportData($orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareCancelOrderData($orderID);

        return __processResponse($processReaction, [
                 2 => __tr('You are not authenticate to cancel order.'),
                18 => __tr('Order does not exist.'),
            ], null, true);
    }

    /**
     * User Cancel order dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function orderCancel(CommonPostRequest $request, $orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareCancelOrderProcess($orderID);

        return __processResponse($processReaction, [
                    1 => __tr('Order cancelled successfully.'),
                    18 => __tr('Order does not exist.'),
                    2 => __tr('Invalid request.'),
                    9 => __tr('Invalid request please contact administrator.'),
                ], $processReaction['data']);
    }

    /**
     * Download Invoice for order by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function invoiceDownload($orderID)
    {
        return  $this->orderEngine->processInvoiceDownload($orderID);
    }

    /**
     * Check user email.
     *
     * @param string $email
     * @param number $couponId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function checkUserEmail($email, $couponId)
    {
        $processReaction = $this->orderEngine
                                ->checkUserEmail($email, $couponId);

        return __processResponse($processReaction, [], [], true);
    }
}
