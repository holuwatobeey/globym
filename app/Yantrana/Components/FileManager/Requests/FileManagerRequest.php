<?php

/*
* FileManagerRequest.php - Request file
*
* This file is part of the FileManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\FileManager\Requests;

use App\Yantrana\Core\BaseRequest;

class FileManagerRequest extends BaseRequest
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
        return [];
    }
}
