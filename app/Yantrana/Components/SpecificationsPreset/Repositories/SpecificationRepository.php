<?php
/*
* SpecificationRepository.php - Repository file
*
* This file is part of the SpecificationPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Repositories;

use App\Yantrana\Core\BaseRepository;
 
use App\Yantrana\Components\SpecificationsPreset\Models\SpecificationPreset;
use App\Yantrana\Components\SpecificationsPreset\Models\Specification;  
use App\Yantrana\Components\SpecificationsPreset\Models\SpecificationPresetItem; 

class SpecificationRepository extends BaseRepository
{ 
    
    /**
      * Fetch the record of SpecificationPreset
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetch($idOrUid)
    {   
        if (is_numeric($idOrUid)) {

            return SpecificationPreset::where('_id', $idOrUid)->first();
        }

        return SpecificationPreset::where('_uid', $idOrUid)->first();
    }

    /**
      * Fetch the record of SpecificationPreset
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchAllSpecificationPreset()
    {   
        return SpecificationPreset::select('_id', 'title')->get();
    }

    /**
      * Fetch the record of SpecificationPreset
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchAllSpecificationPresetById($presetId)
    {   
        return SpecificationPreset::leftjoin('specification_preset_items', 'specification_presets._id', '=', 'specification_preset_items.specification_presets__id')
                                    ->leftjoin('specifications', 'specification_preset_items.specifications__id', '=', 'specifications._id')
                                    ->where('specification_presets._id', '=', $presetId)
                                    ->select(
                                        __nestedKeyValues([
                                            'specification_presets' => [
                                               '_id',
                                               'title'
                                            ],
                                            'specifications' => [
                                                '_id as specID',
                                                'label'
                                            ]
                                        ])
                                    )
                                    ->get();
    }

    /**
      * Fetch the record of SpecificationPreset
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchSpecificationById($specificationId)
    {   
        return Specification::where('_id', $specificationId)->first();
    }

     /**
      * Fetch the record of SpecificationPreset
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchSpecifionPresetById($presetId)
    {   
        return SpecificationPreset::where('_id', $presetId)
                                    ->with([
                                        'presetItem' => function($query) {
                                            $query->with('specification');
                                        }
                                    ])->first();
    }

    /**
      * Fetch all tags
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchSpecifications()
    {
        return Specification::get(['_id', 'label', 'status']);
    }

    
    /**
      * Fetch SpecificationPreset datatable source
      *
      * @return array
      *---------------------------------------------------------------- */
 
    public function fetchDataTableSource()
    {   
        $dataTableConfig = [
            'searchable' => [
                'title'    
            ]
        ];

        return SpecificationPreset::with([
                                        'presetItem' => function($query) {
                                            $query->with('specification');
                                        }
                                    ])->dataTables($dataTableConfig)->toArray();
    }

    /**
      * Store new SpecificationPreset record and return response
      *
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */

    public function storePreset($inputData)
    {   
        $keyValues = [
            'title' => $inputData['title'],
            'status' => 1   
        ];

        $specificationPreset = new SpecificationPreset;
        
        // Check if task testing record added then return positive response
        if ($specificationPreset->assignInputsAndSave($inputData, $keyValues)) {
            $specificationPresetId = $specificationPreset->_id;
            $specficationLabels = $inputData['specficationLabels'];
            $specificationStoreData = $presetItems = [];

            foreach ($specficationLabels as $key => $label) {
                $specification = new Specification;
                $specificationStoreData = [
                    'label'      => $label['label'],
                    'use_for_filter' => $label['use_for_filter'],
                    'status'    => 1
                ];

                if ($specification->assignInputsAndSave($label, $specificationStoreData)) {
                    $presetItems[] = [
                        'specification_presets__id' => $specificationPresetId,
                        'specifications__id' => $specification->_id
                    ];
                }
            }

            $specificationPresetItem = new SpecificationPresetItem;
            if ($specificationPresetItem->prepareAndInsert($presetItems, '_id')) {
                return true;
            }
        }

        return false;
    }

    /**
      * Update preset record and return response
      *
      * @param  object $presetData
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */

    public function updatePreset($presetData, $inputData)
    {      
        // Check if sample updated then return positive response
        if ($presetData->modelUpdate($inputData)) {

            return $presetData->_id;
        }

        return false;
    }

    /**
      * Update preset record and return response
      *
      * @param  object $presetData
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */
    public function storeSpecification($storeData, $presetId)
    {      
        if (!__isEmpty($storeData)) { 
            $specificationPresetItem = [];
            foreach ($storeData as $key => $input) {
              
                $specification = new Specification;
                $specificationStoreData = [
                    'label',
                    'use_for_filter',
                    'status'
                ];

                if ($specification->assignInputsAndSave($input, $specificationStoreData)) {
                    $specificationPresetItem[] = [
                        'specification_presets__id' => $presetId,
                        'specifications__id' => $specification->_id
                    ];
                }
            }

            $newSpecificationPresetItem = new SpecificationPresetItem;
            if ($newSpecificationPresetItem->prepareAndInsert($specificationPresetItem, '_id')) {
                return true;
            }
        }
        
        return false;     
    }

    /**
      * Update preset record and return response
      *
      * @param  object $presetData
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */
    public function updateSpecification($updateData)
    {
        return Specification::bunchUpdate($updateData, '_id');
    }

    /**
    * Delete sample record
    *
    * @param object $specification
    *
    * @return boolean
    *---------------------------------------------------------------- */
 
    public function delete($specification)
    {  
        if ($specification->delete()) {
            activityLog('ID of '.$specification->_id.' specification deleted.');
            return true;
        }

        return false;
    }

    /**
     * Fetch Preset with Specification
     *
     * @param array $specificationPresetIds
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchPresetWithSpecification($specificationPresetIds, $productID)
    {
        return SpecificationPreset::where('_id', $specificationPresetIds)
                                    ->with([
                                        'presetItem' => function($presetItem) use($productID) {
                                            $presetItem->with([
                                                'specification' => function($query) use($productID) {
                                                    $query->with([
                                                        'productSpecification' => function($q) use($productID) {
                                                            $q->where('products_id', $productID);
                                                        }
                                                    ]);
                                                }
                                            ]);
                                        }
                                    ])
                                    ->get();
    }

    /**
     * Fetch Other Product Specification
     *
     * @param int $specificationIds
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchOtherProductSpecifications($specificationIds)
    {
        return Specification::whereNotIn('specifications._id', $specificationIds)->get();
    }
}