<?php
/*
* ShippingTypeEngine.php - Main component file
*
* This file is part of the ShippingTypeEngine component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShippingType;

use App\Yantrana\Core\BaseEngine;
use App\Yantrana\Components\ShippingType\Repositories\ShippingTypeRepository;
use Carbon\Carbon;

class ShippingTypeEngine extends BaseEngine
{   
     
    /**
     * @var  ShippingTypeRepository $shippingTypeRepository - ShippingType Repository
     */
    protected $shippingTypeRepository;
    
    /**
    * Constructor
    *
    * @param  ShippingTypeRepository $shippingTypeRepository - ShippingType Repository
    *
    * @return  void
    *-----------------------------------------------------------------------*/
    function __construct(ShippingTypeRepository $shippingTypeRepository)
    {
        $this->shippingTypeRepository = $shippingTypeRepository;
    }

    
  /**
    * ShippingType datatable source 
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function prepareDatatableShippingTypeList()
    {
        $shippingTypeCollection = $this->shippingTypeRepository->fetchShippingTypeDataTableSource();

        $requireColumns = [
            '_id', 
            'title',  
            'createdOn' => function($key) {
                return formatDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'canAccessEdit' => function() {
                if (canAccess('manage.shipping_type.read.update.data') and canAccess('manage.shipping_type.write.update')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.shipping_type.write.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return $this->dataTableResponse($shippingTypeCollection, $requireColumns);

    }

    /**
      * ShippingType create 
      *
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processShippingTypeCreate($inputData)
    {   
        if ($this->shippingTypeRepository->storeShippingType($inputData)) {

            return $this->engineReaction(1, null, __tr('Shipping Type added.'));
        }

        return $this->engineReaction(2, null, __tr('Shipping Type not added.'));
    }

    /**
      * ShippingType prepare update data 
      *
      * @param  mix $shippingTypeId
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function shippingTypeEditData($shippingTypeId)
    {   
        $shippingType = $this->shippingTypeRepository->fetch($shippingTypeId);
      
        // Check if $shippingType not exist then throw not found 
        // exception
        if (__isEmpty($shippingType)) {
            return $this->engineReaction(18, null, __tr('Shipping Type not found.'));
        }

        $shippingTypeData = [
            'id' => $shippingType->_id,
            'title' => $shippingType->title
        ];

        return $this->engineReaction(1, [
            'shippingTypeData' => $shippingTypeData
        ]);
    }

    /**
      * ShippingType process update 
      * 
      * @param  mix $shippingTypeIdshippingTypeId
      * @param  array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function shippingTypeUpdate($shippingTypeId, $inputData)
    {   
        $shippingType = $this->shippingTypeRepository->fetch($shippingTypeId);

        // Check if $shippingType not exist then throw not found 
        // exception
        if (__isEmpty($shippingType)) {
            return $this->engineReaction(18, null, __tr('Shipping Type not found.'));
        }

        $updateData = [
            'title' => $inputData['title']
        ];
        
        // Check if Shipping Type updated
        if ($this->shippingTypeRepository->updateShippingType($shippingType,  $updateData)) {

            return $this->engineReaction(1, null, __tr('Shipping Type updated.'));
        }

        return $this->engineReaction(14, null, __tr('Shipping Type not updated.'));
    }

    /**
     * process of delete ShippingType.
     *
     * @param int $presetId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function deleteShippingType($shippingTypeId)
    {
        $shippingType = $this->shippingTypeRepository->fetch($shippingTypeId);

        // Check if $shippingType not exist then throw not found 
        // exception
        if (__isEmpty($shippingType)) {
            return $this->engineReaction(18, null, __tr('Shipping Type not found.'));
        }

        // Fetch specification preset data with count
        $shippingData = $this->shippingTypeRepository->fetchShippingCount($shippingType->_id);

        $shippingCount = $shippingData->shipping_count;
    
        // Check if current Shipping Type contain any shipping
        if ($shippingCount !== 0) {
            return $this->engineReaction(2, null, __tr('This Shipping Method contain __shippingCount__ shipping __rule__, you need to delete first related shipping __rule__.', [
                    '__shippingCount__' => $shippingCount,
                    '__rule__'      => ($shippingCount == 1) ? 'rule' : 'rules'
                ]));
        }

        // If Tax Deleted successfully then return engine reaction
        if ($this->shippingTypeRepository->delete($shippingType)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

}
