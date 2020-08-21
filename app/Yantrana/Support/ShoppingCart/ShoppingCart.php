<?php

namespace App\Yantrana\Support\ShoppingCart;
use App\Yantrana\Components\Product\Models\ProductOptionValue;

/**
     * This ShoppingCart class for manage ShoppingCart globally -.
     *---------------------------------------------------------------- */
    class ShoppingCart
    {
        /**
         * Session class instance.
         *
         * @var Illuminate\Session\SessionManager
         */
        protected $session;

        /**
         * Constructor.
         *
         * @param Illuminate\Session\SessionManager $session Session class instance
         *--------------------------------------------------------------------------------*/
        public function __construct($session)
        {
            $this->session = $session;
        }

        /**
         * Generate a unique id for the new row.
         *
         * @param int $ID
         *
         * @return string
         *---------------------------------------------------------------- */
        protected function generateRowId($ID)
        {
            return md5(uniqid($ID, true));
        }

        /**
         * record available in cart.
         *
         * @return bool
         *---------------------------------------------------------------- */
        public function has($name = 'shoppingCartProduct')
        {
            if ($this->session->has($name)) {
                return true;
            }

            return false;
        }

        /**
         * Handle the fetch data into the session in array formate.
         *
         * @param sting $name
         *
         * @return array
         *---------------------------------------------------------------- */
        public function fetch($name = 'shoppingCartProduct')
        {
            return  ($this->session->has($name))
                    ? $this->session->get($name)
                    : [];
        }

        /**
         * Handle the set data into the session in array formate.
         *
         * @param string $name
         * @param array  $items
         *
         * @return array
         *---------------------------------------------------------------- */
        public function set($name, $items)
        {
            return  $this->session->set($name, $items);
        }

        /**
         * fetch cart array convert in collection object.
         *
         * @return collection object
         *---------------------------------------------------------------- */
        protected function cartCollectionObject()
        {
            $cartContent = $this->fetch();

            $cartCollections = collect($cartContent);

            return  $cartCollections;
        }

        /**
         * Handle the setting a single variable in session.
         *
         * @param array $items
         *---------------------------------------------------------------- */
        protected function save($items)
        {
            return $this->session->set('shoppingCartProduct', $items);
        }

        /**
         * Handle the find ItemID in cart.
         *
         * @param int $productID
         *
         * @return array
         *---------------------------------------------------------------- */
        public function findID($productID)
        {
            $cartCollections = $this->cartCollectionObject();
            $cartItem = $cartCollections->where('id', $productID);

            return $cartItem;
        }

		/**
         * Handle the find ItemID in cart.
         *
         * @param int $productID
         *
         * @return array
         *---------------------------------------------------------------- */
        public function findById($productID)
        {
            $cartCollections = $this->cartCollectionObject();

            $cartItem = $cartCollections->where('id', $productID);

            return $cartItem->first();
        }

        /**
         * Handle the find rowID in cart.
         *
         * @param string $rowId The ID of the row to fetch
         *
         * @return array
         *---------------------------------------------------------------- */
        public function findRow($rowID)
        {
            $cartCollections = $this->cartCollectionObject();

            return $cartCollections->where('rowid', $rowID)->first();
        }

        /**
         * Handle the add data into the session in array formate.
         *
         * @param array $data
         * @param array $inputData
         *
         * @return array
         *---------------------------------------------------------------- */
        public function processAddOrUpdate($productID, $inputData, $product, $activeDiscount)
        {
            $existingCart = [];
            
            // check if the shoppingCart is exist in session
            if ($this->has()) {
                $cartItemCollection = $this->cartCollectionObject();

                $existingCartItem = $cartItemCollection->where('id', $productID);

                if (!__isEmpty($existingCartItem)) {
                    $isAvailableOption = $this->checkProductOptions(
                            $productID,
                            $existingCartItem,
                            $inputData
                        );

                    // check option is present so return rowID
                    if ($isAvailableOption) {
                        return $this->updateRow($isAvailableOption, $inputData['quantity']);
                    } else {
                        return $this->addRow($this->makeCartDataStoredFormate($productID, $inputData, $product), $activeDiscount);
                    }
                }
            }

            return $this->addRow($this->makeCartDataStoredFormate($productID, $inputData, $product), $activeDiscount);
        }

        /**
         * formate the data for storing data into cart.
         *
         * @param int productID
         * @param array inputData
         *
         * @return array
         *---------------------------------------------------------------- */
        public function makeCartDataStoredFormate($productID, $inputData, $product)
        {
            $optionObj = [];
            
            if (!empty($inputData['options'])) {
                $options = $inputData['options'][$productID];

                foreach ($options as $key => $option) {
                    $optionObj[] = [
                        'optionID' => (int) $option['product_option_labels_id'],
                        'valueID' => (int) $option['id'],
                        'valueName' => $this->getValueName($option['id']),//$option['name'],
                        'optionName' => $option['optionName'],
                        'addonPrice' => numberFormatAmount($option['addon_price']),
                    ];
                }
            }

            return [
                'qty'            => (int) $inputData['quantity'],
                'id'             => (int) $productID,
                'customProductId' => $product['product_id'],
                'price'          => handleCurrencyAmount($product['price']),
                'name'           => $inputData['name'],
                'options'        => $optionObj,
                'price'          => numberFormatAmount($product['price']),
                'thumbnail'      => $product['thumbnail']
            ];
        }

        /**
         * add new product in the cart.
         *
         * @param param1 $data
         *
         * @return array
         *---------------------------------------------------------------- */
        public function addRow($data, $activeDiscount)
        {
            $rowID = $this->generateRowId($data['id']);
            
            $addonPrice = 0;
            if (!__isEmpty($data['options'])) {
                foreach ($data['options'] as $option) {
                    $addonPrice += array_get($option, 'addonPrice');
                }                
            }
            
            $poductPriceWithAddon = handleCurrencyAmount($addonPrice) + handleCurrencyAmount($data['price']);
            
            $productDiscount = calculateSpecificProductDiscount($data['id'], $poductPriceWithAddon, $activeDiscount,  true);
            
            $cartRows = [
                'rowid' => $rowID,
                'qty' => $data['qty'],
                'id' => $data['id'],
                'customProductId' => $data['customProductId'],
                'name' => $data['name'],
                'options' => $data['options'],
                'price' => handleCurrencyAmount($data['price']),
                'thumbnail' => $data['thumbnail'],
                'discount'  => $productDiscount,
                'currency'       => getCurrency(),
                'currencySumbol' => getCurrencySymbol()
            ];

            $existingCart = [];

            if ($this->has()) {
                $existingCart = $this->session->get('shoppingCartProduct');
            }

            $existingCart[$rowID] = $cartRows;

            $this->save($existingCart);

            return $rowID;
        }

        /**
         * This function check option already exist in
         * shopping cart related to product ID and return rowid or false.
         *
         * @param int   $productID
         * @param array $existingCart
         * @param array $inputData
         *
         * @return rowid or false
         *---------------------------------------------------------------- */
        protected function checkProductOptions($productID, $productCartRows, $inputData)
        {
            $selectedOptions = [];
            $inputOptionsCount = 0;

            //  Input options related cart option
            if (!__isEmpty($inputData)) {
                if (!empty($inputData['options'])) {
                    $inputData = $inputData['options'][$productID];

                    foreach ($inputData as $option) {
                        if (!__isEmpty($option)) {
                            $selectedOptions[$option['product_option_labels_id']] = $option['id'];
                            ++$inputOptionsCount;
                        }
                    }
                }
            }

            //  Shopping cart option
            if (!__isEmpty($productCartRows)) {
                $rowFound = null;

                foreach ($productCartRows as $cartRow) {
                    if (!__isEmpty($cartRow['options'])) {
                        $matchedCount = 0;
                        $cartProductOptionsCount = 0;

                        foreach ($cartRow['options'] as $cartProductOption) {
                            if (array_key_exists($cartProductOption['optionID'], $selectedOptions)
                                    and ($selectedOptions[$cartProductOption['optionID']] == $cartProductOption['valueID'])) {
                                ++$matchedCount;
                            }

                            ++$cartProductOptionsCount;
                        }

                        // match count so it available option in cart
                        if ($matchedCount === count($selectedOptions)) {
                            $rowFound = $cartRow['rowid'];

                            break;
                        }
                    } else {
                        // return row id for existing row update.
                        $rowFound = $cartRow['rowid'];
                    }
                }
            }

            return $rowFound;
        }

        /**
         * Handle the f update row in cart.
         *
         * @param string $rowID
         * @param int    $inputQuantity
         *
         * @return string
         *---------------------------------------------------------------- */
        protected function updateRow($rowID, $inputQuantity)
        {
            $cartItems = $this->fetch();

            if ($inputQuantity > 0) {
                foreach ($cartItems as $key => $cartItem) {
                    if ($key == $rowID) {
                        $cartItems[$rowID]['qty'] = (int) $inputQuantity;
                    }
                }

                // update row
                $this->save($cartItems);

                return $rowID;
            }

            return false;
        }

        /**
         * Update cart row quantity.
         *
         * @param string $rowID
         * @param int    $quantity
         *
         * @return array
         *---------------------------------------------------------------- */
        public function updateQuantity($rowID, $quantity)
        {
            $cartItems = $this->fetch();
            $updatedRowID = $this->updateRow($rowID, $quantity);

            if ($updatedRowID) {
                return collect($cartItems)->where('rowid', $updatedRowID)->first();
            }

            return false;
        }

        /**
         * Handle the search if the cart has a item.
         *
         * @param string rowID
         *---------------------------------------------------------------- */
        public function search($productID, $inputData)
        {
            if (__isEmpty($productID)) {
                return false;
            }

            $cartItemCollection = $this->cartCollectionObject();
            $existingCartItem = $cartItemCollection->where('id', $productID);

            if (!__isEmpty($existingCartItem)) {
                $isAvailableOption = $this->checkProductOptions(
                        $productID,
                        $existingCartItem,
                        $inputData
                    );

                return $isAvailableOption;
            }
        }

        /**
         * Handle the where perticular product id.
         *
         * @param int productID
         *---------------------------------------------------------------- */
        public function where($productID)
        {
            $cartItemCollection = $this->cartCollectionObject();
            $existingCartItem = $cartItemCollection->where('id', $productID);

            return __isEmpty($existingCartItem) ? false : true;
        }

        /**
         * Handle the Remove a row from the cart.
         *
         * @param string $rowId The rowid of the item
         *
         * @return bool
         *---------------------------------------------------------------- */
        public function remove($rowID)
        {
            $cartContents = $this->fetch();

            if (empty($cartContents)) {
                return false;
            }

            if ($this->has()) {
                foreach ($cartContents as $key => $val) {
                    if ($val['rowid'] == $rowID) {
                        unset($_SESSION['shoppingCartProduct'][$rowID]);

                        return true;
                    }
                }
            }

            return false;
        }

        /**
         * Handle the total price.
         *
         * @param array $data
         *
         * @return array
         *---------------------------------------------------------------- */
        public function total($items = [], $discount = null, $options = null)
        {
            //$shippingCharges = getStoreSettings('shipping_charges');
            $currency = getCurrency();

            if (empty($items)) {
                $total = [
                   'base_price' => priceFormat(0, true, true),
                   'shipping' => __('Nil'),
                   'tax' => __('Nil'),
                   'total' => priceFormat(0, true, true),
                   'totalBasePrice' => 0,
                   'currency' => $currency,
                ];

                return $total;
            }

            $basePrice = '';

            $baseTotalItemCart = array_sum($items);

            if (!empty($baseTotalItemCart)) {
                if (!empty($discount)) {
                    $basePrice = $baseTotalItemCart - handleCurrencyAmount($discount);
                } else {
                    $basePrice = $baseTotalItemCart;
                }
            }

            $shippingCharges = 0;
            if (!empty($options['shipping']['discount'])) {
                $shippingCharges = $options['shipping']['discount'];
            }

            $taxCharges = 0;
            if (!empty($options['tax']['discount'])) {
                $taxCharges = $options['tax']['discount'];
            }

            $shippingCharges = ($options['shipping']['type'] != 3) ? $shippingCharges : 0;

            $totalPrice = $basePrice + $shippingCharges + $taxCharges;
            
            $total = [
               'cartTotal' => priceFormat($baseTotalItemCart, true, true),
               'base_price' => priceFormat($basePrice, false, true),
               'unFormatedCartTotalAmount' => $basePrice,
               'shippingFormated' => priceFormat($shippingCharges, false, true),
               'shipping' => $shippingCharges,
               'taxFormated' => priceFormat($taxCharges, false, true),
               'tax' => $taxCharges,
               'total' => priceFormat($totalPrice, true, true),
               'totalAmount' => $totalPrice,
               'totalBasePrice' => $basePrice,
               'shippingType' => $options['shipping']['type'],
               'currency' => $currency,
            ];

            return $total;
        }

        /**
         * Handle the fetch data into the session in array formate.
         *
         * @param array $data
         *
         * @return array
         *---------------------------------------------------------------- */
        public function sum($price = [])
        {
            if (empty($price)) {
                return 0;
            }

            return array_sum($price);
        }

        /**
         * Remove All items from cart.
         *
         * @return bool
         *---------------------------------------------------------------- */
        public function removeAll()
        {   // session_destroy();
            // Removing session data

            if ($this->has()) {
                $this->session->remove('shoppingCartProduct');
            }
        }

        /**
         * Remove All invalid items from cart.
         *
         * @param  array rowIDs;
         *
         * @return bool
         *---------------------------------------------------------------- */
        public function removeInvalidItems($rowIDs = [])
        {
            $cartItems = $this->fetch();

            if (empty($cartItems) or empty($rowIDs)) {
                return false;
            }

            foreach ($rowIDs as $key => $rowID) {
                $this->remove($rowID);
            }

            return true;
        }

        /**
         * verifyCartProduct with communicate with database object.
         *
         * @param array  $cartItem
         * @param object $products
         *
         * @return array
         *---------------------------------------------------------------- */
        public function verifyCartProduct($cartItem, $products, $activeDiscounts)
        {

            // ERR_PRODUCT_NOT_FOUND
            // ERR_PRODUCT_ID_MISMATCH
            // ERR_PRODUCT_INACTIVE
            // ERR_PRODUCT_OUT_OF_STOCK
            // ERR_PRODUCT_BRAND_INACTIVE
            // ERR_PRODUCT_CATEGORY_INACTIVE
            // ERR_PRODUCT_PRICE_MISMATCH
            // ERR_PRODUCT_INVALID_OPTION_COUNT
            // ERR_PRODUCT_INVALID_OPTION_VALUES_MISSSING
            // ERR_PRODUCT_INVALID_OPTION
            // ERR_PRODUCT_INVALID_OPTION_VALUES
            // ERR_PRODUCT_INVALID_OPTION_VALUE_NAME
            // ERR_PRODUCT_INVALID_OPTION_NAME
            // ERR_PRODUCT_INVALID_OPTION_VALUE_PRICE

            // set product id
            $cartProductId = (int) $cartItem['id'];
            // fetch the cart product from db products
            $validProduct = $products->where('id', $cartProductId)->first();

            // error product not found
            if (__isEmpty($validProduct)) {
                return [
                    'result' => 'ERR_PRODUCT_NOT_FOUND',
                    'item_id' => $cartProductId,
                ];
            }

            // error product not found
            if ($validProduct->product_id != $cartItem['customProductId']) {
                return [
                    'result' => 'ERR_PRODUCT_ID_MISMATCH',
                    'item_id' => $cartProductId,
                ];
            }

            // product status is not active
            if ($validProduct->status !== 1) {
                return [
                    'result' => 'ERR_PRODUCT_INACTIVE',
                    'item_id' => $cartProductId,
                ];
            }

            // product is out of stock
            if ($validProduct->out_of_stock == 1) {
                return [
                    'result' => 'ERR_PRODUCT_OUT_OF_STOCK',
                    'item_id' => $cartProductId,
                ];
            } else if ($validProduct->out_of_stock == 2 || $validProduct->out_of_stock == 3) {
                return [
                    'result' => 'ERR_PRODUCT_COMING_SOON',
                    'item_id' => $cartProductId,
                ];
            } 

            // check currency for every item
            if ($cartItem['currency'] !== getCurrency()) {
                return [
                    'result' => 'ERR_CURRENCY_INVALID',
                    'item_id' => $cartProductId,
                ];
            }

            // check currency for every item
            if ($cartItem['currencySumbol'] !== getCurrencySymbol()) {
                return [
                    'result' => 'ERR_CURRENCY_SYMBOL_INVALID',
                    'item_id' => $cartProductId,
                ];
            }

            // check product brand & its status
            if (!__isEmpty($validProduct->brand) and $validProduct->brand->status !== 1) {
                return [
                    'result' => 'ERR_PRODUCT_BRAND_INACTIVE',
                    'item_id' => $cartProductId,
                ];
            }

            // get product category status
            $productCategoryStatus = getProductCategory(getAllCategories(), $cartProductId);
            // error if inactive
            if ($productCategoryStatus === false) {
                return [
                    'result' => 'ERR_PRODUCT_CATEGORY_INACTIVE',
                    'item_id' => $cartProductId,
                ];
            }
            // product price does not match
            if (numberFormatAmount($validProduct->price) != numberFormatAmount($cartItem['price'])) {
                return [
                    'result' => 'ERR_PRODUCT_PRICE_MISMATCH',
                    'item_id' => $cartProductId,
                ];
            }

            // get product options
            $productOptions = $validProduct->option;
            $cartProductOptions = $cartItem['options'];
            
            // if not available no need to further investigation, return it
            if ((__isEmpty($productOptions) === true) and empty($cartProductOptions) === true) {

                $specificProductDiscount = calculateSpecificProductDiscount($cartProductId, numberFormatAmount($validProduct->price), $activeDiscounts);
                
                if ($specificProductDiscount['isDiscountExist']) {
                    if ($specificProductDiscount['productPrice'] < 0) {
                        return [
                            'result' => 'ERROR_PRODUCT_SPECIFIC_DISCOUNT',
                            'item_id' => $cartProductId,
                        ];
                    }
                }

                if (isset($cartItem['discount'])) {
                    if (numberFormatAmount($cartItem['discount']['rawDiscount']) != numberFormatAmount($specificProductDiscount['rawDiscount'])) {
                        return [
                            'result' => 'ERROR_PRODUCT_SPECIFIC_DISCOUNT_AMOUNT',
                            'item_id' => $cartProductId,
                        ];
                    }
                }
                
                return [
                    'result' => true,
                    'item_id' => $cartProductId,
                ];
            }
            
            // check of both items have same options count
            if (count($cartProductOptions) !== $productOptions->count()) {
                return [
                    'result' => 'ERR_PRODUCT_INVALID_OPTION_COUNT',
                    'item_id' => $cartProductId,
                ];
            }

            // valid options container
            $validOptions = [];
            $addonPriceTotal = 0;

            foreach ($productOptions as $option) {
                // create array item for each valid option
                    $validOptions[$option->id] = [];
                    // option details
                    $validOptions[$option->id]['details'] = $option->toArray();
                    // options values container
                    $validOptions[$option->id]['values'] = [];
                    // option should have values if not error!!
                    if (__isEmpty($option->optionValues) === true) {
                        return [
                            'result' => 'ERR_PRODUCT_INVALID_OPTION_VALUES_MISSSING',
                            'item_id' => $cartProductId,
                        ];
                    }
                    // store each valid option values in to container for later use to verify
                    foreach ($option->optionValues as $optionValue) {
                        $validOptions[$option->id]['values'][$optionValue->id] = $optionValue->toArray();
                    }
            }

            // check for each product option items
            foreach ($cartProductOptions as $cartProductOption) {
                // option should have in valid options container
                if (array_key_exists($cartProductOption['optionID'], $validOptions) === false) {
                    return [
                        'result' => 'ERR_PRODUCT_INVALID_OPTION',
                        'item_id' => $cartProductId,
                    ];
                }

                // option values should have in valid option values container
                if (array_key_exists($cartProductOption['valueID'],
                    $validOptions[$cartProductOption['optionID']]['values']) === false) {
                    return [
                        'result' => 'ERR_PRODUCT_INVALID_OPTION_VALUES',
                        'item_id' => $cartProductId,
                    ];
                }

                // get valid option details
                $validOptionDetails = $validValue = $validOptions[$cartProductOption['optionID']]['details'];
                // fetch valid option value
                $validValue = $validOptions[$cartProductOption['optionID']]['values'][$cartProductOption['valueID']];

                // check of option name is same
                if ($cartProductOption['optionName'] !== $validOptionDetails['name']) {
                    return [
                        'result' => 'ERR_PRODUCT_INVALID_OPTION_NAME',
                        'item_id' => $cartProductId,
                    ];
                }

                // check if value name same
                if (($cartProductOption['valueName'] !== $validValue['name'])) {
                    return [
                        'result' => 'ERR_PRODUCT_INVALID_OPTION_VALUE_NAME',
                        'item_id' => $cartProductId,
                    ];
                }

                // check if addon price is the same
                if (numberFormatAmount($cartProductOption['addonPrice']) != numberFormatAmount($validValue['addon_price'])) {
                    return [
                        'result' => 'ERR_PRODUCT_INVALID_OPTION_VALUE_PRICE',
                        'item_id' => $cartProductId,
                    ];
                }

                $addonPriceTotal = $addonPriceTotal + $validValue['addon_price'];
            }

            $productSubTotal = numberFormatAmount($validProduct->price) + numberFormatAmount($addonPriceTotal);

            $productDiscount = calculateSpecificProductDiscount($cartProductId, $productSubTotal, $activeDiscounts);

            // Check if discount is exist
            if ($productDiscount['isDiscountExist']) {
                if ($productDiscount['productPrice'] < 0) {
                    return [
                        'result' => 'ERROR_PRODUCT_SPECIFIC_DISCOUNT',
                        'item_id' => $cartProductId,
                    ];
                }
            }

            if (isset($cartItem['discount'])) {
                if (numberFormatAmount($cartItem['discount']['rawDiscount']) != numberFormatAmount($productDiscount['rawDiscount'])) {
                    return [
                        'result' => 'ERROR_PRODUCT_SPECIFIC_DISCOUNT_AMOUNT',
                        'item_id' => $cartProductId,
                    ];
                }
            }
            
            // clean up
             unset($cartProductOptions, $validOptions, $productOptions, $validProduct, $products, $cartItem);

            // all looks good, it seems to be valid product you can go ahead!!
            return [
                'result' => true,
                'item_id' => $cartProductId,
            ];
        }

        /**
         * Handle the add data into the session in array formate.
         *
         * @param number $valueId
         *
         * @return array
         *---------------------------------------------------------------- */
        public function getValueName($valueId)
        {
            $optionValues = ProductOptionValue::where('id', $valueId)->first();
            return $optionValues->name;
        }
    }