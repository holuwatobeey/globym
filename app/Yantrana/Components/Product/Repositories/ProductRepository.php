<?php
/*
* ProductRepository.php - Repository file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\Product\Models\ProductCategory;
use App\Yantrana\Components\Product\Models\Product as ProductModel;
use App\Yantrana\Components\Product\Models\ProductOptionLabel as ProductOptionLabel;
use App\Yantrana\Components\ShoppingCart\Models\OrderProduct;
use App\Yantrana\Components\Product\Models\ProductRating;
use App\Yantrana\Components\Product\Models\ProductNotification;
use App\Yantrana\Components\Product\Models\ProductWishList;
use App\Yantrana\Components\Product\Models\ProductQuestion;
use App\Yantrana\Components\Product\Models\ProductSpecification;
use App\Yantrana\Components\SpecificationsPreset\Models\SpecificationValue; 

use App\Yantrana\Components\Product\Blueprints\ProductRepositoryBlueprint;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use Route;
use Config;
use DB;
use Auth;
use Searchy;

class ProductRepository extends BaseRepository implements ProductRepositoryBlueprint
{
    /**
     * @var ProductModel - Product Model
     */
    protected $product;

    /**
     * @var ProductModel - Product Model
     */
    protected $paginationArray;

    /**
     * @var $category - Category Model
     */
    protected $category;

    /**
     * @var
     */
    protected $allMyChilds;

    /**
     * @var ProductOptionLabel
     */
    protected $productOptions;

    /**
     * @var ProductCategory - ProductCategory Model
     */
    protected $productCategory;

    /**
     * @var categoryRepository - categoryRepository Repository
     */
    protected $categoryRepository;

	 /**
     * @var productRating - productRating Repository
     */
    protected $productRating;

     /**
     * @var productWishList - productWishList Repository
     */
    protected $productWishList;

     /**
     * @var productQuestionList - productQuestion Repository
     */
    protected $productQuestion;

     /**
     * @var productNotification - productNotification Repository
     */
    protected $productNotification;

     /**
     * @var productSpecification - productSpecification Repository
     */
    protected $productSpecification;

    /**
     * @var OrderProductModel - OrderProduct Model
     */
    protected $orderProduct;


    /**
     * Constructor.
     *
     * @param ProductModel $product - Product Model
     *-----------------------------------------------------------------------*/
    public function __construct(
        ProductModel $product, 
        Category $category,
        ProductCategory $productCategory, 
        ProductOptionLabel $productOptions,
        ManageCategoryRepository $categoryRepository, 
        ProductRating $productRating,
        ProductWishList $productWishList,
        ProductQuestion $productQuestion,
        ProductNotification $productNotification,
        ProductSpecification $productSpecification,
        OrderProduct $orderProduct
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->productCategory = $productCategory;
        $this->allMyChilds = [];
        $this->product = $product;
        $this->productOptions = $productOptions;
		$this->productRating = $productRating;
        $this->productWishList = $productWishList;
        $this->productQuestion = $productQuestion;
		$this->paginationArray = Config::get('__tech.pagination_rows');
        $this->productNotification = $productNotification;
        $this->productSpecification = $productSpecification;
        $this->orderProduct = $orderProduct;
    }

    /**
     * fetch all active products
     * 1 status is active.
     *
     * @param int $categoryIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategorieProducts($categoryIDs, $input = [], $inactiveBrandIds = [])
    {
        return $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds)
                    ->with('checkOptionExists')
                    ->brandAndPrice($input) // filter product when the max & min price available or brand
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description', 'created_at', '__data',
                               'old_price', 'specification_presets__id', 'out_of_stock', 'featured',
                            ],
                        ])
                    )
                    ->whereInHasCategories($categoryIDs)
                    ->paginate($this->getPaginationCount());
    }

    /**
     * fetch all active products without pagination.
     *
     * @param int $categoryIDs
     * @param int $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategorieWithoutPaginate($categoryIDs, $route, $inactiveBrandIds)
    {
        $query = $this->product
                    ->verifyByBrand($inactiveBrandIds)
                    ->activeProductAndCheckOutOfStock()
                    ->whereInHasCategories($categoryIDs);

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->whereNotNull('products.brands__id')
                         ->groupBy('products.brands__id')
                         ->pluck('products.brands__id')
                         ->toArray();
    }

    /**
     * get all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategories()
    {
        return $this->categoryRepository->fetchAll();
    }

    /**
     * Fetch all latest products.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchLatestProduct($latestProductCount, $inactiveBrandIds, $activeCatIds)
    {
        return $this->product->with('productRating')
                            ->latest()
                            ->whereInHasCategories($activeCatIds)
                            ->verifyByBrand($inactiveBrandIds)
                            ->take($latestProductCount)
                            ->where('status', 1)
                            ->get();
    }

    /**
     * Fetch all latest products.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchLatestSaleProducts($startDate, $endDate, $durationType, $inactiveBrandIds, $activeCatIds)
    {
        // If order status any and date is created_at
        $query = $this->product;

        return $query->with(['orderProducts' => function ($query) use ($durationType, $startDate, $endDate) {
                    if ($durationType != 7) {
                        
                        $query->whereBetween(DB::raw('DATE(ordered_products.updated_at)'), [$startDate, $endDate])->where('ordered_products.products_id', '!=', null)
                            ->where('status', '!=', 3);

                    } elseif ($durationType == 7) {
                        $query->where('ordered_products.products_id', '!=', null)
                            ->where('status', '!=', 3);
                    } 

                }])
                ->whereInHasCategories($activeCatIds)
                ->verifyByBrand($inactiveBrandIds)
                ->get();
    }

    /**
     * Fetch all featured products.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchFeaturedProduct($featuredProductCount, $inactiveBrandIds, $activeCatIds)
    {
        return $this->product->with('productRating')
                             ->where('featured', '=', 1)
                             ->whereInHasCategories($activeCatIds)
                             ->verifyByBrand($inactiveBrandIds)
                             ->take($featuredProductCount)
                             ->where('status', 1)
                             ->latest()
                             ->get();
    }

     /**
     * Fetch all featured products.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchPopularProduct($inactiveBrandIds, $activeCatIds)
    {
        return $this->product->with(['orderProducts' => function ($query) {
                    $query->where('ordered_products.products_id', '!=', null)->where('status', '!=', 3);
                }])
                ->with('productRating')
                ->whereInHasCategories($activeCatIds)
                ->verifyByBrand($inactiveBrandIds)
                ->get();
    }

    /**
     * get all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategories($categoriesID = [])
    {
        return  $this->productCategory
                    ->whereIn('categories_id', $categoriesID)
                    ->get(['categories_id', 'products_id']);
    }

    /**
     * fetch all active product count.
     *
     * @param int $categoryID
     *
     * @return int
     *---------------------------------------------------------------- */
    public function fetchProductsCount($categoryID)
    {
        $product = $this->product->isStatus(1); // active

        if (!empty($categoryID)) {
            $product->whereInHasCategories($categoryID);
        }

        return $product->get();
    }

    /**
     * get active featured products.
     *
     * @param int $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProduct($productID)
    {
        return $this->product->where([
                                'id' => $productID,
                                'status' => 1, // active
                            ])->first();
    }

    /**
     * get active featured products.
     *
     * @param int $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($productID)
    {
        return $this->product
                    ->with('categories')
                    ->ofId($productID)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'product_id',
                               'description', 'status', 'out_of_stock',
                               'old_price', 'price', 'youtube_video','specification_presets__id'
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch products categories.
     *
     * @param $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductAllCategory($productID)
    {
        return $this->product
                    ->with('categories')
                    ->ofId($productID)
                    ->first();
    }

    /**
     * fetch products categories.
     *
     * @param $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategory($productID)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->get(['products_id', 'categories_id']);
    }

     /**
     * fetch products categories.
     *
     * @param $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductById($productID, $inactiveBrandIds, $activeCatIds)
    {
        return $this->product
                    ->with('productSpecification', 'productRating')
                    ->whereInHasCategories($activeCatIds)
                    ->verifyByBrand($inactiveBrandIds)
                    ->whereIn('id', $productID)
                    ->where('status', '=', 1)
                    ->get();
    }

    /**
     * fetch product details for quick view details
     * option values.
     *
     * @param int $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchQuickViewDetails($productID)
    {
        return  $this->product
                    ->where([
                        'id' => $productID,
                        // 'status' => 1, // active
                    ])
                    ->with(['option' => function ($query) {
                        $query->with('optionValues');
                    }])->with(['categories' => function ($query) {
                        $query->select('categories.id', 'categories.name');
                    }, 'image'])
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'product_id',
                               'status',
                               'out_of_stock',
                               'old_price',
                               'price',
                               'specification_presets__id',
                               '__data'
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch product details with option for noraml page
     * option values
     * product images
     * related product.
     *
     * @param $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetails($productID)
    {
        $query = $this->product
                        ->with('relatedProducts', 'image', 'categories', 'productSpecification', 'brand', 'productFaq', 'oldProductSpecification')
                        ->with(['option' => function ($query) {
                            $query->with('optionValues');
                        }]);

        if (isAdmin()) {
            $query->ofId($productID);
        } else {
            $query->where([
                'products.id' => $productID,
                'products.status' => 1,
            ]);
        }

        return $query->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'product_id',
                               'description',
                               'status',
                               'out_of_stock',
                               'old_price',
                               'price',
                               'youtube_video',
                               'brands__id',
                               '__data'
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch related product.
     *
     * @param array $relatedProductIDs
     * @param array $activeCatIds
     * @param array $inactiveBrandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRelatedProducts($relatedProductIDs, $activeCatIds, $inactiveBrandIds)
    {
        return $this->product->whereIn('id', $relatedProductIDs)
                            ->isStatus(1) // active)
                            ->verifyByBrand($inactiveBrandIds)
                            ->whereInHasCategories($activeCatIds)
                            ->select(
                                'id',
                                'name',
                                'thumbnail',
                                'status',
                                'out_of_stock',
                                'price'
                            )->get();
    }

    /**
     * fetch recent view products.
     *
     * @param array(int$recentViewProductIDs
     * @param int $productIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRecentViewedProducts($recentViewProductIDs, $productID)
    {
        return $this->product->whereIn('id', $recentViewProductIDs)
                             ->isStatus(1) // active)
                             ->where('id', '!=', $productID)
                             ->select(
                                'id',
                                'name',
                                'thumbnail',
                                'status',
                                'out_of_stock',
                                'price'
                            )->get();
    }

    /**
     * getActiveChilds of parent category.
     *
     * @param  (int) $categoryID.
     *
     * @return array
     *------------------------------------------------------------------------ */
    public function getActiveChilds($categoryID = null)
    {
        foreach ($this->fetchCategories() as $key => $category) {
            if (($category['id'] == $categoryID)
                          and
                ($category['status'] == 1)
                    and
                in_array($categoryID, $this->allMyChilds) !== true) {
                $this->allMyChilds[] = $categoryID;
            }

            if (($category['parent_id'] == $categoryID)
                          and
            ($category['status'] == 1)) {
                if (!in_array($category['id'], $this->allMyChilds)) {
                    $this->allMyChilds[] = $category['id'];
                }

                $this->getActiveChilds($category['id']);
            }
        }

        return $this->allMyChilds;
    }

    /**
     * getActiveChilds of parent category.
     *
     * @param  (int) $categoryID.
     *
     * @return array
     *------------------------------------------------------------------------ */
    public function allChildCategories($categoryID, $allChild = [])
    {
        $allCategories = $this->fetchCategories()->toArray();

        foreach ($allCategories as $category) {
            if ($category['parent_id']  ==  $categoryID) {
                $allChild[] = $category['id'];

                $allChild = self::allChildCategories(
                                                        $category['id'],
                                                        $allChild
                                                    );
            }
        }

        return $allChild;
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array  $activeCatIds
     * @param array  $input
     * @param string $route
     * @param array  $inactiveBrandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAll($activeCatIds, $input = null, $route, $inactiveBrandIds)
    {
        $query = $this->product
                      ->sortOrder()
                      ->activeProductAndCheckOutOfStock()
                      ->verifyByBrand($inactiveBrandIds)
                      ->with('checkOptionExists', 'productOptionValue', 'productRating', 'productWishlist');

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->brandAndPrice($input) // filter product when the max & min price available or brand
                        ->filterProductData($input)
                        ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'status',
                               'created_at',
                               'price',
                               'description',
                               'out_of_stock',
                               'old_price',
                               'brands__id',
                               'featured',
                               'specification_presets__id',
                               '__data'
                            ]
                        ]))
                        ->whereInHasCategories($activeCatIds)
                        ->paginate($this->getPaginationCount());
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array  $activeCatIds
     * @param array  $input
     * @param string $route
     *
     * @return eloquent collection object 
     *---------------------------------------------------------------- */
    public function fetchAllWithoutPaginatationWithFilter($activeCatIds, $input = null, $route, $inactiveBrandIds)
    {
        $query = $this->product
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds);

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->whereInHasCategories($activeCatIds)
                        ->filterProductData($input)
                        ->whereNotNull('products.brands__id')
                        ->get();
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array  $activeCatIds
     * @param array  $input
     * @param string $route
     *
     * @return eloquent collection object 
     *---------------------------------------------------------------- */
    public function fetchAllWithoutPaginate($activeCatIds, $input = null, $route, $inactiveBrandIds)
    {
        $query = $this->product
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds);

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->whereInHasCategories($activeCatIds)
                        ->whereNotNull('products.brands__id')
                        ->groupBy('products.brands__id')
                        ->pluck('products.brands__id')
                        ->toArray();
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array $activeCatIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllForFilter($activeCatIds, $input = null)
    {
        $query = $this->product->sortOrder()->activeProductAndCheckOutOfStock();

        if (Route::currentRouteName() === 'products.featured') {
            $query->where('products.featured', 1);
        }

        if (!empty($input['sbid'])) {
            $query->whereIn('brands__id', $input['sbid']);
        }

        return $query->select('id', 'name', 'thumbnail', 'price', 'out_of_stock')
                     ->whereInHasCategories($activeCatIds)
                     ->get();
    }

    /**
     * Fetch category records.
     *
     * @param number $categoryID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategoryByID($categoryID)
    {
        return $this->categoryRepository->fetchByIdAndIsActive($categoryID);
    }

    /**
     * check search product is valid.
     *
     * @param array $searchTerm
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchProducts($searchTerm, $inactiveBrandIds = [])
    {      
        $productsWhere = [
            'products.status' => 1,
        ];

        $showOutOfStock = getStoreSettings('show_out_of_stock');

        $isOutOFstock = __ifIsset($showOutOfStock, $showOutOfStock, 0);

        if (!$isOutOFstock) {
            $productsWhere['products.out_of_stock'] = 0;
        } 
                    
        $searchResult = Searchy::driver('ufuzzy')->search($this->product)->fields('name', 'description')
                ->query(htmlspecialchars(filter_var(preg_replace('/[^A-Za-z0-9\-]/', '', $searchTerm), FILTER_SANITIZE_STRING), ENT_QUOTES))->getQuery()
                ->whereNotIn('products.brands__id', $inactiveBrandIds)
                ->orWhereNull('products.brands__id')
                ->where($productsWhere)->having('relevance', '>', 20);
        
        return $searchResult->pluck('id');
    }

    /**
     * Fetch Searched active products records.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductSearchSuggestList($searchQuery, $inactiveBrandIds, $activeCatIds)
    {
        $query = $this->product
                        ->activeProductAndCheckOutOfStock()
                        ->whereInHasCategories($activeCatIds)
                        ->verifyByBrand($inactiveBrandIds)
                        ->shodh($searchQuery, ['name'])
                        ->select('id','name');

        return $query->get();
    }

    /**
     * Fetch Searched active products records.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchDataWithoutPagination($pIds = [], $input = [], $inactiveBrandIds)
    {
        return $this->product
                    ->with('checkOptionExists', 'productWishlist')
                    ->verifyByBrand($inactiveBrandIds)
                    ->activeProductAndCheckOutOfStock()
                    ->whereIn('products.id', $pIds)
                    ->sortOrder(null, null, $pIds)
                    ->brandAndPrice($input) // get brand , min_price, max_price
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'status',
                               'created_at',
                               'price',
                               'description',
                               'old_price',
                               'out_of_stock',
                               'brands__id',
                               'specification_presets__id',
                               'featured',
                            ]
                        ])
                    )
                    ->paginate($this->getPaginationCount());
    }

    /**
     * Fetch Searched active products records.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchData($pIds = [], $input = [], $inactiveBrandIds)
    {
        return $this->product
                    ->with('checkOptionExists', 'productWishlist')
                    ->verifyByBrand($inactiveBrandIds)
                    ->activeProductAndCheckOutOfStock()
                    ->whereIn('products.id', $pIds)
                    ->sortOrder(null, null, $pIds)
                    ->brandAndPrice($input) // get brand , min_price, max_price
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'status',
                               'created_at',
                               'price',
                               'description',
                               'old_price',
                               'out_of_stock',
                               'brands__id',
                               'specification_presets__id',
                               'featured',
                            ]
                        ])
                    )
                    ->paginate($this->getPaginationCount());
    }

    /**
     * Fetch Searched active products records & filter.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchedProductDataForFilter($pIds = [], $input = [], $inactiveBrandIds)
    {
        return $this->product
                    ->whereIn('products.id', $pIds)
                    ->verifyByBrand($inactiveBrandIds)
                    ->brandAndPrice($input) // get brand , min_price, max_price
                    ->filterProductData($input)
                    ->whereNotNull('products.brands__id')
                    ->groupBy('products.brands__id')
                    ->pluck('products.brands__id')->toArray();
    }

    /**
     * Fetch products categories.
     *
     * @param array $productIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductsCategories($productIDs)
    {
        return $this->productCategory
                    ->whereIn('products_id', $productIDs)
                    ->select('products_id', 'categories_id')
                    ->get();
    }

    /**
     * if check product option exist.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function productOptionsIsExist($productID = null)
    {
        $options = $this->productOptions
                        ->productID($productID)
                        ->get()
                        ->toArray();

        if (empty($options)) {
            return false;
        }

        return true;
    }

    /**
     * get brand related products.
     *
     * @param number $brandID
     * @param array  $isActiveCate
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandRelatedProductWithoutPagination($productID = [], $input = [])
    {
        return  $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->with('checkOptionExists', 'productWishlist')
                    ->whereIn('products.id', $productID)
                    ->brandAndPrice($input)
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description',
                               'old_price', 'created_at', 
                               'specification_presets__id',
                               'out_of_stock', 
                               'featured',
                            ]
                        ])
                    )
                    ->get();
    }

    /**
     * get brand related products.
     *
     * @param number $brandID
     * @param array  $isActiveCate
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandRelatedProduct($productID = [], $input = [])
    {
        return  $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->with('checkOptionExists', 'productWishlist')
                    ->whereIn('products.id', $productID)
                    ->brandAndPrice($input)
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description',
                               'old_price', 'created_at', 
                               'specification_presets__id',
                               'out_of_stock', 
                               'featured',
                            ]
                        ])
                    )
                    ->paginate($this->getPaginationCount());
    }

    /**
     * fetch brand product by id.
     *
     * @param number $brandID
     * @param array  $isActiveCate
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductByBrandId($brandID)
    {
        return  $this->product
                     ->whereBrands__id($brandID)
                     ->activeProductAndCheckOutOfStock()
                     ->pluck('id')->all();
    }

    /**
     * fetch product max and min price.
     *
     * @param array  $categoryIDs
     * @param string $route
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMaxAndMinPrice($categoryIDs, $route, $inactiveBrandIds = [])
    {
        $query = $this->product
                       ->verifyByBrand($inactiveBrandIds)
                       ->activeProductAndCheckOutOfStock();

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        if (!__isEmpty($categoryIDs)) {
            $query->whereHas('categories', function ($q) use ($categoryIDs) {
                $q->whereIn('categories_id', $categoryIDs);
            });
        }

        return $query
                  ->selectMinAndMaxPrice()
                  ->first();
    }

    /**
     * fetch product min and max price of product based on product ids.
     *
     * @param array $input
     * @param array $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMaxAndMinPriceOfProduct($productIds, $inactiveBrandIds = [])
    {
        return $this->product
                    ->whereIn('id', $productIds)
                    ->verifyByBrand($inactiveBrandIds)
                    ->selectMinAndMaxPrice()
                    ->first();
    }

    /**
     * fetch brand for products.
     *
     * @param array $isActiveCate
     * @param int   $brandID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchFilterBrandRelatedProduct($productIds = [])
    {
        return  $this->product
                    ->whereIn('id', $productIds)
                    ->activeProductAndCheckOutOfStock()
                    ->selectBrandCount()
                    ->groupBy('products.brands__id')
                    ->get();
    }

    /**
     * fetch brand id of this product.
     *
     * @param int $productIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandIdByPid($productIds)
    {
        return  $this->product
                     ->where('id', $productIds)
                     ->whereNotNull('brands__id')
                     ->select('brands__id')
                     ->first();
    }

    /**
      * Fetch rating by item id
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchUserProductRating($productId)
    {
        return  $this->productRating->where('products_id', $productId)
                    ->select(
                        '_id',
                        'rating',
                        'products_id'
                    )->get();
    }

	/**
      * Fetch rating by item id
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchRatingByProductId($productId)
    {
        return  $this->productRating->where('products_id', $productId)
                    ->select(DB::raw('
                        ROUND(AVG(rating), 1) as rate, 
                        COUNT(rating) totalVotes')
                    )->first();
    }

	/**
      * Fetch self on product
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchSelfRatingOnProduct($productId)
    {
        return $this->productRating->where([
                      'products_id' => $productId,
                      'users_id' 	=> Auth::id()
                    ])->select(DB::raw('ROUND(AVG(rating), 1) as selfRate'))
                    ->first();
    }

	/**
      * Fetch rating o items ratings
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchProductRating($productId)
    {
        return $this->productRating->where([
                  'products_id' => $productId,
                  'users_id' 	=> Auth::id()
                ])->first();
    }

	 /**
      * Update ratings
      *
      * @param array $inputData
      *
      * @return mixed
      *---------------------------------------------------------------- */
    
    public function updateRatings($productRating, $inputData)
    {	
        // Check if rating added
        if ($productRating->modelUpdate($inputData)) {

            activityLog("Id of $productRating->_id update rating.");

            return $productRating;
        }

        return false;   // on failed
    }

	/**
      * Store rating for item
      *
      * @param array $inputData
      *
      * @return mixed
      *---------------------------------------------------------------- */
    
    public function storeRatings($inputData)
    {
        $keyValues = [
            'rating',
            'products_id',
            'users_id' => Auth::id()
        ];

        $productRating = new $this->productRating;
       
        // Check if rating added
        if ($productRating->assignInputsAndSave($inputData, $keyValues)) {

            $itemRateId = $productRating->_id;

            activityLog("Id of $itemRateId rating added by Auth::id().");

            return $productRating;
        }

        return false;   // on failed
    }

     /**
      * Store notify User
      *
      * @param array $inputData
      *
      * @return mixed
      *---------------------------------------------------------------- */
    
    public function fetchProductNotifyUser($productId, $userEmail)
    {
        return $this->productNotification
                    ->where('products_id', $productId)
                    ->where('email', $userEmail)
                    ->first();
    }

    /**
      * Store notify User
      *
      * @param array $inputData
      *
      * @return mixed
      *---------------------------------------------------------------- */
    
    public function storeNotifyUser($inputData)
    {
        $keyValues = [
            'email',
            'products_id',
            'users_id',
            'status'
        ];

        $productNotification = new $this->productNotification;
    
        // Check if notify user added
        if ($productNotification->assignInputsAndSave($inputData, $keyValues)) {

            $notificationId = $productNotification->_id;

            activityLog("Id of $notificationId notify user added.");

            return true;
        }

        return false;   // on failed

    }

    /**
      * Fetch rating by item id with user
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function prepareProductRatingAndReviewData($productId)
    {
        return $this->productRating->orderBy('product_ratings.updated_at', 'desc')
                    ->join('users', 'users.id', '=', 'product_ratings.users_id')
                    ->where([
                        'products_id'  => $productId,
                        'users.status' => 1
                    ])
                    ->select(
                        'product_ratings._id',
                        'rating',
                        'review',
                        'product_ratings.updated_at',
                        DB::raw('CONCAT(users.fname, " ", users.lname) AS full_name')
                    )->paginate(configItem('product_user_review_list_count'));
    } 

    /**
      * Fetch rating by item id with user
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function prepareProductQuestion($productId)
    {
        return $this->productQuestion->orderBy('product_questions.updated_at', 'desc')
                    ->join('users', 'users.id', '=', 'product_questions.users_id')
                    ->where([
                        'products_id'  => $productId,
                        'users.status' => 1
                    ])
                    ->select(
                        'product_questions._id',
                        'question',
                        'answer',
                        'product_questions.created_at',
                        'product_questions.updated_at',
                        DB::raw('CONCAT(users.fname, " ", users.lname) AS full_name')
                    )->paginate(configItem('faq_list_count'));
    }


	/**
      * Fetch rating by item id with user
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchProductRatingAndReview($productId)
    {
        return $this->productRating->orderBy('product_ratings.updated_at', 'desc')
                    ->join('users', 'users.id', '=', 'product_ratings.users_id')
                    ->where([
                        'products_id'  => $productId,
                        'users.status' => 1
                    ])
                    ->select(
                        'product_ratings._id',
                        'rating',
                        'review',
                        'product_ratings.updated_at',
						DB::raw('CONCAT(users.fname, " ", users.lname) AS full_name')
                    )->take(configItem('product_detail_user_review_count'))->get();
    } 

    /**
      * Fetch rating by item id with user
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchProductQuestion($productId)
    {
        return $this->productQuestion->orderBy('product_questions.created_at', 'desc')
                    ->join('users', 'users.id', '=', 'product_questions.users_id')
                    ->where([
                        'products_id'  => $productId,
                        'users.status' => 1
                    ])
                    ->select(
                        'product_questions._id',
                        'question',
                        'answer',
                        'product_questions.created_at',
                        DB::raw('CONCAT(users.fname, " ", users.lname) AS userFullName')
                    )->take(configItem('product_detail_faq_count'))->get();
    } 


    /**
      * Store wishlist data
      *
      * @param array $storeData
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function storeProductFaqs($storeData)
    {
        $keyValues = [
            'products_id',
            'users_id',
            'question',
            'status',
            'type'
        ];

        $productFaqs = new $this->productQuestion;

        if ($productFaqs->assignInputsAndSave($storeData, $keyValues)) {

            $faqId = $productFaqs->_id;
            
            activityLog("Id of $faqId product question added by ".getUserID().".");
            
            return true;
        }

        return false;
    }

    /**
      * Store wishlist data
      *
      * @param array $storeData
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function storeToWishList($storeData)
    {
        $keyValues = [
            'products_id',
            'users_id'
        ];

        $wishList = new $this->productWishList;

        if ($wishList->assignInputsAndSave($storeData, $keyValues)) {

            $wishlistId = $wishList->_id;
            
            activityLog("Id of $wishlistId wishlist added by ".getUserID().".");
            
            return true;
        }

        return false;
    }

    /**
      * Fetch Wishlist Data
      *
      * @param array $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function fetchWishListByUserId($productId)
    {
        return $this->productWishList
                    ->where([
                        'products_id' => $productId,
                        'users_id'    => getUserID()
                    ])
                    ->first();
    }

    /**
      * Remove from wishlist
      *
      * @param array $wishlist
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function deleteWishList($wishlist)
    {
        if ($wishlist->delete()) {
            activityLog("Id of $wishlist->_id wishlist removed by ".getUserID().".");
            return true;
        }

        return false;
    }

    /**
      * Fetch My wish lists
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function fetchMyWishList()
    {
        return $this->productWishList
                    ->with('product')
                    ->where('users_id', getUserID())
                    ->latest()
                    ->paginate(configItem('my_whislist_pagination_count'));
    }

	/**
	* Fetch My wish lists
	*
	* @return Eloquent collection object
	*---------------------------------------------------------------- */
    public function fetchMyRatings()
    {
        return $this->productRating
                    ->with('product')
                    ->where('users_id', getUserID())
                    ->latest()
                    ->paginate(configItem('my_rating_pagination_count'));
    }

    /**
    * Fetch Product Specification Count
    *
    * @return Eloquent collection object
    *---------------------------------------------------------------- */

    public function getProductSpecificationCount($productIds)
    {
        return $this->productSpecification
                    ->groupBy('specification_values__id')
                    ->whereNotNull('specification_values__id')
                    ->whereIn('products_id', $productIds)
                    ->select('_id', 'products_id', 'specification_values__id', DB::raw('COUNT(products_id) as productCount'))
                    ->pluck('productCount', '_id');
    }

    /**
    * Fetch Product Specification by product ids
    *
    * @return Eloquent collection object
    *---------------------------------------------------------------- */
    public function fetchProductSpecificationsByProductId($productIds)
    {
        return $this->productSpecification
                    ->join('specification_values', 'product_specifications.specification_values__id', '=', 'specification_values._id')
                    ->whereIn('products_id', $productIds)
                    ->with([
                        'specification' => function($query) {
                            $query->whereNotNull('use_for_filter');
                        }
                    ])
                    ->select(
                        __nestedKeyValues([
                            'product_specifications' => ['*'],
                            'specification_values' => [
                                '_id AS spec_value_id',
                                'specification_value',
                                'specifications__id'
                            ]
                        ])
                    )
                    ->get();

    }

    /**
    * Fetch Product Specification by ids
    *
    * @return Eloquent collection object
    *---------------------------------------------------------------- */
    public function fetchSpecificationsByIds($specificationIds)
    {
        return $this->productSpecification
                    ->whereIn('_id', $specificationIds)
                    ->get();
    }

    /**
    * Fetch Product Ratings by product ids
    *
    * @param array $productsIds
    *
    * @return Eloquent collection object
    *---------------------------------------------------------------- */
    public function fetchRatingsByProductsId($productsIds, $categoryIds)
    {
        return $this->productRating
                    ->when($categoryIds, function($query, $categoryIds) {
                        return $query->join('product_categories', 'product_ratings.products_id', '=', 'product_categories.products_id')
                                ->whereIn('product_categories.categories_id', $categoryIds)
                                ->select(
                                    'product_categories.products_id',
                                    'product_categories.categories_id'
                                );
                    })
                    ->whereIn('product_ratings.products_id', $productsIds)
                    ->select(
                        'product_ratings._id',
                        'product_ratings.products_id',
                        'product_ratings.rating',
                        \DB::raw('AVG(product_ratings.rating) as ratings_average')
                    )
                    ->groupBy('product_ratings.products_id')
                    ->get();
    }

    /**
     * fetch all active products
     * 1 status is active.
     *
     * @param int $categoryIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategorieProductsForFilter($categoryIDs, $input = [], $inactiveBrandIds = [])
    {
        return $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds)
                    ->with('checkOptionExists')
                    ->brandAndPrice($input) // filter product when the max & min price available or brand
                    ->filterProductData($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description', 'created_at', 'specification_presets__id', 'out_of_stock', 'featured',
                               'brands__id'
                            ],
                        ])
                    )
                    ->whereInHasCategories($categoryIDs)
                    ->get();
    }

    /**
     * Fetch product FAQ Count
     *
     * @param int $productId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductFaqCount($productId)
    {
        return $this->productQuestion
                    ->where('products_id', $productId)
                    ->count();
    }

    /**
      * Fetch rating by item id with user
      *
      * @param int $productId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    
    public function fetchProductReviewCount($productId)
    {
        return $this->productRating->orderBy('product_ratings.updated_at', 'desc')
                    ->join('users', 'users.id', '=', 'product_ratings.users_id')
                    ->where([
                        'products_id'  => $productId,
                        'users.status' => 1
                    ])
                    ->whereNotNull('review')
                    ->select(
                        'product_ratings._id',
                        'rating',
                        'review',
                        'product_ratings.updated_at'
                    )->count();
    }

    /**
      * Fetch active products
      *
      * @param array $categoriesIds
      * @param array $inactiveBrandIds
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */
    public function fetchActiveProducts($categoriesIds, $inactiveBrandIds)
    {
        return $this->product
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds)
                    ->whereInHasCategories($categoriesIds)
                    ->get();
    }
}