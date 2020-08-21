<?php

namespace App\Yantrana\Core;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use YesSecurity;
use YesAuthority;
use JavaScript;
use NativeSession;
use App\Yantrana\Components\Store\ManageStoreEngine;

class BaseController extends Controller
{
    /**
     * @var ManageStoreEngine - ManageStore Engine
     */
    protected $manageStoreEngine;

    /**
     * @var customPages - customPages
     */
    protected $customPages;

    /**
     * Constructor.
     *
     * @param ManageStoreEngine $manageStoreEngine - ManageStore Engine
     *-----------------------------------------------------------------------*/
    public function __construct()
    {
        $this->middleware('guest');
        $this->manageStoreEngine = $manageStoreEngine;
        $this->customPages = config('__tech.custom_pages');
    }

    /**
     * Prepare data for clideside.
     *
     * @return Response
     */
    protected function preparePublicForBrowser()
    {
        // get all application routes.
        $routeCollection = Route::getRoutes();
        $routes = [];
        $index = 1;

        // if routes in application
        if (!empty($routeCollection)) {
            foreach ($routeCollection as $route) {
                if ($route->getName()) {
                    $routes[ $route->getName() ] = $route->uri();
                } else {
                    $routes[ 'unnamed_'.$index ] = $route->uri();
                }

                ++$index;
            }
        }

        $config = Config('__tech');
        $themeColors = $config['theme_colors'];
        $themeColors = array_add($themeColors, 'default', 'b9002f');

        return [
            '__appImmutables' => [
                'public_encryption_token' => YesSecurity::getPublicRsaKey(),
                'form_security_id' => YesSecurity::getFormSecurityID(),
                'recaptcha_site_key'  => configItem('recaptcha.site_key'), 
                'routes' => $routes,
                'static_assets' => [
                    'vendor_css' => __yesset('dist/css/vendorlibs*.css'),
                    'application_css' => __yesset('dist/css/application*.css'),
                    'public_css' => __yesset('dist/css/vendorlibs-public*.css'),
                    'css_style' => route('css_style')
                ],
                'messages' => [
                    'validation' => trans('validation'),
                    'js_string' => trans('js-string'),
                ],
                'auth_info' => getUserAuthInfo(),
                'config' => [
                    'currency_format' => [
                        'raw'   => getStoreSettings('currency_format'),
                        'full'  => priceFormat('__LABEL__', true, true),
                        'short' => priceFormat('__LABEL__', true)
                    ],
                    'theme_colors' => $themeColors
                ],
                'publicApp' => \Request::route()->getName() != 'manage.app' ? true : false,
                'availableRoutes' => YesAuthority::availableRoutes(),
            ],
            'appConfig' => [
                'debug' => env('APP_DEBUG', false),
                'appBaseURL' => asset(''),
            ]
        ];
    }

    /**
     * Prepare data for clideside.
     *
     * @return Response
     */
    protected function prepareForBrowser()
    {
        // get all application routes.
        $routeCollection = Route::getRoutes();
        $routes = [];
        $index = 1;

        // if routes in application
        if (!empty($routeCollection)) {
            foreach ($routeCollection as $route) {
                if ($route->getName()) {
                    $routes[ $route->getName() ] = $route->uri();
                } else {
                    $routes[ 'unnamed_'.$index ] = $route->uri();
                }

                ++$index;
            }
        }

        $config = Config('__tech');

        return [
            '__appImmutables' => [
                'public_encryption_token' => YesSecurity::getPublicRsaKey(),
                'form_security_id' => YesSecurity::getFormSecurityID(),
                'recaptcha_site_key'  => configItem('recaptcha.site_key'), 
                'routes' => $routes,
                'static_assets' => [
                    'vendor_css' => __yesset('dist/css/vendorlibs*.css'),
                    'application_css' => __yesset('dist/css/application*.css'),
                    'public_css' => __yesset('dist/css/vendorlibs-public*.css'),
                    'css_style' => route('css_style')
                ],
                'messages' => [
                    'validation' => trans('validation'),
                    'js_string' => trans('js-string'),
                ],
                'ckeditor' => [
                    'filebrowserImageBrowseUrl' => route('file_manager').'?type=Images',
                    'filebrowserImageUploadUrl' => route('file_manager.upload').'?type=Images&_token='.csrf_token(),
                    'filebrowserBrowseUrl' => route('file_manager').'?type=Files',
                    'filebrowserUploadUrl' => route('file_manager.upload').'?type=Files&_token='.csrf_token(),
                ],
                'auth_info' => getUserAuthInfo(),
                'config' => [
                    'currency_format' => [
                        'raw'   => getStoreSettings('currency_format'),
                        'full'  => priceFormat('__LABEL__', true, true),
                        'short' => priceFormat('__LABEL__', true)
                    ]
                ],
                'publicApp' => \Request::route()->getName() != 'manage.app' ? true : false,
                'availableRoutes' => YesAuthority::availableRoutes(),
            ],
            'appConfig' => [
                'debug' => env('APP_DEBUG', false),
                'appBaseURL' => asset(''),
            ],
        ];
    }

    public function loadPublicView($viewName, $data = [])
    {
        $browserData = $this->preparePublicForBrowser();

        JavaScript::put($browserData);

        $output = view('public-master', $data)->nest('pageRequested', $viewName, $data)->render();

        if (!env('APP_DEBUG', false)) {
            $filters = array(
                '/<!--([^\[|(<!)].*)/' => '',  // Remove HTML Comments (breaks with HTML5 Boilerplate)
                '/(?<!\S)\/\/\s*[^\r\n]*/' => '',  // Remove comments in the form /* */
                '/\s{2,}/' => ' ', // Shorten multiple white spaces
                '/(\r?\n)/' => '',  // Collapse new lines
            );

            return preg_replace(
                    array_keys($filters),
                    array_values($filters),
                    $output
                );
        } else { // for clog

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

    public function loadMailView($viewName, $data = [])
    {
        return view('emails.index', $data)->nest('pageMailRequested', $viewName, $data);
    }
}
