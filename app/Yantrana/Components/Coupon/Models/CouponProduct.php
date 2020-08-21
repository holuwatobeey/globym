<?php
/*
* CouponProduct.php - Model file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Product\Models\Product;

class CouponProduct extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'coupon_products';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id'           => 'integer',
        'coupons__id'   => 'integer',
        'products_id'   => 'integer'
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
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id');
    }
}
