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
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_CCTYPES = 'payment/braspag_pagador_creditcard/cctypes';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AVS_ACTIVE = 'payment/braspag_pagador_creditcard/avs_active';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT = 'payment/braspag_pagador_creditcard/paymentsplit';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TYPE = 'payment/braspag_pagador_creditcard/paymentsplit_type';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY = 'payment/braspag_pagador_creditcard/paymentsplit_transactional_post_send_request_automatically';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS = 'payment/braspag_pagador_creditcard/paymentsplit_transactional_post_send_request_automatically_after_x_hours';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_DEFAULT_MDR = 'payment/braspag_pagador_creditcard/paymentsplit_mdr';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_PAYMENTSPLIT_DEFAULT_FEE = 'payment/braspag_pagador_creditcard/paymentsplit_fee';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20 = 'payment/braspag_pagador_creditcard/authentication_3ds_20';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MASTERCARD_ONLY_NOTIFY = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mastercard_notify_only';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_ERROR = 'payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_error';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_FAILURE = 'payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_failure';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_UNENROLLED = 'payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_unenrolled';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20AUTHORIZE_ON_UNSUPPORTED_BRAND = 'payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_unsupported_brand';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD1 = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mdd1';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD2 = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mdd2';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD3 = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mdd3';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD4 = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mdd4';
    const CONFIG_XML_BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20MDD5 = 'payment/braspag_pagador_creditcard/authentication_3ds_20_mdd5';
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
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_SUCCESS = 0;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE = 1;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED = 2;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED = 3;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR = 4;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND = 5;
    const BRASPAG_PAGADOR_CREDITCARD_CARD_VIEW = 'payment/braspag_pagador_creditcard/card_view';

    public function isAuthorizeAndCapture();

    public function getSoftDescriptor();

    public function getIdentityAttributeCode();

    public function hasAntiFraud();

    public function hasAvs();

    public function getReturnUrl();

    public function isSaveCardActive();

    public function isCreateInvoiceOnNotificationCaptured();

    public function getCustomerStreetAttribute();

    public function getCustomerNumberAttribute();

    public function getCustomerComplementAttribute();

    public function getCustomerDistrictAttribute();

    public function getDecimalGrandTotal();

    public function isAuth3Ds20Active();

    public function isAuth3Ds20MCOnlyNotifyActive();

    public function isAuth3Ds20AuthorizedOnError();

    public function isAuth3Ds20AuthorizedOnFailure();

    public function isAuth3Ds20AuthorizeOnUnenrolled();

    public function isAuth3Ds20AuthorizeOnUnsupportedBrand();

    public function getAuth3Ds20Mdd1();

    public function getAuth3Ds20Mdd2();

    public function getAuth3Ds20Mdd3();

    public function getAuth3Ds20Mdd4();

    public function getAuth3Ds20Mdd5();

    public function isPaymentSplitActive();

    public function getPaymentSplitType();

    public function getPaymentSplitTransactionalPostSendRequestAutomatically();

    public function getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours();

    public function getPaymentSplitDefaultMrd();

    public function getPaymentSplitDefaultFee();

    public function isCardViewActive();
}
