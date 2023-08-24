<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Config;

interface ConfigInterface
{
    const CONFIG_XML_ENVIRONMENT = 'braspag_braspagpagador/global/payment_environment';
    const CONFIG_XML_AUTHORIZATION_KEY = 'braspag_braspagpagador/global/zpk_access';
    const CONFIG_XML_AUTHORIZATION_KEY_TESTE = 'braspag_braspagpagador/global/zpk_access_teste';
    const CONFIG_XML_CRON_ORDER_PROCESS = 'braspag_braspagpagador/global/cron_order_process';

    //pix
    const CONFIG_XML_PIX_IS_ACTIVE = 'payment/braspag_pagador_pix/active';
    const CONFIG_XML_PIX_PAYMENT_ACTION = 'payment/braspag_pagador_pix/sort_order/payment_action';
    const CONFIG_XML_PIX_DEMONSTRATIVE = 'payment/braspag_pagador_pix/demonstrative';
    const CONFIG_XML_PIX_IDENTIFICATION = 'payment/braspag_pagador_pix/identification';
    const CONFIG_XML_PIX_EXPIRATION_TIME = 'payment/braspag_pagador_pix/expiration_time';
    const CONFIG_XML_PIX_ORDER_STATUS = 'payment/braspag_pagador_pix/order_status';
    const CONFIG_XML_PIX_ORDER_STATUS_PROCESSING = 'payment/braspag_pagador_pix/order_status_processing';
    const CONFIG_XML_PIX_CRON_CANCEL_PENDING = 'payment/braspag_pagador_pix/cron_cancel_pending';
    const CONFIG_XML_PIX_LOGO = 'payment/braspag_pagador_pix/logo';
    const CONFIG_XML_PIX_DEADLINE = 'payment/braspag_pagador_pix/deadline';

    const DATE_FORMAT = 'Y-m-d';
    const DAY_FORMAT = '+%s day';

    public function getEnvironment();

    public function getAuthorizationKey();

    public function getAuthorizationKeyTeste();

    public function getCronProcessOrders();

    public function getPixPaymentDemonstrative();

    public function pixIsActive();

    public function getPixPaymentAction();

    public function getPixPaymentIdentification();

    public function getPixExpirationTime();

    public function getPixOrderStatus();

    public function getPixCronCancelPending();

    public function getLogo();

    public function getDeadline();
}