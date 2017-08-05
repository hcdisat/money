<?php

use HcDisat\Monetary\Currency;
use HcDisat\Monetary\Money;
use HcDisat\Monetary\MoneyFormatter;
use Tests\TestCase;

class MoneyFormatterTest extends TestCase
{
    /**
     * @var string
     */
    protected $format;
    /**
     * @var string
     */
    protected $locale;
    /**
     * @var Money
     */
    protected $moneyObject;

    /**
     * @var NumberFormatter
     */
    protected $numberFormatter;

    protected function setUp()
    {
        parent::setUp();
        $this->format = '#,##0.00, -#,##0.00';
        $this->locale = 'en_US';

        $this->moneyObject = new Money(100, new Currency('USD'));
        $this->numberFormatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $this->numberFormatter->setPattern($this->format);
    }

    public function testFormatShouldHaveUSFormat()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->assertEquals('$1.00', $moneyFormatter->toCurrency($this->moneyObject));
    }

    public function testFormatNegativeNumbers()
    {
        $this->moneyObject = $this->moneyObject->multiply(-1);

        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->assertEquals('-$1.00', $moneyFormatter->toCurrency($this->moneyObject), $moneyFormatter->toCurrency($this->moneyObject));
    }

    public function testLessThanOneHundred()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->moneyObject = Money::USD(41);
        $this->assertEquals('$0.41', $moneyFormatter->toCurrency($this->moneyObject));

        $this->moneyObject = Money::USD(-41);
        $this->assertEquals('-$0.41', $moneyFormatter->toCurrency($this->moneyObject));
    }

    public function testLessThanTen()
    {
        $this->moneyObject = Money::USD(6);
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->assertEquals('$0.06', $moneyFormatter->toCurrency($this->moneyObject));

        $this->moneyObject = Money::USD(-6);
        $this->assertEquals('-$0.06', $moneyFormatter->toCurrency($this->moneyObject));
    }

    public function testVariousDigits()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);
        $this->numberFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 3);

        $this->assertEquals('$0.005', $moneyFormatter->toCurrency(new Money(5, new Currency('USD'))));
        $this->assertEquals('$0.035', $moneyFormatter->toCurrency(new Money(35, new Currency('USD'))));
        $this->assertEquals('$0.135', $moneyFormatter->toCurrency(new Money(135, new Currency('USD'))));
        $this->assertEquals('$6.135', $moneyFormatter->toCurrency(new Money(6135, new Currency('USD'))));
        $this->assertEquals('-$6.135', $moneyFormatter->toCurrency(new Money(-6135, new Currency('USD'))));
    }

    public function testDifferentLocaleAndDifferentCurrency()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->assertEquals('€0.05', $moneyFormatter->toCurrency(new Money(5, new Currency('EUR'))));
        $this->assertEquals('€0.50', $moneyFormatter->toCurrency(new Money(50, new Currency('EUR'))));
        $this->assertEquals('€5.00', $moneyFormatter->toCurrency(new Money(500, new Currency('EUR'))));
    }

    /**
     * Testing that another format wont break the code.
     */
    public function testStylePercent()
    {
        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::PERCENT);
        $moneyFormatter = new MoneyFormatter($numberFormatter);
        $this->assertEquals('500%', $moneyFormatter->toCurrency(new Money(5, new Currency('EUR'))));
    }

    public function testTODecimal()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);
        $this->numberFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 3);

        $this->assertEquals('0.005', $moneyFormatter->toDecimal(new Money(5, new Currency('USD'))));
        $this->assertEquals('0.035', $moneyFormatter->toDecimal(new Money(35, new Currency('USD'))));
        $this->assertEquals('0.135', $moneyFormatter->toDecimal(new Money(135, new Currency('USD'))));
        $this->assertEquals('6.135', $moneyFormatter->toDecimal(new Money(6135, new Currency('USD'))));
        $this->assertEquals('-6.135', $moneyFormatter->toDecimal(new Money(-6135, new Currency('USD'))));
    }

    public function testTODecimalTwoDigits()
    {
        $moneyFormatter = new MoneyFormatter($this->numberFormatter);

        $this->assertEquals('0.05', $moneyFormatter->toDecimal(new Money(5, new Currency('EUR'))));
        $this->assertEquals('0.50', $moneyFormatter->toDecimal(new Money(50, new Currency('EUR'))));
        $this->assertEquals('5.00', $moneyFormatter->toDecimal(new Money(500, new Currency('EUR'))));
    }

}