<?php namespace HcDisat\Monetary\Contracts;


interface INumberParser
{
    /**
     * Parse object to int
     * @param $number
     * @return mixed
     */
    public function parse($number);
}