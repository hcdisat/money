<?php namespace HcDisat\Monetary;


class Currency
{
    protected $isoCode;

    /**
     * CurrencyInterface constructor.
     * @param $isoCode
     */
    public function __construct($isoCode)
    {
        $this->setIsoCode($isoCode);
    }

    /**
     * @param $isoCodeArg
     * @throws \InvalidArgumentException
     */
    public function setIsoCode($isoCodeArg)
    {
        $this->isValidIsoCode($isoCodeArg);
        $this->isoCode = $isoCodeArg;
    }

    public function isoCode()
    {
        return $this->isoCode;
    }

    /**
     * validates the given iso.
     * @param $isoCodeArg
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function isValidIsoCode($isoCodeArg) : bool
    {
        if( !preg_match('/^[A-Z]{3}$/', $isoCodeArg) ) {
            throw new \InvalidArgumentException('Not valid ISO CODE for a currency.');
        }
        return true;
    }


    /**
     * @param Currency $currencyArg
     * @return bool
     */
    public function equals(Currency $currencyArg) : bool
    {
        return $this->isoCode() === $currencyArg->isoCode();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->isoCode();
    }


}