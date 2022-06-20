<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
// use Braspag\BraspagPagador\Model\Context\ContextInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

abstract class AbstractConfig
{
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $context;
    /**
     * @var State
     */
    protected $appState;

    /**
     * @var ContextInterface
     */
    protected $contextAdmin;

    protected $scopeConfig;

    public function __construct(
        ContextInterface $context,
        array $data = []
    ) {
        $this->setContext($context);
        $this->_construct($data);
    }
    protected function _construct(array $data = [])
    {
    }

    protected function _getConfig($uri)
    {
        return $this->getConfig()->getValue($uri, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function getContext(): ContextInterface
    {
        return $this->context;
    }

    protected function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    protected function getConfig(): ScopeConfigInterface
    {
        return $this->getContext()->getConfig();
    }
}