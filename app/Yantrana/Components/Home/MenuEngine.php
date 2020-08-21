<?php

/*
* MenuEngine.php - Main component file
*
* This file is part of the Home component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Home;

use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Pages\Repositories\ManagePagesRepository;
use App\Yantrana\Components\Product\ProductEngine;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use Route;
use Request;

class MenuEngine
{
    /**
     * category placement $categoryPlacement.
     *---------------------------------------------------------------- */
    protected $categoryPlacement;

    /**s
      * brand placement $brandsPlacement
      *
      * @return void
      *---------------------------------------------------------------- */
    protected $brandsPlacement;

    /**
     * @var - Category Repository
     */
    protected $categoryRepository;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var ManagePagesRepository - ManagePages Repository
     */
    protected $pagesRepository;

    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * Constructor.
     *
     * @param ManageCategoryRepository $categoryRepository - Category Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ManageCategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        ManagePagesRepository $pagesRepository,
        ProductRepository $productRepository,
        ProductEngine $productEngine)
    {
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->pagesRepository = $pagesRepository;
        $this->productRepository = $productRepository;
        $this->currentRouteName = Route::currentRouteName();
        $this->productEngine = $productEngine;
    }

    /**
     * Unique multidim array.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    protected function uniqueMultidimArray($array, $key) { 
        $tempArray = []; 
        $i = 0; 
        $keyArray = []; 
        
        foreach($array as $aKey => $val) { 
            if (!in_array($val[$key], $keyArray)) { 
                $keyArray[$aKey] = $val[$key]; 
                $tempArray[$aKey] = $val; 
            } 
            $i++; 
        } 
        return $tempArray; 
    } 

    /**
     * get side bar categories menu.
     *---------------------------------------------------------------- */
    public function getSidebarFilterData($data)
    {
        $brandsIds = []; //brandID
        
        // If brand exist then get brand ids
        if (__ifIsset($data['brandIds'])) {
            $brandsIds = explode('|', $data['brandIds']);
        }

        $categoruID = null;

        // Check if category exist
        if (isset($data['category']) and !__isEmpty($data['category'])) {
            $categoruID = $data['category']['id'];
        }

        $categories = $specificationFilter = $productRatings = $productIds = $productCollection = $brandProductCount = $rawProductIds = [];
        $availableInStockProductCount = $availableOutStockProductCount = $availableSoonProductCount = 0;
        
        if (isset($data['rawProductCollection']) and !__isEmpty($data['rawProductCollection'])) {
            $rawProducCollection = $data['rawProductCollection'];

            $rawProductIds = $rawProducCollection->pluck('id');

            $availableInStockProductCount = $rawProducCollection->where('out_of_stock', 0)->count();

            $availableOutStockProductCount = $rawProducCollection->where('out_of_stock', 1)->count();

            $availableSoonProductCount = $rawProducCollection->whereIn('out_of_stock', [2,3])->count();;

            $brandProductCount = $rawProducCollection->where('brands__id', '!=', null)->countBy(function($item) {
                                    return $item['brands__id'];
                                })->toArray();
        }
        
        // Check if Category Id Exist
        if (!__isEmpty($categoruID)) {
            // Fetch Category Collection
            $categoryCollection = $this->categoryRepository->fetchActiveCategoriesWithChildren($categoruID);
            // Fetch Inactive Brands
            $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
            // Fetch Category By Id
            $category = $this->productRepository->fetchCategoryByID($categoruID);
            // Find active parent and child category
            $activeCatIds = findActiveChildren($categoryCollection);
            // fetch valid product base on valid categories
            $productCollection = $this->productRepository->fetchCategorieProductsForFilter($activeCatIds, [], $inactiveBrandIds);
            // Get Product ids
            $productIds = $productCollection->pluck('id');
        }

        // Check if brand id exist
        if (__ifIsset($data['brandID'])) {
            $brand = $this->brandRepository->fetchIsActiveByID($data['brandID']);

            $brandRelatedProductIds = $this->productRepository->fetchProductByBrandId($brand->_id);

            // Verify the product ids & return valid product ids
            $productIds = $this->productEngine->isValidProducts($brandRelatedProductIds, null);
        }

        if (__ifIsset($data['searchTerm'])) {
            // fetch inactive brand lists
            $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();        

            // fetch search products ids
            $searchedProductIds = $this->productRepository->fetchSearchProducts($data['searchTerm'], $inactiveBrandIds);
            
            $productIds = $this->productEngine->isValidProducts($searchedProductIds->toArray(), null);
        }

        $productSpecificationCount = $categoryProductCount = [];

        // Check if product ids exist
        if (!__isEmpty($productIds)) {
            $productCategories = $this->categoryRepository->fetchByProductIds($productIds);
            
            // Check if product category exist
            if (!__isEmpty($productCategories)) {
                foreach ($productCategories as $key => $category) {
                    if ($categoruID != $category->id) {
                        $categories[] = [
                            'id' => $category->id,
                            'name' => __transliterate('category', $category->id, 'name', $category->name),
                            'productCount' => $category->productCount
                        ];
                    }
                }
            }

            $productSpecificationCount = $this->productRepository->getProductSpecificationCount($rawProductIds)->toArray();

            $productSpecifications = $this->productRepository->fetchProductSpecificationsByProductId($productIds);

            $specificationFilter = $productSpecifications->groupBy(function($specification) {
                return __transliterate('specification_preset', $specification['specification']['_id'], 'label', $specification['specification']['label']);
            })->toArray();

            if (!__isEmpty($specificationFilter)) {
                foreach ($specificationFilter as $key => $specFilter) {
                    $specificationFilter[$key] = $this->uniqueMultidimArray($specFilter, 'specification_value');
                }
            }
            
            $productRatingCollection = $this->productRepository->fetchRatingsByProductsId($rawProductIds, $data['catIds']);
           
            $productRatings = [
                4 => $productRatingCollection->where('rating', '>=', 4)->count(),
                3 => $productRatingCollection->where('rating', '>=', 3)->count(),
                2 => $productRatingCollection->where('rating', '>=', 2)->count(),
                1 => $productRatingCollection->where('rating', '>=', 1)->count()
            ];
        }
        
        // fetch brands of display found result of products
        $brands = $this->brandRepository->fetchBrand($brandsIds);
        $brands->map(function ($brand) {
            $brand['name'] = __transliterate('brand', $brand['id'], 'name', $brand['name']);
            return $brand;
        });

        $productPrices = [
            'min_price' => isset($data['productPrices']['min_price']) 
                            ? $data['productPrices']['min_price'] : 0,
            'max_price' => isset($data['productPrices']['max_price']) 
                            ? $data['productPrices']['max_price'] : 0
        ];

        $filterData = [
            'filterPrices'          => (isset($data['filterPrices']) 
                                        and (!__isEmpty($data['filterPrices']))) ? 
                                        $data['filterPrices'] 
                                        : [
                                            'min_price' => 0,
                                            'max_price' => 0
                                        ],
            'productPrices'         => $productPrices,
            'currenSymbol'          => getSelectedCurrencySymbol(),
            'productRelatedBrand'   => $brands,
            'productIds'            => $productIds,
            'categories'            => $categories,
            'specificationFilter'   => $specificationFilter,
            'productRatings'        => $productRatings,
            'brandsIds'             => isset($data['brandsIds']) ? $data['brandsIds'] : [],
            'specsIds'              => isset($data['specsIds']) ? $data['specsIds'] : [],
            'catIds'                => isset($data['catIds']) ? $data['catIds'] : [],
            'filterRating'          => isset($data['filterRating']) ? $data['filterRating'] : null,
            'availablity'           => isset($data['availablity']) ? $data['availablity'] : null,
            'specificationCount'    => $productSpecificationCount,
            'inStockProductCount'   => $availableInStockProductCount,
            'outStockProductCount'  => $availableOutStockProductCount,
            'availableSoonProductCount' => $availableSoonProductCount,
            'brandProductCount'     => $brandProductCount
        ];

        return $filterData;
    }

    /**
     * get navigation bar menu tree with html formated.
     *---------------------------------------------------------------- */
    public function nevigationTree()
    {
        // set setting when show the categories in menu
        $categoryPlacement = getStoreSettings('categories_menu_placement');
        $this->categoryPlacement = $categoryPlacement = ($categoryPlacement == 2 or $categoryPlacement == 3) ? $categoryPlacement : false;

        // set setting when show the brands in menu
        $brandsPlacement = getStoreSettings('brand_menu_placement');
        $this->brandsPlacement = $brandsPlacement = ($brandsPlacement == 2 or $brandsPlacement == 3) ? $brandsPlacement : false;

        // page object
        $object = $this->getPages();

        $object->map(function ($page) {
            $page['title'] = __transliterate('menu_pages', $page['id'], 'title', $page['title']);
            return $page;
        });

        // if conditionally the categories show in menu is true the categories object Marge in continue pass object
        // if not the object pass to next process
        $object = __ifIsset($categoryPlacement,
                    collect($object->toArray())->merge($this->getCategories()),
                    collect($object->toArray())
                );

        // if conditionally the brand show in menu is true the brand object marge in continue pass object
        // if not the object pass to next process
        $object = __ifIsset($brandsPlacement,
                    collect($object->toArray())->merge($this->getBrands()),
                    collect($object->toArray())
                );
     

        return $this->buildTree($object, null);
    }

    /**
     * get side bar categories menu.
     *---------------------------------------------------------------- */
    public function getSideBarCategoriesMenu()
    {
        $sidebarCategory = [];
        $categoryCollection = $this->categoryRepository->fetchAllActiveWithChildren();
        
        if (!__isEmpty($categoryCollection)) {
            foreach ($categoryCollection as $categoryKey => $category) {
                $childCategories = $category->toArray();
                $childCategories = array_merge($childCategories, [
                    'children' => $this->generateChildrenCategoriesTree($childCategories['children']),
                    'name' => __transliterate('category', $childCategories['id'], 'name', $childCategories['name']),
                    'target' => '_self',
                    'id' => $childCategories['id'],
                    'parent_id' => $childCategories['parent_id'],
                    'link' => route('products_by_category', [
                                                                'categoryID' => $childCategories['id'],
                                                                'categoryName?' => slugIt($childCategories['name']),
                                                            ]),
                ]);

                array_push($sidebarCategory, $childCategories);
            }
        }
        $categoryCollection = null;
        unset($categoryCollection);
        return $sidebarCategory;
    }

    /**
     * get sidebar bar brands menu.
     *---------------------------------------------------------------- */
    public function getSideBarBrandsData()
    {
        $brands = [];

        $brandCollection = $this->brandRepository->fetchAllActive();

        if (!__isEmpty($brandCollection)) {
            foreach ($brandCollection as $key => $brand) {
                $brandCollection[$key]['name'] = __transliterate('brand', $brand['_id'], 'name', $brand->name);
                $brandCollection[$key]['slugName'] = slugIt($brand->name);
            }
        }

        return $brandCollection;
    }

    /**
     * get all active  and allow to add to menu pages.
     *
     * @return collection object
     *---------------------------------------------------------------- */
    protected function getPages()
    {
        return $this->pagesRepository->fetchAllActiveAndAddToMenu();
    }

    /**
     * get active brands list.
     *
     * @return collection object
     *---------------------------------------------------------------- */
    protected function getBrands()
    {
        $brandArray = [];

        // fetch all active brand lists
        $brandCollection = $this->brandRepository->fetchAllActive();

        $prefix = 'b_';

        foreach ($brandCollection as $key => $brand) {
            $tempBrandArray = [
                  'title' => __transliterate('brand', $brand['_id'], 'name', $brand->name),
                  'id' => $prefix.$brand->_id,
                  'type' => 4, // system defined type
                  'parent_id' => 3, // system brand id
                  'link' => route('product.related.by.brand', [
                                                'brandID' => $brand->_id,
                                                'brandName?' => slugIt($brand->name),
                                            ]),
            ];

            array_push($brandArray, $tempBrandArray);
        }
        
        return $brandArray;
    }

    /**
     * Prepare Children Status.
     *
     * @param array $categoryCollection
     * @param array $colleectionContainer
     *
     * @return json
     *---------------------------------------------------------------- */
    private function prepareChildrenCategories($categoryCollection)
    {
        $prefix = 'c_';
        foreach ($categoryCollection as $categoryKey => $categoryValue) {

            $categoryCollection[$categoryKey]['title'] = __transliterate('category', $categoryValue['id'], 'name', $categoryValue['name']);
            $categoryCollection[$categoryKey]['target'] = '_self';
            $categoryCollection[$categoryKey]['id'] = $prefix.$categoryValue['id'];
            $categoryCollection[$categoryKey]['type'] = 4;
            $categoryCollection[$categoryKey]['parent_id'] = $prefix.$categoryValue['parent_id'];
            $categoryCollection[$categoryKey]['link'] = route('products_by_category', [
                                                            'categoryID' => $categoryValue['id'],
                                                            'categoryName?' => slugIt($categoryValue['name']),
                                                        ]);

            if (!$categoryValue['parent_id']) {
                $categoryCollection[$categoryKey]['parent_id'] = 2;
            }

            // Check if children category exists
            if(isset($categoryValue['children']) and !__isEmpty($categoryValue['children'])) {
               $categoryCollection[$categoryKey]['children'] = $this->prepareChildrenCategories($categoryValue['children']);
            }
        }
        return $categoryCollection;
    }

    /**
     * get active categories list.
     *---------------------------------------------------------------- */
    protected function getCategories()
    {
        $categoryArray = [];

        // fetch all active categories list
        $categoryCollection = $this->categoryRepository->fetchAllActiveWithChildren();
        
        $prefix = 'c_';

        foreach ($categoryCollection as $key => $category) {

            $childCategories = $category->toArray();
            $childCategories = array_merge($childCategories, [
                'children'  => $this->prepareChildrenCategories($childCategories['children']),
                'target'    => '_self',
                'title'     => __transliterate('category', $childCategories['id'], 'name', $childCategories['name']),
                'link'      => route('products_by_category', [
                                                'categoryID' => $childCategories['id'],
                                                'categoryName?' => slugIt($childCategories['name']),
                                            ]),
                'id'        => $childCategories['id'],
                'type'      => 4,
                'parent_id' => $prefix.$childCategories['parent_id']
            ]);
            
            if (!$childCategories['parent_id']) {
                $childCategories['parent_id'] = 2;
            }
            
            array_push($categoryArray, $childCategories);
        }
        
        return [
            'childCategories' => $categoryArray
        ];
    }

    /**
     * generate generate Multidimensional Array.
     *
     * @param array $getArray
     * @param parentID
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function buildTree($getArray, $parentID = null)
    {
        $data = [];
        $count = 0;
        $getArray = $getArray->sortBy('list_order');
        $childCategories = $getArray->pull('childCategories');

        foreach ($getArray as $item) {
            $item = (object) $item;

            if ($item->parent_id == $parentID) {
                $data[$count] = [
                      'name' => $item->title,
                      'id' => $item->id,
                      'type' => $item->type,
                      'parent_id' => $item->parent_id,
                      'currentRouteName' => $this->currentRouteName
                ];

                if ($item->type === 2 and !empty($item->link_details)) {
                    $linkDetils = json_decode($item->link_details, true);
                    $data[$count]['link'] = $linkDetils['value'];
                    $data[$count]['target'] = $linkDetils['type'];
                } elseif ($item->type === 3 and !empty($item->link_details)) {
                    $data[$count]['link'] = route($item->link_details);
                    $data[$count]['target'] = '_self';
                } else {
                    if (isset($item->link)) {
                        $data[$count]['link'] = $item->link;
                        $data[$count]['target'] = '_self';
                    } else {
                        $data[$count]['link'] = pageDetailsRoute($item->id, $item->title);
                        $data[$count]['target'] = '_self';
                    }
                }

                // do not add brands or categories if not required
                if (($this->categoryPlacement === false and $item->id === 2)
                        or ($this->brandsPlacement === false and $item->id === 3)) {
                    continue;
                }

                $children = $this->buildTree($getArray, $item->id);

                // sort categories
                if ($data[$count]['id'] === 2) {
                    $children = collect($children)->sortBy('name');
                }

                // sort brand
                if ($data[$count]['id'] === 3) {
                    $children = collect($children)->sortBy('name');
                }

                if (!empty($children) and $item->id != 2) {
                    $data[$count]['children'] = $children;
                } elseif ($item->id == 2) {
                    $data[$count]['children'] = $childCategories;
                }
            }

            ++$count;
        }
        $getArray = null;
        unset($getArray);
        return $data;
    }

    /**
     * generate tree for side bar.
     *
     * @param array $categoryCollection
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function generateChildrenCategoriesTree($categoryCollection)
    {
        foreach ($categoryCollection as $categoryKey => $categoryValue) {

            $categoryCollection[$categoryKey]['name'] = __transliterate('category', $categoryValue['id'], 'name', $categoryValue['name']);
            $categoryCollection[$categoryKey]['target'] = '_self';
            $categoryCollection[$categoryKey]['id'] = $categoryValue['id'];
            $categoryCollection[$categoryKey]['parent_id'] = $categoryValue['parent_id'];
            $categoryCollection[$categoryKey]['link'] = route('products_by_category', [
                                                            'categoryID' => $categoryValue['id'],
                                                            'categoryName?' => slugIt($categoryValue['name']),
                                                        ]);

            // Check if children category exists
            if(isset($categoryValue['children']) and !__isEmpty($categoryValue['children'])) {
               $categoryCollection[$categoryKey]['children'] = $this->generateChildrenCategoriesTree($categoryValue['children']);
            }
        }

        return $categoryCollection;
    }
}
