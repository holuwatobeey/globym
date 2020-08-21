<?php

/*
* UserRole.php - Model file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\User\Models\User;

class UserRole extends BaseModel
{
    /**
     * @var string $table - The database table used by the model.
     */
    protected $table = "user_roles";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @var array $casts - The attributes that should be casted to native types.
     */
    protected $casts = [
        "status"        => "integer",
        "__permissions" => "array",
    ];

    /**
     * @var array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Let the system knows Text columns treated as JSON
     *
     * @var array
     *----------------------------------------------------------------------- */
    protected $jsonColumns = [
        '__permissions' => [
            'allow' => 'array',
            'deny'  => 'array'
        ]
    ];

    /**
      * Get users related to the role
      *
      * @return void
      *---------------------------------------------------------------- */
    public function users()
    {
        return $this->hasMany(User::class, 'role', '_id');
    }

}
