<?php namespace HcDisat\Monetary\Exceptions;

use Exception;

abstract class ExceptionBase extends \Exception
{
    protected $message;

    /**
     * ExceptionBase constructor.
     * @param string $message
     * @param null $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return
            __CLASS__. "{: $this->message}\n";
    }
}