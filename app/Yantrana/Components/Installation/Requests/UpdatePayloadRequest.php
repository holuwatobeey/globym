<?php
/*
* UpdatePayloadRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Installation\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class UpdatePayloadRequest extends BaseRequest
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
        return [
           'current_version' => 'required',
           'product_uid' => 'required',
           'registration_id' => 'required'
        ];
    }
}
