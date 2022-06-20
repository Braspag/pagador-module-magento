<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model;

use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;

abstract class AbstractHandler
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Session
     */
    protected $checkoutSession;

    public function __construct(
        LoggerInterface $logger,
        Session $checkoutSession
    ) {
        $this->setLogger($logger);
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return AbstractHandler
     */
    public function setLogger(LoggerInterface $logger): AbstractHandler
    {
        $this->logger = $logger;
        return $this;
    }

    protected function getSessionQuote()
    {
        return $this->checkoutSession->getQuote();
    }
}
