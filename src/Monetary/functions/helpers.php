<?php
/**
 * Created by PhpStorm.
 * User: hcdisat
 * Date: 8/5/17
 * Time: 10:20
 */

use \HcDisat\Monetary\Number,
    \HcDisat\Monetary\Money;

if (!function_exists('toMoney')) {

    /**
     * get speaker cost
     * @param $number
     * @param string $currency
     * @return \HcDisat\Monetary\Money
     */
    function toMoney($number, string $currency = null)
    {
        $currency = $currency ?? config('currency.default_currency');

        /** @var Number $cents */
        $cents = app(Number::class, [$number]);
        return Money::$currency($cents->toCents()->__toString() ?? 0);
    }
}

if (!function_exists('money')) {

    /**
     * get speaker cost
     * @param $number
     * @param string $currency
     * @return \HcDisat\Monetary\Money
     */
    function money($number, string $currency = null)
    {
        $currency = $currency ?? config('currency.default_currency');
        return Money::$currency($number ?? 0);
    }
}