<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Magento\Framework\Pricing\Helper\Data;

class Installment implements InstallmentInterface
{
    protected $index;

    protected $price;

    protected $interest;

    protected $priceHelper;

    public function __construct(
        Data $priceHelper
    ) {
        $this->priceHelper = $priceHelper;
    }

    public function getId()
    {
        return $this->index;
    }

    public function getLabel()
    {
        $interest = __('without interest');

        if ($this->interest) {
            $interest = __('with interest*');
        }

        return "{$this->index}x {$this->price} {$interest}";
    }

    public function setIndex($index)
    {
        $this->index = (int) $index;
    }

    public function setPrice($price)
    {
        $this->price = $this->getPriceHelper()->currency($price, true, false);
    }

    public function setWithInterest($isWithInterest)
    {
        $this->interest = (bool) $isWithInterest; 
    }

    protected function getPriceHelper()
    {
        return $this->priceHelper;
    }

    protected function setPriceHelper(Data $priceHelper)
    {
        $this->priceHelper = $priceHelper;

        return $this;
    }
}
