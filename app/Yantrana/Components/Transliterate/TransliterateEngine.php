<?php
/*
* TransliterateEngine.php - Main component file
*
* This file is part of the Transliterate component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Transliterate;

use App\Yantrana\Core\BaseEngine;
 
use App\Yantrana\Components\Transliterate\Repositories\TransliterateRepository;
use App\Yantrana\Components\Transliterate\Interfaces\TransliterateEngineInterface;

class TransliterateEngine extends BaseEngine implements TransliterateEngineInterface 
{   
     
    /**
     * @var  TransliterateRepository $transliterateRepository - Transliterate Repository
     */
    protected $transliterateRepository;
    

    /**
      * Constructor
      *
      * @param  TransliterateRepository $transliterateRepository - Transliterate Repository
      *
      * @return  void
      *-----------------------------------------------------------------------*/

    function __construct(TransliterateRepository $transliterateRepository)
    {
        $this->transliterateRepository = $transliterateRepository;
    }

    /**
     * Prepare transliterate support data
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareTransliterateData($entityType, $entityId, $entityKey)
    {
        $defaultLanguage = getStoreSettings('default_language');
        $availableLocale = [];
        $localeList = config('locale.available');
        foreach ($localeList as $localeKey => $locale) {
            if ($defaultLanguage != $localeKey) {
                $availableLocale[] = [
                    'id' => $localeKey,
                    'name' => $locale
                ];
            }
        }

        $isDefaultLangEng = false;
        $transliterateData = [];
        $transliterateDetails = $this->transliterateRepository
                                    ->fetch($entityType, $entityId, $entityKey, CURRENT_LOCALE);

        // Check if translatriate data is exist
        if (!__isEmpty($transliterateDetails)) {
            $transliterateData = [
                'language'       => $transliterateDetails->language,
                'translate_text' => $transliterateDetails->string
            ];
        }

        // Check if default language is english
        $defaultLangShortCode = substr($defaultLanguage, 0, 2);
        if ($defaultLangShortCode == 'en') {
            $isDefaultLangEng = true;
        }

        return $this->engineReaction(1, [
            'availableLocale'           => $availableLocale,
            'transliterateData'         => $transliterateData,
            'isDefaultLangEng'          => $isDefaultLangEng,
            'storeDefaultLanguage'      => $defaultLangShortCode,
            'availableTransliterate'    => config('locale.available_transliterate_en')
        ]);
    }

    /**
     * process Transliterate Update
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     * @param array $inputData
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processTransliterateUpdate($entityType, $entityId, $entityKey, $inputData)
    {
        $transliterateData = $this->transliterateRepository->fetch($entityType, $entityId, $entityKey, $inputData['language']);

        if (!__isEmpty($transliterateData)) {
            
            $updateData = [
                '_id' => $transliterateData->_id,
                'string'  => $inputData['translate_text']
            ];

            // Check if Translation added successfully
            if ($this->transliterateRepository->updateTransliterate($transliterateData, $updateData)) {
                return $this->engineReaction(1, null, __tr('Translation updated successfully.'));
            }

        } elseif (__isEmpty($transliterateData)) {
            $storeData = [
                'entity_type'   => $entityType,
                'entity_id'     => ($entityId != 'null') ? $entityId : null,
                'entity_key'    => $entityKey,
                'language'      => $inputData['language'],
                'string'        => $inputData['translate_text']
            ];

            // Check if Translation added successfully
            if ($this->transliterateRepository->storeTransliterateData($storeData)) {
                return $this->engineReaction(1, null, __tr('Translation added successfully.'));
            }
        }

        return $this->engineReaction(14, null, __tr('Nothing to update.'));
    }

    /**
     * Prepare Transalate Data
     *
     * @param string $entityType
     * @param number $entityId
     * @param string $entityKey
     * @param string $language
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareTranslateData($entityType, $entityId, $entityKey, $language)
    {
        if ($entityId == 'null') {
            $entityId = null;
        }
        
        $translateDetails = $this->transliterateRepository->fetch($entityType, $entityId, $entityKey, $language);

        $transliterateData = [];
        if (!__isEmpty($translateDetails)) {
            $transliterateData = [
                'translate_text' => $translateDetails->string
            ];
        }
        
        return $this->engineReaction(1, [
            'transliterateData' => $transliterateData
        ]);
    }

    /**
     * Prepare Transalate Data
     *
     * @param array $inputData
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareOriginalTextForTranslation($inputData)
    {
        return $this->engineReaction(1, [
            'originalText' => strip_tags($inputData['string'])
        ]);
    }
}