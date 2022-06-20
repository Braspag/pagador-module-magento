<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Card;

use Braspag\Braspag\Pagador\Transaction\BraspagFacade;

class Handler
{
    /**
     * @var BraspagFacade
     */
    protected $apiFacade;

    /**
     * Token constructor.
     * @param BraspagFacade $apiFacade
     */
    public function __construct(BraspagFacade $apiFacade)
    {
        $this->apiFacade = $apiFacade;
    }

    public function associate($cardToken, $buyerId)
    {
        $cardInstance = $this->apiFacade->getApi()->getMethodFactory()->fetchInstance('Card');

        try {
            return $this->apiFacade->associateBuyer($cardInstance->associateWithBuyer($cardToken, $buyerId));
        } catch (\Exception $e) {
            throw new Exception("Something bad happened!");
        }
    }
}