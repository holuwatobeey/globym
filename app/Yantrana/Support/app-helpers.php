<?php

use Carbon\Carbon;


/*
    |--------------------------------------------------------------------------
    | App Helpers
    |--------------------------------------------------------------------------
    |
    */

    /*
    * Customized GetText string
    *
    * @param string $string
    * @param array $replaceValues
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__') and config('__tech.gettext_fallback')) {
        function __($string, $replaceValues = [])
        {
            $string = T_gettext($string);

            // Check if replaceValues exist
            if (!empty($replaceValues) and is_array($replaceValues)) {
                $string = strtr($string, $replaceValues);
            }

            return $string;
        }
    }

    /*
    * Get Locale settings
    *
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('localeConfig')) {
        function localeConfig($item = null)
        {
            if ($item) {
                return config('__tech.locale.'.$item);
            }

            return config('__tech.locale');
        }
    }

    if (!function_exists('sortOrderURL')) {
        function sortOrderURL($sortBy = null, $options = [])
        {
            $getSbid = (!empty($options['sbid'])) ? $options['sbid'] : '';

            $sortRequest = \Request::only(['sort_by', 'sort_order', 'search_term', 'sbid', 'min_price', 'max_price', 'brandsIds', 'specsIds', 'rating', 'availability', 'categories']);
          
            $sortOrder = (isset($sortRequest['sort_order']) and $sortRequest['sort_order'] == 'asc') ? 'desc' : 'asc';

            if (isset($options['orderChange']) and $options['orderChange'] === false) {
                $sortOrder = $sortRequest['sort_order'] ?? null;
            }

            $sortByRequest = '';
            if (isset($sortRequest['sort_by'])) {
                $sortByRequest = $sortRequest['sort_by'];
            }

            $sortBy = empty($sortBy) ? $sortByRequest : $sortBy;

            $sortUrlPrefix = \Request::url().'?';

            if (!empty($sortRequest['search_term'])) {
                $sortUrlPrefix .= 'search_term='.$sortRequest['search_term'].'&';
            }

            if (!empty($sortRequest['sbid'])) {
                foreach ($sortRequest['sbid'] as $sbid) {
                    if ($sbid != $getSbid) {
                        $sortUrlPrefix .= 'sbid%5B%5D='.$sbid.'&';
                    }
                }
            }

            if (!empty($sortRequest['min_price'])) {
                $sortUrlPrefix .= 'min_price='.$sortRequest['min_price'].'&';
            }

            if (!empty($sortRequest['max_price'])) {
                $sortUrlPrefix .= 'max_price='.$sortRequest['max_price'].'&';
            }

            if (!empty($sortRequest['brandsIds'])) {
                $sortUrlPrefix .= 'brandsIds='.$sortRequest['brandsIds'].'&';
            }

            if (!empty($sortRequest['specsIds'])) {
                $sortUrlPrefix .= 'specsIds='.$sortRequest['specsIds'].'&';
            }

            if (!empty($sortRequest['rating'])) {
                $sortUrlPrefix .= 'rating='.$sortRequest['rating'].'&';
            }

            if (!empty($sortRequest['availability'])) {
                $sortUrlPrefix .= 'availability='.$sortRequest['availability'].'&';
            }

            if (!empty($sortRequest['categories'])) {
                $sortUrlPrefix .= 'categories='.$sortRequest['categories'].'&';
            }

            if (!empty($sortBy)) {
                $sortUrlPrefix .= "sort_by=$sortBy&sort_order=$sortOrder&";
            }
 
            return $sortUrlPrefix;
        }
    }

    /*
    * Removed Price filter
    *
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('removePriceFilter')) {
        function removePriceFilter()
        {
            $filterURL = sortOrderURL();

            $string = strchr($filterURL, 'min_price');

            return str_replace($string, ' ', $filterURL);
        }
    }

    if (!function_exists('createBreadcrumb')) {
        function createBreadcrumb($parentArray = [], $title = null)
        {
            $collectionBreadcrumb = [
                'parents' => $parentArray,
                'title' => $title,
            ];

            return $collectionBreadcrumb;
        }
    }

    /*
      * Clear All Filter
      *
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('clearAllFilter')) {
        function clearAllFilter($filterUrl)
        {
            $replaceUrl = strchr($filterUrl, '?');
            return str_replace($replaceUrl, '', $filterUrl);
        }
    }

    /*
      * Get user ID
      *
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('getUserID')) {
        function getUserID()
        {
            return Auth::id();
        }
    }

    /*
      * Get user ID
      *
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('isActiveUser')) {
        function isActiveUser()
        {
            if (!empty(Auth::user())) {
                if (Auth::user()->status != 1) {
                    Session::flash('invalidUserMessage',
                        __('Invalid request please contact administrator.'));

                    Auth::logout();

                    return true;
                }
            }

            return false;
        }
    }

    /*
      * Check if user logged in application
      *
      * @return boolean
      *-------------------------------------------------------- */

    if (!function_exists('canAccessManageApp')) {
        function canAccessManageApp()
        {
            if (canAccess('manage.app')) {
                return true;
            }

            return false;
        }
    }

    /*
      * check userID
      *
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('isLoggedInUserID')) {
        function isLoggedInUserID($fetchUserID)
        {
            $userID = Auth::user()->id;

            if ($fetchUserID == $userID) {
                return true;
            }

            return false;
        }
    }

    /*
      * Check if user logged in application
      *
      * @return boolean
      *-------------------------------------------------------- */

    if (!function_exists('isLoggedIn')) {
        function isLoggedIn()
        {
            isActiveUser();

            return Auth::check();
        }
    }

    /*
      * Check if logged in user is admin
      *
      * @return boolean
      *-------------------------------------------------------- */

    if (!function_exists('isAdmin')) {
        function isAdmin()
        {
            // Check if user looged in
            if (isLoggedIn()) {
                if (Auth::user()->role === 1) {
                    return true;
                }
            }

            return false;
        }
    }

    /*
      * Get user authentication
      *
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('getUserAuthInfo')) {
        function getUserAuthInfo($statusCode = null)
        {
            $userAuthInfo = [
                'authorized' => false,
                'reaction_code' => 9,
                'currentRouteName' => Route::currentRouteName(),
                'canAccessManageApp' => canAccessManageApp()
            ];

            if (Auth::check()) {
                $user = Auth::user();

                $role = (int) $user->role;

                $authenticationToken = md5(uniqid(true));

                $userAuthInfo = [
                    'authorization_token' => $authenticationToken,
                    'authorized' => true,
                    'reaction_code' => !empty($statusCode) ? $statusCode : 10,
                    'profile' => [
                        'full_name' => $user->fname.' '.$user->lname,
                        'email' => maskEmailId($user->email),
                    ],
                    'personnel' => $user->id,
                    'designation' => $role,
                    'canAccessManageApp'   => canAccessManageApp()
                ];
            }

            return $userAuthInfo;
        }
    }

    /*
    * Add activaity log entry
    *
    * @param string $activity
    *
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('activityLog')) {
        function activityLog($activity)
        {
            App\Yantrana\Components\User\Models\ActivityLog::create([
                    'activity' => $activity,
                    'users_id' => getUserID(),
                ]);
        }
    }

     /*
    * Add order log entry
    *
    * @param string $order
    *
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('orderLog')) {
        function orderLog($order, $message = null)
        {
            $user = Auth::user();

            $orderUpdateBy = '';

            if ($user) {
                $orderUpdateBy = ' by '.$user->fname.' '.$user->lname;
            }

            if (is_int($order)) {
                $orderId = $order;
            } else {
                $orderId = $order['orders__id'];
                $message = $order['description'];
            }

            App\Yantrana\Components\ShoppingCart\Models\OrderLog::create([
                    'orders__id' => $orderId,
                    'description' => json_encode([
                                            'createdAt' => 'On '.formatDateTime(
                                                            Carbon::now()).$orderUpdateBy,
                                            'message' => $message,
                                        ]),
                    'users_id' => getUserID(),
                    'ip_address' => Request::ip(),
                ]);
        }
    }

    /*
    * Get formatted log data
    *
    * @param number $orderID
    *
    * @return array.
    *-------------------------------------------------------- */

     if (!function_exists('getOrderLogFormattedData')) {
         function getOrderLogFormattedData($orderID)
         {
             $orderLogData = App\Yantrana\Components\ShoppingCart\Models\OrderLog::where('orders__id', $orderID)
                                ->orderBy('created_at', 'DESC')
                                ->select('description')
                                  ->get();

             $logDiscription = [];

             foreach ($orderLogData as $log) {

                // JSON decode of discription
                $logData = json_decode($log['description']);

                // push data into array
                $logDiscription [] = [
                    'created_at' => $logData->createdAt,
                    'description' => isset($logData->message) ? $logData->message : '',
                ];
             }

             return $logDiscription;
         }
     }

    /*
      * Generate angular app url based on route name & its segment name
      *
      * @param string $routeID
      * @param string $ngRouteID
      * @param array  $params
      * @param array  $ngParams
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('ngLink')) {
        function ngLink($routeID, $ngRouteID, $params = [], $ngParams = [])
        {
            if (!empty($params)) {
                $url = route($routeID, $params);
            } else {
                $url = route($routeID);
            }

            $url = $url.'#!'.config('__ng-routes.'.$ngRouteID.'.url');

            if (!empty($ngParams)) {
                foreach ($ngParams as $ngParam => $ngParamValue) {
                    $url = str_replace($ngParam, $ngParamValue, $url);
                }
            }

            return $url;
        }
    }

    /*
      * Get Date Time Format from config file
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('formatDateTime')) {
        function formatDateTime($date)
        {
            return formatDate($date, config('__tech.day_date_time_format'));
        }
    }

	if (!function_exists('humanFormatDateTime')) {
        function humanFormatDateTime($rawDateTime)
        {
            $dateUpdatedTimezone = accountTimezone($rawDateTime);

            return $dateUpdatedTimezone->diffForHumans();
        }
    }

    /*
      * Get formatted date.
      *
      * @param carbon object $date
      * @param string $format
      *
      * @return date.
      *-------------------------------------------------------- */

    if (!function_exists('formatDate')) {
        function formatDate($date, $formate = 'jS F Y')
        {
            $date = accountTimezone($date);

            return $date->format($formate);
        }
    }

    /*
      * Convert date with setting time zone
      *
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('accountTimezone')) {
        function accountTimezone($rawDate)
        {
            $carbonDate = Carbon::parse($rawDate);

            $accountTimezone = getStoreSettings('timezone');
            if (!__isEmpty($accountTimezone)) {
                $carbonDate->timezone = $accountTimezone;
            }

            return $carbonDate;
        }
    }

    /*
      * set current email driver and other pre-requisites
      *
      * @param int $driver  - id of driver
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('getEmailConfiguration')) {
        function getEmailConfiguration($key)
        {
            $mail_driver = getStoreSettings('mail_driver');
            $emailConfig = configItem(('mail_drivers.'.$mail_driver), 'config_data');
            
            if (!__isEmpty($key) && !__isEmpty($emailConfig)) {

                if (array_key_exists($key, $emailConfig)) {
                     
                    switch ($mail_driver) {
                        case 'smtp':
                            return getStoreSettings($emailConfig[$key]);
                            break;
                         
                        // case 'mandrill':
                        //  return getStoreSettings($emailConfig[$key]);
                        //  break;

                        default:
                            return null;
                            break;
                    }   
                }

            }

            

            return null;
        }
    }

    /*
    |-------------------------------------------------------------------------
    | Detect Active Routes
    |--------------------------------------------------------------------------
    |
    | Compare given routes with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    **************************************************************************/

    if (!function_exists('isActiveRoute')) {
        function isActiveRoute($route, $output = 'active')
        {
            if (Route::currentRouteName() == $route) {
                return $output;
            }
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTitle')) {
        function getTitle($key, $configString)
        {
            if ($key == 0) {
                $key = 3;
            }
           
            $codes = Config::get($configString);

            return $codes[$key];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTypeTitle')) {
        function getTypeTitle($key)
        {
            $codes = Config('__tech.pages_types_with_system_link');

            return $codes[$key];
        }
    }

    /*
    * Get code getSysLinkId.
    *
    * @param string $string
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getSysLinkId')) {
        function getSysLinkId($string)
        {
            $codes = Config('__tech.system_links');

            return $codes[$string];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTextTitle')) {
        function getTextTitle($key, $configString)
        {
            $newKey = '';
            if (!empty($key)) {
                $newKey = $key;
            }

            $codes = Config($configString);

            return $codes[$newKey];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTextMarkup')) {
        function getTextMarkup($markUpMessage, $data)
        {
            $markUpMessage = strtr($markUpMessage, $data);

            return $markUpMessage;
        }
    }

    /*
      * Get users media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUsersMediaPath')) {
        function getUsersMediaPath()
        {
            return public_path('media-storage/users/');
        }
    }

    /*
      * Get upload manager path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUploadManagerPath')) {
        function getUploadManagerPath()
        {
            return public_path('media-storage/upload-manager-assets/');
        }
    }

    /*
      * Get upload manager URL
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUploadManagerURL')) {
        function getUploadManagerURL()
        {
            return URL::to('media-storage/upload-manager-assets').'/';
        }
    }

    /*
      * Get logged in user media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLoggedInUserMediaPath')) {
        function getLoggedInUserMediaPath()
        {
            return getUsersMediaPath().'user-'.\getUserID();
        }
    }

    /*
      * Get logged in user temp media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLoggedInUserTempMediaPath')) {
        function getLoggedInUserTempMediaPath()
        {
            return getLoggedInUserMediaPath().'/temp/';
        }
    }

    /*
      * Get user temp image url
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUserTempImageURL')) {
        function getUserTempImageURL($tempImage, $userID)
        {
            $sourcePath = getUsersMediaPath().'/user-'.$userID.'/temp/'.$tempImage;

            if (File::exists($sourcePath)) {
                return getUsersMediaURL().'/user-'.$userID.'/temp/'.$tempImage;
            }

            return getNoThumbIconURL();
        }
    }

    /*
      * Get user temp image url
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getSliderTempImageURL')) {
        function getSliderTempImageURL($tempImage, $userID)
        {
            $sourcePath = getUsersMediaPath().'/user-'.$userID.'/temp/'.$tempImage;

            if (File::exists($sourcePath)) {
                return getUsersMediaURL().'/user-'.$userID.'/temp/'.$tempImage;
            }

            return getNoThumbIconURL();
        }
    }

    /*
    * Get products media storage path
    *
    * @return string path.
    *-------------------------------------------------------- */

    if (!function_exists('getSliderThumbanilUrl')) {
        function getSliderThumbanilUrl($id, $imageName)
        {   
            $imageUrl = URL::to("media-storage/slider/slider-$id/$imageName");               
            return serveViaCDN($imageUrl);
        }
    }

    /*
      * Get products media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductsMediaPath')) {
        function getProductsMediaPath()
        {
            return public_path('media-storage/products/');
        }
    }

    /*
      * Get products media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getSliderMediaPath')) {
        function getSliderMediaPath($id)
        {
            return public_path("media-storage/slider/slider-$id");
        }
    }

    /*
      * Get products media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getHomePageContentMediaPath')) {
        function getHomePageContentMediaPath($folderName)
        {
            return public_path("media-storage/home-page/$folderName");
        }
    }

    /*
    * Get Home page content Thumbnail URL
    *
    * @return string path.
    *-------------------------------------------------------- */

    if (!function_exists('getHomePageContentThumbanilUrl')) {
        function getHomePageContentThumbanilUrl($folderName, $imageName)
        {   
            $imageUrl = URL::to("media-storage/home-page/$folderName/$imageName");
            return serveViaCDN($imageUrl);
        }
    }

    /*
      * Get products media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getSlidersMediaURL')) {
        function getSlidersMediaURL()
        {
            return URL::to('media-storage/slider/');
        }
    }

    /*
      * Get users media path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUsersMediaURL')) {
        function getUsersMediaURL()
        {
            return URL::to('media-storage/users');
        }
    }

    /*
      * Get products media storage url
      *
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getProductsMediaURL')) {
        function getProductsMediaURL()
        {
            return URL::to('/media-storage/products/');
        }
    }

    /*
      * Get product media storage path
      *
      * @param number $productID
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductMediaPath')) {
        function getProductMediaPath($productID)
        {
            return getProductsMediaPath().'product-'.$productID;
        }
    }

    /*
      * Get brand media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandMediaPath')) {
        function getBrandMediaPath()
        {
            return public_path('media-storage/brands/logo/');
        }
    }

    /*
      * Get brand media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandMediaUrl')) {
        function getBrandMediaUrl($brandID)
        {
            return getBrandMediaPath().'brand-'.$brandID;
        }
    }

    /*
      * Get brand logo directory
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandLogoDirectory')) {
        function getBrandLogoDirectory()
        {
            return URL::to('/media-storage/brands/logo/');
        }
    }

    /*
      * Get brand logo url
      *
      * @param integer $brandID
      * @param string $fileName
      *
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandLogoURL')) {
        function getBrandLogoURL($brandID, $fileName)
        {

            $imageUrl = getBrandLogoDirectory().'/brand-'.$brandID.'/'.$fileName;

            return serveViaCDN($imageUrl);

            /* $logoMediaPath = getBrandMediaUrl($brandID).'/'.$fileName;

            // If logo file exist then return logo image url
            if (File::exists($logoMediaPath)) {
                return getBrandLogoDirectory().'/brand-'.$brandID.'/'.$fileName;
            } else {
                return noThumbImageURL();
            } */
        }
    }

    /*
      * Get product media storage url
      *
      * @param number $productID
      * @
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductMediaURL')) {
        function getProductMediaURL($productID)
        {
            return getProductsMediaURL().'/product-'.$productID;
        }
    }

    /*
      * Get product image url
      *
      * @param number $productID
      * @param string $fileName
      *
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getProductImageURL')) {
        function getProductImageURL($productID, $fileName)
        {
            $imageUrl = getProductMediaURL($productID).'/'.$fileName;

            return serveViaCDN($imageUrl);

            /* $imageMediaPath = getProductMediaPath($productID).'/'.$fileName;

            // If image file exist then return image url
            if (File::exists($imageMediaPath)) {
                return getProductMediaURL($productID).'/'.$fileName;
            } else {
                return noThumbImageURL();
            } */
        }
    }

    /*
      * Get product image url
      *
      * @param number $productID
      * @param string $fileName
      *
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('serveViaCDN')) {
        function serveViaCDN($assetUrl, $type = 'image')
        {
            if(getStoreSettings('enable_staticaly_cdn') == true) {
                $assetUrl = str_replace('https://','',  $assetUrl);
                $assetUrl = str_replace('http://','',  $assetUrl);

                if($type == 'image') {
                    $assetUrl = 'https://cdn.staticaly.com/img/'.$assetUrl;
                }
            }

            return  $assetUrl;
        }
    }

    /*
      * Get product zoom image url
      *
      * @param number $productID
      * @param string $fileName
      *
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getProductZoomImageURL')) {
        function getProductZoomImageURL($productID, $fileName)
        {
            /*$imageMediaPath = getProductMediaPath($productID).'/big_'.$fileName;
            $imageUrl = '';

            if (File::exists($imageMediaPath)) {
                $imageUrl = getProductMediaURL($productID).'/big_'.$fileName;
            } else {
                $imageUrl = getProductImageURL($productID, $fileName);
            }*/
            $imageUrl = getProductMediaURL($productID).'/big_'.$fileName;
            return  serveViaCDN($imageUrl);
        }
    }

    /*
      * Get current date time
      *
      * @return void
      *-------------------------------------------------------- */

    if (!function_exists('getCurrentDateTime')) {
        function getCurrentDateTime()
        {
            return new DateTime();
        }
    }

    /*
      * Get logo media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLogoMediaPath')) {
        function getLogoMediaPath()
        {
            return public_path('media-storage/logo/');
        }
    }

    /*
      * Get invoice media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getInvoiceMediaPath')) {
        function getInvoiceMediaPath()
        {
            return public_path('media-storage/invoice/');
        }
    }

     /*
      * Get favicon media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getFaviconMediaPath')) {
        function getFaviconMediaPath()
        {
            return public_path('media-storage/favicon/');
        }
    }

    /*
    * Get status code.
    *
    * @param number $status
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getSelectizeSource')) {
        function getSelectizeSource()
        {
            $statusCodes = Config('__tech');

            return $statusCodes;
        }
    }

    /*
      * Get status codes.
      *
      * @param string $configKey
      *
      * @return array.
      *-------------------------------------------------------- */

    if (!function_exists('getSelectizeOptions')) {
        function getSelectizeOptions($configArray, $configKey)
        {
            if (empty($configKey)) {
                return [];
            }

            $newStatusCodes = [];
            $statusCodes = Config($configArray);
            $requestedCodes = Config($configKey);

            if (!empty($requestedCodes)) {
                foreach ($requestedCodes as $code) {
                    if (array_key_exists($code, $statusCodes)) {
                        $newStatusCodes[] = [
                            'value' => $code,
                            'text' => $statusCodes[ $code ],
                        ];
                    }
                }
            }

            return $newStatusCodes;
        }
    }

    /*
      * Get product option required title.
      *
      * @param boolean $requiredValue
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('getProductOptionRequiredTitle')) {
        function getProductOptionRequiredTitle($requiredValue)
        {
            $title = ($requiredValue === true)
                    ? __('Yes')
                    : __('No');

            return $title;
        }
    }

    /*
    * return formated price
    *
    * @param float $amount
    *
    * @return float
    *---------------------------------------------------------------- */

    if (!function_exists('priceFormat')) {
        function priceFormat($amount = null, $currencyCode = false, $currencySymbol = false, $options = [])
        {
            /*$currencySymbol = getCurrencySymbol();

            $formatedCurrency = html_entity_decode($currencySymbol).number_format((float) $amount, 2).($currency == true ? ' '.getCurrency() : '');

            return $formatedCurrency;*/

            if ($currencyCode === true) {
                $currencyCode =  getStoreSettings('currency_value');
            }
            
            if (is_string($currencyCode) and is_string($currencySymbol)) {
                $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
            } elseif ($currencySymbol === true) {
                $currencySymbol = html_entity_decode(getStoreSettings('currency_symbol'));
            }

            if (is_string($currencyCode)
                and !__isEmpty($currencyCode)
                and __isEmpty($currencySymbol)) {
                $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
                $currencyCode = '';
            } elseif (is_string($currencySymbol)
                        and !__isEmpty($currencySymbol)) {
                $currencySymbol = $currencySymbol;
            }

            // If currency code and symbol have string
            if (is_string($currencyCode)
                and !__isEmpty($currencyCode)
                and is_string($currencySymbol)
                and !__isEmpty($currencySymbol)) {
                $currencyCode = $currencyCode;
                $currencySymbol = $currencySymbol;
            }

            // Check if multi currency is selected then calculate foreign exchange rates
            if (getStoreSettings('display_multi_currency') == true
                and array_has($options, 'isMultiCurrency')
                and ($options['isMultiCurrency'] == true)
                and (getSelectedCurrency() != getCurrency())) {

                $forignExchangeRates = calculateForeignExchangeRates($amount);

                $currencyCode = $forignExchangeRates['currencyCode'];
                $currencySymbol = $forignExchangeRates['currencySymbol'];
                $amount = $forignExchangeRates['amount'];
            }
            
            // Check if currency is zero decimal
            // If it is zero decimal currency then remove ".00" from amount
            if (isZeroDecimalCurrency($currencyCode)
                and (getStoreSettings('round_zero_decimal_currency') === true)) {
                //$price = number_format(handleCurrencyAmount($amount, $currencyCode));
                $price = handleCurrencyAmount($amount, $currencyCode);
            } else {
                //$price = number_format(handleCurrencyAmount($amount), getStoreSettings('currency_decimal_round'));
                $price = handleCurrencyAmount($amount);
            }

            $price = formatLocaleNumber($price, true);
            
            return trim(strtr(strip_tags(getStoreSettings('currency_format')), [
                    '{__currencySymbol__}' => $currencySymbol,
                    '{__amount__}' => ($amount === '__LABEL__') ? '{__amount__}' : $price,
                    '{__currencyCode__}' => $currencyCode
                ]));
        }
    }

    /*
    * return formated price
    *
    * @param float $amount
    *
    * @return float
    *---------------------------------------------------------------- */

    if (!function_exists('basePriceFormat')) {
        function basePriceFormat($amount = null, $currencyCode = false, $currencySymbol = false, $options = [])
        {
            if ($currencyCode === true) {
                $currencyCode =  getStoreSettings('currency_value');
            }
            
            if (is_string($currencyCode) and is_string($currencySymbol)) {
                $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
            } elseif ($currencySymbol === true) {
                $currencySymbol = html_entity_decode(getStoreSettings('currency_symbol'));
            }

            if (is_string($currencyCode)
                and !__isEmpty($currencyCode)
                and __isEmpty($currencySymbol)) {
                $currencySymbol = configItem('currencies.details.'.$currencyCode)['symbol'];
                $currencyCode = '';
            } elseif (is_string($currencySymbol)
                        and !__isEmpty($currencySymbol)) {
                $currencySymbol = $currencySymbol;
            }

            // If currency code and symbol have string
            if (is_string($currencyCode)
                and !__isEmpty($currencyCode)
                and is_string($currencySymbol)
                and !__isEmpty($currencySymbol)) {
                $currencyCode = $currencyCode;
                $currencySymbol = $currencySymbol;
            }

            // Check if multi currency is selected then calculate foreign exchange rates
            if (getStoreSettings('display_multi_currency') == true
                and array_has($options, 'isMultiCurrency')
                and ($options['isMultiCurrency'] == true)
                and (getSelectedCurrency() != getCurrency())) {

                $forignExchangeRates = calculateForeignExchangeRates($amount);

                $currencyCode = $forignExchangeRates['currencyCode'];
                $currencySymbol = $forignExchangeRates['currencySymbol'];
                $amount = $forignExchangeRates['amount'];
            }
            
            // Check if currency is zero decimal
            // If it is zero decimal currency then remove ".00" from amount
            if (isZeroDecimalCurrency($currencyCode)
                and (getStoreSettings('round_zero_decimal_currency') === true)) {
                $price = number_format(handleCurrencyAmount($amount, $currencyCode));

            } else {
                $price = number_format(handleCurrencyAmount($amount), getStoreSettings('currency_decimal_round'));
                
            }
            
            return trim(strtr(strip_tags(getStoreSettings('currency_format')), [
                    '{__currencySymbol__}' => $currencySymbol,
                    '{__amount__}' => ($amount === '__LABEL__') ? '{__amount__}' : $price,
                    '{__currencyCode__}' => $currencyCode
                ]));
        }
    }

    /**
    * format number as locale
    *
    * @param number - $amount    - amount
    *
    * @return void
    *-----------------------------------------------------------------------*/
    if (!function_exists('formatLocaleNumber')) {
        function formatLocaleNumber($amount, $isForPriceFormat = false)
        {
            if (class_exists('NumberFormatter')) {
                $numberFormatter = new NumberFormatter( CURRENT_LOCALE, NumberFormatter::DECIMAL );
                if ($isForPriceFormat) {
                    $numberFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, getStoreSettings('currency_decimal_round'));
                }                
                return $numberFormatter->format($amount);
            }

            return $amount;
        }
    }

    /**
    * calculateForeignExchangeRates
    *
    * @param number - $amount    - amount
    * @param String - $currency  - currency
    *
    * @return void
    *-----------------------------------------------------------------------*/
    if (!function_exists('calculateForeignExchangeRates')) {
        function calculateForeignExchangeRates($amount)
        {
            $amount = (float) $amount;
            $currencyAutoRefreshIn = getStoreSettings('auto_refresh_in');
            $refreshTime = configItem('curency_auto_refresh_time', $currencyAutoRefreshIn);
            $commonRepository = new App\Yantrana\Support\CommonRepository;
            $selectedCurrency = getSelectedCurrency();
            $currencySymbol = configItem('currencies.details.'.$selectedCurrency)['symbol'];
            $markup = $rate = 0;
            $cacheIdForCurrencyRates = 'cache.storeSetting.currencyRate';

            $currencyRates = $commonRepository->viaCache($cacheIdForCurrencyRates, $refreshTime, function() use($commonRepository, $selectedCurrency) {
                $baseCurrency = getCurrency();

                try {
                    // Get cURL resource
                    $curl = curl_init();
                    // Set some options - we are passing in a useragent too here
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://api.exchangeratesapi.io/latest?base='.$baseCurrency,
                        // CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                        CURLOPT_TIMEOUT => 10
                    ));
                    // Send the request & save response to $resp
                    $resp = curl_exec($curl);

                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                    if($httpcode !== 200) {
                        throw new Exception("Failed to fetch exchange rate", 1);
                    }

                    // Close request to clear up some resources
                    curl_close($curl);

                    $rates = json_decode($resp, true);

                    $rate = $rates['rates'][$selectedCurrency];

                    $selectedMultiCurrencies = getStoreSettings('multi_currencies');
                    $selectedRates = [];
                    if (!__isEmpty($selectedMultiCurrencies)) {
                        foreach (array_merge($selectedMultiCurrencies, [$baseCurrency]) as $currency) {
                            $selectedRates[$currency] = $rates['rates'][$currency];
                        }
                    }
                    
                    return $selectedRates;
                }
                catch (Exception $e) { // ConnectionException

                    // Remove selected currency from session
                    if (NativeSession::has('currency')) {
                        NativeSession::remove('currency');
                        return [];
                    }

                    // Show message to user
                    Session::flash('error', true);
                    Session::flash('message', __('We are unable to set requested display currency.'));
                }
            });

            if (!array_has($currencyRates, $selectedCurrency)) {
                if (NativeSession::has('currency')) {
                    return NativeSession::remove('currency');
                }
                $commonRepository->clearCache($cacheIdForCurrencyRates);
                Session::flash('error', true);
                Session::flash('message', __('We are unable to set requested display currency.'));
            }
            
            $currencyRate = array_get($currencyRates, $selectedCurrency, 0);

            $amount = $currencyRate * $amount;

            if (!__isEmpty(getStoreSettings('currency_markup'))) {
                $markup = $amount * getStoreSettings('currency_markup') / 100;
            }

            $amount = $amount + $markup;

            return [
                'amount'            => $amount,
                'rate'              => $currencyRate,
                'markup'            => $markup,
                'currencyCode'      => $selectedCurrency,
                'currencySymbol'    => $currencySymbol,
                'currencyRates'     => $currencyRates
            ];
        }
    }

    /**
    * Handle Currency Amount
    *
    * @param number - $amount    - amount
    * @param String - $currency  - currency
    *
    * @return void
    *-----------------------------------------------------------------------*/
    if (!function_exists('handleCurrencyAmount')) {
        function handleCurrencyAmount($amount, $currency = null)
        {
            if (!$amount) {
                return 0;
            }

            if (!$currency) {
                $currency = getStoreSettings('currency_value');
            }
            // Round Zero Decimal Currency
            if ((isZeroDecimalCurrency($currency) === true)
                and (getStoreSettings('round_zero_decimal_currency')) === true) {
                return round($amount);
            }
            
            return round($amount, getStoreSettings('currency_decimal_round'));
        }
    }

    /**
    * Check if Zero Decimal Currency
    *
    * @param String - $currency  - currency
    *
    * @return void
    *-----------------------------------------------------------------------*/
    if (!function_exists('isZeroDecimalCurrency')) {
        function isZeroDecimalCurrency($currency = null)
        {
            if (!$currency) {
                $currency = getStoreSettings('currency_value');
            }
            
            return array_key_exists($currency, configItem('currencies.zero_decimal'));
        }
    }

    /*
      * Cast the variable data
      *
      * @param array $setting
      *
      * @return mixed
      *-------------------------------------------------------- */

    if (!function_exists('getDataType')) {
        function getDataType($setting)
        {
            $configurationNames = configItem('settings.fields');
            $name  = $setting->name;
            $value = $setting->value;

            if (!__isEmpty($name) and array_key_exists($name, $configurationNames)) {
                $datTypeId = $configurationNames[$name]['data_type'];

                switch ($datTypeId) {
                case 1:
                    return (string) $value;
                    break;
                case 2:
                    return (bool) $value;
                    break;
                case 3:
                    return (int) $value;
                    break;
                case 4:
                    return json_decode($value, true);
                    break;
                case 5:
                    return (float) $value;
                    break;
                default:
                    return $value;
            }
            }
        }
    }

    /*
      * Get Re sourse Path
      *
      * @param string $fileName
      * @param string $folderName
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getResourcesAssetsPath')) {
        function getResourcesAssetsPath($fileName = null, $folderName = null)
        {
            $staticAssetsUrl = url('resources/assets/imgs/');

            if (__isEmpty($fileName) and __isEmpty($folderName)) {
                return $staticAssetsUrl;
            }

            if ($folderName) {
                return $staticAssetsUrl.'/'.$folderName.'/'.$fileName;
            }

            return $staticAssetsUrl.'/'.$fileName;
        }
    }

     /*
    * get setting items
    *
    * @param string $name
    *
    * @return void
    *---------------------------------------------------------------- */

    if (!function_exists('convertDateTimeZone')) {
        function convertDateTimeZone($date, $clientTimeZone)
        {   
            if (__isEmpty($date)) {
                return '';
            }

            $systemTimezone = getStoreSettings('timezone');
            $clientTimeZoneDate = '';
            if (!__isEmpty($date) && !__isEmpty($clientTimeZone)) {
                $clientTimeZoneDate = Carbon::createFromFormat('Y-m-d H:i:s', $date, $clientTimeZone)->setTimezone($systemTimezone);
            }
            
            return $clientTimeZoneDate;
        }
    }

    /*
    * get setting items
    *
    * @param string $name
    *
    * @return void
    *---------------------------------------------------------------- */

    if (!function_exists('getStoreSettings')) {
        function getStoreSettings($name, $details = false)
        {
            $configurationNames = config('__tech.settings.fields');
         	$storeSettings = [];

            $checkoutMethods = [
                'use_paypal' => 1,
                'payment_check' => 2,
                'payment_bank' => 3,
                'payment_cod' => 4,
                'payment_other' => 5,
                'use_stripe' => 6,
                'use_razorpay' => 11,
                'use_iyzipay' => 14,
                'use_paytm' => 16,
                'use_instamojo' => 18,
                'use_payStack' => 20
            ];

            

            $settings = Cache::rememberForever('cache.storeSetting.all', function () use ($checkoutMethods, $storeSettings) {

                $getSettings = \App\Yantrana\Components\Store\Models\Setting::all();
 				$storeSettings['valid_checkout_methods'] = [];
                foreach ($getSettings as $setting) {

                    $value = getDataType($setting);

                    $storeSettings[$setting->name] = $value;

                    // if the item set then make it available
                    if (array_key_exists($setting->name, $checkoutMethods) and $value == true) {
                        array_push($storeSettings['valid_checkout_methods'], $checkoutMethods[$setting->name]);
                    }
                }

                unset($checkoutMethods, $getSettings);

                return $storeSettings;
            });

            $settings['selected_theme_color'] = NativeSession::getIfHas('theme_color');
			
            // If logo not found.
            if (__ifIsset($settings['favicon_image'])) {

                $settings['favicon_image'] = asset('media-storage/favicon/'.$settings['favicon_image']);

                $settings['favicon_image_url'] = $settings['favicon_image'].'?logover='.@filemtime($settings['favicon_image']);

            }
            
			// If logo not found.
            if (__ifIsset($settings['logo_image'])) {

                $settings['logo_image'] = asset('media-storage/logo/'.$settings['logo_image']);

                $settings['logo_image_url'] = $settings['logo_image'].'?logover='.@filemtime($settings['logo_image']);

            }

            // If invoice image not found.
            if (isset($settings['invoice_image'])) {
                
                $settings['invoice_image'] = asset('media-storage/invoice/'.$settings['invoice_image']);

                $settings['invoice_image_url'] = $settings['invoice_image'].'?logover='.@filemtime($settings['invoice_image']);

                $settings['invoice_img_media_path'] =  public_path('media-storage/invoice/invoice_logo.png');

            }

            if (array_key_exists($name, $settings)) {

                return $settings[$name];
            }

            if (($name == 'logo_image') or ($name == 'logo_image_url')) {
                $logoName = $configurationNames['logo_image']['default'];

                $fullLogoPath = getResourcesAssetsPath($logoName);

                $defaultSettings['logo_image']     = $fullLogoPath;
                $defaultSettings['logo_image_url'] = $fullLogoPath.'?logover='.@filemtime($fullLogoPath);

                return $defaultSettings[$name];
            }

            if (($name == 'invoice_image') or ($name == 'invoice_image_url') or ($name == 'invoice_img_media_path')) {
                $invoiceImageName = $configurationNames['invoice_image']['default'];

                $fullInvoiceImagePath = getResourcesAssetsPath($invoiceImageName);

                $defaultSettings['invoice_image']     = $fullInvoiceImagePath;
                $defaultSettings['invoice_image_url'] = $fullInvoiceImagePath.'?logover='.@filemtime($fullInvoiceImagePath);
                $defaultSettings['invoice_img_media_path'] =  public_path('resources/assets/imgs/invoice_logo.png');

                return $defaultSettings[$name];
            }

            if (($name == 'favicon_image') or ($name == 'favicon_image_url')) {
                $faviconImageName = $configurationNames['favicon_image']['default'];
                $fullFeviconPath = getResourcesAssetsPath($faviconImageName);

                $defaultSettings['favicon_image']     = $fullFeviconPath;
                $defaultSettings['favicon_image_url'] = $fullFeviconPath.'?logover='.@filemtime($fullFeviconPath);
                 return $defaultSettings[$name];
            }
          
            return $configurationNames[$name]['default'];
        }
    }

    /*
      * Get store auth information
      *
      * @return array
      *-------------------------------------------------------- */

    if (!function_exists('error404')) {
        function error404()
        {
            return abort(404);
        }
    }

    /*
    * Get categories product route.
    *
    * @param number $cateID,
    *
    * @param string $categoryName
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('categoriesProductRoute')) {
        function categoriesProductRoute($cateID, $categoryName = null)
        {
            if (!empty($categoryName)) {
                $categoryName = slugIt($categoryName);
            }

            return route('products_by_category', [$cateID, $categoryName]);
        }
    }

    /*
    * Get all product route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsRoute')) {
        function productsRoute()
        {
            return route('products');
        }
    }

    /*
    * Get all product route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('compareProduct')) {
        function compareProduct()
        {
            return route('compare_product');
        }
    }

    /*
    * Get featured product route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsFeatureRoute')) {
        function productsFeatureRoute()
        {
            return route('products.featured');
        }
    }

    /*
    * Get product details route.
    *
    * @param number $productID,
    *
    * @param string $productName
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsDetailsRoute')) {
        function productsDetailsRoute($productID, $productName, $categoryID = null)
        {
            return route('product.details', [
                        'productID' => $productID,
                        'productName?' => $productName,
                        'categoryID?' => $categoryID,
                        ]);
        }
    }

    /*
    * Get product search route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsSearchRoute')) {
        function productsSearchRoute()
        {
            return route('product.search');
        }
    }

    /*
    * Get page detail route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('pageDetailsRoute')) {
        function pageDetailsRoute($pageID, $pageName = null)
        {
            if (!empty($pageName)) {
                $pageName = slugIt($pageName);
            }

            return route('display.page.details', [$pageID, $pageName]);
        }
    }

    /*
    * Get page detail route.
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('loginRoute')) {
        function loginRoute()
        {
            return route('user.login');
        }
    }

    /*
    * find all active parents recursively
    * and also active parents
    *
    * @param (object) $itemCollection.
    * @param (int) $itemID.
    * @param (array) $activeItemsContainer.
    *
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findActiveParents')) {
        function findActiveParents($itemCollection, $itemID = null, $activeItemsContainer = [])
        {
            foreach ($itemCollection as $item) {
                if ($item->id === (int) $itemID) {
                    if ($item->status === 1) {
                        $activeItemsContainer[] = $item->id;

                        if ($item->parent_id) {
                            $activeItemsContainer = findActiveParents(
                                                            $itemCollection,
                                                            $item->parent_id,
                                                            $activeItemsContainer
                                                        );
                        }
                    } else {
                        $activeItemsContainer = [];

                        break;
                    }
                }
            }

            return array_values(array_unique(array_flatten($activeItemsContainer)));
        }
    }

    /*
    * find all active parents recursively
    * and also active parents
    *
    * @param (object) $itemCollection.
    * @param (int) $itemID.
    * @param (array) $activeItemsContainer.
    *
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findParents')) {
        function findParents($itemCollection, $itemID = null, $activeItemsContainer = [])
        {
            foreach ($itemCollection as $item) {
                if ($item->id === (int) $itemID) {
                    $activeItemsContainer[] = $item->id;

                    if ($item->parent_id) {
                        $activeItemsContainer = findParents(
                                                            $itemCollection,
                                                            $item->parent_id,
                                                            $activeItemsContainer
                                                        );
                    }
                }
            }

            return array_values(array_unique(array_flatten($activeItemsContainer)));
        }
    }

    /*
    * find all active childrens recursively
    *
    * @param (object) $itemCollection.
    * @param (int) $itemID.
    * @param (array) $activeItemsContainer.
    *
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findActiveChildren')) {
        function findActiveChildren($itemCollection, $activeItemsContainer = [])
        {
            foreach ($itemCollection as $category) {
                if ($category->status == 1 and in_array($category->id, $activeItemsContainer) !== true) {
                    $activeItemsContainer[] = $category->id;
                }
                // Check if children category exists
                if(isset($category->children) and !__isEmpty($category->children)) {
                    $activeItemsContainer = findActiveChildren($category->children, $activeItemsContainer);
                }
            }
            $itemCollection = null;
            unset($itemCollection);
            return array_unique($activeItemsContainer);
        }
    }

    /*
    * find all active childrens recursively
    *
    * @param (object) $itemCollection.
    * @param (int) $itemID.
    * @param (array) $activeItemsContainer.
    *
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('getProductCategory')) {
        function getProductCategory($categories, $carProductID)
        {
            $productCategories = \App\Yantrana\Components\Product\
                                    Models\ProductCategory::where(
                                        'products_id',
                                        $carProductID
                                    )->select(
                                        'products_id',
                                        'categories_id'
                                    )->get();

            foreach ($productCategories as $productCategory) {
                $categoriesID = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesID);
            }

            $checkParentStatus = false;  // invalid // deactive

            if (!empty($findActiveParents)) {
                foreach ($findActiveParents as $cateParents) {
                    if (!empty($cateParents)) {
                        $checkParentStatus = true; // valid  // active
                    }
                }
            }

            return $checkParentStatus;
        }
    }

    /*
      * find active parents
      * return self ID  & parent_id
      * @param (object) $itemCollection.
      * @param (int) $itemID.
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('isParentCategoryInactive')) {
        function isParentCategoryInactive($itemCollection, $itemID = null)
        {
            return findActiveParents($itemCollection, (int) $itemID) ?: false;
        }
    }

    /*
      * formate fancytree source
      * @param (object) $nodesCollection.
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('fancytreeSource')) {
        function fancytreeSource($nodesCollection)
        {
            $nodes = [];

            foreach ($nodesCollection as $node) {
                $nodes[] = [
                    'title' => isset($node->name) ? $node->name : $node->title,
                    'key' => $node->id,
                    'parent_id' => $node->parent_id,
                ];
            }

            return $nodes;
        }
    }

    /*
      * generate order id with date formate
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('generateOrderID')) {
        function generateOrderID()
        {
            $uid = uniqid();

            if (is_int($uid)) {
                $uid = generateOrderID();
            }

            return $uid;
        }
    }

    /*
      * get config
      * @param string $configPth
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('formatedConfig')) {
        function formatedConfig($configPath)
        {
            $result = [];

            foreach ($configPath as $key => $status) {
                $result[] = [
                    'value' => strtolower($key),
                    'text' => $status,
                ];
            }

            return $result;
        }
    }

    /*
      * get config
      * @param string $configPth
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('totalPrice')) {
        function totalPrice($price)
        {
            return array_sum($price);
        }
    }

    /*
      * get Refined cart items
      *
      * @param array $getCartItems
      * @param object $products
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('getRefinedCart')) {
        function getRefinedCart($getCartItems, $products)
        {
            $cartTotal = 0;
            $isCartReady = true;
            $isCurrencyMismatch = false;
            $itemIsInvalid = false;// is valid product
            $cartTotalAmount = [];
            $unreadyMsg = null;

            $activeDiscounts = App\Yantrana\Components\Coupon\Models\Coupon::where('start', '<=', currentDateTime())
                ->active()                  
                ->with('couponProduct')
                ->whereNull('code')
                ->whereNotNull('products_scope')
                ->where(function($query) {
                    return $query->where('end', '>=', currentDateTime())
                                ->orWhereNull('end');
                 })->get();
            $itemsCount =  0;
            $totalQuantity = [];
            foreach ($getCartItems as $cartItemKey => $cartItemKeyValue) {

                $showRefreshButton = false;
                
                // verify this product with cart item
                $verifyProduct = ShoppingCart::verifyCartProduct($getCartItems[$cartItemKey], $products, $activeDiscounts);

                $getCartItems[$cartItemKey]['ERROR'] = null;
                $getCartItems[$cartItemKey]['ERROR_MSG'] = null;

                $productVerificationResult = $verifyProduct['result'];
                
                if ($productVerificationResult !== true) {
                    $errorMsg = null;

                    if (($productVerificationResult === 'ERR_PRODUCT_OUT_OF_STOCK')
                        and getStoreSettings('show_out_of_stock')) {
                        $errorMsg = __('Out of Stock');
                    } elseif ($productVerificationResult === 'ERROR_PRODUCT_SPECIFIC_DISCOUNT_AMOUNT') { 
                        $showRefreshButton = true;
                        $errorMsg = __('Product discount has been changed, please reload.');
                    } elseif (($productVerificationResult === 'ERR_CURRENCY_INVALID')
                        or ($productVerificationResult === 'ERR_CURRENCY_SYMBOL_INVALID')) {
                        $isCurrencyMismatch = true;
                        $errorMsg = __('Store Currency has been changed, please remove this product.');
                    } elseif ($productVerificationResult === 'ERR_PRODUCT_PRICE_MISMATCH') {
                        $showRefreshButton = true;
                        $errorMsg = __('Product price has been changed, please reload.');
                    } elseif ($productVerificationResult === 'ERR_PRODUCT_INVALID_OPTION_VALUE_PRICE') {
                        $showRefreshButton = true;
                        $errorMsg = __('Product addon price has been changed, please reload.');
                    } else {
                        $errorMsg = __('Product currently not available');
                    }

                    $getCartItems[$cartItemKey]['ERROR_MSG'] = $errorMsg;
                    $getCartItems[$cartItemKey]['ERROR'] = $productVerificationResult;

                    $unreadyMsg = __('Highlighted item in the cart seems currently not available/changed, please remove it.');
                    $isCartReady = false;
                    $itemIsInvalid = true;
                }

                $currencySymbol = $cartItemKeyValue['currencySumbol'];
                $currencyCode = $cartItemKeyValue['currency'];
                
                $addonPrice = [];

                $cartProductPrice = $cartItemKeyValue['price'];

                if (!__isEmpty($getCartItems[$cartItemKey]['options'])) {
                    foreach ($getCartItems[$cartItemKey]['options'] as $key => $option) {

                        $getCartItems[$cartItemKey]['options'][$key]['formated_addon_price']
                                    = priceFormat($option['addonPrice'], false, $currencySymbol);

                        $addonPrice[] = $option['addonPrice'];
                    }
                }

                $getCartItems[$cartItemKey]['showRefreshButton'] = $showRefreshButton;
                $getCartItems[$cartItemKey]['formated_price'] = priceFormat($cartProductPrice, false, $currencySymbol);
                
                $getCartItems[$cartItemKey]['thumbnail_url'] = getProductImageURL($cartItemKeyValue['id'], $cartItemKeyValue['thumbnail']);
                $getCartItems[$cartItemKey]['productDetailURL'] = route('product.details', ['productID' => $cartItemKeyValue['id'], 'productName' => slugIt($cartItemKeyValue['name'])]);

                $calculatedAddonPrice = 0;

                if (!__isEmpty($addonPrice)) {
                    $calculatedAddonPrice = totalPrice($addonPrice); // array sum
                }

                $subtotal = $beforeDiscountPrice = $cartProductPrice + $calculatedAddonPrice;

                $productDiscount = (isset($cartItemKeyValue['discount'])) 
                                    ? $cartItemKeyValue['discount']
                                    : null;
                
                $getCartItems[$cartItemKey]['productDiscount'] = $productDiscount;

                if ($productDiscount['isDiscountExist'] == true) {
                    $subtotal = $productDiscount['productPrice'];
                }                 
                
                $total = $subtotal * $cartItemKeyValue['qty'];

                $totalQuantity[] = $cartItemKeyValue['qty'];

                $getCartItems[$cartItemKey]['new_price'] = priceFormat($subtotal, false, $currencySymbol);
                $getCartItems[$cartItemKey]['new_subTotal'] = priceFormat($total, false, $currencySymbol);
                $getCartItems[$cartItemKey]['beforeDiscountPrice'] = priceFormat($beforeDiscountPrice, $currencyCode, $currencySymbol);

                $getCartItems[$cartItemKey]['new_raw_price'] = $subtotal;
                $getCartItems[$cartItemKey]['new_raw_subTotal'] = $total;
                
                $cartTotalAmount[] = $total;
                $itemsCount++;
            }

            if (empty($cartTotalAmount)) {
                $isCartReady = false;
                $unreadyMsg = __('Cart is empty');
            }

            $orderDiscount = calculateOrderDiscount(totalPrice($cartTotalAmount));
            
            if ($orderDiscount['isOrderDiscountExist']) {
                if ($orderDiscount['newCartTotal'] < 0) {
                    $isCartReady = false;
                    $unreadyMsg = __('Cart amount is invalid.');
                }
            }
           
            return [
                'productData' => $getCartItems,
                'cartReady' => $isCartReady,
                'itemIsInvalid' => $itemIsInvalid,
                'notReadyReason' => $unreadyMsg,
                'totalPriceItems' => $cartTotalAmount,
                'orderDiscount'   => $orderDiscount,
                'cartPriceTotal' => totalPrice($cartTotalAmount),
                'isCurrencyMismatch' => $isCurrencyMismatch,
                'itemsCount' => $itemsCount,
                'totalQuantity' => array_sum($totalQuantity)
            ];
        }
    }

    /*
      * Calculate Cart Discount
      *
      * @param array $cartItems
      * @param number $productPrice
      * @param bool $useBaseCurrency
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('calculateSpecificProductDiscount')) {
        function calculateSpecificProductDiscount($productId, $productPrice, $activeDiscounts = [], $useBaseCurrency = false)
        {   
            // Check if discount is empty
            if (__isEmpty($activeDiscounts)) {
                return [
                    'isDiscountExist'           => false,
                    'rawDiscount'               => null,
                    'discount'                  => null,
                    'productPrice'              => $productPrice,
                    'formattedProductPrice'     => ($useBaseCurrency) 
                                                    ? priceFormat($productPrice, true, true)
                                                    : priceFormat($productPrice, true, true, ['isMultiCurrency' => true]),
                    'discountDetails'           => null
                ];
            }

            $totalDiscount = $discountDetails = [];
            $productsIds = [];
            $isDiscountExist = false;
            $total = $newProductPrice = $discountAmount = 0;
            
            // Check if active discount exist
            foreach ($activeDiscounts as $key => $discount) {
                if ((__isEmpty($discount->code)) 
                    and ($discount->products_scope == 2)) {
                    
                    $isAmountCapping = false;

                    $productsIds = $discount->couponProduct->pluck('products_id')->all();
                    
                    if (in_array($productId, $productsIds)) {

                        $isDiscountExist = true;
                        $percentageOrAmount = 0;

                        if ($discount->discount_type == 1) {

                            $discountAmount = (handleCurrencyAmount($discount->max_discount) / 100) * $productPrice;

                            if (handleCurrencyAmount($discount->discount) < $discountAmount) {

                                $discountAmount = handleCurrencyAmount($discount->discount);
                                $percentageOrAmount = ($useBaseCurrency)
                                                    ? priceFormat($discountAmount, true, false)
                                                    : priceFormat($discountAmount, true, false, ['isMultiCurrency' => true]);

                            } else {

                                $isAmountCapping = true;
                                $discountAmount = handleCurrencyAmount($discountAmount);
                                $percentageOrAmount = $discount->max_discount.'%';
                            }

                            $totalDiscount[] = $discountAmount;

                            $formattedDiscountAmt = ($useBaseCurrency)
                                                    ? priceFormat(handleCurrencyAmount($discount->discount), true, false)
                                                    : priceFormat(handleCurrencyAmount($discount->discount), true, false, ['isMultiCurrency' => true]);
                            $formattedMaxAmount = $discount->max_discount.'%';


                            $discountDetails[] = [
                                'id'                => $discount->_id,
                                'title'             => $discount->title,
                                'discount'          => $percentageOrAmount,
                                'description'       => $discount->description,
                                'formattedDiscountAmt'  => $formattedDiscountAmt,
                                'formattedMaxAmount'    => $formattedMaxAmount,
                                'isAmountCapping'   => $isAmountCapping,
                                'formattedSingleDiscount' => ($useBaseCurrency)
                                                    ? priceFormat(handleCurrencyAmount($discountAmount), true, false)
                                                    : priceFormat(handleCurrencyAmount($discountAmount), true, false, ['isMultiCurrency' => true])
                            ];

                        } else {

                            $discountAmount = (handleCurrencyAmount($discount->discount) / 100) * $productPrice;

                            if (handleCurrencyAmount($discount->max_discount) < $discountAmount) {

                                $discountAmount = handleCurrencyAmount($discount->max_discount);
                                $isAmountCapping = true;
                                $percentageOrAmount = ($useBaseCurrency)
                                                        ? priceFormat($discountAmount, true, false)
                                                        : priceFormat($discountAmount, true, false, ['isMultiCurrency' => true]);

                            } else {
                                $discountAmount = handleCurrencyAmount($discountAmount);
                                $percentageOrAmount = $discount->discount.'%';
                            }
                            
                            $totalDiscount[] = $discountAmount;

                            $formattedDiscountAmt = $discount->discount.'%';
                            $formattedMaxAmount = ($useBaseCurrency)
                                                    ? priceFormat($discount->max_discount, true, false)
                                                    : priceFormat($discount->max_discount, true, false, ['isMultiCurrency' => true]);

                            $discountDetails[] = [
                                'id'                => $discount->_id,
                                'title'             => $discount->title,
                                'discount'          => $percentageOrAmount,
                                'description'       => $discount->description,
                                'formattedDiscountAmt'  => $formattedDiscountAmt,
                                'formattedMaxAmount'    => $formattedMaxAmount,
                                'isAmountCapping'   => $isAmountCapping,
                                'formattedSingleDiscount' => ($useBaseCurrency)
                                                    ? priceFormat($discountAmount, true, false)
                                                    : priceFormat($discountAmount, true, false, ['isMultiCurrency' => true])
                            ];
                        }
                    }
                }
            }

            if ($isDiscountExist) {
                $total = array_sum($totalDiscount);
                $newProductPrice = $productPrice - $total;

                return [
                    'isDiscountExist'           => true,
                    'rawDiscount'               => handleCurrencyAmount($total),
                    'discount'                  => ($useBaseCurrency)
                                                    ? priceFormat($total, true, true)
                                                    : priceFormat($total, true, true, ['isMultiCurrency' => true]),
                    'productPrice'              => $newProductPrice,
                    'formattedProductPrice'     => ($useBaseCurrency)
                                                    ? priceFormat($newProductPrice, true, true)
                                                    : priceFormat($newProductPrice, true, true, ['isMultiCurrency' => true]),
                    'discountDetails'           => $discountDetails
                ];
            }
            
           return [
                'isDiscountExist'           => false,
                'rawDiscount'               => null,
                'discount'                  => null,
                'productPrice'              => $productPrice,
                'formattedProductPrice'     => ($useBaseCurrency)
                                                ? priceFormat($productPrice, true, true)
                                                : priceFormat($productPrice, true, true, ['isMultiCurrency' => true]),
                'discountDetails'           => null
            ];
        }
    }

    /*
      * Calculate Order Discount
      *
      * @param array $cartItems
      * @param number $cartTotal
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('calculateOrderDiscount')) {
        function calculateOrderDiscount($cartTotal)
        {  
            // Fetch All active discount coupons
            $activeDiscounts = App\Yantrana\Components\Coupon\Models\Coupon::where('start', '<=', currentDateTime())
                    ->active()                  
                    ->with('couponProduct')
                    //->whereNull('code')
                    ->whereNotNull('products_scope')
                    ->where(function($query) {
                        return $query->where('end', '>=', currentDateTime())
                                    ->orWhereNull('end');
                     })->get();

                $totalDiscount = $discountDetails = [];
                $discountAmount = $totalAmount = 0;
                $isDiscountExist = false;

                // Check if discount is empty
                if (__isEmpty($activeDiscounts)) {
                    return [
                        'isOrderDiscountExist'  => false,
                        'discount'              => $discountAmount,
                        'formattedDiscount'     => null,
                        'discountDetails'       => $discountDetails,
                        'newCartTotal'          => $cartTotal
                    ];
                }

            // Check if active discount exist
            foreach ($activeDiscounts as $discountKey => $discount) {

                if ((__isEmpty($discount->code)) 
                    and ($discount->products_scope == 1)
                    and (handleCurrencyAmount($cartTotal) > handleCurrencyAmount($discount->minimum_order_amount))) {

                    $isDiscountExist = true;
                    $percentageOrAmount = 0;
                    $isAmountCapping = false;

                    if ($discount->discount_type == 1) {
                        
                        $discountAmount = (handleCurrencyAmount($discount->max_discount) / 100) * $cartTotal;
                        
                        if (handleCurrencyAmount($discount->discount) < $discountAmount) {

                            $discountAmount = handleCurrencyAmount($discount->discount);
                            $percentageOrAmount = priceFormat($discountAmount, true, false);

                        } else {

                            $isAmountCapping = true;
                            $discountAmount = handleCurrencyAmount($discountAmount);
                            $percentageOrAmount = $discount->max_discount.'%';
                        }

                        $formattedDiscountAmt = priceFormat(handleCurrencyAmount($discount->discount), true, false);
                        $formattedMaxAmount = $discount->max_discount.'%';

                        $discountDetails[] = [
                            'id'                => $discount->_id,
                            'title'             =>  __transliterate('coupons', $discount->_id, 'title', $discount->title),
                            'discount'          => $percentageOrAmount,
                            'description'       => __transliterate('coupons', $discount->_id, 'description', $discount->description),
                            'formattedDiscountAmt'  => $formattedDiscountAmt,
                            'formattedMaxAmount'    =>  $formattedMaxAmount,
                            'isAmountCapping'   => $isAmountCapping,
                            'formattedSingleDiscount' => priceFormat($discountAmount, true, false)
                        ];

                    } elseif ($discount->discount_type == 2) {
                        
                        $discountAmount = (handleCurrencyAmount($discount->discount) / 100) * $cartTotal;

                        if (handleCurrencyAmount($discount->max_discount) < $discountAmount) {

                            $discountAmount = handleCurrencyAmount($discount->max_discount);
                            $isAmountCapping = true;
                            $percentageOrAmount = priceFormat($discountAmount, true, false);

                        } else {

                            $discountAmount = $discountAmount;
                            $percentageOrAmount = $discount->discount.'%';
                        }

                        $formattedDiscountAmt = $discount->discount.'%';
                        $formattedMaxAmount = priceFormat($discount->max_discount, true, false);


                        $discountDetails[] = [
                            'id'                => $discount->_id,
                            'title'             =>  __transliterate('coupons', $discount->_id, 'title', $discount->title),
                            'discount'          => $percentageOrAmount,
                            'description'       => __transliterate('coupons', $discount->_id, 'description', $discount->description),
                            'formattedDiscountAmt' => $formattedDiscountAmt,
                            'formattedMaxAmount'   => $formattedMaxAmount,
                            'isAmountCapping'   => $isAmountCapping,
                            'formattedSingleDiscount' => priceFormat($discountAmount, true, false)
                        ];
                    }

                    $totalDiscount[] = handleCurrencyAmount($discountAmount);
                }
            }

            if ($isDiscountExist) {

                $totalAmount = array_sum($totalDiscount);
                $newCartTotal = handleCurrencyAmount($cartTotal) - handleCurrencyAmount($totalAmount);

                return [
                    'isOrderDiscountExist'  => true,
                    'discount'              => $totalAmount,
                    'formattedDiscount'     => priceFormat($totalAmount, true, true),
                    'discountDetails'       => $discountDetails,
                    'newCartTotal'          => handleCurrencyAmount($newCartTotal),
                ];
            }

            return [
                'isOrderDiscountExist'  => false,
                'discount'              => $discountAmount,
                'formattedDiscount'     => null,
                'discountDetails'       => $discountDetails,
                'newCartTotal'          => $cartTotal,
            ];
        }
    }

    /*
      * get all categories
      *
      * @param string $code
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getAllCategories')) {
        function getAllCategories()
        {
            return Cache::rememberForever('cache.categories.all', function () {
                return \App\Yantrana\Components\Category\Models\Category::all();
            });
        }
    }

    /*
      * Format amount
      *
      * @param string $code
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('numberFormatAmount')) {
        function numberFormatAmount($amount)
        {
            return (double) handleCurrencyAmount($amount);
        }
    }

    /*
      * get youtube url
      *
      * @param string $code
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getYoutubeUrl')) {
        function getYoutubeUrl($code)
        {
            return 'https://www.youtube.com/embed/'.$code;
        }
    }

    /*
      * get all other code
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getAocCode')) {
        function getAocCode()
        {
            return Config('__tech.aoc');
        }
    }

    /*
      * get set currency
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCurrency')) {
        function getCurrency()
        {
            return html_entity_decode(getStoreSettings('currency_value'));
        }
    }

    /*
      * get set currency Symbol
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCurrencySymbol')) {
        function getCurrencySymbol()
        {
            return html_entity_decode(getStoreSettings('currency_symbol'));
        }
    }

    /*
      * get countries list
      *
      * @param string $selectedCountries
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCountries')) {
        function getCountries()
        {
            $countriesCollection = [];

            foreach (config('__tech.countries') as $key => $country) {
                $countriesCollection[] = [
                        'value' => $key,
                        'text' => $country,
                    ];
            }

            return [
                'countries' => $countriesCollection,
                'currencySymbol' => getCurrencySymbol(),
                'currency' => getCurrency(),
            ];
        }
    }

    /*
    * Set new orders count in session
    *
    * @param int $count
    *
    * @return void
    *-------------------------------------------------------- */

    if (!function_exists('setInSessionNewOrderPlacedCount')) {
        function setInSessionNewOrderPlacedCount($count)
        {
            if (isAdmin()) {
                $order = [
                    'orderData' => [
                        'newOrderPlacedCount' => $count,
                    ],
                ];

                Session::put('additional', $order);
            }
        }
    }

    /*
      * get timezone list
      *
      * @param string $timezone
      *
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getTimeZone')) {
        function getTimeZone()
        {
            $timezoneCollection = [];
            $timezoneList = timezone_identifiers_list();
            foreach ($timezoneList as $timezone) {
                $timezoneCollection[] = [
                        'value' => $timezone,
                        'text' => $timezone,
                    ];
            }

            return $timezoneCollection;
        }
    }

    /*
      * Get demo mode for defined admin
      *
      * @return boolean.
      *-------------------------------------------------------- */

    if (!function_exists('isDemoForAdmin')) {
        function isDemoForAdmin()
        {
            if (isDemo()) {
                return true;
            }

            return false;
        }
    }

    /*
      * Get formatted date time from passed raw date using timezone
      *
      * @param string $rawDateTime
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('formatStoreDateTime')) {
        function formatStoreDateTime($rawDateTime, $format = 'l jS F Y g:i:s a')
        {

            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $rawDateTime);

            if (class_exists('IntlDateFormatter') and ($format === 'l jS F Y g:i:s a')) {
                    $fmt = new IntlDateFormatter(CURRENT_LOCALE,
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::LONG,
                    getStoreSettings('timezone'),
                    NULL,
                    "");
                    return $fmt->format($carbonDate);
            } 
            return $carbonDate->format($format);
        }
    }

    /*
      * Get formatted date time from passed raw date using timezone
      *
      * @param string $rawDateTime
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('formattedDateTime')) {
        function formattedDateTime($rawDateTime, $format = 'l jS F Y g:i:s a')
        {
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $rawDateTime);

            return $carbonDate->format($format);
        }
    }

    /*
      * Get formatted date from passed raw date using timezone
      *
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('formatStoreDate')) {
        function formatStoreDate($rawDate, $format = 'jS F Y')
        {
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $rawDate);

            if (class_exists('IntlDateFormatter') 
                and ($format === 'jS F Y')) {
                    $fmt = new IntlDateFormatter(CURRENT_LOCALE,
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::NONE,
                    getStoreSettings('timezone'),
                    NULL,
                    "");
                    return $fmt->format($carbonDate);
            }

            return $carbonDate->format($format);
        }
    }

    /*
      * Get formatted date time from passed current date using timezone
      *
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('currentDateTime')) {
        function currentDateTime()
        {
            return Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    /*
      * Get demo mode for Demo of site
      *
      * @return boolean.
      *-------------------------------------------------------- */

    if (!function_exists('isDemo')) {
        function isDemo()
        {
            return (env('IS_DEMO_MODE', false)) ? true : false;
        }
    }

    /*
      * Get currency symbol with price.
      *
      * @param boolean $amount
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('orderPriceFormat')) {
        function orderPriceFormat($amount = null, $currencyCode)
        {
            return priceFormat($amount, $currencyCode);
        }
    }

    /*
      * return formated keywords for meta data
      *
      * @param array $array
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('getKeywords')) {
        function getKeywords($array)
        {
            if (!__isEmpty($array)) {
                foreach ($array as $value) {
                    echo array_key_exists('name', $value) ? $value['name'] : $value['title'];

                    if (end($array) !== $value) {
                        echo ', ';
                    }
                }
            }
        }
    }

    /*
      * matching current route
      *
      * @param string $routeName
      *
      * @return bool.
      *-------------------------------------------------------- */

    if (!function_exists('isCurrentRoute')) {
        function isCurrentRoute($routeName)
        {
            $currentRouteName = \Route::currentRouteName();

            if(!is_array($routeName)) {
                $routeName = [$routeName];
            }

            if(!__isEmpty($routeName)) {
                foreach ($routeName as $route) {
                   if ($currentRouteName === $route) {
                        return true;
                        break;
                    }
                }
            }
            return false;
        }
    }

    /*
      * Format amount in float
      *
      * @param number $amount
      *
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('formatAmount')) {
        function formatAmount($amount)
        {
            return round($amount, 2);
        }
    }

    /*
      * Get no thumb image URL
      *
      * @return string
      *-------------------------------------------------------- */

    if (!function_exists('noThumbImageURL')) {
        function noThumbImageURL()
        {
            return url('/dist/imgs/no_thumb_image.jpg');
        }
    }


    /**
     * Check is any sanbox order
     *
     * @param object $collection
     *
     * @return bool
     *---------------------------------------------------------------- */

    if (!function_exists('checkisAvailabeSandBoxRecords')) {
        function checkisAvailabeSandBoxRecords($purchaseCollection)
        {
            $result = false;

            if (__isEmpty($purchaseCollection)) {
                return $result;
            }

            foreach ($purchaseCollection as $purchase) {
                $paymentMethod = $purchase['payment_method'];

                if ($paymentMethod == 7 // Paypal test order
                        or $paymentMethod == 8) {
                    $result = true;
                }
            }

            return $result;
        }
    }

    /*
      * Get the technical items from tech items
      *
      * @param string   $key
      * @param mixed    $requireKeys
      *
      * @return mixed
      *-------------------------------------------------------- */

    if (!function_exists('configItem')) {
        function configItem($key, $requireKeys = null)
        {
            if (!__isEmpty($requireKeys) and !is_array($requireKeys)) {
                return config('__tech.'.$key.'.'.$requireKeys);
            }

            $geItem = array_get(config('__tech'), $key);

            if (!__isEmpty($requireKeys) and is_array($requireKeys)) {
                return array_intersect_key($geItem, array_flip($requireKeys));
            }
            
            return $geItem;
        }
    }

    /*
      * Get the technical items from tech items
      *
      * @param string   $key
      * @param mixed    $requireKeys
      *
      * @return mixed
      *-------------------------------------------------------- */

    if (!function_exists('slugIt')) {
        function slugIt($title, $separator = '-')
        {
            return str_slug($title, $separator);
        }
    }

    /*
      * Mask Email Id
      *
      * @param string $email
      * @param string $replacement
      * @param number $start
      * @param number $length
      *
      * @return mixed
      *-------------------------------------------------------- */

    if (!function_exists('maskEmailId')) {
        function maskEmailId($email, $replacement = '***', $start = 0, $length = 3)
        {
            if (isDemoForAdmin() and getUserID() === 2 ) {
                return substr_replace($email, $replacement, $start, $length);
            }
            
            return $email;
        }
    }

    /*
    * Get Invoice image url
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('getInvoiceImageUrl')) {
        function getInvoiceImageUrl()
        {
            $invoiceImage = getStoreSettings('invoice_img_media_path');

            if (File::exists($invoiceImage)) {
                return getStoreSettings('invoice_image');
            }
           
            return getStoreSettings('logo_image');
        }
    }

    /*
    * Get selected currency 
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('getSelectedCurrency')) {
        function getSelectedCurrency()
        {
            if (NativeSession::has('currency')) {
                return NativeSession::get('currency');
            }
            
            return getCurrency();
        }
    }

    /*
    * Get selected currency 
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('getSelectedCurrencySymbol')) {
        function getSelectedCurrencySymbol()
        {
            $selectedCurrency = getSelectedCurrency();

            return configItem('currencies.details.'.$selectedCurrency)['symbol'];
        }
    }

	 /*
    * Check if access social account is valid
    *
    * @param string $providerKey
    *
    * @return bool.
    *-------------------------------------------------------- */

    if (!function_exists('getSocialProviderName')) {
        function getSocialProviderName($providerKey)
        {
            if (!__ifIsset($providerKey)) {
                return false;
            }

            $socialLoginDriver = Config('__tech.social_login_driver');

            if (array_key_exists($providerKey, $socialLoginDriver) !== false) {
                return $socialLoginDriver[$providerKey];
            }


            return false;
        }
    }

    /*
    * Check if access social account is valid
    *
    * @param string $providerKey
    *
    * @return bool.
    *-------------------------------------------------------- */

    if (!function_exists('getSocialProviderKey')) {
        function getSocialProviderKey($providerKey)
        {
            if (!__ifIsset($providerKey)) {
                return false;
            }

            $socialLoginDriver = Config('__tech.social_login_driver_keys');

            if (array_key_exists($providerKey, $socialLoginDriver)) {
                return $socialLoginDriver[$providerKey];
            }


            return false;
        }
    }

    /* Function copied from old __Laraware helpers - 24 MAY 2018 by Vinod
      * NOTE: This helper function is deprecated as of 24 JUN 2016 instead use engineReaction of CoreEngine function
      *
      * Send reaction from Engine mostly to Controllers
      *
      * @param Array    $reactionCode  - Reaction from Repo
      * @param Array    $data          - Array of data if needed
      * @param String   $message       - Message if any
      *
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('__engineReaction')) {
        function __engineReaction($reactionCode, $data = null, $message = null)
        {
            if (is_array($reactionCode) === true) {
                $message = $reactionCode[2];
                $data = (is_array($data) and is_array($reactionCode[1]))
                                        ? array_merge($reactionCode[1], $data)
                                        : (empty($reactionCode[1])
                                            ? (empty($data) ? null : $data)
                                            : $reactionCode[1]);

                $reactionCode = $reactionCode[0];
            }

            if (__isValidReactionCode($reactionCode) === true) {
                return [
                    'reaction_code' => (integer) $reactionCode,
                    'data' => $data,
                    'message' => $message,
                ];
            }

            throw new Exception('__engineReaction:: Invalid Reaction Code!!');
        }
    }

    if (!function_exists('__trd')) {
        function __trd($string, $replaceValues = []) {
            return __tr($string, $replaceValues = []);
        }
    }

    /*
    * Dynamic Translitrate
    *
    * @param string $prepend
    * @param string/int $itemId
    * @param string $append    
    *
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('__transliterate')) {
        function __transliterate($entityType, $entityId, $entityKey, $entityString) {
            
            $transliterateRepository = new App\Yantrana\Components\Transliterate\Repositories\TransliterateRepository;

            $transliterateCollectionData = $transliterateRepository->viaCache('cache.locale.transliterate', function() use($transliterateRepository) {

                    $transliterateData = [];
                    $transliterateCollection = $transliterateRepository->fetchAll();

                    // Check if transliterate Collection exist
                    if (!__isEmpty($transliterateCollection)) {
                        foreach ($transliterateCollection as $transliterate) {
                            $eType = $transliterate->entity_type;
                            $eId = $transliterate->entity_id;
                            $eKey = $transliterate->entity_key;
                            $language = $transliterate->language; 
                            $transliterateData["$eType-$eId-$eKey-$language"] = $transliterate->string;
                        }
                    }

                    return $transliterateData;
            });

            $currentLocale = CURRENT_LOCALE;
            return array_get($transliterateCollectionData, "$entityType-$entityId-$entityKey-$currentLocale", $entityString);
        }
    }

    /*
    * Customized GetText string
    *
    * @param string $string
    * @param array $replaceValues
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__tr')) {
        function __tr($string, $replaceValues = [])
        {
            // $string = gettext($string);
            $string = T_gettext($string);

            // Check if replaceValues exist
            if (!empty($replaceValues) and is_array($replaceValues)) {
                foreach ($replaceValues as $replaceKey => $replaceValue) {
                   if(is_numeric($replaceValue)) {
                       $replaceValues[$replaceKey] = formatLocaleNumber($replaceValue);
                   }
                }
                $string = strtr($string, $replaceValues);
            }

            // if numbers found in string change those also.
            $string = preg_replace_callback('!\d+!', function($matches) {
                if (class_exists('NumberFormatter')) {
                    $numberFormatter = new NumberFormatter(
                        CURRENT_LOCALE, NumberFormatter::IGNORE
                    );
                    return $numberFormatter->format($matches[0]);
                }
                
            }, $string);

            unset($matches);

            return $string;
        }
    }

    /* added from __Laraware helpers by vinod 19 JUN 2018
      * Minify and load view
      *
      * @return view
      *---------------------------------------------------------------- */

    if (!function_exists('__loadView')) {
        function __loadView($viewName, $data = [])
        {
            $output = View::make($viewName, $data)->render();

            if (!env('APP_DEBUG', false)) {
                $filters = array(
                    '/(?<!\S)\/\/\s*[^\r\n]*/' => '',  // Remove comments in the form /* */
                    '/\s{2,}/' => ' ', // Shorten multiple white spaces
                    '/(\r?\n)/' => '',  // Collapse new lines
                );

                return preg_replace(
                    array_keys($filters),
                    array_values($filters),
                    $output
                );
            } else {
                $clogSessItemName = '__clog';

                if (isset($_SESSION[$clogSessItemName]) == true and !empty($_SESSION[$clogSessItemName])) {
                    $responseData = [];

                    $responseData['__dd'] = true;
                    $responseData['__clogType'] = 'NonAjax';
                    $responseData[$clogSessItemName] = $clogSessItemName;
                    // set for response
                    $responseData[$clogSessItemName] = $_SESSION[$clogSessItemName];
                    //reset the __clog items in session
                    $_SESSION[$clogSessItemName] = [];

                    $output = $output.'<script>__globals.clog('.json_encode($responseData).')</script>';
                }
            }

            return $output;
        }
    }

    /*
    * Load DataTable Helper 0.2.1 - 03 JUN 2015
    *
    * helper function to load datatable.
    *
    * @param array $data - for request response
    *
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('__dataTable')) {
        function __dataTable($sourceData, $dataFormat = [], $options = [])
        {
            $data = [];

            if (Session::has('additional')) {
                $data['additional'] = Session::get('additional');
            }

            $rawData = $sourceData['data'];

            $enhancedData = [];

            foreach ($rawData as $key) {
                $newDataFormat = [];

                if (!empty($dataFormat)) {
                    foreach ($dataFormat as $dataItemKey => $dataItemValue) {
                        if (is_numeric($dataItemKey)) {
                            $newDataFormat[ $dataItemValue ] = $key[ $dataItemValue ];
                        } elseif (is_callable($dataItemValue)) {
                            $newDataFormat[ $dataItemKey ] = call_user_func($dataItemValue, $key);
                        } else {
                            $newDataFormat[ $dataItemKey ] = $key[ $dataItemValue ];
                        }
                    }
                } else {
                    $newDataFormat = $key;
                }

                $primaryKey = array_key_exists('_id', $key) ? '_id' : 'id';

                $newDataFormat['DT_RowId'] = 'rowid_'.$key[$primaryKey];

                $enhancedData[] = $newDataFormat;
            }

            $dataTablesData = array(
                    'recordsTotal' => $sourceData['total'],
                    'data' => $enhancedData,
                    'recordsFiltered' => $sourceData['total'],
                    'draw' => (int) Request::get('draw'),
                );

            $data['response_token'] = (int) Request::get('fresh');

            $data = array_merge($data, $dataTablesData);

            if (!empty($options)) {
                $data['_options'] = $options;
            }

            // __pr() to print in console
            if (env('APP_DEBUG', false) == true) {
                $prSessItemName = '__pr';

                if (isset($_SESSION[$prSessItemName]) == true and !empty($_SESSION[$prSessItemName])) {
                    $data['__dd'] = true;
                    $data[$prSessItemName] = $prSessItemName;
                    // set for response
                    $data[$prSessItemName] = $_SESSION[$prSessItemName];
                    //reset the __pr items in session
                    $_SESSION[$prSessItemName] = [];
                }

                $clogSessItemName = '__clog';

                if (isset($_SESSION[$clogSessItemName]) == true and !empty($_SESSION[$clogSessItemName])) {
                    $data['__dd'] = true;
                    $data[$clogSessItemName] = $clogSessItemName;
                    // set for response
                    $data[$clogSessItemName] = $_SESSION[$clogSessItemName];
                    //reset the __clog items in session
                    $_SESSION[$clogSessItemName] = [];
                }

                // email view debugging
                if (env('MAIL_VIEW_DEBUG', false) == true) {
                    $testEmailViewSessName = '__emailDebugView';
                    if (isset($_SESSION[$testEmailViewSessName]) == true and !empty($_SESSION[$testEmailViewSessName])) {
                        $data[$testEmailViewSessName] = $_SESSION[$testEmailViewSessName];
                        //reset the testEmailViewSessName items in session
                        $_SESSION[$testEmailViewSessName] = null;
                    }
                }
            }

            unset($enhancedData, $rawData, $sourceData, $dataFormat, $dataTablesData);

            return Response::json($data);
        }
    }    

    /*
    * get Image Slider items
    *
    * @param string $sliderName
    *
    * @return void
    *---------------------------------------------------------------- */

    if (!function_exists('getImageSlider')) {
        function getImageSlider($sliderName)
        {
            $homeSlider = getStoreSettings('home_slider');
            $sliderTemplate = view('includes.slider')->render();

            $owlConfig = '';
            $slideContent = [];
            if (!__isEmpty($homeSlider)) {
                $slides = [];
                $slideContent = [];
                $configArray = [];
                if (!__isEmpty($homeSlider['sliderData'])) {
                    foreach ($homeSlider['sliderData'] as $sliderKey => $sliderValue) {
                        if ($sliderValue['title'] == $sliderName) {
                            $slideImage = '';                            
                            //slides Data Array
                            foreach ($sliderValue['slides'] as $key => $slide) {
                                $bgColor = '282828';
                                $thumbnailImg = getSliderThumbanilUrl($sliderKey, $slide['image']);
                                if (isset($slide['text_color'])) {
                                    $textColor = $slide['text_color'];
                                } else {
                                    $textColor = 'ffffff';
                                }

                                if (isset($slide['bg_color'])) {
                                    $bgColor = $slide['bg_color'];
                                }
                            
                                $captionOne =  __transliterate('slider', $key, 'caption_1', $slide['caption_1']);
                                $captionTwo = __transliterate('slider', $key, 'caption_2', $slide['caption_2']);
                                $captionThree =  __transliterate('slider', $key, 'caption_3', $slide['caption_3']);
                                
                                $captionOneColor = isset($slide['caption_1_color']) ? $slide['caption_1_color'] : $textColor;
                                $captionTwoColor = isset($slide['caption_2_color']) ? $slide['caption_2_color'] : $textColor;

                                $slideImage .= "<div class='lw-owl-item' data-bg-color='$bgColor'><div class='lw-owl-captions'>
                                                        <div class='lw-owl-caption' style='color: #$captionTwoColor'>$captionTwo</div>
                                                        <div class='lw-owl-caption' style='font-size:40px;color: #$captionOneColor'>$captionOne</div>
                                                        <div class='lw-owl-caption'>$captionThree</div>
                                                    </div>
                                                    <div class='lw-owl-item-'><img style='width:550px;' src='$thumbnailImg'></div></div>";
                            
                                $slides = [
                                    '{__sliderKey__}' => $sliderKey,
                                    '{__caption_1__}' => $captionOne,
                                    '{__caption_2__}' => $captionTwo,
                                    '{__caption_3__}' => $captionThree,
                                    '{__image__}'     => $slide['image'],
                                    '{__thumbnailURL__}' => $slideImage,
                                    '{__owlConfig__}' => $owlConfig
                                ];

                                $slideContent = strtr( (string) $sliderTemplate, $slides);
               
                            }

                            $slideConfig = [
                                'auto_play' => (__ifIsset($sliderValue['auto_play']) and $sliderValue['auto_play'] == true) ? true : false,
                                'autoPlayTimeout' => isset($sliderValue['autoPlayTimeout']) ? $sliderValue['autoPlayTimeout'] : 0
                            ];
                           
                            $configArray[$sliderKey] = $slideConfig;
                        }
                    }
                }
                $sliderConfig = config('__tech.slider_configurations', []);               
                config(['__tech.slider_configurations' => array_merge($configArray, $sliderConfig)]);
            }
            
            return $slideContent;
        }
    }

    /**
      * Get the sort by string title
      *
      * @param string $sortBy
      *
      * @return string
      *-------------------------------------------------------- */

    if (!function_exists('sendDynamicMail')) {
        function sendDynamicMail($templateId, $subject, $messageData, $customerEmailOrId = null, $sendNotifyBccMail = null)
        {
        	if (isLoggedIn()) {
	            $userAuthInfo = getUserAuthInfo();
	            $customerEmail = $userAuthInfo['profile']['email'];
	            $customerName  = $userAuthInfo['profile']['full_name'];

	        }
	        // if customer email or id sent
	        if ($customerEmailOrId) {
	            // set it as customer email address
	            $customerEmail = $customerEmailOrId;

	            // if its a user id then find user & get email address of it
	            if (is_numeric($customerEmailOrId)) {
	                $userInfo = App\Yantrana\Components\User\Models\User::where('_id', $customerEmailOrId)->first();

	                $customerEmail = $userInfo->email;
	                $customerName = $userInfo->first_name.' '.$userInfo->last_name;
	            }
	        }

	        if (!$customerEmail) {
	            throw new Exception('Customer Email is required');
	        }

	        if (!isset($customerName)) {
	            $customerName = $customerEmail;
	        }
  
        	$emailConfig = configItem('email_template_view.'.$templateId);
        	$replaceStrings = $emailConfig['replaceString'];
        	$templateName = $emailConfig['template'];

			$emailTemplate = view('dynamic-mail-templates.index', [
								'emailsTemplate' =>'dynamic-mail-templates.'.$templateName, 
							])->render();

			//fetch all configuration data to check email updated or not
			$configurationCollection =  App\Yantrana\Components\Store\Models\Setting::all();

			$templateView = [];
			$subjectData = [];
	       	if (!__isEmpty($configurationCollection)) {
	            foreach ($configurationCollection as $key => $configuration) {
	            	//check email subject exists
	          		if ($configuration->name === $emailConfig['subjectKey']) {
	          			$subjectData = $configuration->value;
	          		}
	          		//check email template exists
	            	if ($configuration->name === $templateId) {
	        			$templateView = $configuration->value;
	        		}
	            }	
	        }

	        //check is email subject updated then use configuration updated email subject
	        if (!__isEmpty($subjectData)) {
	        	$subject = $subjectData;
	        }

	        //check is email updated then use configuration updated email
	        //else use by defualt template
			if (!__isEmpty($templateView)) {
				$emailTemplate = strtr((string)$templateView, $messageData);
			}
			else { 
				$emailTemplate = strtr((string)$emailTemplate, $messageData);
			}

			if (!__isEmpty($subject)) {
				$subject = strtr((string)$subject, $messageData);
			}
            
        	$mailSent = false;
        	try {

        		// Generating email view as html file instead of sending __email.html
		        if (env('MAIL_VIEW_DEBUG', false) == true) {
		             
		            $emailViewToGenerate = fopen(public_path('__email.html'), 'w') or die('Unable to open file!');
		          
		            fwrite($emailViewToGenerate, $emailTemplate);
		            fclose($emailViewToGenerate);
		            config([
		                'app.__emailDebugView' => url('__email.html')
		            ]);

		            return true;
		        }
		        
 				//$mailfrom = configItem('mail_from');

                //check default email setting is false
                if (!getStoreSettings('use_env_default_email_settings')) {
                    $mailFromAddress    = getStoreSettings('mail_from_address');
                    $mailFromName       = getStoreSettings('mail_from_name');
                } else {
                    $mailFromAddress    = env('MAIL_FROM_ADD') ?: getStoreSettings('business_email');
                    $mailFromName       = getStoreSettings('store_name');
                }
    
                //send mail functionality
        		Mail::send([], [], function ($message) use ($mailFromAddress, $mailFromName, $emailTemplate, $subject, $customerEmail, $sendNotifyBccMail)
		        {
		            $message->from($mailFromAddress, $mailFromName);
                    if (isset($sendNotifyBccMail)) {
                        $message->bcc($customerEmail);
                    } else {
                        $message->to($customerEmail);
                    }
		            $message->setBody($emailTemplate, 'text/html');
		            $message->subject($subject);
		            $message->replyTo($mailFromAddress, $mailFromName);

		        });

        		$mailSent = true;

        	} catch (Exception $e) {

        		$mailSent = false;
        	}
  
        	return $mailSent;
        }
    }

    /*
      * Formats ID for Test Case, etc.
      *
      * @param int $rowId  - id of the row
      * @param string $digits  - number of digits
      * @param string $format  - format in which to convert
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('formatEmailTemplate')) {
        function formatEmailTemplate($replaceStr, $email, $emailTemplate)
        {
 			return  str_replace($replaceStr, $email, $emailTemplate);
        }
    }


	/*
      * Formats ID for Test Case, etc.
      *
      * @param int $rowId  - id of the row
      * @param string $digits  - number of digits
      * @param string $format  - format in which to convert
      *
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('sendMailToAdmin')) {
        function sendMailToAdmin($templateId, $subject, $messageData, $email = null, $messageType)
        {
 		 
	        $adminEmails = [
	            1 => getStoreSettings('business_email'),
	            2 => getStoreSettings('contact_email'),
	        ];

	       	$emailConfig = configItem('email_template_view.'.$templateId);
        	$replaceStrings = $emailConfig['replaceString'];
        	$templateName = $emailConfig['template'];

			$emailTemplate = view('dynamic-mail-templates.index', [
								'emailsTemplate' =>'dynamic-mail-templates.'.$templateName, 
							])->render();

			//fetch all configuration data to check email updated or not
			$configurationCollection =  App\Yantrana\Components\Store\Models\Setting::all();

			$templateView = [];
	       	if (!__isEmpty($configurationCollection)) {
	            foreach ($configurationCollection as $configuration) {
	          
	            	if ($configuration->name === $templateId) {
	        			
	        			$templateView = $configuration->value;
	        		}
	            }	
	        }

	        //check is email updated then use configuration updated email
	        //else use by defualt template
			if (!__isEmpty($templateView)) {
				$emailTemplate = strtr((string)$templateView, $messageData);
			}
			else { 
				$emailTemplate = strtr((string)$emailTemplate, $messageData);
			}
            
        	$mailSent = false;
        	$mailfrom = configItem('mail_from');

            //check default email setting is false
            if (!getStoreSettings('use_env_default_email_settings')) {
                $mailFromAddress    = getStoreSettings('mail_from_address');
                $mailFromName       = getStoreSettings('mail_from_name');
            } else {
                $mailFromAddress    = env('MAIL_FROM_ADD') ?: getStoreSettings('business_email');
                $mailFromName       = getStoreSettings('store_name');
            }

        	try {
 
        		Mail::send([], [], function ($message) use ($mailFromAddress, $mailFromName, $email, $emailTemplate, $subject, $adminEmails, $messageType, $messageData)
		        {
		            $message->from($mailFromAddress, $mailFromName);
		            $message->to($adminEmails[$messageType]);
		            $message->setBody($emailTemplate, 'text/html');
		            $message->subject($subject);

		            if (isset($messageData['senderEmail'])) {
		            	
		            	$message->replyTo($subject);
		            }

		        });
        		$mailSent = true;
        	} catch (Exception $e) {
        		$mailSent = false;
        	}
 
        	return $mailSent;
    }
}

/*
  * Conver Razorpay Amount
  *
  * @param string $amount
  *
  * @return string.
  *-------------------------------------------------------- */
