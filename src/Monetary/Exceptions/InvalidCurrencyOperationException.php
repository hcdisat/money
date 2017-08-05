<?php namespace HcDisat\Monetary\Exceptions;


class InvalidCurrencyOperationException extends ExceptionBase
{

    protected $message = '"%s currency cannot be added with %s currency';

    /**
     * InvalidCurrencyOperationException constructor.
     * @param string $currentCurrency
     * @param string $otherCurrency
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $currentCurrency, string $otherCurrency, $code = 0, \Exception $previous = null)
    {
        $this->message = sprintf($this->message, $currentCurrency, $otherCurrency);
        parent::__construct($this->message, $code, $previous);
    }

}