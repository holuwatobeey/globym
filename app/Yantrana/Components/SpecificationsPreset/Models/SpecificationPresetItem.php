<?php
/*
* SpecificationPresetItem.php - Model file
*
* This file is part of the SpecificationPresetItem component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\SpecificationsPreset\Models\Specification;  

class SpecificationPresetItem extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "specification_preset_items";

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
    public function specification()
    {
        return $this->belongsTo(Specification::class, 'specifications__id', '_id');
    }
}