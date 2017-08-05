<?php

use HcDisat\Monetary\Number;
use Tests\TestCase;

class NumberTest extends TestCase
{

    public function testDecimal()
    {
        $number = new Number('10');
        $this->assertFalse($number->isDecimal());

        $number = new Number('10.5');
        $this->assertTrue($number->isDecimal());

        $number = new Number('10.5');
        $this->assertTrue($number->isDecimal());

        $number = new Number((string) PHP_INT_MAX);
        $this->assertFalse($number->isDecimal());
    }


    public function testFromFloat()
    {
        $number = Number::fromFloat(79.10);
        $this->assertEquals('79.10', (string) $number);
    }

    public function testNoConstructor()
    {
        $number = new Number();
        $this->assertEquals('0', (string)$number);
    }

    public function testToCents()
    {
        $number = new Number('10.85');
        $this->assertEquals('1085', (string)$number->toCents());

        $number = new Number('1085');
        $this->assertEquals('10.85', (string)$number->toUnits());

        $number = new Number('20');
        $this->assertEquals('20', (string)$number->toCents());

        $number = new Number('1685.0');
        $this->assertEquals('1685', (string)$number->toCents());

        $number = new Number('16.85');
        $this->assertEquals('1685.0', (string)$number->toCents());
    }

    public function testNumberCalculationCapabilities()
    {
        /** @var Number $number */
        $number = new Number('16.85');
        $this->assertEquals('1685.0', $number->toCents());
    }
}