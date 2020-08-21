<?php
/*
* SpecificationController.php - Controller file
*
* This file is part of the SpecificationController component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\SpecificationsPreset\SpecificationEngine;
use App\Yantrana\Components\SpecificationsPreset\Requests\PresetAddRequest;
use App\Yantrana\Components\SpecificationsPreset\Requests\PresetEditRequest;
 
class SpecificationController extends BaseController 
{    
    /**
     * @var  SpecificationPresetEngine $specificationPresetEngine - Sample Engine
     */
    protected $specificationPresetEngine;

    /**
      * Constructor
      *
      * @param  SampleEngine $sampleEngine - Sample Engine
      *
      * @return  void
      *-----------------------------------------------------------------------*/

    function __construct(SpecificationEngine $specificationEngine)
    {
        $this->specificationEngine = $specificationEngine;
    }

     
    /**
    * Request to engine for sample data
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function specificationsPresetList() 
    {       
        return $this->specificationEngine->prepareDatatableList();
    }

    /**
      * Preset create process 
      *
      * @param  object PresetListRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function processPresetCreate(PresetAddRequest $request)
    {  
        $processReaction = $this->specificationEngine
                                ->processPresetCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
      * Specification Preset get update data 
      *
      * @param  mix $presetId
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function editSupportData($presetId)
    {   
        $processReaction = $this->specificationEngine
                            ->preparePresetUpdateData($presetId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Specification Preset get label data 
      *
      * @param  mix $presetId
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function getPresetLabels($presetId)
    {   
        $processReaction = $this->specificationEngine
                            ->preparePresetLabelData($presetId);

        return __processResponse($processReaction, [], [], true);
    }


    /**
      * Specification Preset process update 
      * 
      * @param  mix @param  mix $presetId
      * @param  object PresetListRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function editPreset($presetId, PresetEditRequest $request)
    {   
        $processReaction = $this->specificationEngine
                                ->processEditPreset($presetId, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
    * Request for delete Specification 
    *
    * @param object $specificationId
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function processDeleteSpecification($specificationId) 
    {        
        $processReaction = $this->specificationEngine->deleteSpecification($specificationId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
    * Request for delete Specification 
    *
    * @param object $specificationId
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function processDeletePreset($presetId) 
    {        
        $processReaction = $this->specificationEngine->deleteSpecificationPreset($presetId);

        return __processResponse($processReaction, [
                    1 => __tr('Specification Preset deleted successfully.'),
                    2 => __tr('Something went wrong.'),
                    18 => __tr('Specification Preset does not exist.'),
                ]);
    }

}