if (!function_exists('convertRazorpayAmount')) {
    function convertRazorpayAmount($amount)
    {
    	return ($amount/100);
    }
}

/*
  * Calcualte Base Amount
  *
  * @param string $amount
  *
  * @return string.
  *-------------------------------------------------------- */
if (!function_exists('calculateBaseCurrency')) {
    function calculateBaseCurrency($amount)
    {
        $amount = (float) $amount;
        $baseCurrency = getCurrency();
        $displayCurrency  = getSelectedCurrency();
        $markup = 0;
        if ($displayCurrency != $baseCurrency) {
            $foreignExchangeRates = calculateForeignExchangeRates($amount);
            $displayCurrencyRate = array_get($foreignExchangeRates, 'currencyRates.'.$displayCurrency, 0);
            $baseCurrencyRate = array_get($foreignExchangeRates, 'currencyRates.'.$baseCurrency, 0);

            $calculatedDisplayCurrencyRate = $baseCurrencyRate / $displayCurrencyRate;

            if (!__isEmpty(getStoreSettings('currency_markup'))) {
                $markup = $calculatedDisplayCurrencyRate * (getStoreSettings('currency_markup') / 100);
                return $amount * $calculatedDisplayCurrencyRate + $markup;
            }

            return $amount * $calculatedDisplayCurrencyRate;
        }

        return $amount;
    }
}

/*
  * Show Filter Clear Button
  *
  * @param string $filterFor
  *
  * @return string.
  *-------------------------------------------------------- */
if (!function_exists('isFilterClearButtonShow')) {
    function isFilterClearButtonShow($filterFor = '')
    {
        $queryString = \Request::only(['min_price', 'rating', 'availability']);

        if (!__isEmpty($filterFor)) {
            switch ($filterFor) {
                case 'price':
                    if (!empty($queryString['min_price'])) {
                        return true;
                    }
                    return false;
                    break;
                case 'rating':
                    if (!empty($queryString['rating'])) {
                        return true;
                    }
                    return false;
                    break;
                case 'availability':
                    if (!empty($queryString['availability'])) {
                        return true;
                    }
                    return false;
                    break;
                case 'all':
                    if (!empty($queryString)) {
                        return true;
                    }
                    return false;
                    break;
                default:
                    return false;
            }
        }

        return false;
    }
}