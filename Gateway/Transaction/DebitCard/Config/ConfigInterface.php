<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_PAGADOR_DEBIT_REDIRECT_AFTER_PLACE_ORDER = 'payment/braspag_pagador_debitcard/redirect_after_place_order';

    public function getMerchantId();

	public function getMerchantKey();

	public function getPaymentReturnUrl();

	public function isSuperDebitoActive();

	public function getIsTestEnvironment();

    public function getRedirectAfterPlaceOrder();
}