<?php
/*
* MediaController.php - Controller file
*
* This file is part of the Media component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Media\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Media\MediaEngine;

class MediaController extends BaseController
{
    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * Constructor.
     *
     * @param MediaEngine $mediaEngine - Media Engine
     *-----------------------------------------------------------------------*/
    public function __construct(MediaEngine $mediaEngine)
    {
        $this->mediaEngine = $mediaEngine;
    }

    /**
     * Upload image files by user.
     *
     * @param object Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function uploadImage(Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processUploadedImage($request->file('file'));
                    
        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Upload image file.
     *
     * @param object Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processUploadImage(Request $request)
    {
        $processReaction = $this->mediaEngine->processUploadImageByExtension($request->file('file'), 1);
                    
        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Handle uploaded images files request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function imagesFiles()
    {
        $processReaction = $this->mediaEngine->prepareUploadedImagesFiles();

        return __processResponse($processReaction, [
            ], $processReaction['data']);
    }

    /**
     * Handle user temp media delete request.
     *
     * @param string $fileName
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($fileName, Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processDeleteTempMedia($fileName);

        return __processResponse($processReaction, [
                1 => __tr('File deleted.'),
                2 => __tr('File not deleted.'),
                3 => __tr('File does not exist.'),
            ]);
    }

    /**
     * Handle delete multiple user temp media.
     *
     * @param object Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function multipleDelete(Request $request)
    {
        $processReaction = $this->mediaEngine
                                ->processDeleteMultipleTempMedia(
                                    $request->input('files')
                                );

        return __processResponse($processReaction, [
                1 => __tr('Files deleted.'),
                2 => __tr('Files not deleted.'),
            ]);
    }
}
