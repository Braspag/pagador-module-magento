<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Voucher\Command;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Braspag\Braspag\Pagador\Transaction\Api\Voucher\Send\RequestInterface;

/**
 * Braspag Transaction Voucher Order Command
 *
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */
class OrderCommand extends AbstractApiCommand
{
    protected function sendRequest($request)
    {
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Voucher Order Request Lib object should be provided');
        }

        return $this->getApi()->sendVoucher($request);
    }
}