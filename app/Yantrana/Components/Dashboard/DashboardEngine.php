<?php
/*
* DashboardEngine.php - Main component file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard;

use App\Yantrana\Components\Dashboard\Repositories\DashboardRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;
use App\Yantrana\Components\Dashboard\Blueprints\DashboardEngineBlueprint;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use Carbon\Carbon;

class DashboardEngine implements DashboardEngineBlueprint
{
    /**
     * @var DashboardRepository - Dashboard Repository
     */
    protected $dashboardRepository;

    /**
     * @var ManageOrderRepository - ManageOrder Repository
     */
    protected $manageOrderRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var - Category Repository
     */
    protected $manageCategoryRepository;

    /**
     * Constructor.
     *
     * @param DashboardRepository $dashboardRepository - Dashboard Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        DashboardRepository $dashboardRepository,
        ManageOrderRepository $manageOrderRepository,
        ManageProductRepository $manageProductRepository,
        UserRepository $userRepository,
        CouponRepository $couponRepository,
        BrandRepository $brandRepository,
        ShippingRepository $shippingRepository,
        ProductRepository $productRepository,
        ManageCategoryRepository $manageCategoryRepository
    ) {
        $this->dashboardRepository = $dashboardRepository;
        $this->manageOrderRepository = $manageOrderRepository;
        $this->manageProductRepository = $manageProductRepository;
        $this->userRepository = $userRepository;
        $this->couponRepository = $couponRepository;
        $this->brandRepository = $brandRepository;
        $this->shippingRepository = $shippingRepository;
        $this->productRepository = $productRepository;
        $this->manageCategoryRepository = $manageCategoryRepository;
    }

    /**
     * get category count.
     *
     * @param object $category
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getTotalCategories()
    {
        $categoryCollection = $this->productRepository->fetchCategories();

        $totalCategoryCount = 0;
        if (!__isEmpty($categoryCollection)) {
            $totalCategoryCount = $categoryCollection->where('status', 1)->count();
        }
       
        return formatLocaleNumber($totalCategoryCount);
    }

    /**
     * get products count.
     *
     * @param object $products
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getProducts()
    {
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $allProducts = $this->manageProductRepository->fetchAllProduct();
        $outOfStockProduct = $allProducts->where('out_of_stock', 1)->count();
        $comingSoonProduct = $allProducts->whereIn('out_of_stock', [2, 3])->count();
       
        // find all active categories
        $allActiveCategories = findActiveChildren($categoryCollection);

        $productCollection = $this->manageProductRepository->fetchCategoriesProducts($allActiveCategories, $inactiveBrandIds);

        $totalProductsCount = $allProducts->count();
        $active = count($productCollection);
        $deactive = $totalProductsCount - $active;

        //create order chart data set array for print chart js
        $productChartData[] = [
            'data' => [$active, $deactive],
            'backgroundColor' => array_values(config('__tech.product_dashboard.db_product_chart_bg_color')),
        ];

        return [
            'active' => formatLocaleNumber($active),
            'deactive' => formatLocaleNumber($deactive),
            'outOfStock' => formatLocaleNumber($outOfStockProduct),
            'totalProducts' => formatLocaleNumber($totalProductsCount),
            'baseTotalProductCount' => $totalProductsCount,
            'productChartData' => $productChartData,
            'productChartLabels' => ['Active', 'Inactive']
        ];
    }

	/**
     * getAwatingPayamansts
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getAwatingPayamants($startDate, $endDate, $durationType)
    {
        $paymants = $this->manageOrderRepository->fetchAwaitingPaymentsOrders($startDate, $endDate, $durationType);
		
		$paymentData = [];
        
		if(!__isEmpty($paymants)) {
			
			foreach ($paymants as $paymant) {

				$paymentData[] = [
					'formatted_amount' => priceFormat($paymant->amount, $paymant->currency_code, configItem('currencies.details.'.$paymant->currency_code)['symbol']),
					'currency_code'    => $paymant->currency_code,
                    'order_count'      => formatLocaleNumber($paymant->order_count)
				];

			}
		}

		return $paymentData;

    }

    /**
     * get dashboard list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDashboardSupportData($startDate, $endDate, $durationType)
    {
        $recentNewOrder = $this->manageOrderRepository->fetchLatestNewOrder($startDate, $endDate, $durationType);
        $recentCompletedOrder = $this->manageOrderRepository->fetchLatestCompletedOrder($startDate, $endDate, $durationType);
        $orderCollection = $this->manageOrderRepository->fetchAllOrder($startDate, $endDate, $durationType);

        return __engineReaction(1, [
            'orders' => [
	            'receivedInMonthOrders' =>  formatLocaleNumber($this->manageOrderRepository->fetchReceivedInMonth()),
				'pendingOrders' => formatLocaleNumber($this->manageOrderRepository->fetchPendingOrders()),
				'awaitingOrderPayments' => $this->getAwatingPayamants($startDate, $endDate, $durationType),
                'recentNewOrders' => $this->prepareRecentOrder($recentNewOrder),
                'recentCompletedOrders' => $this->prepareRecentOrder($recentCompletedOrder),
                'completedOrders' => $this->getCompletedOrders(),
                'orderChartData' => $this->getOrderChartData($orderCollection),
	        ],
            'brands'     => $this->getBrands(),
            'products' => $this->getProducts(),
            'totalCategories' => $this->getTotalCategories(),
            'latestProductsRating' => $this->getlatestProductsRating(),
            'latestSaleProducts' => $this->getlatestSaleProducts($startDate, $endDate, $durationType),
            'currentCoupans'  => $this->getCurrentCoupans(),
            'duration' => config('__tech.dashboard_duration')
        ]);
    }

    /**
     * get products rating & review.
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getlatestProductsRating()
    {
        $productRating = $this->manageProductRepository->fetchLatestProductRating();

        $productRatingData = [];
        $formatRating = [];
        if (!__isEmpty($productRating)) {
            foreach ($productRating as $key => $rating) {
                $productRating     = $rating['productRating'];
                $decimal    = '';
                $rated      = floor($productRating);
                $unrated    = floor(5 - $rated);
              
                if (fmod($productRating, 1) != 0) { 
                    $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                    $unrated = $unrated - 1;
                }
               
                $formatRating = (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                    $decimal.
                    str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));

                $productRatingData[] = [
                    'id'            => $rating['_id'],
                    'productId'     => $rating['products_id'],
                    'rating'        => $productRating,
                    'review'        => $rating['review'],
                    'formatReview'  => substr($rating['review'], 0, 50)."...",
                    'formatRating'  => $formatRating,
                    'created_at'    => Carbon::parse($rating['created_at'])->diffForHumans(),
                    'productName'   => $rating['productName'],
                ];
            }
        }
       
        return $productRatingData;
    }

    /**
     * get products count.
     *
     * @param object $products
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getlatestSaleProducts($startDate, $endDate, $durationType)
    {
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();
        $categoryCollection = $this->manageCategoryRepository->fetchAllActiveWithChildren();

        // find all active categories
        $activeCatIds = findActiveChildren($categoryCollection);

        $orderProduct = $this->productRepository->fetchLatestSaleProducts($startDate, $endDate, $durationType, $inactiveBrandIds, $activeCatIds);

        $orderProductData = $orderProductCollection = [];
        if (!__isEmpty($orderProduct)) {
            //$orderProductByProduct = $orderProduct->groupBy('products_id')->toArray();
            $orderProductByProduct = $orderProduct->toArray();
            $productQuantity = [];
          
            foreach ($orderProductByProduct as $orderKey => $products) {
                $productQuantity = [];
                if (!__isEmpty($products['order_products'])) {
                    foreach ($products['order_products'] as $key => $order) {
                        $productQuantity[] = $order['quantity'];
                    }
         
                    $productOrderData = [
                        'qty' => formatLocaleNumber(array_sum($productQuantity)),
                        'orderStatus' => $order['status'],
                        'productsId' => $products['id'],
                        'productName' => $products['name']
                    ];

                    $orderProductCollection[$orderKey] = $productOrderData;
                }
            }

            $orderPrductCollection = collect($orderProductCollection);

            $orderProductData = $orderPrductCollection->sortByDesc('qty')->take(configItem('latest_sale_product_count'))->all();

            return array_values($orderProductData);
        }
       
        return $orderProductData;
    }

    /**
     * get products count.
     *
     * @param object $products
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getCurrentCoupans()
    {
        $coupanCollection = $this->couponRepository->fetchActiveAllCoupan();
       
        $currentActiveCoupan = [];
        if (!__isEmpty($coupanCollection)) {
            foreach ($coupanCollection as $key => $currentCoupan) {
                $currentActiveCoupan[] = [
                    'title' => $currentCoupan->title
                ];
            }
        }
      
        return [
            'currentActiveCoupan' => $currentActiveCoupan
        ];
    }

    /**
     * Get Recent Orders
     *
     * @return number
     *---------------------------------------------------------------- */
    public function prepareRecentOrder($recentOrders)
    {
        $orderData = [];

        // Check if recent orders exist
        if (!__isEmpty($recentOrders)) {
            foreach ($recentOrders as $key => $order) {
                $orderData[] = [
                    'orderId'               => $order->order_uid,
                    'humanFormatCreatedOn'  => humanFormatDateTime($order->updated_at),
                    'formattedCreatedOn'    => formatDateTime($order->updated_at),
                    'status'                => $order->status,
                    'formattedStatus'       => configItem('orders.status_codes', $order->status),
                    'ownerName'             => $order->name
                ];
            }            
        }
        
        return $orderData;       
    }

    /**
     * Get Completed Orders.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getCompletedOrders()
    {
        return [
            'inMonth' => formatLocaleNumber($this->manageOrderRepository->fetchCompletedInMonth()),
            'inWeek'  => formatLocaleNumber($this->manageOrderRepository->fetchCompletedInWeek()),
            'today'   => formatLocaleNumber($this->manageOrderRepository->fetchCompletedToday())
        ];
    }

     /**
     * Get Completed Orders.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getOrderChartData($orderData)
    {
        $orderCollection = $orderData;
        $newOrder = $orderCollection->where('status', '=', 1)->count();
        $processionOrder = $orderCollection->where('status', '=', 2)->count();
        $cancelledOrder = $orderCollection->where('status', '=', 3)->count();
        $onHoldOrder = $orderCollection->where('status', '=', 4)->count();
        $inTransitOrder = $orderCollection->where('status', '=', 5)->count();
        $completeOrder = $orderCollection->where('status', '=', 6)->count();
        $confirmOrder = $orderCollection->where('status', '=', 7)->count();
        $deliverOrder = $orderCollection->where('status', '=', 11)->count();

        //order chart status data
        $orderDataCount = [
            'orderReportDataCount' =>  [
                $newOrder, $processionOrder, $cancelledOrder, $onHoldOrder, $inTransitOrder, $completeOrder, $confirmOrder, $deliverOrder ],
            'orderStatusLabel' => array_values(config('__tech.orders.status_codes')),
            'orderChartColor' => array_values(config('__tech.orders.order_chart_bg_color')),
            'orderCount' => count($orderCollection),
        ];

        //create order chart data set array for print chart js
        $orderChartData[] = [
            'data' => $orderDataCount['orderReportDataCount'],
            'backgroundColor' => $orderDataCount['orderChartColor']
        ];

        //collect order report data
        $orderReportData = [
            'orderData' =>   $orderChartData,
            'orderStatusLabel' => $orderDataCount['orderStatusLabel'],
            'orderCount' => $orderDataCount['orderCount'],
        ];

        return $orderReportData;
    }

    /**
     * Get Active Inactive brands.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getBrands()
    {
        $activeBrandCollection = $this->brandRepository->fetchAllActive();
        $inactiveBrandCollection = $this->brandRepository->fetchInactiveBrand();

        $active = $activeBrandCollection->count();
        $inactive = count($inactiveBrandCollection);
        $totalBrandCount = formatLocaleNumber($active + $inactive);
        //create brand chart data set array for print chart js
        $brandChartData[] = [
            'data' => [$active, $inactive],
            'backgroundColor' => array_values(config('__tech.brand.brand_chart_bg_color')),
        ];

        return [
            'active'    => formatLocaleNumber($active),
            'inactive'  => formatLocaleNumber($inactive),
            'total'     => formatLocaleNumber($active + $inactive),
            'baseTotalBrand'    => $active + $inactive,
            'brandChartData' => $brandChartData,
            'brandChartLabels' => ['Active', 'Inactive']
        ];
    }

    /**
     * get total sale calculation.
     *
     * @param array $orders
     *
     * @return number
     *---------------------------------------------------------------- */
    /*public function totalSale($orders)
    {
        $productPrice = [];

        $totalSaleProducts = 0;

        if (!__isEmpty($orders)) {
            foreach ($orders as $order) {
                foreach ($order->orderProduct as $product) {
                    $addonPrice = [];

                    if (isset($product->productOption) and !__isEmpty($product->productOption)) {
                        foreach ($product->productOption as $option) {
                            $addonPrice[] = $option->addon_price;
                        }
                    }

                    $productPriceWithAddonPrice = (!empty($addonPrice)) ? array_sum($addonPrice) + $product->price : $product->price;

                    $productPrice[] = $productPriceWithAddonPrice * $product->quantity;
                }
            }
        }

        $totalSaleProducts = array_sum($productPrice);

        return $totalSaleProducts;
    }*/
}
