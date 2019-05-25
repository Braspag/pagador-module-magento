<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth\Resource\Token;

use Webjump\Braspag\Pagador\Transaction\Resource\Auth\Token\Response as AuthTokenResponse;

interface BuilderInterface
{
    public function build(AuthTokenResponse $token);
}
