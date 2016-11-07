<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfigInterface;

interface InstallmentFactoryInterface
{
    public function create($installmentId, $grandTotal, InstallmentsConfigInterface $installmentsConfig);
}
