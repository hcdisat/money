<?php namespace HcDisat\Monetary;

use HcDisat\Monetary\Contracts\ICalculator;

class Number
{

    /**
     * @var ICalculator
     */
    private $calculator;
    /**
     * @var string
     */
    private $number;

    /**
     * @var bool|int
     */
    private $decimalSeparatorPosition;

    /**
     * @param $number
     * @param ICalculator $calculator
     */
    public function __construct($number = null, ICalculator $calculator = null)
    {
        $this->number = $this->parse((string) $number);
        $this->decimalSeparatorPosition = strpos($number, '.');

        if( is_null($calculator) ) {
            $this->calculator = app(Calculator::class, [Calculator::PRECISE]);
        }
    }

    /**
     * @param ICalculator $calculator
     */
    public function setCalculator(ICalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param string $number
     * @return string
     */
    public function parse(string $number) : string
    {

        if( (filter_var($number, FILTER_VALIDATE_INT))
            || (filter_var($number, FILTER_VALIDATE_FLOAT))){
            return $number;
        }

        $sign = "(?P<sign>[-\+])?";
        $symbol = "(?P<symbol>\W)?";
        $digits = "(?P<digits>\d*)";
        $separator = '(?P<separator>[.,])?';
        $decimals = "(?P<decimal>\d*)?";

        $pattern = sprintf('/%s$/', $sign.$symbol.$digits.$separator.$decimals);

        if( !preg_match($pattern, trim($number), $matches) ){
            throw new \InvalidArgumentException(
                'Number expects a numeric string for calculations'
            );
        }

        $fractionDigits = preg_split('/[.,]/', $number);


        $newNumber = $matches['digits'];
        $newNumber .= $matches['separator'] ?? '';
        $newNumber .= $matches['decimal'] ?? '';


        if( empty($newNumber) ) return '0';

        $newNumber = $matches['sign'] === '-' ? '-'.$newNumber : $newNumber;

        if( ($fractionDigits !== false) && (strlen($fractionDigits[1]) === 1) ) {
            $newNumber .= '0';
        }

        return $newNumber;
    }


    /**
     * @return string
     */
    public function getIntegerPart() : string
    {
        if ($this->decimalSeparatorPosition === false) {
            return $this->number;
        }
        return substr($this->number, 0, $this->decimalSeparatorPosition);
    }

    /**
     * @return string
     */
    public function getFractionalPart() : string
    {
        if ($this->decimalSeparatorPosition === false) {
            return '';
        }
        return rtrim(substr($this->number, $this->decimalSeparatorPosition + 1), '0');
    }

    /**
     *
     * @return Number
     */
    public function toUnits() : Number
    {
        return new self(($this->number  / 100 ));
    }

    /**
     *
     * @return Number
     */
    public function toCents() : Number
    {
        return $this->isDecimal()
            ? new self($this->getIntegerPart() + '0.'.$this->getFractionalPart())
            : new self($this->number);
    }

    /**
     * @return bool
     */
    public function isDecimal() : bool
    {
        return $this->decimalSeparatorPosition !== false;
    }


    /**
     * @return bool
     */
    public function isCloserToNext() : bool
    {
        if ( $this->isDecimal() === false ) {
            return false;
        }

        return $this->number[$this->decimalSeparatorPosition + 1] >= 5;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->number;
    }

    /**
     * @param float $floatingPoint
     *
     * @return Number
     */
    public static function fromFloat($floatingPoint) : Number
    {
        if ( is_float($floatingPoint) === false ) {
            throw new \InvalidArgumentException('Floating point expected');
        }
        return new self(sprintf('%.8g', $floatingPoint));
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->__toString();
    }

    /**
     * @param $value
     * @return Number
     */
    public function add($value) : Number
    {
        $result = $this->calculator
            ->add($this->number, $value);
        return new self($result);
    }

    /**
     * @param $value
     * @return Number
     */
    public function subtract($value) : Number
    {
        $result = $this->calculator
            ->subtract($this->number, $value);
        return new self($result);
    }

    /**
     * @param $factor
     * @return self
     */
    public function multiply($factor) : self
    {
        if( !is_numeric($factor) ) {
            throw new \InvalidArgumentException('amount must be numeric');
        }
        $result = $this->calculator
            ->multiply($this->number, $factor);
        return new self($result);
    }

    /**
     * @param $divisor
     * @return Number
     * @throws \DivisionByZeroError
     */
    public function divide($divisor) : Number
    {
        if( !is_numeric($divisor) ) {
            throw new \InvalidArgumentException('amount must be numeric');
        }

        if( $divisor === 0 ) {
            throw new \DivisionByZeroError();
        }

        $result = $this->calculator
            ->divide($this->number, $divisor);

        return new self($result);
    }

}
