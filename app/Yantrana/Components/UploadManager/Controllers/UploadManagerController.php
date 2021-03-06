<?php
/*
* UploadManagerController.php - Controller file
*
* This file is part of the UploadManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UploadManager\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\UploadManager\UploadManagerEngine;

class UploadManagerController extends BaseController
{
    /**
     * @var UploadManagerEngine - UploadManager Engine
     */
    protected $uploadManagerEngine;

    /**
     * Constructor.
     *
     * @param UploadManagerEngine $uploadManagerEngine - UploadManager Engine
     *-----------------------------------------------------------------------*/
    public function __construct(UploadManagerEngine $uploadManagerEngine)
    {
        $this->uploadManagerEngine = $uploadManagerEngine;
    }

    /**
     * Handle upload manager files request.
     *
     * @param param
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function files()
    {
        $processReaction = $this->uploadManagerEngine->prepareFiles();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Handle upload file.
     *
     * @param Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function upload(Request $request)
    {
        $processReaction = $this->uploadManagerEngine
                                ->processUpload($request->file('file'));

        return __processResponse($processReaction, [
                1 => __tr('File uploaded'),
                2 => __tr('File not uploaded'),
                3 => __tr('Invalid file'),
            ]);
    }

    /**
     * Handle user temp media delete request.
     *
     * @param string $fileName
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($fileName)
    {
        $processReaction = $this->uploadManagerEngine
                                ->processDelete($fileName);

        return __processResponse($processReaction, [
                1 => __tr('File deleted.'),
                2 => __tr('File not deleted.'),
                3 => __tr('File does not exist.'),
            ]);
    }
}
