<?php            
/*
* PresetAddRequest.php - Request file
*
* This file is part of the SpecificationsPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Requests;

use Illuminate\Validation\Rule;
use App\Yantrana\Core\BaseRequest;

class PresetAddRequest extends BaseRequest 
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
            "title"               => "required|min:2|max:150|unique:specification_presets,title",
            'specficationLabels.*.label' => 'required|distinct'
        ];
    }

    /**
    * Set custom msg for field
    *
    * @return array
    *-----------------------------------------------------------------------*/

    public function messages()
    {

        $messages['specficationLabels.*.label.distinct'] = __tr('The Specification Label must be different.');

        return $messages;
    }
}