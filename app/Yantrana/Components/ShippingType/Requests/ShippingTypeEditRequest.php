<?php            
/*
* ShippingTypeEditRequest.php - Request file
*
* This file is part of the ShippingType component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShippingType\Requests;

use App\Yantrana\Core\BaseRequest;

class ShippingTypeEditRequest extends BaseRequest 
{      
    /**
      * Authorization for request.
      *
      * @return  bool
      *-----------------------------------------------------------------------*/

    public function authorize()
    {
       return true; 
    }
    
    /**
      * Validation rules.
      *
      * @return  bool
      *-----------------------------------------------------------------------*/

    public function rules()
    {   
        return [
            "title" => "required", 
        ];
    }
}