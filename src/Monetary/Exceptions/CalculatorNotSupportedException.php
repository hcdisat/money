<?php namespace HcDisat\Monetary\Exceptions;


class CalculatorNotSupportedException extends ExceptionBase
{
    protected $message = 'This calculator class is not supported by the system.';

    /**
     * CalculatorNotSupportedException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}