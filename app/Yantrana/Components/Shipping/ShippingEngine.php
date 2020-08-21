<?php
/*
* ShippingEngine.php - Main component file 
*
* This file is part of the Shipping component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping;

use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Shipping\Blueprints\ShippingEngineBlueprint;
use App\Yantrana\Components\ShippingType\Repositories\ShippingTypeRepository;
use Config;

class ShippingEngine implements ShippingEngineBlueprint
{
    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var  ShippingTypeRepository $shippingTypeRepository - ShippingType Repository
     */
    protected $shippingTypeRepository;

    /**
     * Constructor.
     *
     * @param ShippingRepository $shippingRepository - Shipping Repository
     * @param SupportRepository  $supportRepository  - Support Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ShippingRepository $shippingRepository,
        SupportRepository $supportRepository,
        ShippingTypeRepository $shippingTypeRepository)
    {
        $this->shippingRepository = $shippingRepository;
        $this->supportRepository = $supportRepository;
        $this->shippingTypeRepository = $shippingTypeRepository;
    }

    /**
     * get prepare shippings list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        return $this->shippingRepository
                    ->fetchForList();
    }

    /**
     * Prepare shipping rule detail.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getDetail($shippingID)
    {
        $shipping = $this->shippingRepository
                         ->fetchDetail($shippingID);

        // check if shipping rule is empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        // get all countries name and id from database
        $countries = $this->supportRepository
                          ->fetchCountry($shipping->countries__id);

        // Get shipping type from config
        $shippingType = config('__tech.shipping.typeShow');

        // Create array for all shipping data
        $shippingData = [
            'country' => $countries->name,
            'shippingType' => $shipping->type,
            'type' => $shippingType[$shipping->type],
            'charges' => $shipping->charges,
            'free_after_amount' => $shipping->free_after_amount,
            'amount_cap' => $shipping->amount_cap,
            'notes' => $shipping->notes,
        ];

        return __engineReaction(1, [
                'shippingData' => $shippingData,
                'currencySymbol' => getCurrencySymbol(), // Get currency symbol of store
            ]);
    }

    /**
     * get countries for add.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchAllCountries()
    {
        // get countries array
        $countries = $this->supportRepository->fetchCountries();

        // get save country code from shipping table
        $shippingCountries = $this->shippingRepository->fetchCountries();

        // get save country code from shipping table
        $shippingTypeCollection = $this->shippingTypeRepository->fetchAll();

        $shippingTypeList = [];
        if (!__isEmpty($shippingTypeCollection)) {
            foreach ($shippingTypeCollection as $key => $type) {
                $shippingTypeList[] = [
                    '_id' => $type->_id,
                    'title' => $type->title
                ];
            }
        }

        $allCountriesCode = [];
        $countriesCollection = [];

        // Create array of countries code
        foreach ($shippingCountries as $countryCode) {
            $allCountriesCode[] = $countryCode['country'];
        }

        // Create key value pair of countries and remove exist country
        foreach ($countries as $country) {

            // Check in array country code exist or not
            // if exist then not include that country in countries list
            // if (!in_array($country['iso_code'], $allCountriesCode) == true) {
                $countriesCollection[] = [
                    'value' => $country['_id'],
                    'text' => $country['name'],
                ];
            // }
        }

        return __engineReaction(1, [
                        'countries' => $countriesCollection,
                        'currencySymbol' => getCurrencySymbol(),
                        'currency' => getCurrency(),
                        'shippingType' => config('__tech.shipping.type'),
                        'shippingTypeList' => $shippingTypeList
                    ]);
    }

    /**
     * Process add new shipping rule.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addProcess($inputData)
    {  
        // get country code
        $country = $this->supportRepository->fetchCountry($inputData['country']);

        // If country empty then show message
        if (__isEmpty($country)) {
            return __engineReaction(2, null, __tr('Invalid country code.'));
        }

        $inputData['code'] = $country['iso_code'];

        // get save country code from shipping table
        $shippingCollection = $this->shippingRepository->fetchShippingCountries($inputData['country']);
        
        // $shippingType = [];
        if (!__isEmpty($shippingCollection)) {
            if (!__isEmpty($shippingCollection)) {
                foreach ($shippingCollection as $key => $shipping) {
                    if (array_has($inputData, 'shipping_type')) {
                        if (!__isEmpty($inputData['shipping_type']) and $shipping->shipping_types__id == $inputData['shipping_type']) {
                            return __engineReaction(2, null, __tr('Shipping type already exist in same country.'));
                        }                       
                    }
                    if (!array_has($inputData, 'shipping_type') and __isEmpty($shipping->shipping_types__id)) {
                        return __engineReaction(2, null, __tr('Shipping type already exist in same country.'));
                    } 
                }
            }    
        }
        
        // Check if shipping rule added then return on reaction code
        if ($this->shippingRepository->store($inputData)) {
            return __engineReaction(1, null, __tr('Shipping rule added successfully.'));
        }

        return __engineReaction(2, null, __tr('Shipping rule not added'));
    }

    /**
     * Prepare edit support data.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($shippingID)
    {
        // Get shipping data
        $shipping = $this->shippingRepository
                         ->fetchByID(
                            $shippingID,
                            [
                                'country',
                                'type',
                                'charges',
                                'free_after_amount',
                                'amount_cap',
                                'status',
                                'notes',
                                'countries__id',
                                'shipping_types__id'
                            ]
                        );

        // If Shipping Rule Empty then return 404 reaction code
        if (__isEmpty($shipping)) {
            return __engineReaction(18, null, __tr('Shipping rule does not exist.'));
        }

        // get save country code from shipping table
        $shippingTypeCollection = $this->shippingTypeRepository->fetchAll();

        $shippingTypeList = [];
        if (!__isEmpty($shippingTypeCollection)) {
            foreach ($shippingTypeCollection as $key => $type) {
                $shippingTypeList[] = [
                    '_id' => $type->_id,
                    'title' => $type->title
                ];
            }
        }

        // get countries name
        $country = $this->supportRepository->fetchCountry($shipping->countries__id);

        // Create array for all shipping data
        $shippingData = [
            '_id' => $shippingID,
            'country' => !__isEmpty($country) ? $country->name : '',
            'type' => $shipping->type,
            'charges' => $shipping->charges,
            'free_after_amount' => $shipping->free_after_amount,
            'amount_cap' => $shipping->amount_cap,
            'notes' => $shipping->notes,
            'active' => ($shipping->status == 1) ? true : false,
            'shipping_type' => isset($shipping->shipping_types__id) ? $shipping->shipping_types__id : null
        ];

        return __engineReaction(1, [
                'shippingData' => $shippingData,
                'currencySymbol' => getCurrencySymbol(), // Store currency symbol
                'currency' => getCurrency(),
                'shippingType' => config('__tech.shipping.type'),
                'shippingTypeList' => $shippingTypeList
            ]);
    }

    /**
     * Process update shipping rule.
     *
     * @param int   $shippingID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($shippingID, $inputData)
    {
        // Get shipping data from database
        $shipping = $this->shippingRepository->fetchByID($shippingID);

        // Check if shipping empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        $status = 1;

        // Check if status is not active
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        $updateData = [
            'type' => $inputData['type'],
            'notes' => $inputData['notes'],
            'status' => $status,
            'shipping_types__id' => isset($inputData['shipping_type']) ? $inputData['shipping_type'] : null,
        ];

        $type = $inputData['type'];

        // Check if shipping type is 1 (Flat)
        if ($type == 1) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = (isset($inputData['free_after_amount']))
                                                ? $inputData['free_after_amount']
                                                : null;
        } elseif ($type == 2) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = $inputData['amount_cap'];
        } elseif ($type == 3 or $type == 4) {
            $updateData['charges'] = null;
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = null;
        }

        // get save country code from shipping table
        $shippingCollection = $this->shippingRepository->fetchShippingCountries($shipping->countries__id);

        if (!__isEmpty($shippingCollection)) {
            if (!__isEmpty($shippingCollection)) {
                foreach ($shippingCollection as $key => $existingShipping) {
                    if ($existingShipping->_id != $shippingID) {
                        if (array_has($inputData, 'shipping_type')) {
                            if (!__isEmpty($inputData['shipping_type']) and $existingShipping->shipping_types__id == $inputData['shipping_type']) {
                                return __engineReaction(2, null, __tr('Shipping type already exist in same country.'));
                            }                       
                        }
                        if (!array_has($inputData, 'shipping_type') and __isEmpty($existingShipping->shipping_types__id)) {
                            return __engineReaction(2, null, __tr('Shipping type already exist in same country.'));
                        }
                    }
                }
            }    
        }

        // Check if shipping updated or not
        if ($this->shippingRepository->update($shipping, $updateData)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Process of delete shipping rule.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($shippingID)
    {
        // Get shipping by shipping ID
        $shipping = $this->shippingRepository->fetchByID($shippingID);

        // Check if shipping is empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        // Check if shipping deleted
        if ($this->shippingRepository->delete($shipping)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare all other country data (Aoc).
     *---------------------------------------------------------------- */
    public function getAocData()
    {
        //comment by Sachin on date 27-3-2019
        //$aocShipping = $this->shippingRepository->fetchByAoc(getAocCode());

        // Get All Other countries data from database
        $aocShipping = $this->shippingRepository->fetchDataByAoc(getAocCode());
        $shipping = [];
       
        // Create all other countries shipping array
        $shipping = [
            '_id' => isset($aocShipping->_id) ? $aocShipping->_id : null,
            'country' => getAocCode(),
            'type' => isset($aocShipping->type)
                                    ? $aocShipping->type
                                    : 1,
            'charges' => isset($aocShipping->charges)
                                    ? $aocShipping->charges
                                    : null,
            'free_after_amount' => isset($aocShipping->free_after_amount)
                                    ? $aocShipping->free_after_amount
                                    : null,
            'amount_cap' => isset($aocShipping->amount_cap)
                                    ? $aocShipping->amount_cap
                                    : null,
            'notes' => isset($aocShipping->notes)
                                    ? $aocShipping->notes
                                    : '',
            'shipping_type' => isset($aocShipping->shipping_types__id)
                                    ? $aocShipping->shipping_types__id
                                    : '',
        ];

        // get config Items for shipping
        $configItems = [
            'shippingType' => config('__tech.shipping.type'),
            'storeCurrencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency()
        ];

        return __engineReaction(1, [
                        'shipping' => $shipping,
                        'configItems' => $configItems
                    ]);
    }

    /**
     *  process of update record of All other Country shipping (Aoc).
     *
     * @param array $inputData
     *---------------------------------------------------------------- */
    public function processUpdateAoc($inputData)
    {
        $inputData['status'] = 1; // active
        $inputData['active'] = 1; // active
        $inputData['code'] = getAocCode();
        $inputData['country'] = null;
        $updateData['country'] = getAocCode();

        $updateData = [
            'type' => $inputData['type'],
            'notes' => $inputData['notes']
        ];

        // get input data shipping type
        $shippingType = $inputData['type'];

        // Check if type is 3 (Free) or 4 (Not Shippable)
        if ($shippingType == 3 or $shippingType == 4) {
            $updateData['charges'] = null;
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = null;
        }

        // Check if type is 1 (Flat)
        if ($shippingType == 1) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = $inputData['free_after_amount'];
            $updateData['amount_cap'] = null;
        }

        // Check if type is 2 (Percentage)
        if ($shippingType == 2) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = $inputData['amount_cap'];
        }

        //comment by Sachin on date 27-3-2019
        //$aocCollection = $this->shippingRepository->fetchByAoc(getAocCode());

        // Get aoc Data from database 
        $aocCollection = $this->shippingRepository->fetchAocData(getAocCode());

        // Check if aoc is empty
        // if aoc not exist in database then store new aoc data
        if (__isEmpty($aocCollection)) {

            // Check id aoc saved
            if ($this->shippingRepository->store($inputData)) {
                return __engineReaction(1);
            }

            return __engineReaction(14);
        }

        // if aoc already exist then update aoc data and
        // Check if aoc updated
        if ($this->shippingRepository->update($aocCollection, $updateData)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Get shipping data base on country code.
     *
     * @param string $country
     *
     * @return collection object
     *---------------------------------------------------------------- */
    public function getShipping($country, $shippingMethod = null)
    {
        $shippings = $this->shippingRepository
                         ->checkIsValidByConutry($country, $shippingMethod);
                         
        //language translation convert data
        if (isset($shippings['notes'])) {
            $shippings['notes']  = __transliterate('shipping', $shippings['_id'], 'notes', $shippings['notes']);
            
        }

        //language translation convert data
        if (isset($shippings['shipping_method_title'])) {
            $shippingMethodTitle = $shippings['shipping_method_title'];
            $shippings['shipping_method_title']  = __transliterate('shippings_method', $shippings['shipping_types__id'], 'title', $shippingMethodTitle);

            $shippings['base_shipping_method_title']  = $shippingMethodTitle;
        }
 
        // If shipping not available for given country then get from all country shipping rule
        if (__isEmpty($shippings) and (!__isEmpty($country))) {

            $isCountryShippingExist = $this->shippingRepository->isShippingExist($country);

            if (!$isCountryShippingExist) {
                $shippings = $this->shippingRepository
                             ->fetchDataByAoc(getAocCode());
                
                //language translation convert data
                if (isset($shippings['notes'])) {
                    $shippings['notes']  = __transliterate('aoc_shipping', $shippings['_id'], 'notes', $shippings['notes']);
                }

                //language translation convert data
                if (isset($shippings['shipping_method_title'])) {
                    $shippingMethodTitle = $shippings['shipping_method_title'];
                    $shippings['shipping_method_title']  = __transliterate('shippings_method', $shippings['_id'], 'title', $shippings['shipping_method_title']);
                    $shippings['base_shipping_method_title']  = $shippingMethodTitle;
                }
            }
        }
     
        $shippingData = [];
        if (!__isEmpty($shippings)) {
            $shippingData = collect([$shippings]);
        }
       
        return $shippingData;
    }

    /**
     * This function perform add ion of shipping with cart total price.
     *
     * @param string $country
     * @param float  $cartTotalPrice
     * @param float  $discountAddedPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addShipping($country, $cartTotalPrice, $discount, $shippingMethod = null)
    {   
        // fetch shipping data base on country code
        $shippings = $this->getShipping($country, $shippingMethod);
       
        $shippingAmountArr = $shippingIds = [];
        
        $isShippable  = true;

        $shippingCount = $this->shippingRepository->fetchShippingCount($country);

        if (__isEmpty($shippings)) {

            $subtotal = $cartTotalPrice - $discount;

            return [
                'info' => '',
                'totalPrice' => 0,
                'totalShippingAmount'    => handleCurrencyAmount(0),
                'formettedDiscountPrice' => priceFormat($subtotal, false, true),
                'shipping_method'        => $shippingMethod,
                'shippingIds'            => $shippingIds,
                'isShippable'            => $isShippable,
                'shippingCount'          => $shippingCount
            ];
        }

        $shippingAmount = handleCurrencyAmount(0);
        
        foreach($shippings as $key => $shipping) 
        {
            $shippingType   = $shipping->type;
            $shippingAmount = 0;
            // shipping type 1 means flat amount
            if ($shipping->type == 1) {
                if (handleCurrencyAmount($shipping->free_after_amount) > $cartTotalPrice or
                    handleCurrencyAmount($shipping->free_after_amount) == 0) {
                    $shippingAmount = handleCurrencyAmount($shipping->charges);
                }
            }
    
            // shipping type 2 means percentage
            if ($shipping->type == 2) {
                $shippingAmount = handleCurrencyAmount(($shipping->charges / 100) * $cartTotalPrice);
    
                if (!empty($shipping->amount_cap)) {
                    if (handleCurrencyAmount($shipping->amount_cap) < $shippingAmount) {
                        $shippingAmount = handleCurrencyAmount($shipping->amount_cap);
                    } else {
                        $shippingAmount = $shippingAmount;
                    }
                }
            }

            $shippingAmountArr[] = $shippingAmount;

            // Check if shipping calculated amount is not empty
            $shipping['shippingAmt'] = $shippingAmount;
            $shipping['formattedShippingAmt'] = priceFormat($shippingAmount, false, true);
            
            // how many shipping are applied on order amount
            $shipping['shippingAmount'] = $shippingAmount;

            $shippingIds[] = $shipping->_id;

            if ($shippingType === 4) {
                $isShippable = false;
            }
        }

        $totalShippingAmount = array_sum($shippingAmountArr);

        // calculated sub total when subtract discount from cart total
        if (!__isEmpty($discount)) {
            $discountAddedPrice = $cartTotalPrice - $discount;
        } else {
            $discountAddedPrice = 0;
        }

        // if discount amount is preset then the price calculate with discount amount but
        // condition check with base cart order price $cartTotalPrice
        $price = __isEmpty($discountAddedPrice) ? $cartTotalPrice : $discountAddedPrice;
        
        // return after addition of shipping price in cart
        $afterAddShipping = $price + $totalShippingAmount; // addition of shipping price

        return [
            'info'                   => $shippings,
            'totalPrice'             => $afterAddShipping,
            'discountAddedPrice'     => $discountAddedPrice,
            'shippingAmt'            => $totalShippingAmount,
            'totalShippingAmount'    => handleCurrencyAmount($totalShippingAmount),
            'formettedDiscountPrice' => priceFormat($discountAddedPrice, false, true),
            'shipping_method'        => $shippingMethod,
            'shippingIds'            => $shippingIds,
            'isShippable'            => $isShippable,
            'shippingCount'          => $shippingCount
        ];
    }

    /**
     * This function perform addition of shipping with cart total price.
     *
     * @param string $country
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getShippingInformation($country, $shippingMethod = null)
    {
        // fetch shipping data base on country code
        $shippings = $this->shippingRepository->fetchByAoc($country, $shippingMethod);

        if (__isEmpty($shippings)) {
            return [
                'info' => [],
            ];
        }

        $data = [];

        foreach($shippings as $shipping) 
        {
            $data[] = [
                '_id'     => $shipping->_id,
                'charges' => $shipping->charges,
                'type'    => $shipping->type,
                'notes'   => $shipping->notes,
                'shipping_types__id'   => $shipping->shipping_types__id,
                'shipping_method_title'         => $shipping->shippingMethodTitle,
                'base_shipping_method_title'    => $shipping->shippingMethodTitle
            ];
        }

        return [
            'info' => $data,
        ];
    }
}
