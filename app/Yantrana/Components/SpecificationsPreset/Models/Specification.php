<?php
/*
* SpecificationPreset.php - Model file
*
* This file is part of the SpecificationPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Product\Models\ProductSpecification;

class Specification extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "specifications";

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
     * @return array specifications__id
     *---------------------------------------------------------------- */
    public function productSpecification()
    {
        return $this->belongsTo(ProductSpecification::class, '_id', 'specifications__id');
    }
}