<?php
/*
* NotifyUserRequest.php - Request file
*
* This file is part common support.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;

class NotifyUserRequest extends BaseRequest
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
        return [
        	'notifyUserEmail' => 'email'
        ];
    }

    public function messages()
    {
    	return [
    		'notifyUserEmail.email'	=> 'The entered email must be a valid email address'
    	];
    }
}
