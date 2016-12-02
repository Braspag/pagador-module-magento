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
    public function getMerchantId();

	public function getMerchantKey();

	public function getPaymentReturnUrl();
}