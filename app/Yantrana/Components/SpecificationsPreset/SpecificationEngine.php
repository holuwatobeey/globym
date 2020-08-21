<?php
/*
* SpecificationEngine.php - Main component file
*
* This file is part of the SpecificationPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset;

use App\Yantrana\Core\BaseEngine;
use App\Yantrana\Components\SpecificationsPreset\Repositories\SpecificationRepository;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;

class SpecificationEngine extends BaseEngine
{   
     
    /**
     * @var  SpecificationPresetRepository $specificationRepository - SpecificationPreset Repository
     */
    protected $specificationRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;
    
    /**
    * Constructor
    *
    * @param  SpecificationPresetRepository $specificationRepository - SpecificationPreset Repository
    *
    * @return  void
    *-----------------------------------------------------------------------*/
    function __construct(
        SpecificationRepository $specificationRepository,
        ManageProductRepository $manageProductRepository
)
    {
        $this->specificationRepository = $specificationRepository;
        $this->manageProductRepository = $manageProductRepository;
    }

    
  /**
    * SpecificationPreset datatable source 
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function prepareDatatableList()
    {
        $specificationCollection = $this->specificationRepository->fetchDataTableSource();
        
        $requireColumns = [
            '_id', 
            'title',
            'formatted_labels' => function($key) { 
                $labels = [];
                if (!__isEmpty($key['preset_item'])) {
                    foreach ($key['preset_item'] as $key => $label) {
                        $labels[] = $label['specification']['label'];
                    }

                    return implode(', ', $labels);
                }
                
                return 'N/A';
                
            },
            'canAccessEdit' => function() {
                if (canAccess('manage.specification_preset.read.editSupportadd') and canAccess('manage.specification_preset.write.update')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.specification_preset.write.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return $this->dataTableResponse($specificationCollection, $requireColumns);

    }

    /**
      * Preset create 
      *
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processPresetCreate($inputData)
    {   
        if ($this->specificationRepository->storePreset($inputData)) {

            return $this->engineReaction(1, null, __tr('Preset added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Preset not added.'));
    }

    /**
      * Preset prepare update data 
      *
      * @param  mix $presetId
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function preparePresetLabelData($presetId)
    {   
        $presetData = $this->specificationRepository->fetchAllSpecificationPresetById($presetId);

        // Check if $presetData not exist then throw not found 
        // exception
        if (__isEmpty($presetData)) {
            return $this->engineReaction(18, null, __tr('Preset not found.'));
        }

        $specId = [];
        $specLabels = [];
        $presetTitle = [];
        if (!__isEmpty($presetData)) {
            foreach ($presetData as $key => $presets) {
                $specLabels[]   = $presets['label'];
                $specId         = $presets['_id'];
                $presetTitle    = $presets['title'];
            }
        }

        $presetLabels = [
            'id'        => $specId,
            'labels'    => implode(', ', $specLabels),
            'presetTitle' => $presetTitle
        ];
       
        return $this->engineReaction(1, [
            'presetLabels' => $presetLabels
        ]);
    }

    /**
      * Preset prepare update data 
      *
      * @param  mix $presetId
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function preparePresetUpdateData($presetId)
    {   
        $presetData = $this->specificationRepository->fetchSpecifionPresetById($presetId);

        // Check if $presetData not exist then throw not found 
        // exception
        if (__isEmpty($presetData)) {
            return $this->engineReaction(18, null, __tr('Preset not found.'));
        }
        
        $specificationPreset = $presetData->presetItem;

        $specficationLabels = [];
        if (!__isEmpty($specificationPreset)) {
            foreach ($specificationPreset as $key => $labels) {
                $specficationLabels[] = [
                    '_id'    => $labels->specification->_id,
                    'label'  => $labels->specification->label,
                    'use_for_filter' => (bool) $labels->specification->use_for_filter
                ];
            }
        }

        $presetData = [
            '_id'                   => $presetData->_id,
            'title'                 => $presetData->title,
            'specficationLabels'    => $specficationLabels
        ];
        
        return $this->engineReaction(1, [
            'presetData' => $presetData
        ]);
    }

    /**
      * Preset process update 
      * 
      * @param  mix $presetId
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processEditPreset($presetId, $inputData)
    {   
        $presetData = $this->specificationRepository->fetch($presetId);

        // Check if $presetData not exist then throw not found 
        // exception
        if (__isEmpty($presetData)) {
            return $this->engineReaction(18, null, __tr('Specification Preset not found.'));
        }

        $isUpdated = false;

        $updateData = [
            'title' => $inputData['title']
        ];

        // Update product specification data
        if ($this->specificationRepository->updatePreset($presetData, $updateData)) {
            $isUpdated = true;
        }

        $specificationData = [];
        if (!__isEmpty($inputData['specficationLabels'])) {
            $specificationData = $inputData['specficationLabels'];
        }

        $specificationData = $inputData['specficationLabels'];

        $specificationStoreData = $specificationUpdateData = [];

        // Check specification data exist
        if (!__isEmpty($specificationData)) {
            foreach ($specificationData as $key => $specification) {
                if (!array_key_exists('_id', $specification)) {
                    $specificationStoreData[] = [
                        'label'             => $specification['label'],
                        'use_for_filter'    => $specification['use_for_filter'],
                        'status'            => 1
                    ];
                } elseif (array_key_exists('_id', $specification)) {
                    $specificationUpdateData[] = [
                        '_id'               => $specification['_id'],
                        'label'             => $specification['label'],
                        'use_for_filter'    => $specification['use_for_filter'],
                    ];
                }                    
            }
        }
       
        // Check if specification data stored
        if (!__isEmpty($specificationStoreData)) {
            if ($this->specificationRepository->storeSpecification($specificationStoreData, $presetData->_id)) {
                $isUpdated = true;
            }
        }
        

        // Check if specification data updated
        if (!__isEmpty($specificationUpdateData)) {
            if ($this->specificationRepository->updateSpecification($specificationUpdateData)) {
                $isUpdated = true;
            }
        }
        
        
        if ($isUpdated) {
            return $this->engineReaction(1, null, __tr('Preset updated successfully'));
        }        

        return $this->engineReaction(14, null, __tr('Preset not updated.'));
    }

    /**
    * Process delete Specification record
    *
    * @param string $specificationId
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function deleteSpecification($specificationId)
    {  
        $specification = $this->specificationRepository->fetchSpecificationById($specificationId);
        
        // Check the $specification contain data if not then fire not found error message
        if (__isEmpty($specification)) {
            return $this->engineReaction(18, null, 'The Specification does not exists.');    
        }
        
        // Check the $specification record is deleted if true then fire success message otherwise file error message
        if ($this->specificationRepository->delete($specification)) {
            return $this->engineReaction(1, null, 'The Specification deleted successfully.');
        }

        return $this->engineReaction(2, null, 'The Specification not deleted.');
    }

     /**
     * process of delete Specification Preset.
     *
     * @param int $presetId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function deleteSpecificationPreset($presetId)
    {
        $presetData = $this->specificationRepository->fetch($presetId);

        // Check if $presetData not exist then throw not found 
        // exception
        if (__isEmpty($presetData)) {
            return $this->engineReaction(18, null, __tr('Specification Preset not found.'));
        }

        $productCount = $this->manageProductRepository->fetchProductCountByPreset($presetData->_id);

        if ($productCount > 0) {
            return $this->engineReaction(2, null, __tr('Specification Preset is available in __product__, first remove these presets from product and try again!', [
                '__product__' => ($productCount == 1) ? $productCount.' product' : $productCount.' products'
            ]));
        }

        // If Tax Deleted successfully then return engine reaction
        if ($this->specificationRepository->delete($presetData)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

}