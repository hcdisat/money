<?php namespace HcDisat\Monetary\Contracts;


use HcDisat\Monetary\Money;

interface IMoneyFormatter
{
    /**
     * Formats the money object's amount property
     * @param Money $money
     * @return mixed
     */
    public function format(Money $money);
}