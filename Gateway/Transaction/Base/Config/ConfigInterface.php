<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

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
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID     = 'payment/braspag_pagador_global/merchant_id';
	const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY    = 'payment/braspag_pagador_global/merchant_key';

	const DATE_FORMAT = 'Y-m-d';

    public function getMerchantId();

    public function getMerchantKey();
}