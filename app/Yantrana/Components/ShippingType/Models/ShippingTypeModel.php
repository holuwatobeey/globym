<?php
/*
* ShippingType.php - Model file
*
* This file is part of the ShippingType component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShippingType\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Shipping\Models\Shipping;

class ShippingTypeModel extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "shipping_types";

     /*
     * The custom primary key.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $primaryKey = '_id';
    
    /**
     * @var  array $casts - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var  array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function shipping()
    {
        return $this->hasMany(Shipping::class, 'shipping_types__id', '_id');
    }

}