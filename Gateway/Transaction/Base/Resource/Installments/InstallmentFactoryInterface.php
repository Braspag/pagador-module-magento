<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;

interface InstallmentFactoryInterface
{
    public function create($installmentId, $grandTotal, InstallmentsConfigInterface $installmentsConfig);
}