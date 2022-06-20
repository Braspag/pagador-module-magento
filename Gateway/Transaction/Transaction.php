<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction;

use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;
use Braspag\BraspagPagador\Model\AbstractHandler;
use Braspag\Braspag\Pagador\Transaction\BraspagFacade;

class Transaction extends AbstractHandler
{
    const STATUS_TRANSACTION_NEW = 'new';

    const STATUS_TRANSACTION_PENDING = 'pending';

    const STATUS_TRANSACTION_SUCCEEDED = 'succeeded';

    const STATUS_TRANSACTION_FAILED = 'failed';

    const STATUS_TRANSACTION_CANCELED = 'canceled';

    const STATUS_TRANSACTION_REFUNDED = 'refunded';

    protected $transactionHandler;
    public $transactionBody;
    public $transactionCard;
    public $transactionInstalmentPlan;
    /**
     * @var BraspagFacade
     */
    protected $BraspagFacade;

    public function __construct(
        BraspagFacade $braspagFacade,
        LoggerInterface $logger,
        Session $checkoutSession
    ) {
        parent::__construct($logger, $checkoutSession);
        $this->braspagFacade = $braspagFacade;
        $this->transactionHandler = $this->braspagFacade->getApi()->getMethodFactory()->fetchInstance('Transaction');
        $this->transactionBody = $this->braspagFacade->getApi()->getMethodFactory()->fetchInstance('Transaction\\Body');
        $this->transactionCard = $this->braspagFacade->getApi()->getMethodFactory()->fetchInstance('Transaction\\Card');
        $this->transactionInstalmentPlan = $this->braspagFacade->getApi()->getMethodFactory()->fetchInstance('Transaction\\InstallmentPlan');
    }

    public function executeTransaction()
    {
        try {
            $result = $this->braspagFacade->createTransaction($this->transactionHandler->create($this->transactionBody));
            $this->getLogger()->debug("Braspag Received Create Transaction");
            $this->getLogger()->debug(json_encode($result));
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function executeCaptureTransaction($transactionId, $amount, $sellerId = null)
    {
        try {
            if (!$sellerId) {
                $sellerId = $this->braspagFacade->config->getSellerId();
            }
            $result = $this->braspagFacade->captureTransaction($this->transactionHandler->capture($transactionId, $sellerId, $amount));
            $this->getLogger()->debug("Braspag Captured Transaction");
            $this->getLogger()->debug(json_encode($result));
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getTransaction($transactionId)
    {
        $result = $this->braspagFacade->getTransaction($this->transactionHandler->get($transactionId));
        return $result;
    }
}