<?php
/*
* ShippingAddRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ShippingEditRequest extends BaseRequest
{
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
     * Get the validation rules that apply to the add author client post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rule = [
               'country' => 'required',
               'type' => 'required',
        ];

        if (Request::input('type') == 1) {
            $rule['charges'] = 'required|numeric|min:0.1|amount_validation|decimal_validation';
            $rule['free_after_amount'] = 'numeric|amount_validation|decimal_validation';
        } elseif (Request::input('type') == 2) {
            $rule['charges'] = 'required|numeric|min:0.1|amount_validation|decimal_validation';
            $rule['amount_cap'] = 'numeric|min:0.1|amount_validation|decimal_validation';
        }

        return $rule;
    }

    /**
    * Set custom msg for field
    *
    * @return array
    *-----------------------------------------------------------------------*/

    public function messages()
    {
        return [
            'free_after_amount.decimal_validation' => __tr('Digit after decimal may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '4'
                ]),
            'free_after_amount.amount_validation' => __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]),

            'amount_cap.decimal_validation' => __tr('Digit after decimal may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '4'
                ]),
            'amount_cap.amount_validation' => __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]),

            'charges.decimal_validation' => __tr('Digit after decimal may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '4'
                ]),
            'charges.amount_validation' => __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]),
        ];
    }
}
