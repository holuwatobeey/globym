<?php
/*
* ManageStoreEngine.php - Main component file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store;

use App\Yantrana\Components\Store\Repositories\ManageStoreRepository;
use App\Yantrana\Components\Store\Blueprints\ManageStoreEngineBlueprint;
use App\Yantrana\Components\Media\MediaEngine;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use NativeSession;

class ManageStoreEngine implements ManageStoreEngineBlueprint
{
    /**
     * @var ManageStoreRepository - ManageStore Repository
     */
    protected $manageStoreRepository;

    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var - Category Repository
     */
    protected $manageCategoryRepository;

    /**
     * Constructor.
     *
     * @param ManageStoreRepository $manageStoreRepository - ManageStore Repository
     * @param MediaEngine           $mediaEngine           - Media Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreRepository $manageStoreRepository,
     MediaEngine $mediaEngine, ManageProductRepository $manageProductRepository, BrandRepository $brandRepository, ProductRepository $productRepository, ManageCategoryRepository $manageCategoryRepository)
    {
        $this->manageStoreRepository = $manageStoreRepository;
        $this->mediaEngine = $mediaEngine;
        $this->manageProductRepository = $manageProductRepository;
        $this->brandRepository   = $brandRepository;
        $this->productRepository = $productRepository;
        $this->manageCategoryRepository = $manageCategoryRepository;
    }

    /**
     * get require data for form.
     *
     * @param string $data
     * @param string $string
     *---------------------------------------------------------------- */
    protected function checkIsEmpty($data, $string)
    {
        return isset($data[$string]) ? $data[$string] : '';
    }

    /**
     * check the data is true or false.
     *
     *  @param array $data
     *  @param string $string
     *---------------------------------------------------------------- */
    protected function checkIsValid($data, $string)
    {
        return isset($data[$string]) ? $data[$string] : true;
    }

    /**
     * check the data is true or false.
     *
     *  @param array $data
     *  @param string $string
     *---------------------------------------------------------------- */
    protected function makeItBool($data, $string)
    {
        if (__ifIsset($data[$string])) {
            return (bool) $data[$string];
        }

        return false;
    }

  /**
    * Set type of data
    *
    * @param number number $datTypeId
    * @param sting number $value
    *
    * @return void
    *-----------------------------------------------------------------------*/

    
    public function getDataType($datTypeId, $value)
    {
        switch ($datTypeId) {
            case 1:
                return (string) $value;
                break;
            case 2:
                return (bool) $value;
                break;
            case 3:
                return (int) $value;
                break;
            case 4:
                return json_decode($value, true);
                break;
            case 5:
                return (float) $value;
                break;
            default:
                return $value;
        }
    }

    /**
     * cast each and every value of configuration table.
     *
     * @param array $dataType
     *
     *---------------------------------------------------------------- */
    public function castValue($dataArray)
    {
        $configArray = [];

        $configurationNames = config('__tech.settings.fields');

        foreach ($dataArray as $key => $data) {
            $datTypeId = $configurationNames[$key]['data_type'];

            $configArray[$key] = $this->getDataType($datTypeId, $data);
        }
        return $configArray;
    }

    /**
     * prepare data for configuration setting if data not exist then it will get from
     * config settings
     *
     *  @param array $selectedData
     *  @param array $dbArray
     *
     *  @param string $string
     *---------------------------------------------------------------- */
    protected function prepareConfigurationData($selectedData, $dbArray)
    {
        $configArray = config('__tech.settings.fields');
        
        if (!__isEmpty($selectedData)) {
            foreach ($selectedData as $key => $selectValue) {
                if (array_key_exists($selectValue, $dbArray) == false) {
                    $dbArray[$selectValue] =  $configArray[$selectValue]['default'];
                }
            }
        }

        // Get casting value
        $settingData = $this->castValue($dbArray);
        $settingArray = [];

        if (!__isEmpty($settingData)) {
            foreach ($settingData as $key => $setting) {
                if (array_key_exists('placeholder', $configArray[$key])) {
                    $settingArray[$key.'_placeholder'] = $configArray[$key]['placeholder'];
                }

                $settingArray[$key] = $setting;
            }
        }
        
        return $settingArray;
    }

    /**
     * get only requested data on form
     *
     *  @param string $requestType
     *
     *  @param string $string
     *---------------------------------------------------------------- */
    protected function getRequestedData($selectedData, $requestType)
    {	
        $configurationCollection = $this->manageStoreRepository->fetch();

        $configurationArray = $configurationData = [];

        $isLivePaypalKeysExist = false;
        $isTestingPaypalKeysExist = false;
        $isLiveStripeKeysExist = false;
        $isTestingStripeKeysExist = false;
        $isLiveRazorPayKeysExist = false;
        $isTestingRazorPayKeysExist = false;
        $isLiveIyzipayKeysExist = false;
        $isTestingIyzipayKeysExist = false;
        $isLivePaytmKeysExist = false;
        $isTestingPaytmKeysExist = false;
        $isLiveInstamojoKeysExist = false;
        $isTestingInstamojoKeysExist = false;
        $isLivePayStackKeysExist = false;
        $isTestingPayStackKeysExist = false;
        $isRecaptchaKeyExist = false;

        if (!__isEmpty($configurationCollection)) {
            foreach ($configurationCollection as $configuration) {
                $name   = $configuration->name;
                $value  = $configuration->value;

                // Get logo image URL
                if ($name == 'logo_image') {
                    $configurationData['logoURL'] = getStoreSettings('logo_image_url');
                }

                 // Get logo image URL
                if ($name == 'favicon_image') {
                    $configurationData['faviconURL'] = getStoreSettings('favicon_image');
                }

                // when $selectedData is empty then get all configuration
                if (__isEmpty($selectedData)) {
                    $configurationArray[$name] = $value;
                } else {

                    // when $selectedData is not empty get selected configuration
                    if (in_array($name, $selectedData)) {
                        $configurationArray[$name] = $value;
                    }
                }
            }

            if ($requestType == 7) { // User Setting
                 // Check if stripe keys are exist
                if (isset($configurationArray['recaptcha_site_key'])
                    and !__isEmpty($configurationArray['recaptcha_secret_key'])) {
                    $isRecaptchaKeyExist = true;
                }
            }

            if ($requestType == 13) { // Payment
                
                // Check if paypal keys are exist
                if (isset($configurationArray['paypal_sandbox_email'])
                    and !__isEmpty($configurationArray['paypal_sandbox_email'])) {
                    $isTestingPaypalKeysExist = true;
                }

                // Check if paypal keys are exist
                if (isset($configurationArray['paypal_email'])
                    and !__isEmpty($configurationArray['paypal_email'])) {
                    $isLivePaypalKeysExist = true;
                }

                // Check if stripe keys are exist
                if (isset($configurationArray['stripe_live_secret_key'])
                    and !__isEmpty($configurationArray['stripe_live_secret_key'])
                    and isset($configurationArray['stripe_live_publishable_key'])
                    and !__isEmpty($configurationArray['stripe_live_publishable_key'])) {
                    $isLiveStripeKeysExist = true;
                }

                // Check if stripe keys are exist
                if (isset($configurationArray['stripe_testing_secret_key'])
                    and !__isEmpty($configurationArray['stripe_testing_secret_key'])
                    and isset($configurationArray['stripe_testing_publishable_key'])
                    and !__isEmpty($configurationArray['stripe_testing_publishable_key'])) {
                    $isTestingStripeKeysExist = true;
                }

                // Check if razorpay keys are exist
                if (isset($configurationArray['razorpay_live_key'])
                    and !__isEmpty($configurationArray['razorpay_live_key'])
                    and isset($configurationArray['razorpay_live_secret_key'])
                    and !__isEmpty($configurationArray['razorpay_live_secret_key'])) {
                    $isLiveRazorPayKeysExist = true;
                }

                // Check if razorpay keys are exist
                if (isset($configurationArray['razorpay_testing_key'])
                    and !__isEmpty($configurationArray['razorpay_testing_key'])
                    and isset($configurationArray['razorpay_testing_secret_key'])
                    and !__isEmpty($configurationArray['razorpay_testing_secret_key'])) {
                    $isTestingRazorPayKeysExist = true;
                }

                // Check if iyzico keys are exist
                if (isset($configurationArray['iyzipay_live_key'])
                    and !__isEmpty($configurationArray['iyzipay_live_key'])
                    and isset($configurationArray['iyzipay_live_secret_key'])
                    and !__isEmpty($configurationArray['iyzipay_live_secret_key'])) {
                    $isLiveIyzipayKeysExist = true;
                }

                // Check if iyzico keys are exist
                if (isset($configurationArray['iyzipay_testing_key'])
                    and !__isEmpty($configurationArray['iyzipay_testing_key'])
                    and isset($configurationArray['iyzipay_testing_secret_key'])
                    and !__isEmpty($configurationArray['iyzipay_testing_secret_key'])) {
                    $isTestingIyzipayKeysExist = true;
                }

                // Check if paytm keys are exist
                if (isset($configurationArray['paytm_live_merchant_key'])
                    and !__isEmpty($configurationArray['paytm_live_merchant_key'])
                    and isset($configurationArray['paytm_live_merchant_mid_key'])
                    and !__isEmpty($configurationArray['paytm_live_merchant_mid_key'])) {
                    $isLivePaytmKeysExist = true;
                }

                // Check if paytm keys are exist
                if (isset($configurationArray['paytm_testing_merchant_key'])
                    and !__isEmpty($configurationArray['paytm_testing_merchant_key'])
                    and isset($configurationArray['paytm_testing_merchant_mid_key'])
                    and !__isEmpty($configurationArray['paytm_testing_merchant_mid_key'])) {
                    $isTestingPaytmKeysExist = true;
                }

                // Check if instamojo keys are exist
                if (isset($configurationArray['instamojo_live_api_key'])
                    and !__isEmpty($configurationArray['instamojo_live_api_key'])
                    and isset($configurationArray['instamojo_live_auth_token_key'])
                    and !__isEmpty($configurationArray['instamojo_live_auth_token_key'])) {
                    $isLiveInstamojoKeysExist = true;
                }

                // Check if instamojo keys are exist
                if (isset($configurationArray['instamojo_testing_api_key'])
                    and !__isEmpty($configurationArray['instamojo_testing_api_key'])
                    and isset($configurationArray['instamojo_testing_auth_token_key'])
                    and !__isEmpty($configurationArray['instamojo_testing_auth_token_key'])) {
                    $isTestingInstamojoKeysExist = true;
                }

                // Check if Payu Money keys are exist
                if (isset($configurationArray['payStack_live_secret_key'])
                    and !__isEmpty($configurationArray['payStack_live_secret_key'])
                    and isset($configurationArray['payStack_live_public_key'])
                    and !__isEmpty($configurationArray['payStack_live_public_key'])) {
                    $isLivePayStackKeysExist = true;
                }

                // Check if Payu Money keys are exist
                if (isset($configurationArray['payStack_testing_secret_key'])
                    and !__isEmpty($configurationArray['payStack_testing_secret_key'])
                    and isset($configurationArray['payStack_testing_public_key'])
                    and !__isEmpty($configurationArray['payStack_testing_public_key'])) {
                    $isTestingPayStackKeysExist = true;
                }
            }
        }

        $configurationArray = $this->prepareConfigurationData(
                                                        $selectedData,
                                                        $configurationArray
                                                    );
       
        // Check page type and get data as per request type
        if ($requestType == 1) { // general

            $configurationArray['timezone_list']       = getTimeZone();
           
            $configurationArray['faviconURL']       = __ifIsset($configurationData['faviconURL']) ? $configurationData['faviconURL'] : '';

            $configurationArray['logoURL']            = __ifIsset($configurationData['logoURL']) ? $configurationData['logoURL'] : '';

            $configurationArray['locale_list']  = config('locale.available');

            $themeColors = config('__tech.theme_colors');
            $configurationArray['theme_colors']  = array_add($themeColors, 'default', 'b9002f');
           
        } elseif ($requestType == 2) { // currency

            $configurationArray['currencies'] = configItem('currencies');
            $configurationArray['autoRefreshList'] = configItem('currency_auto_refresh_value');
            $configurationArray['paymentOptionList'] = getStoreSettings('select_payment_option');

            $configurationArray['default_currency_format']
                                = configItem('settings.fields.currency_format.default');
        }                        
        elseif ($requestType == 3) { // currency

            $configurationArray['order_statuses'] = configItem('orders.status_codes', [1, 2, 4, 5, 7]);
       
        } elseif ($requestType == 7) { // user

           // if Live stripe key exist then remove from array
            if ($isRecaptchaKeyExist) {
                unset($configurationArray['recaptcha_site_key']);
                unset($configurationArray['recaptcha_secret_key']);
            }

            $configurationArray['isRecaptchaKeyExist'] = $isRecaptchaKeyExist;
       
        } elseif ($requestType == 13) { //Payment

            // if Live paypal email exist then remove from array
            if ($isLivePaypalKeysExist) {
                unset($configurationArray['paypal_email']);
            }

            // if Testing paypal sandbox email exist then remove from array
            if ($isTestingPaypalKeysExist) {
                unset($configurationArray['paypal_sandbox_email']);
            }

            // if Live stripe key exist then remove from array
            if ($isLiveStripeKeysExist) {
                unset($configurationArray['stripe_live_secret_key']);
                unset($configurationArray['stripe_live_publishable_key']);
            }

            // if Live stripe key exist then remove from array
            if ($isTestingStripeKeysExist) {
                unset($configurationArray['stripe_testing_secret_key']);
                unset($configurationArray['stripe_testing_publishable_key']);
            }

            // if Live razorpay key exist then remove from array
            if ($isLiveRazorPayKeysExist) {
                unset($configurationArray['razorpay_live_key']);
                unset($configurationArray['razorpay_live_secret_key']);
            }

            // if Live razorpay key exist then remove from array
            if ($isTestingRazorPayKeysExist) {
                unset($configurationArray['razorpay_testing_key']);
                unset($configurationArray['razorpay_testing_secret_key']);
            }

            // if Live iyzipay key exist then remove from array
            if ($isLiveIyzipayKeysExist) {
                unset($configurationArray['iyzipay_live_key']);
                unset($configurationArray['iyzipay_live_secret_key']);
            }

            // if Live iyzipay key exist then remove from array
            if ($isTestingIyzipayKeysExist) {
                unset($configurationArray['iyzipay_testing_key']);
                unset($configurationArray['iyzipay_testing_secret_key']);
            }

            // if Live paytm key exist then remove from array
            if ($isLivePaytmKeysExist) {
                unset($configurationArray['paytm_live_merchant_key']);
                unset($configurationArray['paytm_live_merchant_mid_key']);
            }

            // if Testing paytm key exist then remove from array
            if ($isTestingPaytmKeysExist) {
                unset($configurationArray['paytm_testing_merchant_key']);
                unset($configurationArray['paytm_testing_merchant_mid_key']);
            }

            // if Live Instamojo key exist then remove from array
            if ($isLiveInstamojoKeysExist) {
                unset($configurationArray['instamojo_live_api_key']);
                unset($configurationArray['instamojo_live_auth_token_key']);
            }

            // if Testing Instamojo key exist then remove from array
            if ($isTestingInstamojoKeysExist) {
                unset($configurationArray['instamojo_testing_api_key']);
                unset($configurationArray['instamojo_testing_auth_token_key']);
            }

            // if Live PayuMoney key exist then remove from array
            if ($isLivePayStackKeysExist) {
                unset($configurationArray['payuMoney_live_pos_id']);
                unset($configurationArray['payuMoney_live_second_key']);
            }

            // if Testing PayuMoney key exist then remove from array
            if ($isTestingPayStackKeysExist) {
                unset($configurationArray['payStack_live_secret_key']);
                unset($configurationArray['payStack_live_public_key']);
            }

            $configurationArray['isLivePaypalKeysExist'] = $isLivePaypalKeysExist;
            $configurationArray['isTestingPaypalKeysExist'] = $isTestingPaypalKeysExist;
            $configurationArray['isLiveStripeKeysExist'] = $isLiveStripeKeysExist;
            $configurationArray['isTestingStripeKeysExist'] = $isTestingStripeKeysExist;
            $configurationArray['isLiveRazorPayKeysExist'] = $isLiveRazorPayKeysExist;
            $configurationArray['isTestingRazorPayKeysExist'] = $isTestingRazorPayKeysExist;
            $configurationArray['isLiveIyzipayKeysExist'] = $isLiveIyzipayKeysExist;
            $configurationArray['isTestingIyzipayKeysExist'] = $isTestingIyzipayKeysExist;
            $configurationArray['isLivePaytmKeysExist'] = $isLivePaytmKeysExist;
            $configurationArray['isTestingPaytmKeysExist'] = $isTestingPaytmKeysExist;
            $configurationArray['isLiveInstamojoKeysExist'] = $isLiveInstamojoKeysExist;
            $configurationArray['isTestingInstamojoKeysExist'] = $isTestingInstamojoKeysExist;
            $configurationArray['isLivePayStackKeysExist'] = $isLivePayStackKeysExist;
            $configurationArray['isTestingPayStackKeysExist'] = $isTestingPayStackKeysExist;
           
            $configurationArray['paymentOptions'] = configItem('orders.payment_methods', [1, 2, 3, 4, 5, 6, 11, 14, 16, 18, 20]);
          
        } elseif ($requestType == 5) { // placement

            $configurationArray['menu_placement'] = configItem('menu_placement');
        } else if($requestType == 11) { // social authentication

			if (!__isEmpty($configurationArray['facebook_client_id'])) {
                $configurationArray['isFacebookKeyExist'] = true;
                unset($configurationArray['facebook_client_id']);
                unset($configurationArray['facebook_client_secret']);
            }

            if (!__isEmpty($configurationArray['google_client_id'])) {
                $configurationArray['isGoogleKeyExist'] = true;
                unset($configurationArray['google_client_id']);
                unset($configurationArray['google_client_secret']);
            }

            if (!__isEmpty($configurationArray['twitter_client_id'])) {
                $configurationArray['isTwitterKeyExist'] = true;
                unset($configurationArray['twitter_client_id']);
                unset($configurationArray['twitter_client_secret']);
            }

            if (!__isEmpty($configurationArray['github_client_id'])) {
                $configurationArray['isGithubKeyExist'] = true;
                unset($configurationArray['github_client_id']);
                unset($configurationArray['github_client_secret']);
            }
            
		} elseif ($requestType == 15) {
            
            $alldrivers = configItem('mail_drivers');
            $mail_drivers = [];
            foreach ($alldrivers as $driver) {
                $mail_drivers[] = [
                    'id' => $driver['id'],
                    'name' => $driver['name'],
                ];  
            }

            $configurationArray['mail_drivers'] = $mail_drivers;
            $configurationArray['mail_encryption_types'] = configItem('mail_encryption_types');
        }

        return $configurationArray;
    }

    /**
      * get the edit support data
      *
      * @param string $requestType
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function prepareSettingsEditSupportData($requestType)
    { 
        $selectedData = [];
    
        // set required keys
        if ($requestType == 1) { // general

            $selectedData = [
                                'store_name',
                                'logo_image',
                                'favicon_image',
                                'invoice_image',
                                'logo_background_color',
                                'timezone',
                                'business_email',
                                'show_language_menu',
                                'default_language'
                            ];
        } elseif ($requestType == 2) { // currency

            $selectedData = [
                                'currency',
                                'currency_symbol',
                                'currency_value',
                                'currency_format',
                                'round_zero_decimal_currency',
                                'display_multi_currency',
                                'multi_currencies',
                                'auto_refresh_in',
                                'currency_markup'
                            ];
        } elseif ($requestType == 3) { // order & payments
            
            $selectedData = [
                                'enable_guest_order',
                                'apply_tax_after_before_discount',
                                'calculate_tax_as_per_shipping_billing',
                                'allow_customer_order_cancellation',
                                'order_cancellation_statuses'
                            ];
        } elseif ($requestType == 4) { // product
            
            $selectedData = [
                                'show_out_of_stock',
                                'pagination_count',
                                'item_load_type',
								'enable_rating',
                                'enable_rating_review',
                                'restrict_add_rating_to_item_purchased_users',
                                'enable_rating_modification',
                                'enable_user_add_questions',
                                'facebook',
                                'twitter',
                                'enable_whatsapp',
                                'enable_staticaly_cdn'
                            ];

        } elseif ($requestType == 5) { // placement
            
            $selectedData = [
                                'categories_menu_placement',
                                'brand_menu_placement',
                                'credit_info',
                                'addtional_page_end_content',
                                'global_notification'
                            ];
        } elseif ($requestType == 6) { // contact
            
            $selectedData = [
                                'contact_email',
                                'contact_address'
                            ];
        } elseif ($requestType == 7) { // termsAndConditions or users tab
            
            $selectedData = [
                                'register_new_user',
                                'activation_required_for_new_user',
                                'activation_required_for_change_email',
                                'enable_wishlist',
                                'enable_recaptcha',
                                'recaptcha_site_key',
                                'recaptcha_secret_key',
                                'term_condition',
                                'show_captcha'
                        ];
        } elseif ($requestType == 8) { // privacy policy

            $selectedData = [
                                'privacy_policy'
                            ];
        } elseif ($requestType == 9) { // css style

          $selectedData = [
                    'custom_css'
                  ];
        } elseif ($requestType == 10) { // payment

            $selectedData = [
                                'social_facebook',
                                'social_twitter',
                            ];
        } elseif ($requestType == 11) { // social authentication

            $selectedData = [
                            'facebook_client_id',
                            'facebook_client_secret',
                            'allow_facebook_login',

                            'google_client_id',
                            'google_client_secret',
                            'allow_google_login',

                            'twitter_client_id',
                            'twitter_client_secret',
                            'allow_twitter_login',
                            
                            'github_client_id',
                            'github_client_secret',
                            'allow_github_login'
                        ];;
        } elseif ($requestType == 13) { // payments
            
            $selectedData = [
                            'select_payment_option',
                            'payment_other',
                            'payment_other_text',
                            'use_paypal',
                            'paypal_email',
                            'paypal_sandbox_email',
                            'use_stripe',
                            'stripe_live_secret_key',
                            'stripe_live_publishable_key',
                            'stripe_testing_secret_key',
                            'stripe_testing_publishable_key',
                            'use_razorpay',
                            'razorpay_live_key',
                            'razorpay_live_secret_key',
                            'razorpay_testing_key',
                            'razorpay_testing_secret_key',
                            'use_iyzipay',
                            'iyzipay_live_key',
                            'iyzipay_live_secret_key',
                            'iyzipay_testing_key',
                            'iyzipay_testing_secret_key',
                            'use_paytm',
                            'paytm_live_merchant_key',
                            'paytm_live_merchant_mid_key',
                            'paytm_testing_merchant_key',
                            'paytm_testing_merchant_mid_key',
                            'use_instamojo',
                            'instamojo_live_api_key',
                            'instamojo_live_auth_token_key',
                            'instamojo_testing_api_key',
                            'instamojo_testing_auth_token_key',
                            'use_payStack',
                            'payStack_testing_secret_key',
                            'payStack_testing_public_key',
                            'payStack_live_secret_key',
                            'payStack_live_public_key',
                            'payment_check',
                            'payment_check_text',
                            'payment_bank',
                            'payment_bank_text',
                            'payment_cod',
                            'payment_cod_text'
                        ];
        } elseif ($requestType == 14) { // email settings

            $selectedData = [
                            'use_env_default_email_settings',
                            'mail_driver',
                            'mail_from_address',
                            'mail_from_name',

                            'mailgun_domain',
                            'mailgun_mail_password_or_apikey',
                            'mailgun_endpoint',

                            'smtp_mail_port',
                            'smtp_mail_host',
                            'smtp_mail_username',
                            'smtp_mail_encryption',
                            'smtp_mail_password_or_apikey',
                            'sparkpost_mail_password_or_apikey',
                        ];

        } elseif ($requestType == 15) { // email settings

            $selectedData = [
                            'use_env_default_email_settings',
                            'mail_driver',
                            'mail_from_address',
                            'mail_from_name',

                            'mailgun_domain',
                            'mailgun_mail_password_or_apikey',
                            'mailgun_endpoint',

                            'smtp_mail_port',
                            'smtp_mail_host',
                            'smtp_mail_username',
                            'smtp_mail_encryption',
                            'smtp_mail_password_or_apikey',
                            'sparkpost_mail_password_or_apikey',

                            'append_email_message'
                        ];

        }

        return __engineReaction(1, [
            'store_settings' => $this->getRequestedData($selectedData, $requestType)
        ]);
    }

    

    /**
     * Process edit store settings.
     *
     * @param array $inputs
     * @param int $requestType
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditStoreSettings($inputs, $requestType)
    {
        $reactionCode =  $this->manageStoreRepository
                             ->processTransaction(function () use ($inputs, $requestType) {

            // fetch all configuration array
            $configurations = $this->manageStoreRepository->fetch();

            $logoImage = __ifIsset($inputs['logo_image'], $inputs['logo_image'], '');
            $faviconImage = __ifIsset($inputs['favicon_image'],$inputs['favicon_image'],'');

            $landingPageImage = __ifIsset($inputs['landing_page_image'])
                                ? $inputs['landing_page_image']
                                : '';

            $logoUpdated = false;
            $faviconUpdated = false;

            // Check if logo exist Then Process This Logo Image
            if (!__isEmpty($logoImage)) {

                // Store Logo image
                $newLogoImage = $this->mediaEngine->processStoreSettingLogoMedia($logoImage);

                if (!__isEmpty($newLogoImage)) {
                    $inputs['logo_image'] = $newLogoImage;

                    $logoUpdated = true;
                }
            }

            // If logo not exist then remove logo image from input data
            if ($logoUpdated == false) {
                $inputs = array_except($inputs, 'logo_image');
            }

            // Check if favicon exist Then Process This Logo Image
            if (!__isEmpty($faviconImage)) {

                // Store Logo image
                $newFaviconImage = $this->mediaEngine
                ->processFaviconMedia($faviconImage, 'favicon_image');

                if (!__isEmpty($newFaviconImage)) {
                    $inputs['favicon_image'] = $newFaviconImage;

                    $faviconUpdated = true;
                }
            }

            $invoiceImage = __ifIsset($inputs['invoice_image'], $inputs['invoice_image'], '');

            $invoiceUpdated = false;

            if (!__isEmpty($invoiceImage)) {
                // Store Invoice image
                $newInvoiceImage = $this->mediaEngine->processStoreSettingInvoiceMedia($invoiceImage);

                if (!__isEmpty($newInvoiceImage)) {
                    $inputs['invoice_image'] = $newInvoiceImage;

                    $invoiceUpdated = true;
                }
            }

            // If invoice image not exist then remove invoice image from input data
            if ($invoiceUpdated == false) {
                $inputs = array_except($inputs, 'invoice_image');
            }

            // If favicon not exist then remove logo image from input data
            if ($faviconUpdated == false) {
                $inputs = array_except($inputs, 'favicon_image');
            }

            if ($requestType == 6) { // Contact
                $inputs['contact_email'] = strtolower($inputs['contact_email']);
            }

            if ($requestType == 1) { // General
                $inputs['business_email'] = strtolower($inputs['business_email']);
            }

            if ($requestType == 13) {

                $paymentOptionId = [];
                if (isset($inputs['select_payment_option']) and (!__isEmpty($inputs['select_payment_option']))) {
                    $paymentOptionId = $inputs['select_payment_option'];
     
                    $paymentMethodOption = configItem('payment_options');
	            	foreach ($paymentMethodOption as $key => $opt) {
	            		if (in_array($opt, $inputs['select_payment_option']) and (isset($inputs[$key]) and $inputs[$key] == true)) {
	            			$inputs[$key] = true;
	            		} else {
	            			$inputs[$key] = false;
	            		}
	            	}
                }
                

                $inputs['select_payment_option'] = json_encode($paymentOptionId);
            }

            if ($requestType == 3) { // Order and Placement
               /* $inputs['order_cancellation_statuses'] = json_encode($inputs['order_cancellation_statuses']);*/

                if ($inputs['allow_customer_order_cancellation'] == true) {
                    $orderCancellationStatuses = '{}';

                    if (isset($inputs['order_cancellation_statuses'])
                        and (!__isEmpty($inputs['order_cancellation_statuses']))) {
                        $orderCancellationStatuses = $inputs['order_cancellation_statuses'];
                    }

                    $inputs['order_cancellation_statuses'] = json_encode($orderCancellationStatuses);

                } elseif ($inputs['allow_customer_order_cancellation'] == false) {
                    unset($inputs['order_cancellation_statuses']);
                }                            
            }
           
            if ($requestType == 2) { // Currency

				// $currecnyInputs = ['multi_currencies', 'currency_markup'];

                if ($inputs['display_multi_currency'] == true) {

                    $selectedCurrency = getSelectedCurrency();
					
                    if (!in_array($selectedCurrency, $inputs['multi_currencies'])) {
                        NativeSession::removeIfHas('currency');
                    }

                    $inputs['multi_currencies'] = json_encode($inputs['multi_currencies']);

                    $inputs['auto_refresh_in'] = $inputs['auto_refresh_in'];

                } else if($inputs['display_multi_currency'] == false) {
					
					unset($inputs['multi_currencies']);
					unset($inputs['currency_markup']);

				}
            }
           
            $showRealodButton = false;

            $reloadRequiredItems = [
                'logo_image',
                'favicon_image',
                'invoice_image',
                'logo_background_color',
                'credit_info',
                'addtional_page_end_content',
                'custom_css',
                'global_notification',
                'display_multi_currency',
                'multi_currencies'
            ];

            foreach ($reloadRequiredItems as $key => $item) {
                if (array_has($inputs, $item)
                    and (!__isEmpty($inputs[$item]))) {
                    $showRealodButton = true;
                }
            }

            // Check if store configuration is empty
            if (__isEmpty($configurations)) {
                if ($this->manageStoreRepository->addSettings($inputs)) {
                    return $this->manageStoreRepository->transactionResponse(1, [
                            'message' => __tr('Settings updated successfully.'),
                            'textMessage' => __tr('To take a effects of updated setting, please reload the page..'),
                            'showRealodButton' => $showRealodButton
                    ]);
                }

                return $this->manageStoreRepository->transactionResponse(14, null, __tr('Nothing updated.'));
            }
         
            // if the item already available in database
            $configurationUpdated = $this->manageStoreRepository
                                         ->updateSettings($configurations, $inputs);

            // check if the configuration object updated
            if ($configurationUpdated or $logoUpdated or $faviconUpdated or $invoiceUpdated) {
                return $this->manageStoreRepository->transactionResponse(1, [
                            'message' => __tr('Settings updated successfully.'),
                            'textMessage' => __tr('To take a effects of updated setting, please reload the page.'),
                            'showRealodButton' => $showRealodButton
                    ]);
            }

            return $this->manageStoreRepository->transactionResponse(14, null, __tr('Nothing updated.'));
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare store settings.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareStoreSettings()
    {
        return $this->manageStoreRepository->fetchSettings();
    }

    /**
      * get the edit support data
      *
      * @param string $prepareEmailTemplateSupportData
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function prepareEmailTemplateSupportData()
    {		
    	$emailTemplateData = configItem('email_template_view');

    	$emailTemplate = [];
    	foreach ($emailTemplateData as $key => $template) {
            if ($key != 'ordered_products_mail') {
                $emailTemplate[] = [
                    'id'    => $key,
                    'title' => $template['title'],
                    'templateName' => 'dynamic-mail-templates.'.$template['template'],
                ];
            }
    		
    	}

    	return __engineReaction(1, [
            'emailTemplateData' => $emailTemplate,
        ]);
    }

    /**
      * get the edit landing page support data
      *
      * @param string $prepareEditLandingPageSupportData
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function prepareEditLandingPageData()
    {     

        $sliderDataCollection = getStoreSettings('home_slider');

        $sliderList = [];
        if (!__isEmpty($sliderDataCollection)) {
            foreach ($sliderDataCollection['sliderData'] as $key => $slider) {
                $sliderList[] = [
                    'id' => $key,
                    'title' => $slider['title']
                ];
            }
        }

        $productList = [];
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $allActiveCategories = findActiveChildren($categoryCollection);
        $productData = $this->productRepository->fetchActiveProducts($allActiveCategories, $inactiveBrandIds);
        // Check if product data exist
        if (!__isEmpty($productData)) {
            foreach ($productData as $key => $product) {
                $productList[] = [
                    'id' => $product->id,
                    'name' => $product->name
                ];
            }
        }
 
        // Collect slider data list for edit landing page
        $landingPageCollection = getStoreSettings('landing_page');
        $landingPageData = $landingPageEditData = [];
        if (!__isEmpty($landingPageCollection)) {
            $landingPageData = $landingPageCollection['landingPageData'];
            foreach ($landingPageData as $key => $landing) {
                if ($landing['identity'] == 'bannerContent1') {
                    $landingPageData[$key]['banner_1_section_1_image_thumb']    = getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_1_image']);
                    $landingPageData[$key]['banner_1_section_2_image_thumb']    = getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_2_image']);
                    $landingPageData[$key]['banner_1_section_3_image_thumb']    = getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_3_image']);
                } else if ($landing['identity'] == 'bannerContent2') {
                    $landingPageData[$key]['banner_2_section_1_image_thumb'] = getHomePageContentThumbanilUrl('banner-2', $landing['banner_2_section_1_image']);
                    $landingPageData[$key]['banner_2_section_2_image_thumb']= getHomePageContentThumbanilUrl('banner-2', $landing['banner_2_section_2_image']);
                } 
            }
        }

        /*if (!__isEmpty($landingPageData)) {
            $landingPageEditData['landingPageData'] = re_index_array($landingPageData, 'orderIndex');
        } else {
            $landingPageEditData['landingPageData'] = configItem('landing_page_content');
        }*/

        $landingPageContent = configItem('landing_page_content');
        $contentCount = range(0, (count($landingPageContent)-1), 1);
        
      	if (__isEmpty($landingPageData)) {
            $landingPageData = $landingPageContent;
        } else {
        	$oldIndexes = array_column($landingPageData, 'orderIndex');
        	$orderIdentity = array_column($landingPageData, 'identity');
        	$excludedIndexes = array_diff($contentCount, $oldIndexes);

        	foreach ($landingPageContent as $key => $content) {
        		//check if in array
        		if (!in_array($content['identity'], $orderIdentity)) {
        			$content['orderIndex'] = array_shift($excludedIndexes);
        			$landingPageData[] = $content;
        		}
        	}

        	$newIndexes = array_column($landingPageData, 'orderIndex');
      		array_multisort($newIndexes, SORT_ASC, $landingPageData);
        }

        return __engineReaction(1, [
            'sliderList' => $sliderList,
            'productList' => $productList,
            'landingPageEditData'  => $landingPageData,
            'home_page_setting' => configItem('home_page_setting'),
            'home_page' => getStoreSettings('home_page')
        ]);

    }

    /**
      * process the edit or add config items
      *
      * @param array  $inputs
      * @param string $emailTemplateId
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function processLandingPageEditOrStore($inputData)
    {
        // fetch all configuration array
        $configuration = $this->manageStoreRepository->fetchByName('landing_page');

        $landingPageInsertData = $oldLandingPageData = [];

        if (!__isEmpty($configuration)) {
            $oldLandingPageData = json_decode($configuration->value, true);
        }

        $showRealodButton = false;

        $homePage = [
        	'home_page' => $inputData['home_page']
        ];

        // fetch all configuration array
        $configurations = $this->manageStoreRepository->fetch();
        // if the item already available in database
        $configurationUpdated = $this->manageStoreRepository->updateSettings($configurations, $homePage); 

        if (!__isEmpty($inputData)) {

            foreach ($inputData['landingPageData'] as $key => $input) {
                $homePageBannerData = array_get($oldLandingPageData, 'landingPageData', []);
                if ($input['identity'] == 'bannerContent1') {                    
                    if (!__isEmpty($input['banner_1_section_1_image'])) {
                        $fileName = $this->mediaEngine->storeHomePageContentMedia($input['banner_1_section_1_image'], 'banner-1', array_get($homePageBannerData, '5.banner_1_section_1_image'));
                        $inputData['landingPageData'][$key]['banner_1_section_1_image_thumb'] = '';
                    } else {
                        $inputData['landingPageData'][$key]['banner_1_section_1_image_thumb'] = '';
                        $inputData['landingPageData'][$key]['banner_1_section_1_image'] = array_get($homePageBannerData, '5.banner_1_section_1_image');
                    }

                    if (!__isEmpty($input['banner_1_section_2_image'])) {
                        $fileName = $this->mediaEngine->storeHomePageContentMedia($input['banner_1_section_2_image'], 'banner-1', array_get($homePageBannerData, '5.banner_1_section_2_image'));
                        $inputData['landingPageData'][$key]['banner_1_section_2_image_thumb'] = '';
                    } else {
                        $inputData['landingPageData'][$key]['banner_1_section_2_image_thumb'] = '';
                        $inputData['landingPageData'][$key]['banner_1_section_2_image'] = array_get($homePageBannerData, '5.banner_1_section_2_image');
                    }

                    if (!__isEmpty($input['banner_1_section_3_image'])) {
                        $fileName = $this->mediaEngine->storeHomePageContentMedia($input['banner_1_section_3_image'], 'banner-1', array_get($homePageBannerData, '5.banner_1_section_3_image'));
                        $inputData['landingPageData'][$key]['banner_1_section_3_image_thumb'] = '';
                    } else {
                        $inputData['landingPageData'][$key]['banner_1_section_3_image_thumb'] = '';
                        $inputData['landingPageData'][$key]['banner_1_section_3_image'] = array_get($homePageBannerData, '5.banner_1_section_3_image');
                    }
                } else if ($input['identity'] == 'bannerContent2') {
                    if (!__isEmpty($input['banner_2_section_1_image'])) {
                        $fileName = $this->mediaEngine->storeHomePageContentMedia($input['banner_2_section_1_image'], 'banner-2', array_get($homePageBannerData, '6.banner_2_section_1_image'));
                        $inputData['landingPageData'][$key]['banner_2_section_1_image_thumb'] = '';
                    } else {
                        $inputData['landingPageData'][$key]['banner_2_section_1_image_thumb'] = '';
                        $inputData['landingPageData'][$key]['banner_2_section_1_image'] = array_get($homePageBannerData, '6.banner_2_section_1_image');
                    }

                    if (!__isEmpty($input['banner_2_section_2_image'])) {
                        $fileName = $this->mediaEngine->storeHomePageContentMedia($input['banner_2_section_2_image'], 'banner-2', array_get($homePageBannerData, '6.banner_2_section_2_image'));
                        $inputData['landingPageData'][$key]['banner_2_section_2_image_thumb'] = '';
                    } else {
                        $inputData['landingPageData'][$key]['banner_2_section_2_image_thumb'] = '';
                        $inputData['landingPageData'][$key]['banner_2_section_2_image'] = array_get($homePageBannerData, '6.banner_2_section_2_image');
                    }
                }
            }

            $landingPageInsertData = $inputData;
        }

        if (!__isEmpty($configuration)) {
       
            if (!__isEmpty($oldLandingPageData)) {
                $landingPageCollection = array_merge($oldLandingPageData, $landingPageInsertData);
            } 
            else {
                $landingPageCollection =  $landingPageInsertData;
            }

            $updateData = $landingPageCollection;
  
            if ($this->manageStoreRepository->updateSliderSetting($configuration, $updateData)) {
                 $showRealodButton = true;
                return __engineReaction(1, [
                         'showRealodButton' => $showRealodButton
                    ], __tr('Home Page configuration updated successfully.')); // success
            }

        } else {        

            if ($this->manageStoreRepository->storeLandingPage($landingPageInsertData)) {
                $showRealodButton = true;
                return __engineReaction(1, [
                     'showRealodButton' => $showRealodButton
                ], __tr('Home Page configuration added successfully.')); // success
            }
        }

        return __engineReaction(14, null, __tr('Nothing updated')); // error
    }   

    /**
      * get the edit support data
      *
      * @param string $prepareEditTemplateSupportData
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function prepareEditFooterTemplateData()
    {     
        $configurations = $this->manageStoreRepository->fetchEmailTemplate('public_footer_template');

        $footerTemplateData = configItem('footer_template_view', 'public_footer_template');

        $templateDataExist = false;
        $footerTemplate = [];
        if (!__isEmpty($configurations)) {
            $templateDataExist = true;
            $configTemplateView =  $configurations->value;
            $footerTemplate = $configTemplateView;
            
        } else {
            $footerTemplateView = view('includes.public-footer', ['footerTemplate' => $footerTemplateData['template'] ])->render();
            $footerTemplate = $footerTemplateView;
        }

        //footer template data
        $footerTemplateData = [
            'id'                    => 'public_footer_template',
            'title'                 => $footerTemplateData['title'],
            'public_footer_template'=> $footerTemplate,
            'templateDataExist'     => $templateDataExist
        ];
       
        return __engineReaction(1, [
            'footerTemplateData' => $footerTemplateData,
        ]);
    }

     /**
      * process the edit or add config items
      *
      * @param array  $inputs
      * @param string $emailTemplateId
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function processFooterTemplateEditOrStore($input, $footerTemplateId)
    {   
        $footerTemplate = configItem('footer_template_view', $footerTemplateId);

        $configurations = $this->manageStoreRepository
                                ->fetchFooterTemplateData($footerTemplateId);

        $footerTemplateView = view('includes.public-footer', ['footerTemplate' => $footerTemplate['template'] ])->render();
      
        $templateUpdateData = [];
        $templateInsertData = [];
        $templateData = [];
        if (!__isEmpty($configurations)) {
            $templateData  = $configurations->toArray();
        }

        // $footerTemplate = strtr((string)$configTemplateView, $replaceStringData);
        //$footerTemplate = strtr((string)$input['public_footer_template'], $replaceStringData);
        $footerTemplate = $input['public_footer_template'];

        //insert or update email template view data
        if (!__isEmpty($templateData)) {
            $templateUpdateData[] = [ 
                'name' => $footerTemplateId,
                'value' => $footerTemplate
            ];
        } else { 
            $templateInsertData[] = [
                'name' => $footerTemplateId,
                'value' => $footerTemplate,
            ];
        }

        //check input email templateViewData are not changed then not insert any value
        if (__isEmpty($templateData)) {
            if (strnatcmp($input['public_footer_template'], $footerTemplateView) == 0) {
                $templateInsertData = [];
            } 
        }

        if ($this->manageStoreRepository->addorUpdate($templateUpdateData, $templateInsertData)) {
            return __engineReaction(1, null, __tr('Footer template added or updated successfully.')); // success
        }

        return __engineReaction(14, null, __tr('Nothing updated')); // error
    }   

    /**
      * get the edit support data
      *
      * @param string $prepareEditTemplateSupportData
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function prepareEditTemplateSupportData($templateId)
    {		
    	$configurations = $this->manageStoreRepository->fetchEmailTemplate($templateId);
    	$emailTemplateData = configItem('email_template_view', $templateId);
    	$emailSubject = isset($emailTemplateData['emailSubject']) ? $emailTemplateData['emailSubject'] : null;

    	$emailTemplate = [];
    	$templateView = [];
    	$templateDataExist = false;
    	if (!__isEmpty($configurations)) {
    		$templateDataExist = true;
    		$templateView =  $configurations->value;
    		
    	} else {
		    $templateView = view('dynamic-mail-templates.index', ['emailsTemplate' =>'dynamic-mail-templates.'.$emailTemplateData['template'] ])->render();
		   
	    }
   
		$subjectKey = $emailTemplateData['subjectKey'];
	   
		$subjectExists = false;
    	$configurationsData = $this->manageStoreRepository->fetchEmailTemplate($subjectKey);
    	$emailSubjectKey = [];
    	if (!__isEmpty($configurationsData)) {
    		$subjectExists = true;
	    	$emailSubjectKey = $configurationsData->name;
    		$emailSubject = $configurationsData->value;

    	} else {
    		if (!__isEmpty($emailSubject)) {
    			$emailSubjectKey = $subjectKey;
    			$emailSubject = $emailTemplateData['emailSubject'];
    		}
    	}

    	//email template data
    	$emailTemplateData = [
			'id'				=> $templateId,
			'title'				=> $emailTemplateData['title'],
			'replaceString' 	=> $emailTemplateData['replaceString'],
			'templateViewData'  => $templateView,
			'subjectExists'		=> $subjectExists,
            'templateDataExist'	=> $templateDataExist,
            'emailSubjectKey' 	=> $emailSubjectKey,
             'emailSubject'		=> isset($emailSubject) ? $emailSubject : null
		];

    	return __engineReaction(1, [
            'emailTemplateData' => $emailTemplateData,
        ]);
    }

    /**
      * process the edit or add config items
      *
      * @param array  $inputs
      * @param string $emailTemplateId
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function processEmailSubjectDelete($emaiSubjectId)
    {	
    	$configurations = $this->manageStoreRepository
    							->fetchEmailTemplate($emaiSubjectId);
    							
   		// If configurations does not exist then return not exist reaction code
        if (__isEmpty($configurations)) {
            return __engineReaction(2, null, __tr('Email data not found'));
        }

        // If configurations Deleted successfully then return engine reaction
        if ($this->manageStoreRepository->delete($configurations)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

     /**
      * process the edit or add config items
      *
      * @param array  $inputs
      * @param string $emailTemplateId
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function processEmailTemplateEditOrStore($input, $emailTemplateId)
    {	
    	$emailTemplate = configItem('email_template_view', $emailTemplateId);
    	$emailTemplateIds = [ $emailTemplateId, $emailTemplate['subjectKey'] ];

    	$configurations = $this->manageStoreRepository
    							->fetchEmailTemplateAllData($emailTemplateIds);

    	$templateView = view('dynamic-mail-templates.index', ['emailsTemplate' =>'dynamic-mail-templates.'.$emailTemplate['template'] ])->render();
    	
    	$subjectUpdateData = [];
    	$subjectInsertData = [];
    	$templateUpdateData = [];
    	$templateInsertData = [];
    	$subjectData  = $configurations->where('name', $emailTemplate['subjectKey'])->toArray();
    	$templateData  = $configurations->where('name', $emailTemplateId)->toArray();

    	//insert or update email template subject
    	if (isset($input['emailSubject'])) {
    		if (!__isEmpty($subjectData)) {
    			$subjectUpdateData[] = [
	    			'name' => $emailTemplate['subjectKey'],
	    			'value' => $input['emailSubject']
	    		];
    		} else {
    			$subjectInsertData[] = [
	    			'name' => $emailTemplate['subjectKey'],
	    			'value' => $input['emailSubject'],
	    		];
    		}
    	}

    	//check input email subject string are same then not insert any value
    	if (__isEmpty($subjectData) && isset($emailTemplate['emailSubject'])) {
    		if ($input['emailSubject'] == $emailTemplate['emailSubject']) {
	    		$subjectInsertData = [];
	    	} 
    	}

    	//insert or update email template view data
    	if (!__isEmpty($templateData)) {
			$templateUpdateData[] = [ 
    			'name' => $emailTemplateId,
    			'value' => $input['templateViewData']
    		];
		} else { 
			$templateInsertData[] = [
    			'name' => $emailTemplateId,
    			'value' => $input['templateViewData'],
    		];
		}

		//check input email templateViewData are not changed then not insert any value
		if (__isEmpty($templateData)) {
    		if (strnatcmp($input['templateViewData'], $templateView) == 0) {
	    		$templateInsertData = [];
	    	} 
    	}

    	if ($this->manageStoreRepository->addorUpdate($templateUpdateData, $templateInsertData, $subjectUpdateData, $subjectInsertData)) {
            return __engineReaction(1, null, __tr('Email template added or updated successfully.')); // success
        }

        return __engineReaction(14, null, __tr('Nothing updated')); // error
    }   

    /**
      * process the edit or add config items
      *
      * @param array  $inputs
      * @param string $emailTemplateId
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function processEmailTemplateDelete($emailTemplateId)
    {	
    	$configurations = $this->manageStoreRepository
    							->fetchEmailTemplate($emailTemplateId);

   		// If configurations does not exist then return not exist reaction code
        if (__isEmpty($configurations)) {
            return __engineReaction(2, null, __tr('Email data not found'));
        }

        // If configurations Deleted successfully then return engine reaction
        if ($this->manageStoreRepository->delete($configurations)) {
            return __engineReaction(1, null, __tr('Default template loaded succesfully'));
        }

        return __engineReaction(2, null, __tr('Nothing updated'));
    }

    /**
    * Slider datatable source 
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function prepareSliderList()
    {
        $sliderData = [];
        
        $sliderCollection = $this->manageStoreRepository->fetchByName('home_slider');
        
        $sliderDataCollection = [];
        if (!__isEmpty($sliderCollection)) {
            $sliderValue = json_decode($sliderCollection->value, true);

            if (isset($sliderValue['sliderData'])) {
                foreach ($sliderValue['sliderData'] as $sliderKey => $value) {
                    $sliderData[$sliderKey] = $value;
                    $sliderDataCollection = [];

                    foreach ($sliderData as $key => $slider) {
         
                        $sliderDataCollection[] =  [
                            'id'    => $key,
                            'title' => $slider['title']
                        ];
                    }
                }
            }
            
        }
        

        return __engineReaction(1, [
            'sliderDataCollection' => $sliderDataCollection
        ]);

    }

    /**
      * Slider create 
      *
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processSliderCreate($inputData)
    {   
        // Check if slides empty
        if (empty($inputData['slides'])) {
            return 4;
        }

        $sliderData = [];

        $title = $inputData['title'].'_'.uniqid();

        if (isset($inputData['autoPlayTimeout'])) {
        	$inputData['autoPlayTimeout'] = ($inputData['autoPlayTimeout'] * 1000);
    	}

        foreach ($inputData as $key => $value) { 
            $sliderData[$title][$key] = $value;
        }

        $insertData['sliderData'] = $sliderData;
   
        // fetch all configuration array
        $configuration = $this->manageStoreRepository->fetchByName('home_slider');


        if (!__isEmpty($configuration)) {
            $oldData = json_decode($configuration->value, true);
            
            if (!__isEmpty($oldData)) {
                $sliderCollection = array_merge($oldData['sliderData'], $insertData['sliderData']);
            } else {
                $sliderCollection =  $insertData['sliderData'];
            }
           
            $updateData['sliderData'] = $sliderCollection;

            if ($this->manageStoreRepository->updateSliderSetting($configuration, $updateData)) {

                $image = [];


                if (!__isEmpty($inputData['slides'])) {

                    foreach ($inputData['slides'] as $value) {
                        
                        $image =  $value['image'];

                        if (!$this->mediaEngine->isUserTempMedia($image)) {
                            return __engineReaction(3, null, __tr('Image not found.'));;
                        }

                        $newSliderImage = $this->mediaEngine->storeSliderMedia($image, $title, null);

                        if (!$newSliderImage) {
                            return __engineReaction(2, null, __tr('Slider Image not stored.'));
                        }     
                    }
                }
                
                return __engineReaction(1, ['title' => $title], __tr('Slider added successfully.'));
            }

        } else {        

            if ($this->manageStoreRepository->storeSliderSetting($insertData)) {

                $image = [];

                //$title = $inputData['title'].'_'.uniqid();

                if (!__isEmpty($inputData['slides'])) {

                    foreach ($inputData['slides'] as $value) {
                        
                        $image =  $value['image'];

                        if (!$this->mediaEngine->isUserTempMedia($image)) {
                            return __engineReaction(3, null, __tr('Image not found.'));;
                        }

                        $newSliderImage = $this->mediaEngine->storeSliderMedia($image, $title, null);

                        if (!$newSliderImage) {
                            return __engineReaction(2, null, __tr('Slider Image not stored.'));
                        }     
                    }
                }

                return __engineReaction(1, [
                    'title' => $title
                ], __tr('Slider added successfully.'));
            }
        }

        return __engineReaction(2, null, __tr('Slider not added.'));
    }

    /**
      * Slider prepare update data 
      *
      * @param  mix $sliderID
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function prepareSliderUpdateData($sliderID)
    {   
        $slider = $this->manageStoreRepository->fetchByName('home_slider');

        // Check if $slider not exist then throw not found 
        // exception
        if (__isEmpty($slider)) {
            return __engineReaction(18, null, __tr('Slider not found.'));
        }

        $sliderValue = json_decode($slider->value, true);

        $sliderCollection = [];
        foreach ($sliderValue['sliderData'] as $sliderKey => $value) {

            if ($sliderKey == $sliderID) {

                $value['slides'] = array_map(function ($item) use($sliderKey) {

                    $item['thumbnailURL'] = getSliderThumbanilUrl($sliderKey, $item['image']);

                    if (!isset($item['bg_color'])) {
                        $item['bg_color'] = 'ffffff';
                    }
                    $item['newImageExist'] = '';
                    $item['oldImageName'] = '';
                    return $item;

                }, $value['slides']);

                $slideSorted = array_values(array_sort($value['slides'], function ($value) {
                    return $value['orderIndex'];
                }));

                $sliderCollection['slides'] = $slideSorted;
                $sliderCollection['title'] = $value['title'];
                $sliderCollection['auto_play'] = $value['auto_play'];
                $sliderCollection['autoPlayTimeout'] = (int) $value['autoPlayTimeout'];
                if (isset($sliderCollection['autoPlayTimeout'])) {
		        	$sliderCollection['autoPlayTimeout'] = ($sliderCollection['autoPlayTimeout'] / 1000);
		    	}
            }
        }

        return __engineReaction(1, [
            'sliderCollection'  => $sliderCollection,
            'sliderTitle'       => $sliderCollection['title']
        ]);
    }

    /**
      * Slider process update 
      * 
      * @param  mix $sampleId
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processSliderUpdate($sliderID, $inputData)
    {    
        $configuration = $this->manageStoreRepository->fetchByName('home_slider');

        // Check if $slider not exist then throw not found 
        // exception
        if (__isEmpty($configuration)) {
            return __engineReaction(18, null, __tr('Slider not found.'));
        }

        $sliderValue = json_decode($configuration->value, true);

        $existingSliderData = $sliderValue['sliderData'][$sliderID]['slides'];

        if (isset($inputData['autoPlayTimeout'])) {
        	$inputData['autoPlayTimeout'] = ($inputData['autoPlayTimeout'] * 1000);
    	}
    	
        $sliderValue['sliderData'][$sliderID] = $inputData;

        $updateData = $sliderValue;

        $image = [];

        $title = $sliderID;
      
        $newSliderImage = false;

        if (!__isEmpty($inputData['slides'])) {
            
            foreach ($inputData['slides'] as $key => $value) {
                
                $image =  $value['image'];

                if ($value['newImageExist']) {
                    if ($this->mediaEngine->isUserTempMedia($image)) { 
                        $newSliderImage = $this->mediaEngine->storeSliderMedia($image, $title, $value['oldImageName'] ?? '');
                    }
                }                
            }
        }
       
        // Check if Slider updated
        if ($this->manageStoreRepository->updateSliderSetting($configuration, $updateData) or $newSliderImage) {

            return __engineReaction(1, null, __tr('Slider updated.'));
        }

        return __engineReaction(14, null, __tr('Slider not updated.'));
    }

     /**
     * process of delete Specification Preset.
     *
     * @param int $presetId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function deleteSliderData($sliderID, $title)
    {  
        $configuration = $this->manageStoreRepository->fetchByName('home_slider');

        // Check if $slider not exist then throw not found 
        // exception
        if (__isEmpty($configuration)) {
            return __engineReaction(18, null, __tr('Slider not found.'));
        }

        $sliderValue = json_decode($configuration->value, true);

        //landing Page Data
        $landingPage = getStoreSettings('landing_page');
        $sliderTitle = [];
        if (!__isEmpty($landingPage)) {
            foreach ($landingPage['landingPageData'] as $key => $landing) {

                if ($landing['identity'] == 'Slider' and isset($landing['title'])) {
                    $sliderTitle = array_get($landing, 'title');
                }
            }
        }

        if ($sliderTitle == $title) {
            return __engineReaction(2, null, __tr('__title__ already assign in landing page.', [
                    '__title__' => $title,
                ]));
        }

        $deleteData = [];
        foreach ($sliderValue['sliderData'] as $key => $value) {
            if ($key != $sliderID) {
               $deleteData['sliderData'][$key] = $value;
            }
       }
    
        // If Tax Deleted successfully then return engine reaction
        if ($this->manageStoreRepository->updateSliderSetting($configuration, $deleteData)) {

            $this->mediaEngine->deleteSliderMedia($sliderID);

            return __engineReaction(1);
        }

        return __engineReaction(2);
    }
}