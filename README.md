# Monetary

Domain logic to deal with currency and avoid floating point and rounding numbers.
Optimized for Laravel 5.2 and PHP 7

## Getting Started

These instructions will get you a copy of the project up and running on your
local machine for development and testing purposes.

### Installing

We need to add the private repositories to our composer.json file

```
{
  "type": "vcs",
  "url": "git@iteam.contemporaryva.com:cva/Money.git"
}
```

Then, add the actual package to the required object

```
"hcdisat/monetary": "dev-master"
```

After that, update composer.

```
composer update
```
At this point the package has been installed, lets move to configuration process.

### Configuration

First lets register the service provider. add the next line to the providers array in _**config/app.php**_
```
\HcDisat\Monetary\MonetaryServiceProvider::class,
```
Then publish the configuration file:

```
php artisan vendor:publish
```
Done. Lets move the configuration process.

## Configuration
Lets first setup the monetary package, this is very simple, just setup your default
locale and money format, note this are options for NumberFormatter class. You get more info about this class at
[php-intl](http://php.net/manual/es/class.numberformatter.php) official documentation.

At this point you should have this file **_config/currency.php_**. you can set
these entries according to your needs.

```
  'default-locale' => 'en_US',

  'formatter-format' => '#,##0.00, -#,##0.00',
```
Done.

## Using the code.

To use the code is really simple and straightforward. You just have to be aware of that the amount is represented
as its lowest monetary value, for example cents is the lowest unit, so $1.00 refers to one cent and $100.00 is
equivalent to one dollar.

The most notable quality of the money object is its immutability. It means Money class won't change if we perform
an operation with the concrete object. so, it behave as a value object.

It's hard to keep all of that in mind while developing, but don't worry, you can format the amount as you wish
to present it to the final user. Lets see few examples.

```
 $aMoney = new Money('100', $curr = new Currency('USD'));
```

Class's signature as you can see, expects a number (string|int) and a Currency object.
You can also get an instance like so:

```
$usd = Money::USD(300);
```

This syntax uses the currency as a method and the amount as argument. lets see an immutability's example:

```
$aMoney = new Money('100', $curr = new Currency('USD'));
$usd = Money::USD(100);

$aMoney->add($usd);

$this->assertEquals(200, $aMoney->amount()); // fails. Expected :200 | Actual :100

// but this works
$this->assertEquals(200, $aMoney->add($usd)->amount());
```
When you operate on a money object, this operations will always return a whole new object its behavior is similar
as how strings behave. The correct workflow should be

```
$incrementMoney = $aMoney->add($usd);
$this->assertEquals(200, $incrementMoney->amount());
```

You can perform the followings operations:
* increaseAmountBy
* equals
* isSameCurrency
* greaterThan
* lessThan
* greaterThanOrEquals
* lessThanOrEqual
* isNegative
* isPositive
* isZero
* isEqualsOrLessThanZero
* isEqualsOrMoreThanZero
* add
* subtract
* multiply
* divide