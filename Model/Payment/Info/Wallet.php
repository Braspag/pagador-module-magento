<?php

/**
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Wallet\Resource\Send\Response\BaseHandler as ResponseHandler;

class Wallet
{
    protected $order;

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return OrderPaymentInterface
     */
    public function getPayment()
    {
        if (! ($this->getOrder()->getPayment()) instanceof OrderPaymentInterface) {
            throw new \InvalidArgumentException();
        }

        return $this->getOrder()->getPayment();
    }

    /**
     * @return string
     */
    public function getWalletUrl()
    {
        return $this->getPayment()->getAdditionalInformation(ResponseHandler::ADDITIONAL_INFORMATION_WALLET_URL);
    }
}