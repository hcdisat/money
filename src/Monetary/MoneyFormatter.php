<?php namespace HcDisat\Monetary;


use HcDisat\Monetary\Contracts\IMoneyFormatter;

class MoneyFormatter implements IMoneyFormatter
{

    /**
     * @var \NumberFormatter
     */
    protected $formatter;

    /**
     * MoneyFormatter constructor.
     * @param \NumberFormatter $formatter
     */
    public function __construct(\NumberFormatter $formatter = null)
    {
        if( is_null($formatter) )
        {
            $format = config('currency.formatter-format');
            $locale = config('currency.default-locale');
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $formatter->setPattern($format);
        }

        $this->formatter = $formatter;
    }


    /**
     * Formats the money object's amount property
     * @param Money $money
     * @return mixed
     */
    public function format(Money $money)
    {
        // get the string value
        $valueStr = (string) $money->amount();

        //check if the value is negative
        $negative = substr($valueStr, 0, 1) == '-';

        // if so, strip the - sign, otherwise remains the same
        $valueStr = $negative ? substr($valueStr, 1) : $valueStr;

        //get the fractional digits
        $fraction = $this->formatter->getAttribute(\NumberFormatter::FRACTION_DIGITS);

        // get the string size
        $valueStrLength = strlen($valueStr);

        // if string it's greater than $fraction, means we dealing with a long
        // decimal
        if( $valueStrLength > $fraction )
        {
            $subunits = substr($valueStr, 0, $valueStrLength - $fraction).'.';
            $subunits .= substr($valueStr,  $valueStrLength - $fraction);
        }
        else
        {
            $subunits = '0.'.str_pad('', $fraction - $valueStrLength, '0').$valueStr;
        }

        // if negative append the - sign
        if( $negative ) $subunits = '-'.$subunits;
        
        return $subunits;
    }

    public function toCurrency(Money $money)
    {
        $subUnit = $this->format($money);
        return $this->formatter
            ->formatCurrency(
                $subUnit,
                $money->currency()->isoCode()) ?? $money->amount() / 100;
    }

    /**
     * formated number
     * @param Money $money
     * @return string
     */
    public function toDecimal(Money $money)
    {
        return $this->format($money);
    }
}