<?php
/*
* BrandController.php - Controller file
*
* This file is part of the Brand component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Brand\BrandEngine;
use App\Yantrana\Components\Brand\Requests\BrandAddRequest;
use App\Yantrana\Components\Brand\Requests\BrandEditRequest;
use App\Yantrana\Components\Brand\Requests\BrandDeleteRequest;
use Carbon\Carbon;

class BrandController extends BaseController
{
    /**
     * @var BrandEngine - Brand Engine
     */
    protected $brandEngine;

    /**
     * Constructor.
     *
     * @param BrandEngine $brandEngine - Brand Engine
     *-----------------------------------------------------------------------*/
    public function __construct(BrandEngine $brandEngine)
    {
        $this->brandEngine = $brandEngine;
    }

    /**
     * get all brand.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index()
    {
        $engineReaction = $this->brandEngine
                               ->prepareList();

        $requireColumns = [
            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'formattedCreatedDate' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'logo_url' => function ($key) {
                return getBrandLogoURL($key['_id'], $key['logo']);
            },
            'productCount' => function ($key) {
                return count($key['product_count']);
            },
            '_id', 'name', 'status',
            'canAccessDetail' => function() {
                if (canAccess('manage.brand.detailSupportData')) {
                    return true;
                }
                return false;
            },
            'canAccessProductList' => function() {
                if (canAccess('manage.brand.product.list')) {
                    return true;
                }
                return false;
            },
            'canAccessEdit' => function() {
                if (canAccess('manage.brand.edit.process') and canAccess('manage.brand.editSupportData')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.brand.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * get detail dialog.
     *
     * @param int $brandID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($brandID)
    {
        $processReaction = $this->brandEngine
                                ->prepareDetail($brandID);

        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Brand does not exist.'),
                ], $processReaction['data']);
    }
    /**
     * Handle add brand request.
     *
     * @param object BrandAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProccess(BrandAddRequest $request)
    {
        $processReaction = $this->brandEngine
                                ->processAdd($request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Brand added successfully.'),
                    3 => __tr('Selected logo file does not exist.'),
                    2 => __tr('oh..no. error.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details edit request.
     *
     * @param int $brandID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($brandID)
    {
        if (empty($brandID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->brandEngine
                                ->fetchData($brandID);

        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Brand does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle edit brand request.
     *
     * @param object BrandEditRequest $request
     * @param $brandID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editProcess(BrandEditRequest $request, $brandID)
    {
        if (empty($brandID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->brandEngine
                                ->processUpdate($brandID, $request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Brand updated successfully.'),
                    2 => __tr('oh..no. error.'),
                    3 => __tr('Selected logo file does not exist.'),
                    14 => __tr('Nothing update.'),
                    18 => __tr('Brand does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle delete brand request.
     *
     * @param $brandID
     * @param object BrandDeleteRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($brandID, BrandDeleteRequest $request)
    {
        $processReaction = $this->brandEngine
                                ->processDelete($brandID, $request->all());

        // get engine reaction
        return __processResponse($processReaction);
    }

    /**
     * fetch active records.
     *
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function fetchActiveRecord()
    {
        $processReaction = $this->brandEngine
                                ->fetchIsActive();

        $processReaction['data']['hideSidebar'] = true;
        $processReaction['data']['showFilterSidebar'] = false;
        $brands = $processReaction['data'];

        return $this->loadPublicView('brand.list', $brands);
    }
}
