<?php
/*
* ProductFaqAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ProductFaqAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product option
     * post request.
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [];
    }

}
