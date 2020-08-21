<?php
/*
* EditStoreSettingsRequest.php - Request file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class EditStoreSettingsRequest extends BaseRequest
{
    /**
     * Set if you need form request secured.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

    /**
     * Unsecured/Unencrypted form fields.
     *------------------------------------------------------------------------ */
    protected $unsecuredFields = [
        'store_name',
        'contact_address',
        'term_condition',
        'privacy_policy',
        'addtional_page_end_content',
        'payment_check_text',
        'payment_bank_text',
        'payment_cod_text',
        'payment_other_text',
        'custom_css',
        'append_email_message',
        'global_notification',
        'currency_symbol'
    ];

    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = [
                                            'contact_address' => true,
                                            'term_condition' => true,
                                            'privacy_policy' => true,
                                            'payment_check_text' => true,
                                            'payment_bank_text' => true,
                                            'payment_cod_text' => true,
                                            'payment_other_text' => true,
                                            'append_email_message' => true,
                                            'global_notification' => true,
                                            'currency_symbol' => true,
                                            'addtional_page_end_content' => '<script></script>',
                                            'templateViewData' => true,
                                            'public_footer_template' => true,
                                            'header_advertisement_content' => true,
                                            'footer_advertisement_content' => true,
                                            'page_content' => true
                                         ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the edit store settings request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $formType = (int) Request::route()->parameter('formType');

        $rules = [];

        if ($formType == 1) {
            $rules = [
                'store_name' => 'required',
                'business_email' => 'required|email',
                'timezone' => 'required',
            ];
        } elseif ($formType === 2) {
            $rules = [
                'currency'        => 'required',
                'currency_symbol' => 'required',
                'currency_value'  => 'required',
                'currency_format' => 'required|verify_format'
            ];

            if (Request::input('display_multi_currency')) {
                $rules['multi_currencies'] = 'required';
            }
            
        } elseif ($formType === 3) {
            $rules = [];

            // Check payment other checkbox is true then show payment other text
            if (Request::input('payment_other')) {
                $rules['payment_other_text'] = 'required';
            }

            // Check if use paypal true then show papal email
            if (Request::input('use_paypal')) {
                $rules['paypal_email'] = 'required|email';
                $rules['paypal_sandbox_email'] = 'required|email';
            }

            // Check if check payment true or false
            if (Request::input('payment_check')) {
                $rules['payment_check_text'] = 'required';
            }

            // Check if bank payment true or false
            if (Request::input('payment_bank')) {
                $rules['payment_bank_text'] = 'required';
            }

            // Check if cod payment true or false
            if (Request::input('payment_cod')) {
                $rules['payment_cod_text'] = 'required';
            }

            $isLiveStripeKeysExist      = Request::input('isLiveStripeKeysExist');
            $isTestingStripeKeysExist   = Request::input('isTestingStripeKeysExist');

            $stripeLiveSecretKey        = Request::input('stripe_live_secret_key');
            $stripeLivePublishableKey   = Request::input('stripe_live_publishable_key');

            $stripeTestingSecretKey   = Request::input('stripe_testing_secret_key');
            $stripeTestingPublishableKey = Request::input('stripe_testing_publishable_key');

            if (Request::input('use_stripe')) {
                if (!$isLiveStripeKeysExist) {

                    // check if stripe live publish key exist
                    if (!__isEmpty($stripeLivePublishableKey)) {
                        $rules['stripe_live_secret_key']         = 'required';
                    }
                    
                    // check if stripe live secret key exist
                    if (!__isEmpty($stripeLiveSecretKey)) {
                        $rules['stripe_live_publishable_key']    = 'required';
                    }
                }

                if (!$isTestingStripeKeysExist) {

                    // Check if stripe testing publishable key exist
                    if (!__isEmpty($stripeTestingPublishableKey)) {
                        $rules['stripe_testing_secret_key']      = 'required';
                    }
                    
                    // Check if stripe testing secret key exist
                    if (!__isEmpty($stripeTestingSecretKey)) {
                        $rules['stripe_testing_publishable_key'] = 'required';
                    }
                }
            }

            /*$isLiveIyzipayKeyExist = Request::input('isLiveIyzipayKeyExist');
            $isTestingIyzipayKeyExist = Request::input('isTestingIyzipayKeyExist');

            $iyzipayLiveSecretKey = Request::input('iyzipay_live_secret_key');
            $iyzipayLiveApiKey = Request::input('iyzipay_live_api_key');

            $iyzipayTestingSecretKey = Request::input('iyzipay_testing_secret_key');
            $iyzipayTestingApiKey = Request::input('iyzipay_testing_api_key');

            if (Request::input('use_iyzico')) {

                if (!$isLiveIyzipayKeyExist) {
                    if (!__isEmpty($iyzipayLiveSecretKey)) {
                        $rules['iyzipay_live_secret_key'] = 'required';
                    }

                    if (!__isEmpty($iyzipayLiveApiKey)) {
                        $rules['iyzipay_live_api_key'] = 'required';
                    }
                }

                if (!$isTestingIyzipayKeyExist) {
                    if (!__isEmpty($iyzipayTestingSecretKey)) {
                        $rules['iyzipay_testing_secret_key'] = 'required';
                    }

                    if (!__isEmpty($iyzipayTestingApiKey)) {
                        $rules['iyzipay_testing_api_key'] = 'required';
                    }
                }
            }*/

        } elseif ($formType === 6) {
            $rules = [
                'contact_email' => 'required|email',
                'contact_address' => 'required|min:6',
            ];
        } elseif ($formType === 5) {
            $rules = [
                'categories_menu_placement' => 'required',
                'brand_menu_placement' => 'required',
                'footer_text' => 'min:3|max:50',
            ];
        } elseif ($formType === 4) {
            $itemLoadType = (int) Request::input('item_load_type');

            if (__ifIsset($itemLoadType)) {
                if ($itemLoadType === 1) {
                    $rules = [
                        'pagination_count' => 'required|integer|min:20|max:100',
                    ];
                } else {
                    $rules = [
                        'pagination_count' => 'required|integer|min:5|max:100',
                    ];
                }
            }
        } elseif ($formType === 7) {
            $rules['term_condition'] = 'min:6';
            $rules['show_captcha']   = 'required|min:1';
        } elseif ($formType === 8) {
            $rules['privacy_policy'] = 'min:6';
        } elseif ($formType === 10) {
            $rules = [
                'social_facebook' => 'alpha_dash',
                'social_twitter' => 'alpha_dash',
            ];
        }  elseif ($formType === 16) {
            $slides = Request::input('slides');
            $config = Request::input('configuration');
            $rules['title'] = 'alpha_dash|min:2|max:150';
           
            if (is_array($slides)) {
                foreach ($slides as $key => $slideValue) {

                    $rules['slides.'.$key.'.image'] = 'required';
                    $rules['slides.'.$key.'.caption_1'] = 'max:150';
                    $rules['slides.'.$key.'.caption_2'] = 'max:150';
                    $rules['slides.'.$key.'.caption_3'] = 'max:1000';
                   
                }
            }
        }
       

        return $rules;
    }

    /**
    * Set custom msg for field
    *
    * @return array
    *-----------------------------------------------------------------------*/

    public function messages()
    {    
        $formType = (int) Request::route()->parameter('formType');
        $values = Request::input('slides');
        $messages = $rules = [];
        if ($formType === 16) {
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $rules['slides.'.$key.'.caption_1.max'] = __tr('The heading 1 may not be greater than :max characters.');
                    $rules['slides.'.$key.'.caption_2.max'] = __tr('The heading 2 may not be greater than :max characters.');
                    $rules['slides.'.$key.'.caption_3.max'] = __tr('The description may not be greater than :max characters.');
                    $rules['slides.'.$key.'.image.required'] = __tr('The Image field is required.');
                }
                return $rules;
            }
        }
        
        return [
            'currency_format.verify_format' => __tr('Invalid currency format.')
        ];
    }
}
