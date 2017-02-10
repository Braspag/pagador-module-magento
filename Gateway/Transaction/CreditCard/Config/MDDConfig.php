<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;


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
        return $this->_getConfig('customer/create_account/confirm_inherit');
    }

    public function getFetchSelfShippingMethod()
    {
        return $this->_getConfig(self::XML_PATH_FETCH_SELF_SHIPPING_METHOD);
    }

    public function getVerticalSegment()
    {
        return $this->_getConfig(self::XML_PATH_VERTICAL_SEGMENT);
    }
}
