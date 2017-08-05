<?php

use HcDisat\Monetary\Money;
use HcDisat\Monetary\Presenter;
use Tests\TestCase;

class PresenterTest extends TestCase
{
    // tests
    public function testPrice()
    {
        $money = Money::USD(166663);
        $presenter = new Presenter($money);
        $this->assertEquals('$1,666.63', $presenter->price());

        $money = Money::USD(658096);
        $presenter = new Presenter($money);
        $this->assertEquals('$6,580.96', $presenter->price());
    }

    public function testAmountForHumans()
    {
        $money = Money::USD(166663);
        $presenter = new Presenter($money);
        $this->assertEquals('1666.63', $presenter->amountForHumans());

        $money = Money::USD(658096);
        $presenter = new Presenter($money);
        $this->assertEquals('6580.96', $presenter->amountForHumans());
    }
}