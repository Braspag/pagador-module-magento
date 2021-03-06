<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;
use Braspag\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Braspag\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as RequestAvsLibInterface;
use Braspag\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;

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

    public function setPaymentSplitRequest(RequestPaymentSplitLibInterface $requestAntiFraud);
}
