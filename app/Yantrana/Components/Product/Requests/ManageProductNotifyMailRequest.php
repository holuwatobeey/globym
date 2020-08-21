<?php
/*
* ManageProductNotifyMailRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ManageProductNotifyMailRequest extends BaseRequest
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
     * Get the validation rules that apply to the edit product details
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        //$productID = Request::route()->parameter('productID');

        return [
            // 'name' => 'required',
       ];
    }

}
