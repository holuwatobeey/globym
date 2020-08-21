<?php
/*
* ShippingTypeRepository.php - Repository file
*
* This file is part of the ShippingType component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShippingType\Repositories;

use App\Yantrana\Core\BaseRepository;
 
use App\Yantrana\Components\ShippingType\Models\ShippingTypeModel;  

class ShippingTypeRepository extends BaseRepository
{ 
    
    /**
      * Fetch the record of ShippingType
      *
      * @param    int || string $idOrUid
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetch($idOrUid)
    {   
        return ShippingTypeModel::where('_id', $idOrUid)->first();
    }

    /**
      * Fetch allShippingType
      *
      * @return    eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchAll()
    {   
        return ShippingTypeModel::get();
    }

    
    /**
      * Fetch sample datatable source
      *
      * @return array
      *---------------------------------------------------------------- */
 
    public function fetchShippingTypeDataTableSource()
    {   
        $dataTableConfig = [
            'searchable' => [
                'title',
                'created_at'          
            ]
        ];

        return ShippingTypeModel::dataTables($dataTableConfig)->toArray();
    }

    /**
      * Store new ShippingType record and return response
      *
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */

    public function storeShippingType($inputData)
    {   
        $keyValues = [
            'title' => $inputData['title'],
            'status' => 1   
        ];

        $ShippingType = new ShippingTypeModel;
        
        // Check if task testing record added then return positive response
        if ($ShippingType->assignInputsAndSave($inputData, $keyValues)) {

            return true;
        }

        return false;
    }

    /**
      * Update ShippingType record and return response
      *
      * @param  object $ShippingType
      * @param  array $inputData
      *
      * @return  mixed
      *---------------------------------------------------------------- */

    public function updateShippingType($ShippingType, $inputData)
    {       
        // Check if sample updated then return positive response
        if ($ShippingType->modelUpdate($inputData)) {

            return true;
        }

        return false;
    }

    /**
     * Fetch Category count related to user role
     *
     * @param number $user_roles__id
     *
     * @return Eloquent collection object
     *-----------------------------------------------------------------------*/
    public function fetchShippingCount($shippingTypeId)
    {
        return ShippingTypeModel::where('_id', $shippingTypeId)
                        ->withCount(['shipping' => function ($query) use ($shippingTypeId) {
                            $query->where('shipping_types__id', $shippingTypeId);
                        }])
                        ->first();
    }

    /**
    * Delete shipping Type record
    *
    * @param object $shipping Type
    *
    * @return boolean
    *---------------------------------------------------------------- */
 
    public function delete($shippingType)
    {  
        if ($shippingType->delete()) {
            activityLog('ID of '.$shippingType->_id.' shipping type deleted.');
            return true;
        }

        return false;
    }

}