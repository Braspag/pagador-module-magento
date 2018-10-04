<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

/**
 * Braspag Transaction CreditCard Config Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface ConfigInterface extends \Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface
{
    const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AVS_ACTIVE = 'payment/braspag_pagador_creditcard/avs_active';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_3DS_VBV_AUTHENTICATE = 'payment/braspag_pagador_creditcard/authenticate_3ds_vbv';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENT_ACTION = 'payment/braspag_pagador_creditcard/payment_action';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SOFT_ACTION = 'payment/braspag_pagador_creditcard/soft_config';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CUSTOMER_IDENTITY_ATTRIBUTE_CODE = 'payment/braspag_pagador_creditcard/customer_identity_attribute_code';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SILENTORDERPOST_URL_PRODUCTION = 'payment/braspag_pagador_creditcard/silentorderpost_url_production';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SILENTORDERPOST_URL_HOMOLOG = 'payment/braspag_pagador_creditcard/silentorderpost_url_homolog';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_DECIMAL_GRAND_TOTAL = 'payment/braspag_pagador_creditcard/decimal_grand_total';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_SAVECARD_ACTIVE = 'payment/braspag_pagador_creditcardtoken/active';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE = 'payment/braspag_pagador_customer_address/street_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE = 'payment/braspag_pagador_customer_address/number_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/complement_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/district_attribute';
    const CONFIG_XML_BRASPAG_PAGADOR_CREATE_INVOICE_NOTIFICATION_CAPTURE = 'payment/braspag_pagador_creditcard/create_invoice_on_notification_captured';
    const DEFAULT_DECIMAL_GRAND_TOTAL = 2;

    public function isAuthorizeAndCapture();

    public function getSoftDescriptor();

    public function getIdentityAttributeCode();

    public function hasAntiFraud();

    public function hasAvs();

    public function isAuthenticate3DsVbv();

    public function getReturnUrl();

    public function isSaveCardActive();

    public function isCreateInvoiceOnNotificationCaptured();

    public function getCustomerStreetAttribute();

    public function getCustomerNumberAttribute();

    public function getCustomerComplementAttribute();

    public function getCustomerDistrictAttribute();

    public function getDecimalGrandTotal();
}
