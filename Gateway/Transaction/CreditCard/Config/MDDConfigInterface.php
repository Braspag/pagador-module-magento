<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;


interface MDDConfigInterface
{
    const XML_PATH_FETCH_SELF_SHIPPING_METHOD = 'webjump_braspag_antifraud/mdd/fetch_self_shipping_method';
    const XML_PATH_VERTICAL_SEGMENT = 'webjump_braspag_antifraud/mdd/vertical_segment';

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface|\Magento\Framework\Api\ExtensibleDataInterface
     */
    public function getCustomer();

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote();

    public function getConfirmEmailAddress();

    public function getFetchSelfShippingMethod();

    public function getVerticalSegment();
}
