<?php
/*
* TransliterateRepository.php - Repository file
*
* This file is part of the Transliterate component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Transliterate\Repositories;

use App\Yantrana\Core\BaseRepository;
 
use App\Yantrana\Components\Transliterate\Models\TransliterateModel;
use App\Yantrana\Components\Transliterate\Interfaces\TransliterateRepositoryInterface;

class TransliterateRepository extends BaseRepository
                          implements TransliterateRepositoryInterface 
{ 
    
    /**
      * Fetch the record of Transliterate
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetch($entityType, $entityId, $entityKey, $language)
    {   
        return TransliterateModel::where([
                                    'entity_type'   => $entityType,
                                    'entity_id'     => $entityId,
                                    'entity_key'    => $entityKey,
                                    'language'      => $language
                                ])->first();
    }

    /**
      * Fetch the record of Transliterate
      *
      * @param array $storeData
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function storeTransliterateData($storeData)
    {
        $keyValues = [
            'entity_type',
            'entity_id',
            'entity_key',
            'language',
            'string'
        ];

        $transliterateModel = new TransliterateModel;

        // Check if transliterae stored successfully
        if ($transliterateModel->assignInputsAndSave($storeData, $keyValues)) {
            return true;
        }

        return false;
    }

    /**
      * Fetch the record of Transliterate
      *
      * @param array $storeData
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function updateTransliterate($transliterateData, $updateData)
    {
        if ($transliterateData->modelUpdate($updateData)) {
            return true;
        }

        return false;
    }

    /**
      * Fetch all record of Transliterate
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function fetchAll()
    {
        return TransliterateModel::get();
    }
}