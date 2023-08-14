<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Wallet\Command;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Braspag\Braspag\Pagador\Transaction\Api\Wallet\Send\RequestInterface;

/**
 * Braspag Transaction Wallet Send Command
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */
class SendCommand extends AbstractApiCommand
{
    protected function sendRequest($request)
    {
        if (!isset($request) || !$request instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Wallet Send Request Lib object should be provided');
        }

        return $this->getApi()->sendWallet($request);
    }
}