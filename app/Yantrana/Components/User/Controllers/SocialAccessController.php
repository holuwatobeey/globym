<?php

/*
* SocialAccessController.php - Controller file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\User\SocialAccessEngine;
use App\Yantrana\Components\User\Requests\SocialAccessRequest;

use App\Yantrana\Support\CommonPostRequest as Request;

use Socialite;
use Session;

class SocialAccessController extends BaseController
{
    /**
     * @var SocialAccessEngine $socialAccessEngine - SocialAccess Engine
     */
    protected $socialAccessEngine;

    /**
      * Constructor
      *
      * @param SocialAccessEngine $socialAccessEngine - SocialAccess Engine
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct(SocialAccessEngine $socialAccessEngine)
    {
        $this->socialAccessEngine = $socialAccessEngine;
    }


    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     *
     *---------------------------------------------------------------- */
    public function redirectToProvider($provider, Request $request)
    {
        // match key & the provider name like google, facebook
        $providerName = getSocialProviderName($provider);

        if ($providerName === false) {
            abort(404);
        }

        try {
            return Socialite::driver($providerName)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('user.login')->with([
                                'error'   => true,
                                'message' => __tr('Something went wrong, Please contact with administrator.'),
                            ]);
        }
    }
    
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider, Request $request)
    {
        $providerName = getSocialProviderName($provider);

        if ($providerName === false) {
            abort(404);
        }

        $denyRequest = $request->input('error');
        $errorCode   = $request->input('error_code');
        
        // Check app not found and user cancel dialog
        if ((int) $errorCode === 4201) { // User cancel dialog

            return redirect()->route('user.login')->with([
                                'error'   => true,
                                'message' => __tr('App not found please contact administrator'),
                            ]);
        }

        // check the request is deny then redirect user on login page
        if (__ifIsset($denyRequest)
            and $denyRequest === 'access_denied') {
            return redirect()->route('user.login')->with([
                                    'error'   => true,
                                    'message' => __tr('You have denied access to from __provider__', [
                                            '__provider__' => $providerName
                                        ]),
                                ]);
        }
    
        $processReaction = $this->socialAccessEngine->processSocialAccess($providerName);

        if ($processReaction['reaction_code'] === 1) {
            $data = $processReaction['data'];
           
            if (isset($data['intendedUrl'])) {
                return redirect($data['intendedUrl'])
                                ->with([
                                    'success' => true,
                                    'message' => __tr('Welcome, you are logged in successfully'),
                                ]);
            }

            return (isAdmin())

                    ? redirect()->route('manage.app')
                                ->with([
                                    'success' => true,
                                    'message' => __tr('Welcome, you are logged in successfully'),
                                ])

                    : redirect()->route('public.app')
                                ->with([
                                    'success' => true,
                                    'message' => __tr('Welcome, you are logged in successfully'),
                                ]);
        } else {
            return redirect()->route('user.login')
                            ->with([
                                'error' => true,
                                'message' => isset($processReaction['message'])
                                ? $processReaction['message']
                                : __tr('Authentication failed. Please check your  email/password & try again.'),
                            ]);
        }
    }
}
