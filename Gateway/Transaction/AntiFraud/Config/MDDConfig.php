<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

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
        return (int) preg_replace('/[^0-9]/','', $this->_getConfig(self::XML_PATH_STORE_IDENTITY));

    }

    public function getCategoryAttributeCode()
    {
        return $this->_getConfig(self::XML_PATH_CATEGORY_ATTRIBUTE_CODE);
    }
}
