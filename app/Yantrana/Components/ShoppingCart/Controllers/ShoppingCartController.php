<?php
/*
* ShoppingCartController.php - Controller file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Controllers;

use Session;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShoppingCart\ShoppingCartEngine;
use App\Yantrana\Components\ShoppingCart\Requests\AddItemInCartRequest;
use App\Yantrana\Components\ShoppingCart\Requests\UpdateItemInCartRequest;
use Illuminate\Http\Request;
use JavaScript;

class ShoppingCartController extends BaseController
{
    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * Constructor.
     *
     * @param ShoppingCartEngine $shoppingCartEngine - ShoppingCart Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ShoppingCartEngine $shoppingCartEngine)
    {
        $this->shoppingCartEngine = $shoppingCartEngine;
    }

    /**
     * Add Item in cart.
     *
     * @param object AddItemInCartRequest $request
     * @param number                      $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addItem(AddItemInCartRequest $request, $productID)
    {
        $processReaction = $this->shoppingCartEngine
                                ->processAddProductInCart(
                                    $request->all(),
                                    $productID
                                );

        return __processResponse($processReaction, [
                2 => __tr('Product does not exist, Please reload page.'),
                3 => __tr('This product is currently not available, Please reload page.'),
                4 => __tr('This product is out of stock, Please reload page.'),
                9 => __tr("Category of this product is inactive therefore product is not available publicly."),
            ], $processReaction['data']);
    }

    /**
     * Get all cart content.
     *------------------------------------------------------------------------ */
    public function getCartDetails()
    {
        $engineReaction = $this->shoppingCartEngine
                                ->getCartDetails();

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
     * check cart products.
     *
     * @param $productID
     * @param Request $request
     *---------------------------------------------------------------- */
    public function checkProductCart(Request $request, $productID = null)
    {
        $engineReaction = $this->shoppingCartEngine
                               ->getProductCartData($productID, $request->all());

        return __processResponse($engineReaction, [
             2 => __tr('Product does not exist.'),
            ], $engineReaction['data']);
    }

    /**
     * Remove particular item with rowId in the cart.
     *
     * @param number         $ItemID.
     * @param number Request $request.
     *
     * @return json object
     *------------------------------------------------------------------------ */
    public function removeItem(Request $request, $itemID)
    {
        if (empty($itemID)) {
            return error404();
        }

        $engineReaction = $this->shoppingCartEngine
                               ->removeItem($itemID);

        return __processResponse($engineReaction, [
             1 => __tr('Item removed successfully.'),
             2 => __tr('Item not found.'),
            ], $engineReaction['data']);
    }

    /**
     * Remove all item.
     *
     *
     * @return json object
     *------------------------------------------------------------------------ */
    public function removeAllItems()
    {
        $engineReaction = $this->shoppingCartEngine
                               ->processRemoveAlItems();

        return __processResponse($engineReaction, [
             1 => __tr('All items removed successfully.'),
             2 => __tr('Item not found.'),
            ], $engineReaction['data']);
    }

    /**
     * Remove all invalid and out of stock items.
     *
     * @return json object
     *------------------------------------------------------------------------ */
    public function removeInvalidItems()
    {
        $engineReaction = $this->shoppingCartEngine
                               ->processRemoveInvalidItems();

        return __processResponse($engineReaction, [
             1 => __tr('All items removed successfully.'),
             2 => __tr('Items not found.'),
             7 => __tr('Oh..no..error!.'),
            ], $engineReaction['data']);
    }

    /**
     * update cart item quantity.
     *
     * @param object UpdateItemInCartRequest $request
     * @param $itemID
     *
     * @return json data
     *---------------------------------------------------------------- */
    public function updateItemQty(UpdateItemInCartRequest $request, $itemID)
    {
        if (__isEmpty($itemID)) {
            return error404();
        }

        $engineReaction = $this->shoppingCartEngine
                                ->processUpdateQty($request->all(), $itemID);
                                
        $qtyLimit = config('__tech.qty_limit');

        return __processResponse($engineReaction, [
             2 => __tr('Items not found.'),
             3 => __tr('The quantity may not be greater than ').$qtyLimit,
            ], $engineReaction['data']);
    }

    /**
     * update cart btn string cart btn.
     *---------------------------------------------------------------- */
    public function updateCartBtnString()
    {
        $engineReaction = $this->shoppingCartEngine->getCartBtn();

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
     * shopping cart view.
     *---------------------------------------------------------------- */
    public function cartView()
    {
        $breadcrumb = $this->shoppingCartEngine
                            ->generateBreadcrumb('shopping-cart');

        $breadcrumb['data']['hideSidebar'] = false;
        $breadcrumb['data']['showFilterSidebar'] = false;

        if (getStoreSettings('brand_menu_placement') == 2 and getStoreSettings('categories_menu_placement') == 2) {
            $breadcrumb['data']['hideSidebar'] = true;
        }

        if (getStoreSettings('brand_menu_placement') == 4 and getStoreSettings('categories_menu_placement') == 4) {
            $breadcrumb['data']['hideSidebar'] = true;
        }

        return $this->loadPublicView('shoppingCart.cart-view', $breadcrumb['data']);
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
        $status = config('__tech.orders.status_codes');

        return $status[$ID];
    }

    /**
     * order dialog.
     *---------------------------------------------------------------- */
    public function orderSupportData(Request $request, $orderUID)
    {
        if (isActiveUser()) {
            return redirect()->route('user.login');
        }

        if (!empty($request['order_id'])) {
            if (!isLoggedIn()) {
                Session::put('redirect_intended_order_id', $orderUID);

                return redirect()->route('user.login');
            }
        }

        if (!isLoggedIn()) {
            return redirect()->route('user.login');
        }

        if (empty($orderUID)) {
            return __apiResponse([], 7);
        }

        $orderDetail = $this->shoppingCartEngine
                                ->prepareCartOrdersdialogData($orderUID);

        if ($orderDetail == 18) {
            return redirect()->route('home.page');
        }

        JavaScript::put([
            'orderDetails' => $orderDetail['data']['orderDetails'],
            'productOrders' => $orderDetail['data']['productOrders'],
        ]);

        return $this->loadPublicView('order.user.details', $orderDetail['data']);
    }

    /**
     * order log dialog.
     *
     * @param $orderID
     *---------------------------------------------------------------- */
    public function orderLogSupportData($orderID)
    {
        $invalidUser = isActiveUser();

        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->shoppingCartEngine
                                ->prepareOrdersLogDialogData($orderID, $invalidUser);

        if ($processReaction == 18) {
            return error404();
        }

       // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Order does not exist.'),
                    9 => __tr('Invalid request please contact administrator.'),
                ], $processReaction['data']);
    }

    /**
     * cancel order support data.
     *
     * @param int $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function cancelOrderSupportData($orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->shoppingCartEngine
                                ->prepareCancelOrderData($orderID);

       // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Order does not exist.'),
                    2 => __tr('Invalid Request.'),
                ], $processReaction['data']);
    }

    /**
     * order cancel.
     *
     * @param int $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function orderCancel(Request $request, $orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->shoppingCartEngine
                                ->processCancelOrder($orderID, $request->all());

       // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Order cancelled successfully.'),
                    18 => __tr('Order does not exist.'),
                    2 => __tr('Invalid request.'),
                    9 => __tr('Invalid request please contact administrator.'),
                ], $processReaction['data']);
    }

    /**
     * Refresh Cart Product
     *
     * @param object Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function refreshCartProduct(Request $request)
    {
        $processReaction = $this->shoppingCartEngine
                                ->processRefreshCartProduct($request->all());

       // get engine reaction
        return __processResponse($processReaction, [], $processReaction['data']);
    }
}
