<?php
/*
* Transliterate.php - Model file
*
* This file is part of the Transliterate component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Transliterate\Models;

use App\Yantrana\Core\BaseModel;

class TransliterateModel extends BaseModel 
{ 
    /**
     * @var  string $table - The database table used by the model.
     */
    protected $table = "transliterates";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The generate UID or not.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $isGenerateUID = true;

    /*
     * The custom primary key.
     *
     * @var string
     *----------------------------------------------------------------------- */

    protected $primaryKey = '_id';

    /**
     * The UID Name.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $UIDKey = '_id';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.locale.transliterate'
    ];
    
    /**
     * @var  array $casts - The attributes that should be casted to native types.
     */
    protected $casts = [
    ];

    /**
     * @var  array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [
    ];

}