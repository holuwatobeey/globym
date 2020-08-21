<?php

/*
* FileManagerController.php - Controller file
*
* This file is part of the FileManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\FileManager\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\FileManager\FileManagerEngine;
use App\Yantrana\Components\FileManager\Requests\AddFolderRequest;
use App\Yantrana\Components\FileManager\Requests\RenameFolderRequest;
use App\Yantrana\Components\FileManager\Requests\RenameFileRequest;

class FileManagerController extends BaseController
{
    /**
     * @var FileManagerEngine $fileManagerEngine - FileManager Engine
     */
    protected $fileManagerEngine;

    /**
      * Constructor
      *
      * @param FileManagerEngine $fileManagerEngine - FileManager Engine
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct(FileManagerEngine $fileManagerEngine)
    {
        $this->fileManagerEngine = $fileManagerEngine;
    }

    private function useFile($fileUrl)
    {
        return "<script type='text/javascript'>

        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);
            return ( match && match.length > 1 ) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        var par = window.parent,
            op = window.opener,
            o = (par && par.CKEDITOR) ? par : ((op && op.CKEDITOR) ? op : false);

        if (op) window.close();
        if (o !== false) o.CKEDITOR.tools.callFunction(funcNum, '$fileUrl');
        </script>";
    }

    /**
      * Handle upload file request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function upload(Request $request)
    {
        $inputData    = $request->all();

        $processReaction = $this->fileManagerEngine
                                ->processUpload($inputData);

        if ($processReaction['reaction_code'] === 1 and __ifIsset($inputData['file'])) {
           // return $this->useFile($processReaction['data']['url']);
            return json_encode([
                'success' => true, // Must be false if upload fails
                'error' => false,
                'url' => $processReaction['data']['url']
            ]);
        }

        return json_encode([
            'success' => false, // Must be false if upload fails
            'error' => true,
            'url' => ''
        ]);
    }

    /**
      * Handle get uploaded common files
      *
      * @param object Request $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function files(Request $request)
    {
        $processReaction = $this->fileManagerEngine->prepareFiles($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Handle delete file request
      *
      * @param object Request $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function deleteFile(Request $request)
    {
        $processReaction = $this->fileManagerEngine->processDeleteFile($request->all());

        return __processResponse($processReaction);
    }

    /**
      * Handle delete file request
      *
      * @param object Request $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function downloadFile(Request $request)
    {
        $processReaction = $this->fileManagerEngine->processDownloadFile($request->all());

        if ($processReaction['reaction_code'] === 1) {
            return response()->download($processReaction['data']['filename']);
        }

        return __processResponse($processReaction);
    }

    /**
      * Handle add folder request
      *
      * @param object AddFolderRequest $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function addFolder(AddFolderRequest $request)
    {
        $processReaction = $this->fileManagerEngine->processAddFolder($request->all());

        return __processResponse($processReaction);
    }

    /**
      * Handle delete folder request
      *
      * @param object Request $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function deleteFolder(Request $request)
    {
        $processReaction = $this->fileManagerEngine->processDeleteFolder($request->all());

        return __processResponse($processReaction);
    }

    /**
      * Handle rename folder request
      *
      * @param object RenameFolderRequest $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function renameFolder(RenameFolderRequest $request)
    {
        $processReaction = $this->fileManagerEngine->processRenameFolder($request->all());

        return __processResponse($processReaction);
    }

    /**
      * Handle rename file request
      *
      * @param object RenameFileRequest $request
      *
      * @return json object
      *---------------------------------------------------------------- */
    
    public function renameFile(RenameFileRequest $request)
    {
        $processReaction = $this->fileManagerEngine->processRenameFile($request->all());

        return __processResponse($processReaction);
    }
}
