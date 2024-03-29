<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Voucher\Config;

/**
 *
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_REDIRECT_AFTER_PLACE_ORDER = 'payment/braspag_pagador_voucher/redirect_after_place_order';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_DCTYPES = 'payment/braspag_pagador_voucher/cctypes';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20 = 'payment/braspag_pagador_voucher/authentication_3ds_20';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20MASTERCARD_ONLY_NOTIFY = 'payment/braspag_pagador_voucher/authentication_3ds_20_mastercard_notify_only';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_AUTHORIZE_ON_ERROR = 'payment/braspag_pagador_voucher/authentication_3ds_20_authorize_on_error';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_AUTHORIZE_ON_FAILURE = 'payment/braspag_pagador_voucher/authentication_3ds_20_authorize_on_failure';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_AUTHORIZE_ON_UNENROLLED = 'payment/braspag_pagador_voucher/authentication_3ds_20_authorize_on_unenrolled';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_AUTHORIZE_ON_UNSUPPORTED_BRAND = 'payment/braspag_pagador_voucher/authentication_3ds_20_authorize_on_unsupported_brand';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_MDD1 = 'payment/braspag_pagador_voucher/authentication_3ds_20_mdd1';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_MDD2 = 'payment/braspag_pagador_voucher/authentication_3ds_20_mdd2';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_MDD3 = 'payment/braspag_pagador_voucher/authentication_3ds_20_mdd3';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_MDD4 = 'payment/braspag_pagador_voucher/authentication_3ds_20_mdd4';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_MDD5 = 'payment/braspag_pagador_voucher/authentication_3ds_20_mdd5';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT = 'payment/braspag_pagador_voucher/paymentsplit';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT_TYPE = 'payment/braspag_pagador_voucher/paymentsplit_type';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY = 'payment/braspag_pagador_voucher/paymentsplit_transactional_post_send_request_automatically';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT_TRANSACTIONAL_POST_SEND_REQUEST_AUTOMATICALLY_AFTER_X_DAYS = 'payment/braspag_pagador_voucher/paymentsplit_transactional_post_send_request_automatically_after_x_hours';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT_DEFAULT_MDR = 'payment/braspag_pagador_voucher/paymentsplit_mdr';
    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_PAYMENTSPLIT_DEFAULT_FEE = 'payment/braspag_pagador_voucher/paymentsplit_fee';
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_SUCCESS = 0;
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE = 1;
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED = 2;
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED = 3;
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR = 4;
    const BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND = 5;
    const BRASPAG_PAGADOR_VOUCHER_CARD_VIEW = 'payment/braspag_pagador_voucher/card_view';

    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_STREET_ATTRIBUTE = 'payment/braspag_pagador_customer_address/street_attribute';

    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_NUMBER_ATTRIBUTE = 'payment/braspag_pagador_customer_address/number_attribute';

    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_COMPLEMENT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/complement_attribute';

    const CONFIG_XML_BRASPAG_PAGADOR_CUSTOMER_ADDRESS_DISTRICT_ATTRIBUTE = 'payment/braspag_pagador_customer_address/district_attribute';

    const CONFIG_XML_BRASPAG_PAGADOR_VOUCHER_VCTYPES = 'payment/braspag_pagador_voucher/cctypes';


    public function getMerchantId();

    public function getMerchantKey();

    public function getPaymentReturnUrl();

    public function getIsTestEnvironment();

    public function getRedirectAfterPlaceOrder();

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

    public function getCustomerStreetAttribute();

    public function getCustomerComplementAttribute();

    public function getCustomerDistrictAttribute();
}