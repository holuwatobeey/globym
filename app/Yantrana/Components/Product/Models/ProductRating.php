<?php

/*
* ProductRating.php - Model file
*
* This file is part of the ProductRating component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\User\Models\User;

class ProductRating extends BaseModel
{
    /**
     * @var string $table - The database table used by the model.
     */
    protected $table = "product_ratings";

	protected $primaryKey = '_id';

    /**
     * @var array $casts - The attributes that should be casted to native types.
     */
    protected $casts = [
        "_id"        	=> "integer",
        "rating"    	=> "integer",
        "products_id" 	=> "integer",
        "users_id" 		=> "integer"
    ];

    /**
     * @var array $fillable - The attributes that are mass assignable.
     */
    protected $fillable = [];


  /**
    * relation ship with rating table
    *
    * @return collection
    *-----------------------------------------------------------------------*/

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

	 /**
     * Fetch option set exist for this product.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }
}
