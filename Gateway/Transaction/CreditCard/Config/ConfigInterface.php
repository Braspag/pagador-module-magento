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
    public function getMerchantId();

    public function getMerchantKey();

    public function isAuthorizeAndCapture();

    public function getSoftDescriptor();

    public function getIdentityAttributeCode();

    public function getSession();

    public function hasAntiFraud();

    public function hasAvs();

    public function getAuthenticate3DsVbv();

    public function getReturnUrl();

    public function getSilentOrderPostUri();

}
