<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;

interface InstallmentFactoryInterface
{
    public function create($installmentId, $grandTotal, InstallmentsConfigInterface $installmentsConfig);
}
