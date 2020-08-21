<?php
/*
* SocialAccess.php - Model file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Models;

use App\Yantrana\Core\BaseModel;

class SocialAccess extends BaseModel
{
    /**
     * @var string $table - The database table used by the model.
     */
    protected $table = "social_user_registrations";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The generate UID or not
     *
     * @var string
     *----------------------------------------------------------------------- */
    
    //protected $isGenerateUID = false;

    /**
     * @var array $casts - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id'       => 'integer',
        'provider'  => 'integer',
        'users_id'  => 'integer'
    ];

    /**
     * @var array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
