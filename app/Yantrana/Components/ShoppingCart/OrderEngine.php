<?php
/*
* OrderEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

// services
use App\Yantrana\Support\MailService;

// supported Repository to order
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderEngineBlueprint;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderPaymentsRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;

// test
use App\Yantrana\Components\Coupon\Models\Coupon as CouponModel;

// supported engine to order
use App\Yantrana\Components\User\AddressEngine;
use App\Yantrana\Components\Coupon\CouponEngine;
use App\Yantrana\Components\Shipping\ShippingEngine;
use App\Yantrana\Components\ShoppingCart\StripePaymentEngine;
use App\Yantrana\Components\ShoppingCart\InstamojoPaymentEngine;
use App\Yantrana\Components\Tax\TaxEngine;
use App\Yantrana\Components\Support\IyzipayService;
use App\Yantrana\Components\Support\PaytmService;
use Auth;
use ShoppingCart; // custom shopping cart
use NativeSession; // custom Session
use PDF;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Breadcrumb;

class OrderEngine implements OrderEngineBlueprint
{
    /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * @var AddressEngine - Address Repository
     */
    protected $addressEngine;

    /**
     * @var CouponEngine - Coupon Engine
     */
    protected $couponEngine;

    /**
     * @var ShippingEngine - Shipping Engine
     */
    protected $shippingEngine;

    /**
     * @var TaxEngine - Tax Engine
     */
    protected $taxEngine;

    /**
     * @var TaxRepository - Tax Repository
     */
    protected $taxRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderPaymentsRepository
     */
    protected $orderPaymentsRepository;

    /**
     * @var StripePaymentEngine - StripePaymentEngine Engine
     */
    protected $stripePaymentEngine;

    /**
     * @var instamojoPaymentEngine - InstamojoPaymentEngine Engine
     */
    protected $instamojoPaymentEngine;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var PaytmService - Paytm Service
     */
    protected $paytmService;
	
    /**
     * Constructor.
     *
     * @param ShoppingCartEngine $shoppingCartEngine - ShoppingCart Engine
     * @param OrderRepository    $orderRepository    - Order Repository
     * @param AddressEngine      $addressEngine      - Address Engine
     * @param CouponEngine       $CouponEngine       - Coupon Engine
     * @param ShippingEngine     $shippingEngine     - Shipping Engine
     * @param TaxEngine          $taxEngine          - Tax Engine
     *-----------------------------------------------------------------------*/
    public function __construct(
        OrderRepository $orderRepository,
        CouponRepository $couponRepository,
        ShippingRepository $shippingRepository, 
        TaxRepository $taxRepository,
        ShoppingCartEngine $shoppingCartEngine,
        AddressEngine $addressEngine,
        AddressRepository $addressRepository, 
        CouponEngine $couponEngine,
        ShippingEngine $shippingEngine, 
        TaxEngine $taxEngine,
        MailService $mailService, 
        OrderPaymentsRepository $orderPaymentsRepository,
        StripePaymentEngine $stripePaymentEngine,
        InstamojoPaymentEngine $instamojoPaymentEngine,
        SupportRepository $supportRepository,
        UserRepository $userRepository, 
        PaytmService $paytmService
    )
    {
        $this->orderRepository          = $orderRepository;
        $this->couponRepository         = $couponRepository;
        $this->shippingRepository       = $shippingRepository;
        $this->taxRepository            = $taxRepository;
        $this->shoppingCartEngine       = $shoppingCartEngine;
        $this->addressEngine            = $addressEngine;
        $this->addressRepository        = $addressRepository;
        $this->couponEngine             = $couponEngine;
        $this->shippingEngine           = $shippingEngine;
        $this->taxEngine                = $taxEngine;
        $this->mailService              = $mailService;
        $this->orderPaymentsRepository  = $orderPaymentsRepository;
        $this->stripePaymentEngine      = $stripePaymentEngine;
        $this->instamojoPaymentEngine   = $instamojoPaymentEngine;
        $this->supportRepository        = $supportRepository;
        $this->userRepository           = $userRepository;
        $this->paytmService             = $paytmService;
    }

    /**
     * Get Order Details using _UID or id.
     *
     * @param string/int $id - Order UID/id
     *-----------------------------------------------------------------------*/
    public function getByIdOrUid($id)
    {
        return $this->orderRepository->fetch($id);
    }

    /**
     * Fetch cart data from to cart.
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function fetchCartData()
    {
        return ShoppingCart::fetch();
    }

    /**
     * coupon apply process request.
     *
     * @param string $code
     * @param $cartTotalPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processApplyCoupon($code, $userEmail = null)
    {
        return $this->couponEngine->applyCouponProcess($code, $userEmail);
    }

    /**
     * This page return order summary page data.
     *
     * @param string $country
     * @param float  $discountAddedPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderSummaryData($addressID, $addressID1, $couponCode, $countryData, $guestUserEmail = null)
    {
        // this function use for get cart details
        $orderDetails = $this->shoppingCartEngine->getCartDetails();

        $totalPayableAmountFormated = 0;
        $countryCode = '';

        $sameAddres = false;

        if (__isEmpty($addressID) and __isEmpty($addressID1)) {
            $sameAddres = true;
        } elseif ((int) $addressID  === (int) $addressID1) {
            $sameAddres = true;
        } elseif ($addressID1 == 'null') {
            $sameAddres = true;
        }

        if (!__isEmpty($orderDetails)) {
            $cartTotalPrice = $orderDetails['data']['total']['totalBasePrice'];

            // taken primary address of customer
            if ($addressID == 'null') {
                $address = $this->addressEngine->getUserPrimaryAddress();
            } elseif ($addressID != 'null') {
                $address = $this->addressEngine->getUserAddress($addressID);
            }

            if (__isEmpty($address['address'])) {
                $billing = $this->addressEngine->getUserAddress($addressID1);

                $orderDetails['data']['billingAddress'] = $billing['address'];
            } else {
                $orderDetails['data']['shippingAddress'] = $address['address'];

                if ($sameAddres == false) {
                    $billing = $this->addressEngine->getUserAddress($addressID1);

                    $orderDetails['data']['billingAddress'] = $billing['address'];
                } else {
                    $billing = $this->addressEngine->getUserAddress($addressID1);

                    $orderDetails['data']['billingAddress'] = $address['address'];
                }

                $countryCode = $address['address']['countryCode'];
            }

            $countryCodeForTax = '';

            // if the condition is true the country take from shipping other billing
            if (getStoreSettings('calculate_tax_as_per_shipping_billing') === 2 && __ifIsset($orderDetails['data']['billingAddress'])) {
                $countryCodeForTax = $orderDetails['data']['billingAddress']['countryCode'];
            } elseif (__ifIsset($orderDetails['data']['shippingAddress'])) {
                $countryCodeForTax = $orderDetails['data']['shippingAddress']['countryCode'];
            }

            if (!isLoggedIn()) {
                if (getStoreSettings('enable_guest_order')
                    and getStoreSettings('register_new_user')) {

                    if ($countryData['shippingCountryId'] != 'null') {
                        $selectedCountry = $this->supportRepository
                                                ->fetchCountry($countryData['shippingCountryId']);
                        $countryCode = $selectedCountry->iso_code;
                    }

                    if (getStoreSettings('calculate_tax_as_per_shipping_billing') === 2
                        and $countryData['billingCountryId'] != 'null') {

                        $billingAddressCountry = $this->supportRepository
                                                ->fetchCountry($countryData['billingCountryId']);
                        $countryCodeForTax = $billingAddressCountry->iso_code;

                    } else if ($countryData['shippingCountryId'] != 'null'
                        and $countryData['useAsBilling'] == 'true') {
                        $shippingAddressCountry = $this->supportRepository
                                                ->fetchCountry($countryData['shippingCountryId']);
                        $countryCodeForTax = $shippingAddressCountry->iso_code;

                    } elseif (getStoreSettings('calculate_tax_as_per_shipping_billing') === 1
                      and $countryData['shippingCountryId'] != 'null'
                      and $countryData['useAsBilling'] == 'false') {
                        $shippingAddressCountry = $this->supportRepository
                                                ->fetchCountry($countryData['shippingCountryId']);
                        $countryCodeForTax = $shippingAddressCountry->iso_code;
                    }              
                }
            }

            $discount     = 0;
            $discountType = '';
            $couponData   = [];

            // Check if coupon code is exist
            if ($couponCode != 'null') {
                $couponData = $this->couponEngine->applyCouponProcess($couponCode, $guestUserEmail);
              
                if (!__isEmpty($couponData) and $couponData['reaction_code'] === 1) {
                    $discount     = $couponData['data']['couponData']['discount'];
                    $discountType = $couponData['data']['couponData']['discountType'];
                    $orderDetails['data']['couponData'] = $couponData['data']['couponData'];
                }
            } else {
                $couponCode = null;
            }
           
            // add shipping base on country in total order amount
            $shipping = $this->shippingEngine->addShipping($countryCode, $cartTotalPrice, $discount, $countryData['shippingMethod']);

            //__pr($cartTotalPrice, $discount, $shipping['totalPrice']);

            $orderDetails['data']['shipping'] = $shipping;

            // add taxses base on country with cart cart amount
            $orderDetails['data']['taxses'] = $this->taxEngine->additionOfTaxses($countryCodeForTax, $cartTotalPrice, $shipping['totalPrice'], $discount);

            // total amount should be pay by customer
            $totalPayableAmountFormated = $orderDetails['data']['taxses']['totalPrice'];
        }

        $taxIDs = [];
        $shippingID = null;

        // Check if taxes is exist
        if (!__isEmpty($orderDetails['data']['taxses']['info'])) {
            $taxData = $orderDetails['data']['taxses']['info'];

            foreach ($taxData as $key => $tax) {
                $taxIDs[] = $tax['id'];
            }
        }
        
        $user = Auth::user();

        // set login user full name
        if (isLoggedIn()) {
            $orderDetails['data']['user'] = [
                'fullName'          => $user->fname.' '.$user->lname,
                'userEmail'         => $user->email,
                'shippingCountry'   => $countryData['shippingCountryId'],
                'billingCountry'    => $countryData['billingCountryId'],
                'useAsBilling'      => ($countryData['useAsBilling'] == 'true')
                                        ? true : false 
            ];
        } else {
            $orderDetails['data']['user'] = [
                'fullName'          => '', 
                'userEmail'         => '',
                'shippingCountry'   => $countryData['shippingCountryId'],
                'billingCountry'    => $countryData['billingCountryId'],
                'useAsBilling'      => ($countryData['useAsBilling'] == 'true')
                                        ? true : false 
            ];
        }

        $orderDetails['data']['orderRoute'] = route('order.summary.view');
        $orderDetails['data']['totalPayableAmountFormated']  = priceFormat($totalPayableAmountFormated, true, true);
        $orderDetails['data']['totalPayableAmount']          = $totalPayableAmountFormated;
        $orderDetails['data']['totalPayableAmountForRazorPay'] = handleCurrencyAmount($this->stripePaymentEngine->getAmount($totalPayableAmountFormated));
        $orderDetails['data']['totalPayableAmountForStripe'] = handleCurrencyAmount($this->stripePaymentEngine->getAmount($totalPayableAmountFormated));
        $orderDetails['data']['totalPayableAmountForPaystack'] = handleCurrencyAmount($this->stripePaymentEngine->getAmount($totalPayableAmountFormated));
        $orderDetails['data']['discountAddedPrice'] = isset(
                                                $shipping['discountAddedPrice'])
                                                and !__isEmpty($shipping['discountAddedPrice'])
                                                ? $shipping['discountAddedPrice']
                                                : null;
 
        // prepare session data
        $sessionData = [
            'addressID' => $addressID,
            'shippingID' => $shipping['shippingIds'],
            'couponCode' => $couponCode,
            'taxID' => $taxIDs,
            'addressID1' => $addressID1,
            'sameAddress' => $sameAddres, // same address
            'discountType' => $discountType
        ];

        //check stripe test mode on or off
        if (configItem('env_settings.stripe_test_mode') == true) {
            $orderDetails['data']['stripeKey'] = getStoreSettings('stripe_testing_publishable_key');
        } else {
            $orderDetails['data']['stripeKey'] = getStoreSettings('stripe_live_publishable_key');
        }

        //check razorpay test mode on or off
        if (configItem('env_settings.razorpay_test_mode') == true) {
            $orderDetails['data']['razorpayKey'] = getStoreSettings('razorpay_testing_key');
        } else {
            $orderDetails['data']['razorpayKey'] = getStoreSettings('razorpay_live_key');
        }

        //check iyzico test mode on or off
        if (configItem('env_settings.iyzipay_test_mode') == true) {
            $orderDetails['data']['iyzipayKey'] = getStoreSettings('iyzipay_testing_key');
        } else {
            $orderDetails['data']['iyzipayKey'] = getStoreSettings('iyzipay_live_key');
        }

        //check paytm test mode on or off
        if (configItem('env_settings.paytm_test_mode') == true) {
            $orderDetails['data']['paytmKey'] = getStoreSettings('paytm_testing_merchant_key');
        } else {
            $orderDetails['data']['paytmKey'] = getStoreSettings('paytm_live_merchant_key');
        }

        //check instamojo test mode on or off
        if (configItem('env_settings.instamojo_test_mode') == true) {
            $orderDetails['data']['instamojoApiKey'] = getStoreSettings('instamojo_testing_api_key');
        } else {
            $orderDetails['data']['instamojoApiKey'] = getStoreSettings('instamojo_live_api_key');
        }

        //check paytm test mode on or off
        if (configItem('env_settings.paystack_test_mode') == true) {
            $orderDetails['data']['paystackPublicKey'] = getStoreSettings('payStack_testing_public_key');
        } else {
            $orderDetails['data']['paystackPublicKey'] = getStoreSettings('payStack_live_public_key');
        }

        $orderDetails['data']['description'] = __tr('Order at __storeName__', [
            '__storeName__' => __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') )
        ]);

        $orderDetails['data']['sameAddress'] = $sameAddres; // same address
        $orderDetails['data']['businessEmail'] = getStoreSettings('business_email'); // busniess mail
        $orderDetails['data']['checkoutMethod'] = getStoreSettings('valid_checkout_methods'); // payment methods

        $selectPaymentOption = getStoreSettings('select_payment_option');
        $paymentMethodOption = [];

        //check if select payment option is empty
        if (__isEmpty($selectPaymentOption)) {

            $paymentMethodOption = [];

        } else {
            //coolect selected payment option
            $paymentOptionList =  configItem('orders.payment_methods', getStoreSettings('select_payment_option'));
          
            //collect payment array keys
            $selectPaymentOptionId = array_keys($paymentOptionList);

            //payment option list
            $paymentMethod = configItem('payment_methods_list');

            
            //collect valid keys in array
            foreach ($paymentMethod as $key => $method) {
                if (in_array($method['id'], $selectPaymentOptionId)) {
                    $paymentMethodOption[] = $method['id'];
                }
            }
        }
        
        //collect payment methods
        $orderDetails['data']['paymentMethod'] = $paymentMethodOption;
      
        $orderDetails['data']['checkoutMethodInfo'] = [
            'checkText' => getStoreSettings('payment_check_text'),
            'bankText' => getStoreSettings('payment_bank_text'),
            'codText' => getStoreSettings('payment_cod_text'),
            'otherText' => getStoreSettings('payment_other_text'),
        ]; // payment methods text

        $addressTypes = '';
        $countries = '';

        if (!isLoggedIn()) {
            $allCountries = $this->addressEngine->fetchCountries();
            $countries = $allCountries['data']['countries'];
            $addressTypes = config('__tech.address_type_list');
        }

        $orderDetails['data']['addressSupportData'] = [
            'addressTypes' => $addressTypes,
            'countries'    => $countries
        ];

        $orderDetails['data']['shipping_methods'] = $this->shippingRepository->fetchMethodsViaCountry($countryCode)->toArray();
   
        // create new session of order summary details
        NativeSession::set('orderSummaryDataIds', $sessionData);
     
        return __engineReaction(1, ['orderSummaryData' => $orderDetails]);
    }

    /**
     * update order summary page data.
     *
     * @param array $inputs
     *---------------------------------------------------------------- */
    protected function updateOrderSummaryData($inputs)
    {
        $countryData = [
            'shippingCountryId' => $inputs['shipping_country'],
            'billingCountryId'  => $inputs['billing_country'],
            'useAsBilling'      => $inputs['use_as_billing'],
            'shippingMethod'    => array_get($inputs, 'shipping_method')
        ];

        $guestUserEmail = (isset($inputs['userEmail'])) ? $inputs['userEmail'] : null;

        $orderSumamryData = $this->prepareOrderSummaryData(
            __isEmpty($inputs['addressID']) ? 'null' : $inputs['addressID'],
            __isEmpty($inputs['addressID1']) ? 'null' : $inputs['addressID1'],
            $inputs['couponCode'],
            $countryData,
            $guestUserEmail
        );

        return $orderSumamryData['data']['orderSummaryData']['data'];
    }

    /**
     * process order request for place order.
     *
     * @param array $inputs
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderProcess($inputs)
    {
        $orderUID = generateOrderID();

        $reactionCode = $this->orderRepository
                              ->processTransaction(function () use ($inputs, $orderUID, &$checkoutMethod) {
                                  $getCartItems = $this->fetchCartData();

               // Check if cart content empty
            if (__isEmpty($getCartItems)) {
                return $this->orderRepository->transactionResponse(2, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                            'isUpdateIt'         => true
                        ], __tr('Your cart is empty.'));
            }

            // fetch cart data from to the session $this->fetchCartData();
            // check this cart item available in database
            $productsDataForComapre = $this->shoppingCartEngine->getProductForCart($getCartItems);

            // this function match the value of database & cart data.
            // and return in to set key isValidItem or not
            $afterMatchCartItemsData = getRefinedCart(
                                            $getCartItems,
                                            $productsDataForComapre['products']
                                        );

            // if any item of cart is invlid then return error
            if ($afterMatchCartItemsData['cartReady'] === false) {
                return $this->orderRepository->transactionResponse(2, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                            'isUpdateIt'       => true
                        ],
                    __tr("We're sorry. The highlighted item(s) in your Shopping Cart are currently unavailable. Please remove the item(s) to proceed."));
            }

            $cartTotalAmount = $afterMatchCartItemsData['orderDiscount']['newCartTotal'];

            $orderSessionData = [];

            // Check if order summary data ids exist
            if (NativeSession::has('orderSummaryDataIds')) {
                $orderSessionData = NativeSession::get('orderSummaryDataIds');
            }

            $guestUserEmail = null;

            // If guest order submitted then add new user and addresses
            if (!isLoggedIn()) {
                $guestUserData = $this->prepareGuestOrderUser($inputs);
                $guestUserEmail = $inputs['email'];

                if (array_has($guestUserData, 'reaction_code')) {
                
                    if ($guestUserData['reaction_code'] == 3) {
                        if (array_has($guestUserData['data'], 'isInActive')) {
                            return $this->orderRepository->transactionResponse(2, [
                                'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                            ], __tr('Your Account is inactive, please contact administrator to activate it.')); 
                        } else if (array_has($guestUserData['data'], 'openLoginDialog')) {
                            return $this->orderRepository->transactionResponse(9, [
                                'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                            ], $guestUserData['message']); 
                        }
                    } elseif ($guestUserData['reaction_code'] == 2) {
                        return $this->orderRepository->transactionResponse(2, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], $guestUserData['message']);
                    }
                }

                $inputs['addressID'] = $guestUserData['addressId1'];
                $inputs['addressID1'] = $guestUserData['addressId2'];
                $inputs['sameAddress'] = $inputs['use_as_billing'];
                $inputs['current_user_id'] = $guestUserData['users_id'];

                // check address is selected or not
                $isValidAddress = $this->addressEngine->checkIsValidAddress($orderSessionData, $inputs);

                // this address is empty
                if ($isValidAddress === 3) {
                    return $this->orderRepository->transactionResponse(2, [
                        'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                    ], __tr('Please select your shipping address.')); // address not selected
                }

                // this address1 is empty
                if ($isValidAddress === 4) {
                    return $this->orderRepository->transactionResponse(2, [
                        'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                    ], __tr('Please select your billing address'));
                    // billing address not selected
                }

                $prepareOrderData['addresses_id']   = $isValidAddress['addresses_id'];
                $prepareOrderData['addresses_id1']  = $isValidAddress['addresses_id1'];
                $shippingCountry                    = $isValidAddress['shippingCountry'];
                $prepareOrderData['users_id']       = $guestUserData['users_id'];
                $country                            = $isValidAddress['country'];

            } else {

                // check address is selected or not
                $isValidAddress = $this->addressEngine->checkIsValidAddress($orderSessionData, $inputs);

                // this address is empty
                if ($isValidAddress === 3) {
                    return $this->orderRepository->transactionResponse(2, [
                        'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                    ], __tr('Please select your shipping address.')); // address not selected
                }

                // this address1 is empty
                if ($isValidAddress === 4) {
                    return $this->orderRepository->transactionResponse(2, [
                        'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                    ], __tr('Please select your billing address'));
                    // billing address not selected
                }

                $prepareOrderData['addresses_id'] = $isValidAddress['addresses_id'];
                $prepareOrderData['addresses_id1'] = $isValidAddress['addresses_id1'];
                $country = $isValidAddress['country'];
                $shippingCountry = $isValidAddress['shippingCountry'];

            }
            
            if (isset($inputs['orderDiscount'])
                and (!__isEmpty($inputs['orderDiscount']))) {
                
                if (handleCurrencyAmount($inputs['orderDiscount']) != handleCurrencyAmount($afterMatchCartItemsData['orderDiscount']['discount'])) {
                    return $this->orderRepository->transactionResponse(2, null, __tr('Ooops... During order process some changes in cart discount, please reload page.')); //coupon is expired
                }
            }            

            $discount     = 0;
            $discountType = '';
            $appliedDiscountThenTotalAmount = handleCurrencyAmount($cartTotalAmount);

            // check if the coupon is applied then check this coupon is valid
            if (!__isEmpty($orderSessionData['couponCode'])) {
                $couponData = $this->couponEngine->applyCouponProcess($orderSessionData['couponCode'], $guestUserEmail);

                // check applied coupon
                if ($couponData['reaction_code'] == 2) {
                    return $this->orderRepository->transactionResponse(2, null, __tr('Your coupon may be inactive / expire / invalid, please remove this coupon and try another code.')); //coupon is expired
                }
                
                // set coupon data for order storing
                $coupon   = $couponData['data']['couponData'];
                $discount = $coupon['discount'];

                // check applied amount of shipping
                if (handleCurrencyAmount($discount)
                    !== handleCurrencyAmount($inputs['totalDiscountAmount'])) {
                    return $this->orderRepository->transactionResponse(3, [
                                'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                            ], __tr('The applied discount amount changed.'));
                }

                $discountType = $coupon['discountType'];
                $prepareOrderData['coupons__id'] = $coupon['couponID'];
                $prepareOrderData['discount_amount'] = $discount;

                // applied tax after discount amount
                /*if (getStoreSettings('apply_tax_after_before_discount') == 2) {
                    $appliedDiscountThenTotalAmount = $couponData['data']['totalPrice'];
                }*/
            }
            
            // if the discount coupon available then check the type of coupon if not same it fire error
            // amount, percent
            if ((__ifIsset($orderSessionData['discountType']) and __ifIsset($discountType))
                and ($orderSessionData['discountType'] !== $discountType)) {
                return $this->orderRepository->transactionResponse(2, null, __tr('Your coupon may be inactive / expire / invalid, please remove this coupon and try another code.')); //coupon is expired
            }

            $shippingMethod = array_get($inputs, 'shipping_method');

            // check if shipping is valid take calculated data
            $shipping = $this->shippingEngine
                             ->getShipping($country, $shippingMethod);

            // Check if shipping is exist
            if (__isEmpty($shipping)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('Shipping Rule not available, Please contact administrator.'));
            }

            $shippingType = $shipping->pluck('type')->toArray();

            // if shipping is empty or type 4 means [ not shipable ]
            if (in_array(4, $shippingType)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('We are sorry currently shipping not available for your country.'));
            }
          
            // Get calculation of shipping
            $calculatedShipping = $this->shippingEngine
                                       ->addShipping(
                                            $shippingCountry,
                                            $cartTotalAmount,
                                            $discount,
                                            $shippingMethod
                                        );
                                    
            // check applied amount of shipping
            if (handleCurrencyAmount($calculatedShipping['totalShippingAmount'])
                !== handleCurrencyAmount($inputs['totalShippingAmount'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('The applied shipping amount is changed.'));
            }

            $prepareOrderData['shipping_amount'] =  $calculatedShipping['shippingAmt'];

            // add taxses base on country with cart amount
            $taxData = $this->taxEngine
                            ->additionOfTaxses(
                                    $country,
                                    $appliedDiscountThenTotalAmount,
                                    $calculatedShipping['totalPrice'],
                                    $discount
                                );
              
            // check applied amount of shipping
            if (handleCurrencyAmount($taxData['totalTaxAmount'])
                !== handleCurrencyAmount($inputs['totalTaxAmount'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('The applied tax amount is changed.'));
            }


            $totalPayableAmount = $taxData['totalPrice'];

            $onlinePaymentMethods = [
                1, // PayPal
                6, // Stripe
                7, // PayPal Sandbox
                8, // Stripe Test
                11, // Razorpay
                12, // Razorpay Test
                14, // Iyzipay
                15, // Iyzipay Test
                16, // Paytm
                17, // Paytm Test
                18, // Instamojo
                19, // Instamojo Test
                20, // Paystack 
                21, // Paystack test
             ];

            // for check out perticular method for order payment
            if (!__ifIsset($inputs['checkout_method']) or !$this->isValidOrderMethod($inputs['checkout_method'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('Invalid Checkout Method'));
            }

            $currency = getCurrency();

            // if the currency is not set then
            if (!__ifIsset($currency)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('No currency available, Please contact store administrator.'));
            }

            // if the currency is change the show this msg
            if (!__ifIsset($inputs['currency']) or (__ifIsset($inputs['currency']) and $inputs['currency'] != $currency)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('Some information may be updated from server, Please review your order again carefully.'));
            }

            $checkoutMethod = $inputs['checkout_method'];

            if ($checkoutMethod == 6 and configItem('env_settings.stripe_test_mode') == true) {
                $checkoutMethod = 8;

            }   else if ($checkoutMethod == 1 and configItem('env_settings.paypal_test_mode') == true) {
                $checkoutMethod = 7;

            }   else if ($checkoutMethod == 11 and configItem('env_settings.razorpay_test_mode') == true) {
                $checkoutMethod = 12;

            }   elseif ($checkoutMethod == 14 and configItem('env_settings.iyzipay_test_mode') == true) {
                $checkoutMethod = 15;

            }   elseif ($checkoutMethod == 16 and configItem('env_settings.paytm_test_mode') == true) {
                $checkoutMethod = 17;

            }   elseif ($checkoutMethod == 18 and configItem('env_settings.instamojo_test_mode') == true) {
                $checkoutMethod = 19;

            }   elseif ($checkoutMethod == 20 and configItem('env_settings.paystack_test_mode') == true) {
                $checkoutMethod = 21;

            }

            // if check the order price & total order price
            if (handleCurrencyAmount($totalPayableAmount) != handleCurrencyAmount($inputs['totalPayableAmount'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ], __tr('Some information may be updated from server, Please review your order again carefully.'));
            }

            // if set payment method 1 for paypal,
            $prepareOrderData['payment_method'] = $checkoutMethod;
            $prepareOrderData['total_amount'] = $totalPayableAmount;    // total amount of order
            $prepareOrderData['taxses'] = $taxData['info'];
            $prepareOrderData['type'] = (in_array($checkoutMethod, $onlinePaymentMethods)) ? 2 : 1; // offline
            $prepareOrderData['name'] = $inputs['fullName'];
            $prepareOrderData['status'] = 1;
            $prepareOrderData['payment_status'] = 1; // Avaiting Payment
            $prepareOrderData['order_uid'] = $orderUID;
            $prepareOrderData['currency_code'] = $currency;
            $prepareOrderData['cartItems'] = $afterMatchCartItemsData['productData'];

            $additionalDiscountData = [];

            foreach ($afterMatchCartItemsData['productData'] as $discountKey => $additionalDiscount) {
                if (isset($additionalDiscount['productDiscount'])
                    and ($additionalDiscount['productDiscount']['isDiscountExist'])) {

                    $additionalDiscountData['product_discount'][$additionalDiscount['id']] = $additionalDiscount['productDiscount'];
                }                
            }

            if (isset($afterMatchCartItemsData['orderDiscount'])
                and ($afterMatchCartItemsData['orderDiscount']['isOrderDiscountExist'])) {
                $additionalDiscountData['order_discount'] = $afterMatchCartItemsData['orderDiscount'];
            }

            if (isset($prepareOrderData['coupons__id'])) {
                if (isset($coupon)) {
                    $additionalDiscountData['coupon_info'] = $coupon;
                }
            }

            $additionalDiscountData['shipping_method_id'] = $shippingMethod;
            $additionalDiscountData['shippingsData'] = $shipping->toArray();

            $prepareOrderData['additionalDiscountData'] = $additionalDiscountData;
            
           // save order request in database & return created order id
           if (!$storedOrder = $this->orderRepository->orderProcess(
                                            $prepareOrderData
                                )) {
               return $this->orderRepository->transactionResponse(2, null, __tr('oh..no. error..'));
           }

           
            // Get order details from database for latest placed order
            $order = $this->prepareOrderDataForSendMail($orderUID);

            $currencyCode = $order['currencyCode'];

             // send mail of this order which is successfully placed order.
            if (in_array($checkoutMethod, $onlinePaymentMethods) === false) {
                $paymentDetails = [];

                if ($checkoutMethod == 2) { // order by Check

                    $paymentDetails = getStoreSettings('payment_check_text');
                } elseif ($checkoutMethod == 3) { // order by bank

                    $paymentDetails = getStoreSettings('payment_bank_text');
                } elseif ($checkoutMethod == 4) { // order by COD

                    $paymentDetails = getStoreSettings('payment_cod_text');
                }

                // $messageData = [
                //     'orderData' => $order,
                //     'paymentDetails' => $paymentDetails,
                //     'orderConfig' => config('__tech.address_type'),
                //     'orderDetailsUrl' => route('my_order.details', $orderUID),
                // ];
                
                // if (!isLoggedIn()) {
                //     $this->mailService->notifyCustomer('Your Order has been Submitted', 'order.customer-order', $messageData, $inputs['email']);
                // } else {
                //     $this->mailService->notifyCustomer('Your Order has been Submitted', 'order.customer-order', $messageData);
                // }
                
                // $this->mailService->notifyAdmin('New Order Received', 'order.customer-order', $messageData);


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


                $orderAddrHeader = 'Shipping Address';
                if($order['address']['sameAddress'] != false ) {
                	$orderAddrHeader = 'Shipping/Billing Address';
                }
                $shippingAddressData = [
					'{__type__}'		 => $order['address']['shippingAddress']['type'],
					'{__addressLine1__}' => $order['address']['shippingAddress']['addressLine1'],
					'{__addressLine2__}' => $order['address']['shippingAddress']['addressLine2'],
					'{__city__}'		 => $order['address']['shippingAddress']['city'],
					'{__state__}'		 => $order['address']['shippingAddress']['state'],
					'{__country__}'		 => $order['address']['shippingAddress']['country'],
					'{__pinCode__}'		 => $order['address']['shippingAddress']['pinCode'],
					'{__orderAdressHeader__}' => $orderAddrHeader,
					'{__fullName__}' => $order['fullName'],
                ];
                $shippingAddressView = view('dynamic-mail-templates.order.shipping-address')->render();
				$shippingAddressTemplate = strtr($shippingAddressView, $shippingAddressData);

				$billingAddressTemplate = '';
			 
                if ($order['address']['sameAddress']) {

                	$billingAddressData = [
						'{__bill_type__}'		 	=> $order['address']['billingAddress']['type'],
						'{__bill_addressLine1__}' 	=> $order['address']['billingAddress']['addressLine1'],
						'{__bill_addressLine2__}' 	=> $order['address']['billingAddress']['addressLine2'],
						'{__bill_city__}'		 	=> $order['address']['billingAddress']['city'],
						'{__bill_state__}'		 	=> $order['address']['billingAddress']['state'],
						'{__bill_country__}'		=> $order['address']['billingAddress']['country'],
						'{__bill_pinCode__}'		=> $order['address']['billingAddress']['pinCode'],
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
                		'{__detailsURL__}' => $items['detailsURL'],
						'{__imagePath__}' => $items['imagePath'],
						'{__productName__}' => $items['baseProductName'],
                        '{__productFormatPrice__}'  => $items['baseFormatePrice'],
						'{__options__}' => $options,
						'{__productDiscount__}' => $productDiscount ?? '',
                        '{__productOldPrice__}'     => $items['baseFormattedOldProductPrice'],
						'{__quantity__}' => $items['quantity'],
						'{__formatedProductPrice__}' => $items['baseFormatedProductPrice'],
						'{__formatedTotal__}' => $items['baseFormatedTotal'],
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
            		$orderedData['{__formatedOrderDiscount__}'] = $order['baseFormatedOrderDiscount'];
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

                $orderedData = [
                	'{__formatedShippingAmount__}' 	=> 	$formatedShippingAmount,
                	'{__formatedSubtotal__}'		=>	$order['orderProducts']['baseFormatedSubtotal'],
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
                    '{__orderDetailsUrl__}' => route('my_order.details', $orderUID),
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
					

                // $messageData = [
                //     'orderData' => $orderedData,
                //     'paymentDetails' => $paymentDetails,
                //     'orderConfig' => config('__tech.address_type'),
                //     'orderDetailsUrl' => route('my_order.details', $orderUID),
                // ];

                $emailTemplateCustomer = configItem('email_template_view', 'order_process_mail_to_user');
                $emailTemplateAdmin = configItem('email_template_view', 'order_process_mail_to_admin');

		        if (!isLoggedIn()) {
		        	sendDynamicMail('order_process_mail_to_user', $emailTemplateCustomer['emailSubject'], $orderedData, $inputs['email']);
				} else {
					sendDynamicMail('order_process_mail_to_admin', $emailTemplateCustomer['emailSubject'], $orderedData, $order['email']);
				}

				$orderedData['{__orderProcessMailHeader__}'] = 'New Order Received';
				$orderedData['{__orderProcessMailMessage__}'] ='You have received new order. You can see order details below or by clicking button.';
				
                $emailSubject = 'New Order Received';

				sendMailToAdmin('order_process_mail_to_admin', $emailSubject, $orderedData, null, 1);

				
            }

            // iyzico payment process here after order submitted functionality
           if ($prepareOrderData['payment_method'] == 14 or $prepareOrderData['payment_method'] == 15) {

                $prepareOrderData['orderID'] = $storedOrder['orderID'];
                $iyzicoPaymentProcess = $this->processIyzicoPaymentProcess($prepareOrderData, $inputs);
          
                if ($iyzicoPaymentProcess['result'] == true) {
                    return $this->orderRepository->transactionResponse(1, [
                            'htmlContent'   => $iyzicoPaymentProcess['htmlContent'],
                            'message'       => $iyzicoPaymentProcess['message']
                        ]);
                }

                if ($iyzicoPaymentProcess['result'] == false) {
                    return $this->orderRepository->transactionResponse(2, null, $iyzicoPaymentProcess['message']);
                }
                // if ($iyzicoPaymentProcess[0] == 1) {
                //     return $this->orderRepository->transactionResponse(1, [
                //         'htmlContent' => $iyzicoPaymentProcess[1]['htmlContent']
                //     ]);
                // }
            } 
            // iyzico payment end here

            // to display the msg of successfully order placed & provide facility ot user
            // track this order.
            NativeSession::set('orderSuccessMessage', ['successStatus' => true]);

            if (!isLoggedIn()) {
                NativeSession::set('successMessage', [
                    'successStatus' => true,
                    'full_name' => $inputs['fullName'],
                    'order_uid' => $orderUID,
                ]);
            }

            if (in_array($checkoutMethod, $onlinePaymentMethods) === false) { // remove items if Payment method is other than paypal
                // after send this mail the cart is empty
               $this->shoppingCartEngine->processRemoveAlItems();
            }

            return 1;
        });

        // Stripe
        if ($checkoutMethod === 6 or $checkoutMethod === 8) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

        // razorpay
        if ($checkoutMethod === 11 or $checkoutMethod === 12) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

        // iyzipay
        if ($checkoutMethod === 14 or $checkoutMethod === 15) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

        // paytm
        if ($checkoutMethod === 16 or $checkoutMethod === 17) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

        // instamojo
        if ($checkoutMethod === 18 or $checkoutMethod === 19) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

         // paystack
        if ($checkoutMethod === 20 or $checkoutMethod === 21) {
            return __engineReaction($reactionCode, [
                'orderID'       => $orderUID,
                'ckMethod'      => $checkoutMethod,
                'orderPaymentToken' => $this->setOrderPaymentToken($orderUID),
            ]);
        }

        return __engineReaction($reactionCode, [
            'orderID' => $orderUID,
            'ckMethod' => __ifIsset($inputs['checkout_method'], $inputs['checkout_method'], 1),
            'cartItems' => $this->shoppingCartEngine->updateCartString(),
        ]);
    }

    /**
     * Prepare and store guest order user and its addresses.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function prepareGuestOrderUser($input)
    {
        if (!getStoreSettings('register_new_user')) {
            return __engineReaction(2, null, __tr('Register new user has been deactivated, please contact to admin.'));
        }

        // get email of deleted user
        $usersEmail = $this->userRepository->fetchEmailOfUsers()->toArray();

        $emailCollection = [];

        // Delete never activated users old than set time in config account activation hours
        $this->userRepository->deleteNonActicatedUser();

        // push email into array
        foreach ($usersEmail as $key => $email) {
            if (($email['email'] == strtolower($input['email'])) and ($email['status'] === 2)) {
                return __engineReaction(3, ['isInActive' => true]);
            }

            $emailCollection[] = $email['email']; 
        }

        if ($input['shipping_country'] == 'null') {
            return __engineReaction(3, ['openLoginDialog' => true], __tr('Please select your shipping country.'));
        }

        if (in_array(strtolower($input['email']), $emailCollection, true) == true) {
            
            $newUser = $this->userRepository->fetchActiveUserByEmail($input['email']);

            // Check if user stored
            if (empty($newUser)) {
                return __engineReaction(2, [], __tr('Your account may be inactive or deleted, please contact store admin.'));
            }

            $input['guest_user_id'] = $newUser->id;

        } else {

            $input['password'] = substr(str_shuffle($input['fullName']), 0, 6);
            
            $newUser = $this->userRepository->storeNewGuestUser($input);

            // Check if user stored
            if (empty($newUser)) {
                return __engineReaction(2, [], __tr('Registration process failed.'));
            }

            $input['guest_user_id'] = $newUser->id;

            // prepare data for email view
            $messageData = [
                'firstName'      => $newUser->fname,
                'lastName'       => $newUser->lname,
                'email'          => $newUser->email,
                'fullName'       => $newUser->fname.' '.$newUser->lname,
                'expirationTime' => configItem('account.activation_expiry'),
                'userID'         => $newUser->id,
                'activationKey'  => $newUser->remember_token,
            ];
            
            $this->mailService->notifyCustomer('Account Activation', 'account.account-activation', $messageData, $newUser->email);
        }

        // Store new Address
        $guestUserData = $this->addressEngine->storeAddresses($input);
        $guestUserData['users_id'] = $newUser->id;

        return $guestUserData;
        //}
    }

    /**
     * Process Iyzico payments.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processIyzicoPaymentProcess($prepareOrderData, $inputs)
    {
        if (isLoggedIn()) {

            $address = $this->addressEngine->getUserAddress($prepareOrderData['addresses_id']);
            $userAddress = $address['address'];
            $userId  = Auth::id();
            $city = $userAddress['city'];
            $address = $userAddress['address_line_1'].' '.$userAddress['address_line_2'];
            $countriesId = $userAddress['countries_id'];
            $country = $userAddress['country'];

        } elseif (!isLoggedIn()) {
            $shippingCountry = $this->supportRepository->fetchCountry($inputs['shipping_country']);
            $userId = $prepareOrderData['users_id'];
            $city = $inputs['shipping_city'];
            $address = $inputs['shipping_address_line_1'].' '.$inputs['shipping_address_line_2'];
            $countriesId = $inputs['shipping_country'];
            $country = $shippingCountry->name;
        }
      
        
        $paymentData = [
            'amount'        => $prepareOrderData['total_amount'],
            'users__id'     => uniqid($userId),
            'order_id'      => encrypt($prepareOrderData['orderID']),
            'accounts__id'  => uniqid($userId),
            'city'          => $city,
            'address'       => $address,
            'countries__id' => $countriesId,
            'country'       => $country,                
            'name'          => $inputs['fullName'],    
            'email'         => $inputs['userEmail'],                    
            'currency_code' => $prepareOrderData['currency_code'],
            'subject_type'  => 1, // credit
            'txn_type'      => 2, // renewal
            'conversation_id' => uniqid($prepareOrderData['orderID'].'_'), // renewal   
            'item_basket_id' => uniqid($prepareOrderData['orderID'].'_'), // renewal   
            'subscription_plan_type'  => 1,
            'subscription_plan' => $prepareOrderData['orderID']
        ];

        $expiry = explode('/', $inputs['expiry_date']);

        // Prepare card Data
        $cardData = [
            'card_number'   => $inputs['card_number'],
            'cvv'           => $inputs['cvv'],
            'expiry_date'   => $inputs['expiry_date'],
            'name_on_card'  => $inputs['name_on_card'],
            'exp_month'     => array_get($expiry, '0'),
            'exp_year'      => array_get($expiry, '1'),
        ];

        $iyzipayService = new IyzipayService();
        $iyzipayRequest = $iyzipayService->requestPayment($paymentData, $cardData);
      
        // If not success
        if(strtolower($iyzipayRequest->getStatus()) !== 'success') {
             
            return [
                'result'  => false,
                'message' => $iyzipayRequest->getErrorMessage()
            ];
        }

        // If payment request success
        if (strtolower($iyzipayRequest->getStatus()) == 'success') {
           
            return 
                [   
                    'htmlContent'   => $iyzipayRequest->getHtmlContent(),
                    'result'        => true,
                    'message'       => $iyzipayRequest->getStatus()
                ];
        }

        // $paymentData = [
        //     'type' => 1, // Deposit
        //     'txn' => $iyzipayRequest->getPaymentId(),
        //     'payment_method' => 9,
        //     'currency_code' => $prepareOrderData['currency_code'],
        //     'gross_amount' => $prepareOrderData['total_amount'],
        //     'fee' => null,
        //     'orders__id' => $prepareOrderData['orderID'],
        //     'raw_data' => $iyzipayRequest->getRawResult(),
        //     'payment_status' => 2 // Completed
        // ];

        // if ($this->orderPaymentsRepository->storeNewPayment($paymentData)) {
          
        //     //$this->notifyPaymentConfirmation($prepareOrderData['order_uid']);

        //     return [
        //         'result'  => true,
        //         'message' => $iyzipayRequest->getStatus()
        //     ];
        // }

        return [
            'result'  => false,
            'message' => __tr('Something went wrong on server, please try again later.')
        ];
    }

    /**
     * Set the order payment token for accessing recent order.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function setOrderPaymentToken($orderUID)
    {
        $orderToken = uniqid(rand(111111, 999999));
        NativeSession::set('RECENT_PAYMENT_ORDER_'.$orderToken, $orderUID);

        return $orderToken;
    }

    /**
     * Get the order payment token for accessing recent order.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getOrderPaymentToken($orderToken)
    {
        // check if recent order
        return NativeSession::pullIfHas('RECENT_PAYMENT_ORDER_'.$orderToken);
    }

   /**
    * prepare order products.
    *
    * @param array  $products
    * @param string $currency
    *
    * @return array
    *---------------------------------------------------------------- */
   protected function prepareOrderProducts($products, $currency, $discountData = [])
   {
       // calculate product prices
        $subtotal = $orderProducts = $productOldPrice = [];

       foreach ($products as $pKey => $product) { 

            $productDiscount = [];

            if (isset($discountData['product_discount'])) {

                if (array_key_exists($product['products_id'], $discountData['product_discount'])) {
                    $productDiscount = $discountData['product_discount'][$product['products_id']];
                }                
            }
            $productName = __transliterate('product', $product['products_id'], 'name', $product['name']);  
            $orderProducts['orderProducts'][$pKey] = [

                'productName' => str_limit($productName, $limit = 30, $end = '...'),
                'baseProductName' => str_limit($product['name'], $limit = 30, $end = '...'),
                'actualProductName' => $product['name'],
                '_id' => $product['_id'],
                'customProductId' => $product['custom_product_id'],
                'quantity' => $product['quantity'],
                'formatedPrice' => priceFormat(
                                        $product['price'],
                                        $currency
                                    ),
                'baseFormatePrice'     => priceFormat($product['price'], false, true, ['isMultiCurrency' => true]),
                'detailsURL' => route('product.details', [
                                        'productID' => $product['products_id'],
                                        'productName' => slugIt($product['name']),
                                    ]),
                'imagePath' => getProductImageURL($product['products_id'], $product['product']['thumbnail']),
                'productDiscount' => $productDiscount
            ];

           $addonPrice = [];

            // Check if product option is exist
            if (!__isEmpty($product['product_option'])) {
                foreach ($product['product_option'] as $opKey => $options) {
                    $additionalPrice = $options['addon_price'];

                    // addon formated addon price push
                    $orderProducts['orderProducts'][$pKey]['option'][$opKey] = [

                        'formatedOptionPrice' => priceFormat($options['addon_price'], $currency),
                        'baseformatedOptionPrice' => basePriceFormat($options['addon_price'], $currency),
                        'addonPrice' => $additionalPrice,
                        'optionName' => $options['name'],
                        'valueName' => $options['value_name'],
                    ];

                    $addonPrice[] = $additionalPrice;
                }
            }

            // get add price total
            $totalAddonPrice = array_sum($addonPrice);

            $priceAddInAddonPrice = $oldProductPrice = $product['price'] + $totalAddonPrice;

            if (isset($product['product']['old_price'])) {
                $productOldPrice = $product['product']['old_price'];
            }

            // price and addon price total
            if (!__isEmpty($productDiscount)) {
                $priceAddInAddonPrice = $productDiscount['productPrice'];
            }            

            $orderProducts['orderProducts'][$pKey]['formattedOldProductPrice'] = priceFormat($productOldPrice, $currency);

            //add base old price formate  
            $orderProducts['orderProducts'][$pKey]['baseFormattedOldProductPrice'] = basePriceFormat($productOldPrice, $currency);

            //add price formate
            $orderProducts['orderProducts'][$pKey]['formatedProductPrice'] = 
            priceFormat($priceAddInAddonPrice, $currency);

            //add base price formate    
            $orderProducts['orderProducts'][$pKey]['baseFormatedProductPrice'] = basePriceFormat($priceAddInAddonPrice, $currency);

           $orderProducts['orderProducts'][$pKey]['productWithAddonPrice'] = $priceAddInAddonPrice;
            // add quantity and price
            $multQtyWithPrice = $priceAddInAddonPrice * $product['quantity'];

            // add sub total price
            $orderProducts['orderProducts'][$pKey]['formatedTotal'] = priceFormat($multQtyWithPrice, $currency);
            // add base sub total price
            $orderProducts['orderProducts'][$pKey]['baseFormatedTotal'] = basePriceFormat($multQtyWithPrice, $currency);

           $orderProducts['orderProducts'][$pKey]['total'] = $multQtyWithPrice;

           $subtotal[] = $multQtyWithPrice;
       }
       
       $sumTotal = array_sum($subtotal);

       $orderProducts['subtotal'] = $sumTotal;
       $orderProducts['formatedSubtotal'] = priceFormat($sumTotal, $currency);
       $orderProducts['baseFormatedSubtotal'] = basePriceFormat($sumTotal, $currency);

       return $orderProducts;
   }

   /**
    * prepare data for order details.
    *
    * @param string / int $orderUidOrId
    *---------------------------------------------------------------- */
   public function prepareOrderDetails($orderUidOrId)
   {
       $order = $this->orderRepository->fetchOrderDetails($orderUidOrId);

        // If order does not exist then return not found reaction code
        if (__isEmpty($order)) {
            return false;
        }

        // match key and get value
       $orderConfigItems = config('__tech.orders');
       $orderStatus = $orderConfigItems['status_codes'][$order['status']];
       $orderType = $orderConfigItems['type'][$order['type']];
       $paymentStatus = $orderConfigItems['payment_status'][$order['payment_status']];
       $paymentMethod = $orderConfigItems['payment_methods'][$order['payment_method']];
       $shippingMethodId = $order->__data['shipping_method_id'] ?? null;

        // get currency code
        $currency = $order['currency_code'];

        $jsonData = $order['__data'];
        
        // manipulate order products data like calculation, price formatting etc.
        $orderProducts = $this->prepareOrderProducts($order['order_product'], $currency, $jsonData);

        // take shipping & billing address
        $address = $this->addressEngine->getAddress($order['addresses_id'], $order['addresses_id1']);

        // take shipping data
        $shippingData = [];

        if (array_has($jsonData, 'shippingsData') and $jsonData['shippingsData']) {
            $shippingData = $jsonData['shippingsData'];
            
        } else {
            $shippingData = $this->shippingEngine
            ->getShippingInformation($address['shippingAddress']['countryCode']);
        }
       
        // take order taxes data
        $orderTaxes = $this->orderRepository->fetchOrderTaxDetails($order['_id']);

        // take shipping data
        $taxesData = $this->taxEngine->getTaxInformation($orderTaxes, $currency);

        // take coupon information
        $couponData = $this->couponEngine->getCouponInformation($order['coupons__id'], $currency);

        // get order payments
        $payment = $this->orderPaymentsRepository->fetchOrderPayments($order['_id']);

       $orderPayment = __ifIsset($payment, function ($payment) {
           return formattedDateTime($payment->created_at);
       }, null);

       $orderDiscount = __ifIsset($order['discount_amount']) ? $order['discount_amount'] : 0;
       
       $cartDiscount = [];
       
       if (isset($jsonData['order_discount'])) {

            $rawCartDiscountData = $jsonData['order_discount'];
            
            $cartDiscount = [
                'isOrderDiscountExist'  => $rawCartDiscountData['isOrderDiscountExist'],
                'discount'              => $rawCartDiscountData['discount'],
                'shortFormatDiscount'   => priceFormat($rawCartDiscountData['discount'], $order['currency_code']),
                'baseShortFormatDiscount'   => basePriceFormat($rawCartDiscountData['discount'], $order['currency_code']),
                'formattedDiscount'     => $rawCartDiscountData['formattedDiscount'],
                'discountDetails'       => $rawCartDiscountData['discountDetails'],
                'newCartTotal'          => $rawCartDiscountData['newCartTotal'],
                'formatNewCartTotal'    => priceFormat($rawCartDiscountData['newCartTotal'], $order['currency_code']),
                'baseFormatNewCartTotal'    => basePriceFormat($rawCartDiscountData['newCartTotal'], $order['currency_code'])
           ];
       }
       
       return [
            'orderPlacedOn' => formatStoreDateTime($order['created_at']), // formatted placed on time
            'baseOrderPlacedOn' => formattedDateTime($order['created_at']), // formatted placed on time
            'orderStatus' => $orderStatus,
            'status' => $order['status'],
            'businessEmail' => $order['business_email'],
            'orderUID' => $order['order_uid'],
            '_id' => $order['_id'],
            'userId' => $order['users_id'],
            'orderType' => $orderType,
            'orderDiscount' => $orderDiscount,
            'cartDiscount' => $cartDiscount,
            'couponInfo' => (isset($jsonData['coupon_info']))
                                ? $jsonData['coupon_info'] : [],
            'currencyCode' => $order['currency_code'],
            'orderShippingAmount' => $order['shipping_amount'],
            'totalOrderAmount' => $order['total_amount'],
            'formatedPaymentStatus' => $paymentStatus,
            'formatedPaymentMethod' => $paymentMethod,
            'paymentStatus' => $order['payment_status'],
            'paymentMethod' => $order['payment_method'],
            'paymentCompletedOn' => $orderPayment,
            'orderProducts' => [
                'products' => $orderProducts['orderProducts'],
                'subtotal' => $orderProducts['subtotal'],
                'formatedSubtotal' => $orderProducts['formatedSubtotal'],
                'baseFormatedSubtotal' => $orderProducts['baseFormatedSubtotal']
            ],
            'address' => $address,
            'user' => [
                'id' => $order['user']['id'],
                'email' => maskEmailId($order['user']['email']),
                'fullName' => $order['name'],
            ],
            'shipping' => $shippingData,
            'taxes' => $taxesData['info'],
            'coupon' => $couponData,
        ];
   }

   /**
    * prepare order data for send mail.
    *
    * @param string $orderUID
    *
    * @return array
    *---------------------------------------------------------------- */
   public function prepareOrderDataForSendMail($orderUID)
   {
       $prepareForSendData = $this->prepareOrderDetails($orderUID);

       $currencyCode = $prepareForSendData['currencyCode'];

       $subtotal = ($prepareForSendData['orderProducts']['subtotal'] - $prepareForSendData['orderDiscount']);

       return [
            'orderPlacedOn' => $prepareForSendData['orderPlacedOn'],
            'orderUID' => $prepareForSendData['orderUID'],
            'userId' => $prepareForSendData['userId'],
            'orderStatus' => $prepareForSendData['orderStatus'],
            'orderType' => $prepareForSendData['orderType'],
            'formatedSubTotal' => priceFormat($subtotal, $currencyCode),
            'orderDiscount'    => $prepareForSendData['orderDiscount'],
            'cartDiscount'     => $prepareForSendData['cartDiscount'],
            'couponInfo'       => $prepareForSendData['couponInfo'],
            'formatedOrderDiscount' => priceFormat(
                                            $prepareForSendData['orderDiscount'],
                                            $currencyCode
                                        ),
            'baseFormatedOrderDiscount' => basePriceFormat(
                                            $prepareForSendData['orderDiscount'],
                                            $currencyCode
                                        ),
            'currencyCode' => $currencyCode,
            'shippingAmount' => $prepareForSendData['orderShippingAmount'],
            'formatedShippingAmount' => priceFormat(
                                            $prepareForSendData['orderShippingAmount'],
                                            $currencyCode
                                        ),
            'baseFormatedShippingAmount' => basePriceFormat(
                                            $prepareForSendData['orderShippingAmount'],
                                            $currencyCode
                                        ),
            'formatedTotalOrderAmount' => priceFormat(
                                            $prepareForSendData['totalOrderAmount'],
                                            $currencyCode, 'true'
                                        ),
            'baseFormatedTotalOrderAmount' => basePriceFormat(
                                            $prepareForSendData['totalOrderAmount'],
                                            $currencyCode, 'true'
                                        ),
            'formatedPaymentStatus' => $prepareForSendData['formatedPaymentStatus'],
            'formatedPaymentMethod' => $prepareForSendData['formatedPaymentMethod'],
            'paymentStatus' => $prepareForSendData['paymentStatus'],
            'paymentMethod' => $prepareForSendData['paymentMethod'],
            'orderProducts' => $prepareForSendData['orderProducts'],
            'address' => $prepareForSendData['address'],
            'fullName' => $prepareForSendData['user']['fullName'],
            'email' => $prepareForSendData['user']['email'],
            'taxes' => $prepareForSendData['taxes'],
            'shipping' => $prepareForSendData['shipping'],
        ];
   }

    /**
     * Send PayPal for Payment.
     *---------------------------------------------------------------- */
    public function createPaypalOrder($orderUID)
    {
        $orderDetails = $this->prepareOrderDetails($orderUID);
        $current_locale = CURRENT_LOCALE;

        $cancelReturn = route('order.payment_cancelled', [
            'orderToken' => $this->setOrderPaymentToken($orderUID),
        ]);

        $notifyUrl = route('order.ipn_request');
        $shoppingUrl = route('home');
        $returnTo = route('order.thank_you');
        $business = $orderDetails['businessEmail'];
        $currency = $orderDetails['currencyCode'];
        $shippingCharges = $orderDetails['orderShippingAmount'];
        $orderProducts = $orderDetails['orderProducts']['products'];
        $couponDiscount = $orderDetails['orderDiscount'] ?: 0;
        $cartDiscount = array_get($orderDetails, 'cartDiscount.discount', 0);
        $couponDiscount = $couponDiscount + $cartDiscount;

        $paypalUrl = '';

        if (configItem('env_settings.paypal_test_mode') == true) {
            $paypalUrl .= 'Location: '.config('__tech.paypal_urls.sandbox');
        } else {
            $paypalUrl .= 'Location: '.config('__tech.paypal_urls.production');
        }

        $orderTotalAmount = $orderDetails['totalOrderAmount'];
        $storeName = getStoreSettings('store_name');
        $itemName = __tr('__storeName__ Order __orderUid__', [
            '__storeName__' => $storeName,
            '__orderUid__' => $orderUID
        ]);

        $paypalUrl     .= "?cmd=_xclick&upload=1&display=1&add=1&charset=utf-8&currency_code=$currency&business=$business&cancel_return=$cancelReturn&notify_url=$notifyUrl&rm=2&item_name=$itemName&amount=$orderTotalAmount&return=$returnTo&custom=$orderUID&invoice=$orderUID";

        header($paypalUrl);
        exit();
    }

    /**
     * Process Thank you data for Paypal Order.
     *---------------------------------------------------------------- */
    public function processThanksPayPalOrder($orderUid)
    {
        $order = $this->getByIdOrUid($orderUid);

        if ((__ifIsset($order) == false)) {
            return false;
        }

        $this->shoppingCartEngine->processRemoveAlItems();

        return $orderUid;
    }

    /**
     * Send Paytm for Payment.
     *---------------------------------------------------------------- */
    public function createPaytmOrder($orderUID)
    {
        $orderDetails = $this->prepareOrderDetails($orderUID);

        if (__isEmpty($orderDetails)) {
            return __engineReaction(2, null, __tr('Order Data Not Exist.'));
        }
       
        $data_for_request = $this->paytmService->handlePaytmRequest($orderDetails['orderUID'], $orderDetails['totalOrderAmount']);

        if (!__isEmpty($data_for_request)) {
            $paytm_txn_url = config('__tech.paytm_urls.paytm_txn_request_url');
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];

            $handlePaytmRequest = [
                'paramList' => $paramList,
                'checkSum'  => $checkSum,
                'paytm_txn_url' => $paytm_txn_url
            ];
          
            return __engineReaction(1, [
                'handlePaytmRequest'   => $handlePaytmRequest
            ]);
        }
       
        return __engineReaction(2, null, __tr('Invalid request.'));
    }

     /**
     * Send Instamojo for Payment.
     *---------------------------------------------------------------- */
    public function createInstamojoOrder($orderUID)
    {
        $orderDetails = $this->prepareOrderDetails($orderUID);

        if (__isEmpty($orderDetails)) {
            return __engineReaction(2, null, __tr('Order Data Not Exist.'));
        }

        $instamojoPaymentRequest = $this->instamojoPaymentEngine->processInstamojoChargeRequest($orderDetails);
 
        if ($instamojoPaymentRequest['reaction_code'] == 1) {
            return __engineReaction(1, $instamojoPaymentRequest['data']);
        }

        return __engineReaction(2, null, $instamojoPaymentRequest['message']);
    }

    /**
     * Prepare order list.
     *
     * @param int $methodId
     *---------------------------------------------------------------- */
    protected function isValidOrderMethod($methodId)
    {
        $validMethod = true;

        if (!in_array($methodId, getStoreSettings('select_payment_option'))) {
            $validMethod = false;
        }

        return $validMethod;
    }

    /**
     * Prepare order list.
     *---------------------------------------------------------------- */
    public function prepareOrderList($status)
    {
        return $this->orderRepository
                    ->fetchOrdersForList($status);
    }

    /**
     * Prepare for My order details data and check user authentication.
     *
     * @param string $orderUID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getMyOrderDetails($orderUID)
    {
        // Get order details
        $orderDetails = $this->prepareForMyOrderDetails($orderUID);

        // Get user Id
        $userId = $orderDetails['data']['user']['id'];

        $orderStatus = $orderDetails['data']['order']['orderStatus'];
        $allowOrderCancellation = false;

        // Check if order cancellation allow
        if (getStoreSettings('allow_customer_order_cancellation')) {
            $orderStatuses = getStoreSettings('order_cancellation_statuses');
            $definedStatus = ['3', '6', '11'];

            if (!__isEmpty($orderStatuses)) {
                array_push($orderStatuses, '3', '6', '11');
            } else {
                $orderStatuses = $definedStatus;
            }

            if (!in_array($orderStatus, $orderStatuses)) {
                $allowOrderCancellation = true;
            }
        }

        $orderDetails['data']['order']['allowOrderCancellation'] = $allowOrderCancellation;

        // check if login user Id and order user id
        if (!isAdmin() and $userId != getUserID()) {
            return __engineReaction(18);
        }

        return $orderDetails;
    }

    /**
     * Prepare for to show order details data.
     *
     * @param string $orderUID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareForMyOrderDetails($orderUID)
    {
        $prepareOrderData = $this->prepareOrderDetails($orderUID);

        // Check if order data empty then return not exist reaction code
        if (__isEmpty($prepareOrderData)) {
            return __engineReaction(18);
        }

        $newOrderStatus = false;

        // Check if session have new order status
        if (NativeSession::has('orderSuccessMessage')) {
            $orderStatus = NativeSession::get('orderSuccessMessage');
            $newOrderStatus = $orderStatus['successStatus'];
        }

        $currencyCode = $prepareOrderData['currencyCode'];
        $subtotal = ($prepareOrderData['orderProducts']['subtotal'] - $prepareOrderData['orderDiscount']);
        
        return __engineReaction(1, [
            'order' => [
                'formatedOrderPlacedOn' => $prepareOrderData['orderPlacedOn'],
                'baseFormatedOrderPlacedOn' => $prepareOrderData['baseOrderPlacedOn'],
                'orderUID' => $prepareOrderData['orderUID'],
                'formatedOrderStatus' => $prepareOrderData['orderStatus'],
                'orderStatus' => $prepareOrderData['status'],
                'formatedOrderType' => $prepareOrderData['orderType'],
                'orderDiscount' => $prepareOrderData['orderDiscount'],
                'cartDiscount'  => $prepareOrderData['cartDiscount'],
                'couponInfo'    => $prepareOrderData['couponInfo'],
                'formatedSubTotal' => priceFormat($subtotal, $currencyCode),
                'formatedOrderDiscount' => priceFormat(
                                                    $prepareOrderData['orderDiscount'],
                                                    $currencyCode
                                            ),
                'baseFormatedOrderDiscount' => basePriceFormat(
                                                    $prepareOrderData['orderDiscount'],
                                                    $currencyCode
                                            ),
                'currencyCode'  => $currencyCode,
                'shippingAmount' => $prepareOrderData['orderShippingAmount'],
                'formatedShippingAmount' => priceFormat(
                                                    $prepareOrderData['orderShippingAmount'],
                                                    $currencyCode
                                            ),
                'baseFormatedShippingAmount' => basePriceFormat(
                                                    $prepareOrderData['orderShippingAmount'],
                                                    $currencyCode
                                            ),
                'formatedTotalOrderAmount' => priceFormat(
                                                $prepareOrderData['totalOrderAmount'],
                                                $currencyCode, 'true'
                                            ),
                'baseFormatedTotalOrderAmount' => basePriceFormat(
                                                $prepareOrderData['totalOrderAmount'],
                                                $currencyCode, 'true'
                                            ),
                'formatedPaymentStatus' => $prepareOrderData['formatedPaymentStatus'],
                'formatedPaymentMethod' => $prepareOrderData['formatedPaymentMethod'],
                'newOrderStatus' => $newOrderStatus,
                'paymentStatus' => $prepareOrderData['paymentStatus'],
                'paymentCompletedOn' => $prepareOrderData['paymentCompletedOn'],
            ],
            'orderProducts' => $prepareOrderData['orderProducts'],
            'address' => $prepareOrderData['address'],
            'user' => $prepareOrderData['user'],
            'taxes' => $prepareOrderData['taxes'],
            'coupon' => $prepareOrderData['coupon']['info'],
            'shipping' => $prepareOrderData['shipping'],
        ]);
    }

    /**
     * Change address in order then get shipping, tax and coupon amount.
     *
     * @param int   $countryCode
     * @param array $couponDetail
     *---------------------------------------------------------------- */
    public function changeAddressInOrderDetails($addressID)
    {
        // Get order details when address changed
        $orderDetails = $this->prepareOrderSummaryData($addressID);

        return __engineReaction(1, $orderDetails['data']);
    }

    /**
     * Process to Download Invoice for order by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processInvoiceDownload($orderID)
    {
        // get order detail by order ID
        $orderDetails = $this->prepareForMyOrderDetails($orderID);

        // array data for creation of string for pdf
        $arrayData = [
            ':currentDate' => formatStoreDateTime(currentDateTime()),
            ':formatDate'  => formatStoreDateTime(currentDateTime(), 'j F, Y')
        ];
      
        // generated on string
        $orderDetails['currentDateTime'] = formattedDateTime(currentDateTime());
        $orderDetails['formatcurrentDate'] = formattedDateTime(currentDateTime(), 'j F, Y');
        
        //generate barcode instance
        $generator = new BarcodeGeneratorHTML();
        
        $orderDetails['orderBarcode'] = $generator->getBarcode($orderDetails['data']['order']['orderUID'], $generator::TYPE_CODE_128);
     
        // download pdf
        $orderInvoice = PDF::loadView('report.manage.pdf-report', ['orderDetails' => $orderDetails]);
        //return $orderInvoice->stream();
        return $orderInvoice->download(slugIt($orderDetails['data']['order']['orderUID']).'.pdf');
    }

    /**
     * Update Order as Cancelled as PayPal payment cancelled by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function updatePaymentFailed($orderID)
    {
        return $this->orderRepository->updateOrderAndPaymentStatus($orderID, 3, 3);
    }

    /**
     * create breadcrumb for orders.
     *
     * @param string $breadcrumbType
     *
     * @return array
     *---------------------------------------------------------------- */
    public function breadcrumbGenerate($breadcrumbType)
    {
        $breadCrumb = Breadcrumb::generate($breadcrumbType);

        // Check if breadcrumb not empty
        if (!__isEmpty($breadCrumb)) {
            return __engineReaction(1, [
                'breadCrumb' => $breadCrumb,
            ]);
        }

        return __engineReaction(2, [
                'breadCrumb' => null,
            ]);
    }

    /**
     * Prepare Order cancellation Process.
     *
     * @param string $breadcrumbType
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareCancelOrderProcess($orderID)
    {
        $order = $this->orderRepository->fetch($orderID);

        // If order does not exist then return not found reaction code
        if (__isEmpty($order)) {
            return __engineReaction(18);
        }

        if (!getStoreSettings('allow_customer_order_cancellation')) {
            return __engineReaction(9);
        }

        // Check if order cancellation allow
        if (getStoreSettings('allow_customer_order_cancellation')) {
            $orderStatuses = getStoreSettings('order_cancellation_statuses');
            $definedStatus = ['3', '6', '11'];

            if (!__isEmpty($orderStatuses)) {
                array_push($orderStatuses, '3', '6', '11');
            } else {
                $orderStatuses = $definedStatus;
            }
            
            if (in_array($order->status, $orderStatuses)) {
                return __engineReaction(9);
            }
        }

        if ($this->orderRepository->updateOrderStatus($order, 3)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Fetch User By Email.
     *
     * @param string $email
     *
     * @return array
     *---------------------------------------------------------------- */
    public function checkUserEmail($email, $couponId = null)
    {
        $user = $this->userRepository->fetchActiveUserByEmail($email);

        if (__isEmpty($user)) {
            return __engineReaction(1, ['userId' => null]);
        }

        if ($couponId == 'null') {
            return __engineReaction(1, ['userId' => $user->id]);
        }

        $couponUsage = $this->orderRepository->fetchCouponUsage($user->id, $couponId);
        $coupon = $this->couponRepository->fetchByID($couponId);
       
        if (__isEmpty($coupon)) {
            return __engineReaction(1, ['userId' => $user->id]);
        }

        // Check if usage coupon code exceed limit
        if (!__isEmpty($coupon->uses_per_user)) {
            if ($couponUsage >= $coupon->uses_per_user) {
                return __engineReaction(2, ['message' => __tr('You have exceed coupon usage limit, so you need to remove this coupon.'), 'userId' => $user->id]);
            }
        }
        
        return __engineReaction(1, ['userId' => $user->id]);
    }
}
