<?php namespace HcDisat\Monetary\Contracts;


interface ICalculator
{
    /**
     * results are returned as they are
     * @var int
     */
    const PRECISE = 0;

    /**
     * if result above the half next number is returned
     * @var int
     */
    const CEIL = 1;

    /**
     * if result below the half previous number is returned
     * @int
     */
    const FLOOR = 2;

    /**
     * Returns whether the calculator is supported in
     * the current server environment.
     *
     * @return bool
     */
    public static function supported() : bool;

    /**
     * Compare a to b.
     *
     * @param string $a
     * @param string $b
     * @return int
     */
    public function compare($a, $b) : int;

    /**
     * Add added to amount.
     *
     * @param string $amount
     * @param string $addend
     * @return int|string
     */
    public function add($amount, $addend) : string;

    /**
     * Subtract subtrahend from amount.
     *
     * @param string $amount
     * @param string $subtrahend
     * @return int|string
     */
    public function subtract($amount, $subtrahend) : string;

    /**
     * Multiply amount with multiplier.
     *
     * @param string $amount
     * @param int|float|string $multiplier
     * @return int|string
     */
    public function multiply($amount, $multiplier) : string;

    /**
     * Divide amount with divisor.
     *
     * @param string $amount
     * @param int|float|string $divisor
     * @return int|string
     */
    public function divide($amount, $divisor) : string;

    /**
     * Round number to following integer.
     *
     * @param string $number
     * @return int|string
     */
    public function ceil($number) : string;

    /**
     * Round number to preceding integer.
     *
     * @param string $number
     * @return bool|string
     */
    public function floor($number) : string;

    /**
     * Round number, use rounding mode for tie-breaker.
     *
     * @param string $number
     * @return bool|string
     */
    public function round($number) : string;
}