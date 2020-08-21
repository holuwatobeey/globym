<?php
/*
* CouponEngine.php - Main component file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon;

use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\Coupon\Blueprints\CouponEngineBlueprint;
use App\Yantrana\Components\ShoppingCart\ShoppingCartEngine;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use Carbon\Carbon;

class CouponEngine implements CouponEngineBlueprint
{
    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * Constructor.
     *
     * @param CouponRepository $couponRepository - Coupon Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        CouponRepository $couponRepository,
        ShoppingCartEngine $shoppingCartEngine,
        ManageProductRepository $manageProductRepository,
        OrderRepository $orderRepository,
        UserRepository $userRepository
    )
    {
        $this->couponRepository = $couponRepository;
        $this->shoppingCartEngine = $shoppingCartEngine;
        $this->manageProductRepository = $manageProductRepository;
        $this->orderRepository          = $orderRepository;
        $this->userRepository           = $userRepository;
    }

    /**
     * get prepare coupons list.
     *
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList($status)
    {
        return $this->couponRepository
                    ->fetchForList($status);
    }

    /**
     * add new coupon.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addProcess($inputData)
    {   
        $coupon = $this->couponRepository->store($inputData);
        $couponProductData = [];
         
        if ((isset($inputData['product_discount_type']))
            and ($inputData['product_discount_type'] == 2)) { // Selected products
            if (!__isEmpty($inputData['selected_product'])) {
                foreach ($inputData['selected_product'] as $key => $product) {
                    $couponProductData[] = [
                        'coupons__id'   => $coupon,
                        'products_id'   => $product,
                        'created_at'    => currentDateTime()
                    ];
                }

                $this->couponRepository->storeCouponProducts($couponProductData);
            }
        }

        // Check if coupon addded
        if ($coupon) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * get edit data.
     *
     * @param array $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($couponID)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchByID($couponID);
        $couponProducts = [];

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        if (!__isEmpty($coupon->couponProduct)) {
            foreach ($coupon->couponProduct as $key => $productCoupon) {
                $couponProducts[] = $productCoupon->products_id;
            }
        }

        // Prepare coupon data
        $couponData = [
            '_id' => $coupon->_id,
            'title' => $coupon->title,
            'description' => $coupon->description,
            'code' => $coupon->code,
            'start' => Carbon::parse($coupon->start)->toDateTimeString(),
            'end' => Carbon::parse($coupon->end)->toDateTimeString(),
            'discount' => $coupon->discount,
            'discount_type' => $coupon->discount_type,
            'max_discount' => $coupon->max_discount,
            'minimum_order_amount' => $coupon->minimum_order_amount,
            'active' => ($coupon->status == 1) ? true : false,
            'product_discount_type' => $coupon->products_scope,
            'selected_product' => $couponProducts,
            'uses_per_user' => $coupon->uses_per_user,
            'coupon_code_required' => (!__isEmpty($coupon->code)) ? true : false,
            'user_per_usage' => (!__isEmpty($coupon->uses_per_user)) ? true : false
        ];

        // Get discount type config items and currency code & symbol
        $configItems = [
            'discountType' => $discountType = config('__tech.coupon_discount_type'),
            'currencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency(),
            'productDiscountTypes' => configItem('product_discount_type'),
            'products' => $this->fetchProducts()
        ];

        return __engineReaction(1, [
            'couponData' => $couponData,
            'configItems' => $configItems,
        ]);
    }

    /**
     * get coupon detail.
     *
     * @param array $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchDetail($couponID)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchDetailByID($couponID);
        $products = [];

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // Check if products available
        if (!__isEmpty($coupon->couponWithProduct)) {
            foreach ($coupon->couponWithProduct as $discountProduct) {
                $products[] = $discountProduct->product->name;
            }
        }
        
        // Prepare couponData array
        $couponData = [
            'title' => $coupon->title,
            'description' => $coupon->description,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'minimum_order_amount' => $coupon->minimum_order_amount,
            'max_discount' => $coupon->max_discount,
            'discount_type' => $coupon->discount_type,
            'currencySymbol' => getCurrencySymbol(),
            'productScope' => $coupon->products_scope,
            'formattedProductScope' => configItem('product_discount_type', $coupon->products_scope),
            'products' => $products,
        ];

        return __engineReaction(1, $couponData);
    }

    /**
     * update coupon.
     *
     * @param int   $couponID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($couponID, $inputData)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchByID($couponID);
        $isUpdated = false;
        $couponProducts = [];
        
        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // Check status active or not
        $status = 1;
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        if (!__isEmpty($coupon->couponProduct)) {
            foreach ($coupon->couponProduct as $key => $productCoupon) {
                $couponProducts[] = $productCoupon->_id;
            }
        }

        $couponProductData = [];

        if ($inputData['product_discount_type'] == 2) {
            if (!__isEmpty($inputData['selected_product'])) {

                if (!__isEmpty($couponProducts)) {
                    $this->couponRepository->deleteCouponProducts($couponProducts);
                }
                
                foreach ($inputData['selected_product'] as $key => $product) {
                    $couponProductData[] = [
                        'coupons__id'   => $coupon->_id,
                        'products_id'   => $product,
                        'created_at'    => currentDateTime()
                    ];
                }
            }

            if (!__isEmpty($couponProductData)) {
                $this->couponRepository->storeCouponProducts($couponProductData);
            }                

            $isUpdated = true;
        }

        $usagePerUser = null;
        if (isset($inputData['uses_per_user'])) {
            if (isset($inputData['code']) 
                and (!__isEmpty($inputData['code']))) {
                $usagePerUser = $inputData['uses_per_user'];
            }
        }

        $startDate = convertDateTimeZone($inputData['start'], $inputData['getClientTimeZone']);
        
        $endTimeDate = convertDateTimeZone($inputData['end'], $inputData['getClientTimeZone']);

        // Prepare updateData array for update coupon detail
        $updateData = [
            'title' => $inputData['title'],
            'description' => $inputData['description'],
            'code' => (!__isEmpty($inputData['code'])) ? $inputData['code'] : null,
            'start' => $startDate,
            'end' => (!__isEmpty($endTimeDate)) ? $endTimeDate : null,
            'discount' => $inputData['discount'],
            'discount_type' => $inputData['discount_type'],
            'max_discount' => $inputData['max_discount'],
            'minimum_order_amount' => (isset($inputData['minimum_order_amount']))
                                        ? $inputData['minimum_order_amount']
                                        : $coupon->minimum_order_amount,
            'status' => $status,
            'uses_per_user' => $usagePerUser,
            'products_scope' => (isset($inputData['product_discount_type'])) 
                                ? $inputData['product_discount_type'] : null,
        ];

        $reponseData = $this->couponRepository->update($coupon, $updateData);

        if ($reponseData or $isUpdated) {
            return __engineReaction(1, $reponseData);
        }

        return __engineReaction(14);
    }

    /**
     * delete coupon.
     *
     * @param int $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($couponID)
    {
        // find record
        $coupon = $this->couponRepository->fetchByID($couponID);
        $couponProductIds = [];

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // delete coupon request
        $response = $this->couponRepository->delete($coupon);

        if ($response) {

            if (!__isEmpty($coupon->products_scope)
                and $coupon->products_scope == 2) {
                if (!__isEmpty($coupon->couponProduct)) {
                    foreach ($coupon->couponProduct as $key => $cProduct) {
                        $couponProductIds[] = $cProduct->_id;
                    }

                    $this->couponRepository->deleteCouponProducts($couponProductIds);
                }
            }

            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * apply coupon process.
     *
     * @param int $code
     * @param int $cartTotalPrice
     *---------------------------------------------------------------- */
    public function applyCouponProcess($code = null, $userEmail = null)
    {
        // this function use for get cart details
        $orderDetails = $this->shoppingCartEngine->getCartDetails();

        $cartTotalPrice = null;
        $productCouponApplied = false;

        if (!__isEmpty($orderDetails)) {
            $cartTotalPrice = $orderDetails['data']['total']['totalBasePrice'];
        }

        $couponData = [];

        $coupon = $this->couponRepository->fetchCouponCode($code);

        if (__isEmpty($coupon)) {
            $data['couponData'] = $code;
            $data['totalPrice'] = $cartTotalPrice;

            return __engineReaction(2, $data);
        }

        $loggedInUserId = getUserID();

        if (!isLoggedIn() and !__isEmpty($userEmail)) {
            $guestUser = $this->userRepository->fetchActiveUserByEmail($userEmail);

            if (!__isEmpty($guestUser)) {
                $loggedInUserId = $guestUser->id;
            }            
        }

        $couponUsage = $this->orderRepository->fetchCouponUsage($loggedInUserId, $coupon->_id);
        
        // Check if usage coupon code exceed limit
        if (!__isEmpty($coupon->uses_per_user)) {

            if ($couponUsage >= $coupon->uses_per_user) {
                $data['couponData'] = $code;
                $data['totalPrice'] = $cartTotalPrice;

                return __engineReaction(2, $data, __tr('You have exceed coupon usage limit'));
            }
        }

        $productsIds = $totalDiscount = $discountProductDetails = [];

        // Check if product scope is exist
        if ($coupon->products_scope == 2) { // Specific Product
            $productsIds = $coupon->couponProduct->pluck('products_id')->all();

            foreach ($orderDetails['data']['cartItems'] as $key => $catItem) {
                $productDiscount = 0;
                if (in_array($catItem['id'], $productsIds)) {

                    $productCouponApplied = true;

                    if ($coupon->discount_type == 1) {

                        $productDiscount = (handleCurrencyAmount($coupon->max_discount) / 100) * $catItem['new_raw_subTotal'];

                        if (handleCurrencyAmount($coupon->discount) < $productDiscount) {
                            $productDiscount = handleCurrencyAmount($coupon->discount);
                        } else {
                            $productDiscount = handleCurrencyAmount($productDiscount);
                        }

                    } elseif ($coupon->discount_type == 2) {

                        $productDiscount = (handleCurrencyAmount($coupon->discount) / 100) * $catItem['new_raw_subTotal'];

                        if (handleCurrencyAmount($coupon->max_discount) < $productDiscount) {
                            $productDiscount = handleCurrencyAmount($coupon->max_discount);
                        } else {
                            $productDiscount = handleCurrencyAmount($productDiscount);
                        }
                    }

                    $discountProductDetails[] = [
                        'name'          => $catItem['name'],
                        'new_subTotal'  => $catItem['new_subTotal'],
                        'p_discount'    => priceFormat($productDiscount, false, true),
                    ];

                    $totalDiscount[] = handleCurrencyAmount($productDiscount);
                }
            }

            if (!$productCouponApplied) {
                $data['couponData'] = $code;
                $data['totalPrice'] = $cartTotalPrice;

                return __engineReaction(2, $data);
            }
            
            $total = array_sum($totalDiscount);
            $discount = handleCurrencyAmount($total);

        } else {

            if (handleCurrencyAmount($coupon->minimum_order_amount) > handleCurrencyAmount($cartTotalPrice)) {
                $data['couponData'] = priceFormat(handleCurrencyAmount($coupon->minimum_order_amount), false, true);
                $data['couponCode'] = $code;
                $data['totalPrice'] = $cartTotalPrice;

                return __engineReaction(9, $data);
            }

            $discount = 0;

            if ($coupon->discount_type == 2) {
                $discount = (handleCurrencyAmount($coupon->discount) / 100) * $cartTotalPrice;

                if (handleCurrencyAmount($coupon->max_discount) < $discount) {
                    $discount = handleCurrencyAmount($coupon->max_discount);
                } else {
                    $discount = handleCurrencyAmount($discount);
                }
            }

            if ($coupon->discount_type == 1) {
                $discount = (handleCurrencyAmount($coupon->max_discount) / 100) * $cartTotalPrice;

                if (handleCurrencyAmount($coupon->discount) < $discount) {
                    $discount = handleCurrencyAmount($coupon->discount);
                } else {
                    $discount = handleCurrencyAmount($discount);
                }
            }
        }
        
        $couponData = [
            'couponID' => $coupon->_id,
            'couponCode' => $coupon->code,
            'discountType' => $coupon->discount_type,
            'discount' => $discount,
            'formattedDiscount' => priceFormat($discount, false, true),
            'title' => __transliterate('coupons', $coupon->_id, 'title', $coupon->title),
            'description' => __transliterate('coupons', $coupon->_id, 'description', $coupon->description),
            'cartPrice' => $cartTotalPrice,
            'productScope' => $coupon->products_scope,
            'discountProductDetails'=> $discountProductDetails
        ];
      
        $couponDeductionAmount = $cartTotalPrice - $discount; // subtract discount amount from order amount
        
        if ($couponDeductionAmount < 0) { // If amouont in minus
            $data['couponData'] = $code;
            $data['totalPrice'] = $cartTotalPrice;

            return __engineReaction(2, $data);
        }

        $data['totalPrice'] = $couponDeductionAmount;
        $data['couponData'] = $couponData;

        return __engineReaction(1, $data);
    }

    /**
     * fetch coupon by code.
     *
     * @param string $code
     *
     * @return object
     *---------------------------------------------------------------- */
    public function getCouponByCode($code)
    {
        return $this->couponRepository->fetchCouponCode($code);
    }

    /**
     * get coupon information.
     *
     * @param int $couponId
     * @param int $currency
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCouponInformation($couponId, $currency)
    {
        $coupon = $this->couponRepository->fetchByID($couponId);

        if (__isEmpty($coupon)) {
            return [
                'info' => '',
            ];
        }

        return [
            'info' => [
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'formatedCouponDiscount' => priceFormat(
                                                $coupon->discount,
                                                $currency
                                            ),
                'title' => __transliterate('coupons', $coupon->_id, 'title', $coupon->title),
                'description' => __transliterate('coupons', $coupon->_id, 'description', $coupon->description),
            ],
        ];
    }

    /**
     * Get and Prepare Discount type.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDiscountType()
    {
        $configItems = [
            'discountType' => $discountType = config('__tech.coupon_discount_type'),
            'currencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency(),
            'productDiscountTypes' => configItem('product_discount_type'),
            'products' => $this->fetchProducts()
        ];

        return __engineReaction(1, $configItems);
    }

    /**
     * Fetch Products.
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function fetchProducts()
    {
        $productCollection = $this->manageProductRepository->fetchAll();
        $products = [];

        // Check if products exist
        if (!__isEmpty($productCollection)) {
            foreach ($productCollection as $key => $product) {
                $products[] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                ];
            }            
        }

        return $products;
    }
}
