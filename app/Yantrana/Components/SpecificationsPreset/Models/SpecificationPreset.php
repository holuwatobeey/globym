<?php
/*
* SpecificationPreset.php - Model file
*
* This file is part of the SpecificationPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\SpecificationsPreset\Models\SpecificationPresetItem; 
use App\Yantrana\Components\Category\Models\Category;

class SpecificationPreset extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "specification_presets";

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
    public function presetItem()
    {
        return $this->hasMany(SpecificationPresetItem::class, 'specification_presets__id', '_id');
    }
}