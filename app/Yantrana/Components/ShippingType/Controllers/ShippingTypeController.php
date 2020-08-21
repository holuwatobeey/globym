<?php
/*
* ShippingTypeController.php - Controller file
*
* This file is part of the ShippingType component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShippingType\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShippingType\ShippingTypeEngine;
use App\Yantrana\Components\ShippingType\Requests\ShippingTypeAddRequest;
use App\Yantrana\Components\ShippingType\Requests\ShippingTypeEditRequest;

class ShippingTypeController extends BaseController 
{    
    /**
     * @var  ShippingTypeEngine $shippingTypeEngine - ShippingType Engine
     */
    protected $shippingTypeEngine;

    /**
      * Constructor
      *
      * @param  ShippingTypeEngine $shippingTypeEngine - ShippingType Engine
      *
      * @return  void
      *-----------------------------------------------------------------------*/

    function __construct(ShippingTypeEngine $shippingTypeEngine)
    {
        $this->shippingTypeEngine = $shippingTypeEngine;
    }

     
    /**
    * Request to engine for ShippingTyp data
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function prepareShippingTypeList() 
    {        
        return $this->shippingTypeEngine->prepareDatatableShippingTypeList();
    }

    /**
      * ShippingType create process 
      *
      * @param  object ShippingTypeAddRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function processShippingTypeCreate(ShippingTypeAddRequest $request)
    {   
        $processReaction = $this->shippingTypeEngine
                                ->processShippingTypeCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
      * ShippingType get update data 
      *
      * @param  mix $shippingTypeId
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function updateShippingTypeData($shippingTypeId)
    {   
        $processReaction = $this->shippingTypeEngine
                            ->shippingTypeEditData($shippingTypeId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * ShippingType process update 
      * 
      * @param  mix @param  mix $shippingTypeId
      * @param  object ShippingTypeEditRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function processShippingTypeUpdate($shippingTypeId, ShippingTypeEditRequest $request)
    {   
        $processReaction = $this->shippingTypeEngine
                                ->shippingTypeUpdate($shippingTypeId, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
    * Request for delete ShippingType 
    *
    * @param object $shippingTypeId
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function processDeleteShippingType($shippingTypeId) 
    {        
        $processReaction = $this->shippingTypeEngine->deleteShippingType($shippingTypeId);

        return __processResponse($processReaction, [
                    1 => __tr('Shipping Type deleted successfully.'),
                    2 => __tr('Something went wrong.'),
                    18 => __tr('Shipping Type does not exist.'),
                ]);
    }

}