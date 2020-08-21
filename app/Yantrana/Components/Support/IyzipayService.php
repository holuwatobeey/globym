<?php
/*
* IyzipayService.php - Main component file
*
* This file is part of the Account component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support;

/*use Iyzipay\Options as IyzipayOptions;
use Iyzipay\Request\CreatePaymentRequest as IyzipayCreatePaymentRequest;*/

use Request;

class IyzipayService
{
    /**
     * @var array $options
     */
    protected $options;

    /**
      * Constructor
      *
      * @param AccountRepository $accountRepository - Account Repository
      *
      * @return void
      *-----------------------------------------------------------------------*/

    function __construct(
        )
    {
        $this->options = new \Iyzipay\Options();

        if (configItem('env_settings.iyzipay_test_mode') == true) {

            $setApiKey = getStoreSettings('iyzipay_testing_key');
            $setSecretKey = getStoreSettings('iyzipay_testing_secret_key');
            $baseUrl = config('__tech.iyzipay_urls.sandbox');

        } else {
            
            $setApiKey = getStoreSettings('iyzipay_live_key');
            $setSecretKey = getStoreSettings('iyzipay_live_secret_key');
            $baseUrl = config('__tech.iyzipay_urls.production');
        }

        $this->options->setApiKey($setApiKey);
        $this->options->setSecretKey($setSecretKey);
        $this->options->setBaseUrl($baseUrl);

        /*$this->options->setApiKey("sandbox-eTlcFaWcYRmk0ryS5wJfHoqDbDMjJ7J6");
        $this->options->setSecretKey("sandbox-b9i8rYUicVCJfLpLpzDdTFEWQgHmlhWG");
        $this->options->setBaseUrl("https://sandbox-api.iyzipay.com");*/
    }

    /**
      * Request Payment
      *
      * @return json object
      *---------------------------------------------------------------- */
    public function requestPayment($data, $cardData)
    {
        if($data and is_array($data)) {
            return $this->processPayment($data, $cardData);
        }

        return false;
    }

    /**
      * process the payment
      *
      * @return json object
      *---------------------------------------------------------------- */
    protected function processPayment($data, $cardData)
    {  
        $request = new \Iyzipay\Request\CreatePaymentRequest();

            $request->setLocale(\Iyzipay\Model\Locale::EN);
            $request->setConversationId($data['conversation_id']);
            $request->setPrice($data['amount']);
            $request->setPaidPrice($data['amount']);
            $request->setCurrency($data['currency_code']);
            $request->setInstallment(1);
            $request->setBasketId($data['conversation_id']);
            
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        $request->setCallbackUrl(route('order.iyzipay.payment_process', ['encryptOrderID' => $data['order_id']
        ]));
       
        $paymentCard = new \Iyzipay\Model\PaymentCard();

            $paymentCard->setCardHolderName($cardData['name_on_card']);
            $paymentCard->setCardNumber($cardData['card_number']);
            $paymentCard->setExpireMonth($cardData['exp_month']);
            $paymentCard->setExpireYear($cardData['exp_year']);
            $paymentCard->setCvc($cardData['cvv']);
            $paymentCard->setRegisterCard(0);
            $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId($data['users__id']);
            $buyer->setName($data['name']);
            $buyer->setSurname($data['name']);
            // $buyer->setGsmNumber("+905350000000");
            $buyer->setEmail($data['email']);
            $buyer->setIdentityNumber($data['users__id']);
            // $buyer->setLastLoginDate("2015-10-05 12:43:35");
            // $buyer->setRegistrationDate("2013-04-21 15:12:09");
            $buyer->setRegistrationAddress($data['address']);
            $buyer->setIp(Request::ip());
            $buyer->setCity($data['city']);
            $buyer->setCountry($data['country']);
            // $buyer->setZipCode("34732");
            $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName($cardData['name_on_card']);
            $shippingAddress->setCity($data['city']);
            $shippingAddress->setCountry($data['city']);
            $shippingAddress->setAddress($data['address']);
            // $shippingAddress->setZipCode("34742");
            $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName($cardData['name_on_card']);
            $billingAddress->setCity($data['city']);
            $billingAddress->setCountry($data['city']);
            $billingAddress->setAddress($data['address']);
            // $billingAddress->setZipCode("34742");
            $request->setBillingAddress($billingAddress);

        $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId($data['item_basket_id']);
            $firstBasketItem->setName($data['subscription_plan']);
            $firstBasketItem->setCategory1($data['subscription_plan_type']);
            //$firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
            $firstBasketItem->setPrice($data['amount']);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);

        //$payment = \Iyzipay\Model\Payment::create($request, $this->options);
            
        $payment = \Iyzipay\Model\ThreedsInitialize::create($request, $this->options);
        return $payment;
    }

    /**
      * Process Threeds payment
      *
      * @return json object
      *---------------------------------------------------------------- */
    public function processThreedsPayment($inputData)
    {
        $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId($inputData['conversationId']);
        $request->setPaymentId($inputData['paymentId']);
        $request->setConversationData($inputData['conversationData']);

        $threedsPayment = \Iyzipay\Model\ThreedsPayment::create($request, $this->options);

        return $threedsPayment;
    }
}