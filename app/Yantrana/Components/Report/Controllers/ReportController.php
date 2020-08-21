<?php
/*
* ReportController.php - Controller file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Report\ReportEngine;
use Auth;
use Carbon\Carbon;

class ReportController extends BaseController
{
    /**
     * @var ReportEngine - Report Engine
     */
    protected $reportEngine;

    /**
     * Constructor.
     *
     * @param ReportEngine $reportEngine - Report Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ReportEngine $reportEngine)
    {
        $this->reportEngine = $reportEngine;
    }

    /**
     * Handle report list datatable source.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index($startDate, $endDate, $status, $order, $currency)
    { 
        $engineReaction = $this->reportEngine
                               ->prepareList($startDate, $endDate, $status, $order, $currency);

        $orderCollection = collect($engineReaction['data'])->pluck('currency_code')->toArray();
        
        $userRole = Auth::user()->role;

        $requireColumns = [

            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'formated_status' => function ($key) {
                return $this->findStatus($key['status']);
            },
            'formated_payment_status' => function ($key) {
                return $this->findPaymentStatus($key['payment_status']);
            },
            'formated_name' => function ($key) {
                return $key['fname'].' '.$key['lname'];
            },
            'totalAmount' => function ($key) {
                return orderPriceFormat($key['total_amount'], $key['currency_code']);
            },
            'pfdDownloadURL' => function ($key) {
                return route('manage.report.pdf_download', $key['_id']);
            }, '_id', 'status', 'users_id', 'order_uid', 'fname', 'payment_status',
            'canAccessDetail' => function() {
                if (canAccess('manage.order.details.dialog')) {
                    return true;
                }
                return false;
            },
            'canAccessDownloadInvoice' => function() {
                if (canAccess('manage.report.pdf_download')) {
                    return true;
                }
                return false;
            }
        ];

        $excelDownloadURL = route('manage.report.excel_download', [$startDate, $endDate, $status, $order, $currency]);

        // Get total amount by currency code
        $totalAmounts = $this->reportEngine->getTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency);

        $orderReportCollection = $this->reportEngine
                               ->fetchOrderReport($startDate, $endDate, $status, $order, $currency);

        return __dataTable($engineReaction, $requireColumns, [
                'excelDownloadURL' => $excelDownloadURL,
                'duration' => config('__tech.report_duration'),
                'totalAmounts' => $totalAmounts,
                'currencyList'  => array_values(array_unique($orderCollection)),
                'orderReportData' => $orderReportCollection
            ]);
    }

     /**
     * return mathching status.
     *
     * @param int $ID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function preapreOrderPaymentData($startDate, $endDate, $status, $order, $currency)
    { 
        $processReaction = $this->reportEngine->preapreTotalAmountByCurrency($startDate, $endDate, $status, $order, $currency);

       // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Payment does not exist.'),
                ], $processReaction['data']);
    }

    /**
    * Request to engine for sample data
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function prepareProductReportList($startDate, $endDate, $order) 
    {        
        $engineReaction = $this->reportEngine
                               ->prepareDatatableProductReportList($startDate, $endDate, $order);

        $requireColumns = [
            'id', 
            'name',
            'created_at' => function($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'updated_at' => function($key) {
                return formatStoreDateTime($key['updated_at']);
            },
            'formatUpdateData' => function ($key) {
                $createdDate = Carbon::parse($key['updated_at']);
                return $createdDate->diffForHumans();
            },
            'testData' => function ($key) {
                $productQuantity = [];
              
                if (!__isEmpty($key['order_products'])) { 
                    
                    $productQuantity = array_column($key['order_products'], 'quantity');

                    return array_sum($productQuantity);
                }

                return '0';
            },
        ];

        $orderReportCollection = $this->reportEngine->fetchProductReport($startDate, $endDate, $order);

        return __dataTable($engineReaction, $requireColumns, [
            'productChartData' => $orderReportCollection,
            'duration' => config('__tech.report_duration'),
            'orderDateList' => config('__tech.orders.date_filter_code'),
        ]);
    }


    /**
     * return mathching status.
     *
     * @param int $ID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findStatus($ID)
    {
        // Get orders status code
        $status = config('__tech.orders.status_codes');

        return $status[$ID];
    }

    /**
     * return mathching status.
     *
     * @param int $ID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findPaymentStatus($ID)
    {
        // Get orders status code
        $status = config('__tech.orders.payment_status');

        return $status[$ID];
    }

    /**
     * order detail dialog.
     *
     * @param int $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function orderDetailsSupportData($orderID)
    {
        $processReaction = $this->reportEngine
                                ->prepareOrderDetailsDialogData($orderID);

       // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Order does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * download pdf.
     *
     * @param int $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function pdfDownload(Request $request, $orderID)
    {
        return  $this->reportEngine->processPdfDownload($orderID);
    }

    /**
     * download excel.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function excelDownload($startDate, $endDate, $status, $order, $currency)
    {
        return  $this->reportEngine->processExcelDownload($startDate, $endDate, $status, $order, $currency);
    }

    /**
     * download payment excel.
     *
     * @param int $startDate
     * @param int $endDate
     * @param int $status
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function paymentExcelDownload($startDate, $endDate, $status, $order, $currency)
    {
        return  $this->reportEngine->processPaymentExcelDownload($startDate, $endDate, $status, $order, $currency);
    }

    /**
     * get report config items excel.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function orderConfigItems($startDate, $endDate, $status, $order)
    {  
        $config = Config('__tech.orders');
        $orderData = $this->reportEngine->getAllOrder($startDate, $endDate, $status, $order);
        $orderPaymentData = $this->reportEngine->getAllOrderPayment($startDate, $endDate, $status, $order);
       
        $currencyList = [];
        if (!__isEmpty($orderData)) {
            $currencyList = $orderData->pluck('currency_code')->toArray();
        }

        $paymentCurrencyList = [];
        if (!__isEmpty($orderPaymentData)) {
            $paymentCurrencyList = $orderPaymentData->pluck('currency_code')->toArray();
        }

        $defaultCurrencyExist = false;
        if (in_array(getStoreSettings('currency_value'), $currencyList)) {
            $defaultCurrencyExist = true;
            array_push($currencyList, getStoreSettings('currency_value'));
        }
        
        // array_push($paymentCurrencyList, getStoreSettings('currency_value'));
      

        // get engine reaction
        return __apiResponse([
                'orderConfigStatusItems' => $config['status_codes'],
                'payment_status' => $config['payment_status'],
                'orderConfigDateItems' => $config['date_filter_code'],
                'currentCurrency' => getStoreSettings('currency_value'),
                'currencyList' => array_unique($currencyList),
                'paymentCurrencyList' => $paymentCurrencyList,
                'defaultCurrencyExist' => $defaultCurrencyExist 
            ]);
    }
}
