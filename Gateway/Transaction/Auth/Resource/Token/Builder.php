<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth\Resource\Token;

use \Magento\Framework\DataObject;
use Webjump\Braspag\Pagador\Transaction\Resource\Auth\Token\Response as AuthTokenResponse;

/**
 * Class Builder
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth\Resource\Token
 */
class Builder implements BuilderInterface
{
    protected $dataObject;

    public function __construct(
        DataObject $dataObject
    ) {
        $this->dataObject = $dataObject;
    }

    /**
     * @return DataObject
     */
    public function build(AuthTokenResponse $token)
    {
        $tokenObject = $this->dataObject;
        $tokenObject->setToken($token->getAccessToken());
        $tokenObject->setExpiresIn($token->getExpiresIn());

        return $tokenObject;
    }
}
