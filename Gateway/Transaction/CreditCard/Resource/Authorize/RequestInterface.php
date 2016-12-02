<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;

/**
 * Braspag Transaction CreditCard Authorize Request Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface RequestInterface extends BaseRequestInterface
{
    public function setAntiFraudRequest(RequestAntiFraudLibInterface $requestAntiFraud);

    public function setAvsRequest(RequestAvsLibInterface $requestAntiFraud);
}
