<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\OAuth2\Resource\Token;

use Webjump\Braspag\Pagador\Transaction\Resource\OAuth2\Token\Response as OAuth2TokenResponse;

interface BuilderInterface
{
    public function build(OAuth2TokenResponse $token);
}
