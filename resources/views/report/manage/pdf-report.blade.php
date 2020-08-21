<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <style type="text/css">
            body {
                font-family: 'roboto', 'DejaVu Sans', sans-serif;
            }
            /** {
              font-family: DejaVu Sans Mono, sans-serif, monospace;
            }*/
            #page-wrap {
                width: 700px;
                margin: 0 auto;
            }
            .center-justified {
                text-align: justify;
                margin: 0 auto;
                width: 30em;
            }
            table.outline-table {
                border: 1px solid #D3D3D3;
                border-spacing: 0;
            }
            tr.border-bottom td, td.border-bottom {
                border-bottom: 1px solid #D3D3D3;
            }
            tr.border-top td, td.border-top {
                border-top: 1px solid #D3D3D3;
            }
            td.border-thin {
                border-top: 1px;
            }
            tr.border-right td, td.border-right {
                border-right: 1px solid #D3D3D3;
            }
            tr.border-right td:last-child {
                border-right: 0px #D3D3D3;
            }
            tr.center td, td.center {
                text-align: center;
                vertical-align: text-top;
            }
            td.pad-left {
                padding-left: 5px;
            }
            tr.right-center td, td.right-center {
                text-align: right;
                padding-right: 50px;
            }
            tr.right td, td.right {
                text-align: right;
            }
            .grey {
                background:grey;
            }
            .lw-text-font-size {
                font-size: 14px;    
            }
            div.lw-footer {
                position: fixed;
                width: 100%;
                border: 0px solid #888;
                overflow: hidden;
                padding: 0.1cm;
            }
            div.lw-footer {
                padding-top: 35px;
                padding-bottom: 10px;
                bottom: 10px;
                left: 20px;
                right: 20px;
                border-top-width: 1px;
                height: 0.5cm;
            }

            .lw-pdf-page-number {
              text-align: left;
              font-size: 12px;
            }
            
            .lw-pdf-page-number:before {
              content: counter(page);
            }

            hr {
              page-break-after: always;
              border: 0;
            }

            span.lw-horizantal-line {
                height: 30px;
                border-style: solid;
                border-color: #D3D3D3;
                border-width: 1px 0 0 0;
                /*border-radius: 20px;*/
            }
            span.lw-horizantal-line:before { /* Not really supposed to work, but does */
                display: block;
                content: "";
                height: 30px;
                margin-top: -31px;
                border-style: solid;
                border-color: #D3D3D3;
                border-width: 0 0 1px 0;
                /*border-radius: 20px;*/
            }

        </style>
    </head>
    <body>
	    <div id="page-wrap">

            <table width="100%">
                <tr style="height:100px">
                    <td style="width: 70%" class="left">
                        <span style="background: #<?=  getStoreSettings('logo_background_color')  ?>"><img width="250px" height="80px" src="<?=  getInvoiceImageUrl()  ?>"></span>
                    </td>
                    <td valign="top" class="right">
                        <strong> <?= __tr('Invoice') ?> #<?=  $orderDetails['data']['order']['orderUID']  ?> </strong><br>
                        <span class="lw-text-font-size"><?=  $orderDetails['formatcurrentDate']  ?></span> <br><br>
                        <span style="text-align: right;"><?=  $orderDetails['orderBarcode']  ?></span>
                    </td>
                </tr>
            </table>
            <br>

            <table width="100%">
                <tr>
                    <td valign="top" class="left" width="60%">
                        @if(!__isEmpty(getStoreSettings('contact_address')))
                            <strong><small><?= __tr('Ship-from Address: ') ?></small></strong>
                            <span class="lw-text-font-size"><?= __transliterate('contact_setting', null, 'contact_address', getStoreSettings('contact_address') ) ?></span>
                            
                        @endif
                        <br>
                        <strong><small><?= __tr('Store Name: ') ?></small></strong>
                        <span class="lw-text-font-size"><?=  __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') )  ?></span>
                       <br><br>
                    </td>
                    <td valign="top" width="40%">
                        <strong><small><?= __tr('Customer Info') ?></small></strong><br>
                        <span class="lw-text-font-size">
                            Name : <?=  $orderDetails['data']['user']['fullName']  ?><br>
                            Email: <?=  $orderDetails['data']['user']['email']  ?>
                        </span>
                    </td>
                </tr>
            </table>

            @if ($orderDetails['data']['order']['paymentCompletedOn'])
            <div style="color: green;text-align:center;">
                <strong><i><?= __tr('Payment paid on ') ?><?= $orderDetails['data']['order']['paymentCompletedOn'] ?></i></strong>
            </div>
            @endif
            <br>
            <span class="lw-horizantal-line"></span>

            <table width="100%">
                <tbody>
                    <tr>
                        <td width="50%"  class="left lw-text-font-size" valign="top">
                        	<strong><small><?= __tr('Billed To:') ?></small></strong><br>

                            <?=  $orderDetails['data']['user']['fullName']  ?>
                            <br>
                            @if($orderDetails['data']['address']['sameAddress'] == true)
                                <?= __tr('Same as Shipping Address') ?>
                            @endif

                            @if(!empty($orderDetails['data']['address']['billingAddress']) and $orderDetails['data']['address']['sameAddress'] == false)
                                <?=  $orderDetails['data']['address']['billingAddress']['type']  ?><br>
                                <?=  $orderDetails['data']['address']['billingAddress']['addressLine1']  ?><br>
                                <?=  $orderDetails['data']['address']['billingAddress']['addressLine2']  ?><br>
                                City :
                                <?=  $orderDetails['data']['address']['billingAddress']['city']  ?><br>
                                State :
                                <?=  $orderDetails['data']['address']['billingAddress']['state']  ?><br>
                                Country :
                                <?=  $orderDetails['data']['address']['billingAddress']['country']  ?><br>
                                Pin Code :
                                <?=  $orderDetails['data']['address']['billingAddress']['pinCode']  ?><br><br>
                            @endif
                            <!--  /billing address  -->
                        </td>
                        <td class="lw-text-font-size" valign="top">
                        	<strong><small><?= __tr('Shipped To:') ?></small></strong><br>
                            <?=  $orderDetails['data']['user']['fullName']  ?>
                            <br>
                            <!--  shipping address  -->
                            @if(!empty($orderDetails['data']['address']['shippingAddress']))
                                <?=  $orderDetails['data']['address']['shippingAddress']['type']  ?><br>
                                <?=  $orderDetails['data']['address']['shippingAddress']['addressLine1']  ?><br>
                                <?=  $orderDetails['data']['address']['shippingAddress']['addressLine2']  ?><br>
                                City :
                                <?=  $orderDetails['data']['address']['shippingAddress']['city']  ?><br>
                                State :
                                <?=  $orderDetails['data']['address']['shippingAddress']['state']  ?><br>
                                Country :
                                <?=  $orderDetails['data']['address']['shippingAddress']['country']  ?><br>
                                Pin Code :
                                <?=  $orderDetails['data']['address']['shippingAddress']['pinCode']  ?><br><br>
                            @endif
                            <!--  /shipping address  -->   
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" class="left lw-text-font-size">
                        	<strong><small><?= __tr('Payment Method:') ?></small></strong>
                        	<br>
                            <?= __tr('Method') ?>: <?=  $orderDetails['data']['order']['formatedPaymentMethod']  ?>
                            <br>
                            <?= __tr('Status:') ?> <?=  $orderDetails['data']['order']['formatedPaymentStatus']  ?>
                        </td>
                        <td class="lw-text-font-size">
                        	<strong><small><?= __tr('Order :') ?></small></strong>
                        	<br>
                            <?= __tr('ID:') ?> <?=  $orderDetails['data']['order']['orderUID']  ?>
                            <br>
                            <?= __tr('Placed on :') ?> <?=  $orderDetails['data']['order']['baseFormatedOrderPlacedOn']  ?>
                        </td>
                    </tr>
                </tbody>       
            </table>
            <br>

            <table width="100%" class="outline-table">
                <tbody>
                    <tr class="border-bottom border-right" style="background-color: #D3D3D3">
                        <td colspan="5"><strong><?= __tr('Products') ?></strong></td>
                    </tr>
                    <tr class="border-bottom border-right center">
                        <td width="10%"><strong><small><?= __tr('Sr. No.') ?></small></strong></td>
                        <td width="30%"><strong><small><?= __tr('Product Description') ?></small></strong></td>
                        <td width="7%"><strong><small><?= __tr('Qty') ?></small></strong></td>
                        <td width="20%"><strong><small><?= __tr('Price') ?></small></strong></td>
                        <td width="20%"><strong><small><?= __tr('Subtotal') ?></small></strong></td>
                    </tr>

                    <?php
                        $i = 1;
                    ?>
                    @if(!empty($orderDetails['data']['orderProducts']['products']))
                        @foreach($orderDetails['data']['orderProducts']['products'] as $product)
                    <tr class="border-right">
                        <td class="center lw-text-font-size" width="10%"><?=  $i  ?></td>
                        <td class="pad-left lw-text-font-size" width="30%">
                        <!--  product name, option and addon price  -->
                            <span><?=  $product['actualProductName']  ?></span><br>
                            @if(!empty($product['option']))
                                @foreach($product['option'] as $option)
                                <div>
                                    <span><?=  $option['optionName']  ?></span><br>
                                    <span><?=  $option['valueName']  ?></span>
                                    <span>(<?=  $option['baseformatedOptionPrice']  ?>)</span>
                                </div>
                                @endforeach
                            @endif
                            @if(!__isEmpty($product['productDiscount']))
                                @if($product['productDiscount']['isDiscountExist'])
                                    <div>
                                        <strike><?= $product['baseFormattedOldProductPrice'] ?></strike>(<span>Off <?=  $product['productDiscount']['discount']?></span>)
                                    </div>
                                @endif
                            @endif
                        </td>
                        <!--  /product name, option and addon price  -->

                        <!--  qty, price and total  -->
                        <td width="7%" class="center lw-text-font-sizer"><?=  $product['quantity']  ?></td>
                        <td width="20%" class="right lw-text-font-size"><?=  $product['baseFormatedProductPrice']  ?></td>
                        <td width="20%" class="right lw-text-font-size"><?=  $product['baseFormatedTotal']  ?></td>
                        <!--  /qty, price and total  -->
                    </tr>
                        <?php
                            $i++;
                        ?>
                        @endforeach
                    @endif
                    <tr class="border-top border-right">
                        <td colspan="4" class="right lw-text-font-size"><strong><small><?= __tr('Cart Total') ?></small></strong></td>
                        <td class="right lw-text-font-size"><?=  $orderDetails['data']['orderProducts']['baseFormatedSubtotal']  ?>
                        <?=  $orderDetails['data']['order']['currencyCode']  ?></td>
                    </tr>
                    @if(!__isEmpty($orderDetails['data']['order']['cartDiscount']))
                        @if($orderDetails['data']['order']['cartDiscount']['isOrderDiscountExist'])
                            <tr class="border-top border-right lw-text-font-size">
                                <td colspan="4" class="right"><strong><small>Discount</small></strong></td>
                                <td class="right">- <?= $orderDetails['data']['order']['cartDiscount']['baseShortFormatDiscount'] ?></td>
                            </tr>
                        @endif
                    @endif
                    @if(!__isEmpty($orderDetails['data']['order']['cartDiscount']))
                        @if($orderDetails['data']['order']['cartDiscount']['isOrderDiscountExist'])
                            <tr class="border-top border-right">
                                <td colspan="4" class="right lw-text-font-size"><strong><small><?= __tr('Total') ?></small></strong></td>
                                <td class="right lw-text-font-size"><?= $orderDetails['data']['order']['cartDiscount']['baseFormatNewCartTotal'] ?></td>
                            </tr>
                        @endif
                    @endif
                    @if (!empty($orderDetails['data']['coupon']))  
                    <tr class="border-top border-right lw-text-font-size">
                        <td colspan="4" class="right"><strong><small><?= __tr('Discount') ?></small></strong></td>
                        <td class="right">- <?=  $orderDetails['data']['order']['baseFormatedOrderDiscount']  ?></td>
                    </tr>
                    @endif

                    @if (!empty($orderDetails['data']['taxes']))  
                    @foreach($orderDetails['data']['taxes'] as $tax)      
                    <tr class="border-top border-right lw-text-font-size">
                        <td colspan="4" class="right"><strong><small><?= __tr('Tax') ?> ( <i><?= $tax['label'] ?></i> )</small> </strong></td>
                        <td class="right">
                            + <?=  $tax['baseFormatedTaxAmount']  ?>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr class="border-top border-right lw-text-font-size">
                        <td colspan="4" class="right">
                            <strong><small><?= __tr('Shipping') ?></small></strong>
                            <div>
                            @if(!__isEmpty($orderDetails['data']['shipping']))
                                @foreach($orderDetails['data']['shipping'] as $shipping)
                                    @if(isset($shipping['shipping_method_title']) 
                                        and !__isEmpty($shipping['shipping_method_title']))
                                        ( Method : <?= $shipping['shipping_method_title'] ?> )
                                    @endif
                                @endforeach
                            @endif
                            </div>
                        </td>
                        <td class="right">
                            @if(!__isEmpty( $orderDetails['data']['order']['shippingAmount']))
                                + <?=  $orderDetails['data']['order']['baseFormatedShippingAmount']  ?>
                            @else
                                <?= 'Free' ?>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-top border-right lw-text-font-size">
                        <td colspan="4" class="right"><strong><small><?= __tr('Total Paid Amount') ?></small></strong></td>
                        <td class="right"><?=  $orderDetails['data']['order']['baseFormatedTotalOrderAmount']  ?>
                        </td>
                    </tr>

                </tbody>
            </table>
            <br>

            @if (!empty($orderDetails['data']['coupon']))  
            <table width="100%" class="outline-table">
                <tbody>
                    <tr class="border-bottom border-right" style="background-color: #D3D3D3">
                        <td colspan="2"><strong><?= __tr('Discount-') ?></strong> <small><?= __tr('Information') ?></small></td>
                    </tr>
                    <tr class="border-bottom border-right center">
                        <td width="40%"><strong><small><?= __tr('Description') ?></small></strong></td>
                        <td width="25%"><strong><small><?= __tr('Info') ?></small></strong></td>
                    </tr>
                    <tr class="border-right">
                        <td class="center lw-text-font-size">
                            @if($orderDetails['data']['coupon']['description'])
                            <?= $orderDetails['data']['coupon']['description'] ?>
                            @else
                                - 
                            @endif
                        </td>
                        <td>
                            <span class="lw-text-font-size">
                                Code : <?=  $orderDetails['data']['coupon']['code']  ?><br>
                            </span>
                            <span class="lw-text-font-size">
                                Title : <?=  $orderDetails['data']['coupon']['title']  ?><br>
                            </span>
                        </td>
                    </tr>
                    <tr class="border-right">
                        <td class="right border-top lw-text-font-size"><?= __tr('Amount') ?></td>
                        <td class="right border-top lw-text-font-size"> - <?=  $orderDetails['data']['order']['baseFormatedOrderDiscount']  ?></td>
                    </tr>
                </tbody>
            </table>
            @endif
            
            
            @if(!empty($orderDetails['data']['taxes']))   
            <table width="100%" class="outline-table">
                <tbody>
                    <tr class="border-bottom border-right" style="background-color: #D3D3D3">
                        <td colspan="2"><strong><?= __tr('Tax -') ?></strong> <small><?= __tr('Information') ?></small></td>
                    </tr>
                    <tr class="border-bottom border-right">
                        <td width="40%" class="center"><strong><small><?= __tr('Description') ?></small></strong></td>
                        <td width="25%" class="right"><strong><small><?= __tr('Amount') ?></small></strong></td>
                    </tr>
                    @foreach($orderDetails['data']['taxes'] as $tax)
                    <tr class="border-bottom border-right ">
                        <td class="center lw-text-font-size">
                            <div>
                                <span>
                                    <?=  $tax['label']   ?><br>
                                </span>
                            </div>
                            @if($tax['notes'] )
                                <?= $tax['notes'] ?>
                            @else
                                - 
                            @endif
                        </td>
                        <td class="right lw-text-font-size">
                            <span>
                                <?=  $tax['baseFormatedTaxAmount']  ?>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
            @endif
            <br>
            <div class="lw-footer">
                <div  class="lw-pdf-page-number"><?= __tr(' / Page') ?></div>
            </div>

        </div> 
    </body>
</html>