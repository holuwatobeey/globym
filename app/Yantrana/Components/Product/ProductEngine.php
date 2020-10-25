<?php
/*
* ProductEngine.php - Main component file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product;

use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Product\Blueprints\ProductEngineBlueprint;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;

use NativeSession;
use Breadcrumb;
use Route;
use Input;
use ShoppingCart;
use Request;
use Carbon\Carbon;

class ProductEngine implements ProductEngineBlueprint
{
    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var allMyChilds - allMyChilds array
     */
    protected $allMyChilds;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

	 /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var - Category Repository
     */
    protected $manageCategoryRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * Constructor.
     *
     * @param ProductRepository $productRepository - Product Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ProductRepository $productRepository,
        BrandRepository $brandRepository, 
        OrderRepository $orderRepository,
        CouponRepository $couponRepository,
        ManageCategoryRepository $manageCategoryRepository,
        UserRepository $userRepository,
        ManageProductRepository $manageProductRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->brandRepository   = $brandRepository;
		$this->orderRepository   = $orderRepository;
        $this->couponRepository  = $couponRepository;
        $this->manageCategoryRepository = $manageCategoryRepository;
        $this->userRepository = $userRepository;
        $this->manageProductRepository = $manageProductRepository;
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getProductRelatedActiveCategories($categoriesItems = [])
    {
        $categories = [];

        if (!empty($categoriesItems)) {
            // categories related portion
            foreach ($categoriesItems as $key => $category) {
                $categories[] = [
                    'name' => __transliterate('category', $category->id, 'name', $category->name),
                    'categoryUrl' => categoriesProductRoute(
                                        $category->id,
                                        slugIt($category->name)
                                    ),
                ];
            }
        }

        return $categories;
    }

    /**
     * return the price fultering.
     *
     * @param int $categoryID
     * @param array  input

     * @return array
     *---------------------------------------------------------------- */
    public function priceFilter($productPrices, $input)
    {
        $filterPrices = [];

        $showFilterPrice = [];
        $isAmountAlreadyConverted = false;

        if (isset($input['min_price']) and isset($input['max_price'])) {
            if (getSelectedCurrency() != getCurrency()) {
                $isAmountAlreadyConverted = true;
            }
            if ($isAmountAlreadyConverted) {
                $currencySymbol = configItem('currencies.details.'.getSelectedCurrency())['symbol'];
                $showFilterPrice = priceFormat($input['min_price'], false, $currencySymbol).__(' to ').priceFormat($input['max_price'], false, $currencySymbol);
            } else {
                $showFilterPrice = priceFormat($input['min_price'], false, true, ['isMultiCurrency' => true]).__(' to ').priceFormat($input['max_price'], false, true, ['isMultiCurrency' => true]);
            }
        }

        if (!empty($productPrices)) {

            $maxPrice = ceil((isset($input['max_price']))
                        ? ceil($input['max_price'])
                        : ceil($productPrices->max_price));

            $minPrice = floor((isset($input['min_price']))
                        ? $input['min_price']
                        : $productPrices->min_price);
            

            if (getStoreSettings('display_multi_currency') == true
                and getSelectedCurrency() != getCurrency()) {

                if (!$isAmountAlreadyConverted) {
                    $maxPrice = calculateForeignExchangeRates($maxPrice)['amount'];
                    $minPrice = calculateForeignExchangeRates($minPrice)['amount'];
                }
            }

            $filterPrices['max_price'] = $maxPrice;

            $filterPrices['min_price'] = $minPrice;
        }

        return [
            'filtered_price' => $filterPrices,
            'show_filtered_price' => $showFilterPrice,
        ];
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getRelatedProduct($relatedProductItems = [])
    {
        $relatedProductsIDs = [];
        $relatedProductData = [];

        // related product section
        if (!__isEmpty($relatedProductItems)) {
            foreach ($relatedProductItems as $relatedProduct) {
                $relatedProductsIDs[] = $relatedProduct->related_product_id;
            }

            if (!__isEmpty($relatedProductsIDs)) {
                $charactorLimit = config('__tech.character_limit');

                $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();
                $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

                // find all active categories
                $activeCatIds = findActiveChildren($categoryCollection);

                // fetch all related products
                $relatedProducts = $this->productRepository
                                         ->fetchRelatedProducts($relatedProductsIDs, $activeCatIds, $inactiveBrandIds);

                if (!__isEmpty($relatedProducts)) {
                    foreach ($relatedProducts as $key => $relatedProduct) {
                        $relatedProductData[] = [
                            'id' => $relatedProduct->id,
                            'price' => priceFormat($relatedProduct->price, false, true),
                            'thumbnail' => $relatedProduct->thumbnail,
                            'out_of_stock' => $relatedProduct->out_of_stock,
                            'slugName' => slugIt($relatedProduct->name),
                            'related_product_price' => priceFormat($relatedProduct->price, false, true, ['isMultiCurrency' => true]),
                            'name' => str_limit($relatedProduct->name,
                                                $limit = $charactorLimit,
                                                $end = '...'),
                        ];
                    }
                }
            }
        }

        return $relatedProductData;
    }

    /**
     * fetch latest product.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareLandingPageProduct($latestProductCount)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        // Get active brand collection
        $product = $this->productRepository
                                ->fetchLatestProduct($latestProductCount, $inactiveBrandIds, $activeCatIds);

        $latestProduct = [];
        if (!__isEmpty($product)) {
            foreach ($product as $key => $productData) {

                // Show productRating
                $productRating = [];
                if (!empty($productData->productRating)) {
                    $itemRating = collect($productData->productRating);
                 
                    $rating =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                        $decimal.
                        str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'        => $rating,
                        'formatItemRating'  => $formatRating,
                        'totalVote'         => $totalVotes
                    ];
                }

               $latestProduct[] = [
                    'id' => $productData->id,
                    'productImage' => getProductImageURL($productData->id, $productData->thumbnail),
                    'price' => priceFormat($productData->price, false, true, ['isMultiCurrency' => true]),
                    'productSpecExists' => isset($productData->specification_presets__id) ? $productData->specification_presets__id : null,
                    'name' => $productData->name,
                    'slugName' => slugIt($productData->name),
                    'productRating' => $productRating,
                    'oldPriceExist' => isset($productData->old_price) ? $productData->old_price : null,
                    'formatProductOldprice' => priceFormat($productData->old_price, false, true, ['isMultiCurrency' => true]),
                    'status' => $productData->status,
                    'thumbnail' => $productData->thumbnail,
                    'status' => $productData->status,
                    'created_at' => formatDateTime($productData->created_at),
                    'detailURL' => route('product.details', [$productData->id, slugIt($productData->name)]),
                ];
            }
        }
     
        return __engineReaction(1, [
            'latestProducts'    => $latestProduct,
        ]);
    }

     /**
     * fetch latest product.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareLandingPageFeaturedProduct($featuredProductCount)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        // Get active brand collection
        $featuredProductData = $this->productRepository
                                ->fetchFeaturedProduct($featuredProductCount, $inactiveBrandIds, $activeCatIds);

        $latestFeaturedProduct = [];
        if (!__isEmpty($featuredProductData)) {
            foreach ($featuredProductData as $key => $featuredProducts) {

                // Show productRating
                $productRating = [];
                if (!empty($featuredProducts->productRating)) {
                    $itemRating = collect($featuredProducts->productRating);
                 
                    $rating =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                        $decimal.
                        str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'    => $rating,
                        'formatItemRating'  => $formatRating,
                        'totalVote'     => $totalVotes
                    ];
                }

                $latestFeaturedProduct[] = [
                    'id' => $featuredProducts->id,
                    'productImage' => getProductImageURL($featuredProducts->id, $featuredProducts->thumbnail),
                    'price' => priceFormat($featuredProducts->price, false, true, ['isMultiCurrency' => true]),
                    'productSpecExists' => isset($featuredProducts->specification_presets__id) ? $featuredProducts->specification_presets__id : null,
                    'thumbnail' => $featuredProducts->thumbnail,
                    'oldPriceExist' => isset($featuredProducts->old_price) ? $featuredProducts->old_price : null,
                    'formatProductOldprice' => priceFormat($featuredProducts->old_price, false, true, ['isMultiCurrency' => true]),
                    'productRating' => $productRating,
                    'name' => $featuredProducts->name,
                    'slugName' => slugIt($featuredProducts->name),
                    'old_price' => $featuredProducts->old_price,
                    'status' => $featuredProducts->status,
                    'created_at' => formatDateTime($featuredProducts->created_at),
                    'detailURL' => route('product.details', [$featuredProducts->id, slugIt($featuredProducts->name)]),
                ];
            }
        }

        return __engineReaction(1, [
            'featuredProducts'  => $latestFeaturedProduct,
        ]);
    }

    /**
     * fetch popular product.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareLandingPagePopularProduct($popularProductCount)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        // Get active brand collection
        $popularProduct = $this->productRepository
                                ->fetchPopularProduct($inactiveBrandIds, $activeCatIds);

        $popularSaleProduct = [];                        
        if (!__isEmpty($popularProduct)) {
            //$orderProductByProduct = $popularProductData->groupBy('products_id')->toArray();
            $orderProductByProduct = $popularProduct->toArray();

            $productQuantity = $orderProductCollection = [];

            foreach ($orderProductByProduct as $orderKey => $products) {

                // Show productRating
                $productRating = [];
                if (!empty($products['product_rating'])) {
                    $itemRating = collect($products['product_rating']);
                 
                    $rating =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                        $decimal.
                        str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'        => $rating,
                        'formatItemRating'  => $formatRating,
                        'totalVote'         => $totalVotes
                    ];
                }

                $productQuantity = [];
                //order product array
                if (!__isEmpty($products['order_products'])) {

                    foreach ($products['order_products'] as $key => $order) {
                        $productQuantity[] = $order['quantity'];
                    }
     
                    $productOrderData = [
                        'id'            => $products['id'],
                        'qty'           => array_sum($productQuantity),
                        'orderStatus'   => $order['status'],
                        'productsId'    => $products['id'],
                        'productSpecExists' => isset($products['specification_presets__id']) ? $products['specification_presets__id'] : null,
                        'name'          => $products['name'],
                        'productRating' => $productRating,
                        'slugName'      => slugIt($products['name']),
                        'old_price'     => $products['old_price'],
                        'status'        => $products['status'],
                        'created_at'    => $products['created_at'],
                        'detailURL'     => route('product.details', [$products['id'], $products['name']]),
                        'price'         =>  priceFormat($products['price'], false, true, ['isMultiCurrency' => true]),
                        'oldPriceExist' => isset($products['old_price']) ? $products['old_price'] : null,
                        'formatProductOldprice' => priceFormat($products['old_price'], false, true, ['isMultiCurrency' => true]),
                        'thumbnail'     =>  $products['thumbnail'],
                        'productImage'  => getProductImageURL($products['id'], $products['thumbnail'])
                    ];

                    $orderProductCollection[$orderKey] = $productOrderData;
                }
            }

            $orderPrductCollection = collect($orderProductCollection);

            $orderProductData = $orderPrductCollection->sortByDesc('qty')->take($popularProductCount)->all();

            $popularSaleProduct = array_values($orderProductData);
          
        }
     
        return __engineReaction(1, [
            'popularProducts'  => $popularSaleProduct,
        ]);
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getProductForDetails($product)
    {
        $getSelectedOptions = [];
        $addonPrice = [];
        $getPrice = [];

        // fetch product option
        if (!__isEmpty($product->option)) {
            foreach ($product->option as $optionKey => $option) {
                $optionValue = $option->optionValues->toArray();

                $productNameArray = array(
                    '__option_name__' => __transliterate('option', $option->id, 'name', $option->name),
                );
                $nameMarkUp = __tr('Select __option_name__');
                $option['optionName'] = strtr($nameMarkUp, $productNameArray);

                if (!empty($optionValue)) {
                    // fetch option value and price and calculate total
                    $product->option[$optionKey]['optionValueExist'] = true;

                    foreach ($option->optionValues as  $vlaueKey => $optionValue) {
                  
                        $optionValue['addon_price_format'] = priceFormat($optionValue->addon_price, false, true, ['isMultiCurrency' => true]);
                        $optionValue['addon_price'] = $optionValue->addon_price;
                        $optionValue['subtotal'] = $optionValue->addon_price + $product->price;
                        $optionValue['optionName'] = $option->name;
                        $optionValue['translatedOptionName'] = __transliterate('option', $option->id, 'name', $option->name);
                        $optionValue['name'] = __transliterate('value', $optionValue->id, 'name', $optionValue->name);
                        if (!empty($optionValue->image_name)) {
                            $optionValue['thumbnailURL'] = getProductImageURL($product->id, $optionValue->image_name);
                        }
                    }

                    $getSelectedOptions[] = $option->optionValues[0];
                    $addonPrice[] = $option->optionValues[0]->addon_price;
                } else {
                    $product->option[$optionKey]['optionValueExist'] = false;
                }

                $getPrice['total'] = priceFormat($product->price + array_sum($addonPrice), false, true, ['isMultiCurrency' => true]);
                $getPrice['base_price'] = priceFormat($product->price, false, true, ['isMultiCurrency' => true]);
            }
        }

        $searchedCartItemRowID = ShoppingCart::search($product->id, $getSelectedOptions);

        if (!empty($searchedCartItemRowID)) {
            $product->cartProduct = ShoppingCart::findRow($searchedCartItemRowID);
        }

        if (!empty($getSelectedOptions)) {
            $product->getSelectedOptions = $getSelectedOptions;
        }

        $activeDiscount = $this->couponRepository->fetchProductDiscounts();
        // Get product discount
        $productDiscount = calculateSpecificProductDiscount($product->id, $product->price, $activeDiscount);
        $product->getPrice = $getPrice;
        $product->newTotalPriceCount = 0;

        $product->newTotalPrice = priceFormat($product->price, true, true, ['isMultiCurrency' => true]);

        if (!empty($addonPrice)) {
            $totalAddonPrice = array_sum($addonPrice);
            $productTotal = $product->price + $totalAddonPrice;
            $productDiscount = calculateSpecificProductDiscount($product->id, $productTotal, $activeDiscount);
            $product->newTotalPrice = priceFormat($productTotal, true, true, ['isMultiCurrency' => true]);
            $product->newTotalPriceCount = 1;
        }

        $product['productDiscount'] = $productDiscount;
        // get active categories for product related
        $product['productCategories'] = $this->getProductRelatedActiveCategories($product->categories);
        
        return $product;
    }

    /**
     * Check if the this product is valid.
     *
     * @param int $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    protected function checkIsValidCategory($productID)
    {
        $productsCategories = $this->productRepository
                                   ->fetchProductCategory($productID);

        $findActiveParents = [];

        // all categories
        $categories = $this->productRepository->fetchCategories();

        if (!empty($productsCategories)) {
            foreach ($productsCategories as $productCategory) {
                $categoriesIDs = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesIDs);
            }
        }

        // get active categories  & make in sigle level
        $makeArrayInSingleLevel = array_flatten($findActiveParents);

        // get active categories & get only unique
        return array_unique($makeArrayInSingleLevel);
    }
	
	

	/**
    * Check the user purchased this item or not
    *
    * @param int $productId
    *
    * @return bool
    *-----------------------------------------------------------------------*/

    protected function isPurchsedProduct($productId)
    {
        // fetch the user of orders
        $orderId = $this->orderRepository->checkIsPurchasedProductByUser();

        if (__isEmpty($orderId)) {
            return false;
        }

        // fetch the order products by order id
        $productsIds = $this->orderRepository->checkOrderedProducts($orderId);

        if (__isEmpty($productsIds)) {
            return false;
        }

        // the item id not available on the array,
        // so it means user not purchase this item
        if (in_array($productId, $productsIds) !== true) {
            return false;
        }

        return true;
    }

    /**
     * Get data for quick view dialog.
     *
     * @param int $productID
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getQuickViewDetailsData($productID, $categoryID)
    {
        $product = $this->productRepository->fetchQuickViewDetails($productID);
        
        $addedInCompareList = false;
        if (NativeSession::has('compareProductId')) {
            $productIds = NativeSession::get('compareProductId');
            if (in_array($productID, $productIds)) {
                $addedInCompareList = true;
            }
        }
        
        // Check if product exist or not
        if (__isEmpty($product)) {
            return __engineReaction(2);
        }
        
        $productId = $product->id;
        $productName = $product->name;
        $transProductName = __transliterate('product', $product->id, 'name', $product->name);

        // Check this product of category is active or not
        $activeCatIDs = $this->checkIsValidCategory($productId);

        // Check the active category array is empty $activeCatIDs
        // so it's product is invalid
        //check if login user is not admin
        if (__isEmpty($activeCatIDs) and !isAdmin()) {
            return __engineReaction(9);
        }

        $isAddedInWishlist = false;

        $wishlistData = $this->productRepository->fetchWishListByUserId($productId);

        if (!__isEmpty($wishlistData)) {
            $isAddedInWishlist = true;
        }

        // get product related material like option related products etc
        $productDetails = $this->getProductForDetails($product);
        $productDetails->name = $productName;
        $productDetails->transProductName = $transProductName;
        $productDetails['isAddedInWishlist'] = $isAddedInWishlist;

        if ($categoryID == 'undefined') {
            $categoryID = '';
        }

        $detailURL = route('product.details', (!empty($categoryID))
                    ? [$productId, slugIt($productName), $categoryID]
                    : ['productID' => $productId, 'productName' => slugIt($productName)]); // get the item rating details

        $productlaunchingDate = null;
        if (isset($productDetails->__data['launching_date'])) {
            $productlaunchingDate = Carbon::parse($product->__data['launching_date'])->toDayDateTimeString();
        }

        $productImage[0] = [
            'products_id' => $productId,
            'file_name' => $product->thumbnail,
            'title' => $product->name,
            'product_option_values_id' => '',
            'zoomURL'            => getProductZoomImageURL($productId, $product->thumbnail),
            'getProductImageURL' => getProductImageURL($productId, $product->thumbnail)
        ];

        $imageSliderData = [];
        // making a format of image slider
        if (!__isEmpty($product->image)) {            
            foreach ($product->image as $key => $image) {             
                $imageSliderData[$key] = [
                    'products_id'   => $image['products_id'],
                    'file_name'     => $image['file_name'],
                    'title'         => $image['title'],
                    'product_option_values_id' => $image['product_option_values_id'] ?? '',
                    'zoomURL'       => getProductZoomImageURL($image['products_id'], $image['file_name']),
                    'getProductImageURL'       => getProductImageURL($image['products_id'], $image['file_name'])
                ];
            }
        }

         // Marge product image in index 0
        $images = array_merge($productImage, $imageSliderData);

        $initialOptionsIds = [];

        if (!__isEmpty($product->option)) {

            foreach($product->option as $option) 
            {  
               $initialOptionsIds[] = array_get(array_pluck($option->optionValues, 'id'), 0);
            }
        }

        $productOptionsImages = array_values(array_where($images, function($item) use($initialOptionsIds) {
            return !__isEmpty($item['product_option_values_id']) && in_array($item['product_option_values_id'], $initialOptionsIds);
        }));
        
        $defaultImages = array_values(array_where($images, function($item) {
            return __isEmpty($item['product_option_values_id']);
        }));

        $productDetails['allImages'] = $images;
        $productDetails['primaryImages'] = __isEmpty($productOptionsImages) ? $defaultImages : $productOptionsImages;
        $productDetails['defaultImages'] = $defaultImages;

        $productDetails['detailURL'] = $detailURL;
        $productDetails['launchingDate'] = $productlaunchingDate; 
        $productDetails['productSpecExists'] = (isset($product->specification_presets__id) and $product->specification_presets__id == true) ? true : false;
        $productDetails['productStatus'] = $product->status;
        $productDetails['productImage'] = getProductImageURL($productId, $product->thumbnail);
        $productDetails['qtyCart'] = (__isEmpty($productDetails->cartProduct))
                                            ? 1 : $productDetails->cartProduct['qty'];
        $productDetails['isCartExist'] = ShoppingCart::where($productId);
        $productDetails['oldPrice' ] = ($productDetails->old_price) ? priceFormat($productDetails->old_price, true, true, ['isMultiCurrency' => true]) : '';

		$productDetails['enableRating']      = getStoreSettings('enable_rating');
		$productDetails['enableRatingReview'] = getStoreSettings('enable_rating_review');
		$productDetails['restrictAddRating'] = getStoreSettings('restrict_add_rating_to_item_purchased_users');
		$productDetails['enableRatingModification'] = getStoreSettings('enable_rating_modification');
		$productDetails['itemRating']        = $this->productRatingDetails($productId);
		$productDetails['isPurchsedProduct']    = $this->isPurchsedProduct($productId);
        $productDetails['itemRatingOrReview'] = $this->prepareRatingAndReviewSupportData($productId);
        $productDetails['productQuestionData'] = $this->prepareProductQuestionData($productId);
        $productDetails['facebookShareUrl'] = route('product.details', ['id' => $productId, 'name' => str_slug($productName)]);
        $productDetails['watsAppShareUrl'] = urlencode(route('product.details', ['id' => $productId, 'name' => str_slug($productName)]));
        $productDetails['twitterShareUrl'] = route('product.details', ['id' => $productId, 'name' => str_slug($productName)]).'&text='.str_slug($productName);
        $productDetails['addedInCompareList'] = $addedInCompareList;

        return __engineReaction(1, ['details' => $productDetails]);
    }

    /**
      * Prepare item rating details
      *
      * @param int $id
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    protected function productRatingAvgDetails($productId)
    {
        $userAvgRating  = $this->productRepository->fetchUserProductRating($productId);
      
        $firstRatingVoteCount = $userAvgRating->where('rating', 1);
        $secondRatingVoteCount = $userAvgRating->where('rating', 2);
        $thirdRatingVoteCount = $userAvgRating->where('rating', 3);
        $fourthRatingVoteCount = $userAvgRating->where('rating', 4);
        $fifthRatingVoteCount = $userAvgRating->where('rating', 5);
       
        $productUserRatings = [
            'oneStarRating'     => [
                'roundAvg'  => round($firstRatingVoteCount->avg('rating'), 1),
                'countVote' => $firstRatingVoteCount->count()
            ],
            'twoStarRating'     => [
                'roundAvg'  => round($secondRatingVoteCount->avg('rating'), 1),
                'countVote' => $secondRatingVoteCount->count()
            ],
            'threeStarRating'   => [
                'roundAvg'  => round($thirdRatingVoteCount->avg('rating'), 1),
                'countVote' => $thirdRatingVoteCount->count()
            ],
            'fourStarRating'    => [
                'roundAvg'  => round($fourthRatingVoteCount->avg('rating'), 1),
                'countVote' => $fourthRatingVoteCount->count()
            ],
            'fiveStarRating'    => [
                'roundAvg'  => round($fifthRatingVoteCount->avg('rating'), 1),
                'countVote' => $fifthRatingVoteCount->count()
            ]
        ];

        return [
            'productUserRatings' => $productUserRatings
        ];
    }

	/**
      * Prepare item rating details
      *
      * @param int $id
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    protected function productRatingDetails($productId)
    {
        $allRating  = $this->productRepository->fetchRatingByProductId($productId);
        $selfRating = $this->productRepository->fetchSelfRatingOnProduct($productId);
        $reviewData = $this->productRepository->fetchProductRating($productId);
        $userAvgRating  = $this->productRepository->fetchUserProductRating($productId);
      
        $firstRatingVoteCount = $userAvgRating->where('rating', 1);
        $secondRatingVoteCount = $userAvgRating->where('rating', 2);
        $thirdRatingVoteCount = $userAvgRating->where('rating', 3);
        $fourthRatingVoteCount = $userAvgRating->where('rating', 4);
        $fifthRatingVoteCount = $userAvgRating->where('rating', 5);
       
        $productUserRatings = [
            'oneStarRating'     => [
                'roundAvg'  => round($firstRatingVoteCount->avg('rating'), 1),
                'countVote' => $firstRatingVoteCount->count()
            ],
            'twoStarRating'     => [
                'roundAvg'  => round($secondRatingVoteCount->avg('rating'), 1),
                'countVote' => $secondRatingVoteCount->count()
            ],
            'threeStarRating'   => [
                'roundAvg'  => round($thirdRatingVoteCount->avg('rating'), 1),
                'countVote' => $thirdRatingVoteCount->count()
            ],
            'fourStarRating'    => [
                'roundAvg'  => round($fourthRatingVoteCount->avg('rating'), 1),
                'countVote' => $fourthRatingVoteCount->count()
            ],
            'fiveStarRating'    => [
                'roundAvg'  => round($fifthRatingVoteCount->avg('rating'), 1),
                'countVote' => $fifthRatingVoteCount->count()
            ]
        ];

        $showDialog = false;
        
        if (getStoreSettings('enable_rating_review')
            or getStoreSettings('enable_rating_review')
            and isset($reviewData->review)
            and (!__isEmpty($reviewData->review))) {
            $showDialog = true;
        }
		
        return [
            'rate'       => $allRating->rate,
            'totalVotes' => $allRating->totalVotes,
            'selfRating' => $selfRating->selfRate,
            'showDialog' => $showDialog,
            'productUserRatings' => $productUserRatings,
        ];
    }

    /**
     * prepare product details for normal page.
     *
     * @param int $productID
     * @param int $pageType
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function preparDetails($productID, $pageType, $categoryID = null)
    {
        $product = $this->productRepository->fetchDetails($productID);

        // Check if product exist or not
        if (__isEmpty($product)) {
            return __engineReaction(18);
        }

        $brand = $product->brand;
        $brandId = $product->brands__id;
        $isBrandInValid = false;
        $brandData = [];

        // If brand exits then check if is active or not
        if (!__isEmpty($brand)) {

            // if logged in user is not admin then show error notification
            if (!isAdmin() and $brand->status !== 1) {
                return __engineReaction(18);
            }

            if ($brand->status !== 1) {
                $isBrandInValid = true;
            }

            $brandData = [
                'id' => $brandId,
                'logoImageURL' => getBrandLogoURL($brandId, $brand->logo),
                'name' => $brand->name,
            ];
        }

        $productId = $product->id;
        $productName = $product->name;
        if (isset($product->__data['launching_date'])) {
            $productlaunchingDate = Carbon::parse($product->__data['launching_date'])
                							->toDayDateTimeString();
        } else {
            $productlaunchingDate = null;
        }

        $productJsonData = $product->__data;

        $seoMeta = [
        	'keywords' => '',
   			'description' => ''
        ];

       	if (isset($productJsonData['seo_meta_info'])) {
       		$seoMeta = [
       			'keywords' => $productJsonData['seo_meta_info']['keywords'] ?? '',
       			'description' => $productJsonData['seo_meta_info']['description'] ?? ''
       		];
       	}
        
        // Check this product of category is active or not
        $activeCatIDs = $this->checkIsValidCategory($productId);

        // Check the active category array is empty $activeCatIDs
        // so it's product is invalid
        if (!isAdmin() and __isEmpty($activeCatIDs)) {
            return __engineReaction(18);
        }

        // get the product specification data
        $specificationData = $specLabel = [];
        $productSpecValue = $productSpecIds = [];
        if (!__isEmpty($product->productSpecification)) {
            
            foreach ($product->productSpecification as $key => $specification) {
            
                if (!__isEmpty($specification->specification)) {
                    $specLabel[$specification->specifications__id] = $specification->specification->label;
                }

                //translate specification values
                $specValue = __transliterate('specification_value', $specification->specification_values__id, 'value', $specification->specificationValue['specification_value']);

                $productSpecValue[$specification->specifications__id][] = $specValue;

                if (!in_array($specification->specifications__id, $productSpecIds)) {

                    
                    $specLabel = '';
                
                    if (isset($specification->specification->label)) {
                        $specLabel = __transliterate('specification_preset', $specification->specification->_id, 'label', $specification->specification->label);
                    }
                    

                    $specificationData[] = [
                        'id' => $specification->_id,
                        'name' => $specLabel,
                        'value' => __transliterate('specification_value', $specification->specification_values__id, 'value', $specification->specificationValue['specification_value']),
                        'specifications__id' => $specification->specifications__id,
                        'products_id' => $specification->products_id
                    ];
                }
                //collect Specification Ids
                $productSpecIds[] = $specification->specifications__id;
            }

            //specification value array push in specCollection data
            foreach ($specificationData as $key => $specData) { 
                $specId = $specData['specifications__id'];
                $specValue = $productSpecValue[$specId];
                $specificationData[$key]['value'] = implode(', ', $specValue);
            }
        }

        $productQuestionCount = $this->productRepository->fetchProductFaqCount($productId);
        $productQuestionData = [];
        if (!__isEmpty($product->productFaq)) {
            foreach ($product->productFaq as $key => $faq) {
                $productQuestionData[] = [
                    'id'                => $faq->_id,
                    'userFullName'      => (!__isEmpty($faq->user))
                                            ? $faq->user->userFullName
                                            : '',
                    'question'          => __transliterate('faq', $faq->_id, 'question', $faq->question),
                    'answer'            => __transliterate('faq', $faq->_id, 'answer', $faq->answer),
                    'humanReadableDate' => humanFormatDateTime($faq->updated_at)
                ];
            }
        }

        $searchValues['id'] = $productId;

        $productImage[0] = [
            'products_id' => $productId,
            'file_name' => $product->thumbnail,
            'title' => $product->name,
            'product_option_values_id' => '',
            'zoomURL'            => getProductZoomImageURL($productId, $product->thumbnail),
            'getProductImageURL' => getProductImageURL($productId, $product->thumbnail)
        ];

        $imageSliderData = [];

        // making a format of image slider
        if (!__isEmpty($product->image)) {
            
            foreach ($product->image as $key => $image) {
             
                $imageSliderData[$key] = [
                    'products_id'   => $image['products_id'],
                    'file_name'     => $image['file_name'],
                    'title'         => $image['title'],
                    'product_option_values_id' => $image['product_option_values_id'] ?? '',
                    'zoomURL'       => getProductZoomImageURL($image['products_id'], $image['file_name']),
                    'getProductImageURL'       => getProductImageURL($image['products_id'], $image['file_name'])
                ];
            }
        }

        // Marge product image in index 0
        $images = array_merge($productImage, $imageSliderData);

        // get active categories for product related
        $categories = $this->getProductRelatedActiveCategories($product->categories);

        // get the RelatedProduct data
        $relatedProducts = $this->getRelatedProduct($product->relatedProducts);

        $productDetails = [
            'id' => $productId,
            'name' => $productName,
            'launchingDate' => $productlaunchingDate,
            'status' => $product->status,
            'brand' => $brandData,
            'isBrandInValid' => $isBrandInValid,
            'product_id' => $productId,
            'custom_product_id' => $product->product_id,
            'description' => __transliterate('product', $productId, 'description', $product->description),
            'seoMeta'	=> $seoMeta,
            'faqDetailUrl' => route('product.user.product_question', [$productId]).'?page_type='.$pageType,
            'productReviewUrl'    => route('product.user.all_review', [$productId]).'?page_type='.$pageType,
        ];
      
		$productRatings = [];
			
		$productRatings['enableRating']      = getStoreSettings('enable_rating');
		$productRatings['enableRatingReview'] = getStoreSettings('enable_rating_review');
		$productRatings['restrictAddRating'] = getStoreSettings('restrict_add_rating_to_item_purchased_users');
		$productRatings['enableRatingModification'] = getStoreSettings('enable_rating_modification');
		$productRatings['itemRating']        = $this->productRatingDetails($productId);
		$productRatings['isPurchsedProduct']    = $this->isPurchsedProduct($productId);
        
        $isAddedInWishlist = false;

        $wishlistData = $this->productRepository->fetchWishListByUserId($productId);

        if (!__isEmpty($wishlistData)) {
            $isAddedInWishlist = true;
        }

        $oldSpecifications = [];
        if (!__isEmpty($product->oldProductSpecification)) {
            foreach ($product->oldProductSpecification as $oldSpecs) {
                $oldSpecifications[] = [
                    "id" => $oldSpecs->_id,
                    "name" => $oldSpecs->name,
                    "value" => $oldSpecs->value,
                    "specifications__id" => null,
                    "products_id" => $oldSpecs->products_id,
                ];
            }
        }

        $initialOptionsIds = [];

        if (!__isEmpty($product->option)) {

            foreach($product->option as $option) 
            {  
               $initialOptionsIds[] = array_get(array_pluck($option->optionValues, 'id'), 0);
            }
        }

        $productOptionsImages = array_values(array_where($images, function($item) use($initialOptionsIds) {
            return !__isEmpty($item['product_option_values_id']) && in_array($item['product_option_values_id'], $initialOptionsIds);
        }));

        $defaultImages = array_values(array_where($images, function($item) {
            return __isEmpty($item['product_option_values_id']);
        }));
        
        // without javascript iteration data
        $serverPutProductData = [
            'allImages' => $images,
            'image' => __isEmpty($productOptionsImages) ? $defaultImages : $productOptionsImages,
            'defaultImages' => $defaultImages,
            'details' => $productDetails,
            'specifications' => array_merge($specificationData, $oldSpecifications),
            'productQuestionData' => $productQuestionData,
            'productQuestionCount' => $productQuestionCount,
            'youtubeVideoCode' => ($product->youtube_video) ? $product->youtube_video : null,
            'categories' => $categories,
            'isActiveCategory' => $activeCatIDs,
            'relatedProductData' => $relatedProducts,
			'productRatings' => $productRatings,
            'isAddedInWishlist' => $isAddedInWishlist,
        ];

        if ($pageType == '') {
            $pageType = 'categories';
        }

        $breadCrumb = Breadcrumb::generate('productDetails', $productId,
                                             [
                                                'pageType'   => $pageType,
                                                'categoryID' => $categoryID,
                                             ]
                                            );

        return __engineReaction(1, [
                'serverPutProductData' => $serverPutProductData,
                'breadCrumb' => $breadCrumb,
            ]);
    }

    /**
     * Prepare product list.
     *
     * @param int    $categoryID
     * @param array  $input
     * @param string $featureProductRouteName
     *
     * @return array 
     *---------------------------------------------------------------- */
    public function prepareList($categoryID = null, $input = null, $featureProductRouteName)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

        $route = !empty($featureProductRouteName)
                    ? $featureProductRouteName
                    : Route::currentRouteName();

        $brands = $brandsIds = $specsIds = $catIds = [];

        if (!isset($input['sbid'])) {
            $brandsIds = __ifIsset($input['brandsIds'], explode('|', $input['brandsIds']), []);
            $input['sbid'] = $brandsIds;
        }

        $input['specification_values'] = [];

        if (isset($input['specsIds'])) {
            $specsIds = __ifIsset($input['specsIds'], explode('|', $input['specsIds']), []);
            $input['specification_values'] = $specsIds;
        }

        $input['filter_category_ids'] = [];

        if (isset($input['categories'])) {
            $catIds = __ifIsset($input['categories'], explode('|', $input['categories']), []);
            $input['filter_category_ids'] = $catIds;
        }

        $filterRating = $availablity = null;
        if (isset($input['rating'])) {
            $filterRating = $input['rating'];
        }

        if (isset($input['availability'])) {
            $availablity = $input['availability'];
        }

        // When the category is available
        if (!__isEmpty($categoryID)) {
            $category = $this->productRepository->fetchCategoryByID($categoryID);
            
            $pageType = 'categories';
            $breadCrumbType = Breadcrumb::generate('categories', $categoryID);

            // Check if category exist
            if (__isEmpty($category)) {
                return __engineReaction(18, [
                                    'pageType' => $pageType,
                                    'breadCrumb' => $breadCrumbType,
                                ]);
            }

            //  check if current category is inactive
            if ($category->status != 1) {
                return __engineReaction(18, [
                            'pageType' => $pageType,
                            'breadCrumb' => $breadCrumbType,
                        ]);
            }

            $category->name = __transliterate('category', $categoryID, 'name', $category->name);

            $categoryCollection = $this->manageCategoryRepository->fetchActiveCategoriesWithChildren($category->id);
            
            $activeCatIds['childrens'] = findActiveChildren($categoryCollection);

            $isInactiveParent = isParentCategoryInactive(getAllCategories(), $category->id);

            // check if the parent cat deactive so do not display child product
            if ($isInactiveParent === false) {
                return __engineReaction(18, [
                                    'pageType' => $pageType,
                                    'breadCrumb' => $breadCrumbType,
                                ]);
            }

            // fetch valid product base on valid categories
            $productCollection = $this->productRepository
                                      ->fetchCategorieProducts(
                                          $activeCatIds['childrens'],
                                          $input, $inactiveBrandIds
                                    );

            $rawProductCollection = $this->productRepository
                                      ->fetchCategorieProductsForFilter(
                                          $activeCatIds['childrens'],
                                          $input, $inactiveBrandIds
                                    );

            // fetch product without pagination
            $resultedProudctOfBrandIds = $this->productRepository
                                               ->fetchCategorieWithoutPaginate(
                                                  $activeCatIds['childrens'],
                                                  $route, $inactiveBrandIds
                                              );

            // fetch min & max price of product
            $productPrices = $this->productRepository
                                   ->fetchMaxAndMinPrice( 
                                        $activeCatIds['childrens'],
                                        $route, $inactiveBrandIds
                                    );
            
            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        } else {

            $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();
            // find all active categories
            $allActiveCategories = findActiveChildren($categoryCollection);
            
            // fetch products data base on active categories & also valid product
            $productCollection = $this->productRepository->fetchAll($allActiveCategories, $input, $route, $inactiveBrandIds);

            $rawProductCollection = $this->productRepository->fetchAllWithoutPaginatationWithFilter($allActiveCategories, $input, $route, $inactiveBrandIds);

            // fetch product without pagination
            $resultedProudctOfBrandIds = $this->productRepository
                                              ->fetchAllWithoutPaginate(
                                                  $allActiveCategories, $input, $route, $inactiveBrandIds
                                              );

            $pageType = 'products';
            $breadCrumbType = Breadcrumb::generate('products');

            if ($route === 'products.featured') {
                $pageType = 'featured';
                $breadCrumbType = Breadcrumb::generate('featured');
            }

            // fetch min & max price of product
            $productPrices = $this->productRepository->fetchMaxAndMinPrice($allActiveCategories, $route, $inactiveBrandIds);

            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        }

        // fetch brand record if available
        if (isset($input['sbid']) and !__isEmpty($input['sbid'])) {
            $brands = $this->brandRepository->fetchBrand($input['sbid']);
        }

        if (isset($productPrices)) {
            $productPrices = $this->prepareProductPrices($productPrices);
        }

        $remainingItems = $productCollection->total() - $productCollection->lastItem();
        $perPage = $productCollection->perPage();
        
        $paginationData = [
            'currentPage'    	=> $productCollection->currentPage(),
            'lastPage'       	=> $productCollection->lastPage(),
            'nextPageURL'    	=> $productCollection->nextPageUrl(),
            'hasMorePages'   	=> $productCollection->hasMorePages(),
            'remainingItems' 	=> (int) $remainingItems,
            'formattedRemainingItem' => formatLocaleNumber($remainingItems),
            'lastItem'      	=> $productCollection->lastItem(),
            'perPage'       	=> (int) $perPage,
            'formattedPerPage'  => formatLocaleNumber($perPage),
            'count'         	=> $productCollection->count(),
            'total'         	=> $productCollection->total(),
			'paginationLinks' 	=> sprintf($productCollection->links("pagination::bootstrap-4"))
        ];
       
        
        $charactorLimit = config('__tech.character_limit');
        $paginateCount = getStoreSettings('pagination_count');
        $activeDiscount = $this->couponRepository->fetchProductDiscounts();

        $products = [];
        $launchingOn = null;
        $userId = getUserID();

        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {

                $productName = $product->name;
                $isAddedInWishlist = false;
                if (isset($product->productWishlist['wishlistId'])) {
                    // if (!__isEmpty($product->productWishlist['wishlistId'])) {
                    $isAddedInWishlist = true;
                }

                if (isset($product->__data['launching_date'])) {
                	if (isset($product['__data']['launching_date'])) {
                		$launchingOn = Carbon::parse($product['__data']['launching_date'])
                							->toDayDateTimeString();
                	}
                }
                $productID = $product->id;
                $launchingDate = $launchingOn;
                $productSlugName = slugIt($productName);
				$productInCart = ShoppingCart::findById((int)$productID);
                $productDiscount = calculateSpecificProductDiscount($productID, $product->price, $activeDiscount);


                // Show productRating
                $productRating = [];
                if (!empty($product->productRating)) {
                    $itemRating = collect($product->productRating);
                 
                    $rating     =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                        $decimal.
                        str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'    => $rating,
                        'totalVote'     => $totalVotes,
                        'formatItemRating'  => $formatRating
                    ];
                }
                // Show productRating

                // if in product addon price add then show addon otherwise not
                if (!empty($product->productOptionValue)) {
                    $addonPriceExists = false;
                    foreach ($product->productOptionValue as $key => $option) {
                        foreach ($option->optionValues as $key => $optionValue) {
                            if (!empty($optionValue->addon_price)) {
                                $addonPriceExists = true; 
                            }
                        }
                    }
                }
                // if in product addon price add then show addon otherwise not
              
                $isAddedInWishlist = false;
                if ($userId == $product->productWishlist['addWishlistUserId']) {
                    $isAddedInWishlist = (isset($product->productWishlist['wishlistId'])
                            and $product->productWishlist['wishlistId'] == true) ? true : false;
                }

                $products[] = [
                    'id'        => $productID,
                    'name'      => str_limit($productName,
                                            $limit = $charactorLimit,
                                            $end = '...'),
                    'productName'       => $productName,
                    'translatedProductName' => __transliterate('product', $productID, 'name', $productName),
                    'productSpecExists' => (isset($product->specification_presets__id) and $product->specification_presets__id == true) ? true : false,
                    'launchingDate'     => $launchingDate,
                    'slugName'          => $productSlugName,
                    'isAddedInWishlist' => $isAddedInWishlist,
                    'addWishlistUserId' => isset($product->productWishlist['addWishlistUserId'])
                            ? $product->productWishlist['addWishlistUserId'] : null,
                    'productRating'     => $productRating,
                    'thumbnailURL'      => getProductImageURL($productID, $product->thumbnail),
                    'out_of_stock'      => $product->out_of_stock,
                    'before_discount_price'   => priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
                    'price'             => $product->price,
                    'featured'          => $product->featured,
                    'productDiscount'   => $productDiscount,
                    'formate_price'     => priceFormat($productDiscount['productPrice'], false, true, ['isMultiCurrency' => true]),
                    'oldPriceExist'     => isset($product['old_price']) ? $product['old_price'] : null,
                    'formatProductOldprice'     => priceFormat($product['old_price'], false, true, ['isMultiCurrency' => true]),
                    'detailURL' => route('product.details', [
                                            $productID, $productSlugName, $categoryID, ]).'?page_type='.$pageType,
                    'options'       => !__isEmpty($product->checkOptionExists) ? true : false,
                    'addonPriceExists' => $addonPriceExists,
					'cartInfo'     => [
						'isCartExist' => __ifIsset($productInCart) ? true : false,
						'quantity' 	  => __ifIsset($productInCart) ? $productInCart['qty'] : 0
					]
                ];
            }
        }

        $productsBrandID = (!empty($input['sbid'])) ? $input['sbid'] : '';

        $brandIds = __ifIsset($resultedProudctOfBrandIds, implode($resultedProudctOfBrandIds, '|'), '');

        return __engineReaction(1, [
            'breadCrumb' => $breadCrumbType,
            'pageType' => (!empty($pageType)) ? '?page_type='.$pageType : '',
            'productCollection' => $productCollection,
            'brands' => $brands,
            'filterUrl' => route('product.filter').'?brands='.$brandIds,
            'brandIds' => $brandIds,
            'currentRoute' => Request::url(),
            'filterPrices' => __ifIsset($priceFilteredArray['filtered_price'], $priceFilteredArray['filtered_price'], ''),
            'productPrices' => __ifIsset($productPrices, $productPrices, []),
            'showFilterPrice' => __ifIsset($priceFilteredArray['show_filtered_price'], $priceFilteredArray['show_filtered_price'], ''),
            'products' => $products,
            'rawProductCollection' => $rawProductCollection,
            'productsBrandID' => $productsBrandID,
            'paginationData' => $paginationData,
            'category' => isset($category) ? $category : null,
            'customRoute' => $route,
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
            'brandsIds' => $brandsIds,
            'specsIds' => $specsIds,
            'filterRating' => $filterRating,
            'availablity' => $availablity,
            'catIds'      => $catIds
        ]);
    }

    /**
     * get product data.
     *
     * @param int $productID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function getProduct($productID)
    {
        return $this->productRepository->fetchProduct($productID);
    }

    /**
     * returnEmptyData.
     *
     * @return array
     *---------------------------------------------------------------- */
    private function returnEmptyData($searchTerm)
    {
        return [
                'filterPrices' => [],
                'breadCrumb' => BreadCrumb::generate('productSearch', null, ['searchTerm' => $searchTerm]),
                'searchTerm' => $searchTerm,
                'productCount' => 0,
                'filterUrl' => route('product.filter').'?brands='.'',
                'productPrices' => [
                    'min_price' => 0,
                    'max_price' => 0
                ],
                'filterPrices' => [
                    'min_price' => 0,
                    'max_price' => 0
                ],
                'showFilterPrice' => [],
                'pageType' => '',
                'currentRoute' => '',
                'paginationData' => [],
                'productsBrandID' => [],
                'customRoute' => '',
            ];
    }

    /**
     * check the search product is valid.
     *
     * @param array  $activeCategoryIds
     * @param object $productIds
     *---------------------------------------------------------------- */
    public function isValidProducts($productIds, $categoryId = null)
    {
        $activeCategoryIds = findActiveChildren(
                                $this->manageCategoryRepository->fetchAllActiveWithChildren()
                            );

        // check if the any category is active or not
        if (__isEmpty($activeCategoryIds)) {
            return [];
        }

        // get product categories
        $productCategories = $this->productRepository
                                  ->fetchProductsCategories($productIds);

        if (__isEmpty($productCategories)) {
            return [];
        }

        $validProductIds = [];

        foreach ($productCategories as $key => $productCategory) {

            // in array check the category id is available in  $activeCategoryIds array
            // it means the product of category is valid
            if (in_array($productCategory->categories_id, $activeCategoryIds)) {
                $validProductIds[$key] = $productCategory->products_id;
            }
        }

        return array_unique(array_intersect($productIds, $validProductIds));
    }

    /**
     * Prepare search suggest list data.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSearchSuggestList($searchQuery)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        // get search suggest list
        $productResult = $this->productRepository
                                  ->fetchProductSearchSuggestList($searchQuery, $inactiveBrandIds, $activeCatIds);

        $categoryResult = $this->manageCategoryRepository
                                  ->fetchCategorySearchSuggestList($searchQuery, $activeCatIds);
                                  
        $brandResult = $this->brandRepository
                                  ->fetchBrandySearchSuggestList($searchQuery);

        $productData = [];
        if (!__isEmpty($productResult)) {
            foreach ($productResult as $key => $product) {
                $productData[] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'type'  => 1,
                    'searchUrl' => route('product.search').'?search_term='.htmlspecialchars($product->name),
                ];
            }

        }

        $categoryData = [];
        if (!__isEmpty($categoryResult)) {
            foreach ($categoryResult as $key => $category) {
           		
                if (!__isEmpty($category->parentCategory)) {
                    //check if parent category is active
                    if ($category->parentCategory->status == 1) {
                       
                        $categoryName = $category->parentCategory->name.' &raquo; '.$category->name.' <small>(category)</small>';

                        $categoryData[] = [
                            'id'    => $category->id,
                            'name'  => $categoryName,
                            'searchUrl' => route('products_by_category', [$category->id, htmlspecialchars($category->name)]),
                            'type'  => 2
                        ];
                    }
                } else {
                    $categoryName = $category->name.' <small>(category)</small>';

                    $categoryData[] = [
                        'id'    => $category->id,
                        'name'  => $categoryName,
                        'searchUrl' => route('products_by_category', [$category->id, htmlspecialchars($category->name)]),
                        'type'  => 2
                    ];
                }
            }
        }
      
        $brandData = [];
        if (!__isEmpty($brandResult)) {
            foreach ($brandResult as $key => $brand) {
                $brandData[] = [
                    'id'    => $brand->id,
                    'name'  => $brand->name.' <small>(brand)</small>',
                    'searchUrl' => route('product.related.by.brand', [$brand->id, htmlspecialchars($brand->name)]),
                    'type'  => 3
                ];
            }
        }

        $searchData = array_merge($brandData, $categoryData, $productData);

        return __engineReaction(1, ['searchResult' => $searchData]);
    }

    /**
     * Prepare search data.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSearch($input)
    {
        $searchTerm = __ifIsset($input['search_term'], $input['search_term'], '');
        $userRequestedSorting = __ifIsset($input['sort_by']);
        $paginatePage = (int) __ifIsset($input['page'], $input['page'], 1);
        $paginatePage = $paginatePage - 1;

        // fetch inactive brand lists
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();        

        // fetch search products ids
        $searchedProductIds = $this->productRepository->fetchSearchProducts($searchTerm, $inactiveBrandIds);

        // found record of search products or not
        if (__isEmpty($searchedProductIds)) {
            return __engineReaction(2, $this->returnEmptyData($searchTerm));
        }

        $validProductIds = $this->isValidProducts($searchedProductIds->toArray(), null);
         
        // get valid products ids
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2, $this->returnEmptyData($searchTerm));
        }

        $brandsIds = $specsIds = $catIds = [];

        if (!isset($input['sbid'])) {
            $brandsIds = __ifIsset($input['brandsIds'], explode('|', $input['brandsIds']), []);
            $input['sbid'] = $brandsIds;
        }

        $input['specification_values'] = [];

        if (isset($input['specsIds'])) {
            $specsIds = __ifIsset($input['specsIds'], explode('|', $input['specsIds']), []);
            $input['specification_values'] = $specsIds;
        }

        $input['filter_category_ids'] = [];

        if (isset($input['categories'])) {
            $catIds = __ifIsset($input['categories'], explode('|', $input['categories']), []);
            $input['filter_category_ids'] = $catIds;
        }

        $filterRating = $availablity = null;
        if (isset($input['rating'])) {
            $filterRating = $input['rating'];
        }

        if (isset($input['availability'])) {
            $availablity = $input['availability'];
        }

        // fetch valid products data base on ids
        $productCollection = $this->productRepository
                                    ->fetchSearchData($validProductIds, $input, $inactiveBrandIds);
        // fetch search product data filter
        $resultedProudctOfBrandIds = $this->productRepository
                                          ->fetchSearchedProductDataForFilter($validProductIds, $input, $inactiveBrandIds);

        $rawProductCollection = $this->productRepository
                                    ->fetchSearchDataWithoutPagination($validProductIds, $input, $inactiveBrandIds);

        $brands = [];

        $products = array_intersect($productCollection->pluck('id')->all(), $validProductIds);
        
        // fetch brnd record if available
        if (isset($input['sbid']) and !__isEmpty($input['sbid'])) {
            $brands = $this->brandRepository->fetchBrand($input['sbid']);
        }

        // fetch min & max price of product
        $productPrices = $this->productRepository->fetchMaxAndMinPriceOfProduct($validProductIds, $inactiveBrandIds);

        // filter array of max & min price
        $priceFilteredArray = $this->priceFilter($productPrices, $input);
        $remainingItems = $productCollection->total() - $productCollection->lastItem();
        
        $paginationData = [
            'currentPage'    => $productCollection->currentPage(),
            'lastPage'       => $productCollection->lastPage(),
            'nextPageURL'    => $productCollection->nextPageUrl(),
            'hasMorePages'   => $productCollection->hasMorePages(),
            'remainingItems' => ($productCollection->total() - $productCollection->lastItem()),
            'lastItem'      => $productCollection->lastItem(),
            'perPage'       => $productCollection->perPage(),
            'count'         => $productCollection->count(),
            'total'         => $productCollection->total(),
            'formattedPerPage'  => formatLocaleNumber($productCollection->perPage()),
            'formattedRemainingItem' => formatLocaleNumber($remainingItems),
			'paginationLinks' 	=> sprintf($productCollection->links('pagination::bootstrap-4'))
        ];
        
        $brandIDs = [];

        $charactorLimit = config('__tech.character_limit');
        $activeDiscount = $this->couponRepository->fetchProductDiscounts();
        $userId = getUserID();

        // Check if products not empty
        if ($productCollection->total() != 0) {
            $productIndex = 0;
            foreach ($productCollection as $product) {
                $productID = $product->id;
                $productName = $product->name;
                $productIDs[] = $productID;
                $slugName = slugIt($productName);
                $productInCart = ShoppingCart::findById((int)$productID);
                $productDiscount = calculateSpecificProductDiscount($productID, $product->price, $activeDiscount);

                $isAddedInWishlist = false;
                if (!__isEmpty($product->productWishlist['wishlistId'])) {
                    $isAddedInWishlist = true;
                }

                $isAddedInWishlist = false;
                if ($userId == $product->productWishlist['addWishlistUserId']) {
                    $isAddedInWishlist = (isset($product->productWishlist['wishlistId'])
                            and $product->productWishlist['wishlistId'] == true) ? true : false;
                }

                // To maintain a search sorting used array_search to get existing index position.
                /*
                    $userRequestedSorting 
                            ? $productIndex 
                            : array_search($productID, $products)
                    
                */

                // Show productRating
                $productRating = [];
                if (!empty($product->productRating)) {
                    $itemRating = collect($product->productRating);
                 
                    $rating =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).$decimal.str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'        => $rating,
                        'formatItemRating'  => $formatRating,
                        'totalVote'         => $totalVotes
                    ];
                }
                // Show productRating

                // if in product addon price add then show addon otherwise not
                if (!empty($product->productOptionValue)) {
                    $addonPriceExists = false;
                    foreach ($product->productOptionValue as $key => $option) {
                        foreach ($option->optionValues as $key => $optionValue) {
                            if (!empty($optionValue->addon_price)) {
                                $addonPriceExists = true; 
                            }
                        }
                    }
                }
                // if in product addon price add then show addon otherwise not           

                $products[$userRequestedSorting 
                            ? $productIndex 
                            : array_search($productID, $products)] = [
                    'id' => $productID,
                    'name' => str_limit($productName, $limit = $charactorLimit, $end = '...'),
                    'slugName' => $slugName,
                    'productSpecExists' => (isset($product->specification_presets__id) and $product->specification_presets__id == true) ? true : false,
                    'productRating' => $productRating,
                    'isAddedInWishlist' => $isAddedInWishlist,
                    'addWishlistUserId' => isset($product->productWishlist['addWishlistUserId'])
                            ? $product->productWishlist['addWishlistUserId'] : null,
                    'productName' => $productName,
                    'before_discount_price' => priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
                    'productDiscount' => $productDiscount,
                    'formate_price' => priceFormat($productDiscount['productPrice'], false, true, ['isMultiCurrency' => true]),
                    'out_of_stock' => $product->out_of_stock,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'oldPriceExist' => isset($product['old_price']) ? $product['old_price'] : null,
                    'formatProductOldprice' => priceFormat($product['old_price'], false, true, ['isMultiCurrency' => true]),
                    'detailURL' => route('product.details', [$productID, $slugName]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                    'addonPriceExists' => $addonPriceExists,
                    'cartInfo' => [
                        'isCartExist' => __ifIsset($productInCart) ? true : false,
                        'quantity'    => __ifIsset($productInCart) ? $productInCart['qty'] : 0
                    ]
                ];
                $productIndex++;
            }
        }

        $productsBrandID = [];

        if (!empty($input['sbid'])) {
            $productsBrandID = $input['sbid'];
        }

        // set brnad filter url source for data
        $brandIds = __ifIsset($resultedProudctOfBrandIds, implode($resultedProudctOfBrandIds, '|'), '');

        if (isset($productPrices)) {
            $productPrices = $this->prepareProductPrices($productPrices);
        }

        return __engineReaction(1, [
            'breadCrumb' => BreadCrumb::generate('productSearch', null, ['searchTerm' => $searchTerm]),
            'searchTerm' => $searchTerm,
            'productCount' => $productCollection->total(),
            //'productCollection' => $productCollection,
            'products' => $products,
            'rawProductCollection' => $rawProductCollection,
            'filterUrl' => route('product.filter').'?brands='.$brandIds,
            'filterPrices' => $priceFilteredArray['filtered_price'],
            'productPrices' => $productPrices,
            'showFilterPrice' => $priceFilteredArray['show_filtered_price'],
            // 'pageType' => 'search',
            'pageType' => '?page_type=products',
            'currentRoute' => Request::url(),
            'paginationData' => $paginationData,
            'brands' => $brands,
            'productsBrandID' => $productsBrandID,
            'category' => isset($category) ? $category : null,
            'customRoute' => Route::currentRouteName(),
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
            'brandsIds' => $brandsIds,
            'specsIds' => $specsIds,
            'filterRating' => $filterRating,
            'availablity' => $availablity,
            'catIds'      => $catIds
        ]);
    }

    /**
     * prepare filter for search data.
     *
     * @param array $input
     *
     * @return object
     *---------------------------------------------------------------- */
    public function prepareFilter($input)
    {
        $brandsIds = [];

        if (__ifIsset($input['brands'])) {
            $brandsIds = explode('|', $input['brands']);
        }

        // fetch brands of display found result of products
        $brands = $this->brandRepository->fetchBrand($brandsIds);

        $allBrands = [];

        if (!__isEmpty($brands)) {
        	foreach ($brands as $key => $brand) {
        		$allBrands[] = [
        			'brandID' => $brand->brandID,
        			'brandName' => __transliterate('brand', $brand->brandID, 'name', $brand->brandName),
        		];
        	}
        }

        return __engineReaction(1, ['productRelatedBrand' => $allBrands]);
    }

    /**
     * get products raleted brands.
     *
     * @param int $brandID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function prepareFilterBrandRelatedProduct($brandID = null)
    {
        $brand = $this->brandRepository->fetchIsActiveByID($brandID);

        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        // fetch product record base on brand id
        $productIds = $this->productRepository
                                 ->fetchProductByBrandId($brand->_id);
         
        // check  founded brand products is valid
        $validProductIds = $this->isValidProducts($productIds, null);

        // if not valid product so there is no valid product available
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2);
        }

        $productRelatedBrand = $this->productRepository
                                      ->fetchFilterBrandRelatedProduct($validProductIds);

        return __engineReaction(1, ['productRelatedBrand' => $productRelatedBrand]);
    }

    /**
     * get brand raleted products.
     *
     * @param int $brandID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareBrandRelatedProduct($brandID, $brandName, $input = [])
    {
        $brand = $this->brandRepository->fetchIsActiveByID($brandID);

        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        $brand->name = __transliterate('brand', $brandID, 'name', $brand->name);

        $productIds = $this->productRepository
                               ->fetchProductByBrandId($brand->_id);

        // verify the product ids & return valid product ids
        $validProductIds = $this->isValidProducts($productIds, null);

        // check the any valid product id empty $validProductIds
        // it means empty then valid product not available
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2, [
                'breadCrumb' => BreadCrumb::generate('brandProduct', $brandID),
                'brandID' => $brandID,
                'brand' => $brand,
                'filterUrl' => route('product.filter'),
                'currentRoute' => Request::url(),
                'customRoute' => Route::currentRouteName(),
                'filterPrices' => [],
                'productPrices' => [],
                'paginationData' => [],
                'productExistOrNot' => false,
            ]);
        }

        $brandsIds = $specsIds = $catIds = [];

        if (!isset($input['sbid'])) {
            $brandsIds = __ifIsset($input['brandsIds'], explode('|', $input['brandsIds']), []);
            $input['sbid'] = $brandsIds;
        }

        $input['specification_values'] = [];

        if (isset($input['specsIds'])) {
            $specsIds = __ifIsset($input['specsIds'], explode('|', $input['specsIds']), []);
            $input['specification_values'] = $specsIds;
        }

        $input['filter_category_ids'] = [];

        if (isset($input['categories'])) {
            $catIds = __ifIsset($input['categories'], explode('|', $input['categories']), []);
            $input['filter_category_ids'] = $catIds;
        }

        $filterRating = $availablity = null;
        if (isset($input['rating'])) {
            $filterRating = $input['rating'];
        }

        if (isset($input['availability'])) {
            $availablity = $input['availability'];
        }

        // fetch the product data
        $productCollection = $this->productRepository
                                     ->fetchBrandRelatedProduct($validProductIds, $input);

        $rawProductCollection = $this->productRepository
                                     ->fetchBrandRelatedProductWithoutPagination($validProductIds, $input);

        $remainingItems = $productCollection->total() - $productCollection->lastItem();
        $paginationData = [
            'currentPage'    => $productCollection->currentPage(),
            'lastPage'       => $productCollection->lastPage(),
            'nextPageURL'    => $productCollection->nextPageUrl(),
            'hasMorePages'   => $productCollection->hasMorePages(),
            'remainingItems' => $remainingItems,
            'formattedRemainingItem' => formatLocaleNumber($remainingItems),
            'lastItem'       => $productCollection->lastItem(),
            'perPage'        => $productCollection->perPage(),
            'formattedPerPage'  => formatLocaleNumber($productCollection->perPage()),
            'count'          => $productCollection->count(),
            'total'          => $productCollection->total(),
			'paginationLinks' 	=> sprintf($productCollection->links('pagination::bootstrap-4'))
        ];

        $products = [];
        $charactorLimit = config('__tech.character_limit');
        $activeDiscount = $this->couponRepository->fetchProductDiscounts();
        $userId = getUserID();

        // Check if products not empty
        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {
                $productName = $product->name;
                $productID = $product->id;
                $productIDs[] = $product->id;
                $slugName = slugIt($productName);
				$productInCart = ShoppingCart::findById((int)$productID);
                $productDiscount = calculateSpecificProductDiscount($productID, $product->price, $activeDiscount);

                // if in product addon price add then show addon otherwise not
                if (!empty($product->productOptionValue)) {
                    $addonPriceExists = false;
                    foreach ($product->productOptionValue as $key => $option) {
                        foreach ($option->optionValues as $key => $optionValue) {
                            if (!empty($optionValue->addon_price)) {
                                $addonPriceExists = true; 
                            }
                        }
                    }
                }

                // Show productRating
                $productRating = [];
                if (!empty($product->productRating)) {
                    $itemRating = collect($product->productRating);
                 
                    $rating     =  ROUND($itemRating->avg('rating'), 1);
                    $decimal    = '';
                    $rated      = floor($rating);
                    $unrated    = floor(5 - $rated);
                    $totalVotes =  COUNT($itemRating);

                    if (fmod($rating, 1) != 0) { 
                        $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                        $unrated = $unrated - 1;
                    }
                   
                    $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                        $decimal.
                        str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                    $productRating = [
                        'itemRating'    => $rating,
                        'totalVote'     => $totalVotes,
                        'formatItemRating'  => $formatRating
                    ];
                }
                // Show productRating
                
                // if in product addon price add then show addon otherwise not
                $isAddedInWishlist = false;
                if (!__isEmpty($product->productWishlist['wishlistId'])) {
                    $isAddedInWishlist = true;
                }

                $isAddedInWishlist = false;
                if ($userId == $product->addWishlistUserId) {
                    $isAddedInWishlist = (isset($product->productWishlist['wishlistId'])
                            and $product->productWishlist['wishlistId'] == true) ? true : false;
                }

                $products[] = [
                    'id' => $productID,
                    'name' => str_limit($productName,
                                            $limit = $charactorLimit,
                                            $end = '...'),
                    'productName' => $productName,
                    'productSpecExists' => (isset($product->specification_presets__id) and $product->specification_presets__id == true) ? true : false,
                    'slugName' => $slugName,
                    'oldPriceExist' => isset($product['old_price']) ? $product['old_price'] : null,
                    'formatProductOldprice' => priceFormat($product['old_price'], false, true, ['isMultiCurrency' => true]),
                    'isAddedInWishlist' => $isAddedInWishlist,
                    'productRating'    => $productRating,
                    'addWishlistUserId' => isset($product->productWishlist['addWishlistUserId'])
                            ? $product->productWishlist['addWishlistUserId'] : null,
                    'before_discount_price' => priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
                    'productDiscount' => $productDiscount,
                    'formate_price' => priceFormat($productDiscount['productPrice'], false, true, ['isMultiCurrency' => true]),
                    'out_of_stock' => $product->out_of_stock,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'detailURL' => route('product.details', [
                                            $productID, $slugName, ]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                    'addonPriceExists' => $addonPriceExists,
					'cartInfo' => [
						'isCartExist' => __ifIsset($productInCart) ? true : false,
						'quantity' 	  => __ifIsset($productInCart) ? $productInCart['qty'] : 0
					]
                ];
            }
        }

        // fetch min & max price of product
        $productPrices = $this->productRepository->fetchMaxAndMinPriceOfProduct($validProductIds);

        // filter array of max & min price
        $priceFilteredArray = $this->priceFilter($productPrices, $input);

        if (isset($productPrices)) {
            $productPrices = $this->prepareProductPrices($productPrices);
        }

        return __engineReaction(1, [
            'breadCrumb' => BreadCrumb::generate('brandProduct', $brandID),
            'productCollection' => $productCollection,
            'products' => $products,
            'rawProductCollection' => $rawProductCollection,
            'currentRoute' => Request::url(),
            'filterUrl' => route('product.filter'),
            'filterPrices' => $priceFilteredArray['filtered_price'],
            'productPrices' => $productPrices,
            'showFilterPrice' => $priceFilteredArray['show_filtered_price'],
            'brandID' => $brandID,
            'paginationData' => $paginationData,
            'brand' => $brand,
            'customRoute' => Route::currentRouteName(),
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
            'brandsIds' => $brandsIds,
            'specsIds' => $specsIds,
            'filterRating' => $filterRating,
            'availablity' => $availablity,
            'catIds'      => $catIds
        ]);
    }

    /**
     * Prepare product prices
     *
     * @param obj $productPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function prepareProductPrices($productPrice)
    {
        if (getStoreSettings('display_multi_currency') == true
            and getSelectedCurrency() != getCurrency()) {
            
            if (!__isEmpty($productPrice)) {
                $newPrice = [
                    'max_price' => calculateForeignExchangeRates($productPrice->max_price)['amount'],
                    'min_price' => calculateForeignExchangeRates($productPrice->min_price)['amount']
                ];
                
                $productPrice = collect($newPrice);
            }

        }

        return $productPrice;
    }

	 /**
    * Enable rating modification
    *
    * @param int $productId
    *
    * @return bool
    *-----------------------------------------------------------------------*/

    protected function isValidForRateOnProduct($productId)
    {
        $isPurchsedItem = $this->isPurchsedProduct($productId);

        // Who Had purchased item will able to rate for that particular item
        if (getStoreSettings('restrict_add_rating_to_item_purchased_users')
            and $isPurchsedItem === false
            ) {
            return false;
        }

        return true;
    }

	 /**
    * Enable rating modification
    *
    * @param int $itemId
    *
    * @return bool
    *-----------------------------------------------------------------------*/

    protected function enableRatingModification()
    {
        // modification not allowed on this item
        if (!getStoreSettings('enable_rating_modification')
            ) {
            return false;
        }

        return true;
    }

     /**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductCompareCount()
    {
        $exisitingId = [];
        if (NativeSession::has('compareProductId')) {
            $exisitingId = NativeSession::get('compareProductId');
        }

        return __engineReaction(1, [
            'totalProductCompare' => count($exisitingId)
        ]);
    }

    /**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddCompareProduct($productId)
    {
        $product = $this->productRepository->fetchByID($productId);
       
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        if (__isEmpty($product->specification_presets__id)) {
            return __engineReaction(2, null, __tr('This product cannot be compared'));
        }

          
        $exisitingId = [];
        if (NativeSession::has('compareProductId', $productId)) {
            $exisitingId = NativeSession::get('compareProductId', $productId);
            //NativeSession::remove('productId', $productId);
        }

        if (!__isEmpty($productId)) {
            if (in_array($productId, $exisitingId)) {
                return __engineReaction(2, null, __tr('This product already exists in compare list.'));
            }
        }

        // $firstProductCategory = [];
        // $secondProductCategory = [];
        $firstProductSpecId = [];
        $secondProductSpecId = [];
        $existIds = [];
        if (!__isEmpty($exisitingId)) {
            foreach ($exisitingId as $key => $existId) {
                $existIds = $existId;
            }
            $setProductID = $existIds;

            //first product category
            $firstProductCategory = $this->productRepository->fetchProductAllCategory($setProductID);
            $firstProductSpecId = $firstProductCategory->specification_presets__id;
            
            //second product category
            //$secondProductCategory =  $product->categories->toArray();
            $secondProductSpecId = $product->specification_presets__id;
         
        }
     

        $dataExist = false;
   
        if ($firstProductSpecId === $secondProductSpecId) {
            $dataExist = true;
        }
        elseif (__isEmpty($exisitingId)) {
            $dataExist = true; 
        }
    
        if ($dataExist == false) {
            return __engineReaction(2, null, __tr('Please compare similar product.'));
        }
        
        $countCompareProduct = [];
        if (!__isEmpty($productId)) {
            if (!in_array($productId, $exisitingId)) {
                NativeSession::push('compareProductId', $productId);

                if (NativeSession::has('compareProductId', $productId)) {
                    $countCompareProduct = NativeSession::get('compareProductId', $productId);
                }

                return __engineReaction(1, [
                    'countCompareProduct' => count($countCompareProduct)
                ], __tr('The product has been added to your compare list.'));
            }
            
            return __engineReaction(2, null, __tr('The product already exists in compare list.'));
        }

        return __engineReaction(2, null, __tr('Product does not exist'));
    }

     /**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processRemoveAllCompareProduct()
    {
        if (NativeSession::has('compareProductId')) {

            $countCompareProduct = NativeSession::get('compareProductId');
            NativeSession::remove('compareProductId', $countCompareProduct);

            return __engineReaction(1,  [
                    'countCompareProduct' => count($countCompareProduct)
                ], __tr('All products removed from compare list'));
        }

        return __engineReaction(2, null, __tr('Product does not exist'));
    }

     /**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processRemoveCompareProduct($productId)
    {
        $product = $this->productRepository->fetchByID($productId);
     
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $exisitingId = [];
        if (NativeSession::has('compareProductId', $productId)) {
            $exisitingId = NativeSession::get('compareProductId', $productId);
            //NativeSession::remove('productId', $productId);
        }
        //NativeSession::remove('productId');

        if (!__isEmpty($productId)) {
            if (in_array($productId, $exisitingId)) {

                if (($key = array_search($productId, $exisitingId)) !== false) {
                    unset($exisitingId[$key]);
                }

                //remove all existing product id
                NativeSession::remove('compareProductId');
                 
                //set all existing product id
                NativeSession::set('compareProductId', $exisitingId);

                return __engineReaction(1, null, __tr('The product has been removed.'));
            }

            return __engineReaction(2, null, __tr('The product has already been removed.'));
        }

        return __engineReaction(1, null, __tr('Invalid request.'));
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
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductCompareData()
    {
        $productIds = [];
        if (NativeSession::has('compareProductId')) {
            $productIds = NativeSession::get('compareProductId');
        }

        $productCompareData = [];
        $specificationData = [];
        $productSpecificationData = [];
        $specificationData = [];
        if (!__isEmpty($productIds)) {

            $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
            $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

            // find all active categories
            $activeCatIds = findActiveChildren($categoryCollection);

            //product Data
            $productCollection = $this->productRepository->fetchProductById($productIds, $inactiveBrandIds, $activeCatIds);

            //specification Collection
            $productSpecification = $this->manageProductRepository->fetchProductSpecificationBySpecId($productIds);

            $specIds = [];
            if (!__isEmpty($productSpecification)) {
                foreach ($productSpecification as $key => $specData) {

                    if (!in_array($specData->specifications__id, $specIds)) {
                       $specificationData[] = [
                            'specId' => $specData->specifications__id,
                            'label' => $specData->label,
                            'products_id' => $specData->products_id
                        ];
                    }
                    //collect specififcation Ids
                    $specIds[] = $specData->specifications__id;
                }
            }

            $activeDiscount = $this->couponRepository->fetchProductDiscounts();

            //product Collection Data
            if (!__isEmpty($productCollection)) {
                foreach ($productCollection as $key => $product) { 
                    
                    $productDiscount = calculateSpecificProductDiscount($product->id, $product->price, $activeDiscount);

                    $specCollection = [];
                    $productSpecIds = [];   
                    $productSpecValue = []; 
                    foreach ($product->productSpecification as $key => $specification) {
                        $specName = [];
                        if (!__isEmpty($specification->specification)) {
                            $specName = $specification->specification->label;
                        }
                        $productSpecValue[$specification->specifications__id][] = $specification->specificationValue['specification_value'];

                        if (!in_array($specification->specifications__id, $productSpecIds)) {
                            $specCollection[] = [
                                'id' => $specification->_id,
                                'name' => $specName,
                                'value' => $specification->specificationValue['specification_value'],
                                'specifications__id' => $specification->specifications__id,
                                'products_id' => $specification->products_id,

                            ];
                        }

                        //collect Specification Ids
                        $productSpecIds[] = $specification->specifications__id;
                    }     

                    //specification value array push in specCollection data
                    foreach ($specCollection as $key => $specData) {
                        $specId = $specData['specifications__id'];
                        $specValue = $productSpecValue[$specId];
                        $specCollection[$key]['value'] = implode(', ', $specValue);
                    }

                    //product Data
                    $productCompareData[] = [
                        'id' => $product->id,
                        'name' => __transliterate('product', $product->id, 'name', $product->name),
                        'price' =>  priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
                        'before_discount_price'   => priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
                        'productDiscount' => $productDiscount,
                        'old_price' => (!__isEmpty($product->old_price))
                                        ? priceFormat($product->old_price, false, true, ['isMultiCurrency' => true])
                                        : null,
                        'status' => $product->status,
                        'out_of_stock' => $product->out_of_stock,
                        'productImage' =>  getProductImageURL($product->id, $product->thumbnail),
                        'out_of_stock' => $product->out_of_stock,
                        'specCollection' => $specCollection,
                        'detailURL' => route('product.details', [$product->id, slugIt($product->name)]),
                    ];
                }
            }
        }

        $breadCrumbType = Breadcrumb::generate('productsCompare');

        return __engineReaction(1, [
            'productCompareData' => $productCompareData,
            'breadCrumb' => $breadCrumbType,
            'totalProductCompare' => count($productCompareData),
            'specificationCollection' => $specificationData
        ]);
    }

	/**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddOrUpdateRating($productId, $rate)
    {
        // by default rating is on other wise not able to add rating on this item
        if (!getStoreSettings('enable_rating')) {
            return __engineReaction(2, null, __tr('Rating is not allowed'));
        }

        $product = $this->productRepository->fetchByID($productId);

        if (__isEmpty($product) or $product->status !== 1) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }
        
        // modification not allowed on this item
        if (!$this->isValidForRateOnProduct($productId)) {
            return __engineReaction(2, null, __tr('Only verified buyers can add rating.'));
        }

		$productId = $product->id;

        $inputData['products_id'] = $productId;
        $inputData['rating']      = $rate;

        // fetch the user already rate on item or not
        $productRating = $this->productRepository->fetchProductRating($productId);

        // if the rate is empty then add new rate other wise always update.
        if (__isEmpty($productRating)) {

          // Check if rating stored then return message
          if ($this->productRepository->storeRatings($inputData)) {
              return __engineReaction(1, [
                'itemRating' => $this->productRatingDetails($productId) // get the item rating details
            ], null); // success on add
          }
        }

        // modification not allowed on this item
        if (!$this->enableRatingModification()) {
            return __engineReaction(2, null, __tr('Modification not allowed.'));
        }

        // update rating on item
        if ($this->productRepository->updateRatings($productRating, $inputData)) {
            return __engineReaction(1, [
                'itemRating' => $this->productRatingDetails($productId) // get the item rating details
            ], null);  // success on update
        }

        return __engineReaction(3); // error
    }

    /**
    * Prepare Rating And Review SupportData
    *
    * @param int $productId
    *
    * @return void
    *-----------------------------------------------------------------------*/

    public function preparProductAllRerviews($productId, $pageType)
    {
        $product = $this->productRepository->fetchByID($productId);

        // Check if product exist or not
        if (__isEmpty($product)) {
            return __engineReaction(18);
        }
      
        $productReviews = [
            'products_id'       => $productId,
            'file_name'         => $product->thumbnail,
            'title'             => __transliterate('product', $productId, 'name', $product->name),
            'detailURL'         => route('product.details', ['productID' => $productId, 
                                    'productName' => slugIt($product->name)]),
            'productImage'      => getProductImageURL($productId, $product->thumbnail),
            'itemRating'        => $this->productRatingDetails($productId),
        ];

        if ($pageType == '') {
            $pageType = 'categories';
        }

        $breadCrumb = Breadcrumb::generate('productAllReviews', $productId,
                                            [
                                                'pageType'   => $pageType,
                                                'categoryID' => null,
                                            ]
                                        );
      
        return __engineReaction(1, [
            'productAllReviewdetail' => $productReviews,
            'breadCrumb'         => $breadCrumb,
        ]);
    }

    /**
     * Get product reviews paginated data list
     *
     * @return array
     *---------------------------------------------------------------- */

    public function getReviewsPaginateData($productId)
    {
        $productReviewsData =  $this->productRepository
                                        ->prepareProductRatingAndReviewData($productId);

        $reviewData = [];
        if (!__isEmpty($productReviewsData)) {
            foreach ($productReviewsData as $productReview) {
                $reviewData[] = [
                    'fullName' => $productReview->full_name,
                    'rating'   => round($productReview->rating),
                    'ratedOn'  => formatDateTime($productReview->updated_at),
                    'review' => __isEmpty($productReview->review) ? '' : $productReview->review,
                    'userRatingReview'   => configItem('user_rating_review' ,$productReview->rating),
                    'humanReadableDate' => humanFormatDateTime($productReview->updated_at)
                ];
            }
        }
   
        return __engineReaction(1, [
            'paginationLinks'    => sprintf($productReviewsData->links("pagination::bootstrap-4")),
            'productReviewData'  => $reviewData,
        ]);
    }


    /**
    * Prepare Rating And Review SupportData
    *
    * @param int $productId
    *
    * @return void
    *-----------------------------------------------------------------------*/

    public function preparProductAllQuestions($productId, $pageType)
    {
        $product = $this->productRepository->fetchByID($productId);

        // Check if product exist or not
        if (__isEmpty($product)) {
            return __engineReaction(18);
        }
      
        $productData = [
            'products_id'       => $productId,
            'file_name'         => $product->thumbnail,
            'title'             => __transliterate('product', $productId, 'name', $product->name),
            'detailURL'         => route('product.details', ['productID' => $productId, 
                                    'productName' => slugIt($product->name)]),
            'productImage'      => getProductImageURL($productId, $product->thumbnail)
        ];

        if ($pageType == '') {
            $pageType = 'categories';
        }


        $breadCrumb = Breadcrumb::generate('productAllQuestions', $productId,
                                             [
                                                'pageType'   => $pageType,
                                                'categoryID' => null,
                                             ]
                                            );
      
        return __engineReaction(1, [
            'productData'  => $productData,
            'breadCrumb'   => $breadCrumb,
        ]);
    }

     /**
     * Get article post list
     *
     * @return array
     *---------------------------------------------------------------- */

    public function getProductQuestionPaginateData($productId)
    {
        $productQuestion =  $this->productRepository->prepareProductQuestion($productId);

        $productQuestionData = [];
        if (!__isEmpty($productQuestion)) {
            foreach ($productQuestion as $question) {
                if (!__isEmpty($question->answer)) {
                    $productQuestionData[] = [
                        'fullName' => $question->full_name,
                        'question'   => __transliterate('faq', $question->_id, 'question', $question->question),
                        'updated_at'  => formatDateTime($question->updated_at),
                        'answer' => __transliterate('faq', $question->_id, 'answer', $question->answer),
                        'humanReadableDate' => humanFormatDateTime($question->updated_at)
                    ];
                }
            }
        }
   
        return __engineReaction(1, [
            'paginationLinks'    => sprintf($productQuestion->links("pagination::bootstrap-4")),
            'productQuestionData'  => $productQuestionData,
        ]);
    }

    /**
    * Prepare Rating And Review SupportData
    *
    * @param int $productId
    *
    * @return void
    *-----------------------------------------------------------------------*/

    public function prepareProductQuestionData($productId)
    {
        $productQuestions = $this->productRepository->fetchProductQuestion($productId);
      
        if (__isEmpty($productQuestions)) {
            return __engineReaction(1, [
                'productQuestionData' => ''
            ]);
        }

        $productQuestionData = [];

        foreach ($productQuestions as $question) {
            if (!__isEmpty($question->answer)) {
                $productQuestionData[] = [
                    'userFullName'      => $question->userFullName,
                    'question'          => $question->question,
                    'answer'            => $question->answer,
                    'humanReadableDate' => humanFormatDateTime($question->updated_at)
                ];
            }
        }

        return [
            'productQuestionData' => $productQuestionData
        ];

    }

	/**
    * Prepare Rating And Review SupportData
    *
    * @param int $productId
    *
    * @return void
    *-----------------------------------------------------------------------*/

    public function prepareRatingAndReviewSupportData($productId)
    {
        $ratingAndReviews = $this->productRepository->fetchProductRatingAndReview($productId);

        if (__isEmpty($ratingAndReviews)) {
            return [
                'itemRatingAndReview' => '',
                'reviewCount'         => count($ratingAndReviews),
                'totalReview'         => count($ratingAndReviews),
                'configReviewCount'   => configItem('product_detail_user_review_count')
            ];
        }

        $ratedData = [];
        $reviewData = [];
        foreach ($ratingAndReviews as $ratingAndReview) {
            if (!__isEmpty($ratingAndReview->review)) {
                $reviewData[] = $ratingAndReview->review;
            }

            $ratedData[] = [
                'fullName' => $ratingAndReview->full_name,
                'rating'   => round($ratingAndReview->rating),
                'ratedOn'  => formatDateTime($ratingAndReview->updated_at),
                'review'   => __isEmpty($ratingAndReview->review)
                                ? ''
                                : $ratingAndReview->review,
                'userRatingReview' => configItem('user_rating_review', $ratingAndReview->rating),
                'humanReadableDate' => humanFormatDateTime($ratingAndReview->updated_at)
            ];
        }

        $originalRatingAndReviewCount = $this->productRepository->fetchProductReviewCount($productId);

        return [
            'itemRatingAndReview' => $ratedData,
            'reviewCount'         => count($reviewData),
            'totalReview'         => count($ratingAndReviews),
            'configReviewCount'   => configItem('product_detail_user_review_count'),
            'originalRatingAndReviewCount' => $originalRatingAndReviewCount
        ];

        // return __engineReaction(1, [
        //     'itemRatingAndReview' => $ratedData
        // ]);
    }

	/**
     * Get review support data
     *
     * @param int $productId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareReviewSupportData($productId)
    {
        $product = $this->productRepository->fetchByID($productId);

        if (__isEmpty($product) or $product->status !== 1) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        // fetch the user already rate on product or not
        $productReview = $this->productRepository->fetchProductRating($product->id);

        if (__isEmpty($productReview)) {
            return __engineReaction(1, [
                'reviewData' => []
            ]);
        }

        return __engineReaction(1, [
                'reviewData' => [
                    '_id'    => $productReview->_id,
                    'review' => $productReview->review
                ]
            ]);
    }

	/**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddReview($productId, $input)
    {
        // by default review is on other wise not able to add review on this item
        if (!getStoreSettings('enable_rating_review')) {
            return __engineReaction(2, null, __tr('Review not allowed'));
        }

        $product = $this->productRepository->fetchByID($productId);

        if (__isEmpty($product) or $product->status !== 1) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        // modification not allowed on this product
        if (!$this->isValidForRateOnProduct($productId)) {
            return __engineReaction(2, null, __tr('Only verified buyers can add rating.'));
        }

        // fetch the user already rate on product or not
        $productRating = $this->productRepository->fetchProductRating($product->id);

        if (!__isEmpty($productRating->review)) {

            // modification not allowed on this product
            if (!$this->enableRatingModification()) {
                return __engineReaction(2, null, __tr('Modification not allowed.'));
            }
        }

        // update rating on product
        if ($this->productRepository->updateRatings($productRating, $input)) {
            return __engineReaction(1, null, __tr('You reviewed on this product.'));  // success on update
        }

        return __engineReaction(2); // error
    }

    /**
     * Process add item rating.
     *
     * @param int   $itemId
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductFaqs($productId, $type, $input)
    {   
        $product = $this->productRepository->fetchByID($productId);

        if (__isEmpty($product) or $product->status !== 1) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $faqsAddData = [ 
            'products_id' => $productId,
            'question'    => $input['addQuestion'],
            'users_id'    => getUserID(),
            'status'      => 1,
            'type'        => $type
        ];

        //add product faqs questions
        if ($this->productRepository->storeProductFaqs($faqsAddData)) {
            return __engineReaction(1, null, __tr('Faqs Added successfully.'));
        }

        return __engineReaction(2, null, __tr('Faqs Not Added.')); // error
    }

    /**
     * Process add to wishList.
     *
     * @param int $productId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddNotifyUser($productId, $inputData)
    {
        $product = $this->productRepository->fetchByID($productId);

        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $fetchNotifyUser = $this->productRepository->fetchProductNotifyUser($product->id, $inputData['notifyUserEmail']);

        if (!__isEmpty($fetchNotifyUser)) {
            return __engineReaction(2, null, __tr('User Already Notify on This Product.'));
        }

        $userData = $this->userRepository->fetchActiveUserByEmail($inputData['notifyUserEmail']);

        $insertData = [
            'email'         => $inputData['notifyUserEmail'],
            'products_id'   => $product->id,
            'users_id'      => isset($userData->id) ? $userData->id : null,
            'status'        => 1
        ];

        // If tax addded then return reaction code for success
        if ($this->productRepository->storeNotifyUser($insertData)) {
            return __engineReaction(1, null, __tr('Notify User added successfully.'));
        }

        return __engineReaction(2, null, __tr('Notify User not added'));
      
    }

    /**
     * Process add to wishList.
     *
     * @param int $productId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddtoWishlist($productId)
    {  
        $product = $this->productRepository->fetchByID($productId);

        if (!isLoggedIn()) {
            return __engineReaction(2, [
                'showLoggedInDialog' => true,
            ], __tr('Please Logged In to complete request.'));
        }

        if (__isEmpty($product) or $product->status !== 1) {
            return __engineReaction(2, null, __tr('Product does not exist'));
        }

        $wishListAddData = [ 
            'products_id' => $productId,
            'users_id' => getUserID()
        ];

        $wishlistData = $this->productRepository->fetchWishListByUserId($productId);

        if (!__isEmpty($wishlistData)) {
            return __engineReaction(2, null, __tr('Product already added to wishlist.'));
        }
      
        if ($this->productRepository->storeToWishList($wishListAddData)) {
            return __engineReaction(1, null, __tr('Product has been added to wishlist.'));
        }

        return __engineReaction(2, null, __tr('Product not added to wishlist.'));
    }

    /**
     * Process remove from wishList.
     *
     * @param int $productId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processRemoveFromWishlist($productId)
    {
        $product = $this->productRepository->fetchByID($productId);

        if (!isLoggedIn()) {
            return __engineReaction(2, [
                'showLoggedInDialog' => true,
            ], __tr('Please Logged In to complete request.'));
        }
        
        if (__isEmpty($product)) {
            return __engineReaction(2, null, __tr('Product does not exist'));
        }

        $wishlistData = $this->productRepository->fetchWishListByUserId($productId);

        // check if wishlist is empty
        if (__isEmpty($wishlistData)) {
            return __engineReaction(2, null, __tr('Wishlist does not exist.'));
        }

        if ($this->productRepository->deleteWishList($wishlistData)) {
            return __engineReaction(1, null, __tr('Product removed from wishlist.'));
        }

        return __engineReaction(2, null, __tr('Product not removed from wishlist.'));
    }

    /**
     * My Wish list details.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function myWishListBreadcrumb()
    {
        return Breadcrumb::generate('my-wishlist');
    }

     /**
     * My Wish list details.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function myRatingListBreadcrumb()
    {
        return Breadcrumb::generate('my-ratings');
    }


    /**
     * My Wish list details.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareMyWishListDetails()
    {
        $wishListCollection = $this->productRepository->fetchMyWishList();
    
        $wishListData = [];

        $activeDiscount = $this->couponRepository->fetchProductDiscounts();
        
        // Check if wishlist exist
        if (!__isEmpty($wishListCollection)) {
            foreach ($wishListCollection as $key => $wishlist) {

                $wishListData[] = [
                    'productName'   => __transliterate('product', $wishlist->products_id, 'name', $wishlist->product->name), 
                    'productId'     => $wishlist->products_id, 
                    'productDescription' => strip_tags($wishlist->product->description), 
                    'oldPrice'      => (!__isEmpty($wishlist->product->old_price))
                                        ? priceFormat($wishlist->product->old_price, true, true, ['isMultiCurrency' => true])
                                        : null,
                    'newPrice'      => priceFormat($wishlist->product->price, true, true, ['isMultiCurrency' => true]),
                    'productImage'  => getProductImageURL($wishlist->products_id, $wishlist->product->thumbnail),
                    'detailURL'     => route('product.details', ['productID' => $wishlist->products_id, 'productName' => slugIt($wishlist->product->name)]),
                    'itemRating'    => $this->productRatingDetails($wishlist->products_id),
                    'productDiscount' => calculateSpecificProductDiscount($wishlist->products_id, $wishlist->product->price, $activeDiscount)
                ];
            }
        }

        return __engineReaction(1, [
            'wishListData'  => $wishListData,
            'paginationLinks'    => sprintf($wishListCollection->links("pagination::bootstrap-4")),
            'enableRating'  => getStoreSettings('enable_rating'),
            'totalProducts' => $wishListCollection->count()
        ]);
    }

	/**
     * My Wish list details.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareMyRatings()
    {	
		$myRatings = $this->productRepository->fetchMyRatings();
		
		$ratingsData = [];
        
        $activeDiscount = $this->couponRepository->fetchProductDiscounts();

        // Check if wishlist exist
        if (!__isEmpty($myRatings)) {

            foreach ($myRatings as $key => $rating) {

                $ratingsData[] = [
                    'productId'   => $rating->product->id,
                    'productName'   => __transliterate('product', $rating->product->id, 'name', $rating->product->name),
                    'productDescription'  =>  __transliterate('product', $rating->product->id, 'description', strip_tags($rating->product->description)),
                    'oldPrice'      => (!__isEmpty($rating->product->old_price))
                                        ? priceFormat($rating->product->old_price, true, true, ['isMultiCurrency' => true])
                                        : null,
                    'newPrice'      => priceFormat($rating->product->price, true, true, ['isMultiCurrency' => true]),
                    'productImage'  => getProductImageURL($rating->products_id, $rating->product->thumbnail),
                    'detailURL'     => route('product.details', ['productID' => $rating->products_id, 'productName' => slugIt($rating->product->name)]),
					'rating' => $rating->rating,
					'review' => $rating->review ?:false,
                    'itemRating'    => $this->productRatingDetails($rating->products_id),
                    'productDiscount' => calculateSpecificProductDiscount($rating->products_id, $rating->product->price, $activeDiscount),
                    'createdOn' => formatDateTime($rating->created_at)
                ];
            }
        }

		return __engineReaction(1, [
            'ratingsData'  => $ratingsData,
            'paginationLinks'    => sprintf($myRatings->links("pagination::bootstrap-4")),
			'breadCrumb' => Breadcrumb::generate('my-ratings')
        ]);
    }


    /**
     * Prepare search term suggest list data.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSearchRequestData($searchQuery)
    {	
        // get search suggest list
        $productResult = $this->productRepository
                                  ->fetchProductSearchSuggestList($searchQuery);

        $categoryResult = $this->manageCategoryRepository
                                  ->fetchCategorySearchSuggestList($searchQuery);

        $brandResult = $this->brandRepository
                                  ->fetchBrandySearchSuggestList($searchQuery);

        $productData = [];
        if (!__isEmpty($productResult)) {
            foreach ($productResult as $key => $product) {
                $productData[] = [
                    'id'    => $product->id,
                    'name'  => $product->name.' (product)',
                    'type'  => 1
                ];
            }

        }

        $categoryData = [];
        if (!__isEmpty($categoryResult)) {
            foreach ($categoryResult as $key => $category) {
           
                $categoryData[] = [
                    'id'    => $category->id,
                    'name'  => $category->name.' (category)',
                    'type'  => 2
                ];
            }

        }
      
        $brandData = [];
        if (!__isEmpty($brandResult)) {
            foreach ($brandResult as $key => $brand) {
                $brandData[] = [
                    'id'    => $brand->id,
                    'name'  => $brand->name.' (brand)',
                    'type'  => 3
                ];
            }
        }

        $searchData = array_merge($brandData, $categoryData, $productData);

        return __engineReaction(1, ['searchResult' => $searchData]);
    }

    /**
     * Prepare Landing page product tab data
     *
     * @param array $productTabData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareLandingPageProductTabData($productTabData)
    {
        $productTab1Ids = array_get($productTabData, 'tab_1_products', []);
        $productTab2Ids = array_get($productTabData, 'tab_2_products', []);
        $productTab3Ids = array_get($productTabData, 'tab_3_products', []);
        $productTab4Ids = array_get($productTabData, 'tab_4_products', []);
        $productIds = array_merge($productTab1Ids, $productTab2Ids, $productTab3Ids, $productTab4Ids);

        $tab1Data = $tab2Data = $tab3Data = $tab4Data = [];

        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        $productCollection = $this->productRepository->fetchProductById($productIds, $inactiveBrandIds, $activeCatIds);
       

        // Check if products exist
        if (!__isEmpty($productCollection)) {
            foreach ($productCollection as $key => $product) {
                if (in_array($product->id, $productTab1Ids)) {
                    $tab1Data[] = $this->prepareProductData($product);
                }
                if (in_array($product->id, $productTab2Ids)) {
                    $tab2Data[] = $this->prepareProductData($product);
                }
                if (in_array($product->id, $productTab3Ids)) {
                    $tab3Data[] = $this->prepareProductData($product);
                }
                if (in_array($product->id, $productTab4Ids)) {
                    $tab4Data[] = $this->prepareProductData($product);
                }
            }
        }

        return [
            'tab1Data' => $tab1Data,
            'tab2Data' => $tab2Data,
            'tab3Data' => $tab3Data,
            'tab4Data' => $tab4Data
        ];
    }

    /**
     * Prepare Product Data
     *
     * @param object $product
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function prepareProductData($product)
    {

        // Show productRating
        $productRating = [];
        if (!empty($product->productRating)) {
            $itemRating = collect($product->productRating);
         
            $rating =  ROUND($itemRating->avg('rating'), 1);
            $decimal    = '';
            $rated      = floor($rating);
            $unrated    = floor(5 - $rated);
            $totalVotes =  COUNT($itemRating);

            if (fmod($rating, 1) != 0) { 
                $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                $unrated = $unrated - 1;
            }
           
            $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                $decimal.
                str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

            $productRating = [
                'itemRating'        => $rating,
                'formatItemRating'  => $formatRating,
                'totalVote'         => $totalVotes
            ];
        }
        
        return [
            'id' => $product->id,
            'productImage' => getProductImageURL($product->id, $product->thumbnail),
            'price' => priceFormat($product->price, false, true, ['isMultiCurrency' => true]),
            'name' => $product->name,
            'productSpecExists' => isset($product->specification_presets__id) ? $product->specification_presets__id : null,
            'slugName' => slugIt($product->name),
            'productRating' => $productRating,
            'old_price' => $product->old_price,
            'status' => $product->status,
            'thumbnail' => $product->thumbnail,
            'status' => $product->status,
            'oldPriceExist' => isset($product->old_price) ? $product->old_price : null,
            'formatProductOldprice' => priceFormat($product->old_price, false, true, ['isMultiCurrency' => true]),
            'created_at' => formatDateTime($product->created_at),
            'detailURL' => route('product.details', [$product->id, slugIt($product->name)])
        ];
    }
}