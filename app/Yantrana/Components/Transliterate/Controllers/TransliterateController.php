<?php
/*
* TransliterateController.php - Controller file
*
* This file is part of the Transliterate component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Transliterate\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Components\Transliterate\Requests\TranslitrateAddRequest;
use App\Yantrana\Components\Transliterate\TransliterateEngine;

class TransliterateController extends BaseController 
{    
    /**
     * @var  TransliterateEngine $transliterateEngine - Transliterate Engine
     */
    protected $transliterateEngine;

    /**
      * Constructor
      *
      * @param  TransliterateEngine $transliterateEngine - Transliterate Engine
      *
      * @return  void
      *-----------------------------------------------------------------------*/

    function __construct(TransliterateEngine $transliterateEngine)
    {
        $this->transliterateEngine = $transliterateEngine;
    }
    
    /**
     * Get Add transliterate support data
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getAddSupportData($entityType, $entityId, $entityKey)
    {
        $processReaction = $this->transliterateEngine
                                ->prepareTransliterateData($entityType, $entityId, $entityKey);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Add transliterate support data
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     * @param object $TranslitrateAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processTransliterateUpdate($entityType, $entityId, $entityKey, TranslitrateAddRequest $request)
    {
        $processReaction = $this->transliterateEngine
                                ->processTransliterateUpdate($entityType, $entityId, $entityKey, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Add transliterate support data
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     * @param string $language
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getTranslateData($entityType, $entityId, $entityKey, $language)
    {
        $processReaction = $this->transliterateEngine
                                ->prepareTranslateData($entityType, $entityId, $entityKey, $language);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Original Text
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getOriginalText(Request $request)
    {
        $processReaction = $this->transliterateEngine
                                ->prepareOriginalTextForTranslation($request->all());

        return __processResponse($processReaction, [], [], true);
    }
}