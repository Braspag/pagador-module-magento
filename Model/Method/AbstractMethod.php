<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Method;

use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Braspag\BraspagPagador\Model\Buyer\Handler as buyerHandler;
use Braspag\BraspagPagador\Model\Card\Handler as cardHandler;
use Braspag\BraspagPagador\Model\Config\Config;
use Braspag\BraspagPagador\Model\Token\Handler as tokenHandler;
use Braspag\BraspagPagador\Gateway\Transaction\Transaction;

abstract class AbstractMethod
{

    protected $buyerHandler;

    protected $cardHandler;

    protected $storeManager;

    protected $checkoutSession;

    protected $config;

    protected $tokenHandler;

    protected $transaction;

    public function __construct(
        Session $checkoutSession,
        Config $config,
        buyerHandler $buyerHandler,
        cardHandler $cardHandler,
        StoreManagerInterface $storeManager,
        tokenHandler $tokenHandler,
        Transaction $transaction
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->buyerHandler = $buyerHandler;
        $this->cardHandler = $cardHandler;
        $this->storeManager = $storeManager;
        $this->transaction = $transaction;
        $this->tokenHandler = $tokenHandler;
    }

    protected function initSale($id)
    {
        $body = $this->transaction->transactionBody;
        $body->setReferenceId($id);
        $body->setMetadata($this->prepareMetadata());
        return $this->transaction;
    }

    protected function getSessionQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * @param $amount
     * @return int
     */
    protected function prepareAmount($amount)
    {
        return (int) ($amount * 100);
    }

    public function prepareMetadata()
    {
        $platform = new \stdClass();
        $platform->platform_integration = "Braspag";
        $platform->store = $this->storeManager->getStore()->getName();
        return $platform;
    }
}