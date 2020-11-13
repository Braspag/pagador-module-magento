<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config;


interface MDDConfigInterface
{
    const XML_PATH_FETCH_SELF_SHIPPING_METHOD = 'webjump_braspag_antifraud/mdd/fetch_self_shipping_method';
    const XML_PATH_STORE_CODE = 'webjump_braspag_antifraud/mdd/store_code_to_fetch_self';
    const XML_PATH_VERTICAL_SEGMENT = 'webjump_braspag_antifraud/mdd/vertical_segment';
    const XML_PATH_STORE_IDENTITY = 'webjump_braspag_antifraud/mdd/store_identity';
    const XML_PATH_CATEGORY_ATTRIBUTE_CODE = 'webjump_braspag_antifraud/mdd/category_attribute_code';
    const XML_PATH_CUSTOMER_CREATE_NEED_CONFIRM = 'customer/create_account/confirm_inherit';

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

    public function getStoreCode();

    public function getStoreIdentity();

    public function getCategoryAttributeCode();
}
