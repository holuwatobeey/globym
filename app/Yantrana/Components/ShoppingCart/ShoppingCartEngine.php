<?php
/*
* ShoppingCartEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Repositories\ShoppingCartRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\ShoppingCartEngineBlueprint;
use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\Product\ProductEngine;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Category\ManageCategoryEngine;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use Breadcrumb;
use ShoppingCart;
use Session;
use Route;
use NativeSession;

class ShoppingCartEngine implements ShoppingCartEngineBlueprint
{
    /**
     * @var ShoppingCartRepository - ShoppingCart Repository
     */
    protected $shoppingCartRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var AddressRepository - Address Repository
     */
    protected $addressRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * @var addressConfig
     */
    protected $addressConfig;

    /**
     * @var CouponRepository
     */
    protected $couponRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var ManageCategoryEngine - Category Engine
     */
    protected $manageCategoryEngine;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * Constructor.
     *
     * @param ShoppingCartRepository  $shoppingCartRepository  - ShoppingCart Repository
     * @param ManageProductRepository $manageProductRepository - ManageProduct Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ShoppingCartRepository $shoppingCartRepository,
        ManageProductRepository $manageProductRepository,
        AddressRepository $addressRepository,
        MailService $mailService,
        ProductEngine $productEngine,
        CouponRepository $couponRepository,
        ShippingRepository $shippingRepository,
        TaxRepository $taxRepository,
        SupportRepository $supportRepository,
        ManageCategoryEngine $manageCategoryEngine, BrandRepository $brandRepository)
    {
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->manageProductRepository = $manageProductRepository;
        $this->addressRepository = $addressRepository;
        $this->mailService = $mailService;
        $this->productEngine = $productEngine;
        $this->couponRepository = $couponRepository;
        $this->addressConfig = config('__tech.address_type');
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->supportRepository = $supportRepository;
        $this->manageCategoryEngine = $manageCategoryEngine;
        $this->brandRepository = $brandRepository;
    }

    /**
     * check if the this product is valid.
     *
     * @param object $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    protected function checkIsValidProduct($product)
    {
        // check if product status is deactive
        if ($product->status === 2) {
            return 3;
        }

        // check if product out of Stock
        if ($product->out_of_stock === 1) {
            return 4;
        }

        // check the product contain brand
        // if contain the then check the this brand is active
        if (!__isEmpty($product->brands__id)) {
            $brand = $this->brandRepository->fetchIsActiveByID($product->brands__id);

            // so the product of brand is inactive
            if (__isEmpty($brand)) {
                return 3;
            }
        }

        // check is active category of this product
        $response = getProductCategory($this->manageCategoryEngine->getAll(), $product->id);

        if ($response === false) {
            return 9;
        }

        return 1;
    }

    /**
     * Add product in cart.
     *
     * @param array  $inputData
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processAddProductInCart($inputData, $productID)
    {
        $product = $this->manageProductRepository->fetchByID($productID);

        // Check if product exist or not and check product out of stock
        if (empty($product)) {
            return __engineReaction(2);
        }

        // check the product is valid if isActive, out of stock, also check his categories
        $isValid = $this->checkIsValidProduct($product);

       // if the product is inactive and out of stock or his categories is inactive
       // show this block
       if ($isValid !== 1) {
           return __engineReaction($isValid);
       }

       $subtotal = 0;
       if (array_has($inputData, 'options')
            and (!__isEmpty($inputData['options']))) {
           $productOption = $inputData['options'][$productID];
           
           foreach ($productOption as $key => $option) {
               $subtotal += $option['addon_price'];
           }
       }

       $subtotal = $subtotal + $product->price;

       $activeDiscount = $this->couponRepository->fetchProductDiscounts();

       $productDiscount = calculateSpecificProductDiscount($productID, $subtotal, $activeDiscount);

       if ($productDiscount['productPrice'] < 0) {
           return __engineReaction(3);
       }
           
        // verify product to check if the product is already present in cart then
        // increase qty of product & price  and
        // return created or updated row id
        $rowID = ShoppingCart::processAddOrUpdate(
                        $productID,
                        $inputData,
                        $product,
                        $activeDiscount
                    );

        // find row id & return qty of this cart item
           $cartProduct = ShoppingCart::findRow($rowID);

        return __engineReaction(1, [
                    'cartItems' => $this->updateCartString(),
                    'qtyCart' => $cartProduct['qty'],
                ]);
    }

    /**
     * fetch cart data from to cart.
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function fetchCartData()
    {
        return ShoppingCart::fetch();
    }

    /**
     * This function return cart items &
     * check this cart items in database &
     * return this in formated way &
     * ready for comparing cart items & database products
     * which is valid products in cart.
     *
     * @param array $cartItems
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getProductForCart($cartItems)
    {
        // set empty array when cart item empty
        if (empty($cartItems)) {
            return [
                'products' => [],
                'cartItems' => [],
            ];
        }

        // defined array
        $itemsIds = $getCartItems = $cartOpValueId = [];

        foreach ($cartItems as $rowId => $item) {

            // this section run when cart product contain options
            if (!empty($item['options'])) {
                foreach ($item['options'] as $options) {
                    $cartOpValueId[] = $options['valueID'];
                }
            }

            // make array which is push some keys
            $getCartItems[] = $item;

            $itemsIds[] = $item['id'];
        }

        // shopping cart items
        // fetch data of products base on cart item ids ( product id )
        // and his option base on option vlaue id of cart
        // it means check this cart items in database
        $products = $this->manageProductRepository
                        ->fetchProductByCartProductID(
                            $itemsIds,   // it's  cart item ids ( product id )
                            $cartOpValueId // cart option values ids
                            // available products in database search base on cart items ids
                        );

        return [
            'products' => $products,
            'cartItems' => $getCartItems,
        ];
    }

    /**
     * Generate cart string.
     *
     * @param array $data
     *
     * @return string
     *---------------------------------------------------------------- */
    protected function generateCartString($data)
    {
    	   
        $totalItems = formatLocaleNumber($data['totalItems']);

    	$cartStringData = [
            '__totalItems__' => '<strong>'.$totalItems.'</strong>',
            '__itemsInCart__' => $data['itemsInCart'],
            '__cartTotal__' => '<strong>'.$data['cartTotal'].'</strong>',
        ];

        $cartString = "__totalItems__ __itemsInCart__ / __cartTotal__";

        // generate empty cart string
        return strtr($cartString, $cartStringData);
    }

    /**
     * update cart quantity.
     *
     * @param array $input
     * @param array $itemID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function updateCartString()
    {
        $takeCartItems = $this->fetchCartData();

        // when the cart is empty the bock
        if (empty($takeCartItems)) {
            return $this->generateCartString([
                'totalItems' => 0,
                'itemsInCart' => __tr('item'),
                'cartTotal' => priceFormat(0, true, true, ['isMultiCurrency' => true]),
            ]);
        }
 
        // check is valid or invalid in database
        $productsDataForComapre = $this->getProductForCart($takeCartItems);

        // return valid & invlid data of cart
        $cartData = getRefinedCart(
                            $productsDataForComapre['cartItems'],
                            $productsDataForComapre['products']
                        );

        $quantity = [];
        foreach ($cartData['productData'] as $productData) {
            $quantity[] = $productData['qty'];
        }

        // make total of addon price means additional price
        $cartItemsTotalPrice = ShoppingCart::total($cartData['totalPriceItems']);

        // return total of cart data prices
        $cartItemsQty = ShoppingCart::sum($quantity);

        $itemsTotalPrice = 0;

        if (!empty($cartItemsTotalPrice)) {
            $itemsTotalPrice = priceFormat($cartItemsTotalPrice['unFormatedCartTotalAmount'], true, true, ['isMultiCurrency' => true]);
        }
        
        // set total cart items count & total cart price for cart string
        ShoppingCart::set('cartStringData', [
            'totalItems' => $cartItemsQty,
            'itemsInCart' => ($cartItemsQty == 1) ? __tr('Product') : __tr('Products'),
            'cartTotal' => $cartItemsTotalPrice['unFormatedCartTotalAmount'],
            'currency' => $cartItemsTotalPrice['currency'],
        ]);

        return $this->generateCartString([
            'totalItems' => $cartItemsQty,
            'itemsInCart' => ($cartItemsQty == 1) ? __tr('Product') : __tr('Products'),
            'cartTotal' => $itemsTotalPrice,
        ]);
    }

    /**
     * get cart string btn.
     *
     * @return string
     *---------------------------------------------------------------- */
    public function getCartBtn()
    {
         $getCartItems = ShoppingCart::fetch();

        // fetch cart data from to the session $this->fetchCartData();
        // check this cart item available in database
        $productsDataForComapre = $this->getProductForCart($getCartItems);

        $verifiedCartItems = getRefinedCart(
                                    $getCartItems,
                                    $productsDataForComapre['products']
                                );
        
        $productData = [];
        if (!__isEmpty($verifiedCartItems['productData'])) {
            foreach ($verifiedCartItems['productData'] as $key => $product) {
                $productData[] = [
                    'productName'       => $product['name'],
                    'thumbnail_url'     => $product['thumbnail_url'],
                    'productDetailURL'  => $product['productDetailURL'],
                    'qty'               => $product['qty'],
                    'formated_price'    => $product['formated_price'],
                    'formattedProductPrice'    => $product['productDiscount']['formattedProductPrice'],
                ];
            }
        }
        
        $exisitingId = [];
        if (NativeSession::has('compareProductId')) {
            $exisitingId = NativeSession::get('compareProductId');
        }

        //if session cart string not found then call function $this->updateCartString()
        if ((ShoppingCart::has('cartStringData') == false) or __isEmpty($this->fetchCartData())) {

            return __engineReaction(1, [
                'cartString'    => $this->updateCartString(),
                'cartItems'     =>  $productData,
                'cartTotal'     =>  priceFormat($verifiedCartItems['cartPriceTotal'], true, true, ['isMultiCurrency' => true]),
                'totalProductCompare' => count($exisitingId)
            ]);
        }

        // if available then get from session
        $cartStringData = ShoppingCart::fetch('cartStringData');
        
        return __engineReaction(1, [

                'cartString' => $this->generateCartString([
                        'totalItems' => $cartStringData['totalItems'],
                        'itemsInCart' => ($cartStringData['totalItems'] > 1) ? __tr('Products') : __tr('Product'),
                        'cartTotal' => priceFormat($cartStringData['cartTotal'], true, true, ['isMultiCurrency' => true]),
                    ]),
                'cartItems' => $productData,
                'cartTotal' => priceFormat($cartStringData['cartTotal'], true, true, ['isMultiCurrency' => true]),
                'totalItems' => $cartStringData['totalItems'],
                'totalProductCompare' => count($exisitingId)
            ]);
    }

    /**
     * remove perticular cart item base on rowId.
     *
     * @param string @itemID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function removeItem($rowId)
    {
        // remove cart item from to the cart base on rowid
        $getItem = ShoppingCart::remove($rowId);

     /*   if (__isEmpty($getItem)) {

           return __engineReaction(2);
        }
    */
        // return updated string
        return __engineReaction(1, ['cartItems' => $this->updateCartString()]);
    }

    /**
     * Process for all items remove from cart.
     *
     * @return string
     *---------------------------------------------------------------- */
    public function processRemoveAlItems()
    {
        // removed all items from cart
        ShoppingCart::removeAll();

        return __engineReaction(1, ['cartItems' => $this->generateCartString([
                    'totalItems'  => 0,
                    'itemsInCart' => __tr('item'),
                    'cartTotal'   => priceFormat(0, true, true, ['isMultiCurrency' => true]),
                ])]);
    }

    /**
     * get shopping cart data.
     *
     * @param int   $productID
     * @param array $inputs
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getProductCartData($productID, $inputs)
    {
        // again check product id in database
        $product = $this->manageProductRepository->fetchByID($productID);

        if (empty($product)) {
            return __engineReaction(2);
        }

        // search product in shopping cart base on pid then return row id of this item
        $searchedCartItemRowID = ShoppingCart::search($productID, $inputs);

        // find row id cart & return his qty
        $cartProduct = ShoppingCart::findRow($searchedCartItemRowID);

        $totalAddonPrice = 0;

        $addonPrice = [];

        // this block run when the product option is available
        if (isset($inputs['options'])) {
            if (!__isEmpty($inputs['options'])) {
                $options = $inputs['options'][$productID];

                if (!empty($options)) {
                    foreach ($options as $key => $option) {
                        $addonPrice[$key] = handleCurrencyAmount($option['addon_price']);
                    }
                }

                // return sum of addon price
                $totalAddonPrice = array_sum($addonPrice);
            }
        }

        $activeDiscount = $this->couponRepository->fetchProductDiscounts();
        $data['qtyCart'] = (__isEmpty($cartProduct)) ? 1 : $cartProduct['qty'];
        $data['totalPrice'] = priceFormat(($totalAddonPrice + $product->price), true, true, ['isMultiCurrency' => true]);
        $totalProductPrice = $totalAddonPrice + handleCurrencyAmount($product->price);
        $data['productDiscount'] = calculateSpecificProductDiscount($product->id, $totalProductPrice, $activeDiscount);
        $data['formatedTotalPrice'] = priceFormat($totalProductPrice, true, true, ['isMultiCurrency' => true]);
        $data['base_price'] = priceFormat(handleCurrencyAmount($product->price), false, true, ['isMultiCurrency' => true]); // base price of product
        $data['isCartExist'] = ($cartProduct['rowid']) ? true : false; // if item is present in cart then return true otherwise false

        return __engineReaction(1, $data);
    }

    /**
     * get details of cart for order summary.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCartDetails()
    {
        $getCartItems = ShoppingCart::fetch();

        // fetch cart data from to the session $this->fetchCartData();
        // check this cart item available in database
        $productsDataForComapre = $this->getProductForCart($getCartItems);

        // this function match the value of database & cart data.
        // and return in to set key isValidItem or not
        $verifiedCartItems = getRefinedCart(
                                    $getCartItems,
                                    $productsDataForComapre['products']
                                );

        $cartReady = $verifiedCartItems['cartReady'];

        // the cart content is empty then set price is zero (0) with currency
        if (empty($verifiedCartItems)) {
            $data['cartItems'] = [];
            $data['isValidItem'] = $cartReady;
            $data['itemIsInvalid'] = $verifiedCartItems['itemIsInvalid'];// return item is valid or not
            $data['total'] = ShoppingCart::total(); // take null zeros calculations
            $data['itemsCount'] = $verifiedCartItems['itemsCount'];
            $data['totalQuantity'] = $verifiedCartItems['totalQuantity'];
            return __engineReaction(1, $data);
        }

        $cartTotalBeforeDiscount = 0;

        if ($verifiedCartItems['orderDiscount']['isOrderDiscountExist']) {
            $cartTotalBeforeDiscount = ShoppingCart::total($verifiedCartItems['totalPriceItems']);
        }
        
        // Prepare Data for translation
        foreach ($verifiedCartItems['productData'] as $key => $product) {
            $verifiedCartItems['productData'][$key]['name'] = __transliterate('product', $product['id'], 'name', $product['name']);
            if (!__isEmpty($product['options'])) {
                foreach ($product['options'] as $optKey => $optValue) {
                    $verifiedCartItems['productData'][$key]['options'][$optKey]['optionName'] = __transliterate('option', $optValue['optionID'], 'name', $optValue['optionName']);
                    $verifiedCartItems['productData'][$key]['options'][$optKey]['valueName'] = __transliterate('value', $optValue['valueID'], 'name', $optValue['valueName']);
                }
            }
        }

        // when the cart items is presents
        return __engineReaction(1, [
                'currentRoute' => route('cart.view'),
                'cartItems' => $verifiedCartItems['productData'],
                'itemIsInvalid' => $verifiedCartItems['itemIsInvalid'],
                'total' => ShoppingCart::total($verifiedCartItems['totalPriceItems'], $verifiedCartItems['orderDiscount']['discount']),
                'cartTotalBeforeDiscount' => $cartTotalBeforeDiscount,
                'orderDiscount' => $verifiedCartItems['orderDiscount'],
                'isValidItem' => $cartReady,
                'isCurrencyMismatch' => $verifiedCartItems['isCurrencyMismatch'],
                'totalQuantity' => $verifiedCartItems['totalQuantity']
            ]);
    }

    /**
     * update cart quantity.
     *
     * @param array $input
     * @param array $itemID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateQty($input, $itemID)
    {
        // find row base on row id
        $getItem = ShoppingCart::findRow($itemID);

        if (!isset($getItem)) {
            return __engineReaction(2);
        }

        if (__isEmpty($input['items'])) {
            return __engineReaction(2);
        }

        $cartItemRow = $input['items'][$itemID];

        $qtyLimit = config('__tech.qty_limit');

        // the qty limit is 9999
        if ($cartItemRow['qty'] > $qtyLimit) {
            return __engineReaction(3);
        }

        $quantity = $cartItemRow['qty'];
        $rowID = $cartItemRow['rowid'];
        $newPrice = $cartItemRow['new_price'];
        $formatedPrice = handleCurrencyAmount($cartItemRow['price']);

        $addonPrice = [];

        // this block run when product option is present
        if (!empty($cartItemRow['options'])) {
            foreach ($cartItemRow['options'] as $option) {
                $addonPrice[] = handleCurrencyAmount($option['addonPrice']);
            }
        }

        // make total of addon price means additional price
        $addongPriceTotal = ShoppingCart::sum($addonPrice);

        // update cart qty
        $quanityUpdate = ShoppingCart::updateQuantity($rowID, $quantity);

        if (!$quanityUpdate) {
            return __engineReaction(2);
        }

        $activeDiscount = $this->couponRepository->fetchProductDiscounts();

        // make addition of additional price & base price
        $total = $addongPriceTotal + $formatedPrice;

        $productDiscount = calculateSpecificProductDiscount($input['items'][$itemID]['id'], $total, $activeDiscount);
                
        if ($productDiscount['isDiscountExist'] == true) {
            $total = $productDiscount['productPrice'];
        } 

        // multiply total & qty
        $subTotalPrice = $total * $quantity;

        // fetch cart data it not empty
        if (!__isEmpty($cartData = $this->fetchCartData())) {

            // fetch cart data from to the session $this->fetchCartData();
            // check this cart item available in database
            $productsDataForComapre = $this->getProductForCart($cartData);

            // this function match the value of database & cart data.
            // and return in to set key isValidItem or not
            $afterMatchCartItemsData = getRefinedCart(
                                            $cartData,
                                            $productsDataForComapre['products']
                                        );

            // return total of cart data prices
            $totalItems = ShoppingCart::total($afterMatchCartItemsData['totalPriceItems'], $afterMatchCartItemsData['orderDiscount']['discount']);
        }

        $oldCartContent = [
            'new_price'    => $newPrice,
            'subtotal'     => $subTotalPrice,
            'new_subTotal' => priceFormat($subTotalPrice, true, false),
        ];

        return __engineReaction(1, [
            'new_price' => $oldCartContent['new_price'],
            'new_subTotal' => $oldCartContent['new_subTotal'],
            'qty' => $quantity,
            'total' => $totalItems,
            'price' => handleCurrencyAmount($cartItemRow['price']),
            'cartTotalBeforeDiscount' => ShoppingCart::total($afterMatchCartItemsData['totalPriceItems']),
            'orderDiscount' => $afterMatchCartItemsData['orderDiscount'],
            'cartItems' => $this->updateCartString(), // cart string function
        ]);
    }

    /**
     * generate breadcrumb for shopping cart.
     *
     * @param string $breadcrumbType
     *
     * @return array
     *---------------------------------------------------------------- */
    public function generateBreadcrumb($breadcrumbType)
    {
        $breadCrumb = Breadcrumb::generate($breadcrumbType);

        // Check if breadcrumb not empty
        if (!__isEmpty($breadCrumb)) {
            return __engineReaction(1, [
                'breadCrumb' => $breadCrumb,
            ]);
        }

        return __engineReaction(2, [
                'breadCrumb' => null,
            ]);
    }

    /**
     * process remove invalid items.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function processRemoveInvalidItems()
    {
        // get all items form to the cart
        $fetchCartContent = $this->fetchCartData();

        if (empty($fetchCartContent)) {
            return __engineReaction(2);
        }

        // fetch cart data from to the session $this->fetchCartData();
        // check this cart item available in database
        $productsDataForComapre = $this->getProductForCart($fetchCartContent);

        // this function match the value of database & cart data.
        // and return in to set key isValidItem or not
        $cartData = getRefinedCart($fetchCartContent, $productsDataForComapre['products']);

        if (empty($cartData)) {
            return __engineReaction(1);
        }

        $rowIDs = [];

        // this block push invalid cart items in array
        foreach ($cartData['productData'] as $item) {
            if (!__isEmpty($item['ERROR'])) {
                $rowIDs[$item['rowid']] = $item['rowid'];
            }
        }

        // remove invlid cart item row id from to the cart
        $reactionCode = ShoppingCart::removeInvalidItems($rowIDs);

        if (!$reactionCode) {
            return __engineReaction(7);
        }

        // return updated string
        return __engineReaction(1, ['cartItems' => $this->updateCartString()]);
    }

    /**
     * Process Refresh Cart Product.
     *
     * @param array $inputData
     *
     * @return number
     *---------------------------------------------------------------- */
    public function processRefreshCartProduct($inputData)
    {
        $product = $this->manageProductRepository->fetchByID($inputData['id']);

        // Check if product is exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $options = [];

        $addToCartData = [
            'name'              => $product->name,
            'quantity'          => $inputData['qty'],
            'isCartExist'       => false,
        ];

        if (!__isEmpty($inputData['options'])) {
            $optionIds = $valueIds = [];
            foreach ($inputData['options'] as $option) {
                $optionIds[] = $option['optionID'];
                $valueIds[] = $option['valueID'];
            }

            $productOptionValues = $this->manageProductRepository->fetchProductOptionsValuesByIds($optionIds, $valueIds);
            
            // Check if product options exist
            if (__isEmpty($productOptionValues)) {
                return __engineReaction(18, null, __tr('Product options does not exist.'));
            }

            $productOptions = [];
            foreach ($productOptionValues as $key => $productOptionValue) {
                $optionName = $productOptionValue->productOptionLabel->name;
                $subtotal = $product->price + $productOptionValue->addon_price;
                $productOptions[$optionName] = [
                    'product_option_labels_id' => $productOptionValue->product_option_labels_id,
                    'id'                 => $productOptionValue->id,
                    'name'               => $productOptionValue->name,
                    'addon_price'        => $productOptionValue->addon_price,
                    'addon_price_format' => $productOptionValue->addon_price,
                    'subtotal'           => $subtotal,
                    'optionName'         => $optionName
                ];
            }

            $options = [
                $inputData['id'] => $productOptions
            ];
        }

        $addToCartData['options'] = $options;

        // Remove item from cart
        $removeItem = ShoppingCart::remove($inputData['rowid']);

        // Check if product successfully removed from cart
        if (!$removeItem) {
            return __engineReaction(1, null, __tr('Product not exist in cart, please reload page.'));
        }
        
        $this->processAddProductInCart($addToCartData, $inputData['id']);

        return __engineReaction(1, null, __tr('Product Updated Successfully.'));
    }
}
