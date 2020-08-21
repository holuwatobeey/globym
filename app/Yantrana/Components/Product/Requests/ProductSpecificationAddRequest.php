<?php
/*
* ProductSpecificationAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;
use Illuminate\Validation\Rule;

class ProductSpecificationAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product option
     * post request.
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function rules()
    {

        $presetType = Request::route()->parameter('presetType');
        $productID = Request::route()->parameter('productID');
        $specificationData = $this->all();

        $rules = [];
        if ($presetType == 1) {
            if (!__isEmpty($specificationData)) {                
                $rules['specifications.*.*.value'] = 'distinct';
            }
        } elseif ($presetType == 2) {
            $rules = [
                'label' => 'required|unique:specifications,label',
                'value' => 'required'
            ];
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
        $messages = [];
        $specificationData = $this->all();
        if (!__isEmpty($specificationData)) {
            foreach ($specificationData['specifications'] as $preset => $specifications) {
                foreach ($specifications as $index => $specification) {   
                    $specificationId = array_get($specification, 'id');
                    $messages['specifications.'.$preset.'.'.$index.'.value.unique'] = __tr('The Specification __value__ Value must be different', [
                        '__value__' => $preset
                    ]);
                }
            }
        }

        return $messages;
    }
}
