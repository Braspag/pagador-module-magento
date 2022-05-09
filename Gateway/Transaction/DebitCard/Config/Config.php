<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config\ConfigInterface as AntiFraudConfigInterface;

class Config extends AbstractConfig implements ConfigInterface
{
	public function getMerchantId()
	{
		return $this->getConfig()->getValue(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getMerchantKey()
	{
		return $this->getConfig()->getValue(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getPaymentReturnUrl()
	{
        return $this->_getConfig(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_RETURN_URL);
    }

	public function isSuperDebitoActive()
	{
		return (bool) $this->getConfig()->getValue('payment/braspag_pagador_debitcard/superdebit_active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    public function getIsTestEnvironment()
    {
        return $this->_getConfig(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT);
    }

    public function getDcTypes()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_DCTYPES);
    }

    public function getRedirectAfterPlaceOrder()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_REDIRECT_AFTER_PLACE_ORDER);
    }

    public function isAuth3Ds20Active()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20);
    }

    public function isAuth3Ds20MCOnlyNotifyActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20MASTERCARD_ONLY_NOTIFY);
    }

    public function isAuth3Ds20AuthorizedOnError()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_AUTHORIZE_ON_ERROR);
    }

    public function isAuth3Ds20AuthorizedOnFailure()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_AUTHORIZE_ON_FAILURE);
    }

    public function isAuth3Ds20AuthorizeOnUnenrolled()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_AUTHORIZE_ON_UNENROLLED);
    }

    public function isAuth3Ds20AuthorizeOnUnsupportedBrand()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_AUTHORIZE_ON_UNSUPPORTED_BRAND);
    }

    public function getAuth3Ds20Mdd1()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_MDD1);
    }

    public function getAuth3Ds20Mdd2()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_MDD2);
    }

    public function getAuth3Ds20Mdd3()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_MDD3);
    }

    public function getAuth3Ds20Mdd4()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_MDD4);
    }

    public function getAuth3Ds20Mdd5()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_AUTHENTICATION_3DS_20_MDD5);
    }

    public function isPaymentSplitActive()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT);
    }

    public function getPaymentSplitType()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT_TYPE);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomatically()
    {
        return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY);
    }

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS);
    }

    public function getPaymentSplitDefaultMrd()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT_DEFAULT_MDR);
    }

    public function getPaymentSplitDefaultFee()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_DEBIT_PAYMENTSPLIT_DEFAULT_FEE);
    }

    public function isCardViewActive()
    {
        return (bool) $this->_getConfig(self::BRASPAG_PAGADOR_DEBIT_CARD_VIEW);
    }

    public function hasAntiFraud()
    {
        return $this->_getConfig(AntiFraudConfigInterface::XML_PATH_ACTIVE);
    }
}
