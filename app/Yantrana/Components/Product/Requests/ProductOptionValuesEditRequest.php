<?php
/*
* ProductOptionValuesEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class ProductOptionValuesEditRequest extends BaseRequest
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
     * Get the validation rules that apply to the edit product option values
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [];

        $optionID = Request::route()->parameter('optionID');
        $values = Request::input('values');
        $optionType = Request::input('type');

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $rules['values.'.$key.'.name'] = 'required
                    |unique:product_option_values,name,'.!empty($value['id']) ? '' : $value['id'].',id,product_option_labels_id,'.$optionID;
                    
                if (!__isEmpty($value['addon_price'])) {    
                    $rules['values.'.$key.'.addon_price'] = 'numeric|min:0|amount_validation|decimal_validation';
                }

                if ($optionType == 2 and !isset($value['existingThumbnailURL'])) {
                    $rules['values.'.$key.'.image'] = 'required';
                }

            }
        }

        return $rules;
    }

    protected function createMessages()
    {
        $messages = [];

        $values = Request::input('values');
        $optionType = Request::input('type');

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

                if ($optionType == 2 and !isset($value['existingThumbnailURL'])) {
                    $messages['values.'.$key.'.image.required'] = __tr('The image field is required');
                }
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
