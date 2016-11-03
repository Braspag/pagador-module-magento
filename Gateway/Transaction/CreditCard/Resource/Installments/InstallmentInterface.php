<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments;

interface InstallmentInterface
{
    public function getId();

    public function getLabel();

    public function setId($installmentId);

    public function setLabel($label);
}
