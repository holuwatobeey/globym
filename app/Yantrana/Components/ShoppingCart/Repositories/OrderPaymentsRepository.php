<?php
/*
* OrderPaymentsRepository.php - Repository file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\ShoppingCart\Models\OrderPayments as OrderPaymentsModel;
use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderPaymentsRepositoryBlueprint;
use DB;
use Auth;
use Hash;

class OrderPaymentsRepository extends BaseRepository implements OrderPaymentsRepositoryBlueprint
{
    /**
     * @var OrderPaymentsModel - OrderPayments Model
     */
    protected $orderPaymentsModel;

    /**
     * @var OrderModel orderModel - Order Model
     */
    protected $orderModel;

    /**
     * Constructor.
     *
     * @param OrderPaymentsModel $orderPaymentsModel - OrderPayments Model
     *-----------------------------------------------------------------------*/
    public function __construct(OrderPaymentsModel $orderPaymentsModel,
                        OrderModel $orderModel)
    {
        $this->orderPaymentsModel = $orderPaymentsModel;
        $this->orderModel = $orderModel;
    }

    /**
     * Store new coupon using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storePayPalPayment($orderId, $inputData)
    {
        $orderPayment = new $this->orderPaymentsModel();
        $orderPayment->payment_method = 1; // PayPal
        $orderPayment->type = 1; // Credit
        $orderPayment->txn = $inputData['txn_id'];
        $orderPayment->currency_code = $inputData['mc_currency'];
        $orderPayment->gross_amount = $inputData['mc_gross'];
        $orderPayment->fee = array_get($inputData, 'mc_fee');
        $orderPayment->orders__id = $orderId;
        $orderPayment->raw_data = json_encode($inputData);

        // Save Payment Information
        if ($orderPayment->save()) {
            orderLog($orderId, 'Payment for Order '.$orderId.' has been recorded from PayPal.');

            return true;
        }

        return false;
    }

    /**
     * Store new coupon using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeStripePayment($orderDetails, $stripeChargeRequestData)
    {
        $orderPayment = new $this->orderPaymentsModel();
        $orderPayment->payment_method = (configItem('env_settings.stripe_test_mode') == true) ? 8 : 6; // Stripe
        $orderPayment->type = 1; // Credit
        $orderPayment->txn = $stripeChargeRequestData->id;
        $orderPayment->currency_code = strtoupper($stripeChargeRequestData->currency);
        $orderPayment->gross_amount = $orderDetails['totalOrderAmount'];
        $orderPayment->fee = $stripeChargeRequestData->application_fee ?: 0;
        $orderPayment->orders__id = $orderDetails['_id'];
        $orderPayment->raw_data = json_encode($stripeChargeRequestData);

        // Save Payment Information
        if ($orderPayment->save()) {
            return true;
        }

        return false;
    }

     /**
     * Store Paystack payment data
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storePaystackPayment($orderDetails, $paystackChargeRequest)
    {
        $paystackTransationData = $paystackChargeRequest['data']['transactionDetail'];

        $orderPayment = new $this->orderPaymentsModel();
        $orderPayment->payment_method = (configItem('env_settings.paystack_test_mode') == true) ? 21 : 20; // Paystack
        $orderPayment->type = 1; // Credit
        $orderPayment->txn = $paystackTransationData->data->reference;
        $orderPayment->currency_code = strtoupper($paystackTransationData->data->currency);
        $orderPayment->gross_amount = $orderDetails['totalOrderAmount'];
        $orderPayment->fee = $paystackTransationData->data->fees ?: 0;
        $orderPayment->orders__id = $orderDetails['_id'];
        $orderPayment->raw_data = json_encode($paystackTransationData);

        // Save Payment Information
        if ($orderPayment->save()) {
            return true;
        }

        return false;
    }

    /**
     * Store new coupon using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeRazorpayPayment($orderDetails, $razorpayChargeRequestData)
    {
        $orderPayment = new $this->orderPaymentsModel();
        $orderPayment->payment_method = (configItem('env_settings.razorpay_test_mode') == true) ? 12 : 11; // razorpay
        $orderPayment->type = 1; // Credit
        $orderPayment->txn = $razorpayChargeRequestData['id'];
        $orderPayment->currency_code = strtoupper($razorpayChargeRequestData['currency']);
        $orderPayment->gross_amount = $orderDetails['totalOrderAmount'];
        $orderPayment->fee = convertRazorpayAmount($razorpayChargeRequestData['fee']) ?: 0;
        $orderPayment->orders__id = $orderDetails['_id'];
        $orderPayment->raw_data = json_encode($razorpayChargeRequestData);

        // Save Payment Information
        if ($orderPayment->save()) {
            $orderID = $orderPayment->orders__id;

            $order = $this->orderModel
                      ->where('_id', $orderID)
                      ->first();

            if ($order->modelUpdate([
                'payment_status' => 2, // Completed
                // 'status'         => 6 // Completed
            ])) {
                orderLog($orderID, 'Order payment '.$orderID.' has been updated.');
            }

            // Maintain activity log
            orderLog($orderID, 'Payment for Order '.$orderID.' has been recorded.');

            return true;
        }

        return false;
    }

    /**
     * Fetch Payment by TXN.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchByTxn($txnId, $paymentMethod)
    {
        return $this->orderPaymentsModel->where(['txn' => $txnId, 'payment_method' => $paymentMethod])->first();
    }

    /**
     * Fetch Payment Details.
     *
     * @param array $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchDetails($orderPaymentID)
    {
        $paymentDetails = $this->orderPaymentsModel
                                 ->where('_id', $orderPaymentID)
                                 ->first([
                                    'currency_code',
                                    'fee',
                                    'gross_amount',
                                    'payment_method',
                                    'raw_data',
                                    'txn',
                                    'type',
                                    'raw_data',
                                    'created_at',
                                    ]);

        $paymentData = [];

        // Check if payment detail exist
        if (!__isEmpty($paymentDetails)) {
            $paymentData = [
                    'paymentDetails' => $paymentDetails,
                    'rawData' => json_decode($paymentDetails['raw_data']),
            ];
        }

        return $paymentData;
    }

    /**
     * Fetch Order Payment Details.
     *
     * @param array $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrderPayments($orderID)
    {
        return $this->orderPaymentsModel
                    ->where('orders__id', $orderID)
                    ->first();
    }

    /**
     * Fetch order details.
     *
     * @param int $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrderDetails($orderID)
    {
        return $this->orderModel
                      ->where('_id', $orderID)
                      ->select(
                           '_id',
                           'type',
                           'payment_method',
                           'total_amount',
                           'currency_code',
                           'status',
                           'payment_status'
                       )
                      ->first();
    }

    /**
     * Update order payment.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function updateOrderRefundPayment($inputs)
    {
        $orderPayment = new $this->orderPaymentsModel();

        $dataToStore = [
            'txn',
            'type' => 2, // Refund
            'payment_method' => $inputs['paymentMethod'],
            'currency_code' => $inputs['currencyCode'],
            'gross_amount' => $inputs['grossAmount'],
            'fee' => $inputs['orderPaymentFee'],
            'orders__id' => $inputs['orderID'],
            'raw_data' => json_encode([
            	'comment' => $inputs['comment'],
            	'refundData' => $inputs['refundData'] ?? null
            ]),
        ];

        // Check if data store or not
        if ($orderPayment->assignInputsAndSave($inputs, $dataToStore)) {

            // Maintain activity log
            orderLog($orderPayment->orders__id, 'ID of '.$orderPayment->_id.' order refund payment updated.');

            return true;
        }

        return false;
    }

    /**
     * Store order payment if payment details not exist.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeOrderPayment($inputs)
    {
        $orderPayment = new $this->orderPaymentsModel();

        $paymentStatus = $inputs['paymentMethod'];

        $dataToStore = [
            'txn',
            'type'              => 1, // Deposit
            'payment_method'    => $paymentStatus,
            'currency_code'     => $inputs['currencyCode'],
            'gross_amount'      => $inputs['totalAmount'],
            'fee'               => $inputs['orderPaymentFee'],
            'orders__id'        => $inputs['orderID'],
            'raw_data'          => json_encode(['comment' => $inputs['comment']]),
        ];

        // Check if data store or not
        if ($orderPayment->assignInputsAndSave($inputs, $dataToStore)) {
            $orderID = $orderPayment->orders__id;

            $order = $this->orderModel
                      ->where('_id', $orderID)
                      ->first();

            if ($order->modelUpdate(['payment_method' => $paymentStatus])) {
                orderLog($orderID, 'Order payment '.$orderID.' has been updated.');
            }

            // Maintain activity log
            orderLog($orderID, 'Payment for Order '.$orderID.' has been recorded.');

            return true;
        }

        return false;
    }

    /** Fetch order and update status
     * @param int $paymentStatus
     * @param int $orderID
     *
     * @return number
     *-----------------------------------------------------------------------*/
    public function updateOrder($paymentStatus, $orderID)
    {
        $order = $this->orderModel
                      ->where('_id', $orderID)
                      ->first();

        if ($order->modelUpdate(['payment_status' => $paymentStatus])) {
            return true;
        }

        return false;
    }

    /** Fetch order payments and order
     * @return array
     *-----------------------------------------------------------------------*/
    public function fetchOrderPaymentList($startDate, $endDate)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => '_id',
                'totalAmount' => 'total_amount',
                'formattedFee' => 'fee',
                'formattedPaymentMethod' => 'payment_method',
                'formattedPaymentOn' => 'created_at',
            ],
            'searchable' => [
                'order_uid' => 'orders.order_uid',
                'total_amount' => 'orders.total_amount',
                'txn' => 'order_payments.txn',
                'fee' => 'order_payments.fee',
                'payment_method' => 'order_payments.payment_method',
                'created_at' => 'order_payments.created_at',
                ],
        ];

        return $this->orderPaymentsModel
                    ->whereBetween(DB::raw('DATE(order_payments.created_at)'), [$startDate, $endDate])
                    ->join('orders', 'order_payments.orders__id', '=', 'orders._id')
                    ->select(
                        'orders._id',
                        'orders.currency_code',
                        'order_payments._id as order_payment_id',
                        'order_payments.txn',
                        'order_payments.payment_method',
                        'order_payments.gross_amount',
                        'order_payments.fee',
                        'order_payments.orders__id',
                        'order_payments.created_at',
                        'order_payments.type',
                        'orders.order_uid',
                        'orders.total_amount'
                    )->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Fetch order payment details for excel sheet.
     *
     * @param $startDate
     * @param $endDate
     *---------------------------------------------------------------- */
    public function fetchOrderPaymentDetails($startDate, $endDate)
    {
        return $this->orderPaymentsModel
                    ->with('order')
                    ->whereBetween(DB::raw('DATE(order_payments.created_at)'), [$startDate, $endDate])
                    ->get();
    }

    /**
     * Fetch user ID from Order table.
     *
     * @param $orderID
     *
     * @return int
     *---------------------------------------------------------------- */
    public function fetchUserID($orderID)
    {
        return $this->orderModel
        			->with('user')
                    ->where('_id', $orderID)
                    ->first(['users_id']);
    }

    /**
      * Delete sand box order payment
      *
      * @param object $payment
      *
      * @return integer
      *---------------------------------------------------------------- */

    public function deleteSandboxPayment($payment)
    {
        if ($payment->delete()) {
            activityLog("Id of ".$payment->_id." sandbox payment deleted.");

            return true;
        }

        return false;
    }

    /**
      * Delete sand box order payment
      *
      * @param array $inputData
      * @param object $payment
      *
      * @return integer
      *---------------------------------------------------------------- */

    public function deleteOrderPayment($inputData, $payment)
    {
        $password = Auth::user()->password;

        if (Hash::check($inputData['password'], $password) and isAdmin()) {
            if ($payment->delete()) {
                activityLog("Id of ".$payment->_id." payment deleted.");

                return true;
            }
        }

        return false;
    }

    /**
      * Fetch the record of payment
      *
      * @param int $paymentId
      *
      * @return Eloquent collection object
      *---------------------------------------------------------------- */

    public function fetchById($paymentId)
    {
        return $this->orderPaymentsModel->where('_id', $paymentId)->first();
    }

    /**
     * Store order payment if payment details not exist.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeNewPayment($inputs)
    {
        $orderPayment = new $this->orderPaymentsModel();

        $dataToStore = [
            'txn',
            'type',
            'payment_method',
            'currency_code',
            'gross_amount',
            'fee',
            'orders__id',
            'raw_data',
        ];

        // Check if data store or not
        if ($orderPayment->assignInputsAndSave($inputs, $dataToStore)) {
            
            $orderID = $orderPayment->orders__id;

            $order = $this->orderModel
                      ->where('_id', $orderID)
                      ->first();

            if ($order->modelUpdate([
                'payment_status' => $inputs['payment_status'],
                // 'status'         => 6 // Completed
            ])) {
                orderLog($orderID, 'Order payment '.$orderID.' has been updated.');
            }

            // Maintain activity log
            orderLog($orderID, 'Payment for Order '.$orderID.' has been recorded.');

            return true;
        }

        return false;
    }

}
