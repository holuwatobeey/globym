<?php
/*
* ProductOptionValue.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Product\Models\ProductImage;

class ProductOptionValue extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'product_option_values';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'addon_price' => 'float',
        'product_option_labels_id' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['productOptionLabel'];

    /**
     * Fetch option set exist for this product option.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function productOptionLabel()
    {
        return $this->belongsTo(ProductOptionLabel::class, 'product_option_labels_id');
    }


     /**
     * Fetch option set exist for this product option.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function productOptionImages()
    {
        return $this->hasMany(ProductImage::class,'product_option_values_id', 'id');
    }
}
