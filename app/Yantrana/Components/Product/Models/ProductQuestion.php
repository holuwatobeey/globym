<?php
/*
* ProductQuestion.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\User\Models\User;
use DB;

class ProductQuestion extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'product_questions';

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
        'products_id' => 'integer'
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * @var array - Stop created_at and updated_at.
     */
    public $timestamps = true;

    /**
     * Fetch option set exist for this product.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

     /**
     * Fetch option set exist for this product.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id')->select( 'id', DB::raw('CONCAT(users.fname, " ", users.lname) AS userFullName'));
    }
}
