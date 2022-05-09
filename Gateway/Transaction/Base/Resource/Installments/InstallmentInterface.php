<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

interface InstallmentInterface
{
    public function getId();

    public function getLabel();

    public function setIndex($index);

    public function setPrice($price);

    public function setWithInterest($isWithInterest);

}
