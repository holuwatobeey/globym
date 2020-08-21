<?php
/*
* ProductOptionValuesAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ProductOptionValuesAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product option values
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [];

        $optionID = Request::route()->getParameter('optionID');
        $values = Request::input('values');

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $rules['values.'.$key.'.name'] = 'required
                    |unique:product_option_values,name,NULL,id,product_option_labels_id,'.$optionID;

                $rules['values.'.$key.'.addon_price'] = 'numeric|min:0|amount_validation|decimal_validation';
            }
        }

        return $rules;
    }

    protected function createMessages()
    {
        $messages = [];

        $values = Request::input('values');

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $messages['values.'.$key.'.addon_price.amount_validation'] = __tr('The __attribute__ may not be greater than __max__.', [
                    '__attribute__' => ':attribute',
                    '__max__'       => '999999999'
                ]);

                $messages['values.'.$key.'.addon_price.decimal_validation'] = __tr('Digit after decimal may not be greater than __max__.', [
                        '__attribute__' => ':attribute',
                        '__max__'       => '4'
                    ]);
            }
        }

        return $messages;
    }

    /**
    * Set custom msg for field
    *
    * @return array
    *-----------------------------------------------------------------------*/

    public function messages()
    {
        return $this->createMessages();
    }
}
