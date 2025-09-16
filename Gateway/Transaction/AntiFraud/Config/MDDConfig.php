<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\AntiFraud\Config;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

class MDDConfig extends AbstractConfig implements MDDConfigInterface
{
    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface|\Magento\Framework\Api\ExtensibleDataInterface
     */
    public function getCustomer()
    {
        /** @var \Magento\Checkout\Model\Session $session */
        $session = $this->getSession();
        $customer = $session->getQuote()->getCustomer();

        return $customer;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        /** @var \Magento\Checkout\Model\Session $session */
        $session = $this->getSession();
        $quote = $session->getQuote();

        return $quote;
    }

    public function getConfirmEmailAddress()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOMER_CREATE_NEED_CONFIRM);
    }

    public function getFetchSelfShippingMethod()
    {
        return $this->_getConfig(self::XML_PATH_FETCH_SELF_SHIPPING_METHOD);
    }

    public function getVerticalSegment()
    {
        return $this->_getConfig(self::XML_PATH_VERTICAL_SEGMENT);
    }

    public function getStoreCode()
    {
        return $this->_getConfig(self::XML_PATH_STORE_CODE);
    }

    public function getStoreIdentity()
    {
        if ($this->_getConfig(self::XML_PATH_STORE_IDENTITY))
             return (int) preg_replace('/[^0-9]/', '', $this->_getConfig(self::XML_PATH_STORE_IDENTITY));

        return null;     
    }

    public function getCategoryAttributeCode()
    {
        return $this->_getConfig(self::XML_PATH_CATEGORY_ATTRIBUTE_CODE);
    }

    public function getAFType()
    {
        return $this->_getConfig(self::XML_PATH_AF_TYPE);
    }

    public function hasCustomMDD()
    {
        return $this->_getConfig(self::XML_PATH_HAS_CUSTOM_MMD);
    }

    public function getCustomMDD85()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOM_MMD_85);
    }

    public function getCustomMDD86()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOM_MMD_86);
    }

    public function getCustomMDD87()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOM_MMD_87);
    }

    public function getCustomMDD88()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOM_MMD_88);
    }

    public function getCustomMDD89()
    {
        return $this->_getConfig(self::XML_PATH_CUSTOM_MMD_89);
    }
}