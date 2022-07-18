<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Braspag\Braspag\Pagador\Transaction\Resource\Auth3Ds20\Token\Response as Auth3Ds20TokenResponse;

interface BuilderInterface
{
    public function build(Auth3Ds20TokenResponse $token);
}