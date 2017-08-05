<?php namespace HcDisat\Monetary;

use HcDisat\Monetary\Contracts\ICalculator;
use HcDisat\Monetary\Contracts\IPresentable;
use HcDisat\Monetary\Contracts\IPresenter;
use HcDisat\Monetary\Contracts\IValidableOperations;
use HcDisat\Monetary\Exceptions\CalculatorNotSupportedException;
use HcDisat\Monetary\Exceptions\InvalidCurrencyOperationException;
use HcDisat\Monetary\Traits\ValidableAmount;

class Money implements \JsonSerializable, IValidableOperations, IPresentable
{
    use ValidableAmount;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var ICalculator
     */
    protected static $calculator;

    /**
     * @var IPresentable
     */
    protected $presenter;

    /**
     * MoneyInterface constructor.
     * @param int $amount
     * @param Currency $currency
     * @throws \InvalidArgumentException
     */
    public function __construct($amount, Currency $currency)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);

        self::initCalculator();
        $this->initPresenter();
    }



    private function setAmount($amountArg)
    {
        $this->isValidAmount($amountArg);

        $this->amount = (int) $amountArg;
    }



    private function setCurrency(Currency $currencyArg)
    {
        $this->currency = $currencyArg;
    }


    #region Protected Section

    protected function canOperate(Money $moneyArg)
    {
        if( !$this->currency()->equals($moneyArg->currency()) ){
            throw new InvalidCurrencyOperationException(
                $this->currency()->isoCode(),
                $moneyArg->currency()->isoCode()
            );
        }
    }

    protected function compare(Money $moneyArg) : int
    {
        $this->canOperate($moneyArg);

        return $this->calculator()
            ->compare($this->amount(), $moneyArg->amount());
    }

    /**
     * @param $amount
     * @return int|string
     */
    protected function round($amount) : int
    {
        return $this->calculator()->ceil($amount);
    }

    #endregion

    #region Public section Getters

    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function currency()
    {
        return $this->currency;
    }

    #endregion

    #region Factory Methods

    /**
     * create new Object by a money (Keeps Immutability)
     * @param Money $moneyArg
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function fromMoney(Money $moneyArg) : Money
    {
        return new self(
            $moneyArg->amount(),
            $moneyArg->currency()
        );
    }

    /**
     * create new Object by a money (Keeps Immutability)
     * @param Currency $currencyArg
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function ofCurrency(Currency $currencyArg) : Money
    {
        return new self(0, $currencyArg);
    }

    /**
     * Creates a money type by using the currency as
     * method name and the argument a numeric value
     * eg: Money::USD(100)
     * @param $name
     * @param $arguments
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function __callStatic($name, $arguments) : Money
    {
        return new self($arguments[0], new Currency(strtoupper($name)));
    }


    public function fromPrimitive($primitiveInt)
    {
        $this->isValidAmount($primitiveInt);
        return new self($primitiveInt, $this->currency());
    }

    #endregion

    #region Operations

    /**
     * Increment Operation
     * @param $amountArg
     * @return Money
     */
    public function increaseAmountBy($amountArg) : Money
    {
        $this->isValidAmount($amountArg);

        return new self(
            $this->amount() + $amountArg,
            $this->currency()
        );
    }

    /**
     * @param Money $moneyArg
     * @return bool
     */
    public function equals(Money $moneyArg) : bool
    {
        return
            $this->currency()->equals($moneyArg->currency()) &&
            $this->amount() === $moneyArg->amount();
    }

    /**
     * @param Money $moneyArg
     * @return bool
     */
    public function isSameCurrency(Money $moneyArg) : bool
    {
        return
            $this->currency()->equals($moneyArg->currency());
    }

    /**
     * @param Money $moneyArg
     * @return bool
     */
    public function greaterThan(Money $moneyArg) : bool
    {
        return 1 === $this->compare($moneyArg);
    }

    public function lessThan(Money $moneyArg) : bool
    {
        return -1 === $this->compare($moneyArg);
    }

    /**
     * @param Money $moneyArg
     * @return bool
     */
    public function greaterThanOrEquals(Money $moneyArg) : bool
    {
        return $this->compare($moneyArg) >= 0;
    }

    /**
     * @param Money $moneyArg
     * @return bool
     */
    public function lessThanOrEqual(Money $moneyArg) : bool
    {
        return $this->compare($moneyArg) <= 0;
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return -1 === $this->calculator()->compare($this->amount(), 0);
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return 1 === $this->calculator()->compare($this->amount(), 0);
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        return 0 === $this->calculator()->compare($this->amount(), 0);
    }

    /**
     * checks for <= 0 like operation
     * @return bool
     */
    public function isEqualsOrLessThanZero() : bool
    {
        return $this->isZero() || $this->isNegative();
    }

    /**
     * checks for >= 0 like operation
     * @return bool
     */
    public function isEqualsOrMoreThanZero() : bool
    {
        return $this->isZero() || $this->isPositive();
    }

    /**
     * Returns a new Money object that represents
     * the sum of this and an other Money object.
     * @param Money $moneyArg
     * @return Money
     * @throws InvalidCurrencyOperationException
     */
    public function add(Money $moneyArg) : Money
    {
        $this->canOperate($moneyArg);

        $result = $this->calculator()
            ->add($this->amount(), $moneyArg->amount());

        return new self(
            $result,
            $this->currency()
        );
    }

    /**
     * Returns a new Money object that represents
     * the difference of this and an other Money object.
     * @param Money $moneyArg
     * @return Money
     * @throws InvalidCurrencyOperationException
     */
    public function subtract(Money $moneyArg) : Money
    {
        $this->canOperate($moneyArg);

        $result = $this->calculator()
            ->subtract($this->amount(), $moneyArg->amount());

        return new self(
            $result,
            $this->currency()
        );
    }

    /**
     * Returns a new Money object that represents
     * the multiplied value by the given factor
     * @param $factor
     * @return Money
     */
    public function multiply($factor) : Money
    {
        if( !is_numeric($factor) ) {
            throw new \InvalidArgumentException('amount must be numeric');
        }

        $result = $this->calculator()
            ->multiply($this->amount(), $factor);

        return $this->fromPrimitive($this->round($result));
    }

    /**
     * Returns a new Money object that represents
     * the divided value by the given factor
     * @param $divisor
     * @return Money
     */
    public function divide($divisor) : Money
    {
        $this->canDivide($divisor);

        $result = $this->calculator()
            ->divide($this->amount(), $divisor);

        return $this->fromPrimitive($this->round($result));
    }

    #endregion

    #region Calculator
    public static function setCalculator(ICalculator $calculator)
    {
        if( !$calculator->supported() ) {
            throw new CalculatorNotSupportedException();
        }
        self::$calculator = $calculator;
    }

    private static function initCalculator() : ICalculator
    {
        return self::$calculator =
            self::$calculator ?? new Calculator();
    }

    protected function calculator() : ICalculator
    {
        return self::initCalculator();
    }
    #endregion


    #region Presenter
    public function setPresenter(IPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    private function initPresenter() : IPresenter
    {
        return $this->presenter =
            $this->presenter??
            new Presenter($this);
    }

    protected function presenter() : IPresenter
    {
        return $this->initPresenter();
    }
    #endregion



    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() : array
    {
        return [
            'amount' => $this->present()->amountForHumans(),
            'currency' => $this->currency()->isoCode()
        ];
    }


    /**
     * @return IPresenter
     */
    public function present(): IPresenter
    {
        return $this->presenter;
    }

    /**
     * To string Implementation
     * @return string
     */
    public function __toString()
    {
        return (string) $this->amount();
    }
}