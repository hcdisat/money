<?php

use HcDisat\Monetary\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCantCreateInstanceWithInvalidCode()
    {
        new Currency('FOOBAR');
    }
}