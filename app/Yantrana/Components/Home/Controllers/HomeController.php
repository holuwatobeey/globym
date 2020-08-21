<?php
/*
* HomeController.php - Controller file
*
* This file is part of the Home component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Home\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Pages\PagesEngine;
use App\Yantrana\Components\Product\ProductEngine;
use App\Yantrana\Components\Brand\BrandEngine;
use Illuminate\Http\Request;
use JavaScript;
use NativeSession;

class HomeController extends BaseController
{
    /**
     * @var PagesEngine - Pages Engine
     */
    protected $pagesEngine;

    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * @var BrandEngine - Brand Engine
     */
    protected $brandEngine;

    /**
     * Constructor.
     *
     * @param PagesEngine $pagesEngine - Pages Engine
     *-----------------------------------------------------------------------*/
    public function __construct(PagesEngine $pagesEngine,
        ProductEngine $productEngine,
        BrandEngine $brandEngine)
    {
        $this->pagesEngine = $pagesEngine;
        $this->productEngine = $productEngine;
        $this->brandEngine = $brandEngine;
    }

    /**
     * Handle home view request.
     *---------------------------------------------------------------- */
    public function home(Request $request)
    {
       /*  if(file_exists(base_path('verify-install.php'))) {
           header("Location: verify-install.php");
           die();
        } */

        $homePage = getStoreSettings('home_page');

        $homePage = (int) $homePage;

        if (__isEmpty(getStoreSettings('home_page')) || $homePage === 1) {
            $details = $this->pagesEngine->getDetails(1); // home page id is 1
            $details['data']['hideSidebar'] = true;
            $details['data']['showFilterSidebar'] = false;

            return $this->loadPublicView('pages.display-details', $details['data']);

        } elseif ($homePage === 2) {
            return $this->getProductsList($request, $categoryID = null, 'products');
        } elseif ($homePage === 3) {
            return $this->getProductsList($request, $categoryID = null, 'products.featured');
        } elseif ($homePage === 4) {
            return $this->getBrandList();
        } elseif ($homePage === 5) {
          
            return $this->prepareLandingPage();
        }

        $details = $this->pagesEngine->getDetails(1); // home page id is 1
        return $this->loadPublicView('pages.display-details', $details['data']);
    }

    /**
     * Handle home view request.
     *---------------------------------------------------------------- */
    public function errorPage()
    {
        return view('errors.404');
    }

    /**
     * disply the product list.
     *
     * @param $request
     * @param $categoryID
     *
     * @return view
     *---------------------------------------------------------------- */
    protected function getProductsList($request, $categoryID, $route)
    {
        $processReaction = $this->productEngine
                                ->prepareList($categoryID, $request->all(), $route);

        //Route::currentRouteName()
        if ($processReaction['reaction_code'] === 18) {
            return $this->loadPublicView('errors.public-not-found');
        }

        $processReaction['data']['hideSidebar'] = false;
        $processReaction['data']['showFilterSidebar'] = false;

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

        $data = $products;

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'categoryData' => $data['category'],
            'filterUrl' => $data['filterUrl'],
            'productPrices' => $data['productPrices'],
            'filterPrices' => $data['filterPrices'],
            'brandID' => $data['productsBrandID'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getStoreSettings('currency_symbol'),
            'categoryID' => (!empty($categoryID)) ?
                                                       $categoryID : '',
            'pageType' => $data['pageType'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
            'itemLoadType' =>   (getStoreSettings('item_load_type') === null)
                                ? 1
                                : (int) getStoreSettings('item_load_type')
        ]);

        return $this->loadPublicView('product.list', $data);
    }


    /**
     * Render product list view.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    public function prepareLandingPage()
    {
        $landingPage = getStoreSettings('landing_page');

        $sliderData = $pageContentData = $productData = $featuredProduct = $popularProduct = $bannerContentData = $banner2ContentData = $productTabData = [];
        $latestProductCount = $landingPageDetails = [];
        if (!__isEmpty($landingPage)) {
            $landingPage = $landingPage['landingPageData'];
          
            foreach ($landingPage as $key => $landing) {
                if ($landing['identity'] == 'Slider') {
                    $sliderData = [
                        'title' => isset($landing['title']) ? $landing['title'] : null,
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => $landing['identity'],
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                } else if ($landing['identity'] == 'PageContent') {
                    $pageContentData = [
                        'pageContent' => $landing['pageContent'],
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => $landing['identity'],
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];

                } else if ($landing['identity'] == 'latestProduct') {
                    $productData = [
                        'productCount' => (int) $landing['productCount'],
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => $landing['identity'],
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                    
                } else if ($landing['identity'] == 'featuredProduct') {
                    $featuredProduct = [
                        'featuredProductCount' => (int) $landing['featuredProductCount'],
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => $landing['identity'],
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                    
                } else if ($landing['identity'] == 'popularProduct') {
                    $popularProduct = [
                        'popularProductCount' => (int) $landing['popularProductCount'],
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => $landing['identity'],
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];                    
                } else if ($landing['identity'] == 'bannerContent1') {

                    $bannerContentData = [
                        'title'                             => 'bannerSection1',
                        'banner_1_section_1_image_thumb'    => getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_1_image']),
                        'banner_1_section_1_image'          => '',
                        'banner_1_section_1_heading_1'      => __transliterate('landing_page_settings', null, 'banner_1_section_1_heading_1', $landing['banner_1_section_1_heading_1']),
                        'banner_1_section_1_heading_1_color'      => array_get($landing, 'banner_1_section_1_heading_1_color'),
                        'banner_1_section_1_heading_2'      => __transliterate('landing_page_settings', null, 'banner_1_section_1_heading_2', $landing['banner_1_section_1_heading_2']),
                        'banner_1_section_1_heading_2_color'      => array_get($landing, 'banner_1_section_1_heading_2_color'),
                        'banner_1_section_1_description'    => __transliterate('landing_page_settings', null, 'banner_1_section_1_description', $landing['banner_1_section_1_description']),
                        'banner_1_section_1_background_color' => $this->hex2rgba(array_get($landing, 'banner_1_section_1_background_color')),
                        'banner_1_section_2_image_thumb'    => getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_2_image']),
                        'banner_1_section_2_image'          => '',
                        'baner_1_section_2_heading_1'       => __transliterate('landing_page_settings', null, 'baner_1_section_2_heading_1', $landing['baner_1_section_2_heading_1']),
                        'baner_1_section_2_heading_1_color'       => array_get($landing, 'baner_1_section_2_heading_1_color'),
                        'baner_1_section_2_heading_2'       => __transliterate('landing_page_settings', null, 'baner_1_section_2_heading_2', $landing['baner_1_section_2_heading_2']),
                        'baner_1_section_2_heading_2_color'       => array_get($landing, 'baner_1_section_2_heading_2_color'),
                        'baner_1_section_2_description'     => __transliterate('landing_page_settings', null, 'baner_1_section_2_description', $landing['baner_1_section_2_description']),
                        'baner_1_section_2_background_color'     => $this->hex2rgba(array_get($landing, 'baner_1_section_2_background_color')),
                        'banner_1_section_3_image_thumb'    => getHomePageContentThumbanilUrl('banner-1', $landing['banner_1_section_3_image']),
                        'banner_1_section_3_image'          => '',
                        'baner_1_section_3_heading_1'       => __transliterate('landing_page_settings', null, 'baner_1_section_3_heading_1', $landing['baner_1_section_3_heading_1']),
                        'baner_1_section_3_heading_1_color'       => array_get($landing, 'baner_1_section_3_heading_1_color'),
                        'baner_1_section_3_heading_2'       =>  __transliterate('landing_page_settings', null, 'baner_1_section_3_heading_2', $landing['baner_1_section_3_heading_2']),
                         'baner_1_section_3_heading_2_color'       => array_get($landing, 'baner_1_section_3_heading_2_color'),
                        'baner_1_section_3_description'     =>  __transliterate('landing_page_settings', null, 'baner_1_section_3_description', $landing['baner_1_section_3_description']),
                        'baner_1_section_3_background_color'     => $this->hex2rgba(array_get($landing, 'baner_1_section_3_background_color')),
                        'orderIndex'                        => $landing['orderIndex'],
                        'identity'                          => 'bannerContent1',
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                } else if ($landing['identity'] == 'bannerContent2') {
                    $banner2ContentData = [
                        'title'   => 'bannerSection2',
                        'banner_2_section_1_image_thumb'=> getHomePageContentThumbanilUrl('banner-2', $landing['banner_2_section_1_image']),
                        'banner_2_section_1_image'=> '',
                        'banner_2_section_1_heading_1'=> __transliterate('landing_page_settings', null, 'banner_2_section_1_heading_1', $landing['banner_2_section_1_heading_1']),
                        'banner_2_section_1_heading_1_color'=> array_get($landing, 'banner_2_section_1_heading_1_color'),
                        'banner_2_section_1_heading_2'=> __transliterate('landing_page_settings', null, 'banner_2_section_1_heading_2', $landing['banner_2_section_1_heading_2']),
                        'banner_2_section_1_heading_2_color'=> array_get($landing, 'banner_2_section_1_heading_2_color'),
                        'banner_2_section_1_description'=> __transliterate('landing_page_settings', null, 'banner_2_section_1_description', $landing['banner_2_section_1_description']),
                        'banner_2_section_1_background_color'=> $this->hex2rgba(array_get($landing, 'banner_2_section_1_background_color')),
                        'banner_2_section_2_image_thumb'=> getHomePageContentThumbanilUrl('banner-2', $landing['banner_2_section_2_image']),
                        'banner_2_section_2_image'=> '',
                        'baner_2_section_2_heading_1'=> __transliterate('landing_page_settings', null, 'baner_2_section_2_heading_1', $landing['baner_2_section_2_heading_1']),
                        'baner_2_section_2_heading_1_color'=> array_get($landing, 'baner_2_section_2_heading_1_color'),
                        'baner_2_section_2_heading_2'=> __transliterate('landing_page_settings', null, 'baner_2_section_2_heading_2', $landing['baner_2_section_2_heading_2']),
                        'baner_2_section_2_heading_2_color'=> array_get($landing, 'baner_2_section_2_heading_2_color'),
                        'baner_2_section_2_description'=> __transliterate('landing_page_settings', null, 'baner_2_section_2_description', $landing['baner_2_section_2_description']),
                        'baner_2_section_2_background_color'=> $this->hex2rgba(array_get($landing, 'baner_2_section_2_background_color')),
                        'orderIndex' => $landing['orderIndex'],
                        'identity' => 'bannerContent2',
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                } else if ($landing['identity'] == 'productTabContent') {
                    $productTabData = [
                        'title' => 'productTabSection',
                        'tab_section_title' => __transliterate('landing_page_settings', null, 'tab_section_title', $landing['tab_section_title']),
                        'raw_tab_section_title' => $landing['tab_section_title'],
                        'tab_1_title' => __transliterate('landing_page_settings', null, 'tab_1_title', $landing['tab_1_title']),
                        'raw_tab_1_title' => $landing['tab_1_title'],
                        'tab_1_products' => $landing['tab_1_products'],
                        'tab_2_title' => __transliterate('landing_page_settings', null, 'tab_2_title', $landing['tab_2_title']),
                        'raw_tab_2_title' => $landing['tab_2_title'],
                        'tab_2_products' => $landing['tab_2_products'],
                        'tab_3_title' => __transliterate('landing_page_settings', null, 'tab_3_title', $landing['tab_3_title']),
                        'raw_tab_3_title' => $landing['tab_3_title'],
                        'tab_3_products' => $landing['tab_3_products'],
                        'tab_4_title' => __transliterate('landing_page_settings', null, 'tab_4_title', $landing['tab_4_title']),
                        'raw_tab_4_title' => $landing['tab_4_title'],
                        'tab_4_products' => $landing['tab_4_products'],
                        'orderIndex'  => $landing['orderIndex'],
                        'identity'  => 'productTabContent',
                        'isEnable' => array_get($landing, 'isEnable', false)
                    ];
                }
            }

            if (!__isEmpty($productTabData)) {
                $productTabsData = $this->productEngine
                                    ->prepareLandingPageProductTabData($productTabData);
                $productTabData['productTabsData'] = $productTabsData;
            }
            
            $latestProduct = [];
            // latest product data
            if (!__isEmpty($productData)) {
                $latestProductCount = $productData['productCount'];

                $product = $this->productEngine
                                    ->prepareLandingPageProduct($latestProductCount);

                $latestProduct = $product['data']['latestProducts'];                
            }
            // latest product data

            $latestFeaturedProduct = [];
            // latest fetaured product data
            if (!__isEmpty($featuredProduct)) {

                $featuredProductCount = $featuredProduct['featuredProductCount'];

                $featuredProductCollection = $this->productEngine
                                    ->prepareLandingPageFeaturedProduct($featuredProductCount);

                $latestFeaturedProduct = $featuredProductCollection['data']['featuredProducts'];
            }
            // latest fetaured product data

            $popularSaleProduct = [];
            // fetaured product data
            if (!__isEmpty($popularProduct)) {

                $popularProductCount = $popularProduct['popularProductCount'];

                $popularProductCollection = $this->productEngine
                                    ->prepareLandingPagePopularProduct($popularProductCount);

                $popularSaleProduct = $popularProductCollection['data']['popularProducts'];
            }
            // fetaured product data

            //push latest product array & latest featured product array
            $productData['latestProduct'] = $latestProduct;
            $featuredProduct['latestFeaturedProduct'] = $latestFeaturedProduct;
            $popularProduct['popularSaleProduct'] = $popularSaleProduct;

            //get all data in landing page data array
            $landingPageData['landingPage'][$sliderData['identity']] = $sliderData;
            $landingPageData['landingPage'][$pageContentData['identity']] = $pageContentData;
            $landingPageData['landingPage'][$productData['identity']] = $productData;

            if (!__isEmpty($bannerContentData)) {
                $landingPageData['landingPage'][$bannerContentData['identity']] = $bannerContentData;
            }
            if (!__isEmpty($banner2ContentData)) {
                $landingPageData['landingPage'][$banner2ContentData['identity']] = $banner2ContentData;
            }
            if (!__isEmpty($productTabData)) {
                $landingPageData['landingPage'][$productTabData['identity']] = $productTabData;
            }

            if (array_has($featuredProduct, 'identity')) {
                $landingPageData['landingPage'][$featuredProduct['identity']] = $featuredProduct;
            }

            if (array_has($popularProduct, 'identity')) {
                $landingPageData['landingPage'][$popularProduct['identity']] = $popularProduct;
            }
            

            $landingPageCollection = collect($landingPageData['landingPage']);

            $landingPageDetails['landingPage'] = $landingPageCollection->sortBy('orderIndex')->values()->all();        
        }

        $landingPageDetails['hideSidebar'] = true;
        $landingPageDetails['showFilterSidebar'] = false;

        return $this->loadPublicView('landing-page', $landingPageDetails);
    }



    /**
     * disply the brnad list.
     *
     * @return view
     *---------------------------------------------------------------- */
    protected function getBrandList()
    {
        $processReaction = $this->brandEngine
                                ->fetchIsActive();

        $processReaction['data']['hideSidebar'] = true;
        $processReaction['data']['showFilterSidebar'] = false;

        $brands = $processReaction['data'];

        return $this->loadPublicView('brand.list', $brands);
    }

    /**
     * ChangeLocale - It also managed from index.php.
     *---------------------------------------------------------------- */
    protected function changeLocale(Request $request, $localeId = null)
    {
        if (is_string($localeId)) {
            changeAppLocale($localeId);
        }
        if ($request->has('redirectTo')) {
            header('Location: '.base64_decode($request->get('redirectTo')));
            exit();
        }

        return __tr('Invalid Request');
    }

    /**
     * Change Currency
     *---------------------------------------------------------------- */
    protected function changeCurrency(Request $request, $currency = null)
    {
        if (is_string($currency)) {
            NativeSession::set('currency', $currency);
        }
        
        if ($request->has('redirectTo')) {
            if ($currency != getCurrency()) {
                return redirect(base64_decode($request->get('redirectTo')))->with([
                    'notify' => true,
                    'currencyMessage' => __tr('Currency __currency__ is only for display purpose & actual order will be processed in __transactionCurrency__.', [
                        '__currency__' => $currency,
                        '__transactionCurrency__' => getCurrency()
                    ]),
                ]);
            } else {
                header('Location: '.base64_decode($request->get('redirectTo')));
                exit();
            }
        }

        return __tr('Invalid Request');
    }
    /**
     * ChangeLocale - It also managed from index.php.
     *---------------------------------------------------------------- */
    public function cssStyle()
    {
        return response(getStoreSettings('custom_css'))->header('Content-Type', 'text/css');
    }

    /**
     * Change Theme Color
     *---------------------------------------------------------------- */
    public function changeThemeColor($colorName)
    {
        $themeColors = config('__tech.theme_colors');
        $themeColors = array_add($themeColors, 'default', 'b9002f');
        if (!__isEmpty($colorName) 
            and is_string($colorName)
            and array_key_exists($colorName, $themeColors)) {
            $colorName = $themeColors[$colorName];
            NativeSession::set('theme_color', $colorName);
            return $colorName;
        }

        return false;
    }

    /**
     * onvert hexdec color string to rgb(a) string
     *---------------------------------------------------------------- */
    protected function hex2rgba($color, $opacity = '0.4')
    {
        $default = 'rgba(0,0,0,'.$opacity.')';

        //Return default if no color provided
        if(empty($color))
        return $default; 

        //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
            $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }
}
