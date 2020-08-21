<?php
/*
* OrderProcessRequest.php - Request file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Requests;

use App\Yantrana\Core\BaseRequest;

class OrderProcessRequest extends BaseRequest
{
    /**
     * Authorization for request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $inputData = $this->all();    

        if (!isLoggedIn()) {
            $inputData = $this->all();

            $rules['email']                     = 'required';
            $rules['shipping_address_type']     = 'required';
            $rules['shipping_address_line_1']   = 'required';
            $rules['shipping_address_line_2']   = 'required';
            $rules['shipping_city']             = 'required';
            $rules['shipping_state']            = 'required';
            $rules['shipping_pin_code']         = 'required';
            $rules['shipping_country']          = 'required';

            if (!$inputData['use_as_billing']) {
                $rules['billing_address_type']     = 'required';
                $rules['billing_address_line_1']   = 'required';
                $rules['billing_address_line_2']   = 'required';
                $rules['billing_city']             = 'required';
                $rules['billing_state']            = 'required';
                $rules['billing_pin_code']         = 'required';
                $rules['billing_country']          = 'required';
            }
        }
        
        $rules['checkout_method'] = 'required';
        $rules['fullName'] = 'required';
        $rules['card_number'] = 'required_if:checkout_method,14';
        $rules['expiry_date'] = 'required_if:checkout_method,14';
        $rules['cvv'] = 'required_if:checkout_method,14';
        $rules['name_on_card'] = 'required_if:checkout_method,14';
       
        return $rules;
    }

    public function messages()
    {
        return [
            'card_number.required_if'   => 'The card number field is required.',
            'expiry_date.required_if'   => 'The expiry date field is required.',
            'cvv.required_if'           => 'The card cvv field is required.',
            'name_on_card.required_if'  => 'The card holder field is required.',
        ];
    }
}
