<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

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

    public function isAuthorizeAndCapture()
    {
        return (static::ACTION_AUTHORIZE_CAPTURE === $this->getConfig()->getValue(static::XML_CONFIG_PAYMENT_ACTION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
    }

    public function getSoftDescriptor()
    {
        return $this->getConfig()->getValue(static::XML_CONFIG_SOFT_ACTION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function hasAntiFraud()
    {
        return $this->getConfig()->getValue(AntiFraudConfigInterface::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function hasAvs()
    {
        return $this->getConfig()->getValue(static::XML_CONFIG_AVS_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isAuthenticate3DsVbv()
    {
        return (bool) $this->getConfig()->getValue(static::XML_CONFIG_3DS_VBV_AUTHENTICATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getReturnUrl()
    {
        $url = (string) str_replace('index.php/', '', $this->getStoreManager()->getStore()->getUrl($this->getConfig()->getValue(static::XML_CONFIG_RETURN_URL)));
        return substr($url, 0, -1);
    }

    public function getIdentityAttributeCode()
    {
        return $this->getConfig()->getValue(static::XML_CONFIG_CUSTOMER_IDENTITY_ATTRIBUTE_CODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

	public function getSilentOrderPostUri()
	{
		return 'https://homologacao.pagador.com.br/post/api/public/v1/accesstoken?merchantid=' . $this->getMerchantId();
	}

	public function getSession()
    {
        return parent::getSession();
    }
}
