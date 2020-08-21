<?php
/*
* SpecificationValue.php - Model file
*
* This file is part of the SpecificationValue component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SpecificationsPreset\Models;

use App\Yantrana\Core\BaseModel;

class SpecificationValue extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "specification_values";

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

}