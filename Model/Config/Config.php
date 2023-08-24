<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Config;

use Magento\Payment\Model\MethodInterface;

class Config extends AbstractConfig implements ConfigInterface
{
    public function getAuthorizationKey()
    {
        if ($this->getEnvironment() == 'SandBox') {
            return $this->getAuthorizationKeyTeste();
        }
        return $this->_getConfig(self::CONFIG_XML_AUTHORIZATION_KEY);
    }

    public function getCronProcessOrders()
    {
        return $this->_getConfig(self::CONFIG_XML_CRON_ORDER_PROCESS);
    }

    public function getPixPaymentDemonstrative()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_DEMONSTRATIVE);
    }

    public function pixIsActive()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_IS_ACTIVE);
    }

    public function getPixPaymentAction()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_PAYMENT_ACTION);
    }

    public function getPixPaymentIdentification()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_IDENTIFICATION);
    }

    public function getPixExpirationTime()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_EXPIRATION_TIME);
    }

    public function getPixOrderStatus()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_ORDER_STATUS);
    }

    public function getPixCronCancelPending()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_CRON_CANCEL_PENDING);
    }

    public function getEnvironment()
    {
        return $this->_getConfig(self::CONFIG_XML_ENVIRONMENT);
    }

    public function getAuthorizationKeyTeste()
    {
        return $this->_getConfig(self::CONFIG_XML_AUTHORIZATION_KEY_TESTE);
    }

    public function getLogo()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_LOGO);
    }

    public function getDeadline()
    {
        return $this->_getConfig(self::CONFIG_XML_PIX_DEADLINE);
    }
}