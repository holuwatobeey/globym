<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use YesAuthority;

class YesAuthorityCheckpostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (! Auth::guard($guard)->check() or YesAuthority::check() === false) {
        //     return redirect('/');
        // }

        $authority = YesAuthority::withDetails()->check();

        if (($authority->is_access() === false) || (Auth::user()->status !== 1)) {

            if ($authority->response_code() === 403) { // Authentication Required
				
				if ($request->ajax()) {

                    return __apiResponse([
                                'message' => __tr('Please login to complete request.'),
                                'auth_info' => getUserAuthInfo(9),
                            ], 9);
                }

                return redirect()->route('user.login')
                             ->with([
                                'error' => true,	
                                'message' => __tr('Please login to complete request.'),
                            ]);
            }
			
			// When it loggedIn But not permission to access route
            if ($authority->response_code() === 401) { // Unauthorized

                if ($request->ajax()) {

                    return __apiResponse([
                                    'message' => __tr('Unauthorized Access.'),
                                    'auth_info' => getUserAuthInfo(11),
                                ], 11);
                }
            }

            return redirect('/')
                         ->with([
                            'error' => true,
                            'message' => __tr('Unauthorized.'),
                        ]);

            // return redirect()->route('manage.app')
            //              ->with([
            //                 'error' => true,
            //                 'message' => __tr('Unauthorized.'),
            //             ]);
        }

        return $next($request);

        /*
        // Detailed handling
        if (Auth::guard($guard)->check()) {            
             $authority  = YesAuthority::withDetails()->check();

            if($authority->isAccess() === true) {
                return $next($request);
             }
        }        
        return redirect('/home');
        */

    }
}