<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config implements ConfigInterface
{
    protected $config;
    protected $session;
    protected $storeManager;

    const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';
    const XML_CONFIG_AVS_ACTIVE = 'payment/braspag_pagador_creditcard/avs_active';
    const XML_CONFIG_3DS_VBV_AUTHENTICATE = 'payment/braspag_pagador_creditcard/authenticate_3ds_vbv';
    const XML_CONFIG_RETURN_URL = 'payment/braspag_pagador_config/return_url';

    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager
    ){
        $this->setConfig($config);
        $this->setStoreManager($storeManager);
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

    public function hasAvs()
    {
        return $this->getConfig()->getValue(static::XML_CONFIG_AVS_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getAuthenticate3DsVbv()
    {
        return $this->getConfig()->getValue(static::XML_CONFIG_3DS_VBV_AUTHENTICATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getReturnUrl()
    {
        return $this->getStoreManager()->getStore()->getUrl(
            $this->getConfig()->getValue(static::XML_CONFIG_RETURN_URL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
        );
    }

    public function getIdentityAttributeCode()
    {
        return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/customer_identity_attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

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

    /**
     * @return StoreManagerInterface
     */
    protected function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @param StoreManagerInterface $storeManager
     * @return $this
     */
    protected function setStoreManager(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }
}
