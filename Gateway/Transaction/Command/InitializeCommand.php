<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Command;

use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;
use Braspag\Braspag\Pagador\Transaction\FacadeInterface;

class InitializeCommand implements CommandInterface
{
    protected $apiFacade;

    public function __construct(FacadeInterface $apiFacade)
    {
        $this->apiFacade = $apiFacade;
    }

    public function execute(array $commandSubject)
    {
        /** @var \Magento\Framework\DataObject $stateObject */
        $stateObject = $commandSubject['stateObject'];

        $paymentDataObject = SubjectReader::readPayment($commandSubject);

        $payment = $paymentDataObject->getPayment();
        if (!$payment instanceof Payment) {
            throw new \LogicException('Order Payment should be provided');
        }

        $baseTotalDue = $payment->getOrder()->getBaseTotalDue();
        $totalDue = $payment->getOrder()->getTotalDue();

        $payment->authorize(true, $baseTotalDue);

        $payment->setAmountAuthorized($totalDue);

        $payment->setBaseAmountAuthorized($payment->getOrder()->getBaseTotalDue());
        $stateObject->setData(OrderInterface::STATE, Order::STATE_NEW);

        $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('order_status'));
    }
}