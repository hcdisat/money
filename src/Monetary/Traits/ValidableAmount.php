<?php namespace HcDisat\Monetary\Traits;


trait ValidableAmount
{
    #region Operations Validators

    /**
     * @param $amountArg
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValidAmount($amountArg)
    {
        if ( filter_var($amountArg, FILTER_VALIDATE_INT) === false ){
            throw new \InvalidArgumentException('amount must be numeric');
        }
    }

    /**
     * @param $divisor
     */
    public function canDivide($divisor)
    {
        $this->isValidAmount($divisor);

        if( $divisor === 0 || $divisor === 0.0 ){
            throw new \InvalidArgumentException('Division by Zero (0)');
        }
    }

    #endregion
}