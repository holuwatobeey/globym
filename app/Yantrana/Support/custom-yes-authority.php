<?php 
/* 
 *  YesAuthority Configurations
 *
 *  This configuration file is part of YesAuthority
 *
 *------------------------------------------------------------------------------------------------*/
return [
    /* authority configurations
     *--------------------------------------------------------------------------------------------*/
    'config' => [
        /*
         *   @optional - define here your pseudo access ids
         *   psudo_access_ids
        */         
        'pseudo_access_ids'       => [
            'admin',
            'user'
        ],          
        /*
         *   @required - if you want use name other than 'authority.checkpost'
         *   middleware_name - YesAuthority Middleware name
        */    
        'middleware_name'           => 'authority.checkpost',
        /*
         *   @required
         *   col_user_id - ID column name for users table
        */        
        'col_user_id'           => 'id',

        /*
         *   @required
         *   col_role - Your Role ID column name for users table
        */        
        'col_role'              => 'role',

        /*
         *   @optional - if you want to use dynamic permissions
         *   col_user_permissions - Dynamic Permissions(json) column on users table 
         *   This column should contain json encoded array containing 'allow' & 'deny' arrays
        */
        'col_user_permissions'  => '__permissions',

        /*
         *   @required
         *   user_model - User Model
        */        
        'user_model'            => 'App\Yantrana\Components\User\Models\User',
        /*
         *   @optional
         *   role_model - Role Model
        */        
       'role_model'            => 'App\Yantrana\Components\User\Models\UserRole',
        /*
         *   @optional
         *   col_role_id - ID column name for role table
        */
        'col_role_id'           => '_id',        

        /*
         *   @optional
         *   ccol_role_permissions - Dynamic Permissions(json) column on role table, 
         *   This column should contain json encoded array containing 'allow' & 'deny' arrays
        */
        'col_role_permissions'  => '__permissions',

        'default_allowed_access_ids' => [
           'manage.dashboard.count_support_data',
           'user.address.process',
           'user.address.update',
           'user.address.delete',
           'user.change_email.process',
           'user.change_password.process',
           'user.profile.update',
           'user.profile.update.process',
           'user.profile',
           'user.change_password',
           'user.change_email',
           'user.change_email.support_data',
           'user.new_email.activation',
           'user.profile.details',
           'user.get.info',
           'manage.user.contact.process',
           'user.address.list',
           'user.get.addresses',
           'user.fetch.address.support.data',
           'get.addresses.for.order',
           'user.get.primary.address',
           'file_manager',
           'file_manager.files',
           'file_manager.file.delete',
           'file_manager.file.download',
           'file_manager.folder.add',
           'file_manager.folder.delete',
           'file_manager.folder.rename',
           'file_manager.file.rename',
           'manage.report.get.order_config_data'
       ]
    ],
    /* 
     *  Authority rules
     *
     *  Rules item needs to have 2 arrays with keys allow & deny value of it will be array
     *  containing access ids as required.
     *  wildcard entries are accepted using *
     *  for each section level deny will be more powerful than allow
     *  also key length also matters more is length more
     *--------------------------------------------------------------------------------------------*/     
    'rules' => [
        'base' => [
            'allow' => [
                '*',
            ],
            'deny' => [
                'installation.version.*'
            ]
        ],
        /*  
         *  Role Based rules
         *  First level of defense 
         *----------------------------------------------------------------------------------------*/    
        'roles' => [
            /*  
             *  Rules for the Roles for using id (key will be id)
             *------------------------------------------------------------------------------------*/
            // @example given for role id of 1
            1 => [ // this may be admin user role id
                'allow' => [
                    '*',
                    'admin',
                    'installation.version.*'
                ],
                'deny'  => [
                    'user'
                ],
            ],
            // user role permissions
            2 => [ // this may normal user role id
                'allow' => [
                    'user'
                ],
                'deny'  => [
                    '*',
                    'admin',
                    'manage.*',
                    "manage.configurations.*",
                    'manage.user.*',
                    'manage.pages.*',
                    'manage.order.payment.*',
                    'manage.report.*',
                    'manage.payment_report.*',
                    'manage.product_report.*',
                    'manage.order.*',
                    'order.payment.*',
                    'manage.brand.*',
                    'manage.category.*',
                    'manage.product.*',
                    'manage.specification_Preset.*',
                    'manage.product.awating_user.*',
                    'manage.shipping_type.*',
                    'store.settings.slider.*',
                    'manage.tax.*',
                    'manage.coupon.*',
                    'manage.brand.product.list',
                    'manage.category.product.list',
                    'store.settings.*',
                    'manage.get.orders.data',
                    'category.fancytree.support-data',
                    'manage.order.report.details.dialog',
                    'manage.shipping.*',
                    'order_download_invoice_pdf',
                    'manage.product.rating.read.list',
                    'installation.version.*'
                ],
            ],
        ],
        /* 
         *  User based rules
         *  2nd level of defense
         *  Will override the rules of above 1st level(roles) if matched
         *----------------------------------------------------------------------------------------*/                
        'users' => [
             /*  
             *  Rules for the Users for using id (key will be id)
             *------------------------------------------------------------------------------------*/
            // @example given for user id of 1
            //  1 => [ // this may be admin user id
            //     'allow' => ['*'],
            //     'deny'  => [],
            // ],
            // // Team Member permissions
            // 2 => [ // this may normal user  id
            //     'allow' => [
            //         'view_only_blog_post', // zone id can be used
            //         '*' // all the routes/idKeys are allowed
            //     ],
            //     'deny'  => [
            //         "manage.*"
            //     ],
            // ],
        ],
        /*  
         *  DB Role Based rules
         *  3rd level of defense 
         *  Will override the rules of above 2nd level(user) if matched
         *  As it will be database based you don't need to do anything here
         *----------------------------------------------------------------------------------------*/

        /*  
         *  DB User Based rules 
         *  4th level of defense 
         *  Will override the rules of above 3rd level(db roles) if matched
         *  As it will be database based you don't need to do anything here
         *----------------------------------------------------------------------------------------*/        

        /*  Dynamic permissions based on conditions
         *  Will override the rules of above 4th level(db user) if matched
         *  5th level of defense     
         * each condition will be array with following options available:
         *  @key - string - name
         *      @value - string - it will be condition identifier (alpha-numeric-dash)  
         *  @key - string - access_ids
         *      @value - array - of ids (alpha-numeric-dash)
         *  @key - string - uses
         *      @value - string - of of classNamespace@method
         *          OR
         *      @value - anonymous function -            
         *  @note - both the function/method receive following 3 parameters so you can 
         *          run your own magic of logic using it.
         *  $accessIdKey            - string - requested id key
         *  $isAccess               - bool - what is the access received from the above level/condition 
         *  $currentRouteAccessId   - current route/accessIds being checked.
         *----------------------------------------------------------------------------------------*/
        'conditions' => [
            // Example conditions
            //  It should return boolean values, true for access allow & false for deny
            /*[
                'name' => 'xyz',
                'access_ids' => ['demo_authority','delete_blog_post','*'],
                'uses' => 'App\Yantrana\XyzCondition@abc'
            ],
            [
                'name' => 'xyz2',
                'access_ids' => ['demo_authority','delete_blog_post','*'],
                'uses' => function ()
                {
                    return true;
                }
            ]*/
        ]
    ],

    /* 
     *  Dynamic access zones
     *
     *  Zones can be created for various reasons, when using dynamic permission system
     *  its bad to store direct access ids into database in that case we can create dynamic access
     *  zones which is the group of access ids & these can be handled with one single key id.
     *----------------------------------------------------------------------------------------*/   
    'dynamic_access_zones' => [

        /* manage section zones
        *
        *---------------------------------------------------------*/
        // view Section  
        'manage_section' => [
            'title' => "Manage Section",
            'access_ids' => [
            ],
        ],

        // view Section  
        'view_only_manage_section' => [
            'title' => "View Manage Section",
            'access_ids' => [
                'manage.app'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_section'
        ],

        /* manage user zones
        *
        *---------------------------------------------------------*/
        // view users  
        'manage_users' => [
            'title' => "Manage Users",
            'access_ids' => [
            ],
        ],

        // view users  
        'view_only_manage_users' => [
            'title' => "View Users",
            'access_ids' => [
                'manage.user.list',
                'manage.users.get.detail'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_users'
        ],

        // add User
        'add_user' => [
            'title' => 'Add User',
            'access_ids' => [
                'manage.user.add.read.supportData',
                'manage.user.add'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],
        // delete User
        'delete_and_restore_user' => [
            'title' => 'Delete And Restore User',
            'access_ids' => [
                'manage.user.delete',
                'manage.user.restore'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],
        // contact to user
        'contact_user' => [
            'title' => 'Contact To User',
            'access_ids' => [
                'manage.user.contact.info'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],

        // contact to user
        'change_user_password' => [
            'title' => 'Change User Password',
            'access_ids' => [
                'manage.user.change_password.process'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],

        // contact to user
        'view_user_orders' => [
            'title' => 'View User Orders',
            'access_ids' => [
                'manage.get.orders.data'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],

        // add or update permission for user
        'add_update_user_permissions' => [
            'title' => 'Add/Update Permissions',
            'access_ids' => [
                'manage.user.read.user_permissions',
                'manage.user.write.user_permissions'
            ],
            'dependencies' => [
                'view_only_manage_users'
            ],
            'parent' => 'manage_users'
        ],

        /* manage user zones
        *
        *---------------------------------------------------------*/
        // manage roles
        'manage_user_roles' => [
            'title' => "Manage User Roles",
            'access_ids' => [
            ],
        ],

        // view role  
        'view_only_manage_user_roles' => [
            'title' => "View User Roles",
            'access_ids' => [
                'manage.user.role_permission.read.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_user_roles'
        ],

        // view users  
        'add_role' => [
            'title' => "Add Role",
            'access_ids' => [
                'manage.user.role_permission.read.add_support_data',
                'manage.user.role_permission.write.role.create',
                'manage.user.role_permission.read.using_id'
            ],
            'dependencies' => [
                'view_only_manage_user_roles'
            ],
            'parent' => 'manage_user_roles'
        ],

        // view users  
        'delete_role' => [
            'title' => "Delete Role",
            'access_ids' => [
                'manage.user.role_permission.write.delete'
            ],
            'dependencies' => [
                'view_only_manage_user_roles'
            ],
            'parent' => 'manage_user_roles'
        ],

        // view users  
        'change_or_update_role_permission' => [
            'title' => "Update Role Permission",
            'access_ids' => [
                'manage.user.role_permission.read',
                'manage.user.role_permission.write.create'
            ],
            'dependencies' => [
                'view_only_manage_user_roles'
            ],
            'parent' => 'manage_user_roles'
        ],

        /* Pages Zones
        *
        *---------------------------------------------------------*/
        'manage_pages' => [
            'title' => "Manage Pages",
            'access_ids' => [
            ],
        ],

        // view pages
        'view_only_pages' => [
            'title' => "View Pages",
            'access_ids' => [
                'manage.pages.fetch.datatable.source',
                'manage.pages.get.parent.page',
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_pages'
        ],

        // Add or Edit Blog Post
        'add_edit_page' => [
            'title' => 'Add / Edit Page',
            'access_ids' => [
                'manage.pages.get.page_type',
                'manage.pages.add',
                'manage.pages.get.details',
                'manage.page.update.list.order',
                'manage.pages.update'
            ],
            'dependencies' => [
                'view_only_pages'
            ],
            'parent' => 'manage_pages'
        ],

        // Delete Faq
        'delete_page' => [
            'title' => 'Delete Page',
            'access_ids' => [
                'manage.pages.delete'
            ],
            'dependencies' => [
                'view_only_pages'
            ],
            'parent' => 'manage_pages'
        ],

        /* Payment Zones
        *
        *---------------------------------------------------------*/

        'manage_payments' => [
            'title' => "Manage Payments",
            'access_ids' => [
            ],
        ],

        // view pages
        'view_only_payments' => [
            'title' => "View Payments",
            'access_ids' => [
                'manage.order.payment.list',
                'manage.order.details.dialog',
                'manage.order.payment.detail.dialog'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_payments'
        ],

        // Delete Faq
        'delete_payment' => [
            'title' => 'Delete Payment',
            'access_ids' => [
                'manage.order.payment.delete'
            ],
            'dependencies' => [
                'view_only_payments'
            ],
            'parent' => 'manage_payments'
        ],
        
        // generate excel file
        'generate_payment_excel_file' => [
            'title' => 'Generate Payments Excel File',
            'access_ids' => [
                'manage.order.payment.excel_download'
            ],
            'dependencies' => [
                'view_only_payments'
            ],
            'parent' => 'manage_payments'
        ],

        /* Payment Zones
        *
        *---------------------------------------------------------*/
        'order_reports' => [
            'title' => "Order Reports",
            'access_ids' => [
            ]
        ],

        // view reports
        'view_only_reports' => [
            'title' => "View Reports",
            'access_ids' => [
                'manage.report.list',
                'manage.order.details.dialog'
            ],
            'dependencies' => [
            ],
            'parent' => 'order_reports'
        ],

        //download invoice
        'download_invoice_pdf' => [
            'title' => "Download Invoice",
            'access_ids' => [
                'manage.report.pdf_download'
            ],
            'dependencies' => [
                'view_only_reports'
            ],
            'parent' => 'order_reports'
        ],

        //download excel
        'generate_order_excel' => [
            'title' => "Generate Orders Excel",
            'access_ids' => [
                'manage.report.excel_download',
                'manage.report.payment_excel_download'
            ],
            'dependencies' => [
                'view_only_reports'
            ],
            'parent' => 'order_reports'
        ],

        /* Payment Report Zones
        *
        *---------------------------------------------------------*/
        'payment_reports' => [
            'title' => "Payment Reports",
            'access_ids' => [
            ]
        ],

        // view reports
        'view_payment_reports' => [
            'title' => "View Reports",
            'access_ids' => [
                'manage.payment_report.list',
            ],
            'dependencies' => [
            ],
            'parent' => 'payment_reports'
        ],

        //download excel
        'generate_payment_excel' => [
            'title' => "Generate Payment Excel",
            'access_ids' => [
                'manage.payment_report.payment_excel_download'
            ],
            'dependencies' => [
                'view_payment_reports'
            ],
            'parent' => 'payment_reports'
        ],

        /* Product Report Zones
        *
        *---------------------------------------------------------*/
        'product_reports' => [
            'title' => "Product Reports",
            'access_ids' => [
            ]
        ],

        // view reports
        'view_only_product_reports' => [
            'title' => "View Reports",
            'access_ids' => [
                'manage.product_report.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'product_reports'
        ],

        /* Product Rating Zones
        *
        *---------------------------------------------------------*/
        'product_rating' => [
            'title' => "Product Ratings",
            'access_ids' => [
            ]
        ],

        // view reports
        'view_only_product_rating' => [
            'title' => "View Ratings",
            'access_ids' => [
                'manage.product.rating.read.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'product_rating'
        ],

        /* Order Zones
        *
        *---------------------------------------------------------*/
        'manage_orders' => [
            'title' => "Manage Orders",
            'access_ids' => [
            ]
        ],

        //view orders
        'view_only_orders' => [
            'title' => "View Orders",
            'access_ids' => [
                'manage.report.excel_download',
                'manage.order.list',
                'manage.order.payment.detail.dialog',
                'manage.order.details.dialog',
                'manage.order.log.details.dialog'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_orders'
        ],

        //download invoice
        'order_download_invoice_pdf' => [
            'title' => "Download Invoice",
            'access_ids' => [
                'manage.order.report.pdf_download'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],

        //contact to users who placed orders
        'contact_user_for_orders' => [
            'title' => "Contact Users Who Placed Order",
            'access_ids' => [
                'manage.order.get.user.details',
                'manage.order.user.contact'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],

        'delete_order' => [
            'title' => "Delete Order",
            'access_ids' => [
                'manage.order.sandbox_order.delete',
                'manage.order.delete'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],

        //update order status
        'update_order_status' => [
            'title' => "Update Order Status",
            'access_ids' => [
                'manage.order.update.support.data',
                'manage.order.update'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],

        //update order status
        'update_payment_status' => [
            'title' => "Update Payment Status",
            'access_ids' => [
                'manage.order.payment.update.detail.dialog',
                'manage.order.payment.update.process'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],

        //refund order payment
        'refund_order_payment' => [
            'title' => "Refund Order Payment",
            'access_ids' => [
                'manage.order.payment.refund.detail.dialog',
                'manage.order.payment.refund.process'
            ],
            'dependencies' => [
                'view_only_orders'
            ],
            'parent' => 'manage_orders'
        ],


        /* settings zones
        *
        *---------------------------------------------------------*/

        'manage_settings' => [
            'title' => 'Manage Settings',
            'access_ids' => [
            ],
        ],

        'change_update_store_settings' => [
            'title' => 'Update Store Setting',
            'access_ids' => [
                'store.settings.edit.supportdata',
                'store.settings.edit',
                'media.upload.image',
                'media.delete',
                'media.delete.multiple',
                'media.uploaded.images',    
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_settings'
        ],

        /* Manage Shipping Type Zones
        *
        *---------------------------------------------------------*/
        'manage_shipping_type' => [
            'title' => 'Manage Shipping Type',
            'access_ids' => [
            ],
        ],

        // view Shipping Type
        'view_only_shipping_type' => [
            'title' => "View Shipping Type",
            'access_ids' => [
                'manage.shipping_type.read.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_shipping_type'
        ],
        // Add or Edit Shipping Type
        'add_edit_shipping_type' => [
            'title' => 'Add / Edit Shipping Type',
            'access_ids' => [
                'manage.shipping_type.write.create',
                'manage.shipping_type.read.update.data',
                'manage.shipping_type.write.update'
            ],
            'dependencies' => [
                'view_only_shipping_type'
            ],
            'parent' => 'manage_shipping_type'
        ],
        // Delete Shipping Type
        'delete_shipping_type' => [
            'title' => 'Delete Shipping Type',
            'access_ids' => [
                'manage.shipping_type.write.delete'
            ],
            'dependencies' => [
                'view_only_shipping_type'
            ],
            'parent' => 'manage_shipping_type'
        ],


        /* shipping zones
        *
        *---------------------------------------------------------*/
        'manage_shipping_rules' => [
            'title' => 'Manage Shipping Rules',
            'access_ids' => [
            ],
        ],

        'view_only_shipping_rules' => [
            'title' => 'View Shipping Rules',
            'access_ids' => [
                'manage.shipping.list',
                'manage.shipping.aoc.editSupportData',
                'manage.shipping.detailSupportData',
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_shipping_rules'
        ],

        //add/edit rule
        'add_edit_shipping_rule' => [
            'title' => 'Add / Edit shipping rule',
            'access_ids' => [
                'manage.shipping.fetch.contries',
                'manage.shipping.add',
                'manage.shipping.aoc.update',
                'manage.shipping.editSupportData',
                'manage.shipping.edit.process'
            ],
            'dependencies' => [
                'view_only_shipping_rules'
            ],
            'parent' => 'manage_shipping_rules'
        ],

        //delete rule
        'delete_shipping_rule' => [
            'title' => 'Delete Shipping Rule',
            'access_ids' => [
                'manage.shipping.delete'
            ],
            'dependencies' => [
                'view_only_shipping_rules'
            ],
            'parent' => 'manage_shipping_rules'
        ],

        /* Brand Zones
        *
        *---------------------------------------------------------*/
        'manage_brands' => [
            'title' => 'Manage Brands',
            'access_ids' => [
            ],
        ],

        // view pages
        'view_only_brand' => [
            'title' => "View Brand",
            'access_ids' => [
                'manage.brand.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_brands'
        ],
        // Add or Edit Brand
        'add_edit_brand' => [
            'title' => 'Add / Edit Brand',
            'access_ids' => [
                'manage.brand.add',
                'manage.brand.editSupportData',
                'manage.brand.edit.process',
                'media.upload.image',
                'media.delete',
                'media.delete.multiple',
                'media.uploaded.images',    
            ],
            'dependencies' => [
                'view_only_brand'
            ],
            'parent' => 'manage_brands'
        ],

        //view brand product list
        'view_brand_product_list' => [
            'title' => 'View Brand Product List',
            'access_ids' => [
                'manage.brand.product.list',
            ],
            'dependencies' => [
                'view_only_brand'
            ],
            'parent' => 'manage_brands'
        ],
        
        // Delete Brand
        'delete_brand' => [
            'title' => 'Delete Brand',
            'access_ids' => [
                'manage.brand.delete'
            ],
            'dependencies' => [
                'view_only_brand'
            ],
            'parent' => 'manage_brands'
        ],


        /* Product & Category Zones
        *
        *---------------------------------------------------------*/
        'manage_category' => [
            'title' => 'Manage Categories',
            'access_ids' => [
            ],
        ],

        // view category
        'view_only_category' => [
            'title' => "View Category",
            'access_ids' => [
                'manage.category.list',
                'category.get.supportData'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_category'
        ],
        
        // Add or Edit Category
        'add_edit_category' => [
            'title' => 'Add / Edit Category',
            'access_ids' => [
                'category.fancytree.support-data',
                'manage.category.add',
                'manage.category.get.details',
                'manage.category.update'
            ],
            'dependencies' => [
                'view_only_category'
            ],
            'parent' => 'manage_category'
        ],

        // Delete Category
        'delete_category' => [
            'title' => 'Delete Category',
            'access_ids' => [
                'manage.category.delete'
            ],
            'dependencies' => [
                'view_only_category'
            ],
            'parent' => 'manage_category'
        ],

        /* Product Zones
        *
        *---------------------------------------------------------*/
        'manage_products' => [
            'title' => 'Manage Products',
            'access_ids' => [
            ],
        ],

        // view Product
        'view_only_product' => [
            'title' => "View Only Product",
            'access_ids' => [
                'manage.product.list',
                'manage.category.product.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_products'
        ],

        // Add or Edit Product
        'add_edit_product' => [
            'title' => 'Add / Edit Product',
            'access_ids' => [
                'manage.product.add.supportdata',
                'manage.product.add',
                'manage.product.fetch.name',
                'manage.product.edit.details.supportdata',
                'manage.product.edit',
                'media.upload.image',
                'media.delete',
                'media.delete.multiple',
                'media.uploaded.images',
                'manage.product.update_status'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],


        'manage_brand_product_list' => [
            'title' => 'View Brand Product List',
            'access_ids' => [
                'manage.brand.product.list',
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // Delete Product
        'delete_product' => [
            'title' => 'Delete Product',
            'access_ids' => [
                'manage.product.delete'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // Delete Product
        'product_options' => [
            'title' => 'Product Options',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // Delete Product
        'view_product_options' => [
            'title' => 'View Options',
            'access_ids' => [
                'manage.product.option.list',
                'manage.product.fetch.name'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_options'
        ],

        // Add or Edit Product
        'add_edit_product_option' => [
            'title' => 'Add/Edit Options',
            'access_ids' => [
                'manage.product.option.value.list',
                'manage.product.option.value.edit',
                'manage.product.option.edit.supportdata',
                'manage.product.option.edit',
                'media.upload.image',
                'manage.product.option.add',
                'media.delete',
                'media.delete.multiple',
                'media.uploaded.images',
                'manage.product.option.value.delete'
            ],
            'dependencies' => [
                'view_product_options'
            ],
            'parent' => 'product_options'
        ],

        // Delete Product images
        'delete_product_option' => [
            'title' => 'Delete Product Option',
            'access_ids' => [
                'manage.product.option.delete',

            ],
            'dependencies' => [
                'view_product_options'
            ],
            'parent' => 'product_options'
        ],

        //  Product Images
        'product_images' => [
            'title' => 'Product Images',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // view Product images
        'view_product_images' => [
            'title' => 'View Product Images',
            'access_ids' => [
                'manage.product.image.list',
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_images'
        ],

        // add edit images
        'add_edit_product_images' => [
            'title' => 'Add/Edit Product Image',
            'access_ids' => [
                'manage.product.image.add',
                'manage.product.image.edit.supportdata',
                'manage.product.image.edit',
                'manage.product.image.update.list.order',
                'media.upload.image',
                'media.delete',
                'media.delete.multiple',
                'media.uploaded.images',

            ],
            'dependencies' => [
                'view_product_images'
            ],
            'parent' => 'product_images'
        ],

        // Delete Product images
        'delete_product_images' => [
            'title' => 'Delete Product Image',
            'access_ids' => [
                'manage.product.image.delete',

            ],
            'dependencies' => [
                'view_product_images'
            ],
            'parent' => 'product_images'
        ],


        // Delete Product
        'product_specifications' => [
            'title' => 'Product Specifications',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // Delete Product
        'view_product_specifications' => [
            'title' => 'View Product Specifications',
            'access_ids' => [
                'manage.product.specification.list',
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_specifications'
        ],

        // add edit images
        'add_edit_product_specifications' => [
            'title' => 'Add/Edit Product Specifications',
            'access_ids' => [
                'manage.product.specification.get.all',
                'manage.product.specification.add',
                'manage.product.specification.edit',
                'manage.product.specification.update'
            ],
            'dependencies' => [
                'view_product_specifications'
            ],
            'parent' => 'product_specifications'
        ],

        // Delete Product images
        'delete_product_specifications' => [
            'title' => 'Delete Product Specifications',
            'access_ids' => [
                'manage.product.specification.delete',
                'manage.product.specification.change_preset'

            ],
            'dependencies' => [
                'view_product_specifications'
            ],
            'parent' => 'product_specifications'
        ],


        // Delete Product
        'product_ratings' => [
            'title' => 'Product Ratings',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // add edit images
        'view_only_product_ratings' => [
            'title' => 'View Product Ratings',
            'access_ids' => [
                'manage.product.ratings.read.list'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_ratings'
        ],

        // add edit images
        // 'add_edit_product_rating' => [
        //     'title' => 'Add Product Ratings',
        //     'access_ids' => [
        //      'product.rating.add',
        //      'product.process.add.review'
        //     ],
        //     'dependencies' => [
        //         'view_only_product'
        //     ],
        //     'parent' => 'product_ratings'
        // ],

        // Delete Product images
        'delete_product_rating' => [
            'title' => 'Delete Product Ratings',
            'access_ids' => [
                'manage.product.rating.write.delete'
            ],
            'dependencies' => [
                'view_only_product_ratings'
            ],
            'parent' => 'product_ratings'
        ],

        // Delete Product faqs
        'product_faqs' => [
            'title' => 'Product Faqs',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // add edit faqs
        'view_only_product_faqs' => [
            'title' => 'View Product Faqs',
            'access_ids' => [
                'manage.product.faq.read.list'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_faqs'
        ],

        // add edit faqs
        'add_edit_product_faqs' => [
            'title' => 'Add Product Faqs',
            'access_ids' => [
                'manage.product.faq.add',
                'manage.product.faq.editData',
                'manage.product.faq.update'
            ],
            'dependencies' => [
                'view_only_product_faqs'
            ],
            'parent' => 'product_faqs'
        ],

        // Delete Product faqs
        'delete_product_faqs' => [
            'title' => 'Delete Product Faqs',
            'access_ids' => [
                'manage.product.faq.delete'
            ],
            'dependencies' => [
                'view_only_product_faqs'
            ],
            'parent' => 'product_faqs'
        ],

        // Delete Product Awating User
        'product_awating_user' => [
            'title' => 'Product Awating User',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // view Awating User
        'view_only_product_awating_user' => [
            'title' => 'View Product Awating User',
            'access_ids' => [
                'manage.product.awating_user.read.list'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'product_awating_user'
        ],

        // send Awating User
        'send_product_awating_user_mail' => [
            'title' => 'Notify Awating Customer',
            'access_ids' => [
                'manage.product.awating_user.notify_mail.send'
            ],
            'dependencies' => [
                'view_only_product_awating_user'
            ],
            'parent' => 'product_awating_user'
        ],

        // Delete Product Awating User
        'delete_product_awating_user' => [
            'title' => 'Delete Product Awating User',
            'access_ids' => [
                'manage.product.awating_user.delete.multipleUser',
                'manage.product.awating_user.delete'
            ],
            'dependencies' => [
                'view_only_product_awating_user'
            ],
            'parent' => 'product_awating_user'
        ],

        // Delete Product Awating User
        'manage_product_seo_meta' => [
            'title' => 'Product SEO Meta',
            'access_ids' => [
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_products'
        ],

        // view Awating User
        'view_only_product_seo_meta' => [
            'title' => 'Update Product SEO Meta',
            'access_ids' => [
                'manage.product.seo_meta.read',
                'manage.product.seo_meta.write'
            ],
            'dependencies' => [
                'view_only_product'
            ],
            'parent' => 'manage_product_seo_meta'
        ],

        /* Manage Specification Preset Zones
        *
        *---------------------------------------------------------*/
        'manage_specification_preset' => [
            'title' => 'Manage Specification Preset',
            'access_ids' => [
            ],
        ],

        // view Specification Preset
        'view_only_specification_preset' => [
            'title' => "View Specification Preset",
            'access_ids' => [
                'manage.specification_preset.read.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_specification_preset'
        ],
        // Add or Edit Specification Preset
        'add_edit_specification_preset' => [
            'title' => 'Add / Edit Specification Preset',
            'access_ids' => [
                'manage.specification_preset.write.add',
                'manage.specification_preset.read.editSupportadd',
                'manage.specification_preset.write.update',
                'manage.specification_preset.specification.delete'
            ],
            'dependencies' => [
                'view_only_specification_preset'
            ],
            'parent' => 'manage_specification_preset'
        ],
        // Delete Specification Preset
        'delete_specification_preset' => [
            'title' => 'Delete Specification Preset',
            'access_ids' => [
                'manage.specification_preset.write.delete'
            ],
            'dependencies' => [
                'view_only_specification_preset'
            ],
            'parent' => 'manage_specification_preset'
        ],

        /* Tax Zones
        *
        *---------------------------------------------------------*/
        'manage_tax' => [
            'title' => 'Manage Tax',
            'access_ids' => [
            ],
        ],

        // view Tax
        'view_only_tax' => [
            'title' => "View Tax",
            'access_ids' => [
                'manage.tax.list',
                'manage.tax.detailSupportData'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_tax'
        ],
        // Add or Edit Tax
        'add_edit_tax' => [
            'title' => 'Add / Edit Tax',
            'access_ids' => [
                'manage.tax.fetch.contries',
                'manage.tax.add',
                'manage.tax.editSupportData',
                'manage.tax.edit.process'
            ],
            'dependencies' => [
                'view_only_tax'
            ],
            'parent' => 'manage_tax'
        ],
        // Delete Tax
        'delete_tax' => [
            'title' => 'Delete Tax',
            'access_ids' => [
                'manage.tax.delete'
            ],
            'dependencies' => [
                'view_only_tax'
            ],
            'parent' => 'manage_tax'
        ],

        /* Manage Slider Zones
        *
        *---------------------------------------------------------*/
        'manage_slider' => [
            'title' => 'Manage Slider',
            'access_ids' => [
            ],
        ],

        // view Slider Template
        'view_only_slider' => [
            'title' => "View Slider",
            'access_ids' => [
                'store.settings.slider.read.list'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_slider'
        ],

        // Add or Edit Slider
        'add_edit_slider' => [
            'title' => 'Add / Edit Slider',
            'access_ids' => [
                'store.settings.slider.write.addSlider',
                'store.settings.slider.read.update.data',
                'store.settings.slider.write.update'
            ],
            'dependencies' => [
                'view_only_slider'
            ],
            'parent' => 'manage_slider'
        ],

        // Delete Slider
        'delete_slider' => [
            'title' => 'Delete Slider',
            'access_ids' => [
                'store.settings.slider.write.delete'
            ],
            'dependencies' => [
                'view_only_slider'
            ],
            'parent' => 'manage_slider'
        ],

        /* Email Template Zones
        *
        *---------------------------------------------------------*/
        'manage_email_templates' => [
            'title' => 'Manage Email Templates',
            'access_ids' => [
            ],
        ],

        // view Email Template
        'view_only_email_template' => [
            'title' => "View Email Templates",
            'access_ids' => [
                'store.settings.get.email-template.data'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_email_templates'
        ],

        // Add or Edit Tax
        'add_edit_email_template' => [
            'title' => 'Edit Email Template',
            'access_ids' => [
                'store.settings.get.edit.email-template.data',
                'store.settings.email_subject.delete',
                'store.settings.email_template.delete',
                'store.settings.email_template.edit'
            ],
            'dependencies' => [
                'view_only_email_template'
            ],
            'parent' => 'manage_email_templates'
        ],


        /* Product Zones
        *
        *---------------------------------------------------------*/
        'manage_coupon_and_discount' => [
            'title' => 'Manage Coupon and Discount',
            'access_ids' => [
            ],
        ],

        // view Coupon and Discount
        'view_only_coupon_and_discount' => [
            'title' => "View Coupon and Discount",
            'access_ids' => [
                'manage.coupon.list',
                'manage.coupon.detailSupportData'
            ],
            'dependencies' => [
            ],
            'parent' => 'manage_coupon_and_discount'
        ],
        // Add or Edit Coupon and Discount
        'add_edit_coupon_and_discount' => [
            'title' => 'Add / Edit Coupon and Discount',
            'access_ids' => [
                'manage.coupon.editSupportData',
                'manage.coupon.edit.process',
                'manage.coupon.fetch.couponDiscountType',
                'manage.coupon.add'
            ],
            'dependencies' => [
                'view_only_coupon_and_discount'
            ],
            'parent' => 'manage_coupon_and_discount'
        ],
        // Delete Coupon & Discount
        'delete_coupon_and_discount' => [
            'title' => 'Delete Coupon and Discount',
            'access_ids' => [
                'manage.coupon.delete'
            ],
            'dependencies' => [
                'view_only_coupon_and_discount'
            ],
            'parent' => 'manage_coupon_and_discount'
        ],
    ],

    'entities' => [
        /*'project' => [
            'model' => 'App\Yantrana\Components\User\Models\UserAuthorityModel',
            'id_column' => '_id',
            'permission_column' => '__permissions',
            'user_id_column' => 'users__id'
        ]*/
    ]
];