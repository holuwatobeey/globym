<?php
/*
* Product.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\Brand\Models\Brand;
use App\Yantrana\Components\ShoppingCart\Models\OrderProduct;
use App\Yantrana\Components\Product\Models\ProductRating;
use App\Yantrana\Components\Product\Models\ProductSpecification;
use App\Yantrana\Components\Product\Models\ProductWishList;
use App\Yantrana\Components\Product\Models\ProductQuestion;
use Request;
use DB;

class Product extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'products';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
        'old_price' => 'float',
        'sbid' => 'integer',
        'max_price' => 'float',
        'min_price' => 'float',
        'featured' => 'boolean',
        'brands__id' => 'integer',
        'status' => 'integer',
        'out_of_stock' => 'integer',
        'isInvlidProduct' => 'boolean',
        '__data'    => 'array',
    ];

    /**
     * Let the system knows Text columns treated as JSON
     *
     * @var array
     *----------------------------------------------------------------------- */
    protected $jsonColumns = [
        '__data' => [
            'launching_date'  => 'string',
            'seo_meta_info'   => 'array'
        ]
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $sortOrderColumns = [
        'created_at',
        'name',
        'price',
    ];

    protected $defaultSortBy = 'created_at';

    protected $defaultSortOrder = 'asc';

    /**
     * Make products sortable.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function scopeSortOrder($query, $sortRequestItems = null, $allowedColumns = null, $selectedProducts = false)
    {
        $sortRequest = $sortRequestItems ?: Request::only(['sort_by', 'sort_order']);
 
        $sortBy = isset($sortRequest['sort_by']) ? $sortRequest['sort_by'] : null;
        $sortOrder = isset($sortRequest['sort_order']) ? $sortRequest['sort_order'] : null;

        if (!empty($selectedProducts) and empty($sortBy)) {

            $selectedProductsString = implode(',', $selectedProducts);

            return $query->orderByRaw(DB::raw("FIELD(products.id, $selectedProductsString)"));

        } elseif(empty($sortOrder)) {
            $sortOrder = 'desc';
        }
        
        //checking for empty and value mismatch
        if (!in_array($sortBy, $allowedColumns ?: $this->sortOrderColumns)) {
            $sortBy = 'products.created_at';
        }

        if (!isset($sortBy) or empty($sortBy)) {
            $sortBy = 'products.created_at';
        }

        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Take product with verify by brand.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function scopeVerifyByBrand($query, $inactiveBrandIds = [])
    {
        if (__isEmpty($inactiveBrandIds)) {
            return $query;
        }

        return $query->whereNotIn('products.brands__id', $inactiveBrandIds)
                     ->orWhereNull('products.brands__id');
    }

    /**
     * This method define the relationship b/w product & category.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function categories()
    {
        return $this->belongsToMany(Category::class,
            'product_categories',
            'products_id',
            'categories_id'
        )->select('categories.id', 'categories.name',
        'categories.status', 'categories.parent_id');
    }

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function brand()
    {
        return $this->hasOne(Brand::class, '_id', 'brands__id')
                    ->select('_id', 'name', 'status', 'logo');
    }

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function productRating()
    {
        return $this->hasMany(ProductRating::class, 'products_id')->select('products_id', '_id', 'rating');
    }

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function productFaq()
    {
        return $this->hasMany(ProductQuestion::class, 'products_id')->orderBy('created_at', 'desc')->take(configItem('product_detail_faq_count'))->with('user');
    }

    /**
     * This method define the relationship b/w product & brand.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'products_id')->select('products_id', '_id', 'status', 'created_at', 'updated_at', 'quantity');
    }

    /**
     * This method define the relationship b/w product & product option labels.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function option()
    {
        return $this->hasMany(ProductOptionLabel::class, 'products_id', 'id')
                    ->select('products_id', 'id', 'name', 'type');
    }

    /**
     * This method define the relationship b/w product & product option labels.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function productOptionValue()
    {
        return $this->hasMany(ProductOptionLabel::class, 'products_id', 'id')->with('optionValues');
    }

    /**
     * This method define the relationship b/w product & wishlist.
     *
     * @return array brands__id
     *---------------------------------------------------------------- */
    public function productWishlist()
    {
        return $this->hasOne(ProductWishList::class, 'products_id')
                    ->select('_id as wishlistId', 'products_id', 'users_id as addWishlistUserId');
    }

    /**
     * This method define the relationship b/w product & product option labels.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function checkOptionExists()
    {
        return $this->hasMany(ProductOptionLabel::class, 'products_id', 'id')
                    ->whereNotNull('products_id');
    }

    /**
     * This method define the relationship b/w product & its images.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function image()
    {
        return $this->hasMany(ProductImage::class, 'products_id', 'id')
                    ->orderBy('list_order', 'asc')
                    ->select('products_id', 'id', 'title', 'file_name', 'list_order', 'product_option_values_id');
    }

    /**
     * This method define the relationship b/w product & its related products.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function relatedProducts()
    {
        return $this->hasMany(RelatedProduct::class, 'products_id', 'id')
                    ->select('products_id', 'id', 'related_product_id');
    }

    /**
     * This method define the relationship b/w product & its related specification.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function productSpecification()
    {
        return $this->hasMany(ProductSpecification::class, 'products_id', 'id')->whereNotNull('specifications__id')->whereNotNull('specification_values__id')
                    ->select('products_id', '_id', 'value', 'specifications__id', 'specification_values__id')->with('specification', 'specificationValue');
    }

    /**
     * This method define the relationship b/w product & its related specification.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function oldProductSpecification()
    {
        return $this->hasMany(ProductSpecification::class, 'products_id', 'id')->whereNull('specifications__id')->whereNull('specification_values__id');
    }

      /**
     * This method define the relationship b/w product & its related specification.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function specification()
    {
        return $this->hasMany(ProductSpecification::class, 'products_id', 'id')
                    ->select('products_id', '_id', 'value', 'specifications__id', 'specification_values__id');
    }

    /**
     * Scope a query to only include search id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfId($query, $ID)
    {
        return $query->whereId($ID);
    }

    /**
     * Scope a query to only include product id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProduct($query, $IDs)
    {
        return $query->whereIn('id', $IDs);
    }

    /**
     * Scope a query to only include search id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    /**
     * Scope a query to only include search id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveProductAndCheckOutOfStock($query, $returnQuery = false)
    {
        $productsWhere = [
            'products.status' => 1,
        ];

        $showOutOfStock = getStoreSettings('show_out_of_stock');

        $isOutOFstock = __ifIsset($showOutOfStock, $showOutOfStock, 0);

        if (!$isOutOFstock) {
            $productsWhere['products.out_of_stock'] = 0;
        } 

        return $query->where($productsWhere);
    }

    /**
     * Scope a query to only include max & min price.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectMinAndMaxPrice($query)
    {
        return $query->select(DB::raw('MAX(price) as max_price'), DB::raw('MIN(price) as min_price'));
    }

    /**
     * Scope a query to only include brand count.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectBrandCount($query)
    {
        return $query->select(DB::raw('count(*) as product_count, brands__id'), 'brands.name as brandName');
    }

    /**
     * Scope a query to only include check the multiple category records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereInHasCategories($query, $catIds)
    {
        return  $query->whereHas('categories', function ($q) use ($catIds) {
            $q->whereIn('categories_id', $catIds);
        });
    }

    /**
     * Scope a query to only include check the category record.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereHasCategories($query, $catId)
    {
        return  $query->whereHas('categories', function ($q) use ($catId) {
            $q->where('categories_id', $catId);
        });
    }

    /**
     * Scope a query to only include brand count.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCategories($query, $catIds)
    {
        return  $query->with(['categories' => function ($q) use ($catIds) {
            $q->whereIn('categories_id', $catIds);
        }]);
    }

    /**
     * Scope a query to only include brand count.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBrandAndPrice($query, $data)
    {
        if (!empty($data['sbid'])) {
            $query->whereIn('brands__id', $data['sbid']);
        }
        
        if (isset($data['min_price'])) {
            $query->where('price', '>=', calculateBaseCurrency($data['min_price']));
        }

        if (isset($data['max_price'])) {
            $query->where('price', '<=', calculateBaseCurrency($data['max_price']));
        }

        return $query;
    }

    /**
     * Scope a query to only include specifications.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterProductData($query, $data)
    {
        if (isset($data['specification_values']) and !__isEmpty($data['specification_values'])) {
            $query->leftJoin('product_specifications', 'products.id', '=', 'product_specifications.products_id')
                  ->whereIn('product_specifications.specification_values__id', $data['specification_values'])->groupBy('product_specifications.products_id');
        }

        if (isset($data['rating']) and !__isEmpty($data['rating'])) {
            $query->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.products_id')
               //->select(DB::raw('AVG(product_ratings.rating) as productRating'))
               ->groupBy('product_ratings.products_id')
               ->havingRaw("AVG(product_ratings.rating) >= ?", [$data['rating']]);
        }

        if (isset($data['availability'])) {
            if ($data['availability'] == 1) { // in stock
                $query->where('products.out_of_stock', 0);
            } elseif ($data['availability'] == 2) { // out of stock
                $query->where('products.out_of_stock', 1);
            } else if ($data['availability'] == 3) { // Available Soon
                $query->whereIn('products.out_of_stock', [2,3]);
            }
        }

        if (isset($data['filter_category_ids']) 
            and !__isEmpty($data['filter_category_ids'])) {
            $query->leftJoin('product_categories', 'products.id', '=', 'product_categories.products_id')->whereIn('product_categories.categories_id', $data['filter_category_ids'])->groupBy('product_categories.products_id');
        }
        return $query;
    }

    /**
     * Scope a query to only include brand count.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhenMinAndMaxPrice($query, $data)
    {
        if (isset($data['min_price']) and  !__isEmpty($data['min_price'])) {
            $query->where('price', '>=', calculateBaseCurrency($data['min_price']));
        }

        if (isset($data['max_price']) and  !__isEmpty($data['max_price'])) {
            $query->where('price', '<=', calculateBaseCurrency($data['max_price']));
        }

        return $query;
    }

    /**
     * Scope a query to only include brand info.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhenBrand($query, $data)
    {
        if (isset($data['sbid']) and !__isEmpty($data['sbid'])) {
            $query->when($data['sbid'], function ($query) use ($data) {
                return $query->whereIn('brands__id', $data['sbid']);
            });
        }

        return $query;
    }

    /**
     * If the currencies are zero decimal then get round it
     *
     * @param  string  $value
     * @return string
     */
    public function getPriceAttribute($value)
    {
        return handleCurrencyAmount($value);
    }
}
