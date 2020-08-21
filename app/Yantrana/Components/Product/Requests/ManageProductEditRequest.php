<?php
/*
* ManageProductEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ManageProductEditRequest extends BaseRequest
{
    protected $looseSanitizationFields = ['description' => true];

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
     * Get the validation rules that apply to the edit product details
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $productID = Request::route()->parameter('productID');

        return [
            'name' => 'required',
            'product_id' => 'required|alpha_dash|unique:products,product_id,'.$productID.',id',
            'old_price' => 'nullable|numeric|min:0.01|amount_validation|decimal_validation',
            'price' => 'required|numeric|min:0.01|amount_validation|decimal_validation',
            'categories' => 'required',
            'description' => 'required|min:10',
            'active' => 'required',
       ];
    }

    /**
    * Set custom msg for field
    *
    * @return array
    *-----------------------------------------------------------------------*/

    public function messages()
    {
        return [
            'old_price.decimal_validation' => __tr('Digit after decimal may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '4'
                ]),
            'old_price.amount_validation' => __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]),

            'price.decimal_validation' => __tr('Digit after decimal may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '4'
                ]),
            'price.amount_validation' => __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]),
        ];
    }
}
