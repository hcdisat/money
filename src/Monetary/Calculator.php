<?php namespace HcDisat\Monetary;

use HcDisat\Monetary\Contracts\ICalculator;
use HcDisat\Monetary\Contracts\IValidableOperations;
use HcDisat\Monetary\Traits\ValidableAmount;

class Calculator implements ICalculator, IValidableOperations
{
    use ValidableAmount;

    /**
     * @var int
     */
    private $mode;

    /**
     * Calculator constructor.
     * @param int $mode
     */
    public function __construct(int $mode = Calculator::CEIL)
    {
        $this->mode = $mode;
    }

    /**
     * @{@inheritdoc}
     */
    public static function supported() : bool
    {
        return true;
    }

    /**
     * @{@inheritdoc}
     */
    public function compare($a, $b) : int
    {
        return (int)$a <=> $b;
    }

    /**
     * @{@inheritdoc}
     */
    public function add($amount, $added) : string
    {
        $result = $this->getResult($amount) + $added;
        return (string)$result;
    }

    /**
     * @{@inheritdoc}
     */
    public function subtract($amount, $subtrahend) : string
    {
        $result = $this->getResult($amount) - $subtrahend;
        return (string)$result;
    }

    /**
     * @{@inheritdoc}
     */
    public function multiply($amount, $multiplier) : string
    {
        $result = $this->getResult($amount) * $multiplier;
        $this->floor($result);
        return (string)$result;
    }

    /**
     * @{@inheritdoc}
     */
    public function divide($amount, $divisor) : string
    {
        $this->canDivide($divisor);
        $result = $this->getResult($amount) / $divisor;
        return (string)$result;
    }

    /**
     * @{@inheritdoc}
     */
    public function ceil($number) : string
    {
        return $this->toNumericString(ceil($number));
    }

    /**
     * @{@inheritdoc}
     */
    public function floor($number) : string
    {
        return $this->toNumericString(floor($number));
    }

    /**
     * Round number, use rounding mode for tie-breaker.
     *
     * @{@inheritdoc}
     */
    public function round($number) : string
    {
        switch ($this->mode) {
            case Calculator::FLOOR:
                return $this->toNumericString(round($number, 0, PHP_ROUND_HALF_DOWN));
                break;
            default:
                return $this->toNumericString(round($number, 0, PHP_ROUND_HALF_UP));
                break;
        }
    }

    #region Validations

    /**
     * @param int $amount
     */
    private function isInBounds(int $amount)
    {
        // check if the amount is < than the biggest int supported by the system
        if ($amount > PHP_INT_MAX) {
            throw new \OverflowException();
            // check the opposite, the smaller int in the system
        } elseif ($amount < ~PHP_INT_MAX) {
            throw new \UnderflowException();
        }
    }

    /**
     * @param int $amount
     * @return string
     */
    private function toNumericString(int $amount) : string
    {
        $this->isInBounds($amount);
        return (string)$amount;
    }

    #endregion

    /**
     * @param $value
     * @return mixed
     */
    protected function getResult($value)
    {
        $getResult = function (string $value, string $action) {
            $result = call_user_func_array([$this, $action], [$value]);
            $this->isInBounds($result);
            return $result;
        };

        switch ($this->mode) {
            case Calculator::CEIL:
                return $getResult($value, 'ceil');
                break;
            case Calculator::FLOOR:
                return $getResult($value, 'floor');
                break;
            default:
                return $value;
                break;
        }
    }
}