<table cellspacing="0" cellpadding="0" width="600" class="free-text" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
    <tbody>
        <tr style="font-family: Helvetica, Arial, sans-serif;">
            <td class="button" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 30px 0; text-align: center;">
            <div style="font-family: Helvetica, Arial, sans-serif;">
                <a href="{__orderDetailsUrl__}" style="-webkit-text-size-adjust: none; background-color: #<?= getStoreSettings('logo_background_color') ?>; border-radius: 5px; color: #ffffff; display: inline-block; font-family: 'Cabin', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: regular; line-height: 45px; mso-hide: all; text-align: center; text-decoration: none !important; width: 155px;"><?= ( 'Order Details' ) ?></a></div>
            </td>
        </tr>

        <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: left; width: 253px;">
            <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Placed on' ) ?></span><br style="font-family: Helvetica, Arial, sans-serif;"> {__orderPlacedOn__}
            <br style="font-family: Helvetica, Arial, sans-serif;">
            <br style="font-family: Helvetica, Arial, sans-serif;">
            <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Order ID' ) ?></span>
            <br style="font-family: Helvetica, Arial, sans-serif;"> <a href="{__orderDetailsUrl__}">{__orderUID__}</a>
            <br style="font-family: Helvetica, Arial, sans-serif;">
            <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Payment Status' ) ?></span>
            <br style="font-family: Helvetica, Arial, sans-serif;"> {__formatedPaymentStatus__}
            <br style="font-family: Helvetica, Arial, sans-serif;">
            <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Payment Method' ) ?></span>
            <br style="font-family: Helvetica, Arial, sans-serif;">{__formatedPaymentMethod__}
          </td>
        </tr>

        <tr style="font-family: Helvetica, Arial, sans-serif;">
        <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: left; width: 253px;">
             	{__shippingAddressTemplate__}
           		{__billingAddressTemplate__}
          </td>
        </tr>
    </tbody>
</table>
<br style="font-family: Helvetica, Arial, sans-serif;">
<br style="font-family: Helvetica, Arial, sans-serif;">

<table cellspacing="0" cellpadding="0" width="600" class="free-text" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
<tbody>
        <tr style="font-family: Helvetica, Arial, sans-serif;">
            <td align="center" valign="top" width="100%" style="background-color: #ffffff; border-bottom: 1px solid #e5e5e5; border-collapse: collapse; border-top: 1px solid #e5e5e5; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; text-align: center;">
                <center style="font-family: Helvetica, Arial, sans-serif;">
                <table cellpadding="0" cellspacing="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif; text-align:center; margin:15px 0 0px 0; ">
                    <tbody>
                        <tr>
                          <td><span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0; "> <br> <?= ( 'Ordered Items' ) ?></span></td>
                        </tr>
                    </tbody>
                </table>            
                <table cellpadding="0" cellspacing="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif; ">
                  <tbody>
                    <tr style="font-family: Helvetica, Arial, sans-serif;">
                      <td class="item-table" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 50px 20px; text-align: center; width: 560px;">
                        <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
                            <tbody>
                                <tr style="font-family: Helvetica, Arial, sans-serif;">
                                  <td class="title-dark" width="300" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: left;">
                                    <?= ('Item Description') ?>
                                  </td>
                                  <td class="title-dark" width="163" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: center;">
                                    <?= ('Qty') ?>
                                  </td>
                                  <td class="title-dark" width="97" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: right;">
                                    <?= ('Price') ?>
                                  </td>
                                  <td class="title-dark" width="97" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: right;">
                                    <?= ('Total') ?>
                                  </td>
                                </tr>
                                {__productsTemplate__}
                                <tr style="font-family: Helvetica, Arial, sans-serif;">
                                  <td class="item-col item mobile-row-padding" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top; width: 300px;"></td>
                                  <td class="item-col quantity" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                                  <td class="item-col price" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                                  <td class="item-col price" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                                </tr>


                                <tr style="font-family: Helvetica, Arial, sans-serif;">
                                    <td colspan="3" class="item-col quantity" style="border-collapse: collapse; border-top: 1px solid #cccccc; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-right: 10px; padding-top: 20px; text-align: right; vertical-align: top;">

                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"><?= ( 'Cart Total' ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"> <?= ( 'Discount' ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                 
        	                            <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"><?= ( 'Coupon Discount' ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                     
                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">
                                            	{__taxTemplate__}
                                        </span>
                                       <br style="font-family: Helvetica, Arial, sans-serif;">

                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">
                                        	<?=  ('Shipping')  ?> {__shippingMethodTemplate__}
                                        	</span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                        <span class="total-space" style="color: #4d4d4d; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-weight: bold; padding-bottom: 8px;"><?=  ('Total Payable Amount')  ?></span>
                                    </td>

                                    <td class="item-col price" style="border-collapse: collapse; border-top: 1px solid #cccccc; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: right; vertical-align: top; width: 150px;">

                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">{__formatedSubtotal__}</span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">- {__shortFormatDiscount__}</span> <br style="font-family: Helvetica, Arial, sans-serif;">                             
                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px; text-align:right;">- {__formatedOrderDiscount__}</span> <br style="font-family: Helvetica, Arial, sans-serif;">

                                        <span class="total-space" style="text-align:right; display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"> {__taxTemplateAmount__}
                                        </span> 
                                        <br style="font-family: Helvetica, Arial, sans-serif;">

                                        <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; text-align:right; padding-bottom: 8px;">
                                        	{__formatedShippingAmount__}
                                        </span> <br style="font-family: Helvetica, Arial, sans-serif;">

                                        <span class="total-space" style="color: #4d4d4d; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-weight: bold; ">{__formatedTotalOrderAmount__}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                      </td>
                    </tr>

                  </tbody>
                </table>
            </center>
            </td>
        </tr>
    </tbody>
</table>