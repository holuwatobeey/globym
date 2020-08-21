<?php
/*
* ProductController.php - Controller file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Category;
use App\Yantrana\Components\Product\ProductEngine;
use Route;
use Config;
use JavaScript;
use Illuminate\Http\Request;
use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Components\Product\Requests\NotifyUserRequest;
use Breadcrumb;
use NativeSession;

class ProductController extends BaseController
{
    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * @var productAssets - Product Assets
     */
    protected $productAssets;

    /**
     * @var categories
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param ProductEngine $productEngine - Product Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ProductEngine $productEngine)
    {
        $this->productEngine = $productEngine;
        $this->productAssets = Config('__tech.product_assets');
    }
       

    /**
     * Render product list view.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    public function all(Request $request, $categoryID = null)
    {
        $processReaction = $this->productEngine
                                ->prepareList(
                                    $categoryID,
                                    $request->all(),
                                    Route::currentRouteName()
                                );

        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('error.page')
                             ->with([
                                'error' => true,
                                'message' => __tr('Requested category does not exist.'),
                            ]);
        }
 		
        $currentRouteName = Route::currentRouteName();
 		$processReaction['data']['hideSidebar'] = false;
        $processReaction['data']['showFilterSidebar'] = false;

        if ($currentRouteName == 'products_by_category') {
            $processReaction['data']['hideSidebar'] = false;
            $processReaction['data']['showFilterSidebar'] = true;
        }

        if (getStoreSettings('brand_menu_placement') == 2 and getStoreSettings('categories_menu_placement') == 2) {
            $processReaction['data']['hideSidebar'] = true;
        }

        if (getStoreSettings('brand_menu_placement') == 4 and getStoreSettings('categories_menu_placement') == 4) {
            $processReaction['data']['hideSidebar'] = true;
        }
 		
        $products = $processReaction['data'];

        // Check if current ajax request
        if ($request->ajax()) {
            return __processResponse(
                $processReaction,
                [],
                $products
            );
        }

        if (!empty($request['sort_by'])) {
            $products['sortBy'] = $request['sort_by'];
        }

        if (!empty($request['sort_order'])) {
            $products['sortOrder'] = $request['sort_order'];
        }

        $sortByArray = ['name', 'price', 'created_at'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $products['sortBy'] = __tr('name');
        }

        $data = $products;
     
        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'filterUrl' => $data['filterUrl'],
            'categoryData' => $data['category'],
            'productPrices' => $data['productPrices'],
            'filterPrices' => $data['filterPrices'],
            'brandID' => $data['productsBrandID'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getSelectedCurrencySymbol(),
            'categoryID' => (!empty($categoryID)) ? $categoryID : '',
            'pageType' => $data['pageType'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
            'itemLoadType' =>   (getStoreSettings('item_load_type') === null)
                                ? 1
                                : (int) getStoreSettings('item_load_type'),
            'brandsIds' => $data['brandsIds'],
            'specsIds' => $data['specsIds'],
            'filterRating' => $data['filterRating'],
            'availablity' => $data['availablity'],
            'catIds' => $data['catIds'],

        ]);

        return $this->loadPublicView('product.list', $data);
    }


    /**
     * Render product list view.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    public function productCompare()
    {
        $processReaction = $this->productEngine
                                ->prepareProductCompareData();
       
        $processReaction['data']['hideSidebar'] = true;
        $processReaction['data']['showFilterSidebar'] = false;
        $data = $processReaction['data'];

        return $this->loadPublicView('product.product-compare', $data);
    }

    /**
      * Get Review Support data
      *
      * @param int $productId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function prepareProductCompareCount()
    {
        $processReaction = $this->productEngine
                                ->prepareProductCompareCount();

        $data = $processReaction['data'];

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Get Review Support data
      *
      * @param int $productId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function prepareProductCompareData()
    {
        $processReaction = $this->productEngine
                                ->prepareProductCompareData();

        $data = $processReaction['data'];

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * get products details.
     *
     * @param array $request
     * @param int   $productID
     * @param int   $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function quickViewDetailsSupportData(Request $request, $productID, $categoryID = null)
    {
        $products = $this->productEngine->getQuickViewDetailsData($productID, $categoryID);
        
        // get engine reaction
        return __processResponse($products, [
                2 => __tr('Sorry this product is currently not available, Please reload the page.'),
                9 => __tr("Category of this product is inactive therefore product is not available publicly."),
            ], $products['data']);
    }

    /**
     * Render product details view.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function details(Request $request, $productID, $productName, $categoryID = null)
    {
        $pageType = $request['page_type'];

        $processReaction = $this->productEngine
                                   ->preparDetails($productID, $pageType, $categoryID);
                             
        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('error.page');
        }

        $details = $processReaction['data'];
  
        $product = $details['serverPutProductData'];
        JavaScript::put([
            'productID' => (!empty($productID)) ? $productID : '',
            'categoryID' => (!empty($categoryID)) ? $categoryID : '',
			'productRatings' =>  $product['productRatings'],
            'productStatus' =>  $product['details']['status'],
            'launchingDate' =>  $product['details']['launchingDate'],
            'isAddedInWishlist' => $product['isAddedInWishlist'],
            'images' => $product['allImages'],
            'isActiveCategory' => $product['isActiveCategory'],
            'defaultImages' => $product['defaultImages']
        ]);
   
        $data = [
            'breadCrumb' => $details['breadCrumb'],
            'images' => $product['image'],
            'product' => $product['details'],
            'specifications' => $product['specifications'],
            'productQuestionData' => $product['productQuestionData'],
            'productQuestionCount' => $product['productQuestionCount'],
            'youtubeVideoCode' => $product['youtubeVideoCode'],
            'categories' => $product['categories'],
            'isActiveCategory' => $product['isActiveCategory'],
            'relatedProductData' => $product['relatedProductData'],
            'currencySymbol' => getCurrencySymbol(),
            'currencyValue' => getCurrency(),
            'pageType' => $pageType,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ];
    
        return $this->loadPublicView('product.details', $data);
    }

     /**
     * Render product details view.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function productReviews(Request $request, $productID, $pageType = null)
    {
        $processReaction = $this->productEngine
                                   ->preparProductAllRerviews($productID, $pageType);

        JavaScript::put([
            'productID' => (!empty($productID)) ? $productID : '',
        ]);                           
        $productDetails = $processReaction['data'];
        
        $productData = $productDetails['productAllReviewdetail'];
       
        $data = [
            'productData' => $productData,
            'breadCrumb' => $productDetails['breadCrumb'],
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ];

        return $this->loadPublicView('product.product-reviews', $data);
    }

    /**
     * show article list in public.
     *
     * @return view page
     *---------------------------------------------------------------- */

    public function productReviewsPaginateData($productId)
    {
        $processReaction = $this->productEngine->getReviewsPaginateData($productId);
                
        return __processResponse($processReaction, [], [], true);
    }

    /**
     * show product faqs list in public.
     *
     * @return view page
     *---------------------------------------------------------------- */

    public function productQuestionPaginateData($productId)
    {
        $processReaction = $this->productEngine->getProductQuestionPaginateData($productId);
                
        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Render product details view.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function productQuestions($productID, $pageType = null)
    {
        $processReaction = $this->productEngine
                                   ->preparProductAllQuestions($productID, $pageType);

        JavaScript::put([
            'productID' => (!empty($productID)) ? $productID : '',
        ]);                           
        $productDetails = $processReaction['data'];
        
        $productData = $productDetails['productData'];
       
        $data = [
            'productData' => $productData,
            'breadCrumb' => $productDetails['breadCrumb'],
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ];

        return $this->loadPublicView('product.product-questions', $data);
    }


    /**
     * search products supported data.
     *
     * @param array $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function search(Request $request)
    {       
        // user input data
        $search = $this->productEngine->prepareSearch($request->all());
   
        if ($request->ajax()) {
            return __processResponse(
                $search,
                [],
                $search['data']
            );
        }

        $search['data']['sortBy'] = (!empty($request['sort_by'])) ? $request['sort_by'] : '';
        $search['data']['sortOrder'] = (!empty($request['sort_order'])) ? $request['sort_order'] : '';

        $sortByArray = ['name', 'price', 'created_at'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $search['data']['sortBy'] = __tr('name');
        }

        $search['data']['hideSidebar'] = true;
        $search['data']['showFilterSidebar'] = true;

        $data = $search['data'];

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'productPrices' => $data['productPrices'],
            'filterUrl' => $data['filterUrl'],
            'filterPrices' => $data['filterPrices'],
            'searchTerm' => $request['search_term'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getSelectedCurrencySymbol(),
            'brandID' => $data['productsBrandID'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
            'pageType' => $data['pageType'],
            'itemLoadType' =>   (getStoreSettings('item_load_type') === null)
                                ? 1
                                : (int) getStoreSettings('item_load_type')
        ]);

        return $this->loadPublicView('product.list', $data);
    }

    /**
     * search products suggest list supported data.
     *
     * @param array $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function searchSuggestList(Request $request, $searchQuery)
    {
        $processReaction = $this->productEngine->prepareSearchSuggestList($searchQuery);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Brand related product sit.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *---------------------------------------------------------------- */
    public function brandRelatedProducts(Request $request, $brandID, $brandName = null)
    {
        $processReaction = $this->productEngine
                                ->prepareBrandRelatedProduct($brandID, $brandName, $request->all());

        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('error.page');
        }

        if ($request->ajax()) {
            return __processResponse(
                $processReaction,
                [],
                $processReaction['data']
            );
        }

        $processReaction['data']['showFilterSidebar'] = true;

        $products = $processReaction['data'];

        if (!empty($request['sort_by'])) {
            $products['sortBy'] = $request['sort_by'];
        }

        if (!empty($request['sort_order'])) {
            $products['sortOrder'] = $request['sort_order'];
        }

        $sortByArray = ['name', 'price', 'created_at'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $products['data']['sortBy'] = __tr('name');
        }

        $data = $products;

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'productPrices' => $data['productPrices'],
            'pageType' => 'brand',
            'filterUrl' => $data['filterUrl'],
            'currenSymbol' => getSelectedCurrencySymbol(),
            'filterPrices' => $data['filterPrices'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
            'itemLoadType' =>   (getStoreSettings('item_load_type') === null)
                                ? 1
                                : (int) getStoreSettings('item_load_type')
        ]);

        return $this->loadPublicView('product.list', $data);
    }

    /**
     * get data of founded result of product list.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function filterAll(Request $request)
    {
        // user input data
        $filter = $this->productEngine->prepareFilter($request->all());

        JavaScript::put([
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
        ]);

        return __processResponse($filter, [
                2 => __tr('Brand does not exist.'),
            ], $filter['data']);
    }

    /**
     * To show for filtering data.
     *---------------------------------------------------------------- */
    public function filterSearch(Request $request)
    {
        // user input data
        $filter = $this->productEngine->prepareFilterSearch();

        return __processResponse($filter, [
                2 => __tr('Product does not exist.'),
            ], $filter['data']);
    }

    /**
     * brand related filter dialog data.
     *
     * @param $brandID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function filterBrandRelatedProduct($brandID = null)
    {
        // user input data
        $filter = $this->productEngine
                          ->prepareFilterBrandRelatedProduct($brandID);

        return __processResponse($filter, [
                18 => __tr('Product does not exist.'),
            ], $filter['data']);
    }

	/**
      * Handle an add or update rating on product
      *
      * @param int 	$productId
      * @param int 	$rate
      * @param obj 	CommonPostRequest $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function addRating($productId, $rate, CommonPostRequest $request)
    {
        $processReaction = $this->productEngine->processAddOrUpdateRating($productId, $rate);

        $data = $processReaction['data'];
  
        return __processResponse($processReaction, [], $data, true);
    }

    /**
      * Handle an add or update rating on product
      *
      * @param int  $productId
      * @param int  $rate
      * @param obj  CommonPostRequest $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function addCompare($productId)
    {   
        $processReaction = $this->productEngine->processAddCompareProduct($productId);

        $data = $processReaction['data'];
  
        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Handle an add or update rating on product
      *
      * @param int  $productId
      * @param int  $rate
      * @param obj  CommonPostRequest $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function removeAllCompareProduct()
    {   
        $processReaction = $this->productEngine->processRemoveAllCompareProduct();

        $data = $processReaction['data'];
  
        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Handle an add or update rating on product
      *
      * @param int  $productId
      * @param int  $rate
      * @param obj  CommonPostRequest $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function removeCompareProduct($productId)
    {   
   
        $processReaction = $this->productEngine->processRemoveCompareProduct($productId);

        $data = $processReaction['data'];
  
        return __processResponse($processReaction, [], [], true);
    }

	/**
      * Get rating & review support data
      *
      * @param int $productId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function getRatingAndReviewSupportData($productId)
    {	
        $processReaction = $this->productEngine->prepareRatingAndReviewSupportData($productId);

        return __processResponse($processReaction, [], [], true);
    }

	/**
      * Get Review Support data
      *
      * @param int $productId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function getReviewSupportData($productId)
    {
        $processReaction = $this->productEngine
                                ->prepareReviewSupportData($productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Add Review
      *
      * @param int $productId
      * @param array $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function addReview($productId, CommonPostRequest $request)
    {
        $processReaction = $this->productEngine->processAddReview($productId, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Add Review
      *
      * @param int $productId
      * @param array $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function addProductFaqs($productId, $type, CommonPostRequest $request)
    {
        $processReaction = $this->productEngine->processAddProductFaqs($productId, $type, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Add Notify User
      *
      * @param int $productId
      * @param array $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function addNotifyUser($productId, NotifyUserRequest $request)
    {
        $processReaction = $this->productEngine->processAddNotifyUser($productId, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Add To Wishlist 
      *
      * @param int $productId
      * @param array $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function addToWishlistProcess($productId, CommonPostRequest $request)
    { 
        $processReaction = $this->productEngine->processAddtoWishlist($productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Remove From Wishlist 
      *
      * @param int $productId
      * @param array $request
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function removeFromWishlistProcess($productId, CommonPostRequest $request)
    {
        $processReaction = $this->productEngine->processRemoveFromWishlist($productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * My WishList View 
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function myWishListView()
    {
        if (!getStoreSettings('enable_wishlist')) {
            return redirect()->route('home.page')->with([
                'error' => true,
                'success' => false,
                'message' => __tr('wishlist not available.'),
             ]);
        }

        $breadcrumb = $this->productEngine->myWishListBreadcrumb();

        return $this->loadPublicView('product.my-wish-list', [
            'breadCrumb' => $breadcrumb,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
      * Get My Wish list details 
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function myWishListDetails()
    {
        $processReaction = $this->productEngine->prepareMyWishListDetails();

        return __processResponse($processReaction, [], [], true);
    }

     /**
      * Get My Wish list details 
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function myRatingListDetails()
    {
        $processReaction = $this->productEngine->prepareMyRatings();

        return __processResponse($processReaction, [], [], true);
    }

	/**
      * My Ratings View 
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */
    public function myRatings()
    {	
		//$engineReaction = $this->productEngine->prepareMyRatings();

        $breadcrumb = $this->productEngine->myRatingListBreadcrumb();

        return $this->loadPublicView('product.my-ratings', [
            'breadCrumb' => $breadcrumb,
            'hideSidebar' => true,
            'showFilterSidebar' => false
        ]);
    }

    /**
     * search term suggest list supported data.
     *
     * @param array $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareSearchData(Request $request, $searchQuery)
    {
        $processReaction = $this->productEngine->prepareSearchRequestData($searchQuery);

        return __processResponse($processReaction, [], [], true);
    }
}
