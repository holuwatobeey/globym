<?php
/*
* OrderPaymentsEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderPaymentsRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderPaymentsEngineBlueprint;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\ShoppingCart\OrderEngine;
use App\Yantrana\Components\ShoppingCart\StripePaymentEngine;
use App\Yantrana\Components\ShoppingCart\PaystackPaymentEngine;
use App\Yantrana\Components\ShoppingCart\InstamojoPaymentEngine;
use XLSXWriter;
use App;
use App\Yantrana\Support\RazorpayService;
use App\Yantrana\Components\Support\PaytmService;
use App\Yantrana\Components\Support\IyzipayService;
use NativeSession; // custom Session

class OrderPaymentsEngine implements OrderPaymentsEngineBlueprint
{
    /**
     * @var OrderEngine
     */
    protected $orderEngine;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderPaymentsRepository - OrderPayments Repository
     */
    protected $orderPaymentsRepository;

     /**
     * @var manageOrderRepository - manageOrderRepository Repository
     */
    protected $manageOrderRepository;

     /**
     * @var OrderRepository - orderRepository Repository
     */
    protected $orderRepository;

    /**
     * @var StripePaymentEngine - StripePaymentEngine Engine
     */
    protected $stripePaymentEngine;

    /**
     * @var instamojoPaymentEngine - InstamojoPaymentEngine Engine
     */
    protected $instamojoPaymentEngine;

    /**
     * @var RazorpayService - Razorpay Service
     */
    protected $razorpayService;

    /**
     * @var PaytmService - Paytm Service
     */
    protected $paytmService;

    /**
     * @var PaytmService - Paytm Service
     */
    protected $iyzipayService;

    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * @var PaystackPaymentEngine - PaystackPaymentEngine Engine
     */
    protected $paystackPaymentEngine;


    /**
     * Constructor.
     *
     * @param OrderPaymentsRepository $orderPaymentsRepository - OrderPayments Repository
     * @param RazorpayService  	 $razorpayService- Razorpay Service
     *-----------------------------------------------------------------------*/
    public function __construct(OrderEngine  $orderEngine,
        MailService $mailService,
        OrderPaymentsRepository $orderPaymentsRepository,
        ManageOrderRepository $manageOrderRepository,
        OrderRepository $orderRepository,
        StripePaymentEngine $stripePaymentEngine,
        InstamojoPaymentEngine $instamojoPaymentEngine, 
        RazorpayService $razorpayService, 
        PaytmService $paytmService, 
        IyzipayService $iyzipayService,
        ShoppingCartEngine $shoppingCartEngine,
        PaystackPaymentEngine $paystackPaymentEngine)
    {
        $this->mailService     = $mailService;
        $this->orderEngine     = $orderEngine;
        $this->orderRepository = $orderRepository;
        $this->manageOrderRepository = $manageOrderRepository;
        $this->orderPaymentsRepository = $orderPaymentsRepository;
        $this->stripePaymentEngine = $stripePaymentEngine;
        $this->instamojoPaymentEngine   = $instamojoPaymentEngine;
        $this->razorpayService 			= $razorpayService;
        $this->paytmService             = $paytmService;
        $this->iyzipayService             = $iyzipayService;
        $this->shoppingCartEngine       = $shoppingCartEngine;
        $this->paystackPaymentEngine       = $paystackPaymentEngine;
    }

    /**
     * Stripe Charge Request
     *
     * @param int $orderUid - Order Uid
     * @param int $stripeToken - Stripe Token
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function createStripeChargeRequest($orderUid, $stripeToken)
    {
        // Get order details from database for latest placed order
        $orderDetails = $this->orderEngine->prepareOrderDetails($orderUid);

        // process card charge
        $stripeChargeRequest = $this->stripePaymentEngine->processStripeCharge($orderDetails, $stripeToken);
      
        if ($stripeChargeRequest['reaction_code'] == 1) {
            // update order & payment status
            $this->updateOrderForStripe($orderDetails, $stripeChargeRequest);
            // get back the reaction
            return $stripeChargeRequest;

        } else if ($stripeChargeRequest['reaction_code'] == 2) {

            return $stripeChargeRequest;
        }
    }

    /**
     * Razorpay Charge Request
     *
     * @param int $orderUid - Order Uid
     * @param int $stripeToken - Stripe Token
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function createRazorpayChargeRequest($orderUid, $razorpayPaymentId)
    {
        // Get order details from database for latest placed order
        $orderDetails = $this->orderEngine->prepareOrderDetails($orderUid);
        // dd($orderDetails['totalOrderAmount']);
        // process card charge
        $razorpayChargeRequest = $this->razorpayService->capturePayment($razorpayPaymentId);
        // update order & payment status
        $this->updateOrderForRazorpay($orderDetails, $razorpayChargeRequest);
        // get back the reaction

     	return __engineReaction(1, [
            'razorpayChargeRequest' => $razorpayChargeRequest,
        ]);
    }

    /**
     * Paystack Charge Request
     *
     * @param int $orderUid - Order Uid
     * @param int $stripeToken - Stripe Token
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function createPaystackChargeRequest($orderUid, $paystackRefrenceId)
    {
        // Get order details from database for latest placed order
        $orderDetails = $this->orderEngine->prepareOrderDetails($orderUid);
       
        // process card charge
        $paystackChargeRequest = $this->paystackPaymentEngine->capturePaystackTransaction($paystackRefrenceId);
 
        if ($paystackChargeRequest['reaction_code'] == 1) {
            // update order & payment status
            $this->updateOrderForPaystack($orderDetails, $paystackChargeRequest);
            // get back the reaction

            return __engineReaction(1, [
                'paystackChargeRequest' => $paystackChargeRequest,
            ]);

        } else if ($paystackChargeRequest['reaction_code'] == 2) {

            return $paystackChargeRequest;
        }
    }

    /**
     * Stripe Charge Request
     *
     * @param int $orderDetails - Order
     * @param int $stripeChargeRequest -
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    protected function updateOrderForStripe($orderDetails, $stripeChargeRequest)
    {
        if ($stripeChargeRequest['reaction_code'] === 1) {
            // record the payment
            $this->orderPaymentsRepository->storeStripePayment($orderDetails, $stripeChargeRequest['data']['chargeDetails']);

            $order = $this->orderRepository->fetch($orderDetails['orderUID']);

            // mark order and payment as completed
            $this->orderRepository->updateOrderPaymentStatus($order, 2);

            // notify concerned persons
            return $this->notifyPaymentConfirmation($orderDetails['orderUID']);
        }
        // Notify Admin about Payment Failure & Notify concerned persons.
    }


    /**
     * Razor Pay Charge Request
     *
     * @param int $orderDetails - Order
     * @param int $razorpayChargeRequest -
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    protected function updateOrderForRazorpay($orderDetails, $razorpayChargeRequest)
    {
        if ($razorpayChargeRequest['captured'] === true) {
            // record the payment
            $this->orderPaymentsRepository->storeRazorpayPayment($orderDetails, $razorpayChargeRequest);

            $order = $this->orderRepository->fetch($orderDetails['orderUID']);

            // mark order and payment as completed
            $this->orderRepository->updateOrderPaymentStatus($order, 2);

            // notify concerned persons
            return $this->notifyPaymentConfirmation($orderDetails['orderUID']);
        }
        // Notify Admin about Payment Failure & Notify concerned persons.
    }

    /**
     * Razor Pay Charge Request
     *
     * @param int $orderDetails - Order
     * @param int $razorpayChargeRequest -
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    protected function updateOrderForPaystack($orderDetails, $paystackChargeRequest)
    {
       
        if ($paystackChargeRequest['reaction_code'] === 1) {
          
            // record the payment
            $this->orderPaymentsRepository->storePaystackPayment($orderDetails, $paystackChargeRequest);

            $order = $this->orderRepository->fetch($orderDetails['orderUID']);

            // mark order and payment as completed
            $this->orderRepository->updateOrderPaymentStatus($order, 2);

            // notify concerned persons
            return $this->notifyPaymentConfirmation($orderDetails['orderUID']);
        }
        // Notify Admin about Payment Failure & Notify concerned persons.
    }

     /**
     * Send PayPal for Payment.
     *---------------------------------------------------------------- */
    public function createInstamojoPayment($inputData, $orderUid)
    {  
        // Fetch order
        $order = $this->orderRepository->fetch($orderUid);
     
        if (__isEmpty($order)) {
            return __engineReaction(2, null, __tr('Order Data Not Exist.'));
        }

        if ($inputData['payment_status'] == "Failed") {
            //if order failed then update order status
            $this->orderEngine->updatePaymentFailed($order['order_uid']);

            NativeSession::set('orderCancel', 'Payment Failed.');
          
            return __engineReaction(2, null, 'Transaction cancelled by customer after landing on Payment Gateway Page.');
        }
       
        $paymentRequestDetails = $this->instamojoPaymentEngine->preparePaymentRequestStatus($inputData, $order['order_uid']);
      

        if ($paymentRequestDetails['reaction_code'] == 1) {

            $paymentData = $paymentRequestDetails['data'];

            if ($paymentData['paymentDetails'] && $paymentData['paymentRequestDetails']) {

                $paymentDetailData = $paymentData['paymentDetails'];

                if ($paymentDetailData['status'] == 'Credit') {
                    //remove shopping cart item if payment failed.
                    $this->shoppingCartEngine->processRemoveAlItems();

                    //remove shopping cart item if payment failed.
                    $paymentData = [
                        'type' => 1, // Credit
                        'txn' => $paymentDetailData['payment_id'],
                        'payment_method' => (configItem('env_settings.instamojo_test_mode') == true) ? 19 : 18, // instamojo
                        'currency_code' => $paymentDetailData['currency'],
                        'gross_amount' => $paymentDetailData['amount'],
                        'fee' => $paymentDetailData['fees'],
                        'orders__id' => $order['_id'],
                        'raw_data' => json_encode($paymentRequestDetails),
                        'payment_status' => 2, // Completed
                    ];
      
                    // notify concerned persons
                    // return $this->notifyPaymentConfirmation($order['order_uid']);
                   
                    if ($this->orderPaymentsRepository->storeNewPayment($paymentData)) {

                        // mark order and payment as completed
                        $this->orderRepository->updateOrderPaymentStatus($order, 2);

                        // notify concerned persons
                        $this->notifyPaymentConfirmation($order['order_uid']);

                        return __engineReaction(1, [
                            'orderUid' => $order['order_uid']
                        ], __tr('Payment Confirmed.'));

                    }

                    return __engineReaction(2, null, __tr('Payment Failed.'));

                } else {
                    //if order failed then update order status
                    $this->orderEngine->updatePaymentFailed($order['order_uid']);

                    return __engineReaction(2, null, $paymentRequestDetails['message']);
                }
            }
        }

        return __engineReaction(2, null, $paymentRequestDetails['message']);
    }

    /**
     * Send PayPal for Payment.
     *---------------------------------------------------------------- */
    public function createPaytmPayment($inputData, $orderUid)
    {   
        // Fetch order
        $order = $this->orderRepository->fetch($orderUid);
      
        if (__isEmpty($order)) {
            return __engineReaction(2, null, __tr('Order Data Not Exist.'));
        }

        $paytmChecksum = isset($inputData["CHECKSUMHASH"]) ? $inputData["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        $paramList = array();
        $isValidChecksum = "FALSE";

         if (configItem('env_settings.paytm_test_mode') == true) {
            $paytmMerchantKey = getStoreSettings('paytm_testing_merchant_key');
        } else {
            $paytmMerchantKey = getStoreSettings('paytm_live_merchant_key');
        }

         //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = $this->paytmService->verifyCheckSum($paramList, $paytmMerchantKey, $paytmChecksum); //will return TRUE or FALSE string.
        
         // If payment initialization status is not TXN_SUCCESS then show error
        if ($inputData['STATUS'] == 'TXN_FAILURE') {

            //if order failed then update order status
            $this->orderEngine->updatePaymentFailed($order['order_uid']);

            NativeSession::set('orderCancel', 'Payment Failed.');
            
            if ($inputData['RESPCODE'] == 330) {
                return __engineReaction(2, null, 'Paytm Keys are Invalid');
            }

            return __engineReaction(2, null, 'Transaction cancelled by customer after landing on Payment Gateway Page.');
        }
        
        //check checkSum array key same or not
        if ($isValidChecksum) {
            // If payment initialization status is not success then show error
            if ($inputData['STATUS'] == 'TXN_SUCCESS') {

                //remove shopping cart item if payment failed.
                $this->shoppingCartEngine->processRemoveAlItems();

                //remove shopping cart item if payment failed.
                $paymentData = [
                    'type' => 1, // Deposit
                    'txn' => $inputData['TXNID'],
                    'payment_method' => (configItem('env_settings.paytm_test_mode') == true) ? 17 : 16, // paytm
                    'currency_code' => $inputData['CURRENCY'],
                    'gross_amount' => $inputData['TXNAMOUNT'],
                    'fee' => null,
                    'orders__id' => $order['_id'],
                    'raw_data' => json_encode($inputData),
                    'payment_status' => 2, // Completed
                ];
 
                // notify concerned persons
                // return $this->notifyPaymentConfirmation($order['order_uid']);
               
                if ($this->orderPaymentsRepository->storeNewPayment($paymentData)) {

                    // mark order and payment as completed
                    $this->orderRepository->updateOrderPaymentStatus($order, 2);

                    // notify concerned persons
                    $this->notifyPaymentConfirmation($order['order_uid']);

                    return __engineReaction(1, [
                        'orderUid' => $order['order_uid']
                    ], __tr('Payment Confirmed.'));
                }

                return __engineReaction(2, null, __tr('Payment Failed.'));
               
            } 

        }  else if ($isValidChecksum == false) {
            //if order failed then update order status
            $this->orderEngine->updatePaymentFailed($order['order_uid']);

            return $this->engineReaction(2, null, $inputData['RESPMSG']);
        }

        return __engineReaction(2, null, __tr('Ooops Something went wrong on server.'));
    }

     /**
     * process iyzipay payment method
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processIyzipayPayment($inputData, $orderId)
    {    
        // Fetch order
        $order = $this->orderRepository->fetch($orderId);
       
        if (__isEmpty($order)) {
            return __engineReaction(2, null, __tr('Order Data Not Exist.'));
        }

         // If payment initialization status is not success then show error
        if ($inputData['status'] != 'success') {

            //remove shopping cart item if payment failed.
            $this->shoppingCartEngine->processRemoveAlItems();

            //if order failed then update order status
            $this->orderEngine->updatePaymentFailed($order['order_uid']);

            NativeSession::set('orderCancel', 'Payment Failed.');
          
            return __engineReaction(2, [
                'orderUid' => $order->order_uid,
            ], __tr('Invalid request.'));
        }

     
        // If payment initialization status is not success then show error
        if ($inputData['status'] == 'success') {
        
            $iyzipayRequest = $this->iyzipayService->processThreedsPayment($inputData);
         
            // Check if payment status is success
            if ($iyzipayRequest->getStatus() != 'success') {

                 //remove shopping cart item if payment failed.
                $this->shoppingCartEngine->processRemoveAlItems();

                NativeSession::set('orderCancel', 'Payment Failed.');
                //if order failed then update order status
                $this->orderEngine->updatePaymentFailed($order['order_uid']);

                return __engineReaction(2, null, $iyzipayRequest->getErrorMessage());
            }

            // Check if payment status is success
            if ($iyzipayRequest->getStatus() == 'success') {

                //remove shopping cart item if payment failed.
                $this->shoppingCartEngine->processRemoveAlItems();

                $paymentData = [
                    'type' => 1, // Deposit
                    'txn' => $iyzipayRequest->getPaymentId(),
                    'payment_method' =>  (configItem('env_settings.iyzipay_test_mode') == true) ? 15 : 14, // iyzico,
                    'currency_code' => $order['currency_code'],
                    'gross_amount' => $order['total_amount'],
                    'fee' => null,
                    'orders__id' => $order['_id'],
                    'raw_data' => $iyzipayRequest->getRawResult(),
                    'payment_status' => 2, // Completed
                ];

                if ($this->orderPaymentsRepository->storeNewPayment($paymentData)) {

                    // mark order and payment as completed
                    $this->orderRepository->updateOrderPaymentStatus($order, 2);

                    // notify concerned persons
                    $this->notifyPaymentConfirmation($order['order_uid']);

                    return __engineReaction(1, [
                        'orderUid' => $order['order_uid']
                    ], __tr('Payment Confirmed.'));
                }

                return __engineReaction(2, null, __tr('Payment Failed.'));
               
            }
        } 

        return __engineReaction(2, null, __tr('Ooops Something went wrong on server.'));
    }

    /**
     * Check that txn_id has not been previously processed.
     *
     * @param int $txnID - PayPal Txn ID
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function isTxnExists($txnID, $paymentMethod)
    {
        // check if this txn is already been processed
        return __isEmpty($this->orderPaymentsRepository->fetchByTxn($txnID, $paymentMethod)) ? false : true;
    }

    /**
     * Notify order & Payment Confirmation.
     *
     * @param mixed $orderDetails - Order
     * @param array $ipnInformation -
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function notifyPaymentConfirmation($orderDetails, $ipnInformation = [])
    {
        if (is_array($orderDetails) === true) {
            $orderUid = $orderDetails['order_uid'];
            $customerId = $orderDetails['users_id'];

            // Get order details from database for latest placed order
            $updatedOrder = $this->orderEngine->prepareOrderDataForSendMail($orderUid);

            // $messageData = [
            //     'orderData' => $updatedOrder,
            //     'oldPaymentStatus' => $orderDetails['payment_status'],
            //     'orderConfig' => config('__tech.address_type'),
            //     'orderDetailsUrl' => route('my_order.details', $orderUid),
            // ];

            // if order payment status updated from pending
            if ($orderDetails['payment_status'] == 4) {
                $updatedPaymentSubject = 'Payment Confirmed';

                return $this->notifyPaymentCompleted($updatedOrder);

                // notify customer
                // $this->mailService->notifyCustomer($updatedPaymentSubject, 'order.customer-order',
                //     $messageData, $customerId);
                // // notify store admin
                // return $this->mailService->notifyAdmin($updatedPaymentSubject, 'order.customer-order', $messageData);
            }
        }

        // Get order details from database for latest placed order
        // $getOrderDetailsForMail = $this->orderEngine->prepareOrderDataForSendMail($orderDetails);
        $order = $this->orderEngine->prepareOrderDataForSendMail($orderDetails);
       
        return $this->notifyPaymentCompleted($order);

        // $messageData = [
        //     'orderData' => $orderedData,
        //     'paymentDetails' => $paymentDetails,
        //     'orderConfig' => config('__tech.address_type'),
        //     'orderDetailsUrl' => route('my_order.details', $orderUID),
        // ];

        // notify customer
        // $this->mailService->notifyCustomer('Your Order has been Submitted & Payment Confirmed',
        //         'order.customer-order', $messageData, $getOrderDetailsForMail['userId']);
        // // notify store admin
        // return $this->mailService->notifyAdmin('New Order Received & Payment Confirmed', 'order.customer-order', $messageData);
    }

    /**
     * Notify Payment Completed.
     *
     * @param int $txnID - PayPal Txn ID
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function notifyPaymentCompleted($order)
    {   
        $currencyCode = $order['currencyCode'];

        $orderAddrHeader = 'Shipping Address';
        if($order['address']['sameAddress'] != false ) {
            $orderAddrHeader = 'Shipping/Billing Address';
        }
        $shippingAddressData = [
            '{__type__}'         => $order['address']['shippingAddress']['type'],
            '{__addressLine1__}' => $order['address']['shippingAddress']['addressLine1'],
            '{__addressLine2__}' => $order['address']['shippingAddress']['addressLine2'],
            '{__city__}'         => $order['address']['shippingAddress']['city'],
            '{__state__}'        => $order['address']['shippingAddress']['state'],
            '{__country__}'      => $order['address']['shippingAddress']['country'],
            '{__pinCode__}'      => $order['address']['shippingAddress']['pinCode'],
            '{__orderAdressHeader__}' => $orderAddrHeader,
            '{__fullName__}' => $order['fullName'],
        ];
        $shippingAddressView = view('dynamic-mail-templates.order.shipping-address')->render();
        $shippingAddressTemplate = strtr($shippingAddressView, $shippingAddressData);

        $billingAddressTemplate = '';
     
        if (!$order['address']['sameAddress']) {

            $billingAddressData = [
                '{__bill_type__}'           => $order['address']['billingAddress']['type'],
                '{__bill_addressLine1__}'   => $order['address']['billingAddress']['addressLine1'],
                '{__bill_addressLine2__}'   => $order['address']['billingAddress']['addressLine2'],
                '{__bill_city__}'           => $order['address']['billingAddress']['city'],
                '{__bill_state__}'          => $order['address']['billingAddress']['state'],
                '{__bill_country__}'        => $order['address']['billingAddress']['country'],
                '{__bill_pinCode__}'        => $order['address']['billingAddress']['pinCode'],
            ];

            $billingAddressView = view('dynamic-mail-templates.order.billing-address')->render();
            $billingAddressTemplate = strtr($billingAddressView, $billingAddressData);
        }
       
        $productTemplateView = view('dynamic-mail-templates.order.product-items')->render();
        $productsTemplate = '';
        foreach($order['orderProducts']['products'] as $items) {
            $options = '';
            $productDiscount = '';
            if(!empty($items['option'])) {
                foreach($items['option'] as $option) {
                    $options .= "<span><strong>".$option['optionName'].": </strong> ".$option['valueName']." (".$option['baseformatedOptionPrice'].") </span><br>";
                }
            }
           
            if(isset($items['productDiscount']) and (!__isEmpty($items['productDiscount']))) {
                if($items['productDiscount']['isDiscountExist']) {
                    $productDiscount = "(<span>".$items['productDiscount']['discount']." Off</span>)";
                }
            }

            $productInfo = [
                '{__detailsURL__}'          => $items['detailsURL'],
                '{__imagePath__}'           => $items['imagePath'],
                '{__productName__}'         => $items['baseProductName'],
                '{__productFormatPrice__}'  => $items['baseFormatePrice'],
                '{__options__}'             => $options,
                '{__productDiscount__}'     => $productDiscount ?? '',
                '{__productOldPrice__}'     => $items['baseFormattedOldProductPrice'],
                '{__quantity__}'            => $items['quantity'],
                '{__formatedProductPrice__}'    => $items['baseFormatedProductPrice'],
                '{__formatedTotal__}'           => $items['baseFormatedTotal'],
            ];

            $productsTemplate .= strtr($productTemplateView, $productInfo);
        }

        $shortFormatDiscount = basePriceFormat('0', $currencyCode);
        if (isset($order['cartDiscount']) and (!__isEmpty($order['cartDiscount']))){
            if($order['cartDiscount']['isOrderDiscountExist']) {
                $shortFormatDiscount = $order['cartDiscount']['baseShortFormatDiscount'];
            }
        }

        $orderedData['{__formatedOrderDiscount__}'] = 0;
        if($order['orderDiscount'] != 0) {
            $orderedData['{__formatedOrderDiscount__}'] = $order['formatedOrderDiscount'];
        }

        $taxAmountView = view('dynamic-mail-templates.order.tax-amount-template')->render();
        $taxView = view('dynamic-mail-templates.order.tax-template')->render();
        $taxTemplateAmount = '';
        $taxTemplate = '';
        $taxData = [];
        if(!__isEmpty($order['taxes'])) {
            foreach($order['taxes'] as $tax) {
                $taxData = [
                    '{__formatedTaxAmount__}' => $tax['baseFormatedTaxAmount'],
                    '{__taxLabel__}' => $tax['baseTaxLabel'] ?? 'N/A'
                ];
                $taxTemplateAmount .= strtr($taxAmountView, $taxData);
                $taxTemplate .= strtr($taxView, $taxData);
            }
        }


        if (!__isEmpty($order['shippingAmount'])) {
            $formatedShippingAmount = $order['baseFormatedShippingAmount'];
        } else {
            $formatedShippingAmount = 'Free';
        }

        $shippingTitle = '';
        $shippingTemplate = '';
        if ((isset($order['shipping'])) and (!__isEmpty($order['shipping']))) {
            foreach ($order['shipping'] as $key => $shipping) {
                if (!__isEmpty($shipping['shipping_method_title'])) {
                    $shippingTitle = 'Shipping Method : ';
                    $shippingTemplate = '(' .$shipping['base_shipping_method_title'] .')';
                }
            }
        }

        $orderedData = [
            '{__formatedShippingAmount__}'  =>  $formatedShippingAmount,
            '{__formatedSubtotal__}'        =>  $order['orderProducts']['baseFormatedSubtotal'],
            '{__formatedTotalOrderAmount__}' => $order['baseFormatedTotalOrderAmount'],
            '{__taxTemplateAmount__}' => $taxTemplateAmount,
            '{__orderPlacedOn__}' => $order['orderPlacedOn'],
            '{__productsTemplate__}' => $productsTemplate,
            '{__orderUID__}' => $order['orderUID'],
            '{__formatedOrderDiscount__}' => $order['baseFormatedOrderDiscount'],
            '{__formatedPaymentStatus__}' => $order['formatedPaymentStatus'],
            '{__formatedPaymentMethod__}' => $order['formatedPaymentMethod'],            
            '{__fullName__}' => $order['fullName'],      
            '{__orderProcessMailHeader__}' => 'Thank you for your order!',
            '{__orderProcessMailMessage__}' => "We'll notify you once we process your order. You can see order details below or by clicking button.",
            // 'orderConfig' => config('__tech.address_type'),
            '{__orderDetailsUrl__}' => route('my_order.details', $order['orderUID']),
            '{__shippingAddressTemplate__}' => $shippingAddressTemplate,
            '{__billingAddressTemplate__}' => $billingAddressTemplate,
            '{__taxTemplate__}' => $taxTemplate,
            '{__shortFormatDiscount__}' => $shortFormatDiscount,
            '{__shippingMethodTitle__}' => $shippingTitle,
            '{__shippingMethodTemplate__}' => $shippingTemplate
        ];

        if ($order['address']['sameAddress'] != false ) {
            $orderedData['{__orderAdressHeader__}'] = 'Shipping/Billing Address';
        } else {
            $orderedData['{__orderProcessMailHeader__}'] = 'Shipping/Billing Address';
        }

        if (isset($order['oldPaymentStatus']) and ($order['oldPaymentStatus'] == 4))
            $orderedData['{__orderProcessMailHeader__}'] = 'Payment Confirmed';
        else {
            $orderedData['{__orderProcessMailHeader__}'] = 'Thank you for your order!';
        }
           
        $emailSubjectCustomer = 'Your Order has been Submitted & Payment Confirmed';
        $emailSubjectAdmin = 'New Order Received & Payment Confirmed';

        //send dynamic mail to user
        sendDynamicMail('order_process_mail_to_user', $emailSubjectCustomer, $orderedData, $order['email']);

        $orderedData['{__orderProcessMailHeader__}'] = 'New Order Received';
        $orderedData['{__orderProcessMailMessage__}'] ='You have received new order. You can see order details below or by clicking button.';
        
        $emailSubject = 'New Order Received';

        return sendMailToAdmin('order_process_mail_to_admin', $emailSubjectAdmin, $orderedData, null, 1);
    }

    /**
     * Notify Payment Failure.
     *
     * @param int $txnID - PayPal Txn ID
     *
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function notifyPaymentFailure($requestResponse, $ipnData)
    {
        if ($requestResponse === 'ERR_IPN_FAILD'
                or $requestResponse === 'ERR_IPN_INVALID'
                or $requestResponse === 'ERR_IPN_NOTHING') {
            return false;
        }

        if ($requestResponse === 'ERR_IPN_ORDER_NOT_FOUND') {
            return false;
        }

        if (in_array('ERR_IPN_TXN_EXIST', $requestResponse)) {
            return false;
        }

        $orderUid = $ipnData['invoice'];

        // Get order details from database for latest placed order
        $orderDetails = $this->orderEngine->prepareOrderDataForSendMail($orderUid);
        $customerId = $orderDetails['userId'];

        $messageData = [
            'orderData' => $orderDetails,
            'orderConfig' => config('__tech.address_type'),
            'orderDetailsUrl' => route('my_order.details', $orderUid),
            'ipnData' => $ipnData,
            'requestResponse' => $requestResponse,
        ];

        // notify customer
        $this->mailService->notifyCustomer('Order Submitted but PayPal Payment not Completed', 'order.paypal-ipn-payment-failed',
            $messageData, $customerId);
        // notify admin
        return $this->mailService->notifyAdmin('New Order Received but PayPal Payment not Completed',
                    'order.paypal-ipn-payment-failed', $messageData);
    }

    /**
     * Prepare order payment detail dialog data.
     *
     * @param int $orderID
     *-----------------------------------------------------------------------*/
    public function preparePaymentDetailsDialog($orderPaymentID)
    {
        $orderPaymentDetails = $this->orderPaymentsRepository
                                    ->fetchDetails($orderPaymentID);

        // Check if order payment detail is exist
        if (__isEmpty($orderPaymentDetails)) {
            return __engineReaction(18);
        }

        // Get payment method from config
        $paymentMethod = config('__tech.orders.payment_methods');

        $paymentData = [];

        $paymentData = [
            'currencyCode' => $orderPaymentDetails['paymentDetails']['currency_code'],
            'fee' => orderPriceFormat($orderPaymentDetails['paymentDetails']
                                    ['fee'],
                                    $orderPaymentDetails['paymentDetails']['currency_code']),
            'grossAmount' => orderPriceFormat($orderPaymentDetails['paymentDetails']
                                    ['gross_amount'],
                                    $orderPaymentDetails['paymentDetails']['currency_code']),
            'paymentMethod' => $paymentMethod[$orderPaymentDetails['paymentDetails']
                                    ['payment_method']],
            'txn' => $orderPaymentDetails['paymentDetails']['txn'],
            'type' => $orderPaymentDetails['paymentDetails']['type'],
            'formatedPaymentOn' => formatStoreDateTime($orderPaymentDetails['paymentDetails']
                                    ['created_at']),
            'rawData' => $orderPaymentDetails['rawData'],
        ];

        return __engineReaction(1, [
            'orderPaymentDetails' => $paymentData,
            ]);
    }

    /** Prepare order payment update detail dialog data
     * @param int $orderID
     *-----------------------------------------------------------------------*/
    public function prepareOrderPaymentUpdateDialog($orderID)
    {
        // Get order details
        $orderDetails = $this->orderPaymentsRepository->fetchOrderDetails($orderID);

        // Get payment method from config
        $config = config('__tech');
        $orderStatus = $config['orders']['status_codes'];
        $paymentMethod = $config['payment_methods_list'];

        // prepare order data
        $orderData = [
            'totalAmount' => $orderDetails['total_amount'],
            'orderID' => $orderDetails['_id'],
            'currencyCode' => $orderDetails['currency_code'],
            'paymentStatus' => $orderDetails['payment_status'],
            'orderStatus' => $orderStatus[$orderDetails['status']],
        ];

        $acceptPaymentMethod = [];
        //payment valid key
        $acceptPaymentKey = array(1, 2, 3, 4, 5, 6, 11, 14, 16, 18, 20);

        //collect valid keys in array
        foreach ($paymentMethod as $key => $method) {
            if (in_array($method['id'], $acceptPaymentKey)) {
                $acceptPaymentMethod[] = [
                    'id'    => $method['id'],
                    'name'  => $method['name'],
                ];
            }
        }

        return __engineReaction(1, [
            'orderDetails' => $orderData,
            'paymentMethod' => $acceptPaymentMethod,
        ]);
    }

    /** Process order payment update
     * @param int   $orderID
     * @param array $inputs
     *-----------------------------------------------------------------------*/
    public function processUpdateOrderPayment($inputs)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($inputs) {

            // update order status
            $response = $this->orderPaymentsRepository
                             ->updateOrder(2, $inputs['orderID']);

            $inputs['orderPaymentFee'] = null;

            // Check if order payment fee exist
            if (isset($inputs['fee']) and !__isEmpty($inputs['fee'])) {
                $inputs['orderPaymentFee'] = $inputs['fee'];
            }

            // Check if order payment store in DB
            if ($this->orderPaymentsRepository->storeOrderPayment($inputs)) {
                return $this->orderPaymentsRepository->transactionResponse(1, null, __tr('Order payment stored successfully.'));
            }

            return $this->orderPaymentsRepository->transactionResponse(2, null, __tr('Order payment not stored.'));
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare order refund dialog data.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderRefundDialog($orderID)
    {
        $orderPaymentDetails = $this->orderPaymentsRepository
                                   ->fetchOrderPayments($orderID);

        // Check if order payment detail is exist
        if (__isEmpty($orderPaymentDetails)) {
            return __engineReaction(18);
        }

        // get list of payment list
        $paymentMethodList = config('__tech.payment_methods_list');

        // prepare array for refund dialog data
        $orderPaymentData = [
            'orderID' => $orderPaymentDetails['orders__id'],
            'txn' => $orderPaymentDetails['txn'],
            'currencyCode' => $orderPaymentDetails['currency_code'],
            'grossAmount' => orderPriceFormat($orderPaymentDetails['gross_amount'],
                                $orderPaymentDetails['currency_code']),
            'fee' => orderPriceFormat($orderPaymentDetails['fee'],
                                $orderPaymentDetails['currency_code']),
            'paymentOn' => formatStoreDateTime($orderPaymentDetails['created_at']),
        ];

        return __engineReaction(1, [
                'orderPaymentDetails' => $orderPaymentData,
                'paymentMethodList' => $paymentMethodList,
        ]);
    }

    /**
     * Process order payment refund.
     *
     * @param array $orderID
     * @param array $inputs
     *
     * @return reaction code
     *---------------------------------------------------------------- */
    public function processRefundOrderPayment($inputs, $orderID)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($inputs, $orderID) {

            // Get order payment details
            $paymentDetails = $this->orderPaymentsRepository
                                   ->fetchOrderPayments($orderID);

            // update order status
            // 5 (Refunded) payment Status
            $response = $this->orderPaymentsRepository
                             ->updateOrder(5, $orderID);

			$inputData = [
                'txn' => $paymentDetails['txn'],
                'paymentMethod' => $inputs['paymentMethod'],
                'currencyCode' => $paymentDetails['currency_code'],
                'grossAmount' => $paymentDetails['gross_amount'],
                'orderPaymentFee' => $paymentDetails['fee'],
                'orderID' => $paymentDetails['orders__id'],
                'comment' => __ifIsset($inputs['description'], $inputs['description'], ''),
            ];

            //for razorpay only
            // if ($inputs['paymentMethod'] == 11 || $inputs['paymentMethod'] == 12) {

            // 	$refundRequest = $this->razorpayService->refundPayment($paymentDetails['txn']);

            // 	if ($refundRequest) {
	           //  	$inputData['refundData'] = $refundRequest;
            // 	}
            // }

            // update order payment details
            $responseData = $this->orderPaymentsRepository
                                 ->updateOrderRefundPayment($inputData);

            // if order payment refunded successfully
            if ($responseData) {

                // Check if notify customer check box true or false
                if (__ifIsset($inputs['checkMail'])) {

                    // Check description exist
                    $additionalNotes = '';
                    if (!empty($inputs['description'])) {
                        $additionalNotes = $inputs['description'];
                    }

                    $order = $this->orderPaymentsRepository
                                   ->fetchUserID($orderID);

                    // order UID array for markup text
                    $orderUID = [
                        '__orderUID__' => $inputs['orderUID'],
                    ];

                    // make a subject text for refund order mail
                    $subjectText = __tr('Payment Refund Process for __orderUID__ order.');

                    // get a markup string
                    $subject = getTextMarkup($subjectText, $orderUID);

                    // description message for refund mail
                    $discriptionMarkup = __tr('Payment refund has been process for __orderUID__ order');

                    $messageData = [
                        '{__description__}' 	=> getTextMarkup($discriptionMarkup, $orderUID),
                        '{__additionalNotes__}' => $additionalNotes,
                        '{__orderUid__}'		=> $orderUID
                    ];

                    // Notify customer about refund by email
                    // $this->mailService->notifyCustomer($subject, 'order.order-refund', $messageData, $order['users_id']);

                    $emailTemplateData = configItem('email_template_view', 'order_refund_mail');
                   
                    sendDynamicMail('order_refund_mail', $emailTemplateData['emailSubject'], $messageData, $order->user->email);
                }

                return $this->orderPaymentsRepository->transactionResponse(1, null, __tr('Order Refund Successfully.'));
            }

            return $this->orderPaymentsRepository->transactionResponse(14, null, __tr('Nothing Update'));
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare order payment list.
     *---------------------------------------------------------------- */
    public function preparePaymentOrderList($startDate, $endDate)
    {
        return $this->orderPaymentsRepository
                    ->fetchOrderPaymentList($startDate, $endDate);
    }

    /**
     * Process for Excel sheet download.
     *
     * @param $startDate
     * @param $endDate
     *---------------------------------------------------------------- */
    public function processExcelSheetDownload($startDate, $endDate)
    {
        $paymentDetails = $this->orderPaymentsRepository
                               ->fetchOrderPaymentDetails($startDate, $endDate);

        // Check if order payment details exist
        if (__isEmpty($paymentDetails)) {
            App:abort(404);
        }

        $paymentData = [];
        $totalOrderAmount = [];

        // get payment details and prepare array
        foreach ($paymentDetails as $key => $paymentDetail) {
            $totalOrderAmount[$key] = $paymentDetail['gross_amount'];

            $paymentData[] = [
                'orderUID' => $paymentDetail['order']['order_uid'],
                'txn' => $paymentDetail['txn'],
                'fee' => is_null($paymentDetail['fee']) ? '0' : (string)$paymentDetail['fee'],
                'paymentMethod' => getTitle($paymentDetail['payment_method'],
                                    '__tech.orders.payment_methods'),
                'totalAmt' => (string)$paymentDetail['order']['total_amount'],
                'currencyCode' => $paymentDetail['currency_code'],
            ];
        }

        // Excel title, date and total amount data
        $excelData = [
            'excelFileName' => 'Payments-'.''.$startDate.'-'.$endDate,
            'title' => 'Payment Details'.' '.formattedDateTime(currentDateTime()),
            'startEndDate' => 'From'.' '.$startDate.' to '.$endDate,
            'total' => array_sum($totalOrderAmount),
        ];

        //create temp path for store excel file
        $temp_file = tempnam(sys_get_temp_dir(), 'OrderPaymentdetails.xlsx');
        $writer = new XLSXWriter();

        $sheet1 = 'Order Payment Details';

        //set header column string
        $header = array("string","string","string","string","string","string");

        // topHeader for header web site name row set css styles
        $topHeader = array('halign'=>'center','valign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>26);

        // Style 1 for header title set css styles
        $styles1 = array('halign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>20);

        // Style 2 for Column title set css styles
        $styles2 = array('halign'=>'left','font-style'=>'bold','font-size'=>10, 'height'=>15, 'border'=>'left,right,top,bottom', 'border-style'=>'thin');

        // Style 3 for Generated todays date header set css styles
        $styles3 = array('halign'=>'right','font-style'=>'bold','font-size'=>13);

        //Style 4 for Total Order Payment Record
        $styles4 = array(
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Id
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Transaction Id
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Fee
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Payment Method
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Total Amount
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'],// Currency Code
                        'height'=> 17,
                    ); 

        // Style 5 for Column title set css styles
        $styles5 = array('halign'=>'right', 'valign'=>'center', 'font-style'=>'bold','font-size'=>13, 'height'=>20);

        //Main Column Header
        $writer->writeSheetHeader($sheet1, $header, 
            $col_options = ['suppress_row'=>true, 
                            'widths'=>[
                                20, //Id width  set
                                44, //Transaction Id width  set
                                10, //Fee width set
                                25, //Payment Method width  set
                                18, //Total Amount width set
                                15] // Currency Code width set
                            ] 
                        );
        
        $storeName = __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') );
        
        //Website name Row
        $writer->writeSheetRow($sheet1,  [$storeName], $topHeader );

        //Title Row
        $writer->writeSheetRow($sheet1,  [$excelData['title']], $styles1 );

        //Generated Todays date Row
        $writer->writeSheetRow($sheet1,  [$excelData['startEndDate']], $styles1 );

        //Column Title row
        $writer->writeSheetRow($sheet1,  ['OrderUID', 'Transaction ID', 'Fee', 'Payment Method', 'Total Amount', 'Currency Code'], $styles2);

        //create row data
        $rows = $paymentData;
        $dynamicDataCount = count($rows);

        //Total Order Payment Record Data
        foreach($rows as $key => $row) {
            //Create sheet fetch data row dynamically   
            $writer->writeSheetRow($sheet1, $row, $styles4);
        }

        //Generated Todays date Row
        $writer->writeSheetRow($sheet1,  ['Total '.' '.$excelData['total']], $styles5 );
 
        //Merge two cells for set title & generated date in center
        $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=0, $end_col=5, ['halign'=>'right']);
        $writer->markMergedCell($sheet1, $start_row=1, $start_col=0, $end_row=1, $end_col=5);
        $writer->markMergedCell($sheet1, $start_row=2, $start_col=0, $end_row=2, $end_col=5);
        $writer->markMergedCell($sheet1, $start_row=$dynamicDataCount+4, $start_col=0, $end_row=$dynamicDataCount+4, $end_col=4);

        //to prinf in excel file function
        $writer->writeToFile($temp_file);

        return response()->download($temp_file, 'OrderPaymentdetails.xlsx');
    }

    //Section for download Excel files.
    public function downloadFile($file, $name= 'file-download')
    {
        if (is_file($file)) { // File to download.
            
            $type = 'application/octet-stream';
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: attachment; filename="'.$name.'";');
            header('Content-Type: ' . $type);
            header('Content-Length: ' . filesize($file));

            // size of chunks (in bytes).
            $chunkSize = 1024 * 1024;

            // Open a file in read mode.
            $handle = fopen($file, 'rb');

            // Run this until we have read the whole file.
            // feof (eof means "end of file") returns `true` when the handler
            // has reached the end of file.
            while (!feof($handle)) {
                $buffer = fread($handle, $chunkSize);
                echo $buffer;
                ob_flush();  // Flush the output buffer to free memory.
                flush();
            }

            $status = fclose($handle);

            return $status; // Exit to make sure Not to output anything else.
        }
    }

    /**
      * Process Delete order payment
      *
      * @param array $inputData
      * @param int $paymentId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function processDeleteOrderPayment($inputData, $paymentId)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($inputData, $paymentId) {
                                  $payment = $this->orderPaymentsRepository->fetchById($paymentId);
           
                                  if (__isEmpty($payment)) {
                                      return $this->orderPaymentsRepository
                            ->transactionResponse(18, null, __tr('Order payment not found.'));
                                  }

                                  if (!$this->orderPaymentsRepository->deleteOrderPayment($inputData, $payment)) {
                                      return $this->orderPaymentsRepository
                            ->transactionResponse(2, null, __tr('Please enter correct password.'));
                                  }

            // delete related orders
            $this->manageOrderRepository->delete($payment->orders__id);
            
                                  return $this->orderPaymentsRepository
                        ->transactionResponse(1, null, __tr('Order payment deleted Successfully.'));
                              });

        return  __engineReaction($reactionCode);
    }

    /**
      * Process Delete Sandbox orders
      *
      * @param int $orderedId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function processDeleteSandbox($paymentId)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($paymentId) {
                                  $payment = $this->orderPaymentsRepository->fetchById($paymentId);
           
            if (__isEmpty($payment)) {
                return $this->orderPaymentsRepository->transactionResponse(18, null, __tr('Sandbox payment not found.'));
            }

            $paymentMethod = [7,8];

            // check the request for delete order is sandbox order or not
            if (in_array($payment->payment_method, $paymentMethod) !== true) {
                return $this->orderPaymentsRepository
                        ->transactionResponse(18, null, __tr('This is not a sandbox payment.'));
            }

            if ($this->orderPaymentsRepository->deleteSandboxPayment($payment)) {

                // delete related orders
                $this->manageOrderRepository->delete($payment->orders__id);
            
                return $this->orderPaymentsRepository
                    ->transactionResponse(1, null, __tr('Sandbox payment deleted Successfully.'));
            }

            return $this->orderPaymentsRepository
                ->transactionResponse(2, null, __tr('Sandbox payment not deleted.'));
        });

        return  __engineReaction($reactionCode);
    }
}
