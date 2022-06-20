<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Method\Pix;

use Braspag\BraspagPagador\Model\Method\AbstractMethod;

class Pix extends AbstractMethod
{
    /**
     * @param $amount
     */
    public function prepareOrder($amount)
    {
        /**
         * @var $quote Magento\Checkout\Model\Session
         */
        $amount = $this->prepareAmount($amount);

        $quote = $this->getSessionQuote();

        $this->transaction = $this->initSale($quote->getReservedOrderId());

        $this->transaction->transactionBody
                    ->initPix($amount, $this->storeManager->getStore()->getCurrentCurrencyCode())
                    ->setOnBehalfOf($this->config->getSellerId());

        return $this->transaction;
    }
}