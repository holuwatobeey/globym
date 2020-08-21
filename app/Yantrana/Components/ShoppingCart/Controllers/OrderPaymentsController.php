<?php
/*
* OrderPaymentsController.php - Controller file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShoppingCart\OrderPaymentsEngine;
use App\Yantrana\Components\ShoppingCart\PaypalEngine;
use App\Yantrana\Components\ShoppingCart\Requests\PaymentUpdateRequest;
use App\Yantrana\Components\ShoppingCart\Requests\PaymentRefundRequest;
use App\Yantrana\Components\ShoppingCart\Requests\PaymentDeleteRequest;
use Carbon\Carbon;

class OrderPaymentsController extends BaseController
{
    /**
     * @var OrderPaymentsEngine - OrderPayments Engine
     */
    protected $orderPaymentsEngine;

    /**
     * Constructor.
     *
     * @param OrderPaymentsEngine $orderPaymentsEngine - OrderPayments Engine
     *-----------------------------------------------------------------------*/
    public function __construct(OrderPaymentsEngine $orderPaymentsEngine)
    {
        $this->orderPaymentsEngine = $orderPaymentsEngine;
    }

    /**
     * Receive IPN Notification Request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function processPaypalIpnRequest(PaypalEngine $paypalEngine)
    {
        $paypalEngine->processIpnRequest();

        echo 'finished';
    }

    /**
     * get details for order payment details.
     *
     * @param int $orderID
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function orderPaymentDetails($orderPaymentID)
    {
        $orderPaymentsDetails = $this->orderPaymentsEngine
                                     ->preparePaymentDetailsDialog($orderPaymentID);

        return __processResponse($orderPaymentsDetails, [
                    18 => __tr('Order payment does not exist'),
                ], $orderPaymentsDetails['data']);
    }

    /**
     * Update payment detail dialog.
     *
     * @param int $orderID
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function orderPaymentUpdateDetails($orderID)
    {
        $orderDetails = $this->orderPaymentsEngine
                             ->prepareOrderPaymentUpdateDialog($orderID);

        return __processResponse($orderDetails, [], true);
    }

    /**
     * Update payment update process.
     *
     * @param int                         $orderID
     * @param object PaymentUpdateRequest $request
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function orderPaymentUpdate(PaymentUpdateRequest $request)
    {
        $processReaction = $this->orderPaymentsEngine
                                ->processUpdateOrderPayment($request->all());

        return __processResponse($processReaction, []);
    }

    /**
     * Order refund detail dialog.
     *
     * @param int $orderID
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function orderRefundDetails($orderID)
    {
        $orderDetails = $this->orderPaymentsEngine
                             ->prepareOrderRefundDialog($orderID);

        return __processResponse($orderDetails, [], true);
    }

    /**
     * Order refund process.
     *
     * @param int                         $orderID
     * @param object PaymentRefundRequest $request
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function orderRefund(PaymentRefundRequest $request, $orderID)
    {
        $processReaction = $this->orderPaymentsEngine
                                ->processRefundOrderPayment($request->all(), $orderID);

        return __processResponse($processReaction, []);
    }

    /**
     * Order payment List.
     *
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function index($startDate, $endDate)
    {
        $processReaction = $this->orderPaymentsEngine
                                ->preparePaymentOrderList($startDate, $endDate);

        $requireColumns = [

            'totalAmount' => function ($key) {

                // Check if type is refund or deposit
                // if it is refunded then add -(Minus) sign front of the amount
                if ($key['type'] == 2) { // Refund
                    $totalAmount = '-'.$key['total_amount'];
                } else {
                    $totalAmount = $key['total_amount'];
                }

                return orderPriceFormat($totalAmount, $key['currency_code']);
            },
            'formattedFee' => function ($key) {
                return orderPriceFormat($key['fee'], $key['currency_code']);
            },
            'formattedPaymentMethod' => function ($key) {
                return getTitle($key['payment_method'], '__tech.orders.payment_methods');
            },
            'isTestOrder' => function ($key) {
                $paymentMethod = $key['payment_method'];

                return ($paymentMethod == 7 // paypal test order
                    or $paymentMethod == 8) // stripe test order
                        ? true : false;
            },
            'formattedPaymentOn' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'formatPaymentData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'order_uid', 'txn', 'payment_method', 'gross_amount', 'fee', 'total_amount', '_id', 'orders__id', 'type', 'order_payment_id',
            'canAccessDelete' => function() {
                if (canAccess('manage.order.payment.delete')) {
                    return true;
                }
                return false;
            },
            'canAccessOrderDetail' => function() {
                if (canAccess('manage.order.details.dialog')) {
                    return true;
                }
                return false;
            },
            'canAccessTxnDetail' => function() {
                if (canAccess('manage.order.payment.detail.dialog')) {
                    return true;
                }
                return false;
            }
        ];

        // create excel sheet download link
        $excelDownloadURL = route('manage.order.payment.excel_download', [$startDate, $endDate]);

        return __dataTable($processReaction, $requireColumns, [
                'excelDownloadURL' => $excelDownloadURL,
                'duration' => config('__tech.report_duration'),
                'isEmptySanboxRecords' => checkisAvailabeSandBoxRecords($processReaction['data'])
            ]);
    }

    /**
     * Download excel sheet.
     *
     * @param $startDate
     * @param $endDate
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function excelDownload($startDate, $endDate)
    {
        return  $this->orderPaymentsEngine->processExcelSheetDownload($startDate, $endDate);
    }

    /**
     * Delete Order Payment
     *
     * @param int $paymentId
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteOrderPayment(PaymentDeleteRequest $request, $paymentId)
    {
        $processReaction = $this->orderPaymentsEngine
                                ->processDeleteOrderPayment($request->all(), $paymentId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Delete Sandbox Payment
     *
     * @param int $paymentId
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteSandboxPayment($paymentId)
    {
        $processReaction = $this->orderPaymentsEngine->processDeleteSandbox($paymentId);

        return __processResponse($processReaction, [], [], true);
    }
}
