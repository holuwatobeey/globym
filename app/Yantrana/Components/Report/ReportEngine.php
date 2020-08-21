<?php
/*
* ReportEngine.php - Main component file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report;

use App\Yantrana\Components\Report\Repositories\ReportRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\ShoppingCart\OrderEngine;
use App\Yantrana\Components\Report\Blueprints\ReportEngineBlueprint;
use Config;
use PDF;
use Picqer\Barcode\BarcodeGeneratorHTML;
use XLSXWriter;
use App;
use DB;

class ReportEngine implements ReportEngineBlueprint
{
    /**
     * @var ReportRepository - Report Repository
     */
    protected $reportRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var OrderEngine - Order Engine
     */
    protected $orderEngine;

    /**
     * Constructor.
     *
     * @param ReportRepository $reportRepository - Report Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
                    ReportRepository $reportRepository,
                    ShippingRepository $shippingRepository,
                    TaxRepository $taxRepository,
                    SupportRepository $supportRepository,
                    OrderEngine $orderEngine
                ) {
        $this->reportRepository = $reportRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->supportRepository = $supportRepository;
        $this->orderEngine = $orderEngine;
    }

    /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList($startDate, $endDate, $status, $order, $currency)
    {
        return $this->reportRepository
                    ->fetchDataTableSource($startDate, $endDate, $status, $order, $currency);
    }

     /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAllOrder($startDate, $endDate, $status, $order)
    {
        return $this->reportRepository->fetchAllOrder($startDate, $endDate, $status, $order);
    }

    /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAllOrderPayment($startDate, $endDate, $status, $order)
    {
        return $this->reportRepository->fetchAllOrderPayment($startDate, $endDate, $status, $order);
    }

    /**
    * Product Report List datatable source 
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function prepareDatatableProductReportList($startDate, $endDate, $order)
    {
        return $this->reportRepository
                    ->fetchProductReportDataTable($startDate, $endDate, $order);
    }

    /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchProductReport($startDate, $endDate, $order)
    {
        $product = $this->reportRepository
                    ->fetchDataProductReport($startDate, $endDate, $order);

        $productData = $product->sortBy('name')->all();
        $productCollection = collect($productData);
               
        $productName = [];
        $productQty = [];
        if (!__isEmpty($productCollection)) {
            foreach ($productCollection as $key => $product) {
                $productQuantity = [];  

                if (!__isEmpty($product->orderProducts)) {
                    $productQuantity = array_column($product->orderProducts->toArray(), 'quantity');
                }

                $productQty[] = array_sum($productQuantity);
                $productName[] = substr($product['name'], 0, 6)."...";
            }
        }
        
        // the variables
        $colorArray = config('__tech.products.product_chart_bg_color');
        $productLength = count($productCollection);
        $bgColor = [];

        // create a new array with AT LEAST the desired number of elements by joining the array at the end of the new array
        while(count($bgColor) <= $productLength){
            $bgColor = array_merge($bgColor, $colorArray);
        }

        // reduce the new array to the desired length (as there might be too many elements in the new array
        $colorArray = array_slice($bgColor, 0, $productLength);
    
        $productChartDataSet[] = [
            'data' => $productQty,
            'backgroundColor'=> $colorArray,
        ];

        $productChartData = [
            'chartData' => $productChartDataSet,
            'labels' => array_values($productName),
            'productCount' => count($productCollection)
        ];

        return $productChartData;
    }

    /**
     * get prepare order report list.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchOrderReport($startDate, $endDate, $status, $order, $currency)
    {
        $orderCollection = $this->reportRepository
                    ->fetchDataOrderReport($startDate, $endDate, $status, $order, $currency);

        $newOrder = $orderCollection->where('status', '=', 1)->count();
        $processionOrder = $orderCollection->where('status', '=', 2)->count();
        $cancelledOrder = $orderCollection->where('status', '=', 3)->count();
        $onHoldOrder = $orderCollection->where('status', '=', 4)->count();
        $inTransitOrder = $orderCollection->where('status', '=', 5)->count();
        $completeOrder = $orderCollection->where('status', '=', 6)->count();
        $confirmOrder = $orderCollection->where('status', '=', 7)->count();
        $deliverOrder = $orderCollection->where('status', '=', 8)->count();

        $awatingPayment = $orderCollection->where('payment_status', '=', 1)->count();
        $completedPayment = $orderCollection->where('payment_status', '=', 2)->count();
        $paymentFailed = $orderCollection->where('payment_status', '=', 3)->count();
        $pendingPayment = $orderCollection->where('payment_status', '=', 4)->count();
        $refundedPayment = $orderCollection->where('payment_status', '=', 5)->count();

        //order chart status data
        $orderDataCount = [
            'orderReportDataCount' =>  [
                $newOrder, $processionOrder, $cancelledOrder, $onHoldOrder, $inTransitOrder, $completeOrder, $confirmOrder, $deliverOrder ],
            'orderStatusLabel' => array_values(config('__tech.orders.status_codes')),
            'orderChartColor' => array_values(config('__tech.orders.order_chart_bg_color')),
            'orderCount' => count($orderCollection),
        ];

        //order payment chart status data
        $orderPaymentDataCount = [
            'orderPaymentReportCount' =>  [
                $awatingPayment, $completedPayment , $paymentFailed, $pendingPayment, $refundedPayment
            ],
            'orderPaymentStatusLabel' => array_values(config('__tech.orders.payment_status')),
            'orderPaymentChartColor' => array_values(config('__tech.orders.order_payment_chart_bg_color')),
        ];

        //create order chart data set array for print chart js
        $orderChartData[] = [
            'data' => $orderDataCount['orderReportDataCount'],
            'backgroundColor' => $orderDataCount['orderChartColor']
        ];

        //create order payment chart data set array for print chart js
        $orderPaymentChartData[] = [
            'data' => $orderPaymentDataCount['orderPaymentReportCount'],
            'backgroundColor' => $orderPaymentDataCount['orderPaymentChartColor']
        ];

        //collect order report data
        $orderReportData = [
            'orderData' =>   $orderChartData,
            'orderStatusLabel' => $orderDataCount['orderStatusLabel'],
            'orderCount' => $orderDataCount['orderCount'],
            'orderPaymentData' => $orderPaymentChartData,
            'orderPaymentLabel' => $orderPaymentDataCount['orderPaymentStatusLabel'],
        ];

        return $orderReportData;
        
    }

    /**
     * Get total by currency code.
     *
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param $order
     *
     * @return array
     *---------------------------------------------------------------- */
    public function preapreTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency)
    {
        $totalOrderAmounts = $this->reportRepository
                              ->fetchTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency);

        $curencyList = $totalOrderAmounts->pluck('currency_code')->toArray();
       
        $totalOrderAmt = $totalOrderAmounts->groupBy('currency_code')->toArray();

        $orderAmountByType = [];
        $paymentTotalAmount = [];
        $currencyCodeLabel = [];
        $paymentData = [];
        foreach ($totalOrderAmt as $currencyCode => $amtData) {
            $creditAmount = $debitAmount = $paymentTotalAmoun = [];
            // Get currency code
            $currencyCodeLabel[] = $currencyCode;

            foreach ($amtData as $key => $orderData) {  
                $paymentTotalAmount[] = $orderData['gross_amount'];
                if ($orderData['type'] == 1) {
                   $creditAmount[] = $orderData['gross_amount'];

                }

                if ($orderData['type'] == 2) {
                   $debitAmount[] = $orderData['gross_amount'];
                }
            }
  
            $totalCreditAmt = array_sum($creditAmount);
            $totalDebitAmt = array_sum($debitAmount);
 
            // Calculate total amount
            $totalAmount = $totalCreditAmt - $totalDebitAmt;

          

            $orderAmountByType[$currencyCode] = [
                'currencyCode' => $currencyCode,
                'credit' => $totalCreditAmt,
                'formattedCredit' => orderPriceFormat($totalCreditAmt, $currencyCode),
                'debit' => $totalDebitAmt,
                'formattedDebit' => orderPriceFormat($totalDebitAmt, $currencyCode),
                'total' => $totalAmount,
                'formattedTotal' => orderPriceFormat($totalAmount, $currencyCode),
            ];
            
        }

        $orderAmount = array_sum($paymentTotalAmount);
        $currencyLanel = $currencyCodeLabel;
      
        //fetch credit & debit amount data
        $creditData = array_column($orderAmountByType, 'credit');
        $debitData = array_column($orderAmountByType, 'debit');

        //credit amount payment chart data
        $paymentCreditData[] = [
            'label' => 'Credit',
            'backgroundColor' => "#295F28",
            'data'  => $creditData
        ];

        //debit amount payment chart data
        $paymentDebitData[] = [
            'label' => 'Debit',
            'backgroundColor' => "#E63024",
            'data'  => $debitData
        ];
     
        // $payment Report Chart Data Set
        $paymentReportChartData = [
            'currencyLabel' => $currencyCodeLabel,
            'paymentChartDataSet' => array_merge($paymentCreditData, $paymentDebitData),
            'paymentOrderCount' => count($orderAmountByType)
        ];

        $excelDownloadURL = route('manage.payment_report.payment_excel_download', [$startDate, $endDate, $status, $order, $currency]);
      
      return __engineReaction(1, [
            'orderAmountByType' => $orderAmountByType,
            'duration' => config('__tech.report_duration'),
            'paymentReportChartData' => $paymentReportChartData,
            'excelDownloadURL' => $excelDownloadURL,
            'currencyList'  => array_values(array_unique($curencyList)),
        ]);
       
    }


    /**
     * Get total by currency code.
     *
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param $order
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency)
    {
        $totalOrderAmounts = $this->reportRepository
                              ->fetchTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency);
          
        $totalOrderAmt = $totalOrderAmounts->groupBy('currency_code')->toArray();
        $orderAmountByType = [];
 
        foreach ($totalOrderAmt as $currencyCode => $amtData) {
            $creditAmount = $debitAmount = [];
            // Get currency code
             
            foreach ($amtData as $key => $orderData) {
                if ($orderData['type'] == 1) {
                   $creditAmount[] = $orderData['gross_amount'];
                }

                if ($orderData['type'] == 2) {
                   $debitAmount[] = $orderData['gross_amount'];
                }
            }

            $totalCreditAmt = array_sum($creditAmount);
            $totalDebitAmt = array_sum($debitAmount);

            // Calculate total amount
            $totalAmount = $totalCreditAmt - $totalDebitAmt;

            $orderAmountByType[$currencyCode] = [
                    'currencyCode' => $currencyCode,
                    'credit' => $totalCreditAmt,
                    'formattedCredit' => orderPriceFormat($totalCreditAmt, $currencyCode),
                    'debit' => $totalDebitAmt,
                    'formattedDebit' => orderPriceFormat($totalDebitAmt, $currencyCode),
                    'total' => $totalAmount,
                    'formattedTotal' => orderPriceFormat($totalAmount, $currencyCode),
            ];
            
        }
      
        return [
            'orderAmountByType' => $orderAmountByType,
        ];
    }

    /**
     * prepare order detail dialog data.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderDetailsDialogData($orderID)
    {
        // Get order detail with products, address and coupon
        $orderDetails = $this->reportRepository
                             ->fetchOrderDetails($orderID);

         //check order exist
        if (empty($orderDetails)) {
            return __engineReaction(18);
        }

        // get tax detail
        $taxCollection = $this->reportRepository
                               ->fetchOrderTax($orderDetails['_id'])->toArray();

        // Get order currency code
        $currencyCode = $orderDetails['currency_code'];

        // get country code from user address
        foreach ($orderDetails['address'] as $address) {
            $countryCode = $address->country;
        }

        // get shipping data
        $shippingData = [];

        $shippingCollection = $this->shippingRepository
                                   ->fetchByConutry($countryCode);

        // Check if shipping exist and make shipping data array for shipping data
        if (!empty($shippingCollection)) {
            $shippingData = [
                'shippingNotes' => $shippingCollection['notes'],
                'shippingType' => $shippingCollection['type'],
            ];
        }

           // check shipping amount exist
        $shipping = 0;
        if (!empty($orderDetails->shipping_amount)) {
            $shipping = $orderDetails->shipping_amount;
        }

        // get tax data
        $taxData = $this->taxRepository
                        ->fetchByConutry($countryCode);

        // calculation of order products and addon price
        $orderProducts = $orderDetails->orderProduct;

        $getSubTotal = [];

        foreach ($orderProducts as $optionKey => $orderProduct) {
            $addonPrice = [];
                // check product option
                if (!empty($orderProduct['productOption'])) {
                    foreach ($orderProduct['productOption'] as $key => $productOption) {
                        $productOption['addonPrice'] = orderPriceFormat($productOption['addon_price'], $currencyCode);

                        $addonPrice[] = $productOption['addon_price'];
                    }
                }
                // get add price total
                $totalAddonPrice = array_sum($addonPrice);

                // price and addon price total
                $addOptionPriceInAddon = $orderProduct['price'] + $totalAddonPrice;

                //add price formate
                $orderProducts[$optionKey]['priceFormat'] = orderPriceFormat($orderProduct['price'], $currencyCode);

                //create new price with addon price and price
                $orderProducts[$optionKey]['new_price'] = orderPriceFormat($addOptionPriceInAddon, $currencyCode);

                // add quantity and price
                $addQuntity = $addOptionPriceInAddon * $orderProduct['quantity'];

                // add sub total price
                $orderProducts[$optionKey]['sub_total'] = orderPriceFormat($addQuntity, $currencyCode);

            $getSubTotal[] = $addQuntity;
        }

        // calculate tax amount
        $taxData = [];
        $taxCharges = [];
        $totalTax = 0;

        if (!empty($taxCollection)) {
            foreach ($taxCollection as $key => $taxDiscount) {
                //get tax detail
                    $taxes = $this->taxRepository
                                  ->fetchByTaxId($taxDiscount['tax__id'])->toArray();

                if (!empty($taxes)) {
                    foreach ($taxes as $tax) {
                        // push individual tax data	into array
                                $taxData [] = [
                                    'label' => $tax['label'],
                                    'notes' => $tax['notes'],
                                    'type' => $tax['type'],
                                    'discount' => $taxDiscount['amount'],
                                    'formatedTax' => orderPriceFormat($taxDiscount['amount'], $currencyCode),
                                ];
                    }
                } else {
                    // if tax not exist in db
                            $taxData [] = [
                                    'label' => '',
                                    'notes' => '',
                                    'discount' => $taxDiscount['amount'],
                                    'formatedTax' => orderPriceFormat($taxDiscount['amount'], $currencyCode),
                                ];
                }

                $taxCharges[$key] = $taxDiscount['amount'];
            }
                // sum of tax
                $totalTax = array_sum($taxCharges);
        }

            // check coupon detail exist
            if (!empty($orderDetails->coupon)) {
                $couponData = [
                    'code' => $orderDetails->coupon->code,
                    'title' => $orderDetails->coupon->title,
                    'description' => $orderDetails->coupon->description,
                ];
            }

            // calculation of discount (coupon)
            $discountAmount = $orderDetails->discount_amount;
        $basePrice = 0;
        $baseTotal = array_sum($getSubTotal);
        $total = 0;

            // if Base total exist
            if (!empty($baseTotal)) {

                // if discount amount exist then subtract from base total
                if (!empty($discountAmount)) {
                    $basePrice = $baseTotal - $discountAmount;
                    $total = $basePrice + $shipping + $totalTax;
                } else {
                    $basePrice = $baseTotal;
                    $total = $baseTotal + $shipping + $totalTax;
                }
            }

        $shippingAddress = [];
        $billingAddress = [];
        $addressType = config('__tech.address_type');
        $addressSameAs = false;

            //order shipping address
            foreach ($orderDetails->address as $address) {

                // Get country name
                $countryName = $this->supportRepository
                                       ->fetchCountry($address['countries__id']);

                $shippingAddress = [
                    'addressID' => $address['id'],
                    'type' => $addressType[$address['type']],
                    'address_line_1' => $address['address_line_1'],
                    'address_line_2' => $address['address_line_2'],
                    'city' => $address['city'],
                    'state' => $address['state'],
                    'country' => $countryName['name'],
                    'pincode' => $address['pin_code'],
                ];
            }

            //order billing address
            foreach ($orderDetails->address1 as $address1) {

                // Get country name
                $countryName = $this->supportRepository
                                       ->fetchCountry($address1['countries__id']);

                $billingAddress = [
                    'address1ID' => $address1['id'],
                    'type' => $addressType[$address1['type']],
                    'address_line_1' => $address1['address_line_1'],
                    'address_line_2' => $address1['address_line_2'],
                    'city' => $address1['city'],
                    'state' => $address1['state'],
                    'country' => $countryName['name'],
                    'pincode' => $address1['pin_code'],
                ];
            }

            //check address same as or not
            if ($shippingAddress['addressID'] == $billingAddress['address1ID']) {
                $addressSameAs = true;
            }

            // get order status
            $orderStatus = config('__tech.orders.status_codes');
        $orderPaymentMethod = config('__tech.orders.payment_methods');
        $orderPaymentStatus = config('__tech.orders.payment_status');

        $allOrderRelatedData = [
                'orderUID' => $orderDetails->order_uid,
                'name' => $orderDetails->name,
                'orderStatus' => $orderStatus[$orderDetails->status],
                'orderOn' => formatStoreDateTime($orderDetails->created_at),
                'orderBy' => $orderDetails->user->email,
                'paymentMethod' => $orderPaymentMethod[$orderDetails->payment_method],
                'paymentStatus' => $orderPaymentStatus[$orderDetails->payment_status],
                'currencyCode' => $currencyCode,
                'cartTotal' => orderPriceFormat($baseTotal, $currencyCode),
                'total' => orderPriceFormat($total, $currencyCode),
                'shipping' => (!empty($shipping))
                                    ? orderPriceFormat($shipping, $currencyCode)
                                    : '',
                'shippingData' => (!empty($shippingData))
                                    ? $shippingData
                                    : '',
                'couponData' => (!empty($couponData))
                                    ? $couponData
                                    : '',
                'discount' => (!empty($discountAmount))
                                    ? orderPriceFormat($discountAmount, $currencyCode)
                                    : '',
                'totalTax' => (!empty($totalTax))
                                    ? orderPriceFormat($totalTax, $currencyCode)
                                    : '',
                'taxData' => (!empty($taxData))
                                    ? $taxData
                                    : '',
            ];

            // get logo of store
               $logoURL = getStoreSettings('logo_image');

        return __engineReaction(1, [
                'orderDetails' => $allOrderRelatedData,
                'productOrder' => $orderDetails->orderProduct,
                'orderAddress' => $shippingAddress,
                'orderAddress1' => $billingAddress,
                'sameAddress' => $addressSameAs,
                'logoURL' => $logoURL,
                ]);
    }

    /**
     * process pdf download.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processPdfDownload($orderID)
    {
        // get order detail by order ID
        $orderDetails = $this->orderEngine
                             ->prepareForMyOrderDetails((int) $orderID);

        if ($orderDetails['reaction_code'] == 18) {
            App:abort(404);
        }

        // array data for creation of string for pdf
        $arrayData = [
            ':currentDate' => formattedDateTime(currentDateTime()),
            ':formatDate'  => formattedDateTime(currentDateTime(), 'j F, Y')
        ];

        // generated on string
        $orderDetails['currentDateTime'] = formattedDateTime(currentDateTime());
        $orderDetails['formatcurrentDate'] = formattedDateTime(currentDateTime(), 'j F, Y');

        //generate barcode instance
        $generator = new BarcodeGeneratorHTML();
       
        $orderDetails['orderBarcode'] = $generator->getBarcode($orderDetails['data']['order']['orderUID'], $generator::TYPE_CODE_128);

        // download pdf
        $reportPdf = PDF::loadView('report.manage.pdf-report', ['orderDetails' => $orderDetails]);
        // return $reportPdf->stream();
        return $reportPdf->download(slugIt($orderDetails['data']['order']['orderUID']).'.pdf');
    }

    /**
     * process Excel Download.
     *
     * @param date $startDate
     * @param date $endData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processExcelDownload($startDate, $endDate, $status, $orderCode, $currency)
    {
        $orderCollection = $this->reportRepository
                                 ->fetchOrderCollection($startDate, $endDate, $status, $orderCode, $currency);

        // Check if order collection is empty
        if (__isEmpty($orderCollection)) {
            App:abort(404);
        }

        // get order array
        $orderStatus = config('__tech.orders.status_codes');
        $paymentMethod = config('__tech.orders.payment_methods');
        $orderType = config('__tech.orders.payment_type');

        $totalOrderAmountCollection = [];
        $totalOrderAmount = 0;
        $orderData = [];
        $taxCharges = [];
        $totalTax = 0;

        foreach ($orderCollection as $key => $order) {

            // get all total amount of order
            $totalOrderAmountCollection[$key] = $order['total_amount'];

            // get tax detail
            $taxCollection = $this->reportRepository
                                  ->fetchOrderTax($order['_id'])->toArray();

            // push tax amount into array
            foreach ($taxCollection as $key => $tax) {
                $taxCharges[$key] = $tax['amount'];
            }

            //calculate total tax
            $totalTax = array_sum($taxCharges);

            $orderData [] = [
                'orderUID' => $order['order_uid'],
                'fullName' => $order['fname'].' '.$order['lname'],
                'placedOn' => formattedDateTime($order['created_at']),
                'status' => $orderStatus[$order['status']],
                'paymentMethod' => $paymentMethod[$order['payment_method']],
                'currency' => $order['currency_code'],
                'total' => (string)$order['total_amount'],
                'discountAmount' => (!__isEmpty($order['discount_amount']))
                                        ? (string)$order['discount_amount']
                                        : '0',
                'shippingAmount' => (!__isEmpty($order['shipping_amount']))
                                        ? (string)$order['shipping_amount']
                                        : '0',
                'totalAmount' => (!__isEmpty($totalTax))
                                        ? (string)$totalTax
                                        : '0',
            ];
        }

        // total of all order
        $totalOrderAmount = array_sum($totalOrderAmountCollection);

        //set excel file name
        $ExcelFileName = 'Report-'.''.$startDate.'-'.$endDate;

        // set start date and end date title
        if ($orderCode == 1) {
            $startAndEndDate = 'From'.' '.$startDate.' to '.$endDate.' Placed on';
        } else {
            $startAndEndDate = 'From'.' '.$startDate.' to '.$endDate.' Updated on';
        }

        // set order title with date and time
        $currentDate = formattedDateTime(currentDateTime());
        $orderTitle = 'Orders as on '.''.$currentDate;

        // Excel title, date and total amount data
        $excelData = [
            'excelFileName' => $ExcelFileName,
            'title'         => $orderTitle,
            'startEndDate'  => 'From'.' '.$startDate.' to '.$endDate.' '.$startAndEndDate,
            'total'         => $totalOrderAmount,
        ];

        //create temp path for store excel file
        $temp_file = tempnam(sys_get_temp_dir(), 'Orderdetails.xlsx');
        $writer = new XLSXWriter();

        $sheet1 = 'Order Details';

        //set header column string
        $header = array("string","string","string","string","string","string","string","string","string");

        // topHeader for header web site name row set css styles
        $topHeader = array('halign'=>'center','valign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>26);

        // Style 1 for header title set css styles
        $styles1 = array('halign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>20);

        // Style 2 for Column title set css styles
        $styles2 = array('halign'=>'left','font-style'=>'bold','font-size'=>10, 'height'=>15, 'border'=>'left,right,top,bottom', 'border-style'=>'thin');

        // Style 3 for Generated todays date header set css styles
        $styles3 = array('halign'=>'right','font-style'=>'bold','font-size'=>13);

        //Style 4 for Total Order Payment Record
        $styles4 = array(
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Id
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Full Name
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Order Placed on
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Status
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Payment Method
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Currency Method
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Total Method
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Discount Amount
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Shipping Amount
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], // Total Tax
                        'height'=> 17,
                    ); 

        //Main Column Header
        $writer->writeSheetHeader($sheet1, $header, 
            $col_options = ['suppress_row'=>true, 
                            'widths'=>[
                                25, //Id width  set
                                30, //Full Name width  set
                                40, //Order Placed on width  set
                                15, // Status width  set
                                20, // Payment Method set
                                10, // Currency Method set
                                18, // Total Method set
                                18, // Discount Amount set
                                18, //Shipping Amount set
                                12] //Total Tax set
                            ] 
                        );
        
        $storeName = __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') );

        //Website name Row
        $writer->writeSheetRow($sheet1,  [$storeName], $topHeader );

        //Title Row
        $writer->writeSheetRow($sheet1,  [$excelData['title']], $styles1 );

        //Generated Todays date Row
        $writer->writeSheetRow($sheet1,  [$excelData['startEndDate']], $styles1 );

        //Column Title row
        $writer->writeSheetRow($sheet1,  ['OrderUID', 'Full Name', 'Order Placed on', 'Status', 'Payment Method', 'Currency', 'Total', 'Discount Amount', 'Shipping Amount', 'Total Tax'], $styles2);

        //create row data
        $rows = $orderData;

        //Total Order Payment Record Data
        foreach($rows as $key => $row) { 
             
            //Create sheet fetch data row dynamically   
            $writer->writeSheetRow($sheet1, $row, $styles4);
                
        }
 
        //Merge two cells for set title & generated date in center
        $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=0, $end_col=9, ['border'=>'left,right,top,bottom', 'border-style'=>'thin']);
        $writer->markMergedCell($sheet1, $start_row=1, $start_col=0, $end_row=1, $end_col=9);
        $writer->markMergedCell($sheet1, $start_row=2, $start_col=0, $end_row=2, $end_col=9);

        //to prinf in excel file function
        $writer->writeToFile($temp_file);

        return response()->download($temp_file, 'Orderdetails.xlsx');
    }

    /**
     * process pAYMENT Excel Download.
     *
     * @param date $startDate
     * @param date $endData
     * @param number $status
     * @param string $order
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processPaymentExcelDownload($startDate, $endDate, $status, $order, $currency)
    {
        // Get order amount by currency
        $orderAmountByCurrency = $this->getTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency);

        // Check if order collection is empty
        if (__isEmpty($orderAmountByCurrency)) {
            App:abort(404);
        }

        // Create a data for total order amount received and refunded
        $currencyTotalAmountData = [];

        foreach ($orderAmountByCurrency['orderAmountByType'] as $orderAmt) {
            $currencyTotalAmountData [] = [
                'currencyCode' => $orderAmt['currencyCode'],
                'credit' => $orderAmt['credit'],
                'debit' => $orderAmt['debit'],
                'difference' => $orderAmt['total'],
            ];
        }

        // Excel title, date and total amount data
        $excelData = [
            'excelFileName' => 'Payments-'.''.$startDate.'-'.$endDate,
            'title'         => 'Reports as on'.' '.formatStoreDateTime(currentDateTime()),
            'startEndDate'  => 'From'.' '.$startDate.' to '.$endDate,
            'subTitle'      => 'Total Order Payments'
        ];

        //create temp path for store excel file
        $temp_file = tempnam(sys_get_temp_dir(), 'Paymentdetails.xlsx');
        $writer = new XLSXWriter();

        $sheet1 = 'Payment Details';

        //set header column string
        $header = array("string","string","string","string");

        // topHeader for header web site name row set css styles
        $topHeader = array('halign'=>'center','valign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>26);

        // Style 1 for header title set css styles
        $styles1 = array('halign'=>'center','font-size'=>12, 'font-style'=>'bold', 'height'=>20);

        // Style 2 for Column title set css styles
        $styles2 = array('halign'=>'left','font-style'=>'bold','font-size'=>10, 'height'=>15, 'border'=>'left,right,top,bottom', 'border-style'=>'thin');

        // Style 3 for Generated todays date header set css styles
        $styles3 = array('halign'=>'right','font-style'=>'bold','font-size'=>13);

        //Style 4 for Total Order Payment
        $styles4 = array(
                        ['halign'=>'left','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Currency
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Credit Amount
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Debit Amount
                        ['halign'=>'right','border'=>'left,right,top,bottom', 'border-style'=>'thin'], //Difference Amount
                    );

        //Main Column Header
        $writer->writeSheetHeader($sheet1, $header, 
            $col_options = ['suppress_row'=>true, 
                            'widths'=>[
                                15, //Currency width  set
                                20, //Credit Amount set
                                20, //Debit Amount set
                                20] //Difference Amount set
                            ] 
                        );

        $storeName = __transliterate('general_setting', null, 'store_name', getStoreSettings('store_name') );

        //Website name Row
        $writer->writeSheetRow($sheet1,  [$storeName], $topHeader );

        //Title Row
        $writer->writeSheetRow($sheet1,  [$excelData['title']], $styles1 );

        //Generated Todays date Row
        $writer->writeSheetRow($sheet1,  [$excelData['startEndDate']], $styles1 );

         //Generated Todays date Row
        $writer->writeSheetRow($sheet1,  [$excelData['subTitle']], $styles1 );

        //Column Title row
        $writer->writeSheetRow($sheet1,  ['Currency', 'Credit Amount', 'Debit Amount', 'Difference Amount'], $styles2);

        //create row data
        $currencyAmountData = $currencyTotalAmountData;

        //Total Order Payment Record Data
        foreach($currencyAmountData as $key => $currencyData) { 
            
            //Create sheet fetch data row dynamically   
            $writer->writeSheetRow($sheet1, $currencyData, $styles4);
                
        }

        //Merge two cells for set title & generated date in center
        $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=0, $end_col=3, ['border'=>'left,right,top,bottom', 'border-style'=>'thin']);
        $writer->markMergedCell($sheet1, $start_row=1, $start_col=0, $end_row=1, $end_col=3);
        $writer->markMergedCell($sheet1, $start_row=2, $start_col=0, $end_row=2, $end_col=3);
        $writer->markMergedCell($sheet1, $start_row=3, $start_col=0, $end_row=3, $end_col=3);

        //to prinf in excel file function
        $writer->writeToFile($temp_file);

        return response()->download($temp_file, 'Paymentdetails.xlsx');

    }

    //Section for download Excel files.
    public function downloadFile($file, $name= 'file-download')
    {
        if (is_file($file)) { // File to download.
            
            $type = 'application/octet-stream';
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: attachment; filename="'.$name.'";');
            header('Content-Type: ' . $type);
            header('Content-Length: ' . filesize($file));

            // size of chunks (in bytes).
            $chunkSize = 1024 * 1024;

            // Open a file in read mode.
            $handle = fopen($file, 'rb');

            // Run this until we have read the whole file.
            // feof (eof means "end of file") returns `true` when the handler
            // has reached the end of file.
            while (!feof($handle)) {
                $buffer = fread($handle, $chunkSize);
                echo $buffer;
                ob_flush();  // Flush the output buffer to free memory.
                flush();
            }

            $status = fclose($handle);

            return $status; // Exit to make sure Not to output anything else.
        }
    }
}
