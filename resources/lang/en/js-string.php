<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Javascript Stings Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the client side javascript
    | for handle javascript notification & other messages.
    |
    */
    
    'confirm_title'                 => __tr('Are you sure?'),
    'confirm_error_title'           => __tr('Deleted!'),
    'confirm_cancel_button_text'    => __tr('Cancel'),
    'delete_action_button_text'     => __tr('Yes, delete it'),
    'cancel_action_button_text'     => __tr('Yes, cancel it'),
    'cancel_error_title'            => __tr('No, not Cancelled'),
    'cancel_success_title'          => __tr('Cancelled!'),
    'reload_text'					=> __tr('Reload'),
    'order_payment_confirm_text'    => __tr('Yes'),
    'restore_action_button_text'    => __tr('Yes, restore it'),
    'user_delete_confirm_text'      => __tr('you want to delete __name__ user.'),
    'user_restore_confirm_text'     => __tr('you want to restore __name__ user.'),
    'product_delete_confirm_text'   => __tr('you want to delete __name__ product.'),
    'product_image_delete_confirm_text'   => __tr('you want to delete __title__ product image.'),
    'product_option_delete_confirm_text'   	=> __tr('you want to delete __name__ product option.'),
    'product_specification_delete_confirm_text'   => __tr('you want to delete __name__ product specification.'),
    'product_option_value_delete_confirm_text'   => __tr('you want to delete option value.'),
    'product_option_value_add_form_notification' => __tr('It will be values for __option_name__ option dropdown.'),
    'product_add_title_text'       	 => __tr('Add Product for __name__ category'),
    'product_edit_title_text'       	 => __tr('Edit Product : __name__'),

    // Category delete related message
    'category_delete_note_text'     		=> __tr('you want to delete <strong> __name__ </strong> category, all the products belongs to this category will be deleted'),
    'category_delete_input_placeholder_text' => __tr('by entering confirmation number'),
    'category_delete_confirm_note' 			 => __tr('Please confirm'),
    'category_delete_required_input' 		 => __tr('Please confirm your decision by entering correct number.'),
    'category_delete_wrong_input' 		 	=> __tr('You have entered wrong confirmation number.'),
    'category_add_title_text' 		 		=> __tr( 'Add Category in __name__' ),
    // page delete related message
    'page_delete_confirm_text'        		=> __tr('you want to delete __name__ page.'),
    /* breadcrumb relate strings*/
    'change_password'        				=> __tr('Change password'),
    'login'        							=> __tr('Login'),
    'forgot_password'        				=> __tr('Forgot password'),
    'profile_edit'        				    => __tr('Edit profile'),
    'user_register'        				    => __tr('Register'),
    'change_email'        					=> __tr('Change email'),
    'User_account_activation'        		=> __tr('User account activation'),
    'user_new_email_activation'        		=> __tr('User new email activation'),
    'reset_password'        				=> __tr('Reset password'),
    'featured_products'        				=> __tr('Featured products'),
    'products'        						=> __tr('Products'),
    'cancel_order'        					=> __tr('you want to cancel this order'),
    'cancel_action_button_text'     		=> __tr('Yes, cancel it'),
    'no_action_button_text'                 => __tr('No'),
    'order_title'                 			=> __tr('Success'),
    'order_text'                 			=> __tr('Your order has been placed successfully.'),
    'order_text_id'							=> __tr('Your order __orderID__'),
    'order_button'							=> __tr('Continues Shopping'),
    'brand_delete_text'						=> __tr('you want to delete <strong> __name__ </strong> brand.'),
    'coupon_delete_text'					=> __tr('you want to delete __name__ coupon.'),
    'shipping_delete_text'					=> __tr('you want to delete this shipping rule.'),
    'tax_delete_text'						=> __tr('you want to delete this tax.'),
    'address_delete_text'					=> __tr('you want to delete this address.'),
    'loading_text'							=> __tr('Uploading..'),
    'file_uploaded_text'					=> __tr('file uploaded'),
    'user_change_password_title_text'       => __tr('Change Password of __name__'),
    'update_order_dialog_title_text'        => __tr('Update Order of __name__ ( __orderUID__ ) '),
    'reinitiate_order_dialog_title_text'    => __tr('Re-initiate Order of __name__ ( __orderUID__ ) '),
    'addon_option_affect_string'    		=> __tr('Addon option may affect price.'),
    'order_payment_update_string'    		=> __tr('Order Payment Update successfully.'),
    'order_payment_change_status_string'    => __tr('Do you want to change order status also.'),
    'order_refund_string'    				=> __tr('Order Status Changed to cancel.'),
    'order_refund_change_status_string'    	=> __tr('Do you want to process to change payment refund also.'),
    'manage_order_title'			    	=> __tr('Manage Orders of __name__'),
    'status_array_item_in_report'			=> __tr('All'),
    'order_coupon_amount_text'				=> __tr('Minimum __amount__ order amount is required for the requested coupon.'),
    'logo_empty_file_uploaded_text' => __tr('Only PNG images are expected.'),
    'delete_sandbox_order_msg'      => __tr( 'You want to delete this sandbox order, all the payment transaction related to this order will deleted as well.'),
    'delete_all_sandbox_order_msg'  => __tr( 'You want to delete this all sandbox orders.'),
    'delete_sandbox_payment_msg'      => __tr( 'You want to delete this sandbox payment, all the order related to this payment will deleted as well.'),
    'delete_all_sandbox_payment_msg'  => __tr( 'You want to delete this all sandbox payments.'),
    'other'  => __tr('Other'),
	'delete_rating'  => __tr( 'you want to delete this rating.'),
    'order_error_messages'  => __tr('Ooops... Validation Errrors...!!'),
    'order_cancellation_text'                   => __tr('You want to cancel this order.'),
];
