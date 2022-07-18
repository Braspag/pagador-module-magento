<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Token;

use Braspag\BraspagPagador\Model\AbstractHandler;
use Braspag\Braspag\Pagador\Transaction\BraspagFacade;

class Handler extends AbstractHandler
{

    protected $apiFacade;

    protected $tokenInstance;

    public $tokenBody;

    public $tokenCreditcard;

    public function __construct(BraspagFacade $apiFacade)
    {
        $this->apiFacade = $apiFacade;
        $this->tokenInstance = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance('Token');
        $this->tokenBody = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance('Token\\Body');
        $this->tokenCreditcard = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance('Token\\Creditcard');
    }

    public function createCreditCardToken()
    {
        $this->tokenCreditcard
            ->setHolderName($this->tokenBody->getHolderName())
            ->setExpirationMonth($this->tokenBody->getExpirationMonth())
            ->setExpirationYear($this->tokenBody->getExpirationYear())
            ->setSecurityCode($this->tokenBody->getSecurityCode())
            ->setCardNumber($this->tokenBody->getCardNumber());
        try {
            return $this->apiFacade->createToken($this->tokenInstance->createCreditcardToken($this->tokenCreditcard));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}