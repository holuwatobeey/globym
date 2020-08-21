<?php
/*
* ManageOrderEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\ManageOrderEngineBlueprint;
use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\User\AddressEngine;
use Auth;

class ManageOrderEngine implements ManageOrderEngineBlueprint
{
    /**
     * @var ManageOrderRepository - ManageOrder Repository
     */
    protected $manageOrderRepository;

    /**
     * @var AddressRepository - Address Repository
     */
    protected $addressRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var CouponRepository
     */
    protected $couponRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var AddressEngine - Address Repository
     */
    protected $addressEngine;

    /**
     * @var OrderEngine
     */
    protected $orderEngine;

    /**
     * Constructor.
     *
     * @param ManageOrderRepository $manageOrderRepository - ManageOrder Repository
     * @param AddressEngine         $addressEngine         - Address Engine
     *-----------------------------------------------------------------------*/
    public function __construct(
                ManageOrderRepository $manageOrderRepository,
                AddressRepository $addressRepository,
                MailService $mailService,
                UserRepository $userRepository,
                ShippingRepository $shippingRepository,
                TaxRepository $taxRepository,
                CouponRepository $couponRepository,
                SupportRepository $supportRepository,
                AddressEngine $addressEngine,
                OrderEngine  $orderEngine
            ) {
        $this->manageOrderRepository = $manageOrderRepository;
        $this->addressRepository = $addressRepository;
        $this->mailService = $mailService;
        $this->userRepository = $userRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->couponRepository = $couponRepository;
        $this->supportRepository = $supportRepository;
        $this->addressEngine = $addressEngine;
        $this->orderEngine = $orderEngine;
    }

    /**
     * Prepare order list.
     *---------------------------------------------------------------- */
    public function prepareOrderList($status, $userID)
    {
        return $this->manageOrderRepository
                        ->fetchOrdersForList($status, $userID);
    }

    /**
     * Get user details.
     *
     * @param number $userID
     *---------------------------------------------------------------- */
    public function getUserDetails($userID)
    {
        return $this->userRepository
                    ->fetchUserFullName($userID);
    }

    /**
     * get order data.
     *
     * @param int $orderID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function prepareOrderData($orderID)
    {
        $order = $this->manageOrderRepository->fetchById($orderID);

        //check order exist
        if (__isEmpty($order)) {
            return __engineReaction(18);
        }

        $statusCode = [];

        // Get order status from config
        $configOrder = config('__tech.orders');
        $orderStatus = $configOrder['status_codes'];
        $orderPaymentStatus = $configOrder['payment_status'];

        // create new array list of status and
        // neglect 1 (NEW) and current status
        foreach ($orderStatus as $key => $status) {
            if ($key != 1 and $key != $order->status) {
                $statusCode [] = [
                    'id' => $key,
                    'name' => $status,
                ];
            }
        }

        // prepare order data array
        $orderData = [
            '_id' => $order->_id,
            'orderUID' => $order->order_uid,
            'name' => $order->name,
            'status' => $order->status,
            'statusName' => $orderStatus[$order->status],
            'statusCode' => $statusCode,
            'paymentStatus' => $order->payment_status,
            'currentPaymentStatus' => $orderPaymentStatus[$order->payment_status],
        ];

        return __engineReaction(1, ['order' => $orderData]);
    }

    /**
     * prepare for update order.
     *
     * @param int   $orderID
     * @param array $input
     *
     * @return response
     *---------------------------------------------------------------- */
    public function processUpdateOrder($orderID, $input)
    {
        // Get order detail
        $order = $this->getOrderDetailsForMail($orderID);

        // Check if order exist
        if (empty($order)) {
            return __engineReaction(18);
        }

        // Get old status from order for maintain order Log
        $input['oldStatus'] = $order['orderStatus'];

        // Check if user select order status complete and payment status is not
        // completed then show message
        // 6 => Completed (Order Status)
        // 2 => Completed (Payment Status)
        if ($input['status'] == 6 and $order['paymentStatus'] != 2) {
            return __engineReaction(2);
        }

        $response = $this->manageOrderRepository
                         ->updateOrder($orderID, $input);

        // if order updated successfully
        if ($response) {

            // Check if check mail exist and neglect
            // 1 (New),
            // 8 (Cancellation Request Received) and
            // 9 (User Cancelled) status code
            if (!empty($input['checkMail'])) {
                if (($input['status'] != 1)
                or ($input['status'] != 8)
                or ($input['status'] != 9)) {

                    // Check description exist
                    $description = '';
                    if (!empty($input['description'])) {
                        $description = $input['description'];
                    }

                    // Get Order UID
                    $orderUID = $order['orderUID'];

                    // Get email subject
                    $mailMessages = $this->createMailSubjectAndMessage(
                                                            $orderUID,
                                                            $input['status']
                                                            );
                    // get order description message
                    $order['descriptionMessage'] = $mailMessages['descriptionMessage'];

                    // email view
                    // $mailView = 'order.order-complete';

                    //    // Prepare mail data array for sending email
                    // $mailData = [
                    //     'name' => $order['fullName'],
                    //     'orderData' => $order,
                    //     'description' => $description,
                    //      'orderDetailsUrl' => route('my_order.details', $orderUID),
                    // ];

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
                 
                    if ($order['address']['sameAddress']) {

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
	                	if(!empty($items['option'])) {
	                		foreach($items['option'] as $option) {
	                			$options .= "<span><strong>".$option['optionName']." : ".$option['valueName']."<br></span>";
	                		}
	                	}
	                	if(isset($items['productDiscount']) and (!__isEmpty($items['productDiscount']))) {
	                		if($items['productDiscount']['isDiscountExist']) {
	                			$productDiscount = "<strike>".$items['formattedOldProductPrice']."</strike>(<span>".$items['productDiscount']['discount']."</span>)";
	                		}
	                	}

	                	$productInfo = [
	                		'{__detailsURL__}' => $items['detailsURL'],
							'{__imagePath__}' => $items['imagePath'],
							'{__productName__}' => $items['productName'],
							'{__options__}' => $options,
							'{__productDiscount__}' => $productDiscount ?? 0,
							'{__quantity__}' => $items['quantity'],
							'{__formatedProductPrice__}' => $items['formatedProductPrice'],
							'{__formatedTotal__}' => $items['formatedTotal'],
	                	];

						$productsTemplate .= strtr($productTemplateView, $productInfo);
	                }

	                $shortFormatDiscount = '0';
	            	if (isset($order['cartDiscount']) and (!__isEmpty($order['cartDiscount']))){
	            		if($order['cartDiscount']['isOrderDiscountExist']) {
							$shortFormatDiscount = $order['cartDiscount']['shortFormatDiscount'];
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
	                    		'{__formatedTaxAmount__}' => $tax['formatedTaxAmount'],
	                    		'{__taxLabel__}' => $tax['label'] ?? 'N/A'
	                    	];
							$taxTemplateAmount .= strtr($taxAmountView, $taxData);
							$taxTemplate .= strtr($taxView, $taxData);
	                    }
	            	}

	            	if (!__isEmpty($order['shippingAmount'])) {
	            		$formatedShippingAmount = priceFormat($order['formatedShippingAmount'], $order['currencyCode']);
	            	} else {
	            		$formatedShippingAmount = 'Free';
	            	}

	                $orderedData = [
	                	'{__formatedShippingAmount__}' 	=> 	$formatedShippingAmount,
	                	'{__formatedSubtotal__}'		=>	$order['orderProducts']['formatedSubtotal'],
	                	'{__formatedTotalOrderAmount__}' => $order['formatedTotalOrderAmount'],
	       				'{__taxTemplateAmount__}' => $taxTemplateAmount,
	                    '{__orderPlacedOn__}' => $order['orderPlacedOn'],
	                    '{__productsTemplate__}' => $productsTemplate,
			            '{__orderUID__}' => $order['orderUID'],
	      		        '{__formatedOrderDiscount__}' => $order['formatedOrderDiscount'],
			            '{__formatedPaymentStatus__}' => $order['formatedPaymentStatus'],
			            '{__formatedPaymentMethod__}' => $order['formatedPaymentMethod'],		     
	 		            '{__fullName__}' => $order['fullName'],		 
			            '{__orderProcessMailHeader__}' => 'Thank you for your order!',
	                	'{__orderProcessMailMessage__}' => "We'll notify you once we process your order. You can see order details below or by clicking button.",
	                    // 'orderConfig' => config('__tech.address_type'),
	                    '{__orderDetailsUrl__}' => route('my_order.details', $orderUID),
	                    '{__taxTemplate__}' => $taxTemplate,
	                    '{__shortFormatDiscount__}' => $shortFormatDiscount,
	                    '{__orderDetailsUrl__}' => route('my_order.details', $orderUID),
                        '{__description__}' => $description,
                        '{__descriptionMessage__}' => $order['descriptionMessage'],
                        '{__shippingAddressTemplate__}' => $shippingAddressTemplate,
                        '{__billingAddressTemplate__}' => $billingAddressTemplate,
	                ];
 
                    // send notification mail to user and admin
                    $emailTemplateCustomer = configItem('email_template_view', 'order_complete_mail');
                    sendDynamicMail('order_complete_mail', $mailMessages['orderSubjectMessage'], $orderedData, $order['email']);
                }


            }

            // store order log data
            $orderLogData = $this->orderLogData($orderID, $input);

            orderLog($orderLogData);

            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Create subject and message for email.
     *
     * @param array $orderID
     * @param array $status
     *
     * @return object
     *---------------------------------------------------------------- */
    protected function createMailSubjectAndMessage($orderUID, $status)
    {
        // Get order status from config
        $orderStatus = config('__tech.orders.status_codes');

        $messageData = [
                '__orderId__' => $orderUID,
                '__orderStatus__' => $orderStatus[$status],
            ];

            // Get order subject
            $orderSubjectMessage = __tr('Your order __orderId__ has been __orderStatus__.', $messageData);

            // Get order description message for user
            $descriptionMessage = __tr('Your order __orderId__ status has been changed to  <strong> __orderStatus__. </strong>', $messageData);

        return [
                'orderSubjectMessage' => $orderSubjectMessage,
                'descriptionMessage' => $descriptionMessage,
            ];
    }

    /**
     * Send mail to user and admin with message.
     *
     * @param string $message
     * @param string $view
     * @param array  $messageData
     *
     * @return object
     *---------------------------------------------------------------- */
    protected function notifyByMail($message, $view, $messageData, $userID)
    {
        // sent mail to user
        $this->mailService->notifyCustomer($message, $view, $messageData, $userID);
    }

    /**
     * prepare Cart Orders dialog.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderDetailsDialogData($orderID)
    {
        $orderDetails = $this->orderEngine
                              ->prepareForMyOrderDetails((int) $orderID);

        // Check if order is exist
        if (__isEmpty($orderDetails)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, [
            'orderDetails' => $orderDetails,
            ]);
    }

    /**
     * prepare Cart Orders dialog.
     *
     * @param $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrdersLogDialogData($orderID)
    {
        $cartOrder = $this->manageOrderRepository->fetchById($orderID);

        // Get order log data
        $orderLog = getOrderLogFormattedData($orderID);

        //check order exist
        if (__isEmpty($orderLog)) {
            return __engineReaction(18);
        }

        $orders = [];

        // Check if cart order exist
        // prepare orders array for order log.
        if (!__isEmpty($cartOrder)) {
            $orders = [
                '_id' => $cartOrder->order_uid,
                'created_at' => formatDateTime($cartOrder->created_at),
            ];
        }

        return __engineReaction(1, [
                'cartOrder' => $orders,
                'orderLog' => $orderLog,
            ]);
    }

    /**
     * prepare for order log data.
     *
     * @param int   $orderID
     * @param array $input
     *
     * @return response
     *---------------------------------------------------------------- */
    protected function orderLogData($orderID, $input = [])
    {
        // default status is New
        $newStatus = 1;

        // Check if order status exist
        if (isset($input['status']) and !__isEmpty($input['status'])) {
            $newStatus = $input['status'];
        }

        // Assign new and old status string
        $newStatusString = getTitle($newStatus, '__tech.orders.status_codes');
        $oldStatusString = $input['oldStatus'];

        return [
               'orders__id' => $orderID,
               'description' => "Order status updated from $oldStatusString to $newStatusString",
               'role' => Auth::user()->fname.' '.Auth::user()->lname,
           ];
    }

    /**
     * Get order Details for email when status changed.
     *
     * @param $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    protected function getOrderDetailsForMail($orderID)
    {
        return $this->orderEngine
                    ->prepareOrderDataForSendMail((int) $orderID);
    }

    /**
     * Prepare data for email.
     *
     * @param $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareOrdersUserData($orderID)
    {
        $orderDetail = $this->manageOrderRepository
                            ->fetchUserDetailByOrderIDs($orderID);

        // Check if order details exist
        if (__isEmpty($orderDetail)) {
            __engineReaction(18);
        }

        // prepare data for mail
        $mailData = [
            'id' => $orderDetail['_id'],
            'orderUID' => $orderDetail['order_uid'],
            'email' => maskEmailId($orderDetail['user']['email']),
            'fullname' => $orderDetail['name'],
        ];

        return __engineReaction(1, $mailData);
    }

    /**
     * Process Send mail to user.
     *
     * @param array $input
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processContactUser($input)
    {
        // get email ID and order UID
        $email = $input['email'];
        $orderUID = $input['orderUID'];

        $orderDetail = $this->manageOrderRepository
                            ->fetchUserDetailByOrderIDs($input['id']);

        // Check if order is exist
        if (__isEmpty($orderDetail)) {
            return __engineReaction(18);
        }

        if ($email != $orderDetail['user']['email']
            and $orderUID != $orderDetail['order_uid']) {
            return __engineReaction(2);
        }

        // $fullName = 'Hi '.$input['fullName'].',';
        $subject = $input['subject'].' '.$orderUID;

        $mailData = [
            '{__emailMessage__}' 	=> 	$input['message'],
            '{__fullName__}' 		=>	$input['fullName'],
            '{__orderUID__}' 		=> 	$orderUID,
        ];

        // send notification mail to user
        // $this->notifyByMail($subject, 'order.customer-email', $mailData,  $email );

        $emailTemplateData = configItem('email_template_view', 'contact_to_user');
        // if reminder mail has been sent	
        if (sendDynamicMail('contact_to_user', $subject, $mailData, $email) ) {
			return __engineReaction(1); // success reaction
		}

        return __engineReaction(1);
    }


    /**
      * Process Delete orders
      *
      * @param array $inputData
      * @param int $orderId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function processDeleteOrders($inputData, $orderId)
    {
        $order = $this->manageOrderRepository->fetchById($orderId);

        if (__isEmpty($order)) {
            return __engineReaction(18, null, __tr('Order not exist.'));
        }

        // Check if order deleted
        if (!$this->manageOrderRepository->deleteOrder($inputData, $order)) {
            return __engineReaction(2, null, __tr('Please enter correct password.'));
        }

        return __engineReaction(1, null, __tr('Order deleted Successfully.'));
    }

    /**
      * Process Delete Sandbox orders
      *
      * @param int $orderId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function processDeleteSandboxOrder($orderId)
    {
        $order = $this->manageOrderRepository->fetchById($orderId);

        if (__isEmpty($order)) {
            return __engineReaction(18, null, __tr('Order not exist.'));
        }

        $paymentMethod = [7, 8, 10];

        // check the request for delete order is sandbox order or not
        if (in_array($order->payment_method, $paymentMethod) !== true) {
            return __engineReaction(18, null, __tr('This is not a sandbox order.'));
        }

        if ($this->manageOrderRepository->deleteSandboxOrder($order)) {
            return __engineReaction(1, null, __tr('Sandbox order deleted Successfully.'));
        }

        return __engineReaction(2, null, __tr('Sandbox order not deleted.'));
    }
}
