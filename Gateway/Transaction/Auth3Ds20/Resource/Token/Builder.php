<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token;

use \Magento\Framework\DataObject;
use Webjump\Braspag\Pagador\Transaction\Resource\Auth3Ds20\Token\Response as Auth3Ds20TokenResponse;

/**
 * Class Builder
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token
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
    public function build(Auth3Ds20TokenResponse $token)
    {
        $tokenObject = $this->dataObject;
        $tokenObject->setToken($token->getToken());
        $tokenObject->setExpiresIn($token->getExpiresIn());

        return $tokenObject;
    }
}
