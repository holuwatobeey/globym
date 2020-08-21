<?php

namespace App\Yantrana\Middleware;

use Closure;
use Session;
use URL;
use Illuminate\Contracts\Auth\Guard;
use Auth;

class AuthenticateMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {

            if (getStoreSettings('enable_guest_order')
                and getStoreSettings('register_new_user')) {

                $route = \Request::route()->getName();

                $allowedRoutes = [
                    'order.summary.view',
                    'order.summary.details',
                    'order.coupon.apply',
                    'order.process',
                    'order.paypal.checkout',
                    'order.stripe.checkout',
                    'order.guest_order_success',
                    'order.check.user_email'
                ];

                if (in_array($route, $allowedRoutes)) {
                    return $next($request);
                }                
            }

            if ($request->ajax()) {
                return __apiResponse([
                    'message' => __('Please login to complete request.'),
                    'auth_info' => getUserAuthInfo(9),
                ], 9);
            }

            Session::put('intendedUrl', URL::current());

            return redirect()->route('user.login')
                             ->with([
                                'error' => true,
                                'message' => __('Please login to complete request.'),
                            ]);
        }

        // if user not active
        if (Auth::user()->status !== 1) {
            if ($request->ajax()) {
                return __apiResponse([
                                'message' => __('Unauthorized.'),
                                'auth_info' => getUserAuthInfo(11),
                            ], 11);
            }

            return redirect()->route('home.page')
                         ->with([
                            'error' => true,
                            'message' => __('Unauthorized.'),
                        ]);
        }

        return $next($request);
    }
}
