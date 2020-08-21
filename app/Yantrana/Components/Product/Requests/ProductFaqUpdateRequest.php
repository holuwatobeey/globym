<?php
/*
* ProductFaqUpdateRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class ProductFaqUpdateRequest extends BaseRequest
{
    protected $looseSanitizationFields = ['answer' => true];
    
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
        return  [
            'question' => 'required',
        ];
    }
}
