<?php

use HcDisat\Monetary\Currency;
use HcDisat\Monetary\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{

    public function testGetters()
    {
        $money = new Money($amount = 100, $curr = new Currency('EUR'));

        $this->assertEquals($amount, $money->amount());
        $this->assertEquals($curr, $money->currency());
    }


    public function testCopiedMoneyShouldRepresentSameValue()
    {
        $hundred = new Money(100, new Currency('USD'));

        $copiedHundred = Money::fromMoney($hundred);

        $this->assertTrue($hundred->equals($copiedHundred));
    }


    public function testOriginalMoneyShouldNotModifiedOnArithmeticOperations()
    {
        $ten = new Money(10, new Currency('USD'));
        $five = new Money(5, new Currency('USD'));

        //Addition
        $ten->add($five);
        $this->assertTrue($ten->amount() === 10);
    }

    public function testMoneyShouldSupportOperations()
    {
        $hundred = new Money(100, new Currency('USD'));
        $newMoney = $hundred->add(new Money(200, new Currency('USD')));

        $this->assertEquals(300, $newMoney->amount());

        //subtraction
        $ubt = $hundred->subtract(new Money(200, new Currency('USD')));
        $this->assertEquals(-100, $ubt->amount());

        //multiplication
        $mult = $hundred->multiply(4);
        $this->assertEquals(400, $mult->amount());

        //division
        $div = $hundred->divide(2);
        $this->assertEquals(50, $div->amount());

        $this->assertEquals(100, $hundred->amount());
    }

    /**
     * @expectedException HcDisat\Monetary\Exceptions\InvalidCurrencyOperationException
     */
    public function testCannotOperateOnDifferentCurrency()
    {
        $usd = Money::USD(300);
        $eur = Money::EUR(200);

        // here throws an exception
        $usd->add($eur);
        $usd->subtract($eur);
    }


    public function testFactoryMethods()
    {
        $this->assertEquals(
            Money::DOM(300),
            Money::DOM(250)->add(Money::DOM(50))
        );

        $usd = Money::USD(150);
        $newUsd = Money::fromMoney($usd);

        $this->assertTrue($usd->equals($newUsd));
        $this->assertFalse($usd === $newUsd);

        $usd = Money::ofCurrency(new Currency('USD'));
        $this->assertSame(0, $usd->amount());

        $newUsd = $usd->fromPrimitive(263);

        $this->assertEquals(263, $newUsd->amount());
        $this->assertEquals($usd->currency()->isoCode(),$newUsd->currency()->isoCode());

    }

    public function testincreseAmountBy()
    {
        $usd = new Money(800, new Currency('USD'));
        $this->assertEquals(800 + 2, $usd->increaseAmountBy(2)->amount());
    }

    /**
     * @expectedException HcDisat\Monetary\Exceptions\InvalidCurrencyOperationException
     */
    public function testBooleanOperations()
    {
        $usd = new Money(800, new Currency('USD'));
        $eur = new Money(800, new Currency('EUR'));

        // test isSameCurrency
        $this->assertTrue($usd->isSameCurrency(Money::USD(666)));
        $this->assertFalse($eur->isSameCurrency($usd));

        //greaterThan
        $this->assertTrue($usd->greaterThan(Money::USD(600)));
        $this->assertFalse(Money::USD(250)->greaterThan($usd));

        // here throws an exception
        $usd->greaterThan(Money::EUR(900));
    }

    /**
     * @expectedException HcDisat\Monetary\Exceptions\InvalidCurrencyOperationException
     */
    public function testLessThan()
    {
        $usd = new Money(800, new Currency('USD'));
        $eur = new Money(800, new Currency('EUR'));

        //lessThan
        $this->assertFalse($usd->lessThan(Money::USD(600)));
        $this->assertTrue(Money::USD(250)->lessThan($usd));

        // here throws an exception
        $usd->lessThan($eur);

    }

    public function testGraterAndLessThan()
    {
        $usd = new Money(800, new Currency('USD'));
        $eur = new Money(800, new Currency('EUR'));

        $this->assertTrue($usd->greaterThanOrEquals(Money::USD(700)));
        $this->assertTrue($usd->greaterThanOrEquals(Money::USD(800)));

        $this->assertTrue($eur->lessThanOrEqual(Money::EUR(6000)));
        $this->assertTrue($eur->lessThanOrEqual(Money::EUR(800)));
    }


    public function testPositiveNegativeAndIsZero()
    {
        $usd = new Money(800, new Currency('USD'));
        $eur = new Money(-800, new Currency('EUR'));

        $this->assertTrue($usd->isPositive());
        $this->assertTrue($eur->isNegative());

        $this->assertFalse($usd->isZero());

        $this->assertTrue(Money::ofCurrency($eur->currency())->isZero());
    }

}