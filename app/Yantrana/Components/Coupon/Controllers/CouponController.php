<?php
/*
* CouponController.php - Controller file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Coupon\CouponEngine;
use App\Yantrana\Components\Coupon\Requests\CouponAddRequest;
use App\Yantrana\Components\Coupon\Requests\CouponEditRequest;
use Carbon\Carbon;

class CouponController extends BaseController
{
    /**
     * @var CouponEngine - Coupon Engine
     */
    protected $couponEngine;

    /**
     * Constructor.
     *
     * @param CouponEngine $couponEngine - Coupon Engine
     *-----------------------------------------------------------------------*/
    public function __construct(CouponEngine $couponEngine)
    {
        $this->couponEngine = $couponEngine;
    }

    /**
     * get all coupons.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index($status)
    {
        $engineReaction = $this->couponEngine
                                ->prepareList($status);
        $requireColumns = [
            'start_date' => function ($key) {
                return formatStoreDateTime($key['start']);
            },
            'formatStartData' => function ($key) {
                $createdDate = Carbon::parse($key['start']);
                return $createdDate->diffForHumans();
            },
            'end_date' => function ($key) {
                return (!__isEmpty($key['end']))
                        ? formatStoreDateTime($key['end'])
                        : null;
            },
            'formatEndData' => function ($key) {
                $createdDate = Carbon::parse($key['end']);
                return $createdDate->diffForHumans();
            },
            '_id', 'title', 'status', 'code',
            'canAccessDetail' => function() {
                if (canAccess('manage.coupon.detailSupportData')) {
                    return true;
                }
                return false;
            },
            'canAccessEdit' => function() {
                if (canAccess('manage.coupon.edit.process')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.coupon.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * Handle add coupon request.
     *
     * @param object CouponAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(CouponAddRequest $request)
    {   
        $processReaction = $this->couponEngine
                                ->addProcess($request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Coupon added successfully.'),
                    2 => __tr('oh..no. error.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details edit request.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->fetchData($couponID);
        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details of coupon.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->fetchDetail($couponID);
        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle edit coupon request.
     *
     * @param object BrandEditRequest $request
     * @param int                     $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editProcess(CouponEditRequest $request, $couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->processUpdate($couponID, $request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Coupon updated successfully.'),
                    14 => __tr('Nothing update.'),
                    18 => __tr('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle delete coupon request.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($couponID, Request $request)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->processDelete($couponID);

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Coupon deleted successfully.'),
                    2 => __tr('Something went wrong.'),
                    18 => __tr('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Get coupon discount tpe request.
     *
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getCouponDiscountType()
    {
        $processReaction = $this->couponEngine
                                ->prepareDiscountType();

        // get engine reaction
        return __processResponse($processReaction, [], $processReaction['data']);
    }
}
