<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Transaction;

class SendCommand extends AbstractCommand
{
    /**
     * @param Transaction $transaction
     * @return array
     */
    protected function sendRequest($transaction)
    {
        return $transaction->executeTransaction();
    }

    public function getRequestValidator()
    {
        return null;
    }

    public function getResponseValidator()
    {
        return null;
    }
}