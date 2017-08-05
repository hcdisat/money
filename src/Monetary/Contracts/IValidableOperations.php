<?php namespace HcDisat\Monetary\Contracts;


interface IValidableOperations
{
    /**
     * @param $operand
     * @throws \InvalidArgumentException
     */
    public function isValidAmount($operand);

    /**
     * @param $operand
     * @throws \InvalidArgumentException
     */
    public function canDivide($operand);



}