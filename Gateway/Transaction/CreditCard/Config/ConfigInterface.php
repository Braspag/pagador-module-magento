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
interface ConfigInterface
{
    const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';
    const XML_CONFIG_AVS_ACTIVE = 'payment/braspag_pagador_creditcard/avs_active';
    const XML_CONFIG_3DS_VBV_AUTHENTICATE = 'payment/braspag_pagador_creditcard/authenticate_3ds_vbv';
    const XML_CONFIG_RETURN_URL = 'payment/braspag_pagador_config/return_url';
    const XML_CONFIG_PAYMENT_ACTION = 'payment/braspag_pagador_creditcard/payment_action';
    const XML_CONFIG_SOFT_ACTION = 'payment/braspag_pagador_creditcard/soft_config';
    const XML_CONFIG_CUSTOMER_IDENTITY_ATTRIBUTE_CODE = 'payment/braspag_pagador_creditcard/customer_identity_attribute_code';

    public function getMerchantId();

    public function getMerchantKey();

    public function isAuthorizeAndCapture();

    public function getSoftDescriptor();

    public function getIdentityAttributeCode();

    public function hasAntiFraud();

    public function hasAvs();

    public function isAuthenticate3DsVbv();

    public function getReturnUrl();

    public function getSilentOrderPostUri();
}
