<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Pix\Command;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Command\AbstractApiCommand;
use Braspag\Braspag\Pagador\Transaction\Api\Pix\Send\RequestInterface;

/**
 * Braspag Transaction Pix Send Command
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
            throw new \InvalidArgumentException('Braspag Pix Send Request Lib object should be provided');
        }

        return $this->getApi()->sendPix($request);
    }
}