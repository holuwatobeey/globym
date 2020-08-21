<?php

namespace App\Yantrana\Components\User\Models;

use App\Yantrana\Core\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Yantrana\Components\User\Models\SocialAccess;

class User extends BaseModel implements
    AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'role' => 'integer',
        'banned' => 'integer',
        '__permissions'     => 'array',
    ];

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
    
    // public function getEmailAttribute()
    // {
    //     return maskEmailId($this->attributes['email']);
    // }

	/**
    * Get the profile record associated with the user.
    */
    public function socialAccess()
    {
        return $this->hasOne(SocialAccess::class, 'users_id');
    }
}
