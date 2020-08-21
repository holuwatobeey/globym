<?php
/*
* UserEngine.php - Main component file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User;

use Auth;
use Hash;
use YesSecurity;
use YesAuthority;
use Session;
use Carbon\Carbon;
use App\Yantrana\Support\MailService;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\User\Blueprints\UserEngineBlueprint;
use Breadcrumb;
use Socialite;

class UserEngine implements UserEngineBlueprint
{
    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * Constructor.
     *
     * @param UserRepository  $userRepository  - User Repository
     * @param MailService     $mailService     - Mail Service
     * @param OrderRepository $orderRepository - Order Repository
     *-----------------------------------------------------------------------*/
    public function __construct(UserRepository $userRepository,
        MailService $mailService,
        OrderRepository $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Prepare users list.
     *
     * @param number $status
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareUsersList($status)
    {
        return $this->userRepository->fetchUsers($status);
    }

    /**
     * Show captcha.
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function showCaptcha()
    {
        // Check if count greater than 5
        if ($this->userRepository->fetchLoginAttemptsCount() >= getStoreSettings('show_captcha')) {
            return true;
        }

        return false;
    }

    /**
     * Prepare login attempts for this client ip.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareLoginAttempts()
    {
        $showCaptcha = false;
        $site_key = configItem('recaptcha.site_key');

        // Check if count exist
        if ($this->showCaptcha() && getStoreSettings('enable_recaptcha') == true && !__isEmpty($site_key)) {
            $showCaptcha = true;
        } else if ($this->showCaptcha() && getStoreSettings('enable_recaptcha') == false ) {
            $showCaptcha = true;
        }

        return __engineReaction(1, [
            'show_captcha'  => $showCaptcha,
            'site_key'      => $site_key,
            'selectUser'    => configItem('demo_user_login_credential')
        ]);
    }

    /**
     * Prepare login attempts for this client ip.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareRegisterSupportData()
    {
        $site_key = configItem('recaptcha.site_key');

        return __engineReaction(1, [
            'site_key'      => $site_key
        ]);
    }

    /**
     * Process user login request using user repository & return
     * engine reaction.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    /*public function processLogin($input)
    {
        // Check if user authenticated
        if ($this->userRepository->login($input)) {
            return __engineReaction(1, [
             'auth_info' => getUserAuthInfo(),
            // 'redirect_intended' 		=> Session::get('redirect_intended'),
             'intendedUrl' => Session::get('intendedUrl'),
            // 'redirect_intended_order' 	=> Session::get('redirect_intended_order_id')
            ]);
        }

        $showCaptcha = false;

        // Check if count exist
        if ($this->showCaptcha()) {
            $showCaptcha = true;
        }

        return __engineReaction(2, ['show_captcha' => $showCaptcha]);
    }*/

    /**
     * Process user login request using user repository & return
     * engine reaction.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processLogin($input, $loginType = 1)
    {
        if (__ifIsset($input['email'])) {
            $varifyEmail = $this->userRepository->varifyEmail($input['email']);

            // check the email is non-active
            if ($varifyEmail) {
                return __engineReaction(2, ['isInActive' => true]);
            }
        }
         
        // Check if user authenticated
        if ($logged = $this->userRepository->login($input, $loginType)) {
            return __engineReaction(1, [
             'auth_info' => getUserAuthInfo(), /*,
             'redirect_intended'        => Session::get('redirect_intended'),*/
             'intendedUrl' => Session::get('intendedUrl'), /*,
             'redirect_intended_order'  => Session::get('redirect_intended_order_id')*/
            ]);
        }

        $showCaptcha = false;

        // Check if count exist
        if ($this->showCaptcha()) {
            $showCaptcha = true;
        }

        return __engineReaction(2, ['show_captcha' => $showCaptcha]);
    }

    /**
     * Process user logout action.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processLogout()
    {
        if (Session::has('intendedUrl')) {
            Session::forget('intendedUrl');
        }

        Auth::logout(); // logout user
        return __engineReaction(1, ['auth_info' => getUserAuthInfo()]);
    }

    /**
     * Process forgot password request based on passed email address &
     * send password reminder on enter email address.
     *
     * @param string $email
     *
     * @return array
     *---------------------------------------------------------------- */
    public function sendPasswordReminder($email)
    {
        $user = $this->userRepository
                     ->fetchActiveUserByEmail($email, true);

        // Check if user record exist
        if (empty($user)) {
            return __engineReaction(2);
        }

        // Delete old password reminder for this user
        $this->userRepository->deleteOldPasswordReminder($email);

        $token = YesSecurity::generateUid();

        // Check for if password reminder added
        if (!$this->userRepository->storePasswordReminder($email, $token)) {
            return __engineReaction(2);
        }

        $messageData = [
            '{__firstName__}' => $user->fname,
            '{__lastName__}' => $user->lname,
            '{__email__}' => $email,
            '{__fullName__}' => $user->fname.' '.$user->lname,
            '{__expirationTime__}' => config('__tech.account.password_reminder_expiry'),
            '{__userId__}' => $user->id,
            '{__token__}' => $token,
            '{__tokenUrl__}' => route('user.reset_password', ['reminderToken' => $token])
        ];

        // if reminder mail has been sent
        // if ($this->mailService->notifyCustomer('Password Reminder', 'account.password-reminder', $messageData, $email)) {
        //     return __engineReaction(1); // success reaction
        // }

        $emailTemplateData = configItem('email_template_view', 'password_reminder_email');
        // if reminder mail has been sent	
        if (sendDynamicMail('password_reminder_email', $emailTemplateData['emailSubject'], $messageData, $user->email) ) {
			return __engineReaction(1); // success reaction
		}

        return __engineReaction(2); // error reaction
    }

    /**
     * Process reset password request.
     *
     * @param array  $input
     * @param string $reminderToken
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processResetPassword($input, $reminderToken)
    {
        $email = $input['email'];

        $count = $this->userRepository
                       ->fetchPasswordReminderCount($reminderToken, $email);

        // Check if reminder count not exist on 0
        if (!$count > 0) {
            return __engineReaction(18);
        }

        $user = $this->userRepository->fetchActiveUserByEmail($email);

        // Check if user record exist
        if (empty($user)) {
            return __engineReaction(18);
        }

        // Check if user password updated
        if ($this->userRepository
                 ->resetPassword($user, $input['password'])) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Process user update password request.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdatePassword($inputData)
    {
        $user = Auth::user();

        // Check if logged in user password matched with entered password
        if (!Hash::check($inputData['current_password'], $user->password)) {
            return __engineReaction(3);
        }

        // Check if user password updated
        if ($this->userRepository->updatePassword($user, $inputData['new_password'])) {
            $getRoute = [];

            $getRoute = ['passwordRoute' => route('user.change_password')];

            return __engineReaction(1, $getRoute);
        }

        return __engineReaction(14);
    }

    /**
     * Get temp email to user.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getChangeEmailDetails()
    {
        $tempMailData = $this->userRepository->fetchChangeEmailDetails();
        
        $newEmail = false;
        $formattedDate = $humanReadableDate = '';

        // Check if new email is exist
        if (!__isEmpty($tempMailData)) {
            $newEmail = $tempMailData->new_email;
            $formattedDate = formatStoreDateTime($tempMailData->created_at);
            $humanReadableDate = Carbon::createFromFormat('Y-m-d H:i:s', $tempMailData->created_at)->diffForHumans();
        }
        
        return __engineReaction(1, [
            'newEmail' => $newEmail,
            'formattedDate' => $formattedDate,
            'humanReadableDate' => $humanReadableDate,
        ]);
    }

    /**
     * Send new email activation reminder.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function sendNewEmailActivationReminder($inputData)
    {
        $user = Auth::user();

        // Check if user entered correct password or not
        if (!Hash::check($inputData['current_password'], $user->password)) {
            return __engineReaction(3, [], __tr('Please check your password.'));
        }

        // delete olde new email request
        $this->userRepository->deleteOldEmailChangeRequest();

        $newEmail = $inputData['new_email'];

        $activationRequired = getStoreSettings('activation_required_for_change_email');

        // Check if activation required for change email then send activation message
        if (is_null($activationRequired) or $activationRequired == 1) {
            $activationKey = YesSecurity::generateUid();

            // Check for if new email activation store
            if (!$this->userRepository
                      ->storeNewEmailReminder($newEmail, $activationKey)) {
                return __engineReaction(2, [], __tr('Email not send.'));
            }

            // prepare data for email
            $messageData = [
                '{__firstName__}' => $user->fname,
                '{__lastName__}' => $user->lname,
                '{__email__}' => $newEmail,
                '{__fullName__}' => $user->fname.' '.$user->lname,
                '{__expirationTime__}' => config('__tech.account.change_email_expiry'),
                '{__userID__}' => $user->id,
                '{__activationKey__}' => $activationKey,
                '{__activationKeyUrl__}'	=> route('user.new_email.activation', ['activationKey' => $activationKey, 'userID' => $user->id] )
            ];

            // Check if activation link send
            // if ($this->mailService->notifyCustomer('New Email Activation', 'account.new-email-activation', $messageData, $newEmail)) {
            //     return __engineReaction(1, ['activationRequired' => true], __tr('New email activation link has been sent to your new email address, please check your email.')); // success reaction
            // }

            $emailTemplateData = configItem('email_template_view', 'new_email_activation_email');
        	// Check if activation link send
	        if (sendDynamicMail('new_email_activation_email', $emailTemplateData['emailSubject'], $messageData, $newEmail) ) {
				return __engineReaction(1, ['activationRequired' => true], __tr('New email activation link has been sent to your new email address, please check your email.')); // success reaction
			}

            return __engineReaction(2, [], __tr('Email not send.')); // error reaction
        }

        // Check if user email updated
        if ($this->userRepository->updateEmail($newEmail)) {
            return __engineReaction(1, ['activationRequired' => false], __tr('Your email address updated successfully.'));
        }

        return __engineReaction(2, [], __tr('Your email address not updated.'));
    }

    /**
     * Activate new email.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return array
     *---------------------------------------------------------------- */
    public function newEmailActivation($userID, $activationKey)
    {
        // Fetch temporary email
        $tempEmail = $this->userRepository
                           ->fetchTempEmail($userID, $activationKey);

        // Check if temp email exist for this activation key
        if (empty($tempEmail)) {
            return __engineReaction(18);
        }

        // Check if user email updated
        if ($this->userRepository->updateEmail($tempEmail->new_email)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Process user register request.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processRegister($input)
    {
        if (!getStoreSettings('register_new_user')) {
            return __engineReaction(2, null, __tr('Register new user has been deactivated, please contact to admin.'));
        }

        // get email of deleted user
        $usersEmail = $this->userRepository->fetchEmailOfUsers()->toArray();

        $emailCollection = [];

        // Delete never activated users old than set time in config account activation hours
        $this->userRepository->deleteNonActicatedUser();

        // push email into array
        foreach ($usersEmail as $key => $email) {
            if (($email['email'] == strtolower($input['email'])) and ($email['status'] === 2)) {
                return __engineReaction(3, ['isInActive' => true]);
            }

            $emailCollection[] = $email['email'];
        }

        // check if email already exist
        if (in_array(strtolower($input['email']), $emailCollection, true) == true) {
            return __engineReaction(3, [], __tr('Your account has been already exist, please contact system administrator.'));
        }

        $newUser = $this->userRepository->storeNewUser($input);

        // Check if user stored
        if (empty($newUser)) {
            return __engineReaction(2, [], __tr('Registration process failed.'));
        }

        // Check if activation required for new user then send activation message
        if (getStoreSettings('activation_required_for_new_user')) {

            // prepare data for email view
            $messageData = [
                '{__firstName__}'      => $newUser->fname,
                '{__lastName__}'       => $newUser->lname,
                '{__email__}'          => $newUser->email,
                '{__fullName__}'       => $newUser->fname.' '.$newUser->lname,
                '{__expirationTime__}' => configItem('account.activation_expiry'),
                '{__userID__}'         => $newUser->id,
                '{__activationKey__}'  => $newUser->remember_token,
                '{__activationKeyUrl__}'	=> route('user.account.activation', ['activationKey' => $newUser->remember_token, 'userID' => $newUser->id] )
            ];
            
            // $this->mailService->notifyCustomer('Account Activation', 'account.account-activation', $messageData, $newUser->email);

            $emailTemplateData = configItem('email_template_view', 'account_activation_email');
        	
	        if (sendDynamicMail('account_activation_email', $emailTemplateData['emailSubject'], $messageData, $newUser->email) ) {
				return __engineReaction(1, ['activationRequired' => true], __tr('User register successfully.')); //success reaction
			}
        }

        return __engineReaction(1, ['activationRequired' => false], __tr('Your registration process completed successfully. Please logged in for uses.')); // success reaction
    }

    /**
     * User account activation.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAccountActivation($userID, $activationKey)
    {
        $neverActivatedUser = $this->userRepository
                                    ->fetchNeverActivatedUser(
                                        $userID,
                                        $activationKey
                                    );

        // Check if never activated user exist or not
        if (empty($neverActivatedUser)) {
            return __engineReaction(18);
        }

        // Check if user activated successfully
        if ($this->userRepository->activateUser($neverActivatedUser)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare user profile information.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProfileDetails()
    {
        $userProfile = $this->userRepository->fetchProfile();

        return __engineReaction(1, ['profile' => $userProfile]);
    }

    /**
     * create profile update breadcrumb.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function breadcrumbGenerate($breadcrumbType)
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
     * create profile update breadcrumb.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getChangeRequestedEmail()
    {
        return __engineReaction(1, $this->userRepository->fetchChangeEmailRequested());
    }

    /**
     * Update user profile & return response.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateProfile($input)
    {
        // Check if profile updated
        if ($this->userRepository->updateProfile($input)) {
            return __engineReaction(1, ['auth_info' => getUserAuthInfo()]);
        }

        return __engineReaction(14);
    }

    /**
     * Process user delete request.
     *
     * @param number $userID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUserDelete($userID)
    {
        $user = $this->userRepository->fetchByID($userID);

        // Check if user exist or we are trying to delete admin user
        if (empty($user) or $user->role === 1) {
            return __engineReaction(18); // not exist record
        }

        // Check if user delete successfully
        if ($this->userRepository->delete($user)) {
            return __engineReaction(1, [
                'message' => __tr('__fullName__ user deleted successfully.', [
                                    '__fullName__' => $user->fname.' '.$user->lname,
                                    ]
                                ),
                ]);
        }

        return __engineReaction(2);
    }

    /**
     * Process user restore request.
     *
     * @param number $userID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUserRestore($userID)
    {
        $user = $this->userRepository->fetchByID($userID);

        // Check if user records exist
        if (empty($user) or $user->role === 1) {
            return __engineReaction(18); // not exist record
        }

        // Check if user restore successfully
        if ($this->userRepository->restore($user)) {
            return __engineReaction(1, [
                'message' => __tr('__name__ user restore successfully.', [
                                    '__name__' => $user->fname.' '.$user->lname,
                                    ]
                                ),
                ]);
        }

        return __engineReaction(2);
    }

    /**
     * Varify password reminder token.
     *
     * @param string $reminderToken
     *
     * @return array
     *---------------------------------------------------------------- */
    public function varifyPasswordReminderToken($reminderToken)
    {
        $count = $this->userRepository->fetchPasswordReminderCount($reminderToken);
                    
        // Check if reminder count not exist on 0
        if (!$count > 0) {
            return __engineReaction(18, __tr('Token mismatch.'));
        }

        return __engineReaction(1);
    }

    /**
     * Process user contact request.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processContact($inputData)
    {
        // mail subject
        $subject  = $inputData['subject'];
        $formType = 1;

        // if form type dialog then add order word in subject message
        if (array_has($inputData, 'formType') and $inputData['formType'] == 2) {
            $subject = $inputData['subject'].' Order';

            $formType = $inputData['formType'];
        }

        $orderDetailsUrl = '';
        $orderUID = '';

        // Check if order UID is not empty
        if (!empty($inputData['orderUID'])) {
            $orderUID = $inputData['orderUID'];

            $orderDetailsUrl = route('my_order.details', $orderUID);
        }

        $messageData = [
            '{__senderName__}' => $inputData['fullName'],
            '{__mailText__}'       => $inputData['message'],
            '{__senderEmail__}'     => $inputData['email'],
            '{__formType__}'        => $formType,
            '{__orderUID__}' => $orderUID,
            'isloggedIn' => isLoggedIn(),
        ];

        if ($formType == 2) {
        	$messageData['{__orderDetailsUrl__}'] = "for <a href=".$orderDetailsUrl.">".$orderUID."</a>";
        } else {
        	$messageData['{__orderDetailsUrl__}'] = '';
        }

        // if ($this->mailService
        //          ->notifyAdmin($subject, 'contact', $messageData, 2)) {
        //     return __engineReaction(1); // success reaction
        // }

        $emailTemplateData = configItem('email_template_view', 'contact_admin_email');
        
        if (sendMailToAdmin('contact_admin_email', $subject, $messageData, null, 2) ) {
			return __engineReaction(1); // success reaction
		}

        return __engineReaction(2); // error reaction
    }

    /**
     * resend activation email link.
     *
     * @param array $input
     *---------------------------------------------------------------- */
    public function resendActivationEmail($input)
    {
        // Delete never activated users old than 48 hours
        $this->userRepository->deleteNonActicatedUser();

        $email = $input['email'];

        $activeUser = $this->userRepository
                             ->fetchActiveUserByEmail($email);

        // Check if is active user
        if (!empty($activeUser)) {
            return __engineReaction(3);
        }

        $user = $this->userRepository
                       ->getNonActicatedUserByEmail($email);

        // Check if user empty
        if (__isEmpty($user)) {
            return __engineReaction(2, null, __tr('You entered email address not availabe in system.')); // error reaction
        }

        $messageData = [
            '{__firstName__}' => $user->fname,
            '{__lastName__}' => $user->lname,
            '{__email__}' => $email,
            '{__fullName__}' => $user->fname.' '.$user->lname,
            '{__expirationTime__}' => config('__tech.account.change_email_expiry'),
            '{__userID__}' => $user->id,
            '{__activationKey__}' => $user->remember_token,
            '{__activationKeyUrl__}'	=> route('user.account.activation', ['activationKey' => $user->remember_token, 'userID' => $user->id] )
        ];

        // if ($this->mailService
        //          ->notifyCustomer('Account Activation', 'account.account-activation', $messageData, $email)) {
        //     return __engineReaction(1); // success reaction
        // }

        $emailTemplateData = configItem('email_template_view', 'resend_activation_mail');
        	
        if (sendDynamicMail('resend_activation_mail', $emailTemplateData['emailSubject'], $messageData, $user->email) ) {
			return __engineReaction(1); // success reaction
		}

        return __engineReaction(2); // error reaction
    }

    /**
     * Process change password by admin.
     *
     * @param number $userID
     * @param array  $input
     *---------------------------------------------------------------- */
    public function processChangePassword($userID, $input)
    {
        $user = $this->userRepository->fetchByID($userID);

        // check if user exist
        if (__isEmpty($user)) {
            return __engineReaction(18);
        }

        // Check if user password updated
        if ($this->userRepository->updatePassword($user, $input['new_password'])) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare details for user.
     *
     * @param number $userID
     *
     * @return engine reaction
     *---------------------------------------------------------------- */
    public function prepareUserDetails($userID)
    {
        // Get user details
        $userDetails = $this->userRepository->fetchByID($userID);

        if (__isEmpty($userDetails)) {
            return __engineReaction(18);
        }

        // Get order details related to the user
        $orderDetails = $this->orderRepository
                             ->fetchOrderByUserID($userID);

        // Prepare user details array
        $userData = [
            'fullName' 	=> $userDetails->fname.' '.$userDetails->lname,
            'email' 	=> maskEmailId($userDetails->email),
            'lastLogin' => ($userDetails->last_login)
                                ? formatStoreDateTime($userDetails->last_login)
                                : '',
            'lastIp' 	=> ($userDetails->last_ip)
                                ? $userDetails->last_ip
                                : '',
            'creationDate' 	=> formatStoreDateTime($userDetails->created_at),
            'lastOrder' 	=> ($orderDetails['lastOrder']['created_at'])
                                ? formatStoreDateTime($orderDetails['lastOrder']['created_at'])
                                : '',
            'lastOrderUID' 	=> $orderDetails['lastOrder']['order_uid'],
            'totalOrder' 	=> $orderDetails['orderCount'],
        ];

        return __engineReaction(1, $userData);
    }

    /**
    * send mail to the user
    *
    * @param array $input
    *
    * @return void
    *---------------------------------------------------------------- */
    
    public function prepareInfo($userId)
    {
        $user = $this->userRepository->fetchByID($userId);

        if (__isEmpty($user)) {
            return __engineReaction(18, __tr('User does not exist.'));
        }

        return __engineReaction(1, [
                'fullName' => $user->fname.' '. $user->lname,
                'email'    => maskEmailId($user->email),
                'id'       => $user->id
            ]);
    }

    /**
    * send mail to the user
    *
    * @param array $input
    *
    * @return void
    *---------------------------------------------------------------- */
    
    public function userContactProcess($inputData)
    {
        // mail subject
        $subject = $inputData['subject'];

        $messageData = [
            '{__fullName__}'     	=> $inputData['fullName'],
            '{__mailText__}'       	=> $inputData['message'],
            '{__senderEmail__}'    	=> $inputData['email'],
            '{__emailMessage__}'   	=> $inputData['message']
        ];

        // if ($this->mailService
        //          ->notifyToUser($subject, 'account.contact', $messageData, $inputData['email'])) {
        //     return __engineReaction(1); // success reaction
        // }
  
        $emailTemplateData = configItem('email_template_view', 'contact_email_to_user');
        // if reminder mail has been sent	
        if (sendDynamicMail('contact_email_to_user', $inputData['subject'], $messageData, $inputData['email']) ) {
			return __engineReaction(1); // success reaction
		}


        return __engineReaction(2); // error reaction
    }


    /**
     * Process Add New User Request
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAdd($input)
    {
        // Check if user is added
        if ($this->userRepository->store($input)) {
            return __engineReaction(1, null, __tr('New user added successfully.'));
        }

        return __engineReaction(2, null, __tr('User not added.'));
    }

    /*
     * Prepare user Permissions.
     *
     * @param int $userId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareUserPermissions($userId)
     {
        $userData = $this->userRepository->fetchUser($userId);
        $roleId   = $userData->user_roles__id;

        // check if user is exist
        if (__isEmpty($userData)) {
            return __engineReaction(2, null, __tr('User Not Found.'));
        }

        $userRole = $this->userRepository->fetchUserRoles($userData->role);

        $userRoleTitle = '';
        // check if user is exist
        if (!__isEmpty($userRole)) {
            $userRoleTitle = $userRole->title;
        }

        $permissions            = [];

        // Get User permissions zones with details
        $userPermissions    = YesAuthority::checkUpto('DB_USER')
                                          ->withDetails()
                                          ->getZones($userId);
                                
        // Check if user permissions exist
        if (!__isEmpty($userPermissions)) {
            foreach ($userPermissions as $key => $permission) {

                $result = 1; // Inherited

                if ($permission->resultBy() !== 'DB_ROLE') {
                    $result = 1; // Inherited
                }

                if ($permission->resultBy() === 'DB_USER') {
                    // Check if allowed permission
                    if ($permission->isAccess() === true) {
                        $result = 2; // Allow
                    }

                    // Check if denied permission
                    if ($permission->isAccess() !== true) {
                        $result = 3; // Deny
                    }
                }

                $currentInheritStatus   = false; // not available

                // Check if level check array has 'CONFIG_ROLE' key and it is true
                if ((array_has($permission->levelsChecked(), 'CONFIG_ROLE'))
                    and ($permission->levelsChecked()['CONFIG_ROLE'] === true)) {
                    $currentInheritStatus = true; // available
                }


                // Check if level check array has 'CONFIG_ROLE' key and it is true
                if ((array_has($permission->levelsChecked(), 'DB_ROLE'))) {
                    if (($permission->levelsChecked()['DB_ROLE'] === true)) {
                        $currentInheritStatus = true; // available
                    } else if (($permission->levelsChecked()['DB_ROLE'] === false)) {
                        $currentInheritStatus = false; // available
                    } 
                }

                $permissions[] = [
                    "id"            => $permission->access_id_key(),
                    "title"         => str_replace('Read ', '', $permission->title()),
                    "parent"        => $permission->parent(),
                    "folder"        => false,
                    "key"           => $permission->access_id_key(),
                    "result"        => $result,
                    "expanded"      => true,
                    "dependencies"  => $permission->dependencies(),
                    "checkbox"      => false,
                    "inheritStatus" => $currentInheritStatus
                ]; 
            }              
        }
 
       	$allPermissions = $this->buildTree($permissions);
 		$allowPermissions = [];
 		$denyPermissions = [];
 		$inheritPermissions = [];

 		if (!__isEmpty($allPermissions)) {
 			foreach ($allPermissions as $permission) {
 				if (!__isEmpty($permission['children'])) {
 					foreach ($permission['children'] as $child) {
						if ($child['result'] == 2) {
							$allowPermissions[] = $child['id'];
						}
						if ($child['result'] == 3) {
							$denyPermissions[] = $child['id'];
						}
						if ($child['result'] == 1) {
							$inheritPermissions[] = $child['id'];
						}
					}
 				}
 				if (isset($permission['children_permission_group'])) {
 					 
 					foreach ($permission['children_permission_group'] as $groupchild) {

 						foreach ($groupchild['children'] as $subchild) {
 						
							if ($subchild['result'] == 2) {
								$allowPermissions[] = $subchild['id'];
							}
							if ($subchild['result'] == 3) {
								$denyPermissions[] = $subchild['id'];
							}
							if ($subchild['result'] == 1) {
								$inheritPermissions[] = $subchild['id'];
							}
						}
					}
 				}
 			}
 		}
 		
        return __engineReaction(1, [
            'permissions' => $allPermissions,
            'allow_permissions' => $allowPermissions,
            'deny_permissions' => $denyPermissions,
            'inherit_permissions' => $inheritPermissions,
            'userRoleTitle'         => $userRoleTitle
        ]);
    }


    /*
     * Prepare Nested key value array.
     *
     * @param array $elements
     * @param int $parentId
     *
     * @return array
     *---------------------------------------------------------------- */

    protected function buildTree($elements = [], $parentId = '')
    {
        $branch = [];
        $permissionStatuses = configItem('user.permission_status');

        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
  
                if ($children) {
                	foreach ($children as $key => $subparent) {
 						if (__isEmpty($this->buildTree($elements, $subparent['id']))) {

 							$element['children'][] = $subparent;
 							foreach ($permissionStatuses as $statusKey => $status) {

                                $inheritStatus = '';

                                if ($statusKey == 1) {
                                    $inheritStatus = ($subparent['inheritStatus'] == true)
                                                    ? __tr(' (Allow)') : __tr(' (Deny)');
                                }

                                $element['children'][$key]['options'][] = [
                                    'title'     => $status.$inheritStatus,
                                    'status'	=> $statusKey,
                                    'key'       => $subparent['id'].'_'.$statusKey,
                                    'id'        => $subparent['id'].'_'.$statusKey,
                                ];
                            }
 						} else {
 							$element['children_permission_group'][] = $subparent;
 						}

                        $subparent['result'] = $element['result'];
                	}
             	}
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /*
     * Store Dynamic User Permission.
     *
     * @param array $inputData
     * @param int $userId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddUserPermission($inputData, $userId)
    {
    	$userData = $this->userRepository->fetchUser($userId);

        // check if user is exist
        if (__isEmpty($userData)) {
            return __engineReaction(2, null, __tr('User Not Found.'));
        }
        
        $updateData = [
            '__permissions' => [
                'allow' => $inputData['allow_permissions'],
                'deny'  => $inputData['deny_permissions']
            ]
        ];

        if ($this->userRepository->updateUser($userData, $updateData)) {
            return __engineReaction(1, null, __tr('Permission Added Successfully.'));
        }

        return __engineReaction(14, null, __tr('Nothing Updated.'));
    }

    /*
     * Prepare  User add support data
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareUserSupportAddData()
    {
        $roleCollection = $this->userRepository->fetchAllRoles();
        $roleData = [];
       
        // check if role collection exist
        if (!__isEmpty($roleCollection)) {
            foreach ($roleCollection as $key => $role) {
                // check if role admin not exist
                $roleData[] = [
                    'id'    => $role->_id,
                    'name'  => $role->title
                ];  
            }
        }
        
        return __engineReaction(1, [
            'userRoles' => $roleData,
        ]);
    }
}
