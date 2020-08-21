<?php
/*
* ProductWishList.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;

class ProductWishList extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'wishlist';

    /*
     * The custom primary key.
     *
     * @var string
     *----------------------------------------------------------------------- */

    protected $primaryKey = '_id';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id' => 'integer',
        'users_id' => 'integer',
        'products_id' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * @var array - Stop created_at and updated_at.
     */
    public $timestamps = false;

    /**
     * @var array - Generate only created_at functionality.
     */
    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = currentDateTime(); // get current dateTime using selected timezone
        });

        parent::boot();
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
