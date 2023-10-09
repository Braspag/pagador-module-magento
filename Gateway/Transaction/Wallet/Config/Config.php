<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Wallet\Config;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Braspag Transaction Wallet Config
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */
class Config extends BaseConfig implements ConfigInterface
{

    public function getPaymentDemonstrative()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_DEMONSTRATIVE);
    }

    public function getPaymentInstructions()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_INSTRUCTIONS);
    }

    public function getPaymentIdentification()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_IDENTIFICATION);
    }

    public function getPaymentAssignor()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_ASSIGNOR);
    }

    public function getPaymentAssignorAddress()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_ASSIGNOR_ADDRESS);
    }

    public function getExpirationDate()
    {
        // return $this->getDateTime()->gmDate(self::DATE_FORMAT, strtotime(sprintf(self::DAY_FORMAT, (int) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_EXPIRATION_DATE))));
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_EXPIRATION_DATE);
    }

    public function getPaymentProvider()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PROVIDER);
    }

    public function getPaymentBank()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_BANK);
    }

    public function getCustomerStreetAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE);
    }

    public function getCustomerNumberAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE);
    }

    public function getCustomerComplementAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE);
    }

    public function getCustomerDistrictAttribute()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE);
    }

    public function isPaymentSplitActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT);
    }

    public function getPaymentSplitType()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT_TYPE);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomatically()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS);
    }

    public function getPaymentSplitDefaultMrd()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT_DEFAULT_MDR);
    }

    public function getPaymentSplitDefaultFee()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_WALLET_PAYMENTSPLIT_DEFAULT_FEE);
    }
}