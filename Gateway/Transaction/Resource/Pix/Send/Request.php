<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Resource\Pix\Send;

use Magento\Checkout\Model\Session;
use Webjump\BraspagPagador\Model\Buyer\Handler;
use Webjump\BraspagPagador\Model\Method\Pix\Pix;
use Webjump\BraspagPagador\Gateway\Transaction\Transaction;

class Request implements RequestInterface
{
    protected $pix;

    /**
     * @var Handler
     */
    protected $buyerHandler;

    protected $checkoutSession;

    /**
     * @var Transaction $transaction
     */
    protected $transaction;

    /**
     * Request constructor.
     * @param Pix $pix
     * @param Session $checkoutSession
     * @param Handler $buyerHandler
     * @param Transaction $transaction
     */
    public function __construct(
        Pix $pix,
        Handler $buyerHandler,
        Session $checkoutSession,
        Transaction $transaction
    ) {
        $this->pix = $pix;
        $this->buyerHandler = $buyerHandler;
        $this->checkoutSession = $checkoutSession;
        $this->transaction = $transaction;
    }

    public function prepareRequest($quote, $orderAdapter)
    {
        $this->getPix()->prepareOrder($orderAdapter->getGrandTotalAmount());
        return $this->transaction;
    }


    protected function getSessionQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    protected function getPix()
    {
        return $this->pix;
    }
}