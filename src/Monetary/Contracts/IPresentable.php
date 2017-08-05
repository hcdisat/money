<?php  namespace HcDisat\Monetary\Contracts;

interface IPresentable
{

    /**
     * @return IPresenter
     */
    public function present(): IPresenter ;

}