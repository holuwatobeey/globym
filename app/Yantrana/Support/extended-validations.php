<?php

    /**
     * Custom validation rules for check unique email address -
     * for user.
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('unique_email', function ($attribute, $value, $parameters) {
        $email = strtolower($value);
        $userCount = App\Yantrana\Components\User\Models\User::where('email', $email)
                        ->get()
                        ->count();

        // Check for user exist with given email
        if ($userCount > 0) {
            return false;
        }

        $newEmailRequestCount = App\Yantrana\Components\User\Models\TempEmail::where('new_email', $email)
                                    ->count();
        // Check for new email request exist with given email
        if ($newEmailRequestCount > 0) {
            return false;
        }

        return true;
    });

    /**
     * Custom validation rules for check verify currency format -
     *
     * {__currencySymbol__}{__amount__} {__currencyCode__} this is format contains
     *
     * take reference from - config('__settings.configuration_datatypes.currency_format.default')
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('verify_format', function ($attribute, $value, $parameters) {
        $condition = false;

        if (str_contains($value, '{__amount__}')) {
            $condition = true;
        }
        
        // Check if currency symbol exist only one time in string
        if (substr_count($value, '{__currencySymbol__}') > 1
            or substr_count($value, '{__amount__}') > 1
            or substr_count($value, '{__currencyCode__}') > 1) {
            $condition = false;
        }

        return $condition;
    });

    /**
     * Custom validation rules for check amount length
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('amount_validation', function ($attribute, $value, $parameters) {
        $condition = true;

        if (!__isEmpty($value)) {
            $afterDecimalAmount = null;

            $amount = number_format($value, null, '.', '');
            
            $isDotContain = strpos($amount, '.');

            // Check if amount contains dot if yes then get before . amount value
            // and after dot amount.
            if ($isDotContain !== false) {
                $nonDecimalAmount = strchr($amount, '.', true);
            } else {
                $nonDecimalAmount = $amount;
            }
            
            // Get length of original amount
            $priceLength = strlen($nonDecimalAmount);
            
            // Check if amount length is greater than 10
            if ($priceLength > 9) {
                $condition = false;
            }
            
            if ($value > 999999999) {
                $condition = false;
            }
        }
       
        return $condition;
    });

    /**
     * Custom validation rules for check amount length
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('decimal_validation', function ($attribute, $value, $parameters) {
        $condition = true;

        if (!__isEmpty($value)) {
            $afterDecimalAmount = null;

            $isDotContain = strpos($value, '.');

            // Check if amount contains dot if yes then get before . amount value
            // and after dot amount.
            if ($isDotContain !== false) {
                $afterDecimalAmount = strchr($value, '.');
            
                // Get length of decimal amount
                $afterDecimalAmountLength = strlen($afterDecimalAmount)-1;
                
                $decimalValidation = $afterDecimalAmountLength;

                // Check if after decimal amount is greater than 4
                if ($afterDecimalAmountLength > 4) {
                    
                    // Get configuration setting decimal round number
                    $configAfterDecimalAmountLength = getStoreSettings('currency_decimal_round');

                    $decimalValidation = ($configAfterDecimalAmountLength > 4)
                                            ? 4 : $configAfterDecimalAmountLength;
                }

                // Check if decimal amount length is greater than
                if ($afterDecimalAmountLength > $decimalValidation) {
                    $condition = false;
                }
            }
        }
       
        return $condition;
    });

    /**
     * verify number format -
     * for user.
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('different_array', function ($attribute, $value, $parameters) {

        $inputData = request()->all();

        foreach ($inputData['specifications'] as $specficationLabels) {
            $spacificationValues = [];
            foreach ($specficationLabels as $specifications) {
                if (isset($specifications['value'])) {
                    foreach ($specifications['value'] as $value) {
                        if (!in_array(strtolower($value), $spacificationValues)) {
                            $spacificationValues[] = strtolower($value);
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    });
