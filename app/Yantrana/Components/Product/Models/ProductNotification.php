<?php

/*
* ProductNotification.php - Model file
*
* This file is part of the ProductNotification component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\User\Models\User;

class ProductNotification extends BaseModel
{
    /**
     * @var string $table - The database table used by the model.
     */
    protected $table = "product_notifications";

	protected $primaryKey = '_id';

    /**
     * @var array $casts - The attributes that should be casted to native types.
     */
    protected $casts = [
        "_id"        	=> "integer",
        "products_id" 	=> "integer",
        "users_id" 		=> "integer"
    ];

    /**
     * @var array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [];

}
