<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;

class Config implements ConfigInterface
{
    protected $config;
    protected $session;

    const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';

    public function __construct(
        ScopeConfigInterface $config,
        SessionManagerInterface $session
    ){
        $this->setConfig($config);
        $this->setSession($session);
    }

    public function getMerchantId()
    {
        return $this->getConfig()->getValue('payment/braspag_pagador_global/merchant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getMerchantKey()
    {
        return $this->getConfig()->getValue('payment/braspag_pagador_global/merchant_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isAuthorizeAndCapture()
    {
        return (self::ACTION_AUTHORIZE_CAPTURE === $this->getConfig()->getValue('payment/braspag_pagador_creditcard/payment_action', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
    }

    public function getSoftDescriptor()
    {
        return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/soft_config', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function hasAntiFraud()
    {
        return $this->getConfig()->getValue(AntiFraudConfigInterface::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIdentityAttributeCode()
    {
        return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/customer_identity_attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return SessionManagerInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionManagerInterface $session
     */
    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return ScopeConfigInterface
     */
	public function getSilentOrderPostUri()
	{
		return 'https://homologacao.pagador.com.br/post/api/public/v1/accesstoken?merchantid=' . $this->getMerchantId();
	}

    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ScopeConfigInterface $config
     * @return $this
     */
    protected function setConfig(ScopeConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }
}
