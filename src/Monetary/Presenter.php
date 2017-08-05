<?php namespace HcDisat\Monetary;


use HcDisat\Monetary\Contracts\IPresenter;

class Presenter implements IPresenter
{
    /**
     * @var Number
     */
    protected $number;
    /**
     * @var MoneyFormatter
     */
    private $formatter;
    /**
     * @var Money
     */
    private $amount;

    /**
     * Presenter constructor.
     * @param Money $amount 
     */
    public function __construct(Money $amount)
    {
        $this->number = app(Number::class, [$amount->amount()]);
//        $this->number = new Number($amount->amount());
        $this->formatter = app(MoneyFormatter::class);
        $this->amount = $amount;
    }


    /**
     * @inheritdoc
     */
    public function price(): string 
    {
        return $this->formatter->toCurrency($this->amount);
    }

    /**
     * @inheritdoc
     */
    public function amountForHumans(): string
    {
        return $this->formatter->toDecimal($this->amount);
    }
}