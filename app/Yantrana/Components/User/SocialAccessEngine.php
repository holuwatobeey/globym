<?php

/*
* SocialAccessEngine.php - Main component file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User;

use App\Yantrana\Core\BaseEngine;

use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\User\Blueprints\SocialAccessEngineBlueprint;

use Socialite;
use Session;

class SocialAccessEngine extends BaseEngine implements SocialAccessEngineBlueprint
{
    /**
     * @var UserRepository $userRepository - User Repository
     */
    protected $userRepository;

    /**
      * Constructor
      *
      * @param UserRepository $userRepository - User Repository
      *
      * @return void
      *-----------------------------------------------------------------------*/

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
    * only user for generate password
    *
    * @param $string
    *
    * @return string
    *---------------------------------------------------------------- */
    protected function randomPassword($string, $length = 4)
    {
        return substr(str_shuffle($string), 0, $length);
    }


    /**
     * Process Add New User Request
     *
     * @param string $provider
     * @param object $collection
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function prepareSocialAccessStoreData($provider, $user)
    {
        $providerType = configItem('social_login_numbers');

        return [
            'fname'      => $user->getName(),
            'lame'  	 => null,
            'account_id' => $user->getId(),
            'email'      => $user->getEmail(),
            'provider'   => $providerType[$provider]
        ];
    }

    /**
    * Process social user login request using user repository & return
    * engine reaction.
    *
    * @param array $input
    *
    * @return array
    *---------------------------------------------------------------- */

    protected function processSocialLogin($userId, $loginFrom)
    {
        $verifiedUser = $this->userRepository->fetchByID($userId);
       
        if (!__ifIsset($verifiedUser)) {
            return $this->engineReaction(2);
        }

        if ($verifiedUser->status == 12) {
            return $this->engineReaction(2, null, __tr('Your account currently non-activated, Please Contact to administrator.'));
        } elseif ($verifiedUser->status == 5) {
            return $this->engineReaction(2, null, __tr('Your email address is currently deleted, Please Contact to administrator.'));
        } elseif ($verifiedUser->status == 2) {
            return $this->engineReaction(2, null, __tr('Your email address is currently inactive, Please Contact to administrator.'));
        }

        // Check if user authenticated
        if ($logged = $this->userRepository->loginUsingId($userId)) {

            $intendedUrl =  Session::has('intendedUrl')
                                ? Session::get('intendedUrl')
                                : route('home.page');

            // check if the login form shopping Cart
            if (__ifIsset($loginFrom)) {
                $intendedUrl = $loginFrom;
            }

            if (Session::has('from')) {
                Session::remove('from');
            }

            return $this->engineReaction(1, [
                'auth_info'         => getUserAuthInfo(),
                'intendedUrl'       => $intendedUrl
            ]);
        }

        return $this->engineReaction(2);
    }

    /**
     * Process of login social account  or create new user
     *
     * @param string $provider
     *
     * @return reponse
     *---------------------------------------------------------------- */
    public function processSocialAccess($provider)
    {
        try {
            $social = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return $this->engineReaction(18, null, __tr('Something is wrong form social account, Please contact with administrator.'));
        }
      
        // check the record is empty
        if (__isEmpty($social)) {
            return $this->engineReaction(2);
        }

        // prepare data for store
        $socialUser = $this->prepareSocialAccessStoreData($provider, $social);
        $storeName = __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') );

        if (__isEmpty($socialUser['email'])) {
            return $this->engineReaction(18, null, __tr('The email is required for __title__ system.', ['__title__' => $storeName ]));
        }

        $accountId = $socialUser['account_id'];

        $isExistsAccount = $this->userRepository->checkAccountId($accountId);

        $loginFrom = Session::get('from');

        // if email available then
        if (__ifIsset($socialUser['email'])) {

            $verifiedUser = $this->userRepository->checkEmail($socialUser['email']);
        
            // When the email already exists
            if (__ifIsset($verifiedUser)) {
                return $this->processSocialLogin($verifiedUser->id, $loginFrom);
            }

        } elseif ($isExistsAccount) { // if already exists registered id

            return $this->processSocialLogin($isExistsAccount->users_id, $loginFrom);
        }
        
        // This function call when the user totally new for system
        if ($storedUserId = $this->userRepository->storeSocialUser($socialUser)) {
            return $this->processSocialLogin($storedUserId, $loginFrom);
        }

        return $this->engineReaction(2);
    }
}
