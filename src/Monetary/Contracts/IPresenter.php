<?php namespace HcDisat\Monetary\Contracts;

interface IPresenter
{
    /**
     * returns a string like $689.69 or $874.33
     * @return string
     */
    public function price(): string;

    /**
     * returns a string like 689.69 or 874.33
     * @return string
     */
    public function amountForHumans(): string ;
}