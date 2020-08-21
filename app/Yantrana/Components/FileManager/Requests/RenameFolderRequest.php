<?php

/*
* RenameFolderRequest.php - Request file
*
* This file is part of the FileManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\FileManager\Requests;

use App\Yantrana\Core\BaseRequest;

class RenameFolderRequest extends BaseRequest
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
            'name'                    => 'required|min:2|alpha_dash',
            'existing_name'            => 'required|min:2|alpha_dash',
            'folder_relative_path'    => 'required'
        ];
    }
}
