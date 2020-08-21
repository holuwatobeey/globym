<?php
// Response Codes & other global configurations
$techConfig = require app_path('Yantrana/__Laraware/Config/tech-config.php');
 
$techAppConfig = [
    "gettext_fallback" => true,

    /* Paths
    ------------------------------------------------------------------------- */

    "custom_pages"        => "external-pages/",
    "product_assets"      => "media-storage/products/product-",
    "product_user_assets" => "media-storage/users/user-",
    'day_date_time_format'=> 'l jS F Y  g:ia',
    'account_activation'  => (60*60*48),
    'cart_expiration_time' => 60*60*24*365, // shoppingCart expiration in 1 year

    /* pagination
    ------------------------------------------------------------------------- */

    'pagination_count'  => 5,

    /* if demo mode is on then set theme color
    ------------------------------------------------------------------------- */
    'theme_colors' => [
        'purple'        => '6900b9',
        'blue'          => '005bb9',
        'green'         => '008428',
        'brown'         => '842500',
        'dark-gray'     => '424242',
        'light-blue'    => '43a8ff',
        'pink'          => 'e185e2',
        'chocolate'     => 'a06000'
    ],

    /* default item load type
    ------------------------------------------------------------------------- */
    'loadItemType' => 24, // use scroll

    /* character limit
    ------------------------------------------------------------------------- */

    'character_limit'  => 50,

    /* quantity limit
    ------------------------------------------------------------------------- */

    'qty_limit'  => 99999,

    /* Dashboard Recent Order Count
    ------------------------------------------------------------------------- */
    'recent_order_count' => 5,

     /* Dashboard Latest Sale Products
    ------------------------------------------------------------------------- */
    'latest_sale_product_count' => 5,

    /* My Wishlist Products Pagination Count
    ------------------------------------------------------------------------- */
    'my_whislist_pagination_count' => 10,

    /* My Rating list Products Pagination Count
    ------------------------------------------------------------------------- */
    'my_rating_pagination_count' => 10,

    /* Product detail user review count
    ------------------------------------------------------------------------- */
    'product_detail_user_review_count' => 5,

    /* Product Review list count
    ------------------------------------------------------------------------- */
    'product_user_review_list_count' => 5,

    /* Products Detail Faq Show
    ------------------------------------------------------------------------- */
    'product_detail_faq_count' => 5,

    /* Product Faq List Page Show
    ------------------------------------------------------------------------- */
    'faq_list_count' => 5,


    /* Product Rating on dashboard count Show
    ------------------------------------------------------------------------- */
    'product_rating_on_db_count' => 5,

    /* international shipping - All Other Countries
    ------------------------------------------------------------------------- */

    'aoc'  => 'AOC', // treated as Country Code
    'aoc_id'  => 999, // id in countries table 

    /* shop logo name
    ------------------------------------------------------------------------- */

    'logoName'  => 'logo.png',

     /* favicon name
    ------------------------------------------------------------------------- */

    'faviconName'  => 'favicon.ico',

    /* shop Invoice image name
    ------------------------------------------------------------------------- */

    'invoiceName'  => 'invoice_logo.png',

    /* Email Config
    ------------------------------------------------------------------------- */

    'mail_from'         =>  [ 
        env('MAIL_FROM_ADD', 'your@domain.com'),
        env('MAIL_FROM_NAME', 'E-Mail Service')
    ],

    /* Account related 
    ------------------------------------------------------------------------- */

    'account' => [
        'activation_expiry'         => 24 * 2, // hours
        'change_email_expiry'       => 24 * 2, // hours
        'password_reminder_expiry'  => 24 * 2, // hours
        'passwordless_login_expiry' => 5, // minutes
    ],

    'login_attempts'    =>  5,

    /* Status Code Multiple Uses
    ------------------------------------------------------------------------- */

    'status_codes' => [
        1 => ('Active'),
        2 => ('Inactive'),
        3 => ('Banned'),
        4 => ('Never Activated'),
        5 => ('Deleted'),
        6 => ('Suspended'),
        7 => ('On Hold'),
        8 => ('Completed'),
        9 => ('Invite')
    ],


    /* User Roles
    ------------------------------------------------------------------------- */

    'roles' => [
        1 => ('Admin'),
        2 => ('User'),
    ],

    /* Assigned user status codes
    ------------------------------------------------------------------------- */

    'user' => [
        'status_codes' => [ 
            1, // active
            2, // deactive
            3, // banned
            4, // never activated
            5  // deleted
        ],
        'permission_status' => [
            1 => 'Role Inheritance',
            2 => 'Allow',
            3 => 'Deny'
        ]
    ],

    /* dashboard product chart data
    ------------------------------------------------------------------------- */

    'product_dashboard' => [
        'db_product_chart_bg_color' => [
            1 => '#28a745',
            2 => '#6c757d',
            3 => 'black',
            4 => '#ffc107',
        ],
    ],

     /*product chart data
    ------------------------------------------------------------------------- */

    'products' => [
        'product_chart_bg_color' => [
            1 => '#8DDAD4',
            2 => '#8e5ea2',
            3 => '#504E54',
            4 => '#4545fd',
            5 => '#EED57F',
            6 => '#EEB57F',
            7 => '#D3EE7F',
            8 => '#7FC7EE',
            9 => '#94DA8D',
            10 => '#4545fd'
        ],
    ],

    /*product chart data
    ------------------------------------------------------------------------- */

    'brand' => [
        'brand_chart_bg_color' => [
            1 => '#28a745',
            2 => '#6c757d'
        ],
    ],

    /* Order Status
    ------------------------------------------------------------------------- */

    'orders' => [
        'type' => [ 
            1 => ('Order by Email'),
            2 => ('Online Payment')
        ],
        'payment_methods' => [ 
            1 => ('PayPal'), // PayPal IPN Payments
            2 => ('Check'),
            3 => ('Bank Transfer'),
            4 => ('COD'),    
            5 => ('Other'),
            6 => ('Stripe'),
            7 => ('PayPal Sandbox'), 
            8 => ('Stripe Test Mode'),
            11 => ('Razorpay'),
            12 => ('Razorpay Test Mode'),
            13 => ('Other Order Payment Information'),
            14 => ('Iyzico Payment'),
            15 => ('Iyzico Test Mode'),
            16 => ('Paytm'),
            17 => ('Paytm Test Mode'),
            18 => ('Instamojo'),
            19 => ('Instamojo Test Mode'),
            20 => ('Paystack'),
            21 => ('Paystack Test Mode')
        ],
        'payment_status' => [ 
            1 => ('Awaiting Payment'), // PayPal IPN Payments
            2 => ('Completed'),
            3 => ('Payment Failed'),
            4 => ('Pending'),
            5 => ('Refunded')
        ],
        'payment_type' => [ 
            1 => ('Deposit'),
            2 => ('Refund')
        ],
        'products' => [ 
            1 => ('Ordered'),
            2 => ('Confirmed & Available'),
            3 => ('Cancelled'),
            4 => ('Not Available'),
            5 => ('Not Shippable')
        ],
        'status_codes' => [ 
            1 => ('New'),
            2 => ('Processing'),
            3 => ('Cancelled'),
            4 => ('On Hold'),
            5 => ('In Transit'),
            6 => ('Completed'),
            7 => ('Confirmed'),
            // 8 => ('Cancellation Request Received'),
            // 9 => ('User Cancelled'),
            //10 => ('Invalid'),
            11 => ('Delivered')
        ],
        'date_filter_code' => [ 
            1 => ('Created On'),
            2 => ('Updated On')
        ],
        'order_chart_bg_color' => [
            1 => '#251F83',
            2 => '#F39C12',
            3 => '#85929E',
            4 => '#464747',   // NEW or Not Executed
            5 => '#D68910',
            6 => '#0B660F',
            7 => '#3229BF',
            9 => '#27AE60'
        ],
        'order_payment_chart_bg_color' => [
            1 => '#123D73',
            2 => '#0B660F',
            3 => '#DB001B',
            4 => '#F39C12',   // NEW or Not Executed
            5 => 'black',
        ]
    ],


    /* Manage Pages related
    --------------------------------------------------------------------------*/

    'pages_status_codes' => [
        1 => ('Yes'),
        2 => ('No')
    ],

    'pages_types' => [
        1 => ('Page'),
        2 => ('Link')
    ],

    'pages_types_with_system_link' => [
        1 => ('Page'),
        2 => ('Link'),
        3 => ('System Link')
    ],

    /* Multi-Currency Auto refresh Value
    --------------------------------------------------------------------------*/

    'currency_auto_refresh_value' => [
        1 => 'Realtime',
        2 => 'Daily',
        3 => 'Weekly',
        4 => 'Monthly'
    ],

    'curency_auto_refresh_time' => [
        1 => 0,
        2 => now()->addDay(),
        3 => now()->addWeek(),
        4 => now()->addMonth()
    ],


    /* google re-captch key --------------------------------------------------------------------------*/
    'recaptcha' =>  [
        'site_key' => env('RECAPTCHA_PUBLIC_KEY',''),
        'secret_key' => env('RECAPTCHA_PRIVATE_KEY','')
    ],

    /* user login data --------------------------------------------------------------------------*/
    'demo_user_login_credential' =>  [
        1 => [
            'email' => 'demoadmin@livelycart.com',
            'password' => 'demoadmin12'
        ],
        2 => [
            'email' => 'democustomer@livelycart.com',
            'password' => 'demoadmin12'
        ]
    ],

    /* Media extensions
    ------------------------------------------------------------------------- */
    'media' => [
        'extensions' => [
            1 => ["jpg", "png", "gif","jpeg"]
        ]
    ],


    /* Reserve page id
    ------------------------------------------------------------------------- */
    'reserve_pages_ids'    =>  [/*1,*/ 2, 3, 4, 5, 6],
    'reserve_pages'        =>  [/*1,*/ 2, 3, 4, 5, 6],

    'system_links'  => [
        //'home'         => 1,
        'categories' => 2,
        'brand'      => 3,
        'login'      => 4,
        'register'   => 5,
        'contact'    => 6
    ],

    'pages_type_codes' => [1,2,3],

    'link_target' => [
        '_blank'  => ('_blank'),
        '_self'   => ('_self') ,
        '_parent' => ('_parent')
    ],

    'link_target_array' => ['_blank','_self','_parent'],

    /* Manage categories related
    --------------------------------------------------------------------------*/

    'categories_status_codes' => [
        1 => ('Active'),
        2 => ('Deactive')
    ],

    /*
		Start Custom Entity array
	------------------------------------------ */

	'custom_entities' => [

		'manage_products' 	=> [
            'manage_products',
            'product_options',
            'add_edit_product_option',

            'product_images',
			'view_product_images',
		]
	],


    /* Store Related Config Values
    --------------------------------------------------------------------------*/

    'currencies'         => [

        /* Zero-decimal currencies
        ----------------------------------------------------------------------*/
        'zero_decimal'  => [
            'BIF' =>  'Burundian Franc',
            'CLP' =>  'Chilean Peso',
            'DJF' =>  'Djiboutian Franc',
            'GNF' =>  'Guinean Franc',
            'JPY' =>  'Japanese Yen',
            'KMF' =>  'Comorian Franc',
            'KRW' =>  'South Korean Won',
            'MGA' =>  'Malagasy Ariary',
            'PYG' =>  'Paraguayan Guaraní',
            'RWF' =>  'Rwandan Franc',
            'VND' =>  'Vietnamese Đồng',
            'VUV' =>  'Vanuatu Vatu',
            'XAF:' => 'Central African Cfa Franc',
            'XOF' =>  'West African Cfa Franc',
            'XPF' =>  'Cfp Franc',
            // Paypal zero-decimal currencies
            'HUF' =>  'Hungarian Forint',
            'TWD' =>  'New Taiwan Dollar',
        ], 
        
        'options'   => [
            'AUD' => ('Australian Dollar'),
            'CAD' => ('Canadian Dollar'),
            'EUR' => ('Euro'),
            'GBP' => ('British Pound'),
            'USD' => ('U.S. Dollar'),
            'NZD' => ('New Zealand Dollar'),
            'CHF' => ('Swiss Franc'),
            'HKD' => ('Hong Kong Dollar'),
            'SGD' => ('Singapore Dollar'),
            'SEK' => ('Swedish Krona'),
            'DKK' => ('Danish Krone'),
            'PLN' => ('Polish Zloty'),
            'NOK' => ('Norwegian Krone'),
            'HUF' => ('Hungarian Forint'),
            'CZK' => ('Czech Koruna'),
            'ILS' => ('Israeli New Shekel'),
            'MXN' => ('Mexican Peso'),
            'BRL' => ('Brazilian Real (only for Brazilian members)'),
            'MYR' => ('Malaysian Ringgit (only for Malaysian members)'),
            'PHP' => ('Philippine Peso'),
            'TWD' => ('New Taiwan Dollar'),
            'THB' => ('Thai Baht'),
            'TRY' => ('Turkish Lira (only for Turkish members)'),
            'INR' => ('Indian Rupee)'),
            ''    => ('Other')
        ],
        'details'    => [

            'AUD' => [
                'name'   => ("Australian Dollar"), 
                'symbol' => "A$", 
                'ASCII'  => "A&#36;"
            ],
                 
            'CAD' => [
                'name'   => ("Canadian Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'CZK' => [
                'name'   => ("Czech Koruna"), 
                'symbol' => "Kč", 
                'ASCII'  => "K&#x10d;"
            ],

            'DKK' => [
                'name'   => ("Danish Krone"), 
                'symbol' => "Kr", 
                'ASCII'  => "K&#x72;"
            ],

            'EUR' => [
                'name'   => ("Euro"), 
                'symbol' => "€", 
                'ASCII'  => "&euro;"
             ],

            'HKD' => [
                'name'   => ("Hong Kong Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'HUF' => [
                'name'   => ("Hungarian Forint"), 
                'symbol' => "Ft", 
                'ASCII'  => "F&#x74;"
            ],

            'ILS' => [
                'name'   => ("Israeli New Sheqel"), 
                'symbol' => "₪", 
                'ASCII'  => "&#8361;"
            ],

            'JPY' => [
                'name'   => ("Japanese Yen"), 
                'symbol' => "¥", 
                'ASCII'  => "&#165;"
            ],

            'MXN' => [
                'name'   => ("Mexican Peso"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'NOK' => [
                'name'   => ("Norwegian Krone"), 
                'symbol' => "Kr", 
                'ASCII'  => "K&#x72;"
            ],

            'NZD' => [
                'name'   => ("New Zealand Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'PHP' => [
                'name'   => ("Philippine Peso"), 
                'symbol' => "₱", 
                'ASCII'  => "&#8369;"
            ],

            'PLN' => [
                'name'   => ("Polish Zloty"), 
                'symbol' => "zł", 
                'ASCII'  => "z&#x142;"
            ],

            'GBP' => [
                'name'   => ("Pound Sterling"), 
                'symbol' => "£", 
                'ASCII'  => "&#163;"
            ],

            'SGD' => [
                'name'   => ("Singapore Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'SEK' => [
                'name'   => ("Swedish Krona"), 
                'symbol' => "kr", 
                'ASCII'  => "K&#x72;"
            ],

            'CHF' => [
                'name'   => ("Swiss Franc"), 
                'symbol' => "CHF", 
                'ASCII'  => "&#x43;&#x48;&#x46;"
            ],

            'TWD' => [
                'name'   => ("Taiwan New Dollar"), 
                'symbol' => "NT$", 
                'ASCII'  => "NT&#36;"
            ],

            'THB' => [
                'name'   => ("Thai Baht"), 
                'symbol' => "฿", 
                'ASCII'  => "&#3647;"
            ],

            'USD' => [
                'name'   => ("U.S. Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],
            'TRY' => [
                'name'   => ("Turkish Lira"), 
                'symbol' => "₺", 
                'ASCII'  => "&#x20BA;"
            ],
            'INR' => [
                'name'   => ("Indian Rupee"), 
                'symbol' => "₹", 
                'ASCII'  => "&#8377;"
            ],
            'NGN' => [
                'name'   => ("Nigerian naira"), 
                'symbol' => "₦", 
                'ASCII'  => "&#8358;"
            ],
        ],
    ],

    'menu_placement' =>  [
        [
            'value'    => 1,
            'name'  => ('Sidebar')
        ],
        [
            'value'    => 2,
            'name'  => ('Top Menu')
        ],
        [
            'value'    => 3,
            'name'  => ('Both')
        ],
        [
            'value'    => 4,
            'name'  => ('Dont Show')
        ]
    ],

    'settings' => [

        /* Configuration setting data-types id
        ------------------------------------------------------------------------- */
        'datatypes'  => [
            'string' => 1,
            'bool'   => 2,
            'int'    => 3,
            'json'   => 4,
            'float'  => 5
        ],
        'fields' => [
            // General Tab
            'store_name' => [
                'key'           => 'store_name',
                'data_type'     => 1,    // string
                'default'       => 'You Website Name'
            ],
            'favicon_image' => [
                'key'           => 'favicon_image',
                'data_type'     => 1,    // string
                'placeholder'   => 'Select Favicon',
                'default'       => 'favicon.ico'
            ],
            'logo_image' => [
                'key'           => 'logo_image',
                'data_type'     => 1,    // string
                'default'       => 'logo.png'
            ],
            'invoice_image' => [
                'key'           => 'invoice_image',
                'data_type'     => 1,    // string
                'default'       => 'invoice_logo.png'
            ],
            'logo_background_color' => [
                'key'           => 'logo_background_color',
                'data_type'     => 1,    // string
                'default'       => 'b9002f' // 
            ],
            'business_email' => [
                'key'           => 'business_email',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'your-email-address@example.com'
            ],
            'home_page' => [
                'key'           => 'home_page',
                'data_type'     => 3,    // integer
                'default'       => 1    // home page settings
            ],
            'timezone' => [
                'key'           => 'timezone',
                'data_type'     => 1,    // string
                'default'       => 'UTC'
            ],
            // Currency settings
            'currency'              => [
                'key'           => 'currency',
                'data_type'     => 1,    // string
                'default'       => 'USD'
            ],
            'currency_symbol'       => [
                'key'           => 'currency_symbol',
                'data_type'     => 1,    // string
                'default'       => '&#36;'
            ],
            'currency_value'        => [
                'key'           => 'currency_value',
                'data_type'     => 1,    // string
                'default'       => 'USD'
            ],
            'currency_decimal_round' => [
                'key'           => 'currency_decimal_round',
                'data_type'     => 3, // int
                'default'       => 2
            ],
            'round_zero_decimal_currency' => [
                'key'           => 'round_zero_decimal_currency',
                'data_type'     => 2, // boolean
                'default'       => true // round
            ],
            'currency_format'   => [
                'key'           => 'currency_format',
                'data_type'     => 1,    // string
                'default'       => '{__currencySymbol__}{__amount__} {__currencyCode__}'
            ],
            'display_multi_currency' => [
                'key'           => 'display_multi_currency',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'multi_currencies'        => [
                'key'           => 'multi_currencies',
                'data_type'     => 4,    // json
                'default'       => ''
            ],
            'auto_refresh_in' => [
                'key'           => 'auto_refresh_in',
                'data_type'     => 1, // string
                'default'       => 2
            ],
            'currency_markup'        => [
                'key'           => 'currency_markup',
                'data_type'     => 5,    // float
                'default'       => null
            ],
            'payment_other'        => [
                'key'           => 'payment_other',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'payment_other_text'        => [
                'key'           => 'payment_other_text',
                'data_type'     => 1,    // string
                'default'       => 'Add here other payment related information'
            ],
            'hide_sidebar_on_order_page' => [
                'key'           => 'hide_sidebar_on_order_page',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
            'enable_guest_order' => [
                'key'           => 'enable_guest_order',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'select_payment_option'        => [
                'key'           => 'select_payment_option',
                'data_type'     => 4,    // json
                'default'       => ''
            ],
            // Payment method
            'use_paypal'        => [
                'key'           => 'use_paypal',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'paypal_email'        => [
                'key'           => 'paypal_email',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'your-paypal-email-address@example.com'
            ],
            'paypal_sandbox_email'          => [
                'key'           => 'paypal_sandbox_email',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'your-sandbox-email-address@example.com'
            ],
            'use_stripe'            => [
                'key'           => 'use_stripe',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'stripe_live_secret_key'          => [
                'key'           => 'stripe_live_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Live secret key'
            ],
            'stripe_live_publishable_key'          => [
                'key'           => 'stripe_live_publishable_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Live Publishable Key'
            ],
            'stripe_testing_secret_key'          => [
                'key'           => 'stripe_testing_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Test Secret Key'
            ],
            'stripe_testing_publishable_key'          => [
                'key'           => 'stripe_testing_publishable_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Test Publishable key'
            ],
            'use_razorpay'        => [
                'key'           => 'use_razorpay',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'razorpay_live_key'        => [
                'key'           => 'razorpay_live_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Razorpay Live Key'
            ],
            'razorpay_live_secret_key'          => [
                'key'           => 'razorpay_live_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Razorpay Live Secret Key'
            ],
            'razorpay_testing_key'        => [
                'key'           => 'razorpay_testing_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Razorpay Testing Key'
            ],
            'razorpay_testing_secret_key'          => [
                'key'           => 'razorpay_testing_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Razorpay Testing Secret Key'
            ],
            'use_iyzipay'        => [
                'key'           => 'use_iyzipay',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'iyzipay_live_key'        => [
                'key'           => 'iyzipay_live_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Iyzico Live Key'
            ],
            'iyzipay_live_secret_key'          => [
                'key'           => 'iyzipay_live_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Iyzico Live Secret Key'
            ],
            'iyzipay_testing_key'        => [
                'key'           => 'iyzipay_testing_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Iyzico Testing Key'
            ],
            'iyzipay_testing_secret_key'          => [
                'key'           => 'iyzipay_testing_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Iyzico Testing Secret Key'
            ],
            'use_paytm'         => [
                'key'           => 'use_paytm',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'paytm_live_merchant_key'        => [
                'key'           => 'paytm_live_merchant_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paytm Live Merchant Key'
            ],
            'paytm_live_merchant_mid_key'          => [
                'key'           => 'paytm_live_merchant_mid_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paytm Live Merchant Mid Key'
            ],
            'paytm_testing_merchant_key'        => [
                'key'           => 'paytm_testing_merchant_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paytm Testing Merchant Key'
            ],
            'paytm_testing_merchant_mid_key'          => [
                'key'           => 'paytm_testing_merchant_mid_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paytm Testing Merchant Mid Key'
            ],
            'use_instamojo'    => [
                'key'           => 'use_instamojo',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'instamojo_live_api_key'        => [
                'key'           => 'instamojo_live_api_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Instamojo Live Api Key'
            ],
            'instamojo_live_auth_token_key'          => [
                'key'           => 'instamojo_live_auth_token_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Instamojo Live Auth Token Key'
            ],
            'instamojo_testing_api_key'        => [
                'key'           => 'instamojo_testing_api_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Instamojo Testing Api Key'
            ],
            'instamojo_testing_auth_token_key'          => [
                'key'           => 'instamojo_testing_auth_token_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Instamojo Testing Auth Token Key'
            ],
            'use_payStack'    => [
                'key'           => 'use_payStack',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'payStack_live_secret_key'        => [
                'key'           => 'payStack_live_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paystack Live Secret Key'
            ],
            'payStack_live_public_key'          => [
                'key'           => 'payStack_live_public_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paystack Live Public Key'
            ],
            'payStack_testing_secret_key'        => [
                'key'           => 'payStack_testing_secret_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paystack Testing Secret Key'
            ],
            'payStack_testing_public_key'          => [
                'key'           => 'payStack_testing_public_key',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Paystack Testing Public Key'
            ],
            'payment_check'        => [
                'key'           => 'payment_check',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'payment_check_text'        => [
                'key'           => 'payment_check_text',
                'data_type'     => 1,    // string
                'default'       => 'Add here check related information.'
            ],
            'payment_bank'        => [
                'key'           => 'payment_bank',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'payment_bank_text'        => [
                'key'           => 'payment_bank_text',
                'data_type'     => 1,    // string
                'default'       => 'Add here bank related information'
            ],
            'payment_cod'        => [
                'key'           => 'payment_cod',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'payment_cod_text'        => [
                'key'           => 'payment_cod_text',
                'data_type'     => 1,    // string
                'default'       => 'Add here cod related information.'
            ],
            'use_iyzico'        => [
                'key'           => 'use_iyzico',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'allow_customer_order_cancellation'          => [
                'key'           => 'allow_customer_order_cancellation',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'order_cancellation_statuses'        => [
                'key'           => 'order_cancellation_statuses',
                'data_type'     => 4,    // json
                'default'       => ''
            ],
            'show_out_of_stock'        => [
                'key'           => 'show_out_of_stock',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
            'hideSidebar_product_detail_page'        => [
                'key'           => 'hideSidebar_product_detail_page',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
            'pagination_count'        => [
                'key'           => 'pagination_count',
                'data_type'     => 3,    // int
                'default'       => 20
            ],
            'item_load_type'        => [
                'key'           => 'item_load_type',
                'data_type'     => 3,    // int
                'default'       => 2
            ],
            'facebook' => [
                'key'           => 'facebook',
                'data_type'     => 2,     // boolean
                'default'       => true
            ],
            'twitter' => [
                'key'           => 'twitter',
                'data_type'     => 2,     // boolean
                'default'       => true
            ],
            'enable_whatsapp' => [
                'key'           => 'enable_whatsapp',
                'data_type'     => 2,     // boolean
                'default'       => true
            ],
            'categories_menu_placement'        => [
                'key'           => 'categories_menu_placement',
                'data_type'     => 3,    // int
                'default'       => 3
            ],
            'brand_menu_placement'        => [
                'key'           => 'brand_menu_placement',
                'data_type'     => 3,    // int
                'default'       => 3
            ],
            'credit_info'       => [
                'key'           => 'credit_info',
                'data_type'     => 2,    // bool
                'default'       => true
            ],
            'addtional_page_end_content'        => [
                'key'           => 'addtional_page_end_content',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'show_language_menu' => [
                'key'           => 'show_language_menu',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
                'default_language' => [
                'key'           => 'default_language',
                'data_type'     => 1,    // string
                'default'       => 'en_US'
            ],
            'contact_email'        => [
                'key'           => 'contact_email',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'your-email-address@example.com'
            ],
            'contact_address'        => [
                'key'           => 'contact_address',
                'data_type'     => 1,    // string
                'default'       => 'add your contact address'
            ],
            'activation_required_for_new_user'        => [
                'key'           => 'activation_required_for_new_user',
                'data_type'     => 2,    // bool
                'default'       => true
            ],
            'register_new_user'        => [
                'key'           => 'register_new_user',
                'data_type'     => 2,    // bool
                'default'       => true
            ],
            'show_captcha' => [
                'key'           => 'show_captcha',
                'data_type'     => 3,       // integer
                'default'       => 5
            ],
            'activation_required_for_change_email'        => [
                'key'           => 'activation_required_for_change_email',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
            'enable_wishlist'        => [
                'key'           => 'enable_wishlist',
                'data_type'     => 2,    // boolean
                'default'       => true
            ],
            'enable_recaptcha'        => [
                'key'           => 'enable_recaptcha',
                'data_type'     => 2,    // boolean
                'default'       => false
            ],
            'recaptcha_site_key'   => [
                'key'           => 'recaptcha_site_key',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'recaptcha_secret_key'   => [
                'key'           => 'recaptcha_secret_key',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'term_condition'        => [
                'key'           => 'term_condition',
                'data_type'     => 1,    // string
                'default'       => 'Add terms & conditions'
            ],
            'facebook_client_id'    => [
                'key'           => 'facebook_client_id',              
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'facebook_client_secret' => [
                'key'           => 'facebook_client_secret',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'google_client_id'      => [
                'key'           => 'google_client_id',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'google_client_secret'  => [
                'key'           => 'google_client_secret',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'allow_facebook_login'  => [
                'key'           => 'allow_facebook_login',
                'data_type'     => 2,     // boolean
                'default'       => false
            ],
            'allow_google_login' => [
                'key'           => 'allow_google_login',
                'data_type'     => 2,     // boolean
                'default'       => false
            ],// Privacy Policy
            'privacy_policy'        => [
                'key'           => 'privacy_policy',
                'data_type'     => 1,    // string
                'default'       => 'Add Privacy Policy'
            ],
            // Social account configuration
            'social_facebook'   => [
                'key'           => 'social_facebook',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Your Social Facebook Id'
            ],
            'social_twitter' => [
                'key'           => 'social_twitter',
                'data_type'     => 1,    // string
                'default'       => '',
                'placeholder'   => 'Your Social Twitter Id'
            ],
            'custom_css' => [
                'key'           => 'custom_css',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            // Notification
            'global_notification'         => [
                'key'           => 'global_notification',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'apply_tax_after_before_discount' => [
                'key'           => 'apply_tax_after_before_discount',
                'data_type'     => 3,    // int
                'default'       => 1
            ],
            'calculate_tax_as_per_shipping_billing' => [
                'key'           => 'calculate_tax_as_per_shipping_billing',
                'data_type'     => 3,    // int
                'default'       => 1
            ],
			// Social Login Settings
	        'facebook_client_id'    => [
	            'key'           => 'facebook_client_id',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'facebook_client_secret' => [
	            'key'           => 'facebook_client_secret',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'google_client_id'      => [
	            'key'           => 'google_client_id',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'google_client_secret'  => [
	            'key'           => 'google_client_secret',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'twitter_client_id'      => [
	            'key'           => 'twitter_client_id',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'twitter_client_secret'  => [
	            'key'           => 'twitter_client_secret',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'github_client_id'      => [
	            'key'           => 'github_client_id',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'github_client_secret'  => [
	            'key'           => 'github_client_secret',
	            'data_type'     => 1,    // string
	            'default'       => ''
	        ],
	        'allow_facebook_login'  => [
	            'key'           => 'allow_facebook_login',
	            'data_type'     => 2,     // boolean
	            'default'       => false
	        ],
	        'allow_google_login' => [
	            'key'           => 'allow_google_login',
	            'data_type'     => 2,     // boolean
	            'default'       => false
	        ],
	        'allow_twitter_login' => [
	            'key'           => 'allow_twitter_login',
	            'data_type'     => 2,     // boolean
	            'default'       => false
	        ],
	        'allow_github_login' => [
	            'key'           => 'allow_github_login',
	            'data_type'     => 2,     // boolean
	            'default'       => false
	        ],
			// Rating & Reviews 
	        'enable_rating' => [
	            'key'           => 'enable_rating',
	            'data_type'     => 2,       // boolean
	            'default'       => true
	        ],
	        'enable_rating_review' => [
	            'key'           => 'enable_rating_review',
	            'data_type'     => 2,       // boolean
	            'default'       => false
	        ],
	        'restrict_add_rating_to_item_purchased_users' => [
	            'key'           => 'restrict_add_rating_to_item_purchased_users',
	            'data_type'     => 2,       // boolean
	            'default'       => false
	        ],
	        'enable_rating_modification' => [
	            'key'           => 'enable_rating_modification',
	            'data_type'     => 2,       // boolean
	            'default'       => false
	        ],
            'enable_user_add_questions' => [
                'key'           => 'enable_user_add_questions',
                'data_type'     => 2,       // boolean
                'default'       => false
            ],
            'enable_staticaly_cdn' => [
                'key'           => 'enable_staticaly_cdn',
                'data_type'     => 2,       // boolean
                'default'       => false
            ],

            /*
            * Email Settings
            * ---------------------------------------------------
            */
            'use_env_default_email_settings'       => [
                'key'           => 'use_env_default_email_settings',
                'data_type'     => 2,    // integer
                'default'       => true
            ],
            'mail_driver'       => [
                'key'           => 'mail_driver',
                'data_type'     => 1,    // integer
                'default'       => 1
            ],
            'mail_from_address'          => [
                'key'           => 'mail_from_address',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'mail_from_name'          => [
                'key'           => 'mail_from_name',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'smtp_mail_port'    => [
                'key'           => 'smtp_mail_port',
                'data_type'     => 3,    // integer
                'default'       => null
            ],
            'smtp_mail_host'    => [
                'key'           => 'smtp_mail_host',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'smtp_mail_username'  => [
                'key'           => 'smtp_mail_username',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'smtp_mail_encryption' => [
                'key'           => 'smtp_mail_encryption',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'smtp_mail_password_or_apikey' => [
                'key'           => 'smtp_mail_password_or_apikey',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'sparkpost_mail_password_or_apikey' => [
                'key'           => 'sparkpost_mail_password_or_apikey',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'mailgun_mail_password_or_apikey' => [
                'key'           => 'mailgun_mail_password_or_apikey',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'mailgun_domain'          => [
                'key'           => 'mailgun_domain',
                'data_type'     => 1,    // string
                'default'       => ''
            ],
            'mailgun_endpoint'          => [
                'key'           => 'mailgun_endpoint',
                'data_type'     => 1,    // string
                'default'       => ''
            ],

            'append_email_message' => [
                'key'           => 'append_email_message',
                'data_type'     => 1,    // string
                'default'       => ''
            ],

            //footer template setting
            'public_footer_template'     => [
                'key'           => 'public_footer_template',
                'data_type'     => 1,    // string
                'default'       => ''
            ],

            // Home Page Image slider
            'home_slider' => [
                'key'           => 'home_slider',
                'data_type'     => 4,    // json
                'default'       => ''
            ],

            // Landing Page
            'landing_page' => [
                'key'           => 'landing_page',
                'data_type'     => 4,    // json
                'default'       => ''
            ],
        ],
    ],

    'address_type' => [
        1 => ('Home'),
        2 => ('Office'),
        3 => ('Other Address')
    ],

    /*
    ------------------------------------------------------------------------- */
    'home_page_setting' => [
        1 => ('Home page'),
        2 => ('All Products'),
        3 => ('Featured Products'),
        4 => ('Brands'),
        5 => ('Landing Page')
    ],

    'address_type_list' =>  [
        [
            'id'    => 1,
            'name'  => ('Home')
        ],
        [
            'id'    => 2,
            'name'  => ('Office')
        ],
        [
            'id'    => 3,
            'name'  => ('Other Address')
        ]
    ],

    'payment_methods_list' =>  [
        [
            'id'    => 1,
            'name'  => ('PayPal')
        ],
        [
            'id'    => 6,
            'name'  =>  ('Stripe')
        ],
        [
            'id'    => 7,
            'name'  =>  ('PayPal Sandbox')
        ],
        [
            'id'    => 8,
            'name'  =>  ('Stripe Test Mode')
        ],
        [
            'id'    => 11,
            'name'  =>  ('Razorpay')
        ],
        [
            'id'    => 12,
            'name'  =>  ('Razorpay Sandbox')
        ],
        [
            'id'    => 14,
            'name'  =>  ('Iyzico')
        ],
        [
            'id'    => 15,
            'name'  =>  ('Iyzico Sandbox')
        ],
        [
            'id'    => 16,
            'name'  =>  ('Paytm')
        ],
        [
            'id'    => 17,
            'name'  =>  ('Paytm Sandbox')
        ],
        [
            'id'    => 18,
            'name'  =>  ('Instamojo')
        ],
        [
            'id'    => 19,
            'name'  =>  ('Instamojo Sandbox')
        ],
        [
            'id'    => 20,
            'name'  =>  ('Paystack')
        ],
        [
            'id'    => 21,
            'name'  =>  ('Paystack Sandbox')
        ],
        [
            'id'    => 2,
            'name'  => ('Check')
        ],
        [
            'id'    => 3,
            'name'  => ('Bank Transfer')
        ],
        [
            'id'    => 4,
            'name'  => ('COD')
        ],
        [
            'id'    => 5,
            'name'  =>  ('Other')
        ]
    ],

    'payment_options' => [
    	'use_paypal'	=> 1,
		'payment_check'	=> 2,
		'payment_bank'	=> 3,
		'payment_cod'	=> 4,
		'payment_other'	=> 5,
		'use_stripe'	=> 6,
		'use_razorpay'	=> 11,
		'use_iyzico'	=> 14,
		'use_paytm'		=> 16,
		'use_instamojo'	=> 18,
        'use_payStack'  => 20
    ],

    // Brand Status
    'brand_status' => [
        1 => ('Active'),
        2 => ('Deactive')
    ],

    // Coupon Discount Type
    'coupon_type' => [
        1 => ('Amount'),
        2 => ('Percentage')
    ],

    // Faqs Add Type
    'faq_type' => [
        1 => 'Faq',
        2 => 'Question'
    ],

    // User rating review
    'user_rating_review' => [
        1 => 'Very Bad',
        2 => 'Bad',
        3 => 'Good',
        4 => 'Very Good',
        5 => 'Excellent'
    ],

    'coupon_discount_type' =>  [
        [
            'id'    => 1,
            'name'  => ('Amount')
        ],
        [
            'id'    => 2,
            'name'  => ('Percentage')
        ]
    ],

    'product_discount_type' => [
        1 => ('Cart Discount'),
        2 => ('Selected Products Discount')
    ],

    /* Shipping 
    ------------------------------------------------------------------------- */
    'shipping' => [
        'type' => [
        
            [
                'id'    => 1,
                'name'  => ('Flat')
            ],
            [
                'id'    => 2,
                'name'  => ('Percentage')
            ],
            [
                'id'    => 3,
                'name'  => ('Free')
            ],
            [
                'id'    => 4,
                'name'  => ('Not Shippable')
            ]
        ],
        'typeShow' => [
            1 => ('Flat'),
            2 => ('Percentage'),
            3 => ('Free'),
            4 => ('Not Shippable')
        ],
        'status' => [
            1 => ('Active'),
            2 => ('Deactive')
        ]
    ],

    /* Tax 
    ------------------------------------------------------------------------- */
    'tax' => [
        'type' => [
            1 => ('Flat'),
            2 => ('Percentage')/*,
            3 => ('No Tax')*/
        ],
        'status' => [
            1 => ('Active'),
            2 => ('Deactive')
        ]
    ],

    /* Mail Drivers 
    ------------------------------------------------------------------------- */
    'mail_drivers' => [
        'smtp' => [
            'id' => 'smtp',
            'name' => 'SMTP',
            'config_data' => [
                'port'          =>  'smtp_mail_port',
                'host'          =>  'smtp_mail_host', 
                'username'      =>  'smtp_mail_username',
                'encryption'    =>  'smtp_mail_encryption',
                'password'      =>  'smtp_mail_password_or_apikey'
            ]
        ],
        'sparkpost' => [
            'id' => 'sparkpost',
            'name' => 'Sparkpost',
            'config_data' => [
                'sparkpost_mail_password_or_apikey'
            ]
        ],
        'mailgun' => [
            'id' => 'mailgun',
            'name' => 'Mailgun',
            'config_data' => [
                'mailgun_domain',
                'mailgun_mail_password_or_apikey',
                'mailgun_endpoint'
            ]
        ],
    ],

    /* Mail encryption types 
    ------------------------------------------------------------------------- */
    'mail_encryption_types' => [
        'ssl' => 'SSL',
        'tls' => 'TLS',
        'starttls' => 'STARTTLS',
    ],

      /* Email Template View 
    ------------------------------------------------------------------------- */
    'footer_template_view' => [
        'public_footer_template' => [
            'title' => 'Public Footer Template',
            'template'   => 'includes.public-footer',
            'replaceString' => [
                '{__privacyPolicyRoute__}',
                '{__privacy_policy__}',
                '{__lcVersion__}',
                '{__creditInfo__}',
                '{__storeName__}',
                '{__footerText__}',
                '{__socialTwitter__}',
                '{__socialFacebook__}'
            ],
        ],
    ],

     /* Email Template View 
    ------------------------------------------------------------------------- */
    'email_template_view' => [
        'account_activation_email' => [
            'title' => 'Account activation',
            'template'   => 'account.account-activation',
            'subjectKey' => 'account_activation_email_subject',
            'emailSubject' => 'Account Activation',
            'replaceString' => [
	            '{__firstName__}',
				'{__lastName__}',
				'{__email__}',
				'{__fullName__}',
				'{__expirationTime__}',
				'{__userID__}',
				'{__activationKey__}',
				'{__activationKeyUrl__}'     
	        ],
        ],
        'contact_email_to_user' => [
            'title' => 'Contact mail to user',
            'subjectKey' => 'contact_email_to_user_subject',
            'template'   => 'account.contact',
            'replaceString' => [
            	'{__fullName__}',
	            '{__emailMessage__}'
	        ],    
        ],
        'resend_activation_mail' => [
            'title' => 'Resend activation email',
            'template'   => 'account.account-activation',
            'subjectKey' => 'resend_activation_mail_subject',
            'emailSubject' => 'Account Activation',
            'replaceString' => [
	            '{__firstName__}',
				'{__lastName__}',
				'{__email__}' ,
				'{__fullName__}',
				'{__expirationTime__}',
				'{__userID__}',
				'{__activationKey__}',
				'{__activationKeyUrl__}'    
	        ],    
        ],
        'new_email_activation_email' => [
            'title' => 'New email activation',
            'template'   => 'account.new-email-activation',
            'subjectKey' => 'new_email_activation_email_subject',
            'emailSubject' => 'New Email Activation',
            'replaceString' => [
	            '{__firstName__}',
				'{__lastName__}',
				'{__email__}',
				'{__fullName__}',
				'{__expirationTime__}',
				'{__userID__}',
				'{__activationKeyUrl__}'
	        ],    
        ],
        'password_reminder_email' => [
            'title' => 'Password Reminder',
            'template'   => 'account.password-reminder',
            'subjectKey' => 'password_reminder_email_subject',
            'emailSubject' => 'Password Reminder',
            'replaceString' => [
	            '{__firstName__}',
				'{__lastName__}',
				'{__email__}',
				'{__fullName__}',
				'{__expirationTime__}',
				'{__userId__}',
				'{__token__}',
				'{__tokenUrl__}' 
	        ],    
        ],
        'customer_order_email' => [
            'title' => 'Customer order',
            'template'   => 'order.customer-order',
            'subjectKey' => 'customer_order_email_subject',
            'emailSubject' => 'Payment Confirmed',
            'replaceString' => [
            	'{__paymentMessage__}',
	            '{__description__}',
	            '{__orderDetailUrl__}',
	            '{__createdOn__}',
	            '{__orderUid__}',
	            '{__paymentMethod__}',
	            '{__productsTemplate__}',
	            '{__allOrderTaxTemplate__}',
	            '{__subtotal__}',
	            '{__formatDiscountAmount__}',
	            '{__allOrderTaxAmountTemplate__}',
	            '{__formatTotalPrice__}'
	        ],    
        ],
        'order_complete_mail' => [
            'title' => 'Order complete',
            'template'   => 'order.order-complete',
            'subjectKey' => 'order_complete_mail_subject',
            'emailSubject' => 'Your order {__orderId__} has been {__orderStatus__}',
            'replaceString' => [
            	'{__fullName__}',
	            '{__description__}',
				'{__shortDescription__}',
				'{__orderUid__}',
				'{__orderStatus__}'
	        ],    
        ],
        'order_process_mail_to_user' => [
            'title' => 'Order process mail to Customer',
            'template'   => 'order.customer-order',
            'subjectKey' => 'order_process_mail_to_user_subject',
            'emailSubject' => 'Your Order has been Submitted',
            'replaceString' => [
	            '{__orderDetailUrl__}',
				'{__createdOn__}',
				'{__orderUid__}',
				'{__paymentMethod__}',
				'{__formatTotalPrice__}',
				'{__formatDiscountAmount__}',
				'{__formatTaxAmount__}',
				'{__subtotal__}',
				'{__allOrderTaxTemplate__}',
				'{__allOrderTaxAmountTemplate__}',
				'{__orderItems__}',
				'{__productsTemplate__}',
				'{__orderProcessMailHeader__}',
				'{__orderProcessMailMessage__}'
	        ],    
        ],
        'order_process_mail_to_admin' => [
            'title' => 'Order process mail to Admin',
            'template'   => 'order.customer-order',
            'subjectKey' => 'order_process_mail_to_admin_subject',
            'emailSubject' => 'Your Order has been Submitted',
            'replaceString' => [
	            '{__orderDetailUrl__}',
				'{__createdOn__}',
				'{__orderUid__}',
				'{__paymentMethod__}',
				'{__formatTotalPrice__}',
				'{__formatDiscountAmount__}',
				'{__formatTaxAmount__}',
				'{__subtotal__}',
				'{__allOrderTaxTemplate__}',
				'{__allOrderTaxAmountTemplate__}',
				'{__orderItems__}',
				'{__productsTemplate__}',
				'{__orderProcessMailHeader__}',
				'{__orderProcessMailMessage__}'
	        ],    
        ],
        'order_refund_mail' => [
            'title' => 'Order refund',
            'template'   => 'order.order-refund',
            'subjectKey' => 'order_refund_mail_subject',
            'emailSubject' => 'Payment Refund Process for __orderUid__ order',
            'replaceString' => [
	            '{__description__}',
				'{__additionalNotes__}',
				'{__orderUid__}'      
	        ],    
        ],
        'ordered_products_mail' => [
            'title' => 'Ordered products',
            'template'   => 'order.ordered-products',
            'subjectKey' => 'ordered_products_mail_subject',
            'replaceString' => [
	            '{__orderDetailUrl__}',
	            '{__createdOn__}',
	            '{__orderUid__}',
	            '{__paymentMethod__}',
	            '{__productsTemplate__}',
	            '{__allOrderTaxTemplate__}',
	            '{__subtotal__}',
	            '{__formatDiscountAmount__}',
	            '{__allOrderTaxAmountTemplate__}',
	            '{__formatTotalPrice__}'
	        ],    
        ],
        'contact_admin_email' => [
            'title' => 'Contact for admin',
            'template'   => 'contact',
            'subjectKey' => 'contact_admin_email_subject',
            'emailSubject' => '{__purchaseUid__}',
            'replaceString' => [
	            '{__userName__}',
	            '{__senderEmail__}'
	        ],
        ],
		'contact_to_user' => [
            'title' => 'Contact mail to user',
            'subjectKey' => 'contact_to_user',
            'template'   => 'order.customer-email',
            'replaceString' => [
            	'{__fullName__}',
	            '{__emailMessage__}',
	            '{__orderUID__}'
	        ],    
        ],
        'notify_product_mail_to_customer' => [
            'title' => 'Product Notify Mail To Customer',
            'subjectKey' => 'notify_product_mail_to_customer',
            'emailSubject' => 'Notify Product Mail',
            'template'   => 'product-notify-customer',
            'replaceString' => [
                '{__productUrl__}',
                '{__productName__}',
                '{__emailDescription_}'
            ],    
        ],
    ],

    /* Landing Page Content 
    ------------------------------------------------------------------------- */
    'landing_page_content' => [
        0 => [
            'title'    => '',
            'orderIndex'    => 0,
            'identity' => 'Slider',
            'isEnable' => true
        ],
        1 => [
            'pageContent'  => '',
            'orderIndex'   => 1,
            'identity' => 'PageContent',
            'isEnable' => true
        ],
        2 => [
            'productCount'  => 10,
            'orderIndex'   => 2,
            'identity' => 'latestProduct',
            'isEnable' => true
        ],
        3 => [
            'featuredProductCount'    => 10,
            'orderIndex'  => 3,
            'identity'  => 'featuredProduct',
            'isEnable' => true
        ],
        4 => [
            'popularProductCount'    => 10,
            'orderIndex'  => 4,
            'identity'  => 'popularProduct',
            'isEnable' => true
        ],
        5 => [
            'title'    => '3BoxBanner',
            'banner_1_section_1_image_thumb' => '',
            'banner_1_section_1_image' => '',
            'banner_1_section_1_heading_1' => '',
            'banner_1_section_1_heading_1_color' => '',
            'banner_1_section_1_heading_2' => '',
            'banner_1_section_1_heading_2_color' => '',
            'banner_1_section_1_description' => '',
            'banner_1_section_1_background_color' => '',
            'banner_1_section_2_image_thumb' => '',
            'banner_1_section_2_image' => '',
            'baner_1_section_2_heading_1' => '',
            'baner_1_section_2_heading_1_color' => '',
            'baner_1_section_2_heading_2' => '',
            'baner_1_section_2_heading_2_color' => '',
            'baner_1_section_2_description' => '',
            'baner_1_section_2_background_color' => '',
            'banner_1_section_3_image_thumb' => '',
            'banner_1_section_3_image' => '',
            'baner_1_section_3_heading_1' => '',
            'baner_1_section_3_heading_1_color' => '',
            'baner_1_section_3_heading_2' => '',
            'baner_1_section_3_heading_2_color' => '',
            'baner_1_section_3_description' => '',
            'baner_1_section_3_background_color' => '',
            'orderIndex'  => 5,
            'identity'  => 'bannerContent1',
            'isEnable'  =>  false
        ],
        6 => [
            'title'    => '2BoxBanner',
            'banner_2_section_1_image_thumb' => '',
            'banner_2_section_1_image' => '',
            'banner_2_section_1_heading_1' => '',
            'banner_2_section_1_heading_1_color' => '',
            'banner_2_section_1_heading_2' => '',
            'banner_2_section_1_heading_2_color' => '',
            'banner_2_section_1_description' => '',
            'banner_2_section_1_background_color' => '',
            'banner_2_section_2_image_thumb' => '',
            'banner_2_section_2_image' => '',
            'baner_2_section_2_heading_1' => '',
            'baner_2_section_2_heading_1_color' => '',
            'baner_2_section_2_heading_2' => '',
            'baner_2_section_2_heading_2_color' => '',
            'baner_2_section_2_description' => '',
            'baner_2_section_2_background_color' => '',
            'orderIndex'  => 6,
            'identity'  => 'bannerContent2',
            'isEnable'  =>  false
        ],
        7 => [
            'title' => 'productTabSection',
            'tab_section_title' => '',
            'tab_1_title' => '',
            'tab_1_products' => [],
            'tab_2_title' => '',
            'tab_2_products' => [],
            'tab_3_title' => '',
            'tab_3_products' => [],
            'tab_4_title' => '',
            'tab_4_products' => [],
            'orderIndex'  => 7,
            'identity'  => 'productTabContent',
            'isEnable'  =>  false
        ]
    ],

    /* Report duration 
    ------------------------------------------------------------------------- */
    'report_duration' => [
        1 => ('Current Month'),
        2 => ('Last Month'),
        3 => ('Current Week'),
        4 => ('Last Week'),
        5 => ('Today'),
        6 => ('Yesterday'),
        7 => ('Last Year'), 
        8 => ('Current Year'), 
        9 => ('Last 30 Days'), 
        10 => ('Custom')
    ],

    /* dashboard duration 
    ------------------------------------------------------------------------- */
    'dashboard_duration' => [
        1 => ('This Month'),
        2 => ('This Week'),
        3 => ('Today'),
        4 => ('Last Year'), 
        5 => ('Current Year'), 
        6 => ('Last 30 Days'), 
        7 => ('All Time')
    ],

	/* There is defined the key for social login providers
    ------------------------------------------------------------------------- */
    'social_login_driver' =>  [
        'via-google' 	=> 'google',
        'via-facebook'  => 'facebook',
        'via-twitter'   => 'twitter',
        'via-github'    => 'github'
    ],

    /* There is defined the key for social login providers
    ------------------------------------------------------------------------- */
    'social_login_driver_keys' =>  [
        'google'    => 'via-google',
        'facebook'  => 'via-facebook',
        'twitter'   => 'via-twitter',
        'github'    => 'via-github'
    ],

    /* There is defined the key for social login providers
    ------------------------------------------------------------------------- */
    'social_login_numbers' =>  [
        'google'    => 1,
        'facebook'  => 2,
        'twitter'   => 3,
        'github'    => 4
    ],

    'social_login' =>  [
        1 => 'google',
        2 => 'facebook',
        3 => 'twitter',
        4 => 'github'
    ],

    /* Payment modes
    ------------------------------------------------------------------------- */
    'env_settings' => [
        'paypal_test_mode'  => env('USE_PAYPAL_SANDBOX', true), 
        'stripe_test_mode'  => env('STRIPE_TEST_MODE', true),
        'iyzipay_test_mode' => env('USE_IYZIPAY_SANDBOX', true),
        'razorpay_test_mode'=> env('USE_RAZORPAY_SANDBOX', true),
        'paytm_test_mode'   => env('USE_PAYTM_SANDBOX', true),
        'instamojo_test_mode'   => env('USE_INSTAMOJO_SANDBOX', true),
        'paystack_test_mode'   => env('USE_PAYSTACK_SANDBOX', true)
    ],

    /* PayPal URLs
    ------------------------------------------------------------------------- */
    "paypal_urls" => [
        "production" => "https://www.paypal.com/cgi-bin/webscr",
        "sandbox" => "https://www.sandbox.paypal.com/cgi-bin/webscr",
    ],

    /* Iyzipay Payment modes
    ------------------------------------------------------------------------- */
    'iyzipay_urls' => [
        "production" => "https://api.iyzipay.com",
        "sandbox" => "https://sandbox-api.iyzipay.com",
    ],

    /* Iyzipay Payment modes
    ------------------------------------------------------------------------- */
    'instamojo_urls' => [
        "production"    => "https://www.instamojo.com/api/1.1/",
        "sandbox"       => "https://test.instamojo.com/api/1.1/",
    ],

    /* Paytm Txn Request Url
    ------------------------------------------------------------------------- */
    'paytm_urls' => [
        "paytm_txn_request_url" => "https://securegw-stage.paytm.in/theia/processTransaction"
    ],
    
    /* Security configurations for encrypting/decrypting form values
     * one can generate these keys using like given in below example:

        $ openssl genrsa -out rsa_1024_priv.pem 1024
        $ openssl rsa -pubout -in rsa_1024_priv.pem -out rsa_1024_pub.pem

        ---------- OR ------------

        $ openssl genrsa -out rsa_aes256_priv.pem -aes256
        $ openssl rsa -pubout -in rsa_aes256_priv.pem -out rsa_aes256_pub.pem

    ------------------------------------------------------------------------- */
    'form_encryption' => [

        /* Passphrse for RSA Key
        --------------------------------------------------------------------- */
        'default_rsa_passphrase' => 'y3C5YNTjuD%cGQDjZAp4supS$XRRcKUk2UcY8gVgJQ4',

        /* Default Public RSA Key
        --------------------------------------------------------------------- */

        'default_rsa_public_key' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsyxk7lyxXLDjXvoVFETF
4mEWC7ijLqqqjvDDFBKAudbL6bJFLNjh0re+k8urpRYs39bjEQW4lVXDgV6TI+rR
KkBwQuKZGayB/tDITDf/ZnoQM8gE4v8hshJrgdmUVwYxiuWa7BZry5YTpyrb73Xy
0GJ80PSMZhFh1OKJwVi65b00i47QnFSDqWLbnUNqsuXlOvJX6PUutT7EvXknVgLy
/wDX06a8EvyDeQv6F8M9T63caZ5POjPjDSwwkAxqQnnYtddmdZXXWutelad1A98p
0NJ7Tfy3NLqJIAFMd399ClVb3FoEPFnqewi6Goy6AywkpFTlZJ2p/b32KtRoKPS+
rwIDAQAB
-----END PUBLIC KEY-----',

        /* Default Private RSA Key
        --------------------------------------------------------------------- */

        'default_rsa_private_key' => '-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: DES-EDE3-CBC,F35C9F974C35747A

RDJG1/pelC57l778T0tJGRkGaOfo1pFe1G07CoTy3xbBDoWHnKOGWelY91yH8a7t
/laodvyAjfGw2vhKW0KtSLyGQBwXzmLSHm5rM4D7dFKJNHthaEEmjwK81vwlCvQY
sB5UHMkrcRC5K8gGfmsA01he/9w0H+XT0kQE2qh+eyhrTj1JRltbXYqVPdor23NV
Q/tny8l9rCwe3mVN2F7nqSQx0c/kHP/cSpVRePGoOBrovT4nwhH+K2wJbpQYsWQL
k1yo99orzrwHoUMrYeJrcNKZi1PG2xWqktRAA2JMpfvmKMbFSqm4VNZENXjX9LkR
NbaqvJdF+RsekY4YtUvuy1l/yJxHd+dJUlVp4oURp9UHpWbjJGOwtdXexW3drvqg
0pewAJRv+kC6QUm8X1iI/+Dis/Zn476AtdCpmN/JzBsvjubWhq0YvEOHxroQ2grw
Jo+QmpSl0oeAB93mnqPiNhnsg6Qx9WvWg4eEgx1oNo0VJdeOWR2dvCPTJ+4OA15y
1ZIYs4i91vcFtM0S3I8+KpXiSsXo036oaYUrhE6aGzowZr0dwVfO0hvo/K8kNRJ6
GkUJk/riHRZ9tkErcjCfGTBR2F2NZodunWc6EqzJaWcCpmpRWcdKBRRpgR1Nc90Z
IUpvrW4NL7UmfUULKe/nylDJUx1OxurFrIQ7A37hqk0M+w2b5K9jJslbY3uf/6mm
vTYM1YTsrDjBoYaZA/7uK3Hp7J7fRrRibWYfCax3Ry2cRjwz3kL+2Xbq9Axn9ZKs
W6QXHRv3FUgmn88NnthTyQxgVNM123z86vk7TzMFwwJ/tzRmavoCmy2WynKOwoIl
FTGO9ikt+feEjTMCImU4FpVWqJQCdWYPrjBCHalyrU+5Cl7hGgLFqvV8/qJrlWeh
/xoONLjwNHGt69VU7bdKYKTfqITfaHgcvuWTfED0Vez8iFJBX5yx7MKjM2uQ89a2
GDOTgPkKR7qToGxJa8sIjUsHPQERDf08tJ0ct7N3m2Hm+XH3NRZG0IEDKL6lBOwO
Qkjz9Mrzkrl1CW4GVXlTCGFGfEISYpXRSxJL1ebmeJkU+Qmm2gz8hjO1VrTFv1fv
r8Q8RlDhbjzuyHliFb3SJj79A8j9muttE3JGz/BFxI3QwtFXHmWUu0btVlT58/Qw
0IQ0f63xnHBx2fC79AC0Vj0iiWvmwyIlQFNWiplwIXtvcuZIcu2iewglu91r4TrH
TtGO/vMbdi/tKqWQXmegcJE5peksTST++8KvdZBzyzfszEMyC5iHlZzBE9juRYae
pICL9uUp8hbQfr/LPZ9FPqNk4Ccu9JqsPi+ZfoYvO56qbCGK272dI1/wJamFxCJL
Km5A3ZPlzJ00dclSkZZXBqP0Fz5TlwX/i8ppvvd0TgYS1OlR/VwqpwNisRCHbHZX
dZLtJYSo2wX2BFfOI3dCZNWZt37QLx7cDtoi+doT2aWioJcD9r7Quz9Y02LU4DUq
VexgPJ+i71U5uXLKn6n9e4Ayc3BonDuOSSScxoAMdeweacLor+NyyA0w++v/4W4X
z/yNMgnWP39Fwy19eg/cMg4jc+gtpgcmH9ggnqRPTZS7Rv3GdbofCec1jiU9veFs
-----END RSA PRIVATE KEY-----',
    ],
];

$appTechConfig = require base_path('user-tech-config.php');

return array_merge( $techConfig, $techAppConfig, $appTechConfig);