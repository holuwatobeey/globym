<?php

/*
* UserAddRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Requests;

use App\Yantrana\Core\BaseRequest;

class UserAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the user register request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $userId = auth()->user()->id;

        return [
            'fname'      => 'required|min:2|max:30',
            'lname'      => 'required|min:2|max:30',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|max:30',
            'role'   	 => 'required',
            'password_confirmation' => 'required|min:6|max:30|same:password'
        ];
    }
}
