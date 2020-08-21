<?php
/*
* TranslitrateAddRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Transliterate\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class TranslitrateAddRequest extends BaseRequest
{
    protected $looseSanitizationFields = ['translate_text' => true];
    
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
        return [
           'language' => 'required',
           'translate_text' => 'required'
        ];
    }
}
