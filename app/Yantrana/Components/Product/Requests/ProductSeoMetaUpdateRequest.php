<?php
/*
* ProductSeoMetaUpdateRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class ProductSeoMetaUpdateRequest extends BaseRequest
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
        return  [
        ];
    }
}
