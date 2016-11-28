<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface as BaseRequestInterface;

interface RequestAntiFraudInterface
{
    public function setAntiFraudRequest(BaseRequestInterface $requestInterface);
}
